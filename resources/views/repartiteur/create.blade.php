@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? '⚖️ Répartition Automatique' : '⚖️ Automatic Distribution' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench 
                            ? 'Répartissez automatiquement les matières selon les proportions de valeur des produits' 
                            : 'Automatically distribute materials according to product value proportions' 
                        }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-sm font-medium text-blue-800">
                                {{ $isFrench ? 'Calcul basé sur Prix × Quantité' : 'Calculation based on Price × Quantity' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="repartitionForm" action="{{ route('repartiteur.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section Date de Production -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 rounded-t-xl">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1V8a1 1 0 00-1-1"></path>
                        </svg>
                        {{ $isFrench ? 'Date de Production' : 'Production Date' }}
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_production" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Date de production (optionnel)' : 'Production date (optional)' }}
                            </label>
                            <input type="date" 
                                   id="date_production"
                                   name="date_production" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200"
                                   value="{{ old('date_production') }}"
                                   max="{{ date('Y-m-d') }}">
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $isFrench 
                                    ? 'Laissez vide pour utiliser la date actuelle. L\'heure sera automatiquement fixée à 15:00.' 
                                    : 'Leave empty to use current date. Time will be automatically set to 3:00 PM.' 
                                }}
                            </p>
                        </div>
                        
                        
                    </div>
                    
                    
                </div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Section Matières -->
                <div class="bg-white rounded-xl shadow-lg border border-green-100">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-xl">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"></path>
                            </svg>
                            {{ $isFrench ? 'Matières Premières' : 'Raw Materials' }}
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div id="matieres-container" class="space-y-4">
                            <!-- Template pour matière -->
                            <div class="matiere-item bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Matière' : 'Material' }}
                                        </label>
                                        <select name="matieres[0][id]" class="matiere-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                            <option value="">{{ $isFrench ? 'Sélectionner...' : 'Select...' }}</option>
                                            @foreach($matieres as $matiere)
                                                <option value="{{ $matiere->id }}" 
                                                    data-unite-minimale="{{ $matiere->unite_minimale }}"
                                                    data-unite-classique="{{ $matiere->unite_classique }}"
                                                    data-prix="{{ $matiere->prix_par_unite_minimale }}">
                                                    {{ $matiere->nom }} ({{ $matiere->quantite }} {{ $matiere->unite_classique }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                        </label>
                                        <input type="number" 
                                               name="matieres[0][quantite]" 
                                               step="0.001" 
                                               min="0.001"
                                               class="quantite-matiere w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                               required>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <div class="flex-1 mr-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Unité' : 'Unit' }}
                                            </label>
                                            <select name="matieres[0][unite]" class="unite-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                                <option value="">{{ $isFrench ? 'Sélectionner...' : 'Select...' }}</option>
                                                <!-- Unités de poids (base: g) -->
                                                <option value="kg" data-base="g">kg</option>
                                                <option value="g" data-base="g">g</option>
                                                <option value="pincee" data-base="g">{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                                <!-- Unités de volume (base: ml) -->
                                                <option value="l" data-base="ml">L</option>
                                                <option value="litre" data-base="ml">{{ $isFrench ? 'Litre' : 'Liter' }}</option>
                                                <option value="dl" data-base="ml">dL</option>
                                                <option value="cl" data-base="ml">cL</option>
                                                <option value="ml" data-base="ml">mL</option>
                                                <option value="cc" data-base="ml">{{ $isFrench ? 'Cuillère à café' : 'Teaspoon' }}</option>
                                                <option value="cs" data-base="ml">{{ $isFrench ? 'Cuillère à soupe' : 'Tablespoon' }}</option>
                                                <!-- Unité -->
                                                <option value="unite" data-base="unite">{{ $isFrench ? 'Unité' : 'Unit' }}</option>
                                            </select>
                                        </div>
                                        <button type="button" class="remove-matiere bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="{{ $isFrench ? 'Supprimer' : 'Remove' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="text-sm text-green-800">
                                        <span class="font-medium">{{ $isFrench ? 'Coût estimé:' : 'Estimated cost:' }}</span>
                                        <span class="cout-matiere font-semibold">0 FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" 
                                id="add-matiere" 
                                class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $isFrench ? 'Ajouter une matière' : 'Add Material' }}
                        </button>
                        
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-blue-800">{{ $isFrench ? 'Total Matières:' : 'Total Materials:' }}</span>
                                <span id="total-matieres" class="font-bold text-blue-900 text-lg">0 FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Produits -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-100">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-xl">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $isFrench ? 'Produits à Fabriquer' : 'Products to Manufacture' }}
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div id="produits-container" class="space-y-4">
                            <!-- Template pour produit -->
                            <div class="produit-item bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Produit' : 'Product' }}
                                        </label>
                                        <select name="produits[0][id]" class="produit-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">{{ $isFrench ? 'Sélectionner...' : 'Select...' }}</option>
                                            @foreach($produits as $produit)
                                                <option value="{{ $produit->code_produit }}" data-prix="{{ $produit->prix }}">
                                                    {{ $produit->nom }} ({{ number_format($produit->prix) }} FCFA)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <div class="flex-1 mr-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                            </label>
                                            <input type="number" 
                                                   name="produits[0][quantite]" 
                                                   step="1" 
                                                   min="1"
                                                   class="quantite-produit w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   required>
                                        </div>
                                        <button type="button" class="remove-produit bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="{{ $isFrench ? 'Supprimer' : 'Remove' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="text-sm text-blue-800">
                                            <span class="font-medium">{{ $isFrench ? 'Valeur:' : 'Value:' }}</span>
                                            <span class="valeur-produit font-semibold">0 FCFA</span>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                        <div class="text-sm text-purple-800">
                                            <span class="font-medium">{{ $isFrench ? 'Proportion:' : 'Proportion:' }}</span>
                                            <span class="proportion-produit font-semibold">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" 
                                id="add-produit" 
                                class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $isFrench ? 'Ajouter un produit' : 'Add Product' }}
                        </button>
                        
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-blue-800">{{ $isFrench ? 'Valeur Totale:' : 'Total Value:' }}</span>
                                <span id="total-produits" class="font-bold text-blue-900 text-lg">0 FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu de la Répartition -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Aperçu de la Répartition' : 'Distribution Preview' }}
                </h3>
                
                <div id="repartition-preview" class="text-gray-500 text-center py-8">
                    {{ $isFrench ? 'Ajoutez des matières et des produits pour voir l\'aperçu' : 'Add materials and products to see preview' }}
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('repartiteur.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 text-center">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                
                <button type="submit" 
                        id="submit-btn"
                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $isFrench ? 'Lancer la Répartition' : 'Start Distribution' }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let matiereIndex = 0;
    let produitIndex = 0;
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    
    // Gestion de la date de production
    const dateInput = document.getElementById('date_production');
    const dateInfo = document.getElementById('date-info');
    const dateDisplay = document.getElementById('date-display');
    
    dateInput.addEventListener('change', function() {
        if (this.value) {
            const selectedDate = new Date(this.value);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const formattedDate = selectedDate.toLocaleDateString(isFrench ? 'fr-FR' : 'en-US', options);
            
            dateDisplay.textContent = formattedDate;
            dateInfo.classList.remove('hidden');
        } else {
            dateInfo.classList.add('hidden');
        }
    });
    
    // Ajouter matière
    document.getElementById('add-matiere').addEventListener('click', function() {
        matiereIndex++;
        const container = document.getElementById('matieres-container');
        const template = container.children[0].cloneNode(true);
        
        // Mettre à jour les noms et IDs
        template.querySelectorAll('select, input').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace('[0]', `[${matiereIndex}]`));
                element.value = '';
            }
        });
        
        container.appendChild(template);
        updateEventListeners();
        calculateTotals();
    });
    
    // Ajouter produit
    document.getElementById('add-produit').addEventListener('click', function() {
        produitIndex++;
        const container = document.getElementById('produits-container');
        const template = container.children[0].cloneNode(true);
        
        // Mettre à jour les noms et IDs
        template.querySelectorAll('select, input').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace('[0]', `[${produitIndex}]`));
                element.value = '';
            }
        });
        
        container.appendChild(template);
        updateEventListeners();
        calculateTotals();
    });
    
    function updateEventListeners() {
        // Supprimer matière
        document.querySelectorAll('.remove-matiere').forEach(btn => {
            btn.removeEventListener('click', removeMatiereHandler);
            btn.addEventListener('click', removeMatiereHandler);
        });
        
        // Supprimer produit
        document.querySelectorAll('.remove-produit').forEach(btn => {
            btn.removeEventListener('click', removeProduitHandler);
            btn.addEventListener('click', removeProduitHandler);
        });
        
        // Calculs et filtrage des unités
        document.querySelectorAll('.matiere-select, .quantite-matiere, .unite-select').forEach(element => {
            element.removeEventListener('change', calculateTotals);
            element.removeEventListener('input', calculateTotals);
            element.addEventListener('change', calculateTotals);
            element.addEventListener('input', calculateTotals);
        });
        
        // Filtrage des unités basé sur la matière sélectionnée
        document.querySelectorAll('.matiere-select').forEach(select => {
            select.removeEventListener('change', filterUnitsHandler);
            select.addEventListener('change', filterUnitsHandler);
        });
        
        document.querySelectorAll('.produit-select, .quantite-produit').forEach(element => {
            element.removeEventListener('change', calculateTotals);
            element.removeEventListener('input', calculateTotals);
            element.addEventListener('change', calculateTotals);
            element.addEventListener('input', calculateTotals);
        });
    }
    
    function removeMatiereHandler(e) {
        if (document.querySelectorAll('.matiere-item').length > 1) {
            e.target.closest('.matiere-item').remove();
            calculateTotals();
        }
    }
    
    function removeProduitHandler(e) {
        if (document.querySelectorAll('.produit-item').length > 1) {
            e.target.closest('.produit-item').remove();
            calculateTotals();
        }
    }
    
    function calculateTotals() {
        let totalMatieres = 0;
        let totalProduits = 0;
        
        // Calculer coût des matières
        document.querySelectorAll('.matiere-item').forEach(item => {
            const select = item.querySelector('.matiere-select');
            const quantiteInput = item.querySelector('.quantite-matiere');
            const coutSpan = item.querySelector('.cout-matiere');
            
            if (select.value && quantiteInput.value) {
                const option = select.options[select.selectedIndex];
                const prix = parseFloat(option.dataset.prix || 0);
                const quantite = parseFloat(quantiteInput.value || 0);
                const cout = prix * quantite;
                
                coutSpan.textContent = formatCurrency(cout);
                totalMatieres += cout;
            } else {
                coutSpan.textContent = '0 FCFA';
            }
        });
        
        // Calculer valeur des produits
        document.querySelectorAll('.produit-item').forEach(item => {
            const select = item.querySelector('.produit-select');
            const quantiteInput = item.querySelector('.quantite-produit');
            const valeurSpan = item.querySelector('.valeur-produit');
            
            if (select.value && quantiteInput.value) {
                const option = select.options[select.selectedIndex];
                const prix = parseFloat(option.dataset.prix || 0);
                const quantite = parseFloat(quantiteInput.value || 0);
                const valeur = prix * quantite;
                
                valeurSpan.textContent = formatCurrency(valeur);
                totalProduits += valeur;
            } else {
                valeurSpan.textContent = '0 FCFA';
            }
        });
        
        // Calculer proportions
        document.querySelectorAll('.produit-item').forEach(item => {
            const valeurSpan = item.querySelector('.valeur-produit');
            const proportionSpan = item.querySelector('.proportion-produit');
            
            const valeurText = valeurSpan.textContent.replace(/[^\d]/g, '');
            const valeur = parseFloat(valeurText || 0);
            const proportion = totalProduits > 0 ? (valeur / totalProduits) * 100 : 0;
            
            proportionSpan.textContent = proportion.toFixed(1) + '%';
        });
        
        // Mettre à jour totaux
        document.getElementById('total-matieres').textContent = formatCurrency(totalMatieres);
        document.getElementById('total-produits').textContent = formatCurrency(totalProduits);
        
        // Mettre à jour aperçu
        updatePreview();
        
        // Vérifier si le formulaire est valide
        updateSubmitButton();
    }
    
    function updatePreview() {
        const preview = document.getElementById('repartition-preview');
        const produits = [];
        let totalValeur = 0;
        
        document.querySelectorAll('.produit-item').forEach(item => {
            const select = item.querySelector('.produit-select');
            const quantiteInput = item.querySelector('.quantite-produit');
            
            if (select.value && quantiteInput.value) {
                const option = select.options[select.selectedIndex];
                const nom = option.textContent.split(' (')[0];
                const prix = parseFloat(option.dataset.prix || 0);
                const quantite = parseFloat(quantiteInput.value || 0);
                const valeur = prix * quantite;
                
                produits.push({ nom, quantite, valeur, prix });
                totalValeur += valeur;
            }
        });
        
        if (produits.length === 0) {
            preview.innerHTML = `<div class="text-gray-500 text-center py-8">${isFrench ? 'Ajoutez des matières et des produits pour voir l\'aperçu' : 'Add materials and products to see preview'}</div>`;
            return;
        }
        
        let html = '<div class="grid gap-4">';
        
        produits.forEach(produit => {
            const proportion = totalValeur > 0 ? (produit.valeur / totalValeur) * 100 : 0;
            html += `
                <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-semibold text-gray-900">${produit.nom}</h4>
                            <p class="text-sm text-gray-600">${produit.quantite} ${isFrench ? 'unités' : 'units'} × ${formatCurrency(produit.prix)} = ${formatCurrency(produit.valeur)}</p>
                        </div>
                        <div class="text-right">
                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                ${proportion.toFixed(1)}%
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: ${proportion}%"></div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        preview.innerHTML = html;
    }
    
    function updateSubmitButton() {
        const submitBtn = document.getElementById('submit-btn');
        const matieresValides = Array.from(document.querySelectorAll('.matiere-item')).some(item => {
            const select = item.querySelector('.matiere-select');
            const quantite = item.querySelector('.quantite-matiere');
            return select.value && quantite.value;
        });
        
        const produitsValides = Array.from(document.querySelectorAll('.produit-item')).some(item => {
            const select = item.querySelector('.produit-select');
            const quantite = item.querySelector('.quantite-produit');
            return select.value && quantite.value;
        });
        
        submitBtn.disabled = !(matieresValides && produitsValides);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(amount)) + ' FCFA';
    }
    
    // Fonction pour déterminer la base d'une unité
    function getBaseUnit(unite) {
        const uniteBases = {
            'kg': 'g', 'g': 'g', 'pincee': 'g',
            'l': 'ml', 'litre': 'ml', 'dl': 'ml', 'cl': 'ml', 'ml': 'ml', 'cc': 'ml', 'cs': 'ml',
            'unite': 'unite'
        };
        return uniteBases[unite.toLowerCase()] || unite;
    }
    
    // Fonction pour filtrer les unités selon la matière sélectionnée
    function filterUnitsHandler(e) {
        const matiereSelect = e.target;
        const matiereItem = matiereSelect.closest('.matiere-item');
        const uniteSelect = matiereItem.querySelector('.unite-select');
        
        if (!matiereSelect.value) {
            // Si aucune matière n'est sélectionnée, désactiver le select des unités
            uniteSelect.disabled = true;
            uniteSelect.value = '';
            return;
        }
        
        const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
        const uniteMinimale = selectedOption.dataset.uniteMinimale;
        const baseRequise = getBaseUnit(uniteMinimale);
        
        // Activer le select des unités
        uniteSelect.disabled = false;
        
        // Filtrer les options selon la base
        Array.from(uniteSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Garder l'option vide
                return;
            }
            
            const optionBase = option.dataset.base;
            if (optionBase === baseRequise) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                // Si cette option était sélectionnée, la désélectionner
                if (option.selected) {
                    uniteSelect.value = '';
                }
            }
        });
        
        // Si aucune unité compatible n'est sélectionnée, sélectionner l'unité minimale par défaut
        if (!uniteSelect.value) {
            const defaultOption = Array.from(uniteSelect.options).find(option => 
                option.value === uniteMinimale && option.style.display !== 'none'
            );
            if (defaultOption) {
                uniteSelect.value = uniteMinimale;
            }
        }
        
        calculateTotals();
    }
    
    // Initialiser
    updateEventListeners();
    calculateTotals();
    
    // Initialiser le filtrage des unités pour le premier élément
    document.querySelectorAll('.matiere-select').forEach(select => {
        if (select.value) {
            filterUnitsHandler({ target: select });
        }
    });
});
</script>
@endsection
