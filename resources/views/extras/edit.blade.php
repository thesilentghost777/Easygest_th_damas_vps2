@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Modifier Réglementation" : "Edit Regulation" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-8 px-4">
        <div class="container mx-auto max-w-4xl">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 lg:px-8 py-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">
                        {{ $isFrench ? "Modifier la Réglementation" : "Edit Regulation" }}
                    </h1>
                    <p class="text-blue-50 mt-2">
                        {{ $isFrench ? "Mettez à jour les informations de réglementation" : "Update regulation information" }}
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('extras.update', $extra) }}" method="POST" class="p-6 lg:p-8 space-y-6 lg:space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Secteur -->
                    <div class="space-y-3">
                        <label for="secteur" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Secteur d'activité" : "Activity Sector" }}
                        </label>
                        <div class="relative">
                            <select
                                class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 appearance-none bg-white @error('secteur') border-red-300 @enderror"
                                id="secteur"
                                name="secteur"
                                required>
                                <option value="">{{ $isFrench ? "Sélectionnez un secteur" : "Select a sector" }}</option>
                                <option value="administration" {{ old('secteur', $extra->secteur) == 'administration' ? 'selected' : '' }}>
                                    {{ $isFrench ? "Administration" : "Administration" }}
                                </option>
                                <option value="alimentation" {{ old('secteur', $extra->secteur) == 'alimentation' ? 'selected' : '' }}>
                                    {{ $isFrench ? "Alimentation" : "Food" }}
                                </option>
                                <option value="glace" {{ old('secteur', $extra->secteur) == 'glace' ? 'selected' : '' }}>
                                    {{ $isFrench ? "Glace" : "Ice Cream" }}
                                </option>
                                <option value="production" {{ old('secteur', $extra->secteur) == 'production' ? 'selected' : '' }}>
                                    {{ $isFrench ? "Production" : "Production" }}
                                </option>
                            </select>
                            <div class="pointer-events-none absolute right-3 top-3.5">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('secteur')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Horaires Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Heure d'arrivée -->
                        <div class="space-y-3">
                            <label for="heure_arriver_adequat" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Heure d'arrivée" : "Arrival Time" }}
                            </label>
                            <input type="time"
                                class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('heure_arriver_adequat') border-red-300 @enderror"
                                id="heure_arriver_adequat"
                                name="heure_arriver_adequat"
                                value="{{ old('heure_arriver_adequat', $extra->heure_arriver_adequat->format('H:i')) }}"
                                required>
                            @error('heure_arriver_adequat')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Heure de départ -->
                        <div class="space-y-3">
                            <label for="heure_depart_adequat" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Heure de départ" : "Departure Time" }}
                            </label>
                            <input type="time"
                                class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('heure_depart_adequat') border-red-300 @enderror"
                                id="heure_depart_adequat"
                                name="heure_depart_adequat"
                                value="{{ old('heure_depart_adequat', $extra->heure_depart_adequat->format('H:i')) }}"
                                required>
                            @error('heure_depart_adequat')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Salaire -->
                    <div class="space-y-3">
                        <label for="salaire_adequat" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Salaire mensuel standard" : "Standard Monthly Salary" }}
                        </label>
                        <div class="relative">
                            <input type="number"
                                step="0.01"
                                class="w-full px-4 py-3 lg:py-3 pr-16 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('salaire_adequat') border-red-300 @enderror"
                                id="salaire_adequat"
                                name="salaire_adequat"
                                value="{{ old('salaire_adequat', $extra->salaire_adequat) }}"
                                placeholder="0.00"
                                required>
                            <span class="absolute right-4 top-3 text-gray-500 font-medium">XAF</span>
                        </div>
                        @error('salaire_adequat')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Règles et Interdits Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Interdits -->
                        <div class="space-y-3">
                            <label for="interdit" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                                <div class="bg-red-100 p-2 rounded-full">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Interdictions" : "Prohibitions" }}
                            </label>
                            <textarea
                                class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('interdit') border-red-300 @enderror"
                                id="interdit"
                                name="interdit"
                                rows="4"
                                placeholder="{{ $isFrench ? 'Ex: téléphone portable, nourriture...' : 'Ex: mobile phone, food...' }}">{{ old('interdit', $extra->interdit) }}</textarea>
                            <p class="text-sm text-gray-500">
                                {{ $isFrench ? "Séparez les interdictions par des virgules" : "Separate prohibitions with commas" }}
                            </p>
                            @error('interdit')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Règles -->
                        <div class="space-y-3">
                            <label for="regles" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Règles à suivre" : "Rules to Follow" }}
                            </label>
                            <textarea
                                class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('regles') border-red-300 @enderror"
                                id="regles"
                                name="regles"
                                rows="4"
                                placeholder="{{ $isFrench ? 'Ex: ponctualité, tenue correcte...' : 'Ex: punctuality, proper attire...' }}">{{ old('regles', $extra->regles) }}</textarea>
                            <p class="text-sm text-gray-500">
                                {{ $isFrench ? "Séparez les règles par des virgules" : "Separate rules with commas" }}
                            </p>
                            @error('regles')
                            <p class="text-red-600 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Âge minimum -->
                    <div class="space-y-3">
                        <label for="age_adequat" class="text-base font-semibold text-gray-700 flex items-center gap-2">
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Âge minimum requis" : "Minimum Required Age" }}
                        </label>
                        <input type="number"
                            class="w-full px-4 py-3 lg:py-3 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 @error('age_adequat') border-red-300 @enderror"
                            id="age_adequat"
                            name="age_adequat"
                            value="{{ old('age_adequat', $extra->age_adequat) }}"
                            min="16"
                            max="70"
                            required>
                        @error('age_adequat')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col lg:flex-row gap-4 pt-6">
                        <button type="submit"
                            class="w-full lg:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white font-semibold rounded-xl
                                   shadow-lg hover:from-blue-700 hover:to-green-700 transition-all duration-200 transform hover:-translate-y-1 active:scale-95 lg:active:scale-100">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $isFrench ? "Mettre à jour" : "Update" }}
                        </button>
                        <a href="{{ route('extras.index') }}"
                            class="w-full lg:w-auto px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200
                                   transition-all duration-200 text-center active:scale-95 lg:active:scale-100">
                            {{ $isFrench ? "Annuler" : "Cancel" }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    input:focus, textarea:focus, select:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add input focus animations
    document.querySelectorAll('input, textarea, select').forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-green-500', 'ring-opacity-50');
        });
        
        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-green-500', 'ring-opacity-50');
        });
    });

    // Vibration feedback on submit
    document.querySelector('form').addEventListener('submit', function() {
        if (navigator.vibrate) {
            navigator.vibrate(100);
        }
    });
});
</script>
@endsection
