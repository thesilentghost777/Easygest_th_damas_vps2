@extends('layouts.app')

@section('title', $isFrench ? 'Gestion des Manquants et Flux' : 'Missing Items & Flow Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl px-6 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $isFrench ? 'Flux Journaliers et Manquants' : 'Daily Flows & Missing Items' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $isFrench ? 'Suivi en temps réel des flux de production' : 'Real-time production flow tracking' }}
                            </p>
                        </div>
                    </div>
                    
                    <button type="button" 
                            id="openModalBtn"
                            class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center space-x-2 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $isFrench ? 'Calculer Flux' : 'Calculate Flow' }}</span>
                    </button>
                   <button type="button"
        onclick="window.location.href='{{ route('manquant-flux.repartition') }}'"
        class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center space-x-2 transform hover:scale-105">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
        </path>
    </svg>
    <span>{{ $isFrench ? 'Répartir Manquants' : 'Distribute Missing Items' }}</span>
</button>


                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="mx-6 mt-6">
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center space-x-3 shadow-sm">
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                        <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Data Table -->
            <div class="p-6">
                @if($flux->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-100">
                                    <th class="text-left py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Date' : 'Date' }}
                                    </th>
                                    <th class="text-left py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Production' : 'Production' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Pointage' : 'Tracking' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Réception Vendeur' : 'Vendor Reception' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Manquants' : 'Missing' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Montant Total' : 'Total Amount' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Actions' : 'Actions' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($flux as $item)
                                    @php
                                        $manquant = $item->manquant;
                                        $manquantProdPointeur = max(0, $item->total_production - $item->total_pointage);
                                        $manquantPointeurVendeur = max(0, $item->total_pointage - $item->total_reception_vendeur);
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="py-4 px-3">
                                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-3 py-2 rounded-lg font-medium text-sm inline-block">
                                                {{ $item->date_flux->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="bg-green-100 rounded-full p-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $item->produit->nom ?? ($isFrench ? 'Produit inconnu' : 'Unknown Product') }}</div>
                                                    <div class="text-sm text-gray-500">Code: {{ $item->produit_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-green-100 text-green-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($item->total_production, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($item->total_pointage, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($item->total_reception_vendeur, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            @if($manquantProdPointeur > 0 || $manquantPointeurVendeur > 0 || ($manquant && $manquant->manquant_vendeur_invendu > 0))
                                                <div class="space-y-1">
                                                    @if($manquantProdPointeur > 0)
                                                        <div class="bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium">
                                                            {{ $isFrench ? 'Prod-Point' : 'Prod-Track' }}: {{ number_format($manquantProdPointeur, 0) }}
                                                        </div>
                                                    @endif
                                                    @if($manquantPointeurVendeur > 0)
                                                        <div class="bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium">
                                                            {{ $isFrench ? 'Point-Vend' : 'Track-Vend' }}: {{ number_format($manquantPointeurVendeur, 0) }}
                                                        </div>
                                                    @endif
                                                    @if($manquant && $manquant->manquant_vendeur_invendu > 0)
                                                        <div class="bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium">
                                                            {{ $isFrench ? 'Invendu' : 'Unsold' }}: {{ number_format($manquant->manquant_vendeur_invendu, 0) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ $isFrench ? 'Aucun' : 'None' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            @if($manquant)
                                                <div class="font-bold text-red-600 bg-red-50 px-3 py-2 rounded-lg">
                                                    {{ number_format($manquant->total_montant, 0) }} FCFA
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <a href="{{ route('manquants_flux.details', $item->id) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-2 transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>{{ $isFrench ? 'Détails' : 'Details' }}</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @foreach($flux as $item)
                            @php
                                $manquant = $item->manquant;
                                $manquantProdPointeur = max(0, $item->total_production - $item->total_pointage);
                                $manquantPointeurVendeur = max(0, $item->total_pointage - $item->total_reception_vendeur);
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="font-semibold text-gray-900 mb-1">{{ $item->produit->nom ?? ($isFrench ? 'Produit inconnu' : 'Unknown Product') }}</div>
                                        <div class="text-sm text-gray-500">Code: {{ $item->produit_id }}</div>
                                    </div>
                                    <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-3 py-1 rounded-lg text-sm font-medium">
                                        {{ $item->date_flux->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Production' : 'Production' }}</div>
                                        <div class="bg-green-100 text-green-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($item->total_production, 0) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Pointage' : 'Tracking' }}</div>
                                        <div class="bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($item->total_pointage, 0) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Réception' : 'Reception' }}</div>
                                        <div class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($item->total_reception_vendeur, 0) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Montant' : 'Amount' }}</div>
                                        @if($manquant)
                                            <div class="font-bold text-red-600 bg-red-50 px-3 py-2 rounded-lg text-sm">
                                                {{ number_format($manquant->total_montant, 0) }} FCFA
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($manquantProdPointeur > 0 || $manquantPointeurVendeur > 0 || ($manquant && $manquant->manquant_vendeur_invendu > 0))
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-500 mb-2">{{ $isFrench ? 'Manquants' : 'Missing' }}</div>
                                        <div class="space-y-2">
                                            @if($manquantProdPointeur > 0)
                                                <div class="bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm font-medium">
                                                    {{ $isFrench ? 'Prod-Point' : 'Prod-Track' }}: {{ number_format($manquantProdPointeur, 0) }}
                                                </div>
                                            @endif
                                            @if($manquantPointeurVendeur > 0)
                                                <div class="bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm font-medium">
                                                    {{ $isFrench ? 'Point-Vend' : 'Track-Vend' }}: {{ number_format($manquantPointeurVendeur, 0) }}
                                                </div>
                                            @endif
                                            @if($manquant && $manquant->manquant_vendeur_invendu > 0)
                                                <div class="bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm font-medium">
                                                    {{ $isFrench ? 'Invendu' : 'Unsold' }}: {{ number_format($manquant->manquant_vendeur_invendu, 0) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <a href="{{ route('manquants_flux.details', $item->id) }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>{{ $isFrench ? 'Voir les détails' : 'View Details' }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                            {{ $flux->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="bg-gradient-to-br from-blue-100 to-green-100 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $isFrench ? 'Aucun flux journalier trouvé' : 'No daily flows found' }}
                        </h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            {{ $isFrench ? 'Commencez par calculer les flux pour une date donnée en utilisant le bouton ci-dessus.' : 'Start by calculating flows for a specific date using the button above.' }}
                        </p>
                        <button type="button" 
                                id="openModalBtnEmpty"
                                class="bg-gradient-to-r from-blue-600 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            {{ $isFrench ? 'Calculer les flux maintenant' : 'Calculate flows now' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tailwind CSS -->
<div id="calculerModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto transform transition-all">
            <form method="POST" action="{{ route('manquants_flux.calculer') }}" id="calculerForm">
                @csrf
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl px-6 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-white/20 rounded-xl p-3 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h5 class="text-xl font-bold">
                                {{ $isFrench ? 'Calculer Flux et Manquants' : 'Calculate Flow & Missing Items' }}
                            </h5>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="p-8">
                    <div class="mb-6">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Date de calcul' : 'Calculation Date' }}
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="date" 
                                   name="date" 
                                   value="{{ date('Y-m-d') }}" 
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            {{ $isFrench ? 'Sélectionnez la date pour laquelle calculer les flux et manquants.' : 'Select the date for which to calculate flows and missing items.' }}
                        </p>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-full p-2 flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h6 class="font-semibold text-blue-900 mb-2">
                                    {{ $isFrench ? 'Information' : 'Information' }}
                                </h6>
                                <p class="text-blue-800 text-sm leading-relaxed">
                                    {{ $isFrench ? 'Cette opération calculera automatiquement tous les flux et manquants pour la date sélectionnée en analysant les données de production, pointage et réception vendeur.' : 'This operation will automatically calculate all flows and missing items for the selected date by analyzing production, tracking, and vendor reception data.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-4 rounded-b-2xl flex justify-end space-x-4">
                    <button type="button" 
                            id="cancelBtn"
                            class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-xl transition-colors duration-200">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $isFrench ? 'Calculer' : 'Calculate' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Smooth transitions */
    .transform {
        transition: transform 0.2s ease-in-out;
    }
    
    /* Modal animation */
    #calculerModal.modal-show {
        display: flex !important;
        animation: modalFadeIn 0.3s ease-out;
    }
    
    #calculerModal.modal-hide {
        animation: modalFadeOut 0.3s ease-in;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes modalFadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    /* Loading states */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin-top: -10px;
        margin-left: -10px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Mobile responsiveness */
    @media (max-width: 640px) {
        #calculerModal .relative {
            margin: 1rem;
            max-height: calc(100vh - 2rem);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('calculerModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const openModalBtnEmpty = document.getElementById('openModalBtnEmpty');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const submitBtn = document.getElementById('submitBtn');
    const calculerForm = document.getElementById('calculerForm');
    
    // Open modal function
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('modal-show');
        document.body.style.overflow = 'hidden';
        
        // Focus on date input
        setTimeout(() => {
            const dateInput = document.getElementById('date');
            if (dateInput) dateInput.focus();
        }, 100);
    }
    
    // Close modal function
    function closeModal() {
        modal.classList.add('modal-hide');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-show', 'modal-hide');
            document.body.style.overflow = '';
        }, 300);
    }
    
    // Event listeners
    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    
    if (openModalBtnEmpty) {
        openModalBtnEmpty.addEventListener('click', openModal);
    }
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    // Close modal when clicking backdrop
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Form submission with loading state
    if (calculerForm) {
        calculerForm.addEventListener('submit', function() {
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Update button text
                const span = submitBtn.querySelector('span');
                if (span) {
                    span.textContent = '{{ $isFrench ? "Calcul en cours..." : "Calculating..." }}';
                }
            }
        });
    }
    
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.bg-green-50');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    });
    
    // Enhanced table row hover effects
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Scroll to top functionality
    if (document.body.scrollHeight > window.innerHeight * 2) {
        const scrollBtn = document.createElement('button');
        scrollBtn.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        `;
        scrollBtn.className = 'fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-110 z-40';
        scrollBtn.style.display = 'none';
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        document.body.appendChild(scrollBtn);
        
        // Show/hide scroll button
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection