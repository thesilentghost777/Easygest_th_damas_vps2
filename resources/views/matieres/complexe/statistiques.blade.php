@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $isFrench ? 'Statistiques des Matières du Complexe' : 'Complex Ingredient Statistics' }}
        </h1>
        <div class="flex">
            @include('buttons')
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filtres de période -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            {{ $isFrench ? 'Sélectionner la période' : 'Select Period' }}
        </h2>
        <form action="{{ route('matieres.complexe.statistiques') }}" method="GET" class="flex flex-wrap gap-4 md:flex-nowrap">
            <div class="flex items-center space-x-2">
                <input type="radio" id="jour" name="periode" value="jour" {{ $periode == 'jour' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                <label for="jour" class="text-sm font-medium text-gray-700">
                    {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                </label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="semaine" name="periode" value="semaine" {{ $periode == 'semaine' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                <label for="semaine" class="text-sm font-medium text-gray-700">
                    {{ $isFrench ? 'Cette semaine' : 'This Week' }}
                </label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="radio" id="mois" name="periode" value="mois" {{ $periode == 'mois' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                <label for="mois" class="text-sm font-medium text-gray-700">
                    {{ $isFrench ? 'Ce mois' : 'This Month' }}
                </label>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-4 rounded">
                {{ $isFrench ? 'Filtrer' : 'Filter' }}
            </button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Récapitulatif des matières du complexe' : 'Summary of Complex Materials' }}
                ({{ $periode == 'jour' ? ($isFrench ? "Aujourd'hui" : 'Today') : ($periode == 'semaine' ? ($isFrench ? 'Cette semaine' : 'This Week') : ($isFrench ? 'Ce mois' : 'This Month')) }})
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                {{ $isFrench ? 'Période' : 'Period' }}: {{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }}
            </p>

            @if(count($statistiques) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">{{ $isFrench ? 'Matière' : 'Ingredient' }}</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-500 uppercase">{{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">{{ $isFrench ? 'Unité' : 'Unit' }}</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-500 uppercase">{{ $isFrench ? 'Montant Total' : 'Total Amount' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($statistiques as $stat)
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $stat->nom }}</td>
                                    <td class="px-4 py-2 text-right text-gray-900">{{ number_format($stat->quantite_totale, 3, ',', ' ') }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $stat->unite_minimale }}</td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-900">{{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-4 py-2 text-right font-bold text-lg text-gray-900">
                                    {{ $isFrench ? 'Total' : 'Total' }}
                                </td>
                                <td class="px-4 py-2 text-right font-bold text-lg text-gray-900">
                                    {{ number_format($montantTotal, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="bg-blue-50 p-4 rounded">
                    <p class="text-blue-700">
                        {{ $isFrench ? 'Aucune donnée disponible pour cette période.' : 'No data available for this period.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Graphique circulaire -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Répartition par matière' : 'Breakdown by Ingredient' }}
                </h2>
                @if(count($dataGraphique) > 0)
                    <div id="pie-chart-container" class="h-72">
                        <canvas id="pieChart"></canvas>
                    </div>
                @else
                    <div class="bg-gray-100 h-72 flex items-center justify-center">
                        <p class="text-gray-500">
                            {{ $isFrench ? 'Aucune donnée disponible pour cette période.' : 'No data available for this period.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Graphique d'évolution temporelle -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Évolution sur la période' : 'Trend Over the Period' }}
                </h2>
                @if(count($evolutionTemporelle) > 0)
                    <div id="time-series-container" class="h-72">
                        <canvas id="timeSeriesChart"></canvas>
                    </div>
                @else
                    <div class="bg-gray-100 h-72 flex items-center justify-center">
                        <p class="text-gray-500">
                            {{ $isFrench ? 'Aucune donnée disponible pour cette période.' : 'No data available for this period.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(count($dataGraphique) > 0 || count($evolutionTemporelle) > 0)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(count($dataGraphique) > 0)
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(@json($dataGraphique)),
                datasets: [{
                    data: Object.values(@json($dataGraphique)),
                    backgroundColor: ['#4299e1', '#38b2ac', '#ed8936', '#9f7aea', '#f56565', '#48bb78'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = context.raw || 0;
                                return `${context.label}: ${new Intl.NumberFormat('fr-FR').format(value)} FCFA`;
                            }
                        }
                    }
                }
            }
        });
        @endif

        @if(count($evolutionTemporelle) > 0)
        const timeCtx = document.getElementById('timeSeriesChart').getContext('2d');
        const data = @json($evolutionTemporelle);
        const timeSeriesChart = new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: data.map(item => item.date),
                datasets: [{
                    label: '{{ $isFrench ? 'Montant total (FCFA)' : 'Total Amount (FCFA)' }}',
                    data: data.map(item => item.value),
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => new Intl.NumberFormat('fr-FR').format(value) + ' FCFA'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA'
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endif
@endsection
