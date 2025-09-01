@extends('layouts.app')

@section('title', $isFrench ? 'Nouvelle Réception Vendeur' : 'New Vendor Reception')

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

            <!-- Form -->
            <div class="p-6">
                <form method="POST" action="{{ route('receptions.vendeurs.store') }}" id="receptionForm">
                    @csrf

                    <!-- Vendor and Date Selection -->
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

                    <!-- Products Section -->
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
                            <!-- Product rows will be added here -->
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

                    <!-- Submit Button -->
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

<!-- Product Row Template (Hidden) -->
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
                <select name="produits[INDEX][produit_id]" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">{{ $isFrench ? 'Sélectionnez un produit' : 'Select a product' }}</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->code_produit }}">{{ $produit->nom }}-{{ $produit->prix }}</option>
                    @endforeach
                </select>
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
<script>
let productIndex = 0;

function addProduct() {
    const container = document.getElementById('products-container');
    const template = document.getElementById('product-row-template');
    const clone = template.cloneNode(true);
    
    clone.id = 'product-row-' + productIndex;
    clone.classList.remove('hidden');
    
    // Replace INDEX placeholders with actual index
    const html = clone.innerHTML.replace(/INDEX/g, productIndex);
    clone.innerHTML = html;
    
    // Update product number
    clone.querySelector('.product-number').textContent = productIndex + 1;
    
    container.appendChild(clone);
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

// Add first product automatically
document.addEventListener('DOMContentLoaded', function() {
    updateNoProductsMessage();
});

// Form validation
document.getElementById('receptionForm').addEventListener('submit', function(e) {
    const container = document.getElementById('products-container');
    if (container.children.length === 0) {
        e.preventDefault();
        alert('{{ $isFrench ? "Veuillez ajouter au moins un produit." : "Please add at least one product." }}');
    }
});
</script>
@endpush
@endsection
