<?php
// app/Http/Controllers/AvarieController.php

namespace App\Http\Controllers;

use App\Models\Avarie;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Avarie2Controller extends Controller
{
    // Afficher la liste des avaries avec filtres
    public function index(Request $request)
    {
        $query = Avarie::with(['produit', 'user']);
        
        // Si l'utilisateur n'est pas admin/manager, il ne voit que ses avaries
        if (!in_array(Auth::user()->secteur, ['administration'])) {
            $query->where('user_id', Auth::id());
        }
        
        // Filtre par pointeur (pour admin/manager)
        if ($request->filled('pointeur_id') && in_array(Auth::user()->secteur, ['administration'])) {
            $query->where('user_id', $request->pointeur_id);
        }
        
        // Filtre par date de début
        if ($request->filled('date_debut')) {
            $query->where('date_avarie', '>=', $request->date_debut);
        }
        
        // Filtre par date de fin
        if ($request->filled('date_fin')) {
            $query->where('date_avarie', '<=', $request->date_fin);
        }
        
        // Tri par colonne et ordre
        $sortBy = $request->get('sort_by', 'date_avarie');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validations des colonnes de tri
        $allowedSortColumns = ['date_avarie', 'montant_total', 'quantite', 'pointeur'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'date_avarie';
        }
        
        // Tri spécial pour le pointeur (join avec users)
        if ($request->get('sort_by') === 'pointeur' && in_array(Auth::user()->role, ['admin', 'manager'])) {
            $query->join('users', 'avaries.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('avaries.*'); // Éviter la duplication des colonnes
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $avaries = $query->paginate(15)->withQueryString();
        
        // Récupérer la liste des pointeurs pour le filtre (admin/manager seulement)
        $pointeurs = collect();
        if (in_array(Auth::user()->role, ['admin', 'manager'])) {
            $pointeurs = User::where('role', 'pointeur')
                ->orderBy('name')
                ->get(['id', 'name', 'secteur']);
        }
        
        return view('avaries.index', compact('avaries', 'pointeurs'));
    }

    // Afficher le formulaire de création d'avarie
    public function create()
    {
        $produits = Produit_fixes::orderBy('nom')->get();
        return view('avaries.create', compact('produits'));
    }

    // Enregistrer une nouvelle avarie
    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:Produit_fixes,code_produit',
            'quantite' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'date_avarie' => 'required|date|before_or_equal:today'
        ]);

        // Récupérer le prix du produit
        $produit = Produit_fixes::find($request->produit_id);
        $montantTotal = $produit->prix * $request->quantite;

        Avarie::create([
            'user_id' => Auth::id(),
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'montant_total' => $montantTotal,
            'description' => $request->description,
            'date_avarie' => $request->date_avarie
        ]);

        return redirect()->route('avaries.index')
            ->with('success', 'Avarie enregistrée avec succès.');
    }

    // Afficher les détails d'une avarie
    public function show(Avarie $avarie)
    {
        // Vérifier que l'avarie appartient au pointeur connecté
        if ($avarie->user_id !== Auth::id()) {
            abort(403);
        }

        return view('avaries.show', compact('avarie'));
    }

    // Vue administrative - Rapport des avaries par pointeur
    public function rapport()
    {
        // Vérifier que l'utilisateur a le droit d'accès (admin/manager)
        if (!in_array(Auth::user()->role, ['admin', 'manager'])) {
            abort(403);
        }

        // Rapport mensuel des avaries par pointeur
        $rapportPointeurs = DB::table('avaries')
            ->join('users', 'avaries.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.secteur',
                DB::raw('COUNT(avaries.id) as nombre_avaries'),
                DB::raw('SUM(avaries.montant_total) as montant_total_avaries'),
                DB::raw('MONTH(avaries.date_avarie) as mois'),
                DB::raw('YEAR(avaries.date_avarie) as annee')
            )
            ->whereYear('avaries.date_avarie', date('Y'))
            ->groupBy('users.id', 'users.name', 'users.secteur', 'mois', 'annee')
            ->orderBy('montant_total_avaries', 'desc')
            ->get();

        // Résumé général par pointeur (toute période)
        $resumeGeneral = DB::table('avaries')
            ->join('users', 'avaries.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.secteur',
                DB::raw('COUNT(avaries.id) as total_avaries'),
                DB::raw('SUM(avaries.montant_total) as montant_total'),
                DB::raw('AVG(avaries.montant_total) as moyenne_par_avarie'),
                DB::raw('MIN(avaries.date_avarie) as premiere_avarie'),
                DB::raw('MAX(avaries.date_avarie) as derniere_avarie')
            )
            ->groupBy('users.id', 'users.name', 'users.secteur')
            ->orderBy('montant_total', 'desc')
            ->get();

        return view('avaries.rapport', compact('rapportPointeurs', 'resumeGeneral'));
    }

    // API pour récupérer le prix d'un produit (AJAX)
    public function getPrixProduit($produitId)
    {
        $produit = Produit_fixes::find($produitId);
        return response()->json([
            'prix' => $produit ? $produit->prix : 0
        ]);
    }
}