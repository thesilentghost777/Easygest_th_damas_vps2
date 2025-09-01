<?php

namespace App\Services;

use App\Models\Utilisation;
use App\Models\Produit_fixes;
use App\Models\Daily_assignments;
use App\Models\ProductionSuggererParJour;
use App\Models\MatiereRecommander;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductionService
{
    public function getTodayProductions(int $employeId): Collection
    {
        $utilisations = $this->getTodayUtilisationsWithDetails($employeId);
        return $this->buildProductionsFromUtilisations($utilisations);
    }

    public function getExpectedProductions(int $employeId): Collection
    {
        $assignments = Daily_assignments::where('producteur', $employeId)
            ->whereDate('assignment_date', now())
            ->get();
        return $this->buildExpectedProductionsCollection($assignments, $employeId);
    }

    public function getRecommendedProductions(): Collection
    {
        $jour_actuel = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $suggestions = ProductionSuggererParJour::where('day', $jour_actuel)->get();
        return $this->buildRecommendedProductionsCollection($suggestions);
    }

    /**
     * Récupère toutes les utilisations du jour avec les détails des produits et matières
     */
    private function getTodayUtilisationsWithDetails(int $employeId): Collection
    {
        return DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->select(
                'Utilisation.id_lot',
                'Utilisation.produit as code_produit',
                'Produit_fixes.nom as nom_produit',
                'Produit_fixes.prix as prix_produit',
                'Utilisation.quantite_produit',
                'Matiere.nom as nom_matiere',
                'Matiere.prix_par_unite_minimale',
                'Utilisation.quantite_matiere',
                'Utilisation.unite_matiere',
                'Utilisation.created_at'
            )
            ->where('Utilisation.producteur', $employeId)
            ->whereDate('Utilisation.created_at', Carbon::now()->toDateString())
            ->orderBy('Utilisation.id_lot')
            ->get();
    }

    /**
     * Construit la collection des productions à partir des utilisations groupées par id_lot
     */
    private function buildProductionsFromUtilisations(Collection $utilisations): Collection
    {
        $productionsParLot = [];

        // Grouper par id_lot (chaque lot = une production unique)
        foreach ($utilisations as $utilisation) {
            $idLot = $utilisation->id_lot;
            $codeProduit = $utilisation->code_produit;

            if (!isset($productionsParLot[$idLot])) {
                $productionsParLot[$idLot] = [
                    'code_produit' => $codeProduit,
                    'nom' => $utilisation->nom_produit,
                    'prix' => $utilisation->prix_produit,
                    'quantite' => $utilisation->quantite_produit,
                    'matieres_premieres' => [],
                    'created_at' => $utilisation->created_at
                ];
            }

            // Ajouter la matière première à cette production
            $productionsParLot[$idLot]['matieres_premieres'][] = [
                'nom' => $utilisation->nom_matiere,
                'quantite' => $utilisation->quantite_matiere,
                'unite' => $utilisation->unite_matiere
            ];
        }

        // Maintenant grouper par produit pour avoir les statistiques globales
        $productionsGroupees = [];

        foreach ($productionsParLot as $idLot => $production) {
            $codeProduit = $production['code_produit'];

            if (!isset($productionsGroupees[$codeProduit])) {
                $productionsGroupees[$codeProduit] = [
                    'nom' => $production['nom'],
                    'prix' => $production['prix'],
                    'quantite' => 0,
                    'nombre_productions' => 0,
                    'matieres_premieres' => []
                ];
            }

            // Additionner les quantités et compter les productions
            $productionsGroupees[$codeProduit]['quantite'] += $production['quantite'];
            $productionsGroupees[$codeProduit]['nombre_productions']++;

            // Agréger les matières premières
            foreach ($production['matieres_premieres'] as $matiere) {
                $matiereKey = $matiere['nom'] . '_' . $matiere['unite'];

                $found = false;
                foreach ($productionsGroupees[$codeProduit]['matieres_premieres'] as &$existingMatiere) {
                    if ($existingMatiere['nom'] === $matiere['nom'] && $existingMatiere['unite'] === $matiere['unite']) {
                        $existingMatiere['quantite'] += $matiere['quantite'];
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $productionsGroupees[$codeProduit]['matieres_premieres'][] = [
                        'nom' => $matiere['nom'],
                        'quantite' => $matiere['quantite'],
                        'unite' => $matiere['unite']
                    ];
                }
            }
        }

        return collect(array_values($productionsGroupees));
    }

    private function buildExpectedProductionsCollection(Collection $assignments, int $employeId): Collection
    {
        $productions = collect();

        foreach ($assignments as $assignment) {
            $produit = Produit_fixes::where('code_produit', $assignment->produit)->first();
            if ($produit) {
                $productionStats = $this->getTodayProductionStatsByLot($employeId, $assignment->produit);
                $quantite_produite = $productionStats['quantite_totale'];

                if ($quantite_produite >= $assignment->expected_quantity) {
                    $assignment->status = 1;
                    $assignment->save();
                }

                $productions->push([
                    'nom' => $produit->nom,
                    'quantite_attendue' => $assignment->expected_quantity,
                    'quantite_produite' => $quantite_produite,
                    'nombre_productions' => $productionStats['nombre_productions'],
                    'prix' => $produit->prix,
                    'status' => $assignment->status == 1 ? "Terminé" : "En attente",
                    'progression' => min(($quantite_produite / $assignment->expected_quantity) * 100, 100)
                ]);
            }
        }

        return $productions;
    }

    private function buildRecommendedProductionsCollection(Collection $suggestions): Collection
    {
        return $suggestions->map(function ($suggestion) {
            $produit = Produit_fixes::where('code_produit', $suggestion->produit)->first();
            if ($produit) {
                return [
                    'nom' => $produit->nom,
                    'quantite_recommandee' => $suggestion->quantity,
                    'prix' => $produit->prix
                ];
            }
        })->filter();
    }

    /**
     * Calcule les statistiques de production en tenant compte des lots (id_lot)
     */
    private function getTodayProductionStatsByLot(int $employeId, string $produitId): array
    {
        // Grouper par id_lot pour éviter de compter plusieurs fois la même production
        // Utiliser MAX() car quantite_produit est identique pour toutes les lignes d'un même lot
        $productions = Utilisation::where('producteur', $employeId)
            ->where('produit', $produitId)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->select('id_lot', DB::raw('MAX(quantite_produit) as quantite_produit'))
            ->groupBy('id_lot')
            ->get();

        return [
            'quantite_totale' => $productions->sum('quantite_produit'),
            'nombre_productions' => $productions->count()
        ];
    }

    /**
     * Méthode utilitaire pour obtenir les productions détaillées par lot
     */
    public function getTodayProductionsByLot(int $employeId): Collection
    {
        $utilisations = $this->getTodayUtilisationsWithDetails($employeId);
        $productionsParLot = [];

        foreach ($utilisations as $utilisation) {
            $idLot = $utilisation->id_lot;

            if (!isset($productionsParLot[$idLot])) {
                $productionsParLot[$idLot] = [
                    'id_lot' => $idLot,
                    'produit' => $utilisation->nom_produit,
                    'quantite_produit' => $utilisation->quantite_produit,
                    'prix_unitaire' => $utilisation->prix_produit,
                    'matieres' => [],
                    'valeur_production' => $utilisation->quantite_produit * $utilisation->prix_produit,
                    'cout_matieres' => 0
                ];
            }

            $coutMatiere = $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;

            $productionsParLot[$idLot]['matieres'][] = [
                'nom' => $utilisation->nom_matiere,
                'quantite' => $utilisation->quantite_matiere,
                'unite' => $utilisation->unite_matiere,
                'cout' => $coutMatiere
            ];

            $productionsParLot[$idLot]['cout_matieres'] += $coutMatiere;
        }

        return collect(array_values($productionsParLot));
    }
}