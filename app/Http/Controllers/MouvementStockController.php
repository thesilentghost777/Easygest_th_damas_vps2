<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorisableActions;

class MouvementStockController extends Controller
{
    use HistorisableActions;
    public function index(Request $request)
    {
        $query = MouvementStock::with(['produit', 'user'])
            ->latest();

        // Filtrer par produit
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }

        // Filtrer par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrer par date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->paginate(20);

        return view('stock.mouvements.index', compact('mouvements'));
    }

    public function entree(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
            'motif' => 'required|string'
        ]);

        DB::transaction(function () use ($produit, $validated) {
            $produit->increment('quantite', $validated['quantite']);

            MouvementStock::create([
                'produit_id' => $produit->id,
                'type' => 'entree',
                'quantite' => $validated['quantite'],
                'user_id' => auth()->id(),
                'motif' => $validated['motif']
            ]);
        });
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a enregistré une entrée en stock pour le produit ID: {$produit->id}", 'entree_stock');

        return back()->with('success', 'Entrée en stock enregistrée');
    }

    public function sortie(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1|max:' . $produit->quantite,
            'motif' => 'required|string'
        ]);

        DB::transaction(function () use ($produit, $validated) {
            $produit->decrement('quantite', $validated['quantite']);

            MouvementStock::create([
                'produit_id' => $produit->id,
                'type' => 'sortie',
                'quantite' => $validated['quantite'],
                'user_id' => auth()->id(),
                'motif' => $validated['motif']
            ]);
        });
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a enregistré une sortie de stock pour le produit ID: {$produit->id}", 'sortie_stock');

        return back()->with('success', 'Sortie de stock enregistrée');
    }
}
