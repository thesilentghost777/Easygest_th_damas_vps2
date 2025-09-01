<?php

namespace App\Http\Controllers;

use App\Models\ACouper;
use App\Models\ManquantTemporaire;
use App\Models\AssignationMatiere;
use App\Models\ProduitRecu1;
use App\Models\ProduitRecuVendeur;
use App\Models\Utilisation;
use App\Models\BagAssignment;
use App\Models\BagReception;
use App\Models\BagSale;
use App\Models\Produit_fixes;
use App\Models\TransactionVente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\HistorisableActions;


class ManquantController extends Controller
{
    use HistorisableActions;

    protected $gaspillageController;
    
    public function __construct(GaspillageController $gaspillageController)
    {
        $this->gaspillageController = $gaspillageController;
    }
    /**
     * Afficher la liste des manquants temporaires pour le DG
     */
    public function index()
    {
        // Récupérer tous les manquants temporaires classés par montant décroissant
        $manquants = ManquantTemporaire::with('employe')
            ->orderBy('montant', 'desc')
            ->get();

        return view('manquants.index', compact('manquants'));
    }

    /**
     * Afficher les manquants de l'employé connecté
     */
    public function mesManquants()
    {
        $employe = Auth::user();
        $manquant = ManquantTemporaire::where('employe_id', $employe->id)->first();

        return view('manquants.mes-manquants', compact('manquant'));
    }

    /**
     * Formulaire d'ajustement pour le DG
     */
    public function ajuster($id)
    {
        $manquant = ManquantTemporaire::with('employe')->findOrFail($id);
        return view('manquants.ajuster', compact('manquant'));
    }

    /**
     * Enregistrer l'ajustement du manquant
     */
    public function sauvegarderAjustement(Request $request, $id)
    {
        $request->validate([
            'montant' => 'required|integer|min:0',
            'commentaire_dg' => 'nullable|string'
        ]);

        $manquant = ManquantTemporaire::findOrFail($id);
        $manquant->montant = $request->montant;
        $manquant->commentaire_dg = $request->commentaire_dg;
        $manquant->statut = 'ajuste';
        $manquant->save();
        // Historiser l'action
        $this->historiser("L'utilisateur " . Auth::user()->name . " a ajusté le manquant ID: {$id} à {$request->montant} FCFA", 'adjust_manquant');
        return redirect()->route('manquants.index')
            ->with('success', 'Manquant ajusté avec succès');
    }

    /**
     * Valider un manquant et le transférer dans la table ACouper
     */
    public function valider($id)
    {
        $manquant = ManquantTemporaire::findOrFail($id);
        $manquant->statut = 'valide';
        $manquant->valide_par = Auth::id();
        $manquant->save();

        // Vérifier s'il existe déjà une entrée pour cet employé dans ACouper
        $aCouper = ACouper::where('id_employe', $manquant->employe_id)->first();

        if ($aCouper) {
            // Mettre à jour l'entrée existante
            $aCouper->manquants += $manquant->montant;
            $aCouper->date = Carbon::now();
            $aCouper->save();
        } else {
            // Créer une nouvelle entrée
            ACouper::create([
                'id_employe' => $manquant->employe_id,
                'manquants' => $manquant->montant,
                'date' => Carbon::now()
            ]);
        }
        $user = User::find($manquant->employe_id);
        $this->historiser("Le dg vient de confirmer le manquant de  {$user->name} a {$manquant->montant} XAF", 'valider_manquant_temporaire');

        return redirect()->route('manquants.index')
            ->with('success', 'Manquant validé et transféré avec succès');
    }

   /**
 * Calculer les manquants pour tous les employés
 */
public function calculerTousLesManquants()
{
    Log::info('=== DÉBUT DU CALCUL DES MANQUANTS POUR TOUS LES EMPLOYÉS ===');
    
    // Récupérer tous les employés par secteur (sauf administration)
    $employes = User::whereNotIn('secteur', ['administration'])
        ->get();

    Log::info('Nombre d\'employés trouvés (hors administration): ' . $employes->count());
    Log::info('Détail des employés:', $employes->pluck('id', 'name')->toArray());

    $resultats = [
        'pointeurs' => 0,
        'producteurs' => 0,
        'total_manquants' => 0
    ];

    try {
        Log::info('Début de la transaction de base de données');
        DB::beginTransaction();
        
        foreach ($employes as $employe) {
            Log::info("Traitement de l'employé: ID={$employe->id}, Nom={$employe->name}, Rôle={$employe->role}, Secteur={$employe->secteur}");
            
            switch ($employe->role) {
                
                case 'boulanger':
                    Log::info("Calcul des manquants pour le producteur ID={$employe->id}");
                    $montant = $this->calculerManquantProducteur($employe->id);
                    Log::info("Montant calculé pour le producteur ID={$employe->id}: {$montant} FCFA");
                    $resultats['producteurs'] += $montant;
                    break;
                case 'patissier':
                    Log::info("Calcul des manquants pour le producteur ID={$employe->id}");
                    $montant = $this->calculerManquantProducteur($employe->id);
                    Log::info("Montant calculé pour le producteur ID={$employe->id}: {$montant} FCFA");
                    $resultats['producteurs'] += $montant;
                    break;
                default:
                    Log::warning("Rôle non reconnu pour l'employé ID={$employe->id}: {$employe->role}");
                    break;
            }
        }
        
        $resultats['total_manquants'] = $resultats['pointeurs'] + $resultats['producteurs'];
        
        Log::info('Résultats finaux du calcul:', $resultats);
        
        $this->historiser(
            "Calcul des manquants effectué: Pointeurs: {$resultats['pointeurs']} FCFA, Producteurs: {$resultats['producteurs']} FCFA",
            'calculate',
            null,
            'manquants'
        );
        
        Log::info('Historisation effectuée avec succès');
        
        DB::commit();
        Log::info('Transaction commitée avec succès');
        
        Log::info('=== FIN DU CALCUL DES MANQUANTS - SUCCÈS ===');
        
        return redirect()->route('manquants.index')
            ->with('success', "Calcul des manquants effectué. Total: {$resultats['total_manquants']} FCFA");
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('=== ERREUR LORS DU CALCUL DES MANQUANTS ===');
        Log::error('Message d\'erreur: ' . $e->getMessage());
        Log::error('Trace de la pile:', $e->getTrace());
        Log::error('Fichier: ' . $e->getFile() . ' - Ligne: ' . $e->getLine());
        
        return redirect()->route('manquants.index')
            ->with('error', 'Erreur lors du calcul des manquants: ' . $e->getMessage());
    }
}

private function calculerManquantProducteur($producteurId)
{
    Log::info("=== DÉBUT CALCUL MANQUANTS PRODUCTEUR ID={$producteurId} ===");
    
    // Date du début et fin du mois courant
    $dateDebut = Carbon::now()->startOfMonth();
    $dateFin = Carbon::now()->endOfMonth();
    Log::info("Période de calcul: du {$dateDebut} au {$dateFin}");
    
    // Total des manquants
    $totalManquants = 0;
    
    // 1. Gaspillage de matières - CORRECTION: Calculer par id_lot
    Log::info('--- ÉTAPE 1: Calcul du gaspillage de matières ---');
    
    // Récupérer tous les lots de production du producteur
    $lots = Utilisation::where('producteur', $producteurId)
        ->whereBetween('created_at', [$dateDebut, $dateFin])
        ->distinct('id_lot')
        ->pluck('id_lot');
        
    Log::info('Nombre de lots trouvés: ' . $lots->count());
    
    foreach ($lots as $idLot) {
        Log::info("Traitement du lot: {$idLot}");
        
        try {
            // Utiliser la méthode existante pour calculer le gaspillage
            $gaspillageDetails = $this->gaspillageController->calculerGaspillageProduction($idLot);
            $valeurGaspillageLot = 0;
            foreach ($gaspillageDetails as $detail) {
                $valeurGaspillageLot += $detail->valeur_gaspillage;
            }
            
            Log::info("Valeur de gaspillage pour le lot {$idLot}: {$valeurGaspillageLot} FCFA");
            
            if ($valeurGaspillageLot > 0) {
                $this->mettreAJourManquantTemporaire(
                    $producteurId,
                    $valeurGaspillageLot,
                    "Gaspillage de matières pour le lot de production {$idLot}"
                );
                
                $totalManquants += $valeurGaspillageLot;
            }
        } catch (\Exception $e) {
            Log::error("Erreur lors du calcul du gaspillage pour le lot {$idLot}: " . $e->getMessage());
            throw $e;
        }
    }
    
    Log::info("Total des manquants après étape 1 (gaspillage): {$totalManquants} FCFA");
    
    // 2. Valeur des produits fabriqués < Valeur des matières assignées
    Log::info('--- ÉTAPE 2: Comparaison valeur production vs matières assignées ---');
    
    // CORRECTION: Calculer la valeur des produits fabriqués par lot
    $valeurTotaleProduction = 0;
    $productionsParLot = Utilisation::with('produitFixe')
        ->where('producteur', $producteurId)
        ->whereBetween('created_at', [$dateDebut, $dateFin])
        ->select('id_lot', 'produit', 'quantite_produit')
        ->distinct()
        ->get();
        
    Log::info('Nombre de productions par lot: ' . $productionsParLot->count());
        
    foreach ($productionsParLot as $index => $production) {
        $valeurProduction = $production->quantite_produit * ($production->produitFixe->prix ?? 0);
        Log::info("Production #{$index}: Lot={$production->id_lot}, Produit={$production->produitFixe->nom}, Quantité={$production->quantite_produit}, Prix unitaire={$production->produitFixe->prix}, Valeur={$valeurProduction}");
        $valeurTotaleProduction += $valeurProduction;
    }
    
    Log::info("Valeur totale de la production: {$valeurTotaleProduction} FCFA");
    
    // Calculer la valeur des matières assignées
    $assignations = AssignationMatiere::with('matiere')
        ->where('producteur_id', $producteurId)
        ->whereBetween('created_at', [$dateDebut, $dateFin])
        ->get();
        
    Log::info('Nombre d\'assignations de matières: ' . $assignations->count());
        
    $valeurTotaleMatieresAssignees = 0;
    foreach ($assignations as $index => $assignation) {
        // Convertir en unité minimale si nécessaire
        $prixUnitaire = $assignation->matiere->prix_par_unite_minimale ?? 0;
        
        Log::info("Assignation #{$index}: Matière={$assignation->matiere->nom}, Quantité={$assignation->quantite_assignee}, Unité={$assignation->unite_assignee}");
        Log::info("  Prix unitaire: {$prixUnitaire}, Unité minimale: {$assignation->matiere->unite_minimale->toString()}");
        
        if ($assignation->unite_assignee == $assignation->matiere->unite_minimale) {
            $valeurAssignation = $assignation->quantite_assignee * $prixUnitaire;
            Log::info("  Pas de conversion nécessaire - Valeur: {$valeurAssignation}");
        } else {
            // Conversion nécessaire
            $facteurConversion = $assignation->matiere->quantite_par_unite ?? 1;
            $valeurAssignation = $assignation->quantite_assignee * $facteurConversion * $prixUnitaire;
            Log::info("  Conversion nécessaire - Facteur: {$facteurConversion}, Valeur: {$valeurAssignation}");
        }
        
        $valeurTotaleMatieresAssignees += $valeurAssignation;
    }
    
    Log::info("Valeur totale des matières assignées: {$valeurTotaleMatieresAssignees} FCFA");
    
    // Si la valeur des produits est inférieure à la valeur des matières, il y a un manquant
    if ($valeurTotaleProduction < $valeurTotaleMatieresAssignees) {
        $difference = $valeurTotaleMatieresAssignees - $valeurTotaleProduction;
        
        Log::info("RENDEMENT INSUFFISANT DÉTECTÉ");
        Log::info("  - Valeur production: {$valeurTotaleProduction} FCFA");
        Log::info("  - Valeur matières: {$valeurTotaleMatieresAssignees} FCFA");
        Log::info("  - Manquant: {$difference} FCFA");
        
        try {
            $this->mettreAJourManquantTemporaire(
                $producteurId,
                $difference,
                "Valeur des produits fabriqués ({$valeurTotaleProduction} FCFA) inférieure à la valeur des matières assignées ({$valeurTotaleMatieresAssignees} FCFA)"
            ); 
            
            $totalManquants += $difference;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'enregistrement du manquant rendement: " . $e->getMessage());
            throw $e;
        }
    } else {
        Log::info("Rendement suffisant (valeur production >= valeur matières)");
    }
    
    Log::info("Total des manquants après étape 2: {$totalManquants} FCFA");
    
    // 3. Moitié de la différence entre production réelle et déclarée au pointeur
    Log::info('--- ÉTAPE 3: Calcul écart production vs pointage ---');
    
    // CORRECTION: Utiliser la même logique que pour le pointeur
    $productionsParProduit = DB::table('Utilisation')
        ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
        ->select(
            'Utilisation.produit',
            'Produit_fixes.nom as nom_produit',
            'Produit_fixes.prix',
            'Utilisation.id_lot',
            'Utilisation.quantite_produit'
        )
        ->where('Utilisation.producteur', $producteurId)
        ->whereBetween('Utilisation.created_at', [$dateDebut, $dateFin])
        ->distinct()
        ->get();
    
    // Regrouper par produit et sommer les quantités
    $productionsGroupees = $productionsParProduit->groupBy('produit')->map(function ($items) {
        $first = $items->first();
        return (object) [
            'produit' => $first->produit,
            'nom_produit' => $first->nom_produit,
            'prix' => $first->prix,
            'quantite_produite' => $items->sum('quantite_produit')
        ];
    });
        
    Log::info('Nombre de produits en production: ' . $productionsGroupees->count());
        
    foreach ($productionsGroupees as $index => $production) {
        Log::info("Production #{$index}: Produit={$production->produit}, Nom={$production->nom_produit}, Quantité produite={$production->quantite_produite}");
        
        // Quantité reçue par le pointeur pour ce produit et ce producteur
        $quantitesRecues = ProduitRecu1::where('produit_id', $production->produit)
            ->where('producteur_id', $producteurId)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->sum('quantite');
            
        Log::info("Quantité reçue par le pointeur: {$quantitesRecues}");
            
        // Si la quantité produite est supérieure à la quantité reçue, il y a un manquant
        if ($production->quantite_produite > $quantitesRecues) {
            $difference = $production->quantite_produite - $quantitesRecues;
            $valeurDifference = $difference * $production->prix;
            
            // Le producteur est responsable de la moitié du manquant
            $manquantProducteur = $valeurDifference / 2;
            
            Log::info("ÉCART POINTAGE DÉTECTÉ - Produit: {$production->nom_produit}");
            Log::info("  - Quantité produite: {$production->quantite_produite}");
            Log::info("  - Quantité reçue: {$quantitesRecues}");
            Log::info("  - Différence: {$difference}");
            Log::info("  - Valeur différence: {$valeurDifference} FCFA");
            Log::info("  - Manquant producteur (50%): {$manquantProducteur} FCFA");
            
            try {
                $this->mettreAJourManquantTemporaire(
                    $producteurId,
                    $manquantProducteur,
                    "Écart entre production et pointage pour {$production->nom_produit}: {$difference} unités non comptabilisées (partage de responsabilité)"
                );
                
                $totalManquants += $manquantProducteur;
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'enregistrement du manquant écart: " . $e->getMessage());
                throw $e;
            }
        } else {
            Log::info("Pas d'écart pour le produit {$production->nom_produit} (quantité produite <= quantité reçue)");
        }
    }
    
    Log::info("=== FIN CALCUL MANQUANTS PRODUCTEUR ID={$producteurId} - Total: {$totalManquants} FCFA ===");
    
    return $totalManquants;
}

// MÉTHODE CORRIGÉE POUR LE CALCUL DU GASPILLAGE
public function calculerGaspillageProduction($idLot)
{
    Log::info("Calculating waste details for production: {$idLot}");
    
    $result = [];
    $valeurTotaleGaspillage = 0;
    
    // Récupérer toutes les utilisations pour ce lot
    $utilisations = Utilisation::where('id_lot', $idLot)
        ->with(['produitFixe', 'matierePremiere'])
        ->get();
    
    if ($utilisations->isEmpty()) {
        Log::warning("No utilizations found for lot {$idLot}");
        return [];
    }
    
    // Regrouper par matière pour éviter les doublons
    $utilisationsParMatiere = $utilisations->groupBy('matierep');
    
    foreach ($utilisationsParMatiere as $matiereId => $utilisationsMatiere) {
        // Prendre la première utilisation comme référence (toutes ont les mêmes infos de base)
        $utilisationRef = $utilisationsMatiere->first();
        
        Log::info("Processing material ID {$matiereId}: {$utilisationRef->matierePremiere->nom}");
        
        // Sommer les quantités de matière utilisées pour cette matière dans ce lot
        $quantiteMatiereUtilisee = $utilisationsMatiere->sum('quantite_matiere');
        
        // Récupérer la quantité totale de produit fabriqué dans ce lot
        $quantiteProduitTotal = $utilisations->where('produit', $utilisationRef->produit)->first()->quantite_produit ?? 0;
        
        Log::info("Total material used: {$quantiteMatiereUtilisee} {$utilisationRef->unite_matiere}");
        Log::info("Total product quantity: {$quantiteProduitTotal}");
        
        // Trouver la recommandation correspondante
        $recommandation = MatiereRecommander::where('produit', $utilisationRef->produit)
            ->where('matierep', $matiereId)
            ->first();
        
        if (!$recommandation) {
            // Vérifier si la matière est 'Taule%' (tôles)
            if (str_starts_with(strtolower($utilisationRef->matierePremiere->nom), 'taules')) {
                Log::info("Material {$utilisationRef->matierePremiere->nom} is sheet metal - not considering as waste");
                continue;
            }
            
            // Pas de recommandation = gaspillage total
            $quantiteRecommandee = 0;
            $quantiteGaspillee = $quantiteMatiereUtilisee;
            $pourcentageGaspillage = 100;
            $valeurGaspillage = $quantiteGaspillee * ($utilisationRef->matierePremiere->prix_par_unite_minimale ?? 0);
            
            Log::info("No recommendation found - considering total quantity as waste", [
                'quantite_utilisee' => "{$quantiteMatiereUtilisee} {$utilisationRef->unite_matiere}",
                'quantite_gaspillee' => $quantiteGaspillee,
                'valeur_gaspillage' => $valeurGaspillage
            ]);
        } else {
            // Calculer la quantité recommandée pour la quantité totale de produit
            $quantiteRecommandee = $recommandation->getRecommendedQuantityFor($quantiteProduitTotal);
            
            // Calculer la quantité gaspillée
            $quantiteGaspillee = $quantiteMatiereUtilisee - $quantiteRecommandee;
            
            Log::info("Waste calculation", [
                'quantite_utilisee' => "{$quantiteMatiereUtilisee} {$utilisationRef->unite_matiere}",
                'quantite_recommandee' => "{$quantiteRecommandee} {$utilisationRef->matierePremiere->unite_minimale->toString()}",
                'quantite_gaspillee' => $quantiteGaspillee
            ]);
            
            // Ne considérer que les gaspillages positifs
            if ($quantiteGaspillee <= 0) {
                Log::info("No waste detected: {$quantiteGaspillee} <= 0");
                continue;
            }
            
            // Calculer le pourcentage de gaspillage
            $pourcentageGaspillage = ($quantiteGaspillee / $quantiteMatiereUtilisee) * 100;
            
            // Calculer la valeur du gaspillage
            $valeurGaspillage = $quantiteGaspillee * ($utilisationRef->matierePremiere->prix_par_unite_minimale ?? 0);
        }
        
        Log::info("Waste value: {$valeurGaspillage}");
        $valeurTotaleGaspillage += $valeurGaspillage;
        
        $result[] = (object) [
            'id' => $utilisationRef->id,
            'nom_produit' => $utilisationRef->produitFixe->nom ?? 'Inconnu',
            'nom_matiere' => $utilisationRef->matierePremiere->nom ?? 'Inconnue',
            'quantite_utilisee' => $quantiteMatiereUtilisee,
            'quantite_recommandee' => $quantiteRecommandee,
            'quantite_gaspillee' => $quantiteGaspillee,
            'pourcentage_gaspillage' => $pourcentageGaspillage,
            'valeur_gaspillage' => $valeurGaspillage,
            'unite_matiere' => $utilisationRef->unite_matiere,
            'unite_minimale' => $utilisationRef->matierePremiere->unite_minimale ?? '',
            'prix_par_unite_minimale' => $utilisationRef->matierePremiere->prix_par_unite_minimale ?? 0
        ];
    }
    
    // Trier par valeur de gaspillage (la plus élevée d'abord)
    usort($result, function ($a, $b) {
        return $b->valeur_gaspillage <=> $a->valeur_gaspillage;
    });
    
    Log::info("Total waste value for lot {$idLot}: {$valeurTotaleGaspillage}");
    
    return $result;
}

private function mettreAJourManquantTemporaire($employeId, $montant, $explication)
{
    // CORRECTION: Accumuler les manquants au lieu de les écraser
    $manquant = ManquantTemporaire::where('employe_id', $employeId)->first();
    
    if ($manquant) {
        // Accumuler le montant et ajouter l'explication
        $manquant->montant += $montant;
        $manquant->explication .= "\n" . $explication;
        $manquant->save();
    } else {
        // Créer un nouveau manquant
        $manquant = ManquantTemporaire::create([
            'employe_id' => $employeId,
            'montant' => $montant,
            'explication' => $explication,
            'statut' => 'en_attente',
            'commentaire_dg' => null,
            'valide_par' => null
        ]);
    }

    // Historiser l'action
    $employe = User::findOrFail($employeId);
    $this->historiser(
        "L'utilisateur " . Auth::user()->name . " a mis à jour le manquant temporaire de l'utilisateur " . $employe->name . " : " . $montant . " FCFA"
    );
    return $manquant;
}
    /**
     * Calculer les manquants pour un serveur
     * Manquant = montant attendu - montant du versement
     * Montant attendu = valeur des produits (reçus - vendus - invendus - avariés)
     */
    /**
     * Obtenir les détails d'une production par lot (similaire à produit_par_lot)
     */
    private function getProductionDetails($idLot)
    {
        $utilisations = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->select(
                'Produit_fixes.nom as nom_produit',
                'Produit_fixes.prix as prix_produit',
                'Utilisation.quantite_produit',
                'Matiere.prix_par_unite_minimale',
                'Utilisation.quantite_matiere'
            )
            ->where('Utilisation.id_lot', $idLot)
            ->get();

        $productionDetails = [
            'produit' => $utilisations->first()->nom_produit,
            'quantite_produit' => $utilisations->first()->quantite_produit,
            'prix_unitaire' => $utilisations->first()->prix_produit,
            'valeur_production' => $utilisations->first()->quantite_produit * $utilisations->first()->prix_produit,
            'cout_matieres' => 0
        ];

        foreach ($utilisations as $utilisation) {
            $productionDetails['cout_matieres'] += $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
        }

        return $productionDetails;
    }

    /**
     * Méthode pour facturer un manquant à un producteur (action spécifique)
     */
    public function create()
    {
        $producteurs = User::where('role', 'producteur')->get();
        return view('manquants.create', compact('producteurs'));
    }
    /**
     * Enregistrer un manquant facturé
     */
    public function store(Request $request)
    {
        $request->validate([
            'employe_id' => 'required|exists:users,id',
            'montant' => 'required|integer|min:1',
            'explication' => 'required|string'
        ]);

        $this->mettreAJourManquantTemporaire(
            $request->employe_id,
            $request->montant,
            $request->explication
        );
        // Historiser l'action
        $employe = User::findOrFail($request->employe_id);
        $this->historiser(
            "L'utilisateur " . Auth::user()->name . " a facturé un manquant à l'employé " . $employe->name . " : " . $request->montant . " FCFA"
        );

        return redirect()->route('manquants.index')
            ->with('success', 'Manquant facturé avec succès');
    }

    public function details($id)
    {
        $manquant = ManquantTemporaire::with('employe')->findOrFail($id);

        return response()->json([
            'id' => $manquant->id,
            'employe' => [
                'name' => $manquant->employe->name,
                'role' => ucfirst($manquant->employe->role)
            ],
            'montant' => $manquant->montant,
            'explication' => $manquant->explication,
            'statut' => ucfirst(str_replace('_', ' ', $manquant->statut)),
            'commentaire_dg' => $manquant->commentaire_dg,
            'updated_at' => $manquant->updated_at->format('d/m/Y H:i')
        ]);
    }

    public function mesDeductions()
    {
        $employe = Auth::user();
        $deductions = Acouper::where('id_employe', $employe->id)->first();

        return view('manquants.mes-deductions', compact('deductions'));
    }
}
