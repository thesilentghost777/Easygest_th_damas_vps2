@extends('layouts.app')

@section('content')
<div class="py-6 lg:py-12 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl lg:rounded-lg p-4 lg:p-6">
            <!-- Mobile Header -->
            <div class="mb-6 md:hidden">
                <h2 class="text-xl font-bold text-gray-800 mb-4 animate-fade-in">
                    @if($isFrench)
                        Modifier l'objectif
                    @else
                        Edit Objective
                    @endif
                </h2>
                
                @include('buttons')
                
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <a href="{{ route('objectives.show', $objective->id) }}" class="mobile-action-btn bg-gray-100 rounded-2xl p-3 text-center hover:bg-gray-200 transition-all duration-200">
                        <svg class="w-5 h-5 mx-auto mb-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700">
                            @if($isFrench)
                                Voir
                            @else
                                View
                            @endif
                        </span>
                    </a>
                    <a href="{{ route('objectives.index') }}" class="mobile-action-btn bg-gray-100 rounded-2xl p-3 text-center hover:bg-gray-200 transition-all duration-200">
                        <svg class="w-5 h-5 mx-auto mb-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700">
                            @if($isFrench)
                                Liste
                            @else
                                List
                            @endif
                        </span>
                    </a>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    @if($isFrench)
                        Modifier l'objectif
                    @else
                        Edit Objective
                    @endif
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('objectives.show', $objective->id) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300 transition-colors">
                        @if($isFrench)
                            Voir les détails
                        @else
                            View Details
                        @endif
                    </a>
                    <a href="{{ route('objectives.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300 transition-colors">
                        @if($isFrench)
                            Retour à la liste
                        @else
                            Back to List
                        @endif
                    </a>
                </div>
            </div>

            <form action="{{ route('objectives.update', $objective->id) }}" method="POST" class="mobile-form" 
                x-data="{ 
                    periodType: '{{ old('period_type', $objective->period_type) }}', 
                    startDate: '{{ old('start_date', $objective->start_date->format('Y-m-d')) }}',
                    sector: '{{ old('sector', $objective->sector) }}',
                    useStandard: {{ old('use_standard_sources', $objective->use_standard_sources) ? 'true' : 'false' }},
                    updateEndDate() {
                        if (!this.startDate || !this.periodType) return;
                        
                        const start = new Date(this.startDate);
                        let end = new Date(start);
                        
                        if (this.periodType === 'daily') {
                            end.setDate(start.getDate());
                        } else if (this.periodType === 'weekly') {
                            end.setDate(start.getDate() + 6);
                        } else if (this.periodType === 'monthly') {
                            end.setMonth(start.getMonth() + 1);
                            end.setDate(start.getDate() - 1);
                        } else if (this.periodType === 'yearly') {
                            end.setFullYear(start.getFullYear() + 1);
                            end.setDate(start.getDate() - 1);
                        }
                        
                        const endDateInput = document.getElementById('end_date');
                        const year = end.getFullYear();
                        const month = String(end.getMonth() + 1).padStart(2, '0');
                        const day = String(end.getDate()).padStart(2, '0');
                        endDateInput.value = `${year}-${month}-${day}`;
                    }
                }">
                @csrf
                @method('PUT')
                
                <!-- Informations générales -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl lg:rounded-lg p-4 mobile-section">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">
                        @if($isFrench)
                            Informations générales
                        @else
                            General Information
                        @endif
                    </h3>
                    
                    <div class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                        <div class="mobile-input-group">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Titre de l'objectif*
                                @else
                                    Objective Title*
                                @endif
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $objective->title) }}" 
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mobile-input-group">
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Montant cible (FCFA)*
                                @else
                                    Target Amount (FCFA)*
                                @endif
                            </label>
                            <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $objective->target_amount) }}" 
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                min="1" step="1" required>
                            @error('target_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($isFrench)
                                Description (optionnelle)
                            @else
                                Description (optional)
                            @endif
                        </label>
                        <textarea name="description" id="description" rows="3" 
                            class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2">{{ old('description', $objective->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Type et période -->
                <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-2xl lg:rounded-lg p-4 mobile-section">
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4">
                        @if($isFrench)
                            Type et période
                        @else
                            Type and Period
                        @endif
                    </h3>
                    
                    <div class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                        <div class="mobile-input-group">
                            <label for="sector" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Secteur concerné*
                                @else
                                    Concerned Sector*
                                @endif
                            </label>
                            <select name="sector" id="sector" x-model="sector"
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                required>
                                <option value="">
                                    @if($isFrench)
                                        -- Sélectionner un secteur --
                                    @else
                                        -- Select a sector --
                                    @endif
                                </option>
                                <option value="alimentation" {{ old('sector', $objective->sector) === 'alimentation' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Alimentation
                                    @else
                                        General store
                                    @endif
                                </option>
                                <option value="boulangerie-patisserie" {{ old('sector', $objective->sector) === 'boulangerie-patisserie' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Boulangerie-Pâtisserie
                                    @else
                                        Bakery-Pastry
                                    @endif
                                </option>
                                <option value="glace" {{ old('sector', $objective->sector) === 'glace' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Glaces
                                    @else
                                        Ice Cream
                                    @endif
                                </option>
                                <option value="global" {{ old('sector', $objective->sector) === 'global' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Global (Toute entreprise)
                                    @else
                                        Global (All business)
                                    @endif
                                </option>
                            </select>
                            @error('sector')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mobile-input-group">
                            <label for="goal_type" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Type d'objectif*
                                @else
                                    Objective Type*
                                @endif
                            </label>
                            <select name="goal_type" id="goal_type" 
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                required>
                                <option value="">
                                    @if($isFrench)
                                        -- Sélectionner un type --
                                    @else
                                        -- Select a type --
                                    @endif
                                </option>
                                <option value="revenue" {{ old('goal_type', $objective->goal_type) === 'revenue' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Chiffre d'affaires
                                    @else
                                        Revenue
                                    @endif
                                </option>
                                <option value="profit" {{ old('goal_type', $objective->goal_type) === 'profit' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Bénéfice
                                    @else
                                        Profit
                                    @endif
                                </option>
                            </select>
                            @error('goal_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0 mt-4">
                        <div class="mobile-input-group">
                            <label for="period_type" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Type de période*
                                @else
                                    Period Type*
                                @endif
                            </label>
                            <select name="period_type" id="period_type" x-model="periodType" @change="updateEndDate()"
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                required>
                                <option value="">
                                    @if($isFrench)
                                        -- Sélectionner une période --
                                    @else
                                        -- Select a period --
                                    @endif
                                </option>
                                <option value="daily" {{ old('period_type', $objective->period_type) === 'daily' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Journalier
                                    @else
                                        Daily
                                    @endif
                                </option>
                                <option value="weekly" {{ old('period_type', $objective->period_type) === 'weekly' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Hebdomadaire
                                    @else
                                        Weekly
                                    @endif
                                </option>
                                <option value="monthly" {{ old('period_type', $objective->period_type) === 'monthly' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Mensuel
                                    @else
                                        Monthly
                                    @endif
                                </option>
                                <option value="yearly" {{ old('period_type', $objective->period_type) === 'yearly' ? 'selected' : '' }}>
                                    @if($isFrench)
                                        Annuel
                                    @else
                                        Yearly
                                    @endif
                                </option>
                            </select>
                            @error('period_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mobile-input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Catégories de dépenses associées
                                @else
                                    Associated Expense Categories
                                @endif
                            </label>
                            <div class="border border-gray-300 rounded-xl lg:rounded-md shadow-sm p-3 max-h-40 overflow-y-auto bg-white">
                                @foreach($expenseCategories as $category)
                                    <div class="flex items-center mb-2">
                                        <input type="checkbox" name="expense_categories[]" id="expense_category_{{ $category->id }}" 
                                            value="{{ $category->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                            {{ in_array($category->id, old('expense_categories', $objective->expense_categories ?? [])) ? 'checked' : '' }}>
                                        <label for="expense_category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                @if($isFrench)
                                    Si définies, seules les dépenses de ces catégories seront comptabilisées.
                                @else
                                    If defined, only expenses from these categories will be counted.
                                @endif
                            </p>
                            @error('expense_categories')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Période de l'objectif -->
                <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl lg:rounded-lg p-4 mobile-section">
                    <h3 class="text-lg font-semibold text-green-700 mb-4">
                        @if($isFrench)
                            Période de l'objectif
                        @else
                            Objective Period
                        @endif
                    </h3>
                    
                    <div class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                        <div class="mobile-input-group">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Date de début*
                                @else
                                    Start Date*
                                @endif
                            </label>
                            <div class="flex">
                                <input type="date" name="start_date" id="start_date" x-model="startDate" @change="updateEndDate()"
                                    min="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                    value="{{ old('start_date', $objective->start_date->format('Y-m-d')) }}" 
                                    class="flex-1 rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                    required>
                                <button type="button" @click="startDate = '{{ date('Y-m-d') }}'; updateEndDate()" 
                                    class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-xl lg:rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    @if($isFrench)
                                        Aujourd'hui
                                    @else
                                        Today
                                    @endif
                                </button>
                            </div>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mobile-input-group">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($isFrench)
                                    Date de fin*
                                @else
                                    End Date*
                                @endif
                            </label>
                            <input type="date" name="end_date" id="end_date" 
                                value="{{ old('end_date', $objective->end_date->format('Y-m-d')) }}" 
                                min="{{ date('Y-m-d') }}"
                                class="w-full rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-3 lg:p-2" 
                                required>
                            <p class="text-xs text-gray-500 mt-2">
                                @if($isFrench)
                                    Calculée automatiquement selon la période choisie, mais peut être modifiée.
                                @else
                                    Automatically calculated based on selected period, but can be modified.
                                @endif
                            </p>
                            @error('end_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuration des sources de données -->
                <div x-show="sector" class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl lg:rounded-lg p-4 mobile-section">
                    <h3 class="text-lg font-semibold text-amber-700 mb-4">
                        @if($isFrench)
                            Configuration des sources de données
                        @else
                            Data Sources Configuration
                        @endif
                    </h3>
                    
                    <div class="mb-4" x-show="sector">
                        <p class="text-sm text-gray-700 mb-2">
                            @if($isFrench)
                                <strong>Sources de données standard pour ce secteur :</strong>
                            @else
                                <strong>Standard data sources for this sector:</strong>
                            @endif
                        </p>
                        <div class="bg-white p-3 rounded-xl lg:rounded-md border border-amber-100 mb-3">
                            <template x-if="sector === 'alimentation'">
                                <p class="text-sm">
                                    @if($isFrench)
                                        Les entrées sont calculées à partir des versements effectués par les caissier(ère)s (personnel ayant le rôle "caissiere").
                                    @else
                                        Entries are calculated from payments made by cashiers (staff with "caissiere" role).
                                    @endif
                                </p>
                            </template>
                            <template x-if="sector === 'boulangerie-patisserie'">
                                <p class="text-sm">
                                    @if($isFrench)
                                        Les entrées sont calculées à partir des versements effectués par les chefs de production (rôle "chef_production") et les vendeurs (secteur "vente").
                                    @else
                                        Entries are calculated from payments made by production managers ("chef_production" role) and sellers ("vente" sector).
                                    @endif
                                </p>
                            </template>
                            <template x-if="sector === 'glace'">
                                <p class="text-sm">
                                    @if($isFrench)
                                        Les entrées sont calculées à partir des versements effectués par les responsables glace (personnel ayant le rôle "glace").
                                    @else
                                        Entries are calculated from payments made by ice cream managers (staff with "glace" role).
                                    @endif
                                </p>
                            </template>
                            <template x-if="sector === 'global'">
                                <p class="text-sm">
                                    @if($isFrench)
                                        Les entrées sont calculées à partir de toutes les transactions de type "income" (entrée d'argent) dans le système.
                                    @else
                                        Entries are calculated from all "income" type transactions in the system.
                                    @endif
                                </p>
                            </template>
                        </div>
                        
                        <div class="space-y-3 lg:flex lg:items-center lg:space-x-4 lg:space-y-0 mb-4">
                            <div class="flex items-center">
                                <input type="radio" name="use_standard_sources" id="use_standard_true" value="1" 
                                    checked
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="use_standard_true" class="ml-2 block text-sm text-gray-700">
                                    @if($isFrench)
                                        Utiliser les sources standard
                                    @else
                                        Use standard sources
                                    @endif
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="use_standard_sources" id="use_standard_false" value="0" 
                                    {{ old('use_standard_sources') === '0' ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="use_standard_false" class="ml-2 block text-sm text-gray-700">
                                    @if($isFrench)
                                        Personnaliser les sources
                                    @else
                                        Customize sources
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div x-show="!useStandard" class="bg-amber-50 p-3 rounded-xl lg:rounded-md mb-2">
                            <p class="text-sm font-medium mb-2">
                                @if($isFrench)
                                    Configurer les sources personnalisées :
                                @else
                                    Configure custom sources:
                                @endif
                            </p>
                            
                            <div class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">
                                        @if($isFrench)
                                            Utilisateurs responsables des versements
                                        @else
                                            Users responsible for payments
                                        @endif
                                    </label>
                                    <div class="border border-gray-300 rounded-xl lg:rounded-md shadow-sm p-2 max-h-40 overflow-y-auto bg-white">
                                        @foreach(\App\Models\User::all() as $user)
                                            <div class="flex items-center mb-1">
                                                <input type="checkbox" name="custom_users[]" id="custom_user_{{ $user->id }}" 
                                                    value="{{ $user->id }}" 
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                                    {{ in_array($user->id, old('custom_users', $objective->custom_users ?? [])) ? 'checked' : '' }}>
                                                <label for="custom_user_{{ $user->id }}" class="ml-2 text-sm text-gray-700">
                                                    {{ $user->name }} ({{ $user->role }})
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">
                                        @if($isFrench)
                                            Catégories de transactions entrantes
                                        @else
                                            Incoming transaction categories
                                        @endif
                                    </label>
                                    <div class="border border-gray-300 rounded-xl lg:rounded-md shadow-sm p-2 max-h-40 overflow-y-auto bg-white">
                                        @foreach($expenseCategories as $category)
                                            <div class="flex items-center mb-1">
                                                <input type="checkbox" name="custom_categories[]" id="custom_category_{{ $category->id }}" 
                                                    value="{{ $category->id }}" 
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                                    {{ in_array($category->id, old('custom_categories', $objective->custom_categories ?? [])) ? 'checked' : '' }}>
                                                <label for="custom_category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-xs text-amber-600 mt-2">
                                @if($isFrench)
                                    <strong>Note:</strong> Si vous ne sélectionnez aucun utilisateur ou catégorie, aucune source de données ne sera associée à cet objectif.
                                @else
                                    <strong>Note:</strong> If you don't select any user or category, no data source will be associated with this objective.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col lg:flex-row lg:justify-between gap-4 mt-6">
                    <button type="button" onclick="history.back()" class="w-full lg:w-auto px-6 py-4 lg:py-3 bg-gray-300 text-gray-700 rounded-2xl lg:rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors font-medium">
                        @if($isFrench)
                            Annuler
                        @else
                            Cancel
                        @endif
                    </button>
                    
                    <button type="submit" class="w-full lg:w-auto px-6 py-4 lg:py-3 bg-blue-600 text-white rounded-2xl lg:rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium mobile-submit-btn">
                        @if($isFrench)
                            Mettre à jour l'objectif
                        @else
                            Update Objective
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Mobile-first animations and styles */
@media (max-width: 768px) {
    .mobile-form {
        animation: slideInUp 0.6s ease-out;
    }
    
    .mobile-section {
        animation: fadeInUp 0.6s ease-out;
        transform-origin: bottom;
    }
    
    .mobile-section:nth-child(1) { animation-delay: 0.1s; }
    .mobile-section:nth-child(2) { animation-delay: 0.2s; }
    .mobile-section:nth-child(3) { animation-delay: 0.3s; }
    .mobile-section:nth-child(4) { animation-delay: 0.4s; }
    
    .mobile-input-group {
        animation: slideInLeft 0.5s ease-out;
    }
    
    .mobile-input-group:nth-child(1) { animation-delay: 0.1s; }
    .mobile-input-group:nth-child(2) { animation-delay: 0.2s; }
    .mobile-input-group:nth-child(3) { animation-delay: 0.3s; }
    .mobile-input-group:nth-child(4) { animation-delay: 0.4s; }
    
    .mobile-action-btn {
        animation: bounceIn 0.6s ease-out;
        transform-origin: center;
    }
    
    .mobile-action-btn:nth-child(1) { animation-delay: 0.1s; }
    .mobile-action-btn:nth-child(2) { animation-delay: 0.2s; }
    
    .mobile-action-btn:active {
        transform: scale(0.95);
    }
    
    .mobile-submit-btn {
        animation: pulse 0.8s ease-out;
    }
    
    .mobile-submit-btn:active {
        transform: scale(0.98);
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

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
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
