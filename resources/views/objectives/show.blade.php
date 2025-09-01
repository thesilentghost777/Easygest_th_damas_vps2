@extends('layouts.app')

@section('content')
<div class="py-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile-First Notifications -->
        @if(session('success'))
            <div class="mobile-toast mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl md:rounded-lg mobile-card">
                <div class="flex items-center">
                    <i class="mdi mdi-check-circle mr-3 text-xl"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mobile-toast mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl md:rounded-lg mobile-card">
                <div class="flex items-center">
                    <i class="mdi mdi-alert-circle mr-3 text-xl"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @include('buttons')

        <!-- Mobile-First Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6 relative">
                    <!-- Mobile Header -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6">
                        <div class="mb-4 md:mb-0">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $objective->title }}</h2>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="inline-block px-3 py-1 text-xs md:text-sm rounded-full {{ $objective->sector_color }}">
                                     @if ($isFrench)
                                            
                                            {{ $objective->formatted_sector }}
                                        @else
                                           @if ($objective->formatted_sector == 'Alimentation')
                                               'General store'
                                           @elseif ($objective->formatted_sector == 'Glaces')
                                                'Ice Cream'
                                            @elseif ($objective->formatted_sector == 'Boulangerie-Pâtisserie')
                                                'Bakery-Pastry'
                                            @else
                                                'Global (all sectors)'
                                            @endif
                                        @endif
                                </span>
                                <span class="text-sm text-gray-600">
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
                        </div>
                        
                        <!-- Mobile Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            @if(!$objective->is_confirmed)
                                <a href="{{ route('objectives.edit', $objective->id) }}" 
                                   class="mobile-btn flex-1 md:flex-initial px-4 py-2 bg-blue-600 text-white text-sm rounded-2xl md:rounded hover:bg-blue-700 transition-colors text-center">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <form action="{{ route('objectives.confirm', $objective->id) }}" method="POST" class="flex-1 md:flex-initial">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="mobile-btn w-full px-4 py-2 bg-green-600 text-white text-sm rounded-2xl md:rounded hover:bg-green-700 transition-colors">
                                        {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('objectives.index') }}" 
                               class="mobile-btn flex-1 md:flex-initial px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-2xl md:rounded hover:bg-gray-300 transition-colors text-center">
                                {{ $isFrench ? 'Retour' : 'Back' }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($objective->description)
                        <div class="mb-6 bg-gray-50 p-4 rounded-2xl md:rounded-lg mobile-card">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                {{ $isFrench ? 'Description' : 'Description' }}
                            </h3>
                            <p class="text-gray-700">{{ $objective->description }}</p>
                        </div>
                    @endif
                    
                    <!-- Mobile Progress Card -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl md:rounded-xl shadow-lg p-6 text-white mobile-card">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                                <div class="mb-4 md:mb-0">
                                    <p class="text-blue-100">{{ $isFrench ? 'Montant cible' : 'Target Amount' }}</p>
                                    <p class="text-xl md:text-2xl font-bold">{{ $objective->formatted_target_amount }}</p>
                                </div>
                                <div class="text-left md:text-right">
                                    <p class="text-blue-100">{{ $isFrench ? 'Type d\'objectif' : 'Goal Type' }}</p>
                                    <p class="text-lg md:text-xl font-semibold">{{ $objective->formatted_goal_type }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                                <span class="text-blue-100">{{ $isFrench ? 'Progression' : 'Progress' }}: {{ number_format($objective->current_progress, 1) }}%</span>
                                <span class="text-blue-100">
                                    {{ $objective->formatted_current_amount }} / {{ $objective->formatted_target_amount }}
                                </span>
                            </div>
                            
                            <div class="w-full bg-blue-200 bg-opacity-30 rounded-full h-3 mb-4">
                                <div class="h-3 bg-white rounded-full transition-all duration-1000 ease-out" style="width: {{ $objective->current_progress }}%"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-blue-100">{{ $isFrench ? 'Montant restant' : 'Remaining Amount' }}</p>
                                    <p class="font-semibold">{{ $objective->formatted_remaining_amount }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-100">{{ $isFrench ? 'Période' : 'Period' }}</p>
                                    <p class="font-semibold">{{ $objective->start_date->format('d/m/Y') }} - {{ $objective->end_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            {{ $isFrench ? 'Évolution de la progression' : 'Progress Evolution' }}
                        </h3>
                        <div class="bg-white border border-gray-200 rounded-2xl md:rounded-lg p-4 h-64 mobile-card" id="progressChart"></div>
                    </div>
                    
                    <!-- Financial Data Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        @php
                            $latestProgress = $objective->progress()->latest()->first();
                            $expenses = $latestProgress ? $latestProgress->expenses : 0;
                            $profit = $latestProgress ? $latestProgress->profit : 0;
                        @endphp
                        
                        <div class="mobile-card bg-blue-50 border border-blue-100 rounded-2xl md:rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                {{ $isFrench ? 'Montant actuel' : 'Current Amount' }}
                            </h4>
                            <p class="text-xl font-bold text-blue-700">
                                {{ $latestProgress ? $latestProgress->formatted_current_amount : '0 FCFA' }}
                            </p>
                        </div>
                        
                        <div class="mobile-card bg-red-50 border border-red-100 rounded-2xl md:rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                {{ $isFrench ? 'Dépenses' : 'Expenses' }}
                            </h4>
                            <p class="text-xl font-bold text-red-700">
                                {{ $latestProgress ? $latestProgress->formatted_expenses : '0 FCFA' }}
                            </p>
                        </div>
                        
                        <div class="mobile-card bg-green-50 border border-green-100 rounded-2xl md:rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-1">
                                {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                            </h4>
                            <p class="text-xl font-bold text-green-700">
                                {{ $latestProgress ? $latestProgress->formatted_profit : '0 FCFA' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Mobile Transactions Table -->
                    @if(count($transactions) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                {{ $isFrench ? 'Transactions récentes' : 'Recent Transactions' }}
                            </h3>
                            <div class="bg-white border border-gray-200 rounded-2xl md:rounded-lg overflow-hidden mobile-card">
                                <!-- Mobile Cards View -->
                                <div class="mobile-only space-y-3 p-4">
                                    @if($objective->sector !== 'global')
                                        @foreach($transactions as $transaction)
                                            <div class="mobile-card bg-gray-50 rounded-xl p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-sm font-semibold text-gray-800">{{ $transaction->code_vc }}</span>
                                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Verseur' : 'Payer' }}: {{ $transaction->verseur_name ?? 'Inconnu' }}</p>
                                                    <p class="text-lg font-bold text-blue-600">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</p>
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $transaction->libelle }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($transactions as $transaction)
                                            <div class="mobile-card bg-gray-50 rounded-xl p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-sm font-semibold text-gray-800">#{{ $transaction->id }}</span>
                                                    <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $transaction->type === 'income' ? ($isFrench ? 'Entrée' : 'Income') : ($isFrench ? 'Sortie' : 'Expense') }}
                                                    </span>
                                                </div>
                                                <div class="mb-2">
                                                    <p class="text-lg font-bold text-blue-600">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</p>
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $transaction->description }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Desktop Table View -->
                                <div class="desktop-only">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ID
                                                </th>
                                                @if($objective->sector !== 'global')
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ $isFrench ? 'Verseur' : 'Payer' }}
                                                    </th>
                                                @else
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Type
                                                    </th>
                                                @endif
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $isFrench ? 'Montant' : 'Amount' }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Date
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $isFrench ? 'Libellé' : 'Description' }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @if($objective->sector !== 'global')
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $transaction->code_vc }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ $transaction->verseur_name ?? 'Inconnu' }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $transaction->libelle }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $transaction->id }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $transaction->type === 'income' ? ($isFrench ? 'Entrée' : 'Income') : ($isFrench ? 'Sortie' : 'Expense') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $transaction->description }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mobile-card bg-yellow-50 border border-yellow-100 rounded-2xl md:rounded-lg p-4">
                            <p class="text-yellow-700">{{ $isFrench ? 'Aucune transaction n\'a encore été enregistrée pour cet objectif.' : 'No transactions have been recorded for this objective yet.' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-4 md:space-y-6">
                <!-- Status Card -->
                <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Statut de l\'objectif' : 'Objective Status' }}
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Actif' : 'Active' }}:</span>
                            <span class="px-3 py-1 text-xs rounded-full {{ $objective->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $objective->is_active ? ($isFrench ? 'Oui' : 'Yes') : ($isFrench ? 'Non' : 'No') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
    <span class="text-sm text-gray-600">{{ $isFrench ? 'Atteint' : 'Achieved' }}:</span>
    <span class="px-3 py-1 text-xs rounded-full 
        @if($objective->is_active)
            {{ $objective->is_achieved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}
        @else
            {{ $objective->is_achieved ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}
        @endif
    ">
        @if ($objective->is_active)
            {{ $objective->is_achieved ? ($isFrench ? 'Oui' : 'Yes') : ($isFrench ? 'En cours' : 'In Progress') }}
        @else
            {{ $objective->is_achieved ? ($isFrench ? 'Oui' : 'Yes') : ($isFrench ? 'Non applicable' : 'Not Applicable') }}
        @endif
    </span>
</div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Confirmé' : 'Confirmed' }}:</span>
                            <span class="px-3 py-1 text-xs rounded-full {{ $objective->is_confirmed ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $objective->is_confirmed ? ($isFrench ? 'Oui' : 'Yes') : ($isFrench ? 'Non' : 'No') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Créé par' : 'Created by' }}:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $objective->user->name }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Créé le' : 'Created on' }}:</span>
                            <span class="text-sm text-gray-800">{{ $objective->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    @if(!$objective->is_confirmed)
                        <div class="mt-6">
                            <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" 
                                  onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet objectif?' : 'Are you sure you want to delete this objective?' }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mobile-btn w-full px-4 py-2 bg-red-600 text-white text-sm rounded-2xl md:rounded hover:bg-red-700 transition-colors">
                                    {{ $isFrench ? 'Supprimer l\'objectif' : 'Delete Objective' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                
                <!-- Sub-objectives Section -->
                @if($objective->sector === 'boulangerie-patisserie')
                    <div class="mobile-card bg-white overflow-hidden shadow-xl rounded-2xl md:rounded-lg p-4 md:p-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 md:mb-0">
                                {{ $isFrench ? 'Sous-objectifs par produit' : 'Sub-objectives by Product' }}
                            </h3>
                            
                            @if(!$objective->is_confirmed)
                                <button type="button" 
                                    class="mobile-btn px-4 py-2 bg-green-600 text-white text-sm rounded-2xl md:rounded hover:bg-green-700 transition-colors"
                                    onclick="document.getElementById('add-sub-objective-modal').classList.remove('hidden')">
                                    {{ $isFrench ? 'Ajouter' : 'Add' }}
                                </button>
                            @endif
                        </div>
                        
                        @if($objective->subObjectivesExceedLimit)
                            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-2xl md:rounded-lg text-sm mobile-card">
                                {{ $isFrench ? 'Attention: Le montant total des sous-objectifs dépasse l\'objectif principal!' : 'Warning: The total amount of sub-objectives exceeds the main objective!' }}
                            </div>
                        @endif
                        
                        <div class="mb-4 grid grid-cols-2 gap-4 text-sm">
                            <div class="mobile-card bg-blue-50 border border-blue-100 rounded-xl md:rounded p-3 text-center">
                                <p class="text-xs text-gray-600">{{ $isFrench ? 'Total alloué' : 'Total Allocated' }}</p>
                                <p class="font-semibold text-blue-800">
                                    {{ number_format($objective->totalSubObjectivesAmount, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                            <div class="mobile-card bg-green-50 border border-green-100 rounded-xl md:rounded p-3 text-center">
                                <p class="text-xs text-gray-600">{{ $isFrench ? 'Reste à allouer' : 'Remaining to Allocate' }}</p>
                                <p class="font-semibold text-green-800">
                                    {{ number_format($objective->subObjectivesRemainingAllocation, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                        
                        @if(count($subObjectives) > 0)
                            <div class="space-y-4">
                                @foreach($subObjectives as $subObj)
                                    <div class="mobile-card border border-gray-200 rounded-2xl md:rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $subObj->title }}</h4>
                                                @if($subObj->product_id)
                                                    <p class="text-xs text-gray-600">
                                                        {{ $isFrench ? 'Produit' : 'Product' }}: {{ $subObj->product ? $subObj->product->nom : ($isFrench ? 'Inconnu' : 'Unknown') }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            @if(!$objective->is_confirmed)
                                                <div class="flex space-x-1">
                                                    <button type="button" 
                                                        class="text-blue-600 hover:text-blue-800 p-1" 
                                                        onclick="editSubObjective({{ $subObj->id }}, '{{ $subObj->title }}', {{ $subObj->product_id ?: 'null' }}, {{ $subObj->target_amount }})">
                                                        <i class="mdi mdi-pencil text-lg"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('objectives.sub-objectives.destroy', ['objective' => $objective->id, 'subObjective' => $subObj->id]) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce sous-objectif?' : 'Are you sure you want to delete this sub-objective?' }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                            <i class="mdi mdi-delete text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-2 mb-2 text-xs">
                                            <div>
                                                <span class="text-gray-600">{{ $isFrench ? 'Montant cible' : 'Target Amount' }}: </span>
                                                <span class="font-medium">{{ $subObj->formatted_target_amount }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">{{ $isFrench ? 'Montant actuel' : 'Current Amount' }}: </span>
                                                <span class="font-medium">{{ $subObj->formatted_current_amount }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                            <div class="h-2 bg-blue-600 rounded-full transition-all duration-1000 ease-out" style="width: {{ $subObj->progress_percentage }}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-gray-600">{{ number_format($subObj->progress_percentage, 1) }}% {{ $isFrench ? 'complété' : 'completed' }}</span>
                                            <span class="font-medium">
                                                {{ $subObj->formatted_remaining_amount }} {{ $isFrench ? 'restant' : 'remaining' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mobile-card bg-gray-50 border border-gray-200 rounded-2xl md:rounded-lg p-4 text-center">
                                <p class="text-gray-600">
                                    @if(!$objective->is_confirmed)
                                        {{ $isFrench ? 'Aucun sous-objectif n\'a encore été ajouté. Cliquez sur "Ajouter" pour en créer un.' : 'No sub-objectives have been added yet. Click "Add" to create one.' }}
                                    @else
                                        {{ $isFrench ? 'Aucun sous-objectif n\'a été défini pour cet objectif.' : 'No sub-objectives have been defined for this objective.' }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal with mobile-first design -->
@if($objective->sector === 'boulangerie-patisserie' && !$objective->is_confirmed)
    <div id="add-sub-objective-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Ajouter un sous-objectif' : 'Add Sub-objective' }}
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 p-1" 
                    onclick="document.getElementById('add-sub-objective-modal').classList.add('hidden')">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>
            
            <form action="{{ route('objectives.sub-objectives.store', $objective->id) }}" method="POST" id="subObjectiveForm">
                @csrf
                <input type="hidden" name="sub_objective_id" id="sub_objective_id">
                
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Titre du sous-objectif*' : 'Sub-objective Title*' }}
                    </label>
                    <input type="text" name="title" id="title" required 
                        class="mobile-input w-full rounded-2xl md:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                
                <div class="mb-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Produit associé (optionnel)' : 'Associated Product (optional)' }}
                    </label>
                    <select name="product_id" id="product_id" 
                        class="mobile-input w-full rounded-2xl md:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">-- {{ $isFrench ? 'Aucun produit spécifique' : 'No specific product' }} --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->code_produit }}">
                                {{ $product->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Montant cible (FCFA)*' : 'Target Amount (FCFA)*' }}
                    </label>
                    <input type="number" name="target_amount" id="target_amount" required min="1" step="1"
                        class="mobile-input w-full rounded-2xl md:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        max="{{ $objective->subObjectivesRemainingAllocation }}">
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $isFrench ? 'Maximum disponible' : 'Maximum available' }}: {{ number_format($objective->subObjectivesRemainingAllocation, 0, ',', ' ') }} FCFA
                    </p>
                </div>
                
                <div class="flex flex-col md:flex-row justify-end gap-3">
                    <button type="button" 
                        class="mobile-btn px-4 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        onclick="document.getElementById('add-sub-objective-modal').classList.add('hidden')">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit" 
                        class="mobile-btn px-4 py-3 md:py-2 bg-blue-600 border border-transparent rounded-2xl md:rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $isFrench ? 'Ajouter' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart data preparation
        const progressData = @json($progressHistory);
        
        const dates = progressData.map(item => item.date);
        const amounts = progressData.map(item => item.amount);
        const percentages = progressData.map(item => item.percentage);
        
        // Chart configuration
        const options = {
            series: [{
                name: '{{ $isFrench ? "Montant" : "Amount" }}',
                type: 'column',
                data: amounts
            }, {
                name: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}',
                type: 'line',
                data: percentages
            }],
            chart: {
                height: 250,
                type: 'line',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            stroke: {
                width: [0, 3],
                curve: 'smooth'
            },
            title: {
                text: '{{ $isFrench ? "Évolution de la progression" : "Progress Evolution" }}',
                align: 'left',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#263238'
                }
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: dates,
            xaxis: {
                type: 'datetime'
            },
            yaxis: [{
                title: {
                    text: '{{ $isFrench ? "Montant (FCFA)" : "Amount (FCFA)" }}',
                },
            }, {
                opposite: true,
                title: {
                    text: '{{ $isFrench ? "Progression (%)" : "Progress (%)" }}'
                },
                min: 0,
                max: 100
            }],
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const chart = new ApexCharts(document.querySelector("#progressChart"), options);
        chart.render();
        
        // Edit sub-objective function
        window.editSubObjective = function(id, title, productId, targetAmount) {
            document.getElementById('sub_objective_id').value = id;
            document.getElementById('title').value = title;
            document.getElementById('product_id').value = productId || '';
            document.getElementById('target_amount').value = targetAmount;
            
            const form = document.getElementById('subObjectiveForm');
            form.action = "{{ route('objectives.sub-objectives.update', ['objective' => $objective->id, 'subObjective' => '_id_']) }}".replace('_id_', id);
            
            if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }
            
            document.getElementById('add-sub-objective-modal').classList.remove('hidden');
        }

        // Add touch feedback animations
        document.querySelectorAll('.mobile-card').forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
@endsection
