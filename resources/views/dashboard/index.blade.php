@extends('pages.dg.dg_default')
@section('page-content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Tableau de Bord Directeur' : 'Director Dashboard' }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Vue d\'ensemble de votre boulangerie-pâtisserie' : 'Overview of your bakery-pastry business' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Revenue Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 animate-scale-in">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}
                        </h3>
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent mb-2">
                        {{ number_format($revenue['current'], 0, ',', ' ') }} XAF
                    </p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $revenue['growth'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        <span class="mr-1">{{ $revenue['growth'] }}%</span>
                        <span class="text-xs">{{ $isFrench ? 'vs mois dernier' : 'vs last month' }}</span>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 animate-scale-in" style="animation-delay: 0.1s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $isFrench ? 'Bénéfice net' : 'Net profit' }}
                        </h3>
                        <div class="p-3 bg-emerald-50 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-transparent mb-2">
                        {{ number_format($profit['current'], 0, ',', ' ') }} XAF
                    </p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $profit['growth'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        <span class="mr-1">{{ $profit['growth'] }}%</span>
                        <span class="text-xs">{{ $isFrench ? 'vs mois dernier' : 'vs last month' }}</span>
                    </div>
                </div>
            </div>

            <!-- Expenses Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 animate-scale-in" style="animation-delay: 0.2s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $isFrench ? 'Dépenses' : 'Expenses' }}
                        </h3>
                        <div class="p-3 bg-red-50 rounded-xl">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent mb-2">
                        {{ number_format($expenses['current'], 0, ',', ' ') }} XAF
                    </p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $expenses['growth'] <= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        <span class="mr-1">{{ $expenses['growth'] }}%</span>
                        <span class="text-xs">{{ $isFrench ? 'vs mois dernier' : 'vs last month' }}</span>
                    </div>
                </div>
            </div>

            <!-- Staff Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 animate-scale-in" style="animation-delay: 0.3s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $isFrench ? 'Effectif total' : 'Total staff' }}
                        </h3>
                        <div class="p-3 bg-cyan-50 rounded-xl">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-cyan-600 to-cyan-800 bg-clip-text text-transparent mb-2">
                        {{ $staff['total'] }}
                    </p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $staff['stability'] === 'Stable' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $isFrench ? ($staff['stability'] === 'Stable' ? 'Stable' : 'Variable') : $staff['stability'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.4s">
    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        {{ $isFrench ? 'Évolution financière (Année courante)' : 'Financial evolution (Current year)' }}
    </h3>
    
    <!-- Legend Interactive -->
    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mb-6">
        <div class="flex items-center cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors" onclick="toggleDataset(0)">
            <div class="w-4 h-4 bg-blue-500 rounded-full mr-2" id="legend-revenue"></div>
            <span class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Revenus' : 'Revenue' }}</span>
        </div>
        <div class="flex items-center cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors" onclick="toggleDataset(1)">
            <div class="w-4 h-4 bg-red-500 rounded-full mr-2" id="legend-expenses"></div>
            <span class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Dépenses' : 'Expenses' }}</span>
        </div>
        <div class="flex items-center cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors" onclick="toggleDataset(2)">
            <div class="w-4 h-4 bg-green-500 rounded-full mr-2" id="legend-profit"></div>
            <span class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Bénéfices' : 'Profit' }}</span>
        </div>
        <div class="ml-4 text-xs text-gray-500">
            {{ $isFrench ? 'Cliquez sur une légende pour masquer/afficher' : 'Click on legend to hide/show' }}
        </div>
    </div>
    
    <div class="chart-container">
        <canvas id="financialChart" class="w-full max-h-96"></canvas>
    </div>
</div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in-up" style="animation-delay: 0.5s">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $isFrench ? 'Demandes AS en attente' : 'Pending AS requests' }}
            </h3>
            
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $isFrench ? 'Demande AS de' : 'AS request from' }} <span class="font-semibold">{{ $request->employe->name }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $isFrench ? 'En attente' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune demande en attente' : 'No pending requests' }}
                    </h3>
                    <p class="text-gray-500">
                        {{ $isFrench ? 'Toutes les demandes sont traitées' : 'All requests are processed' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
    <br><br><br><br>.
</div>

<!-- Mobile-First CSS and Chart Script -->
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

/* Chart container responsiveness */
.chart-container {
    position: relative;
    height: 300px;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .chart-container {
        height: 250px;
    }
    
    .text-3xl, .text-4xl {
        font-size: 1.875rem;
    }
    
    .grid {
        gap: 1rem;
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let financialChart;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financialChart').getContext('2d');
    const chartData = @json($chartData);

    financialChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: '{{ $isFrench ? "Revenus" : "Revenue" }}',
                    data: chartData.revenues,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: false,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    hidden: false
                },
                {
                    label: '{{ $isFrench ? "Dépenses" : "Expenses" }}',
                    data: chartData.expenses,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: false,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    hidden: false
                },
                {
                    label: '{{ $isFrench ? "Bénéfices" : "Profit" }}',
                    data: chartData.profits,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    hidden: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 750,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: false, // On utilise notre propre légende interactive
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + 
                                new Intl.NumberFormat('{{ $isFrench ? "fr-FR" : "en-US" }}', {
                                    style: 'currency',
                                    currency: 'XAF',
                                    minimumFractionDigits: 0
                                }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M XAF';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'K XAF';
                            }
                            return value + ' XAF';
                        }
                    }
                },
                x: {
                    grid: {
                        color: '#f3f4f6',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        maxRotation: 45
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        }
    });
});

// Fonction pour basculer l'affichage des datasets
function toggleDataset(datasetIndex) {
    const dataset = financialChart.data.datasets[datasetIndex];
    const legendElements = ['legend-revenue', 'legend-expenses', 'legend-profit'];
    const legendElement = document.getElementById(legendElements[datasetIndex]);
    
    // Basculer la visibilité
    dataset.hidden = !dataset.hidden;
    
    // Mettre à jour l'apparence de la légende
    if (dataset.hidden) {
        legendElement.style.opacity = '0.3';
        legendElement.style.backgroundColor = '#d1d5db';
        legendElement.parentElement.style.opacity = '0.5';
    } else {
        legendElement.style.opacity = '1';
        legendElement.style.backgroundColor = '';
        legendElement.parentElement.style.opacity = '1';
        
        // Restaurer la couleur originale
        const originalColors = ['#3b82f6', '#ef4444', '#10b981'];
        legendElement.style.backgroundColor = originalColors[datasetIndex];
    }
    
    // Mettre à jour le graphique
    financialChart.update('active');
}

// Ajouter des effets visuels sur hover pour les légendes
document.addEventListener('DOMContentLoaded', function() {
    const legendItems = document.querySelectorAll('[onclick^="toggleDataset"]');
    
    legendItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (!this.style.opacity || this.style.opacity === '1') {
                this.style.transform = 'scale(1.05)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

@endsection
