@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <div class="px-4 lg:px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h2 class="text-lg lg:text-xl font-bold text-gray-900">
                    {{ $isFrench ? 'Modifier le Versement' : 'Edit Payment' }}
                </h2>
            </div>

            <form action="{{ route('versements.update', $versement) }}" method="POST" class="p-4 lg:p-6">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Libellé' : 'Description' }}
                    </label>
                    <input type="text"
                           name="libelle"
                           id="libelle"
                           class="form-input w-full rounded-xl shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 text-base"
                           value="{{ old('libelle', $versement->libelle) }}"
                           required>
                    @error('libelle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}
                    </label>
                    <input type="number"
                           name="montant"
                           id="montant"
                           class="form-input w-full rounded-xl shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 text-base"
                           value="{{ old('montant', $versement->montant) }}"
                           required>
                    @error('montant')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('versements.index') }}"
                       class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 text-center active:scale-95">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-blue-500 text-white rounded-xl text-sm font-medium hover:bg-blue-600 transition-all duration-200 active:scale-95">
                        {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        /* Touch targets */
        button, .btn, a {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
