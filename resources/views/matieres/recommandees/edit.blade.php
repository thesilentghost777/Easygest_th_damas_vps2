@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Modifier Matière' : 'Edit Material' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ $matiereRecommandee->matiere->nom }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Modifier une Matière Recommandée' : 'Edit Recommended Material' }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Mettre à jour les quantités recommandées' : 'Update recommended quantities' }}
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Mobile Form -->
        <div class="md:hidden">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right">
                <div class="px-6 py-6">
                    <!-- Mobile Product Info -->
                    <div class="bg-blue-50 rounded-xl p-4 mb-6 border-l-4 border-blue-500">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">
                            {{ $isFrench ? 'Informations du Produit' : 'Product Information' }}
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Produit:' : 'Product:' }}</span>
                                <span class="text-gray-900">{{ $matiereRecommandee->produit_fixes->nom }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Code:' : 'Code:' }}</span>
                                <span class="text-gray-900">{{ $matiereRecommandee->produit_fixes->code_produit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Matière:' : 'Material:' }}</span>
                                <span class="text-gray-900">{{ $matiereRecommandee->matiere->nom }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Unité minimale:' : 'Minimal unit:' }}</span>
                                <span class="text-gray-900">{{ $matiereRecommandee->matiere->unite_minimale }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('matieres.recommandees.update', $matiereRecommandee->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="quantitep_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Quantité de produit (référence)' : 'Product quantity (reference)' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <input type="number" id="quantitep_mobile" name="quantitep" min="1" 
                                       value="{{ old('quantitep', $matiereRecommandee->quantitep) }}" required
                                       class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $isFrench ? 'Quantité de produit pour laquelle la recommandation est donnée.' : 'Product quantity for which the recommendation is given.' }}
                            </p>
                            @error('quantitep')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="quantite_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Quantité de matière' : 'Material quantity' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10l-3-3m3 3l-3-3m3 3H9"/>
                                    </svg>
                                </div>
                                <input type="number" id="quantite_mobile" name="quantite" min="0.001" step="0.001" 
                                       value="{{ old('quantite', $matiereRecommandee->quantite) }}" required
                                       class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                            </div>
                            @error('quantite')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="unite_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Unité' : 'Unit' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"/>
                                    </svg>
                                </div>
                                <input type="text" id="unite_mobile" name="unite" 
                                       value="{{ old('unite', $matiereRecommandee->unite) }}" required
                                       class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                            </div>
                            @error('unite')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col space-y-3 pt-6">
                            <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
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
                    <!-- Desktop Product Info -->
                    <div class="mb-8 bg-blue-50 p-6 rounded-xl border-l-4 border-blue-500">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                            {{ $isFrench ? 'Informations du Produit' : 'Product Information' }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Produit:' : 'Product:' }}</span>
                                <span class="ml-2">{{ $matiereRecommandee->produit_fixes->nom }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Code:' : 'Code:' }}</span>
                                <span class="ml-2">{{ $matiereRecommandee->produit_fixes->code_produit }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Matière:' : 'Material:' }}</span>
                                <span class="ml-2">{{ $matiereRecommandee->matiere->nom }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">{{ $isFrench ? 'Unité minimale de la matière:' : 'Material minimal unit:' }}</span>
                                <span class="ml-2">{{ $matiereRecommandee->matiere->unite_minimale }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('matieres.recommandees.update', $matiereRecommandee->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                <label for="quantitep" class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Quantité de produit (référence)' : 'Product quantity (reference)' }}
                                </label>
                                <input type="number" id="quantitep" name="quantitep" min="1" 
                                       value="{{ old('quantitep', $matiereRecommandee->quantitep) }}" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium">
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ $isFrench ? 'Quantité de produit pour laquelle la recommandation est donnée.' : 'Product quantity for which the recommendation is given.' }}
                                </p>
                                @error('quantitep')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="bg-gray-50 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                <label for="quantite" class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Quantité de matière' : 'Material quantity' }}
                                </label>
                                <input type="number" id="quantite" name="quantite" min="0.001" step="0.001" 
                                       value="{{ old('quantite', $matiereRecommandee->quantite) }}" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium">
                                @error('quantite')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="bg-gray-50 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                <label for="unite" class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Unité' : 'Unit' }}
                                </label>
                                <input type="text" id="unite" name="unite" 
                                       value="{{ old('unite', $matiereRecommandee->unite) }}" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium">
                                @error('unite')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
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
