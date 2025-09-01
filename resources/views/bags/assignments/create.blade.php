@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Mobile Back Button -->
    @include('buttons')

    <div class="mb-6 mobile:animate-fadeIn">
        <h1 class="text-2xl font-bold text-blue-700">{{ $isFrench ? 'Assigner des Sacs' : 'Assign Bags' }}</h1>
        <p class="text-gray-600 mt-1">{{ $isFrench ? 'Assignez des sacs aux serveurs pour la vente' : 'Assign bags to servers for sale' }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded mobile:animate-pulse" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 mb-8 mobile:animate-slideUp mobile:duration-300">
        <form action="{{ route('bag.assignments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mobile:animate-fadeIn mobile:delay-100">
                    <label for="bag_id" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Type de sac' : 'Bag Type' }}</label>
                    <select name="bag_id" id="bag_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mobile:p-3">
                        <option value="">{{ $isFrench ? 'Sélectionner un type de sac' : 'Select a bag type' }}</option>
                        @foreach($bags as $bag)
                        <option value="{{ $bag->id }}" data-stock="{{ $bag->stock_quantity }}" {{ old('bag_id') == $bag->id ? 'selected' : '' }}>
                            {{ $bag->name }} ({{ $isFrench ? 'Stock' : 'Stock' }}: {{ $bag->stock_quantity }})
                        </option>
                        @endforeach
                    </select>
                    @if(count($bags) === 0)
                        <p class="text-amber-600 text-sm mt-2">{{ $isFrench ? 'Aucun sac disponible. Veuillez vérifier le stock.' : 'No bags available. Please check stock.' }}</p>
                    @else
                        <p class="text-gray-500 text-sm mt-2">{{ $isFrench ? 'Note: Les sacs avec un stock à 0 n\'apparaissent pas dans cette liste.' : 'Note: Bags with 0 stock are not shown in this list.' }}</p>
                    @endif
                    @error('bag_id')
                    <p class="text-red-500 text-xs mt-1 mobile:animate-shake">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mobile:animate-fadeIn mobile:delay-200">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Serveur' : 'Server' }}</label>
                    <select name="user_id" id="user_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mobile:p-3">
                        <option value="">{{ $isFrench ? 'Sélectionner un serveur' : 'Select a server' }}</option>
                        @foreach($servers as $server)
                        <option value="{{ $server->id }}" {{ old('user_id') == $server->id ? 'selected' : '' }}>
                            {{ $server->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="text-red-500 text-xs mt-1 mobile:animate-shake">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 mobile:animate-fadeIn mobile:delay-300">
                <label for="quantity_assigned" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Quantité à assigner' : 'Quantity to assign' }}</label>
                <input type="number" name="quantity_assigned" id="quantity_assigned" value="{{ old('quantity_assigned') }}" required min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mobile:p-3">
                <p id="stock-warning" class="text-red-500 text-xs mt-1 hidden mobile:animate-shake">{{ $isFrench ? 'La quantité demandée dépasse le stock disponible!' : 'Requested quantity exceeds available stock!' }}</p>
                @error('quantity_assigned')
                <p class="text-red-500 text-xs mt-1 mobile:animate-shake">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 mobile:animate-fadeIn mobile:delay-400">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Notes (facultatif)' : 'Notes (optional)' }}</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mobile:p-3">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-6 flex justify-end mobile:animate-fadeIn mobile:delay-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded shadow transition duration-150 ease-in-out mobile:transform mobile:hover:scale-105 mobile:w-full mobile:py-3">
                    {{ $isFrench ? 'Assigner les sacs' : 'Assign Bags' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Assignments -->
    <div class="mobile:animate-fadeIn mobile:delay-600">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">{{ $isFrench ? 'Assignations récentes' : 'Recent Assignments' }}</h2>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Desktop Table -->
            <div class="overflow-x-auto hidden md:block">
                <table class="min-w-full table-auto">
                    <thead class="bg-blue-50 text-blue-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Sac' : 'Bag' }}</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Serveur' : 'Server' }}</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentAssignments as $assignment)
                        <tr class="hover:bg-gray-50 mobile:transition mobile:duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $assignment->bag->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $assignment->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $assignment->quantity_assigned }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('bag.assignments.edit', $assignment) }}" class="text-blue-600 hover:text-blue-900 mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                                    <i class="fas fa-edit"></i> {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $isFrench ? 'Aucune assignation récente' : 'No recent assignments' }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4 p-4">
                @forelse($recentAssignments as $assignment)
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 mobile:animate-fadeIn mobile:duration-300 mobile:transform mobile:hover:scale-[1.01] mobile:transition mobile:duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-blue-600">{{ $assignment->bag->name }}</h3>
                            <p class="text-gray-600">{{ $assignment->user->name }}</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            {{ $assignment->quantity_assigned }}
                        </span>
                    </div>
                    
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Date' : 'Date' }}</p>
                            <p class="text-sm">{{ $assignment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('bag.assignments.edit', $assignment) }}"
                           class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                           <i class="fas fa-edit"></i> {{ $isFrench ? 'Modifier' : 'Edit' }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 text-center text-gray-500">
                    {{ $isFrench ? 'Aucune assignation récente' : 'No recent assignments' }}
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Mobile Animations */
    @media (max-width: 768px) {
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .mobile\:animate-slideUp {
            animation: slideUp 0.3s ease-out forwards;
        }
        
        .mobile\:animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .mobile\:animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .mobile\:animate-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Add padding to form elements on mobile */
        .mobile\:p-3 {
            padding: 0.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bagSelect = document.getElementById('bag_id');
    const quantityInput = document.getElementById('quantity_assigned');
    const stockWarning = document.getElementById('stock-warning');

    function checkStock() {
        const selectedOption = bagSelect.options[bagSelect.selectedIndex];
        if (!selectedOption.value) return;

        const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
        const requestedQuantity = parseInt(quantityInput.value) || 0;

        if (requestedQuantity > availableStock) {
            stockWarning.classList.remove('hidden');
            stockWarning.classList.add('mobile:animate-shake');
            setTimeout(() => stockWarning.classList.remove('mobile:animate-shake'), 500);
        } else {
            stockWarning.classList.add('hidden');
        }
    }

    bagSelect.addEventListener('change', checkStock);
    quantityInput.addEventListener('input', checkStock);

    // Initial check
    checkStock();

    // Add touch feedback for mobile
    if (window.innerWidth <= 768) {
        const buttons = document.querySelectorAll('button, a');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('mobile:transform', 'mobile:scale-105');
            });
            
            button.addEventListener('touchend', function() {
                this.classList.remove('mobile:transform', 'mobile:scale-105');
            });
        });
    }
});
</script>
@endsection