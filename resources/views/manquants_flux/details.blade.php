@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                                {{ $isFrench ? 'Détails du Flux' : 'Flow Details' }} - {{ $flux->produit->nom }} - {{ $flux->produit->prix }} FCFA
                            </h1>
                            <p class="text-gray-600">{{ $flux->date_flux->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Résumé du flux -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Résumé du Flux' : 'Flow Summary' }}
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Production -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">{{ $isFrench ? 'Production' : 'Production' }}</h3>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($flux->total_production, 2) }}</p>
                        <p class="text-blue-100 text-sm mt-1">{{ $isFrench ? 'Unités produites' : 'Units produced' }}</p>
                    </div>

                    <!-- Pointage -->
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">{{ $isFrench ? 'Pointage' : 'Counting' }}</h3>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($flux->total_pointage, 2) }}</p>
                        <p class="text-purple-100 text-sm mt-1">{{ $isFrench ? 'Unités pointées' : 'Units counted' }}</p>
                    </div>

                    <!-- Réception Vendeur -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">{{ $isFrench ? 'Réception Vendeur' : 'Vendor Reception' }}</h3>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4H7a1 1 0 00-1 1v6a3 3 0 003 3h6a3 3 0 003-3v-6a1 1 0 00-1-1h-1zM10 7a2 2 0 114 0v4h-4V7z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ number_format($flux->total_reception_vendeur, 2) }}</p>
                        <p class="text-green-100 text-sm mt-1">{{ $isFrench ? 'Unités reçues' : 'Units received' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails des activités -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Productions -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-100">
                <div class="px-6 py-4 bg-blue-50 rounded-t-xl border-b border-blue-100">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        </svg>
                        {{ $isFrench ? 'Productions' : 'Productions' }}
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @if(!empty($detailsProducteurs))
                        <div class="space-y-3">
                            @foreach($detailsProducteurs as $detail)
                            <div class="bg-gray-50 hover:bg-blue-50 rounded-lg p-4 border border-gray-200 hover:border-blue-200 transition-colors duration-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $detail['producteur_nom'] ?? ($isFrench ? 'Inconnu' : 'Unknown') }}</h4>
                                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Lot' : 'Batch' }}: {{ $detail['id_lot'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-blue-600">{{ number_format((float)($detail['quantite_produit'] ?? $detail['quantite'] ?? 0), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500">{{ $isFrench ? 'Aucune production enregistrée' : 'No production recorded' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pointages -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-100">
                <div class="px-6 py-4 bg-purple-50 rounded-t-xl border-b border-purple-100">
                    <h3 class="text-lg font-bold text-purple-900 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $isFrench ? 'Pointages' : 'Countings' }}
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @if(!empty($detailsPointeurs))
                        <div class="space-y-3">
                            @foreach($detailsPointeurs as $detail)
                            <div class="bg-gray-50 hover:bg-purple-50 rounded-lg p-4 border border-gray-200 hover:border-purple-200 transition-colors duration-200">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-semibold text-gray-900">{{ $detail['pointeur_nom'] ?? ($isFrench ? 'Inconnu' : 'Unknown') }}</h4>
                                    <span class="text-lg font-bold text-purple-600">{{ number_format((float)($detail['quantite_recue'] ?? $detail['quantite'] ?? 0), 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500">{{ $isFrench ? 'Aucun pointage enregistré' : 'No counting recorded' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Réceptions Vendeurs -->
            <div class="bg-white rounded-xl shadow-lg border border-green-100">
                <div class="px-6 py-4 bg-green-50 rounded-t-xl border-b border-green-100">
                    <h3 class="text-lg font-bold text-green-900 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                        </svg>
                        {{ $isFrench ? 'Réceptions Vendeurs' : 'Vendor Receptions' }}
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @if(!empty($detailsVendeurs))
                        <div class="space-y-3">
                            @foreach($detailsVendeurs as $detail)
                            <div class="bg-gray-50 hover:bg-green-50 rounded-lg p-4 border border-gray-200 hover:border-green-200 transition-colors duration-200">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $detail['vendeur_nom'] ?? ($isFrench ? 'Inconnu' : 'Unknown') }}</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $isFrench ? 'Matin' : 'Morning' }}:</span>
                                        <span class="font-medium">{{ number_format(floatval($detail['quantite_entree_matin'] ?? $detail['entree_matin'] ?? 0), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $isFrench ? 'Journée' : 'Day' }}:</span>
                                        <span class="font-medium">{{ number_format(floatval($detail['quantite_entree_journee'] ?? $detail['entree_journee'] ?? 0), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $isFrench ? 'Invendu' : 'Unsold' }}:</span>
                                        <span class="font-medium">{{ number_format(floatval($detail['quantite_invendue'] ?? $detail['invendue'] ?? 0), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $isFrench ? 'Reste hier' : 'Yesterday rest' }}:</span>
                                        <span class="font-medium">{{ number_format(floatval($detail['quantite_reste_hier'] ?? $detail['reste_hier'] ?? 0), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4H7a1 1 0 00-1 1v6a3 3 0 003 3h6a3 3 0 003-3v-6a1 1 0 00-1-1h-1zM10 7a2 2 0 114 0v4h-4V7z"/>
                            </svg>
                            <p class="text-gray-500">{{ $isFrench ? 'Aucune réception enregistrée' : 'No reception recorded' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($manquant)
        <!-- Manquants et Incohérences -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Manquants -->
            <div class="bg-white rounded-xl shadow-lg border border-red-100">
                <div class="px-6 py-4 bg-red-50 rounded-t-xl border-b border-red-100">
                    <h3 class="text-lg font-bold text-red-900 flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ $isFrench ? 'Manquants (Quantité)' : 'Missing (Quantity)' }}
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $hasManquant = $manquant->manquant_producteur_pointeur > 0 || $manquant->manquant_pointeur_vendeur > 0 || $manquant->manquant_vendeur_invendu > 0;
                    @endphp
                    
                    @if($hasManquant)
                        <div class="space-y-3">
                            @if($manquant->manquant_producteur_pointeur > 0)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-red-900">{{ $isFrench ? 'Production → Pointage' : 'Production → Counting' }}:</span>
                                    <span class="text-lg font-bold text-red-600">{{ number_format($manquant->manquant_producteur_pointeur, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($manquant->manquant_pointeur_vendeur > 0)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-red-900">{{ $isFrench ? 'Pointage → Vendeur' : 'Counting → Vendor' }}:</span>
                                    <span class="text-lg font-bold text-red-600">{{ number_format($manquant->manquant_pointeur_vendeur, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($manquant->manquant_vendeur_invendu > 0)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-red-900">{{ $isFrench ? 'Invendus non retrouvés' : 'Unsold not found' }}:</span>
                                    <span class="text-lg font-bold text-red-600">{{ number_format($manquant->manquant_vendeur_invendu, 2) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-green-600 font-medium">{{ $isFrench ? 'Aucun manquant détecté' : 'No missing detected' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Incohérences -->
            <div class="bg-white rounded-xl shadow-lg border border-orange-100">
                <div class="px-6 py-4 bg-orange-50 rounded-t-xl border-b border-orange-100">
                    <h3 class="text-lg font-bold text-orange-900 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ $isFrench ? 'Incohérences (Surplus)' : 'Inconsistencies (Surplus)' }}
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $hasIncoherence = (isset($manquant->incoherence_producteur_pointeur) && $manquant->incoherence_producteur_pointeur > 0) || (isset($manquant->incoherence_pointeur_vendeur) && $manquant->incoherence_pointeur_vendeur > 0);
                    @endphp
                    
                    @if($hasIncoherence)
                        <div class="space-y-3">
                            @if(isset($manquant->incoherence_producteur_pointeur) && $manquant->incoherence_producteur_pointeur > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-orange-900">{{ $isFrench ? 'Surplus Pointage' : 'Counting Surplus' }}:</span>
                                        <p class="text-sm text-orange-700 mt-1">{{ $isFrench ? '(Pointé plus que produit)' : '(Counted more than produced)' }}</p>
                                    </div>
                                    <span class="text-lg font-bold text-orange-600">{{ number_format($manquant->incoherence_producteur_pointeur, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($manquant->incoherence_pointeur_vendeur) && $manquant->incoherence_pointeur_vendeur > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-orange-900">{{ $isFrench ? 'Surplus Réception' : 'Reception Surplus' }}:</span>
                                        <p class="text-sm text-orange-700 mt-1">{{ $isFrench ? '(Reçu plus que pointé)' : '(Received more than counted)' }}</p>
                                    </div>
                                    <span class="text-lg font-bold text-orange-600">{{ number_format($manquant->incoherence_pointeur_vendeur, 2) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-green-600 font-medium">{{ $isFrench ? 'Aucune incohérence détectée' : 'No inconsistency detected' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Répartition des montants -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-100">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-green-50 rounded-t-xl border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C13.10 2 14 2.89 14 4C14 5.11 13.10 6 12 6C10.90 6 10 5.11 10 4C10 2.89 10.90 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V19C3 20.11 3.89 21 5 21H11V19H5V5H13V9H21Z"/>
                    </svg>
                    {{ $isFrench ? 'Répartition des Manquants (FCFA)' : 'Missing Distribution (FCFA)' }}
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Producteurs -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold">{{ $isFrench ? 'Producteurs' : 'Producers' }}</h4>
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl lg:text-3xl font-bold">{{ number_format($manquant->montant_producteur, 0) }} FCFA</p>
                        <p class="text-red-100 text-sm mt-2">{{ $isFrench ? 'Le producteur a toujours raison' : 'The producer is always right' }}</p>
                    </div>

                    <!-- Pointeurs -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold">{{ $isFrench ? 'Pointeurs' : 'Counters' }}</h4>
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl lg:text-3xl font-bold">{{ number_format($manquant->montant_pointeur, 0) }} FCFA</p>
                        <p class="text-orange-100 text-sm mt-2">{{ $isFrench ? '100% prod-point' : '100% prod-count' }}</p>
                    </div>

                    <!-- Vendeurs -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold">{{ $isFrench ? 'Vendeurs' : 'Vendors' }}</h4>
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl lg:text-3xl font-bold">{{ number_format($manquant->montant_vendeur, 0) }} FCFA</p>
                        <p class="text-green-100 text-sm mt-2">{{ $isFrench ? 'Invendus non retrouvés + 100% point-vendeur' : 'Unsold not found + 100% count-vendor' }}</p>
                    </div>
                </div>

                
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8">
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                {{ $isFrench ? 'Imprimer' : 'Print' }}
            </button>
           
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Fonction d'export PDF (à implémenter selon vos besoins)
    alert('{{ $isFrench ? "Fonctionnalité d'export PDF à implémenter" : "PDF export functionality to be implemented" }}');
}

// Animation au scroll
window.addEventListener('scroll', () => {
    const cards = document.querySelectorAll('.transform');
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
        
        if (isVisible) {
            card.classList.add('animate-pulse');
            setTimeout(() => card.classList.remove('animate-pulse'), 1000);
        }
    });
});

// Responsive table pour mobile
document.addEventListener('DOMContentLoaded', function() {
    const isMobile = window.innerWidth < 768;
    if (isMobile) {
        // Ajustements spécifiques pour mobile
        console.log('Mobile view activated');
    }
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-gradient-to-br {
        background: white !important;
    }
    
    .shadow-lg, .shadow-md, .shadow-xl {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}

/* Animations personnalisées */
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

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Scrollbar personnalisée */
.max-h-96::-webkit-scrollbar {
    width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Hover effects */
.hover-scale:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}
</style>
@endsection
