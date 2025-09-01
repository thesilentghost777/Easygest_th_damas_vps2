<?php

namespace App\Http\Controllers;

use App\Models\Produit_fixes;
use App\Models\TransactionVente;
use App\Models\Matiere;
use App\Models\Utilisation;
use App\Models\MatiereRecommander;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyseProduitController extends Controller
{
    public function index(Request $request)
    {
        // Période par défaut: mois courant
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Conversion en objets Carbon avec les bonnes heures limites
        $dateDebut = Carbon::parse($dateDebut)->startOfDay(); // 00:00:00 du jour de début
        $dateFin = Carbon::parse($dateFin)->endOfDay();       // 23:59:59 du jour de fin
        // Récupérer les paramètres de tri
        $sortBy = $request->input('sort_by', 'chiffre_affaire');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Analyses des ventes et bénéfices
        $produitsAnalyse = $this->analyseProduitsComplete($dateDebut, $dateFin);
        
        // Appliquer le tri sur les données
        $produitsAnalyse = $this->sortProductsBy($produitsAnalyse, $sortBy, $sortOrder);
        
        // Appliquer la pagination
        $page = $request->input('page', 1);
        $perPage = 10; // 10 produits par page
        $totalItems = count($produitsAnalyse);
        $totalPages = ceil($totalItems / $perPage);
        
        // Extraire les produits de la page courante
        $produitsAnalysePaginated = array_slice(
            $produitsAnalyse, 
            ($page - 1) * $perPage, 
            $perPage
        );
        
        // Top produits par jour de la semaine
        $topParJour = $this->getTopProduitParJour();
        
        // Évolution mensuelle des ventes (pour tous les produits)
        $evolutionVentes = $this->getEvolutionVentes();
        
        // Récapitulatif des deux derniers mois
        $recapitulatifMois = $this->getRecapitulatifMois();

        // Produits les plus vendus mais avec peu de bénéfice
        $produitsPopulairesNonRentables = $this->getProduitsPopulairesNonRentables($dateDebut, $dateFin);
        
        // Produits les moins vendus
        $produitsLessMoinsVendus = $this->getProduitsLesMoinsVendus($dateDebut, $dateFin);

        // Meilleures ventes par jour de la semaine sur période totale
        $meilleuresVentesParJour = $this->getMeilleuresVentesParJour();
        
        return view('analyse.produits.index', compact(
            'produitsAnalysePaginated',
            'topParJour',
            'evolutionVentes',
            'recapitulatifMois',
            'produitsPopulairesNonRentables',
            'produitsLessMoinsVendus',
            'meilleuresVentesParJour',
            'dateDebut',
            'dateFin',
            'sortBy',
            'sortOrder',
            'page',
            'totalPages'
        ));
    }
    /**
     * Trie les produits selon le critère spécifié
     *
     * @param array $products Liste des produits à trier
     * @param string $sortBy Critère de tri
     * @param string $sortOrder Direction du tri (asc/desc)
     * @return array
     */
    public function sortProductsBy($products, $sortBy, $sortOrder)
    {
        $multiplier = ($sortOrder === 'asc') ? 1 : -1;
        
        usort($products, function($a, $b) use ($sortBy, $multiplier) {
            if (!isset($a[$sortBy]) || !isset($b[$sortBy])) {
                return 0;
            }
            
            if ($a[$sortBy] == $b[$sortBy]) {
                return 0;
            }
            
            return ($a[$sortBy] < $b[$sortBy]) ? -1 * $multiplier : 1 * $multiplier;
        });
        
        return $products;
    }
    
   /**
 * Calcule le coût de production pour un producteur spécifique
 */
public function calculerCoutProductionProducteur($produitId, $producteurId)
{
    // Récupérer tous les lots distincts pour ce producteur et ce produit
    $lots = Utilisation::where('produit', $produitId)
        ->where('producteur', $producteurId)
        ->select('id_lot')
        ->distinct()
        ->get()
        ->pluck('id_lot');
    
    $coutTotal = 0;
    
    foreach ($lots as $lot) {
        // Pour chaque lot, calculer le coût des matières
        $coutMatieresLot = Utilisation::where('produit', $produitId)
            ->where('producteur', $producteurId)
            ->where('id_lot', $lot)
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->select(
                'Utilisation.quantite_matiere',
                'Matiere.prix_par_unite_minimale'
            )
            ->get()
            ->sum(function ($utilisation) {
                return $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
            });
        
        $coutTotal += $coutMatieresLot;
    }
    
    return $coutTotal;
}

/**
 * Version améliorée de la fonction details pour corriger le calcul des coûts
 */
public function details($id)
{
    $produit = Produit_fixes::findOrFail($id);
    
    // Statistiques générales
    // Calculer la quantité vendue
    $quantiteVendue = TransactionVente::where('produit', $id)
        ->where('type', 'Vente')
        ->sum('quantite');
    
    // Calculer le chiffre d'affaires
    $chiffreAffaire = TransactionVente::where('produit', $id)
        ->where('type', 'Vente')
        ->sum(DB::raw('prix * quantite'));
    
    // Calculer le coût de production
    $coutProduction = $this->calculerCoutProduction($id);
    
    $stats = [
        'chiffre_affaire' => $chiffreAffaire,
        'quantite_vendue' => $quantiteVendue,
        'cout_production' => $coutProduction,
        'benefice' => $chiffreAffaire - $coutProduction,
        'marge' => $chiffreAffaire > 0 ? (($chiffreAffaire - $coutProduction) / $chiffreAffaire) * 100 : 0,
    ];
    
    // Performances par producteur
    // D'abord récupérer les quantités produites en distinguant par lot pour éviter de compter plusieurs fois
    $productionParProducteur = Utilisation::where('produit', $id)
        ->join('users', 'Utilisation.producteur', '=', 'users.id')
        ->select(
            'users.id',
            'users.name as nom',
            'Utilisation.id_lot',
            'Utilisation.quantite_produit'
        )
        ->distinct('Utilisation.id_lot')
        ->get()
        ->groupBy('id')
        ->map(function ($lots) {
            return [
                'nom' => $lots->first()->nom,
                'quantite' => $lots->sum('quantite_produit')
            ];
        });
    
    $producteurs = collect($productionParProducteur)
        ->map(function ($data, $producteurId) use ($id, $produit) {
            $coutTotal = $this->calculerCoutProductionProducteur($id, $producteurId);
            $quantite = $data['quantite'];
            $benefice = ($quantite * $produit->prix) - $coutTotal;
            $coutMoyen = $quantite > 0 ? $coutTotal : 0;
            
            return [
                'id' => $producteurId,
                'nom' => $data['nom'],
                'quantite' => $quantite,
                'cout_total' => $coutTotal,
                'cout_moyen' => $coutMoyen,
                'benefice' => $benefice,
            ];
        })
        ->sortByDesc('quantite')
        ->values()
        ->all();
    
    // Matières recommandées
    $matieres_recommandees = MatiereRecommander::where('produit', $id)
        ->with('matiere')
        ->get();
    
    // Évolution des ventes (des 3 derniers mois)
    $evolution_ventes = TransactionVente::where('produit', $id)
        ->where('date_vente', '>=', Carbon::now()->subMonths(3))
        ->select(
            DB::raw('DATE(date_vente) as date'),
            DB::raw('SUM(quantite) as quantite'),
            DB::raw('SUM(quantite * prix) as chiffre_affaire')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->where('type', 'Vente')
        ->get();
    
    return view('analyse.produits.details', compact(
        'produit',
        'stats',
        'producteurs',
        'matieres_recommandees',
        'evolution_ventes'
    ));
}
   

  public function analyseProduitsComplete($dateDebut = null, $dateFin = null)
{
    $query = DB::table('Produit_fixes')
        ->leftJoin('transaction_ventes', function ($join) use ($dateDebut, $dateFin) {
            $join->on('Produit_fixes.code_produit', '=', 'transaction_ventes.produit')
                 ->where('transaction_ventes.type', 'Vente');
            
            if ($dateDebut && $dateFin) {
                $join->whereBetween('transaction_ventes.date_vente', [$dateDebut, $dateFin]);
            }
        });
    
    return $query->select(
            'Produit_fixes.code_produit',
            'Produit_fixes.nom',
            'Produit_fixes.prix',
            DB::raw('SUM(transaction_ventes.quantite) as quantite_vendue'),
            DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as chiffre_affaire'),
            DB::raw('COUNT(DISTINCT transaction_ventes.date_vente) as jours_vente')
        )
        ->groupBy('Produit_fixes.code_produit', 'Produit_fixes.nom', 'Produit_fixes.prix')
        ->get()
        ->map(function ($produit) use ($dateDebut, $dateFin) {
            // Appeler toutes les méthodes avec les paramètres de date
            $coutProduction = $this->calculerCoutProduction($produit->code_produit, $dateDebut, $dateFin);
            $meilleurProducteur = $this->trouverMeilleurProducteur($produit->code_produit, $dateDebut, $dateFin);
            $ratioProductionVente = $this->calculerRatioProductionVente($produit->code_produit, $dateDebut, $dateFin);
            $benefice = ($produit->chiffre_affaire ?? 0) - $coutProduction;
            $perteAvariee = $this->calculerPertesAvariees($produit->code_produit);
            $jourMaxVente = $this->trouverJourMaxVente($produit->code_produit, $dateDebut, $dateFin);
            $moisMaxVente = $this->trouverMoisMaxVente($produit->code_produit, $dateDebut, $dateFin);
            
            return [
                'id' => $produit->code_produit,
                'nom' => $produit->nom,
                'prix_vente' => $produit->prix,
                'quantite_vendue' => $produit->quantite_vendue ?? 0,
                'chiffre_affaire' => $produit->chiffre_affaire ?? 0,
                'cout_production' => $coutProduction,
                'benefice' => $benefice,
                'benefice_par_unite' => $produit->quantite_vendue > 0 ? $benefice / $produit->quantite_vendue : 0,
                'marge' => $produit->chiffre_affaire > 0 ? ($benefice / $produit->chiffre_affaire) * 100 : 0,
                'ratio_production_vente' => $ratioProductionVente,
                'meilleur_producteur' => $meilleurProducteur,
                'frequence_vente' => $produit->jours_vente ?? 0,
                'pertes_avariees' => $perteAvariee,
                'jour_max_vente' => $jourMaxVente,
                'mois_max_vente' => $moisMaxVente,
                'periode_analyse' => [
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin
                ]
            ];
        })
        ->sortByDesc('chiffre_affaire')
        ->values()
        ->all();
}

public function calculerCoutProduction($produitId, $dateDebut = null, $dateFin = null)
{
    // Récupérer toutes les utilisations regroupées par lot avec filtrage par date
    $query = Utilisation::where('produit', $produitId);
    
    if ($dateDebut && $dateFin) {
        $query->whereBetween('created_at', [$dateDebut, $dateFin]);
    }
    
    $lots = $query->select('id_lot')
        ->distinct()
        ->get()
        ->pluck('id_lot');
    
    $coutTotal = 0;
    
    foreach ($lots as $lot) {
        // Pour chaque lot, calculer le coût des matières
        $coutMatieresLot = Utilisation::where('produit', $produitId)
            ->where('id_lot', $lot)
            ->when($dateDebut && $dateFin, function ($query) use ($dateDebut, $dateFin) {
                return $query->whereBetween('Utilisation.created_at', [$dateDebut, $dateFin]);
            })
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->select(
                'Utilisation.quantite_matiere',
                'Matiere.prix_par_unite_minimale'
            )
            ->get()
            ->sum(function ($utilisation) {
                return $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
            });
        
        $coutTotal += $coutMatieresLot;
    }
    
    return $coutTotal;
}


   public function calculerRatioProductionVente($produitId, $dateDebut = null, $dateFin = null)
{
    // Récupérer tous les lots distincts pour ce produit dans la période
    $lotsQuery = Utilisation::where('produit', $produitId);
    
    if ($dateDebut && $dateFin) {
        $lotsQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
    }
    
    $lots = $lotsQuery->select('id_lot')
        ->distinct()
        ->get();
    
    // Calculer la production totale en ne comptant chaque lot qu'une seule fois
    $totalProduit = 0;
    foreach ($lots as $lot) {
        // Pour chaque lot, prendre la quantité produite une seule fois
        $quantiteLotQuery = Utilisation::where('produit', $produitId)
            ->where('id_lot', $lot->id_lot);
            
        if ($dateDebut && $dateFin) {
            $quantiteLotQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
        
        $quantiteLot = $quantiteLotQuery->select('quantite_produit')->first();
        
        if ($quantiteLot) {
            $totalProduit += $quantiteLot->quantite_produit;
        }
    }
    
    // Calculer le total vendu dans la période
    $totalVenduQuery = TransactionVente::where('produit', $produitId)
        ->where('type', 'Vente');
        
    if ($dateDebut && $dateFin) {
        $totalVenduQuery->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }
    
    $totalVendu = $totalVenduQuery->sum('quantite');
    
    // Calculer et retourner le ratio
    return $totalProduit > 0 ? ($totalVendu / $totalProduit) * 100 : 0;
}


    public function calculerPertesAvariees($produitId)
    {
        // Supposons que les pertes avariées sont la différence entre ce qui est produit et ce qui est vendu
        // multipliée par le coût de production unitaire
        $totalProduit = Utilisation::where('produit', $produitId)
            ->sum('quantite_produit');
            
        $totalVendu = TransactionVente::where('produit', $produitId)
            ->where('type','Vente')
            ->sum('quantite');
            
            
        $difference = $totalVendu = TransactionVente::where('produit', $produitId)
        ->where('type','Produit Avarie')
        ->sum('quantite');
        
        // Cout moyen de production par unité
        $coutTotal = $this->calculerCoutProduction($produitId);
        $coutUnitaire = $totalProduit > 0 ? $coutTotal / $totalProduit : 0;
        
        return $difference * $coutUnitaire;
    }

    public function trouverJourMaxVente($produitId, $dateDebut = null, $dateFin = null)
{
    $query = DB::table('transaction_ventes')
        ->where('produit', $produitId)
        ->where('type', 'Vente');
        
    if ($dateDebut && $dateFin) {
        $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }
    
    return $query->select(
            DB::raw('DAYNAME(date_vente) as jour'),
            DB::raw('SUM(quantite) as total_vente')
        )
        ->groupBy('jour')
        ->orderBy('total_vente', 'desc')
        ->first();
}

public function trouverMoisMaxVente($produitId, $dateDebut = null, $dateFin = null)
{
    $query = DB::table('transaction_ventes')
        ->where('produit', $produitId)
        ->where('type', 'Vente');
        
    if ($dateDebut && $dateFin) {
        $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }
    
    return $query->select(
            DB::raw('MONTH(date_vente) as mois'),
            DB::raw('MONTHNAME(date_vente) as nom_mois'),
            DB::raw('SUM(quantite) as total_vente')
        )
        ->groupBy('mois', 'nom_mois')
        ->orderBy('total_vente', 'desc')
        ->first();
}


   public function trouverMeilleurProducteur($produitId, $dateDebut = null, $dateFin = null)
{
    // Récupérer tous les producteurs qui ont produit ce produit dans la période
    $query = Utilisation::where('produit', $produitId);
    
    if ($dateDebut && $dateFin) {
        $query->whereBetween('Utilisation.created_at', [$dateDebut, $dateFin]);
    }
    
    $producteurs = $query->join('users', 'Utilisation.producteur', '=', 'users.id')
        ->select('users.id', 'users.name')
        ->distinct()
        ->get();
    
    $resultats = [];
    
    // Pour chaque producteur, calculer correctement sa production totale
    foreach ($producteurs as $producteur) {
        // Récupérer tous les lots distincts pour ce producteur dans la période
        $lotsQuery = Utilisation::where('produit', $produitId)
            ->where('producteur', $producteur->id);
            
        if ($dateDebut && $dateFin) {
            $lotsQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
        
        $lots = $lotsQuery->select('id_lot')
            ->distinct()
            ->get();
        
        // Calculer la production totale en ne comptant chaque lot qu'une seule fois
        $totalProduction = 0;
        foreach ($lots as $lot) {
            // Pour chaque lot, prendre la quantité produite une seule fois
            $quantiteLotQuery = Utilisation::where('produit', $produitId)
                ->where('producteur', $producteur->id)
                ->where('id_lot', $lot->id_lot);
                
            if ($dateDebut && $dateFin) {
                $quantiteLotQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
            }
            
            $quantiteLot = $quantiteLotQuery->select('quantite_produit')->first();
            
            if ($quantiteLot) {
                $totalProduction += $quantiteLot->quantite_produit;
            }
        }
        
        // Ajouter les résultats pour ce producteur
        $resultats[] = [
            'id' => $producteur->id,
            'name' => $producteur->name,
            'total_production' => $totalProduction,
            'nombre_lots' => $lots->count()
        ];
    }
    
    // Trier les résultats par production totale (décroissant)
    usort($resultats, function($a, $b) {
        return $b['total_production'] - $a['total_production'];
    });
    
    // Retourner le meilleur producteur ou null si aucun trouvé
    return !empty($resultats) ? (object)$resultats[0] : null;
}

    public function getTopProduitParJour()
    {
        $jours = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $topParJour = [];

        foreach ($jours as $jour) {
            $topParJour[$jour] = DB::table('transaction_ventes')
                ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
                ->select(
                    'Produit_fixes.nom',
                    'Produit_fixes.code_produit',
                    DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as total_ventes')
                )
                ->whereRaw("DAYNAME(date_vente) = ?", [$jour])
                ->groupBy('Produit_fixes.nom', 'Produit_fixes.code_produit')
                ->orderBy('total_ventes', 'desc')
                ->where('type','Vente')
                ->first();
        }

        return $topParJour;
    }

    public function getEvolutionVentes()
    {
        $evolutionParProduit = DB::table('transaction_ventes')
            ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->select(
                'Produit_fixes.nom',
                DB::raw('DATE_FORMAT(date_vente, "%Y-%m") as mois'),
                DB::raw('SUM(quantite * Produit_fixes.prix) as total_ventes')
            )
            ->where('date_vente', '>=', Carbon::now()->subYear()->format('Y-m-d'))
            ->groupBy('Produit_fixes.nom', 'mois')
            ->orderBy('mois')
            ->where('type','Vente')
            ->get()
            ->groupBy('nom');

        return $evolutionParProduit;
    }

    public function getRecapitulatifMois()
    {
        return DB::table('transaction_ventes')
            ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->select(
                'Produit_fixes.nom',
                'Produit_fixes.code_produit',
                DB::raw('DATE_FORMAT(date_vente, "%Y-%m") as mois'),
                DB::raw('MONTHNAME(date_vente) as nom_mois'),
                DB::raw('SUM(quantite) as total_quantite')
            )
            ->whereRaw('date_vente >= DATE_SUB(NOW(), INTERVAL 2 MONTH)')
            ->groupBy('Produit_fixes.nom', 'Produit_fixes.code_produit', 'mois', 'nom_mois')
            ->orderBy('total_quantite', 'desc')
            ->get()
            ->groupBy('mois')
            ->map(function($items) {
                return $items->take(2);
            });
    }

    public function getProduitsPopulairesNonRentables($dateDebut, $dateFin)
    {
        $produits = $this->analyseProduitsComplete($dateDebut, $dateFin);
        
        return collect($produits)
            ->sortByDesc('quantite_vendue')
            ->filter(function($produit) {
                // Produits avec une marge inférieure à la moyenne mais une quantité vendue supérieure à la moyenne
                return $produit['marge'] < 30 && $produit['quantite_vendue'] > 0;
            })
            ->take(5)
            ->values()
            ->all();
    }

    public function getProduitsLesMoinsVendus($dateDebut, $dateFin)
    {
        $produits = $this->analyseProduitsComplete($dateDebut, $dateFin);
        
        return collect($produits)
            ->sortBy('quantite_vendue')
            ->take(5)
            ->values()
            ->all();
    }

    public function getMeilleuresVentesParJour()
    {
        $jours = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $resultat = [];
        
        foreach ($jours as $jour) {
            $topVentes = DB::table('transaction_ventes')
                ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
                ->select(
                    'Produit_fixes.nom',
                    'Produit_fixes.code_produit',
                    DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as total_ventes'),
                    DB::raw('SUM(transaction_ventes.quantite) as quantite_totale')
                )
                ->whereRaw('DAYNAME(date_vente) = ?', [$jour])
                ->groupBy('Produit_fixes.nom', 'Produit_fixes.code_produit')
                ->orderBy('total_ventes', 'desc')
                ->limit(3)
                ->where('type','Vente')
                ->get();
                
            $resultat[$jour] = $topVentes;
        }
        
        return $resultat;
    }
}
