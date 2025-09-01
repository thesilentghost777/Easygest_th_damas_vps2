@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-6 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-blue-100/50 p-6 sm:p-8 mb-8 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Modifier la dépense' : 'Edit Expense' }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Connecté en tant que:' : 'Connected as:' }} 
                            <span class="font-semibold text-blue-600">{{ $nom }}</span> 
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-sm ml-2">{{ $role }}</span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('depenses.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ $isFrench ?? 'fr' == 'fr' ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-6 mb-8 animate-fade-in">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-red-800 font-semibold">
                        {{ $isFrench ?? 'fr' == 'fr' ? 'Erreurs de validation' : 'Validation Errors' }}
                    </h3>
                </div>
                <ul class="space-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-700 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-6 mb-8 animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-blue-100/50 overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 sm:px-8 py-6">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $isFrench ?? 'fr' == 'fr' ? 'Informations de la dépense' : 'Expense Information' }}
                </h2>
            </div>

            <form action="{{ route('depenses.update', $depense->id) }}" method="POST" class="p-6 sm:p-8">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Expense Name -->
                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-semibold text-gray-700">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Nom de la dépense' : 'Expense Name' }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $depense->nom) }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                   placeholder="{{ $isFrench ?? 'fr' == 'fr' ? 'Saisissez le nom de la dépense' : 'Enter expense name' }}"
                                   required 
                                   maxlength="255">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('nom')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Expense Type -->
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-gray-700">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Type de dépense' : 'Expense Type' }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select id="type" 
                                    name="type" 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror appearance-none bg-white"
                                    required>
                                <option value="">{{ $isFrench ?? 'fr' == 'fr' ? 'Sélectionnez un type' : 'Select a type' }}</option>
                                <option value="achat_matiere" {{ old('type', $depense->type) == 'achat_matiere' ? 'selected' : '' }}>
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Achat matière' : 'Material Purchase' }}
                                </option>
                                <option value="livraison_matiere" {{ old('type', $depense->type) == 'livraison_matiere' ? 'selected' : '' }}>
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Livraison matière' : 'Material Delivery' }}
                                </option>
                                <option value="reparation" {{ old('type', $depense->type) == 'reparation' ? 'selected' : '' }}>
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Réparation' : 'Repair' }}
                                </option>
                                <option value="depense_fiscale" {{ old('type', $depense->type) == 'depense_fiscale' ? 'selected' : '' }}>
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Dépense fiscale' : 'Tax Expense' }}
                                </option>
                                <option value="autre" {{ old('type', $depense->type) == 'autre' ? 'selected' : '' }}>
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Autre' : 'Other' }}
                                </option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-semibold text-gray-700">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Date' : 'Date' }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $depense->date) }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('date') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <label for="prix" class="block text-sm font-semibold text-gray-700">
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Prix (€)' : 'Price (€)' }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="prix" 
                                   name="prix" 
                                   value="{{ old('prix', $depense->prix) }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('prix') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                   placeholder="0.00"
                                   min="0" 
                                   step="0.01" 
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        @error('prix')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Material Section (Conditional) -->
                <div id="matiere-section" 
                     class="transition-all duration-500 ease-in-out overflow-hidden {{ in_array(old('type', $depense->type), ['achat_matiere', 'livraison_matiere']) ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-8 border border-green-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $isFrench ?? 'fr' == 'fr' ? 'Informations Matière' : 'Material Information' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Material Selection -->
                            <div class="space-y-2">
                                <label for="idm" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Matière' : 'Material' }}
                                    <span class="text-red-500 ml-1 matiere-required" style="{{ in_array(old('type', $depense->type), ['achat_matiere', 'livraison_matiere']) ? '' : 'display: none;' }}">*</span>
                                </label>
                                <div class="relative">
                                    <select id="idm" 
                                            name="idm" 
                                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 @error('idm') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror appearance-none bg-white">
                                        <option value="">{{ $isFrench ?? 'fr' == 'fr' ? 'Sélectionnez une matière' : 'Select a material' }}</option>
                                        @foreach($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" {{ old('idm', $depense->idm) == $matiere->id ? 'selected' : '' }}>
                                                {{ $matiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('idm')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="space-y-2">
                                <label for="quantite" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ?? 'fr' == 'fr' ? 'Quantité' : 'Quantity' }}
                                    <span class="text-red-500 ml-1 quantite-required" style="{{ in_array(old('type', $depense->type), ['achat_matiere', 'livraison_matiere']) ? '' : 'display: none;' }}">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="quantite" 
                                           name="quantite" 
                                           value="{{ old('quantite', $depense->quantite) }}"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 @error('quantite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                           placeholder="0.00"
                                           min="0" 
                                           step="0.01">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('quantite')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 sm:justify-between">
                    <a href="{{ route('depenses.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                        <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ $isFrench ?? 'fr' == 'fr' ? 'Retour' : 'Back' }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                        <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $isFrench ?? 'fr' == 'fr' ? 'Mettre à jour' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

/* Mobile responsiveness for smaller screens */
@media (max-width: 640px) {
    .text-2xl { font-size: 1.5rem; }
    .text-3xl { font-size: 1.875rem; }
    .p-6 { padding: 1rem; }
    .p-8 { padding: 1.5rem; }
}

/* Custom scrollbar for mobile */
* {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.5) transparent;
}

*::-webkit-scrollbar {
    width: 6px;
}

*::-webkit-scrollbar-track {
    background: transparent;
}

*::-webkit-scrollbar-thumb {
    background-color: rgba(59, 130, 246, 0.5);
    border-radius: 3px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const matiereSection = document.getElementById('matiere-section');
    const matiereSelect = document.getElementById('idm');
    const quantiteInput = document.getElementById('quantite');
    const matiereRequired = document.querySelectorAll('.matiere-required');
    const quantiteRequired = document.querySelectorAll('.quantite-required');

    function toggleMatiereFields() {
        const selectedType = typeSelect.value;
        const needsMatiere = ['achat_matiere', 'livraison_matiere'].includes(selectedType);

        if (needsMatiere) {
            matiereSection.classList.remove('max-h-0', 'opacity-0');
            matiereSection.classList.add('max-h-96', 'opacity-100');
            matiereSelect.setAttribute('required', 'required');
            quantiteInput.setAttribute('required', 'required');
            matiereRequired.forEach(el => el.style.display = '');
            quantiteRequired.forEach(el => el.style.display = '');
        } else {
            matiereSection.classList.remove('max-h-96', 'opacity-100');
            matiereSection.classList.add('max-h-0', 'opacity-0');
            matiereSelect.removeAttribute('required');
            quantiteInput.removeAttribute('required');
            matiereSelect.value = '';
            quantiteInput.value = '';
            matiereRequired.forEach(el => el.style.display = 'none');
            quantiteRequired.forEach(el => el.style.display = 'none');
        }
    }

    typeSelect.addEventListener('change', toggleMatiereFields);
    
    // Initialize state on page load
    toggleMatiereFields();

    // Add loading state to submit button
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${ {{ $isFrench ?? 'fr' == 'fr' ? "'Mise à jour...'" : "'Updating...'" }} }
        `;
    });
});
</script>
@endsection