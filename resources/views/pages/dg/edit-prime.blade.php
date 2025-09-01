
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <div class="md:hidden bg-gradient-to-r from-amber-600 to-amber-700 text-white">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('primes.create') }}" class="mr-4 p-2 rounded-full bg-white/20 backdrop-blur-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold">
                            {{ $isFrench ? 'Modifier Prime' : 'Edit Bonus' }}
                        </h1>
                        <p class="text-amber-100 text-sm mt-1">
                            {{ $isFrench ? 'Modification des détails' : 'Modify details' }}
                        </p>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <i class="fas fa-edit text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:block bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('primes.create') }}" class="mr-4 p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $isFrench ? 'Modifier Prime' : 'Edit Bonus' }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ $isFrench ? 'Modifier les détails de la prime' : 'Modify bonus details' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-shake">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Current Prime Info -->
        <div class="bg-white rounded-2xl md:rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                <h2 class="text-lg font-semibold text-blue-900">
                    {{ $isFrench ? 'Prime Actuelle' : 'Current Bonus' }}
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $prime->user->name }}</h3>
                            <p class="text-gray-600">{{ $prime->libelle }}</p>
                            <p class="text-gray-500 text-sm">
                                {{ $isFrench ? 'Créée le' : 'Created on' }} {{ $prime->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600">
                            {{ number_format($prime->montant, 0, ',', ' ') }}
                        </p>
                        <p class="text-gray-500">FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl md:rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Modifier les Détails' : 'Edit Details' }}
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('primes.update', $prime->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Employee Selection -->
                    <div>
                        <label for="id_employe" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Employé' : 'Employee' }} *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <select name="id_employe" id="id_employe" required
                                class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 bg-white h-12 text-sm font-medium">
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->id }}" {{ $employe->id == $prime->id_employe ? 'selected' : '' }}>
                                        {{ $employe->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Prime Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="libelle" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Libellé' : 'Description' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="libelle" id="libelle" required value="{{ $prime->libelle }}"
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 bg-white h-12 text-sm font-medium">
                            </div>
                        </div>

                        <div>
                            <label for="montant" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-coins text-gray-400"></i>
                                </div>
                                <input type="number" name="montant" id="montant" required min="0" step="1" value="{{ $prime->montant }}"
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 bg-white h-12 text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- PIN -->
                    <div>
                        <label for="pin" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Code PIN pour confirmer' : 'PIN code to confirm' }} *
                        </label>
                        <div class="relative max-w-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="pin" id="pin" required maxlength="6"
                                class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 bg-white h-12 text-sm font-medium text-center"
                                placeholder="••••••">
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-4 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold py-3 px-8 rounded-xl hover:from-amber-700 hover:to-amber-800 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>
                            {{ $isFrench ? 'Sauvegarder les Modifications' : 'Save Changes' }}
                        </button>
                        <a href="{{ route('primes.create') }}" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 px-8 rounded-xl hover:bg-gray-200 transition-colors text-center">
                            <i class="fas fa-times mr-2"></i>
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>
@endsection
