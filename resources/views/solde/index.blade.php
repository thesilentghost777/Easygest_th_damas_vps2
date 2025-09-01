@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')

        <!-- Header avec animation -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mobile-card animate-fade-in">
                {{ $isFrench ? 'Tableau de bord financier' : 'Financial Dashboard' }}
            </h1>
        </div>

        <!-- Statistiques principales avec animations mobiles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Solde total' : 'Total Balance' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-blue-600">{{ number_format($soldeTotalEntreprise, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center pulse-ring">
                        <i class="mdi mdi-wallet text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>


            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Total Salaires (mois courant)' : 'Total Salaries (current month)' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-green-600">{{ number_format($salairesTotauxMois, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-account-cash text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Total Avances (mois courant)' : 'Total Advances (current month)' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-yellow-600">{{ number_format($avancesTotalesMois, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-cash-plus text-xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Total Remboursement (mois courant)' : 'Total Reimbursements (current month)' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-blue-600">{{ number_format($remboursementTotalesMois, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-cash-plus text-xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques secondaires -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Total Manquants et Délis' : 'Total Missing and Delays' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-red-500">{{ number_format($manquantsEtDelis, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-alert-circle text-xl text-red-500"></i>
                    </div>
                </div>
            </div>

            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Total Primes' : 'Total Bonuses' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-purple-600">{{ number_format($primesTotalesMois, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-gift text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">
                            {{ $isFrench ? 'Montant Caisse Sociale' : 'Social Fund Amount' }}
                        </h3>
                        <p class="text-xl md:text-3xl font-bold text-indigo-600">{{ number_format($caisseSociale, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                    </div>
                    <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="mdi mdi-shield-account text-xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prévisions Salariales avec style mobile -->
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-lg p-4 md:p-6 mb-6 md:mb-8">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4">
                {{ $isFrench ? 'Prévisions Salariales du Mois' : 'Monthly Salary Forecasts' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-gray-50 rounded-2xl md:rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">
                        {{ $isFrench ? 'Montant Total Prévisionnel' : 'Total Forecast Amount' }}
                    </h4>
                    <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ number_format($montantPrevisionnel, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl md:rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">
                        {{ $isFrench ? 'Caisse Sociale' : 'Social Fund' }}
                    </h4>
                    <p class="text-xl md:text-2xl font-bold text-purple-600">{{ number_format($montantCaisseSocialetotal, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl md:rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">
                        {{ $isFrench ? 'Montant Enveloppes' : 'Envelope Amount' }}
                    </h4>
                    <p class="text-xl md:text-2xl font-bold text-green-600">{{ number_format($montantEnveloppes, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                </div>
            </div>
        </div>

      

        <!-- Graphiques avec design mobile responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">
            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
                    {{ $isFrench ? 'Évolution mensuelle' : 'Monthly Evolution' }}
                </h3>
                <div class="h-64 md:h-96">
                    <canvas id="statsMonthlyChart"></canvas>
                </div>
            </div>
            <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-4 md:p-6">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
                    {{ $isFrench ? 'Évolution annuelle' : 'Annual Evolution' }}
                </h3>
                <div class="h-64 md:h-96">
                    <canvas id="statsYearlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration responsive pour mobile
    const mobileConfig = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: window.innerWidth < 768 ? 'bottom' : 'top',
                labels: {
                    fontSize: window.innerWidth < 768 ? 10 : 12,
                    padding: window.innerWidth < 768 ? 10 : 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    fontSize: window.innerWidth < 768 ? 10 : 12,
                    callback: function(value) {
                        return value.toLocaleString('fr-FR') + ' {{ $isFrench ? "FCFA" : "XAF" }}';
                    }
                }
            },
            x: {
                ticks: {
                    fontSize: window.innerWidth < 768 ? 10 : 12,
                    maxRotation: window.innerWidth < 768 ? 45 : 0
                }
            }
        }
    };

    const monthlyCtx = document.getElementById('statsMonthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($statsParMois->pluck('mois')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Salaires" : "Salaries" }}',
                data: {!! json_encode($statsParMois->pluck('salaires')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: '{{ $isFrench ? "Avances" : "Advances" }}',
                data: {!! json_encode($statsParMois->pluck('avances')) !!},
                borderColor: 'rgb(234, 179, 8)',
                backgroundColor: 'rgba(234, 179, 8, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: '{{ $isFrench ? "Délis" : "Delays" }}',
                data: {!! json_encode($statsParMois->pluck('delis')) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: '{{ $isFrench ? "Primes" : "Bonuses" }}',
                data: {!! json_encode($statsParMois->pluck('primes')) !!},
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: mobileConfig
    });

    const yearlyCtx = document.getElementById('statsYearlyChart').getContext('2d');
    new Chart(yearlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($statsParAnnee->pluck('annee')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Salaires" : "Salaries" }}',
                data: {!! json_encode($statsParAnnee->pluck('salaires')) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2,
                borderRadius: 8
            }, {
                label: '{{ $isFrench ? "Avances" : "Advances" }}',
                data: {!! json_encode($statsParAnnee->pluck('avances')) !!},
                backgroundColor: 'rgba(234, 179, 8, 0.8)',
                borderColor: 'rgb(234, 179, 8)',
                borderWidth: 2,
                borderRadius: 8
            }, {
                label: '{{ $isFrench ? "Délis" : "Delays" }}',
                data: {!! json_encode($statsParAnnee->pluck('delis')) !!},
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 2,
                borderRadius: 8
            }, {
                label: '{{ $isFrench ? "Primes" : "Bonuses" }}',
                data: {!! json_encode($statsParAnnee->pluck('primes')) !!},
                backgroundColor: 'rgba(147, 51, 234, 0.8)',
                borderColor: 'rgb(147, 51, 234)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: mobileConfig
    });

    // Animation d'entrée pour les cartes
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.mobile-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endpush
@endsection
