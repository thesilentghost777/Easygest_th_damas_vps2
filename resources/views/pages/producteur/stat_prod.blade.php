@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 lg:p-6 rounded-xl shadow-lg">
            <h1 class="text-xl lg:text-2xl font-bold flex items-center">
                <i class="mdi mdi-chart-line mr-3"></i>
                {{ $isFrench ? 'Statistiques de Production' : 'Production Statistics' }}
            </h1>
            <p class="mt-2 text-sm lg:text-base text-blue-200">{{ $nom }} - {{ $secteur }}</p>
        </div>
    </div>

    <!-- Production Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Daily Production -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-blue-600 text-white p-4">
                <h2 class="text-lg lg:text-xl font-bold flex items-center">
                    <i class="mdi mdi-calendar-today mr-2"></i>
                    {{ $isFrench ? 'Production Journalière' : 'Daily Production' }}
                </h2>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Production -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-blue-600 text-white p-4">
                <h2 class="text-lg lg:text-xl font-bold flex items-center">
                    <i class="mdi mdi-calendar-month mr-2"></i>
                    {{ $isFrench ? 'Production Mensuelle' : 'Monthly Production' }}
                </h2>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Yearly Production -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-600 text-white p-4">
            <h2 class="text-lg lg:text-xl font-bold flex items-center">
                <i class="mdi mdi-calendar mr-2"></i>
                {{ $isFrench ? 'Production Annuelle' : 'Yearly Production' }}
            </h2>
        </div>
        <div class="p-4">
            <div class="h-64 lg:h-96">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="bg-gray-50 p-4 lg:p-6 border-b border-gray-200">
            <h2 class="text-lg lg:text-xl font-bold text-gray-800">
                {{ $isFrench ? 'Détails des Produits' : 'Product Details' }}
            </h2>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden p-4 space-y-4">
            @foreach($stats['products'] as $product)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mobile-card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $product['produit']['nom'] }}</h3>
                            <button onclick="toggleMaterials('mobile-{{ $loop->index }}')" class="text-sm text-blue-600 hover:text-blue-800 mt-1">
                                {{ $isFrench ? 'Voir matières premières' : 'View raw materials' }}
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-blue-600">{{ $product['produit']['quantite_totale'] }}</span>
                            <p class="text-xs text-gray-500">{{ $isFrench ? 'unités' : 'units' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Revenu Total:' : 'Total Revenue:' }}</span>
                            <span class="text-sm font-medium text-green-600">{{ number_format($product['produit']['revenu_total']) }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Coût MP:' : 'RM Cost:' }}</span>
                            <span class="text-sm font-medium text-orange-600">{{ number_format($product['cout_total_mp']) }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-t border-gray-200 pt-2">
                            <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Bénéfice:' : 'Profit:' }}</span>
                            <span class="text-lg font-bold {{ $product['benefice'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($product['benefice']) }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Marge:' : 'Margin:' }}</span>
                            <span class="text-sm font-medium">{{ number_format($product['marge'], 1) }}%</span>
                        </div>
                    </div>

                    <!-- Materials Details (hidden by default) -->
                    <div id="mobile-materials-{{ $loop->index }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                        @foreach($product['matieres_premieres'] as $mp)
                        <div class="bg-white rounded-lg p-2 mb-2 border border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-800">{{ $mp['nom'] }}</span>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">{{ $mp['quantite_totale'] }} {{ $mp['unite'] }}</div>
                                    <div class="text-xs text-gray-500">{{ number_format($mp['cout_total']) }} FCFA</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Produit' : 'Product' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Revenu Total' : 'Total Revenue' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Coût MP' : 'RM Cost' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Marge (%)' : 'Margin (%)' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stats['products'] as $product)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $product['produit']['nom'] }}</div>
                            <div class="mt-1">
                                <button onclick="toggleMaterials('desktop-{{ $loop->index }}')" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                    {{ $isFrench ? 'Voir matières premières' : 'View raw materials' }}
                                </button>
                            </div>
                            <!-- Materials Details (hidden by default) -->
                            <div id="desktop-materials-{{ $loop->index }}" class="hidden mt-2 pl-4 border-l-2 border-gray-200">
                                @foreach($product['matieres_premieres'] as $mp)
                                <div class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">{{ $mp['nom'] }}:</span>
                                    {{ $mp['quantite_totale'] }} {{ $mp['unite'] }}
                                    ({{ number_format($mp['cout_total']) }} FCFA)
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $product['produit']['quantite_totale'] }}</td>
                        <td class="px-6 py-4">{{ number_format($product['produit']['revenu_total']) }} FCFA</td>
                        <td class="px-6 py-4">{{ number_format($product['cout_total_mp']) }} FCFA</td>
                        <td class="px-6 py-4 {{ $product['benefice'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($product['benefice']) }} FCFA
                        </td>
                        <td class="px-6 py-4">{{ number_format($product['marge'], 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, .mobile-card {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleMaterials(index) {
    const element = document.getElementById(`materials-${index}`);
    if (element) {
        element.classList.toggle('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const stats = @json($stats);
    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    // Helper function to create charts
    function createChart(elementId, labels, data, title, type = 'line') {
        const reversedLabels = labels.reverse();
        const reversedData = data.reverse();

        const ctx = document.getElementById(elementId).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: {
                labels: reversedLabels,
                datasets: [{
                    label: isFrench ? 'Quantité produite' : 'Quantity produced',
                    data: reversedData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Create charts
    const dailyTitle = isFrench ? 'Production des 7 derniers jours' : 'Production of the last 7 days';
    const monthlyTitle = isFrench ? 'Production des 12 derniers mois' : 'Production of the last 12 months';
    const yearlyTitle = isFrench ? 'Production des 5 dernières années' : 'Production of the last 5 years';

    createChart('dailyChart', stats.daily.labels, stats.daily.quantities, dailyTitle);
    createChart('monthlyChart', stats.monthly.labels, stats.monthly.quantities, monthlyTitle);
    createChart('yearlyChart', stats.yearly.labels, stats.yearly.quantities, yearlyTitle, 'bar');
});
</script>
@endsection
