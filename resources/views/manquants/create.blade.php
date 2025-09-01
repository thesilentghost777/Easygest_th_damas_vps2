@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 lg:bg-white lg:rounded-lg lg:shadow-md lg:p-6 lg:m-4">
    <!-- Mobile Header -->
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 px-4 py-3 sticky top-0 z-10">
        <div class="flex items-center justify-between">
            @include('buttons')
            <div class="flex-1 text-center">
                <h1 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Facturer Manquant' : 'Bill Missing Item' }}
                </h1>
            </div>
            <div class="w-10"></div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $isFrench ? 'Facturer un Manquant' : 'Bill a Missing Item' }}
        </h1>
        @include('buttons')
    </div>

    <!-- Mobile Content Container -->
    <div class="lg:hidden">
        <div class="px-4 py-6">
            <!-- Mobile Form Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white font-semibold text-lg">
                                {{ $isFrench ? 'Nouveau Manquant' : 'New Missing Item' }}
                            </h2>
                            <p class="text-blue-100 text-sm">
                                {{ $isFrench ? 'Remplissez les détails ci-dessous' : 'Fill in the details below' }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('manquant.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="space-y-2">
                        <label for="employe_id" class="block text-sm font-semibold text-gray-800">
                            {{ $isFrench ? 'Employé' : 'Employee' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="employe_id" id="employe_id" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:bg-white focus:outline-none transition-all duration-200 text-gray-800 font-medium" required>
                                <option value="">{{ $isFrench ? 'Sélectionner un producteur' : 'Select a producer' }}</option>
                                @foreach($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}">{{ $producteur->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('employe_id')
                            <div class="flex items-center space-x-2 text-red-600 text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Amount Input -->
                    <div class="space-y-2">
                        <label for="montant" class="block text-sm font-semibold text-gray-800">
                            {{ $isFrench ? 'Montant du Manquant' : 'Missing Amount' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="montant" id="montant" class="w-full px-4 py-3 pr-16 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:bg-white focus:outline-none transition-all duration-200 text-gray-800 font-medium text-lg" placeholder="0" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-gray-500 font-semibold text-sm">FCFA</span>
                            </div>
                        </div>
                        @error('montant')
                            <div class="flex items-center space-x-2 text-red-600 text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Explanation Textarea -->
                    <div class="space-y-2">
                        <label for="explication" class="block text-sm font-semibold text-gray-800">
                            {{ $isFrench ? 'Explication du Manquant' : 'Missing Explanation' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="explication" name="explication" rows="4" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:bg-white focus:outline-none transition-all duration-200 text-gray-800 resize-none" placeholder="{{ $isFrench ? 'Détaillez la raison de ce manquant...' : 'Detail the reason for this missing item...' }}" required></textarea>
                        </div>
                        @error('explication')
                            <div class="flex items-center space-x-2 text-red-600 text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-4 space-y-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $isFrench ? 'Facturer le Manquant' : 'Bill the Missing Item' }}</span>
                            </div>
                        </button>
                        <a href="{{ route('manquants.index') }}" class="block w-full bg-white border-2 border-gray-200 text-gray-700 py-4 px-6 rounded-xl font-semibold text-lg text-center hover:bg-gray-50 transition-all duration-200 active:scale-95">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>{{ $isFrench ? 'Annuler' : 'Cancel' }}</span>
                            </div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Content -->
    <div class="hidden lg:block">
        <form action="{{ route('manquant.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="employe_id_desktop" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $isFrench ? 'Employé' : 'Employee' }}
                </label>
                <select name="employe_id" id="employe_id_desktop" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                    <option value="">{{ $isFrench ? 'Sélectionner un producteur' : 'Select a producer' }}</option>
                    @foreach($producteurs as $producteur)
                        <option value="{{ $producteur->id }}">{{ $producteur->name }}</option>
                    @endforeach
                </select>
                @error('employe_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="montant_desktop" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $isFrench ? 'Montant du Manquant' : 'Missing Amount' }}
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" name="montant" id="montant_desktop" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-12 py-2 sm:text-sm border-gray-300 rounded-md" placeholder="0" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">FCFA</span>
                    </div>
                </div>
                @error('montant')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="explication_desktop" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $isFrench ? 'Explication du Manquant' : 'Missing Explanation' }}
                </label>
                <div class="mt-1">
                    <textarea id="explication_desktop" name="explication" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="{{ $isFrench ? 'Détaillez la raison de ce manquant...' : 'Detail the reason for this missing item...' }}" required></textarea>
                </div>
                @error('explication')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('manquants.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $isFrench ? 'Facturer le Manquant' : 'Bill the Missing Item' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Mobile-specific animations and interactions */
@media (max-width: 1023px) {
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Form input focus animations */
    input:focus, select:focus, textarea:focus {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }
    
    /* Button press animation */
    button:active, .button:active {
        transform: scale(0.98);
    }
    
    /* Card hover animation */
    .bg-white.rounded-2xl:hover {
        transform: translateY(-2px);
    }
    
    /* Floating label effect */
    .floating-label {
        transition: all 0.2s ease-in-out;
    }
    
    /* Loading state for submit button */
    button[type="submit"]:active {
        background: linear-gradient(to right, #1d4ed8, #1e40af);
    }
    
    /* Input validation states */
    .border-red-500 {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 20%, 40%, 60%, 80% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
    }
    
    /* Smooth transitions for all interactive elements */
    * {
        -webkit-tap-highlight-color: transparent;
    }
    
    /* Enhanced touch targets */
    button, a, select, input, textarea {
        min-height: 44px;
    }
}

/* Desktop-specific styles remain unchanged */
@media (min-width: 1024px) {
    /* Keep original desktop styling intact */
}
</style>
@endsection