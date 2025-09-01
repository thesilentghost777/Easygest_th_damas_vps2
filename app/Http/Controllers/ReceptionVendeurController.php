<?php

// ReceptionVendeurController.php - Version mise à jour avec le champ avarie
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceptionVendeur;
use App\Models\Produit_fixes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReceptionVendeurController extends Controller
{
     public function index(Request $request)
    {
        $vendeurs = User::where('secteur', 'vente')->get();
        $produits = Produit_fixes::all();
        
        // Construction de la requête avec les filtres
        $query = ReceptionVendeur::with(['vendeur', 'produit']);
        
        // Filtre par vendeur
        if ($request->filled('vendeur_id')) {
            $query->where('vendeur_id', $request->vendeur_id);
        }
        
        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date_reception', $request->date);
        }
        
        // Récupération des résultats avec pagination
        $receptions = $query->orderBy('date_reception', 'desc')->paginate(20);
        
        // Ajout de variables pour detecter la langue (vous pouvez adapter selon votre logique)
        $isFrench = session('locale') === 'fr' || app()->getLocale() === 'fr';
        
        return view('receptions.vendeurs.index', compact('vendeurs', 'produits', 'receptions', 'isFrench'));
    }

    public function create()
    {
        $vendeurs = User::where('secteur', 'vente')->get();
        $produits = Produit_fixes::orderBy('nom', 'asc')->get();

        
        return view('receptions.vendeurs.create', compact('vendeurs', 'produits'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'vendeur_id' => 'required|exists:users,id',
            'date_reception' => 'required|date',
            'produits' => 'required|array',
            'produits.*.produit_id' => 'required|exists:Produit_fixes,code_produit',
            'produits.*.quantite_entree_matin' => 'nullable|numeric|min:0',
            'produits.*.quantite_entree_journee' => 'nullable|numeric|min:0',
            'produits.*.quantite_invendue' => 'nullable|numeric|min:0',
            'produits.*.quantite_reste_hier' => 'nullable|numeric|min:0',
            'produits.*.quantite_avarie' => 'nullable|numeric|min:0', // Nouveau champ
        ]);

        foreach ($request->produits as $produitData) {
            ReceptionVendeur::updateOrCreate(
                [
                    'vendeur_id' => $request->vendeur_id,
                    'produit_id' => $produitData['produit_id'],
                    'date_reception' => $request->date_reception,
                ],
                [
                    'quantite_entree_matin' => $produitData['quantite_entree_matin'] ?? 0,
                    'quantite_entree_journee' => $produitData['quantite_entree_journee'] ?? 0,
                    'quantite_invendue' => $produitData['quantite_invendue'] ?? 0,
                    'quantite_reste_hier' => $produitData['quantite_reste_hier'] ?? 0,
                    'quantite_avarie' => $produitData['quantite_avarie'] ?? 0, // Nouveau champ
                ]
            );
        }

        return redirect()->route('receptions.vendeurs.index')
            ->with('success', 'Réceptions vendeur enregistrées avec succès');
    }

    public function show($id)
    {
        $reception = ReceptionVendeur::with(['vendeur', 'produit'])->findOrFail($id);
        
        return view('receptions.vendeurs.show', compact('reception'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $reception = ReceptionVendeur::with(['vendeur', 'produit'])->findOrFail($id);
        $vendeurs = User::where('secteur', 'vente')->get();
        $produits = Produit_fixes::all();
        
        return view('receptions.vendeurs.edit', compact('reception', 'vendeurs', 'produits'));
    }

    public function update(Request $request, $id)
    {
        $reception = ReceptionVendeur::findOrFail($id);
        
        $request->validate([
            'vendeur_id' => 'required|exists:users,id',
            'produit_id' => 'required|exists:Produit_fixes,code_produit',
            'date_reception' => 'required|date',
            'quantite_entree_matin' => 'nullable|numeric|min:0',
            'quantite_entree_journee' => 'nullable|numeric|min:0',
            'quantite_invendue' => 'nullable|numeric|min:0',
            'quantite_reste_hier' => 'nullable|numeric|min:0',
            'quantite_avarie' => 'nullable|numeric|min:0', // Nouveau champ
        ]);

        $reception->update([
            'vendeur_id' => $request->vendeur_id,
            'produit_id' => $request->produit_id,
            'date_reception' => $request->date_reception,
            'quantite_entree_matin' => $request->quantite_entree_matin ?? 0,
            'quantite_entree_journee' => $request->quantite_entree_journee ?? 0,
            'quantite_invendue' => $request->quantite_invendue ?? 0,
            'quantite_reste_hier' => $request->quantite_reste_hier ?? 0,
            'quantite_avarie' => $request->quantite_avarie ?? 0, // Nouveau champ
        ]);

        return redirect()->route('receptions.vendeurs.index')
            ->with('success', 'Réception vendeur mise à jour avec succès');
    }

    public function destroy($id)
    {
        $reception = ReceptionVendeur::findOrFail($id);
        $reception->delete();

        return redirect()->route('receptions.vendeurs.index')
            ->with('success', 'Réception vendeur supprimée avec succès');
    }

    public function getReceptionsByDate(Request $request)
    {
        $date = $request->get('date');
        $vendeur_id = $request->get('vendeur_id');
        
        $receptions = ReceptionVendeur::with(['vendeur', 'produit'])
            ->when($date, function($query, $date) {
                return $query->whereDate('date_reception', $date);
            })
            ->when($vendeur_id, function($query, $vendeur_id) {
                return $query->where('vendeur_id', $vendeur_id);
            })
            ->orderBy('date_reception', 'desc')
            ->get();

        return response()->json($receptions);
    }

    public function rapport(Request $request)
    {
        $dateDebut = $request->get('date_debut', Carbon::now()->startOfMonth());
        $dateFin = $request->get('date_fin', Carbon::now()->endOfMonth());
        $vendeur_id = $request->get('vendeur_id');

        $receptions = ReceptionVendeur::with(['vendeur', 'produit'])
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->when($vendeur_id, function($query, $vendeur_id) {
                return $query->where('vendeur_id', $vendeur_id);
            })
            ->orderBy('date_reception', 'desc')
            ->get();

        $vendeurs = User::where('secteur', 'vente')->get();

        return view('receptions.vendeurs.rapport', compact('receptions', 'vendeurs', 'dateDebut', 'dateFin', 'vendeur_id'));
    }
}
