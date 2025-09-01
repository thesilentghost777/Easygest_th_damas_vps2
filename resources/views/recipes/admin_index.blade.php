@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $isFrench ? 'Administration des Recettes' : 'Recipe Administration' }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        {{ $isFrench ? 'Gérer vos recettes' : 'Manage your recipes' }}
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 p-2 rounded-xl">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Administration des Recettes' : 'Recipe Administration' }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Gérer et organiser toutes vos recettes de production' : 'Manage and organize all your production recipes' }}
                </p>
            </div>
        </div>

        <!-- Mobile Cards Grid -->
        <div class="md:hidden grid grid-cols-1 gap-4">
            <!-- Recipes Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Gestion des Recettes' : 'Recipe Management' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $isFrench ? 'Toutes vos recettes' : 'All your recipes' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('recipes.index') }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Voir' : 'View' }}
                        </a>
                        <a href="{{ route('recipes.create') }}" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Ajouter' : 'Add' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Catégories' : 'Categories' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $isFrench ? 'Organiser par catégories' : 'Organize by categories' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('recipe.categories.index') }}" class="flex-1 bg-green-100 text-green-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Voir' : 'View' }}
                        </a>
                        <a href="{{ route('recipe.categories.create') }}" class="flex-1 bg-green-600 text-white py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Ajouter' : 'Add' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ingredients Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $isFrench ? 'Liste des ingrédients' : 'Ingredient list' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('recipe.ingredients.index') }}" class="flex-1 bg-yellow-100 text-yellow-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Voir' : 'View' }}
                        </a>
                        <a href="{{ route('recipe.ingredients.create') }}" class="flex-1 bg-yellow-600 text-white py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                            {{ $isFrench ? 'Ajouter' : 'Add' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        {{ $isFrench ? 'Statistiques' : 'Statistics' }}
                    </h3>
                    @php
                        $totalRecipes = \App\Models\Recipe::count();
                        $totalActiveRecipes = \App\Models\Recipe::where('active', true)->count();
                        $totalCategories = \App\Models\RecipeCategory::count();
                        $totalIngredients = \App\Models\Ingredient::count();
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Recettes' : 'Recipes' }}</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $totalRecipes }}</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-green-600 font-medium">{{ $isFrench ? 'Actives' : 'Active' }}</p>
                            <p class="text-2xl font-bold text-green-700">{{ $totalActiveRecipes }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-yellow-600 font-medium">{{ $isFrench ? 'Catégories' : 'Categories' }}</p>
                            <p class="text-2xl font-bold text-yellow-700">{{ $totalCategories }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-purple-600 font-medium">{{ $isFrench ? 'Ingrédients' : 'Ingredients' }}</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $totalIngredients }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Grid -->
        <div class="hidden md:block grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Recipes Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ $isFrench ? 'Gestion des Recettes' : 'Recipe Management' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $isFrench ? 'Gérer toutes vos recettes de production' : 'Manage all your production recipes' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex space-x-3">
                            <a href="{{ route('recipes.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Voir les recettes' : 'View recipes' }}
                            </a>
                            <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Ajouter' : 'Add' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ $isFrench ? 'Catégories' : 'Categories' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $isFrench ? 'Gérer les catégories de recettes' : 'Manage recipe categories' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex space-x-3">
                            <a href="{{ route('recipe.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Voir les catégories' : 'View categories' }}
                            </a>
                            <a href="{{ route('recipe.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-green-600 text-green-600 hover:bg-green-50 rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Ajouter' : 'Add' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ingredients Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $isFrench ? 'Gérer la liste des ingrédients' : 'Manage the ingredient list' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex space-x-3">
                            <a href="{{ route('recipe.ingredients.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Voir les ingrédients' : 'View ingredients' }}
                            </a>
                            <a href="{{ route('recipe.ingredients.create') }}" class="inline-flex items-center px-4 py-2 border border-yellow-600 text-yellow-600 hover:bg-yellow-50 rounded-md transition transform hover:scale-105">
                                {{ $isFrench ? 'Ajouter' : 'Add' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Recipes Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 md:col-span-2 transform hover:scale-102 transition-all duration-300">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $isFrench ? 'Recettes Récentes' : 'Recent Recipes' }}
                    </h2>
                    @php
                        $recentRecipes = \App\Models\Recipe::with('category')->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp

                    @if($recentRecipes->isEmpty())
                        <p class="text-gray-500">
                            {{ $isFrench ? 'Aucune recette n\'a été créée récemment.' : 'No recipes have been created recently.' }}
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nom' : 'Name' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Difficulté' : 'Difficulty' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date de création' : 'Created date' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentRecipes as $recipe)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $recipe->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $recipe->difficulty_level ?: ($isFrench ? 'Non défini' : 'Not defined') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $recipe->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-900 mr-3 transform hover:scale-110 transition-all duration-200">
                                                    {{ $isFrench ? 'Voir' : 'View' }}
                                                </a>
                                                <a href="{{ route('recipes.edit', $recipe) }}" class="text-indigo-600 hover:text-indigo-900 transform hover:scale-110 transition-all duration-200">
                                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $isFrench ? 'Statistiques' : 'Statistics' }}
                    </h2>
                    @php
                        $totalRecipes = \App\Models\Recipe::count();
                        $totalActiveRecipes = \App\Models\Recipe::where('active', true)->count();
                        $totalCategories = \App\Models\RecipeCategory::count();
                        $totalIngredients = \App\Models\Ingredient::count();
                    @endphp

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center transform hover:scale-105 transition-all duration-200">
                            <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Recettes' : 'Recipes' }}</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $totalRecipes }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center transform hover:scale-105 transition-all duration-200">
                            <p class="text-sm text-green-600 font-medium">{{ $isFrench ? 'Recettes Actives' : 'Active Recipes' }}</p>
                            <p class="text-2xl font-bold text-green-700">{{ $totalActiveRecipes }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center transform hover:scale-105 transition-all duration-200">
                            <p class="text-sm text-yellow-600 font-medium">{{ $isFrench ? 'Catégories' : 'Categories' }}</p>
                            <p class="text-2xl font-bold text-yellow-700">{{ $totalCategories }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center transform hover:scale-105 transition-all duration-200">
                            <p class="text-sm text-purple-600 font-medium">{{ $isFrench ? 'Ingrédients' : 'Ingredients' }}</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $totalIngredients }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
