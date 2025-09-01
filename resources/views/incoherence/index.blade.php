@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header - Mobile First Design -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="flex flex-col space-y-3 md:space-y-2">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Analyse Production/Ventes' : 'Production/Sales Analysis' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Tableau de bord des Relations Production/Vente' : 'Production/Sales Relationship Dashboard' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:py-8 md:px-4">
        <!-- Mobile Navigation Tabs - Swipeable on mobile -->
        <div class="mb-6 md:mb-8">
            <div class="flex overflow-x-auto scrollbar-hide border-b border-gray-200 space-x-1 md:space-x-0 pb-2 md:pb-0" id="tabContainer">
                <button class="tab-btn active flex-shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg whitespace-nowrap transition-all duration-300" 
                        onclick="openTab(event, 'resume')" data-tab="resume">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $isFrench ? 'Résumé' : 'Summary' }}</span>
                    </div>
                </button>
                <button class="tab-btn flex-shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg whitespace-nowrap transition-all duration-300" 
                        onclick="openTab(event, 'evolution')" data-tab="evolution">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span>{{ $isFrench ? 'Évolution' : 'Evolution' }}</span>
                    </div>
                </button>
                <button class="tab-btn flex-shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg whitespace-nowrap transition-all duration-300" 
                        onclick="openTab(event, 'top-produits')" data-tab="top-produits">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span>{{ $isFrench ? 'Top Produits' : 'Top Products' }}</span>
                    </div>
                </button>
                <button class="tab-btn flex-shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg whitespace-nowrap transition-all duration-300" 
                        onclick="openTab(event, 'alertes')" data-tab="alertes">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $isFrench ? 'Alertes' : 'Alerts' }}</span>
                    </div>
                </button>
                <button class="tab-btn flex-shrink-0 px-4 py-3 text-sm font-medium rounded-t-lg whitespace-nowrap transition-all duration-300" 
                        onclick="openTab(event, 'recommandations')" data-tab="recommandations">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $isFrench ? 'Recommandations' : 'Recommendations' }}</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Summary Tab -->
        <div id="resume" class="tab-content">
            <!-- KPI Cards - Mobile Optimized -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
                <!-- Total Products Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-blue-500">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="p-2 md:p-3 rounded-full bg-blue-100 text-blue-600 self-start md:self-center mb-2 md:mb-0 md:mr-4">
                            <svg class="h-5 w-5 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-xs md:text-sm font-medium">
                                {{ $isFrench ? 'Total Produits' : 'Total Products' }}
                            </h2>
                            <p class="text-lg md:text-2xl font-bold text-gray-800">{{ count($ratioProduitsVendus) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Profit Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-green-500">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="p-2 md:p-3 rounded-full bg-green-100 text-green-600 self-start md:self-center mb-2 md:mb-0 md:mr-4">
                            <svg class="h-5 w-5 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-xs md:text-sm font-medium">
                                {{ $isFrench ? 'Profit Total' : 'Total Profit' }}
                            </h2>
                            <p class="text-lg md:text-2xl font-bold text-gray-800">
                                {{ number_format(array_sum(array_column($ratioProduitsVendus, 'profit')), 0, ',', ' ') }} XAF
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Average Ratio Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-yellow-500">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="p-2 md:p-3 rounded-full bg-yellow-100 text-yellow-600 self-start md:self-center mb-2 md:mb-0 md:mr-4">
                            <svg class="h-5 w-5 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-xs md:text-sm font-medium">
                                {{ $isFrench ? 'Ratio Moyen' : 'Average Ratio' }}
                            </h2>
                            <p class="text-lg md:text-2xl font-bold text-gray-800">
                                @if(count($ratioProduitsVendus) != 0)
                                {{ number_format(array_sum(array_column($ratioProduitsVendus, 'ratio')) / count($ratioProduitsVendus), 1, ',', ' ') }}%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Alerts Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-red-500">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="p-2 md:p-3 rounded-full bg-red-100 text-red-600 self-start md:self-center mb-2 md:mb-0 md:mr-4">
                            <svg class="h-5 w-5 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-gray-600 text-xs md:text-sm font-medium">
                                {{ $isFrench ? 'Alertes' : 'Alerts' }}
                            </h2>
                            <p class="text-lg md:text-2xl font-bold text-gray-800">{{ count($alertesProduits) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Ratios Cards - Mobile Friendly -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Best Ratios Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-white">
                            {{ $isFrench ? 'Top 5 - Meilleurs Ratios' : 'Top 5 - Best Ratios' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="space-y-3">
                            @foreach($topMeilleursRatios as $index => $produit)
                            <div class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm md:text-base font-semibold text-gray-800 truncate">{{ $produit['nom'] }}</h4>
                                    <p class="text-xs md:text-sm text-gray-600">
                                        {{ number_format($produit['quantite_vendue'], 0) }}/{{ number_format($produit['quantite_produite'], 0) }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                                        {{ number_format($produit['ratio'], 1) }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Poor Ratios Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-white">
                            {{ $isFrench ? 'Top 5 - Faibles Ratios' : 'Top 5 - Poor Ratios' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="space-y-3">
                            @foreach($topPiresRatios as $index => $produit)
                            <div class="flex items-center p-3 rounded-lg bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-sm mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm md:text-base font-semibold text-gray-800 truncate">{{ $produit['nom'] }}</h4>
                                    <p class="text-xs md:text-sm text-gray-600">
                                        {{ number_format($produit['quantite_vendue'], 0) }}/{{ number_format($produit['quantite_produite'], 0) }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500 text-white">
                                        {{ number_format($produit['ratio'], 1) }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations Overview - Mobile Optimized -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-white">
                        {{ $isFrench ? 'Aperçu des Recommandations' : 'Recommendations Overview' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($recommandationsProduits as $codeProduit => $recommandation)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 space-y-2 sm:space-y-0">
                                <h4 class="font-semibold text-gray-800 text-sm md:text-base pr-2">{{ $recommandation['nom'] }}</h4>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white self-start
                                    {{ $recommandation['statut'] == 'augmenter' ? 'bg-green-500' :
                                       ($recommandation['statut'] == 'maintenir' ? 'bg-blue-500' :
                                       ($recommandation['statut'] == 'reduire' ? 'bg-yellow-500' :
                                       ($recommandation['statut'] == 'optimiser' ? 'bg-purple-500' : 'bg-red-500'))) }}">
                                    {{ $isFrench ? ucfirst($recommandation['statut']) : ucfirst($recommandation['statut']) }}
                                </span>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">{{ $recommandation['message'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Evolution Tab -->
        <div id="evolution" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg mb-6 md:mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-white">
                        {{ $isFrench ? 'Évolution du Ratio Produit/Vendu' : 'Product/Sales Ratio Evolution' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="h-64 md:h-96">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Tab -->
        <div id="top-produits" class="tab-content hidden">
            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-white">
                            {{ $isFrench ? 'Meilleurs Ratios' : 'Best Ratios' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="h-64 md:h-80">
                            <canvas id="topRatiosChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-4 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-white">
                            {{ $isFrench ? 'Faibles Ratios' : 'Poor Ratios' }}
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="h-64 md:h-80">
                            <canvas id="lowRatiosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Products Table -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-white">
                        {{ $isFrench ? 'Tous les Produits' : 'All Products' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6 overflow-x-auto">
                    <div class="min-w-full">
                        <!-- Mobile Cards View -->
                        <div class="md:hidden space-y-4">
                            @foreach($ratioProduitsVendus as $produit)
                            <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-semibold text-gray-800">{{ $produit['nom'] }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white
                                        {{ $produit['ratio'] >= 90 ? 'bg-green-500' :
                                           ($produit['ratio'] >= 75 ? 'bg-blue-500' :
                                           ($produit['ratio'] >= 50 ? 'bg-yellow-500' : 'bg-red-500')) }}">
                                        {{ number_format($produit['ratio'], 1) }}%
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">{{ $isFrench ? 'Produit' : 'Produced' }}:</span>
                                        <span class="font-medium block">{{ number_format($produit['quantite_produite'], 0, ',', ' ') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">{{ $isFrench ? 'Vendu' : 'Sold' }}:</span>
                                        <span class="font-medium block">{{ number_format($produit['quantite_vendue'], 0, ',', ' ') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">{{ $isFrench ? 'Coût' : 'Cost' }}:</span>
                                        <span class="font-medium block">{{ number_format($produit['cout_production'], 0, ',', ' ') }} XAF</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">{{ $isFrench ? 'Profit' : 'Profit' }}:</span>
                                        <span class="font-medium block {{ $produit['profit'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($produit['profit'], 0, ',', ' ') }} XAF
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Quantité Produite' : 'Produced Quantity' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Quantité Vendue' : 'Sold Quantity' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Ratio' : 'Ratio' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Coût Production' : 'Production Cost' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Valeur Ventes' : 'Sales Value' }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Profit' : 'Profit' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ratioProduitsVendus as $produit)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $produit['nom'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($produit['quantite_produite'], 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($produit['quantite_vendue'], 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white
                                            {{ $produit['ratio'] >= 90 ? 'bg-green-500' :
                                               ($produit['ratio'] >= 75 ? 'bg-blue-500' :
                                               ($produit['ratio'] >= 50 ? 'bg-yellow-500' : 'bg-red-500')) }}">
                                            {{ number_format($produit['ratio'], 1, ',', ' ') }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($produit['cout_production'], 0, ',', ' ') }} XAF</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($produit['valeur_ventes'], 0, ',', ' ') }} XAF</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white
                                            {{ $produit['profit'] > 0 ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ number_format($produit['profit'], 0, ',', ' ') }} XAF
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Tab -->
        <div id="alertes" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="bg-gradient-to-r from-red-600 to-red-700 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-white">
                        {{ $isFrench ? 'Alertes (Pertes > 5000 XAF)' : 'Alerts (Losses > 5000 XAF)' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    @if(count($alertesProduits) > 0)
                        <div class="space-y-4">
                            @foreach($alertesProduits as $alerte)
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                                    <div class="flex-shrink-0 text-blue-600 mr-0 sm:mr-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-blue-800 text-sm md:text-base">{{ $alerte['produit'] }} - {{ $alerte['date'] }}</h4>
                                        <p class="text-xs md:text-sm text-blue-600 mt-1">
                                            {{ $isFrench ? 'Invendus' : 'Unsold' }}: {{ $alerte['invendus'] }} {{ $isFrench ? 'unités' : 'units' }} -
                                            {{ $isFrench ? 'Perte estimée' : 'Estimated loss' }}: {{ number_format($alerte['perte'], 0, ',', ' ') }} XAF
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucune alerte' : 'No alerts' }}
                            </h4>
                            <p class="text-gray-500">
                                {{ $isFrench ? 'Aucune perte importante n\'a été détectée.' : 'No significant losses detected.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommendations Tab -->
        <div id="recommandations" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 md:p-6">
                    <h3 class="text-lg md:text-xl font-bold text-white">
                        {{ $isFrench ? 'Recommandations de Production' : 'Production Recommendations' }}
                    </h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="space-y-6">
                        @foreach($recommandationsProduits as $codeProduit => $recommandation)
                        <div class="border border-gray-200 rounded-lg p-4 md:p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-2 sm:space-y-0">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $recommandation['nom'] }}</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white self-start
                                    {{ $recommandation['statut'] == 'augmenter' ? 'bg-green-500' :
                                       ($recommandation['statut'] == 'maintenir' ? 'bg-blue-500' :
                                       ($recommandation['statut'] == 'reduire' ? 'bg-yellow-500' :
                                       ($recommandation['statut'] == 'optimiser' ? 'bg-purple-500' : 'bg-red-500'))) }}">
                                    {{ $isFrench ? ucfirst($recommandation['statut']) : ucfirst($recommandation['statut']) }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 ease-out
                                        {{ $recommandation['statut'] == 'augmenter' ? 'bg-green-500' :
                                           ($recommandation['statut'] == 'maintenir' ? 'bg-blue-500' :
                                           ($recommandation['statut'] == 'reduire' ? 'bg-yellow-500' :
                                           ($recommandation['statut'] == 'optimiser' ? 'bg-purple-500' : 'bg-red-500'))) }}"
                                         style="width: {{ $recommandation['statut'] == 'augmenter' ? '100%' :
                                                          ($recommandation['statut'] == 'maintenir' ? '80%' :
                                                          ($recommandation['statut'] == 'reduire' ? '60%' :
                                                          ($recommandation['statut'] == 'optimiser' ? '40%' : '20%'))) }}">
                                    </div>
                                </div>
                            </div>

                            <p class="text-sm md:text-base text-gray-600 mb-4 leading-relaxed">{{ $recommandation['message'] }}</p>

                            @php
                                $produitIndex = false;
                                foreach ($ratioProduitsVendus as $index => $prod) {
                                    if ($prod['nom'] === $recommandation['nom']) {
                                        $produitIndex = $index;
                                        break;
                                    }
                                }
                                $produit = $produitIndex !== false ? $ratioProduitsVendus[$produitIndex] : null;
                            @endphp

                            @if($produit)
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg">
                                    <span class="block text-gray-500 text-xs md:text-sm font-medium mb-1">
                                        {{ $isFrench ? 'Ratio' : 'Ratio' }}
                                    </span>
                                    <span class="text-lg md:text-xl font-bold text-gray-800">{{ number_format($produit['ratio'], 1) }}%</span>
                                </div>
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg">
                                    <span class="block text-gray-500 text-xs md:text-sm font-medium mb-1">
                                        {{ $isFrench ? 'Profit' : 'Profit' }}
                                    </span>
                                    <span class="text-lg md:text-xl font-bold {{ $produit['profit'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($produit['profit'], 0, ',', ' ') }} XAF
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 p-3 md:p-4 rounded-lg">
                                <p class="text-sm text-yellow-700">
                                    {{ $isFrench ? 'Données détaillées non disponibles' : 'Detailed data not available' }}
                                </p>
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

<!-- Footer -->
<footer class="bg-blue-900 text-white py-6 mt-8">
    <div class="container mx-auto px-4 text-center">
        <p>© {{ date('Y') }} Easy Gest</p>
    </div>
</footer>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        @apply px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-t-lg transition-all duration-300;
    }
    .tab-btn.active {
        @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg;
    }

    /* Mobile enhancements */
    @media (max-width: 768px) {
        .container {
            @apply px-3;
        }
        
        /* Smooth touch scrolling for tabs */
        #tabContainer {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Enhanced touch targets */
        .tab-btn {
            @apply min-w-max;
        }
    }
</style>

<script>
// Tab functionality with mobile optimizations
function openTab(evt, tabName) {
    var i, tabContent, tabButtons;

    // Hide all tab contents
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].classList.add("hidden");
        tabContent[i].classList.remove("block");
    }

    // Remove active class from all tab buttons
    tabButtons = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }

    // Show selected tab content and add active class to button
    document.getElementById(tabName).classList.remove("hidden");
    document.getElementById(tabName).classList.add("block");
    evt.currentTarget.classList.add("active");

    // Scroll active tab into view on mobile
    if (window.innerWidth < 768) {
        evt.currentTarget.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });
    }
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Chart color schemes
    const chartColors = {
        primary: '#1D4ED8',
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
        purple: '#8B5CF6'
    };

    // Evolution data
    const evolutionData = {!! $dataEvolutionRatio !!};

    // Group data by date
    const dateGrouped = {};
    evolutionData.forEach(item => {
        if (!dateGrouped[item.date]) {
            dateGrouped[item.date] = {};
        }
        dateGrouped[item.date][item.produit] = item.ratio;
    });

    // Extract dates and unique products
    const dates = Object.keys(dateGrouped).sort();
    const uniqueProducts = [...new Set(evolutionData.map(item => item.produit))];

    // Create datasets for each product
    const datasets = uniqueProducts.map((product, index) => {
        const colorIndex = index % 5;
        const colors = [chartColors.primary, chartColors.success, chartColors.warning, chartColors.danger, chartColors.purple];

        return {
            label: product,
            data: dates.map(date => dateGrouped[date][product] || 0),
            borderColor: colors[colorIndex],
            backgroundColor: colors[colorIndex] + '33',
            tension: 0.4,
            pointRadius: 3,
            pointHoverRadius: 6,
            borderWidth: 2
        };
    });

    // Evolution Chart
    new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ddd',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Ratio (%)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Top ratios data
    const topRatios = {!! json_encode($topMeilleursRatios) !!};
    const lowRatios = {!! json_encode($topPiresRatios) !!};

    // Top Ratios Chart
    new Chart(document.getElementById('topRatiosChart'), {
        type: 'bar',
        data: {
            labels: topRatios.map(item => item.nom),
            datasets: [{
                label: 'Ratio (%)',
                data: topRatios.map(item => item.ratio),
                backgroundColor: chartColors.success + 'CC',
                borderColor: chartColors.success,
                borderWidth: 2,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                }
            }
        }
    });

    // Low Ratios Chart
    new Chart(document.getElementById('lowRatiosChart'), {
        type: 'bar',
        data: {
            labels: lowRatios.map(item => item.nom),
            datasets: [{
                label: 'Ratio (%)',
                data: lowRatios.map(item => item.ratio),
                backgroundColor: chartColors.danger + 'CC',
                borderColor: chartColors.danger,
                borderWidth: 2,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                }
            }
        }
    });

    // Touch gestures for mobile tab navigation
    if (window.innerWidth < 768) {
        let startX = 0;
        let currentTabIndex = 0;
        const tabs = ['resume', 'evolution', 'top-produits', 'alertes', 'recommandations'];

        document.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        document.addEventListener('touchend', function(e) {
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;

            if (Math.abs(diffX) > 50) { // Minimum swipe distance
                if (diffX > 0 && currentTabIndex < tabs.length - 1) {
                    // Swipe left - next tab
                    currentTabIndex++;
                } else if (diffX < 0 && currentTabIndex > 0) {
                    // Swipe right - previous tab
                    currentTabIndex--;
                }

                // Simulate tab click
                const tabButton = document.querySelector(`[data-tab="${tabs[currentTabIndex]}"]`);
                if (tabButton) {
                    tabButton.click();
                }
            }
        });
    }
});
</script>
@endpush
@endsection