@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    
    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-6 bg-yellow-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Modifier l\'ajustement' : 'Edit Adjustment' }}
                    </h2>
                    <p class="text-yellow-100 mt-2">
                        {{ $isFrench ? 'Modification avec recalcul automatique de l\'historique' : 'Edit with automatic history recalculation' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Header -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg mb-6">
                    <h2 class="text-lg font-semibold">{{ $isFrench ? 'Modifier l\'ajustement' : 'Edit Adjustment' }}</h2>
                    <p class="text-yellow-100 text-sm mt-1">{{ $isFrench ? 'Attention: Cette action recalculera tout l\'historique' : 'Warning: This action will recalculate all history' }}</p>
                </div>

                <!-- Mobile Form -->
                <form action="{{ route('solde-cp.update', $historique->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations actuelles -->
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <h3 class="font-semibold text-gray-800 mb-3">{{ $isFrench ? 'Informations actuelles' : 'Current Information' }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Date' : 'Date' }}:</span>
                                <span class="font-medium">{{ $historique->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Utilisateur' : 'User' }}:</span>
                                <span class="font-medium">{{ $historique->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $isFrench ? 'Solde avant' : 'Balance before' }}:</span>
                                <span class="font-medium">{{ number_format($historique->solde_avant, 0, ',', ' ') }} XAF</span>
                            </div>
                        </div>
                    </div>

                    <!-- Montant -->
                    <div>
                        <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Montant de l\'ajustement' : 'Adjustment Amount' }}
                        </label>
                        <input 
                            type="number" 
                            id="montant" 
                            name="montant" 
                            step="0.01"
                            value="{{ old('montant', $historique->montant) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors @error('montant') border-red-500 @enderror"
                            required
                        >
                        @error('montant')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Description' : 'Description' }}
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors @error('description') border-red-500 @enderror"
                            required
                        >{{ old('description', $historique->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Avertissement -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <div>
                                <h4 class="text-yellow-800 font-medium">{{ $isFrench ? 'Attention' : 'Warning' }}</h4>
                                <p class="text-yellow-700 text-sm mt-1">
                                    {{ $isFrench ? 'Cette modification recalculera automatiquement tous les soldes suivants dans l\'historique.' : 'This modification will automatically recalculate all subsequent balances in the history.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex space-x-4 pt-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-yellow-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-yellow-700 transform active:scale-95 transition-all duration-150"
                        >
                            {{ $isFrench ? 'Modifier' : 'Update' }}
                        </button>
                        <a 
                            href="{{ route('solde-cp.index') }}" 
                            class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-gray-700 transform active:scale-95 transition-all duration-150 text-center"
                        >
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-yellow-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $isFrench ? 'Modifier l\'ajustement' : 'Edit Adjustment' }}</h2>
                </div>

                <form action="{{ route('solde-cp.update', $historique->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informations actuelles -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-800 mb-4">{{ $isFrench ? 'Informations actuelles' : 'Current Information' }}</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Date' : 'Date' }}:</span>
                                    <span class="font-medium">{{ $historique->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Utilisateur' : 'User' }}:</span>
                                    <span class="font-medium">{{ $historique->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Type' : 'Type' }}:</span>
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                                        {{ $isFrench ? 'Ajustement' : 'Adjustment' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Solde avant' : 'Balance before' }}:</span>
                                    <span class="font-medium">{{ number_format($historique->solde_avant, 0, ',', ' ') }} XAF</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Solde après (actuel)' : 'Balance after (current)' }}:</span>
                                    <span class="font-medium text-yellow-600">{{ number_format($historique->solde_apres, 0, ',', ' ') }} XAF</span>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de modification -->
                        <div class="space-y-6">
                            <!-- Montant -->
                            <div>
                                <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Nouveau montant de l\'ajustement' : 'New Adjustment Amount' }}
                                </label>
                                <input 
                                    type="number" 
                                    id="montant" 
                                    name="montant" 
                                    step="0.01"
                                    value="{{ old('montant', $historique->montant) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('montant') border-red-500 @enderror"
                                    required
                                >
                                @error('montant')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Description' : 'Description' }}
                                </label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('description') border-red-500 @enderror"
                                    required
                                >{{ old('description', $historique->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Avertissement -->
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                            </svg>
                            <div>
                                <h4 class="text-yellow-800 font-medium">{{ $isFrench ? 'Attention - Recalcul automatique' : 'Warning - Automatic Recalculation' }}</h4>
                                <p class="text-yellow-700 text-sm mt-1">
                                    {{ $isFrench ? 'Cette modification recalculera automatiquement tous les soldes suivants dans l\'historique. L\'opération est irréversible une fois validée.' : 'This modification will automatically recalculate all subsequent balances in the history. The operation is irreversible once validated.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                        <a 
                            href="{{ route('solde-cp.index') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transform hover:scale-105 transition-all duration-200"
                        >
                            {{ $isFrench ? 'Modifier l\'ajustement' : 'Update Adjustment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}
</style>
@endsection
