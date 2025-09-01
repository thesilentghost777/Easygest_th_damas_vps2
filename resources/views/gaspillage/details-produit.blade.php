@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header responsive -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white">
                    {{ $isFrench ? 'Détails du Gaspillage - Produit' : 'Waste Details - Product' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Produit:' : 'Product:' }} {{ $produit->nom }} 
                    ({{ $isFrench ? 'Code:' : 'Code:' }} {{ $produit->code_produit }})
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6 md:py-8 space-y-6">
        <!-- Product Information - Mobile responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Informations Produit' : 'Product Information' }}
                </h3>
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium">{{ $isFrench ? 'Nom:' : 'Name:' }}</span>
                        <span class="font-semibold text-gray-900">{{ $produit->nom }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium">{{ $isFrench ? 'Code:' : 'Code:' }}</span>
                        <span class="font-semibold text-gray-900">{{ $produit->code_produit }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</span>
                        <span class="font-semibold text-gray-900">{{ $produit->categorie }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium">{{ $isFrench ? 'Prix:' : 'Price:' }}</span>
                        <span class="font-semibold text-gray-900">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Résumé du Gaspillage' : 'Waste Summary' }}
                </h3>
                <div class="space-y-3">
                    @php
                        $nbProductions = count($detailsGaspillage);
                        $totalGaspillage = collect($detailsGaspillage)->sum('valeur_gaspillage');
                        $totalQuantiteGaspillee = collect($detailsGaspillage)->sum('quantite_gaspillee');
                        $pourcentageMoyenGaspillage = collect($detailsGaspillage)->avg('pourcentage_gaspillage');
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Nombre de productions analysées:' : 'Number of analyzed productions:' }}
                        </span>
                        <span class="font-semibold text-gray-900">{{ $nbProductions }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Valeur totale gaspillée:' : 'Total wasted value:' }}
                        </span>
                        <span class="font-semibold text-red-600">{{ number_format($totalGaspillage, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Coût moyen gaspillé par production:' : 'Average waste cost per production:' }}
                        </span>
                        <span class="font-semibold text-red-600">{{ number_format($nbProductions > 0 ? $totalGaspillage / $nbProductions : 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <span class="text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Pourcentage moyen gaspillé:' : 'Average waste percentage:' }}
                        </span>
                        <span class="font-semibold text-orange-600">{{ number_format($pourcentageMoyenGaspillage, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Waste Evolution Chart - Mobile responsive -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Évolution du gaspillage dans le temps' : 'Waste evolution over time' }}
            </h3>
            <div class="h-64 md:h-80">
                <canvas id="evolutionGaspillageChart" class="w-full h-full"></canvas>
            </div>
        </div>
        
        <!-- Detailed Waste Table - Mobile responsive -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Détails du gaspillage par production' : 'Waste details by production' }}
            </h3>
            
            <!-- Mobile cards -->
            <div class="block lg:hidden space-y-4">
                @foreach($detailsGaspillage as $detail)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $isFrench ? 'ID Lot:' : 'Batch ID:' }} {{ $detail->id_lot }}</p>
                                    <p class="text-sm text-gray-600">{{ $detail->nom_matiere }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($detail->date_production)->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="text-red-600 font-semibold text-sm">
                                    {{ number_format($detail->valeur_gaspillage, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">{{ $isFrench ? 'Utilisée:' : 'Used:' }}</p>
                                    <p class="font-medium">{{ number_format($detail->quantite_utilisee, 3, ',', ' ') }} {{ $detail->unite_minimale }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">{{ $isFrench ? 'Recommandée:' : 'Recommended:' }}</p>
                                    <p class="font-medium">{{ number_format($detail->quantite_recommandee, 3, ',', ' ') }} {{ $detail->unite_minimale }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">{{ $isFrench ? 'Gaspillage:' : 'Waste:' }}</p>
                                    <p class="font-medium text-red-600">{{ number_format($detail->quantite_gaspillee, 3, ',', ' ') }} {{ $detail->unite_minimale }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">{{ $isFrench ? '% Gaspillage:' : '% Waste:' }}</p>
                                    <p class="font-medium {{ $detail->pourcentage_gaspillage > 20 ? 'text-red-600' : 'text-orange-600' }}">
                                        {{ number_format($detail->pourcentage_gaspillage, 1) }}%
                                    </p>
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-gray-100">
                                <a href="{{ route('gaspillage.details-production', $detail->id_lot) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                    {{ $isFrench ? 'Voir détails →' : 'View details →' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'ID Lot' : 'Batch ID' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Date Production' : 'Production Date' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité utilisée' : 'Used quantity' }}
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
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailsGaspillage as $detail)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detail->id_lot }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $detail->nom_matiere }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($detail->date_production)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($detail->quantite_utilisee, 3, ',', ' ') }} {{ $detail->unite_minimale }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($detail->quantite_recommandee, 3, ',', ' ') }} {{ $detail->unite_minimale }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                {{ number_format($detail->quantite_gaspillee, 3, ',', ' ') }} {{ $detail->unite_minimale }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $detail->pourcentage_gaspillage > 20 ? 'text-red-600' : 'text-orange-600' }}">
                                {{ number_format($detail->pourcentage_gaspillage, 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                {{ number_format($detail->valeur_gaspillage, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('gaspillage.details-production', $detail->id_lot) }}" 
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
        
        <!-- Recommendations - Mobile responsive -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Recommandations pour réduire le gaspillage' : 'Recommendations to reduce waste' }}
            </h3>
            
            <div class="space-y-4">
                @if($pourcentageMoyenGaspillage > 15)
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg hover:shadow-md transition-all duration-200">
                    <h4 class="text-red-800 font-medium mb-2">
                        {{ $isFrench ? 'Révision des recommandations' : 'Review of recommendations' }}
                    </h4>
                    <p class="text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Le taux de gaspillage est élevé (' . number_format($pourcentageMoyenGaspillage, 1) . '%). Une révision des quantités recommandées pour ce produit est fortement conseillée.'
                            : 'The waste rate is high (' . number_format($pourcentageMoyenGaspillage, 1) . '%). A review of the recommended quantities for this product is strongly advised.'
                        }}
                    </p>
                </div>
                @endif
                
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg hover:shadow-md transition-all duration-200">
                    <h4 class="text-blue-800 font-medium mb-2">
                        {{ $isFrench ? 'Analyse des écarts' : 'Gap analysis' }}
                    </h4>
                    <p class="text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Analysez les tendances de gaspillage pour ce produit. Y a-t-il des matières systématiquement gaspillées plus que d\'autres?'
                            : 'Analyze waste trends for this product. Are there materials systematically wasted more than others?'
                        }}
                    </p>
                </div>
                
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg hover:shadow-md transition-all duration-200">
                    <h4 class="text-green-800 font-medium mb-2">
                        {{ $isFrench ? 'Optimisation du processus' : 'Process optimization' }}
                    </h4>
                    <p class="text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Considérez une révision du processus de production pour ce produit spécifique pour minimiser les pertes de matières.'
                            : 'Consider reviewing the production process for this specific product to minimize material losses.'
                        }}
                    </p>
                </div>
                
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg hover:shadow-md transition-all duration-200">
                    <h4 class="text-yellow-800 font-medium mb-2">
                        {{ $isFrench ? 'Formation ciblée' : 'Targeted training' }}
                    </h4>
                    <p class="text-sm text-gray-700">
                        {{ $isFrench 
                            ? 'Formez spécifiquement les producteurs sur les particularités de ce produit et les mesures précises de matières à utiliser.'
                            : 'Specifically train producers on the specifics of this product and precise measurements of materials to use.'
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour les graphiques
        const detailsGaspillage = @json($detailsGaspillage);
        
        // Trier les données par date pour le graphique d'évolution
        const sortedData = [...detailsGaspillage].sort((a, b) => new Date(a.date_production) - new Date(b.date_production));
        
        // Regrouper les données par date de production
        const groupedByDate = {};
        sortedData.forEach(item => {
            const date = new Date(item.date_production).toLocaleDateString('fr-FR');
            if (!groupedByDate[date]) {
                groupedByDate[date] = {
                    date,
                    valeur_gaspillage: 0
                };
            }
            groupedByDate[date].valeur_gaspillage += parseFloat(item.valeur_gaspillage);
        });
        
        // Convertir en tableau
        const evolutionData = Object.values(groupedByDate);
        
        // Graphique d'évolution du gaspillage
        new Chart(document.getElementById('evolutionGaspillageChart'), {
            type: 'line',
            data: {
                labels: evolutionData.map(item => item.date),
                datasets: [{
                    label: '{{ $isFrench ? "Valeur du gaspillage (FCFA)" : "Waste value (FCFA)" }}',
                    data: evolutionData.map(item => item.valeur_gaspillage),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true
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
                            text: '{{ $isFrench ? "Valeur (FCFA)" : "Value (FCFA)" }}'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
