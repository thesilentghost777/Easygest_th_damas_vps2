@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
   
    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5 flex flex-col md:flex-row md:items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Détail de la Distribution' : 'Distribution Details' }} - {{ $distribution->date->format('d/m/Y') }}
                    </h2>
                    <div class="mt-3 md:mt-0 flex space-x-2">
                        @if($distribution->status === 'en_cours')
                            <a href="{{ route('cash.distributions.edit', $distribution) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>
                            @if($flag == 0)
                            <a href="{{ route('cash.distributions.close.form', $distribution) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10H12V2Z"/><path d="M12 2a10 10 0 0 1 10 10"/><path d="M12 12h10"/></svg>
                                {{ $isFrench ? 'Clôturer' : 'Close' }}
                            </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Status Card -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        @if($distribution->status === 'en_cours')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        @endif
                    </div>
                    @if($distribution->status === 'en_cours')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $isFrench ? 'En cours' : 'In progress' }}
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $isFrench ? 'Clôturé' : 'Closed' }}
                        </span>
                    @endif
                </div>

                <!-- Mobile Action Buttons -->
                @if($distribution->status === 'en_cours')
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <a href="{{ route('cash.distributions.edit', $distribution) }}" class="bg-green-600 text-white text-center py-3 px-4 rounded-2xl font-semibold shadow-lg hover:bg-green-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                        </a>
                        @if($flag == 0)
                        <a href="{{ route('cash.distributions.close.form', $distribution) }}" class="bg-blue-600 text-white text-center py-3 px-4 rounded-2xl font-semibold shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2a10 10 0 1 0 10 10H12V2Z"/>
                                <path d="M12 2a10 10 0 0 1 10 10"/>
                                <path d="M12 12h10"/>
                            </svg>
                            {{ $isFrench ? 'Clôturer' : 'Close' }}
                        </a>
                        @endif
                    </div>
                @endif

                <!-- Mobile Info Cards -->
                <div class="space-y-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-fade-in">
                        <p class="text-sm font-medium text-blue-600 mb-1">{{ $isFrench ? 'Vendeuse' : 'Seller' }}</p>
                        <p class="font-bold text-blue-900 text-lg">{{ $distribution->user->name }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-fade-in">
                        <p class="text-sm font-medium text-blue-600 mb-1">{{ $isFrench ? 'Date' : 'Date' }}</p>
                        <p class="font-bold text-blue-900 text-lg">{{ $distribution->date->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Mobile Main Info -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-2xl border-l-4 border-blue-500 shadow-md">
                        <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Montant obtenu' : 'Amount received' }}</p>
                        <p class="font-bold text-blue-900 text-lg">{{ number_format($distribution->bill_amount, 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border-l-4 border-blue-500 shadow-md">
                        <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Monnaie initiale' : 'Initial change' }}</p>
                        <p class="font-bold text-blue-900 text-lg">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} XAF</p>
                    </div>
                </div>

                <!-- Mobile Sales Amount -->
                <div class="bg-green-50 p-4 rounded-2xl border-l-4 border-green-500 mb-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 mb-1">{{ $isFrench ? 'Montant des ventes' : 'Sales amount' }}</p>
                            <p class="font-bold text-green-900 text-xl">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} XAF</p>
                        </div>
                        <form action="{{ route('cash.distributions.update-sales', $distribution) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 hover:bg-green-200 transform hover:scale-110 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 2v6h-6"/>
                                    <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/>
                                    <path d="M3 22v-6h6"/>
                                    <path d="M21 12a9 9 0 0 1-15 6.7L3 16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                @if($distribution->status === 'cloture')
                    <!-- Mobile Closure Info -->
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-2xl border-l-4 border-blue-500 shadow-md">
                                <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Monnaie finale' : 'Final change' }}</p>
                                <p class="font-bold text-blue-900">{{ number_format($distribution->final_coin_amount, 0, ',', ' ') }} XAF</p>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border-l-4 border-green-500 shadow-md">
                                <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Montant versé' : 'Deposited amount' }}</p>
                                <p class="font-bold text-green-900">{{ number_format($distribution->deposited_amount, 0, ',', ' ') }} XAF</p>
                            </div>
                        </div>

                        <div class="bg-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-50 p-4 rounded-2xl border-l-4 border-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-600 mb-1">{{ $isFrench ? 'Montant manquant' : 'Missing amount' }}</p>
                                    <p class="font-bold text-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-900 text-xl">
                                        @if($distribution->missing_amount > 0)
                                            {{ number_format($distribution->missing_amount, 0, ',', ' ') }} XAF
                                        @else
                                            0 XAF
                                        @endif
                                    </p>
                                </div>
                                <form action="{{ route('cash.distributions.update-missing', $distribution) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-10 h-10 bg-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-100 rounded-full flex items-center justify-center text-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-600 hover:bg-{{ $distribution->missing_amount > 0 ? 'red' : 'green' }}-200 transform hover:scale-110 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 2v6h-6"/>
                                            <path d="M3 12a9 9 0 0 1 15-6.7L21 8"/>
                                            <path d="M3 22v-6h6"/>
                                            <path d="M21 12a9 9 0 0 1-15 6.7L3 16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-2xl">
                            <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Clôturé par' : 'Closed by' }}</p>
                            <p class="text-gray-800">{{ $distribution->closedByUser->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $distribution->closed_at ? $distribution->closed_at->format('d/m/Y à H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                @endif

                @if($distribution->notes)
                    <div class="bg-gray-50 p-4 rounded-2xl mb-6">
                        <p class="text-sm font-medium text-gray-600 mb-2">{{ $isFrench ? 'Notes' : 'Notes' }}</p>
                        <p class="text-gray-800">{{ $distribution->notes }}</p>
                    </div>
                @endif

                <!-- Mobile Sales Details -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-blue-600 px-4 py-3">
                        <h3 class="text-white font-semibold">{{ $isFrench ? 'Détail des ventes' : 'Sales details' }}</h3>
                    </div>
                    <div class="p-4">
                        @forelse($sales as $sale)
                            <div class="border-b border-gray-100 pb-3 mb-3 last:border-b-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $sale->produit_nom ?? ($isFrench ? 'Produit inconnu' : 'Unknown product') }}</p>
                                        <p class="text-sm text-gray-600">{{ $sale->quantite }} × {{ number_format($sale->prix, 0, ',', ' ') }} XAF</p>
                                        <p class="text-xs text-gray-500">{{ $sale->type }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-900">{{ number_format($sale->quantite * $sale->prix, 0, ',', ' ') }} XAF</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">{{ $isFrench ? 'Aucune vente trouvée' : 'No sales found' }}</p>
                        @endforelse
                        
                        @if(count($sales) > 0)
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <p class="font-semibold text-gray-900">{{ $isFrench ? 'Total' : 'Total' }}</p>
                                    <p class="font-bold text-blue-900 text-lg">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                <div class="p-6 sm:p-8">
                    <div class="bg-gradient-to-r from-blue-100 to-green-100 p-4 rounded-lg mb-6 transform hover:scale-105 transition-all duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-blue-800 text-sm">{{ $isFrench ? 'Vendeuse' : 'Seller' }}</p>
                                <p class="text-lg font-bold text-blue-900">{{ $distribution->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-blue-800 text-sm">{{ $isFrench ? 'Date' : 'Date' }}</p>
                                <p class="text-lg font-bold text-blue-900">{{ $distribution->date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-blue-800 text-sm">{{ $isFrench ? 'Statut' : 'Status' }}</p>
                                @if($distribution->status === 'en_cours')
                                    <p class="text-lg font-bold text-yellow-600">{{ $isFrench ? 'En cours' : 'In progress' }}</p>
                                @else
                                    <p class="text-lg font-bold text-green-600">{{ $isFrench ? 'Clôturé' : 'Closed' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Main Information -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-6 transform hover:scale-105 transition-all duration-300">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Informations principales' : 'Main information' }}</h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Montant obtenu pour les ventes' : 'Amount received for sales' }}</p>
                                        <p class="text-xl font-bold text-blue-800">{{ number_format($distribution->bill_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Monnaie initiale' : 'Initial change' }}</p>
                                        <p class="text-xl font-bold text-blue-800">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-gray-500 text-sm">{{ $isFrench ? 'Montant des ventes' : 'Sales amount' }}
                                        <form action="{{ route('cash.distributions.update-sales', $distribution) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 ml-2 transform hover:scale-125 transition-all duration-200" title="{{ $isFrench ? 'Actualiser le montant des ventes' : 'Refresh sales amount' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                                            </button>
                                        </form>
                                    </p>
                                    <p class="text-xl font-bold text-green-800">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} FCFA</p>
                                </div>

                                @if($distribution->status === 'cloture')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Monnaie finale' : 'Final change' }}</p>
                                        <p class="text-xl font-bold text-blue-800">{{ number_format($distribution->final_coin_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Montant versé' : 'Deposited amount' }}</p>
                                        <p class="text-xl font-bold text-green-800">{{ number_format($distribution->deposited_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-gray-500 text-sm">{{ $isFrench ? 'Montant manquant' : 'Missing amount' }}
                                        <form action="{{ route('cash.distributions.update-missing', $distribution) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 ml-2 transform hover:scale-125 transition-all duration-200" title="{{ $isFrench ? 'Recalculer le manquant' : 'Recalculate missing' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                                            </button>
                                        </form>
                                    </p>
                                    @if($distribution->missing_amount > 0)
                                        <p class="text-xl font-bold text-red-600">{{ number_format($distribution->missing_amount, 0, ',', ' ') }} FCFA</p>
                                    @else
                                        <p class="text-xl font-bold text-green-600">0 FCFA</p>
                                    @endif
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-gray-500 text-sm">{{ $isFrench ? 'Clôturé par' : 'Closed by' }}</p>
                                    <p class="text-gray-800">{{ $distribution->closedByUser->name ?? 'N/A' }} {{ $isFrench ? 'le' : 'on' }} {{ $distribution->closed_at ? $distribution->closed_at->format('d/m/Y à H:i') : 'N/A' }}</p>
                                </div>
                                @endif

                                @if($distribution->notes)
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-gray-500 text-sm">{{ $isFrench ? 'Notes' : 'Notes' }}</p>
                                    <p class="text-gray-800">{{ $distribution->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Missing Calculation -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow p-6 transform hover:scale-105 transition-all duration-300">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Calcul du manquant' : 'Missing calculation' }}</h3>

                            @if($distribution->status === 'cloture')
                                <div class="space-y-6">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-gray-500 text-sm font-medium mb-2">{{ $isFrench ? 'Formule appliquée' : 'Applied formula' }}</p>
                                        <p class="text-gray-900">
                                            {{ $isFrench ? '(Ventes Produits + Montant obtenu pour les ventes + (Monnaie initiale - Monnaie finale(Ration y compris))) - Versement = Manquant' : '(Product Sales + Amount received for sales + (Initial change - Final change)) - Deposit = Missing' }}
                                        </p>
                                    </div>

                                    <div class="bg-blue-50 p-4 rounded-lg space-y-2">
                                        <div class="flex justify-between items-center">
                                            <p class="text-blue-800">{{ $isFrench ? 'Montant des ventes' : 'Sales amount' }}</p>
                                            <p class="text-blue-900 font-medium">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-blue-800">{{ $isFrench ? 'Montant obtenu pour les ventes' : 'Amount received for sales' }}</p>
                                            <p class="text-blue-900 font-medium">{{ number_format($distribution->bill_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-blue-800">{{ $isFrench ? 'Monnaie initiale' : 'Initial change' }}</p>
                                            <p class="text-blue-900 font-medium">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-blue-800">{{ $isFrench ? 'Monnaie finale' : 'Final change' }}</p>
                                            <p class="text-blue-900 font-medium">{{ number_format($distribution->final_coin_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-blue-800">{{ $isFrench ? 'Différence monnaie' : 'Change difference' }}</p>
                                            <p class="text-blue-900 font-medium">{{ number_format($distribution->initial_coin_amount - $distribution->final_coin_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="border-t border-blue-200 pt-2">
                                            <div class="flex justify-between items-center">
                                                <p class="text-blue-800 font-medium">{{ $isFrench ? 'Montant total attendu' : 'Total expected amount' }}</p>
                                                <p class="text-blue-900 font-bold">
                                                    {{ number_format($distribution->sales_amount + $distribution->bill_amount + ($distribution->initial_coin_amount - $distribution->final_coin_amount), 0, ',', ' ') }} FCFA
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center px-4">
                                        <p class="text-gray-800 font-medium">{{ $isFrench ? 'Montant versé' : 'Deposited amount' }}</p>
                                        <p class="text-gray-900 font-bold">{{ number_format($distribution->deposited_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>

                                    <div class="border-t border-gray-200 pt-4 px-4">
                                        <div class="flex justify-between items-center">
                                            <p class="text-lg text-gray-800 font-medium">{{ $isFrench ? 'Montant manquant' : 'Missing amount' }}</p>
                                            @if($distribution->missing_amount > 0)
                                                <p class="text-xl text-red-600 font-bold">{{ number_format($distribution->missing_amount, 0, ',', ' ') }} FCFA</p>
                                            @else
                                                <p class="text-xl text-green-600 font-bold">0 FCFA</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-60 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    <p class="text-gray-500 mb-2">{{ $isFrench ? 'Le calcul du manquant sera disponible après la clôture de la distribution' : 'Missing calculation will be available after distribution closure' }}</p>
                                    <a href="{{ route('cash.distributions.close.form', $distribution) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10H12V2Z"/><path d="M12 2a10 10 0 0 1 10 10"/><path d="M12 12h10"/></svg>
                                        {{ $isFrench ? 'Clôturer maintenant' : 'Close now' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sales Details -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <div class="p-6 pb-0">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Détail des ventes' : 'Sales details' }}</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Prix unitaire' : 'Unit price' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Type' : 'Type' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($sales as $sale)
                                        <tr class="hover:bg-gray-50 transform hover:scale-105 transition-all duration-300">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->produit_nom ?? ($isFrench ? 'Produit inconnu' : 'Unknown product') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->quantite }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($sale->prix, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($sale->quantite * $sale->prix, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sale->type }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $isFrench ? 'Aucune vente trouvée pour cette date.' : 'No sales found for this date.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if(count($sales) > 0)
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            {{ $isFrench ? 'Total' : 'Total' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-800">
                                            {{ number_format($distribution->sales_amount, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}
</style>
@endsection
