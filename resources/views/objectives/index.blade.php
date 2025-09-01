@extends('layouts.app')

@section('content')
<div class="py-6 lg:py-12 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Notification de succès -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl lg:rounded-lg mobile-notification">
                {{ session('success') }}
            </div>
        @endif
        
        @include('buttons')

        <!-- Mobile Header -->
        <div class="mb-6 md:hidden">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 animate-fade-in">
                @if($isFrench)
                    Mes objectifs
                @else
                    My Objectives
                @endif
            </h1>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                @if($isFrench)
                    Mes objectifs
                @else
                    My Objectives
                @endif
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('objectives.dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        @if($isFrench)
                            Tableau de bord
                        @else
                            Dashboard
                        @endif
                    </div>
                </a>
                <a href="{{ route('objectives.create') }}" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        @if($isFrench)
                            Créer un objectif
                        @else
                            Create Objective
                        @endif
                    </div>
                </a>
            </div>
        </div>

        <!-- Mobile Action Buttons -->
        <div class="md:hidden grid grid-cols-2 gap-3 mb-6">
            <a href="{{ route('objectives.dashboard') }}" class="mobile-action-btn bg-white border border-gray-300 rounded-2xl p-4 text-center hover:bg-gray-50 transition-all duration-200 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">
                    @if($isFrench)
                        Tableau de bord
                    @else
                        Dashboard
                    @endif
                </span>
            </a>
            <a href="{{ route('objectives.create') }}" class="mobile-action-btn bg-blue-600 rounded-2xl p-4 text-center hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-white">
                    @if($isFrench)
                        Créer
                    @else
                        Create
                    @endif
                </span>
            </a>
        </div>
        
        <!-- Objectifs actifs -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl lg:rounded-lg mb-6 lg:mb-8 mobile-slide-up">
            <div class="p-4 lg:p-6 border-b border-gray-200 bg-blue-50">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900">
                    @if($isFrench)
                        Objectifs actifs
                    @else
                        Active Objectives
                    @endif
                </h2>
            </div>
            
            @if(count($activeObjectives) > 0)
                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach($activeObjectives as $objective)
                        <div class="mobile-objective-card bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg mb-1">
                                        <a href="{{ route('objectives.show', $objective->id) }}" class="hover:text-blue-600">
                                            {{ $objective->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $objective->sector_color }}">
                                        @if ($isFrench)
                                            
                                            {{ $objective->formatted_sector }}
                                        @else
                                           @if ($objective->formatted_sector == 'Alimentation')
                                               General store
                                           @elseif ($objective->formatted_sector == 'Glaces')
                                                Ice Cream
                                            @elseif ($objective->formatted_sector == 'Boulangerie-Pâtisserie')
                                                Bakery-Pastry
                                            @else
                                                Global (all sectors)
                                            @endif
                                        @endif
                                        
                                    </span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">
                                        @if($isFrench)
                                            Période
                                        @else
                                            Period
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">
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
                                           @endif 
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">
                                        @if($isFrench)
                                            Cible
                                        @else
                                            Target
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $objective->formatted_target_amount }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">
                                        @if($isFrench)
                                            Progression
                                        @else
                                            Progress
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($objective->current_progress, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full {{ $objective->progress_color }} transition-all duration-500" style="width: {{ $objective->current_progress }}%"></div>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('objectives.show', $objective->id) }}" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl text-center text-sm font-medium hover:bg-blue-700 transition-colors">
                                    @if($isFrench)
                                        Détails
                                    @else
                                        Details
                                    @endif
                                </a>
                                @if(!$objective->is_confirmed)
                                    <a href="{{ route('objectives.edit', $objective->id) }}" class="flex-1 bg-green-600 text-white py-3 px-4 rounded-xl text-center text-sm font-medium hover:bg-green-700 transition-colors">
                                        @if($isFrench)
                                            Modifier
                                        @else
                                            Edit
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Titre
                                    @else
                                        Title
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Secteur
                                    @else
                                        Sector
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Période
                                    @else
                                        Period
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Cible
                                    @else
                                        Target
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Progression
                                    @else
                                        Progress
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activeObjectives as $objective)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="hover:text-blue-600 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if ($isFrench)
                                            {{ $objective->formatted_goal_type }}
                                        @else
                                            @if ($objective->formatted_goal_type == 'Bénéfice')
                                                Profit
                                            @else
                                                Revenue
                                            @endif
                                        @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $objective->sector_color }}">
                                             @if ($isFrench)
                                            {{ $objective->formatted_sector }}
                                        @else
                                           @if ($objective->formatted_sector == 'Alimentation')
                                               General store
                                           @elseif ($objective->formatted_sector == 'Glaces')
                                                Ice Cream
                                            @elseif ($objective->formatted_sector == 'Boulangerie-Pâtisserie')
                                                Bakery-Pastry
                                            @else
                                                Global (all sectors)
                                            @endif
                                        @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
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
                                           @endif 
                                            </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $objective->formatted_target_amount }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="mr-2 text-sm font-medium text-gray-900">
                                                {{ number_format($objective->current_progress, 1) }}%
                                            </div>
                                            <div class="w-32 bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full {{ $objective->progress_color }}" style="width: {{ $objective->current_progress }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('objectives.show', $objective->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            @if($isFrench)
                                                Détails
                                            @else
                                                Details
                                            @endif
                                        </a>
                                        @if(!$objective->is_confirmed)
                                            <a href="{{ route('objectives.edit', $objective->id) }}" class="text-green-600 hover:text-green-900">
                                                @if($isFrench)
                                                    Modifier
                                                @else
                                                    Edit
                                                @endif
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-blue-50 text-blue-700 p-6 text-center mobile-empty-state">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="mb-4">
                        @if($isFrench)
                            Aucun objectif actif pour le moment.
                        @else
                            No active objectives at the moment.
                        @endif
                    </p>
                    <a href="{{ route('objectives.create') }}" class="inline-flex items-center text-blue-600 hover:underline font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        @if($isFrench)
                            Créer votre premier objectif
                        @else
                            Create your first objective
                        @endif
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Objectifs passés -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl lg:rounded-lg mobile-slide-up">
            <div class="p-4 lg:p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900">
                    @if($isFrench)
                        Historique des objectifs
                    @else
                        Objectives History
                    @endif
                </h2>
            </div>
            
            @if(count($pastObjectives) > 0)
                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach($pastObjectives as $objective)
                        <div class="mobile-objective-card bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg mb-1">
                                        <a href="{{ route('objectives.show', $objective->id) }}" class="hover:text-blue-600">
                                            {{ $objective->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $objective->formatted_goal_type }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $objective->sector_color }}">
                                        {{ $objective->formatted_sector }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">
                                        @if($isFrench)
                                            Période
                                        @else
                                            Period
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $objective->formatted_period_type }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">
                                        @if($isFrench)
                                            Cible
                                        @else
                                            Target
                                        @endif
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $objective->formatted_target_amount }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-full w-full text-center {{ $objective->is_achieved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($isFrench)
                                        {{ $objective->is_achieved ? 'Atteint' : 'Non atteint' }}
                                    @else
                                        {{ $objective->is_achieved ? 'Achieved' : 'Not achieved' }}
                                    @endif
                                    ({{ number_format($objective->current_progress, 1) }}%)
                                </span>
                            </div>
                            
                            <a href="{{ route('objectives.show', $objective->id) }}" class="block bg-blue-600 text-white py-3 px-4 rounded-xl text-center text-sm font-medium hover:bg-blue-700 transition-colors">
                                @if($isFrench)
                                    Détails
                                @else
                                    Details
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Titre
                                    @else
                                        Title
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Secteur
                                    @else
                                        Sector
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Période
                                    @else
                                        Period
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Cible
                                    @else
                                        Target
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @if($isFrench)
                                        Résultat
                                    @else
                                        Result
                                    @endif
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pastObjectives as $objective)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('objectives.show', $objective->id) }}" class="hover:text-blue-600 hover:underline">
                                                {{ $objective->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $objective->formatted_goal_type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $objective->sector_color }}">
                                            {{ $objective->formatted_sector }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
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
                                           @endif 
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $objective->formatted_target_amount }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $objective->is_achieved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            @if($isFrench)
                                                {{ $objective->is_achieved ? 'Atteint' : 'Non atteint' }}
                                            @else
                                                {{ $objective->is_achieved ? 'Achieved' : 'Not achieved' }}
                                            @endif
                                            ({{ number_format($objective->current_progress, 1) }}%)
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('objectives.show', $objective->id) }}" class="text-blue-600 hover:text-blue-900">
                                            @if($isFrench)
                                                Détails
                                            @else
                                                Details
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 text-gray-700 p-6 text-center mobile-empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p>
                        @if($isFrench)
                            Aucun objectif passé.
                        @else
                            No past objectives.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Mobile-first animations and styles */
@media (max-width: 768px) {
    .mobile-notification {
        animation: slideInDown 0.5s ease-out;
    }
    
    .mobile-action-btn {
        animation: slideInUp 0.6s ease-out;
        transform-origin: bottom;
    }
    
    .mobile-action-btn:nth-child(1) { animation-delay: 0.1s; }
    .mobile-action-btn:nth-child(2) { animation-delay: 0.2s; }
    
    .mobile-action-btn:active {
        transform: scale(0.98);
    }
    
    .mobile-slide-up {
        animation: slideInUp 0.8s ease-out;
    }
    
    .mobile-objective-card {
        animation: fadeInUp 0.6s ease-out;
        transition: all 0.3s ease;
    }
    
    .mobile-objective-card:nth-child(1) { animation-delay: 0.1s; }
    .mobile-objective-card:nth-child(2) { animation-delay: 0.2s; }
    .mobile-objective-card:nth-child(3) { animation-delay: 0.3s; }
    .mobile-objective-card:nth-child(4) { animation-delay: 0.4s; }
    
    .mobile-objective-card:active {
        transform: scale(0.98);
    }
    
    .mobile-empty-state {
        animation: fadeInUp 0.8s ease-out;
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes animate-fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: animate-fade-in 0.6s ease-out;
}
</style>
@endsection
