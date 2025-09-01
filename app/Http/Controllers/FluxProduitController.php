<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\ProduitRecu1;
use App\Models\ProduitRecuVendeur;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\ManquantTemporaire;
use App\Traits\HistorisableActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FluxProduitController extends Controller
{
    use HistorisableActions;
    
    protected $notificationController;
    
    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }
    
    /**
     * Dashboard principal du flux de produits
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les autorisations
        if (!in_array($user->role, ['dg', 'pdg', 'chef_production'])) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $producteur_id = $request->get('producteur_id');
        $pointeur_id = $request->get('pointeur_id');
        
        $isFrench = session('locale', 'fr') === 'fr';
        
        // Récupérer les données du flux
        $fluxData = $this->getFluxData($date, $producteur_id, $pointeur_id);
        
        // Calculer les statistiques
        $stats = $this->calculateFluxStats($date);
        
        // Détecter les anomalies
        $anomalies = $this->detectAnomalies($date);
        
        // Listes pour les filtres
        $producteurs = User::where('role', 'boulanger')
            ->orWhere('role', 'patissier')
            ->get();
            
        $pointeurs = User::where('role', 'pointeur')->get();
        
        return view('flux-produit.dashboard', compact(
            'fluxData', 'stats', 'anomalies', 'producteurs', 'pointeurs', 
            'date', 'producteur_id', 'pointeur_id', 'isFrench'
        ));
    }
    
    /**
     * Récupérer les données structurées du flux
     */
    private function getFluxData($date, $producteur_id = null, $pointeur_id = null)
    {
        $dateDebut = Carbon::parse($date)->startOfDay();
        $dateFin = Carbon::parse($date)->endOfDay();
        
        // Étape 1: Productions par lot
        $productionsQuery = DB::table('Utilisation as u')
            ->join('Produit_fixes as pf', 'u.produit', '=', 'pf.code_produit')
            ->join('users as prod', 'u.producteur', '=', 'prod.id')
            ->select(
                'u.id_lot',
                'u.produit',
                'pf.nom as nom_produit',
                'pf.prix as prix_unitaire',
                'prod.id as producteur_id',
                'prod.name as producteur_nom',
                DB::raw('SUM(u.quantite_produit) as quantite_totale_produite'),
                DB::raw('MAX(u.created_at) as date_production')
            )
            ->whereBetween('u.created_at', [$dateDebut, $dateFin])
            ->groupBy('u.id_lot', 'u.produit', 'pf.nom', 'pf.prix', 'prod.id', 'prod.name');

        if ($producteur_id) {
            $productionsQuery->where('u.producteur', $producteur_id);
        }

        $productions = $productionsQuery->get();
        
        // Étape 2: Réceptions par pointeur
        $receptionsQuery = ProduitRecu1::with(['produit', 'producteur', 'pointeur'])
            ->whereBetween('date_reception', [$dateDebut, $dateFin]);
            
        if ($producteur_id) {
            $receptionsQuery->where('producteur_id', $producteur_id);
        }
        
        if ($pointeur_id) {
            $receptionsQuery->where('pointeur_id', $pointeur_id);
        }
        
        $receptions = $receptionsQuery->get();
        
        // Étape 3: Assignations aux vendeurs
        $assignations = ProduitRecuVendeur::with(['produitRecu.produit', 'vendeur'])
            ->whereHas('produitRecu', function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_reception', [$dateDebut, $dateFin]);
            })
            ->get();
        
        // Structurer les données par flux complet
        return $this->structureFluxData($productions, $receptions, $assignations);
    }
    
    /**
     * Structurer les données en flux complets
     */
    private function structureFluxData($productions, $receptions, $assignations)
    {
        $flux = [];
        
        // Regrouper les productions par produit et producteur
        $productionsGroupees = $productions->groupBy(function($item) {
            return $item->produit . '_' . $item->producteur_id;
        });
        
        foreach ($productionsGroupees as $key => $productionGroupe) {
            list($produit_id, $producteur_id) = explode('_', $key);
            
            $premiereProd = $productionGroupe->first();
            
            // Calculer les totaux de production
            $quantite_produite = $productionGroupe->sum('quantite_totale_produite');
            $valeur_produite = $quantite_produite * $premiereProd->prix_unitaire;
            
            // Trouver les réceptions correspondantes
            $receptionsCorrespondantes = $receptions->where('produit_id', $produit_id)
                ->where('producteur_id', $producteur_id);
                
            $quantite_recue_total = $receptionsCorrespondantes->sum('quantite');
            $valeur_recue_total = $quantite_recue_total * $premiereProd->prix_unitaire;
            
            // Analyser les assignations
            $assignationsData = [];
            $quantite_assignee_total = 0;
            
            foreach ($receptionsCorrespondantes as $reception) {
                $assignationsReception = $assignations->where('produit_recu_id', $reception->id);
                
                foreach ($assignationsReception as $assignation) {
                    $assignationsData[] = [
                        'vendeur_nom' => $assignation->vendeur->name,
                        'quantite' => $assignation->quantite_recue,
                        'status' => $assignation->status,
                        'date_assignation' => $assignation->created_at
                    ];
                    
                    $quantite_assignee_total += $assignation->quantite_recue;
                }
            }
            
            $valeur_assignee_total = $quantite_assignee_total * $premiereProd->prix_unitaire;
            
            // Calculer les manquants
            $manquant_production_reception = max(0, $quantite_produite - $quantite_recue_total);
            $manquant_reception_assignation = max(0, $quantite_recue_total - $quantite_assignee_total);
            
            $valeur_manquant_prod_rec = $manquant_production_reception * $premiereProd->prix_unitaire;
            $valeur_manquant_rec_ass = $manquant_reception_assignation * $premiereProd->prix_unitaire;
            
            // Déterminer le statut du flux
            $statut = $this->determinerStatutFlux(
                $quantite_produite, 
                $quantite_recue_total, 
                $quantite_assignee_total
            );
            
            $flux[] = [
                'produit_id' => $produit_id,
                'nom_produit' => $premiereProd->nom_produit,
                'prix_unitaire' => $premiereProd->prix_unitaire,
                'producteur' => [
                    'id' => $producteur_id,
                    'nom' => $premiereProd->producteur_nom
                ],
                'production' => [
                    'lots' => $productionGroupe->pluck('id_lot')->toArray(),
                    'quantite' => $quantite_produite,
                    'valeur' => $valeur_produite,
                    'date' => $premiereProd->date_production
                ],
                'reception' => [
                    'quantite' => $quantite_recue_total,
                    'valeur' => $valeur_recue_total,
                    'pointeurs' => $receptionsCorrespondantes->pluck('pointeur.name')->unique()->values()->toArray()
                ],
                'assignation' => [
                    'quantite' => $quantite_assignee_total,
                    'valeur' => $valeur_assignee_total,
                    'details' => $assignationsData
                ],
                'manquants' => [
                    'production_reception' => [
                        'quantite' => $manquant_production_reception,
                        'valeur' => $valeur_manquant_prod_rec,
                        'pourcentage' => $quantite_produite > 0 ? round(($manquant_production_reception / $quantite_produite) * 100, 2) : 0
                    ],
                    'reception_assignation' => [
                        'quantite' => $manquant_reception_assignation,
                        'valeur' => $valeur_manquant_rec_ass,
                        'pourcentage' => $quantite_recue_total > 0 ? round(($manquant_reception_assignation / $quantite_recue_total) * 100, 2) : 0
                    ]
                ],
                'statut' => $statut,
                'alerte_niveau' => $this->calculerNiveauAlerte($manquant_production_reception, $manquant_reception_assignation, $quantite_produite)
            ];
        }
        
        return collect($flux)->sortByDesc('production.valeur');
    }
    
    /**
     * Déterminer le statut du flux
     */
    private function determinerStatutFlux($quantite_produite, $quantite_recue, $quantite_assignee)
    {
        if ($quantite_assignee == $quantite_produite) {
            return 'complet';
        } elseif ($quantite_recue < $quantite_produite) {
            return 'manquant_reception';
        } elseif ($quantite_assignee < $quantite_recue) {
            return 'manquant_assignation';
        } else {
            return 'en_cours';
        }
    }
    
    /**
     * Calculer le niveau d'alerte
     */
    private function calculerNiveauAlerte($manquant_prod_rec, $manquant_rec_ass, $quantite_totale)
    {
        $pourcentage_manquant = (($manquant_prod_rec + $manquant_rec_ass) / $quantite_totale) * 100;
        
        if ($pourcentage_manquant >= 20) {
            return 'critique';
        } elseif ($pourcentage_manquant >= 10) {
            return 'important';
        } elseif ($pourcentage_manquant > 0) {
            return 'mineur';
        } else {
            return 'normal';
        }
    }
    
    /**
     * Calculer les statistiques générales
     */
    private function calculateFluxStats($date)
    {
        $dateDebut = Carbon::parse($date)->startOfDay();
        $dateFin = Carbon::parse($date)->endOfDay();
        
        return [
            'total_productions' => Utilisation::whereBetween('created_at', [$dateDebut, $dateFin])
                ->distinct('id_lot')->count(),
            'total_receptions' => ProduitRecu1::whereBetween('date_reception', [$dateDebut, $dateFin])->count(),
            'total_assignations' => ProduitRecuVendeur::whereHas('produitRecu', function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_reception', [$dateDebut, $dateFin]);
            })->count(),
            'valeur_totale_produite' => $this->calculateTotalValue('production', $dateDebut, $dateFin),
            'valeur_totale_recue' => $this->calculateTotalValue('reception', $dateDebut, $dateFin),
            'valeur_totale_assignee' => $this->calculateTotalValue('assignation', $dateDebut, $dateFin)
        ];
    }
    
    /**
     * Calculer la valeur totale pour un type donné
     */
    private function calculateTotalValue($type, $dateDebut, $dateFin)
    {
        switch ($type) {
            case 'production':
                return DB::table('Utilisation as u')
                    ->join('Produit_fixes as pf', 'u.produit', '=', 'pf.code_produit')
                    ->whereBetween('u.created_at', [$dateDebut, $dateFin])
                    ->sum(DB::raw('u.quantite_produit * pf.prix'));
                    
            case 'reception':
                return DB::table('produits_recu_1 as pr')
                    ->join('Produit_fixes as pf', 'pr.produit_id', '=', 'pf.code_produit')
                    ->whereBetween('pr.date_reception', [$dateDebut, $dateFin])
                    ->sum(DB::raw('pr.quantite * pf.prix'));
                    
            case 'assignation':
                return DB::table('produits_recu_vendeur as prv')
                    ->join('produits_recu_1 as pr', 'prv.produit_recu_id', '=', 'pr.id')
                    ->join('Produit_fixes as pf', 'pr.produit_id', '=', 'pf.code_produit')
                    ->whereBetween('pr.date_reception', [$dateDebut, $dateFin])
                    ->sum(DB::raw('prv.quantite_recue * pf.prix'));
                    
            default:
                return 0;
        }
    }
    
    /**
     * Détecter les anomalies
     */
    private function detectAnomalies($date)
    {
        $anomalies = [];
        $dateDebut = Carbon::parse($date)->startOfDay();
        $dateFin = Carbon::parse($date)->endOfDay();
        
        // Anomalie 1: Produits non assignés depuis plus de 2 heures
        $produitsNonAssignes = ProduitRecu1::with(['produit', 'pointeur'])
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->whereDoesntHave('produitRecuVendeur')
            ->where('date_reception', '<', Carbon::now()->subHours(2))
            ->get();
            
        foreach ($produitsNonAssignes as $produit) {
            $anomalies[] = [
                'type' => 'produit_non_assigne',
                'niveau' => 'important',
                'message' => "Produit {$produit->produit->nom} (ID: {$produit->id}) reçu par {$produit->pointeur->name} non assigné depuis " . $produit->date_reception,
                'action_requise' => 'Assigner à un vendeur',
                'responsable' => $produit->pointeur->name,
                'data' => $produit
            ];
        }
        
        // Anomalie 2: Productions non déclarées
        $productionsNonDeclarees = DB::table('Utilisation as u')
            ->join('Produit_fixes as pf', 'u.produit', '=', 'pf.code_produit')
            ->join('users as prod', 'u.producteur', '=', 'prod.id')
            ->leftJoin('produits_recu_1 as pr', function($join) {
                $join->on('u.produit', '=', 'pr.produit_id')
                     ->on('u.producteur', '=', 'pr.producteur_id');
            })
            ->select(
                'u.id_lot',
                'pf.nom as nom_produit',
                'prod.name as producteur_nom',
                DB::raw('SUM(u.quantite_produit) as quantite_produite'),
                DB::raw('COALESCE(SUM(pr.quantite), 0) as quantite_recue')
            )
            ->whereBetween('u.created_at', [$dateDebut, $dateFin])
            ->groupBy('u.id_lot', 'pf.nom', 'prod.name')
            ->havingRaw('SUM(u.quantite_produit) > COALESCE(SUM(pr.quantite), 0)')
            ->get();
            
        foreach ($productionsNonDeclarees as $production) {
            $difference = $production->quantite_produite - $production->quantite_recue;
            $anomalies[] = [
                'type' => 'production_non_declaree',
                'niveau' => 'critique',
                'message' => "Production {$production->nom_produit} (Lot: {$production->id_lot}) par {$production->producteur_nom}: {$difference} unités non déclarées",
                'action_requise' => 'Vérifier avec le pointeur',
                'responsable' => 'Pointeur',
                'data' => $production
            ];
        }
        
        return collect($anomalies)->sortByDesc('niveau');
    }
    
   public function calculerManquantsAuto(Request $request)
    {
        try {
            $user = Auth::user();
          
            // Récupérer la date - par défaut hier
            $date = $request->get('date', Carbon::yesterday()->format('Y-m-d'));
            
            // Validation de la date
            try {
                $dateCarbon = Carbon::parse($date);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format de date invalide'
                ], 400);
            }
            
            // Récupérer tous les pointeurs
            $pointeurs = User::where('role', 'pointeur')->get();
            
            if ($pointeurs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun pointeur trouvé dans le système'
                ], 404);
            }
            
            $manquantsTotaux = [];
            $totalGeneral = 0;
            
            foreach ($pointeurs as $pointeur) {
                try {
                    $manquants = $this->calculatePointeurManquants($pointeur->id, $date);
                    
                    if ($manquants['total'] > 0) {
                        // Créer ou mettre à jour l'enregistrement de manquant temporaire
                        ManquantTemporaire::updateOrCreate(
                            [
                                'employe_id' => $pointeur->id,
                                'created_at' => $dateCarbon->startOfDay()
                            ],
                            [
                                'montant' => $manquants['total'],
                                'explication' => $this->genererExplicationManquant($manquants),
                                'statut' => 'en_attente'
                            ]
                        );
                        
                        $manquantsTotaux[$pointeur->name] = $manquants;
                        $totalGeneral += $manquants['total'];
                        
                        // Envoyer les notifications
                        $this->envoyerNotificationsManquants($pointeur->id, $manquants, $date);
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur calcul manquants pour pointeur {$pointeur->id}: " . $e->getMessage());
                    continue;
                }
            }
            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a calculé les manquants pour la date {$date}", 'calculate_missing');

            return response()->json([
                'success' => true,
                'manquants' => $manquantsTotaux,
                'total_general' => $totalGeneral,
                'nombre_pointeurs' => count($manquantsTotaux),
                'message' => 'Calcul des manquants effectué avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erreur générale calcul manquants: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des manquants: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    /**
     * Générer l'explication détaillée d'un manquant
     */
    private function genererExplicationManquant($manquants)
    {
        $explication = "Calcul automatique des manquants:\n\n";
        
        if (!empty($manquants['production_reception'])) {
            $explication .= "1. Responsabilité partagée (50%):\n";
            foreach ($manquants['production_reception'] as $pr) {
                $explication .= "- {$pr['produit']} ({$pr['producteur']}): {$pr['difference']} unités non déclarées = {$pr['valeur_manquant']} FCFA\n";
            }
            $explication .= "\n";
        }
        
        if (!empty($manquants['reception_assignation'])) {
            $explication .= "2. Responsabilité complète (100%):\n";
            foreach ($manquants['reception_assignation'] as $ra) {
                $explication .= "- {$ra['produit']}: {$ra['difference']} unités non assignées = {$ra['valeur_manquant']} FCFA\n";
            }
        }
        
        $explication .= "\nTotal: {$manquants['total']} FCFA";
        
        return $explication;
    }
    
    /**
     * Gestion des anomalies
     */
    public function gererAnomalies(Request $request)
    {
        $user = Auth::user();
        $isFrench = session('locale', 'fr') === 'fr';
        
        // Vérifier les autorisations
        if (!in_array($user->role, ['dg', 'pdg', 'chef_production'])) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }
        
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $produit_id = $request->get('produit_id');
        $producteur_id = $request->get('producteur_id');
        
        // Détecter toutes les anomalies
        $anomalies = $this->detectAnomalies($date);
        
        // Filtrer si des paramètres spécifiques sont fournis
        if ($produit_id && $producteur_id) {
            $anomalies = $anomalies->filter(function($anomalie) use ($produit_id, $producteur_id) {
                return isset($anomalie['data']) && 
                       (($anomalie['data']->produit_id ?? null) == $produit_id || 
                        ($anomalie['data']->produit ?? null) == $produit_id) &&
                       (($anomalie['data']->producteur_id ?? null) == $producteur_id ||
                        ($anomalie['data']->producteur ?? null) == $producteur_id);
            });
        }
        
        return view('flux-produit.anomalies', compact('anomalies', 'isFrench'));
    }
    
    /**
     * Résoudre une anomalie
     */
    public function resoudreAnomalie(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les autorisations
        if (!in_array($user->role, ['dg', 'pdg', 'chef_production'])) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }
        
        // Pour cette démo, on marque simplement comme résolu
        // Dans une implémentation complète, on aurait une table pour tracker les anomalies
        
        return response()->json([
            'success' => true,
            'message' => 'Anomalie marquée comme résolue'
        ]);
    }
    
    /**
     * Export des rapports
     */
    public function exportRapport(Request $request)
    {
        $user = Auth::user();
        $format = $request->get('format', 'pdf');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        // Vérifier les autorisations
        if (!in_array($user->role, ['dg', 'pdg', 'chef_production'])) {
            return redirect()->back()->with('error', 'Accès non autorisé');
        }
        
        // Récupérer toutes les données nécessaires
        $fluxData = $this->getFluxData($date);
        $stats = $this->calculateFluxStats($date);
        $anomalies = $this->detectAnomalies($date);
        
        if ($format === 'pdf') {
            return $this->exportPDF($fluxData, $stats, $anomalies, $date);
        } elseif ($format === 'excel') {
            return $this->exportExcel($fluxData, $stats, $anomalies, $date);
        }
        
        return redirect()->back()->with('error', 'Format non supporté');
    }
    
    /**
     * Export PDF
     */
    private function exportPDF($fluxData, $stats, $anomalies, $date)
    {
        $isFrench = session('locale', 'fr') === 'fr';
        
        // Créer le contenu HTML pour le PDF
        $html = view('flux-produit.pdf-export', compact(
            'fluxData', 'stats', 'anomalies', 'date', 'isFrench'
        ))->render();
        
        // Pour cette démo, on retourne le HTML
        // Dans une vraie implémentation, on utiliserait une lib comme DomPDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="rapport-flux-'.$date.'.html"');
    }
    
    /**
     * Export Excel
     */
    private function exportExcel($fluxData, $stats, $anomalies, $date)
{
    // Pour cette démo, on crée un CSV
    $csv = "Rapport Flux de Produits - {$date}\n\n";
    $csv .= "Produit,Producteur,Production,Reception,Assignation,Manquants\n";
    
    foreach ($fluxData as $flux) {
        $csv .= "\"{$flux['nom_produit']}\",";
        $csv .= "\"{$flux['producteur']['nom']}\",";
        $csv .= "{$flux['production']['quantite']},";
        $csv .= "{$flux['reception']['quantite']},";
        $csv .= "{$flux['assignation']['quantite']},";
        
        // Calculer la somme des manquants dans une variable séparée
        $totalManquants = $flux['manquants']['production_reception']['valeur'] + $flux['manquants']['reception_assignation']['valeur'];
        $csv .= "{$totalManquants}\n";
    }
    
    return response($csv)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="rapport-flux-'.$date.'.csv"');
}


    /**
     * Calculer automatiquement les manquants pour un pointeur
     */
    public function calculerManquantsPointeur(Request $request)
    {
        $pointeur_id = $request->get('pointeur_id');
        $date = $request->get('date', Carbon::yesterday()->format('Y-m-d'));
        
        if (!$pointeur_id) {
            return response()->json([
                'success' => false,
                'message' => 'ID du pointeur requis'
            ], 400);
        }
        
        $manquants = $this->calculatePointeurManquants($pointeur_id, $date);
        
        // Envoyer les notifications si des manquants sont détectés
        if ($manquants['total'] > 0) {
            $this->envoyerNotificationsManquants($pointeur_id, $manquants, $date);
        }
        
        return response()->json([
            'success' => true,
            'manquants' => $manquants,
            'message' => 'Calcul des manquants effectué'
        ]);
    }

   
    /**
     * Calculer les manquants d'un pointeur selon la formule
     */
    private function calculatePointeurManquants($pointeur_id, $date)
    {
        $dateDebut = Carbon::parse($date)->startOfDay();
        $dateFin = Carbon::parse($date)->endOfDay();
        
        $manquants = [
            'production_reception' => [],
            'reception_assignation' => [],
            'total' => 0,
            'details' => []
        ];
        
        try {
            // Partie 1: (valeur produits par le producteur - valeur déclarée par le pointeur)/2
            $productionsReceptions = DB::table('Utilisation as u')
                ->join('Produit_fixes as pf', 'u.produit', '=', 'pf.code_produit')
                ->join('users as prod', 'u.producteur', '=', 'prod.id')
                ->leftJoin('produits_recu_1 as pr', function($join) use ($pointeur_id) {
                    $join->on('u.produit', '=', 'pr.produit_id')
                         ->on('u.producteur', '=', 'pr.producteur_id')
                         ->where('pr.pointeur_id', '=', $pointeur_id);
                })
                ->select(
                    'u.produit',
                    'pf.nom as nom_produit',
                    'pf.prix',
                    'prod.name as producteur_nom',
                    'u.producteur as producteur_id',
                    DB::raw('SUM(u.quantite_produit) as quantite_produite'),
                    DB::raw('COALESCE(SUM(pr.quantite), 0) as quantite_declaree')
                )
                ->whereBetween('u.created_at', [$dateDebut, $dateFin])
                ->groupBy('u.produit', 'pf.nom', 'pf.prix', 'prod.name', 'u.producteur')
                ->get();
                
            foreach ($productionsReceptions as $pr) {
                if ($pr->quantite_produite > $pr->quantite_declaree) {
                    $difference = $pr->quantite_produite - $pr->quantite_declaree;
                    $valeur_difference = $difference * $pr->prix;
                    $manquant_pointeur = $valeur_difference / 2; // Responsabilité partagée
                    
                    $manquants['production_reception'][] = [
                        'produit' => $pr->nom_produit,
                        'producteur' => $pr->producteur_nom,
                        'producteur_id' => $pr->producteur_id,
                        'quantite_produite' => $pr->quantite_produite,
                        'quantite_declaree' => $pr->quantite_declaree,
                        'difference' => $difference,
                        'prix_unitaire' => $pr->prix,
                        'valeur_manquant' => $manquant_pointeur,
                        'explication' => "Responsabilité partagée (50%) sur {$difference} unités non déclarées"
                    ];
                    
                    $manquants['total'] += $manquant_pointeur;
                }
            }
            
            // Partie 2: (valeur déclarée par le pointeur - valeur reçue par le vendeur)
            $receptionsAssignations = ProduitRecu1::with(['produit'])
                ->where('pointeur_id', $pointeur_id)
                ->whereBetween('date_reception', [$dateDebut, $dateFin])
                ->get();
                
            foreach ($receptionsAssignations as $reception) {
                $quantite_assignee = ProduitRecuVendeur::where('produit_recu_id', $reception->id)
                    ->sum('quantite_confirmee');
                    
                if ($reception->quantite > $quantite_assignee) {
                    $difference = $reception->quantite - $quantite_assignee;
                    $valeur_manquant = $difference * $reception->produit->prix;
                    
                    $manquants['reception_assignation'][] = [
                        'produit' => $reception->produit->nom,
                        'produit_id' => $reception->produit_id,
                        'quantite_recue' => $reception->quantite,
                        'quantite_assignee' => $quantite_assignee,
                        'difference' => $difference,
                        'prix_unitaire' => $reception->produit->prix,
                        'valeur_manquant' => $valeur_manquant,
                        'explication' => "Responsabilité complète sur {$difference} unités non assignées"
                    ];
                    
                    $manquants['total'] += $valeur_manquant;
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Erreur calcul manquants pointeur {$pointeur_id}: " . $e->getMessage());
            throw $e;
        }
        
        return $manquants;
    }
    
    
    /**
     * Envoyer les notifications de manquants
     */
    private function envoyerNotificationsManquants($pointeur_id, $manquants, $date)
    {
        $pointeur = User::find($pointeur_id);
        $isFrench = session('locale', 'fr') === 'fr';
        
        // Notification au DG
        $dg = User::where('role', 'dg')->first();
        if ($dg) {
            $request = new Request();
            $request->merge([
                'recipient_id' => $dg->id,
                'subject' => $isFrench ? 'Manquants détectés - Pointeur' : 'Missing items detected - Pointer',
                'message' => $isFrench 
                    ? "Manquants détectés pour le pointeur {$pointeur->name} le {$date}. Montant total: {$manquants['total']} FCFA"
                    : "Missing items detected for pointer {$pointeur->name} on {$date}. Total amount: {$manquants['total']} FCFA"
            ]);
            $this->notificationController->send($request);
        }
        
        // Notification au Chef de Production
        $cp = User::where('role', 'chef_production')->first();
        if ($cp) {
            $request = new Request();
            $request->merge([
                'recipient_id' => $cp->id,
                'subject' => $isFrench ? 'Manquants détectés - Pointeur' : 'Missing items detected - Pointer',
                'message' => $isFrench 
                    ? "Manquants détectés pour le pointeur {$pointeur->name} le {$date}. Montant total: {$manquants['total']} FCFA"
                    : "Missing items detected for pointer {$pointeur->name} on {$date}. Total amount: {$manquants['total']} FCFA"
            ]);
            $this->notificationController->send($request);
        }
    }
    
    /**
     * Afficher les détails d'un flux spécifique
     */
    public function detailsFlux(Request $request)
    {
        $produit_id = $request->get('produit_id');
        $producteur_id = $request->get('producteur_id');
        $date = $request->get('date');
        
        if (!$produit_id || !$producteur_id || !$date) {
            return response()->json(['error' => 'Paramètres manquants'], 400);
        }
        
        $details = $this->getDetailedFluxData($produit_id, $producteur_id, $date);
        
        return response()->json($details);
    }
    
    /**
     * Récupérer les détails complets d'un flux
     */
    private function getDetailedFluxData($produit_id, $producteur_id, $date)
    {
        $dateDebut = Carbon::parse($date)->startOfDay();
        $dateFin = Carbon::parse($date)->endOfDay();
        
        // Détails de production
        $productions = Utilisation::with(['produitFixe', 'matierePremiere'])
            ->where('produit', $produit_id)
            ->where('producteur', $producteur_id)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get()
            ->groupBy('id_lot');
        
        // Détails de réception
        $receptions = ProduitRecu1::with(['pointeur'])
            ->where('produit_id', $produit_id)
            ->where('producteur_id', $producteur_id)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->get();
        
        // Détails d'assignation
        $assignations = ProduitRecuVendeur::with(['vendeur'])
            ->whereIn('produit_recu_id', $receptions->pluck('id'))
            ->get();
        
        return [
            'productions' => $productions,
            'receptions' => $receptions,
            'assignations' => $assignations,
            'chronologie' => $this->buildChronologie($productions->flatten(), $receptions, $assignations)
        ];
    }
    
    /**
     * Construire la chronologie des événements
     */
        private function buildChronologie($productions, $receptions, $assignations)
    {
        $events = [];
        
        // Événements de production
        foreach ($productions as $production) {
            $events[] = [
                'type' => 'production',
                'timestamp' => $production->created_at,
                'description' => "Production de {$production->quantite_produit} unités (Lot: {$production->id_lot})",
                'data' => $production
            ];
        }
        
        // Événements de réception
        foreach ($receptions as $reception) {
            $events[] = [
                'type' => 'reception',
                'timestamp' => $reception->date_reception,
                'description' => "Réception de {$reception->quantite} unités par {$reception->pointeur->name}",
                'data' => $reception
            ];
        }
        
        // Événements d'assignation
        foreach ($assignations as $assignation) {
            $events[] = [
                'type' => 'assignation',
                'timestamp' => $assignation->created_at,
                'description' => "Assignation de {$assignation->quantite_recue} unités à {$assignation->vendeur->name}",
                'data' => $assignation
            ];
        }
        
        return collect($events)->sortBy('timestamp');
        try {
            $pointeur = User::find($pointeur_id);
            $isFrench = session('locale', 'fr') === 'fr';
            
            // Notification au DG
            $dg = User::where('role', 'dg')->first();
            if ($dg) {
                $request = new Request();
                $request->merge([
                    'recipient_id' => $dg->id,
                    'subject' => $isFrench ? 'Manquants détectés - Pointeur' : 'Missing items detected - Pointer',
                    'message' => $isFrench 
                        ? "Manquants détectés pour le pointeur {$pointeur->name} le {$date}. Montant total: " . number_format($manquants['total']) . " FCFA"
                        : "Missing items detected for pointer {$pointeur->name} on {$date}. Total amount: " . number_format($manquants['total']) . " FCFA"
                ]);
                $this->notificationController->send($request);
            }
            
            // Notification au Chef de Production
            $cp = User::where('role', 'chef_production')->first();
            if ($cp) {
                $request = new Request();
                $request->merge([
                    'recipient_id' => $cp->id,
                    'subject' => $isFrench ? 'Manquants détectés - Pointeur' : 'Missing items detected - Pointer',
                    'message' => $isFrench 
                        ? "Manquants détectés pour le pointeur {$pointeur->name} le {$date}. Montant total: " . number_format($manquants['total']) . " FCFA"
                        : "Missing items detected for pointer {$pointeur->name} on {$date}. Total amount: " . number_format($manquants['total']) . " FCFA"
                ]);
                $this->notificationController->send($request);
            }
            
        } catch (\Exception $e) {
            Log::error("Erreur envoi notifications manquants: " . $e->getMessage());
        }
    }
}
