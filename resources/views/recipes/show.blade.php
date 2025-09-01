@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-white">{{ $recipe->name }}</h1>
                        <p class="text-blue-100 text-sm mt-1">
                            {{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}
                        </p>
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <a href="{{ route('recipes.edit', $recipe) }}" class="bg-white bg-opacity-20 p-2 rounded-xl transform hover:scale-110 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @include('buttons')
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $recipe->name }}</h2>
                    <p class="text-blue-100 mt-1">
                        {{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('recipes.edit', $recipe) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 transition duration-200 transform hover:scale-105">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                    </a>
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Mobile Content -->
        <div class="md:hidden space-y-4">
            <!-- Recipe Status Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right">
                <div class="text-center">
                    @if ($recipe->active)
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $isFrench ? 'Actif' : 'Active' }}
                        </span>
                    @else
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                            </svg>
                        </div>
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $isFrench ? 'Inactif' : 'Inactive' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- General Info Cards -->
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-white rounded-2xl shadow-lg p-5 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.1s">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Niveau de difficulté' : 'Difficulty Level' }}</h3>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-5 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.2s">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Quantité produite' : 'Yield Quantity' }}</h3>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ $recipe->yield_quantity ?: ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-5 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.3s">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Temps total' : 'Total Time' }}</h3>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ $recipe->total_time > 0 ? $recipe->total_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                </div>
            </div>

            <!-- Time Details -->
            <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right" style="animation-delay: 0.4s">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isFrench ? 'Détails des temps' : 'Time Details' }}</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Préparation' : 'Preparation' }}</span>
                        <span class="font-bold text-blue-600">{{ $recipe->preparation_time ? $recipe->preparation_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Cuisson' : 'Cooking' }}</span>
                        <span class="font-bold text-orange-600">{{ $recipe->cooking_time ? $recipe->cooking_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Repos' : 'Rest' }}</span>
                        <span class="font-bold text-purple-600">{{ $recipe->rest_time ? $recipe->rest_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</span>
                    </div>
                </div>
            </div>

            @if ($recipe->description)
                <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right" style="animation-delay: 0.5s">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $isFrench ? 'Description' : 'Description' }}</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $recipe->description }}</p>
                </div>
            @endif

            <!-- Mobile Ingredients -->
            <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right" style="animation-delay: 0.6s">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isFrench ? 'Ingrédients' : 'Ingredients' }}</h3>
                @if ($recipe->ingredients->isEmpty())
                    <div class="text-center py-8">
                        <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-gray-500">{{ $isFrench ? 'Aucun ingrédient n\'a été ajouté à cette recette.' : 'No ingredients have been added to this recipe.' }}</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($recipe->ingredients as $recipeIngredient)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl transform hover:scale-105 transition-all duration-200">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $recipeIngredient->ingredient->name }}</p>
                                    @if($recipeIngredient->notes)
                                        <p class="text-sm text-gray-600 mt-1">{{ $recipeIngredient->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600">{{ $recipeIngredient->quantity }}</p>
                                    <p class="text-sm text-gray-500">{{ $recipeIngredient->unit ?: $recipeIngredient->ingredient->unit }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Mobile Steps -->
            <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right" style="animation-delay: 0.7s">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isFrench ? 'Étapes de préparation' : 'Preparation Steps' }}</h3>
                @if ($recipe->steps->isEmpty())
                    <div class="text-center py-8">
                        <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500">{{ $isFrench ? 'Aucune étape n\'a été ajoutée à cette recette.' : 'No steps have been added to this recipe.' }}</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($recipe->steps as $step)
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-5 rounded-r-xl transform hover:scale-105 transition-all duration-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 font-bold text-sm">
                                        {{ $step->step_number }}
                                    </div>
                                    <h4 class="font-bold text-blue-700">{{ $isFrench ? 'Étape' : 'Step' }} {{ $step->step_number }}</h4>
                                </div>
                                <p class="text-gray-800 mb-3 leading-relaxed">{{ $step->instruction }}</p>
                                
                                @if ($step->tips || $step->time_required)
                                    <div class="grid grid-cols-1 gap-3">
                                        @if ($step->tips)
                                            <div class="bg-white p-3 rounded-lg">
                                                <p class="text-xs font-medium text-yellow-600 mb-1">{{ $isFrench ? 'Astuces:' : 'Tips:' }}</p>
                                                <p class="text-sm text-gray-700">{{ $step->tips }}</p>
                                            </div>
                                        @endif
                                        @if ($step->time_required)
                                            <div class="bg-white p-3 rounded-lg">
                                                <p class="text-xs font-medium text-green-600 mb-1">{{ $isFrench ? 'Temps nécessaire:' : 'Required time:' }}</p>
                                                <p class="text-sm font-medium text-gray-700">{{ $step->time_required }} {{ $isFrench ? 'minutes' : 'minutes' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Desktop Content -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    <!-- General Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            {{ $isFrench ? 'Informations générales' : 'General Information' }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Catégorie' : 'Category' }}</p>
                                    <p class="font-medium">{{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Niveau de difficulté' : 'Difficulty Level' }}</p>
                                    <p class="font-medium">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Quantité produite' : 'Yield Quantity' }}</p>
                                    <p class="font-medium">{{ $recipe->yield_quantity ?: ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Statut' : 'Status' }}</p>
                                    <p class="font-medium">
                                        @if ($recipe->active)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $isFrench ? 'Actif' : 'Active' }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $isFrench ? 'Inactif' : 'Inactive' }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Temps de préparation' : 'Preparation Time' }}</p>
                                    <p class="font-medium">{{ $recipe->preparation_time ? $recipe->preparation_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Temps de cuisson' : 'Cooking Time' }}</p>
                                    <p class="font-medium">{{ $recipe->cooking_time ? $recipe->cooking_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Temps de repos' : 'Rest Time' }}</p>
                                    <p class="font-medium">{{ $recipe->rest_time ? $recipe->rest_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-500">{{ $isFrench ? 'Temps total' : 'Total Time' }}</p>
                                    <p class="font-medium">{{ $recipe->total_time > 0 ? $recipe->total_time . ' min' : ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($recipe->description)
                            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Description' : 'Description' }}</p>
                                <p class="mt-1">{{ $recipe->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Ingredients -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                        </h3>

                        @if ($recipe->ingredients->isEmpty())
                            <div class="text-center py-12">
                                <svg class="h-20 w-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-500">{{ $isFrench ? 'Aucun ingrédient n\'a été ajouté à cette recette.' : 'No ingredients have been added to this recipe.' }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Ingrédient' : 'Ingredient' }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Notes' : 'Notes' }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($recipe->ingredients as $recipeIngredient)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $recipeIngredient->ingredient->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $recipeIngredient->quantity }} {{ $recipeIngredient->unit ?: $recipeIngredient->ingredient->unit }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $recipeIngredient->notes }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Steps -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            {{ $isFrench ? 'Étapes de préparation' : 'Preparation Steps' }}
                        </h3>

                        @if ($recipe->steps->isEmpty())
                            <div class="text-center py-12">
                                <svg class="h-20 w-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-500">{{ $isFrench ? 'Aucune étape n\'a été ajoutée à cette recette.' : 'No steps have been added to this recipe.' }}</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach ($recipe->steps as $step)
                                    <div class="p-6 border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <h4 class="font-medium text-blue-700 mb-3 flex items-center">
                                            <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3 text-sm font-bold">
                                                {{ $step->step_number }}
                                            </span>
                                            {{ $isFrench ? 'Étape' : 'Step' }} {{ $step->step_number }}
                                        </h4>

                                        <div class="mt-3 mb-4">
                                            <p class="text-gray-800 leading-relaxed">{{ $step->instruction }}</p>
                                        </div>

                                        @if ($step->tips || $step->time_required)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                                                @if ($step->tips)
                                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                                        <p class="text-sm font-medium text-yellow-700 mb-2">{{ $isFrench ? 'Astuces:' : 'Tips:' }}</p>
                                                        <p class="text-sm text-yellow-800">{{ $step->tips }}</p>
                                                    </div>
                                                @endif

                                                @if ($step->time_required)
                                                    <div class="bg-green-50 p-4 rounded-lg">
                                                        <p class="text-sm font-medium text-green-700 mb-2">{{ $isFrench ? 'Temps nécessaire:' : 'Required time:' }}</p>
                                                        <p class="text-sm font-medium text-green-800">{{ $step->time_required }} {{ $isFrench ? 'minutes' : 'minutes' }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
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