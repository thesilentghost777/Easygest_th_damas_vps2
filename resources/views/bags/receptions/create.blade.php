@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
   
    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Enregistrer une Réception de Sacs' : 'Register Bag Reception' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Déclarez les sacs que vous avez reçus du chef de production' : 'Declare bags received from production manager' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-2xl animate-fade-in">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Mobile Form Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Nouvelle réception' : 'New reception' }}
                    </h3>
                </div>

                <form action="{{ route('bag.receptions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Mobile Assignment Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="bag_assignment_id" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Assignation de sacs' : 'Bag assignment' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"/>
                                    <path d="M9 7h6v4H9z"/>
                                </svg>
                            </div>
                            <select name="bag_assignment_id" id="bag_assignment_id" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                                <option value="">{{ $isFrench ? 'Sélectionner une assignation' : 'Select assignment' }}</option>
                                @foreach($assignments as $assignment)
                                <option value="{{ $assignment->id }}" data-assigned="{{ $assignment->quantity_assigned }}" data-received="{{ $assignment->total_received }}" {{ old('bag_assignment_id') == $assignment->id ? 'selected' : '' }}>
                                    {{ $assignment->bag->name }} - {{ $isFrench ? 'Assigné:' : 'Assigned:' }} {{ $assignment->quantity_assigned }}, {{ $isFrench ? 'Déjà reçu:' : 'Already received:' }} {{ $assignment->total_received }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bag_assignment_id')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Quantity Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="quantity_received" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Quantité reçue' : 'Quantity received' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">#</span>
                            </div>
                            <input type="number" name="quantity_received" id="quantity_received" value="{{ old('quantity_received') }}" required min="0"
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
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
                                class="pl-12 w-full border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Mobile Action Button -->
                    <div class="pt-6">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                           
                            {{ $isFrench ? 'Enregistrer la réception' : 'Register reception' }}
                        </button>
                    </div>
                </form>

                <!-- Mobile Recent Receptions -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Réceptions récentes' : 'Recent receptions' }}
                    </h3>
                    <div class="space-y-3">
                        @forelse($recentReceptions as $reception)
                            <div class="bg-gradient-to-r from-white to-blue-50 rounded-2xl shadow-md border border-gray-100 p-4 transform hover:scale-105 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $reception->assignment->bag->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $reception->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Quantité:' : 'Quantity:' }} {{ $reception->quantity_received }}</p>
                                    </div>
                                    <a href="{{ route('bag.receptions.edit', $reception) }}" class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transform hover:scale-110 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                                <p class="text-gray-500">{{ $isFrench ? 'Aucune réception récente' : 'No recent receptions' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg animate-fade-in">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300 mb-8">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('bag.receptions.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="bag_assignment_id" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Assignation de sacs' : 'Bag assignment' }}
                            </label>
                            <select name="bag_assignment_id" id="bag_assignment_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">
                                <option value="">{{ $isFrench ? 'Sélectionner une assignation' : 'Select assignment' }}</option>
                                @foreach($assignments as $assignment)
                                <option value="{{ $assignment->id }}" data-assigned="{{ $assignment->quantity_assigned }}" data-received="{{ $assignment->total_received }}" {{ old('bag_assignment_id') == $assignment->id ? 'selected' : '' }}>
                                    {{ $assignment->bag->name }} - {{ $isFrench ? 'Assigné:' : 'Assigned:' }} {{ $assignment->quantity_assigned }}, {{ $isFrench ? 'Déjà reçu:' : 'Already received:' }} {{ $assignment->total_received }}
                                </option>
                                @endforeach
                            </select>
                            @error('bag_assignment_id')
                                <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="quantity_received" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Quantité reçue' : 'Quantity received' }}
                            </label>
                            <input type="number" name="quantity_received" id="quantity_received" value="{{ old('quantity_received') }}" required min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded shadow transition duration-150 ease-in-out transform hover:scale-105">
                                {{ $isFrench ? 'Enregistrer la réception' : 'Register reception' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Receptions -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-blue-700 mb-4">
                        {{ $isFrench ? 'Réceptions récentes' : 'Recent receptions' }}
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-blue-50 text-blue-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Sac' : 'Bag' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Quantité reçue' : 'Quantity received' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentReceptions as $reception)
                                <tr class="hover:bg-gray-50 transform hover:scale-105 transition-all duration-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $reception->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $reception->assignment->bag->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $reception->quantity_received }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('bag.receptions.edit', $reception) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-125 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ $isFrench ? 'Aucune réception récente' : 'No recent receptions' }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignmentSelect = document.getElementById('bag_assignment_id');
    const quantityInput = document.getElementById('quantity_received');
    const receptionWarning = document.getElementById('reception-warning');

    function checkQuantity() {
        const selectedOption = assignmentSelect.options[assignmentSelect.selectedIndex];
        if (!selectedOption.value) return;

        const assignedQuantity = parseInt(selectedOption.getAttribute('data-assigned'));
        const alreadyReceived = parseInt(selectedOption.getAttribute('data-received'));
        const newQuantity = parseInt(quantityInput.value) || 0;

        if (alreadyReceived + newQuantity > assignedQuantity) {
            receptionWarning.classList.remove('hidden');
        } else {
            receptionWarning.classList.add('hidden');
        }
    }

    assignmentSelect.addEventListener('change', checkQuantity);
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
