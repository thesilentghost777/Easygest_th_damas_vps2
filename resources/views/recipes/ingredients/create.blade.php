@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Nouvel Ingrédient' : 'New Ingredient' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ $isFrench ? 'Ajouter un nouvel ingrédient' : 'Add a new ingredient' }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Ajouter un nouvel ingrédient' : 'Add New Ingredient' }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Créer un nouvel ingrédient pour vos recettes' : 'Create a new ingredient for your recipes' }}
                </p>
            </div>
        </div>

        <!-- Mobile Form -->
        <div class="md:hidden">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right">
                <div class="px-6 py-6">
                    <form action="{{ route('recipe.ingredients.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="name_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Nom de l\'ingrédient' : 'Ingredient Name' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name_mobile" value="{{ old('name') }}" required
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="{{ $isFrench ? 'Ex: Farine, Sucre, Œufs...' : 'Ex: Flour, Sugar, Eggs...' }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="unit_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Unité par défaut' : 'Default Unit' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10l-3-3m3 3l-3-3m3 3H9"/>
                                    </svg>
                                </div>
                                <select name="unit" id="unit_mobile" class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                                    <option value="">{{ $isFrench ? '-- Aucune unité --' : '-- No unit --' }}</option>
                                    <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>{{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }}</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }}</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>{{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }}</option>
                                    <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>{{ $isFrench ? 'Litre (l)' : 'Liter (l)' }}</option>
                                    <option value="cs" {{ old('unit') == 'cs' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à soupe' : 'Tablespoon' }}</option>
                                    <option value="cc" {{ old('unit') == 'cc' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à café' : 'Teaspoon' }}</option>
                                    <option value="pièce" {{ old('unit') == 'pièce' ? 'selected' : '' }}>{{ $isFrench ? 'Pièce' : 'Piece' }}</option>
                                    <option value="pincée" {{ old('unit') == 'pincée' ? 'selected' : '' }}>{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                </select>
                            </div>
                            @error('unit')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col space-y-3 pt-6">
                            <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Ajouter l\'ingrédient' : 'Add Ingredient' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Desktop Form -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    <form action="{{ route('recipe.ingredients.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="name" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Nom de l\'ingrédient' : 'Ingredient Name' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium"
                                    placeholder="{{ $isFrench ? 'Ex: Farine de blé, Sucre blanc, Œufs frais...' : 'Ex: Wheat flour, White sugar, Fresh eggs...' }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="unit" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Unité par défaut' : 'Default Unit' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10l-3-3m3 3l-3-3m3 3H9"/>
                                    </svg>
                                </div>
                                <select name="unit" id="unit" class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium">
                                    <option value="">{{ $isFrench ? '-- Aucune unité --' : '-- No unit --' }}</option>
                                    <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>{{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }}</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }}</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>{{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }}</option>
                                    <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>{{ $isFrench ? 'Litre (l)' : 'Liter (l)' }}</option>
                                    <option value="cs" {{ old('unit') == 'cs' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à soupe' : 'Tablespoon' }}</option>
                                    <option value="cc" {{ old('unit') == 'cc' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à café' : 'Teaspoon' }}</option>
                                    <option value="pièce" {{ old('unit') == 'pièce' ? 'selected' : '' }}>{{ $isFrench ? 'Pièce' : 'Piece' }}</option>
                                    <option value="pincée" {{ old('unit') == 'pincée' ? 'selected' : '' }}>{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                </select>
                            </div>
                            @error('unit')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Ajouter l\'ingrédient' : 'Add Ingredient' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
@endsection
