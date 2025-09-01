@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Gest Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div x-data="{ sidebarOpen: false }" x-init="if (window.innerWidth < 1024) { sidebarOpen = false }" class="min-h-screen">
        <!-- Mobile menu button -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden fixed z-50 top-4 left-4 p-1.5 rounded-md bg-blue-600 text-white shadow-lg transform transition-all duration-300 hover:scale-110">
            <i class="mdi mdi-menu text-lg"></i>
        </button>

        <!-- Mobile overlay -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="lg:hidden fixed inset-0 z-30 bg-black bg-opacity-50">
        </div>
         <!-- Mobile Header Bar -->
<div x-data="{ sidebarOpen: false, showHeader: true }">
    <div x-show="showHeader"
         class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-lg border-b border-gray-200"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform"
         x-transition:leave-end="opacity-0 -translate-y-full">
        <div class="flex items-center justify-between p-4">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 animate-glow">
                <i class="mdi mdi-menu text-xl"></i>
            </button>
            
            <div class="flex items-center space-x-3 animate-fade-in">
                <div class="text-right">
                    <div class="font-semibold text-gray-800 text-sm">{{ $nom }}</div>
                    <div class="text-xs text-blue-600 font-medium">{{ $role }}</div>
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
+     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
+     class="fixed inset-y-0 left-0 z-40 w-72 lg:w-72 bg-gradient-to-br from-blue-800 to-blue-600 text-white transform transition-transform duration-300 ease-in-out overflow-y-auto shadow-2xl lg:shadow-lg -translate-x-full lg:translate-x-0">
            <!-- Mobile close button -->
            <button
                @click="sidebarOpen = false"
                class="lg:hidden absolute top-4 right-4 p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors">
                <i class="mdi mdi-close text-white text-xl"></i>
            </button>

            <!-- Logo -->
            <div class="px-6 py-8 border-b border-white/20 relative">
                <div class="transform transition-all duration-500 hover:scale-105">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                        EASY GEST
                    </h1>
                    <span class="text-xs text-blue-100 opacity-80">Powered by TFS237</span>
                </div>
            </div>

            <!-- Menu Items -->
            <nav class="p-6 space-y-8">
                <!-- Ventes Section -->
                <div class="animate-fadeInUp">
                    <h3 class="text-xs font-semibold tracking-wider uppercase text-blue-100 mb-3 flex items-center">
                        <i class="mdi mdi-cart mr-2"></i>
                        {{ $isFrench ? 'Ventes' : 'Sales' }}
                    </h3>
                    <ul class="space-y-2">
                       @if ($user->role == 'glace')
                       <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('serveur.workspace') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                           <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                           <i class="mdi mdi-view-dashboard mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                           <span class="relative z-10">{{ $isFrench ? 'Dashboard' : 'Dashboard' }}</span>
                        </a>
                    </li>
                    
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('cash.distributions.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <i class="mdi mdi-package-variant-closed-check mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                            <span class="relative z-10">{{ $isFrench ? 'Démarrer une session de vente' : 'Start a sales session' }}</span>
                        </a>
                    </li>
                    @feature('manage_bags')
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('serveur-nbre_sacs_vente') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <i class="mdi mdi-bag-suitcase mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                            <span class="relative z-10">{{ $isFrench ? 'Sac et contenant' : 'Bags and containers' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('payments')
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('versements.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                           <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                           <i class="mdi mdi-bank-transfer mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                           <span class="relative z-10">{{ $isFrench ? 'Versement' : 'Payment' }}</span>
                        </a>
                    </li>
                     
                    @feature('sales_details')
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('ventes.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <i class="mdi mdi-eye mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                            <span class="relative z-10">{{ $isFrench ? 'Détails des Ventes' : 'Sales Details' }}</span>
                        </a>
                    </li>
                    @endfeature
                    @endfeature
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                        <a href="{{ route('ice.workspace') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                          <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                          <i class="mdi mdi-swap-horizontal mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                          <span class="relative z-10">{{ $isFrench ? 'Changer de mode' : 'Switch mode' }}</span>
                        </a>
                      </li>


                </ul>
                       @else
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('serveur.workspace') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                               <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                               <i class="mdi mdi-view-dashboard mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                               <span class="relative z-10">{{ $isFrench ? 'Dashboard' : 'Dashboard' }}</span>
                            </a>
                        </li>
                        <!-- Solution 1: Avec SVG (recommandée) -->
<li class="transform transition-all duration-300 hover:translate-x-2">
    <a href="{{ route('receptions.vendeurs.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
        <!-- Icône SVG -->
        <svg class="w-5 h-5 mr-3 text-blue-200 group-hover:text-white transform transition-all duration-300 group-hover:scale-110 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H3"></path>
        </svg>
        <span class="relative z-10">{{ $isFrench ? 'Réceptions Vendeurs' : 'Vendor Receipts' }}</span>
    </a>
</li>
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('cash.distributions.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-package-variant-closed-check mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Démarrer une session de vente' : 'Start a sales session' }}</span>
                            </a>
                        </li>
                        @feature('manage_bags')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('serveur-nbre_sacs_vente') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-bag-suitcase mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Sac et contenant' : 'Bags and containers' }}</span>
                            </a>
                        </li>
                        @endfeature
                      
                        
                        @feature('payments')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('versements.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                               <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                               <i class="mdi mdi-bank-transfer mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                               <span class="relative z-10">{{ $isFrench ? 'Versement' : 'Payment' }}</span>
                            </a>
                        </li>
                        @endfeature
                    </ul>
                </div>

                <!-- Général Section -->
                <div class="animate-fadeInUp" style="animation-delay: 0.1s;">
                    <h3 class="text-xs font-semibold tracking-wider uppercase text-blue-100 mb-3 flex items-center">
                        <i class="mdi mdi-cog mr-2"></i>
                        {{ $isFrench ? 'Général' : 'General' }}
                    </h3>
                    <ul class="space-y-2">
                        
                        @feature('sales_details')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('ventes.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-eye mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Détails des Ventes' : 'Sales Details' }}</span>
                            </a>
                        </li>
                        @endfeature
                        
                        @feature('temp_missing_items')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('manquant.view') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-alert-circle mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Manquants' : 'Missing Items' }}</span>
                            </a>
                        </li>
                        
                        @endfeature
                        
                        @feature('prime')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('primes.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-gift mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Primes' : 'Bonuses' }}</span>
                            </a>
                        </li>
                        @endfeature
                                                
                        @feature('loans')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('loans.my-loans') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-cash-multiple mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Effectuer un prêt' : 'Make a loan' }}</span>
                            </a>
                        </li>
                        @endfeature
                        
                        @feature('daily_rations')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('rations.employee.claim') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-currency-usd mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Ration Journalière' : 'Daily Ration' }}</span>
                            </a>
                        </li>
                        @endfeature

                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('horaire.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-clock-check mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Horaires' : 'Schedules' }}</span>
                            </a>
                        </li>
                        
                        @feature('payslips_salary')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('consulterfp') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-file-document-multiple mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Fiche de paie' : 'Payslip' }}</span>
                            </a>
                        </li>
                        @endfeature
                    </ul>
                </div>

                <!-- Communications Section -->
                <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
                    <h3 class="text-xs font-semibold tracking-wider uppercase text-blue-100 mb-3 flex items-center">
                        <i class="mdi mdi-chat mr-2"></i>
                        {{ $isFrench ? 'Communications' : 'Communications' }}
                    </h3>
                    <ul class="space-y-2">
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('extras.index2') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-gavel mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Réglementation' : 'Regulations' }}</span>
                            </a>
                        </li>
                        
                        @feature('salary_advances')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('reclamer-as') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-cash mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Réclamer Avance Salaire' : 'Claim Salary Advance' }}</span>
                            </a>
                        </li>
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('validation-retrait') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-currency-usd mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Retirer Avance Salaire' : 'Withdraw Salary Advance' }}</span>
                            </a>
                        </li>
                        @endfeature
                        
                        @feature('messages_suggestions')
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('message') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-message-text mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Messages privés et suggestions' : 'Private messages and suggestions' }}</span>
                            </a>
                        </li>
                        <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('message') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-alert mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Signalements' : 'Reports' }}</span>
                            </a>
                        </li>
                        @endfeature
                    </ul>
                </div>
                @endif
            </nav>

           <!-- Profile Section -->
            <div class="mt-auto border-t border-white/20 pt-6 px-6 pb-6">
                <div class="flex items-center space-x-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all duration-300 transform hover:scale-105">
                    <div class="w-12 h-12 bg-gradient-to-br from-white/30 to-white/10 rounded-full flex items-center justify-center ring-2 ring-white/20">
                        <i class="mdi mdi-account-circle text-2xl text-white"></i>
                    </div>
                    <div>
                        <div class="font-medium text-white">{{ $nom }}</div>
                        <div class="text-sm text-blue-100 opacity-80">{{ $isFrench ? 'Vendeur(se)' : 'Seller' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="lg:ml-72 min-h-screen transition-all duration-300">
            <div class="p-4 lg:p-6 bg-white">
                <div class="container mx-auto">
                   
                    @yield('page-content')
                </div>
            </div>
        </main>
    </div>

<style>
    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* Mobile-first responsive design */
    @media (max-width: 1023px) {
        /* Mobile sidebar adjustments */
        aside {
            width: 280px;
        }
        
        /* Mobile menu animations */
        .sidebar-enter {
            transform: translateX(-100%);
        }
        
        .sidebar-enter-active {
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile touch improvements */
        a, button {
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Mobile scroll improvements */
        aside {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }
        
        /* Mobile menu item spacing */
        nav ul li {
            margin-bottom: 4px;
        }
        
        /* Mobile text sizing */
        nav a span {
            font-size: 0.95rem;
            line-height: 1.4;
        }
    }

    /* Desktop enhancements */
    @media (min-width: 1024px) {
        /* Desktop hover effects */
        aside:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Desktop smooth transitions */
        main {
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* Desktop menu item animations */
        nav li:hover {
            transform: translateX(8px) scale(1.02);
        }
    }

    /* Scrollbar customization */
    aside {
        height: 100vh;
        overflow-y: auto;
        position: fixed;
        top: 0;
    }
    
    main {
        overflow-y: auto;
    }
    
    /* Mobile scrollbar (webkit) */
    @media (max-width: 1023px) {
        aside::-webkit-scrollbar {
            width: 2px;
        }
        
        aside::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        aside::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }
    }
    
    /* Desktop scrollbar */
    @media (min-width: 1024px) {
        aside::-webkit-scrollbar {
            width: 4px;
        }
        
        aside::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        aside::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        aside::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    }
    
    /* Main content scrollbar */
    main::-webkit-scrollbar {
        width: 6px;
    }
    
    main::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    main::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    
    main::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Loading animations */
    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }

    /* Mobile-specific improvements */
    @media (max-width: 768px) {
        /* Reduce padding on mobile */
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Mobile typography */
        h1, h2, h3 {
            font-size: clamp(1.5rem, 4vw, 2rem);
        }
        
        /* Mobile button sizing */
        button {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Mobile menu item touch targets */
        nav a {
            min-height: 48px;
            display: flex;
            align-items: center;
        }
    }

    /* Focus states for accessibility */
    button:focus,
    a:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    /* Reduced motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        aside {
            background: #000;
            border-right: 2px solid #fff;
        }
        
        nav a {
            border: 1px solid transparent;
        }
        
        nav a:hover {
            border-color: #fff;
        }
    }

    /* Dark mode preferences */
    @media (prefers-color-scheme: dark) {
        main {
            background-color: #111827;
        }
    }
</style>

<script>
    // Mobile-specific JavaScript enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent body scroll when sidebar is open on mobile
        const sidebar = document.querySelector('aside');
        const sidebarToggle = document.querySelector('[x-data]');
        
        if (window.innerWidth < 1024) {
            // Add touch gesture support
            let startX = 0;
            let currentX = 0;
            let isSwipping = false;
            
            document.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isSwipping = true;
            });
            
            document.addEventListener('touchmove', function(e) {
                if (!isSwipping) return;
                currentX = e.touches[0].clientX;
                
                // Swipe right to open sidebar (from left edge)
                if (startX < 50 && currentX - startX > 50) {
                    // Trigger Alpine.js sidebar open
                    Alpine.store('sidebar', { open: true });
                }
                
                // Swipe left to close sidebar
                if (startX > window.innerWidth - 300 && startX - currentX > 50) {
                    // Trigger Alpine.js sidebar close
                    Alpine.store('sidebar', { open: false });
                }
            });
            
            document.addEventListener('touchend', function(e) {
                isSwipping = false;
            });
        }
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            // ESC key closes sidebar on mobile
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                const sidebarState = Alpine.$data(document.querySelector('[x-data]'));
                if (sidebarState && sidebarState.sidebarOpen) {
                    sidebarState.sidebarOpen = false;
                }
            }
        });
        
        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Add loading state management
        window.addEventListener('beforeunload', function() {
            document.body.classList.add('loading');
        });
        
        // Optimize performance on mobile
        if (window.innerWidth < 1024) {
            // Reduce animation complexity on slower devices
            const isSlowDevice = navigator.hardwareConcurrency < 4;
            if (isSlowDevice) {
                document.documentElement.style.setProperty('--animation-duration', '0.2s');
            }
        }
        
        // Add viewport height fix for mobile browsers
        function setVH() {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setVH();
        window.addEventListener('resize', setVH);
        window.addEventListener('orientationchange', setVH);
    });
</script>
</body>
@endsection