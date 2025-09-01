@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    <!-- Mobile Header -->
    <div class="block md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Modifier Réception' : 'Edit Reception' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Modification de la réception' : 'Reception modification' }}
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
                        {{ $isFrench ? 'Modifier la Réception' : 'Edit Reception' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Modifiez les informations de réception de sacs' : 'Modify bag reception information' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Form Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Modifier les informations' : 'Edit information' }}
                    </h3>
                </div>

                <!-- Mobile Read-only Info -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-2xl border-l-4 border-gray-400">
                        <p class="text-sm font-medium text-gray-600 mb-1">
                            {{ $isFrench ? 'Type de sac' : 'Bag type' }}
                        </p>
                        <p class="font-bold text-gray-900 text-lg">{{ $reception->assignment->bag->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border-l-4 border-gray-400">
                        <p class="text-sm font-medium text-gray-600 mb-1">
                            {{ $isFrench ? 'Quantité assignée' : 'Assigned quantity' }}
                        </p>
                        <p class="font-bold text-gray-900 text-lg">{{ $reception->assignment->quantity_assigned }}</p>
                    </div>
                </div>

                <form action="{{ route('bag.receptions.update', $reception) }}" method="POST" id="receptionForm" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Mobile Quantity Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="quantity_received" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Quantité reçue' : 'Quantity received' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">#</span>
                            </div>
                            <input type="number" name="quantity_received" id="quantity_received"
                                value="{{ old('quantity_received', $reception->quantity_received) }}"
                                required min="0"
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium"
                                data-assigned="{{ $reception->assignment->quantity_assigned }}"
                                data-total-received="{{ $reception->assignment->total_received - $reception->quantity_received }}">
                        </div>
                        <p id="reception-warning" class="mt-2 text-yellow-500 text-sm font-medium hidden animate-pulse">
                            {{ $isFrench ? 'Attention: Le total des sacs reçus dépassera la quantité assignée!' : 'Warning: Total received bags will exceed assigned quantity!' }}
                        </p>
                        @error('quantity_received')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Notes Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="notes" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Notes (facultatif)' : 'Notes (optional)' }}
                        </label>
                        <div class="relative">
                            <div class="absolute top-4 left-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <textarea name="notes" id="notes" rows="4" placeholder="{{ $isFrench ? 'Ajouter des notes...' : 'Add notes...' }}"
                                class="pl-12 w-full border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md resize-none">{{ old('notes', $reception->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                        </button>
                        <a href="{{ route('bag.receptions.create') }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('bag.receptions.update', $reception) }}" method="POST" id="receptionForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Type de sac' : 'Bag type' }}
                            </label>
                            <div class="bg-gray-100 px-3 py-2 rounded-md">
                                {{ $reception->assignment->bag->name }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Quantité assignée' : 'Assigned quantity' }}
                            </label>
                            <div class="bg-gray-100 px-3 py-2 rounded-md">
                                {{ $reception->assignment->quantity_assigned }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="quantity_received" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Quantité reçue' : 'Quantity received' }}
                            </label>
                            <input type="number" name="quantity_received" id="quantity_received"
                                value="{{ old('quantity_received', $reception->quantity_received) }}"
                                required min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300"
                                data-assigned="{{ $reception->assignment->quantity_assigned }}"
                                data-total-received="{{ $reception->assignment->total_received - $reception->quantity_received }}">
                            <p id="reception-warning" class="text-yellow-500 text-sm mt-1 hidden font-medium">
                                {{ $isFrench ? 'Attention: Le total des sacs reçus dépassera la quantité assignée!' : 'Warning: Total received bags will exceed assigned quantity!' }}
                            </p>
                            @error('quantity_received')
                                <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Notes (facultatif)' : 'Notes (optional)' }}
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">{{ old('notes', $reception->notes) }}</textarea>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('bag.receptions.create') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out transform hover:scale-105">
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded shadow transition duration-150 ease-in-out transform hover:scale-105">
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_received');
    const receptionWarning = document.getElementById('reception-warning');
    const assignedQuantity = parseInt(quantityInput.getAttribute('data-assigned'));
    const otherReceivedQuantity = parseInt(quantityInput.getAttribute('data-total-received'));

    function checkQuantity() {
        const newQuantity = parseInt(quantityInput.value) || 0;

        if (otherReceivedQuantity + newQuantity > assignedQuantity) {
            receptionWarning.classList.remove('hidden');
        } else {
            receptionWarning.classList.add('hidden');
        }
    }

    quantityInput.addEventListener('input', checkQuantity);
    checkQuantity();
});
</script>

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
