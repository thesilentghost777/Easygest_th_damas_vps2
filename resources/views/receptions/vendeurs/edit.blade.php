@extends('layouts.app')

@section('title', $isFrench ? 'Modifier Réception Vendeur' : 'Edit Vendor Reception')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl px-6 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $isFrench ? 'Modifier Réception Vendeur' : 'Edit Vendor Reception' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $isFrench ? 'Mettre à jour les quantités reçues' : 'Update received quantities' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('receptions.vendeurs.show', $reception->id) }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Voir' : 'View' }}</span>
                        </a>
                        @include('buttons')
                    </div>
                </div>
            </div>

            <!-- Current Reception Info -->
            <div class="bg-blue-50 mx-6 mt-6 rounded-xl p-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $isFrench ? 'Réception Actuelle' : 'Current Reception' }}</h3>
                        <p class="text-sm text-gray-600">
                            <strong>{{ $isFrench ? 'Vendeur:' : 'Vendor:' }}</strong> {{ $reception->vendeur->name }} | 
                            <strong>{{ $isFrench ? 'Produit:' : 'Product:' }}</strong> {{ $reception->produit->nom }} | 
                            <strong>{{ $isFrench ? 'Date:' : 'Date:' }}</strong> {{ $reception->date_reception->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-6">
                <form method="POST" action="{{ route('receptions.vendeurs.update', $reception->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="bg-blue-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="vendeur_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Vendeur' : 'Vendor' }} <span class="text-red-500">*</span>
                                </label>
                                <select name="vendeur_id" id="vendeur_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    @foreach($vendeurs as $vendeur)
                                        <option value="{{ $vendeur->id }}" {{ $reception->vendeur_id == $vendeur->id ? 'selected' : '' }}>
                                            {{ $vendeur->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendeur_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="produit_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Produit' : 'Product' }} <span class="text-red-500">*</span>
                                </label>
                                <select name="produit_id" id="produit_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->code_produit }}" {{ $reception->produit_id == $produit->code_produit ? 'selected' : '' }}>
                                            {{ $produit->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('produit_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_reception" class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Date de Réception' : 'Reception Date' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_reception" id="date_reception" required
                                       value="{{ old('date_reception', $reception->date_reception->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                @error('date_reception')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quantities Section with Before/After Comparison -->
                    <div class="bg-green-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ $isFrench ? 'Quantités - Modification' : 'Quantities - Modification' }}
                        </h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Morning Entry -->
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="bg-yellow-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    {{ $isFrench ? 'Entrée Matin' : 'Morning Entry' }}
                                </h4>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Valeur Actuelle' : 'Current Value' }}
                                        </label>
                                        <div class="text-lg font-semibold text-gray-800">
                                            {{ number_format($reception->quantite_entree_matin, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantite_entree_matin" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Nouvelle Valeur' : 'New Value' }}
                                        </label>
                                        <input type="number" name="quantite_entree_matin" id="quantite_entree_matin" step="0.01" min="0"
                                               value="{{ old('quantite_entree_matin', $reception->quantite_entree_matin) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                        @error('quantite_entree_matin')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Day Entry -->
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    {{ $isFrench ? 'Entrée Journée' : 'Day Entry' }}
                                </h4>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Valeur Actuelle' : 'Current Value' }}
                                        </label>
                                        <div class="text-lg font-semibold text-gray-800">
                                            {{ number_format($reception->quantite_entree_journee, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantite_entree_journee" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Nouvelle Valeur' : 'New Value' }}
                                        </label>
                                        <input type="number" name="quantite_entree_journee" id="quantite_entree_journee" step="0.01" min="0"
                                               value="{{ old('quantite_entree_journee', $reception->quantite_entree_journee) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                        @error('quantite_entree_journee')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Unsold -->
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="bg-orange-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    {{ $isFrench ? 'Quantité Invendue' : 'Unsold Quantity' }}
                                </h4>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Valeur Actuelle' : 'Current Value' }}
                                        </label>
                                        <div class="text-lg font-semibold text-gray-800">
                                            {{ number_format($reception->quantite_invendue, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantite_invendue" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Nouvelle Valeur' : 'New Value' }}
                                        </label>
                                        <input type="number" name="quantite_invendue" id="quantite_invendue" step="0.01" min="0"
                                               value="{{ old('quantite_invendue', $reception->quantite_invendue) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                        @error('quantite_invendue')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Yesterday's Remainder -->
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    {{ $isFrench ? 'Reste d\'Hier' : 'Yesterday\'s Remainder' }}
                                </h4>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Valeur Actuelle' : 'Current Value' }}
                                        </label>
                                        <div class="text-lg font-semibold text-gray-800">
                                            {{ number_format($reception->quantite_reste_hier, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantite_reste_hier" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Nouvelle Valeur' : 'New Value' }}
                                        </label>
                                        <input type="number" name="quantite_reste_hier" id="quantite_reste_hier" step="0.01" min="0"
                                               value="{{ old('quantite_reste_hier', $reception->quantite_reste_hier) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                        @error('quantite_reste_hier')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Spoiled Quantity -->
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm lg:col-span-2">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <div class="bg-red-100 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    {{ $isFrench ? 'Quantité Avariée' : 'Spoiled Quantity' }}
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Valeur Actuelle' : 'Current Value' }}
                                        </label>
                                        <div class="text-lg font-semibold text-gray-800">
                                            {{ number_format($reception->quantite_avarie ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="quantite_avarie" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Nouvelle Valeur' : 'New Value' }}
                                        </label>
                                        <input type="number" name="quantite_avarie" id="quantite_avarie" step="0.01" min="0"
                                               value="{{ old('quantite_avarie', $reception->quantite_avarie ?? 0) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                        @error('quantite_avarie')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('receptions.vendeurs.show', $reception->id) }}" 
                           class="px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Mettre à Jour' : 'Update' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection