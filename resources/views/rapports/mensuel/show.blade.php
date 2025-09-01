@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Rapport Mensuel' : 'Monthly Report' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $startDate->locale($isFrench ? 'fr' : 'en')->isoFormat('MMMM YYYY') }}
            </p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white">
                            {{ $isFrench ? 'Rapport Mensuel' : 'Monthly Report' }} - {{ $startDate->locale($isFrench ? 'fr' : 'en')->isoFormat('MMMM YYYY') }}
                        </h2>
                        <p class="text-blue-100 mt-1">
                            {{ $isFrench ? 'Vue d\'ensemble des performances de l\'entreprise' : 'Overview of company performance' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                      
                
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Content -->
    <div class="md:hidden px-4 pb-20">
        <!-- Mobile Action Buttons -->
        <div class="flex space-x-3 mb-6">
            <a href="{{ route('rapports.mensuel.export', ['month_year' => $monthYear]) }}" class="flex-1 bg-white rounded-2xl shadow-lg p-4 flex items-center justify-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-blue-600 font-medium text-sm">{{ $isFrench ? 'PDF' : 'PDF' }}</span>
            </a>
            
        </div>

        <!-- Mobile KPI Cards -->
        <div class="space-y-4 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300 animate-slide-in-right">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}</h3>
                        <p class="text-3xl font-bold text-green-600">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="{{ $evolutionChiffreAffaires >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center text-sm font-medium">
                        @if($evolutionChiffreAffaires >= 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($evolutionChiffreAffaires), 2) }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $isFrench ? 'Dépenses totales' : 'Total Expenses' }}</h3>
                        <p class="text-3xl font-bold text-red-600">{{ number_format($depensesTotales, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="{{ $evolutionDepenses <= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center text-sm font-medium">
                        @if($evolutionDepenses <= 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @endif
                        {{ number_format(abs($evolutionDepenses), 2) }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $isFrench ? 'Bénéfice' : 'Profit' }}</h3>
                        <p class="text-3xl font-bold {{ $benefice >= 0 ? 'text-blue-600' : 'text-red-600' }}">{{ number_format($benefice, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2V7a2 2 0 012-2h2a2 2 0 002 2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v2m0 0V7a2 2 0 012-2h2a2 2 0 012 2v2m0 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2m0 0v-6a2 2 0 012-2h2a2 2 0 012 2v6"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="{{ $evolutionBenefice >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center text-sm font-medium">
                        @if($evolutionBenefice >= 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @endif
                        {{ number_format(abs($evolutionBenefice), 2) }}%
                    </span>
                    <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                </div>
            </div>
        </div>

        <!-- Mobile AI Analysis -->
        @if(isset($analyseIA['summary']) && !empty($analyseIA['summary']))
        <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-indigo-800 to-indigo-600 px-6 py-4">
                <h2 class="text-white text-lg font-bold flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    {{ $isFrench ? 'Analyse IA' : 'AI Analysis' }}
                </h2>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4 leading-relaxed">{{ $analyseIA['summary'] }}</p>
                
                @foreach($analyseIA['secteurs'] as $secteur => $analyse)
                    @if(!empty($analyse))
                        <div class="mb-4 bg-gray-50 p-4 rounded-xl">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $isFrench ? 'Secteur' : 'Sector' }} : {{ ucfirst($secteur) }}</h3>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $analyse }}</p>
                        </div>
                    @endif
                @endforeach
                
                @if(!empty($analyseIA['objectifs']))
                    <div class="mb-4 bg-blue-50 p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">{{ $isFrench ? 'Analyse des objectifs' : 'Objectives Analysis' }}</h3>
                        <p class="text-blue-800 text-sm leading-relaxed">{{ $analyseIA['objectifs'] }}</p>
                    </div>
                @endif
                
                @if(!empty($analyseIA['recommendations']))
                    <div class="bg-green-50 p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-green-900 mb-3">{{ $isFrench ? 'Recommandations' : 'Recommendations' }}</h3>
                        <div class="space-y-2">
                            @foreach($analyseIA['recommendations'] as $recommendation)
                                <div class="flex items-start">
                                    <svg class="h-4 w-4 text-green-600 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-green-800 text-sm">{{ $recommendation }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Mobile Sector Analysis Accordion -->
        <div class="space-y-4 mb-8">
            <!-- Revenue by Sector -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Gains par secteur' : 'Revenue by Sector' }}</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($gainsParSecteur as $secteur => $data)
                            @if($data['montant'] > 0)
                                <div class="border rounded-xl overflow-hidden">
                                    <div class="p-4 bg-blue-50">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-bold text-blue-900">{{ ucfirst($secteur) }}</span>
                                            <span class="text-blue-900 font-bold">{{ number_format($data['montant'], 0, ',', ' ') }} FCFA</span>
                                        </div>
                                        <div class="w-full bg-blue-200 rounded-full h-3">
                                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000" style="width: {{ $data['pourcentage'] }}%"></div>
                                        </div>
                                        <div class="text-blue-700 text-sm mt-1 font-medium">{{ $data['pourcentage'] }}%</div>
                                    </div>
                                    @if(!empty($data['details']))
                                        <details class="group">
                                            <summary class="p-4 cursor-pointer bg-gray-50 border-t flex justify-between items-center">
                                                <span class="font-medium text-gray-700">{{ $isFrench ? 'Voir détails' : 'View Details' }}</span>
                                                <svg class="h-5 w-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </summary>
                                            <div class="p-4 bg-white border-t">
                                                @foreach($data['details'] as $detail)
                                                    <div class="flex justify-between py-2 border-b last:border-0">
                                                        <span class="text-gray-700 text-sm">{{ $detail->name }}</span>
                                                        <span class="font-medium text-sm">{{ number_format($detail->total, 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Expenses by Sector -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.5s">
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Dépenses par secteur' : 'Expenses by Sector' }}</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($depensesParSecteur as $secteur => $data)
                            @if($data['montant'] > 0)
                                <div class="border rounded-xl overflow-hidden">
                                    <div class="p-4 bg-red-50">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-bold text-red-900">{{ ucfirst($secteur) }}</span>
                                            <span class="text-red-900 font-bold">{{ number_format($data['montant'], 0, ',', ' ') }} FCFA</span>
                                        </div>
                                        <div class="w-full bg-red-200 rounded-full h-3">
                                            <div class="bg-red-600 h-3 rounded-full transition-all duration-1000" style="width: {{ $data['pourcentage'] }}%"></div>
                                        </div>
                                        <div class="text-red-700 text-sm mt-1 font-medium">{{ $data['pourcentage'] }}%</div>
                                    </div>
                                    @if(!empty($data['details']))
                                        <details class="group">
                                            <summary class="p-4 cursor-pointer bg-gray-50 border-t flex justify-between items-center">
                                                <span class="font-medium text-gray-700">{{ $isFrench ? 'Voir détails' : 'View Details' }}</span>
                                                <svg class="h-5 w-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </summary>
                                            <div class="p-4 bg-white border-t">
                                                @foreach($data['details'] as $detail)
                                                    <div class="flex justify-between py-2 border-b last:border-0">
                                                        <span class="text-gray-700 text-sm">{{ $detail->name }}</span>
                                                        <span class="font-medium text-sm">{{ number_format($detail->total, 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Objectives -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.6s">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Suivi des objectifs' : 'Objectives Tracking' }}</h2>
            </div>
            <div class="p-6">
                @if(count($objectifs) > 0)
                    <div class="space-y-4">
                        @foreach($objectifs as $objectif)
                            <div class="border rounded-xl p-4 {{ $objectif['atteint'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-bold text-gray-900">{{ $objectif['titre'] }}</h3>
                                    <span class="px-3 py-1 text-xs rounded-full {{ $objectif['atteint'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $objectif['atteint'] ? ($isFrench ? 'Atteint' : 'Achieved') : ($isFrench ? 'En cours' : 'In Progress') }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="capitalize">{{ $objectif['secteur'] }}</span> · 
                                    <span>{{ $objectif['type'] === 'revenue' ? ($isFrench ? 'Chiffre d\'affaires' : 'Revenue') : ($isFrench ? 'Bénéfice' : 'Profit') }}</span>
                                </div>
                                <div class="mb-3">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                        <span class="font-bold">{{ number_format($objectif['progression'], 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000" style="width: {{ min(100, $objectif['progression']) }}%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="font-bold">{{ number_format($objectif['montant_actuel'], 0, ',', ' ') }} FCFA</span>
                                    <span class="text-gray-600">{{ $isFrench ? 'sur' : 'of' }} {{ number_format($objectif['montant_cible'], 0, ',', ' ') }} FCFA</span>
                                </div>
                                
                                @if(count($objectif['sous_objectifs']) > 0)
                                    <details class="mt-4 group">
                                        <summary class="flex items-center text-sm text-blue-600 cursor-pointer font-medium">
                                            <span>{{ $isFrench ? 'Voir sous-objectifs' : 'View sub-objectives' }} ({{ count($objectif['sous_objectifs']) }})</span>
                                            <svg class="h-4 w-4 ml-1 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </summary>
                                        <div class="mt-3 space-y-3 pl-2 border-l-2 border-blue-200">
                                            @foreach($objectif['sous_objectifs'] as $sousObjectif)
                                                <div class="bg-white p-3 rounded-lg border">
                                                    <div class="flex justify-between mb-2">
                                                        <span class="font-medium text-sm">{{ $sousObjectif['titre'] }}</span>
                                                        <span class="{{ $sousObjectif['progression'] >= 100 ? 'text-green-600' : 'text-gray-500' }} text-sm font-bold">
                                                            {{ number_format($sousObjectif['progression'], 1) }}%
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min(100, $sousObjectif['progression']) }}%"></div>
                                                    </div>
                                                    <div class="flex justify-between text-xs text-gray-600 mt-2">
                                                        <span>{{ number_format($sousObjectif['montant_actuel'], 0, ',', ' ') }} FCFA</span>
                                                        <span>{{ $isFrench ? 'sur' : 'of' }} {{ number_format($sousObjectif['montant_cible'], 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500">{{ $isFrench ? 'Aucun objectif défini pour cette période.' : 'No objectives defined for this period.' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Additional Sections -->
        @if(!empty($config->social_climat) || !empty($config->major_problems) || !empty($config->recommendations))
        <div class="space-y-4">
            @if(!empty($config->social_climat))
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.7s">
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                        <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Climat social' : 'Social Climate' }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($config->social_climat as $item)
                            <div class="bg-teal-50 p-4 rounded-xl">
                                <h3 class="font-bold text-teal-900 mb-2">{{ $item['title'] }}</h3>
                                <p class="text-teal-800 text-sm leading-relaxed">{{ $item['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(!empty($config->major_problems))
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.8s">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Problèmes majeurs' : 'Major Problems' }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($config->major_problems as $item)
                            <div class="bg-orange-50 p-4 rounded-xl border-l-4 border-orange-500">
                                <h3 class="font-bold text-orange-900 mb-2">{{ $item['title'] }}</h3>
                                <p class="text-orange-800 text-sm leading-relaxed">{{ $item['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(!empty($config->recommendations))
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.9s">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                        <h2 class="text-white text-lg font-bold">{{ $isFrench ? 'Recommandations' : 'Recommendations' }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($config->recommendations as $item)
                            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
                                <div class="flex items-start">
                                    <div class="bg-emerald-500 p-2 rounded-full mr-3 flex-shrink-0">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-emerald-900 font-bold text-sm mb-1">{{ $item['source'] }}</h3>
                                        <p class="text-emerald-800 text-sm leading-relaxed">{{ $item['content'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Desktop Content -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <!-- Desktop KPI Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</p>
                    <div class="flex items-center mt-2">
                        <span class="{{ $evolutionChiffreAffaires >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                            @if($evolutionChiffreAffaires >= 0)
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            @endif
                            {{ number_format(abs($evolutionChiffreAffaires), 2) }}%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500 transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $isFrench ? 'Dépenses totales' : 'Total Expenses' }}</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($depensesTotales, 0, ',', ' ') }} FCFA</p>
                    <div class="flex items-center mt-2">
                        <span class="{{ $evolutionDepenses <= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                            @if($evolutionDepenses <= 0)
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            @endif
                            {{ number_format(abs($evolutionDepenses), 2) }}%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $isFrench ? 'Bénéfice' : 'Profit' }}</h3>
                    <p class="text-3xl font-bold {{ $benefice >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($benefice, 0, ',', ' ') }} FCFA
                    </p>
                    <div class="flex items-center mt-2">
                        <span class="{{ $evolutionBenefice >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                            @if($evolutionBenefice >= 0)
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            @endif
                            {{ number_format(abs($evolutionBenefice), 2) }}%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">{{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}</span>
                    </div>
                </div>
            </div>

          @if(isset($analyseIA['summary']) && !empty($analyseIA['summary']))
<div class="mb-4">
    <button onclick="printAIAnalysis()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        {{ $isFrench ? 'Imprimer l\'analyse IA' : 'Print AI Analysis' }}
    </button>
</div>

<div class="bg-white rounded-lg shadow-lg mb-8 transform hover:scale-[1.02] transition-all duration-300">
    <div class="bg-gradient-to-r from-indigo-800 to-indigo-600 rounded-t-lg px-6 py-4">
        <h2 class="text-white text-xl font-semibold flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            {{ $isFrench ? 'Analyse Intelligente par IA' : 'AI Smart Analysis' }}
        </h2>
    </div>
    
    <div class="p-6 space-y-8">
        <!-- Section 1: Diagnostic Global -->
        @if(!empty($analyseIA['summary']))
        <div class="border-l-4 border-indigo-500 pl-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-indigo-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">
                    <span class="text-indigo-600">1.</span> {{ $isFrench ? 'DIAGNOSTIC GLOBAL' : 'GLOBAL DIAGNOSTIC' }}
                </h3>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 leading-relaxed">{{ $analyseIA['summary'] }}</p>
            </div>
        </div>
        @endif

        <!-- Section 2: Analyse Sectorielle -->
        @if(!empty($analyseIA['secteurs']) && (isset($analyseIA['secteurs']['alimentation']) || isset($analyseIA['secteurs']['production']) || isset($analyseIA['secteurs']['glaces'])))
        <div class="border-l-4 border-green-500 pl-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">
                    <span class="text-green-600">2.</span> {{ $isFrench ? 'ANALYSE SECTORIELLE' : 'SECTORIAL ANALYSIS' }}
                </h3>
            </div>
            
            <div class="grid gap-6">
                <!-- Secteur Alimentation -->
                @if(!empty($analyseIA['secteurs']['alimentation']))
                <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-blue-500 w-3 h-3 rounded-full"></div>
                        <h4 class="text-lg font-semibold text-blue-900">
                            {{ $isFrench ? 'Secteur Alimentation (General Store)' : 'General Store (Alimentation)' }}
                        </h4>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $analyseIA['secteurs']['alimentation'] }}</p>
                </div>
                @endif

                <!-- Secteur Production -->
                @if(!empty($analyseIA['secteurs']['production']))
                <div class="bg-orange-50 rounded-lg p-5 border border-orange-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-orange-500 w-3 h-3 rounded-full"></div>
                        <h4 class="text-lg font-semibold text-orange-900">
                            {{ $isFrench ? 'Secteur Production (Boulangerie-Pâtisserie)' : 'Production (Boulangerie-Pâtisserie)' }}
                        </h4>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $analyseIA['secteurs']['production'] }}</p>
                </div>
                @endif

                <!-- Secteur Glaces -->
                @if(!empty($analyseIA['secteurs']['glaces']))
                <div class="bg-cyan-50 rounded-lg p-5 border border-cyan-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-cyan-500 w-3 h-3 rounded-full"></div>
                        <h4 class="text-lg font-semibold text-cyan-900">
                            {{ $isFrench ? 'Secteur Glaces' : 'Ice Cream (Glaces)' }}
                        </h4>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $analyseIA['secteurs']['glaces'] }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Section 3: Bilan des Objectifs -->
        @if(!empty($analyseIA['objectifs']))
        <div class="border-l-4 border-yellow-500 pl-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-yellow-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">
                    <span class="text-yellow-600">3.</span> {{ $isFrench ? 'BILAN DES OBJECTIFS' : 'OBJECTIVES BALANCE' }}
                </h3>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                <p class="text-gray-700 leading-relaxed">{{ $analyseIA['objectifs'] }}</p>
            </div>
        </div>
        @endif

        <!-- Section 4: Recommandations Stratégiques -->
        @if(!empty($analyseIA['recommendations']))
        <div class="border-l-4 border-red-500 pl-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">
                    <span class="text-red-600">4.</span> {{ $isFrench ? 'RECOMMANDATIONS STRATÉGIQUES' : 'STRATEGIC RECOMMENDATIONS' }}
                </h3>
            </div>
            
            <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                <div class="space-y-4">
                    @foreach($analyseIA['recommendations'] as $index => $recommendation)
                    <div class="flex gap-4 items-start">
                        <div class="bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-1">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 leading-relaxed">{{ $recommendation }}</p>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="border-red-200">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endif
            <!-- Desktop Sector Analysis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Desktop Revenue by Sector -->
                <div class="bg-white rounded-lg shadow transform hover:scale-105 transition-all duration-300">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Répartition des gains par secteur' : 'Revenue Distribution by Sector' }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($gainsParSecteur as $secteur => $data)
                                @if($data['montant'] > 0)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-700">{{ ucfirst($secteur) }}</span>
                                            <span class="text-gray-900 font-medium">{{ number_format($data['montant'], 0, ',', ' ') }} FCFA ({{ $data['pourcentage'] }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['pourcentage'] }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Details for desktop -->
                        <div class="mt-6">
                            <div class="border rounded-lg overflow-hidden">
                                @foreach($gainsParSecteur as $secteur => $data)
                                    @if($data['montant'] > 0 && !empty($data['details']))
                                        <details class="group">
                                            <summary class="flex justify-between items-center p-4 cursor-pointer border-b last:border-0 hover:bg-gray-50">
                                                <span class="font-medium">{{ $isFrench ? 'Détails' : 'Details' }} {{ ucfirst($secteur) }}</span>
                                                <span class="transform group-open:rotate-180 transition-transform">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>
                                            </summary>
                                            <div class="p-4 border-t bg-gray-50">
                                                <div class="divide-y divide-gray-200">
                                                    @foreach($data['details'] as $detail)
                                                        <div class="flex justify-between py-2">
                                                            <span class="text-gray-700">{{ $detail->name }}</span>
                                                            <span class="font-medium">{{ number_format($detail->total, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </details>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop Expenses by Sector -->
                <div class="bg-white rounded-lg shadow transform hover:scale-105 transition-all duration-300">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Répartition des dépenses par secteur' : 'Expense Distribution by Sector' }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($depensesParSecteur as $secteur => $data)
                                @if($data['montant'] > 0)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-700">{{ ucfirst($secteur) }}</span>
                                            <span class="text-gray-900 font-medium">{{ number_format($data['montant'], 0, ',', ' ') }} FCFA ({{ $data['pourcentage'] }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $data['pourcentage'] }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Details for desktop -->
                        <div class="mt-6">
                            <div class="border rounded-lg overflow-hidden">
                                @foreach($depensesParSecteur as $secteur => $data)
                                    @if($data['montant'] > 0 && !empty($data['details']))
                                        <details class="group">
                                            <summary class="flex justify-between items-center p-4 cursor-pointer border-b last:border-0 hover:bg-gray-50">
                                                <span class="font-medium">{{ $isFrench ? 'Détails' : 'Details' }} {{ ucfirst($secteur) }}</span>
                                                <span class="transform group-open:rotate-180 transition-transform">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>
                                            </summary>
                                            <div class="p-4 border-t bg-gray-50">
                                                <div class="divide-y divide-gray-200">
                                                    @foreach($data['details'] as $detail)
                                                        <div class="flex justify-between py-2">
                                                            <span class="text-gray-700">{{ $detail->name }}</span>
                                                            <span class="font-medium">{{ number_format($detail->total, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </details>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Objectives Tracking -->
            <div class="bg-white rounded-lg shadow mb-8 transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Suivi des objectifs' : 'Objectives Tracking' }}</h2>
                </div>
                <div class="p-6">
                    @if(count($objectifs) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($objectifs as $objectif)
                                <div class="border rounded-lg p-4 {{ $objectif['atteint'] ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium">{{ $objectif['titre'] }}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $objectif['atteint'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $objectif['atteint'] ? ($isFrench ? 'Atteint' : 'Achieved') : ($isFrench ? 'En cours' : 'In Progress') }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-2">
                                        <span class="capitalize">{{ $objectif['secteur'] }}</span> · 
                                        <span>{{ $objectif['type'] === 'revenue' ? ($isFrench ? 'Chiffre d\'affaires' : 'Revenue') : ($isFrench ? 'Bénéfice' : 'Profit') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                            <span>{{ number_format($objectif['progression'], 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $objectif['progression']) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>{{ number_format($objectif['montant_actuel'], 0, ',', ' ') }} FCFA</span>
                                        <span class="text-gray-500">{{ $isFrench ? 'sur' : 'of' }} {{ number_format($objectif['montant_cible'], 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    
                                    @if(count($objectif['sous_objectifs']) > 0)
                                        <details class="mt-3">
                                            <summary class="flex items-center text-sm text-blue-600 cursor-pointer">
                                                <span>{{ $isFrench ? 'Voir les sous-objectifs' : 'View sub-objectives' }} ({{ count($objectif['sous_objectifs']) }})</span>
                                                <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </summary>
                                            <div class="mt-2 space-y-2 pl-2 border-l-2 border-gray-200">
                                                @foreach($objectif['sous_objectifs'] as $sousObjectif)
                                                    <div class="text-sm">
                                                        <div class="flex justify-between mb-1">
                                                            <span class="font-medium">{{ $sousObjectif['titre'] }}</span>
                                                            <span class="{{ $sousObjectif['progression'] >= 100 ? 'text-green-600' : 'text-gray-500' }}">
                                                                {{ number_format($sousObjectif['progression'], 1) }}%
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ min(100, $sousObjectif['progression']) }}%"></div>
                                                        </div>
                                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                            <span>{{ number_format($sousObjectif['montant_actuel'], 0, ',', ' ') }} FCFA</span>
                                                            <span>{{ $isFrench ? 'sur' : 'of' }} {{ number_format($sousObjectif['montant_cible'], 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">{{ $isFrench ? 'Aucun objectif défini pour cette période.' : 'No objectives defined for this period.' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Desktop Additional Sections -->
            @if(!empty($config->social_climat) || !empty($config->major_problems) || !empty($config->recommendations))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Social climate and problems -->
                <div>
                    @if(!empty($config->social_climat))
                        <div class="bg-white rounded-lg shadow mb-6 transform hover:scale-105 transition-all duration-300">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Climat social' : 'Social Climate' }}</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($config->social_climat as $item)
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $item['title'] }}</h3>
                                            <p class="text-gray-700 mt-1">{{ $item['description'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(!empty($config->major_problems))
                        <div class="bg-white rounded-lg shadow transform hover:scale-105 transition-all duration-300">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Problèmes majeurs rencontrés' : 'Major Problems Encountered' }}</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($config->major_problems as $item)
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $item['title'] }}</h3>
                                            <p class="text-gray-700 mt-1">{{ $item['description'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Recommendations -->
                @if(!empty($config->recommendations))
                    <div class="bg-white rounded-lg shadow transform hover:scale-105 transition-all duration-300">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Recommandations' : 'Recommendations' }}</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($config->recommendations as $item)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-gray-900">{{ $item['source'] }}</h3>
                                                <p class="text-sm text-gray-700 mt-1">{{ $item['content'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        /* Styles pour l'impression */
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 12pt;
            color: #000;
            background-color: #fff;
        }
        
        .shadow {
            box-shadow: none !important;
        }
        
        button, a.inline-flex {
            display: none !important;
        }
        
        /* Forcer les marges pour impression */
        @page {
            margin: 2cm;
            size: A4 portrait;
        }

        .md\:hidden {
            display: none !important;
        }

        .mobile-content {
            padding-bottom: 0 !important;
        }

        .floating-toggle {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
    }
</style>

<script>
function printAIAnalysis() {
    // Créer une nouvelle fenêtre pour l'impression
    const printWindow = window.open('', '_blank');
    
    // Récupérer le contenu de l'analyse IA
    const aiAnalysisContent = document.querySelector('.bg-white.rounded-lg.shadow-lg').cloneNode(true);
    
    // Récupérer la langue depuis une variable globale ou un attribut HTML
    const isFrench = document.documentElement.lang === 'fr' || true; // Définir selon votre logique
    
    // Récupérer la date actuelle
    const currentDate = new Date();
    const monthYear = currentDate.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
    
    // Textes selon la langue
    const title = isFrench ? 'Analyse Intelligente par IA' : 'AI Smart Analysis';
    const reportText = isFrench ? 'Rapport du' : 'Report from';
    const pageTitle = isFrench ? 'Analyse IA - Rapport Mensuel' : 'AI Analysis - Monthly Report';
    
    // Créer le HTML pour l'impression
    const printHTML = `
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${pageTitle}</title>
            <script src="https://cdn.tailwindcss.com"><\/script>
            <style>
                @media print {
                    body { font-size: 12px; }
                    .bg-gradient-to-r { background: #4f46e5 !important; -webkit-print-color-adjust: exact; }
                    .transform { transform: none !important; }
                    .shadow-lg { box-shadow: none !important; }
                    @page { margin: 1cm; }
                }
            </style>
        </head>
        <body class="bg-gray-100 p-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">${title}</h1>
                    <p class="text-gray-600">${reportText} ${monthYear}</p>
                </div>
                ${aiAnalysisContent.outerHTML}
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(printHTML);
    printWindow.document.close();
    
    // Attendre que le contenu soit chargé avant d'imprimer
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}
</script>
@endsection
