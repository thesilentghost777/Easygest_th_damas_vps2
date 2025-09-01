@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')

        <!-- Header Section avec animations mobiles -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mobile-card animate-fade-in">
                    {{ $isFrench ? 'Tableau de bord des objectifs' : 'Objectives Dashboard' }}
                </h1>
                
                <!-- Boutons d'action mobile -->
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                    <a href="{{ route('objectives.index') }}" 
                       class="mobile-btn w-full md:w-auto text-center px-4 py-3 md:py-2 bg-white border border-gray-300 rounded-xl md:rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-300">
                        <i class="mdi mdi-format-list-bulleted mr-2"></i>
                        {{ $isFrench ? 'Liste des objectifs' : 'Objectives List' }}
                    </a>
                    <a href="{{ route('objectives.create') }}" 
                       class="mobile-btn w-full md:w-auto text-center px-4 py-3 md:py-2 bg-blue-600 border border-transparent rounded-xl md:rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 transition-all duration-300">
                        <i class="mdi mdi-plus mr-2"></i>
                        {{ $isFrench ? 'Créer un objectif' : 'Create Objective' }}
                    </a>
                </div>
            </div>
            
            <!-- Statistiques générales avec animations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6 md:mb-8">
                <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-5 border-t-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Objectifs actifs' : 'Active Objectives' }}</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $activeObjectivesCount }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center pulse-ring">
                            <i class="mdi mdi-target text-xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-5 border-t-4 border-green-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Objectifs complétés' : 'Completed Objectives' }}</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $completedObjectives }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="mdi mdi-check-circle text-xl text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-5 border-t-4 border-purple-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Montant total ciblé' : 'Total Target Amount' }}</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">{{ number_format($totalTargetAmount, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'XAF' }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="mdi mdi-currency-usd text-xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mobile-card bg-white overflow-hidden shadow-lg rounded-2xl md:rounded-lg p-5 border-t-4 border-amber-500 transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Progression moyenne' : 'Average Progress' }}</p>
                            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ number_format($averageProgress, 1) }}%</p>
                        </div>
                        <div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="mdi mdi-trending-up text-xl text-amber-600"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-amber-500 h-2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $averageProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graphiques Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $isFrench ? 'Progression par période' : 'Progress by Period' }}</h2>
                    <div id="progressByPeriodChart" class="h-48 md:h-64"></div>
                </div>
                
                <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $isFrench ? 'Progression par secteur' : 'Progress by Sector' }}</h2>
                    <div id="progressBySectorChart" class="h-48 md:h-64"></div>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $isFrench ? 'Répartition des objectifs' : 'Objectives Distribution' }}</h2>
                    <div id="objectivesDistributionChart" class="h-48 md:h-64"></div>
                </div>
            </div>
        </div>
        
        <!-- Liste des objectifs par secteur -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <!-- Objectifs Alimentation -->
            <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg">
                <div class="border-b border-gray-200 bg-blue-50 px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold text-blue-900">{{ $isFrench ? 'Alimentation' : 'General store' }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    @if(count($alimentationObjectives) > 0)
                        <div class="space-y-4">
                            @foreach($alimentationObjectives as $objective)
                                <div class="mobile-card border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all duration-300 hover:shadow-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="text-base md:text-lg font-medium text-blue-800 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($isFrench)
                                               {{ $objective->formatted_period_type }}
                                           @else
                                               @if ($objective->formatted_period_type == 'Mensuel')
                                                   Monthly
                                               @elseif ($objective->formatted_period_type == 'Journalier')
                                                   Daily
                                               @elseif ($objective->formatted_period_type == 'Annuel')
                                                   Yearly
                                               @elseif ($objective->formatted_period_type == 'Hebdomadaire')
                                                   Weekly
                                               @else
                                                   {{ $objective->formatted_period_type }}
                                               @endif
                                           @endif  | {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                            <span class="font-medium">{{ number_format($objective->current_progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full {{ $objective->progress_color }} transition-all duration-1000 ease-out" style="width: {{ $objective->current_progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span>{{ $objective->formatted_current_amount }}</span>
                                            <span>{{ $objective->formatted_target_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="mdi mdi-target text-4xl mb-2"></i>
                            <p>{{ $isFrench ? 'Aucun objectif actif pour ce secteur.' : 'No active objectives for this sector.' }}</p>
                            <a href="{{ route('objectives.create') }}" class="mobile-btn inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
                                {{ $isFrench ? 'Créer un objectif' : 'Create Objective' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Objectifs Global -->
            <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg">
                <div class="border-b border-gray-200 bg-green-50 px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold text-green-900">{{ $isFrench ? 'Global (Toute entreprise)' : 'Global (Entire Company)' }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    @if(count($globalObjectives) > 0)
                        <div class="space-y-4">
                            @foreach($globalObjectives as $objective)
                                <div class="mobile-card border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all duration-300 hover:shadow-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="text-base md:text-lg font-medium text-green-800 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($isFrench)
                                               {{ $objective->formatted_period_type }}
                                           @else
                                               @if ($objective->formatted_period_type == 'Mensuel')
                                                   Monthly
                                               @elseif ($objective->formatted_period_type == 'Journalier')
                                                   Daily
                                               @elseif ($objective->formatted_period_type == 'Annuel')
                                                   Yearly
                                               @elseif ($objective->formatted_period_type == 'Hebdomadaire')
                                                   Weekly
                                               @else
                                                   {{ $objective->formatted_period_type }}
                                               @endif
                                           @endif  | {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                            <span class="font-medium">{{ number_format($objective->current_progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full {{ $objective->progress_color }} transition-all duration-1000 ease-out" style="width: {{ $objective->current_progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span>{{ $objective->formatted_current_amount }}</span>
                                            <span>{{ $objective->formatted_target_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="mdi mdi-target text-4xl mb-2"></i>
                            <p>{{ $isFrench ? 'Aucun objectif actif pour ce secteur.' : 'No active objectives for this sector.' }}</p>
                            <a href="{{ route('objectives.create') }}" class="mobile-btn inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300">
                                {{ $isFrench ? 'Créer un objectif' : 'Create Objective' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Objectifs Boulangerie-Pâtisserie -->
            <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg">
                <div class="border-b border-gray-200 bg-yellow-50 px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold text-yellow-900">{{ $isFrench ? 'Boulangerie-Pâtisserie' : 'Bakery-Pastry' }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    @if(count($boulangerieObjectives) > 0)
                        <div class="space-y-4">
                            @foreach($boulangerieObjectives as $objective)
                                <div class="mobile-card border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all duration-300 hover:shadow-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="text-base md:text-lg font-medium text-yellow-800 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($isFrench)
                                               {{ $objective->formatted_period_type }}
                                           @else
                                               @if ($objective->formatted_period_type == 'Mensuel')
                                                   Monthly
                                               @elseif ($objective->formatted_period_type == 'Journalier')
                                                   Daily
                                               @elseif ($objective->formatted_period_type == 'Annuel')
                                                   Yearly
                                               @elseif ($objective->formatted_period_type == 'Hebdomadaire')
                                                   Weekly
                                               @else
                                                   {{ $objective->formatted_period_type }}
                                               @endif
                                           @endif  | {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                            </div>
                                            @if($objective->subObjectives->count() > 0)
                                                <div class="text-xs mt-1">
                                                    <span class="text-yellow-600">{{ $objective->subObjectives->count() }} {{ $isFrench ? 'sous-objectifs' : 'sub-objectives' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                            <span class="font-medium">{{ number_format($objective->current_progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full {{ $objective->progress_color }} transition-all duration-1000 ease-out" style="width: {{ $objective->current_progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span>{{ $objective->formatted_current_amount }}</span>
                                            <span>{{ $objective->formatted_target_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="mdi mdi-bread-slice text-4xl mb-2"></i>
                            <p>{{ $isFrench ? 'Aucun objectif actif pour ce secteur.' : 'No active objectives for this sector.' }}</p>
                            <a href="{{ route('objectives.create') }}" class="mobile-btn inline-block mt-4 px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-all duration-300">
                                {{ $isFrench ? 'Créer un objectif' : 'Create Objective' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Objectifs Glaces -->
            <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg">
                <div class="border-b border-gray-200 bg-purple-50 px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold text-purple-900">{{ $isFrench ? 'Glaces' : 'Ice Cream' }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    @if(count($glaceObjectives) > 0)
                        <div class="space-y-4">
                            @foreach($glaceObjectives as $objective)
                                <div class="mobile-card border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all duration-300 hover:shadow-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="text-base md:text-lg font-medium text-purple-800 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if ($isFrench)
                                               {{ $objective->formatted_period_type }}
                                           @else
                                               @if ($objective->formatted_period_type == 'Mensuel')
                                                   Monthly
                                               @elseif ($objective->formatted_period_type == 'Journalier')
                                                   Daily
                                               @elseif ($objective->formatted_period_type == 'Annuel')
                                                   Yearly
                                               @elseif ($objective->formatted_period_type == 'Hebdomadaire')
                                                   Weekly
                                               @else
                                                   {{ $objective->formatted_period_type }}
                                               @endif
                                           @endif  | {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                           @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                            <span class="font-medium">{{ number_format($objective->current_progress, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full {{ $objective->progress_color }} transition-all duration-1000 ease-out" style="width: {{ $objective->current_progress }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span>{{ $objective->formatted_current_amount }}</span>
                                            <span>{{ $objective->formatted_target_amount }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="mdi mdi-ice-cream text-4xl mb-2"></i>
                            <p>{{ $isFrench ? 'Aucun objectif actif pour ce secteur.' : 'No active objectives for this sector.' }}</p>
                            <a href="{{ route('objectives.create') }}" class="mobile-btn inline-block mt-4 px-6 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all duration-300">
                                {{ $isFrench ? 'Créer un objectif' : 'Create Objective' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique de progression par période
        const progressByPeriodOptions = {
            series: [{
                name: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}',
                data: [
                    {{ $progressByPeriod['daily'] }},
                    {{ $progressByPeriod['weekly'] }},
                    {{ $progressByPeriod['monthly'] }},
                    {{ $progressByPeriod['yearly'] }}
                ]
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded'
                },
            },
            colors: ['#4F46E5'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: ['{{ $isFrench ? "Journalier" : "Daily" }}', '{{ $isFrench ? "Hebdomadaire" : "Weekly" }}', '{{ $isFrench ? "Mensuel" : "Monthly" }}', '{{ $isFrench ? "Annuel" : "Yearly" }}'],
            },
            yaxis: {
                title: { text: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}' },
                min: 0,
                max: 100
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%"
                    }
                }
            }
        };

        const progressByPeriodChart = new ApexCharts(document.querySelector("#progressByPeriodChart"), progressByPeriodOptions);
        progressByPeriodChart.render();
        
        // Graphique de progression par secteur
        const progressBySectorOptions = {
            series: [{
                name: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}',
                data: [
                    {{ $progressBySector['alimentation'] }},
                    {{ $progressBySector['boulangerie'] }},
                    {{ $progressBySector['glace'] }},
                    {{ $progressBySector['global'] }}
                ]
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded'
                },
            },
            colors: ['#10B981'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: ['{{ $isFrench ? "Alimentation" : "General Store" }}', '{{ $isFrench ? "Boulangerie" : "Bakery" }}', '{{ $isFrench ? "Glaces" : "Ice Cream" }}', '{{ $isFrench ? "Global" : "Global" }}'],
            },
            yaxis: {
                title: { text: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}' },
                min: 0,
                max: 100
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%"
                    }
                }
            }
        };

        const progressBySectorChart = new ApexCharts(document.querySelector("#progressBySectorChart"), progressBySectorOptions);
        progressBySectorChart.render();
        
        // Graphique de distribution des objectifs
        const objectivesDistributionOptions = {
            series: [
                {{ count($alimentationObjectives) }}, 
                {{ count($boulangerieObjectives) }}, 
                {{ count($glaceObjectives) }},
                {{ count($globalObjectives) }}
            ],
            chart: {
                type: 'donut',
                height: 250,
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            labels: ['{{ $isFrench ? "Alimentation" : "General Store" }}', '{{ $isFrench ? "Boulangerie" : "Bakery" }}', '{{ $isFrench ? "Glaces" : "Ice Cream" }}', '{{ $isFrench ? "Global" : "Global" }}'],
            colors: ['#3B82F6', '#F59E0B', '#8B5CF6', '#10B981'],
            plotOptions: {
                pie: {
                    donut: { size: '55%' }
                }
            },
            legend: { position: 'bottom' },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { height: 200 },
                    legend: { position: 'bottom' }
                }
            }]
        };

        const objectivesDistributionChart = new ApexCharts(document.querySelector("#objectivesDistributionChart"), objectivesDistributionOptions);
        objectivesDistributionChart.render();

        // Animation d'entrée pour les cartes
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.mobile-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endsection
