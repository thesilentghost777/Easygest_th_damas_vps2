@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header responsive -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-900 p-4 md:p-8 shadow-xl">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div className="space-y-2">
                    @include('buttons')
                    
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                        {{ $isFrench ? 'Tableau de bord des statistiques de ventes' : 'Sales Statistics Dashboard' }}
                    </h1>
                    <p class="text-blue-100 opacity-90 text-sm md:text-base">
                        {{ $isFrench ? 'Vue d\'ensemble des performances et indicateurs clés' : 'Overview of performance and key indicators' }}
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <a href="{{ route('serveur.vente.liste') }}" 
                       class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 md:py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-lg md:rounded-md transition-all duration-300 ease-in-out transform hover:scale-105 active:scale-95">
                        <span>{{ $isFrench ? 'Liste détaillée' : 'Detailed List' }}</span>
                        <ArrowRight class="h-5 w-5 ml-2" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main container -->
    <div class="container mx-auto px-4 py-6 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Key figures - Mobile optimized cards -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800 px-2">
                {{ $isFrench ? 'Chiffres clés' : 'Key Figures' }}
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Daily Revenue -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider">
                            {{ $isFrench ? 'CA Journalier' : 'Daily Revenue' }}
                        </h3>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <DollarSign class="h-4 w-4 text-blue-600" />
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mb-2">
                        {{ number_format($chiffreAffaires['journalier'], 0, ',', ' ') }} XAF
                    </p>
                    <div class="text-xs md:text-sm">
                        @if($chiffreAffaires['hebdomadaire'] > 0)
                            <span class="text-green-600 flex items-center">
                                <TrendingUp class="h-3 w-3 mr-1" />
                                +{{ number_format(($chiffreAffaires['journalier'] / $chiffreAffaires['hebdomadaire']) * 100, 1) }}%
                            </span>
                        @else
                            <span class="text-gray-500">
                                {{ $isFrench ? 'INDISPONIBLE' : 'UNAVAILABLE' }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Weekly Revenue -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider">
                            {{ $isFrench ? 'CA Hebdomadaire' : 'Weekly Revenue' }}
                        </h3>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <Calendar class="h-4 w-4 text-green-600" />
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">
                        {{ number_format($chiffreAffaires['hebdomadaire'], 0, ',', ' ') }} XAF
                    </p>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider">
                            {{ $isFrench ? 'CA Mensuel' : 'Monthly Revenue' }}
                        </h3>
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <TrendingUp class="h-4 w-4 text-purple-600" />
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">
                        {{ number_format($chiffreAffaires['mensuel'], 0, ',', ' ') }} XAF
                    </p>
                </div>

                <!-- Total Deposits -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-yellow-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider">
                            {{ $isFrench ? 'Total Versements' : 'Total Deposits' }}
                        </h3>
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <DollarSign class="h-4 w-4 text-yellow-600" />
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">
                        {{ number_format($versements['total'], 0, ',', ' ') }} XAF
                    </p>
                </div>
            </div>
        </div>

        <!-- Performance Charts Section - Mobile responsive -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-800 px-2">
                {{ $isFrench ? 'Performance des ventes' : 'Sales Performance' }}
            </h2>
            
            <!-- Charts Grid - Stack on mobile -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sales Evolution -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Évolution des ventes' : 'Sales Evolution' }}
                    </h3>
                    <div class="h-64 md:h-80">
                        <canvas id="evolutionVentes" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Popular Products -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Top 5 des produits vendus' : 'Top 5 Selling Products' }}
                    </h3>
                    <div class="h-64 md:h-80">
                        <canvas id="produitsPopulaires" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales Chart - Full width -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h2 class="text-xl font-semibold text-blue-600 mb-6">
                    {{ $isFrench ? 'Évolution des Ventes par mois' : 'Monthly Sales Evolution' }}
                </h2>
                <div class="h-64 md:h-96">
                    <canvas id="ventesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Analysis Section - Mobile optimized -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Server Performance -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-blue-600">
                        {{ $isFrench ? 'Performance des Serveurs' : 'Server Performance' }}
                    </h2>
                    <Users class="h-5 w-5 text-blue-600" />
                </div>
                <div class="space-y-4">
                    @foreach($performanceServeurs as $serveur)
                        <div class="border-b pb-4 last:border-0 hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                                <span class="text-base font-medium text-gray-700">{{ $serveur->nom_serveur }}</span>
                                <span class="text-green-600 font-semibold text-sm sm:text-base">
                                    {{ number_format($serveur->chiffre_affaires) }} XAF
                                </span>
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 mt-1">
                                {{ $isFrench ? 'Total ventes:' : 'Total sales:' }} {{ number_format($serveur->total_ventes) }} {{ $isFrench ? 'unités' : 'units' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

           

            <!-- Top 5 Damaged Products -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                <h2 class="text-lg font-semibold text-red-600 mb-4">
                    {{ $isFrench ? 'Top 5 Produits Avariés' : 'Top 5 Damaged Products' }}
                </h2>
                <div class="space-y-4">
                    @foreach($topProduitsAvaries as $produit)
                        <div class="p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex justify-between mb-2 text-sm">
                                <span class="text-gray-700 font-medium">{{ $produit->nom }}</span>
                                <span class="text-red-600 font-semibold">{{ number_format($produit->pourcentage_avarie, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-red-400 to-red-500 h-2.5 rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ min($produit->pourcentage_avarie, 100) }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Evolution and Trends Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-800 px-2">
                {{ $isFrench ? 'Évolution et tendances' : 'Evolution and Trends' }}
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Annual Evolution -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Évolution annuelle' : 'Annual Evolution' }}
                    </h3>
                    <div class="h-64">
                        <canvas id="evolutionAnnuelle" class="w-full h-full"></canvas>
                    </div>
                </div>

              
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des couleurs
    const colors = {
        blue: 'rgb(59, 130, 246)',
        green: 'rgb(16, 185, 129)',
        purple: 'rgb(139, 92, 246)',
        yellow: 'rgb(245, 158, 11)',
        red: 'rgb(239, 68, 68)'
    };

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        }
    };

    // Évolution des ventes
    new Chart(document.getElementById('evolutionVentes'), {
        type: 'line',
        data: {
            labels: {!! json_encode($evolutionVentes->pluck('date')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Chiffre affaires" : "Revenue" }}',
                data: {!! json_encode($evolutionVentes->pluck('total')) !!},
                borderColor: colors.blue,
                backgroundColor: colors.blue + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: defaultOptions
    });

    // Produits populaires
    new Chart(document.getElementById('produitsPopulaires'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($produitsPopulaires->pluck('nom_produit')) !!},
            datasets: [{
                data: {!! json_encode($produitsPopulaires->pluck('total_vendu')) !!},
                backgroundColor: [colors.blue, colors.green, colors.purple, colors.yellow, colors.red]
            }]
        },
        options: {
            ...defaultOptions,
            cutout: '70%',
            plugins: {
                ...defaultOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value} {{ $isFrench ? "FCFA" : "FCFA" }}`;
                        }
                    }
                }
            }
        }
    });

    // Évolution annuelle
    new Chart(document.getElementById('evolutionAnnuelle'), {
        type: 'line',
        data: {
            labels: {!! json_encode($evolutionAnnuelle->map(function($item) {
                return date('F Y', mktime(0, 0, 0, $item->mois, 1, $item->annee));
            })) !!},
            datasets: [{
                label: '{{ $isFrench ? "Chiffre d\'affaires" : "Revenue" }}',
                data: {!! json_encode($evolutionAnnuelle->pluck('total')) !!},
                borderColor: colors.purple,
                backgroundColor: colors.purple + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: defaultOptions
    });

    // Monthly sales chart
    function chartData() {
        const ctx = document.getElementById('ventesChart').getContext('2d');
        const ventesData = @json($ventesParMois);
        const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        
        const produitsUniques = [...new Set(ventesData.map(item => item.produit))];
        const datasets = produitsUniques.map((produit, index) => {
            const donneesProduit = ventesData.filter(item => item.produit === produit);
            const colorList = [colors.blue, colors.green, colors.purple, colors.yellow, colors.red];
            return {
                label: produit,
                data: donneesProduit.map(item => item.total_ventes),
                borderColor: colorList[index % colorList.length],
                backgroundColor: colorList[index % colorList.length] + '20',
                fill: false,
                tension: 0.4
            };
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: mois,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '{{ $isFrench ? "Évolution des ventes par produit" : "Sales evolution by product" }}'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ $isFrench ? "Quantité vendue" : "Quantity sold" }}'
                        }
                    }
                }
            }
        });
    }
    
    chartData();
});
</script>
@endsection
