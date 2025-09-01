@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Analyse Détaillée du Produit' : 'Detailed Product Analysis' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Insights approfondis et métriques de performance' : 'In-depth insights and performance metrics' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 space-y-6">
        <!-- General Statistics - Mobile Responsive -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-blue-500">
                <div class="flex flex-col items-center text-center">
                    <div class="p-2 md:p-3 bg-blue-100 rounded-full mb-2 md:mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider mb-1">
                        {{ $isFrench ? 'CA' : 'Revenue' }}
                    </h3>
                    <div class="text-base md:text-2xl font-bold text-blue-600">{{ number_format($stats['chiffre_affaire']) }} FCFA</div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-green-500">
                <div class="flex flex-col items-center text-center">
                    <div class="p-2 md:p-3 bg-green-100 rounded-full mb-2 md:mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider mb-1">
                        {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                    </h3>
                    <div class="text-base md:text-2xl font-bold {{ $stats['benefice'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['benefice']) }} FCFA
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-amber-500">
                <div class="flex flex-col items-center text-center">
                    <div class="p-2 md:p-3 bg-amber-100 rounded-full mb-2 md:mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider mb-1">
                        {{ $isFrench ? 'Vendues' : 'Sold' }}
                    </h3>
                    <div class="text-base md:text-2xl font-bold text-amber-600">{{ number_format($stats['quantite_vendue']) }}</div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-purple-500">
                <div class="flex flex-col items-center text-center">
                    <div class="p-2 md:p-3 bg-purple-100 rounded-full mb-2 md:mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider mb-1">
                        {{ $isFrench ? 'Marge' : 'Margin' }}
                    </h3>
                    <div class="text-base md:text-2xl font-bold {{ $stats['marge'] >= 30 ? 'text-green-600' : ($stats['marge'] >= 15 ? 'text-amber-600' : 'text-red-600') }}">
                        {{ number_format($stats['marge'], 1) }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Evolution Chart -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Évolution des Ventes' : 'Sales Evolution' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="h-64 md:h-80">
                    <canvas id="evolutionVentes"></canvas>
                </div>
            </div>
        </div>

        <!-- Producer Performance -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Performance par Producteur' : 'Performance by Producer' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                @if(count($producteurs) > 0)
                    <!-- Mobile Cards View -->
                    <div class="md:hidden space-y-4">
                        @foreach($producteurs as $producteur)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 bg-green-50">
                                <h3 class="font-semibold text-lg text-gray-800 mb-3">{{ $producteur['nom'] }}</h3>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="bg-white p-2 rounded">
                                        <span class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}:</span>
                                        <span class="font-medium block">{{ number_format($producteur['quantite']) }}</span>
                                    </div>
                                    <div class="bg-white p-2 rounded">
                                        <span class="text-gray-600">{{ $isFrench ? 'Coût' : 'Cost' }}:</span>
                                        <span class="font-medium block">{{ number_format($producteur['cout_moyen']) }} FCFA</span>
                                    </div>
                                    <div class="bg-white p-2 rounded col-span-2">
                                        <span class="text-gray-600">{{ $isFrench ? 'Bénéfice' : 'Profit' }}:</span>
                                        <span class="font-medium block text-green-600">{{ number_format($producteur['benefice']) }} FCFA</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Producteur' : 'Producer' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Quantité Produite' : 'Quantity Produced' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Coût Total' : 'Total Cost' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Bénéfice Généré' : 'Generated Profit' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($producteurs as $producteur)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $producteur['nom'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ number_format($producteur['quantite']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ number_format($producteur['cout_moyen']) }} FCFA</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600">{{ number_format($producteur['benefice']) }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>
                        </svg>
                        <p class="text-gray-600">{{ $isFrench ? 'Aucun producteur n\'a fabriqué ce produit' : 'No producer has manufactured this product' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Raw Materials and Recommendations -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Matières Premières et Recommandations' : 'Raw Materials and Recommendations' }}
                </h2>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                    <!-- Recommended Materials -->
                    <div>
                        <h3 class="text-lg md:text-xl font-semibold text-gray-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}
                        </h3>
                        @if(count($matieres_recommandees) > 0)
                            <!-- Mobile Cards View -->
                            <div class="md:hidden space-y-3">
                                @foreach($matieres_recommandees as $matiere)
                                    <div class="border border-purple-200 rounded-lg p-3 bg-purple-50">
                                        <h4 class="font-semibold text-gray-800">{{ $matiere->matiere->nom }}</h4>
                                        <div class="flex justify-between mt-2 text-sm">
                                            <span class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}:</span>
                                            <span class="font-medium">{{ number_format($matiere->quantite, 3) }} {{ $matiere->unite }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Matière' : 'Material' }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ $isFrench ? 'Unité' : 'Unit' }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($matieres_recommandees as $matiere)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $matiere->matiere->nom }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ number_format($matiere->quantite, 3) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $matiere->unite }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 border border-gray-200 rounded-lg bg-gray-50">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-600">{{ $isFrench ? 'Aucune matière première recommandée' : 'No recommended raw materials' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ventesData = @json($evolution_ventes);
        const isFrench = @json($isFrench ?? true);
        
        const ctx = document.getElementById('evolutionVentes').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: isFrench ? 'Quantité vendue' : 'Quantity sold',
                        data: ventesData.map(d => ({
                            x: d.date,
                            y: d.quantite
                        })),
                        borderColor: '#3B82F6',
                        backgroundColor: '#3B82F620',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: isFrench ? 'Chiffre d\'affaires' : 'Revenue',
                        data: ventesData.map(d => ({
                            x: d.date,
                            y: d.chiffre_affaire
                        })),
                        borderColor: '#10B981',
                        backgroundColor: '#10B98120',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 0) {
                                        label += new Intl.NumberFormat().format(context.parsed.y) + (isFrench ? ' unités' : ' units');
                                    } else {
                                        label += new Intl.NumberFormat().format(context.parsed.y) + ' FCFA';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: isFrench ? 'Quantité' : 'Quantity'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat().format(value);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: isFrench ? 'Chiffre d\'affaires (FCFA)' : 'Revenue (FCFA)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat().format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
