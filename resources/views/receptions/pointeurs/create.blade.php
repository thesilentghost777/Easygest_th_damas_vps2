{{-- resources/views/receptions/pointeurs/create.blade.php --}}
@extends('layouts.app')

@section('title', $isFrench ? 'Nouvelle Réception Pointeur' : 'New Pointer Reception')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-green-50 py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            @include('buttons')
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ $isFrench ? 'Nouvelle Réception Pointeur' : 'New Pointer Reception' }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ $isFrench ? 'Enregistrer une nouvelle réception de produits' : 'Record a new product reception' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('receptions.pointeurs.store') }}" method="POST" id="receptionForm" class="space-y-8">
            @csrf
            
            <!-- General Information Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Pointeur Selection -->
                        <div class="group">
                            <label for="pointeur_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <select name="pointeur_id" id="pointeur_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('pointeur_id') border-red-500 ring-2 ring-red-200 @enderror" 
                                        required>
                                    <option value="">{{ $isFrench ? 'Sélectionner un pointeur' : 'Select a pointer' }}</option>
                                    @foreach($pointeurs as $pointeur)
                                        <option value="{{ $pointeur->id }}" {{ old('pointeur_id') == $pointeur->id ? 'selected' : '' }}>
                                            {{ $pointeur->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('pointeur_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Reception Date -->
                        <div class="group">
                            <label for="date_reception" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Date de réception' : 'Reception Date' }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="date_reception" id="date_reception" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('date_reception') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('date_reception', date('Y-m-d')) }}" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                            @error('date_reception')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-boxes mr-3"></i>
                        {{ $isFrench ? 'Produits Reçus' : 'Received Products' }}
                    </h2>
                </div>
                
                <div class="p-6">
                    <div id="produits-container" class="space-y-4">
                        <!-- First Product Row -->
                        <div class="produit-row bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 transition-all duration-300 hover:shadow-md" data-index="0">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <!-- Product Selection with Search -->
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative product-search-container">
                                        <!-- Search Input -->
                                        <div class="relative">
                                            <input type="text" 
                                                   class="product-search-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                                   placeholder="{{ $isFrench ? 'Rechercher un produit...' : 'Search for a product...' }}"
                                                   style="font-size: 16px;">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden Select -->
                                        <select name="produits[0][produit_id]" class="produit-select hidden" required>
                                            <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                                            @foreach($produits as $produit)
                                                <option value="{{ $produit->code_produit }}" 
                                                        data-nom="{{ $produit->nom }}"
                                                        data-prix="{{ $produit->prix }}"
                                                        data-categorie="{{ $produit->categorie }}">
                                                    {{ $produit->nom }} ({{ $produit->prix }})
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Search Results Dropdown -->
                                        <div class="product-dropdown absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                            <div class="p-2">
                                                <div class="search-loading hidden flex items-center justify-center py-4">
                                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500"></div>
                                                    <span class="ml-2 text-gray-600">{{ $isFrench ? 'Recherche...' : 'Searching...' }}</span>
                                                </div>
                                                <div class="search-results"></div>
                                                <div class="no-results hidden text-center py-4 text-gray-500">
                                                    <i class="fas fa-search-minus mb-2"></i>
                                                    <div>{{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('produits.0.produit_id')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Quantity -->
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ $isFrench ? 'Quantité reçue' : 'Received Quantity' }}
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="produits[0][quantite_recue]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 @error('produits.0.quantite_recue') border-red-500 ring-2 ring-red-200 @enderror"
                                               step="0.01" min="0" placeholder="0.00" required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-hashtag text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('produits.0.quantite_recue')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Remove Button -->
                                <div class="md:col-span-2">
                                    <button type="button" class="remove-produit w-full px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        <i class="fas fa-trash"></i>
                                        <span class="hidden sm:inline ml-2">{{ $isFrench ? 'Supprimer' : 'Remove' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Product Button -->
                    <div class="mt-6">
                        <button type="button" id="add-produit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg shadow-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i>
                            {{ $isFrench ? 'Ajouter un produit' : 'Add Product' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div id="recapitulatif" class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        {{ $isFrench ? 'Récapitulatif' : 'Summary' }}
                    </h2>
                </div>
                <div class="p-6">
                    <div id="recap-content"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row gap-4 sm:justify-end">
                    <a href="{{ route('receptions.pointeurs.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let produitIndex = 1;
    const produitsContainer = document.getElementById('produits-container');
    const addProduitBtn = document.getElementById('add-produit');
    const recapDiv = document.getElementById('recapitulatif');
    const recapContent = document.getElementById('recap-content');
    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    // Products data for search
    const allProducts = [
        @foreach($produits as $produit)
        {
            id: '{{ $produit->code_produit }}',
            nom: '{{ addslashes($produit->nom) }}',
            prix: '{{ $produit->prix }}',
            categorie: '{{ addslashes($produit->categorie) }}',
            searchText: '{{ addslashes(strtolower($produit->nom . ' ' . $produit->categorie)) }}'
        },
        @endforeach
    ];

    // Product template
    function getProduitTemplate(index) {
        return `
            <div class="produit-row bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 transition-all duration-300 hover:shadow-md animate-slideIn" data-index="${index}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ${isFrench ? 'Produit' : 'Product'}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative product-search-container">
                            <div class="relative">
                                <input type="text" 
                                       class="product-search-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                       placeholder="${isFrench ? 'Rechercher un produit...' : 'Search for a product...'}"
                                       style="font-size: 16px;">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            
                            <select name="produits[${index}][produit_id]" class="produit-select hidden" required>
                                <option value="">${isFrench ? 'Sélectionner un produit' : 'Select a product'}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}" 
                                            data-nom="{{ $produit->nom }}"
                                            data-prix="{{ $produit->prix }}"
                                            data-categorie="{{ $produit->categorie }}">
                                        {{ $produit->nom }} ({{ $produit->prix }})
                                    </option>
                                @endforeach
                            </select>

                            <div class="product-dropdown absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                                <div class="p-2">
                                    <div class="search-loading hidden flex items-center justify-center py-4">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500"></div>
                                        <span class="ml-2 text-gray-600">${isFrench ? 'Recherche...' : 'Searching...'}</span>
                                    </div>
                                    <div class="search-results"></div>
                                    <div class="no-results hidden text-center py-4 text-gray-500">
                                        <i class="fas fa-search-minus mb-2"></i>
                                        <div>${isFrench ? 'Aucun produit trouvé' : 'No products found'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ${isFrench ? 'Quantité reçue' : 'Received Quantity'}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="produits[${index}][quantite_recue]" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                                   step="0.01" min="0" placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" class="remove-produit w-full px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-all duration-200">
                            <i class="fas fa-trash"></i>
                            <span class="hidden sm:inline ml-2">${isFrench ? 'Supprimer' : 'Remove'}</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Initialize product search for a container
    function initializeProductSearch(container) {
        const searchInput = container.querySelector('.product-search-input');
        const dropdown = container.querySelector('.product-dropdown');
        const resultsContainer = container.querySelector('.search-results');
        const loadingIndicator = container.querySelector('.search-loading');
        const noResults = container.querySelector('.no-results');
        const hiddenSelect = container.querySelector('.produit-select');
        
        let searchTimeout;
        let selectedIndex = -1;
        let currentResults = [];

        // Search function with debouncing
        function performSearch(query) {
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            // Show loading
            loadingIndicator.classList.remove('hidden');
            noResults.classList.add('hidden');
            dropdown.classList.remove('hidden');

            searchTimeout = setTimeout(() => {
                const searchQuery = query.toLowerCase().trim();
                const results = allProducts.filter(product => 
                    product.searchText.includes(searchQuery)
                ).slice(0, 10); // Limit to 10 results

                currentResults = results;
                displayResults(results);
                loadingIndicator.classList.add('hidden');
            }, 300);
        }

        // Display search results
        function displayResults(results) {
            if (results.length === 0) {
                noResults.classList.remove('hidden');
                resultsContainer.innerHTML = '';
                return;
            }

            noResults.classList.add('hidden');
            resultsContainer.innerHTML = results.map((product, index) => `
                <div class="product-result p-3 cursor-pointer hover:bg-gray-50 rounded-lg border-l-4 border-transparent hover:border-green-500 transition-all duration-200" 
                     data-index="${index}" 
                     data-product-id="${product.id}">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900 text-sm">${product.nom}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                    ${product.categorie}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-green-600">${product.prix}</div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Handle keyboard navigation
        function handleKeyboard(e) {
            const results = resultsContainer.querySelectorAll('.product-result');
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, results.length - 1);
                    updateSelection();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && results[selectedIndex]) {
                        selectProduct(currentResults[selectedIndex]);
                    }
                    break;
                case 'Escape':
                    dropdown.classList.add('hidden');
                    selectedIndex = -1;
                    break;
            }
        }

        function updateSelection() {
            const results = resultsContainer.querySelectorAll('.product-result');
            results.forEach((result, index) => {
                if (index === selectedIndex) {
                    result.classList.add('bg-green-50', 'border-green-500');
                } else {
                    result.classList.remove('bg-green-50', 'border-green-500');
                }
            });
        }

        function selectProduct(product) {
            // Update hidden select
            hiddenSelect.value = product.id;
            
            // Update search input
            searchInput.value = `${product.nom} (${product.prix})`;
            
            // Hide dropdown
            dropdown.classList.add('hidden');
            selectedIndex = -1;
            
            // Update summary
            updateRecapitulatif();
        }

        // Event listeners
        searchInput.addEventListener('input', (e) => {
            performSearch(e.target.value);
        });

        searchInput.addEventListener('keydown', handleKeyboard);

        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length >= 2) {
                dropdown.classList.remove('hidden');
            }
        });

        // Handle result clicks
        resultsContainer.addEventListener('click', (e) => {
            const resultEl = e.target.closest('.product-result');
            if (resultEl) {
                const index = parseInt(resultEl.dataset.index);
                selectProduct(currentResults[index]);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
                selectedIndex = -1;
            }
        });
    }

    // Add product
    addProduitBtn.addEventListener('click', function() {
        const newProduit = document.createElement('div');
        newProduit.innerHTML = getProduitTemplate(produitIndex);
        const produitRow = newProduit.firstElementChild;
        produitsContainer.appendChild(produitRow);
        
        // Initialize search for the new row
        initializeProductSearch(produitRow.querySelector('.product-search-container'));
        
        updateRemoveButtons();
        produitIndex++;
    });

    // Remove product
    produitsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-produit')) {
            const row = e.target.closest('.produit-row');
            row.style.transform = 'translateX(-100%)';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                updateRemoveButtons();
                updateRecapitulatif();
            }, 300);
        }
    });

    // Update remove buttons
    function updateRemoveButtons() {
        const produitRows = document.querySelectorAll('.produit-row');
        produitRows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-produit');
            removeBtn.disabled = produitRows.length === 1;
        });
    }

    // Update summary
    function updateRecapitulatif() {
        const produitRows = document.querySelectorAll('.produit-row');
        let recap = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ${isFrench ? 'Produit' : 'Product'}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ${isFrench ? 'Quantité' : 'Quantity'}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;
        let hasData = false;

        produitRows.forEach(row => {
            const select = row.querySelector('.produit-select');
            const searchInput = row.querySelector('.product-search-input');
            const quantite = row.querySelector('input[type="number"]');
            
            if (select.value && quantite.value) {
                recap += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${searchInput.value}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ${quantite.value}
                            </span>
                        </td>
                    </tr>
                `;
                hasData = true;
            }
        });

        recap += '</tbody></table></div>';

        if (hasData) {
            recapContent.innerHTML = recap;
            recapDiv.classList.remove('hidden');
            recapDiv.classList.add('animate-slideIn');
        } else {
            recapDiv.classList.add('hidden');
        }
    }

    // Listen for changes
    produitsContainer.addEventListener('change', updateRecapitulatif);
    produitsContainer.addEventListener('input', updateRecapitulatif);

    // Form validation
    document.getElementById('receptionForm').addEventListener('submit', function(e) {
        const produitRows = document.querySelectorAll('.produit-row');
        let hasValidProduit = false;

        produitRows.forEach(row => {
            const select = row.querySelector('.produit-select');
            const quantite = row.querySelector('input[type="number"]');
            
            if (select.value && quantite.value && parseFloat(quantite.value) > 0) {
                hasValidProduit = true;
            }
        });

        if (!hasValidProduit) {
            e.preventDefault();
            const message = isFrench ? 
                'Vous devez ajouter au moins un produit avec une quantité valide.' :
                'You must add at least one product with a valid quantity.';
            
            // Show toast notification
            showToast(message, 'error');
            return false;
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize search for existing rows
    document.querySelectorAll('.product-search-container').forEach(container => {
        initializeProductSearch(container);
    });

    // Initialize
    updateRemoveButtons();
});
</script>
@endpush

@push('styles')
<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out forwards;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Focus states */
.group:focus-within label {
    color: #2563eb;
}

/* Product search styles */
.product-search-container .product-dropdown {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.product-result {
    min-height: 60px; /* Minimum touch target for mobile */
}

.product-result:hover {
    transform: translateX(4px);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .product-dropdown {
        position: fixed !important;
        left: 1rem !important;
        right: 1rem !important;
        top: auto !important;
        bottom: 1rem !important;
        width: auto !important;
        max-height: 50vh !important;
        z-index: 9999 !important;
    }
    
    .product-search-input {
        font-size: 16px !important; /* Prevent zoom on iOS */
    }
    
    .product-result {
        padding: 1rem;
        min-height: 70px;
    }
    
    .produit-row {
        padding: 1rem;
    }
    
    .grid.grid-cols-1.md\\:grid-cols-12 {
        gap: 1rem;
    }
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Search input focus */
.product-search-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

/* Dropdown positioning for mobile */
@media (max-width: 640px) {
    .product-search-container {
        position: static;
    }
}

/* Better visibility for selected items */
.product-result.bg-green-50 {
    background-color: #f0fdf4 !important;
    border-left-color: #22c55e !important;
}

/* Smooth transitions */
.product-dropdown,
.product-result,
.product-search-input {
    transition: all 0.2s ease-in-out;
}

/* No results styling */
.no-results i {
    font-size: 2rem;
    color: #9ca3af;
}
</style>
@endpush