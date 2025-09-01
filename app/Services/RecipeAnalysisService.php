<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Ingredient;
use App\Models\RecipeCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RecipeAnalysisService
{
    /**
     * Analyse une question liée aux recettes et retourne les informations pertinentes
     */
    public function analyzeRecipeQuery($question, $context = [])
    {
        Log::info('Analyzing recipe query', [
            'question' => $question,
            'context' => $context
        ]);
        
        try {
            // Récupération des données de recettes pour fournir du contexte à l'IA
            $recipeData = $this->collectRecipeContextData();
            
            // Utilisation du service IA pour obtenir une réponse
            $aiService = app(AIQueryServiceSherlock::class);
            $aiService->initConversation($this->buildRecipeSystemPrompt());
            
            // Ajout du contexte des recettes - Conversion en array pour éviter l'erreur
            $dataArray = is_array($recipeData) ? $recipeData : $recipeData->toArray();            
             // Ajout de la question de l'utilisateur
            $aiService->addMessage($question);
            
            // Génération d'une réponse
            $response = $aiService->getResponse('recipe_query_' . md5($question));
            
            Log::info('Recipe analysis completed successfully');
            
            return [
                'success' => true,
                'response' => $response,
                'context' => $recipeData
            ];
        } catch (\Exception $e) {
            Log::error('Error analyzing recipe query', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'analyse de la recette: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Génère une nouvelle recette basée sur les paramètres fournis
     */
    public function generateRecipe($name, $category, $quantity, $context = [])
    {
        Log::info('Generating recipe', [
            'name' => $name,
            'category' => $category,
            'quantity' => $quantity,
            'context' => $context
        ]);
        
        try {
            // Récupération des données de contexte pour l'IA
            $ingredientsData = $this->collectIngredientsData();
            
            // Utilisation du service IA pour générer la recette
            $aiService = app(AIQueryServiceSherlock::class);
            $aiService->initConversation($this->buildRecipeGenerationPrompt());
            
            // Ajout des données d'ingrédients disponibles
            $aiService->addStructuredData('Ingrédients disponibles', $ingredientsData);
            
            $userLanguage = auth()->user()->language;

            if ($userLanguage == 'fr') {
                $langue = 'française';
            } else {
                $langue = 'anglaise';
            }
            Log::info("Langue:{$langue}");
            // Construction de la demande de recette
            $recipeRequest = "Génère une recette détaillée pour {$quantity} {$name} dans la catégorie {$category}. " .
                             "Inclus les quantités précises d'ingrédients en grammes/litres/unités et les étapes détaillées de préparation adaptées pour une boulangerie-pâtisserie camerounaise.: Renvoie le rapport dans la langue {$langue}";
             
            $aiService->addMessage($recipeRequest);
            
            // Génération de la recette
            $generatedRecipe = $aiService->getResponse('recipe_generation_' . md5($recipeRequest));
            
            // Traitement de la recette générée pour extraction des données structurées
            $parsedRecipe = $this->parseGeneratedRecipe($generatedRecipe, $name, $category, $quantity);
            
            Log::info('Recipe generation completed successfully');
            
            return [
                'success' => true,
                'recipe' => $parsedRecipe,
                'raw_response' => $generatedRecipe
            ];
        } catch (\Exception $e) {
            Log::error('Error generating recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération de la recette: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Analyse une recette existante et propose des optimisations
     */
    public function optimizeRecipe($recipeId)
    {
        Log::info('Optimizing recipe', [
            'recipe_id' => $recipeId
        ]);
        
        try {
            $recipe = Recipe::with(['ingredients.ingredient', 'steps', 'category'])
                ->findOrFail($recipeId);
            
            // Récupération des données de contexte
            $productionData = $this->collectProductionData($recipe->name);
            
            // Utilisation du service IA pour analyser et optimiser
            $aiService = app(AIQueryServiceSherlock::class);
            $aiService->initConversation($this->buildRecipeOptimizationPrompt());
            
            // Ajout des données de recette
            $recipeData = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'category' => $recipe->category ? $recipe->category->name : 'Non catégorisé',
                'preparation_time' => $recipe->preparation_time,
                'cooking_time' => $recipe->cooking_time,
                'rest_time' => $recipe->rest_time,
                'yield_quantity' => $recipe->yield_quantity,
                'difficulty' => $recipe->difficulty_level,
                'ingredients' => $recipe->ingredients->map(function($item) {
                    return [
                        'name' => $item->ingredient->name,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit ?: $item->ingredient->unit,
                        'notes' => $item->notes
                    ];
                }),
                'steps' => $recipe->steps->map(function($step) {
                    return [
                        'number' => $step->step_number,
                        'instruction' => $step->instruction,
                        'time' => $step->time_required
                    ];
                })
            ];
            
            $aiService->addStructuredData('Recette à optimiser', $recipeData);
            $aiService->addStructuredData('Données de production historiques', $productionData);
            
            // Construction de la demande d'optimisation
            $optimizationRequest = "Analyse cette recette de {$recipe->name} et propose des optimisations pour: " .
                                  "1. Réduire le coût des ingrédients sans compromettre la qualité " .
                                  "2. Améliorer l'efficacité de production " .
                                  "3. Ajuster les proportions pour un rendement optimal " .
                                  "4. Réduire le gaspillage de matières premières";
            
            $aiService->addMessage($optimizationRequest);
            
            // Génération de l'analyse
            $optimization = $aiService->getResponse('recipe_optimization_' . $recipeId);
            
            Log::info('Recipe optimization completed successfully');
            
            return [
                'success' => true,
                'recipe' => $recipeData,
                'optimization' => $optimization
            ];
        } catch (\Exception $e) {
            Log::error('Error optimizing recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'optimisation de la recette: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Parse une recette générée par l'IA en format structuré
     */
    private function parseGeneratedRecipe($generatedText, $name, $category, $quantity)
    {
        // Structure de base pour la recette
        $parsedRecipe = [
            'name' => $name,
            'category' => $category,
            'yield_quantity' => $quantity,
            'ingredients' => [],
            'steps' => [],
            'notes' => []
        ];
        
        // Extraction des ingrédients (recherche des lignes avec quantités)
        $lines = explode("\n", $generatedText);
        $currentSection = 'ingredients'; // Par défaut on commence par les ingrédients
        $stepCount = 1;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Détection des sections
            if (preg_match('/(ingrédients|ingredients)/i', $line)) {
                $currentSection = 'ingredients';
                continue;
            } elseif (preg_match('/(étapes|steps|préparation|preparation)/i', $line)) {
                $currentSection = 'steps';
                continue;
            } elseif (preg_match('/(notes|conseils|tips)/i', $line)) {
                $currentSection = 'notes';
                continue;
            }
            
            // Traitement selon la section
            if ($currentSection === 'ingredients') {
                // Essayer de détecter un ingrédient avec sa quantité
                if (preg_match('/^[\-\*•]\s*([\d.,]+)\s*([a-zéèêëàâäôöûüùïîç]+)\s+(?:de\s+)?(.+)$/i', $line, $matches) || 
                    preg_match('/^([\d.,]+)\s*([a-zéèêëàâäôöûüùïîç]+)\s+(?:de\s+)?(.+)$/i', $line, $matches)) {
                    $quantity = str_replace(',', '.', $matches[1]);
                    $unit = trim($matches[2]);
                    $ingredient = trim($matches[3]);
                    
                    $parsedRecipe['ingredients'][] = [
                        'name' => $ingredient,
                        'quantity' => (float)$quantity,
                        'unit' => $unit,
                        'notes' => ''
                    ];
                }
            } elseif ($currentSection === 'steps') {
                // Essayer de détecter une étape (avec ou sans numérotation)
                if (preg_match('/^[\d]+[\.\)]\s*(.+)$/i', $line, $matches)) {
                    $step = $matches[1];
                } else {
                    $step = $line;
                }
                
                $parsedRecipe['steps'][] = [
                    'number' => $stepCount,
                    'instruction' => trim($step),
                    'time' => null
                ];
                $stepCount++;
            } elseif ($currentSection === 'notes') {
                $parsedRecipe['notes'][] = $line;
            }
        }
        
        // Extraire automatiquement les temps de préparation/cuisson si mentionnés
        $parsedRecipe['preparation_time'] = $this->extractTimeFromText($generatedText, 'préparation');
        $parsedRecipe['cooking_time'] = $this->extractTimeFromText($generatedText, 'cuisson');
        $parsedRecipe['rest_time'] = $this->extractTimeFromText($generatedText, 'repos');
        
        // Définir un niveau de difficulté par défaut
        $parsedRecipe['difficulty_level'] = 'moyen';
        
        return $parsedRecipe;
    }
    
    /**
     * Extraire un temps (en minutes) à partir du texte généré
     */
    private function extractTimeFromText($text, $timeType)
    {
        $patterns = [
            "/{$timeType}\s*:?\s*(\d+)\s*(minutes|mins|min)/i",
            "/(\d+)\s*(minutes|mins|min)\s*(?:de|pour)?\s*{$timeType}/i"
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return (int)$matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Collecte des données de recettes pour le contexte
     */
    private function collectRecipeContextData()
    {
        $recipes = Recipe::with(['ingredients.ingredient', 'category'])->get();
        $categories = RecipeCategory::all();
        
        $topIngredients = RecipeIngredient::select('ingredient_id', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('ingredient_id')
            ->orderByDesc('usage_count')
            ->limit(20)
            ->with('ingredient')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->ingredient->name,
                    'usage_count' => $item->usage_count,
                    'unit' => $item->ingredient->unit
                ];
            });
        
        return [
            'recipes_count' => $recipes->count(),
            'categories' => $categories->map(function($cat) { return $cat->name; })->toArray(),
            'top_ingredients' => $topIngredients->toArray(),
            'sample_recipes' => $recipes->take(5)->map(function($recipe) {
                return [
                    'name' => $recipe->name,
                    'category' => $recipe->category ? $recipe->category->name : null,
                    'ingredients_count' => $recipe->ingredients->count(),
                    'yield' => $recipe->yield_quantity
                ];
            })->toArray()
        ];
    }
    
    /**
     * Collecte des données d'ingrédients disponibles
     */
    private function collectIngredientsData()
    {
        return Ingredient::orderBy('name')->get()->map(function($ingredient) {
            return [
                'name' => $ingredient->name,
                'unit' => $ingredient->unit
            ];
        })->toArray();
    }
    
    /**
     * Collecte des données historiques de production pour un produit
     */
    private function collectProductionData($productName)
    {
        try {
            $produitFixe = DB::table('Produit_fixes')
                ->where('nom', 'like', "%{$productName}%")
                ->first();
            
            if (!$produitFixe) {
                return ['message' => 'Aucune donnée de production trouvée pour ce produit.'];
            }
            
            $utilisations = DB::table('Utilisation')
                ->join('Produit_fixes', 'Utilisation.produit', '=', 'Produit_fixes.code_produit')
                ->join('Matiere', 'Utilisation.matierep', '=', 'Matiere.id')
                ->join('users', 'Utilisation.producteur', '=', 'users.id')
                ->where('Utilisation.produit', $produitFixe->code_produit)
                ->select(
                    'Utilisation.id_lot',
                    'Produit_fixes.nom as nom_produit',
                    'Utilisation.quantite_produit',
                    'Matiere.nom as nom_matiere',
                    'Matiere.prix_par_unite_minimale',
                    'Utilisation.quantite_matiere',
                    'Utilisation.unite_matiere',
                    'users.name as producteur',
                    'Utilisation.created_at'
                )
                ->orderByDesc('Utilisation.created_at')
                ->limit(10)
                ->get();
                
            // Regrouper par lot pour faciliter l'analyse
            $productionsByLot = [];
            foreach ($utilisations as $utilisation) {
                $idLot = $utilisation->id_lot;
                
                if (!isset($productionsByLot[$idLot])) {
                    $productionsByLot[$idLot] = [
                        'id_lot' => $idLot,
                        'produit' => $utilisation->nom_produit,
                        'quantite_produit' => $utilisation->quantite_produit,
                        'date' => $utilisation->created_at,
                        'producteur' => $utilisation->producteur,
                        'matieres' => [],
                        'cout_matieres' => 0
                    ];
                }
                
                $productionsByLot[$idLot]['matieres'][] = [
                    'nom' => $utilisation->nom_matiere,
                    'quantite' => $utilisation->quantite_matiere,
                    'unite' => $utilisation->unite_matiere,
                    'cout' => $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale
                ];
                
                $productionsByLot[$idLot]['cout_matieres'] += 
                    $utilisation->quantite_matiere * $utilisation->prix_par_unite_minimale;
            }
            
            // Calcul de statistiques
            $efficiencyByLot = array_map(function($lot) {
                $quantite = $lot['quantite_produit'];
                $cout = $lot['cout_matieres'];
                return [
                    'id_lot' => $lot['id_lot'],
                    'cout_par_unite' => $quantite > 0 ? $cout / $quantite : 0,
                    'date' => $lot['date'],
                    'producteur' => $lot['producteur']
                ];
            }, $productionsByLot);
            
            // Trouver le lot le plus efficace (coût le plus bas par unité)
            usort($efficiencyByLot, function($a, $b) {
                return $a['cout_par_unite'] <=> $b['cout_par_unite'];
            });
            
            $mostEfficientLot = !empty($efficiencyByLot) ? $efficiencyByLot[0]['id_lot'] : null;
            
            return [
                'productions' => array_values($productionsByLot),
                'efficiency' => $efficiencyByLot,
                'most_efficient_lot' => $mostEfficientLot
            ];
        } catch (\Exception $e) {
            Log::error('Error collecting production data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données de production: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Construit le prompt système pour l'analyse des recettes
     */
    private function buildRecipeSystemPrompt()
    {
        return "Tu es Sherlock Recette, un expert en boulangerie-pâtisserie spécialisé pour assister les professionnels camerounais. " .
               "Tu as une connaissance approfondie des techniques de pâtisserie, des ingrédients locaux disponibles au Cameroun et des adaptations nécessaires pour le climat tropical. " .
               "Tu fournis des conseils précis et pratiques sur l'optimisation des recettes, les quantités d'ingrédients et les méthodes de production. " .
               "Tu réponds également aux questions sur les techniques de conservation, la chaîne du froid pour les produits comme les crèmes glacées. " .
               "Tes réponses sont toujours adaptées au contexte d'une boulangerie-pâtisserie professionnelle camerounaise avec équipement standard. " .
               "Pour la farine standard c'est la farine de ble mais tu peux proposer des astuces avec d'autres type de farine (mais,,manioc)" .
               "Tu dois être précis dans tes recommandations de quantités, températures et durées.";
    }
    
    /**
     * Construit le prompt système pour la génération de recettes
     */
    private function buildRecipeGenerationPrompt()
    {
        return "Tu es Sherlock Recette, un maître pâtissier spécialiste de la création de recettes professionnelles pour boulangeries-pâtisseries camerounaises. " .
               "Tu vas créer une recette complète et détaillée adaptée aux ingrédients et conditions locales du Cameroun. " .
               "Pour chaque recette, tu dois fournir: " .
               "1. La liste complète des ingrédients avec quantités précises en grammes/litres/unités " .
               "2. Les étapes détaillées de préparation avec temps et températures " .
               "3. Des conseils spécifiques pour l'adaptation au climat tropical et aux équipements standards " .
               "4. Des indications sur la conservation et la durée de vie du produit " .
               "5. Des variantes possibles avec les ingrédients locaux. " .
               "Tes recettes doivent être professionnelles, précises et optimisées pour la production en boulangerie.";
    }
    
    /**
     * Construit le prompt système pour l'optimisation des recettes
     */
    private function buildRecipeOptimizationPrompt()
    {
        return "Tu es Sherlock Recette, un expert en analyse et optimisation de recettes pour boulangeries-pâtisseries professionnelles. " .
               "Tu analyses les recettes et proposes des optimisations basées sur: " .
               "1. La réduction des coûts des ingrédients sans compromettre la qualité " .
               "2. L'amélioration de l'efficacité de production " .
               "3. L'ajustement des proportions pour un rendement optimal " .
               "4. La réduction du gaspillage " .
               "5. L'adaptation aux conditions camerounaises (climat, disponibilité des ingrédients) " .
               "Tu dois fournir des recommandations précises et quantifiées, en te basant sur les données de production historiques pour justifier tes suggestions. " .
               "Chaque recommandation doit être accompagnée d'une explication claire de ses avantages.";
    }
}
