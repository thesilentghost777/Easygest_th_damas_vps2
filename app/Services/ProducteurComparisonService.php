<?php

namespace App\Services;

use App\Models\User;
use App\Models\Utilisation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\ProductionStatsService;

class ProducteurComparisonService
{
    private $productionStatsService;

    public function __construct(ProductionStatsService $productionStatsService)
    {
        $this->productionStatsService = $productionStatsService;
    }

    public function compareProducteurs(string $critere, string $periode, ?string $dateDebut = null, ?string $dateFin = null): Collection
    {
        $producteurs = User::whereIn('role', ['patissier', 'boulanger'])->get();

        $comparaisons = collect();

        foreach ($producteurs as $producteur) {
            $stats = $this->getProducteurStats($producteur->id, $periode, $dateDebut, $dateFin);
            $comparaisons->push([
                'id' => $producteur->id,
                'nom' => $producteur->name,
                'secteur' => $producteur->secteur,
                'stats' => $stats
            ]);
        }

        return $this->trierParCritere($comparaisons, $critere);
    }

    private function getProducteurStats(int $producteurId, string $periode, ?string $dateDebut, ?string $dateFin): array
    {
        $query = Utilisation::with(['produitFixe', 'matiere'])
            ->where('producteur', $producteurId);

        // Appliquer le filtre de période
        switch ($periode) {
            case 'jour':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'semaine':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'mois':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'personnalise':
                if ($dateDebut && $dateFin) {
                    $query->whereBetween('created_at', [$dateDebut, $dateFin]);
                }
                break;
        }

        $productions = $query->get();

        // Regrouper par lot pour éviter de compter plusieurs fois le même produit
        $productionsParLot = $productions->groupBy('id_lot');
        
        // Calculer les statistiques en tenant compte des lots
        $quantite_totale = $this->calculerQuantiteTotaleParLot($productionsParLot);
        $cout_total = $this->calculerCoutTotal($productions);
        $revenu_total = $this->calculerRevenuTotalParLot($productionsParLot);
        $benefice = $revenu_total - $cout_total;
        $nombre_produits = $productions->groupBy('produit')->count();
        $efficacite = $cout_total > 0 ? ($revenu_total / $cout_total) : 0;

        return [
            'quantite_totale' => $quantite_totale,
            'cout_total' => $cout_total,
            'revenu_total' => $revenu_total,
            'benefice' => $benefice,
            'nombre_produits' => $nombre_produits,
            'efficacite' => $efficacite,
            'moyenne_journaliere' => $this->calculerMoyenneJournaliere($productionsParLot)
        ];
    }

    private function calculerQuantiteTotaleParLot(Collection $productionsParLot): float
    {
        return $productionsParLot->sum(function ($productionsLot) {
            // Pour chaque lot, prendre la quantité de produit une seule fois
            // (toutes les entrées du même lot ont la même quantité de produit)
            return $productionsLot->first()->quantite_produit;
        });
    }

    private function calculerCoutTotal(Collection $productions): float
    {
        return $productions->sum(function ($production) {
            return $production->quantite_matiere * $production->matiere->prix_par_unite_minimale;
        });
    }

    private function calculerRevenuTotalParLot(Collection $productionsParLot): float
    {
        return $productionsParLot->sum(function ($productionsLot) {
            // Pour chaque lot, calculer le revenu une seule fois
            $premiereProduction = $productionsLot->first();
            return $premiereProduction->quantite_produit * $premiereProduction->produitFixe->prix;
        });
    }

    private function calculerMoyenneJournaliere(Collection $productionsParLot): float
    {
        if ($productionsParLot->isEmpty()) return 0;

        // Regrouper les lots par date
        $joursProduction = collect();
        foreach ($productionsParLot as $idLot => $productionsLot) {
            $date = $productionsLot->first()->created_at->format('Y-m-d');
            if (!$joursProduction->has($date)) {
                $joursProduction[$date] = 0;
            }
            $joursProduction[$date] += $productionsLot->first()->quantite_produit;
        }

        $nombreJours = $joursProduction->count();
        $totalQuantite = $joursProduction->sum();

        return $nombreJours > 0 ? $totalQuantite / $nombreJours : 0;
    }

    private function trierParCritere(Collection $comparaisons, string $critere): Collection
    {
        return $comparaisons->sortByDesc(function ($item) use ($critere) {
            switch ($critere) {
                case 'quantite':
                    return $item['stats']['quantite_totale'];
                case 'benefice':
                    return $item['stats']['benefice'];
                case 'efficacite':
                    return $item['stats']['efficacite'];
                case 'diversite':
                    return $item['stats']['nombre_produits'];
                default:
                    return $item['stats']['benefice'];
            }
        })->values();
    }
}