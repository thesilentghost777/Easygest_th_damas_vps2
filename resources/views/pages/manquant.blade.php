@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100" x-data="{ isMobile: window.innerWidth < 768 }" x-init="() => {
    window.addEventListener('resize', () => isMobile = window.innerWidth < 768);
}">
    <!-- Box principale avec animations -->
    <div class="w-full max-w-md md:max-w-xl bg-white p-4 md:p-6 rounded-lg shadow-lg relative transition-all duration-300"
         :class="{'scale-95': isMobile, 'hover:shadow-xl': !isMobile}"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        @include('buttons')

        <!-- Conseil avec animation -->
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 md:p-4 mb-4 md:mb-6 rounded-md"
             x-transition:enter="transition ease-out duration-300 delay-100"
             :class="{'animate-pulse': isMobile}">
            <h3 class="text-base md:text-lg font-semibold">
                {{ $isFrench ? 'Conseil important' : 'Important advice' }}
            </h3>
            <p class="mt-1 md:mt-2 text-sm md:text-base">
                {{ $isFrench ? 
                    'Les manquants peuvent avoir un impact significatif sur la vie des employés...' : 
                    'Shortages can significantly impact employees lives...' }}
            </p>
        </div>

        <!-- Titre avec animation -->
        <h2 class="text-xl md:text-2xl font-semibold text-center text-blue-700 mb-3 md:mb-4"
            x-transition:enter="transition ease-out duration-300 delay-200">
            {{ $isFrench ? 'Attribuer un Manquant' : 'Assign a Shortage' }}
        </h2>
        
        <div class="border-t-2 border-blue-500 my-3 md:my-4"></div>

        <!-- Messages flash -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 rounded p-3 md:p-4 mb-3 md:mb-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 scale-95">
            {{ session('success') }}
        </div>
        @endif

        <!-- Formulaire -->
        <form action="{{ route('manquant.store') }}" method="POST"
              x-transition:enter="transition ease-out duration-300 delay-300">
            @csrf
            
            <!-- Employé -->
            <div class="mb-3 md:mb-4">
                <label for="employe_id" class="block text-gray-700 font-medium mb-1 md:mb-2 text-sm md:text-base">
                    {{ $isFrench ? 'Employé' : 'Employee' }}
                </label>
                <select name="employe_id" id="employe_id" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base py-2 px-3"
                        required
                        :class="{'border-2 border-blue-300': isMobile}">
                    <option value="">{{ $isFrench ? 'Sélectionnez un employé' : 'Select an employee' }}</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employe_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
                @error('employe_id')
                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Montant -->
            <div class="mb-3 md:mb-4">
                <label for="montant" class="block text-gray-700 font-medium mb-1 md:mb-2 text-sm md:text-base">
                    {{ $isFrench ? 'Montant du Manquant' : 'Shortage Amount' }}
                </label>
                <input type="number" name="montant" id="montant" step="0.01" min="1" 
                       value="{{ old('montant') }}"
                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base py-2 px-3"
                       required
                       :class="{'border-2 border-blue-300': isMobile}">
                @error('montant')
                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Explication -->
            <div class="mb-4 md:mb-5">
                <label for="explication" class="block text-gray-700 font-medium mb-1 md:mb-2 text-sm md:text-base">
                    {{ $isFrench ? 'Explication' : 'Explanation' }}
                </label>
                <textarea name="explication" id="explication" rows="3" 
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base py-2 px-3"
                          required
                          :class="{'border-2 border-blue-300': isMobile}">{{ old('explication') }}</textarea>
                @error('explication')
                <span class="text-red-500 text-xs md:text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Bouton avec animation -->
            <div class="text-center">
                <button type="submit" 
                        class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm md:text-base transition-all duration-200"
                        :class="{'transform hover:scale-105 active:scale-95': isMobile}">
                    {{ $isFrench ? 'Attribuer' : 'Assign' }}
                </button>
            </div>
        </form>

        <!-- Reflection Effect - visible seulement sur desktop -->
        <div class="absolute bottom-[-10px] left-0 right-0 hidden md:block">
            <div class="h-6 bg-gradient-to-t from-gray-300 to-transparent opacity-50 blur-md rounded-b-lg"></div>
        </div>
    </div>
</div>

<style>
    /* Animation pour les entrées mobiles */
    @keyframes mobileEntry {
        0% { transform: translateY(20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    /* Animation pour le bouton mobile */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
</style>
@endsection