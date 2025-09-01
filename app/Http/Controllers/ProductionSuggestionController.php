<?php

namespace App\Http\Controllers;

use App\Models\Produit_fixes;
use App\Models\ProductionSuggererParJour;
use App\Models\ProduitStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\HistorisableActions;

class ProductionSuggestionController extends Controller
{
    use HistorisableActions;
    //$this->historiser("description", 'type');
    /**
     * Affiche l'interface de suggestion de production
     */
    public function index(Request $request)
    {
        // Récupérer tous les produits avec leurs stocks
        $produits = Produit_fixes::with('stock')->get();
        
        // Récupérer les suggestions existantes pour la semaine courante
        $dateDebut = Carbon::now()->startOfWeek();
        $dateFin = Carbon::now()->endOfWeek();
        
        $suggestions = ProductionSuggererParJour::whereBetween('day', [
            $dateDebut->format('Y-m-d H:i:s'),
            $dateFin->format('Y-m-d H:i:s')
        ])->with('produit_fixes')->get();
        Log::info("suggestion:{$suggestions}");
        
        // Grouper les suggestions par produit et jour
        $suggestionsGrouped = $suggestions->groupBy(['produit', 'day']);
        
        return view('production.suggestions.index', compact(
            'produits',
            'suggestionsGrouped',
            'dateDebut',
            'dateFin'
        ));
    }
    
    /**
     * Enregistre une suggestion de production
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit' => 'required|exists:Produit_fixes,code_produit',
            'quantity' => 'required|integer|min:0',
            'day' => 'required|date',
        ]);
        
        // Vérifier si une suggestion existe déjà pour ce produit et ce jour
        $existing = ProductionSuggererParJour::where('produit', $validated['produit'])
            ->where('day', $validated['day'])
            ->first();
            
        if ($existing) {
            $existing->update(['quantity' => $validated['quantity']]);
        } else {
            ProductionSuggererParJour::create($validated);
        }
        //historiser l'action en specifiant les details important
        $this->historiser("Suggestion de production pour le produit {$validated['produit']} le {$validated['day']} : {$validated['quantity']}", 'create_production_suggestion');
        return response()->json([
            'success' => true,
            'message' => 'Suggestion de production mise à jour avec succès'
        ]);
    }
    
    /**
     * Supprime une suggestion de production
     */
    public function destroy($id)
    {
        $suggestion = ProductionSuggererParJour::findOrFail($id);
        $suggestion->delete();
        //historiser l'action en specifiant les details important
        $this->historiser("Suppression de la suggestion de production ID {$id} pour le produit {$suggestion->produit} le {$suggestion->day}", 'delete_production_suggestion');
        return response()->json([
            'success' => true,
            'message' => 'Suggestion supprimée avec succès'
        ]);
    }
}
