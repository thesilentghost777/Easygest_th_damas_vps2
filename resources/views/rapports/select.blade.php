@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Sélection des Rapports" : "Reports Selection" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
            <h1 class="text-3xl font-bold text-gray-800 mt-4">
                {{ $isFrench ? "Sélection des Rapports" : "Reports Selection" }}
            </h1>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
            <!-- Rapport Employés -->
            <a href="{{ route('rapports.index') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-purple-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapport Employés" : "Employees Report" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Informations détaillées sur les employés" : "Detailed information about employees" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Salaire -->
            <a href="{{ route('rapport_salaire') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapport Salaire" : "Salary Report" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "État des salaires versés" : "Status of paid salaries" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Avance Salaire -->
            <a href="{{ route('avances_salaire') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Avance Salaire" : "Salary Advance" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Suivi des avances sur salaire" : "Salary advance tracking" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Versement CP -->
            <a href="{{ route('versements_chef') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-yellow-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Versement" : "Payment" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Suivi des Versements" : "Payment tracking" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Transaction Monétaire -->
            <a href="{{ route('transactions') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-indigo-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Transactions" : "Transactions" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "État des transactions monétaires" : "Monetary transactions status" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Production (Global) -->
            <a href="{{ route('rapports.production.global') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-cyan-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Production Globale" : "Global Production" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Aperçu global de la production" : "Global production overview" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Vente -->
            <a href="{{ route('rapports.vente.global') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-emerald-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapport Vente" : "Sales Report" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Analyse des ventes" : "Sales analysis" }}
                    </p>
                </div>
            </a>

            <!-- Rapports Manquants -->
            <a href="{{ route('deductions') }}" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-orange-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapports Déductions" : "Deductions Reports" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Suivis des déductions" : "Deductions tracking" }}
                    </p>
                </div>
            </a>

            <!-- Rapport Delis -->
            <a href="depenses" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-rose-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapport Dépense" : "Expense Report" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Suivi des incidents" : "Incident tracking" }}
                    </p>
                </div>
            </a>

            <!-- Rapports Commande -->
            <a href="commandes" 
               class="bg-white rounded-xl shadow-sm p-4 lg:p-6 hover:shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 lg:active:scale-105 border border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-fuchsia-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-fuchsia-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-sm lg:text-lg font-semibold text-gray-800 mb-1 lg:mb-2">
                        {{ $isFrench ? "Rapports Commande" : "Order Reports" }}
                    </h3>
                    <p class="text-xs lg:text-sm text-gray-600 text-center">
                        {{ $isFrench ? "Suivi des commandes clients" : "Customer order tracking" }}
                    </p>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    .hover\:scale-105:hover {
        transform: scale(1.02);
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
    
    /* Simulate vibration feedback */
    a:active {
        animation: vibrate 0.1s ease-in-out;
    }
}

@keyframes vibrate {
    0% { transform: scale(1) rotate(0deg); }
    25% { transform: scale(0.95) rotate(-1deg); }
    50% { transform: scale(0.95) rotate(1deg); }
    75% { transform: scale(0.95) rotate(-1deg); }
    100% { transform: scale(0.95) rotate(0deg); }
}
</style>

<script>
// Add vibration feedback on mobile
document.querySelectorAll('a').forEach(link => {
    link.addEventListener('touchstart', function() {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
});
</script>
@endsection