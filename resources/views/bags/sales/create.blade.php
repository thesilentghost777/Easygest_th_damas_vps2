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
                        {{ $isFrench ? 'Déclarer les Sacs Vendus/Invendus' : 'Declare Sold/Unsold Bags' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Enregistrez les sacs que vous avez vendus ou qui sont restés invendus' : 'Record bags you sold or that remained unsold' }}
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
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Nouvelle déclaration' : 'New declaration' }}
                    </h3>
                </div>

                <form action="{{ route('bag.sales.store') }}" method="POST" id="salesForm" class="space-y-6">
                    @csrf

                    <!-- Mobile Reception Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="bag_reception_id" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Réception de sacs' : 'Bag reception' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                            </div>
                            <select name="bag_reception_id" id="bag_reception_id" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                                <option value="">{{ $isFrench ? 'Sélectionner une réception' : 'Select reception' }}</option>
                                @foreach($receptions as $reception)
                                <option value="{{ $reception->id }}" data-quantity="{{ $reception->quantity_received }}" {{ old('bag_reception_id') == $reception->id ? 'selected' : '' }}>
                                    {{ $reception->assignment->bag->name }} - {{ $isFrench ? 'Reçu le' : 'Received on' }} {{ $reception->created_at->format('d/m/Y') }} - {{ $reception->quantity_received }} {{ $isFrench ? 'sacs' : 'bags' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bag_reception_id')
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Mobile Quantities -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="transform hover:scale-102 transition-all duration-200">
                            <label for="quantity_sold" class="block text-base font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Sacs vendus' : 'Bags sold' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-green-600 font-semibold text-lg">✓</span>
                                </div>
                                <input type="number" name="quantity_sold" id="quantity_sold" value="{{ old('quantity_sold') }}" required min="0"
                                    class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                            </div>
                            @error('quantity_sold')
                                <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                    <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <div class="transform hover:scale-102 transition-all duration-200">
                            <label for="quantity_unsold" class="block text-base font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Sacs invendus' : 'Bags unsold' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-red-600 font-semibold text-lg">✗</span>
                                </div>
                                <input type="number" name="quantity_unsold" id="quantity_unsold" value="{{ old('quantity_unsold') }}" required min="0"
                                    class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                            </div>
                            @error('quantity_unsold')
                                <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded-r-lg animate-shake">
                                    <p class="text-sm font-medium text-red-700">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mobile Total Info -->
                    <div class="bg-blue-50 p-4 rounded-2xl border-l-4 border-blue-500">
                        <p id="total-info" class="text-blue-600 font-medium hidden">
                            {{ $isFrench ? 'Total:' : 'Total:' }} <span id="total-quantity">0</span> / <span id="expected-quantity">0</span> {{ $isFrench ? 'sacs' : 'bags' }}
                        </p>
                        <p id="balance-warning" class="text-red-500 text-sm font-medium hidden animate-pulse">
                            {{ $isFrench ? 'La somme des sacs vendus et invendus doit être égale à la quantité reçue!' : 'Sum of sold and unsold bags must equal received quantity!' }}
                        </p>
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
                           
                            {{ $isFrench ? 'Enregistrer la déclaration' : 'Register declaration' }}
                        </button>
                    </div>
                </form>

                <!-- Mobile Recent Sales -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isFrench ? 'Déclarations récentes' : 'Recent declarations' }}
                    </h3>
                    <div class="space-y-3">
                        @forelse($recentSales as $sale)
                            <div class="bg-gradient-to-r from-white to-green-50 rounded-2xl shadow-md border border-gray-100 p-4 transform hover:scale-105 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $sale->reception->assignment->bag->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                                        <div class="flex space-x-4 text-sm">
                                            <span class="text-green-600 font-medium">{{ $isFrench ? 'Vendus:' : 'Sold:' }} {{ $sale->quantity_sold }}</span>
                                            <span class="text-red-600 font-medium">{{ $isFrench ? 'Invendus:' : 'Unsold:' }} {{ $sale->quantity_unsold }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('bag.sales.edit', $sale) }}" class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 hover:bg-green-200 transform hover:scale-110 transition-all duration-200">
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
                                    <circle cx="9" cy="21" r="1"/>
                                    <circle cx="20" cy="21" r="1"/>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                <p class="text-gray-500">{{ $isFrench ? 'Aucune déclaration récente' : 'No recent declarations' }}</p>
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
                    <form action="{{ route('bag.sales.store') }}" method="POST" id="salesForm">
                        @csrf

                        <div class="mb-6">
                            <label for="bag_reception_id" class="block text-lg font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Réception de sacs' : 'Bag reception' }}
                            </label>
                            <select name="bag_reception_id" id="bag_reception_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">
                                <option value="">{{ $isFrench ? 'Sélectionner une réception' : 'Select reception' }}</option>
                                @foreach($receptions as $reception)
                                <option value="{{ $reception->id }}" data-quantity="{{ $reception->quantity_received }}" {{ old('bag_reception_id') == $reception->id ? 'selected' : '' }}>
                                    {{ $reception->assignment->bag->name }} - {{ $isFrench ? 'Reçu le' : 'Received on' }} {{ $reception->created_at->format('d/m/Y') }} - {{ $reception->quantity_received }} {{ $isFrench ? 'sacs' : 'bags' }}
                                </option>
                                @endforeach
                            </select>
                            @error('bag_reception_id')
                                <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="quantity_sold" class="block text-lg font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Sacs vendus' : 'Bags sold' }}
                                </label>
                                <input type="number" name="quantity_sold" id="quantity_sold" value="{{ old('quantity_sold') }}" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">
                                @error('quantity_sold')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quantity_unsold" class="block text-lg font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Sacs invendus' : 'Bags unsold' }}
                                </label>
                                <input type="number" name="quantity_unsold" id="quantity_unsold" value="{{ old('quantity_unsold') }}" required min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white hover:shadow-md transition-all duration-300">
                                @error('quantity_unsold')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <p id="total-info" class="text-blue-600 font-medium hidden">
                                {{ $isFrench ? 'Total:' : 'Total:' }} <span id="total-quantity">0</span> / <span id="expected-quantity">0</span> {{ $isFrench ? 'sacs' : 'bags' }}
                            </p>
                            <p id="balance-warning" class="text-red-500 text-sm font-medium hidden">
                                {{ $isFrench ? 'La somme des sacs vendus et invendus doit être égale à la quantité reçue!' : 'Sum of sold and unsold bags must equal received quantity!' }}
                            </p>
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
                                {{ $isFrench ? 'Enregistrer la déclaration' : 'Register declaration' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:shadow-2xl transition-all duration-300">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-blue-700 mb-4">
                        {{ $isFrench ? 'Déclarations récentes' : 'Recent declarations' }}
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-blue-50 text-blue-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Sac' : 'Bag' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Vendus' : 'Sold' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Invendus' : 'Unsold' }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentSales as $sale)
                                <tr class="hover:bg-gray-50 transform hover:scale-105 transition-all duration-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $sale->reception->assignment->bag->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $sale->quantity_sold }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $sale->quantity_unsold }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('bag.sales.edit', $sale) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-125 transition-all duration-200">
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
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ $isFrench ? 'Aucune déclaration récente' : 'No recent declarations' }}
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
    const receptionSelect = document.getElementById('bag_reception_id');
    const soldInput = document.getElementById('quantity_sold');
    const unsoldInput = document.getElementById('quantity_unsold');
    const totalInfo = document.getElementById('total-info');
    const totalQuantity = document.getElementById('total-quantity');
    const expectedQuantity = document.getElementById('expected-quantity');
    const balanceWarning = document.getElementById('balance-warning');
    const salesForm = document.getElementById('salesForm');

    function checkBalance() {
        const selectedOption = receptionSelect.options[receptionSelect.selectedIndex];
        if (!selectedOption.value) {
            totalInfo.classList.add('hidden');
            balanceWarning.classList.add('hidden');
            return;
        }

        const receivedQuantity = parseInt(selectedOption.getAttribute('data-quantity'));
        const soldQuantity = parseInt(soldInput.value) || 0;
        const unsoldQuantity = parseInt(unsoldInput.value) || 0;
        const totalDeclared = soldQuantity + unsoldQuantity;

        totalInfo.classList.remove('hidden');
        totalQuantity.textContent = totalDeclared;
        expectedQuantity.textContent = receivedQuantity;

        if (totalDeclared !== receivedQuantity) {
            balanceWarning.classList.remove('hidden');
        } else {
            balanceWarning.classList.add('hidden');
        }
    }

    receptionSelect.addEventListener('change', checkBalance);
    soldInput.addEventListener('input', checkBalance);
    unsoldInput.addEventListener('input', checkBalance);

    salesForm.addEventListener('submit', function(event) {
        const selectedOption = receptionSelect.options[receptionSelect.selectedIndex];
        if (!selectedOption.value) return;

        const receivedQuantity = parseInt(selectedOption.getAttribute('data-quantity'));
        const soldQuantity = parseInt(soldInput.value) || 0;
        const unsoldQuantity = parseInt(unsoldInput.value) || 0;
        const totalDeclared = soldQuantity + unsoldQuantity;

        if (totalDeclared !== receivedQuantity) {
            event.preventDefault();
            balanceWarning.classList.remove('hidden');
            window.scrollTo(0, balanceWarning.offsetTop - 100);
        }
    });

    checkBalance();
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
