<?php

namespace App\Http\Controllers;

use App\Models\AssignationMatiere;
use App\Models\AssignationRetour;
use App\Models\Matiere;
use App\Services\UniteConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\HistorisableActions;

class MatiereRetourController extends Controller
{
    use HistorisableActions;
    protected $uniteConversionService;

    public function __construct(UniteConversionService $uniteConversionService)
    {
        $this->uniteConversionService = $uniteConversionService;
    }

    public function index()
    {
        $user = Auth::user();
        $isFrench = app()->getLocale() === 'fr';

        if ($user->role === 'chef_production' || $user->role === 'pdg' || $user->role === 'dg') {
            // Pour les CP, PDG, DG - voir tous les retours en attente
            $retoursEnAttente = AssignationRetour::with(['assignation.matiere', 'producteur'])
                ->enAttente()
                ->orderBy('created_at', 'desc')
                ->get();
            
            $retoursValidees = AssignationRetour::with(['assignation.matiere', 'producteur', 'validateur'])
                ->validees()
                ->orderBy('date_validation', 'desc')
                ->limit(20)
                ->get();

            return view('matieres.retours.validation', compact('retoursEnAttente', 'retoursValidees', 'isFrench'));
        } else {
            // Pour les producteurs - voir leurs propres retours
            $mesRetours = AssignationRetour::with(['assignation.matiere', 'validateur'])
                ->where('producteur_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('matieres.retours.mes_retours', compact('mesRetours', 'isFrench'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $isFrench = app()->getLocale() === 'fr';

        // Récupérer les assignations du producteur avec quantité restante > 0
        $assignations = AssignationMatiere::with('matiere')
            ->where('producteur_id', $user->id)
            ->where('quantite_restante', '>', 0)
            ->where('date_limite_utilisation', '>=', Carbon::now())
            ->get();

        return view('matieres.retours.create', compact('assignations', 'isFrench'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assignation_id' => 'required|exists:assignations_matiere,id',
            'quantite_retournee' => 'required|numeric|min:0.001',
            'motif_retour' => 'nullable|string|max:500'
        ]);

        $assignation = AssignationMatiere::with('matiere')->findOrFail($request->assignation_id);
        
        // Vérifier que l'assignation appartient au producteur connecté
        if ($assignation->producteur_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        // Vérifier que la quantité à retourner ne dépasse pas la quantité restante
        if ($request->quantite_retournee > $assignation->quantite_restante) {
            return redirect()->back()->with('error', 'La quantité à retourner ne peut pas dépasser la quantité restante.');
        }

        try {
            DB::beginTransaction();

            // Calculer la quantité à ajouter au stock (conversion vers unité de stockage)
            $quantiteStockIncrementee = $this->calculerQuantiteStock(
                $request->quantite_retournee,
                $assignation->unite_assignee,
                $assignation->matiere
            );

            // Créer l'enregistrement de retour
            AssignationRetour::create([
                'assignation_id' => $assignation->id,
                'producteur_id' => Auth::id(),
                'matiere_id' => $assignation->matiere_id,
                'quantite_retournee' => $request->quantite_retournee,
                'unite_retour' => $assignation->unite_assignee,
                'quantite_stock_incrementee' => $quantiteStockIncrementee,
                'motif_retour' => $request->motif_retour,
                'statut' => 'en_attente'
            ]);

            DB::commit();

            $message = app()->getLocale() === 'fr' 
                ? 'Demande de retour enregistrée avec succès. Elle sera validée par le Chef de Production.'
                : 'Return request recorded successfully. It will be validated by the Production Manager.';

            $this->historiser("L'utilisateur " . auth()->user()->name . " a créé une demande de retour pour la matière ID: {$assignation->matiere_id}", 'create_retour');

            return redirect()->route('matieres.retours.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'enregistrement du retour de matière: ' . $e->getMessage());
            
            $message = app()->getLocale() === 'fr' 
                ? 'Erreur lors de l\'enregistrement du retour.'
                : 'Error recording the return.';

            return redirect()->back()->with('error', $message);
        }
    }

    public function valider(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:valider,refuser',
            'commentaire_validation' => 'nullable|string|max:500'
        ]);

        $retour = AssignationRetour::with(['assignation.matiere'])->findOrFail($id);

        if ($retour->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        try {
            DB::beginTransaction();

            if ($request->action === 'valider') {
                // Valider le retour
                $retour->update([
                    'statut' => 'validee',
                    'validee_par' => Auth::id(),
                    'date_validation' => now(),
                    'commentaire_validation' => $request->commentaire_validation
                ]);

                // Mettre à jour le stock de la matière
                $matiere = $retour->assignation->matiere;
                $matiere->increment('quantite', $retour->quantite_stock_incrementee);

                // Mettre à jour la quantité assignée
                $assignation = $retour->assignation;
                $assignation->update([
                    'quantite_assignee' => $assignation->quantite_assignee - $retour->quantite_retournee,
                    'quantite_restante' => $assignation->quantite_restante - $retour->quantite_retournee
                ]);

                $message = app()->getLocale() === 'fr' 
                    ? 'Retour validé avec succès. Le stock a été mis à jour.'
                    : 'Return validated successfully. Stock has been updated.';

            } else {
                // Refuser le retour
                $retour->update([
                    'statut' => 'refusee',
                    'validee_par' => Auth::id(),
                    'date_validation' => now(),
                    'commentaire_validation' => $request->commentaire_validation
                ]);

                $message = app()->getLocale() === 'fr' 
                    ? 'Retour refusé.'
                    : 'Return rejected.';
            }
            // Historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a " . ($request->action === 'valider' ? 'validé' : 'refusé') . " le retour ID: {$id}", 'valider_retour');
            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la validation du retour: ' . $e->getMessage());
            
            $message = app()->getLocale() === 'fr' 
                ? 'Erreur lors de la validation.'
                : 'Error during validation.';

            return redirect()->back()->with('error', $message);
        }
    }

    private function calculerQuantiteStock($quantiteRetournee, $uniteRetour, $matiere)
    {
        try {
            // Convertir la quantité retournée en unité minimale
            $quantiteEnUniteMinimale = $this->uniteConversionService->convertir(
                $quantiteRetournee,
                $uniteRetour,
                $matiere->unite_classique
            );

            // Convertir en unités de stock (diviser par quantite_par_unite)
            $quantiteStock = $quantiteEnUniteMinimale / $matiere->quantite_par_unite;


            Log::info('Quantité stock calculée: ' . $quantiteStock);
            //historiser l'action
            $this->historiser("L'utilisateur " . auth()->user()->name . " a calculé la quantité stock pour le retour de matière ID: {$matiere->id}", 'calculer_quantite_stock');
            return round($quantiteStock, 3);

        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul de la quantité stock: ' . $e->getMessage());
            throw $e;
        }
    }
}
