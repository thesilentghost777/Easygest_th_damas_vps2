@extends('layouts.app')

@section('title', $isFrench ? 'Déclarer des Sacs Avariés' : 'Declare Damaged Bags')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Déclarer des Sacs Avariés' : 'Declare Damaged Bags' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Indiquer la quantité de sacs avariés' : 'Indicate quantity of damaged bags' }}
            </p>
        </div>
    </div>
    <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Mobile Bag Info -->
                <div class="bg-blue-50 rounded-2xl p-6 mb-6 border-l-4 border-blue-500 animate-fade-in">
                    <h3 class="text-lg font-bold text-blue-800 mb-4">
                        {{ $isFrench ? 'Informations sur le sac' : 'Bag Information' }}
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Nom du sac' : 'Bag name' }}</p>
                            <p class="font-bold text-gray-900">{{ $bag->name }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Prix unitaire' : 'Unit price' }}</p>
                            <p class="font-bold text-gray-900">{{ number_format($bag->price, 2) }} XAF</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Stock disponible' : 'Available stock' }}</p>
                            <p class="font-bold text-gray-900">{{ $bag->stock_quantity }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}</p>
                            <p class="font-bold text-gray-900">{{ $bag->alert_threshold }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('damaged-bags.store', $bag->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Mobile Damaged Quantity Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="damaged_quantity" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Quantité de sacs avariés' : 'Quantity of damaged bags' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <input type="number" name="damaged_quantity" id="damaged_quantity" min="1" max="{{ $bag->stock_quantity }}" value="{{ old('damaged_quantity', 1) }}" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md @error('damaged_quantity') border-red-500 @enderror">
                        </div>
                        @error('damaged_quantity')
                            <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">{{ $isFrench ? 'Maximum:' : 'Maximum:' }} {{ $bag->stock_quantity }} {{ $isFrench ? 'sacs' : 'bags' }}</p>
                    </div>

                    <!-- Mobile Reason Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="reason" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Motif de l\'avarie' : 'Reason for damage' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <select name="reason" id="reason" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md @error('reason') border-red-500 @enderror">
                                <option value="">{{ $isFrench ? 'Sélectionner un motif' : 'Select a reason' }}</option>
                                <option value="Défaut de fabrication" {{ old('reason') == 'Défaut de fabrication' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Défaut de fabrication' : 'Manufacturing defect' }}
                                </option>
                                <option value="Endommagé lors du transport" {{ old('reason') == 'Endommagé lors du transport' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Endommagé lors du transport' : 'Damaged during transport' }}
                                </option>
                                <option value="Endommagé lors du stockage" {{ old('reason') == 'Endommagé lors du stockage' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Endommagé lors du stockage' : 'Damaged during storage' }}
                                </option>
                                <option value="Humidité/Dégât des eaux" {{ old('reason') == 'Humidité/Dégât des eaux' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Humidité/Dégât des eaux' : 'Humidity/Water damage' }}
                                </option>
                                <option value="Autre" {{ old('reason') == 'Autre' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Autre' : 'Other' }}
                                </option>
                            </select>
                        </div>
                        @error('reason')
                            <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-6 w-6 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $isFrench ? 'Valider l\'avarie' : 'Validate damage' }}
                        </button>
                        <a href="{{ route('damaged-bags.index') }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="container mx-auto py-6">
            @include('buttons')
            
            <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-blue-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i> {{ $isFrench ? 'Déclarer des Sacs Avariés' : 'Declare Damaged Bags' }}
                    </h1>
                    <p class="text-gray-600 mt-2">{{ $isFrench ? 'Veuillez indiquer la quantité de sacs avariés à déduire du stock.' : 'Please indicate the quantity of damaged bags to deduct from stock.' }}</p>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-2">{{ $isFrench ? 'Informations sur le sac' : 'Bag Information' }}</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Nom du sac' : 'Bag name' }}</p>
                            <p class="font-medium">{{ $bag->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Prix unitaire' : 'Unit price' }}</p>
                            <p class="font-medium">{{ number_format($bag->price, 2) }} XAF</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Stock disponible' : 'Available stock' }}</p>
                            <p class="font-medium">{{ $bag->stock_quantity }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}</p>
                            <p class="font-medium">{{ $bag->alert_threshold }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('damaged-bags.store', $bag->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="damaged_quantity" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Quantité de sacs avariés' : 'Quantity of damaged bags' }}</label>
                        <input
                            type="number"
                            name="damaged_quantity"
                            id="damaged_quantity"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('damaged_quantity') border-red-500 @enderror"
                            min="1"
                            max="{{ $bag->stock_quantity }}"
                            value="{{ old('damaged_quantity', 1) }}"
                            required
                        >
                        @error('damaged_quantity')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ $isFrench ? 'Maximum:' : 'Maximum:' }} {{ $bag->stock_quantity }} {{ $isFrench ? 'sacs' : 'bags' }}</p>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Motif de l\'avarie' : 'Reason for damage' }}</label>
                        <select
                            name="reason"
                            id="reason"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror"
                            required
                        >
                            <option value="">{{ $isFrench ? 'Sélectionner un motif' : 'Select a reason' }}</option>
                            <option value="Défaut de fabrication" {{ old('reason') == 'Défaut de fabrication' ? 'selected' : '' }}>{{ $isFrench ? 'Défaut de fabrication' : 'Manufacturing defect' }}</option>
                            <option value="Endommagé lors du transport" {{ old('reason') == 'Endommagé lors du transport' ? 'selected' : '' }}>{{ $isFrench ? 'Endommagé lors du transport' : 'Damaged during transport' }}</option>
                            <option value="Endommagé lors du stockage" {{ old('reason') == 'Endommagé lors du stockage' ? 'selected' : '' }}>{{ $isFrench ? 'Endommagé lors du stockage' : 'Damaged during storage' }}</option>
                            <option value="Humidité/Dégât des eaux" {{ old('reason') == 'Humidité/Dégât des eaux' ? 'selected' : '' }}>{{ $isFrench ? 'Humidité/Dégât des eaux' : 'Humidity/Water damage' }}</option>
                            <option value="Autre" {{ old('reason') == 'Autre' ? 'selected' : '' }}>{{ $isFrench ? 'Autre' : 'Other' }}</option>
                        </select>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('damaged-bags.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md hover:from-blue-700 hover:to-blue-800 transition">
                            {{ $isFrench ? 'Valider l\'avarie' : 'Validate damage' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection
