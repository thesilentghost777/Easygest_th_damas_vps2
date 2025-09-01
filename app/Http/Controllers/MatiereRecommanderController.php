<?php

namespace App\Http\Controllers;

use App\Models\MatiereRecommander;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\HistorisableActions;

class MatiereRecommanderController extends Controller
{
    use HistorisableActions;
    /**
     * Affiche la liste des produits avec leurs matières recommandées
     */
    public function index()
    {
        $produits = Produit_fixes::with('matiereRecommandee.matiere')->paginate(10);
        return view('matieres.recommandees.index', compact('produits'));
    }
    
    /**
     * Affiche le formulaire pour ajouter des matières recommandées à un produit
     */
    public function create($produitId = null)
    {
        $produit = null;
        $produits = Produit_fixes::orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')
        ->where('nom','not like', 'Taule%')
        ->where('nom','not like', 'produit avarie%')
        ->get();
        
        if ($produitId) {
            $produit = Produit_fixes::with('matiereRecommandee.matiere')->findOrFail($produitId);
        }
        
        return view('matieres.recommandees.create', compact('produit', 'produits', 'matieres'));
    }
    
    /**
     * Enregistre une nouvelle matière recommandée
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produit' => 'required|exists:Produit_fixes,code_produit',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite' => 'required|numeric|min:0',
            'matieres.*.unite' => 'required|string',
            'quantite_produit' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $produit = $request->input('produit');
        $quantiteP = $request->input('quantite_produit');
        $matieres = $request->input('matieres');
        
        foreach ($matieres as $matiere) {
         
            MatiereRecommander::create([
                'produit' => $produit,
                'matierep' => $matiere['id'],
                'quantitep' => $quantiteP,
                'quantite' => $matiere['quantite'],
                'unite' => $matiere['unite']
            ]);
        }

        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a ajouté des matières recommandées pour le produit {$produit}", 'add_matiere_recommandee');

        return redirect()->route('matieres.recommandees.show', $produit)
            ->with('success', 'Les matières recommandées ont été ajoutées avec succès.');
    }
    
    /**
     * Affiche les matières recommandées pour un produit spécifique
     */
    public function show($id)
    {
        $produit = Produit_fixes::with('matiereRecommandee.matiere')->findOrFail($id);
        $matieres = Matiere::whereNotIn('id', $produit->matiereRecommandee->pluck('matierep'))->orderBy('nom')
        ->where('nom','not like', 'Taule%')
        ->where('nom','not like', 'produit avarie%')
        ->get();
        
        return view('matieres.recommandees.show', compact('produit', 'matieres'));
    }
    
    /**
     * Affiche le formulaire pour modifier une matière recommandée
     */
    public function edit($id)
    {
        $matiereRecommandee = MatiereRecommander::with(['produit_fixes', 'matiere'])->findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé
        if (!$this->peutModifier()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier les matières recommandées.');
        }
        
        return view('matieres.recommandees.edit', compact('matiereRecommandee'));
    }
    
    /**
     * Met à jour une matière recommandée
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantitep' => 'required|integer|min:1',
            'quantite' => 'required|numeric|min:0',
            'unite' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $matiereRecommandee = MatiereRecommander::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé
        if (!$this->peutModifier()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier les matières recommandées.');
        }
      
        $matiereRecommandee->update([
            'quantitep' => $request->input('quantitep'),
            'quantite' => $request->input('quantite'),
            'unite' => $request->input('unite')
        ]);

        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour la matière recommandée ID: {$id}", 'update_matiere_recommandee');

        return redirect()->route('matieres.recommandees.show', $matiereRecommandee->produit)
            ->with('success', 'La matière recommandée a été mise à jour avec succès.');
    }
    
    /**
     * Ajoute une matière recommandée supplémentaire à un produit
     */
    public function addMatiere(Request $request, $produitId)
    {
        $validator = Validator::make($request->all(), [
            'matierep' => 'required|exists:Matiere,id',
            'quantitep' => 'required|integer|min:1',
            'quantite' => 'required|numeric|min:0',
            'unite' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Vérifier si l'utilisateur est autorisé
        if (!$this->peutModifier()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à ajouter des matières recommandées.');
        }
        
        // Vérifier si cette matière est déjà recommandée pour ce produit
        $existant = MatiereRecommander::where('produit', $produitId)
            ->where('matierep', $request->input('matierep'))
            ->first();
            
        if ($existant) {
            return redirect()->back()
                ->with('error', 'Cette matière est déjà recommandée pour ce produit.')
                ->withInput();
        }
        
         
        MatiereRecommander::create([
            'produit' => $produitId,
            'matierep' => $request->input('matierep'),
            'quantitep' => $request->input('quantitep'),
            'quantite' => $request->input('quantite'),
            'unite' => $request->input('unite')
        ]);
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a ajouté une matière recommandée pour le produit ID: {$produitId}", 'add_matiere_recommandee');
        return redirect()->route('matieres.recommandees.show', $produitId)
            ->with('success', 'La matière a été ajoutée avec succès aux recommandations.');
    }
    
    /**
     * Supprimer une matière recommandée
     */
    public function destroy($id)
    {
        $matiereRecommandee = MatiereRecommander::findOrFail($id);
        $produitId = $matiereRecommandee->produit;
        
        // Vérifier si l'utilisateur est autorisé
        if (!$this->peutModifier()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer les matières recommandées.');
        }
        
        $matiereRecommandee->delete();
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé la matière recommandée ID: {$id}", 'delete_matiere_recommandee');
        return redirect()->route('matieres.recommandees.show', $produitId)
            ->with('success', 'La matière recommandée a été supprimée avec succès.');
    }
    
    /**
     * API pour obtenir les données de conversion sur la base de la quantité produit
     */
    public function getConversion(Request $request)
    {
        $produitId = $request->input('produit_id');
        $quantiteProduit = $request->input('quantite_produit', 1);
        
        if (!$produitId) {
            return response()->json(['error' => 'ID du produit requis'], 400);
        }
        
        $matieres = MatiereRecommander::where('produit', $produitId)
            ->with('matiere')
            ->get()
            ->map(function ($item) use ($quantiteProduit) {
                $facteur = $quantiteProduit / $item->quantitep;
                $quantiteAjustee = $item->quantite * $facteur;
                
                return [
                    'id' => $item->id,
                    'matiere_id' => $item->matierep,
                    'matiere_nom' => $item->matiere->nom,
                    'quantite' => $quantiteAjustee,
                    'unite' => $item->unite,
                    'stock_disponible' => $this->getStockDisponible($item->matierep)
                ];
            });
            
        return response()->json([
            'produit_id' => $produitId,
            'quantite_produit' => $quantiteProduit,
            'matieres' => $matieres
        ]);
    }
    
    /**
     * Obtient le stock disponible d'une matière
     */
    private function getStockDisponible($matiereId)
    {
        $matiere = Matiere::find($matiereId);
        return $matiere ? $matiere->quantite : 0;
    }
    
    /**
     * Vérifie si l'utilisateur peut modifier les recommandations
     */
    private function peutModifier()
    {
        $user = Auth::user();
        
        // Le DG ou CP peut toujours modifier
       if ($user->secteur == 'administration') {
            return true;
       }
    }
}
