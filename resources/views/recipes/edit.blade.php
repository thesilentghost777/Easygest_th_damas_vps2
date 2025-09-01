@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Modifier la Recette' : 'Edit Recipe' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ $recipe->name }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Modifier la Recette' : 'Edit Recipe' }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Mettre à jour' : 'Update' }} "{{ $recipe->name }}"
                </p>
            </div>
        </div>

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" id="recipeForm">
            @csrf
            @method('PUT')

            <!-- Mobile Form Layout -->
            <div class="md:hidden space-y-6">
                <!-- General Information - Mobile -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right">
                    <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                        <h3 class="text-lg font-bold text-blue-800">
                            {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="name_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Nom de la recette' : 'Recipe Name' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name_mobile" value="{{ old('name', $recipe->name) }}" required
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="{{ $isFrench ? 'Ex: Pain au chocolat...' : 'Ex: Chocolate croissant...' }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="category_id_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Catégorie' : 'Category' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <select name="category_id" id="category_id_mobile" 
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                                    <option value="">{{ $isFrench ? '-- Sélectionner une catégorie --' : '-- Select a category --' }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $recipe->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="description_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Description' : 'Description' }}
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <textarea name="description" id="description_mobile" rows="4"
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 text-lg transform hover:scale-102 transition-all duration-200"
                                    placeholder="{{ $isFrench ? 'Description de la recette...' : 'Recipe description...' }}">{{ old('description', $recipe->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div class="transform hover:scale-105 transition-all duration-300">
                                <label for="preparation_time_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Préparation (min)' : 'Prep time (min)' }}
                                </label>
                                <input type="number" name="preparation_time" id="preparation_time_mobile" min="0" value="{{ old('preparation_time', $recipe->preparation_time) }}"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12 text-center font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="0">
                            </div>
                            <div class="transform hover:scale-105 transition-all duration-300">
                                <label for="cooking_time_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Cuisson (min)' : 'Cook time (min)' }}
                                </label>
                                <input type="number" name="cooking_time" id="cooking_time_mobile" min="0" value="{{ old('cooking_time', $recipe->cooking_time) }}"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12 text-center font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="0">
                            </div>
                            <div class="transform hover:scale-105 transition-all duration-300">
                                <label for="rest_time_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Repos (min)' : 'Rest time (min)' }}
                                </label>
                                <input type="number" name="rest_time" id="rest_time_mobile" min="0" value="{{ old('rest_time', $recipe->rest_time) }}"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12 text-center font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="transform hover:scale-105 transition-all duration-300">
                                <label for="yield_quantity_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Quantité produite' : 'Yield quantity' }}
                                </label>
                                <input type="number" name="yield_quantity" id="yield_quantity_mobile" min="1" value="{{ old('yield_quantity', $recipe->yield_quantity) }}"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12 text-center font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="1">
                            </div>
                            <div class="transform hover:scale-105 transition-all duration-300">
                                <label for="difficulty_level_mobile" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Difficulté' : 'Difficulty' }}
                                </label>
                                <select name="difficulty_level" id="difficulty_level_mobile"
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12 font-medium transform hover:scale-102 transition-all duration-200">
                                    <option value="">{{ $isFrench ? '-- Niveau --' : '-- Level --' }}</option>
                                    <option value="Facile" {{ old('difficulty_level', $recipe->difficulty_level) == 'Facile' ? 'selected' : '' }}>{{ $isFrench ? 'Facile' : 'Easy' }}</option>
                                    <option value="Moyen" {{ old('difficulty_level', $recipe->difficulty_level) == 'Moyen' ? 'selected' : '' }}>{{ $isFrench ? 'Moyen' : 'Medium' }}</option>
                                    <option value="Difficile" {{ old('difficulty_level', $recipe->difficulty_level) == 'Difficile' ? 'selected' : '' }}>{{ $isFrench ? 'Difficile' : 'Hard' }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label class="inline-flex items-center bg-blue-50 p-4 rounded-xl">
                                <input type="checkbox" name="active" value="1" {{ old('active', $recipe->active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 h-5 w-5">
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    {{ $isFrench ? 'Recette active (visible pour les employés)' : 'Active recipe (visible to employees)' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Ingredients - Mobile -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right" style="animation-delay: 0.1s">
                    <div class="px-6 py-4 bg-green-50 border-b border-green-100">
                        <h3 class="text-lg font-bold text-green-800">
                            {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="ingredients-container-mobile">
                            @foreach($recipe->ingredients as $index => $recipeIngredient)
                                <div class="ingredients-entry-mobile mb-4 p-4 bg-gray-50 rounded-xl border-l-4 border-green-500">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                {{ $isFrench ? 'Ingrédient' : 'Ingredient' }} *
                                            </label>
                                            <select name="ingredients[{{ $index }}][id]" required
                                                class="w-full border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 bg-white h-12 text-sm font-medium">
                                                <option value="">{{ $isFrench ? '-- Sélectionner un ingrédient --' : '-- Select an ingredient --' }}</option>
                                                @foreach($ingredients as $ingredient)
                                                    <option value="{{ $ingredient->id }}" {{ $recipeIngredient->ingredient_id == $ingredient->id ? 'selected' : '' }}>
                                                        {{ $ingredient->name }} {{ $ingredient->unit ? '('.$ingredient->unit.')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    {{ $isFrench ? 'Quantité' : 'Quantity' }} *
                                                </label>
                                                <input type="number" name="ingredients[{{ $index }}][quantity]" required min="0" step="0.01" value="{{ $recipeIngredient->quantity }}"
                                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 bg-white h-12 text-center font-medium">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    {{ $isFrench ? 'Unité' : 'Unit' }}
                                                </label>
                                                <input type="text" name="ingredients[{{ $index }}][unit]" placeholder="g, kg, ml..." value="{{ $recipeIngredient->unit }}"
                                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 bg-white h-12 text-center font-medium">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                {{ $isFrench ? 'Notes' : 'Notes' }}
                                            </label>
                                            <input type="text" name="ingredients[{{ $index }}][notes]" value="{{ $recipeIngredient->notes }}"
                                                class="w-full border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 bg-white h-12 font-medium">
                                        </div>
                                        
                                        <button type="button" class="remove-ingredient-mobile w-full text-red-600 py-2 text-sm font-medium" {{ $index == 0 ? 'style=visibility:hidden' : '' }}>
                                            {{ $isFrench ? 'Supprimer cet ingrédient' : 'Remove this ingredient' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" id="add-ingredient-mobile" class="w-full bg-green-100 text-green-700 py-4 px-6 rounded-xl font-bold transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $isFrench ? 'Ajouter un ingrédient' : 'Add ingredient' }}
                        </button>
                    </div>
                </div>

                <!-- Steps - Mobile -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right" style="animation-delay: 0.2s">
                    <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                        <h3 class="text-lg font-bold text-yellow-800">
                            {{ $isFrench ? 'Étapes de préparation' : 'Preparation steps' }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="steps-container-mobile">
                            @foreach($recipe->steps as $index => $step)
                                <div class="steps-entry-mobile mb-4 p-4 bg-gray-50 rounded-xl border-l-4 border-yellow-500">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-bold text-gray-900">{{ $isFrench ? 'Étape' : 'Step' }} {{ $step->step_number }}</h4>
                                        <button type="button" class="remove-step-mobile text-red-600 text-sm font-medium" {{ $index == 0 ? 'style=visibility:hidden' : '' }}>
                                            {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                {{ $isFrench ? 'Instructions' : 'Instructions' }} *
                                            </label>
                                            <textarea name="steps[{{ $index }}][instruction]" required rows="3"
                                                class="w-full border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-0 bg-white font-medium"
                                                placeholder="{{ $isFrench ? 'Décrivez cette étape...' : 'Describe this step...' }}">{{ $step->instruction }}</textarea>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    {{ $isFrench ? 'Conseils ou astuces' : 'Tips or tricks' }}
                                                </label>
                                                <input type="text" name="steps[{{ $index }}][tips]" value="{{ $step->tips }}"
                                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-0 bg-white h-12 font-medium"
                                                    placeholder="{{ $isFrench ? 'Conseil utile...' : 'Useful tip...' }}">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    {{ $isFrench ? 'Temps nécessaire (min)' : 'Time required (min)' }}
                                                </label>
                                                <input type="number" name="steps[{{ $index }}][time_required]" min="0" value="{{ $step->time_required }}"
                                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-yellow-500 focus:ring-0 bg-white h-12 text-center font-medium"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" id="add-step-mobile" class="w-full bg-yellow-100 text-yellow-700 py-4 px-6 rounded-xl font-bold transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $isFrench ? 'Ajouter une étape' : 'Add step' }}
                        </button>
                    </div>
                </div>

                <!-- Submit Button - Mobile -->
                <div class="bg-white rounded-2xl shadow-lg p-6 animate-slide-in-right" style="animation-delay: 0.3s">
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="h-6 w-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $isFrench ? 'Mettre à jour la recette' : 'Update recipe' }}
                    </button>
                </div>
            </div>

            <!-- Desktop Form Layout -->
            <div class="hidden md:block">
                <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <!-- General Information - Desktop -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                                        {{ $isFrench ? 'Informations générales' : 'General Information' }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Nom de la recette' : 'Recipe Name' }} *
                                            </label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $recipe->name) }}" required
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                            @error('name')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Catégorie' : 'Category' }}
                                            </label>
                                            <select name="category_id" id="category_id"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                                <option value="">{{ $isFrench ? '-- Sélectionner une catégorie --' : '-- Select a category --' }}</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (old('category_id', $recipe->category_id) == $category->id) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Description' : 'Description' }}
                                        </label>
                                        <textarea name="description" id="description" rows="3"
                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">{{ old('description', $recipe->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div>
                                            <label for="preparation_time" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Temps de préparation (min)' : 'Preparation time (min)' }}
                                            </label>
                                            <input type="number" name="preparation_time" id="preparation_time" min="0" value="{{ old('preparation_time', $recipe->preparation_time) }}"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                            @error('preparation_time')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Temps de cuisson (min)' : 'Cooking time (min)' }}
                                            </label>
                                            <input type="number" name="cooking_time" id="cooking_time" min="0" value="{{ old('cooking_time', $recipe->cooking_time) }}"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                            @error('cooking_time')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="rest_time" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Temps de repos (min)' : 'Rest time (min)' }}
                                            </label>
                                            <input type="number" name="rest_time" id="rest_time" min="0" value="{{ old('rest_time', $recipe->rest_time) }}"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                            @error('rest_time')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label for="yield_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Quantité produite' : 'Yield quantity' }}
                                            </label>
                                            <input type="number" name="yield_quantity" id="yield_quantity" min="1" value="{{ old('yield_quantity', $recipe->yield_quantity) }}"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                            @error('yield_quantity')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Niveau de difficulté' : 'Difficulty level' }}
                                            </label>
                                            <select name="difficulty_level" id="difficulty_level"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base p-3">
                                                <option value="">{{ $isFrench ? '-- Sélectionner un niveau --' : '-- Select a level --' }}</option>
                                                <option value="Facile" {{ old('difficulty_level', $recipe->difficulty_level) == 'Facile' ? 'selected' : '' }}>{{ $isFrench ? 'Facile' : 'Easy' }}</option>
                                                <option value="Moyen" {{ old('difficulty_level', $recipe->difficulty_level) == 'Moyen' ? 'selected' : '' }}>{{ $isFrench ? 'Moyen' : 'Medium' }}</option>
                                                <option value="Difficile" {{ old('difficulty_level', $recipe->difficulty_level) == 'Difficile' ? 'selected' : '' }}>{{ $isFrench ? 'Difficile' : 'Hard' }}</option>
                                            </select>
                                            @error('difficulty_level')
                                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="active" value="1" {{ old('active', $recipe->active) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $isFrench ? 'Recette active (visible pour les employés)' : 'Active recipe (visible to employees)' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Ingredients - Desktop -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                                        {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                                    </h3>

                                    <div id="ingredients-container">
                                        @foreach($recipe->ingredients as $index => $recipeIngredient)
                                            <div class="ingredients-entry mb-4 p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $isFrench ? 'Ingrédient' : 'Ingredient' }} *
                                                        </label>
                                                        <select name="ingredients[{{ $index }}][id]" required
                                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                            <option value="">{{ $isFrench ? '-- Sélectionner un ingrédient --' : '-- Select an ingredient --' }}</option>
                                                            @foreach($ingredients as $ingredient)
                                                                <option value="{{ $ingredient->id }}" {{ $recipeIngredient->ingredient_id == $ingredient->id ? 'selected' : '' }}>
                                                                    {{ $ingredient->name }} {{ $ingredient->unit ? '('.$ingredient->unit.')' : '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $isFrench ? 'Quantité' : 'Quantity' }} *
                                                        </label>
                                                        <input type="number" name="ingredients[{{ $index }}][quantity]" required min="0" step="0.01" value="{{ $recipeIngredient->quantity }}"
                                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $isFrench ? 'Unité' : 'Unit' }}
                                                        </label>
                                                        <input type="text" name="ingredients[{{ $index }}][unit]" placeholder="g, kg, ml..." value="{{ $recipeIngredient->unit }}"
                                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                    </div>

                                                    <div class="flex items-end">
                                                        <button type="button" class="remove-ingredient text-red-600 px-2 py-1 text-sm transform hover:scale-110 transition-all duration-200" {{ $index == 0 ? 'style=visibility:hidden' : '' }}>
                                                            {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        {{ $isFrench ? 'Notes' : 'Notes' }}
                                                    </label>
                                                    <input type="text" name="ingredients[{{ $index }}][notes]" value="{{ $recipeIngredient->notes }}"
                                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" id="add-ingredient" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ $isFrench ? 'Ajouter un ingrédient' : 'Add ingredient' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Steps - Desktop -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                                        {{ $isFrench ? 'Étapes de préparation' : 'Preparation steps' }}
                                    </h3>

                                    <div id="steps-container">
                                        @foreach($recipe->steps as $index => $step)
                                            <div class="steps-entry mb-4 p-4 border border-gray-200 rounded-lg bg-white">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h4 class="font-medium text-gray-900">{{ $isFrench ? 'Étape' : 'Step' }} {{ $step->step_number }}</h4>
                                                    <button type="button" class="remove-step text-red-600 px-2 py-1 text-sm transform hover:scale-110 transition-all duration-200" {{ $index == 0 ? 'style=visibility:hidden' : '' }}>
                                                        {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                                    </button>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        {{ $isFrench ? 'Instructions' : 'Instructions' }} *
                                                    </label>
                                                    <textarea name="steps[{{ $index }}][instruction]" required rows="3"
                                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">{{ $step->instruction }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $isFrench ? 'Conseils ou astuces' : 'Tips or tricks' }}
                                                        </label>
                                                        <input type="text" name="steps[{{ $index }}][tips]" value="{{ $step->tips }}"
                                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $isFrench ? 'Temps nécessaire (min)' : 'Time required (min)' }}
                                                        </label>
                                                        <input type="number" name="steps[{{ $index }}][time_required]" min="0" value="{{ $step->time_required }}"
                                                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" id="add-step" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ $isFrench ? 'Ajouter une étape' : 'Add step' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-8">
                            <button type="submit" class="inline-flex items-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Mettre à jour la recette' : 'Update recipe' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile ingredient management
    const ingredientsContainerMobile = document.getElementById('ingredients-container-mobile');
    const addIngredientButtonMobile = document.getElementById('add-ingredient-mobile');
    let ingredientCounterMobile = {{ count($recipe->ingredients) - 1 }};

    document.querySelectorAll('.remove-ingredient-mobile').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.ingredients-entry-mobile').remove();
        });
    });

    if (addIngredientButtonMobile) {
        addIngredientButtonMobile.addEventListener('click', function() {
            ingredientCounterMobile++;
            const newIngredient = document.querySelector('.ingredients-entry-mobile').cloneNode(true);

            newIngredient.querySelectorAll('select, input, textarea').forEach(function(element) {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, '[' + ingredientCounterMobile + ']'));
                    if (element.tagName === 'SELECT') {
                        element.selectedIndex = 0;
                    } else if (element.tagName === 'INPUT') {
                        if (element.type === 'number') {
                            element.value = element.min || '0';
                        } else {
                            element.value = '';
                        }
                    } else if (element.tagName === 'TEXTAREA') {
                        element.value = '';
                    }
                }
            });

            const removeButton = newIngredient.querySelector('.remove-ingredient-mobile');
            removeButton.style.visibility = 'visible';
            removeButton.addEventListener('click', function() {
                newIngredient.remove();
            });

            ingredientsContainerMobile.appendChild(newIngredient);
        });
    }

    // Mobile step management
    const stepsContainerMobile = document.getElementById('steps-container-mobile');
    const addStepButtonMobile = document.getElementById('add-step-mobile');
    let stepCounterMobile = {{ count($recipe->steps) - 1 }};

    document.querySelectorAll('.remove-step-mobile').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.steps-entry-mobile').remove();
            document.querySelectorAll('.steps-entry-mobile').forEach(function(step, index) {
                step.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (index + 1);
            });
        });
    });

    if (addStepButtonMobile) {
        addStepButtonMobile.addEventListener('click', function() {
            stepCounterMobile++;
            const newStep = document.querySelector('.steps-entry-mobile').cloneNode(true);

            newStep.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (stepCounterMobile + 1);

            newStep.querySelectorAll('input, textarea').forEach(function(element) {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, '[' + stepCounterMobile + ']'));
                    element.value = '';
                }
            });

            const removeButton = newStep.querySelector('.remove-step-mobile');
            removeButton.style.visibility = 'visible';
            removeButton.addEventListener('click', function() {
                newStep.remove();
                document.querySelectorAll('.steps-entry-mobile').forEach(function(step, index) {
                    step.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (index + 1);
                });
            });

            stepsContainerMobile.appendChild(newStep);
        });
    }

    // Desktop ingredient management
    const ingredientsContainer = document.getElementById('ingredients-container');
    const addIngredientButton = document.getElementById('add-ingredient');
    let ingredientCounter = {{ count($recipe->ingredients) - 1 }};

    document.querySelectorAll('.remove-ingredient').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.ingredients-entry').remove();
        });
    });

    if (addIngredientButton) {
        addIngredientButton.addEventListener('click', function() {
            ingredientCounter++;
            const newIngredient = document.querySelector('.ingredients-entry').cloneNode(true);

            newIngredient.querySelectorAll('select, input, textarea').forEach(function(element) {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, '[' + ingredientCounter + ']'));
                    if (element.tagName === 'SELECT') {
                        element.selectedIndex = 0;
                    } else if (element.tagName === 'INPUT') {
                        if (element.type === 'number') {
                            element.value = element.min || '0';
                        } else {
                            element.value = '';
                        }
                    } else if (element.tagName === 'TEXTAREA') {
                        element.value = '';
                    }
                }
            });

            const removeButton = newIngredient.querySelector('.remove-ingredient');
            removeButton.style.visibility = 'visible';
            removeButton.addEventListener('click', function() {
                newIngredient.remove();
            });

            ingredientsContainer.appendChild(newIngredient);
        });
    }

    // Desktop step management
    const stepsContainer = document.getElementById('steps-container');
    const addStepButton = document.getElementById('add-step');
    let stepCounter = {{ count($recipe->steps) - 1 }};

    document.querySelectorAll('.remove-step').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.steps-entry').remove();
            document.querySelectorAll('.steps-entry').forEach(function(step, index) {
                step.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (index + 1);
            });
        });
    });

    if (addStepButton) {
        addStepButton.addEventListener('click', function() {
            stepCounter++;
            const newStep = document.querySelector('.steps-entry').cloneNode(true);

            newStep.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (stepCounter + 1);

            newStep.querySelectorAll('input, textarea').forEach(function(element) {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, '[' + stepCounter + ']'));
                    element.value = '';
                }
            });

            const removeButton = newStep.querySelector('.remove-step');
            removeButton.style.visibility = 'visible';
            removeButton.addEventListener('click', function() {
                newStep.remove();
                document.querySelectorAll('.steps-entry').forEach(function(step, index) {
                    step.querySelector('h4').textContent = '{{ $isFrench ? "Étape" : "Step" }} ' + (index + 1);
                });
            });

            stepsContainer.appendChild(newStep);
        });
    }
});
</script>
@endsection
