@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    
    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-3xl font-bold text-white">
                        {{ $isFrench ? 'Gestion des Stocks' : 'Stock Management' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Suivi des matières premières et produits' : 'Raw materials and products tracking' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
              

                <!-- Mobile Stats Cards -->
                <div class="grid grid-cols-1 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Matières en stock' : 'Materials in stock' }}</p>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($total_matieres, 0, ',', ' ') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Valeur totale' : 'Total value' }}</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($valeur_stock_matieres, 0, ',', ' ') }} XAF</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Produits en stock' : 'Products in stock' }}</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($total_produits, 0, ',', ' ') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Valeur totale' : 'Total value' }}</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($valeur_stock_produits, 0, ',', ' ') }} XAF</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-purple-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.4s;">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Par catégorie' : 'By category' }}</h3>
                        <div class="space-y-3">
                            @foreach($stats['produits_par_categorie'] as $categorie => $stat)
                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <span class="text-sm font-medium text-purple-700">{{ $categorie }}</span>
                                <span class="text-sm text-purple-600 font-semibold">{{ $stat['nombre_produits'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Mobile Charts -->
                <div class="space-y-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Répartition Matières' : 'Materials Distribution' }}</h3>
                        <canvas id="chartMatieresMobile"></canvas>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Répartition Produits' : 'Products Distribution' }}</h3>
                        <canvas id="chartProduitsMobile"></canvas>
                    </div>
                </div>

                <!-- Mobile Tables (Simplified) -->
                @include('stock.partials.matieres-table', ['matieres' => $matieres])
                @include('stock.partials.produits-table', ['produits' => $produits])
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <!-- Desktop Navigation Buttons -->
            <div class="flex justify-center space-x-4 mb-8">
                <a href="{{ route('chef.matieres.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    {{ $isFrench ? 'Gérer vos matières premières' : 'Manage raw materials' }}
                </a>
                <a href="{{ route('chef.produits.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 transition duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    {{ $isFrench ? 'Gérer vos produits' : 'Manage products' }}
                </a>
            </div>

            <!-- Desktop Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg transform hover:shadow-xl transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">{{ $isFrench ? 'Matières premières en stock' : 'Raw materials in stock' }}</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($total_matieres, 0, ',', ' ') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Valeur totale' : 'Total value' }}</p>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ number_format($valeur_stock_matieres, 0, ',', ' ') }} XAF</p>
                            </div>
                        </div>
                        @if($matiere_max)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Stock le plus important' : 'Largest stock' }}</p>
                            <p class="font-medium">{{ $matiere_max->nom }} ({{ $matiere_max->quantite }} {{ $matiere_max->unite_classique }})</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg transform hover:shadow-xl transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">{{ $isFrench ? 'Produits en stock' : 'Products in stock' }}</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($total_produits, 0, ',', ' ') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Valeur totale' : 'Total value' }}</p>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ number_format($valeur_stock_produits, 0, ',', ' ') }} XAF</p>
                            </div>
                        </div>
                        @if($produit_max)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Produit le plus stocké' : 'Most stocked product' }}</p>
                            <p class="font-medium">{{ $produit_max->nom }} ({{ $produit_max->quantite_totale }} {{ $isFrench ? 'unités' : 'units' }})</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg transform hover:shadow-xl transition-all duration-300">
                    <div class="p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Répartition par catégorie' : 'Distribution by category' }}</h3>
                        <div class="space-y-4">
                            @foreach($stats['produits_par_categorie'] as $categorie => $stat)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">{{ $categorie }}</span>
                                <span class="text-sm text-gray-900">{{ $stat['nombre_produits'] }} {{ $isFrench ? 'produits' : 'products' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow transform hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Répartition des Matières Premières' : 'Raw Materials Distribution' }}</h3>
                    <canvas id="chartMatieres"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow transform hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Répartition des Produits' : 'Products Distribution' }}</h3>
                    <canvas id="chartProduits"></canvas>
                </div>
            </div>

            <!-- Desktop Tables -->
            @include('stock.partials.matieres-table', ['matieres' => $matieres])
            @include('stock.partials.produits-table', ['produits' => $produits])

            <!-- Modal d'ajustement -->
            @include('stock.partials.adjust-quantity-modal')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = [
        '#2563EB', '#059669', '#DC2626', '#D97706', '#7C3AED',
        '#DB2777', '#2563EB', '#059669', '#DC2626', '#D97706'
    ];

    // Configuration des graphiques
    const configMatieres = {
        type: 'doughnut',
        data: {
            labels: @json($data_matieres['labels']),
            datasets: [{
                data: @json($data_matieres['data']),
                backgroundColor: chartColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    };

    const configProduits = {
        type: 'doughnut',
        data: {
            labels: @json($data_produits['labels']),
            datasets: [{
                data: @json($data_produits['data']),
                backgroundColor: chartColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    };

    // Initialisation des graphiques desktop
    if (document.getElementById('chartMatieres')) {
        new Chart(document.getElementById('chartMatieres'), configMatieres);
    }
    if (document.getElementById('chartProduits')) {
        new Chart(document.getElementById('chartProduits'), configProduits);
    }

    // Initialisation des graphiques mobiles
    if (document.getElementById('chartMatieresMobile')) {
        new Chart(document.getElementById('chartMatieresMobile'), configMatieres);
    }
    if (document.getElementById('chartProduitsMobile')) {
        new Chart(document.getElementById('chartProduitsMobile'), configProduits);
    }

    // Recherche dynamique
    const searchMatieres = document.getElementById('searchMatieres');
    const searchProduits = document.getElementById('searchProduits');

    if (searchMatieres) {
        searchMatieres.addEventListener('input', debounce(function(e) {
            filterTable('tableMatieres', e.target.value);
        }, 300));
    }

    if (searchProduits) {
        searchProduits.addEventListener('input', debounce(function(e) {
            filterTable('tableProduits', e.target.value);
        }, 300));
    }
});

function filterTable(tableId, query) {
    const rows = document.querySelectorAll(`#${tableId} tr:not(.header)`);
    const lowercaseQuery = query.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(lowercaseQuery) ? '' : 'none';
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}
</style>
@endsection
