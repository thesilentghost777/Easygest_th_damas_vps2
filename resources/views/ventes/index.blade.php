@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Header responsive -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden animate-fade-in">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 text-white p-4 lg:p-5">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-3 lg:space-y-0">
                <h1 class="text-xl lg:text-2xl font-bold">
                    {{ $isFrench ? 'Liste Détaillée des Opérations de Vente' : 'Detailed List of Sales Operations' }}
                </h1>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="{{ route('ventes.compare') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 lg:py-2 bg-blue-600 border border-transparent rounded-xl lg:rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <i class="mdi mdi-chart-bar mr-2"></i>
                        {{ $isFrench ? 'Comparer Vendeurs' : 'Compare Sellers' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 lg:p-6">
            <!-- Filters Section -->
            <div class="mb-6 bg-gray-50 rounded-xl p-4 shadow-sm animate-slide-in">
                <h2 class="text-lg text-blue-800 font-semibold mb-3 flex items-center">
                    <i class="mdi mdi-filter mr-2"></i>
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </h2>
                
                <!-- Mobile filters (vertical layout) -->
                <div class="lg:hidden space-y-4">
                    <div class="mobile-field">
                        <label for="searchInputMobile" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Rechercher' : 'Search' }}
                        </label>
                        <input type="text" id="searchInputMobile" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200" placeholder="{{ $isFrench ? 'Rechercher...' : 'Search...' }}">
                    </div>
                    <div class="mobile-field">
                        <label for="typeFilterMobile" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Type d\'opération' : 'Operation Type' }}
                        </label>
                        <select id="typeFilterMobile" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200">
                            <option value="">{{ $isFrench ? 'Tous les types' : 'All Types' }}</option>
                            <option value="Vente">{{ $isFrench ? 'Vente' : 'Sale' }}</option>
                            <option value="Produit invendu">{{ $isFrench ? 'Produit Invendu' : 'Unsold Product' }}</option>
                            <option value="Produit Avarie">{{ $isFrench ? 'Produit Avarie' : 'Damaged Product' }}</option>
                        </select>
                    </div>
                    <div class="mobile-field">
                        <label for="dateFilterMobile" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <input type="date" id="dateFilterMobile" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200">
                    </div>
                    <button id="resetFiltersMobile" class="w-full py-3 px-4 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                        <i class="mdi mdi-refresh mr-2"></i>
                        {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </button>
                </div>

                <!-- Desktop filters (horizontal layout) -->
                <div class="hidden lg:grid lg:grid-cols-4 lg:gap-4">
                    <div>
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isFrench ? 'Rechercher' : 'Search' }}
                        </label>
                        <input type="text" id="searchInput" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="{{ $isFrench ? 'Rechercher...' : 'Search...' }}">
                    </div>
                    <div>
                        <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isFrench ? 'Type d\'opération' : 'Operation Type' }}
                        </label>
                        <select id="typeFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ $isFrench ? 'Tous les types' : 'All Types' }}</option>
                            <option value="Vente">{{ $isFrench ? 'Vente' : 'Sale' }}</option>
                            <option value="Produit invendu">{{ $isFrench ? 'Produit Invendu' : 'Unsold Product' }}</option>
                            <option value="Produit Avarie">{{ $isFrench ? 'Produit Avarie' : 'Damaged Product' }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <input type="date" id="dateFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div class="flex items-end">
                        <button id="resetFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition w-full">
                            {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto bg-white rounded-lg shadow animate-fade-in">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-blue-800 text-white">
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Serveur' : 'Server' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Total' : 'Total' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Type' : 'Type' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Monnaie' : 'Currency' }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ventes as $vente)
                        <tr data-type="{{ $vente->type }}" data-date="{{ $vente->date_vente }}" class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->date_vente }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->nom_produit ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->nom_serveur ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->quantite }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->prix ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($vente->prix && $vente->type == 'Vente')
                                    {{ number_format($vente->prix * $vente->quantite, 0, ',', ' ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $typeLabels = [
                                        'Vente' => $isFrench ? 'Vente' : 'Sale',
                                        'Produit invendu' => $isFrench ? 'Produit invendu' : 'Unsold Product',
                                        'Produit Avarie' => $isFrench ? 'Produit Avarie' : 'Damaged Product'
                                    ];
                                    $displayType = $typeLabels[$vente->type] ?? $vente->type;
                                @endphp
                                @if($vente->type == 'Vente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $displayType }}</span>
                                @elseif($vente->type == 'Produit invendu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $displayType }}</span>
                                @elseif($vente->type == 'Produit Avarie')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $displayType }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $displayType }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vente->monnaie ?? 'XAF' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-4 animate-fade-in">
                @foreach($ventes as $vente)
                <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-200 mobile-card" data-type="{{ $vente->type }}" data-date="{{ $vente->date_vente }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900 mb-1">#{{ $vente->id }}</div>
                            <div class="text-xs text-blue-600 font-medium">{{ $vente->date_vente }}</div>
                        </div>
                        <div class="text-right">
                            @php
                                $typeLabels = [
                                    'Vente' => $isFrench ? 'Vente' : 'Sale',
                                    'Produit invendu' => $isFrench ? 'Produit invendu' : 'Unsold Product',
                                    'Produit Avarie' => $isFrench ? 'Produit Avarie' : 'Damaged Product'
                                ];
                                $displayType = $typeLabels[$vente->type] ?? $vente->type;
                            @endphp
                            @if($vente->type == 'Vente')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $displayType }}</span>
                            @elseif($vente->type == 'Produit invendu')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $displayType }}</span>
                            @elseif($vente->type == 'Produit Avarie')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $displayType }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $displayType }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Produit:' : 'Product:' }}</span>
                            <span class="text-sm font-medium">{{ $vente->nom_produit ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Serveur:' : 'Server:' }}</span>
                            <span class="text-sm font-medium">{{ $vente->nom_serveur ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <span class="text-sm font-medium">{{ $vente->quantite }}</span>
                        </div>
                        @if($vente->prix)
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }}</span>
                            <span class="text-sm font-medium">{{ $vente->prix }} {{ $vente->monnaie ?? 'XAF' }}</span>
                        </div>
                        @endif
                        @if($vente->prix && $vente->type == 'Vente')
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($vente->prix * $vente->quantite, 0, ',', ' ') }} {{ $vente->monnaie ?? 'XAF' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        .mobile-field {
            transition: all 0.2s ease-out;
        }
        .mobile-field:focus-within {
            transform: translateY(-2px);
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
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isMobile = window.innerWidth < 1024;
        
        // Mobile and desktop selectors
        const searchInput = isMobile ? document.getElementById('searchInputMobile') : document.getElementById('searchInput');
        const typeFilter = isMobile ? document.getElementById('typeFilterMobile') : document.getElementById('typeFilter');
        const dateFilter = isMobile ? document.getElementById('dateFilterMobile') : document.getElementById('dateFilter');
        const resetFilters = isMobile ? document.getElementById('resetFiltersMobile') : document.getElementById('resetFilters');
        
        const tableRows = document.querySelectorAll(isMobile ? '.mobile-card' : 'tbody tr');

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const typeValue = typeFilter.value;
            const dateValue = dateFilter.value;

            tableRows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const rowDate = row.getAttribute('data-date');
                const rowText = row.textContent.toLowerCase();

                const matchesSearch = !searchTerm || rowText.includes(searchTerm);
                const matchesType = !typeValue || rowType === typeValue;
                const matchesDate = !dateValue || rowDate === dateValue;

                if (matchesSearch && matchesType && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
        dateFilter.addEventListener('change', applyFilters);

        resetFilters.addEventListener('click', function() {
            searchInput.value = '';
            typeFilter.value = '';
            dateFilter.value = '';

            tableRows.forEach(row => {
                row.style.display = '';
            });
        });
    });
</script>
@endsection