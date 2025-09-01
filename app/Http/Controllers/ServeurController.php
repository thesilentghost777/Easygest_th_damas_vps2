<?php

namespace App\Http\Controllers;

use App\Models\TransactionVente;
use App\Models\ProduitRecu1;
use App\Models\ProduitRecuVendeur;
use App\Models\Produit_fixes;
use App\Models\ProduitStock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\HistorisableActions;

class ServeurController extends Controller
{
    use HistorisableActions;
    /**
     * Affiche le tableau de bord du vendeur
     */
    public function nbre_sacs(Request $request){
        $request->validate=([
       'quantite'=>'required',
       'sac'=>'required'
        ]);
        TransactionVente::create([
        'quantite'=>$request->quantite,
        'type'=>$request->sac
        ]);
     return redirect()->route('serveur.workspace');
     }

    public function nbre_sacs_vente(){
        $user = Auth::user();
        $role = $user->role;
        $nom=auth()->user()->name;
        return view('pages/serveur/serveur-sac',compact('user','nom','role'));
    }
    public function dashboard()
    {
        $serveurId = Auth::id();
        $aujourdhui = Carbon::today();
        $user=Auth::user();
        $nom = $user->name;
        $role = $user->role;
        // Récupérer les ventes du jour
        $ventesJour = TransactionVente::with('produit')
            ->where('serveur', $serveurId)
            ->where('date_vente', $aujourdhui)
            ->where('type', 'Vente')
            ->get();
            
        // Récupérer les produits invendus du jour
        $invendusJour = TransactionVente::with('produit')
            ->where('serveur', $serveurId)
            ->where('date_vente', $aujourdhui)
            ->where('type', 'Produit invendu')
            ->get();
            
        // Récupérer les produits avariés du jour
        $avariesJour = TransactionVente::with('produit')
            ->where('serveur', $serveurId)
            ->where('date_vente', $aujourdhui)
            ->where('type', 'Produit Avarie')
            ->get();
            
        // Récupérer les produits invendus d'hier
        $hier = Carbon::yesterday();
        #recuperer le role de l'utilisateur connecté
        $user = Auth::user();
        $role = $user->role;
        // Si le rôle est vendeur_boulangerie, on ne peut récupérer que les invendus de boulangerie
        if ($role == 'vendeur_boulangerie') {
            $produits = Produit_fixes::where('categorie', 'boulangerie')->pluck('code_produit');
        } elseif ($role == 'vendeur_patisserie') {
            $produits = Produit_fixes::where('categorie', 'patisserie')->pluck('code_produit');
        } elseif ($role == 'glace') {
            $produits = Produit_fixes::where('categorie', 'glace')->pluck('code_produit');
        } else {
            $produits = Produit_fixes::pluck('code_produit');
        }
        // Récupérer les invendus d'hier pour ce vendeur
        #recuperons les invendus ne concernant que les produits de l'utilisateur connecté
        $invendusHier = TransactionVente::with('produit')
            ->where('date_vente', $hier)
            ->where('type', 'Produit invendu')
            ->whereIn('produit', $produits)
            ->get();
        
        // Récupérer les produits assignés par le pointeur qui sont en attente de confirmation
        $produitsEnAttente = ProduitRecuVendeur::with(['produitRecu.produit', 'produitRecu.pointeur'])
            ->where('vendeur_id', $serveurId)
            ->where('status', 'en_attente')
            ->get();
            
        // Statistiques des ventes
        $totalVentes = $ventesJour->sum(function ($vente) {
            return $vente->quantite * $vente->prix;
        });
        
        // Produits disponibles (confirmés)
        $produitsDisponibles = ProduitRecuVendeur::with('produitRecu.produit')
            ->where('vendeur_id', $serveurId)
            ->where('status', 'confirmé')
            ->whereDate('updated_at', $aujourdhui)
            ->get();
        
        return view('serveur.dashboard', compact(
            'ventesJour', 
            'invendusJour', 
            'avariesJour', 
            'invendusHier', 
            'produitsEnAttente', 
            'totalVentes',
            'produitsDisponibles',
            'nom',
            'role',
            'user'
        ));
    }
    
    /**
     * Affiche le formulaire pour enregistrer une nouvelle vente
     */
    public function createVente()
    {
        $serveurId = Auth::id();
        $role = Auth::user()->role;
        if ($role == 'vendeur_boulangerie') {
            $categorie = 'boulangerie';
        }else if ($role == 'vendeur_patisserie') {
            $categorie = 'patisserie';
        }else if ($role == 'glace') {
            $categorie = 'glace';
        }else{
            $categorie = 'default';
        }

        if ($categorie != 'default') {
            $produits = Produit_fixes::where('categorie',$categorie)->get();

        }else{
            $produits = Produit_fixes::all();
        }
        

        if ($produits->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun produit disponible pour cette catégorie.');
        }
       
        
        $typesPaiement = ['Espèces', 'Mobile Money'];

        return view('serveur.vente.create', compact('produits', 'typesPaiement'));
    }
    
    /**
     * Enregistre une nouvelle vente
     */
   public function storeVente(Request $request)
{
    // Log de début avec toutes les données de la requête
    Log::info('=== DÉBUT storeVente ===', [
        'request_data' => $request->all(),
        'user_id' => Auth::id(),
        'timestamp' => now()->toDateTimeString()
    ]);

    $validatedData = $request->validate([
        'produit' => 'required|exists:Produit_fixes,code_produit',
        'quantite' => 'required|integer|min:1',
        'prix' => 'required|numeric|min:0',
        'monnaie' => 'required|string',
        'type' => 'required|in:Vente,Produit invendu,Produit Avarie',
        'date_vente' => 'required|date|before_or_equal:today'
    ]);
    
    Log::info('Données validées avec succès', [
        'validated_data' => $validatedData
    ]);
    
    $serveurId = Auth::id();
    Log::info('ID serveur récupéré', ['serveur_id' => $serveurId]);
    
    try {
        Log::info('Début de la transaction DB');
        DB::beginTransaction();
        
        // Créer la transaction de vente
        Log::info('Création de la transaction de vente');
        
        $transactionData = [
            'produit' => $validatedData['produit'],
            'serveur' => $serveurId,
            'quantite' => $validatedData['quantite'],
            'prix' => $validatedData['prix'],
            'date_vente' => Carbon::parse($validatedData['date_vente'])->format('Y-m-d'),
            'type' => $validatedData['type'],
            'monnaie' => $validatedData['monnaie']
        ];
        
        Log::info('Données de transaction à créer', $transactionData);
        
        $transaction = TransactionVente::create($transactionData);
        
            Log::info('Transaction créée avec succès', [
                'transaction_id' => $transaction->id,
                'transaction_data' => $transaction->toArray()
            ]);
            
            // Mettre à jour le stock global
            Log::info('Début mise à jour du stock global');
            
            // S'assurer que l'enregistrement de stock existe
            Log::info('Vérification/création de l\'enregistrement stock');
            
            $produitStock = ProduitStock::firstOrCreate(
                ['id_produit' => $validatedData['produit']],
                [
                    'quantite_en_stock' => 0,
                    'quantite_invendu' => 0,
                    'quantite_avarie' => 0
                ]
            );
            
            Log::info('Stock après firstOrCreate', [
                'stock_data' => $produitStock->toArray(),
                'was_recently_created' => $produitStock->wasRecentlyCreated
            ]);
            
            $stockUpdated = false;
            
            // Mise à jour selon le type
            if ($validatedData['type'] === 'Vente') {
                Log::info('Tentative de décrémentation du stock', [
                    'stock_avant' => $produitStock->quantite_en_stock,
                    'quantite_a_decrémenter' => $validatedData['quantite']
                ]);
                
                $stockUpdated = ProduitStock::where('id_produit', $validatedData['produit'])
                    ->decrement('quantite_en_stock', $validatedData['quantite']);
                    
                 Log::info('Résultat décrémentation', [
                    'rows_affected' => $stockUpdated,
                    'stock_après' => ProduitStock::where('id_produit', $validatedData['produit'])->first()->quantite_en_stock ?? 'NON TROUVÉ'
                ]);
                
            } elseif ($validatedData['type'] === 'Produit Avarie') {
                Log::info('Tentative d\'incrémentation quantite_avarie', [
                    'avarie_avant' => $produitStock->quantite_avarie,
                    'quantite_a_incrementer' => $validatedData['quantite']
                ]);
                
                $stockUpdated = ProduitStock::where('id_produit', $validatedData['produit'])
                    ->increment('quantite_avarie', $validatedData['quantite']);
                
                $stockUpdated = ProduitStock::where('id_produit', $validatedData['produit'])
                    ->decrement('quantite_en_stock', $validatedData['quantite']);
                 
                Log::info('Résultat incrémentation avarie', [
                    'rows_affected' => $stockUpdated,
                    'avarie_après' => ProduitStock::where('id_produit', $validatedData['produit'])->first()->quantite_avarie ?? 'NON TROUVÉ'
                ]);
                
            } elseif ($validatedData['type'] === 'Produit invendu') {
                Log::info('Tentative d\'incrémentation quantite_invendu', [
                    'invendu_avant' => $produitStock->quantite_invendu,
                    'quantite_a_incrementer' => $validatedData['quantite']
                ]);
                
                $stockUpdated = ProduitStock::where('id_produit', $validatedData['produit'])
                    ->increment('quantite_invendu', $validatedData['quantite']);
                    
                Log::info('Résultat incrémentation invendu', [
                    'rows_affected' => $stockUpdated,
                    'invendu_après' => ProduitStock::where('id_produit', $validatedData['produit'])->first()->quantite_invendu ?? 'NON TROUVÉ'
                ]);
            }
            
            // Vérifier si la mise à jour du stock a réussi
            if ($stockUpdated === false || $stockUpdated === 0) {
                Log::warning('ALERTE: Aucune ligne de stock mise à jour', [
                    'produit' => $validatedData['produit'],
                    'type' => $validatedData['type'],
                    'stock_updated_result' => $stockUpdated,
                    'stock_actuel' => ProduitStock::where('id_produit', $validatedData['produit'])->first()?->toArray()
                ]);
            } else {
                Log::info('Mise à jour du stock réussie', [
                    'rows_affected' => $stockUpdated,
                    'stock_final' => ProduitStock::where('id_produit', $validatedData['produit'])->first()?->toArray()
                ]);
            }
            
            Log::info('Validation de la transaction DB');
            //historiser l'action en respectant la syntaxe a 2 parametre //$this->historiser("description(une seule chaine de caractere)", 'type');
           $this->historiser("Enregistrement de la vente de " . $validatedData['quantite'] . " unité(s) du produit " . $validatedData['produit']. " par " .$serveurId, 'enregistrement_vente');

            DB::commit();
            
            $message = match($validatedData['type']) {
                'Vente' => 'Vente enregistrée avec succès',
                'Produit invendu' => 'Produit invendu enregistré avec succès',
                'Produit Avarie' => 'Produit avarié enregistré avec succès'
            };
            
            Log::info('=== FIN storeVente - SUCCÈS ===', [
                'message' => $message,
                'transaction_id' => $transaction->id,
                'execution_time' => now()->toDateTimeString()
            ]);
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('=== ERREUR dans storeVente ===');
            
            DB::rollBack();
            Log::info('Transaction DB annulée suite à l\'erreur');
            
            Log::error('Détails de l\'erreur', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'data' => $validatedData ?? 'Données non disponibles',
                'user_id' => $serveurId ?? 'ID non disponible',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            // Log de l'état de la base de données au moment de l'erreur
            try {
                $currentStock = ProduitStock::where('id_produit', $validatedData['produit'] ?? null)->first();
                Log::info('État du stock au moment de l\'erreur', [
                    'stock_data' => $currentStock ? $currentStock->toArray() : 'Stock non trouvé'
                ]);
            } catch (\Exception $stockError) {
                Log::error('Impossible de récupérer l\'état du stock', [
                    'error' => $stockError->getMessage()
                ]);
            }
            
            Log::info('=== FIN storeVente - ÉCHEC ===');
            
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    /**
     * Récupération des produits invendus d'hier
     */
    public function recupererInvendusHier()
    {
        $serveurId = Auth::id();
        $hier = Carbon::yesterday();
        #recuperer le role de l'utilisateur connecté
        $user = Auth::user();
        $role = $user->role;
        try {
            #si le role de l'utilisateur est vendeur_boulangerie, on ne peut recuperer les invendus que des produits de boulangerie
            if ($role == 'vendeur_boulangerie') {
                $produits = Produit_fixes::where('categorie', 'boulangerie')->pluck('code_produit');
            } elseif ($role == 'vendeur_patisserie') {
                $produits = Produit_fixes::where('categorie', 'patisserie')->pluck('code_produit');
            } elseif ($role == 'glace') {
                $produits = Produit_fixes::where('categorie', 'glace')->pluck('code_produit');
            } else {
                $produits = Produit_fixes::pluck('code_produit');
            }
            
            #recuperons les invendus ne concernant que les produits de l'utilisateur connecté
            $invendusHier = TransactionVente::where('date_vente', $hier)
                ->where('type', 'Produit invendu')
                ->whereIn('produit', $produits)
                ->where('serveur', $serveurId)
                ->get();
                
            foreach ($invendusHier as $invendu) {
                // Réduire le stock d'invendus
                ProduitStock::where('id_produit', $invendu->produit)
                    ->decrement('quantite_invendu', $invendu->quantite);
                
                $last_reception = ProduitRecu1::where('produit_id', $invendu->produit)
                    ->orderBy('date_reception', 'desc')
                    ->first();
                
                if(!$last_reception){
                    $user = Auth::user();
                    $language = $user->language;
                    if ($language == 'fr') {
                        return redirect()->back()->with('error', 'Aucune réception pour le produit trouvé (rapprochez-vous du chef de production)');
                    } else {
                        return redirect()->back()->with('error', 'No reception found for the product (please contact the production manager)');
                    }
                }
                ProduitRecuVendeur::create([
                    'vendeur_id' => $serveurId,
                    'produit_recu_id' => $last_reception->id,
                    'quantite_recue' => $invendu->quantite,
                    'quantite_confirmee' => $invendu->quantite,
                    'status' => 'confirmé',
                    'remarques' => 'Récupération automatique de produit invendu du ' . $hier->format('Y-m-d')
                ]);

                // Supprimer la transaction d'invendu(met en gardant les traces (on va changer le type par invendu_recupere)
                $invendu->update(['type' => 'Produit invendu recupere']);

                Log::info('Transaction d\'invendu mise à jour', [
                    'invendu_id' => $invendu->id,
                    'nouveau_type' => 'Produit invendu recupere'
                ]);

            }
            //historiser l'action en respectant la syntaxe a 2 parametre //$this->historiser("description(une seule chaine de caractere)", 'type');
            $this->historiser("Récupération des produits invendus d'hier par " . $serveurId, 'recuperation_invendus');
            return redirect()->route('serveur.workspace')
                ->with('success', 'Produits invendus d\'hier récupérés avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des invendus: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la récupération des invendus: ' . $e->getMessage());
        }
    }
    
    /**
     * Confirmer la réception des produits assignés par le pointeur
     */
    public function confirmerReception(Request $request, $id)
    {
        $validatedData = $request->validate([
            'quantite_confirmee' => 'required|integer|min:0',
            'remarques' => 'nullable|string'
        ]);
        
        try {
            $produitRecu = ProduitRecuVendeur::findOrFail($id);
            
            // Vérifier que c'est bien ce vendeur qui est concerné
            if ($produitRecu->vendeur_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à confirmer cette réception.');
            }
            
            // Si la quantité confirmée est différente de la quantité reçue, ajouter un commentaire
            if ($produitRecu->quantite_recue != $validatedData['quantite_confirmee']) {
                $commentaire = 'Différence de quantité: reçu ' . $produitRecu->quantite_recue . 
                    ', confirmé ' . $validatedData['quantite_confirmee'] . '. ';
                $validatedData['remarques'] = $commentaire . ($validatedData['remarques'] ?? '');
            }
            
            $produitRecu->update([
                'quantite_confirmee' => $validatedData['quantite_confirmee'],
                'remarques' => $validatedData['remarques'],
                'status' => 'confirmé'
            ]);
            //historiser l'action en respectant la syntaxe a 2 parametre //$this->historiser("description(une seule chaine de caractere)", 'type');
            $this->historiser("Confirmation de réception de " . $produitRecu->produitRecu->produit->nom . 
                " par " . Auth::user()->name, 'confirmation_reception');
            return redirect()->route('serveur.workspace')
                ->with('success', 'Réception confirmée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation de réception: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    
    /**
     * Rejeter la réception des produits assignés par le pointeur
     */
    public function rejeterReception(Request $request, $id)
    {
        $validatedData = $request->validate([
            'remarques' => 'required|string'
        ]);
        
        try {
            $produitRecu = ProduitRecuVendeur::findOrFail($id);
            
            // Vérifier que c'est bien ce vendeur qui est concerné
            if ($produitRecu->vendeur_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter cette réception.');
            }
            
            $produitRecu->update([
                'status' => 'rejeté',
                'remarques' => $validatedData['remarques']
            ]);
            //historiser l'action en respectant la syntaxe a 2 parametre //$this->historiser("description(une seule chaine de caractere)", 'type');
            $this->historiser("Rejet de réception de " . $produitRecu->produitRecu->produit->nom . 
                " par " . Auth::user()->name, 'rejet_reception');
            return redirect()->route('serveur.workspace')
                ->with('success', 'Réception rejetée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors du rejet de réception: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher la liste détaillée des ventes avec filtres
     */
    public function listeVentes(Request $request)
    {
        $serveurId = Auth::id();
        
        $query = TransactionVente::with('produit')
            ->where('serveur', $serveurId);
        $user = Auth::user();
        if ($user->role == 'dg' || $user->role == 'pdg' || $user->role == 'chef_production') {
            $query = TransactionVente::with('produit')->whereNot('serveur',0);        }
        
        // Filtres
        if ($request->filled('date_debut')) {
            $query->where('date_vente', '>=', Carbon::parse($request->date_debut));
        }
        
        if ($request->filled('date_fin')) {
            $query->where('date_vente', '<=', Carbon::parse($request->date_fin));
        }
        
        if ($request->filled('produit')) {
            $query->where('produit', $request->produit);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('monnaie')) {
            $query->where('monnaie', $request->monnaie);
        }
        
        // Tri
        $sortField = $request->get('sort', 'date_vente');
        $sortDirection = $request->get('direction', 'desc');
        
        $ventes = $query->orderBy($sortField, $sortDirection)->paginate(20);
        
        // Pour les filtres
        $produits = Produit_fixes::all();
        $types = ['Vente', 'Produit invendu', 'Produit Avarie','Produit invendu recupere'];
        $monnaies = TransactionVente::distinct()->pluck('monnaie')->filter()->values();
        
        return view('serveur.vente.liste', compact('ventes', 'produits', 'types', 'monnaies'));
    }
    
    /**
     * Afficher le classement des vendeurs
     */
    public function classementVendeurs(Request $request)
    {
        // Période par défaut: mois en cours
        $dateDebut = $request->get('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', Carbon::now()->format('Y-m-d'));
        
        $ventesParVendeur = TransactionVente::join('users', 'transaction_ventes.serveur', '=', 'users.id')
            ->join('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->where('type', 'Vente')
            ->whereBetween('date_vente', [$dateDebut, $dateFin])
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(transaction_ventes.quantite * transaction_ventes.prix) as chiffre_affaires'),
                DB::raw('SUM(transaction_ventes.quantite) as quantite_totale'),
                DB::raw('COUNT(transaction_ventes.id) as nombre_ventes'),
                DB::raw('COUNT(DISTINCT transaction_ventes.produit) as diversite_produits')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('chiffre_affaires', 'desc')
            ->get();
            
        // Calcul des performances par critère
        $meilleurCA = $ventesParVendeur->max('chiffre_affaires') ?: 1;
        $meilleurQuantite = $ventesParVendeur->max('quantite_totale') ?: 1;
        $meilleurNbVentes = $ventesParVendeur->max('nombre_ventes') ?: 1;
        $meilleurDiversite = $ventesParVendeur->max('diversite_produits') ?: 1;
        
        foreach ($ventesParVendeur as $vendeur) {
            // Score sur 100 points pour chaque critère
            $scoreCA = ($vendeur->chiffre_affaires / $meilleurCA) * 100;
            $scoreQuantite = ($vendeur->quantite_totale / $meilleurQuantite) * 100;
            $scoreNbVentes = ($vendeur->nombre_ventes / $meilleurNbVentes) * 100;
            $scoreDiversite = ($vendeur->diversite_produits / $meilleurDiversite) * 100;
            
            // Score global (moyenne pondérée)
            $vendeur->score_global = 
                ($scoreCA * 0.4) +            // 40% basé sur le CA
                ($scoreQuantite * 0.3) +      // 30% basé sur la quantité
                ($scoreNbVentes * 0.2) +      // 20% basé sur le nombre de ventes
                ($scoreDiversite * 0.1);      // 10% basé sur la diversité
                
            // Scores détaillés
            $vendeur->scores = [
                'ca' => round($scoreCA),
                'quantite' => round($scoreQuantite),
                'nb_ventes' => round($scoreNbVentes),
                'diversite' => round($scoreDiversite),
            ];
            
            // Formatage des chiffres
            $vendeur->chiffre_affaires_formate = number_format($vendeur->chiffre_affaires, 0, ',', ' ') . ' FCFA';
        }
        
        // Trier par score global
        $vendeurs = $ventesParVendeur->sortByDesc('score_global')->values();
        
        return view('serveur.classement', compact('vendeurs', 'dateDebut', 'dateFin'));
    }
}
