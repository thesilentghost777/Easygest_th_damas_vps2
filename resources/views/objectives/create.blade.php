@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-4 sm:py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="px-4 py-4 sm:px-6 sm:py-6">
                    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                            {{ $isFrench ? 'Créer un nouvel objectif' : 'Create New Objective' }}
                        </h1>
                        <a href="{{ route('objectives.index') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            {{ $isFrench ? 'Retour à la liste' : 'Back to List' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">
                        {{ $isFrench ? 'Erreurs détectées' : 'Errors Detected' }}
                    </h3>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Main Form -->
            <form action="{{ route('objectives.store') }}" method="POST" id="objectiveForm" class="space-y-6">
                @csrf
                
                <!-- General Information Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-blue-50 px-4 py-3 sm:px-6 border-b border-blue-100">
                        <h2 class="text-lg font-semibold text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isFrench ? 'Informations générales' : 'General Information' }}
                        </h2>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Titre de l\'objectif' : 'Objective Title' }}*
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    placeholder="{{ $isFrench ? 'Entrez le titre de votre objectif' : 'Enter your objective title' }}"
                                    required>
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Montant cible (FCFA)' : 'Target Amount (FCFA)' }}*
                                </label>
                                <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    min="1" step="1" 
                                    placeholder="{{ $isFrench ? 'Ex: 100000' : 'Ex: 100000' }}"
                                    required>
                                @error('target_amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Description (optionnelle)' : 'Description (optional)' }}
                            </label>
                            <textarea name="description" id="description" rows="3" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200 resize-none"
                                placeholder="{{ $isFrench ? 'Décrivez votre objectif...' : 'Describe your objective...' }}">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Type and Period Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-indigo-50 px-4 py-3 sm:px-6 border-b border-indigo-100">
                        <h2 class="text-lg font-semibold text-indigo-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $isFrench ? 'Type et période' : 'Type and Period' }}
                        </h2>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="sector" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Secteur concerné' : 'Related Sector' }}*
                                </label>
                                <select name="sector" id="sector"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    required>
                                    <option value="">{{ $isFrench ? '-- Sélectionner un secteur --' : '-- Select a sector --' }}</option>
                                    <option value="alimentation" {{ old('sector') === 'alimentation' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Alimentation' : 'Food' }}
                                    </option>
                                    <option value="boulangerie-patisserie" {{ old('sector') === 'boulangerie-patisserie' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Boulangerie-Pâtisserie' : 'Bakery-Pastry' }}
                                    </option>
                                    <option value="glace" {{ old('sector') === 'glace' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Glaces' : 'Ice Cream' }}
                                    </option>
                                    <option value="global" {{ old('sector') === 'global' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Global (Toute entreprise)' : 'Global (All Business)' }}
                                    </option>
                                </select>
                                @error('sector')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="goal_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Type d\'objectif' : 'Objective Type' }}*
                                </label>
                                <select name="goal_type" id="goal_type" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    required>
                                    <option value="">{{ $isFrench ? '-- Sélectionner un type --' : '-- Select a type --' }}</option>
                                    <option value="revenue" {{ old('goal_type') === 'revenue' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}
                                    </option>
                                    <option value="profit" {{ old('goal_type') === 'profit' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                                    </option>
                                </select>
                                @error('goal_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="period_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Type de période' : 'Period Type' }}*
                                </label>
                                <select name="period_type" id="period_type"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    required>
                                    <option value="">{{ $isFrench ? '-- Sélectionner une période --' : '-- Select a period --' }}</option>
                                    <option value="daily" {{ old('period_type') === 'daily' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Journalier' : 'Daily' }}
                                    </option>
                                    <option value="weekly" {{ old('period_type') === 'weekly' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Hebdomadaire' : 'Weekly' }}
                                    </option>
                                    <option value="monthly" {{ old('period_type') === 'monthly' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Mensuel' : 'Monthly' }}
                                    </option>
                                    <option value="yearly" {{ old('period_type') === 'yearly' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Annuel' : 'Yearly' }}
                                    </option>
                                </select>
                                @error('period_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Catégories de dépenses associées' : 'Associated Expense Categories' }}
                                </label>
                                <div class="border border-gray-300 rounded-lg shadow-sm p-3 max-h-40 overflow-y-auto bg-white">
                                    @foreach($expenseCategories as $category)
                                        <div class="flex items-center py-1">
                                            <input type="checkbox" name="expense_categories[]" id="expense_category_{{ $category->id }}" 
                                                value="{{ $category->id }}" 
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                                {{ in_array($category->id, old('expense_categories', [])) ? 'checked' : '' }}>
                                            <label for="expense_category_{{ $category->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $isFrench ? 'Si définies, seules les dépenses de ces catégories seront comptabilisées.' : 'If defined, only expenses from these categories will be counted.' }}
                                </p>
                                @error('expense_categories')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Objective Period Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-green-50 px-4 py-3 sm:px-6 border-b border-green-100">
                        <h2 class="text-lg font-semibold text-green-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $isFrench ? 'Période de l\'objectif' : 'Objective Period' }}
                        </h2>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Date de début' : 'Start Date' }}*
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="date" name="start_date" id="start_date"
                                        min="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                        value="{{ old('start_date', date('Y-m-d')) }}" 
                                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                        required>
                                    <button type="button" id="todayButton"
                                        class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 whitespace-nowrap">
                                        {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                                    </button>
                                </div>
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Date de fin' : 'End Date' }}*
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200" 
                                    required>
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $isFrench ? 'Calculée automatiquement selon la période choisie, mais peut être modifiée.' : 'Automatically calculated based on the chosen period, but can be modified.' }}
                                </p>
                                @error('end_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Sources Configuration Section -->
                <div id="sourceConfig" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
                    <div class="bg-amber-50 px-4 py-3 sm:px-6 border-b border-amber-100">
                        <h2 class="text-lg font-semibold text-amber-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                            {{ $isFrench ? 'Configuration des sources de données' : 'Data Sources Configuration' }}
                        </h2>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">
                                <strong>{{ $isFrench ? 'Sources de données standard pour ce secteur :' : 'Standard data sources for this sector:' }}</strong>
                            </p>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                                <div id="alimentation-sources" class="sector-sources" style="display: none;">
                                    <p class="text-sm text-gray-700">
                                        {{ $isFrench ? 'Les entrées sont calculées à partir des versements effectués par les caissier(ère)s (personnel ayant le rôle "caissiere").' : 'Entries are calculated from deposits made by cashiers (staff with "caissiere" role).' }}
                                    </p>
                                </div>
                                <div id="boulangerie-sources" class="sector-sources" style="display: none;">
                                    <p class="text-sm text-gray-700">
                                        {{ $isFrench ? 'Les entrées sont calculées à partir des versements effectués par les chefs de production (rôle "chef_production") et les vendeurs (secteur "vente").' : 'Entries are calculated from deposits made by production managers ("chef_production" role) and sellers ("vente" sector).' }}
                                    </p>
                                </div>
                                <div id="glace-sources" class="sector-sources" style="display: none;">
                                    <p class="text-sm text-gray-700">
                                        {{ $isFrench ? 'Les entrées sont calculées à partir des versements effectués par les responsables glace (personnel ayant le rôle "glace").' : 'Entries are calculated from deposits made by ice cream managers (staff with "glace" role).' }}
                                    </p>
                                </div>
                                <div id="global-sources" class="sector-sources" style="display: none;">
                                    <p class="text-sm text-gray-700">
                                        {{ $isFrench ? 'Les entrées sont calculées à partir de toutes les transactions de type "income" (entrée d\'argent) dans le système.' : 'Entries are calculated from all "income" type transactions in the system.' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" name="use_standard_sources" id="use_standard_true" value="1" 
                                        checked
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="use_standard_true" class="ml-3 block text-sm text-gray-700">
                                        {{ $isFrench ? 'Utiliser les sources standard' : 'Use standard sources' }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="use_standard_sources" id="use_standard_false" value="0" 
                                        {{ old('use_standard_sources') === '0' ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="use_standard_false" class="ml-3 block text-sm text-gray-700">
                                        {{ $isFrench ? 'Personnaliser les sources' : 'Customize sources' }}
                                    </label>
                                </div>
                            </div>
                            
                            <div id="custom-sources" class="bg-amber-50 p-4 rounded-lg border border-amber-200 mt-4" style="display: none;">
                                <p class="text-sm font-medium mb-3">
                                    {{ $isFrench ? 'Configurer les sources personnalisées :' : 'Configure custom sources:' }}
                                </p>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Utilisateurs responsables des versements' : 'Users responsible for deposits' }}
                                        </label>
                                        <div class="border border-gray-300 rounded-lg shadow-sm p-3 max-h-40 overflow-y-auto bg-white">
                                            @foreach(\App\Models\User::all() as $user)
                                                <div class="flex items-center py-1">
                                                    <input type="checkbox" name="custom_users[]" id="custom_user_{{ $user->id }}" 
                                                        value="{{ $user->id }}" 
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                                        {{ in_array($user->id, old('custom_users', [])) ? 'checked' : '' }}>
                                                    <label for="custom_user_{{ $user->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                                        {{ $user->name }} ({{ $user->role }})
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $isFrench ? 'Catégories de transactions entrantes' : 'Incoming transaction categories' }}
                                        </label>
                                        <div class="border border-gray-300 rounded-lg shadow-sm p-3 max-h-40 overflow-y-auto bg-white">
                                            @foreach($expenseCategories as $category)
                                                <div class="flex items-center py-1">
                                                    <input type="checkbox" name="custom_categories[]" id="custom_category_{{ $category->id }}" 
                                                        value="{{ $category->id }}" 
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                                        {{ in_array($category->id, old('custom_categories', [])) ? 'checked' : '' }}>
                                                    <label for="custom_category_{{ $category->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 p-3 bg-amber-100 rounded-lg">
                                    <p class="text-xs text-amber-800">
                                        <strong>{{ $isFrench ? 'Note:' : 'Note:' }}</strong> 
                                        {{ $isFrench ? 'Si vous ne sélectionnez aucun utilisateur ou catégorie, aucune source de données ne sera associée à cet objectif.' : 'If you do not select any user or category, no data source will be associated with this objective.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 py-4 sm:px-6">
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $isFrench ? 'Créer l\'objectif' : 'Create Objective' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile-optimized JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectorSelect = document.getElementById('sector');
            const periodTypeSelect = document.getElementById('period_type');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const todayButton = document.getElementById('todayButton');
            const sourceConfigDiv = document.getElementById('sourceConfig');
            const customSourcesDiv = document.getElementById('custom-sources');
            const useStandardTrue = document.getElementById('use_standard_true');
            const useStandardFalse = document.getElementById('use_standard_false');
            const sectorSourceDivs = document.querySelectorAll('.sector-sources');

            // Mobile-friendly date handling
            function updateEndDate() {
                if (!startDateInput.value || !periodTypeSelect.value) return;
                
                const start = new Date(startDateInput.value);
                let end = new Date(start);
                
                switch (periodTypeSelect.value) {
                    case 'daily':
                        // End date is the same as start date for daily objectives
                        break;
                    case 'weekly':
                        end.setDate(start.getDate() + 6); // 7 days total (including start day)
                        break;
                    case 'monthly':
                        end.setMonth(start.getMonth() + 1);
                        end.setDate(start.getDate() - 1);
                        break;
                    case 'yearly':
                        end.setFullYear(start.getFullYear() + 1);
                        end.setDate(start.getDate() - 1);
                        break;
                }
                
                const year = end.getFullYear();
                const month = String(end.getMonth() + 1).padStart(2, '0');
                const day = String(end.getDate()).padStart(2, '0');
                endDateInput.value = `${year}-${month}-${day}`;
            }

            // Update source configuration visibility
            function updateSourceConfig() {
                const selectedSector = sectorSelect.value;
                
                if (selectedSector) {
                    sourceConfigDiv.style.display = 'block';
                    
                    // Hide all sector source descriptions
                    sectorSourceDivs.forEach(div => {
                        div.style.display = 'none';
                    });
                    
                    // Show the selected sector source description
                    const selectedSectorDiv = document.getElementById(selectedSector + '-sources');
                    if (selectedSectorDiv) {
                        selectedSectorDiv.style.display = 'block';
                    }
                } else {
                    sourceConfigDiv.style.display = 'none';
                }
            }

            // Update custom sources section visibility
            function updateCustomSources() {
                if (useStandardFalse && useStandardFalse.checked) {
                    customSourcesDiv.style.display = 'block';
                } else {
                    customSourcesDiv.style.display = 'none';
                }
            }

            // Mobile-optimized event listeners
            periodTypeSelect.addEventListener('change', updateEndDate);
            startDateInput.addEventListener('change', updateEndDate);
            
            todayButton.addEventListener('click', function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                startDateInput.value = `${year}-${month}-${day}`;
                updateEndDate();
            });
            
            sectorSelect.addEventListener('change', updateSourceConfig);
            
            if (useStandardTrue) {
                useStandardTrue.addEventListener('change', updateCustomSources);
            }
            if (useStandardFalse) {
                useStandardFalse.addEventListener('change', updateCustomSources);
            }

            // Mobile form validation feedback
            const form = document.getElementById('objectiveForm');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $isFrench ? 'Création en cours...' : 'Creating...' }}
                `;
                submitButton.disabled = true;
            });

            // Touch-friendly checkbox interactions
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Add visual feedback for mobile
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    if (label) {
                        if (this.checked) {
                            label.classList.add('text-blue-600', 'font-medium');
                        } else {
                            label.classList.remove('text-blue-600', 'font-medium');
                        }
                    }
                });
            });

            // Mobile-optimized select interactions
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('focus', function() {
                    // Scroll to select on mobile for better UX
                    if (window.innerWidth < 768) {
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                });
            });

            // Prevent zoom on input focus for iOS
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth < 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        if (viewport) {
                            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                        }
                    }
                });
                
                input.addEventListener('blur', function() {
                    if (window.innerWidth < 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        if (viewport) {
                            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                        }
                    }
                });
            });
            
            // Initialize the form
            updateEndDate();
            updateSourceConfig();
            updateCustomSources();

            // Handle back button for mobile
            if (window.innerWidth < 768) {
                const backButton = document.querySelector('a[href*="objectives.index"]');
                if (backButton) {
                    backButton.addEventListener('click', function(e) {
                        // Add loading state for mobile
                        this.innerHTML = `
                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $isFrench ? 'Retour...' : 'Back...' }}
                        `;
                    });
                }
            }
        });

        // Handle window resize for responsive behavior
        window.addEventListener('resize', function() {
            const viewport = document.querySelector('meta[name="viewport"]');
            if (viewport && window.innerWidth >= 768) {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
            }
        });
    </script>

    <!-- Add mobile-specific styles -->
    <style>
        /* Mobile-first responsive improvements */
        @media (max-width: 767px) {
            .sticky {
                position: -webkit-sticky;
                position: sticky;
            }
            
            /* Improve touch targets */
            input[type="checkbox"], input[type="radio"] {
                min-width: 20px;
                min-height: 20px;
            }
            
            /* Better mobile form spacing */
            .space-y-4 > * + * {
                margin-top: 1.5rem;
            }
            
            /* Optimize select dropdowns for mobile */
            select {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 12px;
            }
            
            input[type="text"], input[type="number"], input[type="date"], textarea {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 12px;
            }
            
            /* Better mobile button spacing */
            .grid.grid-cols-1.lg\\:grid-cols-2 {
                gap: 1.5rem;
            }
            
            /* Improve mobile scrollable areas */
            .max-h-40.overflow-y-auto {
                max-height: 120px;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Tablet optimizations */
        @media (min-width: 768px) and (max-width: 1023px) {
            .max-w-4xl {
                max-width: 100%;
                padding: 0 2rem;
            }
        }
        
        /* Loading state styles */
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Enhanced focus states for accessibility */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Better mobile section headers */
        @media (max-width: 767px) {
            .bg-blue-50, .bg-indigo-50, .bg-green-50, .bg-amber-50 {
                padding: 1rem;
            }
            
            .text-lg.font-semibold {
                font-size: 1rem;
                line-height: 1.5;
            }
        }
    </style>
@endsection