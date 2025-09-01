@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="loadingModal">
                <!-- Loading Animation -->
                <div class="relative mb-6">
                    <div class="loading-spinner mx-auto"></div>
                    <div class="loading-pulse absolute inset-0 mx-auto"></div>
                </div>
                
                <!-- Loading Text -->
                <h3 class="text-xl font-bold text-gray-900 mb-2" id="loadingTitle">
                    {{ $isFrench ? 'Génération du rapport...' : 'Generating report...' }}
                </h3>
                <p class="text-gray-600 mb-4" id="loadingDescription">
                    {{ $isFrench ? 'Veuillez patienter pendant que l\'IA analyse vos données' : 'Please wait while AI analyzes your data' }}
                </p>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="bg-blue-600 h-2 rounded-full loading-progress" style="width: 0%"></div>
                </div>
                
                <!-- Loading Steps -->
                <div class="text-sm text-gray-500 space-y-1" id="loadingSteps">
                    <div class="loading-step opacity-50">
                        <span class="step-icon">●</span>
                        <span>{{ $isFrench ? 'Collecte des données...' : 'Collecting data...' }}</span>
                    </div>
                    <div class="loading-step opacity-50">
                        <span class="step-icon">●</span>
                        <span>{{ $isFrench ? 'Analyse des transactions...' : 'Analyzing transactions...' }}</span>
                    </div>
                    <div class="loading-step opacity-50">
                        <span class="step-icon">●</span>
                        <span>{{ $isFrench ? 'Génération du rapport...' : 'Generating report...' }}</span>
                    </div>
                    <div class="loading-step opacity-50">
                        <span class="step-icon">●</span>
                        <span>{{ $isFrench ? 'Finalisation...' : 'Finalizing...' }}</span>
                    </div>
                </div>
                
                <!-- Cancel Button -->
                <button type="button" onclick="cancelLoading()" class="mt-6 px-4 py-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors duration-200">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-white">
                            {{ $isFrench ? 'Rapport Mensuel Global' : 'Global Monthly Report' }}
                        </h1>
                        <p class="text-blue-100 text-sm">
                            {{ $isFrench ? 'Consultez vos rapports mensuels' : 'View your monthly reports' }}
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('rapports.mensuel.configure') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-xl font-medium text-sm transform hover:scale-105 active:scale-95 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $isFrench ? 'Configurer' : 'Configure' }}
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Rapport Mensuel Global' : 'Global Monthly Report' }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        {{ $isFrench ? 'Consultez les rapports mensuels complets de votre entreprise' : 'View comprehensive monthly reports of your business' }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    @include('buttons')
                    <a href="{{ route('rapports.mensuel.configure') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 transition duration-200 transform hover:scale-105">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $isFrench ? 'Configurer le rapport' : 'Configure Report' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Report Generation Form -->
        <div class="md:hidden bg-white rounded-2xl shadow-lg mb-6 overflow-hidden transform hover:scale-102 transition-all duration-300 animate-slide-in-right">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    {{ $isFrench ? 'Générer un rapport mensuel' : 'Generate Monthly Report' }}
                </h2>
                <form action="{{ route('rapports.mensuel.show') }}" method="GET" class="space-y-4" id="mobileReportForm">
                    <div>
                        <label for="month_year_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Mois du rapport' : 'Report Month' }}
                        </label>
                        <select name="month_year" id="month_year_mobile" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 active:scale-95" id="mobileSubmitBtn">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="btn-text">{{ $isFrench ? 'Générer le rapport' : 'Generate Report' }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Report Generation Form -->
        <div class="hidden md:block bg-gray-50 p-6 rounded-lg shadow-sm mb-6 transform hover:scale-102 transition-all duration-300">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ $isFrench ? 'Générer un rapport mensuel' : 'Generate Monthly Report' }}
            </h2>
            <form action="{{ route('rapports.mensuel.show') }}" method="GET" class="space-y-4" id="desktopReportForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="month_year" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isFrench ? 'Mois du rapport' : 'Report Month' }}
                        </label>
                        <select name="month_year" id="month_year" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150" id="desktopSubmitBtn">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="btn-text">{{ $isFrench ? 'Générer le rapport' : 'Generate Report' }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mobile Recent Reports -->
        <div class="md:hidden space-y-4">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                {{ $isFrench ? 'Rapports récents' : 'Recent Reports' }}
            </h2>
            
            @foreach(array_slice($months, 0, 5) as $value => $label)
                @php
                    list($year, $month) = explode('-', $value);
                    $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
                    $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
                    
                    $ca = App\Models\Transaction::where('type', 'income')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('amount');
                        
                    $depenses = App\Models\Transaction::where('type', 'outcome')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->sum('amount');
                        
                    $benefice = $ca - $depenses;
                @endphp
                
                <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $label }}</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $isFrench ? 'Rapport mensuel' : 'Monthly report' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-blue-50 p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-blue-600 mb-1">
                                {{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}
                            </p>
                            <p class="font-bold text-blue-700 text-sm">{{ number_format($ca, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-xl text-center">
                            <p class="text-xs font-medium text-green-600 mb-1">
                                {{ $isFrench ? 'Bénéfices' : 'Profit' }}
                            </p>
                            <p class="font-bold {{ $benefice >= 0 ? 'text-green-700' : 'text-red-700' }} text-sm">
                                {{ number_format($benefice, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('rapports.mensuel.show', ['month_year' => $value]) }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200 report-link" data-month="{{ $value }}">
                            {{ $isFrench ? 'Voir' : 'View' }}
                        </a>
                        
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Recent Reports Table -->
        <div class="hidden md:block mt-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ $isFrench ? 'Rapports récents' : 'Recent Reports' }}
            </h2>
            <div class="bg-white rounded-lg shadow overflow-hidden transform hover:scale-102 transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Période' : 'Period' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Bénéfices' : 'Profit' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(array_slice($months, 0, 5) as $value => $label)
                            @php
                                list($year, $month) = explode('-', $value);
                                $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
                                $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();
                                
                                $ca = App\Models\Transaction::where('type', 'income')
                                    ->whereBetween('date', [$startDate, $endDate])
                                    ->sum('amount');
                                    
                                $depenses = App\Models\Transaction::where('type', 'outcome')
                                    ->whereBetween('date', [$startDate, $endDate])
                                    ->sum('amount');
                                    
                                $benefice = $ca - $depenses;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($ca, 0, ',', ' ') }} FCFA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="{{ $benefice >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($benefice, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('rapports.mensuel.show', ['month_year' => $value]) }}" class="text-indigo-600 hover:text-indigo-900 transform hover:scale-110 transition-all duration-200 report-link" data-month="{{ $value }}">
                                        {{ $isFrench ? 'Voir' : 'View' }}
                                    </a>
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}

/* Loading Animations */
.loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-pulse {
    width: 80px;
    height: 80px;
    border: 2px solid #3b82f6;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
    opacity: 0.3;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes pulse {
    0% { transform: scale(0.8); opacity: 0.3; }
    50% { transform: scale(1.2); opacity: 0.1; }
    100% { transform: scale(0.8); opacity: 0.3; }
}

.loading-progress {
    animation: progressBar 30s linear infinite;
}

@keyframes progressBar {
    0% { width: 0%; }
    25% { width: 25%; }
    50% { width: 50%; }
    75% { width: 75%; }
    100% { width: 100%; }
}

.loading-step {
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.loading-step.active {
    opacity: 1;
    transform: translateX(4px);
}

.loading-step.active .step-icon {
    color: #3b82f6;
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-4px); }
    60% { transform: translateY(-2px); }
}

.loading-step.completed {
    opacity: 0.7;
}

.loading-step.completed .step-icon {
    color: #10b981;
}

/* Button Loading State */
.btn-loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-loading .btn-text {
    opacity: 0;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Modal Animations */
.modal-show {
    opacity: 1 !important;
    transform: scale(1) !important;
}

.modal-hide {
    opacity: 0 !important;
    transform: scale(0.95) !important;
}

@media (max-width: 768px) {
    .loading-spinner {
        width: 50px;
        height: 50px;
    }
    
    .loading-pulse {
        width: 70px;
        height: 70px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingModal = document.getElementById('loadingModal');
    const loadingSteps = document.querySelectorAll('.loading-step');
    const mobileForm = document.getElementById('mobileReportForm');
    const desktopForm = document.getElementById('desktopReportForm');
    const reportLinks = document.querySelectorAll('.report-link');
    
    let currentStep = 0;
    let stepInterval;
    let isLoading = false;

    // Fonction pour afficher le loading
    function showLoading() {
        if (isLoading) return;
        isLoading = true;
        
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
        
        // Animer l'apparition du modal
        setTimeout(() => {
            loadingModal.classList.add('modal-show');
        }, 100);
        
        // Démarrer l'animation des étapes
        startStepAnimation();
        
        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';
    }

    // Fonction pour cacher le loading
    function hideLoading() {
        if (!isLoading) return;
        
        loadingModal.classList.remove('modal-show');
        loadingModal.classList.add('modal-hide');
        
        setTimeout(() => {
            loadingOverlay.classList.add('hidden');
            loadingOverlay.classList.remove('flex');
            loadingModal.classList.remove('modal-hide');
            
            // Restaurer le scroll du body
            document.body.style.overflow = '';
            
            // Reset des étapes
            resetSteps();
            isLoading = false;
        }, 300);
    }

    // Animation des étapes
    function startStepAnimation() {
        currentStep = 0;
        
        stepInterval = setInterval(() => {
            if (currentStep > 0) {
                loadingSteps[currentStep - 1].classList.remove('active');
                loadingSteps[currentStep - 1].classList.add('completed');
            }
            
            if (currentStep < loadingSteps.length) {
                loadingSteps[currentStep].classList.add('active');
                currentStep++;
            } else {
                // Recommencer le cycle
                resetSteps();
                currentStep = 0;
            }
        }, 7500); // 30s / 4 étapes = 7.5s par étape
    }

    // Reset des étapes
    function resetSteps() {
        if (stepInterval) {
            clearInterval(stepInterval);
        }
        
        loadingSteps.forEach(step => {
            step.classList.remove('active', 'completed');
        });
    }

    // Gestion des formulaires
    function handleFormSubmit(form, submitBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Ajouter classe loading au bouton
            submitBtn.classList.add('btn-loading');
            submitBtn.style.position = 'relative';
            
            // Afficher le loading overlay
            showLoading();
            
            // Soumettre le formulaire après un court délai
            setTimeout(() => {
                form.submit();
            }, 500);
        });
    }

    // Gestion des liens de rapport
    function handleReportLinks() {
        reportLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Afficher le loading
                showLoading();
                
                // Naviguer après un court délai
                setTimeout(() => {
                    window.location.href = this.href;
                }, 500);
            });
        });
    }

    // Fonction pour annuler le chargement
    window.cancelLoading = function() {
        hideLoading();
        
        // Retirer les classes loading des boutons
        document.querySelectorAll('.btn-loading').forEach(btn => {
            btn.classList.remove('btn-loading');
            btn.style.position = '';
        });
    };

    // Initialiser les gestionnaires d'événements
    if (mobileForm) {
        handleFormSubmit(mobileForm, document.getElementById('mobileSubmitBtn'));
    }
    
    if (desktopForm) {
        handleFormSubmit(desktopForm, document.getElementById('desktopSubmitBtn'));
    }
    
    handleReportLinks();

    // Gérer le retour en arrière du navigateur
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoading();
        }
    });

    // Gérer la fermeture de la page
    window.addEventListener('beforeunload', function() {
        hideLoading();
    });

    // Animation de texte dynamique pour le titre de chargement
    const loadingMessages = {
        fr: [
            'Génération du rapport...',
            'Analyse des données...',
            'Traitement par l\'IA...',
            'Construction du rapport...',
            'Finalisation...'
        ],
        en: [
            'Generating report...',
            'Analyzing data...',
            'AI processing...',
            'Building report...',
            'Finalizing...'
        ]
    };

    let messageIndex = 0;
    let messageInterval;

    function startMessageAnimation() {
        const isFrench = {{ $isFrench ? 'true' : 'false' }};
        const messages = isFrench ? loadingMessages.fr : loadingMessages.en;
        const titleElement = document.getElementById('loadingTitle');

        messageInterval = setInterval(() => {
            titleElement.style.opacity = '0.5';
            
            setTimeout(() => {
                titleElement.textContent = messages[messageIndex];
                titleElement.style.opacity = '1';
                messageIndex = (messageIndex + 1) % messages.length;
            }, 200);
        }, 3000);
    }

    function stopMessageAnimation() {
        if (messageInterval) {
            clearInterval(messageInterval);
            messageInterval = null;
        }
        messageIndex = 0;
    }

    // Modifier la fonction showLoading pour inclure l'animation de texte
    const originalShowLoading = showLoading;
    showLoading = function() {
        originalShowLoading();
        startMessageAnimation();
    };

    // Modifier la fonction hideLoading pour arrêter l'animation de texte
    const originalHideLoading = hideLoading;
    hideLoading = function() {
        stopMessageAnimation();
        originalHideLoading();
    };

    // Gestion des touches du clavier
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isLoading) {
            cancelLoading();
        }
    });

    // Effet de particules pour le chargement (optionnel)
    function createParticles() {
        const particleContainer = document.createElement('div');
        particleContainer.className = 'fixed inset-0 pointer-events-none z-40';
        particleContainer.innerHTML = `
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400 rounded-full opacity-60 animate-ping" style="animation-delay: 0s;"></div>
            <div class="absolute top-1/3 right-1/4 w-1 h-1 bg-indigo-400 rounded-full opacity-40 animate-ping" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/3 left-1/3 w-1.5 h-1.5 bg-purple-400 rounded-full opacity-50 animate-ping" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-1 h-1 bg-blue-300 rounded-full opacity-30 animate-ping" style="animation-delay: 3s;"></div>
        `;
        
        return particleContainer;
    }

    // Ajouter des particules pendant le chargement
    let particleContainer;
    const originalShowLoadingWithParticles = showLoading;
    showLoading = function() {
        originalShowLoadingWithParticles();
        
        // Ajouter les particules après un délai
        setTimeout(() => {
            if (isLoading) {
                particleContainer = createParticles();
                document.body.appendChild(particleContainer);
            }
        }, 1000);
    };

    const originalHideLoadingWithParticles = hideLoading;
    hideLoading = function() {
        if (particleContainer) {
            document.body.removeChild(particleContainer);
            particleContainer = null;
        }
        originalHideLoadingWithParticles();
    };
});
</script>
@endsection