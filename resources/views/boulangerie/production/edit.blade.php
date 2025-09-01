@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            {{ $isFrench ? 'Modifier la Production' : 'Edit Production' }}
        </h1>
        <p class="text-gray-600">
            {{ $isFrench ? 'Modifiez les produits fabriqués dans ce sac et suivez la performance.' : 'Modify the products manufactured in this bag and track performance.' }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('boulangerie.production.update', $production->id) }}" method="POST" id="productionForm">
            @csrf
            @method('PUT')
            
            <!-- Informations générales -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Informations générales' : 'General Information' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sac_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Type de sac' : 'Bag Type' }} <span class="text-red-500">*</span>
                        </label>
                        <select id="sac_id" 
                                name="sac_id" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sac_id') border-red-500 @enderror">
                            <option value="">
                                {{ $isFrench ? 'Sélectionner un sac' : 'Select a bag' }}
                            </option>
                            @foreach($sacs as $sac)
                                <option value="{{ $sac->id }}" 
                                        {{ old('sac_id', $production->sac_id) == $sac->id ? 'selected' : '' }}
                                        data-moyenne="{{ $sac->configuration ? $sac->configuration->valeur_moyenne_fcfa : 0 }}">
                                    {{ $sac->nom }}
                                    @if($sac->configuration)
                                        ({{ $isFrench ? 'Objectif' : 'Target' }}: {{ number_format($sac->configuration->valeur_moyenne_fcfa, 0, ',', ' ') }} FCFA)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('sac_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Indicateur de performance -->
                        <div id="performanceIndicator" class="mt-2 hidden">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $isFrench ? 'Objectif:' : 'Target:' }}</span>
                                <span id="objectifValue" class="font-medium text-gray-800">0 FCFA</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $isFrench ? 'Valeur actuelle:' : 'Current value:' }}</span>
                                <span id="valeurActuelle" class="font-medium text-blue-600">0 FCFA</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div id="progressBar" class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span id="pourcentage" class="text-sm font-medium text-gray-600">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="date_production" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date de production' : 'Production Date' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="date_production" 
                               name="date_production" 
                               value="{{ old('date_production', $production->date_production->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_production') border-red-500 @enderror">
                        @error('date_production')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Produits fabriqués -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Produits fabriqués' : 'Manufactured Products' }}
                    </h2>
                    <button type="button" 
                            id="addProduit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ $isFrench ? 'Ajouter produit' : 'Add Product' }}
                    </button>
                </div>

                <div id="produitsList" class="space-y-4">
                    <!-- Les produits existants seront chargés ici -->
                </div>
                
                @error('produits')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Observations -->
            <div class="mb-8">
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Observations' : 'Observations' }}
                </label>
                <textarea id="observations" 
                          name="observations" 
                          rows="3"
                          placeholder="{{ $isFrench ? 'Observations ou remarques particulières...' : 'Observations or special remarks...' }}"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observations') border-red-500 @enderror">{{ old('observations', $production->observations) }}</textarea>
                @error('observations')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('boulangerie.production.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 font-medium">
                    {{ $isFrench ? 'Mettre à jour la production' : 'Update Production' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitsList = document.getElementById('produitsList');
    const addProduitBtn = document.getElementById('addProduit');
    const sacSelect = document.getElementById('sac_id');
    const performanceIndicator = document.getElementById('performanceIndicator');
    const objectifValue = document.getElementById('objectifValue');
    const valeurActuelle = document.getElementById('valeurActuelle');
    const progressBar = document.getElementById('progressBar');
    const pourcentage = document.getElementById('pourcentage');
    
    let produitCount = 0;
    let valeurTotale = 0;
    let objectif = 0;

    const produits = @json($produits);
    const productionProduits = @json($production->productionProduits);
    const $isFrench = {{ $isFrench ? 'true' : 'false' }};

    // Textes bilingues pour JavaScript
    const texts = {
        selectProduct: $isFrench ? 'Sélectionner un produit' : 'Select a product',
        product: $isFrench ? 'Produit' : 'Product',
        quantity: $isFrench ? 'Quantité' : 'Quantity',
        total: $isFrench ? 'Total' : 'Total'
    };

    // Initialiser l'indicateur de performance
    const selectedOption = sacSelect.options[sacSelect.selectedIndex];
    if (selectedOption.value) {
        objectif = parseFloat(selectedOption.dataset.moyenne) || 0;
        objectifValue.textContent = formatNumber(objectif) + ' FCFA';
        performanceIndicator.classList.remove('hidden');
    }

    // Gérer le changement de sac
    sacSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            objectif = parseFloat(selectedOption.dataset.moyenne) || 0;
            objectifValue.textContent = formatNumber(objectif) + ' FCFA';
            performanceIndicator.classList.remove('hidden');
            updatePerformance();
        } else {
            performanceIndicator.classList.add('hidden');
            objectif = 0;
        }
    });

    function formatNumber(number) {
        return new Intl.NumberFormat($isFrench ? 'fr-FR' : 'en-US').format(number);
    }

    function updatePerformance() {
        valeurActuelle.textContent = formatNumber(valeurTotale) + ' FCFA';
        
        if (objectif > 0) {
            const pct = (valeurTotale / objectif) * 100;
            const pctDisplay = Math.min(pct, 100);
            
            progressBar.style.width = pctDisplay + '%';
            pourcentage.textContent = pct.toFixed(1) + '%';
            
            // Changer la couleur selon la performance
            if (pct >= 100) {
                progressBar.className = 'bg-green-500 h-2 rounded-full';
                pourcentage.className = 'text-sm font-medium text-green-600';
            } else if (pct >= 80) {
                progressBar.className = 'bg-yellow-500 h-2 rounded-full';
                pourcentage.className = 'text-sm font-medium text-yellow-600';
            } else {
                progressBar.className = 'bg-red-500 h-2 rounded-full';
                pourcentage.className = 'text-sm font-medium text-red-600';
            }
        }
    }

    function createProduitRow(existingProduit = null) {
        const index = produitCount++;
        const div = document.createElement('div');
        div.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200';
        
        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ${texts.product} <span class="text-red-500">*</span>
                    </label>
                    <select name="produits[${index}][id]" 
                            required
                            onchange="updateProduitPrix(this, ${index})"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">${texts.selectProduct}</option>
                        ${produits.map(produit => `
                            <option value="${produit.code_produit}" 
                                    data-prix="${produit.prix}"
                                    ${existingProduit && existingProduit.produit_id == produit.code_produit ? 'selected' : ''}>
                                ${produit.nom} - ${formatNumber(produit.prix)} FCFA
                            </option>
                        `).join('')}
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ${texts.quantity} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="produits[${index}][quantite]" 
                           required
                           min="1"
                           placeholder="0"
                           value="${existingProduit ? existingProduit.quantite : ''}"
                           onchange="updateTotal(${index})"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-700 mb-2">${texts.total}</div>
                        <div id="total-${index}" class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-2 rounded border">0 FCFA</div>
                    </div>
                    <button type="button" 
                            onclick="removeProduitRow(this, ${index})"
                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return div;
    }

    // Charger les produits existants
    productionProduits.forEach(produitProduction => {
        const row = createProduitRow(produitProduction);
        produitsList.appendChild(row);
        // Calculer le total initial
        setTimeout(() => updateTotal(produitCount - 1), 100);
    });

    window.updateProduitPrix = function(selectElement, index) {
        updateTotal(index);
    };

    window.updateTotal = function(index) {
        const row = document.querySelector(`[name="produits[${index}][id]"]`).closest('.bg-gray-50');
        const selectElement = row.querySelector('select');
        const quantiteInput = row.querySelector('input[type="number"]');
        const totalDiv = document.getElementById(`total-${index}`);
        
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const prix = parseFloat(selectedOption.dataset.prix) || 0;
        const quantite = parseInt(quantiteInput.value) || 0;
        const total = prix * quantite;
        
        totalDiv.textContent = formatNumber(total) + ' FCFA';
        
        // Recalculer la valeur totale
        calculateValeurTotale();
    };

    window.removeProduitRow = function(button, index) {
        button.closest('.bg-gray-50').remove();
        calculateValeurTotale();
    };

    function calculateValeurTotale() {
        valeurTotale = 0;
        const totalDivs = document.querySelectorAll('[id^="total-"]');
        
        totalDivs.forEach(div => {
            const text = div.textContent.replace(/[^\d]/g, '');
            valeurTotale += parseInt(text) || 0;
        });
        
        updatePerformance();
    }

    addProduitBtn.addEventListener('click', function() {
        const row = createProduitRow();
        produitsList.appendChild(row);
    });

    // Calculer la valeur totale initiale
    calculateValeurTotale();
});
</script>
@endsection
