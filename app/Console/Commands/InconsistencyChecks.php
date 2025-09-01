<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Matiere;
use App\Models\TransactionVente;
use App\Models\ProduitRecu;
use App\Models\CashierSession;
use App\Models\CashDistribution;
use App\Models\ListenerLog;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Services\UniteConversionService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InconsistencyChecks extends Command
{
    protected $signature = 'checks:inconsistency {--type=all}';
    protected $description = 'Check for various inconsistencies in the system';

    protected $notificationController;
    protected $messageController;
    protected $conversionService;

    public function __construct(NotificationController $notificationController, MessageController $messageController, UniteConversionService $conversionService)
    {
        parent::__construct();
        $this->notificationController = $notificationController;
        $this->messageController = $messageController;
        $this->conversionService = $conversionService;
    }

    public function handle()
    {
        $startTime = microtime(true);
        $type = $this->option('type');
        
        Log::info("InconsistencyChecks: Début des vérifications d'incohérence - Type: $type");

        try {
            if ($type === 'all') {
                return $this->runAllChecks($startTime);
            }

            $method = 'check' . ucfirst(str_replace('-', '', $type));
            
            if (!method_exists($this, $method)) {
                $message = "Méthode $method non trouvée pour le type $type";
                Log::error("InconsistencyChecks: $message");
                $this->logExecution('failed', $message, [], $startTime);
                return 1;
            }

            return $this->$method($startTime);
        } catch (\Exception $e) {
            $message = "Erreur lors des vérifications d'incohérence: " . $e->getMessage();
            Log::error("InconsistencyChecks: $message");
            $this->logExecution('failed', $message, ['error' => $e->getTraceAsString()], $startTime);
            return 1;
        }
    }

    private function runAllChecks($startTime)
    {
        $checks = [
            'unsoldCalculation',
            'productionQuantityDiscrepancy',
            'salesQuantityDiscrepancy',
            'missingAmounts',
            'openSessions',
            'duplicateMaterials',
            'duplicateProducts',
            'unprofitableProduction'
        ];

        $results = [];
        $totalErrors = 0;

        foreach ($checks as $check) {
            try {
                $result = $this->{'check' . ucfirst($check)}($startTime);
                $results[$check] = $result === 0 ? 'success' : 'failed';
                if ($result !== 0) $totalErrors++;
            } catch (\Exception $e) {
                $results[$check] = 'error';
                $totalErrors++;
                Log::error("InconsistencyChecks: Erreur dans $check: " . $e->getMessage());
            }
        }

        $message = "Toutes les vérifications terminées. Erreurs: $totalErrors/" . count($checks);
        Log::info("InconsistencyChecks: $message");
        $this->logExecution($totalErrors > 0 ? 'partial' : 'success', $message, $results, $startTime);

        return $totalErrors > 0 ? 1 : 0;
    }

    private function checkUnsoldCalculation($startTime)
    {
        Log::info("InconsistencyChecks: Calcul automatique des invendus de la journée précédente");
        return 0;
    }

    private function checkProductionQuantityDiscrepancy($startTime)
{
    Log::info("InconsistencyChecks: Vérification des écarts de quantité de production");
    $yesterday = Carbon::yesterday('Africa/Douala')->toDateString();
    $discrepancies = [];

    // Récupérer les productions par lot (une seule ligne par lot)
    $productions = DB::table('Utilisation as u')
        ->where(DB::raw('DATE(u.created_at)'), $yesterday)
        ->select('u.id_lot', 'u.produit', 'u.producteur', 
                DB::raw('MAX(u.quantite_produit) as quantite_produite'))
        ->groupBy('u.id_lot', 'u.produit', 'u.producteur')
        ->get();

    foreach ($productions as $prod) {
        // Récupérer la quantité reçue par le pointeur pour ce lot
        $receptionData = DB::table('produits_recu_1')
            ->where('produit_id', $prod->produit)
            ->where('producteur_id', $prod->producteur)
            ->where(DB::raw('DATE(date_reception)'), $yesterday)
            ->select('quantite as quantite_recue', 'pointeur_id')
            ->first();

        if ($receptionData) {
            $ecart = abs($prod->quantite_produite - $receptionData->quantite_recue);
            $ecartValeur = $ecart * DB::table('Produit_fixes')
                ->where('code_produit', $prod->produit)
                ->value('prix');

            if ($ecartValeur > 1000) {
                $discrepancies[] = [
                    'id_lot' => $prod->id_lot,
                    'produit' => $prod->produit,
                    'producteur' => $prod->producteur,
                    'pointeur' => $receptionData->pointeur_id,
                    'quantite_produite' => $prod->quantite_produite,
                    'quantite_recue' => $receptionData->quantite_recue,
                    'ecart_valeur' => $ecartValeur
                ];

                $this->notifyManagers("Écart de production détecté pour le lot {$prod->id_lot}. Écart: {$ecartValeur} XAF");
            }
        }
    }

    $message = "Vérification des écarts de production: " . count($discrepancies) . " écart(s) détecté(s)";
    Log::info("InconsistencyChecks: $message");
    $this->logExecution('success', $message, $discrepancies, $startTime);
    return 0;
}

  private function checkSalesQuantityDiscrepancy($startTime)
{
    Log::info("InconsistencyChecks: Vérification des écarts de quantité de vente");
    $yesterday = Carbon::yesterday('Africa/Douala')->toDateString();
    $discrepancies = [];

    // Récupérer les réceptions avec confirmations vendeur
    $receptions = DB::table('produits_recu_1 as pr1')
        ->join('produits_recu_vendeur as prv', 'pr1.id', '=', 'prv.produit_recu_id')
        ->where(DB::raw('DATE(pr1.date_reception)'), $yesterday)
        ->where('prv.status', 'confirmé')
        ->whereNotNull('prv.quantite_confirmee') // Gérer les valeurs nulles
        ->select(
            'pr1.id as reception_id',
            'pr1.produit_id', 
            'pr1.quantite as quantite_pointeur',
            'prv.quantite_confirmee as quantite_vendeur', 
            'prv.vendeur_id', 
            'pr1.pointeur_id'
        )
        ->get();

    // Grouper par réception si plusieurs vendeurs pour le même produit
    $receptionsGrouped = $receptions->groupBy('reception_id');

    foreach ($receptionsGrouped as $receptionId => $receptionGroup) {
        $reception = $receptionGroup->first();
        
        // Si plusieurs vendeurs, prendre la somme des quantités confirmées
        $quantiteTotalVendeur = $receptionGroup->sum('quantite_confirmee');
        
        // Calculer l'écart (dans les deux sens)
        $ecart = abs($reception->quantite_pointeur - $quantiteTotalVendeur);
        
        if ($ecart > 0) {
            $ecartValeur = $ecart * DB::table('Produit_fixes')
                ->where('code_produit', $reception->produit_id)
                ->value('prix');

            if ($ecartValeur > 1000) {
                // Déterminer le type d'écart
                $typeEcart = $reception->quantite_pointeur > $quantiteTotalVendeur 
                    ? 'surplus_pointeur' 
                    : 'surplus_vendeur';

                $discrepancies[] = [
                    'reception_id' => $receptionId,
                    'produit_id' => $reception->produit_id,
                    'pointeur_id' => $reception->pointeur_id,
                    'vendeurs' => $receptionGroup->pluck('vendeur_id')->toArray(),
                    'quantite_pointeur' => $reception->quantite_pointeur,
                    'quantite_vendeur_total' => $quantiteTotalVendeur,
                    'ecart_valeur' => $ecartValeur,
                    'type_ecart' => $typeEcart
                ];

                // Message plus précis selon le type d'écart
                $messageType = $typeEcart === 'surplus_pointeur' 
                    ? "Pointeur a reçu plus que vendeur confirmé" 
                    : "Vendeur a confirmé plus que pointeur reçu";
                
                $this->notifyManagers("Écart de réception détecté pour le produit {$reception->produit_id}. {$messageType}. Écart: {$ecartValeur} XAF");
            }
        }
    }

    $message = "Vérification des écarts de vente: " . count($discrepancies) . " écart(s) détecté(s)";
    Log::info("InconsistencyChecks: $message");
    $this->logExecution('success', $message, $discrepancies, $startTime);
    return 0;
}

    private function checkMissingAmounts($startTime)
    {
        Log::info("InconsistencyChecks: Calcul automatique des manquants");
   
    Log::info("InconsistencyChecks: Calcul des manquants des pointeurs");
    $yesterday = Carbon::yesterday('Africa/Douala')->toDateString();
    $pointeurManquants = [];
    $notifications = [];

    // 1. Récupérer toutes les productions de la veille
    $productions = DB::table('Utilisation as u')
        ->where(DB::raw('DATE(u.created_at)'), $yesterday)
        ->select('u.id_lot', 'u.produit', 'u.producteur', 
                DB::raw('MAX(u.quantite_produit) as quantite_produite'))
        ->groupBy('u.id_lot', 'u.produit', 'u.producteur')
        ->get();

    // 2. Vérifier les productions non reçues par les pointeurs
    foreach ($productions as $production) {
        $receptionPointeur = DB::table('produits_recu_1')
            ->where('produit_id', $production->produit)
            ->where('producteur_id', $production->producteur)
            ->where(DB::raw('DATE(date_reception)'), $yesterday)
            ->first();

        if (!$receptionPointeur) {
            // Aucun pointeur n'a reçu ce produit - NOTIFICATION
            $prixUnitaire = DB::table('Produit_fixes')
                ->where('code_produit', $production->produit)
                ->value('prix');
            
            $valeurProduit = $production->quantite_produite * $prixUnitaire;
            
            $notifications[] = [
                'type' => 'produit_non_recu',
                'id_lot' => $production->id_lot,
                'produit' => $production->produit,
                'producteur' => $production->producteur,
                'quantite_produite' => $production->quantite_produite,
                'valeur_produit' => $valeurProduit
            ];
            
            $this->notifyManagers("ALERTE: Produit non reçu par aucun pointeur - Lot: {$production->id_lot}, Produit: {$production->produit}, Valeur: {$valeurProduit} XAF");
        }
    }

    // 3. Calculer les manquants pour chaque pointeur
    $pointeurs = DB::table('produits_recu_1')
        ->where(DB::raw('DATE(date_reception)'), $yesterday)
        ->select('pointeur_id')
        ->distinct()
        ->get();

    foreach ($pointeurs as $pointeur) {
        $manquantTotal = 0;
        $detailsManquants = [];

        // 3.1 Première partie: Écarts production vs réception pointeur (50% du manquant)
        $receptionsPointeur = DB::table('produits_recu_1 as pr1')
            ->join('Utilisation as u', function($join) use ($yesterday) {
                $join->on('pr1.produit_id', '=', 'u.produit')
                     ->on('pr1.producteur_id', '=', 'u.producteur')
                     ->where(DB::raw('DATE(u.created_at)'), $yesterday);
            })
            ->where('pr1.pointeur_id', $pointeur->pointeur_id)
            ->where(DB::raw('DATE(pr1.date_reception)'), $yesterday)
            ->select('pr1.produit_id', 'pr1.producteur_id', 'pr1.quantite as quantite_recue_pointeur',
                    DB::raw('MAX(u.quantite_produit) as quantite_produite'))
            ->groupBy('pr1.produit_id', 'pr1.producteur_id', 'pr1.quantite')
            ->get();

        foreach ($receptionsPointeur as $reception) {
            if ($reception->quantite_produite > $reception->quantite_recue_pointeur) {
                $ecart = $reception->quantite_produite - $reception->quantite_recue_pointeur;
                $prixUnitaire = DB::table('Produit_fixes')
                    ->where('code_produit', $reception->produit_id)
                    ->value('prix');
                
                $valeurEcart = $ecart * $prixUnitaire;
                $manquantPartiel = $valeurEcart * 0.5; // 50% du manquant
                $manquantTotal += $manquantPartiel;
                
                $detailsManquants[] = [
                    'type' => 'ecart_production_reception',
                    'produit' => $reception->produit_id,
                    'producteur' => $reception->producteur_id,
                    'quantite_produite' => $reception->quantite_produite,
                    'quantite_recue' => $reception->quantite_recue_pointeur,
                    'ecart' => $ecart,
                    'valeur_ecart' => $valeurEcart,
                    'manquant_partiel' => $manquantPartiel
                ];
            }
        }

        // 3.2 Deuxième partie: Écarts réception pointeur vs confirmation vendeur (100% du manquant)
        $ventesPointeur = DB::table('produits_recu_1 as pr1')
            ->join('produits_recu_vendeur as prv', 'pr1.id', '=', 'prv.produit_recu_id')
            ->where('pr1.pointeur_id', $pointeur->pointeur_id)
            ->where(DB::raw('DATE(pr1.date_reception)'), $yesterday)
            ->where('prv.status', 'confirmé')
            ->whereNotNull('prv.quantite_confirmee')
            ->select('pr1.id as reception_id', 'pr1.produit_id', 'pr1.quantite as quantite_pointeur',
                    'prv.quantite_confirmee', 'prv.vendeur_id')
            ->get();

        // Grouper par réception pour gérer plusieurs vendeurs
        $ventesGrouped = $ventesPointeur->groupBy('reception_id');
        
        foreach ($ventesGrouped as $receptionId => $ventes) {
            $reception = $ventes->first();
            $quantiteTotalVendeur = $ventes->sum('quantite_confirmee');
            
            if ($reception->quantite_pointeur > $quantiteTotalVendeur) {
                $ecart = $reception->quantite_pointeur - $quantiteTotalVendeur;
                $prixUnitaire = DB::table('Produit_fixes')
                    ->where('code_produit', $reception->produit_id)
                    ->value('prix');
                
                $valeurEcart = $ecart * $prixUnitaire;
                $manquantTotal += $valeurEcart; // 100% du manquant
                
                $detailsManquants[] = [
                    'type' => 'ecart_pointeur_vendeur',
                    'reception_id' => $receptionId,
                    'produit' => $reception->produit_id,
                    'quantite_pointeur' => $reception->quantite_pointeur,
                    'quantite_vendeur_total' => $quantiteTotalVendeur,
                    'ecart' => $ecart,
                    'valeur_ecart' => $valeurEcart,
                    'vendeurs' => $ventes->pluck('vendeur_id')->toArray()
                ];
            }
        }

        // 4. Enregistrer les manquants du pointeur
        if ($manquantTotal > 0) {
            $this->insertManquant($pointeur->user_id, 'Easy Gest -> Calcul automatique de manquant', $manquantTotal, $yesterday);
            // Notification si manquant significatif
            if ($manquantTotal > 5000) { // Seuil à ajuster selon vos besoins
                $this->notifyManagers("Manquant important détecté pour le pointeur {$pointeur->pointeur_id}: {$manquantTotal} XAF");
            }
        }
    }

    // 5. Logging et retour
    $message = "Calcul des manquants pointeurs: " . count($pointeurManquants) . " pointeur(s) avec manquants, " . count($notifications) . " produit(s) non reçu(s)";
    Log::info("InconsistencyChecks: $message");
    
    $this->logExecution('success', $message, [
        'pointeur_manquants' => $pointeurManquants,
        'produits_non_recus' => $notifications
    ], $startTime);
    
    return 0;
}


   private function insertManquant($userId, $explication, $montant, $date = null)
{
    // Validation des paramètres
    if (empty($userId) || !is_numeric($montant)) {
        Log::error("insertManquant: Paramètres invalides - userId: {$userId}, montant: {$montant}");
        return false;
    }

    try {
        DB::table('manquant_temporaire')->insert([
            'employe_id' => $userId,
            'montant' => $montant,
            'explication' => $explication, // Champ manquant dans votre version
            'statut' => 'en_attente', // Statut par défaut
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
            // Remarque: Pas de champ 'date' dans votre schéma, utilisez created_at
        ]);

        Log::info("Manquant inséré avec succès - Employé: {$userId}, Montant: {$montant} XAF");
        return true;

    } catch (Exception $e) {
        Log::error("Erreur lors de l'insertion du manquant: " . $e->getMessage());
        return false;
    }
}

    private function notifyManagers($message)
    {
        $managers = User::whereIn('role', ['dg', 'chef_production'])->get();
        
        foreach ($managers as $manager) {
            $request = new Request([
                'recipient_id' => $manager->id,
                'subject' => 'Alerte Incohérence Système',
                'message' => $message
            ]);
            
            $this->notificationController->send($request);
        }
    }

    

    private function checkOpenSessions($startTime)
    {
        Log::info("InconsistencyChecks: Vérification des sessions ouvertes");
        
        $openCashierSessions = CashierSession::whereNull('end_time')->count();
        $openCashDistributions = CashDistribution::where('status', 'en_cours')->count();
        
        if ($openCashierSessions > 0 || $openCashDistributions > 0) {
            $admins = User::whereIn('role', ['dg', 'chef_production'])->get();
            
            foreach ($admins as $admin) {
                $message = $this->getOpenSessionMessage($admin->language, $openCashierSessions, $openCashDistributions);
                
                $request = new Request([
                    'recipient_id' => $admin->id,
                    'subject' => $admin->language === 'en' ? 'Open Sessions Alert' : 'Alerte Sessions Ouvertes',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
            }
        }
        
        $message = "Vérification des sessions ouvertes: $openCashierSessions caissières, $openCashDistributions distributions";
        Log::info("InconsistencyChecks: $message");
        $this->logExecution('success', $message, ['open_cashier_sessions' => $openCashierSessions, 'open_distributions' => $openCashDistributions], $startTime);
        
        return 0;
    }

    private function checkDuplicateMaterials($startTime)
    {
        Log::info("InconsistencyChecks: Vérification des matières en double");
        
        $duplicateMaterials = Matiere::select('nom', 'prix_unitaire')
            ->groupBy('nom', 'prix_unitaire')
            ->havingRaw('COUNT(*) > 1')
            ->get();
        
        if (!$duplicateMaterials->isEmpty()) {
            $chefProductions = User::where('role', 'chef_production')->get();
            
            foreach ($chefProductions as $cp) {
                $message = $this->getDuplicateMaterialsMessage($cp->language, count($duplicateMaterials));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Duplicate Materials Alert' : 'Alerte Matières en Double',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
            }
        }
        
        $message = "Vérification des matières en double: " . count($duplicateMaterials) . " doublons trouvés";
        Log::info("InconsistencyChecks: $message");
        $this->logExecution('success', $message, ['duplicates_count' => count($duplicateMaterials)], $startTime);
        
        return 0;
    }

    private function checkDuplicateProducts($startTime)
    {
        Log::info("InconsistencyChecks: Vérification des produits en double");
        
        $duplicateProducts = DB::table('Produit_fixes')
            ->select('nom', 'prix')
            ->groupBy('nom', 'prix')
            ->havingRaw('COUNT(*) > 1')
            ->get();
        
        if (!$duplicateProducts->isEmpty()) {
            $chefProductions = User::where('role', 'chef_production')->get();
            
            foreach ($chefProductions as $cp) {
                $message = $this->getDuplicateProductsMessage($cp->language, count($duplicateProducts));
                
                $request = new Request([
                    'recipient_id' => $cp->id,
                    'subject' => $cp->language === 'en' ? 'Duplicate Products Alert' : 'Alerte Produits en Double',
                    'message' => $message
                ]);
                
                $this->notificationController->send($request);
            }
        }
        
        $message = "Vérification des produits en double: " . count($duplicateProducts) . " doublons trouvés";
        Log::info("InconsistencyChecks: $message");
        $this->logExecution('success', $message, ['duplicates_count' => count($duplicateProducts)], $startTime);
        
        return 0;
    }

private function checkUnprofitableProduction($startTime)
{
    Log::info("InconsistencyChecks: Vérification des productions non rentables");
    
    // Récupération des données avec les informations nécessaires pour le calcul
    $productionsData = DB::table('Matiere_recommander as mr')
        ->join('Produit_fixes as pf', 'mr.produit', '=', 'pf.code_produit')
        ->join('Matiere as m', 'mr.matierep', '=', 'm.id')
        ->select(
            'mr.produit', 
            'pf.nom as produit_nom', 
            'pf.prix as produit_prix',
            'mr.quantite as quantite_recommandee',
            'mr.unite as unite_recommandee',
            'mr.quantitep as quantite_produit',
            'm.prix_par_unite_minimale',
            'm.unite_minimale',
            'm.nom as matiere_nom'
        )
        ->get()
        ->groupBy('produit');
    
    $unprofitableProductions = collect();
    $lowMarginProductions = collect();
    
    foreach ($productionsData as $produitId => $matieres) {
        $productPrice = 0;
        $productName = '';
        $quantiteProduit = 0;
        $totalCostAllMaterials = 0; // Coût total de toutes les matières
        
        // Récupération des informations du produit (identiques pour toutes les matières)
        $firstMatiere = $matieres->first();
        $productPrice = $firstMatiere->produit_prix;
        $productName = $firstMatiere->produit_nom;
        $quantiteProduit = $firstMatiere->quantite_produit;
        
        Log::info("Calcul pour le produit {$produitId} ({$productName}) - Quantité produite: {$quantiteProduit}");
        
        // Calcul du coût total de toutes les matières pour ce produit
        foreach ($matieres as $matiere) {
            try {
                // Conversion de la quantité recommandée vers l'unité minimale de la matière
                $quantiteConvertie = $this->conversionService->convertir(
                    $matiere->quantite_recommandee,
                    $matiere->unite_recommandee,
                    $matiere->unite_minimale
                );
                
                // Calcul du coût total pour cette matière (sans diviser par la quantité produite)
                $coutMatiere = $quantiteConvertie * $matiere->prix_par_unite_minimale;
                
                // Ajout au coût total de toutes les matières
                $totalCostAllMaterials += $coutMatiere;
                
                Log::info("  - Matière {$matiere->matiere_nom}: 
                    Quantité recommandée: {$matiere->quantite_recommandee} {$matiere->unite_recommandee}, 
                    Quantité convertie: {$quantiteConvertie} {$matiere->unite_minimale}, 
                    Prix par unité minimale: {$matiere->prix_par_unite_minimale}, 
                    Coût matière: {$coutMatiere}");
                
            } catch (\Exception $e) {
                Log::error("Erreur lors du calcul du coût pour le produit {$produitId} et la matière {$matiere->matiere_nom}: " . $e->getMessage());
                // En cas d'erreur de conversion, utiliser directement la quantité et le prix
                $coutMatiere = $matiere->quantite_recommandee * $matiere->prix_par_unite_minimale;
                $totalCostAllMaterials += $coutMatiere;
            }
        }
        
        // Calcul du coût unitaire (coût total divisé par la quantité produite)
        $coutUnitaire = $quantiteProduit > 0 ? $totalCostAllMaterials / $quantiteProduit : $totalCostAllMaterials;
        
        // Calcul de la marge basée sur le coût unitaire
        $marge = $productPrice > 0 ? (($productPrice - $coutUnitaire) / $productPrice) * 100 : 0;
        
        Log::info("Produit {$produitId} ({$productName}): 
            Prix de vente unitaire: {$productPrice}, 
            Coût total matières: {$totalCostAllMaterials}, 
            Coût unitaire: {$coutUnitaire}, 
            Marge: {$marge}%");
        
        // Vérification si la production est non rentable (coût unitaire > prix de vente)
        if ($coutUnitaire > $productPrice) {
            $unprofitableProductions->put($produitId, [
                'produit_id' => $produitId,
                'produit_nom' => $productName,
                'prix_vente' => $productPrice,
                'cout_unitaire' => $coutUnitaire,
                'cout_total' => $totalCostAllMaterials,
                'quantite_produit' => $quantiteProduit,
                'marge' => $marge
            ]);
        }
        
        // Vérification si la marge est inférieure à 30%
        if ($marge < 30 && $marge >= 0) {
            $lowMarginProductions->put($produitId, [
                'produit_id' => $produitId,
                'produit_nom' => $productName,
                'prix_vente' => $productPrice,
                'cout_unitaire' => $coutUnitaire,
                'cout_total' => $totalCostAllMaterials,
                'quantite_produit' => $quantiteProduit,
                'marge' => $marge
            ]);
        }
    }
    
    // Traitement des productions non rentables
    if ($unprofitableProductions->count() > 0) {
        $message = "Alerte : " . $unprofitableProductions->count() . " production(s) ont un coût de matières supérieur au prix de vente. Vérification des prix recommandée.";
        
        // Ajouter les détails des productions problématiques
        $details = $unprofitableProductions->map(function ($prod) {
            return sprintf(
                "- %s (ID: %s): Prix de vente: %s, Coût unitaire: %.2f, Coût total: %.2f (pour %d unités), Perte unitaire: %.2f",
                $prod['produit_nom'],
                $prod['produit_id'],
                $prod['prix_vente'],
                $prod['cout_unitaire'],
                $prod['cout_total'],
                $prod['quantite_produit'],
                $prod['cout_unitaire'] - $prod['prix_vente']
            );
        })->join("\n");
        
        $fullMessage = $message . "\n\nDétails:\n" . $details;
        
        $signalementRequest = new Request([
            'message' => $fullMessage,
            'category' => 'report'
        ]);
        
        $this->messageController->store_message($signalementRequest);
        
        // Notification aux chefs de production
        $chefProductions = User::where('role', 'chef_production')->get();
        
        foreach ($chefProductions as $cp) {
            $message = $this->getUnprofitableMessage($cp->language, $unprofitableProductions->count());
            
            $request = new Request([
                'recipient_id' => $cp->id,
                'subject' => $cp->language === 'en' ? 'Unprofitable Production Alert' : 'Alerte Production Non Rentable',
                'message' => $message . "\n\n" . $details
            ]);
            
            $this->notificationController->send($request);
        }
    }
    
    // Traitement des productions avec marge faible
    if ($lowMarginProductions->count() > 0) {
        $message = "Alerte : " . $lowMarginProductions->count() . " production(s) ont une marge inférieure à 30%. Révision des prix recommandée.";
        
        // Ajouter les détails des productions à marge faible
        $details = $lowMarginProductions->map(function ($prod) {
            return sprintf(
                "- %s (ID: %s): Prix de vente: %s, Coût unitaire: %.2f, Coût total: %.2f (pour %d unités), Marge: %.1f%%",
                $prod['produit_nom'],
                $prod['produit_id'],
                $prod['prix_vente'],
                $prod['cout_unitaire'],
                $prod['cout_total'],
                $prod['quantite_produit'],
                $prod['marge']
            );
        })->join("\n");
        
        $fullMessage = $message . "\n\nDétails:\n" . $details;
        
        $signalementRequest = new Request([
            'message' => $fullMessage,
            'category' => 'report'
        ]);
        
        $this->messageController->store_message($signalementRequest);
        
        // Notification aux chefs de production
        $chefProductions = User::where('role', 'chef_production')->get();
        
        foreach ($chefProductions as $cp) {
            $message = $this->getLowMarginMessage($cp->language, $lowMarginProductions->count());
            
            $request = new Request([
                'recipient_id' => $cp->id,
                'subject' => $cp->language === 'en' ? 'Low Margin Production Alert' : 'Alerte Production à Marge Faible',
                'message' => $message . "\n\n" . $details
            ]);
            
            $this->notificationController->send($request);
        }
    }
    
    $totalProblematicProductions = $unprofitableProductions->count() + $lowMarginProductions->count();
    $message = "Vérification des productions: " . $unprofitableProductions->count() . " non rentables, " . $lowMarginProductions->count() . " à marge faible (<30%)";
    
    Log::info("InconsistencyChecks: $message");
    $this->logExecution('success', $message, [
        'unprofitable_count' => $unprofitableProductions->count(),
        'low_margin_count' => $lowMarginProductions->count(),
        'total_problematic' => $totalProblematicProductions
    ], $startTime);
    
    return 0;
}

// Méthode helper pour les messages de marge faible
private function getLowMarginMessage($language, $count)
{
    if ($language === 'en') {
        return "Alert: {$count} production(s) have a margin below 30%. Price review recommended.";
    } else {
        return "Alerte : {$count} production(s) ont une marge inférieure à 30%. Révision des prix recommandée.";
    }
}

    
    private function getOpenSessionMessage($language, $cashierSessions, $distributions)
    {
        if ($language === 'en') {
            return "Alert: $cashierSessions cashier session(s) and $distributions cash distribution(s) are still open at 4 AM. Please close them.";
        }
        
        return "Alerte : $cashierSessions session(s) de caissière et $distributions distribution(s) de cash sont encore ouvertes à 4h. Veuillez les fermer.";
    }

    private function getDuplicateMaterialsMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count duplicate material(s) found with the same name and price. Please review and consolidate.";
        }
        
        return "Alerte : $count matière(s) en double trouvée(s) avec le même nom et prix. Veuillez réviser et consolider.";
    }

    private function getDuplicateProductsMessage($language, $count)
    {
        if ($language === 'en') {
            return "Alert: $count duplicate product(s) found with the same name and price. Please review and consolidate.";
        }
        
        return "Alerte : $count produit(s) en double trouvé(s) avec le même nom et prix. Veuillez réviser et consolider.";
    }

    private function getUnprofitableMessage($language, $count)
    {
        if ($language === 'en') {
        return "Alert: $count Recommended production(s) (Defined by administration) have material costs exceeding the selling price. Please review pricing or recipes.";
    }
    return "Alerte : $count production(s) recommandée(s) (définie(s) par l'administration) ont des coûts de matières dépassant le prix de vente. Veuillez réviser les prix ou les recettes.";
}

    private function logExecution($status, $message, $details, $startTime)
    {
        ListenerLog::create([
            'listener_name' => 'InconsistencyChecks',
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'executed_at' => Carbon::now('Africa/Douala'),
            'execution_time' => microtime(true) - $startTime
        ]);
    }
}