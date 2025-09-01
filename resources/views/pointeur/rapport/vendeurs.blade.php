@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
 

    <!-- Desktop Header -->
    <div class="hidden md:block container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                @include('buttons')
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $isFrench ? 'Rapport des vendeurs' : 'Sales Report' }}
                </h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('pointeur.workspace') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition-all duration-200 transform hover:scale-105">
                    {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                </a>
                <a href="{{ route('pointeur.assignation.liste') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    {{ $isFrench ? 'Liste des assignations' : 'Assignment List' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Pills -->
    <div class="md:hidden px-4 pb-4">
        <div class="flex space-x-2 overflow-x-auto">
            <a href="{{ route('pointeur.workspace') }}" class="flex-shrink-0 bg-gray-100 text-gray-700 py-2 px-4 rounded-full text-sm font-medium transition-all duration-200 active:scale-95">
                {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
            </a>
            <a href="{{ route('pointeur.assignation.liste') }}" class="flex-shrink-0 bg-blue-600 text-white py-2 px-4 rounded-full text-sm font-medium transition-all duration-200 active:scale-95 shadow-lg">
                {{ $isFrench ? 'Assignations' : 'Assignments' }}
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-8">
        <!-- Filtres de date -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden">
            <!-- Mobile Filter Header -->
            <div class="md:hidden bg-blue-50 px-4 py-3 border-b">
                <h3 class="text-sm font-semibold text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ $isFrench ? 'Filtrer par période' : 'Filter by Period' }}
                </h3>
            </div>

            <form action="{{ route('pointeur.rapport.vendeurs') }}" method="GET" class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">
                            {{ $isFrench ? 'Date début' : 'Start Date' }}
                        </label>
                        <div class="relative">
                            <input type="date" id="date_debut" name="date_debut" value="{{ $dateDebut }}" 
                                class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pl-4 pr-4 py-3 md:py-2">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none md:hidden">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">
                            {{ $isFrench ? 'Date fin' : 'End Date' }}
                        </label>
                        <div class="relative">
                            <input type="date" id="date_fin" name="date_fin" value="{{ $dateFin }}" 
                                class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pl-4 pr-4 py-3 md:py-2">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none md:hidden">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 md:py-2 px-6 rounded-xl hover:bg-blue-700 transition-all duration-200 transform active:scale-95 md:hover:scale-105 shadow-lg font-medium">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                {{ $isFrench ? 'Filtrer' : 'Filter' }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Rapport détaillé -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="p-4 md:p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg md:text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Performances des vendeurs' : 'Sales Performance' }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $isFrench ? 'Période' : 'Period' }}: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} {{ $isFrench ? 'au' : 'to' }} {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                </p>
            </div>

            <!-- Mobile Cards View -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse ($rapportVendeurs as $rapport)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $rapport->vendeur->name }}</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full {{ $rapport->taux_vente >= 80 ? 'bg-green-500' : ($rapport->taux_vente >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} animate-pulse"></div>
                                <span class="text-sm font-bold {{ $rapport->taux_vente >= 80 ? 'text-green-600' : ($rapport->taux_vente >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $rapport->taux_vente }}%
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs text-blue-600 font-medium mb-1">{{ $isFrench ? 'Reçus' : 'Received' }}</div>
                                <div class="text-lg font-bold text-blue-800">{{ $rapport->quantite_confirmee_total + $rapport->quantite_en_attente }}</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3">
                                <div class="text-xs text-green-600 font-medium mb-1">{{ $isFrench ? 'Vendus' : 'Sold' }}</div>
                                <div class="text-lg font-bold text-green-800">{{ $rapport->quantite_vendue }}</div>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <div class="text-xs text-yellow-600 font-medium mb-1">{{ $isFrench ? 'Invendus' : 'Unsold' }}</div>
                                <div class="text-lg font-bold text-yellow-800">{{ $rapport->quantite_invendue }}</div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3">
                                <div class="text-xs text-red-600 font-medium mb-1">{{ $isFrench ? 'Avariés' : 'Damaged' }}</div>
                                <div class="text-lg font-bold text-red-800">{{ $rapport->quantite_avariee }}</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <div class="text-xs text-gray-600 font-medium mb-1">{{ $isFrench ? 'Montant vendu' : 'Sales Amount' }}</div>
                            <div class="text-xl font-bold text-gray-900">{{ number_format($rapport->montant_vendu, 0, ',', ' ') }} FCFA</div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Taux de vente' : 'Sales Rate' }}</span>
                                <span class="text-sm font-bold {{ $rapport->taux_vente >= 80 ? 'text-green-600' : ($rapport->taux_vente >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $rapport->taux_vente }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full transition-all duration-1000 ease-out {{ $rapport->taux_vente >= 80 ? 'bg-gradient-to-r from-green-400 to-green-600' : ($rapport->taux_vente >= 50 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 'bg-gradient-to-r from-red-400 to-red-600') }}" 
                                     style="width: {{ $rapport->taux_vente }}%;"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">
                            {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
                        </p>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $isFrench ? 'pour cette période' : 'for this period' }}
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Vendeur' : 'Seller' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Produits reçus' : 'Products Received' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité vendue' : 'Quantity Sold' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Montant vendu' : 'Amount Sold' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Invendus' : 'Unsold' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Avariés' : 'Damaged' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Taux vente' : 'Sales Rate' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($rapportVendeurs as $rapport)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }} hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $rapport->vendeur->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rapport->quantite_confirmee_total + $rapport->quantite_en_attente }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rapport->quantite_vendue }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    {{ number_format($rapport->montant_vendu, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $rapport->quantite_invendue }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $rapport->quantite_avariee }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="mr-2 text-sm font-medium {{ $rapport->taux_vente >= 80 ? 'text-green-600' : ($rapport->taux_vente >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $rapport->taux_vente }}%
                                        </div>
                                        <div class="relative w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="absolute h-2 rounded-full transition-all duration-1000 ease-out {{ $rapport->taux_vente >= 80 ? 'bg-green-500' : ($rapport->taux_vente >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $rapport->taux_vente }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">
                                        {{ $isFrench ? 'Aucune donnée disponible pour cette période' : 'No data available for this period' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 md:p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        {{ $isFrench ? 'Répartition des ventes' : 'Sales Distribution' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="aspect-w-16 aspect-h-9 h-64 md:h-80">
                        <canvas id="ventesChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 md:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        {{ $isFrench ? 'Performance par vendeur' : 'Performance by Seller' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="aspect-w-16 aspect-h-9 h-64 md:h-80">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Safe Area -->
<div class="md:hidden h-6 bg-gray-50"></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour les graphiques
        const vendeurs = @json($rapportVendeurs->pluck('vendeur.name'));
        const ventesData = @json($rapportVendeurs->pluck('quantite_vendue'));
        const invendusData = @json($rapportVendeurs->pluck('quantite_invendue'));
        const avariesData = @json($rapportVendeurs->pluck('quantite_avariee'));
        const montantData = @json($rapportVendeurs->pluck('montant_vendu'));
        const isFrench = @json($isFrench ?? true);
        
        // Configuration responsive pour mobile
        const isMobile = window.innerWidth < 768;
        
        // Graphique de répartition des ventes
        const ctxVentes = document.getElementById('ventesChart').getContext('2d');
        new Chart(ctxVentes, {
            type: 'bar',
            data: {
                labels: vendeurs,
                datasets: [
                    {
                        label: isFrench ? 'Vendus' : 'Sold',
                        data: ventesData,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 2,
                        borderRadius: isMobile ? 6 : 4,
                        borderSkipped: false,
                    },
                    {
                        label: isFrench ? 'Invendus' : 'Unsold',
                        data: invendusData,
                        backgroundColor: 'rgba(234, 179, 8, 0.8)',
                        borderColor: 'rgb(234, 179, 8)',
                        borderWidth: 2,
                        borderRadius: isMobile ? 6 : 4,
                        borderSkipped: false,
                    },
                    {
                        label: isFrench ? 'Avariés' : 'Damaged',
                        data: avariesData,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 2,
                        borderRadius: isMobile ? 6 : 4,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'top',
                        labels: {
                            usePointStyle: true,
                            padding: isMobile ? 15 : 20,
                            font: {
                                size: isMobile ? 11 : 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: isMobile ? 10 : 11
                            },
                            maxRotation: isMobile ? 45 : 0
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: isMobile ? 10 : 11
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
        
        // Graphique de performance (montant)
        const ctxPerformance = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctxPerformance, {
            type: 'bar',
            data: {
                labels: vendeurs,
                datasets: [{
                    label: isFrench ? 'Montant des ventes (FCFA)' : 'Sales Amount (FCFA)',
                    data: montantData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    borderRadius: isMobile ? 6 : 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'top',
                        labels: {
                            usePointStyle: true,
                            padding: isMobile ? 15 : 20,
                            font: {
                                size: isMobile ? 11 : 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + 
                                       new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: isMobile ? 10 : 11
                            },
                            maxRotation: isMobile ? 45 : 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: isMobile ? 10 : 11
                            },
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Animation d'entrée pour les cartes mobiles
        if (isMobile) {
            const cards = document.querySelectorAll('.md\\:hidden .p-4');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Animation des barres de progression
        const progressBars = document.querySelectorAll('[style*="width:"]');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.transition = 'width 1s ease-out';
                bar.style.width = width;
            }, 500);
        });

        // Gestion du swipe pour les graphiques sur mobile
        if (isMobile) {
            let startX, startY, distX, distY;
            const charts = document.querySelectorAll('canvas');
            
            charts.forEach(chart => {
                chart.addEventListener('touchstart', e => {
                    const touch = e.touches[0];
                    startX = touch.clientX;
                    startY = touch.clientY;
                });
                
                chart.addEventListener('touchmove', e => {
                    e.preventDefault();
                });
            });
        }
    });

    // Gestion du redimensionnement
    window.addEventListener('resize', function() {
        // Recréer les graphiques avec les nouvelles dimensions
        Chart.helpers.each(Chart.instances, function(instance) {
            instance.resize();
        });
    });

    // Animation au scroll pour les éléments
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer les éléments à animer
    document.querySelectorAll('.bg-white').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
</script>
@endsection