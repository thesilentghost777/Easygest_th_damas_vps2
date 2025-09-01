<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Utilisation;
use App\Traits\HistorisableActions;

class ProductionEditController extends Controller
{
    use HistorisableActions;
    //$this->historiser("description", 'type');
    /**
     * Affiche la page de sélection du type de données à modifier
     */
    public function index()
    {
        return view('production.edit.index');
    }
    
     public function mesProductions()
{
    $query = Utilisation::select('id_lot', 'produit', 'quantite_produit', 'created_at')
        ->with(['produit_fixes:code_produit,nom'])
        ->groupBy('id_lot', 'produit', 'quantite_produit', 'created_at')
        ->orderBy('created_at', 'desc');

    // Si l'utilisateur n'est pas administrateur, on filtre par son id
    if (Auth::user()->secteur !== 'administration') {
        $query->where('producteur', Auth::id());
    }

    $productions = $query->get();

    // Ajouter le nombre de matières utilisées
    foreach ($productions as $production) {
        $matieresQuery = Utilisation::where('id_lot', $production->id_lot);

        if (Auth::user()->role !== 'administrateur') {
            $matieresQuery->where('producteur', Auth::id());
        }

        $production->nombre_matieres = $matieresQuery->count();
    }

    return view('productions.mes-productions', compact('productions'));
}


    /**
     * Supprimer une production complète basée sur l'id_lot
     */
    public function supprimerProduction(Request $request)
{
    Log::info('--- Début de supprimerProduction ---', [
        'user_id' => Auth::id(),
        'payload' => $request->all()
    ]);

    $request->validate([
        'id_lot' => 'required|string|max:20'
    ]);

    $idLot = $request->id_lot;
    $producteurId = Auth::id();

    try {
        DB::beginTransaction();
        Log::debug("Transaction commencée pour suppression", [
            'id_lot' => $idLot,
            'producteur' => $producteurId
        ]);

        // Vérifier que le producteur est bien propriétaire de cette production
        $utilisations = Utilisation::where('id_lot', $idLot)
            ->where('producteur', $producteurId)
            ->get();

        Log::debug("Utilisations trouvées", [
            'count' => $utilisations->count(),
            'ids' => $utilisations->pluck('id')->toArray()
        ]);

        if ($utilisations->isEmpty()) {
            Log::warning("Tentative de suppression non autorisée", [
                'id_lot' => $idLot,
                'producteur' => $producteurId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Production introuvable ou vous n\'avez pas les droits pour la supprimer.'
            ], 404);
        }

        // Supprimer toutes les utilisations liées à ce lot
        $nombreSupprime = Utilisation::where('id_lot', $idLot)
            ->where('producteur', $producteurId)
            ->delete();

        DB::commit();

        Log::info("Production supprimée avec succès", [
            'id_lot' => $idLot,
            'producteur' => $producteurId,
            'nombre_utilisations_supprimees' => $nombreSupprime
        ]);

        return response()->json([
            'success' => true,
            'message' => "Production {$idLot} supprimée avec succès ({$nombreSupprime} enregistrements supprimés)."
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error("Erreur lors de la suppression de la production", [
            'id_lot' => $idLot,
            'producteur' => $producteurId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression de la production.'
        ], 500);
    }
}


    /**
     * Affiche les transactions de vente avec filtres
     */
    public function ventes(Request $request)
    {
        $query = DB::table('transaction_ventes')
            ->leftJoin('Produit_fixes', 'transaction_ventes.produit', '=', 'Produit_fixes.code_produit')
            ->leftJoin('users', 'transaction_ventes.serveur', '=', 'users.id')
            ->select(
                'transaction_ventes.*',
                'Produit_fixes.nom as produit_nom',
                'users.name as serveur_nom'
            );
        
        // Appliquer les filtres
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('transaction_ventes.date_vente', [$request->date_debut, $request->date_fin]);
        }
        
        if ($request->filled('produit')) {
            $query->where('transaction_ventes.produit', $request->produit);
        }
        
        if ($request->filled('serveur')) {
            $query->where('transaction_ventes.serveur', $request->serveur);
        }
        
        $transactions = $query->orderBy('transaction_ventes.created_at', 'desc')->paginate(50);
        
        // Récupérer les données pour les filtres
        $produits = DB::table('Produit_fixes')->select('code_produit', 'nom')->orderBy('nom')->get();
        $serveurs = DB::table('users')->where('secteur', 'vente')->orWhere('secteur', 'glace')->select('id', 'name')->orderBy('name')->get();
        
        return view('production.edit.ventes', compact('transactions', 'produits', 'serveurs'));
    }
    
    /**
     * Affiche les utilisations de matières avec filtres
     */
    public function utilisations(Request $request)
    {
        $query = DB::table('Utilisation')
            ->leftJoin('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
            ->leftJoin('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
            ->leftJoin('users', 'Utilisation.producteur', '=', 'users.id')
            ->select(
                'Utilisation.*',
                'Produit_fixes.nom as produit_nom',
                'Matiere.nom as matiere_nom',
                'users.name as producteur_nom'
            );
        
        // Appliquer les filtres
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('Utilisation.created_at', [
                $request->date_debut . ' 00:00:00',
                $request->date_fin . ' 23:59:59'
            ]);
        }
        
        if ($request->filled('id_lot')) {
            $query->where('Utilisation.id_lot', 'like', '%' . $request->id_lot . '%');
        }
        
        if ($request->filled('produit')) {
            $query->where('Utilisation.produit', $request->produit);
        }
        
        if ($request->filled('matiere')) {
            $query->where('Utilisation.matierep', $request->matiere);
        }
        
        $utilisations = $query->orderBy('Utilisation.created_at', 'desc')->paginate(50);
        
        // Récupérer les données pour les filtres
        $produits = DB::table('Produit_fixes')->select('code_produit', 'nom')->orderBy('nom')->get();
        $matieres = DB::table('Matiere')->select('id', 'nom')->orderBy('nom')->get();
        $producteurs = DB::table('users')->where('secteur', 'production')->orWhere('secteur', 'glace')->select('id', 'name')->orderBy('name')->get();
        
        return view('production.edit.utilisations', compact('utilisations', 'produits', 'matieres', 'producteurs'));
    }
    
    /**
     * Récupère une transaction spécifique pour l'édition
     */
    public function getVente($id)
    {
        $transaction = DB::table('transaction_ventes')->where('id', $id)->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction non trouvée'], 404);
        }
        
        return response()->json($transaction);
    }
    
    /**
     * Met à jour une transaction de vente
     */
   public function updateVente(Request $request, $id)
{
    // Vérifier si la transaction existe
    $transaction = DB::table('transaction_ventes')->where('id', $id)->first();
    if (!$transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction non trouvée'
        ], 404);
    }

    $validated = $request->validate([
        'produit' => 'required|exists:Produit_fixes,code_produit',
        'serveur' => 'required|exists:users,id',
        'quantite' => 'required|integer|min:1',
        'prix' => 'required|integer|min:0',
        'date_vente' => 'required|date',
        'type' => 'required|string',
        'monnaie' => 'nullable|string'
    ]);

    $updated = DB::table('transaction_ventes')
        ->where('id', $id)
        ->update($validated);

    if ($updated) {
        $this->historiser("Mise à jour de la transaction de vente {$id} : nouveaux détails: " . json_encode($validated), 'update_sale_transaction');
        return response()->json([
            'success' => true,
            'message' => 'Transaction mise à jour avec succès'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour'
    ], 500);
}
    
    /**
     * Supprime une transaction de vente
     */
    public function destroyVente($id)
    {
        $deleted = DB::table('transaction_ventes')->where('id', $id)->delete();
        
        if ($deleted) {
             //historiser l'action en specifiant les details important
            $this->historiser("Suppression de la transaction de vente {$id}", 'delete_sale_transaction');
        
            return response()->json([
                'success' => true,
                'message' => 'Transaction supprimée avec succès'
            ]);
        }
       return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ], 500);
    }
    
    /**
     * Récupère une utilisation spécifique pour l'édition
     */
    public function getUtilisation($id)
    {
        $utilisation = DB::table('Utilisation')->where('id', $id)->first();
        
        if (!$utilisation) {
            return response()->json(['error' => 'Utilisation non trouvée'], 404);
        }
        
        return response()->json($utilisation);
    }
    
    /**
     * Met à jour une utilisation de matière
     */
    public function updateUtilisation(Request $request, $id)
    {
        $validated = $request->validate([
            'id_lot' => 'required|string|max:20',
            'produit' => 'required|exists:Produit_fixes,code_produit',
            'matierep' => 'required|exists:Matiere,id',
            'producteur' => 'required|exists:users,id',
            'quantite_produit' => 'required|numeric|min:0',
            'quantite_matiere' => 'required|numeric|min:0',
            'unite_matiere' => 'required|string'
        ]);
        
        $updated = DB::table('Utilisation')
            ->where('id', $id)
            ->update($validated);
        
        if ($updated) {
            //historiser l'action en specifiant les details important
            $this->historiser("Mise à jour de l'utilisation de matière {$id} : nouveau details : " . json_encode($validated), 'update_material_usage');

            return response()->json([
                'success' => true,
                'message' => 'Utilisation mise à jour avec succès'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ], 500);
    }
    
    /**
     * Supprime une utilisation de matière
     */
    public function destroyUtilisation($id)
    {
        $deleted = DB::table('Utilisation')->where('id', $id)->delete();
        
        if ($deleted) {
            //historiser l'action en specifiant les details important
            $this->historiser("Suppression de l'utilisation de matière {$id}", 'delete_material_usage');
        
            return response()->json([
                'success' => true,
                'message' => 'Utilisation supprimée avec succès'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ], 500);
    }
}
