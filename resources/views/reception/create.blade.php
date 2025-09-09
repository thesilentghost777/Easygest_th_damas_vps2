@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-green-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ $isFrench ? 'Nouvel Enregistrement de Réception' : 'New Reception Record' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Enregistrez les nouveaux produits reçus en production' : 'Record new products received in production' }}
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

        <form action="{{ route('reception.store') }}" method="POST" id="receptionForm" class="space-y-8">
            @csrf
            
            <!-- Section Date -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-100">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-xl">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                        </svg>
                        {{ $isFrench ? 'Date de Réception' : 'Reception Date' }}
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_reception" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Date de Réception' : 'Reception Date' }} *
                            </label>
                            <div class="relative">
                                <input type="date" 
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       id="date_reception" 
                                       name="date_reception" 
                                       value="{{ old('date_reception', date('Y-m-d')) }}" 
                                       required>
                                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Produits -->
            <div class="bg-white rounded-xl shadow-lg border border-green-100">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h2 class="text-xl font-semibold text-white flex items-center mb-4 sm:mb-0">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $isFrench ? 'Produits Reçus' : 'Received Products' }}
                        </h2>
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-green-600 font-medium rounded-lg transition-colors duration-200" 
                                id="ajouterProduit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $isFrench ? 'Ajouter un produit' : 'Add Product' }}
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="produitsContainer" class="space-y-4">
                        <!-- Les lignes de produits seront ajoutées ici par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 rounded-t-xl">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 4h6m-6 4h6m-6 4h6"></path>
                        </svg>
                        {{ $isFrench ? 'Récapitulatif' : 'Summary' }}
                    </h3>
                </div>
                
                <div class="p-6">
                    <!-- Version Desktop -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full" id="recapitulatif">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Action' : 'Action' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="recapBody" class="bg-white divide-y divide-gray-200">
                                <tr id="noItems">
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">
                                                {{ $isFrench ? 'Aucun produit ajouté' : 'No products added' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Version Mobile -->
                    <div class="md:hidden">
                        <div id="recapMobile" class="space-y-4">
                            <div id="noItemsMobile" class="text-center py-12">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-500">
                                        {{ $isFrench ? 'Aucun produit ajouté' : 'No products added' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <button type="submit" 
                        class="order-1 sm:order-2 inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" 
                        id="submitBtn" 
                        disabled>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $isFrench ? 'Enregistrer la Réception' : 'Save Reception' }}
                </button>
                
                <a href="{{ route('reception.index') }}" 
                   class="order-2 sm:order-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let produitIndex = 0;
let produitsSelectionnes = [];
const isFrench = {{ $isFrench ? 'true' : 'false' }};

const produits = @json($produits->map(function($p) {
    return ['code_produit' => $p->code_produit, 'nom' => $p->nom];
}));

document.getElementById('ajouterProduit').addEventListener('click', function() {
    ajouterLigneProduit();
});

function ajouterLigneProduit() {
    const container = document.getElementById('produitsContainer');
    
    const div = document.createElement('div');
    div.className = 'produit-ligne bg-gray-50 border border-gray-200 rounded-lg p-4';
    div.dataset.index = produitIndex;
    
    div.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ${isFrench ? 'Produit' : 'Product'} *
                </label>
                <div class="relative">
                    <select class="produit-select w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                            name="produits[${produitIndex}][code_produit]" required>
                        <option value="">${isFrench ? 'Sélectionner un produit' : 'Select a product'}</option>
                        ${produits.map(p => `<option value="${p.code_produit}">${p.nom}</option>`).join('')}
                    </select>
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-end">
                <div class="flex-1 mr-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ${isFrench ? 'Quantité' : 'Quantity'} *
                    </label>
                    <div class="relative">
                        <input type="number" 
                               class="quantite-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                               name="produits[${produitIndex}][quantite]" 
                               placeholder="${isFrench ? 'Quantité' : 'Quantity'}" 
                               min="1" 
                               required>
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                </div>
                <button type="button" 
                        class="supprimer-produit bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg transition-colors duration-200" 
                        title="${isFrench ? 'Supprimer' : 'Remove'}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(div);
    
    // Event listeners
    const selectProduit = div.querySelector('.produit-select');
    const inputQuantite = div.querySelector('.quantite-input');
    const btnSupprimer = div.querySelector('.supprimer-produit');
    
    selectProduit.addEventListener('change', updateRecapitulatif);
    inputQuantite.addEventListener('input', updateRecapitulatif);
    
    btnSupprimer.addEventListener('click', function() {
        div.remove();
        updateRecapitulatif();
    });
    
    produitIndex++;
}

function updateRecapitulatif() {
    const lignes = document.querySelectorAll('.produit-ligne');
    const recapBody = document.getElementById('recapBody');
    const recapMobile = document.getElementById('recapMobile');
    const submitBtn = document.getElementById('submitBtn');
    const noItems = document.getElementById('noItems');
    const noItemsMobile = document.getElementById('noItemsMobile');
    
    recapBody.innerHTML = '';
    recapMobile.innerHTML = '';
    produitsSelectionnes = [];
    
    let hasValidItems = false;
    
    lignes.forEach(ligne => {
        const select = ligne.querySelector('.produit-select');
        const input = ligne.querySelector('.quantite-input');
        
        if (select.value && input.value && input.value > 0) {
            hasValidItems = true;
            const produitNom = produits.find(p => p.code_produit == select.value)?.nom || '';
            
            // Version desktop
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${produitNom}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        ${parseInt(input.value).toLocaleString()}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button type="button" 
                            class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200" 
                            onclick="supprimerLigne(${ligne.dataset.index})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </td>
            `;
            recapBody.appendChild(tr);
            
            // Version mobile
            const mobileDiv = document.createElement('div');
            mobileDiv.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
            mobileDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-medium text-gray-900">${produitNom}</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                            ${parseInt(input.value).toLocaleString()}
                        </span>
                    </div>
                    <button type="button" 
                            class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200" 
                            onclick="supprimerLigne(${ligne.dataset.index})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            recapMobile.appendChild(mobileDiv);
            
            produitsSelectionnes.push({
                code_produit: select.value,
                nom: produitNom,
                quantite: input.value
            });
        }
    });
    
    if (!hasValidItems) {
        recapBody.appendChild(noItems);
        recapMobile.appendChild(noItemsMobile);
    }
    
    submitBtn.disabled = !hasValidItems;
}

function supprimerLigne(index) {
    const ligne = document.querySelector(`[data-index="${index}"]`);
    if (ligne) {
        ligne.remove();
        updateRecapitulatif();
    }
}

// Ajouter une première ligne au chargement
document.addEventListener('DOMContentLoaded', function() {
    ajouterLigneProduit();
});
</script>
@endsection