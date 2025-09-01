@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-8">
            @include('buttons')
            <div class="mt-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full mb-6 animate-pulse">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-fade-in">
                    Sherlock Conseiller
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto animate-fade-in delay-100">
                    {{ $isFrench ? 'Assistant IA d\'Analyse' : 'AI Analysis Assistant' }}
                </p>
            </div>
        </div>

        <!-- Main Description Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 animate-scale-in">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $isFrench ? 'À propos de Sherlock' : 'About Sherlock' }}
                </h2>
            </div>
            
            <div class="p-6 md:p-8">
                <p class="text-gray-700 leading-relaxed text-lg">
                    {{ $isFrench 
                        ? 'Sherlock Conseiller est un puissant outil d\'intelligence artificielle conçu pour analyser vos données d\'entreprise et vous fournir des informations stratégiques, des recommandations et des alertes pertinentes.'
                        : 'Sherlock Advisor is a powerful artificial intelligence tool designed to analyze your business data and provide you with strategic insights, recommendations and relevant alerts.'
                    }}
                </p>
            </div>
        </div>

        <!-- How it Works Card -->
        <div class="bg-blue-50 rounded-2xl border-2 border-blue-200 p-6 md:p-8 mb-8 animate-fade-in-up">
            <h3 class="font-bold text-blue-800 mb-4 text-xl flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $isFrench ? 'Comment ça fonctionne ?' : 'How does it work?' }}
            </h3>
            <p class="text-blue-700 leading-relaxed">
                {{ $isFrench 
                    ? 'Sherlock analyse vos données de production, ventes, stocks, ressources humaines et finances pour vous aider à prendre des décisions éclairées. L\'analyse peut prendre quelques minutes en fonction de la quantité de données à traiter.'
                    : 'Sherlock analyzes your production, sales, inventory, human resources and finance data to help you make informed decisions. Analysis may take a few minutes depending on the amount of data to process.'
                }}
            </p>
        </div>
        
        <!-- Generate Analysis Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 animate-fade-in-up delay-200">
            <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    {{ $isFrench ? 'Générer une analyse' : 'Generate Analysis' }}
                </h2>
            </div>
            
            <div class="p-6 md:p-8">
                <form action="{{ route('sherlock.analyze') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="month_year" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Période d\'analyse' : 'Analysis Period' }}
                            </label>
                            <select name="month_year" 
                                    id="month_year" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white">
                                @foreach($months as $value => $label)
                                    <option value="{{ $value }}" {{ $value == date('Y-m') ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>{{ $isFrench ? 'Générer l\'Analyse' : 'Generate Analysis' }}</span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('sherlock.configure') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:shadow-md transition-all duration-200 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $isFrench ? 'Configurer les modules d\'analyse' : 'Configure analysis modules' }}</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Features Grid -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up delay-300">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ $isFrench ? 'Fonctionnalités d\'analyse' : 'Analysis Features' }}
                </h2>
            </div>
            
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $features = $isFrench ? [
                            ['title' => 'Performance des produits', 'desc' => 'Analyse des produits les plus vendus et les plus rentables.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['title' => 'Détection du gaspillage', 'desc' => 'Identification des sources de pertes et gaspillages.', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z'],
                            ['title' => 'Incohérences de caisse', 'desc' => 'Détection des écarts entre ventes et versements.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                            ['title' => 'Performance RH', 'desc' => 'Évaluation des employés et recommandations RH.', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'],
                            ['title' => 'Analyse d\'objectifs', 'desc' => 'Suivi des objectifs et analyse des écarts.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['title' => 'Tendances du marché', 'desc' => 'Veille concurrentielle et opportunités business.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
                        ] : [
                            ['title' => 'Product Performance', 'desc' => 'Analysis of best-selling and most profitable products.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['title' => 'Waste Detection', 'desc' => 'Identification of sources of losses and waste.', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z'],
                            ['title' => 'Cash Inconsistencies', 'desc' => 'Detection of discrepancies between sales and deposits.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                            ['title' => 'HR Performance', 'desc' => 'Employee evaluation and HR recommendations.', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'],
                            ['title' => 'Goal Analysis', 'desc' => 'Goal tracking and variance analysis.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['title' => 'Market Trends', 'desc' => 'Competitive intelligence and business opportunities.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
                        ];
                    @endphp
                    
                    @foreach($features as $index => $feature)
                        <div class="p-6 border border-gray-200 rounded-xl bg-gradient-to-br from-white to-gray-50 hover:shadow-lg hover:border-indigo-200 transition-all duration-300 animate-scale-in"
                             style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-indigo-700 text-lg">{{ $feature['title'] }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
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

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-100 {
    animation-delay: 0.1s;
}

.delay-200 {
    animation-delay: 0.2s;
}

.delay-300 {
    animation-delay: 0.3s;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
