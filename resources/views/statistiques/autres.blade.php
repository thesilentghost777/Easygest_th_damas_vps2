@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <br><br>    

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Sections -->
                <div class="space-y-6">
                    <!-- Dépenses Section -->
                    <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500 animate-slide-in-right" style="animation-delay: 0.1s">
                        <h2 class="text-lg font-bold text-blue-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Dépenses Du CP' : 'Production Chief Expense Statistics' }}
                        </h2>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-blue-600 mb-1">
                                    {{ $isFrench ? 'Total Dépenses' : 'Total Expenses' }}
                                </p>
                                <p class="font-bold text-blue-700 text-sm">{{ number_format($depenseStats['total'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-green-600 mb-1">
                                    {{ $isFrench ? 'Nombre' : 'Count' }}
                                </p>
                                <p class="font-bold text-green-700 text-sm">{{ $depenseStats['count'] }}</p>
                            </div>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-xl">
                            <p class="text-amber-700 text-xs text-center">
                                {{ $isFrench ? 'Graphiques détaillés disponibles sur PC' : 'Detailed charts available on PC' }}
                            </p>
                        </div>
                    </div>

                    <!-- Retenues Section -->
                    <div class="bg-red-50 rounded-2xl p-4 border-l-4 border-red-500 animate-slide-in-right" style="animation-delay: 0.2s">
                        <h2 class="text-lg font-bold text-red-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Retenues' : 'Deduction Statistics' }}
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-red-600 mb-1">
                                    {{ $isFrench ? 'Manquants' : 'Missing' }}
                                </p>
                                <p class="font-bold text-red-700 text-sm">{{ number_format($retenueStats['totalManquants'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-green-600 mb-1">
                                    {{ $isFrench ? 'Remboursements' : 'Refunds' }}
                                </p>
                                <p class="font-bold text-green-700 text-sm">{{ number_format($retenueStats['totalRemboursements'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-yellow-600 mb-1">
                                    {{ $isFrench ? 'Prêts' : 'Loans' }}
                                </p>
                                <p class="font-bold text-yellow-700 text-sm">{{ number_format($retenueStats['totalPrets'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-purple-600 mb-1">
                                    {{ $isFrench ? 'Caisse Sociale' : 'Social Fund' }}
                                </p>
                                <p class="font-bold text-purple-700 text-sm">{{ number_format($retenueStats['totalCaisseSociale'], 2) }} XAF</p>
                            </div>
                        </div>
                    </div>

                    <!-- Primes Section -->
                    <div class="bg-green-50 rounded-2xl p-4 border-l-4 border-green-500 animate-slide-in-right" style="animation-delay: 0.3s">
                        <h2 class="text-lg font-bold text-green-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Primes' : 'Bonus Statistics' }}
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-green-600 mb-1">
                                    {{ $isFrench ? 'Total Primes' : 'Total Bonuses' }}
                                </p>
                                <p class="font-bold text-green-700 text-sm">{{ number_format($primeStats['totalPrimes'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-blue-600 mb-1">
                                    {{ $isFrench ? 'Prime Moyenne' : 'Average Bonus' }}
                                </p>
                                <p class="font-bold text-blue-700 text-sm">{{ number_format($primeStats['avgPrime'], 2) }} XAF</p>
                            </div>
                        </div>
                    </div>

                    <!-- Congés Section -->
                    <div class="bg-purple-50 rounded-2xl p-4 border-l-4 border-purple-500 animate-slide-in-right" style="animation-delay: 0.4s">
                        <h2 class="text-lg font-bold text-purple-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Congés' : 'Leave Statistics' }}
                        </h2>
                        <div class="bg-white p-3 rounded-xl">
                            <p class="text-purple-700 text-sm text-center">
                                {{ $isFrench ? 'Données de congés et repos disponibles' : 'Leave and rest data available' }}
                            </p>
                        </div>
                    </div>

                    <!-- Délits Section -->
                    <div class="bg-orange-50 rounded-2xl p-4 border-l-4 border-orange-500 animate-slide-in-right" style="animation-delay: 0.5s">
                        <h2 class="text-lg font-bold text-orange-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Délits' : 'Offense Statistics' }}
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-red-600 mb-1">
                                    {{ $isFrench ? 'Total Délits' : 'Total Offenses' }}
                                </p>
                                <p class="font-bold text-red-700 text-sm">{{ $deliStats['totalDelits'] }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-red-600 mb-1">
                                    {{ $isFrench ? 'Montant Total' : 'Total Amount' }}
                                </p>
                                <p class="font-bold text-red-700 text-sm">{{ number_format($deliStats['montantTotal'], 2) }} XAF</p>
                            </div>
                        </div>
                    </div>

                    <!-- Salaires Section -->
                    <div class="bg-indigo-50 rounded-2xl p-4 border-l-4 border-indigo-500 animate-slide-in-right" style="animation-delay: 0.6s">
                        <h2 class="text-lg font-bold text-indigo-800 mb-4">
                            {{ $isFrench ? 'Statistiques des Salaires' : 'Salary Statistics' }}
                        </h2>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-green-600 mb-1">
                                    {{ $isFrench ? 'Total Mensuel' : 'Monthly Total' }}
                                </p>
                                <p class="font-bold text-green-700 text-sm">{{ number_format($salaireStats['totalMensuel'], 2) }} XAF</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-blue-600 mb-1">
                                    {{ $isFrench ? 'Salaire Moyen' : 'Average Salary' }}
                                </p>
                                <p class="font-bold text-blue-700 text-sm">{{ number_format($salaireStats['moyenneSalaire'], 2) }} XAF</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Info -->
                <div class="mt-6 bg-blue-100 rounded-2xl p-4 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-blue-700 text-sm">
                            {{ $isFrench ? 'Pour une analyse complète avec graphiques interactifs, utilisez la version ordinateur.' : 'For complete analysis with interactive charts, use the desktop version.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @include('buttons')

                <!-- Dépenses Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        {{ $isFrench ? 'Statistiques des Dépenses du CP' : 'Production Chief Expense Statistics' }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Dépenses' : 'Total Expenses' }}</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($depenseStats['total'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Nombre de Dépenses' : 'Number of Expenses' }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $depenseStats['count'] }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Dépenses par Type' : 'Expenses by Type' }}</h3>
                            <canvas id="depenseTypeChart"></canvas>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Évolution Mensuelle' : 'Monthly Evolution' }}</h3>
                            <canvas id="depenseMonthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Retenues Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Statistiques des Retenues' : 'Deduction Statistics' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Manquants' : 'Total Missing' }}</h3>
                            <p class="text-3xl font-bold text-red-600">{{ number_format($retenueStats['totalManquants'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Remboursements' : 'Total Refunds' }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($retenueStats['totalRemboursements'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Prêts' : 'Total Loans' }}</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ number_format($retenueStats['totalPrets'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Caisse Sociale' : 'Social Fund' }}</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ number_format($retenueStats['totalCaisseSociale'], 2) }} XAF</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Top 5 Employés avec Manquants' : 'Top 5 Employees with Missing Items' }}</h3>
                        <canvas id="manquantsChart"></canvas>
                    </div>
                </div>

                <!-- Primes Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Statistiques des Primes' : 'Bonus Statistics' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Primes' : 'Total Bonuses' }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($primeStats['totalPrimes'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Prime Moyenne' : 'Average Bonus' }}</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($primeStats['avgPrime'], 2) }} XAF</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Distribution des Primes' : 'Bonus Distribution' }}</h3>
                        <canvas id="primesDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Congés Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Statistiques des Congés' : 'Leave Statistics' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Jours de Repos' : 'Rest Days' }}</h3>
                            <canvas id="reposChart"></canvas>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Raisons des Congés' : 'Leave Reasons' }}</h3>
                            <canvas id="congesRaisonChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Délits Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Statistiques des Délits' : 'Offense Statistics' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Délits' : 'Total Offenses' }}</h3>
                            <p class="text-3xl font-bold text-red-600">{{ $deliStats['totalDelits'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Montant Total' : 'Total Amount' }}</h3>
                            <p class="text-3xl font-bold text-red-600">{{ number_format($deliStats['montantTotal'], 2) }} XAF</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Incidents par Mois' : 'Incidents by Month' }}</h3>
                            <canvas id="incidentsChart"></canvas>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Top 5 Types de Délits' : 'Top 5 Offense Types' }}</h3>
                            <canvas id="topDelitsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Salaires Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Statistiques des Salaires' : 'Salary Statistics' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Total Mensuel' : 'Monthly Total' }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($salaireStats['totalMensuel'], 2) }} XAF</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Salaire Moyen' : 'Average Salary' }}</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($salaireStats['moyenneSalaire'], 2) }} XAF</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Distribution des Salaires' : 'Salary Distribution' }}</h3>
                        <canvas id="salairesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize charts on desktop
    if (window.innerWidth >= 768) {
        // Dépenses par Type
        new Chart(document.getElementById('depenseTypeChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($depenseStats['byType']->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($depenseStats['byType']->pluck('total')) !!},
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
                }]
            }
        });

        // Évolution Mensuelle des Dépenses
        new Chart(document.getElementById('depenseMonthlyChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($depenseStats['monthly']->pluck('month')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Dépenses mensuelles" : "Monthly expenses" }}',
                    data: {!! json_encode($depenseStats['monthly']->pluck('total')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            }
        });

        // Top 5 Employés avec Manquants
        new Chart(document.getElementById('manquantsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($retenueStats['employesManquants']->pluck('name')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Manquants" : "Missing" }}',
                    data: {!! json_encode($retenueStats['employesManquants']->pluck('total_manquants')) !!},
                    backgroundColor: 'rgb(239, 68, 68)'
                }]
            },
            options: {
                indexAxis: 'y'
            }
        });

        // Distribution des Primes
        new Chart(document.getElementById('primesDistributionChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($primeStats['distribution']->pluck('name')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Total des primes" : "Total bonuses" }}',
                    data: {!! json_encode($primeStats['distribution']->pluck('total_primes')) !!},
                    backgroundColor: 'rgb(16, 185, 129)'
                }]
            }
        });

        // Jours de Repos
        new Chart(document.getElementById('reposChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($congeStats['joursRepos']->pluck('jour')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Nombre d\'employés" : "Number of employees" }}',
                    data: {!! json_encode($congeStats['joursRepos']->pluck('count')) !!},
                    backgroundColor: 'rgb(59, 130, 246)'
                }]
            }
        });

        // Raisons des Congés
        new Chart(document.getElementById('congesRaisonChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($congeStats['raisonConges']->pluck('raison_c')) !!},
                datasets: [{
                    data: {!! json_encode($congeStats['raisonConges']->pluck('count')) !!},
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6']
                }]
            }
        });

        // Incidents par Mois
        new Chart(document.getElementById('incidentsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($deliStats['incidentsByMonth']->pluck('month')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Nombre d\'incidents" : "Number of incidents" }}',
                    data: {!! json_encode($deliStats['incidentsByMonth']->pluck('count')) !!},
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.1
                }]
            }
        });

        // Top Délits
        new Chart(document.getElementById('topDelitsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($deliStats['topDelits']->pluck('nom')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Nombre d\'occurrences" : "Number of occurrences" }}',
                    data: {!! json_encode($deliStats['topDelits']->pluck('count')) !!},
                    backgroundColor: 'rgb(239, 68, 68)'
                }]
            },
            options: {
                indexAxis: 'y'
            }
        });

        // Distribution des Salaires
        new Chart(document.getElementById('salairesChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($salaireStats['distribution']->pluck('name')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Salaire de base" : "Base salary" }}',
                    data: {!! json_encode($salaireStats['distribution']->pluck('somme')) !!},
                    backgroundColor: 'rgb(16, 185, 129)'
                }, {
                    label: '{{ $isFrench ? "Salaire effectif" : "Effective salary" }}',
                    data: {!! json_encode($salaireStats['distribution']->pluck('somme_effective_mois')) !!},
                    backgroundColor: 'rgb(59, 130, 246)'
                }]
            }
        });
    }
});
</script>
@endpush

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
}
</style>
@endsection
