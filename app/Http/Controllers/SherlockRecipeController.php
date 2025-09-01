<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecipeAnalysisService;
use App\Services\AIQueryServiceSherlock;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SherlockRecipeController extends Controller
{
    protected $recipeAnalysisService;
    
    public function __construct(RecipeAnalysisService $recipeAnalysisService)
    {
        $this->recipeAnalysisService = $recipeAnalysisService;
    }
    
    /**
     * Affiche la page d'accueil de Sherlock Recette
     */
    public function index()
    {
        $recipes = Recipe::with('category')->orderBy('name')->get();
        $categories = RecipeCategory::orderBy('name')->get();
        
        $exampleQueries = [
            "Comment optimiser l'utilisation de la farine pour réduire les coûts?",
            "Quelle est la recette de base pour 50 croissants?",
            "Comment adapter mes pâtisseries au climat chaud et humide du Cameroun?",
            "Quelles sont les meilleures techniques pour produire des glaces qui résistent à la chaleur?",
            "Comment puis-je organiser ma production pour améliorer l'efficacité?"
        ];
        
        return view('sherlock.recipes.index', compact('recipes', 'categories', 'exampleQueries'));
    }
    
    /**
     * Traite une requête d'analyse de recette
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:10|max:1000',
        ]);
        
        try {
            $result = $this->recipeAnalysisService->analyzeRecipeQuery($validated['query']);
            
            if (!$result['success']) {
                return back()->with('error', $result['error']);
            }
            
            return view('sherlock.recipes.result', [
                'query' => $validated['query'],
                'response' => $result['response'],
                'isRecipeGeneration' => false
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in recipe analysis controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche le formulaire de génération de recette
     */
    public function createForm()
    {
        $categories = RecipeCategory::orderBy('name')->get();
        return view('sherlock.recipes.create', compact('categories'));
    }
    
    /**
     * Génère une nouvelle recette
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'quantity' => 'required|string|max:255',
            'specific_requirements' => 'nullable|string|max:1000',
        ]);
        
        try {
            $context = [];
            if (!empty($validated['specific_requirements'])) {
                $context['requirements'] = $validated['specific_requirements'];
            }
            
            $result = $this->recipeAnalysisService->generateRecipe(
                $validated['name'],
                $validated['category'],
                $validated['quantity'],
                $context
            );
            
            if (!$result['success']) {
                return back()->with('error', $result['error']);
            }
            
            return view('sherlock.recipes.recipe_result', [
                'recipe' => $result['recipe'],
                'raw_response' => $result['raw_response']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in recipe generation controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la génération de la recette: ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche la page d'optimisation d'une recette
     */
    public function optimizeForm($recipeId)
    {
        $recipe = Recipe::with(['ingredients.ingredient', 'steps', 'category'])
            ->findOrFail($recipeId);
        
        return view('sherlock.recipes.optimize', compact('recipe'));
    }
    
    /**
     * Optimise une recette existante
     */
    public function optimize(Request $request, $recipeId)
    {
        try {
            $result = $this->recipeAnalysisService->optimizeRecipe($recipeId);
            
            if (!$result['success']) {
                return back()->with('error', $result['error']);
            }
            
            return view('sherlock.recipes.optimization_result', [
                'recipe' => $result['recipe'],
                'optimization' => $result['optimization']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in recipe optimization controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'optimisation de la recette: ' . $e->getMessage());
        }
    }
    
    /**
     * Sauvegarde une recette générée par l'IA
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:recipe_categories,id',
            'description' => 'nullable|string',
            'preparation_time' => 'nullable|integer|min:0',
            'cooking_time' => 'nullable|integer|min:0',
            'rest_time' => 'nullable|integer|min:0',
            'yield_quantity' => 'required|integer|min:1',
            'difficulty_level' => 'nullable|string|max:50',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'ingredients.*.notes' => 'nullable|string',
            'steps' => 'required|array|min:1',
            'steps.*.instruction' => 'required|string',
            'steps.*.time_required' => 'nullable|integer|min:0',
        ]);
        
        try {
            \DB::beginTransaction();
            
            // Créer la recette
            $recipe = Recipe::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'preparation_time' => $validated['preparation_time'],
                'cooking_time' => $validated['cooking_time'],
                'rest_time' => $validated['rest_time'],
                'yield_quantity' => $validated['yield_quantity'],
                'difficulty_level' => $validated['difficulty_level'],
                'user_id' => auth()->id(),
                'active' => true,
            ]);
            
            // Ajouter les ingrédients
            foreach ($validated['ingredients'] as $index => $ingredientData) {
                // Vérifier si l'ingrédient existe déjà ou le créer
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    ['unit' => $ingredientData['unit']]
                );
                
                // Créer la relation entre la recette et l'ingrédient
                $recipe->ingredients()->create([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                    'notes' => $ingredientData['notes'] ?? null,
                    'order' => $index + 1,
                ]);
            }
            
            // Ajouter les étapes
            foreach ($validated['steps'] as $index => $stepData) {
                $recipe->steps()->create([
                    'step_number' => $index + 1,
                    'instruction' => $stepData['instruction'],
                    'tips' => null,
                    'time_required' => $stepData['time_required'] ?? null,
                ]);
            }
            
            \DB::commit();
            
            return redirect()->route('recipes.show', $recipe->id)
                ->with('success', 'Recette créée avec succès!');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error saving AI-generated recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la recette: ' . $e->getMessage());
        }
    }
    
    /**
     * Page de debug pour analyser les requêtes et réponses
     */
    public function debug(Request $request)
    {
        try {
            $query = $request->input('query', '');
            $result = null;
            
            if (!empty($query)) {
                $result = $this->recipeAnalysisService->analyzeRecipeQuery($query);
                
                // Log détaillé pour le débogage
                Log::debug('Recipe debug query result', [
                    'query' => $query,
                    'result' => $result
                ]);
            }
            
            return view('sherlock.recipes.debug', compact('query', 'result'));
            
        } catch (\Exception $e) {
            Log::error('Error in recipe debug controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('sherlock.recipes.debug', [
                'query' => $query ?? '',
                'error' => $e->getMessage()
            ]);
        }
    }
}
