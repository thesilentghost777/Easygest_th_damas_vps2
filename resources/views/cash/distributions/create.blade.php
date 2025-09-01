@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
   

    <!-- Mobile Form Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Form Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="6" x2="12" y2="10"></line>
                            <line x1="12" y1="14" x2="12" y2="18"></line>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Saisir les informations' : 'Enter information' }}
                    </h3>
                </div>

                <form action="{{ route('cash.distributions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Mobile Date Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="date" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                        @error('date')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Bill Amount -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="bill_amount" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Montant obtenu pour les ventes' : 'Amount received for sales' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" name="bill_amount" id="bill_amount" min="0" step="1" value="{{ old('bill_amount', 0) }}" required
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                        </div>
                        @error('bill_amount')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Coin Amount -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="initial_coin_amount" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Montant en Monnaie' : 'Coin Amount' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" name="initial_coin_amount" id="initial_coin_amount" min="0" step="1" value="{{ old('initial_coin_amount', 0) }}" required
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                        </div>
                        @error('initial_coin_amount')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Notes -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="notes" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Notes' : 'Notes' }}
                        </label>
                        <div class="relative">
                            <div class="absolute top-4 left-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <textarea id="notes" name="notes" rows="4" placeholder="{{ $isFrench ? 'Ajouter des notes...' : 'Add notes...' }}"
                                class="pl-12 w-full border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md resize-none">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                           
                            {{ $isFrench ? 'Enregistrer' : 'Save' }}
                        </button>
                        <a href="{{ route('cash.distributions.index') }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Form Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                @include('buttons')

                <div class="p-6 sm:p-8">
                    <form action="{{ route('cash.distributions.store') }}" method="POST">
                        @csrf

                        <!-- Desktop Date Field -->
                        <div class="mb-8">
                            <label for="date" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                                    class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-gray-50 hover:bg-gray-100 transition-all duration-300">
                            </div>
                            @error('date')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desktop Amounts Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <!-- Bills Amount -->
                            <div class="bg-gray-50 p-5 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transform hover:scale-105 transition-all duration-300">
                                <label for="bill_amount" class="block text-base font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Montant obtenu pour les ventes' : 'Amount received for sales' }}
                                </label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">XAF</span>
                                    </div>
                                    <input type="number" name="bill_amount" id="bill_amount" min="0" step="1" value="{{ old('bill_amount', 0) }}" required
                                        class="pl-14 focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium hover:shadow-sm transition-all duration-300">
                                </div>
                                @error('bill_amount')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Coins Amount -->
                            <div class="bg-gray-50 p-5 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transform hover:scale-105 transition-all duration-300">
                                <label for="initial_coin_amount" class="block text-base font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Montant en Monnaie' : 'Coin Amount' }}
                                </label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">XAF</span>
                                    </div>
                                    <input type="number" name="initial_coin_amount" id="initial_coin_amount" min="0" step="1" value="{{ old('initial_coin_amount', 0) }}" required
                                        class="pl-14 focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium hover:shadow-sm transition-all duration-300">
                                </div>
                                @error('initial_coin_amount')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Desktop Notes Section -->
                        <div class="mb-8 bg-gray-50 p-5 rounded-xl shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-300">
                            <label for="notes" class="block text-base font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Notes' : 'Notes' }}
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <textarea id="notes" name="notes" rows="4" class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white hover:shadow-sm transition-all duration-300">{{ old('notes') }}</textarea>
                            </div>
                            @error('notes')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desktop Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <a href="{{ route('cash.distributions.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gray-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                               
                                {{ $isFrench ? 'Enregistrer' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.hover\:scale-102:hover {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .mobile-app-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
}
</style>
@endsection
