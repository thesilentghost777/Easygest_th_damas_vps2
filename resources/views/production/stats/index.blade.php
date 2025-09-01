@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Mobile Header -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
            <h1 class="text-xl font-bold">{{ $isFrench ? 'Statistiques de Production' : 'Production Statistics' }}</h1>
            <p class="text-sm text-blue-200 mt-1">{{ $isFrench ? 'Tableau de bord analytique' : 'Analytics Dashboard' }}</p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200 text-center">
            <h1 class="text-4xl font-bold text-blue-800 mb-2">{{ $isFrench ? 'Statistiques de Production' : 'Production Statistics' }}</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-blue-600 to-green-500 mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Section Récapitulatif d'hier -->
    <div class="mb-8 animate-fade-in">
        <h2 class="text-lg lg:text-xl font-bold text-gray-800 mb-4">
            {{ $isFrench ? 'Récapitulatif d\'hier' : 'Yesterday\'s Summary' }}
        </h2>
        
        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-blue-500 mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Valeur production' : 'Production value' }}</p>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($yesterdayStats['production_value'], 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500 mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Matières assignées' : 'Assigned materials' }}</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($yesterdayStats['assigned_materials_value'], 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-yellow-500 mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Matières utilisées' : 'Used materials' }}</p>
                        <p class="text-xl font-bold text-yellow-600">{{ number_format($yesterdayStats['used_materials_value'], 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-purple-500 mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Production attendue' : 'Expected production' }}</p>
                        <p class="text-xl font-bold text-purple-600">{{ number_format($yesterdayStats['expected_production_value'], 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-500 mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Bénéfice estimé' : 'Estimated benefit' }}</p>
                        <p class="text-xl font-bold text-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-600">{{ number_format($yesterdayStats['estimated_benefit'], 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-5 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Valeur production' : 'Production value' }}</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($yesterdayStats['production_value'], 1, ',', ' ') }} XAF</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Matières assignées' : 'Assigned materials' }}</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($yesterdayStats['assigned_materials_value'], 1, ',', ' ') }} XAF</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Matières utilisées' : 'Used materials' }}</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($yesterdayStats['used_materials_value'], 1, ',', ' ') }} XAF</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Production attendue' : 'Expected production' }}</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($yesterdayStats['expected_production_value'], 1, ',', ' ') }} XAF</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-500 transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Bénéfice estimé' : 'Estimated benefit' }}</p>
                        <p class="text-2xl font-bold text-{{ $yesterdayStats['estimated_benefit'] >= 0 ? 'green' : 'red' }}-600">{{ number_format($yesterdayStats['estimated_benefit'], 0, ',', ' ') }} XAF</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Statistiques Personnalisées -->
    <div class="mb-8 animate-fade-in">
        <h2 class="text-lg lg:text-xl font-bold text-gray-800 mb-4">
            {{ $isFrench ? 'Statistiques Personnalisées' : 'Custom Statistics' }}
        </h2>
        
        <!-- Filtres de période -->
        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mb-6">
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 lg:gap-4">
                <button onclick="loadCustomStats('day')" class="period-btn bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-blue-200 active:scale-95">
                    {{ $isFrench ? 'Jour' : 'Day' }}
                </button>
                <button onclick="loadCustomStats('week')" class="period-btn bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-gray-200 active:scale-95">
                    {{ $isFrench ? 'Semaine' : 'Week' }}
                </button>
                <button onclick="loadCustomStats('month')" class="period-btn bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-gray-200 active:scale-95">
                    {{ $isFrench ? 'Mois' : 'Month' }}
                </button>
                <button onclick="loadCustomStats('year')" class="period-btn bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-gray-200 active:scale-95">
                    {{ $isFrench ? 'Année' : 'Year' }}
                </button>
                <div class="col-span-2 lg:col-span-2 flex gap-2">
                    <input type="date" id="start_date" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <input type="date" id="end_date" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <button onclick="loadCustomPeriod()" class="bg-green-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-600 transition-all duration-200 active:scale-95">
                        {{ $isFrench ? 'OK' : 'OK' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Résultats des statistiques personnalisées -->
        <div id="custom-stats-container" class="hidden">
            <!-- Le contenu sera chargé dynamiquement -->
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des produits -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-blue-600 text-white p-4 lg:p-6">
                <h3 class="text-lg font-bold">{{ $isFrench ? 'Répartition des Productions' : 'Production Distribution' }}</h3>
            </div>
            <div class="p-4 lg:p-6">
                <canvas id="productionChart" class="w-full h-64 lg:h-80"></canvas>
            </div>
        </div>

        <!-- Graphique des matières -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-green-600 text-white p-4 lg:p-6">
                <h3 class="text-lg font-bold">{{ $isFrench ? 'Répartition des Matières' : 'Materials Distribution' }}</h3>
            </div>
            <div class="p-4 lg:p-6">
                <canvas id="materialsChart" class="w-full h-64 lg:h-80"></canvas>
            </div>
        </div>
    </div>

    <!-- Courbes d'évolution -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="bg-purple-600 text-white p-4 lg:p-6">
            <h3 class="text-lg font-bold">{{ $isFrench ? 'Évolution sur 30 jours' : '30-Day Evolution' }}</h3>
        </div>
        <div class="p-4 lg:p-6">
            <canvas id="evolutionChart" class="w-full h-64 lg:h-96"></canvas>
        </div>
    </div>

    <!-- Lien vers les détails -->
    <div class="mt-8 text-center animate-fade-in">
        <a href="{{ route('production.stats.details') }}" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 active:scale-95">
            {{ $isFrench ? 'Voir les détails des productions' : 'View production details' }}
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
            touch-action: manipulation;
        }
        .mobile-card:active {
            transform: scale(0.98) !important;
        }
        /* Touch targets */
        button, input {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données des graphiques
const productionData = @json($productionChartData);
const materialsData = @json($materialsChartData);
const evolutionData = @json($evolutionData);

// Graphique circulaire des productions
const productionCtx = document.getElementById('productionChart').getContext('2d');
new Chart(productionCtx, {
    type: 'doughnut',
    data: {
        labels: productionData.map(item => item.nom),
        datasets: [{
            data: productionData.map(item => item.total_value),
            backgroundColor: [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique circulaire des matières
const materialsCtx = document.getElementById('materialsChart').getContext('2d');
new Chart(materialsCtx, {
    type: 'doughnut',
    data: {
        labels: materialsData.map(item => item.nom),
        datasets: [{
            data: materialsData.map(item => item.total_value),
            backgroundColor: [
                '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
                '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Courbe d'évolution
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: evolutionData.map(item => item.date),
        datasets: [
            {
                label: '{{ $isFrench ? "Valeur production" : "Production value" }}',
                data: evolutionData.map(item => item.production_value),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            },
            {
                label: '{{ $isFrench ? "Matières assignées" : "Assigned materials" }}',
                data: evolutionData.map(item => item.assigned_materials_value),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4
            },
            {
                label: '{{ $isFrench ? "Bénéfice estimé" : "Estimated benefit" }}',
                data: evolutionData.map(item => item.estimated_benefit),
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Fonctions pour les statistiques personnalisées
function loadCustomStats(period) {
    // Mettre à jour l'apparence des boutons
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.className = 'period-btn bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-gray-200 active:scale-95';
    });
    event.target.className = 'period-btn bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-blue-200 active:scale-95';
    
    fetch(`{{ route('production.stats.custom') }}?period=${period}`)
        .then(response => response.json())
        .then(data => displayCustomStats(data));
}

function loadCustomPeriod() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (!startDate || !endDate) {
        alert('{{ $isFrench ? "Veuillez sélectionner les deux dates" : "Please select both dates" }}');
        return;
    }
    
    fetch(`{{ route('production.stats.custom') }}?period=custom&start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => displayCustomStats(data));
}

function displayCustomStats(stats) {
    const container = document.getElementById('custom-stats-container');
    container.classList.remove('hidden');
    
    container.innerHTML = `
        <div class="lg:hidden space-y-4">
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">{{ $isFrench ? 'Valeur production' : 'Production value' }}</p>
                <p class="text-xl font-bold text-blue-600">${formatNumber(stats.production_value)} XAF</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">{{ $isFrench ? 'Matières assignées' : 'Assigned materials' }}</p>
                <p class="text-xl font-bold text-green-600">${formatNumber(stats.assigned_materials_value)} XAF</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-600">{{ $isFrench ? 'Matières utilisées' : 'Used materials' }}</p>
                <p class="text-xl font-bold text-yellow-600">${formatNumber(stats.used_materials_value)} XAF</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">{{ $isFrench ? 'Production attendue' : 'Expected production' }}</p>
                <p class="text-xl font-bold text-purple-600">${formatNumber(stats.expected_production_value)} XAF</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-${stats.estimated_benefit >= 0 ? 'green' : 'red'}-500">
                <p class="text-sm text-gray-600">{{ $isFrench ? 'Bénéfice estimé' : 'Estimated benefit' }}</p>
                <p class="text-xl font-bold text-${stats.estimated_benefit >= 0 ? 'green' : 'red'}-600">${formatNumber(stats.estimated_benefit)} XAF</p>
            </div>
        </div>
        
        <div class="hidden lg:grid lg:grid-cols-5 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Valeur production' : 'Production value' }}</p>
                <p class="text-2xl font-bold text-blue-600">${formatNumber(stats.production_value)} XAF</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Matières assignées' : 'Assigned materials' }}</p>
                <p class="text-2xl font-bold text-green-600">${formatNumber(stats.assigned_materials_value)} XAF</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Matières utilisées' : 'Used materials' }}</p>
                <p class="text-2xl font-bold text-yellow-600">${formatNumber(stats.used_materials_value)} XAF</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Production attendue' : 'Expected production' }}</p>
                <p class="text-2xl font-bold text-purple-600">${formatNumber(stats.expected_production_value)} XAF</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-${stats.estimated_benefit >= 0 ? 'green' : 'red'}-500">
                <p class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Bénéfice estimé' : 'Estimated benefit' }}</p>
                <p class="text-2xl font-bold text-${stats.estimated_benefit >= 0 ? 'green' : 'red'}-600">${formatNumber(stats.estimated_benefit)} XAF</p>
            </div>
        </div>
    `;
}

function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(num));
}
</script>
@endsection
