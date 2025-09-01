@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')

    <!-- Mobile Header -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
            <h1 class="text-xl font-bold">{{ $recipe->name }}</h1>
            <p class="text-sm text-blue-200 mt-1">{{ $isFrench ? 'Instructions de Production' : 'Production Instructions' }}</p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $recipe->name }}</h1>
                    <p class="text-gray-600 text-lg mt-1">{{ $isFrench ? 'Instructions de Production' : 'Production Instructions' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Recipe Info Cards -->
    <div class="lg:hidden space-y-4 mb-6">
        <!-- About Recipe Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mobile-card animate-fade-in">
            <div class="bg-blue-100 p-4 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-blue-900">{{ $isFrench ? 'À propos de cette recette' : 'About this recipe' }}</h3>
            </div>
            <div class="p-4">
                @if ($recipe->description)
                    <p class="text-gray-700 mb-4">{{ $recipe->description }}</p>
                @endif
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <span class="text-blue-700 font-medium text-sm">{{ $isFrench ? 'Préparation' : 'Preparation' }}</span>
                        <div class="text-xl font-bold mt-1 text-blue-600">{{ $recipe->preparation_time ?? 0 }}</div>
                        <span class="text-xs text-blue-500">min</span>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <span class="text-green-700 font-medium text-sm">{{ $isFrench ? 'Cuisson' : 'Cooking' }}</span>
                        <div class="text-xl font-bold mt-1 text-green-600">{{ $recipe->cooking_time ?? 0 }}</div>
                        <span class="text-xs text-green-500">min</span>
                    </div>
                    
                    <div class="bg-indigo-50 rounded-lg p-3 text-center">
                        <span class="text-indigo-700 font-medium text-sm">{{ $isFrench ? 'Repos' : 'Rest' }}</span>
                        <div class="text-xl font-bold mt-1 text-indigo-600">{{ $recipe->rest_time ?? 0 }}</div>
                        <span class="text-xs text-indigo-500">min</span>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                        <span class="text-purple-700 font-medium text-sm">{{ $isFrench ? 'Total' : 'Total' }}</span>
                        <div class="text-xl font-bold mt-1 text-purple-600">{{ $recipe->total_time }}</div>
                        <span class="text-xs text-purple-500">min</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipe Details Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mobile-card animate-fade-in">
            <div class="bg-green-100 p-4 border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-green-900">{{ $isFrench ? 'Informations' : 'Information' }}</h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</span>
                    </div>
                    <span class="text-sm font-medium">{{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}</span>
                </div>
                
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Difficulté:' : 'Difficulty:' }}</span>
                    </div>
                    <span class="text-sm font-medium">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non définie' : 'Not defined') }}</span>
                </div>
                
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Quantité produite:' : 'Yield quantity:' }}</span>
                    </div>
                    <span class="text-sm font-medium">{{ $recipe->yield_quantity ?: ($isFrench ? 'Non définie' : 'Not defined') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Info Section -->
    <div class="hidden lg:block bg-white overflow-hidden shadow-xl rounded-lg mb-8 animate-fade-in">
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'À propos de cette recette' : 'About this recipe' }}</h3>
                    
                    @if ($recipe->description)
                        <p class="text-gray-700 mb-6 text-lg">{{ $recipe->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                            <span class="text-blue-700 font-medium">{{ $isFrench ? 'Préparation' : 'Preparation' }}</span>
                            <div class="text-3xl font-bold mt-2 text-blue-600">{{ $recipe->preparation_time ?? 0 }}</div>
                            <span class="text-sm text-blue-500">min</span>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                            <span class="text-green-700 font-medium">{{ $isFrench ? 'Cuisson' : 'Cooking' }}</span>
                            <div class="text-3xl font-bold mt-2 text-green-600">{{ $recipe->cooking_time ?? 0 }}</div>
                            <span class="text-sm text-green-500">min</span>
                        </div>
                        
                        <div class="bg-indigo-50 rounded-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                            <span class="text-indigo-700 font-medium">{{ $isFrench ? 'Repos' : 'Rest' }}</span>
                            <div class="text-3xl font-bold mt-2 text-indigo-600">{{ $recipe->rest_time ?? 0 }}</div>
                            <span class="text-sm text-indigo-500">min</span>
                        </div>
                        
                        <div class="bg-purple-50 rounded-lg p-6 text-center transform transition-all duration-300 hover:scale-105">
                            <span class="text-purple-700 font-medium">{{ $isFrench ? 'Total' : 'Total' }}</span>
                            <div class="text-3xl font-bold mt-2 text-purple-600">{{ $recipe->total_time }}</div>
                            <span class="text-sm text-purple-500">min</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Informations' : 'Information' }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <div>
                                <span class="text-gray-600 text-sm">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</span>
                                <div class="font-medium">{{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <div>
                                <span class="text-gray-600 text-sm">{{ $isFrench ? 'Difficulté:' : 'Difficulty:' }}</span>
                                <div class="font-medium">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non définie' : 'Not defined') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <span class="text-gray-600 text-sm">{{ $isFrench ? 'Quantité produite:' : 'Yield quantity:' }}</span>
                                <div class="font-medium">{{ $recipe->yield_quantity ?: ($isFrench ? 'Non définie' : 'Not defined') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Ingredients -->
    <div class="lg:hidden mb-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mobile-card animate-fade-in">
            <div class="bg-yellow-100 p-4 border-l-4 border-yellow-500">
                <h3 class="text-lg font-bold text-yellow-900">{{ $isFrench ? 'Ingrédients nécessaires' : 'Required ingredients' }}</h3>
            </div>
            <div class="p-4">
                @if ($recipe->ingredients->isEmpty())
                    <p class="text-gray-500 text-center py-4">{{ $isFrench ? 'Aucun ingrédient n\'a été ajouté à cette recette.' : 'No ingredients have been added to this recipe.' }}</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recipe->ingredients as $recipeIngredient)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $recipeIngredient->ingredient->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium text-blue-600">{{ $recipeIngredient->quantity }}</span> {{ $recipeIngredient->unit ?: $recipeIngredient->ingredient->unit }}
                                    </p>
                                    @if($recipeIngredient->notes)
                                    <p class="text-xs text-gray-500 mt-1">{{ $recipeIngredient->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Ingredients -->
    <div class="hidden lg:block bg-white overflow-hidden shadow-xl rounded-lg mb-8 animate-fade-in">
        <div class="p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Ingrédients nécessaires' : 'Required ingredients' }}</h3>
            
            @if ($recipe->ingredients->isEmpty())
                <p class="text-gray-500 text-center py-8 text-lg">{{ $isFrench ? 'Aucun ingrédient n\'a été ajouté à cette recette.' : 'No ingredients have been added to this recipe.' }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Ingrédient' : 'Ingredient' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Instructions spéciales' : 'Special instructions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recipe->ingredients as $recipeIngredient)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $recipeIngredient->ingredient->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="font-medium text-blue-600">{{ $recipeIngredient->quantity }}</span> {{ $recipeIngredient->unit ?: $recipeIngredient->ingredient->unit }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $recipeIngredient->notes ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Mobile Steps -->
    <div class="lg:hidden">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mobile-card animate-fade-in">
            <div class="bg-red-100 p-4 border-l-4 border-red-500">
                <h3 class="text-lg font-bold text-red-900">{{ $isFrench ? 'Étapes de préparation' : 'Preparation steps' }}</h3>
            </div>
            <div class="p-4">
                @if ($recipe->steps->isEmpty())
                    <p class="text-gray-500 text-center py-4">{{ $isFrench ? 'Aucune étape n\'a été ajoutée à cette recette.' : 'No steps have been added to this recipe.' }}</p>
                @else
                    <div class="space-y-4">
                        @foreach ($recipe->steps as $step)
                        <div class="relative border-l-4 border-blue-300 pl-6 pb-4">
                            <div class="absolute -left-3 top-0 flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold">
                                {{ $step->step_number }}
                            </div>
                            
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <p class="text-gray-800 mb-3">{{ $step->instruction }}</p>
                                
                                @if ($step->time_required || $step->tips)
                                <div class="space-y-2">
                                    @if ($step->time_required)
                                    <div class="flex items-center bg-blue-100 p-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs">
                                            <span class="text-gray-600">{{ $isFrench ? 'Temps:' : 'Time:' }}</span>
                                            <span class="font-medium text-blue-800">{{ $step->time_required }} {{ $isFrench ? 'minutes' : 'minutes' }}</span>
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if ($step->tips)
                                    <div class="flex items-start bg-green-100 p-2 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs">
                                            <span class="text-gray-600">{{ $isFrench ? 'Conseil:' : 'Tip:' }}</span>
                                            <span class="font-medium text-green-800">{{ $step->tips }}</span>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Steps -->
    <div class="hidden lg:block bg-white overflow-hidden shadow-xl rounded-lg animate-fade-in">
        <div class="p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-8">{{ $isFrench ? 'Étapes de préparation' : 'Preparation steps' }}</h3>
            
            @if ($recipe->steps->isEmpty())
                <p class="text-gray-500 text-center py-8 text-lg">{{ $isFrench ? 'Aucune étape n\'a été ajoutée à cette recette.' : 'No steps have been added to this recipe.' }}</p>
            @else
                <div class="space-y-10">
                    @foreach ($recipe->steps as $step)
                        <div class="relative pl-12 pb-8 border-l-2 border-blue-200 hover:border-blue-400 transition-colors duration-300">
                            <div class="absolute -left-6 top-0 flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg shadow-lg transform transition-all duration-300 hover:scale-110">
                                {{ $step->step_number }}
                            </div>
                            
                            <div class="ml-6">
                                <div class="p-6 bg-gradient-to-r from-blue-50 to-white rounded-lg border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300">
                                    <p class="text-gray-800 text-lg leading-relaxed">{{ $step->instruction }}</p>
                                    
                                    @if ($step->time_required || $step->tips)
                                    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        @if ($step->time_required)
                                            <div class="flex items-center bg-blue-50 p-4 rounded-lg border border-blue-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm">
                                                    <span class="text-gray-600">{{ $isFrench ? 'Temps:' : 'Time:' }}</span>
                                                    <span class="font-medium text-blue-800 ml-1">{{ $step->time_required }} {{ $isFrench ? 'minutes' : 'minutes' }}</span>
                                                </span>
                                            </div>
                                        @endif
                                        
                                        @if ($step->tips)
                                            <div class="flex items-start bg-green-50 p-4 rounded-lg border border-green-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm">
                                                    <span class="text-gray-600">{{ $isFrench ? 'Conseil:' : 'Tip:' }}</span>
                                                    <span class="font-medium text-green-800 ml-1">{{ $step->tips }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
            touch-action: manipulation;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, input, .mobile-card {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
