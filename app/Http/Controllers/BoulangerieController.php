<?php

namespace App\Http\Controllers;

use App\Models\Sac;
use App\Models\SacConfiguration;
use App\Models\SacMatiere;
use App\Models\ProductionSac;
use App\Models\ProductionProduit;
use App\Models\Matiere;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoulangerieController extends Controller
{
    protected $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    // === CONFIGURATION DES SACS ===

    public function indexConfiguration()
    {
        $sacs = Sac::with('configuration', 'matieres')->actif()->get();
        return view('boulangerie.configuration.index', compact('sacs'));
    }

    public function createConfiguration()
    {
        $matieres = Matiere::all();
        return view('boulangerie.configuration.create', compact('matieres'));
    }

    public function storeConfiguration(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valeur_moyenne_fcfa' => 'required|numeric|min:0',
            'matieres' => 'required|array|min:1',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Créer le sac
            $sac = Sac::create([
                'nom' => $request->nom,
                'description' => $request->description,
                'actif' => true
            ]);

            // Créer la configuration
            SacConfiguration::create([
                'sac_id' => $sac->id,
                'valeur_moyenne_fcfa' => $request->valeur_moyenne_fcfa,
                'notes' => $request->notes,
                'actif' => true
            ]);

            // Associer les matières
            foreach ($request->matieres as $matiereData) {
                SacMatiere::create([
                    'sac_id' => $sac->id,
                    'matiere_id' => $matiereData['id'],
                    'quantite_utilisee' => $matiereData['quantite']
                ]);
            }
        });

        return redirect()->route('boulangerie.configuration.index')
                        ->with('success', 'Configuration du sac créée avec succès.');
    }

    public function editConfiguration($id)
    {
        $sac = Sac::with('configuration', 'matieres')->findOrFail($id);
        $matieres = Matiere::all();
        return view('boulangerie.configuration.edit', compact('sac', 'matieres'));
    }

    public function updateConfiguration(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valeur_moyenne_fcfa' => 'required|numeric|min:0',
            'matieres' => 'required|array|min:1',
            'matieres.*.id' => 'required|exists:Matiere,id',
            'matieres.*.quantite' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $sac = Sac::findOrFail($id);

        DB::transaction(function () use ($request, $sac) {
            // Mettre à jour le sac
            $sac->update([
                'nom' => $request->nom,
                'description' => $request->description
            ]);

            // Mettre à jour la configuration
            $sac->configuration->update([
                'valeur_moyenne_fcfa' => $request->valeur_moyenne_fcfa,
                'notes' => $request->notes
            ]);

            // Supprimer les anciennes associations de matières
            SacMatiere::where('sac_id', $sac->id)->delete();

            // Créer les nouvelles associations
            foreach ($request->matieres as $matiereData) {
                SacMatiere::create([
                    'sac_id' => $sac->id,
                    'matiere_id' => $matiereData['id'],
                    'quantite_utilisee' => $matiereData['quantite']
                ]);
            }
        });

        return redirect()->route('boulangerie.configuration.index')
                        ->with('success', 'Configuration du sac mise à jour avec succès.');
    }

    public function destroyConfiguration($id)
    {
        $sac = Sac::findOrFail($id);
        $sac->update(['actif' => false]);

        return redirect()->route('boulangerie.configuration.index')
                        ->with('success', 'Sac désactivé avec succès.');
    }

    // === PRODUCTION DES SACS ===

public function indexProduction() 
{
    
    // Charger les productions avec toutes les relations nécessaires
    $productions = ProductionSac::with([
        'sac.configuration', 
        'producteur:id,name,email', // Sélectionner seulement les champs nécessaires
        'productionProduits.produit:code_produit,nom,prix,categorie'
    ])
    ->where('producteur_id', Auth::id())
    ->orderBy('date_production', 'desc')
    ->get();

    // Vérifier si l'utilisateur est un admin
    $user = Auth::user();
    if($user->secteur == 'administration'){
        $productions = ProductionSac::with([
            'sac.configuration', 
            'producteur:id,name,email',
            'productionProduits.produit:code_produit,nom,prix,categorie'
        ])
        ->orderBy('date_production', 'desc')
        ->get();
    }

    return view('boulangerie.production.index', compact('productions'));
}

    public function createProduction()
    {
        $sacs = Sac::with('configuration')->actif()->get();
        $produits = Produit_fixes::where('categorie', 'boulangerie')->get();
        
        return view('boulangerie.production.create', compact('sacs', 'produits'));
    }

    public function storeProduction(Request $request)
    {
        $request->validate([
            'sac_id' => 'required|exists:sacs,id',
            'date_production' => 'required|date',
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:Produit_fixes,code_produit',
            'produits.*.quantite' => 'required|integer|min:1',
            'observations' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Créer la production
            $production = ProductionSac::create([
                'sac_id' => $request->sac_id,
                'producteur_id' => Auth::id(),
                'date_production' => $request->date_production,
                'observations' => $request->observations,
                'valide' => false
            ]);

            $valeurTotale = 0;

            // Ajouter les produits
            foreach ($request->produits as $produitData) {
                $produit = Produit_fixes::find($produitData['id']);
                $valeurUnitaire = $produit->prix;
                $valeurTotaleProduit = $produitData['quantite'] * $valeurUnitaire;
                $valeurTotale += $valeurTotaleProduit;

                ProductionProduit::create([
                    'production_sac_id' => $production->id,
                    'produit_id' => $produit->code_produit,
                    'quantite' => $produitData['quantite'],
                    'valeur_unitaire' => $valeurUnitaire,
                    'valeur_totale' => $valeurTotaleProduit
                ]);
            }

            // Mettre à jour la valeur totale
            $production->update(['valeur_totale_fcfa' => $valeurTotale]);

            // Vérifier si la production atteint la moyenne
            $this->verifierEtNotifier($production);
        });

        return redirect()->route('boulangerie.production.index')
                        ->with('success', 'Production enregistrée avec succès.');
    }

    public function editProduction($id)
    {
        $production = ProductionSac::with('productionProduits.produit')->findOrFail($id);
        
        // Vérifier que c'est le producteur qui modifie sa production
        if ($production->producteur_id !== Auth::id()) {
            abort(403);
        }

        $sacs = Sac::with('configuration')->actif()->get();
        $produits = Produit_fixes::where('categorie', 'boulangerie')->get();
        
        return view('boulangerie.production.edit', compact('production', 'sacs', 'produits'));
    }

    public function updateProduction(Request $request, $id)
    {
        $request->validate([
            'sac_id' => 'required|exists:sacs,id',
            'date_production' => 'required|date',
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:Produit_fixes,code_produit',
            'produits.*.quantite' => 'required|integer|min:1',
            'observations' => 'nullable|string'
        ]);

        $production = ProductionSac::findOrFail($id);
        
        if ($production->producteur_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($request, $production) {
            // Mettre à jour la production
            $production->update([
                'sac_id' => $request->sac_id,
                'date_production' => $request->date_production,
                'observations' => $request->observations
            ]);

            // Supprimer les anciens produits
            ProductionProduit::where('production_sac_id', $production->id)->delete();

            $valeurTotale = 0;

            // Ajouter les nouveaux produits
            foreach ($request->produits as $produitData) {
                $produit = Produit_fixes::find($produitData['id']);
                $valeurUnitaire = $produit->prix;
                $valeurTotaleProduit = $produitData['quantite'] * $valeurUnitaire;
                $valeurTotale += $valeurTotaleProduit;

                ProductionProduit::create([
                    'production_sac_id' => $production->id,
                    'produit_id' => $produit->code_produit,
                    'quantite' => $produitData['quantite'],
                    'valeur_unitaire' => $valeurUnitaire,
                    'valeur_totale' => $valeurTotaleProduit
                ]);
            }

            // Mettre à jour la valeur totale
            $production->update(['valeur_totale_fcfa' => $valeurTotale]);

            // Vérifier si la production atteint la moyenne
            $this->verifierEtNotifier($production);
        });

        return redirect()->route('boulangerie.production.index')
                        ->with('success', 'Production mise à jour avec succès.');
    }

    public function destroyProduction($id)
    {
        $production = ProductionSac::findOrFail($id);
        
        if ($production->producteur_id !== Auth::id()) {
            abort(403);
        }

        $production->delete();

        return redirect()->route('boulangerie.production.index')
                        ->with('success', 'Production supprimée avec succès.');
    }

    public function showProduction($id)
    {
        $production = ProductionSac::with('sac.configuration', 'producteur', 'productionProduits.produit')->findOrFail($id);
        
        return view('boulangerie.production.show', compact('production'));
    }

    // === MÉTHODES PRIVÉES ===

    private function verifierEtNotifier(ProductionSac $production)
    {
        Log::info("Vérification de la production ID {$production->id} pour notification.");
        if ($production->estSousLaMoyenne()) {
            // Récupérer les employés à notifier (DG et Chef de production)
            $employesANotifier = User::whereIn('role', ['dg', 'chef_production'])->get();
            
            $sac = $production->sac;
            $producteur = $production->producteur;
            $valeurMoyenne = $sac->configuration->valeur_moyenne_fcfa;
            $valeurActuelle = $production->valeur_totale_fcfa;
            $ecart = $valeurMoyenne - $valeurActuelle;

            foreach ($employesANotifier as $employe) {
                $notificationRequest = new Request([
                    'recipient_id' => $employe->id,
                    'subject' => "⚠️ Production sous la moyenne - Sac {$sac->nom}",
                    'message' => "Production du {$production->date_production->format('d/m/Y')} par {$producteur->name}:\n\n" .
                               "• Sac: {$sac->nom}\n" .
                               "• Valeur attendue: " . number_format($valeurMoyenne, 0, ',', ' ') . " FCFA\n" .
                               "• Valeur obtenue: " . number_format($valeurActuelle, 0, ',', ' ') . " FCFA\n" .
                               "• Écart: -" . number_format($ecart, 0, ',', ' ') . " FCFA\n\n" .
                               ($production->observations ? "Observations: {$production->observations}" : "")
                ]);

                $this->notificationController->send($notificationRequest);
            }
        }
    }

    // === API/AJAX ENDPOINTS ===

    public function getSacDetails($id)
    {
        $sac = Sac::with('configuration', 'matieres')->findOrFail($id);
        
        return response()->json([
            'sac' => $sac,
            'valeur_moyenne' => $sac->configuration ? $sac->configuration->valeur_moyenne_fcfa : 0,
            'matieres' => $sac->matieres
        ]);
    }

    public function getProduitPrix($id)
    {
        $produit = Produit_fixes::findOrFail($id);
        
        return response()->json([
            'prix' => $produit->prix
        ]);
    }
}
