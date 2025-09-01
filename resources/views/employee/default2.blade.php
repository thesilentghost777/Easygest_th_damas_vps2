@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Easy Gest Dashboard' : 'Easy Gest Dashboard' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-bounce-gentle { animation: bounce 2s infinite; }
        .mobile-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .bakery-gradient { background: rgb(10, 37, 190); }
        .mobile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Assure que la sidebar est cachée par défaut sur mobile au chargement initial */
        @media (max-width: 1023px) {
            .sidebar-initial-hidden {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-50 font-sans min-h-screen">
<div class="flex min-h-screen" 
     x-data="{ 
         sidebarOpen: false,
         init() {
             // S'assurer que sur mobile, la sidebar reste fermée au chargement
             if (window.innerWidth < 1024) {
                 this.sidebarOpen = false;
             }
         }
     }"
     x-init="init()">
    
    <!-- Mobile Menu Button - Reduced size -->
    <button
        class="lg:hidden fixed z-50 top-3 left-3 p-2 text-white bg-gradient-to-r from-blue-600 to-blue-600 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 animate-bounce-gentle"
        @click="sidebarOpen = !sidebarOpen"
        aria-label="{{ $isFrench ? 'Ouvrir le menu' : 'Open menu' }}">
        <i class="mdi mdi-menu text-lg"></i>
    </button>
       <!-- Mobile Header Bar -->
<div x-data="{ sidebarOpen: false, showHeader: true }">
    <div x-show="showHeader"
         class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-lg border-b border-gray-200"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform"
         x-transition:leave-end="opacity-0 -translate-y-full">
        <div class="flex items-center justify-between p-4">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl bg-white-10 text-white shadow-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 animate-glow">
                <i class="mdi mdi-menu text-xl"></i>
            </button>
            
            <div class="flex items-center space-x-3 animate-fade-in">
                <div class="text-right">
                    <div class="font-semibold text-gray-800 text-sm">{{ $nom }}</div>
                    <div class="text-xs text-blue-600 font-medium">{{ $secteur }}</div>
                </div>
                <div @click="showHeader = false"
                     class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg animate-float cursor-pointer">
                    <i class="mdi mdi-account-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="lg:translate-x-0 transform lg:w-72 w-64 bakery-gradient text-white p-4 lg:p-6 flex flex-col fixed lg:static inset-y-0 z-40 transition-all duration-300 ease-in-out overflow-y-auto mobile-shadow lg:shadow-none"
        x-show="sidebarOpen || window.innerWidth >= 1024"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-x-full"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform -translate-x-full"
        x-cloak>
        
        <div class="text-center border-b border-white/20 pb-4 lg:pb-6 animate-fade-in">
            <h1 class="text-xl lg:text-2xl font-bold">EASY GEST</h1>
            <span class="text-xs opacity-80">{{ $isFrench ? 'Propulsé par TFS237' : 'Powered by TFS237' }}</span>
        </div>

        <!-- Menu Sections -->
        <div class="mt-4 lg:mt-6 space-y-6 lg:space-y-8 flex-1">
            <!-- General Section -->
            <div class="animate-fade-in">
                <h3 class="uppercase text-xs lg:text-sm font-semibold opacity-70 mb-2 lg:mb-3">
                    {{ $isFrench ? 'Général' : 'General' }}
                </h3>
                <ul class="space-y-1 lg:space-y-2">

                @feature('cashier_transactions')
                    <li>
                        <a href="{{ route('cashier.index') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-cash-register mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Gestion de la caisse' : 'Cash Management' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('cashier_payments')
                    <li>
                        <a href="{{ route('versements.index') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-cash-register mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Versement' : 'Payment' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('complex_invoices')
                    <li>
                    <a href="{{ route('factures-complexe.index') }}"
                    class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                    <i class="mdi mdi-check-circle-outline mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                    <span class="text-sm lg:text-base">{{ $isFrench ? 'Valider les factures' : 'Validate Invoices' }}</span>
                    </a>
                    </li>
                    <li>
                   
                    </li>
                    @endfeature
                    @feature('temp_missing_items')
                  
                    <li>
                        <a href="{{ route('manquant.view') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-alert-circle mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Manquants' : 'Missing Items' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('prime')
                    <li>
                        <a href="{{ route('primes.index') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-gift mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Primes' : 'Bonuses' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('loans')
                    <li>
                        <a href="{{ route('loans.my-loans') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-cash-multiple mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Effectuer un prêt' : 'Make a Loan' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('daily_rations')
                    <li>
                        <a href="{{ route('rations.employee.claim') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-currency-usd mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Ration Journalière' : 'Daily Ration' }}</span>
                        </a>
                    </li>
                    @endfeature

                    <li>
                        <a href="{{ route('horaire.index') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-clock-check mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Horaires' : 'Schedules' }}</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('repos-conges.employee') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-calendar-check mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Planning et jour de repos' : 'Planning and Rest Days' }}</span>
                        </a>
                    </li>
                    
                       @feature('payslips_salary')
                        <li><a href="{{ route('consulterfp') }}" class="flex items-center p-2 rounded hover:bg-white/10"><i class="mdi mdi-file-document-multiple mr-2"></i>{{ $isFrench ? 'Fiche de paie' : 'Payslip' }}</a></li>
                    @endfeature
                </ul>
            </div>

            <!-- Communications Section -->
            <div class="animate-fade-in">
                <h3 class="uppercase text-xs lg:text-sm font-semibold opacity-70 mb-2 lg:mb-3">
                    {{ $isFrench ? 'Communications' : 'Communications' }}
                </h3>
                <ul class="space-y-1 lg:space-y-2">
                    <li>
                        <a href="{{ route('extras.index2') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-gavel mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Réglementation' : 'Regulations' }}</span>
                        </a>
                    </li>
                    
                    @feature('salary_advances')
                    <li>
                        <a href="{{ route('reclamer-as') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-cash mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Réclamer Avance Salaire' : 'Request Salary Advance' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('validation-retrait') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-currency-usd mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Retirer Avance Salaire' : 'Withdraw Salary Advance' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('messages_suggestions')
                    <li>
                        <a href="{{ route('message') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-message-text mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Messages privés et suggestions' : 'Private Messages and Suggestions' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('message') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-alert mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Signalements' : 'Reports' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('announcements.index') }}" 
                           class="flex items-center p-2 lg:p-3 rounded-xl hover:bg-white/10 transition-all duration-200 transform hover:scale-105 hover:translate-x-1 group">
                            <i class="mdi mdi-bullhorn mr-2 lg:mr-3 text-blue-200 group-hover:text-white text-lg lg:text-xl"></i>
                            <span class="text-sm lg:text-base">{{ $isFrench ? 'Annonce' : 'Announcements' }}</span>
                        </a>
                    </li>
                    @endfeature
                </ul>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="mt-auto border-t border-white/20 pt-4 lg:pt-6 animate-fade-in">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="mdi mdi-account-circle text-lg lg:text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm lg:text-base truncate">{{ $nom }}</div>
                    <div class="text-xs lg:text-sm opacity-70 truncate">{{ $secteur }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
         @click="sidebarOpen = false"
         x-cloak></div>

    <!-- Content Area -->
    <main class="flex-1 p-3 lg:p-6 lg:ml-0 overflow-y-auto min-h-screen animate-fade-in">
        @yield('page-content')
    </main>
</div>

<style>
    /* Enhanced mobile scrolling */
    aside {
        height: 100vh;
        overflow-y: auto;
        position: sticky;
        top: 0;
        -webkit-overflow-scrolling: touch;
    }
    
    main {
        height: 100vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Custom scrollbar for mobile */
    aside::-webkit-scrollbar {
        width: 2px;
    }
    
    aside::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }
    
    aside::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }
    
    main::-webkit-scrollbar {
        width: 3px;
    }
    
    main::-webkit-scrollbar-track {
        background: transparent;
    }
    
    main::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    /* Mobile touch optimizations */
    @media (max-width: 1024px) {
        .mobile-optimized {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        button, a {
            min-height: 44px;
            min-width: 44px;
        }
    }
    
    /* Éviter le flash lors du chargement */
    [x-cloak] {
        display: none !important;
    }
</style>

<script>
// Script pour s'assurer que la sidebar reste fermée au chargement initial sur mobile
document.addEventListener('alpine:init', () => {
    // Cette fonction s'exécute avant qu'Alpine.js ne démarre
    if (window.innerWidth < 1024) {
        // Force l'état initial à false pour mobile
        window.Alpine = window.Alpine || {};
        window.Alpine.data = window.Alpine.data || {};
    }
});
</script>
</body>
@endsection