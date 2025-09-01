<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\ProduitRecuVendeur;

class ReceptionProduitController extends Controller
{
    /**
     * Afficher la liste des réceptions de produits
     */
    public function index()
    {
        $user = Auth::user();
        
        $receptions = ProduitRecuVendeur::with(['produitRecu', 'vendeur'])
            ->where('vendeur_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reception-produits.index', compact('receptions'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        
        // Récupérer les pointeurs (secteur = production, role = pointeur)
        $pointeurs = User::where('secteur', 'production')
            ->where('role', 'pointeur')
            ->select('id', 'name')
            ->get();

        // Récupérer les produits disponibles
        $produits = Produit_fixes::all();

        return view('reception-produits.create', compact('pointeurs', 'produits'));
    }

    /**
     * Enregistrer une nouvelle réception
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'produit_recu_id' => 'required|exists:produits_recu_1,id',
            'quantite_recue' => 'required|integer|min:1',
            'remarques' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Créer la réception avec confirmation automatique
            $reception = ProduitRecuVendeur::create([
                'produit_recu_id' => $request->produit_recu_id,
                'vendeur_id' => $user->id,
                'quantite_recue' => $request->quantite_recue,
                'quantite_confirmee' => $request->quantite_recue, // Confirmation automatique
                'status' => 'confirmé', // Status confirmé automatiquement
                'remarques' => $request->remarques
            ]);

            DB::commit();

            return redirect()->route('reception-produits.index')
                ->with('success', 'Réception enregistrée avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une réception
     */
    public function show($id)
    {
        $user = Auth::user();

        $reception = ProduitRecuVendeur::with(['produitRecu', 'vendeur'])
            ->where('id', $id)
            ->where('vendeur_id', $user->id)
            ->firstOrFail();

        return view('reception-produits.show', compact('reception'));
    }

    /**
     * Vérifier si l'utilisateur est une vendeuse
     */
    private function isVendeuse($user)
    {
        return $user && 
               $user->secteur === 'vente' && 
               in_array($user->role, ['vendeuse_boulangerie', 'vendeuse_patisserie']);
    }

    /**
     * API pour récupérer les détails d'un produit
     */
    public function getProduitDetails($id)
    {
        $produit = ProduitRecu::find($id);
        
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        return response()->json([
            'id' => $produit->id,
            'nom_produit' => $produit->nom_produit,
            'quantite_totale' => $produit->quantite_totale,
            'description' => $produit->description ?? ''
        ]);
    }
}