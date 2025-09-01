
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-t-2xl p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            {{ $isFrench ? 'Statistiques de Production' : 'Production Statistics' }}
                        </h1>
                        <p class="text-blue-100 text-sm md:text-base">
                            {{ $isFrench ? 'Analyse détaillée de votre production' : 'Detailed analysis of your production' }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <a href="{{ route('employee.performance') }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 font-medium text-center">
                            {{ $isFrench ? 'Voir statistiques par producteur' : 'View statistics by producer' }}
                        </a>
                        @include('buttons')
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 animate-scale-in">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
                {{ $isFrench ? 'Filtres' : 'Filters' }}
            </h2>
            
            <form action="{{ route('statistiques.details') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="animate-fade-in-up" style="animation-delay: 0.1s">
                    <label for="date_debut" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Date début' : 'Start date' }}
                    </label>
                    <input type="date" 
                           id="date_debut" 
                           name="date_debut" 
                           value="{{ request('date_debut') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200">
                </div>
                
                <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                    <label for="date_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Date fin' : 'End date' }}
                    </label>
                    <input type="date" 
                           id="date_fin" 
                           name="date_fin" 
                           value="{{ request('date_fin') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200">
                </div>
                
                <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                    <label for="producteur" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Producteur' : 'Producer' }}
                    </label>
                    <select id="producteur" 
                            name="producteur" 
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200">
                        <option value="">{{ $isFrench ? 'Tous les producteurs' : 'All producers' }}</option>
                        @foreach($producteurs as $producteur)
                            <option value="{{ $producteur->id }}" {{ request('producteur') == $producteur->id ? 'selected' : '' }}>
                                {{ $producteur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="animate-fade-in-up" style="animation-delay: 0.4s">
                    <label for="produit" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select id="produit" 
                            name="produit" 
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200">
                        <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                                {{ $produit->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2 lg:col-span-4 flex justify-end animate-fade-in-up" style="animation-delay: 0.5s">
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Résultats de production' : 'Production results' }}
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Lot ID' : 'Lot ID' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Producteur' : 'Producer' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Prix Vente' : 'Sale Price' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Coût' : 'Cost' }}
                            </th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider">
                                {{ $isFrench ? 'Bénéfice Potentiel' : 'Potential Profit' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($productions as $prod)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900 font-medium">{{ $prod['id_lot'] }}</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900">{{ $prod['date_production'] }}</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900">{{ $prod['producteur'] }}</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900">{{ $prod['produit'] }}</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900 font-medium">{{ number_format($prod['quantite'], 0) }}</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-blue-600 font-medium">{{ number_format($prod['chiffre_affaires'], 0) }} F</td>
                            <td class="px-4 md:px-6 py-4 text-sm text-red-600 font-medium">{{ number_format($prod['cout_production'], 0) }} F</td>
                            <td class="px-4 md:px-6 py-4 text-sm font-medium {{ $prod['benefice_brut'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($prod['benefice_brut'], 0) }} F
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
                                    </h3>
                                    <p class="text-gray-500">
                                        {{ $isFrench ? 'Aucune production trouvée pour les critères sélectionnés.' : 'No production found for the selected criteria.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($productions) > 0)
                    <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                        <tr>
                            <td colspan="4" class="px-4 md:px-6 py-3 text-right text-sm font-semibold text-gray-700">
                                {{ $isFrench ? 'Total:' : 'Total:' }}
                            </td>
                            <td class="px-4 md:px-6 py-3 text-sm font-semibold text-gray-900">
                                {{ number_format($productions->sum('quantite'), 0) }}
                            </td>
                            <td class="px-4 md:px-6 py-3 text-sm font-semibold text-blue-600">
                                {{ number_format($productions->sum('chiffre_affaires'), 0) }} F
                            </td>
                            <td class="px-4 md:px-6 py-3 text-sm font-semibold text-red-600">
                                {{ number_format($productions->sum('cout_production'), 0) }} F
                            </td>
                            <td class="px-4 md:px-6 py-3 text-sm font-semibold {{ $productions->sum('benefice_brut') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($productions->sum('benefice_brut'), 0) }} F
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            
            @if($productions instanceof \Illuminate\Pagination\LengthAwarePaginator && $productions->hasPages())
            <div class="px-6 py-3 bg-white border-t border-gray-200">
                {{ $productions->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Mobile-First CSS Animations -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Mobile table adjustments */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem !important;
        min-width: 100px;
    }
    
    /* Mobile grid adjustments */
    .lg\:grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    /* Touch-friendly form elements */
    input, select, button {
        min-height: 44px;
        font-size: 16px; /* Prevents zoom on iOS */
        touch-action: manipulation;
    }
}

/* Enhanced table styling */
.table-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.table-container::-webkit-scrollbar {
    height: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

/* Smooth transitions */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Focus improvements */
input:focus, select:focus, button:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
@endsection
