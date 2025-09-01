@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-blue-50 to-blue-100">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-3 md:space-y-0">
                <div class="flex-1">
                    <h1 class="text-xl md:text-3xl font-bold text-white leading-tight">
                        {{ $isFrench ? 'Détails du Gaspillage - Matière' : 'Waste Details - Material' }}
                    </h1>
                    <p class="text-blue-100 mt-1 md:mt-2 text-sm md:text-base">
                        {{ $isFrench ? 'Matière' : 'Material' }}: {{ $matiere->nom }} 
                        ({{ $isFrench ? 'Unité' : 'Unit' }}: {{ $matiere->unite_minimale }})
                    </p>
                </div>
                <div class="flex justify-end">
                    @include('buttons')
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-3 md:px-4 py-4 md:py-8 space-y-4 md:space-y-6">
        <!-- Mobile Cards Stack / Desktop Grid -->
        <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-1 lg:grid-cols-2 md:gap-6">
            <!-- Material Information Card -->
            <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Informations Matière' : 'Material Information' }}
                    </h3>
                </div>
                <div class="space-y-3 md:space-y-2">
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Nom' : 'Name' }}:</span>
                        <span class="font-medium text-sm md:text-base">{{ $matiere->nom }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Unité minimale' : 'Minimum unit' }}:</span>
                        <span class="font-medium text-sm md:text-base">{{ $matiere->unite_minimale }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Unité classique' : 'Standard unit' }}:</span>
                        <span class="font-medium text-sm md:text-base">{{ $matiere->unite_classique }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Prix par unité minimale' : 'Price per minimum unit' }}:</span>
                        <span class="font-medium text-green-600 text-sm md:text-base">{{ number_format($matiere->prix_par_unite_minimale, 2, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Quantité en stock' : 'Stock quantity' }}:</span>
                        <span class="font-medium text-blue-600 text-sm md:text-base">{{ number_format($matiere->quantite, 2, ',', ' ') }} {{ $matiere->unite_minimale }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Waste Summary Card -->
            <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2 h-8 bg-red-500 rounded-full md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Résumé du Gaspillage' : 'Waste Summary' }}
                    </h3>
                </div>
                <div class="space-y-3 md:space-y-2">
                    @php
                        $nbProductions = count($detailsGaspillage);
                        $totalGaspillage = collect($detailsGaspillage)->sum('valeur_gaspillage');
                        $totalQuantiteGaspillee = collect($detailsGaspillage)->sum('quantite_gaspillee');
                        $pourcentageMoyenGaspillage = collect($detailsGaspillage)->avg('pourcentage_gaspillage');
                    @endphp
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Nombre de productions analysées' : 'Number of productions analyzed' }}:</span>
                        <span class="font-medium text-sm md:text-base">{{ $nbProductions }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Valeur totale gaspillée' : 'Total waste value' }}:</span>
                        <span class="font-medium text-red-600 text-sm md:text-base">{{ number_format($totalGaspillage, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0 border-b border-gray-100 md:border-none">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Quantité totale gaspillée' : 'Total quantity wasted' }}:</span>
                        <span class="font-medium text-red-600 text-sm md:text-base">{{ number_format($totalQuantiteGaspillee, 3, ',', ' ') }} {{ $matiere->unite_minimale }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 md:py-0">
                        <span class="text-gray-600 text-sm md:text-base">{{ $isFrench ? 'Pourcentage moyen gaspillé' : 'Average waste percentage' }}:</span>
                        <span class="font-medium text-orange-600 text-sm md:text-base">{{ number_format($pourcentageMoyenGaspillage, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section - Mobile Stack / Desktop Grid -->
        <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-1 lg:grid-cols-2 md:gap-6">
            <!-- Evolution Chart -->
            <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2 h-8 bg-purple-500 rounded-full md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Évolution du gaspillage dans le temps' : 'Waste evolution over time' }}
                    </h3>
                </div>
                <div class="h-64 md:h-80">
                    <canvas id="evolutionGaspillageChart"></canvas>
                </div>
            </div>
            
            <!-- Top Products Chart -->
            <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2 h-8 bg-green-500 rounded-full md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Top produits associés au gaspillage' : 'Top products associated with waste' }}
                    </h3>
                </div>
                <div class="h-64 md:h-80">
                    <canvas id="topProduitsGaspillageChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Detailed Table Section -->
        <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-2 h-8 bg-indigo-500 rounded-full md:hidden"></div>
                <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                    {{ $isFrench ? 'Détails du gaspillage par production' : 'Waste details by production' }}
                </h3>
            </div>
            
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3">
                @foreach($detailsGaspillage as $detail)
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500 transform transition-all duration-200 hover:bg-gray-100 active:scale-95">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">{{ $detail->nom_produit }}</h4>
                            <p class="text-xs text-gray-600">{{ $isFrench ? 'Lot' : 'Batch' }}: {{ $detail->id_lot }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $detail->pourcentage_gaspillage > 20 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ number_format($detail->pourcentage_gaspillage, 1) }}%
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <p class="text-gray-600">{{ $isFrench ? 'Date' : 'Date' }}</p>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($detail->date_production)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ $isFrench ? 'Utilisée' : 'Used' }}</p>
                            <p class="font-medium text-gray-900">{{ number_format($detail->quantite_utilisee, 2, ',', ' ') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ $isFrench ? 'Recommandée' : 'Recommended' }}</p>
                            <p class="font-medium text-gray-900">{{ number_format($detail->quantite_recommandee, 2, ',', ' ') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ $isFrench ? 'Gaspillage' : 'Waste' }}</p>
                            <p class="font-medium text-red-600">{{ number_format($detail->quantite_gaspillee, 2, ',', ' ') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-sm font-medium text-red-600">
                            {{ number_format($detail->valeur_gaspillage, 0, ',', ' ') }} FCFA
                        </span>
                        <a href="{{ route('gaspillage.details-production', $detail->id_lot) }}" 
                           class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium transform transition-all duration-200 hover:bg-blue-600 active:scale-95">
                            {{ $isFrench ? 'Détails' : 'Details' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View -->
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
                                {{ $isFrench ? 'Date Production' : 'Production Date' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité utilisée' : 'Quantity used' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité recommandée' : 'Recommended quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Écart (gaspillage)' : 'Gap (waste)' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? '% Gaspillage' : '% Waste' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Valeur gaspillée' : 'Wasted value' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailsGaspillage as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detail->id_lot }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detail->nom_produit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($detail->date_production)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($detail->quantite_utilisee, 3, ',', ' ') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($detail->quantite_recommandee, 3, ',', ' ') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                {{ number_format($detail->quantite_gaspillee, 3, ',', ' ') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $detail->pourcentage_gaspillage > 20 ? 'text-red-600' : 'text-orange-600' }}">
                                {{ number_format($detail->pourcentage_gaspillage, 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                {{ number_format($detail->valeur_gaspillage, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('gaspillage.details-production', $detail->id_lot) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $isFrench ? 'Détails' : 'Details' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recommendations Section -->
        <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl md:hover:shadow-lg">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-2 h-8 bg-yellow-500 rounded-full md:hidden"></div>
                <h3 class="text-lg md:text-lg font-semibold text-gray-800">
                    {{ $isFrench ? 'Recommandations pour réduire le gaspillage' : 'Recommendations to reduce waste' }}
                </h3>
            </div>
            
            <div class="space-y-3 md:space-y-4">
                <div class="p-3 md:p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-md md:rounded-md transform transition-all duration-200 hover:bg-yellow-100">
                    <h4 class="text-yellow-800 font-medium mb-1 text-sm md:text-base">
                        {{ $isFrench ? 'Attention particulière' : 'Special attention' }}
                    </h4>
                    <p class="text-xs md:text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Cette matière présente un taux de gaspillage de ' . number_format($pourcentageMoyenGaspillage, 1) . '%. Soyez particulièrement vigilant lors de son utilisation.'
                            : 'This material has a waste rate of ' . number_format($pourcentageMoyenGaspillage, 1) . '%. Be particularly careful when using it.'
                        }}
                    </p>
                </div>
                
                <div class="p-3 md:p-4 bg-blue-50 border-l-4 border-blue-500 rounded-md md:rounded-md transform transition-all duration-200 hover:bg-blue-100">
                    <h4 class="text-blue-800 font-medium mb-1 text-sm md:text-base">
                        {{ $isFrench ? 'Stockage et conservation' : 'Storage and conservation' }}
                    </h4>
                    <p class="text-xs md:text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Vérifiez les conditions de stockage de cette matière. Une conservation inadéquate peut entraîner un gaspillage plus important.'
                            : 'Check the storage conditions of this material. Inadequate conservation can lead to greater waste.'
                        }}
                    </p>
                </div>
                
                <div class="p-3 md:p-4 bg-green-50 border-l-4 border-green-500 rounded-md md:rounded-md transform transition-all duration-200 hover:bg-green-100">
                    <h4 class="text-green-800 font-medium mb-1 text-sm md:text-base">
                        {{ $isFrench ? 'Optimisation des mesures' : 'Measurement optimization' }}
                    </h4>
                    <p class="text-xs md:text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Utilisez des outils de mesure précis et adaptés spécifiquement à cette matière pour limiter les erreurs de dosage.'
                            : 'Use precise measuring tools specifically adapted to this material to limit dosing errors.'
                        }}
                    </p>
                </div>
                
                @if($totalQuantiteGaspillee > $matiere->quantite * 0.1)
                <div class="p-3 md:p-4 bg-red-50 border-l-4 border-red-500 rounded-md md:rounded-md transform transition-all duration-200 hover:bg-red-100">
                    <h4 class="text-red-800 font-medium mb-1 text-sm md:text-base">
                        {{ $isFrench ? 'Impact économique significatif' : 'Significant economic impact' }}
                    </h4>
                    <p class="text-xs md:text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Le gaspillage de cette matière représente une valeur de ' . number_format($totalGaspillage, 0, ',', ' ') . ' FCFA, soit l\'équivalent de ' . number_format(($totalGaspillage / ($matiere->prix_unitaire ?: 1)) / $matiere->quantite_par_unite, 1, ',', ' ') . ' unités d\'achat.'
                            : 'The waste of this material represents a value of ' . number_format($totalGaspillage, 0, ',', ' ') . ' FCFA, equivalent to ' . number_format(($totalGaspillage / ($matiere->prix_unitaire ?: 1)) / $matiere->quantite_par_unite, 1, ',', ' ') . ' purchase units.'
                        }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart data
        const detailsGaspillage = @json($detailsGaspillage);
        const isFrench = @json($isFrench);
        
        // Sort data by date for evolution chart
        const sortedData = [...detailsGaspillage].sort((a, b) => new Date(a.date_production) - new Date(b.date_production));
        
        // Group data by production date
        const groupedByDate = {};
        sortedData.forEach(item => {
            const date = new Date(item.date_production).toLocaleDateString('fr-FR');
            if (!groupedByDate[date]) {
                groupedByDate[date] = {
                    date,
                    valeur_gaspillage: 0,
                    quantite_gaspillee: 0
                };
            }
            groupedByDate[date].valeur_gaspillage += parseFloat(item.valeur_gaspillage);
            groupedByDate[date].quantite_gaspillee += parseFloat(item.quantite_gaspillee);
        });
        
        // Convert to array
        const evolutionData = Object.values(groupedByDate);
        
        // Evolution chart
        new Chart(document.getElementById('evolutionGaspillageChart'), {
            type: 'line',
            data: {
                labels: evolutionData.map(item => item.date),
                datasets: [{
                    label: isFrench ? 'Valeur du gaspillage (FCFA)' : 'Waste value (FCFA)',
                    data: evolutionData.map(item => item.valeur_gaspillage),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: isFrench ? 'Quantité gaspillée ({{ $matiere->unite_minimale }})' : 'Quantity wasted ({{ $matiere->unite_minimale }})',
                    data: evolutionData.map(item => item.quantite_gaspillee),
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
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: isFrench ? 'Valeur (FCFA)' : 'Value (FCFA)'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: isFrench ? 'Quantité ({{ $matiere->unite_minimale }})' : 'Quantity ({{ $matiere->unite_minimale }})'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        
        // Group data by product for top products chart
        const groupedByProduit = {};
        detailsGaspillage.forEach(item => {
            if (!groupedByProduit[item.nom_produit]) {
                groupedByProduit[item.nom_produit] = {
                    nom_produit: item.nom_produit,
                    valeur_gaspillage: 0
                };
            }
            groupedByProduit[item.nom_produit].valeur_gaspillage += parseFloat(item.valeur_gaspillage);
        });
        
        // Convert to array and sort by descending value
        const produitData = Object.values(groupedByProduit).sort((a, b) => b.valeur_gaspillage - a.valeur_gaspillage);
        
        // Top products chart
        new Chart(document.getElementById('topProduitsGaspillageChart'), {
            type: 'bar',
            data: {
                labels: produitData.map(item => item.nom_produit),
                datasets: [{
                    label: isFrench ? 'Valeur du gaspillage (FCFA)' : 'Waste value (FCFA)',
                    data: produitData.map(item => item.valeur_gaspillage),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: isFrench ? 'Valeur (FCFA)' : 'Value (FCFA)'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection