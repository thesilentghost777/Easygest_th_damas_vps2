<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Matiere;
use App\Models\User;
use App\Models\AssignationMatiere;
use App\Models\Utilisation;
use App\Services\LotGeneratorService;
use App\Services\UniteConversionService;
use App\Http\Requests\StoreUtilisationRequest;
use App\Models\ProduitStock;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ProductionSac; // Assumez que les modèles existent ; ajustez si nécessaire
use App\Models\ProductionProduit;
use App\Models\SacMatiere;

class RepartiteurController extends Controller
{
    protected $lotGeneratorService;
    protected $uniteConversionService;
    protected $notificationController;

    public function __construct(
        LotGeneratorService $lotGeneratorService,
        UniteConversionService $uniteConversionService,
        NotificationController $notificationController
    ) {
        $this->lotGeneratorService = $lotGeneratorService;
        $this->uniteConversionService = $uniteConversionService;
        $this->notificationController = $notificationController;
    }

    public function index()
    {
        $isFrench = session('locale', 'fr') === 'fr';
        $producteur = Auth::user();
        
        // Récupérer les matières assignées au producteur
        $matieres = Matiere::all();

        // Récupérer les produits fixes avec leurs prix
        $produits = DB::table('Produit_fixes')->orderBy('nom')->get();

        return view('repartiteur.create', compact('matieres', 'produits', 'isFrench'));
    }

 
    /**
     * Calcule la répartition automatique basée sur prix × quantité
     */
    private function calculerRepartitionAutomatique($produits)
    {
        $repartitions = [];
        $totalValeur = 0;

        // Première passe : calculer la valeur totale (prix × quantité)
        foreach ($produits as $produit) {
            $produitData = DB::table('Produit_fixes')
                ->where('code_produit', $produit['id'])
                ->first();
            
            if (!$produitData) {
                throw new \Exception("Produit introuvable avec l'ID: {$produit['id']}");
            }

            $valeurProduit = $produitData->prix * $produit['quantite'];
            $totalValeur += $valeurProduit;

            $repartitions[] = [
                'produit_id' => $produit['id'],
                'quantite_produit' => $produit['quantite'],
                'prix_unitaire' => $produitData->prix,
                'valeur_totale' => $valeurProduit,
                'nom_produit' => $produitData->nom
            ];
        }

        if ($totalValeur <= 0) {
            throw new \Exception('La valeur totale des produits doit être supérieure à 0');
        }

        // Deuxième passe : calculer les proportions
        foreach ($repartitions as &$repartition) {
            $repartition['proportion'] = $repartition['valeur_totale'] / $totalValeur;
        }

        return $repartitions;
    }

    private function convertirEnKg($quantite, $unite)
    {
        // Conversion simple vers kg
        switch (strtolower($unite)) {
            case 'g':
            case 'gramme':
            case 'grammes':
                return $quantite / 1000;
            case 'kg':
            case 'kilogramme':
            case 'kilogrammes':
                return $quantite;
            case 'litre':
            case 'litres':
            case 'l':
                return $quantite; // Approximation 1L = 1kg pour les liquides
            case 'ml':
            case 'millilitre':
            case 'millilitres':
                return $quantite / 1000;
            default:
                return $quantite; // Par défaut, on assume que c'est déjà en kg
        }
    }

    private function convertirDepuisKg($quantiteKg, $uniteTarget)
    {
        // Conversion depuis kg vers l'unité cible
        switch (strtolower($uniteTarget)) {
            case 'g':
            case 'gramme':
            case 'grammes':
                return $quantiteKg * 1000;
            case 'kg':
            case 'kilogramme':
            case 'kilogrammes':
                return $quantiteKg;
            case 'litre':
            case 'litres':
            case 'l':
                return $quantiteKg; // Approximation 1kg = 1L pour les liquides
            case 'ml':
            case 'millilitre':
            case 'millilitres':
                return $quantiteKg * 1000;
            default:
                return $quantiteKg; // Par défaut
        }
    }

    public function store(Request $request)
{
    $request->validate([
        'matieres' => 'required|array|min:1',
        'matieres.*.id' => 'required|exists:Matiere,id',
        'matieres.*.quantite' => 'required|numeric|min:0.001',
        'matieres.*.unite' => 'required|string',
        'produits' => 'required|array|min:1',
        'produits.*.id' => 'required|exists:Produit_fixes,code_produit',
        'produits.*.quantite' => 'required|numeric|min:1',
        'date_production' => 'nullable|date', // Nouveau champ pour la date de production
    ]);

    try {
        DB::beginTransaction();

        $producteurId = Auth::id();
        $matieres = $request->matieres;
        $produits = $request->produits;
        $dateProduction = $request->date_production;

        // Calculer la répartition automatique basée sur prix × quantité
        $repartitionsCalculees = $this->calculerRepartitionAutomatique($produits);

        // Calculer le total des matières (en kg pour la cohérence)
        $totalMatieres = 0;
        $matieresConvertiesKg = [];

        foreach ($matieres as $matiere) {
            $matiereModel = Matiere::findOrFail($matiere['id']);
            
            // Convertir toutes les matières en kg pour le calcul
            $quantiteKg = $this->convertirEnKg($matiere['quantite'], $matiere['unite']);
            $matieresConvertiesKg[] = [
                'id' => $matiere['id'],
                'quantite_originale' => $matiere['quantite'],
                'unite_originale' => $matiere['unite'],
                'quantite_kg' => $quantiteKg,
                'matiere_model' => $matiereModel
            ];
            $totalMatieres += $quantiteKg;
        }

        // Traiter chaque produit avec sa répartition calculée
        foreach ($repartitionsCalculees as $repartition) {
            $produitId = $repartition['produit_id'];
            $quantiteProduit = $repartition['quantite_produit'];
            $proportion = $repartition['proportion'];

            // Calculer les matières proportionnelles pour ce produit
            $matieresProportionnelles = [];
            foreach ($matieresConvertiesKg as $matiere) {
                $quantiteProportionnelle = $matiere['quantite_kg'] * $proportion;
                
                // Reconvertir dans l'unité originale
                $quantiteOriginale = $this->convertirDepuisKg($quantiteProportionnelle, $matiere['unite_originale']);
                
                $matieresProportionnelles[] = [
                    'matiere_id' => $matiere['id'],
                    'quantite' => $quantiteOriginale,
                    'unite' => $matiere['unite_originale']
                ];
            }

            // Créer une fausse requête pour utiliser enregistrerProduction()
            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->merge([
                'produit' => $produitId,
                'quantite_produit' => $quantiteProduit,
                'matieres' => $matieresProportionnelles,
                'date_production' => $dateProduction // Passer la date de production
            ]);

            // Enregistrer cette production
            $this->enregistrerProduction($fakeRequest);
        }

        DB::commit();

        $isFrench = session('locale', 'fr') === 'fr';
        $message = $isFrench 
            ? 'Répartition automatique enregistrée avec succès. Toutes les productions ont été créées proportionnellement.'
            : 'Automatic distribution recorded successfully. All productions have been created proportionally.';

        return redirect()->route('repartiteur.index')->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        $isFrench = session('locale', 'fr') === 'fr';
        $message = $isFrench 
            ? 'Erreur lors de la répartition automatique: ' . $e->getMessage()
            : 'Error during automatic distribution: ' . $e->getMessage();
        
        return redirect()->back()->with('error', $message)->withInput();
    }
}

private function enregistrerProduction($request, $producteurId = null)
{
    // Utiliser l'ID fourni en paramètre ou celui de l'utilisateur connecté
    $producteurId = $producteurId ?? Auth::id();
    $conversionService = $this->uniteConversionService;
    $coutTotal = 0;

    // Gestion de la date de production et génération du lot ID
    $dateProduction = $request->date_production;
    if ($dateProduction) {
        // Si une date personnalisée est fournie, créer un DateTime à 15:00
        $dateProductionFormatted = Carbon::createFromFormat('Y-m-d', $dateProduction)->setTime(15, 0, 0);
        
        // Essayer de trouver le dernier lot du jour spécifié
        $lotId = $this->generateLotIdForDate($dateProductionFormatted);
    } else {
        // Utiliser la méthode habituelle si aucune date n'est spécifiée
        $dateProductionFormatted = now();
        $lotId = $this->lotGeneratorService->generateLotId();
    }

    foreach ($request->matieres as $matiere) {
        $matiereModel = Matiere::findOrFail($matiere['matiere_id']);
        
        // Conversion de la quantité vers l'unité minimale
        if ($matiere['unite'] !== $matiereModel->unite_minimale) {
            try {
                $quantiteConvertie = $conversionService->convertir(
                    $matiere['quantite'],
                    $matiere['unite'],
                    $matiereModel->unite_minimale
                );
            } catch (\Exception $e) {
                $quantiteConvertie = $matiere['quantite'];
            }
        } else {
            $quantiteConvertie = $matiere['quantite'];
        }

        // Créer l'enregistrement d'utilisation avec la date personnalisée
        $utilisation = new Utilisation();
        $utilisation->id_lot = $lotId;
        $utilisation->produit = $request->produit;
        $utilisation->matierep = $matiere['matiere_id'];
        $utilisation->producteur = $producteurId; // Utiliser l'ID passé en paramètre
        $utilisation->quantite_produit = $request->quantite_produit;
        $utilisation->quantite_matiere = $quantiteConvertie;
        $utilisation->unite_matiere = is_object($matiereModel->unite_minimale) ? $matiereModel->unite_minimale->value : $matiereModel->unite_minimale;
        $utilisation->created_at = $dateProductionFormatted; // Définir la date de création personnalisée
        $utilisation->updated_at = $dateProductionFormatted;
        $utilisation->save();

        // Ajouter au coût total
        $coutMatiere = $quantiteConvertie * $matiereModel->prix_par_unite_minimale;
        $coutTotal += $coutMatiere;
    }

    // Mettre à jour le stock de produits
    $produit = DB::table('Produit_fixes')->where('code_produit', $request->produit)->first();
    $produitStock = ProduitStock::firstOrNew(['id_produit' => $request->produit]);
    $produitStock->quantite_en_stock += $request->quantite_produit;
    $produitStock->save();

    // Historiser
    $infoProducteur = User::findOrFail($producteurId); // Utiliser l'ID passé en paramètre
    $dateStr = $dateProductionFormatted->format('d/m/Y');
    $this->historiser("Production automatique du lot {$lotId} par {$infoProducteur->name} le {$dateStr}: {$request->quantite_produit} unités de {$produit->nom}", 'repartiteur_production_auto');
}

/**
 * Génère un ID de lot pour une date spécifique
 */
private function generateLotIdForDate($dateProduction)
{
    // Format: YYYYMMDD_XXX où XXX est un compteur séquentiel
    $dateStr = $dateProduction->format('Ymd');
    
    // Rechercher le dernier lot créé pour cette date
    $lastLot = DB::table('Utilisation')
        ->where('id_lot', 'LIKE', $dateStr . '-%')
        ->orderBy('id_lot', 'desc')
        ->first();
    
    if ($lastLot) {
        // Extraire le numéro séquentiel du dernier lot
        $lastLotParts = explode('-', $lastLot->id_lot);
        if (count($lastLotParts) >= 2) {
            $lastSequence = intval(end($lastLotParts));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
    } else {
        $newSequence = 1;
    }
    
    // Formater avec des zéros à gauche (3 chiffres)
    return $dateStr . '-' . str_pad($newSequence, 3, '0', STR_PAD_LEFT);
}

    private function historiser($message, $action)
    {
        // Implémentation basique de l'historisation
        Log::info($message, ['action' => $action, 'user_id' => Auth::id()]);
    }


public function calculProductionBoulangerie(Request $request)
{
    try {
        Log::info("=== Début du calcul de la production boulangerie ===");
        DB::beginTransaction();

        // Supprimer toutes les entrées liées aux produits boulangerie
        Log::info("Suppression des utilisations liées aux produits de catégorie 'boulangerie'.");
        Utilisation::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('Produit_fixes')
                ->whereColumn('Produit_fixes.code_produit', 'Utilisation.produit')
                ->where('Produit_fixes.categorie', 'boulangerie');
        })->delete();

        // Récupérer le début et la fin du mois

        $now = Carbon::now();
$start = $now->copy()->startOfMonth();
$end = $now->copy()->endOfMonth();

        Log::info("Période analysée : du {$start->toDateString()} au {$end->toDateString()}");

        // Boucle sur chaque jour du mois
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            Log::info("Traitement du jour : {$date->toDateString()}");

            $productions = ProductionSac::whereDate('date_production', $date)
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('production_produits')
                        ->join('Produit_fixes', 'production_produits.produit_id', '=', 'Produit_fixes.code_produit')
                        ->whereColumn('production_produits.production_sac_id', 'productions_sacs.id')
                        ->where('Produit_fixes.categorie', 'boulangerie');
                })
                ->get();

            Log::info("Nombre de productions trouvées : " . $productions->count());

            foreach ($productions as $production) {
                Log::info("Traitement de la production ID: {$production->id}, SAC: {$production->sac_id}");

                // Matières utilisées
                $sacMatieres = SacMatiere::where('sac_id', $production->sac_id)->get();
                Log::info("Matières trouvées pour le sac : " . $sacMatieres->count());

                $matieres = [];
                foreach ($sacMatieres as $sm) {
                    $matiereModel = Matiere::find($sm->matiere_id);
                    Log::info("Matière ID: {$sm->matiere_id}, quantité utilisée: {$sm->quantite_utilisee} {$matiereModel->unite_classique}");
                    $matieres[] = [
                        'id' => $sm->matiere_id,
                        'quantite' => $sm->quantite_utilisee,
                            'unite' => $sm->unite ?? $matiereModel->unite_minimale->toString(), // prendre l'unité du sac si dispo
                    ];
                }

                // Produits générés
                $prodProduits = ProductionProduit::where('production_sac_id', $production->id)->get();
                Log::info("Produits générés dans la production : " . $prodProduits->count());

                $produits = [];
                foreach ($prodProduits as $pp) {
                    Log::info("Produit ID: {$pp->produit_id}, quantité: {$pp->quantite}");
                    $produits[] = [
                        'id' => $pp->produit_id,
                        'quantite' => $pp->quantite,
                    ];
                }

                // Répartitions calculées
                $repartitionsCalculees = $this->calculerRepartitionAutomatique($produits);
                Log::info("Répartitions calculées : ", $repartitionsCalculees);

                // Conversion matières en kg
                $totalMatieres = 0;
                $matieresConvertiesKg = [];
                foreach ($matieres as $matiere) {
                    $matiereModel = Matiere::findOrFail($matiere['id']);
                    $quantiteKg = $this->convertirEnKg($matiere['quantite'], $matiere['unite']);
                    Log::info("Conversion matière ID: {$matiere['id']} - {$matiere['quantite']} {$matiere['unite']} = {$quantiteKg} kg");
                    $matieresConvertiesKg[] = [
                        'id' => $matiere['id'],
                        'quantite_originale' => $matiere['quantite'],
                        'unite_originale' => $matiere['unite'],
                        'quantite_kg' => $quantiteKg,
                        'matiere_model' => $matiereModel
                    ];
                    $totalMatieres += $quantiteKg;
                }
                Log::info("Total matières converties en kg: {$totalMatieres}");

                // Traitement produit par produit
                foreach ($repartitionsCalculees as $repartition) {
                    Log::info("Répartition produit ID: {$repartition['produit_id']} - quantité: {$repartition['quantite_produit']} - proportion: {$repartition['proportion']}");

                    $matieresProportionnelles = [];
                    foreach ($matieresConvertiesKg as $matiere) {
                        $quantiteProportionnelle = $matiere['quantite_kg'] * $repartition['proportion'];
                        $quantiteOriginale = $this->convertirDepuisKg($quantiteProportionnelle, $matiere['unite_originale']);
                        Log::info("Matière ID: {$matiere['id']} proportionnelle: {$quantiteOriginale} {$matiere['unite_originale']}");
                        $matieresProportionnelles[] = [
                            'matiere_id' => $matiere['id'],
                            'quantite' => $quantiteOriginale,
                            'unite' => $matiere['unite_originale']
                        ];
                    }

                    $fakeRequest = new Request();
                    $fakeRequest->merge([
                        'produit' => $repartition['produit_id'],
                        'quantite_produit' => $repartition['quantite_produit'],
                        'matieres' => $matieresProportionnelles,
                        'date_production' => $production->date_production->format('Y-m-d')
                    ]);

                    Log::info("Enregistrement de la production proportionnelle pour produit ID: {$repartition['produit_id']}");
                    $this->enregistrerProduction($fakeRequest, $production->producteur_id);
                }
            }
        }

        DB::commit();
        Log::info("=== Fin du calcul de la production boulangerie ===");

        $isFrench = session('locale', 'fr') === 'fr';
        $message = $isFrench 
            ? 'Calcul de la production boulangerie du mois effectué avec succès.'
            : 'Bakery production calculation for the month completed successfully.';

        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erreur lors du calcul de la production boulangerie : " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        
        $isFrench = session('locale', 'fr') === 'fr';
        $message = $isFrench 
            ? 'Erreur lors du calcul: ' . $e->getMessage()
            : 'Error during calculation: ' . $e->getMessage();
        
        return redirect()->back()->with('error', $message);
    }
}


}
