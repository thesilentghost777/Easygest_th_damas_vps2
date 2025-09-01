@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Statistiques Employés' : 'Employee Statistics' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Analyse des performances du mois' : 'Current month performance analysis' }}
            </p>
        </div>
    </div>

    <!-- Mobile Action Button -->
    <div class="md:hidden px-4 py-4">
        <a href="{{ route('statistiques.absences') }}" class="w-full bg-green-600 text-white py-3 px-6 rounded-2xl shadow-lg flex items-center justify-center transform hover:scale-105 active:scale-95 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            {{ $isFrench ? 'Voir Statistiques Absences' : 'View Absence Statistics' }}
        </a>
    </div>

    <br><br>
    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Charts -->
                <div class="grid grid-cols-1 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-2xl p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $isFrench ? 'Ponctualité Globale' : 'Overall Punctuality' }}</h3>
                        <canvas id="mobilePunctualityChart" class="w-full h-48"></canvas>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-6 animate-fade-in" style="animation-delay: 0.1s">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $isFrench ? 'Performance Globale' : 'Overall Performance' }}</h3>
                        <canvas id="mobilePerformanceChart" class="w-full h-48"></canvas>
                    </div>
                </div>

                <!-- Mobile Records Section -->
                <div class="bg-gray-50 rounded-2xl p-6 mb-8 animate-fade-in" style="animation-delay: 0.2s">
                    <h2 class="text-xl font-bold text-center mb-6 text-blue-800">
                        🏆 {{ $isFrench ? 'Records Employés' : 'Employee Records' }}
                    </h2>
                    
                    <!-- Mobile Positive Records -->
                    <div class="bg-green-50 rounded-2xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">🌟</span>
                            <h3 class="text-lg font-bold text-green-800">{{ $isFrench ? 'Records Positifs' : 'Positive Records' }}</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="font-semibold text-green-700 text-sm">{{ $isFrench ? 'Plus Ponctuel' : 'Most Punctual' }}</p>
                                <div class="flex justify-between">
                                    <span class="text-sm">{{ $plusPonctuel->name }}</span>
                                    <span class="text-green-600 font-bold text-sm">{{ $plusPonctuel->taux_ponctualite }}%</span>
                                </div>
                            </div>
                            @php
                                $topWorker = $statistiquesHoraires->sortByDesc('heures_travaillees')->first();
                            @endphp
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="font-semibold text-green-700 text-sm">{{ $isFrench ? 'Plus d\'Heures' : 'Most Hours' }}</p>
                                <div class="flex justify-between">
                                    <span class="text-sm">{{ $topWorker->name }}</span>
                                    <span class="text-green-600 font-bold text-sm">{{ round($topWorker->heures_travaillees, 2) }} h/j</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Improvement Points -->
                    <div class="bg-red-50 rounded-2xl p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">⚠️</span>
                            <h3 class="text-lg font-bold text-red-800">{{ $isFrench ? 'Points d\'Amélioration' : 'Improvement Points' }}</h3>
                        </div>
                        <div class="space-y-3">
                            @foreach($plusAbsentParSecteur as $secteur => $employe)
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="font-semibold text-red-700 text-sm">{{ $isFrench ? 'Secteur' : 'Sector' }} {{ $secteur }}</p>
                                <div class="flex justify-between">
                                    <span class="text-sm">{{ $employe->name }}</span>
                                    <span class="text-red-600 font-bold text-sm">{{ $employe->nombre_absences }} {{ $isFrench ? 'abs.' : 'abs.' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile Consistency Records -->
                    <div class="bg-purple-50 rounded-2xl p-4">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">📊</span>
                            <h3 class="text-lg font-bold text-purple-800">{{ $isFrench ? 'Consistance' : 'Consistency' }}</h3>
                        </div>
                        <div class="space-y-3">
                            @php
                                $mostConsistentArrival = $variationHoraires->sortBy('variation_arrivee')->first();
                                $mostConsistentDeparture = $variationHoraires->sortBy('variation_depart')->first();
                            @endphp
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="font-semibold text-purple-700 text-sm">{{ $isFrench ? 'Plus Consistant' : 'Most Consistent' }}</p>
                                <div class="flex justify-between">
                                    <span class="text-sm">{{ $mostConsistentArrival->name }}</span>
                                    <span class="text-purple-600 font-bold text-sm">±{{ round($mostConsistentArrival->variation_arrivee, 2) }}h</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Employee Cards -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $isFrench ? 'Détails par Employé' : 'Employee Details' }}</h3>
                    @foreach($statistiquesGlobales as $stat)
                        <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right border-l-4 border-blue-500" style="animation-delay: {{ $loop->index * 0.05 }}s">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-blue-800">{{ $stat->name }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $stat->secteur ?? ($isFrench ? 'Non assigné' : 'Not assigned') }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-blue-50 p-3 rounded-xl text-center">
                                    <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Heures/Jour' : 'Hours/Day' }}</p>
                                    <p class="font-bold text-blue-700">{{ number_format($stat->moyenne_heures_jour, 1) }}h</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-xl text-center">
                                    <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Min. Retard' : 'Late Min.' }}</p>
                                    <p class="font-bold text-green-700">{{ $stat->total_minutes_retard }}min</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Respect des Horaires' : 'Schedule Compliance' }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($stat->taux_respect_horaires, 1) }}%</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ number_format($stat->taux_respect_horaires, 1) }}%"></div>
                                </div>
                            </div>

                            @php
                                $employeeVariation = $variationHoraires->where('name', $stat->name)->first();
                            @endphp
                            @if($employeeVariation)
                                <div class="bg-gray-50 p-3 rounded-xl">
                                    <p class="text-xs text-gray-600 mb-2">{{ $isFrench ? 'Variation Horaires' : 'Schedule Variation' }}</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Arrivée' : 'Arrival' }}</p>
                                            <p class="font-medium text-blue-700 text-sm">±{{ number_format($employeeVariation->variation_arrivee, 1) }}h</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Départ' : 'Departure' }}</p>
                                            <p class="font-medium text-blue-700 text-sm">±{{ number_format($employeeVariation->variation_depart, 1) }}h</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Export Actions -->
                <div class="mt-8 grid grid-cols-1 gap-3">
                    <button id="mobileExportCSV" class="w-full bg-green-600 text-white py-3 px-6 rounded-2xl shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200">
                        {{ $isFrench ? 'Exporter CSV' : 'Export CSV' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-8 bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen p-6">
            <div class="container mx-auto">
                @include('buttons')

                <header class="bg-white shadow-lg rounded-lg p-6 mb-8 flex justify-between items-center">
                    <div class="space-x-4">
                        <button id="exportCSV" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                            <a href="{{ route('statistiques.absences') }}">{{ $isFrench ? 'voir statistique sur les Absences' : 'view Absence statistics' }}</a>
                        </button>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-blue-800">{{ $isFrench ? 'Statistiques Horaires pour le mois courant' : 'Current Month Schedule Statistics' }}</h1>
                        <p class="text-gray-600">{{ $isFrench ? 'Analyse détaillée des performances et présences' : 'Detailed performance and attendance analysis' }}</p>
                    </div>
                    <div class="space-x-4">
                        <button id="exportCSV" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                            {{ $isFrench ? 'Exporter CSV' : 'Export CSV' }}
                        </button>
                    </div>
                </header>
                
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-4">{{ $isFrench ? 'Ponctualité Globale' : 'Overall Punctuality' }}</h3>
                        <canvas id="punctualityChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-4">{{ $isFrench ? 'Performance Globale' : 'Overall Performance' }}</h3>
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-2xl rounded-2xl p-8 col-span-full mt-8">
                    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 border-b-4 border-blue-500 pb-4">
                        🏆 {{ $isFrench ? 'Tableaux des Records Employés' : 'Employee Records Tables' }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Records Positifs -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">🌟</span>
                                <h3 class="text-2xl font-bold text-green-800">{{ $isFrench ? 'Records Positifs' : 'Positive Records' }}</h3>
                            </div>
                            <ul class="space-y-3">
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-green-700">{{ $isFrench ? 'Plus Ponctuel' : 'Most Punctual' }}</span>
                                    <div class="flex justify-between">
                                        <span>{{ $plusPonctuel->name }}</span>
                                        <span class="text-green-600 font-bold">{{ $plusPonctuel->taux_ponctualite }}%</span>
                                    </div>
                                </li>
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-green-700">{{ $isFrench ? 'Plus d\'Heures Travaillées' : 'Most Hours Worked' }}</span>
                                    @php
                                        $topWorker = $statistiquesHoraires->sortByDesc('heures_travaillees')->first();
                                    @endphp
                                    <div class="flex justify-between">
                                        <span>{{ $topWorker->name }}</span>
                                        <span class="text-green-600 font-bold">{{ round($topWorker->heures_travaillees, 2) }} h/j</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Points d'Amélioration -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">⚠️</span>
                                <h3 class="text-2xl font-bold text-red-800">{{ $isFrench ? 'Points d\'Amélioration' : 'Improvement Points' }}</h3>
                            </div>
                            <ul class="space-y-3">
                                @foreach($plusAbsentParSecteur as $secteur => $employe)
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-red-700">{{ $isFrench ? 'Secteur' : 'Sector' }} {{ $secteur }}</span>
                                    <div class="flex justify-between">
                                        <span>{{ $employe->name }}</span>
                                        <span class="text-red-600 font-bold">{{ $employe->nombre_absences }} {{ $isFrench ? 'abs.' : 'abs.' }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Records Variabilité -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">📊</span>
                                <h3 class="text-2xl font-bold text-purple-800">{{ $isFrench ? 'Variabilité & Consistance' : 'Variability & Consistency' }}</h3>
                            </div>
                            <ul class="space-y-3">
                                @php
                                    $mostConsistentArrival = $variationHoraires->sortBy('variation_arrivee')->first();
                                    $mostConsistentDeparture = $variationHoraires->sortBy('variation_depart')->first();
                                @endphp
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-purple-700">{{ $isFrench ? 'Plus Consistant (Arrivée)' : 'Most Consistent (Arrival)' }}</span>
                                    <div class="flex justify-between">
                                        <span>{{ $mostConsistentArrival->name }}</span>
                                        <span class="text-purple-600 font-bold">±{{ round($mostConsistentArrival->variation_arrivee, 2) }}h</span>
                                    </div>
                                </li>
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-purple-700">{{ $isFrench ? 'Plus Consistant (Départ)' : 'Most Consistent (Departure)' }}</span>
                                    <div class="flex justify-between">
                                        <span>{{ $mostConsistentDeparture->name }}</span>
                                        <span class="text-purple-600 font-bold">±{{ round($mostConsistentDeparture->variation_depart, 2) }}h</span>
                                    </div>
                                </li>
                                @php
                                    $mostInconsistentArrival = $variationHoraires->sortByDesc('variation_arrivee')->first();
                                @endphp
                                <li class="bg-white p-3 rounded-lg shadow">
                                    <span class="font-semibold text-purple-700">{{ $isFrench ? 'Plus Variable (Horaires)' : 'Most Variable (Schedule)' }}</span>
                                    <div class="flex justify-between">
                                        <span>{{ $mostInconsistentArrival->name }}</span>
                                        <span class="text-purple-600 font-bold">±{{ round($mostInconsistentArrival->variation_arrivee, 2) }}h</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="statisticsContainer" class="mt-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($statistiquesGlobales as $stat)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 space-y-4 border-l-4 border-blue-500">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-xl font-semibold text-blue-800">{{ $stat->name }}</h3>
                                    <span class="text-sm text-gray-500">{{ $stat->secteur ?? ($isFrench ? 'Non assigné' : 'Not assigned') }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Heures/Jour' : 'Hours/Day' }}</p>
                                        <p class="font-bold text-blue-700">{{ number_format($stat->moyenne_heures_jour, 1) }} h</p>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Minutes Retard' : 'Late Minutes' }}</p>
                                        <p class="font-bold text-green-700">{{ $stat->total_minutes_retard }} min</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $isFrench ? 'Respect des Horaires' : 'Schedule Compliance' }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div
                                            class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ number_format($stat->taux_respect_horaires, 1) }}%"
                                        ></div>
                                    </div>
                                    <p class="text-xs text-gray-500 text-right mt-1">
                                        {{ number_format($stat->taux_respect_horaires, 1) }}%
                                    </p>
                                </div>

                                @php
                                    $employeeVariation = $variationHoraires->where('name', $stat->name)->first();
                                @endphp
                                @if($employeeVariation)
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-2">{{ $isFrench ? 'Variation Horaires' : 'Schedule Variation' }}</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <p class="text-xs text-gray-500">{{ $isFrench ? 'Arrivée' : 'Arrival' }}</p>
                                                <p class="font-medium text-blue-700">
                                                    ±{{ number_format($employeeVariation->variation_arrivee, 1) }}h
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">{{ $isFrench ? 'Départ' : 'Departure' }}</p>
                                                <p class="font-medium text-blue-700">
                                                    ±{{ number_format($employeeVariation->variation_depart, 1) }}h
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Desktop Ponctualité Chart
    const punctualityCtx = document.getElementById('punctualityChart');
    if (punctualityCtx) {
        new Chart(punctualityCtx, {
            type: 'bar',
            data: {
                labels: @json($tauxPonctualite->pluck('name')),
                datasets: [{
                    label: '{{ $isFrench ? "Taux de Ponctualité (%)" : "Punctuality Rate (%)" }}',
                    data: @json($tauxPonctualite->pluck('taux_ponctualite')),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // Desktop Performance Chart
    const performanceCtx = document.getElementById('performanceChart');
    if (performanceCtx) {
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: @json($workTimePerDay->pluck('work_date')),
                datasets: [{
                    label: '{{ $isFrench ? "Heures Travaillées" : "Hours Worked" }}',
                    data: @json($workTimePerDay->pluck('hours_worked')),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    // Mobile Charts
    const mobilePunctualityCtx = document.getElementById('mobilePunctualityChart');
    if (mobilePunctualityCtx) {
        new Chart(mobilePunctualityCtx, {
            type: 'doughnut',
            data: {
                labels: @json($tauxPonctualite->take(5)->pluck('name')),
                datasets: [{
                    data: @json($tauxPonctualite->take(5)->pluck('taux_ponctualite')),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 69, 19, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            fontSize: 10
                        }
                    }
                }
            }
        });
    }

    const mobilePerformanceCtx = document.getElementById('mobilePerformanceChart');
    if (mobilePerformanceCtx) {
        new Chart(mobilePerformanceCtx, {
            type: 'line',
            data: {
                labels: @json($workTimePerDay->slice(-7)->pluck('work_date')),                datasets: [{
                    label: '{{ $isFrench ? "Heures" : "Hours" }}',
                    data: @json($workTimePerDay->slice(-7)->pluck('hours_worked')),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    }
                }
            }
        });
    }

    // Export functionality
    const exportCSV = () => {
        const data = @json($statistiquesGlobales);
        let csvContent = "data:text/csv;charset=utf-8,";

        // Headers
        csvContent += "{{ $isFrench ? 'Nom,Secteur,Moyenne Heures/Jour,Minutes Retard,Taux Respect Horaires' : 'Name,Sector,Average Hours/Day,Late Minutes,Schedule Compliance Rate' }}\n";

        // Data
        data.forEach(stat => {
            csvContent += `${stat.name},${stat.secteur || ''},${stat.moyenne_heures_jour},${stat.total_minutes_retard},${stat.taux_respect_horaires}\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "{{ $isFrench ? 'statistiques_employes.csv' : 'employee_statistics.csv' }}");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Desktop export button
    const exportBtn = document.getElementById('exportCSV');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportCSV);
    }

    // Mobile export button
    const mobileExportBtn = document.getElementById('mobileExportCSV');
    if (mobileExportBtn) {
        mobileExportBtn.addEventListener('click', exportCSV);
    }
});
</script>
@endsection
