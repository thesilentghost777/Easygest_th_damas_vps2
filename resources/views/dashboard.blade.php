<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Dashboard - Boulangerie Pâtisserie' : 'Dashboard - Bakery Pastry' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&family=Caveat:wght@400;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
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
        
        .animate-slideInUp {
            animation: slideInUp 0.6s ease-out;
        }
        
        .animate-fadeInScale {
            animation: fadeInScale 0.5s ease-out;
        }
        
        .animate-pulse-gentle {
            animation: pulse 2s infinite;
        }
        
        .mobile-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .mobile-card:active {
            transform: scale(0.98);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .mobile-nav-item {
            transition: all 0.3s ease;
        }
        
        .mobile-nav-item.active {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- En-tête avec logo -->
        <header class="pt-4 px-4 md:px-8">
            <div class="max-w-7xl mx-auto flex items-center">
                
                <!-- Version Desktop -->
                <!--
                <div class="hidden md:flex items-center w-full">
                    <img src="{{ asset('assets/logos/TH_LOGO.png') }}" alt="TH Logo" class="h-40 w-auto">
                    <div class="border-b-2 border-black py-2">
                        <p class="ml-12 font-['Poppins'] text-2xl leading-loose">
                            <span class="font-bold text-red-600">TH MARKET</span> :
                            <span class="text-blue-900">{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</span>
                            <span class="text-blue-700">{{ $isFrench ? 'Patisserie' : 'Pastry' }}</span>
                            <span class="text-blue-500">{{ $isFrench ? 'Alimentation' : 'Food' }}</span>
                            <span class="text-blue-400">Snack</span>
                            <span class="text-blue-300">Restaurant</span>
                        </p>
                    </div>
                </div>-->
                
                <!-- Version Mobile -->

                <!--
                <div class="md:hidden w-full">
                    <div class="flex flex-col items-center space-y-3 animate-fadeInScale">
                        <img src="{{ asset('assets/logos/TH_LOGO.png') }}" alt="TH Logo" class="h-20 w-auto animate-pulse-gentle">
                        <div class="text-center">
                            <h1 class="font-bold text-red-600 text-lg">TH MARKET</h1>
                            <div class="flex flex-wrap justify-center gap-1 text-xs mt-1">
                                <span class="bg-blue-900 text-white px-2 py-1 rounded-full">{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</span>
                                <span class="bg-blue-700 text-white px-2 py-1 rounded-full">{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</span>
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full">{{ $isFrench ? 'Alimentation' : 'Food' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            -->
            </div>
        </header>

        <!-- Navigation -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <!-- Navigation Desktop -->
            <div class="hidden md:block border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('dashboard') }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ $isFrench ? 'Accueil' : 'Home' }}
                    </a>
                    <a href="{{ route('workspace.redirect') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ $isFrench ? 'Espace de travail' : 'Workspace' }}
                    </a>
                    <a href="{{ route('about') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{ $isFrench ? 'Mentions légales' : 'Legal Disclaimer' }}
                    </a>
                </nav>
            </div>
            
            <!-- Navigation Mobile -->
            <div class="md:hidden">
                <div class="glass-effect rounded-2xl p-1 shadow-lg">
                    <nav class="flex justify-around">
                        <a href="{{ route('dashboard') }}" class="mobile-nav-item active flex-1 text-center py-3 px-2 rounded-xl bg-blue-500 text-white font-medium text-sm">
                            {{ $isFrench ? 'Accueil' : 'Home' }}
                        </a>
                        <a href="{{ route('workspace.redirect') }}" class="mobile-nav-item flex-1 text-center py-3 px-2 rounded-xl text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Travail' : 'Work' }}
                        </a>
                        <a href="{{ route('about') }}" class="mobile-nav-item flex-1 text-center py-3 px-2 rounded-xl text-gray-600 font-medium text-sm">
                            {{ $isFrench ? 'Mentions légales' : 'Legal Disclaimer' }}
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <main class="flex-1 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section d'accueil -->
                <section id="home" class="space-y-6">
                    <!-- Bannière de bienvenue -->
                    <div class="bg-gradient-to-r from-blue-600 to-green-400 rounded-lg md:rounded-lg shadow-xl p-6 sm:p-10 animate-slideInUp mobile-card">
                        <!-- Version Desktop -->
                        <div class="hidden md:block max-w-3xl">
                            <h2 class="text-3xl font-bold text-white mb-2">
                                {{ $isFrench ? 'Bienvenue sur votre espace de travail' : 'Welcome to your workspace' }}
                            </h2>
                            <p class="text-blue-50">
                                {{ $isFrench ? 'EasyGest - La solution complète pour gérer efficacement votre activité de boulangerie-pâtisserie' : 'EasyGest - The complete solution to efficiently manage your bakery-pastry business' }}
                            </p>
                        </div>
                        
                        <!-- Version Mobile -->
                        <div class="md:hidden text-center">
                            <h2 class="text-xl font-bold text-white mb-3">
                                {{ $isFrench ? '🎉 Bienvenue !' : '🎉 Welcome!' }}
                            </h2>
                            <p class="text-blue-50 text-sm leading-relaxed">
                                {{ $isFrench ? 'EasyGest - Votre solution complète pour gérer votre boulangerie-pâtisserie' : 'EasyGest - Your complete solution for managing your bakery-pastry' }}
                            </p>
                            <div class="mt-4 flex justify-center">
                                <div class="bg-white/20 rounded-full p-3 animate-pulse-gentle">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description de l'application -->
                    <div class="bg-white overflow-hidden shadow rounded-lg md:rounded-lg mobile-card animate-slideInUp" style="animation-delay: 0.1s">
                        <div class="p-5">
                            <!-- Version Desktop -->
                            <div class="hidden md:block">
                                <h2 class="text-xl font-semibold text-gray-900 underline">
                                    {{ $isFrench ? 'Application de gestion et contrôle complet de votre Boulangerie-Pâtisserie' : 'Complete management and control application for your Bakery-Pastry' }}
                                </h2>
                                <p class="mt-2 text-gray-600">
                                    {{ $isFrench ? 'Cette application permet de gérer efficacement la production, les ventes, les gains, pertes, les commandes, et le stock des matières premières dans une boulangerie-pâtisserie. Grâce à ses fonctionnalités avancées, elle aide les propriétaires et les employés à optimiser leurs processus et à réduire les erreurs humaines. Elle fournit également des outils de suivi des ventes et des performances en temps réel.' : 'This application allows you to efficiently manage production, sales, profits, losses, orders, and raw material inventory in a bakery-pastry. Thanks to its advanced features, it helps owners and employees optimize their processes and reduce human errors. It also provides real-time sales and performance tracking tools.' }}
                                </p>
                            </div>
                            
                            <!-- Version Mobile -->
                            <div class="md:hidden">
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $isFrench ? 'Gestion Complète' : 'Complete Management' }}
                                    </h2>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed text-center">
                                    {{ $isFrench ? 'Gérez efficacement votre production, ventes, stocks et commandes. Optimisez vos processus et suivez vos performances en temps réel.' : 'Efficiently manage your production, sales, inventory and orders. Optimize your processes and track your performance in real time.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Problèmes rencontrés -->
                    <div class="bg-white overflow-hidden shadow rounded-lg md:rounded-lg mt-6 mobile-card animate-slideInUp" style="animation-delay: 0.2s">
                        <div class="p-5">
                            <!-- Version Desktop -->
                            <div class="hidden md:block">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="text-blue-500 mr-2">💡</span>
                                    {{ $isFrench ? 'Problèmes rencontrés' : 'Problems Encountered' }}
                                </h3>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Gestion manuelle (papier, cahier, calculatrice)' : 'Manual management (paper, notebook, calculator)' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Pas de traçabilité des ventes / pertes / stocks' : 'No traceability of sales / losses / inventory' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Mauvaise visibilité sur la rentabilité' : 'Poor visibility on profitability' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Vols internes ou fuites de caisse' : 'Internal theft or cash leaks' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Aucun contrôle sur la production' : 'No control over production' }}</span>
                                        </li>
                                    </ul>
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Difficulté à fournir des rapports en temps réel' : 'Difficulty providing real-time reports' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Difficulté d\'utilisation des données (sur papier)' : 'Difficulty using data (on paper)' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Visualisation difficile de la relation production-vente' : 'Difficult visualization of production-sales relationship' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Non-respect des quantités de matière et gaspillage' : 'Non-compliance with material quantities and waste' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Difficulté à obtenir des rapports dans un délai raisonnable' : 'Difficulty obtaining reports within reasonable time' }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <p class="mt-4 text-gray-700 italic text-sm border-t pt-3">
                                    {{ $isFrench ? 'En résumé, ces structures ne gagnent que parce que le prix de vente est très supérieur au coût de production, mais elles ne réalisent pas leur potentiel maximal.' : 'In summary, these structures only profit because the selling price is much higher than the production cost, but they do not achieve their maximum potential.' }}
                                </p>
                            </div>

                            <!-- Version Mobile -->
                            <div class="md:hidden">
                                <div class="text-center mb-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                                        <span class="text-2xl">💡</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $isFrench ? 'Problèmes Actuels' : 'Current Problems' }}
                                    </h3>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="bg-red-50 rounded-xl p-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-red-900 text-sm">{{ $isFrench ? 'Gestion Manuelle' : 'Manual Management' }}</h4>
                                                <p class="text-red-700 text-xs mt-1">{{ $isFrench ? 'Papier, cahiers, calculatrices...' : 'Paper, notebooks, calculators...' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-red-50 rounded-xl p-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-red-900 text-sm">{{ $isFrench ? 'Aucune Traçabilité' : 'No Traceability' }}</h4>
                                                <p class="text-red-700 text-xs mt-1">{{ $isFrench ? 'Ventes, pertes, stocks invisibles' : 'Sales, losses, invisible inventory' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-red-50 rounded-xl p-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-red-900 text-sm">{{ $isFrench ? 'Rentabilité Floue' : 'Unclear Profitability' }}</h4>
                                                <p class="text-red-700 text-xs mt-1">{{ $isFrench ? 'Impossible de mesurer les performances' : 'Impossible to measure performance' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-3 bg-amber-50 rounded-xl border-l-4 border-amber-400">
                                    <p class="text-amber-800 text-xs italic">
                                        {{ $isFrench ? 'Résultat : Potentiel non exploité' : 'Result: Untapped potential' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solution EasyGest -->
                    <div class="bg-white overflow-hidden shadow rounded-lg md:rounded-lg mt-6 mobile-card animate-slideInUp" style="animation-delay: 0.3s">
                        <div class="p-5">
                            <!-- Version Desktop -->
                            <div class="hidden md:block">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="text-green-500 mr-2">✅</span>
                                    {{ $isFrench ? 'Solution à tous ces problèmes: EasyGest' : 'Solution to all these problems: EasyGest' }}
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    {{ $isFrench ? 'Une application simple, mobile, tablette, PC... intuitive, en français, qui permet de:' : 'A simple, mobile, tablet, PC... intuitive application, available in multiple languages, which allows you to:' }}
                                </p>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Contrôle fin sur tous les aspects de votre structure' : 'Fine control over all aspects of your organization' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Élimination du papier et automatisation des données' : 'Paper elimination and data automation' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Analyse et prévisions pour booster le rendement' : 'Analysis and forecasting to boost performance' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Analyse et statistique complète' : 'Complete analysis and statistics' }}</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $isFrench ? 'Rapports détaillés en temps réel sur toute l\'entreprise' : 'Detailed real-time reports on the entire business' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Version Mobile -->
                            <div class="md:hidden">
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4 animate-pulse-gentle">
                                        <span class="text-2xl">✅</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                        {{ $isFrench ? 'EasyGest' : 'EasyGest' }}
                                    </h3>
                                    <p class="text-green-600 font-medium text-sm">
                                        {{ $isFrench ? 'La Solution Complète' : 'The Complete Solution' }}
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="bg-green-50 rounded-xl p-4 mobile-card">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900 text-sm">{{ $isFrench ? 'Calculs Précis' : 'Precise Calculations' }}</h4>
                                                <p class="text-green-700 text-xs mt-1">{{ $isFrench ? 'Coûts au gramme près' : 'Costs to the gram' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-green-50 rounded-xl p-4 mobile-card">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900 text-sm">{{ $isFrench ? 'Contrôle Total' : 'Total Control' }}</h4>
                                                <p class="text-green-700 text-xs mt-1">{{ $isFrench ? 'Production, ventes, finances' : 'Production, sales, finances' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-green-50 rounded-xl p-4 mobile-card">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900 text-sm">{{ $isFrench ? 'Statistiques Live' : 'Live Statistics' }}</h4>
                                                <p class="text-green-700 text-xs mt-1">{{ $isFrench ? 'Rapports temps réel' : 'Real-time reports' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-green-50 rounded-xl p-4 mobile-card">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900 text-sm">{{ $isFrench ? 'Sécurité Maximale' : 'Maximum Security' }}</h4>
                                                <p class="text-green-700 text-xs mt-1">{{ $isFrench ? 'Anti-vol et traçabilité' : 'Anti-theft and traceability' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 text-center">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl p-4 text-white">
                                        <p class="font-semibold text-sm mb-2">
                                            {{ $isFrench ? '🚀 Maximisez votre potentiel !' : '🚀 Maximize your potential!' }}
                                        </p>
                                        <p class="text-xs opacity-90">
                                            {{ $isFrench ? 'Passez du traditionnel au digital' : 'Move from traditional to digital' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

    </div>

    <script>
        // Gestion des onglets pour desktop
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('nav a');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    if (!this.getAttribute('href').startsWith('http')) {
                        e.preventDefault();
                        tabs.forEach(t => t.classList.remove('border-blue-500', 'text-blue-600'));
                        tabs.forEach(t => t.classList.add('border-transparent', 'text-gray-500'));
                        this.classList.remove('border-transparent', 'text-gray-500');
                        this.classList.add('border-blue-500', 'text-blue-600');
                    }
                });
            });

            // Animation des cartes mobiles au défilement
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.opacity = '1';
                    }
                });
            }, observerOptions);

            // Observer les cartes mobiles
            document.querySelectorAll('.mobile-card').forEach(card => {
                observer.observe(card);
            });

            // Gestion des onglets mobiles
            const mobileNavItems = document.querySelectorAll('.mobile-nav-item');
            mobileNavItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!this.getAttribute('href').startsWith('http')) {
                        e.preventDefault();
                        mobileNavItems.forEach(nav => {
                            nav.classList.remove('active', 'bg-blue-500', 'text-white');
                            nav.classList.add('text-gray-600');
                        });
                        this.classList.add('active', 'bg-blue-500', 'text-white');
                        this.classList.remove('text-gray-600');
                    }
                });
            });

            // Animation d'apparition progressive des éléments
            const animatedElements = document.querySelectorAll('.animate-slideInUp');
            animatedElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Effet de vibration subtile sur les cartes mobiles au toucher
            if (window.innerWidth <= 768) {
                document.querySelectorAll('.mobile-card').forEach(card => {
                    card.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });
                    
                    card.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                    });
                });
            }
        });

        // Animation de pulsation pour les éléments importants
        setInterval(() => {
            const pulseElements = document.querySelectorAll('.animate-pulse-gentle');
            pulseElements.forEach(element => {
                element.style.animation = 'none';
                setTimeout(() => {
                    element.style.animation = 'pulse 2s infinite';
                }, 10);
            });
        }, 4000);
    </script>
</body>
