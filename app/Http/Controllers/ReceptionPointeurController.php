<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceptionPointeur;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReceptionPointeurController extends Controller
{
    public function index(Request $request)
    {
        $pointeurs = User::where('role', 'pointeur')->get();
        $produits = Produit_fixes::all();
        
        $query = ReceptionPointeur::with(['pointeur', 'produit']);
        
        // Filtres
        if ($request->filled('pointeur_id')) {
            $query->where('pointeur_id', $request->pointeur_id);
        }
        
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }
        
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_reception', [$request->date_debut, $request->date_fin]);
        } elseif ($request->filled('date_reception')) {
            $query->whereDate('date_reception', $request->date_reception);
        }
        $user = Auth::user();
        $receptions = $query->orderBy('date_reception', 'desc')
                           ->orderBy('created_at', 'desc')
                           //->where('pointeur_id', $user->id) // Filtrer par l'utilisateur connecté
                           ->paginate(20);
        
        return view('receptions.pointeurs.index', compact('pointeurs', 'produits', 'receptions'));
    }

    public function create()
    {
        $pointeurs = User::where('role', 'pointeur')->get();
        $produits = Produit_fixes::orderBy('nom', 'asc')->get();

        
        return view('receptions.pointeurs.create', compact('pointeurs', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pointeur_id' => 'required|exists:users,id',
            'date_reception' => 'required|date',
            'produits' => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:Produit_fixes,code_produit',
            'produits.*.quantite_recue' => 'required|numeric|min:0.01',
        ], [
            'pointeur_id.required' => 'Veuillez sélectionner un pointeur.',
            'pointeur_id.exists' => 'Le pointeur sélectionné n\'existe pas.',
            'date_reception.required' => 'La date de réception est obligatoire.',
            'date_reception.date' => 'La date de réception doit être une date valide.',
            'produits.required' => 'Vous devez ajouter au moins un produit.',
            'produits.min' => 'Vous devez ajouter au moins un produit.',
            'produits.*.produit_id.required' => 'Veuillez sélectionner un produit.',
            'produits.*.produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'produits.*.quantite_recue.required' => 'La quantité reçue est obligatoire.',
            'produits.*.quantite_recue.numeric' => 'La quantité reçue doit être un nombre.',
            'produits.*.quantite_recue.min' => 'La quantité reçue doit être supérieure à 0.',
        ]);

        DB::beginTransaction();
        
        try {
            $createdCount = 0;
            $updatedCount = 0;
            
            foreach ($request->produits as $produitData) {
                $reception = ReceptionPointeur::updateOrCreate(
                    [
                        'pointeur_id' => $request->pointeur_id,
                        'produit_id' => $produitData['produit_id'],
                        'date_reception' => $request->date_reception,
                    ],
                    [
                        'quantite_recue' => $produitData['quantite_recue'],
                    ]
                );
                
                if ($reception->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            $message = "Réceptions enregistrées avec succès. ";
            if ($createdCount > 0) {
                $message .= "{$createdCount} nouvelle(s) réception(s) créée(s). ";
            }
            if ($updatedCount > 0) {
                $message .= "{$updatedCount} réception(s) mise(s) à jour.";
            }
            
            return redirect()->route('receptions.pointeurs.index')
                           ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $reception = ReceptionPointeur::with(['pointeur', 'produit'])->findOrFail($id);
        
        // Récupérer les autres réceptions du même pointeur pour la même date
        $relatedReceptions = ReceptionPointeur::with(['produit'])
            ->where('pointeur_id', $reception->pointeur_id)
            ->where('date_reception', $reception->date_reception)
            ->where('id', '!=', $reception->id)
            ->get();
        
        return view('receptions.pointeurs.show', compact('reception', 'relatedReceptions'));
    }

    public function edit($id)
    {
        $reception = ReceptionPointeur::with(['pointeur', 'produit'])->findOrFail($id);
        $pointeurs = User::where('role', 'pointeur')->get();
        $produits = Produit_fixes::orderBy('nom', 'asc')->get();
        
        return view('receptions.pointeurs.edit', compact('reception', 'pointeurs', 'produits'));
    }

    public function update(Request $request, $id)
    {
        $reception = ReceptionPointeur::findOrFail($id);
        
        $request->validate([
            'pointeur_id' => 'required|exists:users,id',
            'date_reception' => 'required|date',
            'produit_id' => 'required|exists:Produit_fixes,code_produit',
            'quantite_recue' => 'required|numeric|min:0.01',
        ], [
            'pointeur_id.required' => 'Veuillez sélectionner un pointeur.',
            'pointeur_id.exists' => 'Le pointeur sélectionné n\'existe pas.',
            'date_reception.required' => 'La date de réception est obligatoire.',
            'date_reception.date' => 'La date de réception doit être une date valide.',
            'produit_id.required' => 'Veuillez sélectionner un produit.',
            'produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'quantite_recue.required' => 'La quantité reçue est obligatoire.',
            'quantite_recue.numeric' => 'La quantité reçue doit être un nombre.',
            'quantite_recue.min' => 'La quantité reçue doit être supérieure à 0.',
        ]);

        // Vérifier s'il existe déjà une réception avec les mêmes critères (sauf l'ID actuel)
        $existingReception = ReceptionPointeur::where('pointeur_id', $request->pointeur_id)
            ->where('produit_id', $request->produit_id)
            ->where('date_reception', $request->date_reception)
            ->where('id', '!=', $id)
            ->first();

        if ($existingReception) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Une réception existe déjà pour ce pointeur, ce produit et cette date.');
        }

        try {
            $reception->update([
                'pointeur_id' => $request->pointeur_id,
                'date_reception' => $request->date_reception,
                'produit_id' => $request->produit_id,
                'quantite_recue' => $request->quantite_recue,
            ]);

            return redirect()->route('receptions.pointeurs.index')
                           ->with('success', 'Réception mise à jour avec succès.');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $reception = ReceptionPointeur::findOrFail($id);
            $reception->delete();

            return redirect()->route('receptions.pointeurs.index')
                           ->with('success', 'Réception supprimée avec succès.');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Exporter les réceptions en PDF
     */
    public function exportPdf(Request $request)
    {
        $query = ReceptionPointeur::with(['pointeur', 'produit']);
        
        // Appliquer les filtres si présents
        if ($request->filled('pointeur_id')) {
            $query->where('pointeur_id', $request->pointeur_id);
        }
        
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_reception', [$request->date_debut, $request->date_fin]);
        }
        
        $receptions = $query->orderBy('date_reception', 'desc')->get();
        
        // Ici vous pouvez utiliser une librairie comme DomPDF
        // return PDF::loadView('receptions.pointeurs.pdf', compact('receptions'))->download('receptions.pdf');
        
        return response()->json(['message' => 'Fonctionnalité PDF à implémenter']);
    }

    /**
     * Statistiques des réceptions
     */
    public function stats()
    {
        $stats = [
            'total_receptions' => ReceptionPointeur::count(),
            'receptions_aujourd_hui' => ReceptionPointeur::whereDate('date_reception', today())->count(),
            'receptions_ce_mois' => ReceptionPointeur::whereMonth('date_reception', now()->month)
                                                   ->whereYear('date_reception', now()->year)
                                                   ->count(),
            'pointeurs_actifs' => ReceptionPointeur::distinct('pointeur_id')->count(),
            'produits_recus' => ReceptionPointeur::distinct('produit_id')->count(),
            'quantite_totale' => ReceptionPointeur::sum('quantite_recue'),
        ];

        // Top 5 des pointeurs les plus actifs
        $topPointeurs = ReceptionPointeur::select('pointeur_id', DB::raw('COUNT(*) as nb_receptions'))
            ->with('pointeur')
            ->groupBy('pointeur_id')
            ->orderBy('nb_receptions', 'desc')
            ->take(5)
            ->get();

        // Top 5 des produits les plus reçus
        $topProduits = ReceptionPointeur::select('produit_id', DB::raw('SUM(quantite_recue) as quantite_totale'))
            ->with('produit')
            ->groupBy('produit_id')
            ->orderBy('quantite_totale', 'desc')
            ->take(5)
            ->get();

        return view('receptions.pointeurs.stats', compact('stats', 'topPointeurs', 'topProduits'));
    }
}