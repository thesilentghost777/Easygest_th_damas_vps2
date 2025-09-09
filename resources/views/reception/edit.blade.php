extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-yellow-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ $isFrench ? 'Modifier la Réception' : 'Edit Reception' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Modifiez les détails de cette réception de production' : 'Edit the details of this production reception' }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('reception.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ $isFrench ? 'Retour' : 'Back' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-red-800 mb-2">
                            {{ $isFrench ? 'Erreurs de validation' : 'Validation errors' }}
                        </h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $isFrench ? 'Informations de la Réception' : 'Reception Information' }}
                </h2>
            </div>

            <form action="{{ route('reception.update', $reception) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Informations actuelles -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $isFrench ? 'Informations Actuelles' : 'Current Information' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">{{ $isFrench ? 'Date originale:' : 'Original date:' }}</span>
                            <p class="text-gray-600">{{ $reception->date_reception->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">{{ $isFrench ? 'Produit actuel:' : 'Current product:' }}</span>
                            <p class="text-gray-600">{{ $reception->produit->nom }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">{{ $isFrench ? 'Quantité actuelle:' : 'Current quantity:' }}</span>
                            <p class="text-gray-600">{{ number_format($reception->quantite) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Date de Réception -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_reception" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date de Réception' : 'Reception Date' }} *
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200" 
                                   id="date_reception" 
                                   name="date_reception" 
                                   value="{{ old('date_reception', $reception->date_reception->format('Y-m-d')) }}" 
                                   required>
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Produit -->
                <div>
                    <label for="code_produit" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }} *
                    </label>
                    <div class="relative">
                        <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200" 
                                id="code_produit" 
                                name="code_produit" 
                                required>
                            <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->code_produit }}" 
                                        {{ old('code_produit', $reception->code_produit) == $produit->code_produit ? 'selected' : '' }}>
                                    {{ $produit->nom }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Quantité -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quantite" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Quantité' : 'Quantity' }} *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200" 
                                   id="quantite" 
                                   name="quantite" 
                                   value="{{ old('quantite', $reception->quantite) }}" 
                                   min="1" 
                                   required>
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $isFrench ? 'Entrez la nouvelle quantité reçue' : 'Enter the new quantity received' }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $isFrench ? 'Modifier la Réception' : 'Update Reception' }}
                    </button>
                    
                    <a href="{{ route('reception.index') }}" 
                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-blue-800 mb-2">
                        {{ $isFrench ? 'Informations importantes' : 'Important information' }}
                    </h3>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• {{ $isFrench ? 'La modification affectera les stocks actuels' : 'The modification will affect current stocks' }}</li>
                        <li>• {{ $isFrench ? 'Assurez-vous que les données sont correctes avant de valider' : 'Make sure the data is correct before validating' }}</li>
                        <li>• {{ $isFrench ? 'Cette action sera enregistrée dans l\'historique' : 'This action will be recorded in the history' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection