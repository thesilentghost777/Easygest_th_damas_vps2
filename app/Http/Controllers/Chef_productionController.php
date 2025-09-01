<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use App\Models\MatiereRecommander;
use App\Enums\UniteMinimale;
use App\Enums\UniteClassique;
use App\Http\Requests\MatierePremRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;
use App\Models\ManquantTemporaire;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Daily_assignments;
use App\Models\AssignationsMatiere;
use \App\Models\ProduitStock;
use Carbon\Carbon;
use App\Models\ACouper;
use App\Http\Controllers\NotificationController;
use App\Traits\HistorisableActions;

class Chef_productionController extends Controller
{


    use HistorisableActions;

    public function __construct(NotificationController $notificationController)
	{
    		$this->notificationController = $notificationController;
	}
    public function gestionProduits()
    {
        $employe = Auth::user();
        $nom = $employe->name;
        $role = $employe->role;
        $produits = Produit_fixes::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.chef_production.gestion_produits', compact('produits','nom','role'));
    }

    public function storeProduit(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:50',
                'prix' => 'required|numeric|min:0',
                'categorie' => 'required|string|in:boulangerie,patisserie,glace'
            ]);

            DB::beginTransaction();

            $produit = Produit_fixes::create($validated);

        // Créer l'entrée correspondante dans produit_stocks avec des quantités à 0
        DB::table('produit_stocks')->insert([
            'id_produit' => $produit->code_produit,
            'quantite_en_stock' => 0,
            'quantite_invendu' => 0,
            'quantite_avarie' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user = auth()->user();
        $request->merge([
            'recipient_id' => $user->id,
            'subject' => 'Nouveau produit créé',
            'message' => 'Le produit ' . $produit->nom . 'A été créé avec succès. Veuillez définir la recette pour cette dernière et les matières recommandées.'
        ]);
        // Appel de la méthode send
        $this->notificationController->send($request);
        $this->historiser("L'utilisateur {$user->name} a créé le produit {$validated['nom']}", 'create_produit');
            DB::commit();
            return redirect()->back()->with('success', 'Produit ajouté avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Erreur de validation: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'ajout du produit: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de l\'ajout du produit: ' . $e->getMessage()])
                ->withInput();
        }

    }
    public function updateProduit(Request $request, $code_produit)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:50',
                'prix' => 'required|numeric|min:0',
                'categorie' => 'required|string|in:boulangerie,patisserie,glace'
            ]);

            DB::beginTransaction();

            $produit = Produit_fixes::where('code_produit', $code_produit)->firstOrFail();
            $produit->update($validated);

            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour le produit " . $produit->nom, 'update_produit');
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Produit mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyProduit($code_produit)
    {
        try {
            DB::beginTransaction();

            $produit = Produit_fixes::where('code_produit', $code_produit)->firstOrFail();

            // Vérifier les relations
            if ($produit->utilisations()->exists() ||
                DB::table('Commande')->where('produit', $code_produit)->exists()) {
                throw new \Exception(
                    "Impossible de supprimer le produit « {$produit->nom} » car il est actuellement " .
                    "utilisé dans des commandes ou des productions en cours. " .
                    "Veuillez d'abord supprimer toutes les références à ce produit avant de le supprimer."
                );
            }

            $produit->delete();
            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé le produit " . $produit->nom, 'delete_produit');
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Produit supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Gestion des Matières Premières
    public function gestionMatieres()
    {
        $employe = Auth::user();
        $nom = $employe->name;
        $role = $employe->role;
        $matieres = Matiere::where('nom', 'NOT LIKE', 'Taule%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $unites_minimales = UniteMinimale::values();
        $unites_classiques = UniteClassique::values();

        return view('pages.chef_production.gestion_matieres', compact('matieres', 'unites_minimales', 'unites_classiques','nom','role'));
    }

    public function storeMatiere(MatierePremRequest $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:50',
                'quantite_par_unite' => 'required|numeric|min:0',
                'quantite' => 'required|numeric|min:0',
                'prix_unitaire' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Vérifier si le nom existe déjà
            if (Matiere::where('nom', $request->nom)->exists()) {
                return redirect()->back()->with('Error', 'Une matière avec ce nom existe déjà');
            }

            // Validation supplémentaire des unités compatibles
            $unites_permises = UniteMinimale::getUniteClassiquePermise($request->unite_minimale);
            if (!in_array($request->unite_classique, $unites_permises)) {
                return redirect()->back()->with('error', 'Combinaison d\'unités invalide');
            }

            // Créer la matière
            $matiere = Matiere::create($request->validated());

            // Ajouter comme ingrédient dans la table ingredients
            Ingredient::create([
                'name' => $matiere->nom,
                'unit' => $request->unite_classique
            ]);

            // Historiser
            $user = auth()->user();
            $date = Carbon::now();
            $this->historiser("La matière première '{$matiere->nom}' a été créée par {$user->name}", 'create_matiere');

            DB::commit();
            return redirect()->back()->with('success', 'Matière première ajoutée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'ajout: ' . $e->getMessage()]);
        }
    }

    public function updateMatiere(MatierePremRequest $request, Matiere $matiere)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:50',
                'quantite_par_unite' => 'required|numeric|min:0',
                'quantite' => 'required|numeric|min:0',
                'prix_unitaire' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Vérifier si le nom existe déjà (sauf pour cette matière)
            if (Matiere::where('nom', $request->nom)->where('id', '!=', $matiere->id)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Une matière avec ce nom existe déjà']);
            }

            // Validation supplémentaire des unités compatibles
            $unites_permises = UniteMinimale::getUniteClassiquePermise($request->unite_minimale);
            if (!in_array($request->unite_classique, $unites_permises)) {
                return redirect()->back()->withErrors(['error' => 'Combinaison d\'unités invalide']);
            }

            // Sauvegarder l'ancien nom pour la recherche dans la table ingredients
            $oldName = $matiere->nom;

            // Mettre à jour la matière
            $matiere->update($request->validated());

            // Mettre à jour l'ingrédient correspondant
            $ingredient = Ingredient::where('name', $oldName)->first();
            if ($ingredient) {
                $ingredient->name = $matiere->nom;
                $ingredient->unit = $request->unite_classique;
                $ingredient->save();
            } else {
                // Si l'ingrédient n'existe pas, le créer
                Ingredient::create([
                    'name' => $matiere->nom,
                    'unit' => $request->unite_classique
                ]);
            }

            // Historiser
            $user = auth()->user();
            $date = Carbon::now();
            $this->historiser("La matière première '{$matiere->nom}' a été mise à jour par {$user->name}", 'update_matiere');

            DB::commit();
            return redirect()->back()->with('success', 'Matière première mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    public function editMatiere($id)
{
    try {
        $matiere = Matiere::findOrFail($id);
        return response()->json($matiere);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Matière première non trouvée'], 404);
    }
}
    public function destroyMatiere(Matiere $matiere)
    {
        try {
            DB::beginTransaction();
            $matiere->delete();
            DB::commit();

            // Historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé la matière première " . $matiere->nom, 'delete_matiere');
            return redirect()->back()->with('success', 'Matière première supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    public function dashboard() {
        return view('pages.chef_production.chef_production_dashboard');
    }

    public function createcommande()
    {
        $employe = Auth::user();
        $nom = $employe->name;
        $role = $employe->role;
        $produits = Produit_fixes::orderBy('nom', 'asc')->get();
        $commandes = Commande::all();
        return view('pages.chef_production.ajouter-commande', compact('produits', 'commandes', 'role', 'nom'));
    }

    
    public function storeCommande(Request $request)
    {
        try {
            // Validate basic order information
            $validated = $request->validate([
                'libelle' => 'required|string|max:50',
                'date_commande' => 'required|date',
                'produits' => 'required|array|min:1',
                'produits.*.id' => 'required|exists:Produit_fixes,code_produit',
                'produits.*.quantite' => 'required|integer|min:1',
                'produits.*.categorie' => 'required|string'
            ]);

            Log::info('Données validées', ['data' => $validated]);

            $user = auth()->user();
            $createdCommandes = [];

            // Create one command for each product
            foreach ($validated['produits'] as $produitData) {
                // Get product information
                $produit = Produit_fixes::where('code_produit', $produitData['id'])->first();
                
                if (!$produit) {
                    throw new \Exception("Produit avec le code {$produitData['id']} non trouvé");
                }

                // Create new command
                $commande = new Commande();
                $commande->libelle = $validated['libelle'];
                $commande->produit = $produitData['id'];
                $commande->quantite = $produitData['quantite'];
                $commande->date_commande = $validated['date_commande'];
                
                // Set category based on product category
                $commande->categorie = $this->mapProductCategoryToCommandeCategory($produit->categorie);
                $commande->valider = false;

                $commande->save();
                $createdCommandes[] = $commande;

                Log::info('Commande créée', [
                    'id' => $commande->id,
                    'libelle' => $commande->libelle,
                    'produit' => $produit->nom,
                    'quantite' => $commande->quantite
                ]);
            }

            // Historize the action
            $totalProducts = count($validated['produits']);
            $this->historiser(
                "Une nouvelle commande '{$validated['libelle']}' avec {$totalProducts} produit(s) a été créée par {$user->name}",
                'create_commande'
            );

            // Group commands by category for notifications
            $commandesByCategory = [];
            foreach ($createdCommandes as $commande) {
                if (!isset($commandesByCategory[$commande->categorie])) {
                    $commandesByCategory[$commande->categorie] = [];
                }
                $commandesByCategory[$commande->categorie][] = $commande;
            }

            // Notify pointeurs (users with 'pointeur' role)
            $pointeurs = User::where('role', 'pointeur')->get();
            foreach ($pointeurs as $pointeur) {
                $productsList = $this->buildProductsListForNotification($createdCommandes);
                $request->merge([
                    'recipient_id' => $pointeur->id,
                    'subject' => 'Nouvelle commande à valider',
                    'message' => "Une nouvelle commande '{$validated['libelle']}' a été créée et nécessite votre validation. Produits: {$productsList}"
                ]);

                $this->notificationController->send($request);
            }

            // Notify producers by category
            foreach ($commandesByCategory as $category => $commandes) {
                $roleMapping = [
                    'patisserie' => 'patissier',
                    'boulangerie' => 'boulanger',
                    'glace' => 'glace'
                ];

                $role = $roleMapping[$category] ?? $category;
                $producteurs = User::where('role', $role)->get();

                foreach ($producteurs as $producteur) {
                    $productsList = $this->buildProductsListForNotification($commandes);
                    $request->merge([
                        'recipient_id' => $producteur->id,
                        'subject' => 'Nouvelle commande pour votre catégorie',
                        'message' => "Une nouvelle commande '{$validated['libelle']}' a été définie pour la catégorie {$category}. Produits: {$productsList}"
                    ]);

                    $this->notificationController->send($request);
                }
            }

            return redirect()->back()->with('success', 
                "Commande '{$validated['libelle']}' créée avec succès ({$totalProducts} produit(s))"
            );

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la commande : ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Map product category to commande category
     */
    private function mapProductCategoryToCommandeCategory($productCategory)
    {
        $mapping = [
            'patisserie' => 'patisserie',
            'boulangerie' => 'boulangerie',
            'glace' => 'glace',
            'patissier' => 'patisserie',
            'boulanger' => 'boulangerie',
            'glacier' => 'glace'
        ];

        return $mapping[strtolower($productCategory)] ?? 'boulangerie';
    }

    /**
     * Build a formatted list of products for notifications
     */
    private function buildProductsListForNotification($commandes)
    {
        $productsList = [];
        foreach ($commandes as $commande) {
            $produit = $commande->produit_fixe;
            if ($produit) {
                $productsList[] = "{$commande->quantite}x {$produit->nom}";
            }
        }
        return implode(', ', $productsList);
    }

    /**
     * Historize an action (you'll need to implement this method based on your history system)
     */
    private function historiser($message, $action)
    {
        // Implement your history logging logic here
        Log::info("HISTORIQUE: {$message}", ['action' => $action]);
    }


    public function validateCommande($id)
    {
        try {
            $commande = Commande::findOrFail($id);

            // Check if command is already validated
            if ($commande->valider) {
                return redirect()->back()->with('error', 'Cette commande a déjà été validée');
            }

            // Get product stock
            $stock = ProduitStock::where('id_produit', $commande->produit)->first();

            if (!$stock || $stock->quantite_en_stock < $commande->quantite) {
                return redirect()->back()->with('error', 'Stock insuffisant pour valider cette commande');
            }

            // Update stock
            $stock->quantite_en_stock -= $commande->quantite;
            $stock->save();

            // Update command status
            $commande->valider = true;
            $commande->save();

            // Historize the action
            $user = auth()->user();
            $this->historiser("La commande de {$commande->quantite} {$commande->libelle} a été validée par {$user->name}", 'update');

            // Notify chef_production
            $chefProduction = \App\Models\User::where('role', 'chef_production')->get();

            foreach ($chefProduction as $chef) {
                $request->merge([
                    'recipient_id' => $chef->id,
                    'subject' => 'Commande validée et livrée',
                    'message' => "La commande #{$commande->id} de {$commande->quantite} {$commande->libelle} a été validée et livrée. Le stock a été mis à jour en conséquence."
                ]);

                $this->notificationController->send($request);
            }

            //historiser l'action
            $this->historiser("La commande de {$commande->quantite} {$commande->libelle} a été validée et livrée par {$user->name}", 'validate_commande');
            return redirect()->back()->with('success', 'Commande validée avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la validation de la commande : ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editcommande($id)
    {
        $commande = Commande::findOrFail($id);
        $produits = Produit_fixes::all();
        return view('pages.chef_production.modifier-commande', compact('commande', 'produits'));
    }

    public function updatecommande(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:50',
            'produit' => 'required|exists:Produit_fixes,code_produit',
            'quantite' => 'required|integer|min:1',
            'date_commande' => 'required|date',
            'categorie' => 'required|string'
        ]);
        #si la commande est deja valider , retourner une erreur
        $commande = Commande::findOrFail($id);
        if ($commande->valider == 1) {
            return redirect()->route('chef.commandes.create')->with('error', 'Cette commande a déjà été validée');
        }

        $commande->update($validated);
        //historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour la commande de {$commande->quantite} {$commande->libelle} pour la catégorie {$commande->categorie}", 'update_commande');
        return redirect()->route('chef.commandes.create')->with('success', 'Commande mise à jour avec succès');
    }

    public function destroycommande($id)
    {
        try {
            $commande = Commande::findOrFail($id);
            $commande->delete();
            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé la commande de {$commande->quantite} {$commande->libelle} pour la catégorie {$commande->categorie}", 'delete_commande');
            return response()->json(['status' => 'success', 'message' => 'Commande supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
        }
    }

    public function index()
    {
        $employe = Auth::user();
        if (!$employe) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter');
        }
        $nom = $employe->name;
        $role = $employe->role;
        $today = Carbon::today();

        // Production aujourd'hui
        $productionJour = $this->getProductionJournaliere();

        // Bénéfice brut
        $beneficeBrut = $this->getBeneficeBrut();

        // Rendement
        $rendementData = $this->getRendement();

        // Pertes
        $pertes = $this->getPertes();

        // Gaspillage
        $gaspillage = $this->getGaspillageMatiere();

        // Données pour les graphiques
        $graphData = $this->getGraphData();

        // Productions en cours
        $productionsEnCours = $this->getProductionsEnCours();

        // Liste des produits pour le formulaire d'assignation
        $produits = Produit_fixes::all();
        $producteurs = User::where('role', 'boulanger')
        ->orWhere('role', 'patissier')
        ->orWhere('role', 'glace')
        ->get();


        return view('pages.chef_production.chef_production_dashboard', compact(
            'productionJour',
            'beneficeBrut',
            'rendementData',
            'pertes',
            'gaspillage',
            'graphData',
            'productionsEnCours',
            'produits',
            'producteurs',
            'nom',
            'role'
        ));
    }

    private function getProductionJournaliere()
    {
        $utilisations = DB::table('Utilisation')
            ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->select(
                'Utilisation.id_lot',
                'Utilisation.quantite_produit'
            )
            ->whereDate('Utilisation.created_at', Carbon::today())
            ->get();

        $productionsParLot = [];
        $totalProduction = 0;

        foreach ($utilisations as $utilisation) {
            $idLot = $utilisation->id_lot;

            // Si ce lot n'a pas encore été traité, on ajoute sa production au total
            if (!isset($productionsParLot[$idLot])) {
                $productionsParLot[$idLot] = true;
                $totalProduction += $utilisation->quantite_produit;
            }
        }

        return $totalProduction;
    }

    // Méthode mise à jour pour calculer correctement le bénéfice brut par lot
    private function getBeneficeBrut()
    {
        try {
            $utilisations = DB::table('Utilisation')
                ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
                ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
                ->select(
                    'Utilisation.id_lot',
                    'Produit_fixes.prix as prix_produit',
                    'Utilisation.quantite_produit',
                    'Matiere.prix_par_unite_minimale',
                    'Utilisation.quantite_matiere'
                )
                ->whereDate('Utilisation.created_at', Carbon::today())
                ->get();

            $productionsParLot = [];
            $beneficeBrutTotal = 0;

            foreach ($utilisations as $utilisation) {
                $idLot = $utilisation->id_lot;

                if (!isset($productionsParLot[$idLot])) {
                    $productionsParLot[$idLot] = [
                        'quantite_produit' => $utilisation->quantite_produit,
                        'prix_unitaire' => $utilisation->prix_produit,
                        'valeur_production' => $utilisation->quantite_produit * $utilisation->prix_produit,
                        'cout_matieres' => 0
                    ];
                }

                // Accumule le coût des matières pour ce lot
                $productionsParLot[$idLot]['cout_matieres'] +=
                    $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
            }

            // Calcul du bénéfice brut total (valeur production - coût matières) pour tous les lots
            foreach ($productionsParLot as $production) {
                $beneficeBrutTotal += ($production['valeur_production'] - $production['cout_matieres']);
            }

            return $beneficeBrutTotal;
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs
            \Log::error('Erreur lors du calcul du bénéfice brut : ' . $e->getMessage());
            return 0; // Retourner 0 en cas d'erreur
        }
    }


    private function getRendement()
    {
        $beneficeReel = $this->getBeneficeBrut();
        $pertes = $this->getPertes();
        $beneficeAttendu = DB::table('Daily_assignments as da')
            ->join('Produit_fixes as p', 'da.produit', '=', 'p.code_produit')
            ->whereDate('assignment_date', Carbon::today())
            ->select(DB::raw('SUM(da.expected_quantity * p.prix) as benefice_attendu'))
            ->value('benefice_attendu') ?? 0;

        return [
            'pourcentage' => $beneficeAttendu > 0 ? (($beneficeReel+$pertes) / $beneficeAttendu) * 100 : 0,
            'reel' => $beneficeReel,
            'attendu' => $beneficeAttendu
        ];
    }

    private function getPertes()
    {
            $coutMatieresUtilisees = DB::table('Utilisation as u')
                ->join('Matiere as m', 'u.matierep', '=', 'm.id')
                ->whereDate('u.created_at', Carbon::today())
                ->select(DB::raw('SUM(u.quantite_matiere * m.prix_par_unite_minimale) as cout_total'))
                ->value('cout_total') ?? 0;

            return $coutMatieresUtilisees;
        }


        private function getGaspillageMatiere()
        {
            $today = Carbon::today();

            // Récupérer les utilisations groupées par produit, matière première et heure
            $utilisations = DB::table('Utilisation as u')
                ->join('Matiere as m', 'u.matierep', '=', 'm.id')
                ->join('Produit_fixes as p', 'u.produit', '=', 'p.code_produit')
                ->select(
                    DB::raw('HOUR(u.created_at) as heure'),
                    'u.produit',
                    'u.matierep',
                    DB::raw('SUM(u.quantite_matiere) as total_utilisee')
                )
                ->whereDate('u.created_at', $today)
                ->groupBy(DB::raw('HOUR(u.created_at)'), 'u.produit', 'u.matierep')
                ->get();

            $gaspillageParHeure = [];

            foreach ($utilisations as $utilisation) {
                $heure = $utilisation->heure;
                $produitId = $utilisation->produit;
                $matiereId = $utilisation->matierep;
                $quantiteUtilisee = $utilisation->total_utilisee;

                $recommandation = DB::table('Matiere_recommander')
                    ->where('produit', $produitId)
                    ->where('matierep', $matiereId)
                    ->first();

                if ($recommandation) {
                    $quantiteRecommandee = $recommandation->quantite;

                    if ($quantiteUtilisee > $quantiteRecommandee) {
                        $gaspillage = ($quantiteUtilisee - $quantiteRecommandee) / $quantiteRecommandee * 100;

                        // Ajouter au gaspillage pour l'heure
                        if (!isset($gaspillageParHeure[$heure])) {
                            $gaspillageParHeure[$heure] = 0;
                        }
                        $gaspillageParHeure[$heure] += $gaspillage;
                    }
                }
            }

            // Assurez-vous de retourner un tableau avec les 24 heures de la journée
            $gaspillageParHeureComplet = [];
            for ($i = 0; $i < 24; $i++) {
                $gaspillageParHeureComplet[$i] = $gaspillageParHeure[$i] ?? 0;
            }

            return $gaspillageParHeureComplet;
        }




        public function getGraphData()
    {
        $today = Carbon::today();

        // Productions par heure - Regroupement par lot pour éviter les doublons
        $productions = DB::table('Utilisation')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%H:00:00") as timestamp'),
                'id_lot',
                'quantite_produit'
            )
            ->whereDate('created_at', $today)
            ->get()
            ->groupBy(function($item) {
                // Regrouper par heure et par lot
                return $item->timestamp . '-' . $item->id_lot;
            })
            ->map(function($lotGroup) {
                // Ne prendre que la première entrée pour chaque lot (par heure)
                $item = $lotGroup->first();
                return [
                    'timestamp' => $item->timestamp,
                    'id_lot' => $item->id_lot,
                    'total' => $item->quantite_produit
                ];
            })
            ->values()
            ->groupBy('timestamp')
            ->map(function($group) {
                return [
                    'timestamp' => $group->first()['timestamp'],
                    'total' => $group->sum('total')
                ];
            })
            ->values();

        // Pertes/Gaspillage par heure - Avec prise en compte correcte des lots
        $pertes = DB::table('Utilisation as u')
            ->join('Matiere_recommander as mr', function($join) {
                $join->on('u.produit', '=', 'mr.produit')
                     ->on('u.matierep', '=', 'mr.matierep');
            })
            ->select(
                DB::raw('DATE_FORMAT(u.created_at, "%H:00:00") as timestamp'),
                'u.id_lot',
                DB::raw('CASE
                    WHEN u.quantite_matiere > (mr.quantite * (u.quantite_produit / mr.quantitep))
                    THEN ((u.quantite_matiere - (mr.quantite * (u.quantite_produit / mr.quantitep))) / (mr.quantite * (u.quantite_produit / mr.quantitep)) * 100)
                    ELSE 0
                END as perte')
            )
            ->whereDate('u.created_at', $today)
            ->get()
            ->groupBy(function($item) {
                // Regrouper par heure et par lot
                return $item->timestamp . '-' . $item->id_lot;
            })
            ->map(function($lotGroup) {
                // Ne prendre que la première entrée pour chaque lot (par heure)
                return $lotGroup->first();
            })
            ->values()
            ->groupBy('timestamp')
            ->map(function($group) {
                return [
                    'timestamp' => $group->first()->timestamp,
                    'perte' => $group->avg('perte')
                ];
            })
            ->values();

        // Bénéfices par heure - Avec prise en compte correcte des lots
        $benefices = DB::table('Utilisation as u')
            ->join('Produit_fixes as p', 'u.produit', '=', 'p.code_produit')
            ->join('Matiere as m', 'u.matierep', '=', 'm.id')
            ->select(
                DB::raw('DATE_FORMAT(u.created_at, "%H:00:00") as timestamp'),
                'u.id_lot',
                'u.quantite_produit',
                'p.prix as prix_produit',
                'u.quantite_matiere',
                'm.prix_par_unite_minimale'
            )
            ->whereDate('u.created_at', $today)
            ->get()
            ->groupBy(function($item) {
                // Regrouper par heure et par lot
                return $item->timestamp . '-' . $item->id_lot;
            })
            ->map(function($lotGroup) {
                // Pour chaque lot, calculer le bénéfice
                $lot = $lotGroup->first();
                $valeurProduction = $lot->quantite_produit * $lot->prix_produit;
                $coutMatieres = 0;

                // Calculer le coût total des matières pour ce lot
                foreach ($lotGroup as $item) {
                    $coutMatieres += $item->quantite_matiere * $item->prix_par_unite_minimale;
                }

                return [
                    'timestamp' => $lot->timestamp,
                    'benefice' => $valeurProduction - $coutMatieres
                ];
            })
            ->values()
            ->groupBy('timestamp')
            ->map(function($group) {
                return [
                    'timestamp' => $group->first()['timestamp'],
                    'benefice' => $group->sum('benefice')
                ];
            })
            ->values();

        // Calcul du gaspillage moyen
        $gaspillageTotal = $pertes->avg('perte') ?? 0;

        // Assurer que toutes les heures sont représentées (de 00:00 à 23:00)
        $heuresCompletes = collect(range(0, 23))->map(function ($heure) {
            return str_pad($heure, 2, '0', STR_PAD_LEFT) . ':00:00';
        });

        $productions = $this->completerHeures($productions, $heuresCompletes);
        $pertes = $this->completerHeures($pertes, $heuresCompletes);
        $benefices = $this->completerHeures($benefices, $heuresCompletes);

        return [
            'productions' => $productions,
            'pertes' => $pertes,
            'benefices' => $benefices,
            'gaspillage' => round($gaspillageTotal, 2)
        ];
    }

        private function completerHeures($donnees, $heuresCompletes)
        {
            $donneesParHeure = $donnees->pluck('timestamp')->flip();

            return $heuresCompletes->map(function ($heure) use ($donnees, $donneesParHeure) {
                if (isset($donneesParHeure[$heure])) {
                    return $donnees[$donneesParHeure[$heure]];
                }

                return [
                    'timestamp' => $heure,
                    'total' => 0,
                    'perte' => 0,
                    'benefice' => 0
                ];
            })->values();
        }



        private function getProductionsEnCours()
        {
            $assignments = Daily_assignments::with(['produitFixe'])
                ->whereDate('assignment_date', Carbon::today())
                ->get();
        
            if ($assignments->isEmpty()) {
                // Utilisation de la logique optimisée par lot
                $utilisations = DB::table('Utilisation')
                    ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
                    ->whereDate('Utilisation.created_at', Carbon::today())
                    ->select(
                        'Utilisation.id_lot',
                        'Utilisation.produit',
                        'Produit_fixes.nom as nom_produit',
                        'Utilisation.quantite_produit'
                    )
                    ->orderBy('Utilisation.id_lot')
                    ->get();
        
                // Regroupement par produit en évitant le double comptage par lot
                $productionsParProduit = [];
                
                foreach ($utilisations as $utilisation) {
                    $codeProduit = $utilisation->produit;
                    
                    if (!isset($productionsParProduit[$codeProduit])) {
                        $productionsParProduit[$codeProduit] = [
                            'produit' => $codeProduit,
                            'nom_produit' => $utilisation->nom_produit,
                            'total_produit' => 0,
                            'lots_utilises' => []
                        ];
                    }
                    
                    // Éviter de compter plusieurs fois le même lot pour le même produit
                    $idLot = $utilisation->id_lot;
                    if (!in_array($idLot, $productionsParProduit[$codeProduit]['lots_utilises'])) {
                        $productionsParProduit[$codeProduit]['total_produit'] += $utilisation->quantite_produit;
                        $productionsParProduit[$codeProduit]['lots_utilises'][] = $idLot;
                    }
                }
                
                // CORRECTION : Retourner la même structure que le cas avec assignments
                return collect($productionsParProduit)->map(function ($item) {
                    return [
                        'produit' => $item['nom_produit'],
                        'quantite_actuelle' => $item['total_produit'],
                        'quantite_attendue' => 0, // Pas d'objectif défini
                        'progression' => 0,
                        'status' => 0 // En cours par défaut
                    ];
                });
            }
        
            // Cas avec assignments : optimisation similaire pour éviter les requêtes multiples
            $producteurIds = $assignments->pluck('producteur')->unique();
            $produitIds = $assignments->pluck('produit')->unique();
            
            // Une seule requête pour récupérer toutes les utilisations du jour
            $utilisationsJour = DB::table('Utilisation')
                ->whereDate('created_at', Carbon::today())
                ->whereIn('producteur', $producteurIds)
                ->whereIn('produit', $produitIds)
                ->select('id_lot', 'produit', 'producteur', 'quantite_produit')
                ->get();
            
            // Regroupement des productions par produit et producteur
            $productionsParProducteurProduit = [];
            
            foreach ($utilisationsJour as $utilisation) {
                $key = $utilisation->produit . '_' . $utilisation->producteur;
                
                if (!isset($productionsParProducteurProduit[$key])) {
                    $productionsParProducteurProduit[$key] = [
                        'total' => 0,
                        'lots_utilises' => []
                    ];
                }
                
                // Éviter de compter plusieurs fois le même lot
                $idLot = $utilisation->id_lot;
                if (!in_array($idLot, $productionsParProducteurProduit[$key]['lots_utilises'])) {
                    $productionsParProducteurProduit[$key]['total'] += $utilisation->quantite_produit;
                    $productionsParProducteurProduit[$key]['lots_utilises'][] = $idLot;
                }
            }
        
            return $assignments->map(function ($assignment) use ($productionsParProducteurProduit) {
                $key = $assignment->produit . '_' . $assignment->producteur;
                $productionActuelle = $productionsParProducteurProduit[$key]['total'] ?? 0;
                
                $progression = $assignment->expected_quantity > 0 
                    ? ($productionActuelle / $assignment->expected_quantity) * 100 
                    : 0;
                    
                return [
                    'produit' => $assignment->produitFixe->nom,
                    'quantite_actuelle' => $productionActuelle,
                    'quantite_attendue' => $assignment->expected_quantity,
                    'progression' => $progression,
                    'status' => $assignment->status
                ];
            });
        }

    public function assignerProduction(Request $request)
    {
        $validated = $request->validate([
            'producteur' => 'required|exists:users,id',
            'produit' => 'required|exists:Produit_fixes,code_produit',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);
        //verifier si la production est deja assignée pour le meme produit
        $existingAssignment = Daily_assignments::where('producteur', $validated['producteur'])
            ->where('produit', $validated['produit'])
            ->whereDate('assignment_date', Carbon::today())
            ->first();
        //si la production a deja ete assigner , alors , ajouter la quantite
        if ($existingAssignment) {
            $existingAssignment->expected_quantity += $validated['quantite'];
            $existingAssignment->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Production mise à jour avec succès'
            ]);
        }
        $assignment = new Daily_assignments();
        $assignment->chef_production = auth()->id();
        $assignment->producteur = $validated['producteur'];
        $assignment->produit = $validated['produit'];
        $assignment->expected_quantity = $validated['quantite'];
        $assignment->assignment_date = Carbon::today();
        $assignment->status = 0;
        $assignment->save();
        //notifier le producteur
        $producteur = User::find($validated['producteur']);
        $request->merge([
            'recipient_id' => $producteur->id,
            'subject' => 'Nouvelle production assignée',
            'message' => "Vous avez été assigné à produire {$validated['quantite']} unités de {$assignment->produitFixe->nom}."
        ]);
        $this->notificationController->send($request);
        //historiser
        $user = auth()->user();
        $this->historiser("La production de {$assignment->produitFixe->nom} a été assignée à {$producteur->name} par {$user->name}", 'assigner_production');
        return response()->json([
            'status' => 'success',
            'message' => 'Production assignée avec succès'
        ]);
    }

    public function createmanquant()
    {
        $employe = Auth::user();
        $nom = $employe->name;
        $role = $employe->role;
        $employees = User::where('secteur', 'production')
        ->orWhere('secteur', 'alimentation')
        ->get();
return view('pages.manquant', compact('employees','nom','role'));
    }

    public function storemanquant2(Request $request)
{
    Log::info("entering storemanquant2 method");
    Log::info("request data: ", $request->all());
    // Validate the request data
    $validated = $request->validate([
        'employe_id' => 'required|exists:users,id',
        'montant' => 'required|numeric|min:1',
        'explication' => 'required|string',
    ]);
    Log::info("validated data: ", $validated);

    try {
        // Check if there's an existing manquant for this employee
        $existingManquant = ManquantTemporaire::where('employe_id', $validated['employe_id'])->latest()->first();

        if ($existingManquant && $existingManquant->statut === 'en_attente') {
            // If an entry exists with status 'en_attente', update it by adding the amount
            $existingManquant->montant += $validated['montant'];
            $existingManquant->explication .= "\n\n--- Ajout le " . now()->format('d/m/Y H:i') . " ---\n" . $validated['explication'];
            $existingManquant->save();
            Log::info("Updated existing manquant: ", $existingManquant->toArray());
            $message = 'Le montant a été ajouté au manquant existant en attente pour cet employé.';
        } else {
            // If no entry exists or the existing one is validated, create a new one
            ManquantTemporaire::create([
                'employe_id' => $validated['employe_id'],
                'montant' => $validated['montant'],
                'explication' => $validated['explication'],
                'statut' => 'en_attente', // Default status as per schema
            ]);
            Log::info("Created new manquant: ", $validated);

            $message = 'Le nouveau manquant a été facturé avec succès et est en attente de validation.';
        }

        //historiser l'action
        $user = auth()->user();
        $this->historiser("L'utilisateur {$user->name} a créé ou mis à jour un manquant pour l'employé {$validated['employe_id']} de montant {$validated['montant']}", 'create_update_manquant');
        // Redirect with success message
        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        Log::info("message: " . $e->getMessage());
        // Handle any unexpected errors
        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de l\'enregistrement du manquant: ' . $e->getMessage());
    }
}


public function choix_classement(){
    return view('pages.choix-classement');
}

}
