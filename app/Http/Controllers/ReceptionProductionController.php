<?php

namespace App\Http\Controllers;

use App\Models\ReceptionProduction;
use App\Models\Produit_fixes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = ReceptionProduction::with(['produit', 'user']);

        // Filtres
        if ($request->filled('produit')) {
            $query->whereHas('produit', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->produit . '%');
            });
        }

        if ($request->filled('date_debut')) {
            $query->where('date_reception', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_reception', '<=', $request->date_fin);
        }

        $receptions = $query->orderBy('date_reception', 'desc')->paginate(15);
        $produits = Produit_fixes::all();

        return view('reception.index', compact('receptions', 'produits'));
    }

    public function create()
    {
        $produits = Produit_fixes::all();
        return view('reception.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_reception' => 'required|date',
            'produits' => 'required|array|min:1',
            'produits.*.code_produit' => 'required|exists:Produit_fixes,code_produit',
            'produits.*.quantite' => 'required|integer|min:1',
        ]);

        foreach ($request->produits as $produit) {
            ReceptionProduction::create([
                'code_produit' => $produit['code_produit'],
                'quantite' => $produit['quantite'],
                'date_reception' => $request->date_reception,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('reception.index')->with('success', 'Réception enregistrée avec succès!');
    }

    public function edit(ReceptionProduction $reception)
    {
        $produits = Produit_fixes::all();
        return view('reception.edit', compact('reception', 'produits'));
    }

    public function update(Request $request, ReceptionProduction $reception)
    {
        $request->validate([
            'code_produit' => 'required|exists:Produit_fixes,code_produit',
            'quantite' => 'required|integer|min:1',
            'date_reception' => 'required|date',
        ]);

        $reception->update([
            'code_produit' => $request->code_produit,
            'quantite' => $request->quantite,
            'date_reception' => $request->date_reception,
        ]);

        return redirect()->route('reception.index')->with('success', 'Réception modifiée avec succès!');
    }

    public function destroy(ReceptionProduction $reception)
    {
        $reception->delete();
        return redirect()->route('reception.index')->with('success', 'Réception supprimée avec succès!');
    }
}