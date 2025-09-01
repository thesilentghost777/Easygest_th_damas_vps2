@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-800 tracking-tight mb-2">
                        {{ $isFrench ? 'Rapport de Production Mensuel' : 'Monthly Production Report' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Période analysée' : 'Period analyzed' }}: {{ $moisCourantNom }}
                    </p>
                </div>
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                    <button id="printBtn" 
                            class="inline-flex items-center bg-blue-600 text-white font-medium px-4 py-3 rounded-lg border-none shadow-lg transition-all duration-300 cursor-pointer hover:bg-blue-700 hover:shadow-xl transform hover:scale-105">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>{{ $isFrench ? 'Imprimer le rapport' : 'Print report' }}</span>
                    </button>
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div id="printableArea" class="bg-white rounded-2xl shadow-xl overflow-hidden animate-scale-in">
            <!-- Report Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 md:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-2">
                            {{ $isFrench ? 'Rapport de Production' : 'Production Report' }}
                        </h2>
                        <p class="text-blue-100">
                            {{ $isFrench ? 'Exercice' : 'Period' }}: {{ $moisCourantNom }}
                        </p>
                    </div>
                    <div class="text-right text-white mt-4 md:mt-0">
                        <p class="font-semibold">{{ $isFrench ? 'Rapport généré le' : 'Report generated on' }}</p>
                        <p>{{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-6 md:p-8 space-y-8">
                <!-- Executive Summary -->
                <section class="animate-fade-in-up" style="animation-delay: 0.1s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $isFrench ? 'Résumé Exécutif' : 'Executive Summary' }}
                    </h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{ $isFrench 
                            ? 'Au cours du mois de ' . $moisCourantNom . ', la production totale a généré une valeur de'
                            : 'During the month of ' . $moisCourantNom . ', total production generated a value of'
                        }}
                        <span class="font-semibold text-blue-700">{{ number_format($valeurTotaleProduction, 0, ',', ' ') }} FCFA</span>.
                        {{ $isFrench 
                            ? 'Les coûts de matières premières se sont élevés à'
                            : 'Raw material costs amounted to'
                        }}
                        <span class="font-semibold text-red-700">{{ number_format($coutMatierePremiere, 0, ',', ' ') }} FCFA</span>,
                        {{ $isFrench 
                            ? 'dégageant un bénéfice brut estimé à'
                            : 'generating an estimated gross profit of'
                        }}
                        <span class="font-semibold text-green-700">{{ number_format($beneficeBrut, 0, ',', ' ') }} FCFA</span>.
                    </p>

                    <p class="text-gray-700 leading-relaxed">
                        @if($pourcentageEvolution > 0)
                            {{ $isFrench 
                                ? 'Par rapport au mois de ' . $moisPrecedentNom . ', le bénéfice brut a connu une hausse de ' . number_format($pourcentageEvolution, 1) . '%. Cette progression démontre une amélioration significative de la performance de production.'
                                : 'Compared to the month of ' . $moisPrecedentNom . ', gross profit increased by ' . number_format($pourcentageEvolution, 1) . '%. This progression demonstrates a significant improvement in production performance.'
                            }}
                        @elseif($pourcentageEvolution < 0)
                            {{ $isFrench 
                                ? 'Par rapport au mois de ' . $moisPrecedentNom . ', le bénéfice brut a connu une baisse de ' . abs(number_format($pourcentageEvolution, 1)) . '%. Cette diminution nécessite une analyse approfondie des facteurs sous-jacents.'
                                : 'Compared to the month of ' . $moisPrecedentNom . ', gross profit decreased by ' . abs(number_format($pourcentageEvolution, 1)) . '%. This decrease requires an in-depth analysis of underlying factors.'
                            }}
                        @else
                            {{ $isFrench 
                                ? 'Par rapport au mois de ' . $moisPrecedentNom . ', le bénéfice brut est resté stable, indiquant une constance dans les performances de production.'
                                : 'Compared to the month of ' . $moisPrecedentNom . ', gross profit remained stable, indicating consistency in production performance.'
                            }}
                        @endif
                    </p>
                </section>

                <!-- Financial Analysis -->
                <section class="animate-fade-in-up" style="animation-delay: 0.2s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        {{ $isFrench ? 'Analyse Financière' : 'Financial Analysis' }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-50 rounded-xl p-5 border-l-4 border-blue-500">
                            <p class="text-sm text-blue-800 font-medium mb-1">
                                {{ $isFrench ? 'Valeur de production' : 'Production value' }}
                            </p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($valeurTotaleProduction, 0, ',', ' ') }} FCFA</p>
                        </div>

                        <div class="bg-red-50 rounded-xl p-5 border-l-4 border-red-500">
                            <p class="text-sm text-red-800 font-medium mb-1">
                                {{ $isFrench ? 'Coût matières premières' : 'Raw material cost' }}
                            </p>
                            <p class="text-2xl font-bold text-red-900">{{ number_format($coutMatierePremiere, 0, ',', ' ') }} FCFA</p>
                        </div>

                        <div class="bg-green-50 rounded-xl p-5 border-l-4 border-green-500">
                            <p class="text-sm text-green-800 font-medium mb-1">
                                {{ $isFrench ? 'Bénéfice brut' : 'Gross profit' }}
                            </p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($beneficeBrut, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <p class="text-gray-700 leading-relaxed">
                        {{ $isFrench 
                            ? 'L\'analyse financière révèle un taux de rentabilité brute de ' . number_format(($beneficeBrut / $valeurTotaleProduction) * 100, 1) . '% pour le mois de ' . $moisCourantNom . '. Les coûts de matières premières représentent ' . number_format(($coutMatierePremiere / $valeurTotaleProduction) * 100, 1) . '% de la valeur totale de production.'
                            : 'Financial analysis reveals a gross profitability rate of ' . number_format(($beneficeBrut / $valeurTotaleProduction) * 100, 1) . '% for the month of ' . $moisCourantNom . '. Raw material costs represent ' . number_format(($coutMatierePremiere / $valeurTotaleProduction) * 100, 1) . '% of total production value.'
                        }}
                    </p>
                </section>

                <!-- Product Analysis -->
                <section class="animate-fade-in-up" style="animation-delay: 0.3s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        {{ $isFrench ? 'Analyse des Produits' : 'Product Analysis' }}
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                        <div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                {{ $isFrench 
                                    ? 'L\'analyse de la rentabilité par produit montre que'
                                    : 'Product profitability analysis shows that'
                                }}
                                @if(count($produitsLabels) > 0)
                                    <span class="font-semibold">{{ $produitsLabels[0] }}</span>
                                    {{ $isFrench 
                                        ? 'est le produit le plus rentable ce mois-ci, représentant ' . number_format(($produitsBenefices[0] / array_sum($produitsBenefices)) * 100, 1) . '% du bénéfice total.'
                                        : 'is the most profitable product this month, representing ' . number_format(($produitsBenefices[0] / array_sum($produitsBenefices)) * 100, 1) . '% of total profit.'
                                    }}
                                @else
                                    {{ $isFrench 
                                        ? 'nous n\'avons pas de données suffisantes pour déterminer le produit le plus rentable.'
                                        : 'we do not have sufficient data to determine the most profitable product.'
                                    }}
                                @endif
                            </p>

                            <p class="text-gray-700 leading-relaxed">
                                {{ $isFrench 
                                    ? 'Les données montrent que'
                                    : 'The data shows that'
                                }}
                                @if(count($produitsLabels) > 1)
                                    {{ count($produitsLabels) }} {{ $isFrench ? 'produits contribuent significativement au bénéfice global, avec une diversification qui renforce la stabilité de notre production.' : 'products contribute significantly to overall profit, with diversification that strengthens the stability of our production.' }}
                                @else
                                    {{ $isFrench 
                                        ? 'la diversification de notre production est limitée, ce qui pourrait présenter un risque pour la stabilité de nos revenus.'
                                        : 'the diversification of our production is limited, which could present a risk for the stability of our income.'
                                    }}
                                @endif
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="chart-container">
                                <canvas id="pieChart"></canvas>
                            </div>
                            <p class="text-center text-sm text-gray-500 mt-2">
                                {{ $isFrench ? 'Répartition des bénéfices par produit' : 'Profit distribution by product' }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Top Producers -->
                <section class="animate-fade-in-up" style="animation-delay: 0.4s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $isFrench ? 'Performance des Producteurs' : 'Producer Performance' }}
                    </h3>

                    <p class="text-gray-700 leading-relaxed mb-6">
                        @if(count($topProducteurs) > 0)
                            {{ $isFrench 
                                ? 'Les performances des producteurs montrent que ' . $topProducteurs[0]['name'] . ' est le producteur le plus performant ce mois-ci, avec un bénéfice généré de ' . number_format($topProducteurs[0]['benefice'], 0, ',', ' ') . ' FCFA, principalement grâce à la production de ' . $topProducteurs[0]['produit_phare'] . '.'
                                : 'Producer performance shows that ' . $topProducteurs[0]['name'] . ' is the top performer this month, with a profit generated of ' . number_format($topProducteurs[0]['benefice'], 0, ',', ' ') . ' FCFA, mainly thanks to the production of ' . $topProducteurs[0]['produit_phare'] . '.'
                            }}
                        @else
                            {{ $isFrench 
                                ? 'Nous ne disposons pas de données suffisantes pour évaluer la performance des producteurs ce mois-ci.'
                                : 'We do not have sufficient data to evaluate producer performance this month.'
                            }}
                        @endif
                    </p>

                    <div class="space-y-4">
                        @foreach($topProducteurs as $index => $producteur)
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center gap-4">
                                <div class="
                                    @if($index == 0) text-yellow-800 bg-yellow-100
                                    @elseif($index == 1) text-gray-800 bg-gray-100
                                    @else text-amber-800 bg-amber-100 @endif
                                    h-10 w-10 rounded-full flex items-center justify-center font-bold text-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-900">{{ $producteur['name'] }}</h4>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                        <p class="text-blue-700 font-medium">{{ number_format($producteur['benefice'], 0, ',', ' ') }} FCFA</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $isFrench ? 'Produit phare' : 'Top product' }}: {{ $producteur['produit_phare'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Conclusion -->
                <section class="animate-fade-in-up" style="animation-delay: 0.5s">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        {{ $isFrench ? 'Conclusion et Recommandations' : 'Conclusion and Recommendations' }}
                    </h3>

                    <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-400">
                        <p class="text-gray-700 leading-relaxed mb-4">
                            @if($pourcentageEvolution > 0)
                                {{ $isFrench 
                                    ? 'Le mois de ' . $moisCourantNom . ' a montré une évolution positive de la production, avec une augmentation significative du bénéfice brut de ' . number_format($pourcentageEvolution, 1) . '% par rapport au mois précédent.'
                                    : 'The month of ' . $moisCourantNom . ' showed positive production evolution, with a significant increase in gross profit of ' . number_format($pourcentageEvolution, 1) . '% compared to the previous month.'
                                }}
                            @elseif($pourcentageEvolution < 0)
                                {{ $isFrench 
                                    ? 'Le mois de ' . $moisCourantNom . ' a montré une évolution préoccupante de la production, avec une diminution du bénéfice brut de ' . abs(number_format($pourcentageEvolution, 1)) . '% par rapport au mois précédent.'
                                    : 'The month of ' . $moisCourantNom . ' showed concerning production evolution, with a decrease in gross profit of ' . abs(number_format($pourcentageEvolution, 1)) . '% compared to the previous month.'
                                }}
                            @else
                                {{ $isFrench 
                                    ? 'Le mois de ' . $moisCourantNom . ' a montré une stabilité dans la production, avec un bénéfice brut équivalent à celui du mois précédent.'
                                    : 'The month of ' . $moisCourantNom . ' showed stability in production, with gross profit equivalent to that of the previous month.'
                                }}
                            @endif
                        </p>

                        <p class="text-gray-700 leading-relaxed">
                            {{ $isFrench ? 'Il est recommandé de' : 'It is recommended to' }}
                            @if($pourcentageEvolution > 0)
                                {{ $isFrench 
                                    ? 'continuer sur cette lancée positive en renforçant la production des produits les plus rentables, tout en optimisant davantage les coûts des matières premières pour améliorer encore la marge bénéficiaire.'
                                    : 'continue this positive momentum by strengthening production of the most profitable products, while further optimizing raw material costs to further improve profit margins.'
                                }}
                            @elseif($pourcentageEvolution < 0)
                                {{ $isFrench 
                                    ? 'analyser en profondeur les causes de cette baisse et de mettre en place des mesures correctives, notamment en révisant la stratégie de production et en optimisant l\'utilisation des matières premières.'
                                    : 'analyze in depth the causes of this decline and implement corrective measures, particularly by revising the production strategy and optimizing the use of raw materials.'
                                }}
                            @else
                                {{ $isFrench 
                                    ? 'maintenir cette stabilité tout en cherchant à diversifier la gamme de produits pour renforcer la résilience de la production face aux variations du marché.'
                                    : 'maintain this stability while seeking to diversify the product range to strengthen production resilience against market variations.'
                                }}
                            @endif
                        </p>
                    </div>
                </section>
            </div>

            <!-- Report Footer -->
            <div class="bg-gray-50 px-6 md:px-8 py-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                    <p>{{ $isFrench ? 'Rapport généré automatiquement' : 'Report generated automatically' }} - {{ date('d/m/Y H:i') }}</p>
                    <p>{{ $isFrench ? 'Page 1/1' : 'Page 1/1' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile-First CSS and Scripts -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

/* Chart container */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .chart-container {
        height: 250px;
    }
    
    .text-2xl, .text-3xl {
        font-size: 1.5rem;
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    /* Touch-friendly buttons */
    button {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }
    #printableArea, #printableArea * {
        visibility: visible;
    }
    #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .bg-gradient-to-br {
        background: white !important;
    }
    .shadow-xl {
        box-shadow: none !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart colors
    const colorPalette = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
        '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
    ];

    // Chart data
    const data = {
        labels: {!! json_encode($produitsLabels) !!},
        datasets: [{
            data: {!! json_encode($produitsBenefices) !!},
            backgroundColor: colorPalette.slice(0, {!! count($produitsLabels) !!}),
            borderColor: '#FFFFFF',
            borderWidth: 2
        }]
    };

    // Create chart
    const canvas = document.getElementById('pieChart');
    if (canvas) {
        new Chart(canvas, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${context.label}: ${new Intl.NumberFormat().format(value)} FCFA (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Print functionality
    document.getElementById('printBtn').addEventListener('click', function() {
        window.print();
    });
});
</script>
@endsection