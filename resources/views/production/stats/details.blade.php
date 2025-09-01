@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <br>
<div class="mb-6 hidden md:block">
<button onclick="history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
</svg>
{{ $isFrench ? 'Retour' : 'Back' }}
</button>
</div>
<br>
    
    <!-- Mobile Header -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
            <h1 class="text-xl font-bold">{{ $isFrench ? 'Détails des Productions' : 'Production Details' }}</h1>
            <p class="text-sm text-blue-200 mt-1">{{ $isFrench ? 'Analyse détaillée par lot' : 'Detailed batch analysis' }}</p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200 text-center">
            <h1 class="text-4xl font-bold text-blue-800 mb-2">{{ $isFrench ? 'Détails des Productions' : 'Production Details' }}</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-blue-600 to-green-500 mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="mb-6 animate-fade-in">
        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">{{ $isFrench ? 'Filtres et Recherche' : 'Filters and Search' }}</h2>
            
            <!-- Mobile Filters -->
            <div class="lg:hidden">
                <form method="GET" action="{{ route('production.stats.details') }}" id="mobileFilterForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Recherche par ID Lot' : 'Search by Batch ID' }}</label>
                            <input type="text" name="id_lot" value="{{ request('id_lot') }}" placeholder="{{ $isFrench ? 'Entrez l\'ID du lot...' : 'Enter batch ID...' }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date début' : 'Start date' }}</label>
                                <input type="date" name="period_start" value="{{ request('period_start') }}" class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date fin' : 'End date' }}</label>
                                <input type="date" name="period_end" value="{{ request('period_end') }}" class="w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Producteur' : 'Producer' }}</label>
                            <select name="producteur" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ $isFrench ? 'Tous les producteurs' : 'All producers' }}</option>
                                @foreach($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" {{ request('producteur') == $producteur->id ? 'selected' : '' }}>
                                        {{ $producteur->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Produit' : 'Product' }}</label>
                            <select name="produit" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                                        {{ $produit->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Trier par' : 'Sort by' }}</label>
                            <select name="sort_by" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ $isFrench ? 'Date' : 'Date' }}</option>
                                <option value="id_lot" {{ request('sort_by') == 'id_lot' ? 'selected' : '' }}>{{ $isFrench ? 'ID Lot' : 'Batch ID' }}</option>
                                <option value="nom_produit" {{ request('sort_by') == 'nom_produit' ? 'selected' : '' }}>{{ $isFrench ? 'Produit' : 'Product' }}</option>
                                <option value="nom_producteur" {{ request('sort_by') == 'nom_producteur' ? 'selected' : '' }}>{{ $isFrench ? 'Producteur' : 'Producer' }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Ordre' : 'Order' }}</label>
                            <select name="sort_order" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ $isFrench ? 'Décroissant' : 'Descending' }}</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ $isFrench ? 'Croissant' : 'Ascending' }}</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-blue-700 transition-all duration-200 active:scale-95">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                {{ $isFrench ? 'Appliquer' : 'Apply' }}
                            </button>
                            <a href="{{ route('production.stats.details') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-xl font-medium text-center hover:bg-gray-300 transition-all duration-200 active:scale-95">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Desktop Filters -->
            <div class="hidden lg:block">
                <form method="GET" action="{{ route('production.stats.details') }}" id="desktopFilterForm">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Recherche par ID Lot' : 'Search by Batch ID' }}</label>
                            <input type="text" name="id_lot" value="{{ request('id_lot') }}" placeholder="{{ $isFrench ? 'Entrez l\'ID du lot...' : 'Enter batch ID...' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date début' : 'Start date' }}</label>
                            <input type="date" name="period_start" value="{{ request('period_start') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date fin' : 'End date' }}</label>
                            <input type="date" name="period_end" value="{{ request('period_end') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Producteur' : 'Producer' }}</label>
                            <select name="producteur" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ $isFrench ? 'Tous les producteurs' : 'All producers' }}</option>
                                @foreach($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}" {{ request('producteur') == $producteur->id ? 'selected' : '' }}>
                                        {{ $producteur->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Produit' : 'Product' }}</label>
                            <select name="produit" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                                        {{ $produit->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Trier par' : 'Sort by' }}</label>
                            <select name="sort_by" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ $isFrench ? 'Date' : 'Date' }}</option>
                                <option value="id_lot" {{ request('sort_by') == 'id_lot' ? 'selected' : '' }}>{{ $isFrench ? 'ID Lot' : 'Batch ID' }}</option>
                                <option value="nom_produit" {{ request('sort_by') == 'nom_produit' ? 'selected' : '' }}>{{ $isFrench ? 'Produit' : 'Product' }}</option>
                                <option value="nom_producteur" {{ request('sort_by') == 'nom_producteur' ? 'selected' : '' }}>{{ $isFrench ? 'Producteur' : 'Producer' }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Ordre' : 'Order' }}</label>
                            <select name="sort_order" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ $isFrench ? 'Décroissant' : 'Descending' }}</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ $isFrench ? 'Croissant' : 'Ascending' }}</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-3">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                                {{ $isFrench ? 'Appliquer' : 'Apply' }}
                            </button>
                            <a href="{{ route('production.stats.details') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium text-center hover:bg-gray-300 transition-all duration-200 transform hover:scale-105">
                                {{ $isFrench ? 'Reset' : 'Reset' }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des productions -->
    <div class="animate-fade-in">
        @if($productions->count() > 0)
            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-4">
                @foreach($productions as $production)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: {{ $loop->index * 0.05 }}s">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold">{{ $isFrench ? 'Lot :' : 'Batch:' }} {{ $production->id_lot }}</h3>
                                <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full">
                                    {{ \Carbon\Carbon::parse($production->created_at)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        @php
                            $prix = $production->valeur_production / $production->quantite_produit;
                        @endphp
                        <div class="p-4 space-y-3">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Produit' : 'Product' }}</p>
                                <p class="font-medium text-blue-900">{{ $production->nom_produit }} - {{ $prix }}</p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-3">
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Producteur' : 'Producer' }}</p>
                                <p class="font-medium text-green-900">{{ $production->nom_producteur }}</p>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-3">
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}</p>
                                <p class="font-medium text-purple-900">{{ $production->quantite_produit }}</p>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2 mt-4">
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ $isFrench ? 'Valeur' : 'Value' }}</p>
                                    <p class="text-sm font-bold text-blue-600">{{ number_format($production->valeur_production, 0, ',', ' ') }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ $isFrench ? 'Coût' : 'Cost' }}</p>
                                    <p class="text-sm font-bold text-orange-600">{{ number_format($production->cout_matieres, 0, ',', ' ') }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ $isFrench ? 'Bénéfice' : 'Benefit' }}</p>
                                    <p class="text-sm font-bold text-{{ $production->benefice >= 0 ? 'green' : 'red' }}-600">{{ number_format($production->benefice, 0, ',', ' ') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <button onclick="toggleMaterials('{{ $production->id_lot }}')" class="w-full bg-blue-100 text-blue-800 py-2 px-4 rounded-lg font-medium hover:bg-blue-200 transition-all duration-200 active:scale-95">
                                    {{ $isFrench ? 'Voir les matières' : 'View materials' }}
                                </button>
                                
                                <div id="materials-{{ $production->id_lot }}" class="hidden mt-3 space-y-2">
                                    @foreach($production->matieres as $matiere)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-yellow-900">{{ $matiere['nom'] }}</span>
                                                <span class="text-xs text-yellow-700">{{ number_format($matiere['cout'], 0, ',', ' ') }} XAF</span>
                                            </div>
                                            <p class="text-xs text-yellow-600 mt-1">{{ $matiere['quantite'] }} {{ $matiere['unite'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Lot' : 'Batch' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Producteur' : 'Producer' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Valeur (XAF)' : 'Value (XAF)' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Coût MP (XAF)' : 'Materials Cost (XAF)' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Bénéfice (XAF)' : 'Benefit (XAF)' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($productions as $production)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    @php
                                        $prix= $production->valeur_production/$production->quantite_produit;
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $production->id_lot }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($production->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $production->nom_produit }} - {{ $prix }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $production->nom_producteur }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $production->quantite_produit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ number_format($production->valeur_production, 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-orange-600">{{ number_format($production->cout_matieres, 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-{{ $production->benefice >= 0 ? 'green' : 'red' }}-600">{{ number_format($production->benefice, 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button onclick="toggleMaterials('{{ $production->id_lot }}')" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-lg font-medium hover:bg-blue-200 transition-all duration-200 transform hover:scale-105">
                                            {{ $isFrench ? 'Détails' : 'Details' }}
                                        </button>
                                    </td>
                                </tr>
                                <tr id="materials-desktop-{{ $production->id_lot }}" class="hidden bg-yellow-50">
                                    <td colspan="9" class="px-6 py-4">
                                        <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                            <h4 class="font-medium text-gray-900 mb-3">{{ $isFrench ? 'Matières premières utilisées :' : 'Raw materials used:' }}</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach($production->matieres as $matiere)
                                                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-3">
                                                        <div class="flex justify-between items-center">
                                                            <span class="font-medium text-yellow-900">{{ $matiere['nom'] }}</span>
                                                            <span class="text-sm text-yellow-700">{{ number_format($matiere['cout'], 0, ',', ' ') }} XAF</span>
                                                        </div>
                                                        <p class="text-sm text-yellow-600 mt-1">{{ $matiere['quantite'] }} {{ $matiere['unite'] }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $productions->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production trouvée avec ces critères.' : 'No production found with these criteria.' }}</p>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
            touch-action: manipulation;
        }
        .mobile-card:active {
            transform: scale(0.98) !important;
        }
        /* Touch targets */
        button, input, select {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
        /* Prevent zoom on input focus */
        input[type="text"], input[type="date"], select {
            font-size: 16px;
        }
    }
</style>

<script>
function toggleMaterials(idLot) {
    const element = document.getElementById(`materials-${idLot}`);
    const desk = document.getElementById(`materials-desktop-${idLot}`);
    
    if (element) {
        element.classList.toggle('hidden');
    }
    if (desk) {
        desk.classList.toggle('hidden');
    }
}

// Fonction pour soumettre le formulaire mobile avec validation
function submitMobileForm() {
    const form = document.getElementById('mobileFilterForm');
    if (form) {
        // Ajouter un indicateur de chargement
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Chargement...';
        }
        form.submit();
    }
}

// Gestion des événements pour les formulaires
document.addEventListener('DOMContentLoaded', function() {
    // Formulaire mobile
    const mobileForm = document.getElementById('mobileFilterForm');
    if (mobileForm) {
        mobileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitMobileForm();
        });
    }
    
    // Formulaire desktop
    const desktopForm = document.getElementById('desktopFilterForm');
    if (desktopForm) {
        desktopForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Chargement...';
            }
        });
    }
    
    // Optimisation pour mobile : éviter le double-tap zoom
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);
});
</script>
@endsection
