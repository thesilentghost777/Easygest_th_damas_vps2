@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-blue-50 to-blue-100">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Détails du Gaspillage - Production</h1>
                    <p class="text-blue-100 mt-2">
                        ID Lot: {{ $production->first()->id_lot }} - 
                        Produit: {{ $production->first()->produitFixe->nom }}
                    </p>
                </div>
               @include('buttons')
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <!-- Informations générales de la production -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Informations Production</h3>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">ID Lot:</span>
                        <span class="font-medium">{{ $production->first()->id_lot }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Produit:</span>
                        <span class="font-medium">{{ $production->first()->produitFixe->nom }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Producteur:</span>
                        <span class="font-medium">{{ $production->first()->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Date de production:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($production->first()->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            
            
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Résumé du Gaspillage</h3>
                <div class="mt-4 space-y-2">
                    @php
                        $totalGaspillage = collect($detailsGaspillage)->sum('valeur_gaspillage');
                        $totalQuantiteGaspillee = collect($detailsGaspillage)->sum('quantite_gaspillee');
                        $pourcentageMoyenGaspillage = collect($detailsGaspillage)->avg('pourcentage_gaspillage');
                    @endphp
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Valeur totale gaspillée:</span>
                        <span class="font-medium text-red-600">{{ number_format($totalGaspillage, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Pourcentage moyen gaspillé:</span>
                        <span class="font-medium text-orange-600">{{ number_format($pourcentageMoyenGaspillage, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graphique de répartition du gaspillage -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition du gaspillage par matière</h3>
                <div class="h-80">
                    <canvas id="gaspillageRepatitionChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Écart entre recommandation et utilisation</h3>
                <div class="h-80">
                    <canvas id="ecartRecommandationChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Tableau détaillé du gaspillage -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Détails du gaspillage par matière</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Matière
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantité utilisée
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantité recommandée
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Écart (gaspillage)
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                % Gaspillage
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Valeur gaspillée
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailsGaspillage as $detail)
                       <tr>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm font-medium text-gray-900">{{ $detail->nom_matiere }}</div>
                       </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm text-gray-900">
                       {{ number_format(round($detail->quantite_utilisee), 0, '.', ' ') }} {{ $detail->unite_matiere }}
                       </div>
                       </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm text-gray-900">
                       {{ number_format(round($detail->quantite_recommandee), 0, '.', ' ') }} {{ $detail->unite_matiere }}
                       </div>
                       </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm text-red-600 font-medium">
                       {{ number_format(round($detail->quantite_gaspillee), 0, '.', ' ') }} {{ $detail->unite_matiere }}
                       </div>
                       </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm font-medium {{ $detail->pourcentage_gaspillage > 20 ? 'text-red-600' : 'text-orange-600' }}">
                       {{ number_format(round($detail->pourcentage_gaspillage), 0, '.', ' ') }}%
                       </div>
                       </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                       <div class="text-sm font-medium text-red-600">
                       {{ number_format($detail->valeur_gaspillage, 0, '.', ' ') }} FCFA
                       </div>
                       </td>
                       </tr>
                       @endforeach
                       </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                            Total:
                           </td>
                           <td class="px-6 py-3 text-sm font-medium text-red-600">
                           {{ number_format(round($totalQuantiteGaspillee), 0, '.', ' ') }} (unités diverses)
                           </td>
                           <td class="px-6 py-3 text-sm font-medium text-red-600">
                           {{ number_format(round($pourcentageMoyenGaspillage), 0, '.', ' ') }}%
                           </td>
                           <td class="px-6 py-3 text-sm font-medium text-red-600">
                           {{ number_format($totalGaspillage, 0, '.', ' ') }} FCFA
                           </td>
                           </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Suggestions pour réduire le gaspillage -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Suggestions pour réduire le gaspillage</h3>
            
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-md">
                    <h4 class="text-blue-800 font-medium mb-1">Révision des recommandations</h4>
                    <p class="text-sm text-gray-700">
                        Si ce même écart se reproduit constamment, envisagez de réviser les recommandations de matières pour ce produit.
                    </p>
                </div>
                
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-md">
                    <h4 class="text-green-800 font-medium mb-1">Formation des producteurs</h4>
                    <p class="text-sm text-gray-700">
                        Organisez une formation supplémentaire pour les producteurs sur l'utilisation optimale des matières premières.
                    </p>
                </div>
                
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-md">
                    <h4 class="text-yellow-800 font-medium mb-1">Contrôle des mesures</h4>
                    <p class="text-sm text-gray-700">
                        Vérifiez les outils de mesure utilisés pendant la production pour assurer leur précision.
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
        
        // Graphique de répartition du gaspillage
        new Chart(document.getElementById('gaspillageRepatitionChart'), {
            type: 'pie',
            data: {
                labels: detailsGaspillage.map(item => item.nom_matiere),
                datasets: [{
                    data: detailsGaspillage.map(item => item.valeur_gaspillage),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(14, 165, 233, 0.7)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const item = context.raw;
                                const percentage = (item / detailsGaspillage.reduce((sum, detail) => sum + detail.valeur_gaspillage, 0)) * 100;
                                return `${context.label}: ${new Intl.NumberFormat('fr-FR').format(item)} FCFA (${percentage.toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Graphique d'écart entre recommandation et utilisation
        new Chart(document.getElementById('ecartRecommandationChart'), {
            type: 'bar',
            data: {
                labels: detailsGaspillage.map(item => item.nom_matiere),
                datasets: [
                    {
                        label: 'Quantité recommandée',
                        data: detailsGaspillage.map(item => item.quantite_recommandee),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Quantité utilisée',
                        data: detailsGaspillage.map(item => item.quantite_utilisee),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
