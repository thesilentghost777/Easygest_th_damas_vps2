@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
   <br><br>
    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Stats Cards -->
                <div class="space-y-4 mb-6">
                    <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.1s">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-50 rounded-xl">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Salaires' : 'Salaries' }}
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                                <span class="font-bold text-blue-600">{{ number_format($statsSalaires['total_salaires'], 0, ',', ' ') }} XAF</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'En attente:' : 'Pending:' }}</span>
                                <span class="font-semibold">{{ $statsSalaires['salaires_en_attente'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Moyenne:' : 'Average:' }}</span>
                                <span class="font-semibold">{{ number_format($statsSalaires['moyenne_salaires'], 0, ',', ' ') }} XAF</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.2s">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-50 rounded-xl">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Avances' : 'Advances' }}
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                                <span class="font-bold text-green-600">{{ number_format($statsAvances['total_avances'], 0, ',', ' ') }} XAF</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'En cours:' : 'Ongoing:' }}</span>
                                <span class="font-semibold">{{ $statsAvances['avances_en_cours'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? '% Employés:' : '% Employees:' }}</span>
                                <span class="font-semibold">{{ number_format($statsAvances['pourcentage_employes_avec_avance'], 1) }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-50 rounded-xl">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Primes' : 'Bonuses' }}
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                                <span class="font-bold text-purple-600">{{ number_format($statsPrimes['total_primes'], 0, ',', ' ') }} XAF</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Moyenne:' : 'Average:' }}</span>
                                <span class="font-semibold">{{ number_format($statsPrimes['moyenne_prime_par_employe'], 0, ',', ' ') }} XAF</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.4s">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-50 rounded-xl">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isFrench ? 'Délis' : 'Offenses' }}
                            </h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Nombre:' : 'Count:' }}</span>
                                <span class="font-bold text-red-600">{{ $statsDelis['total_delis'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Montant:' : 'Amount:' }}</span>
                                <span class="font-semibold">{{ number_format($statsDelis['montant_total_delis'], 0, ',', ' ') }} XAF</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Deductions Section -->
                <div class="bg-blue-50 rounded-2xl p-4 mb-6 border-l-4 border-blue-500 animate-fade-in">
                    <h3 class="text-lg font-bold text-blue-800 mb-4">
                        {{ $isFrench ? 'Déductions' : 'Deductions' }}
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-red-600 mb-1">
                                {{ $isFrench ? 'Manquants' : 'Missing' }}
                            </p>
                            <p class="font-bold text-red-700 text-sm">{{ number_format($statsDeductions['total_manquants'], 0, ',', ' ') }} XAF</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-blue-600 mb-1">
                                {{ $isFrench ? 'Remboursements' : 'Refunds' }}
                            </p>
                            <p class="font-bold text-blue-700 text-sm">{{ number_format($statsDeductions['total_remboursements'], 0, ',', ' ') }} XAF</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-yellow-600 mb-1">
                                {{ $isFrench ? 'Prêts' : 'Loans' }}
                            </p>
                            <p class="font-bold text-yellow-700 text-sm">{{ number_format($statsDeductions['total_prets'], 0, ',', ' ') }} XAF</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-green-600 mb-1">
                                {{ $isFrench ? 'Caisse sociale' : 'Social Fund' }}
                            </p>
                            <p class="font-bold text-green-700 text-sm">{{ number_format($statsDeductions['total_caisse_sociale'], 0, ',', ' ') }} XAF</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Charts Info -->
                <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500 animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-amber-700 text-sm">
                            {{ $isFrench ? 'Les graphiques détaillés sont disponibles sur la version ordinateur pour une meilleure visualisation.' : 'Detailed charts are available on the desktop version for better visualization.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @include('buttons')

                <h1 class="text-3xl font-bold text-gray-900 mb-8">
                    {{ $isFrench ? 'Tableau de bord des statistiques' : 'Statistics Dashboard' }}
                </h1>

                <!-- Desktop Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Salaires' : 'Salaries' }}
                        </h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }} <span class="font-semibold text-gray-900">{{ number_format($statsSalaires['total_salaires'], 0, ',', ' ') }} FCFA</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'En attente:' : 'Pending:' }} <span class="font-semibold text-gray-900">{{ $statsSalaires['salaires_en_attente'] }}</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Moyenne:' : 'Average:' }} <span class="font-semibold text-gray-900">{{ number_format($statsSalaires['moyenne_salaires'], 0, ',', ' ') }} FCFA</span></p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Avances' : 'Advances' }}
                        </h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }} <span class="font-semibold text-gray-900">{{ number_format($statsAvances['total_avances'], 0, ',', ' ') }} FCFA</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'En cours:' : 'Ongoing:' }} <span class="font-semibold text-gray-900">{{ $statsAvances['avances_en_cours'] }}</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? '% Employés avec avance:' : '% Employees with advance:' }} <span class="font-semibold text-gray-900">{{ number_format($statsAvances['pourcentage_employes_avec_avance'], 1) }}%</span></p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Primes' : 'Bonuses' }}
                        </h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Total:' : 'Total:' }} <span class="font-semibold text-gray-900">{{ number_format($statsPrimes['total_primes'], 0, ',', ' ') }} FCFA</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Moyenne/employé:' : 'Average/employee:' }} <span class="font-semibold text-gray-900">{{ number_format($statsPrimes['moyenne_prime_par_employe'], 0, ',', ' ') }} FCFA</span></p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Délis' : 'Offenses' }}
                        </h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Nombre total:' : 'Total count:' }} <span class="font-semibold text-gray-900">{{ $statsDelis['total_delis'] }}</span></p>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Montant total:' : 'Total amount:' }} <span class="font-semibold text-gray-900">{{ number_format($statsDelis['montant_total_delis'], 0, ',', ' ') }} FCFA</span></p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Deductions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        {{ $isFrench ? 'Déductions' : 'Deductions' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="p-4 bg-red-50 rounded-lg">
                            <p class="text-sm text-red-600">{{ $isFrench ? 'Manquants' : 'Missing' }}</p>
                            <p class="text-2xl font-bold text-red-700">{{ number_format($statsDeductions['total_manquants'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600">{{ $isFrench ? 'Remboursements' : 'Refunds' }}</p>
                            <p class="text-2xl font-bold text-blue-700">{{ number_format($statsDeductions['total_remboursements'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-600">{{ $isFrench ? 'Prêts' : 'Loans' }}</p>
                            <p class="text-2xl font-bold text-yellow-700">{{ number_format($statsDeductions['total_prets'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-600">{{ $isFrench ? 'Caisse sociale' : 'Social Fund' }}</p>
                            <p class="text-2xl font-bold text-green-700">{{ number_format($statsDeductions['total_caisse_sociale'], 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Tendances mensuelles - Salaires et Avances' : 'Monthly Trends - Salaries and Advances' }}
                        </h3>
                        <div class="h-80">
                            <canvas id="salairesAvancesChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $isFrench ? 'Tendances mensuelles - Délis' : 'Monthly Trends - Offenses' }}
                        </h3>
                        <div class="h-80">
                            <canvas id="delisChart"></canvas>
                        </div>
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
        const salairesAvancesCtx = document.getElementById('salairesAvancesChart').getContext('2d');
        new Chart(salairesAvancesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($tendances['salaires']->pluck('mois')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Salaires" : "Salaries" }}',
                    data: {!! json_encode($tendances['salaires']->pluck('total')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }, {
                    label: '{{ $isFrench ? "Avances" : "Advances" }}',
                    data: {!! json_encode($tendances['avances']->pluck('total')) !!},
                    borderColor: 'rgb(239, 68, 68)',
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
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                }
            }
        });

        const delisCtx = document.getElementById('delisChart').getContext('2d');
        new Chart(delisCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($tendances['delis']->pluck('mois')) !!},
                datasets: [{
                    label: '{{ $isFrench ? "Délis" : "Offenses" }}',
                    data: {!! json_encode($tendances['delis']->pluck('total')) !!},
                    backgroundColor: 'rgba(245, 158, 11, 0.5)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                }
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
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection
