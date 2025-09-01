@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header responsive -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between flex-wrap gap-4">
            </div>
            @include('buttons')

            <div class="space-y-3 mt-4">
                <h1 class="text-2xl md:text-3xl font-bold text-white">
                    {{ $isFrench ? 'Analyse du Gaspillage' : 'Waste Analysis' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Analyse détaillée des écarts entre les matières recommandées et utilisées' : 'Detailed analysis of gaps between recommended and used materials' }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-8 space-y-6">
        <!-- Global Statistics - Mobile responsive cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Productions avec gaspillage' : 'Productions with waste' }}
                    </h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-blue-600 mb-2">
                    {{ number_format($statsGlobales['nbProductionsAvecGaspillage']) }}
                </p>
                <p class="text-xs md:text-sm text-gray-500">
                    {{ $isFrench ? 'Productions avec surplus de matières' : 'Productions with excess materials' }}
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Valeur totale gaspillée' : 'Total wasted value' }}
                    </h3>
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-red-600 mb-2">
                    {{ number_format($statsGlobales['valeurTotaleGaspillage'], 0, ',', ' ') }} FCFA
                </p>
                <p class="text-xs md:text-sm text-gray-500">
                    {{ $isFrench ? 'Coût total des matières gaspillées' : 'Total cost of wasted materials' }}
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Gaspillage moyen' : 'Average waste' }}
                    </h3>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 6a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM14 6a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zM3 14a1 1 0 011-1h2a1 1 0 110 2H4a1 1 0 01-1-1zM8 14a1 1 0 011-1h8a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-orange-600 mb-2">
                    {{ number_format($statsGlobales['pourcentageMoyenGaspillage'], 1) }}%
                </p>
                <p class="text-xs md:text-sm text-gray-500">
                    {{ $isFrench ? 'Pourcentage moyen de gaspillage par production' : 'Average waste percentage per production' }}
                </p>
            </div>
        </div>
        
        <!-- Waste Evolution Chart - Mobile responsive -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Évolution du gaspillage sur 30 jours' : '30-day waste evolution' }}
            </h3>
            <div class="h-64 md:h-80">
                <canvas id="evolutionGaspillageChart" class="w-full h-full"></canvas>
            </div>
        </div>
        
        <!-- Recent Productions with Waste - Mobile responsive table -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                    {{ $isFrench ? 'Productions récentes avec gaspillage' : 'Recent productions with waste' }}
                </h3>
               
            </div>
            
            <!-- Mobile cards for small screens -->
            <div class="block md:hidden space-y-4">
                @foreach($topProductionsGaspillage as $production)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $isFrench ? 'ID Lot:' : 'Batch ID:' }} {{ $production->id_lot }}</p>
                                <p class="text-sm text-gray-600">{{ $production->nom_produit }}</p>
                            </div>
                            <span class="text-red-600 font-semibold text-sm">
                                {{ number_format($production->valeur_gaspillage, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">{{ \Carbon\Carbon::parse($production->date_production)->format('d/m/Y H:i') }}</span>
                            <a href="{{ route('gaspillage.details-production', $production->id_lot) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                {{ $isFrench ? 'Détails' : 'Details' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'ID Lot' : 'Batch ID' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Valeur Gaspillée' : 'Wasted Value' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topProductionsGaspillage as $production)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $production->id_lot }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $production->nom_produit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($production->date_production)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">
                                {{ number_format($production->valeur_gaspillage, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('gaspillage.details-production', $production->id_lot) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    {{ $isFrench ? 'Détails' : 'Details' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Top Products and Materials - Mobile responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Products -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Top produits - gaspillage' : 'Top products - waste' }}
                </h3>
                
                <div class="space-y-4">
                    @foreach($topProduitsGaspillage as $index => $produit)
                        <div class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-bold mr-3 text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                                    <a href="{{ route('gaspillage.details-produit', $produit->code_produit) }}" 
                                       class="text-gray-800 font-medium hover:text-blue-600 transition-colors duration-200 text-sm md:text-base truncate">
                                        {{ $produit->nom_produit }}
                                    </a>
                                    <span class="text-red-600 font-semibold text-sm md:text-base flex-shrink-0">
                                        {{ number_format($produit->valeur_gaspillage, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-400 to-red-500 transition-all duration-1000 ease-out" 
                                         style="width: {{ min(100, $index == 0 ? 100 : ($produit->valeur_gaspillage / $topProduitsGaspillage[0]->valeur_gaspillage) * 100) }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $produit->nb_productions }} {{ $isFrench ? 'productions' : 'productions' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Top Materials -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Top matières - gaspillage' : 'Top materials - waste' }}
                </h3>
                
                <div class="space-y-4">
                    @foreach($topMatieresGaspillage as $index => $matiere)
                        <div class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-800 flex items-center justify-center font-bold mr-3 text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                                    <a href="{{ route('gaspillage.details-matiere', $matiere->id) }}" 
                                       class="text-gray-800 font-medium hover:text-blue-600 transition-colors duration-200 text-sm md:text-base truncate">
                                        {{ $matiere->nom_matiere }}
                                    </a>
                                    <span class="text-red-600 font-semibold text-sm md:text-base flex-shrink-0">
                                        {{ number_format($matiere->valeur_gaspillage, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-400 to-red-500 transition-all duration-1000 ease-out" 
                                         style="width: {{ min(100, $index == 0 ? 100 : ($matiere->valeur_gaspillage / $topMatieresGaspillage[0]->valeur_gaspillage) * 100) }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ number_format($matiere->quantite_gaspillage, 2) }} {{ $matiere->unite_minimale }}</span>
                                    <span>{{ $matiere->nb_productions }} {{ $isFrench ? 'productions' : 'productions' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Materials Waste Chart -->
                <div class="mt-6">
                    <h4 class="text-base font-semibold text-gray-700 mb-3">
                        {{ $isFrench ? 'Répartition du gaspillage par matière' : 'Waste distribution by material' }}
                    </h4>
                    <div class="h-48 md:h-64">
                        <canvas id="materielGaspillageChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour le graphique d'évolution du gaspillage
        const evolutionData = @json($evolutionGaspillage);
        
        // Données pour le graphique de répartition des matières
        const materielData = @json($topMatieresGaspillage);
        
        // Graphique d'évolution du gaspillage
        new Chart(document.getElementById('evolutionGaspillageChart'), {
            type: 'line',
            data: {
                labels: evolutionData.map(item => new Date(item.date).toLocaleDateString('fr-FR')),
                datasets: [{
                    label: '{{ $isFrench ? "Valeur du gaspillage (FCFA)" : "Waste value (FCFA)" }}',
                    data: evolutionData.map(item => item.valeur_gaspillage),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true
                }, {
                    label: '{{ $isFrench ? "Nombre de productions" : "Number of productions" }}',
                    data: evolutionData.map(item => item.nb_productions),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ $isFrench ? "Valeur (FCFA)" : "Value (FCFA)" }}'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: '{{ $isFrench ? "Nombre de productions" : "Number of productions" }}'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
        
        // Graphique de répartition des matières
        new Chart(document.getElementById('materielGaspillageChart'), {
            type: 'doughnut',
            data: {
                labels: materielData.map(item => item.nom_matiere),
                datasets: [{
                    data: materielData.map(item => item.valeur_gaspillage),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(139, 92, 246)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                return label + ': ' + new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
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
