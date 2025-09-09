@extends('layouts.app')

@section('title', $isFrench ? 'Nouvelle Réception Vendeur' : 'New Vendor Reception')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl px-6 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $isFrench ? 'Nouvelle Réception Vendeur' : 'New Vendor Reception' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $isFrench ? 'Enregistrer les quantités reçues par le vendeur' : 'Record quantities received by vendor' }}
                            </p>
                        </div>
                    </div>
                    @include('buttons')
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('receptions.vendeurs.store') }}" id="receptionForm">
                    @csrf

                    <div class="bg-blue-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="vendeur_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Vendeur' : 'Vendor' }} <span class="text-red-500">*</span>
                                </label>
                                <select name="vendeur_id" id="vendeur_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">{{ $isFrench ? 'Sélectionnez un vendeur' : 'Select a vendor' }}</option>
                                    @foreach($vendeurs as $vendeur)
                                        <option value="{{ $vendeur->id }}" {{ old('vendeur_id') == $vendeur->id ? 'selected' : '' }}>
                                            {{ $vendeur->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendeur_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_reception" class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ $isFrench ? 'Date de Réception' : 'Reception Date' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_reception" id="date_reception" required
                                    value="{{ old('date_reception', date('Y-m-d')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                @error('date_reception')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-xl p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                {{ $isFrench ? 'Produits et Quantités' : 'Products and Quantities' }}
                            </h3>
                            <button type="button" onclick="addProduct()"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>{{ $isFrench ? 'Ajouter Produit' : 'Add Product' }}</span>
                            </button>
                        </div>

                        <div id="products-container">
                        </div>

                        <div class="text-center py-8" id="no-products-message">
                            <div class="bg-white rounded-xl p-6 border-2 border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">{{ $isFrench ? 'Aucun produit ajouté' : 'No products added' }}</p>
                                <button type="button" onclick="addProduct()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    {{ $isFrench ? 'Ajouter le premier produit' : 'Add first product' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('receptions.vendeurs.index') }}"
                           class="px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Enregistrer les Réceptions' : 'Save Receptions' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="product-row-template" class="hidden">
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm product-row mb-4">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-900">{{ $isFrench ? 'Produit' : 'Product' }} <span class="product-number"></span></h4>
            <button type="button" onclick="removeProduct(this)"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>{{ $isFrench ? 'Supprimer' : 'Remove' }}</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="md:col-span-2 lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Produit' : 'Product' }} <span class="text-red-500">*</span>
                </label>
                <div class="product-search-container relative">
                    <div class="relative">
                        <input type="text"
                               class="product-search-input w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                               placeholder="{{ $isFrench ? 'Rechercher un produit...' : 'Search for a product...' }}"
                               autocomplete="off">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div class="loading-spinner hidden">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="product-search-dropdown absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                        <div class="product-search-results">
                        </div>
                        <div class="no-results-message hidden p-4 text-center text-gray-500 text-sm">
                            {{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}
                        </div>
                    </div>

                    <input type="hidden" name="produits[INDEX][produit_id]" class="selected-product-input" required>

                    <div class="selected-product-display hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div class="selected-product-info">
                                <div class="font-medium text-gray-900 selected-product-name"></div>
                                <div class="text-sm text-gray-600 selected-product-details"></div>
                            </div>
                            <button type="button" class="clear-selection text-red-600 hover:text-red-800 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Entrée Matin' : 'Morning Entry' }}
                </label>
                <input type="number" name="produits[INDEX][quantite_entree_matin]" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Entrée Journée' : 'Day Entry' }}
                </label>
                <input type="number" name="produits[INDEX][quantite_entree_journee]" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Quantité Invendue' : 'Unsold Quantity' }}
                </label>
                <input type="number" name="produits[INDEX][quantite_invendue]" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Reste d\'Hier' : 'Yesterday\'s Remainder' }}
                </label>
                <input type="number" name="produits[INDEX][quantite_reste_hier]" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0.00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Quantité Avariée' : 'Spoiled Quantity' }}
                </label>
                <input type="number" name="produits[INDEX][quantite_avarie]" step="0.01" min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0.00">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
/* Styles pour optimiser l'affichage mobile */
@media (max-width: 768px) {
    .product-search-dropdown.mobile-fixed {
        position: fixed;
        left: 1rem;
        right: 1rem;
        width: calc(100% - 2rem);
        max-height: 50vh;
        z-index: 9999;
    }
}

.product-search-result {
    transition: background-color 0.15s ease-in-out;
    min-height: 60px; /* Hauteur tactile optimisée pour mobile */
}

.product-search-result:hover,
.product-search-result.highlighted {
    background-color: #f3f4f6;
}

.product-search-result.highlighted {
    background-color: #dbeafe;
}

/* Éviter le zoom automatique sur iOS */
.product-search-input {
    font-size: 16px !important;
}

/* Animation du spinner */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

<script>
let productIndex = 0;
let searchTimeout = null;
let currentHighlightedIndex = -1;

const productsCache = {!! json_encode($produits->map(function($produit) {
    return [
        'id' => $produit->code_produit,
        'nom' => $produit->nom,
        'prix' => $produit->prix,
        'categorie' => $produit->categorie ?? '',
        'display' => $produit->nom . ' - ' . $produit->prix . 'FCFA'
    ];
})->toArray()) !!};

function fetchProducts(query = '') {
    if (!query) {
        return productsCache;
    }
    const filtered = productsCache.filter(product =>
        product.nom.toLowerCase().includes(query.toLowerCase()) ||
        product.categorie.toLowerCase().includes(query.toLowerCase()) ||
        product.id.toString().includes(query)
    );
    return filtered.slice(0, 10);
}

function addProduct() {
    const container = document.getElementById('products-container');
    const template = document.getElementById('product-row-template');
    const clone = template.cloneNode(true);
    clone.id = 'product-row-' + productIndex;
    clone.classList.remove('hidden');
    const html = clone.innerHTML.replace(/INDEX/g, productIndex);
    clone.innerHTML = html;
    clone.querySelector('.product-number').textContent = productIndex + 1;
    container.appendChild(clone);
    initializeProductSearch(clone);
    productIndex++;
    updateNoProductsMessage();
}

function removeProduct(button) {
    const row = button.closest('.product-row');
    row.remove();
    updateProductNumbers();
    updateNoProductsMessage();
}

function updateProductNumbers() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        const numberSpan = row.querySelector('.product-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
    });
}

function updateNoProductsMessage() {
    const container = document.getElementById('products-container');
    const message = document.getElementById('no-products-message');
    const hasProducts = container.children.length > 0;
    message.style.display = hasProducts ? 'none' : 'block';
}

function initializeProductSearch(productRow) {
    const searchInput = productRow.querySelector('.product-search-input');
    const dropdown = productRow.querySelector('.product-search-dropdown');
    const resultsContainer = productRow.querySelector('.product-search-results');
    const noResultsMessage = productRow.querySelector('.no-results-message');
    const hiddenInput = productRow.querySelector('.selected-product-input');
    const selectedDisplay = productRow.querySelector('.selected-product-display');
    const selectedName = productRow.querySelector('.selected-product-name');
    const selectedDetails = productRow.querySelector('.selected-product-details');
    const clearButton = productRow.querySelector('.clear-selection');
    const searchIcon = productRow.querySelector('.search-icon');
    const loadingSpinner = productRow.querySelector('.loading-spinner');

    function showLoading() {
        searchIcon.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
    }

    function hideLoading() {
        searchIcon.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
        dropdown.classList.remove('mobile-fixed'); // Retirer la classe mobile
        currentHighlightedIndex = -1;
    }

    function openDropdown() {
        dropdown.classList.remove('hidden');
        // Gérer le positionnement pour mobile
        if (window.innerWidth <= 768) {
            dropdown.classList.add('mobile-fixed');
            const rect = searchInput.getBoundingClientRect();
            dropdown.style.top = `${rect.bottom + 4}px`;
            // Scroll to the input to prevent it from being hidden by the keyboard
            searchInput.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            dropdown.classList.remove('mobile-fixed');
            dropdown.style.top = ''; // Réinitialiser pour le desktop
        }
    }

    // Gestion de l'événement `input` pour la recherche
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        if (query.length < 1) {
            closeDropdown();
            return;
        }
        showLoading();
        searchTimeout = setTimeout(() => {
            const results = fetchProducts(query);
            displaySearchResults(results, query);
            hideLoading();
        }, 300);
    });

    // Fonction pour afficher les résultats
    function displaySearchResults(results, query) {
        resultsContainer.innerHTML = '';
        currentHighlightedIndex = -1;
        if (results.length === 0) {
            noResultsMessage.classList.remove('hidden');
            openDropdown();
            return;
        }
        noResultsMessage.classList.add('hidden');
        results.forEach((product, index) => {
            const resultDiv = document.createElement('div');
            resultDiv.className = 'product-search-result px-4 py-3 cursor-pointer border-b border-gray-100 last:border-b-0';
            resultDiv.dataset.productId = product.id;
            resultDiv.dataset.index = index;
            const highlightedName = product.nom.replace(
                new RegExp(query, 'gi'),
                '<mark class="bg-yellow-200">$&</mark>'
            );
            resultDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-medium text-gray-900">${highlightedName}</div>
                        <div class="text-sm text-gray-600">
                            ${product.prix}FCFA ${product.categorie ? '• ' + product.categorie : ''}
                            <span class="text-xs text-gray-400">#${product.id}</span>
                        </div>
                    </div>
                </div>
            `;
            resultDiv.addEventListener('click', () => {
                selectProduct(product);
            });
            resultsContainer.appendChild(resultDiv);
        });
        openDropdown();
    }

    // Fonction pour sélectionner un produit
    function selectProduct(product) {
        hiddenInput.value = product.id;
        searchInput.value = '';
        selectedName.textContent = product.nom;
        selectedDetails.textContent = `${product.prix}FCFA ${product.categorie ? '• ' + product.categorie : ''} #${product.id}`;
        selectedDisplay.classList.remove('hidden');
        searchInput.style.display = 'none';
        closeDropdown();
    }

    clearButton.addEventListener('click', () => {
        hiddenInput.value = '';
        searchInput.value = '';
        selectedDisplay.classList.add('hidden');
        searchInput.style.display = 'block';
        searchInput.focus();
    });

    searchInput.addEventListener('keydown', function(e) {
        const results = resultsContainer.querySelectorAll('.product-search-result');
        if (isSearching) return; // Empêcher la navigation au clavier pendant la recherche

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (currentHighlightedIndex < results.length - 1) {
                    currentHighlightedIndex++;
                    updateHighlight(results);
                }
                break;
            case 'ArrowUp':
                e.preventDefault();
                if (currentHighlightedIndex > 0) {
                    currentHighlightedIndex--;
                    updateHighlight(results);
                }
                break;
            case 'Enter':
                e.preventDefault();
                if (currentHighlightedIndex >= 0 && results[currentHighlightedIndex]) {
                    results[currentHighlightedIndex].click();
                }
                break;
            case 'Escape':
                closeDropdown();
                searchInput.blur();
                break;
        }
    });

    function updateHighlight(results) {
        results.forEach((result, index) => {
            result.classList.toggle('highlighted', index === currentHighlightedIndex);
        });
        if (currentHighlightedIndex >= 0 && results[currentHighlightedIndex]) {
            results[currentHighlightedIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }

    document.addEventListener('click', function(e) {
        if (!productRow.contains(e.target)) {
            closeDropdown();
        }
    });

    // L'événement `focus` est plus fiable que `input` sur certains mobiles pour déclencher l'affichage initial
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            // Afficher le dropdown et les résultats instantanément
            const results = fetchProducts(this.value.trim());
            displaySearchResults(results, this.value.trim());
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateNoProductsMessage();
});

document.getElementById('receptionForm').addEventListener('submit', function(e) {
    const container = document.getElementById('products-container');
    if (container.children.length === 0) {
        e.preventDefault();
        alert('{{ $isFrench ? "Veuillez ajouter au moins un produit." : "Please add at least one product." }}');
        return;
    }
    const hiddenInputs = container.querySelectorAll('.selected-product-input');
    let hasEmptyProduct = false;
    hiddenInputs.forEach((input, index) => {
        if (!input.value) {
            hasEmptyProduct = true;
            const productRow = input.closest('.product-row');
            productRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            productRow.style.border = '2px solid #ef4444';
            setTimeout(() => {
                productRow.style.border = '';
            }, 3000);
        }
    });
    if (hasEmptyProduct) {
        e.preventDefault();
        alert('{{ $isFrench ? "Veuillez sélectionner tous les produits avant de soumettre le formulaire." : "Please select all products before submitting the form." }}');
    }
});
</script>
@endpush
@endsection
