@extends('layouts.app')

@section('title', $isFrench ? 'Détails Réception Vendeur' : 'Vendor Reception Details')

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $isFrench ? 'Détails de la Réception' : 'Reception Details' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $isFrench ? 'Informations complètes et calculs' : 'Complete information and calculations' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('receptions.vendeurs.edit', $reception->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Modifier' : 'Edit' }}</span>
                        </a>
                        <a href="{{ route('receptions.vendeurs.index') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Retour' : 'Back' }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Reception Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Vendor Info -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $isFrench ? 'Vendeur' : 'Vendor' }}</h3>
                                <p class="text-blue-600 font-medium">{{ $reception->vendeur->name }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p><strong>{{ $isFrench ? 'Secteur:' : 'Sector:' }}</strong> {{ ucfirst($reception->vendeur->secteur ?? 'N/A') }}</p>
                            <p><strong>{{ $isFrench ? 'Rôle:' : 'Role:' }}</strong> {{ ucfirst($reception->vendeur->role ?? 'N/A') }}</p>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="bg-green-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $isFrench ? 'Produit' : 'Product' }}</h3>
                                <p class="text-green-600 font-medium">{{ $reception->produit->nom }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p><strong>{{ $isFrench ? 'Code:' : 'Code:' }}</strong> {{ $reception->produit_id }}</p>
                            <p><strong>{{ $isFrench ? 'Prix:' : 'Price:' }}</strong> {{ number_format($reception->produit->prix, 0) }} FCFA</p>
                            <p><strong>{{ $isFrench ? 'Catégorie:' : 'Category:' }}</strong> {{ ucfirst($reception->produit->categorie ?? 'N/A') }}</p>
                        </div>
                    </div>

                    <!-- Date Info -->
                    <div class="bg-yellow-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $isFrench ? 'Date' : 'Date' }}</h3>
                                <p class="text-yellow-600 font-medium">{{ $reception->date_reception->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p><strong>{{ $isFrench ? 'Jour de la semaine:' : 'Day of week:' }}</strong> {{ $reception->date_reception->locale($isFrench ? 'fr' : 'en')->dayName }}</p>
                            <p><strong>{{ $isFrench ? 'Enregistré le:' : 'Recorded on:' }}</strong> {{ $reception->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quantities Details -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
                    <div class="bg-gray-50 rounded-t-xl px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ $isFrench ? 'Détail des Quantités' : 'Quantity Details' }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Morning Entry -->
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-yellow-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Entrée Matin' : 'Morning Entry' }}</h4>
                                <p class="text-3xl font-bold text-yellow-700">{{ number_format($reception->quantite_entree_matin, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Quantité reçue le matin' : 'Quantity received in the morning' }}</p>
                            </div>

                            <!-- Day Entry -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Entrée Journée' : 'Day Entry' }}</h4>
                                <p class="text-3xl font-bold text-blue-700">{{ number_format($reception->quantite_entree_journee, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Quantité reçue en journée' : 'Quantity received during the day' }}</p>
                            </div>

                            <!-- Yesterday's Remainder -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-purple-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Reste d\'Hier' : 'Yesterday\'s Remainder' }}</h4>
                                <p class="text-3xl font-bold text-purple-700">{{ number_format($reception->quantite_reste_hier, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Quantité restante du jour précédent' : 'Remaining quantity from previous day' }}</p>
                            </div>

                            <!-- Unsold -->
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-orange-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Invendu' : 'Unsold' }}</h4>
                                <p class="text-3xl font-bold text-orange-700">{{ number_format($reception->quantite_invendue, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Quantité non vendue' : 'Unsold quantity' }}</p>
                            </div>

                            <!-- Spoiled -->
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-red-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Avarie' : 'Spoiled' }}</h4>
                                <p class="text-3xl font-bold text-red-700">{{ number_format($reception->quantite_avarie ?? 0, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Quantité avariée/gâtée' : 'Spoiled/damaged quantity' }}</p>
                            </div>

                            <!-- Total Available -->
                            @php
                                $totalDisponible = $reception->quantite_entree_matin + $reception->quantite_entree_journee + $reception->quantite_reste_hier;
                            @endphp
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-green-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $isFrench ? 'Total Disponible' : 'Total Available' }}</h4>
                                <p class="text-3xl font-bold text-green-700">{{ number_format($totalDisponible, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $isFrench ? 'Entrées + Reste d\'hier' : 'Entries + Yesterday\'s remainder' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calculations and Analysis -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="bg-gray-50 rounded-t-xl px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ $isFrench ? 'Analyses et Calculs' : 'Analysis and Calculations' }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $totalEntree = $reception->quantite_entree_matin + $reception->quantite_entree_journee;
                            $totalVenduEstime = $totalDisponible - ($reception->quantite_avarie ?? 0) - $reception->quantite_invendue;
                            $tauxVente = $totalDisponible > 0 ? ($totalVenduEstime / $totalDisponible) * 100 : 0;
                            $montantVenduEstime = $totalVenduEstime * $reception->produit->prix;
                            $montantPerteAvarie = ($reception->quantite_avarie ?? 0) * $reception->produit->prix;
                            $montantPerteInvendu = $reception->quantite_invendue * $reception->produit->prix;
                            $montantTotalPerte = $montantPerteAvarie + $montantPerteInvendu;
                        @endphp

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Sales Analysis -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-xl p-6 border border-blue-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        {{ $isFrench ? 'Analyse des Ventes' : 'Sales Analysis' }}
                                    </h4>
                                    
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Total entrée journée:' : 'Total day entry:' }}</span>
                                            <span class="font-semibold text-blue-700">{{ number_format($totalEntree, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Total disponible:' : 'Total available:' }}</span>
                                            <span class="font-semibold text-green-700">{{ number_format($totalDisponible, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Vendu estimé:' : 'Estimated sold:' }}</span>
                                            <span class="font-bold text-green-800 text-lg">{{ number_format($totalVenduEstime, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Taux de vente:' : 'Sales rate:' }}</span>
                                            <span class="font-semibold {{ $tauxVente >= 80 ? 'text-green-700' : ($tauxVente >= 60 ? 'text-yellow-700' : 'text-red-700') }}">
                                                {{ number_format($tauxVente, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Performance Indicator -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h5 class="font-semibold text-gray-900 mb-3">{{ $isFrench ? 'Indicateur de Performance' : 'Performance Indicator' }}</h5>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full {{ $tauxVente >= 80 ? 'bg-green-500' : ($tauxVente >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ min($tauxVente, 100) }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        @if($tauxVente >= 80)
                                            {{ $isFrench ? 'Excellente performance' : 'Excellent performance' }}
                                        @elseif($tauxVente >= 60)
                                            {{ $isFrench ? 'Performance acceptable' : 'Acceptable performance' }}
                                        @else
                                            {{ $isFrench ? 'Performance à améliorer' : 'Performance needs improvement' }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Financial Analysis -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl p-6 border border-green-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        {{ $isFrench ? 'Analyse Financière' : 'Financial Analysis' }}
                                    </h4>
                                    
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }}</span>
                                            <span class="font-semibold text-gray-800">{{ number_format($reception->produit->prix, 0) }} FCFA</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Chiffre d\'affaires estimé:' : 'Estimated revenue:' }}</span>
                                            <span class="font-bold text-green-800 text-lg">{{ number_format($montantVenduEstime, 0) }} FCFA</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Perte par avarie:' : 'Loss from spoilage:' }}</span>
                                            <span class="font-semibold text-red-700">{{ number_format($montantPerteAvarie, 0) }} FCFA</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">{{ $isFrench ? 'Perte par invendu:' : 'Loss from unsold:' }}</span>
                                            <span class="font-semibold text-orange-700">{{ number_format($montantPerteInvendu, 0) }} FCFA</span>
                                        </div>
                                        <hr class="border-gray-300">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-semibold">{{ $isFrench ? 'Total des pertes:' : 'Total losses:' }}</span>
                                            <span class="font-bold text-red-800 text-lg">{{ number_format($montantTotalPerte, 0) }} FCFA</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h5 class="font-semibold text-gray-900 mb-3">{{ $isFrench ? 'Résumé' : 'Summary' }}</h5>
                                    <div class="text-sm text-gray-600 space-y-2">
                                        <p><strong>{{ $isFrench ? 'Formule:' : 'Formula:' }}</strong></p>
                                        <p class="bg-white p-3 rounded border font-mono text-xs">
                                            {{ $isFrench ? 'Vendu Estimé = (Entrée Total + Reste Hier) - Avarie - Invendu' : 'Estimated Sold = (Total Entry + Yesterday Remainder) - Spoiled - Unsold' }}
                                        </p>
                                        <p class="font-mono text-xs">
                                            {{ number_format($totalVenduEstime, 2) }} = ({{ number_format($totalEntree, 2) }} + {{ number_format($reception->quantite_reste_hier, 2) }}) - {{ number_format($reception->quantite_avarie ?? 0, 2) }} - {{ number_format($reception->quantite_invendue, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection