@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    <!-- Mobile Header -->
    <div class="block md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Clôturer Distribution' : 'Close Distribution' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $distribution->user->name }} - {{ $distribution->date->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Clôturer la Distribution' : 'Close Distribution' }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Warning Alert -->
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl p-4 animate-pulse">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                <path d="M12 9v4"/>
                                <path d="M12 17h.01"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base font-semibold text-amber-800">
                                {{ $isFrench ? 'Action irréversible' : 'Irreversible action' }}
                            </h3>
                            <p class="text-sm text-amber-700 mt-1">
                                {{ $isFrench ? 'Cette action ne peut pas être annulée.' : 'This action cannot be undone.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Distribution Info Cards -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                        <p class="text-sm font-medium text-blue-600 mb-1">
                            {{ $isFrench ? 'Vendeuse' : 'Seller' }}
                        </p>
                        <p class="font-bold text-blue-900 text-lg">{{ $distribution->user->name }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                        <p class="text-sm font-medium text-blue-600 mb-1">
                            {{ $isFrench ? 'Ventes totales' : 'Total sales' }}
                        </p>
                        <p class="font-bold text-blue-900 text-lg">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                        <p class="text-sm font-medium text-blue-600 mb-1">
                            {{ $isFrench ? 'Monnaie initiale' : 'Initial change' }}
                        </p>
                        <p class="font-bold text-blue-900 text-lg">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} XAF</p>
                    </div>
                </div>

                <form action="{{ route('cash.distributions.close', $distribution) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Mobile Final Coin Amount -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="final_coin_amount" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Monnaie Finale (ajouter y la ration recuperer)' : 'Final Change' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" name="final_coin_amount" id="final_coin_amount" min="0" step="1" value="{{ old('final_coin_amount', 0) }}" required
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $isFrench ? 'Montant de monnaie restant' : 'Remaining change amount' }}
                        </p>
                        @error('final_coin_amount')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Deposited Amount -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="deposited_amount" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Montant Versé' : 'Deposited Amount' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" name="deposited_amount" id="deposited_amount" min="0" step="1" value="{{ old('deposited_amount', 0) }}" required
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $isFrench ? 'Montant total versé par la vendeuse' : 'Total amount deposited by seller' }}
                        </p>
                        @error('deposited_amount')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Calculation Info -->
                    <div class="bg-gray-50 p-4 rounded-2xl border-l-4 border-gray-400">
                        <h4 class="font-semibold text-gray-800 mb-2">
                            {{ $isFrench ? 'Calcul automatique' : 'Automatic calculation' }}
                        </h4>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $isFrench ? 'Le manquant sera calculé ainsi :' : 'Missing amount will be calculated as:' }}
                        </p>
                        <div class="bg-white p-3 rounded-lg font-mono text-sm text-gray-800">
                            ({{ $isFrench ? 'Ventes + Billets + (Monnaie initiale - Monnaie finale(Ration y compris))' : 'Sales + Bills + (Initial change - Final change)' }}) - {{ $isFrench ? 'Versement' : 'Deposit' }} = {{ $isFrench ? 'Manquant' : 'Missing' }}
                        </div>
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2a10 10 0 1 0 10 10H12V2Z"/>
                                <path d="M12 2a10 10 0 0 1 10 10"/>
                                <path d="M12 12h10"/>
                            </svg>
                            {{ $isFrench ? 'Clôturer' : 'Close' }}
                        </button>
                        <a href="{{ route('cash.distributions.show', $distribution) }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                <div class="p-6 sm:p-8">
                    <!-- Desktop Information Alert -->
                    <div class="mb-8 bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $isFrench ? 'Informations importantes' : 'Important information' }}
                                </h3>
                                <div class="mt-2 text-base text-gray-700">
                                    <p>{{ $isFrench ? 'Vous êtes sur le point de clôturer la distribution de monnaie pour' : 'You are about to close the cash distribution for' }} <span class="font-semibold">{{ $distribution->user->name }}</span> {{ $isFrench ? 'du' : 'from' }} <span class="font-semibold">{{ $distribution->date->format('d/m/Y') }}</span>.</p>
                                    <p class="mt-2 font-medium">{{ $isFrench ? 'Cette action est irréversible. Une fois clôturée, la distribution ne pourra plus être modifiée.' : 'This action is irreversible. Once closed, the distribution cannot be modified.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Distribution Details -->
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-gray-50 rounded-xl shadow-md">
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Vendeuse' : 'Seller' }}</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $distribution->user->name }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Date' : 'Date' }}</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $distribution->date->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Montant des ventes' : 'Sales amount' }}</p>
                            <p class="font-bold text-gray-900 text-lg">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Montant obtenu pour la vente' : 'Amount received for sale' }}</p>
                            <p class="font-bold text-gray-900 text-lg">{{ number_format($distribution->bill_amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Monnaie initiale' : 'Initial change' }}</p>
                            <p class="font-bold text-gray-900 text-lg">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <form action="{{ route('cash.distributions.close', $distribution) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <!-- Final Coin Amount -->
                            <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                                <label for="final_coin_amount" class="block text-base font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Monnaie Finale(injectez y la ration recuperer)' : 'Final Change' }}
                                </label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">FCFA</span>
                                    </div>
                                    <input type="number" name="final_coin_amount" id="final_coin_amount" min="0" step="1" value="{{ old('final_coin_amount', 0) }}" required
                                        class="pl-14 focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium hover:shadow-sm transition-all duration-300">
                                </div>
                                <p class="mt-2 text-sm text-gray-600">{{ $isFrench ? 'Montant de monnaie restant à la fin de la journée.' : 'Change amount remaining at end of day.' }}</p>
                                @error('final_coin_amount')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deposited Amount -->
                            <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                                <label for="deposited_amount" class="block text-base font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Montant Versé' : 'Deposited Amount' }}
                                </label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">FCFA</span>
                                    </div>
                                    <input type="number" name="deposited_amount" id="deposited_amount" min="0" step="1" value="{{ old('deposited_amount', 0) }}" required
                                        class="pl-14 focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium hover:shadow-sm transition-all duration-300">
                                </div>
                                <p class="mt-2 text-sm text-gray-600">{{ $isFrench ? 'Montant total versé par la vendeuse.' : 'Total amount deposited by seller.' }}</p>
                                @error('deposited_amount')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-fade-in">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Desktop Warning Alert -->
                        <div class="bg-gray-50 p-5 rounded-xl border-l-4 border-amber-500 shadow-md mb-8 transform hover:scale-105 transition-all duration-300">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                        <path d="M12 9v4"/>
                                        <path d="M12 17h.01"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        {{ $isFrench ? 'Calcul automatique du manquant' : 'Automatic missing calculation' }}
                                    </h3>
                                    <div class="mt-2 text-base text-gray-700">
                                        <p>{{ $isFrench ? 'Le système calculera automatiquement le montant manquant selon la formule :' : 'The system will automatically calculate the missing amount according to the formula:' }}</p>
                                        <p class="font-mono mt-2 p-2 bg-gray-100 rounded-lg text-gray-800">({{ $isFrench ? 'Ventes + Billets + (Monnaie initiale - Monnaie finale(Ration y compris))' : 'Sales + Bills + (Initial change - Final change)' }}) - {{ $isFrench ? 'Versement' : 'Deposit' }} = {{ $isFrench ? 'Manquant' : 'Missing' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <a href="{{ route('cash.distributions.show', $distribution) }}" class="inline-flex items-center justify-center px-8 py-4 bg-gray-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2a10 10 0 1 0 10 10H12V2Z"/>
                                    <path d="M12 2a10 10 0 0 1 10 10"/>
                                    <path d="M12 12h10"/>
                                </svg>
                                {{ $isFrench ? 'Clôturer la distribution' : 'Close distribution' }}
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
</style>
@endsection
