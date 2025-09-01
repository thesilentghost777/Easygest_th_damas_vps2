<?php
namespace App\Http\Controllers;
use App\Models\Production;
use App\Models\Daily_assignments;
use App\Models\Produit_fixes;
use App\Models\User;
use App\Models\AssignationMatiere;
use App\Models\Commande;
use App\Models\Production_suggerer_par_jour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  // Ajout de l'import
use App\Models\Utilisation;
use App\Models\Matiere;
use App\Models\ProduitStock;
use App\Services\UniteConversionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUtilisationRequest;
use App\Services\AdvancedProductionStatsService;
use App\Services\ProductionService;
use App\Services\ProducteurComparisonService;
use App\Services\ProductionStatsService;
use App\Services\LotGeneratorService;
use App\Services\PerformanceService;
use Illuminate\Support\Facades\Log;
use App\Models\ReservationMp;
use App\Traits\HistorisableActions;
use App\Http\Controllers\NotificationController;
class ProducteurController extends Controller  // Hérite de Controller
{

    use HistorisableActions;
    protected $statsService;
    protected $conversionService;
    protected $productionService;
    protected $uniteConversionService;
    protected $lotGeneratorService;

    public function __construct(
        AdvancedProductionStatsService $statsService,
        UniteConversionService $uniteConversionService,
        ProductionService $productionService,
        LotGeneratorService $lotGeneratorService,
        ProductionStatsService $productionStatsService,
        NotificationController $notificationController
    ) {
        $this->statsService = $statsService;
        $this->uniteConversionService = $uniteConversionService;
        $this->conversionService = $uniteConversionService;
        $this->productionService = $productionService;
        $this->lotGeneratorService = $lotGeneratorService;
        $this->productionStatsService = $productionStatsService;
        $this->notificationController = $notificationController;
    }


    public function produit()
    {
        $employe = Auth::user();
        if (!$employe) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }

        // Récupérer le rôle
        $role = $employe->role === 'patissier' ? 'patisserie' :
        ($employe->role === 'boulanger' ? 'boulangerie' :
        ($employe->role === 'glace' ? 'glace' : ''));
        // Récupérer les données via le service
        $productions = $this->productionService->getTodayProductions($employe->id);
        $productions_attendues = $this->productionService->getExpectedProductions($employe->id);
        $productions_recommandees = $this->productionService->getRecommendedProductions();

        // Données complémentaires
        $all_produits = Produit_fixes::where('categorie', $role)->get();
        if ($employe->secteur === 'administration') {
            $all_produits = Produit_fixes::all();
        }
        $info = User::find($employe->id);

        return view('pages.producteur.producteur_produit', [
            'p' => $productions,
            'all_produits' => $all_produits,
            'heure_actuelle' => now(),
            'nom' => $info->name,
            'secteur' => $info->secteur,
            'productions_attendues' => $productions_attendues,
            'productions_recommandees' => $productions_recommandees,
            'day' => strtolower(now()->locale('fr')->dayName)
        ]);
    }

    public function produit_par_lot()
    {
        $utilisations = DB::table('Utilisation')
        ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
        ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
        ->select(
            'Utilisation.id_lot',
            'Produit_fixes.nom as nom_produit',
            'Produit_fixes.prix as prix_produit',
            'Utilisation.quantite_produit',
            'Matiere.nom as nom_matiere',
            'Matiere.prix_par_unite_minimale',
            'Utilisation.quantite_matiere',
            'Utilisation.unite_matiere'
        )
        ->orderBy('Utilisation.id_lot')
        ->get();

    $productionsParLot = [];

    foreach ($utilisations as $utilisation) {
        $idLot = $utilisation->id_lot;
        $nomProduit = $utilisation->nom_produit;

        if (!isset($productionsParLot[$idLot])) {
            $productionsParLot[$idLot] = [
                'produit' => $nomProduit,
                'quantite_produit' => $utilisation->quantite_produit,
                'prix_unitaire' => $utilisation->prix_produit,
                'matieres' => [],
                'valeur_production' => $utilisation->quantite_produit * $utilisation->prix_produit,
                'cout_matieres' => 0
            ];
        }

        $productionsParLot[$idLot]['matieres'][] = [
            'nom' => $utilisation->nom_matiere,
            'quantite' => $utilisation->quantite_matiere,
            'unite' => $utilisation->unite_matiere,
            'cout' => $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale
        ];

        $productionsParLot[$idLot]['cout_matieres'] +=
            $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
    }

    #prenoms maximun 75 production par lot
    $productionsParLot = array_slice($productionsParLot, 0, 75, true);
         $info = Auth::user();
        $nom = $info->name;
        $secteur = $info->secteur;

    return view('pages.producteur.produit_par_lot', compact('productionsParLot','nom','secteur'));
    }

    


private function getPeriode(): array
{
    return [
        'debut' => now()->startOfMonth(),
        'fin' => now()->endOfMonth(),
        'mois_actuel' => now()->format('F Y')
    ];
}


    public function commande() 
    {
    // Vérification de l'authentification
    $employe = auth()->user();
    if (!$employe) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter');
    }

    // Définition du rôle avec tableau associatif (plus propre)
    $roles = [
        'patissier' => 'patisserie',
        'boulanger' => 'boulangerie',
        'glace' => 'glace'
    ];
    $role = $roles[$employe->role] ?? '';

    // Récupération des infos utilisateur
    $info = User::where('id', $employe->id)->first();
    $nom = $info->name;
    $secteur = $info->secteur;

    // Récupération des commandes
    $commandes = Commande::where('categorie', $role)->where('valider',0)->get();
    return view('pages/producteur/producteur_commande', compact('nom', 'secteur', 'commandes'));
}


    public function reserverMp() {
        $employe = auth()->user();
        if (!$employe) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }
        $info = User::where('id', $employe->id)->first();
        $nom = $info ->name;
        $secteur = $info->secteur;
        return view('pages/producteur/producteur_reserverMp',compact('nom','secteur'));

    }


    public function stat_prod()
    {
        $employe = Auth::user();
        if (!$employe) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }

        $stats = $this->statsService->getStats($employe->id);

        return view('pages.producteur.stat_prod', [
            'stats' => $stats,
            'nom' => $employe->name,
            'secteur' => $employe->secteur
        ]);
    }

    public function create()
{
    $producteur = Auth::user();
    $produits = Produit_fixes::all();
    $role = $producteur->role;
    $produits = $produits->filter(function ($produit) use ($role) {
        // Filtrer les produits en fonction du rôle de l'utilisateur
        if ($role === 'patissier') {
            return $produit->categorie === 'patisserie';
        } elseif ($role === 'boulanger') {
            return $produit->categorie === 'boulangerie';
        } elseif ($role === 'glace') {
            return $produit->categorie === 'glace';
        }
        return false; // Si le rôle ne correspond à aucun, ne pas inclure le produit
    }); 
    if (Auth::user()->secteur === 'administration') {
            $produits = Produit_fixes::all();
        }
    // Dates de référence
    $today = now()->startOfDay();
    $yesterday = now()->subDay()->startOfDay();

    // Récupérer toutes les matières sauf celles dont le nom commence par 'Taule'
    // et qui ont été créées avant hier
    
    $matieres = Matiere::where('nom', 'not like', 'Taule%')->get();


    $info = Auth::user();
    $nom = $info->name;
    $secteur = $info->secteur;

    return view('pages.producteur.produitmp', compact('produits', 'matieres', 'nom', 'secteur'));
}

public function store2(StoreUtilisationRequest $request)
{
    try {
        DB::beginTransaction();

        $producteurId = Auth::id();
        $errors = [];
        $conversionService = $this->uniteConversionService;
        $coutTotal = 0;

        // Traitement de la date de production personnalisée
        $dateProduction = $request->date_production;
        if ($dateProduction) {
            // Si une date personnalisée est fournie, créer un DateTime à 15:00
            $dateProductionFormatted = Carbon::createFromFormat('Y-m-d', $dateProduction)->setTime(15, 0, 0);
            
            // Utiliser la génération intelligente pour la date spécifiée
            $lotId = $this->generateLotIdForDate($dateProductionFormatted);
        } else {
            // Utiliser la méthode habituelle si aucune date n'est spécifiée
            $dateProductionFormatted = now()->setTime(15, 0, 0);
            $lotId = $this->lotGeneratorService->generateLotId();
        }

        // Vérifier que la quantité produite est positive
        if ($request->quantite_produit <= 0) {
            DB::rollBack();
            return redirect()->back()->with('error', 'La quantité produite doit être positive')->withInput();
        }

        foreach ($request->matieres as $index => $matiere) {
            Log::info('Début traitement matière', [
                'index' => $index, 
                'matiere_request' => $matiere,
                'matiere_id' => $matiere['matiere_id']
            ]);
            
            $matiereModel = Matiere::findOrFail($matiere['matiere_id']);
            Log::info('Matière trouvée', [
                'matiere_model' => $matiereModel->toArray(), 
                'unite_minimale' => $matiereModel->unite_minimale
            ]);
            
            // Conversion de la quantité demandée vers l'unité minimale si pas déjà en unité minimale
            Log::info('Vérification des unités', [
                'unite_demandee' => $matiere['unite'],
                'unite_minimale' => $matiereModel->unite_minimale,
                'quantite_demandee' => $matiere['quantite']
            ]);
            
            if ($matiere['unite'] !== $matiereModel->unite_minimale) {
                Log::info('Conversion nécessaire', [
                    'quantite_avant' => $matiere['quantite'],
                    'unite_avant' => $matiere['unite'],
                    'unite_cible' => $matiereModel->unite_minimale
                ]);
                
                try {
                    $quantiteConvertie = $conversionService->convertir(
                        $matiere['quantite'],
                        $matiere['unite'],
                        $matiereModel->unite_minimale
                    );
                    Log::info('Conversion réussie', [
                        'quantite_convertie' => $quantiteConvertie,
                        'unite_minimale' => $matiereModel->unite_minimale
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur de conversion', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $quantiteConvertie = $matiere['quantite'];
                    Log::warning('Utilisation de la quantité non convertie', ['quantite' => $quantiteConvertie]);
                }
            } else {
                $quantiteConvertie = $matiere['quantite'];
                Log::info('Aucune conversion nécessaire', ['quantite' => $quantiteConvertie]);
            }
            
            // Vérifier si la quantité de matière est positive
            Log::info('Vérification quantité positive', ['quantite_convertie' => $quantiteConvertie]);
            if ($quantiteConvertie <= 0) {
                Log::warning('Quantité non positive détectée', [
                    'matiere' => $matiereModel->nom,
                    'quantite' => $quantiteConvertie
                ]);
                $errors[] = "La quantité de '{$matiereModel->nom}' doit être positive.";
                continue;
            }
            
            // Créer l'enregistrement d'utilisation avec la date personnalisée
            $utilisation = new Utilisation();
            $utilisation->id_lot = $lotId;
            $utilisation->produit = $request->produit;
            $utilisation->matierep = $matiere['matiere_id'];
            $utilisation->producteur = $producteurId;
            $utilisation->quantite_produit = $request->quantite_produit;
            $utilisation->quantite_matiere = $quantiteConvertie;
            $utilisation->unite_matiere = is_object($matiereModel->unite_minimale) ? $matiereModel->unite_minimale->value : $matiereModel->unite_minimale;
            $utilisation->created_at = $dateProductionFormatted;
            $utilisation->updated_at = $dateProductionFormatted;
            $utilisation->save();

            // Ajouter au coût total des matières
            $coutMatiere = $quantiteConvertie * $matiereModel->prix_par_unite_minimale;
            $coutTotal += $coutMatiere;
        }

        // S'il y a des erreurs, annuler la transaction
        if (!empty($errors)) {
            DB::rollBack();
            return redirect()->back()->with('error', implode('<br>', $errors))->withInput();
        }

        // Récupérer le produit pour calculer la valeur de la production
        $produit = DB::table('Produit_fixes')->where('code_produit', $request->produit)->first();
        if (!$produit) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Produit non trouvé')->withInput();
        }

        // Calculer la valeur totale de la production
        $valeurTotale = $request->quantite_produit * $produit->prix;
        
        // Calculer le bénéfice
        $benefice = $valeurTotale - $coutTotal;

        // Mettre à jour le stock de produits
        $produitStock = ProduitStock::firstOrNew(['id_produit' => $request->produit]);
        $produitStock->quantite_en_stock += $request->quantite_produit;
        $produitStock->save();

        // Historiser l'opération avec la date de production
        $infoProducteur = User::findOrFail($producteurId);
        $dateFormatee = $dateProductionFormatted->format('d/m/Y à H:i');
        $this->historiser(
            "Production du lot {$lotId} par {$infoProducteur->name} le {$dateFormatee}: {$request->quantite_produit} unités de {$produit->nom}. Bénéfice: {$benefice} FCFA", 
            'create_production'
        );

        // Envoyer des notifications en fonction du bénéfice
        $producteur = User::findOrFail($producteurId);
        $chefProduction = User::where('role', 'chef_production')->first();

        // Notification pour bénéfice négatif ou inférieur à 5000
        if ($benefice < 5000) {
            // Notification au producteur
            $request->merge([
                'recipient_id' => $producteurId,
                'subject' => 'Alerte - Bénéfice faible sur production',
                'message' => "Votre production du lot {$lotId} effectuée le {$dateFormatee} a généré un bénéfice de {$benefice} FCFA, ce qui est inférieur au seuil de rentabilité recommandé de 5000 FCFA. Nous vous invitons à revoir votre processus de production pour optimiser les coûts."
            ]);
            $this->notificationController->send($request);

            // Notification au chef de production si disponible
            if ($chefProduction) {
                $request->merge([
                    'recipient_id' => $chefProduction->id,
                    'subject' => 'Alerte - Production à faible rentabilité',
                    'message' => "La production du lot {$lotId} par {$producteur->name} effectuée le {$dateFormatee} a généré un bénéfice de seulement {$benefice} FCFA, ce qui est inférieur au seuil de rentabilité recommandé de 5000 FCFA."
                ]);
                $this->notificationController->send($request);
            }
        }
        // Notification pour bénéfice supérieur à 25000
        elseif ($benefice > 25000) {
            // Notification au producteur
            $request->merge([
                'recipient_id' => $producteurId,
                'subject' => 'Félicitations - Production très rentable',
                'message' => "Votre production du lot {$lotId} effectuée le {$dateFormatee} a généré un excellent bénéfice de {$benefice} FCFA, dépassant le seuil de haute rentabilité de 25000 FCFA. Félicitations pour cette performance remarquable!"
            ]);
            $this->notificationController->send($request);

            // Notification au chef de production si disponible
            if ($chefProduction) {
                $request->merge([
                    'recipient_id' => $chefProduction->id,
                    'subject' => 'Performance exceptionnelle - Production très rentable',
                    'message' => "La production du lot {$lotId} par {$producteur->name} effectuée le {$dateFormatee} a généré un excellent bénéfice de {$benefice} FCFA, dépassant le seuil de haute rentabilité de 25000 FCFA."
                ]);
                $this->notificationController->send($request);
            }
        }
        
        DB::commit();
        return redirect()->back()->with('success', 'Production enregistrée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())->withInput();
    }
}

/**
 * Génère un ID de lot pour une date spécifique
 * (Méthode réutilisée du contrôleur précédent)
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

/**
 * Génère un ID de lot intelligent basé sur la date de production
 * @param Carbon $dateProduction
 * @return string
 */
private function generateIntelligentLotId(Carbon $dateProduction): string
{
    try {
        // Format de base pour le lot: YYYYMMDD-XXX (où XXX est un numéro séquentiel)
        $dateStr = $dateProduction->format('Ymd');
        
        // Rechercher le dernier lot créé pour cette date
        $dernierLot = DB::table('utilisations')
            ->whereDate('created_at', $dateProduction->toDateString())
            ->where('id_lot', 'like', $dateStr . '-%')
            ->orderBy('id_lot', 'desc')
            ->value('id_lot');
        
        if ($dernierLot) {
            // Extraire le numéro séquentiel du dernier lot
            $parts = explode('-', $dernierLot);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $numeroSequentiel = intval($parts[1]) + 1;
            } else {
                $numeroSequentiel = 1;
            }
        } else {
            // Aucun lot trouvé pour cette date, commencer à 1
            $numeroSequentiel = 1;
        }
        
        // Formater le numéro sur 3 chiffres
        $lotId = $dateStr . '-' . str_pad($numeroSequentiel, 3, '0', STR_PAD_LEFT);
        
        // Vérifier que cet ID n'existe pas déjà (sécurité supplémentaire)
        $existingLot = DB::table('utilisations')->where('id_lot', $lotId)->first();
        if ($existingLot) {
            // Si l'ID existe déjà, utiliser la méthode de génération habituelle
            Log::warning('ID de lot déjà existant, utilisation de la méthode habituelle', ['lot_id' => $lotId]);
            return $this->lotGeneratorService->generateLotId();
        }
        
        Log::info('ID de lot intelligent généré', [
            'lot_id' => $lotId,
            'date_production' => $dateProduction->toDateString(),
            'numero_sequentiel' => $numeroSequentiel
        ]);
        
        return $lotId;
        
    } catch (\Exception $e) {
        // En cas d'erreur, utiliser la méthode de génération habituelle
        Log::error('Erreur lors de la génération intelligente du lot ID', [
            'message' => $e->getMessage(),
            'date_production' => $dateProduction->toDateString()
        ]);
        
        return $this->lotGeneratorService->generateLotId();
    }
}

public function comparaison(Request $request)
{
    $employe = auth()->user();
    if (!$employe) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter');
    }

    $critere = $request->input('critere', 'benefice');
    $periode = $request->input('periode', 'mois');
    $dateDebut = $request->input('date_debut');
    $dateFin = $request->input('date_fin');

    $comparisonService = app(ProducteurComparisonService::class);
    $resultats = $comparisonService->compareProducteurs($critere, $periode, $dateDebut, $dateFin);

    return view('pages.producteur.comparaison', [
        'resultats' => $resultats,
        'critere' => $critere,
        'periode' => $periode,
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin
    ]);
}

public function fiche_production()
{
    $employe = auth()->user();
    if (!$employe) {
        return redirect()->route('login')->with('error', 'Veuillez vous connecter');
    }

    $userId = auth()->id();
    $stats = $this->statsService->getStats($userId);

    // Calcul des statistiques globales
    $globalStats = $this->calculateGlobalStats($stats);

    // Calcul des appréciations
    $appreciations = $this->calculateAppreciations($globalStats, $stats);
    $info = User::where('id', $employe->id)->first();
    $nom = $info ->name;
    $secteur = $info->secteur;
    $age = $info->age;
    return view('pages.producteur.producteur_fiche_production', compact('stats', 'globalStats', 'appreciations','nom', 'secteur', 'age'));
}

private function calculateGlobalStats($stats)
{
    $products = collect($stats['products']);

    return [
        'max_production' => [
            'produit' => $products->sortByDesc('produit.quantite_totale')->first(),
            'valeur' => $products->max('produit.quantite_totale')
        ],
        'max_benefice' => [
            'produit' => $products->sortByDesc('benefice')->first(),
            'valeur' => $products->max('benefice')
        ],
        'max_perte' => [
            'produit' => $products->sortBy('benefice')->first(),
            'valeur' => $products->min('benefice')
        ],
        'meilleur_jour' => [
            'date' => collect($stats['daily']['quantities'])->search(collect($stats['daily']['quantities'])->max()),
            'quantite' => collect($stats['daily']['quantities'])->max()
        ],
        'meilleur_mois' => [
            'date' => collect($stats['monthly']['quantities'])->search(collect($stats['monthly']['quantities'])->max()),
            'quantite' => collect($stats['monthly']['quantities'])->max()
        ],
        'total_benefice' => $products->sum('benefice'),
        'moyenne_marge' => $products->avg('marge')
    ];
}

private function calculateAppreciations($globalStats, $stats)
{
    $appreciations = [];

    // Appréciation de la rentabilité
    if ($globalStats['moyenne_marge'] > 30) {
        $appreciations['rentabilite'] = 'Excellente rentabilité';
    } elseif ($globalStats['moyenne_marge'] > 20) {
        $appreciations['rentabilite'] = 'Bonne rentabilité';
    } else {
        $appreciations['rentabilite'] = 'Rentabilité à améliorer';
    }

    // Tendance production
    $recentQuantities = array_slice($stats['daily']['quantities']->toArray(), 0, 3);
    if (array_sum($recentQuantities) > 0) {
        $appreciations['tendance'] = 'Production en hausse';
    } else {
        $appreciations['tendance'] = 'Production en baisse';
    }

    return $appreciations;
}
}

