<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\HistorisableActions;

class FeatureController extends Controller
{
    use HistorisableActions;
    /**
     * Afficher la liste des fonctionnalités
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer les fonctionnalités groupées par catégorie
        $featuresByCategory = Feature::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        // Traduction des catégories pour l'affichage
        $categoryTranslations = [
            'all_employees' => 'Tous les employés',
            'producers' => 'Producteurs',
            'sellers' => 'Vendeurs',
            'cashiers' => 'Caissiers',
            'production_manager' => 'Chef de production',
            'structure' => 'Structure'
        ];

        return view('features.index', compact('featuresByCategory', 'categoryTranslations'));
    }

    /**
     * Mettre à jour le statut d'une fonctionnalité
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feature_id' => 'required|exists:features,id',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Données invalides'], 422);
        }

        try {
            $feature = Feature::findOrFail($request->feature_id);
            $feature->active = $request->active;
            $feature->save();

            // Réinitialiser le cache pour cette fonctionnalité
            Feature::resetCache($feature->code);

            // Historiser l'action
            $action = $request->active ? 'activer' : 'désactiver';
            $this->historiser("L'utilisateur " . auth()->user()->name . " a {$action} la fonctionnalité '{$feature->name}'", "{$action}_feature");
            return response()->json([
                'success' => true, 
                'message' => "La fonctionnalité '{$feature->name}' a été " . ($request->active ? 'activée' : 'désactivée')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Une erreur est survenue'], 500);
        }
    }

    /**
     * Activer toutes les fonctionnalités
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enableAll()
    {
        Feature::query()->update(['active' => true]);
        Feature::resetAllCache();

        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a activé toutes les fonctionnalités", 'enable_all_features');

        return redirect()->route('features.index')->with('success', 'Toutes les fonctionnalités ont été activées');
    }

    /**
     * Désactiver toutes les fonctionnalités d'une catégorie
     *
     * @param string $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableCategory($category)
    {
        Feature::where('category', $category)->update(['active' => false]);
        Feature::resetAllCache();
        
        $categoryNames = [
            'all_employees' => 'Tous les employés',
            'producers' => 'Producteurs',
            'sellers' => 'Vendeurs',
            'cashiers' => 'Caissiers',
            'production_manager' => 'Chef de production',
            'structure' => 'Structure'
        ];
        
        $categoryName = $categoryNames[$category] ?? $category;

        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a désactivé toutes les fonctionnalités de la catégorie '{$categoryName}'", 'disable_category_features');

        return redirect()->route('features.index')->with('success', "Toutes les fonctionnalités de la catégorie '{$categoryName}' ont été désactivées");
    }
}