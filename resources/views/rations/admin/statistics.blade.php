@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isFrench ? 'Statistiques des Rations' : 'Ration Statistics' }}
        </h2>
    </x-slot>

    <style>
        /* Animations pour mobile */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .mobile-card {
                animation: fadeInUp 0.6s ease-out;
                transform: translateZ(0);
                backface-visibility: hidden;
            }

            .mobile-card:nth-child(1) { animation-delay: 0.1s; }
            .mobile-card:nth-child(2) { animation-delay: 0.2s; }
            .mobile-card:nth-child(3) { animation-delay: 0.3s; }

            .mobile-stat-item {
                animation: slideInLeft 0.5s ease-out;
                animation-fill-mode: both;
            }

            .mobile-stat-item:nth-child(1) { animation-delay: 0.1s; }
            .mobile-stat-item:nth-child(2) { animation-delay: 0.2s; }
            .mobile-stat-item:nth-child(3) { animation-delay: 0.3s; }
            .mobile-stat-item:nth-child(4) { animation-delay: 0.4s; }
            .mobile-stat-item:nth-child(5) { animation-delay: 0.5s; }
            .mobile-stat-item:nth-child(6) { animation-delay: 0.6s; }

            .mobile-table-row {
                animation: bounceIn 0.5s ease-out;
                animation-fill-mode: both;
            }

            .touch-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .touch-card:active {
                transform: scale(0.98);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .number-counter {
                animation: pulse 2s infinite;
            }

            .mobile-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .mobile-summary-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .mobile-stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .mobile-table-container {
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }

            .mobile-card-header {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                padding: 1.5rem;
                text-align: center;
            }

            .mobile-section {
                margin-bottom: 2rem;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
            }
        }
    </style>

    <div class="py-4 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')

            <!-- Résumé Mobile/Desktop -->
            <div class="mobile-section mobile-card bg-white overflow-hidden shadow-xl rounded-lg md:rounded-lg p-4 md:p-6 mb-4 md:mb-6">
                <div class="mobile-card-header md:bg-transparent md:text-gray-900 md:p-0 md:mb-4 mb-6 rounded-2xl md:rounded-none">
                    <h3 class="text-lg md:text-lg font-medium mobile-header md:text-gray-900">
                        {{ $isFrench ? 'Résumé des rations' : 'Ration Summary' }}
                    </h3>
                </div>

                <div class="mobile-summary-grid md:grid md:grid-cols-3 md:gap-6">
                    <div class="mobile-card touch-card bg-indigo-50 rounded-2xl md:rounded-lg p-4 md:p-4 mb-4 md:mb-0">
                        <div class="text-indigo-700 text-sm font-semibold mb-2 md:mb-1">
                            {{ $isFrench ? 'Rations prises aujourd\'hui' : 'Rations taken today' }}
                        </div>
                        <div class="flex items-center justify-between md:items-end md:justify-start">
                            <div class="number-counter text-2xl md:text-2xl font-bold text-indigo-800">
                                {{ number_format($rationsJour, 0, ',', ' ') }}
                            </div>
                            <div class="text-sm md:ml-1 md:mb-1 text-indigo-600 font-medium">FCFA</div>
                        </div>
                        <div class="mt-2 md:hidden">
                            <div class="h-1 bg-indigo-200 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full animate-pulse" style="width: 75%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card touch-card bg-green-50 rounded-2xl md:rounded-lg p-4 md:p-4 mb-4 md:mb-0">
                        <div class="text-green-700 text-sm font-semibold mb-2 md:mb-1">
                            {{ $isFrench ? 'Rations prises ce mois' : 'Rations taken this month' }}
                        </div>
                        <div class="flex items-center justify-between md:items-end md:justify-start">
                            <div class="number-counter text-2xl md:text-2xl font-bold text-green-800">
                                {{ number_format($rationsMois, 0, ',', ' ') }}
                            </div>
                            <div class="text-sm md:ml-1 md:mb-1 text-green-600 font-medium">FCFA</div>
                        </div>
                        <div class="mt-2 md:hidden">
                            <div class="h-1 bg-green-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full animate-pulse" style="width: 90%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card touch-card bg-red-50 rounded-2xl md:rounded-lg p-4 md:p-4">
                        <div class="text-red-700 text-sm font-semibold mb-2 md:mb-1">
                            {{ $isFrench ? 'Rations non réclamées aujourd\'hui' : 'Unclaimed rations today' }}
                        </div>
                        <div class="flex items-center justify-between md:items-end md:justify-start">
                            <div class="number-counter text-2xl md:text-2xl font-bold text-red-800">
                                {{ number_format($rationPerdue, 0, ',', ' ') }}
                            </div>
                            <div class="text-sm md:ml-1 md:mb-1 text-red-600 font-medium">FCFA</div>
                        </div>
                        <div class="text-xs text-red-600 mt-2 md:mt-1 font-medium">
                            ({{ $isFrench ? 'Économie potentielle' : 'Potential savings' }})
                        </div>
                        <div class="mt-2 md:hidden">
                            <div class="h-1 bg-red-200 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full animate-pulse" style="width: 60%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques générales Mobile/Desktop -->
            <div class="mobile-section mobile-card bg-white overflow-hidden shadow-xl rounded-lg md:rounded-lg p-4 md:p-6 mb-4 md:mb-6">
                <div class="mobile-card-header md:bg-transparent md:text-gray-900 md:p-0 md:mb-4 mb-6 rounded-2xl md:rounded-none">
                    <h3 class="text-lg md:text-lg font-medium mobile-header md:text-gray-900">
                        {{ $isFrench ? 'Statistiques générales' : 'General Statistics' }}
                    </h3>
                </div>

                <div class="mobile-stats-grid md:grid md:grid-cols-3 md:gap-4">
                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0 mb-4 md:mb-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Montant total des rations' : 'Total ration amount' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800 flex items-baseline">
                            {{ number_format($statistiques['total_rations'], 0, ',', ' ') }}
                            <span class="text-sm ml-1 text-gray-600">FCFA</span>
                        </div>
                    </div>

                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0 mb-4 md:mb-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Nombre d\'employés avec ration' : 'Employees with ration' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800">
                            {{ $statistiques['nb_employes_avec_ration'] }}
                        </div>
                    </div>

                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0 mb-4 md:mb-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Ration moyenne' : 'Average ration' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800 flex items-baseline">
                            {{ number_format($statistiques['ration_moyenne'], 0, ',', ' ') }}
                            <span class="text-sm ml-1 text-gray-600">FCFA</span>
                        </div>
                    </div>

                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0 mb-4 md:mb-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Ration minimum' : 'Minimum ration' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800 flex items-baseline">
                            {{ number_format($statistiques['ration_min'], 0, ',', ' ') }}
                            <span class="text-sm ml-1 text-gray-600">FCFA</span>
                        </div>
                    </div>

                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0 mb-4 md:mb-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Ration maximum' : 'Maximum ration' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800 flex items-baseline">
                            {{ number_format($statistiques['ration_max'], 0, ',', ' ') }}
                            <span class="text-sm ml-1 text-gray-600">FCFA</span>
                        </div>
                    </div>

                    <div class="mobile-stat-item bg-gray-50 md:bg-transparent rounded-2xl md:rounded-none p-4 md:p-0">
                        <div class="text-gray-500 text-sm font-medium mb-2 md:mb-0">
                            {{ $isFrench ? 'Rations personnalisées' : 'Custom rations' }}
                        </div>
                        <div class="text-xl font-semibold text-gray-800">
                            {{ $statistiques['nb_rations_personnalisees'] }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Réclamations journalières du mois Mobile/Desktop -->
            <div class="mobile-section mobile-card bg-white overflow-hidden shadow-xl rounded-lg md:rounded-lg mb-4 md:mb-6">
                <div class="mobile-card-header md:bg-transparent md:text-gray-900 md:p-6 md:pb-0 mb-0 rounded-2xl md:rounded-none">
                    <h3 class="text-lg md:text-lg font-medium mobile-header md:text-gray-900">
                        {{ $isFrench ? 'Réclamations de rations ce mois' : 'Ration claims this month' }}
                    </h3>
                </div>

                <div class="mobile-table-container md:p-6 md:pt-4">
                    <!-- Mobile Table -->
                    <div class="block md:hidden">
                        @foreach ($statistiquesJournalieres as $index => $stat)
                            <div class="mobile-table-row bg-gray-50 rounded-2xl p-4 mb-3" style="animation-delay: {{ $index * 0.1 }}s;">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $stat->nombre }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Montant total' : 'Total amount' }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Date' : 'Date' }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Nombre de rations' : 'Number of rations' }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Montant total' : 'Total amount' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($statistiquesJournalieres as $stat)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $stat->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Employés qui prennent rarement leur ration Mobile/Desktop -->
            <div class="mobile-section mobile-card bg-white overflow-hidden shadow-xl rounded-lg md:rounded-lg">
                <div class="mobile-card-header md:bg-transparent md:text-gray-900 md:p-6 md:pb-0 mb-0 rounded-2xl md:rounded-none">
                    <h3 class="text-lg md:text-lg font-medium mobile-header md:text-gray-900">
                        {{ $isFrench ? 'Employés qui prennent peu leur ration ce mois' : 'Employees with few ration claims this month' }}
                    </h3>
                </div>

                <div class="mobile-table-container md:p-6 md:pt-4">
                    <!-- Mobile Table -->
                    <div class="block md:hidden">
                        @foreach ($employesRarement as $index => $employe)
                            <div class="mobile-table-row bg-gray-50 rounded-2xl p-4 mb-3" style="animation-delay: {{ $index * 0.1 }}s;">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $employe->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench ? 'Rations réclamées' : 'Rations claimed' }}
                                        </div>
                                    </div>
                                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                                        {{ $employe->ration_claims_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Employé' : 'Employee' }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Nombre de rations réclamées' : 'Number of rations claimed' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($employesRarement as $employe)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $employe->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $employe->ration_claims_count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Amélioration de l'expérience mobile avec JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des compteurs sur mobile
            if (window.innerWidth <= 768) {
                const counters = document.querySelectorAll('.number-counter');
                counters.forEach(counter => {
                    const target = parseInt(counter.textContent.replace(/\s/g, ''));
                    let current = 0;
                    const increment = target / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target.toLocaleString();
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current).toLocaleString();
                        }
                    }, 20);
                });

                // Effet de vibration tactile sur les cartes
                const touchCards = document.querySelectorAll('.touch-card');
                touchCards.forEach(card => {
                    card.addEventListener('touchstart', function() {
                        if (navigator.vibrate) {
                            navigator.vibrate(10);
                        }
                    });
                });
            }
        });
    </script>
@endsection