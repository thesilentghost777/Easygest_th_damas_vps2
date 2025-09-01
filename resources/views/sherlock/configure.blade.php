@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 animate-fade-in">
                    {{ $isFrench ? 'Configuration de Sherlock Conseiller' : 'Sherlock Advisor Configuration' }}
                </h1>
                @include('buttons')
            </div>
            <p class="text-gray-600 mt-2 animate-fade-in delay-100">
                {{ $isFrench ? 'Personnalisez les modules d\'analyse selon vos besoins' : 'Customize analysis modules according to your needs' }}
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm animate-slide-in-right">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Configuration Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-scale-in">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-6">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $isFrench ? 'Modules d\'analyse' : 'Analysis Modules' }}
                </h2>
            </div>
            
            <!-- Info Banner -->
            <div class="bg-blue-50 border-b border-blue-200 p-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-blue-800 leading-relaxed">
                            {{ $isFrench 
                                ? 'Activez ou désactivez les modules d\'analyse que vous souhaitez inclure dans les rapports de Sherlock Conseiller. Les modules désactivés ne seront pas utilisés pour l\'analyse IA, ce qui peut accélérer le temps de génération du rapport.'
                                : 'Enable or disable the analysis modules you want to include in Sherlock Advisor reports. Disabled modules will not be used for AI analysis, which may speed up report generation time.'
                            }}
                        </p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('sherlock.save-config') }}" method="POST" class="p-6 md:p-8">
                @csrf
                
                <!-- Configuration Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Commercial Analysis -->
                    <div class="animate-fade-in-up">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            {{ $isFrench ? 'Analyses commerciales' : 'Commercial Analysis' }}
                        </h3>
                        
                        <div class="space-y-6">
                            @php
                                $commercialModules = [
                                    'analyze_product_performance' => $isFrench ? ['title' => 'Performance des produits', 'desc' => 'Analyse des produits les plus vendus, les plus rentables et les tendances de ventes.'] : ['title' => 'Product Performance', 'desc' => 'Analysis of best-selling, most profitable products and sales trends.'],
                                    'analyze_sales_discrepancies' => $isFrench ? ['title' => 'Écarts de ventes', 'desc' => 'Détection des anomalies entre ventes enregistrées et versements.'] : ['title' => 'Sales Discrepancies', 'desc' => 'Detection of anomalies between recorded sales and deposits.'],
                                    'analyze_theft_detection' => $isFrench ? ['title' => 'Détection de vols potentiels', 'desc' => 'Analyse des alertes de vol et des comportements suspects.'] : ['title' => 'Potential Theft Detection', 'desc' => 'Analysis of theft alerts and suspicious behavior.'],
                                    'analyze_objectives' => $isFrench ? ['title' => 'Objectifs commerciaux', 'desc' => 'Analyse des objectifs, de leur progression et des raisons d\'échec ou de réussite.'] : ['title' => 'Commercial Objectives', 'desc' => 'Analysis of objectives, their progress and reasons for failure or success.'],
                                    'analyze_orders' => $isFrench ? ['title' => 'Analyse des commandes', 'desc' => 'Analyse des commandes par catégorie et tendances.'] : ['title' => 'Order Analysis', 'desc' => 'Analysis of orders by category and trends.']
                                ];
                            @endphp
                            
                            @foreach($commercialModules as $key => $module)
                                <div class="bg-gradient-to-r from-gray-50 to-green-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between space-x-4">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <input id="{{ $key }}" 
                                                       name="{{ $key }}" 
                                                       type="checkbox" 
                                                       value="1" 
                                                       {{ $config->$key ?? true ? 'checked' : '' }} 
                                                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 mr-3">
                                                <label for="{{ $key }}" class="font-medium text-gray-800 text-lg">{{ $module['title'] }}</label>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed ml-8">{{ $module['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Operational Analysis -->
                    <div class="animate-fade-in-up delay-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                            </div>
                            {{ $isFrench ? 'Analyses opérationnelles' : 'Operational Analysis' }}
                        </h3>
                        
                        <div class="space-y-6">
                            @php
                                $operationalModules = [
                                    'analyze_waste' => $isFrench ? ['title' => 'Gaspillage', 'desc' => 'Analyse des sources et coûts du gaspillage.'] : ['title' => 'Waste', 'desc' => 'Analysis of sources and costs of waste.'],
                                    'analyze_material_usage' => $isFrench ? ['title' => 'Utilisation des matières premières', 'desc' => 'Analyse de l\'utilisation vs recommandations et optimisation des recettes.'] : ['title' => 'Raw Material Usage', 'desc' => 'Analysis of usage vs recommendations and recipe optimization.'],
                                    'analyze_spoilage' => $isFrench ? ['title' => 'Avaries et invendus', 'desc' => 'Analyse des produits avariés et invendus avec recommandations.'] : ['title' => 'Spoilage and Unsold', 'desc' => 'Analysis of spoiled and unsold products with recommendations.'],
                                    'analyze_hr_data' => $isFrench ? ['title' => 'Ressources humaines', 'desc' => 'Analyse des horaires, absences et présences.'] : ['title' => 'Human Resources', 'desc' => 'Analysis of schedules, absences and attendance.'],
                                    'analyze_employee_performance' => $isFrench ? ['title' => 'Performance des employés', 'desc' => 'Analyse et classement des employés par performance.'] : ['title' => 'Employee Performance', 'desc' => 'Analysis and ranking of employees by performance.']
                                ];
                            @endphp
                            
                            @foreach($operationalModules as $key => $module)
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between space-x-4">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <input id="{{ $key }}" 
                                                       name="{{ $key }}" 
                                                       type="checkbox" 
                                                       value="1" 
                                                       {{ $config->$key ?? true ? 'checked' : '' }} 
                                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                                                <label for="{{ $key }}" class="font-medium text-gray-800 text-lg">{{ $module['title'] }}</label>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed ml-8">{{ $module['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Additional Modules -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up delay-200">
                    
                    <!-- Market & Trends -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            {{ $isFrench ? 'Analyses de marché et tendances' : 'Market and Trends Analysis' }}
                        </h3>
                        
                        <div class="space-y-6">
                            @php
                                $marketModules = [
                                    'analyze_market_trends' => $isFrench ? ['title' => 'Tendances du marché', 'desc' => 'Analyse des tendances produits et opportunités de marché au Cameroun.'] : ['title' => 'Market Trends', 'desc' => 'Analysis of product trends and market opportunities in Cameroon.'],
                                    'analyze_event_impact' => $isFrench ? ['title' => 'Impact des événements', 'desc' => 'Analyse des impacts d\'événements locaux et mondiaux sur l\'activité.'] : ['title' => 'Event Impact', 'desc' => 'Analysis of local and global event impacts on activity.']
                                ];
                            @endphp
                            
                            @foreach($marketModules as $key => $module)
                                <div class="bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between space-x-4">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <input id="{{ $key }}" 
                                                       name="{{ $key }}" 
                                                       type="checkbox" 
                                                       value="1" 
                                                       {{ $config->$key ?? true ? 'checked' : '' }} 
                                                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 mr-3">
                                                <label for="{{ $key }}" class="font-medium text-gray-800 text-lg">{{ $module['title'] }}</label>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed ml-8">{{ $module['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Sector Analysis -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            {{ $isFrench ? 'Analyses sectorielles' : 'Sector Analysis' }}
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-gray-50 to-orange-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start justify-between space-x-4">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <input id="analyze_ice_cream_sector" 
                                                   name="analyze_ice_cream_sector" 
                                                   type="checkbox" 
                                                   value="1" 
                                                   {{ $config->analyze_ice_cream_sector ?? true ? 'checked' : '' }} 
                                                   class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500 mr-3">
                                            <label for="analyze_ice_cream_sector" class="font-medium text-gray-800 text-lg">
                                                {{ $isFrench ? 'Secteur des glaces' : 'Ice Cream Sector' }}
                                            </label>
                                        </div>
                                        <p class="text-sm text-gray-600 leading-relaxed ml-8">
                                            {{ $isFrench ? 'Analyse spécifique de la performance du secteur des glaces.' : 'Specific analysis of ice cream sector performance.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end mt-10 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ $isFrench ? 'Enregistrer les préférences' : 'Save Preferences' }}</span>
                    </button>
                </div>
            </form>
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

@keyframes slide-in-right {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
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

.animate-slide-in-right {
    animation: slide-in-right 0.5s ease-out;
}

.delay-100 {
    animation-delay: 0.1s;
}

.delay-200 {
    animation-delay: 0.2s;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
