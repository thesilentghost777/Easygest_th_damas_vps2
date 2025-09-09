<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisation;
use App\Models\ReceptionPointeur;
use App\Models\ReceptionVendeur;
use App\Models\FluxJournalier;
use App\Models\Manquant;
use App\Models\ManquantTemporaire;
use App\Models\Produit_fixes;
use App\Models\User;
use Carbon\Carbon;
use DB;

class ManquantFluxController extends Controller
{
    public function index()
    {
        $flux = FluxJournalier::with('produit')
            ->orderBy('date_flux', 'desc')
            ->paginate(20);

        return view('manquants_flux.index', compact('flux'));
    }

    public function calculerFluxJournalier(Request $request)
{
    $date = $request->date ?? Carbon::today()->toDateString();
    
    // Vider toute la table flux_journaliers avant de recalculer
    DB::table('flux_journaliers')->truncate();
    
    // Récupérer tous les produits ayant une activité à cette date
    $produits = $this->getProduitsAvecActivite($date);
    
    foreach ($produits as $produitId) {
        $this->calculerFluxProduit($produitId, $date);
        $this->calculerManquantsProduit($produitId, $date);
    }
    
    return redirect()->route('manquant-flux.index')
        ->with('success', "Flux et manquants calculés pour le $date");
}

    private function getProduitsAvecActivite($date)
    {
        $produitsProduction = Utilisation::whereDate('created_at', $date)
            ->select('produit')
            ->distinct()
            ->pluck('produit');

        $produitsPointage = ReceptionPointeur::where('date_reception', $date)
            ->select('produit_id')
            ->distinct()
            ->pluck('produit_id');

        $produitsVente = ReceptionVendeur::where('date_reception', $date)
            ->select('produit_id')
            ->distinct()
            ->pluck('produit_id');

        return collect()
            ->merge($produitsProduction)
            ->merge($produitsPointage)
            ->merge($produitsVente)
            ->unique()
            ->values();
    }

    private function calculerFluxProduit($produitId, $date)
    {
        // Production totale en regroupant par id_lot pour éviter les doublons
        $productionsGroupees = Utilisation::where('produit', $produitId)
            ->whereDate('created_at', $date)
            ->select('id_lot', 'quantite_produit', 'producteur')
            ->distinct() // Important: éviter les doublons par id_lot
            ->get()
            ->groupBy('id_lot')
            ->map(function($group) {
                // Prendre le premier élément de chaque groupe (même id_lot = même production)
                return $group->first();
            });
        
        $totalProduction = $productionsGroupees->sum('quantite_produit');
        
        $detailProductions = $productionsGroupees->map(function($item) {
            $producteur = User::find($item->producteur);
            return [
                'id_lot' => $item->id_lot,
                'quantite_produit' => (float)$item->quantite_produit,
                'producteur_id' => $item->producteur,
                'producteur_nom' => $producteur ? $producteur->name : 'Inconnu'
            ];
        })->values()->toArray();

        // Pointage total avec détails JSON
        $pointages = ReceptionPointeur::where('produit_id', $produitId)
            ->where('date_reception', $date)
            ->get(['pointeur_id', 'quantite_recue']);
        
        $totalPointage = $pointages->sum('quantite_recue');
        $detailPointages = $pointages->map(function($item) {
            $pointeur = User::find($item->pointeur_id);
            return [
                'pointeur_id' => $item->pointeur_id,
                'pointeur_nom' => $pointeur ? $pointeur->name : 'Inconnu',
                'quantite_recue' => (float)$item->quantite_recue
            ];
        })->toArray();

        // Réception vendeur avec détails JSON
        $receptions = ReceptionVendeur::where('produit_id', $produitId)
            ->where('date_reception', $date)
            ->get(['vendeur_id', 'quantite_entree_matin', 'quantite_entree_journee', 'quantite_invendue', 'quantite_reste_hier']);
        
        $totalReceptionVendeur = $receptions->sum(function($item) {
            return $item->quantite_entree_matin + $item->quantite_entree_journee;
        });
        
        $detailReceptions = $receptions->map(function($item) {
            $vendeur = User::find($item->vendeur_id);
            return [
                'vendeur_id' => $item->vendeur_id,
                'vendeur_nom' => $vendeur ? $vendeur->name : 'Inconnu',
                'quantite_entree_matin' => (float)$item->quantite_entree_matin,
                'quantite_entree_journee' => (float)$item->quantite_entree_journee,
                'quantite_invendue' => (float)$item->quantite_invendue,
                'quantite_reste_hier' => (float)$item->quantite_reste_hier
            ];
        })->toArray();

        FluxJournalier::updateOrCreate(
            [
                'produit_id' => $produitId,
                'date_flux' => $date,
            ],
            [
                'total_production' => $totalProduction,
                'total_pointage' => $totalPointage,
                'total_reception_vendeur' => $totalReceptionVendeur,
                'detail_productions' => empty($detailProductions) ? null : json_encode($detailProductions),
                'detail_pointages' => empty($detailPointages) ? null : json_encode($detailPointages),
                'detail_receptions' => empty($detailReceptions) ? null : json_encode($detailReceptions),
            ]
        );
    }

    private function calculerManquantsProduit($produitId, $date)
    {
        $flux = FluxJournalier::where('produit_id', $produitId)
            ->where('date_flux', $date)
            ->first();

        if (!$flux) return;

        $produit = Produit_fixes::find($produitId);
        $prixUnitaire = $produit->prix ?? 0;

        // Calcul des manquants et incohérences
        $diffProdPointeur = $flux->total_production - $flux->total_pointage;
        $diffPointeurVendeur = $flux->total_pointage - $flux->total_reception_vendeur;

        // Si la différence est positive = manquant, si négative = incohérence
        $manquantProdPointeur = max(0, $diffProdPointeur);
        $incoherenceProdPointeur = max(0, -$diffProdPointeur);
        
        $manquantPointeurVendeur = max(0, $diffPointeurVendeur);
        $incoherencePointeurVendeur = max(0, -$diffPointeurVendeur);

        // Manquant vendeur (invendus de la veille non retrouvés)
        $manquantVendeurInvendu = $this->calculerManquantVendeurInvendu($produitId, $date);

        // Conversion en montants FCFA (seulement pour les manquants, pas les incohérences)
        $montantProducteur = 0;
        $montantPointeur = ($manquantProdPointeur * $prixUnitaire); 
        $montantVendeur = ($manquantPointeurVendeur * $prixUnitaire) + ($manquantVendeurInvendu * $prixUnitaire);

        // Récupération des détails des intervenants depuis les JSON
        $detailsProducteurs = json_decode($flux->detail_productions, true) ?? [];
        $detailsPointeurs = json_decode($flux->detail_pointages, true) ?? [];
        $detailsVendeurs = json_decode($flux->detail_receptions, true) ?? [];

        Manquant::updateOrCreate(
            [
                'produit_id' => $produitId,
                'date_calcul' => $date,
            ],
            [
                'manquant_producteur_pointeur' => $manquantProdPointeur,
                'manquant_pointeur_vendeur' => $manquantPointeurVendeur,
                'manquant_vendeur_invendu' => $manquantVendeurInvendu,
                'incoherence_producteur_pointeur' => $incoherenceProdPointeur,
                'incoherence_pointeur_vendeur' => $incoherencePointeurVendeur,
                'montant_producteur' => $montantProducteur,
                'montant_pointeur' => $montantPointeur,
                'montant_vendeur' => $montantVendeur,
                'details_producteurs' => json_encode($detailsProducteurs),
                'details_pointeurs' => json_encode($detailsPointeurs),
                'details_vendeurs' => json_encode($detailsVendeurs),
            ]
        );
    }

    private function calculerManquantVendeurInvendu($produitId, $date)
    {
        $dateVeille = Carbon::parse($date)->subDay()->toDateString();
        
        $invenduVeille = ReceptionVendeur::where('produit_id', $produitId)
            ->where('date_reception', $dateVeille)
            ->sum('quantite_invendue');

        $resteRecupere = ReceptionVendeur::where('produit_id', $produitId)
            ->where('date_reception', $date)
            ->sum('quantite_reste_hier');

        return max(0, $invenduVeille - $resteRecupere);
    }

    public function voirDetails($fluxId)
    {
        $flux = FluxJournalier::with('produit')->findOrFail($fluxId);
        $manquant = Manquant::where('produit_id', $flux->produit_id)
            ->where('date_calcul', $flux->date_flux)
            ->first();

        // Récupérer les détails depuis les JSON stockés dans flux_journaliers
        $detailsProducteurs = json_decode($flux->detail_productions, true) ?? [];
        $detailsPointeurs = json_decode($flux->detail_pointages, true) ?? [];
        $detailsVendeurs = json_decode($flux->detail_receptions, true) ?? [];

        return view('manquants_flux.details', compact('flux', 'manquant', 'detailsProducteurs', 'detailsPointeurs', 'detailsVendeurs'));
    }

//calcul des manquants - VERSION CORRIGÉE

public function afficherRepartition()
{
    return view('manquants_flux.repartition');
}

public function repartirManquants(Request $request)
{
    $request->validate([
        'mois' => 'required|date_format:Y-m'
    ]);
    
    $mois = $request->mois;
    $annee = substr($mois, 0, 4);
    $moisNum = substr($mois, 5, 2);
    
    try {
        DB::beginTransaction();
        
        // Récupérer tous les manquants du mois spécifié
        $manquants = Manquant::whereYear('date_calcul', $annee)
            ->whereMonth('date_calcul', $moisNum)
            ->where(function($query) {
                $query->where('montant_pointeur', '>', 0)
                      ->orWhere('montant_vendeur', '>', 0);
            })
            ->get();
        
        if ($manquants->isEmpty()) {
            return redirect()->back()
                ->with('warning', "Aucun manquant trouvé pour le mois $mois");
        }
        
        $totalReparti = 0;
        $employesAffectes = 0;
        
        foreach ($manquants as $manquant) {
            // Répartir les manquants des pointeurs (seulement si positifs)
            if ($manquant->montant_pointeur > 0) {
                $detailsPointeurs = json_decode($manquant->details_pointeurs, true) ?? [];
                $result = $this->repartirMontantPointeurs($detailsPointeurs, $manquant->montant_pointeur, $manquant->date_calcul, $manquant->produit_id);
                $totalReparti += $result['montant'];
                $employesAffectes += $result['employes'];
            }
            
            // Répartir les manquants des vendeurs (seulement si positifs)
            if ($manquant->montant_vendeur > 0) {
                $detailsVendeurs = json_decode($manquant->details_vendeurs, true) ?? [];
                $result = $this->repartirMontantVendeurs($detailsVendeurs, $manquant->montant_vendeur, $manquant->date_calcul, $manquant->produit_id);
                $totalReparti += $result['montant'];
                $employesAffectes += $result['employes'];
            }
        }
        
        DB::commit();
        
        return redirect()->back()
            ->with('success', "Répartition terminée pour $mois : $totalReparti FCFA répartis sur $employesAffectes employés");
            
    } catch (\Exception $e) {
        DB::rollback();
        
        return redirect()->back()
            ->with('error', "Erreur lors de la répartition : " . $e->getMessage());
    }
}

private function repartirMontantPointeurs($detailsPointeurs, $montantTotal, $date, $produitId)
{
    if (empty($detailsPointeurs)) {
        return ['montant' => 0, 'employes' => 0];
    }
    
    $produit = Produit_fixes::find($produitId);
    $nomProduit = $produit->designation ?? "Produit $produitId";
    
    $montantReparti = 0;
    $employesAffectes = 0;
    
    // Filtrer seulement les pointeurs avec des manquants positifs 
    // Le manquant pointeur est déjà calculé et stocké dans manquant_pointeur_vendeur
    // On répartit seulement si ce montant est positif
    $pointeursAvecManquants = [];
    foreach ($detailsPointeurs as $detail) {
        // Chaque pointeur contribue au manquant s'il y en a un
        if ($montantTotal > 0) {
            $pointeursAvecManquants[] = $detail;
        }
    }
    
    $nombrePointeurs = count($pointeursAvecManquants);
    
    if ($nombrePointeurs > 0) {
        $montantParPointeur = round($montantTotal / $nombrePointeurs);
        
        foreach ($pointeursAvecManquants as $detail) {
            if ($montantParPointeur > 0) {
                $this->ajouterManquantTemporaire(
                    $detail['pointeur_id'],
                    $montantParPointeur,
                    "Manquant pointeur - $nomProduit du $date (Montant réparti: $montantParPointeur FCFA)"
                );
                $montantReparti += $montantParPointeur;
                $employesAffectes++;
            }
        }
    }
    
    return ['montant' => $montantReparti, 'employes' => $employesAffectes];
}

private function repartirMontantVendeurs($detailsVendeurs, $montantTotal, $date, $produitId)
{
    if (empty($detailsVendeurs)) {
        return ['montant' => 0, 'employes' => 0];
    }
    
    $produit = Produit_fixes::find($produitId);
    $nomProduit = $produit->designation ?? "Produit $produitId";
    
    $montantReparti = 0;
    $employesAffectes = 0;
    
    // Filtrer seulement les vendeurs avec des manquants positifs 
    // Le manquant vendeur est déjà calculé et stocké dans montant_vendeur
    // On répartit seulement si ce montant est positif
    $vendeursAvecManquants = [];
    foreach ($detailsVendeurs as $detail) {
        // Chaque vendeur contribue au manquant s'il y en a un
        if ($montantTotal > 0) {
            $vendeursAvecManquants[] = $detail;
        }
    }
    
    $nombreVendeurs = count($vendeursAvecManquants);
    
    if ($nombreVendeurs > 0) {
        $montantParVendeur = round($montantTotal / $nombreVendeurs);
        
        foreach ($vendeursAvecManquants as $detail) {
            if ($montantParVendeur > 0) {
                $this->ajouterManquantTemporaire(
                    $detail['vendeur_id'],
                    $montantParVendeur,
                    "Manquant vendeur - $nomProduit du $date (Montant réparti: $montantParVendeur FCFA, invendus exclus)"
                );
                $montantReparti += $montantParVendeur;
                $employesAffectes++;
            }
        }
    }
    
    return ['montant' => $montantReparti, 'employes' => $employesAffectes];
}

private function ajouterManquantTemporaire($employeId, $montant, $explication)
{
    // Vérifier si l'employé existe
    $employe = User::find($employeId);
    if (!$employe) return;
    
    // Créer le manquant temporaire
    \App\Models\ManquantTemporaire::create([
        'employe_id' => $employeId,
        'montant' => $montant,
        'explication' => $explication,
        'statut' => 'en_attente'
    ]);
}

}