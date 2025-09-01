@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Analyse Approfondie des Produits' : 'In-Depth Product Analysis' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Performance détaillée et insights pour optimiser vos ventes' : 'Detailed performance and insights to optimize your sales' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 space-y-6">
        <!-- Period Selector - Mobile Optimized -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:shadow-xl transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $isFrench ? 'Sélecteur de période' : 'Period Selector' }}
            </h3>
            
            <form action="{{ route('analyse.produits') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4 md:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date de début' : 'Start Date' }}
                    </label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date de fin' : 'End Date' }}
                    </label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit" 
                            class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- KPI Cards - Mobile Responsive -->
        @if(count($produitsAnalysePaginated) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Top Revenue -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 md:p-3 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right flex-1 ml-3">
                        <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider">
                            {{ $isFrench ? 'Top CA' : 'Top Revenue' }}
                        </h3>
                        <p class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ $produitsAnalysePaginated[0]['nom'] }}</p>
                        <p class="text-sm md:text-base font-semibold text-blue-600">{{ number_format($produitsAnalysePaginated[0]['chiffre_affaire']) }} FCFA</p>
                    </div>
                </div>
            </div>
            
            <!-- Top Profit -->
            @php
                $topBenefice = collect($produitsAnalysePaginated)->sortByDesc('benefice')->first();
            @endphp
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 md:p-3 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="text-right flex-1 ml-3">
                        <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider">
                            {{ $isFrench ? 'Top Bénéfice' : 'Top Profit' }}
                        </h3>
                        <p class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ $topBenefice['nom'] }}</p>
                        <p class="text-sm md:text-base font-semibold text-green-600">{{ number_format($topBenefice['benefice']) }} FCFA</p>
                    </div>
                </div>
            </div>
            
            <!-- Most Sold -->
            @php
                $topQuantite = collect($produitsAnalysePaginated)->sortByDesc('quantite_vendue')->first();
            @endphp
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-amber-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 md:p-3 bg-amber-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div class="text-right flex-1 ml-3">
                        <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider">
                            {{ $isFrench ? 'Plus Vendu' : 'Best Seller' }}
                        </h3>
                        <p class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ $topQuantite['nom'] }}</p>
                        <p class="text-sm md:text-base font-semibold text-amber-600">{{ number_format($topQuantite['quantite_vendue']) }} {{ $isFrench ? 'unités' : 'units' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- To Improve -->
            @php
                $worstRatio = collect($produitsAnalysePaginated)
                    ->filter(function($p) { return $p['quantite_vendue'] > 0; })
                    ->sortBy('benefice_par_unite')
                    ->first();
            @endphp
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 transform hover:scale-105 transition-all duration-300 border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 md:p-3 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="text-right flex-1 ml-3">
                        <h3 class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-wider">
                            {{ $isFrench ? 'À Améliorer' : 'To Improve' }}
                        </h3>
                        <p class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ $worstRatio ? $worstRatio['nom'] : 'N/A' }}</p>
                        <p class="text-sm md:text-base font-semibold text-red-600">{{ $worstRatio ? number_format($worstRatio['benefice_par_unite']) : 0 }} FCFA/{{ $isFrench ? 'unité' : 'unit' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-600">{{ $isFrench ? 'Aucune donnée disponible pour la période sélectionnée' : 'No data available for the selected period' }}</p>
        </div>
        @endif

        <!-- Main Products Table - Mobile Responsive -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <h2 class="text-xl md:text-2xl font-bold text-white">
                        {{ $isFrench ? 'Performance des Produits' : 'Product Performance' }}
                    </h2>
                    <div class="text-sm text-yellow-900 bg-yellow-300 px-4 py-2 rounded-md animate-pulse shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        <span class="font-semibold">{{ $isFrench ? 'Cliquez sur les en-têtes pour trier' : 'Click headers to sort' }}</span>
                    </div>
                    
                    
                    
                </div>
            </div>
            
            <div class="p-4 md:p-6">
                <!-- Mobile Cards View -->
                <div class="md:hidden space-y-4">
                    @foreach($produitsAnalysePaginated as $produit)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-800 text-lg">{{ $produit['nom'] }}</h3>
                            <a href="{{ route('analyse.produits.details', $produit['id']) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                {{ $isFrench ? 'Détails' : 'Details' }} →
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-blue-50 p-2 rounded">
                                <span class="block text-gray-500 text-xs">{{ $isFrench ? 'CA' : 'Revenue' }}</span>
                                <span class="font-semibold text-blue-600">{{ number_format($produit['chiffre_affaire']) }} FCFA</span>
                            </div>
                            <div class="bg-green-50 p-2 rounded">
                                <span class="block text-gray-500 text-xs">{{ $isFrench ? 'Bénéfice' : 'Profit' }}</span>
                                <span class="font-semibold {{ $produit['benefice'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($produit['benefice']) }} FCFA
                                </span>
                            </div>
                            <div class="bg-amber-50 p-2 rounded">
                                <span class="block text-gray-500 text-xs">{{ $isFrench ? 'Vendues' : 'Sold' }}</span>
                                <span class="font-semibold text-amber-600">{{ $produit['quantite_vendue'] }}</span>
                            </div>
                            <div class="bg-purple-50 p-2 rounded">
                                <span class="block text-gray-500 text-xs">{{ $isFrench ? 'Marge' : 'Margin' }}</span>
                                <span class="font-semibold {{ $produit['marge'] > 30 ? 'text-green-600' : ($produit['marge'] > 15 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ number_format($produit['marge'], 1) }}%
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-3 bg-gray-50 p-2 rounded">
                            <span class="block text-gray-500 text-xs">{{ $isFrench ? 'Ratio Prod/Vente' : 'Prod/Sales Ratio' }}</span>
                            <span class="font-semibold text-gray-700">{{ number_format($produit['ratio_production_vente'], 1) }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut, 
                                        'date_fin' => $dateFin, 
                                        'sort_by' => 'chiffre_affaire', 
                                        'sort_order' => ($sortBy == 'chiffre_affaire' && $sortOrder == 'desc') ? 'asc' : 'desc'
                                    ]) }}" class="flex items-center">
                                        {{ $isFrench ? 'CA' : 'Revenue' }}
                                        @if($sortBy == 'chiffre_affaire')
                                            <span class="ml-1">
                                                @if($sortOrder == 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15-6-6-6 6"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut, 
                                        'date_fin' => $dateFin, 
                                        'sort_by' => 'benefice', 
                                        'sort_order' => ($sortBy == 'benefice' && $sortOrder == 'desc') ? 'asc' : 'desc'
                                    ]) }}" class="flex items-center">
                                        {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                                        @if($sortBy == 'benefice')
                                            <span class="ml-1">
                                                @if($sortOrder == 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15-6-6-6 6"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut, 
                                        'date_fin' => $dateFin, 
                                        'sort_by' => 'quantite_vendue', 
                                        'sort_order' => ($sortBy == 'quantite_vendue' && $sortOrder == 'desc') ? 'asc' : 'desc'
                                    ]) }}" class="flex items-center">
                                        {{ $isFrench ? 'Quantité Vendue' : 'Quantity Sold' }}
                                        @if($sortBy == 'quantite_vendue')
                                            <span class="ml-1">
                                                @if($sortOrder == 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15-6-6-6 6"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut, 
                                        'date_fin' => $dateFin, 
                                        'sort_by' => 'marge', 
                                        'sort_order' => ($sortBy == 'marge' && $sortOrder == 'desc') ? 'asc' : 'desc'
                                    ]) }}" class="flex items-center">
                                        {{ $isFrench ? 'Marge' : 'Margin' }}
                                        @if($sortBy == 'marge')
                                            <span class="ml-1">
                                                @if($sortOrder == 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15-6-6-6 6"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut, 
                                        'date_fin' => $dateFin, 
                                        'sort_by' => 'ratio_production_vente', 
                                        'sort_order' => ($sortBy == 'ratio_production_vente' && $sortOrder == 'desc') ? 'asc' : 'desc'
                                    ]) }}" class="flex items-center">
                                        {{ $isFrench ? 'Ratio Prod/Vente' : 'Prod/Sales Ratio' }}
                                        @if($sortBy == 'ratio_production_vente')
                                            <span class="ml-1">
                                                @if($sortOrder == 'asc')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15-6-6-6 6"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Détails' : 'Details' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($produitsAnalysePaginated as $produit)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $produit['nom'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-blue-600 font-semibold">
                                    {{ number_format($produit['chiffre_affaire']) }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold {{ $produit['benefice'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($produit['benefice']) }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $produit['quantite_vendue'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold {{ $produit['marge'] > 30 ? 'text-green-600' : ($produit['marge'] > 15 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ number_format($produit['marge'], 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ number_format($produit['ratio_production_vente'], 1) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('analyse.produits.details', $produit['id']) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                        {{ $isFrench ? 'Voir détails' : 'View details' }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile-Friendly Pagination -->
                @if($totalPages > 1)
                <div class="mt-6">
                    <div class="flex justify-center">
                        <nav class="relative z-0 inline-flex rounded-lg shadow-sm space-x-1" aria-label="Pagination">
                            <!-- Previous Button -->
                            @if($page > 1)
                            <a href="{{ route('analyse.produits', [
                                'date_debut' => $dateDebut,
                                'date_fin' => $dateFin,
                                'sort_by' => $sortBy,
                                'sort_order' => $sortOrder,
                                'page' => $page - 1
                            ]) }}" class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="hidden sm:block ml-1">{{ $isFrench ? 'Précédent' : 'Previous' }}</span>
                            </a>
                            @else
                            <span class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                            @endif
                            
                            <!-- Page Numbers - Show limited on mobile -->
                            @php
                                $showPages = 5;
                                $startPage = max(1, $page - floor($showPages / 2));
                                $endPage = min($totalPages, $startPage + $showPages - 1);
                                $startPage = max(1, $endPage - $showPages + 1);
                            @endphp
                            
                            @for ($i = $startPage; $i <= $endPage; $i++)
                                @if ($i == $page)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ route('analyse.produits', [
                                        'date_debut' => $dateDebut,
                                        'date_fin' => $dateFin,
                                        'sort_by' => $sortBy,
                                        'sort_order' => $sortOrder,
                                        'page' => $i
                                    ]) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            
                            <!-- Next Button -->
                            @if($page < $totalPages)
                            <a href="{{ route('analyse.produits', [
                                'date_debut' => $dateDebut,
                                'date_fin' => $dateFin,
                                'sort_by' => $sortBy,
                                'sort_order' => $sortOrder,
                                'page' => $page + 1
                            ]) }}" class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                                <span class="hidden sm:block mr-1">{{ $isFrench ? 'Suivant' : 'Next' }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @else
                            <span class="relative inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                            @endif
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Popular but Less Profitable Products -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-red-600 to-red-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Produits Populaires Mais Peu Rentables' : 'Popular But Less Profitable Products' }}
                </h2>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($produitsPopulairesNonRentables as $produit)
                        <div class="border border-red-200 rounded-lg p-4 bg-red-50 hover:shadow-md transition-all duration-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 space-y-2 sm:space-y-0">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $produit['nom'] }}</h3>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500 text-white">
                                    {{ $isFrench ? 'Marge' : 'Margin' }}: {{ number_format($produit['marge'], 1) }}%
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-white p-2 rounded">
                                    <span class="text-gray-600">{{ $isFrench ? 'Ventes' : 'Sales' }}:</span>
                                    <span class="font-medium block">{{ number_format($produit['quantite_vendue']) }} {{ $isFrench ? 'unités' : 'units' }}</span>
                                </div>
                                <div class="bg-white p-2 rounded">
                                    <span class="text-gray-600">{{ $isFrench ? 'CA' : 'Revenue' }}:</span>
                                    <span class="font-medium block">{{ number_format($produit['chiffre_affaire']) }} FCFA</span>
                                </div>
                                <div class="bg-white p-2 rounded">
                                    <span class="text-gray-600">{{ $isFrench ? 'Bénéfice' : 'Profit' }}:</span>
                                    <span class="font-medium block {{ $produit['benefice'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($produit['benefice']) }} FCFA
                                    </span>
                                </div>
                                <div class="bg-white p-2 rounded">
                                    <span class="text-gray-600">{{ $isFrench ? 'Bénéfice/unité' : 'Profit/unit' }}:</span>
                                    <span class="font-medium block {{ $produit['benefice_par_unite'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($produit['benefice_par_unite']) }} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-6 border border-gray-200 rounded-lg bg-gray-50 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600">{{ $isFrench ? 'Aucun produit dans cette catégorie' : 'No products in this category' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Least Sold Products -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Produits Les Moins Vendus' : 'Least Sold Products' }}
                </h2>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($produitsLessMoinsVendus as $produit)
                        <div class="border border-orange-200 rounded-lg p-4 bg-orange-50 hover:shadow-md transition-all duration-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 space-y-2 sm:space-y-0">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $produit['nom'] }}</h3>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-500 text-white">
                                    {{ $produit['quantite_vendue'] }} {{ $isFrench ? 'unités' : 'units' }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'CA' : 'Revenue' }}:</span>
                                    <span class="font-medium">{{ number_format($produit['chiffre_affaire']) }} FCFA</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Bénéfice' : 'Profit' }}:</span>
                                    <span class="font-medium {{ $produit['benefice'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($produit['benefice']) }} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 p-6 border border-gray-200 rounded-lg bg-gray-50 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600">{{ $isFrench ? 'Aucun produit dans cette catégorie' : 'No products in this category' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sales Evolution Chart -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Évolution des Ventes par Produit' : 'Sales Evolution by Product' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="h-64 md:h-96">
                    <canvas id="evolutionVentes"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products by Day -->
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ $isFrench ? 'Produits Performants par Jour' : 'Top Performing Products by Day' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($topParJour as $jour => $produit)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-300 bg-gradient-to-br from-green-50 to-blue-50">
                        <h3 class="font-semibold text-lg mb-2 text-gray-800">{{ $jour }}</h3>
                        @if($produit)
                            <p class="text-blue-600 font-medium">{{ $produit->nom }}</p>
                            <p class="text-sm text-gray-600">
                                {{ number_format($produit->total_ventes) }} FCFA
                            </p>
                        @else
                            <p class="text-gray-500">{{ $isFrench ? 'Aucune vente' : 'No sales' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const evolutionData = @json($evolutionVentes);
        const isFrench = @json($isFrench ?? true);
        
        const ctx = document.getElementById('evolutionVentes').getContext('2d');
        const datasets = Object.entries(evolutionData).map(([produit, donnees], index) => {
            const colors = [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
                '#EC4899', '#06B6D4', '#F97316', '#84CC16', '#6366F1'
            ];
            
            return {
                label: produit,
                data: donnees.map(d => ({
                    x: d.mois,
                    y: d.total_ventes
                })),
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '20',
                borderWidth: 3,
                tension: 0.4,
                fill: false,
                pointRadius: 4,
                pointHoverRadius: 6
            };
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                datasets
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
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat().format(context.parsed.y) + ' FCFA';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: isFrench ? 'Mois' : 'Month'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: isFrench ? 'Chiffre d\'affaires (FCFA)' : 'Revenue (FCFA)'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat().format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection

