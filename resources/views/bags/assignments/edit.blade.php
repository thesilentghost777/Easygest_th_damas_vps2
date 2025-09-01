@extends('layouts.app')

@section('content')
<div class="mobile-container mx-auto px-4 py-6 sm:hidden">
    @include('buttons')
    
    <div class="mb-6 animate-fadeIn">
        <h1 class="text-3xl font-bold text-blue-700 text-center transform transition-transform duration-300 hover:scale-105">
            @if($isFrench)
                Modifier l'Assignation
            @else
                Edit Assignment
            @endif
        </h1>
        <p class="text-gray-600 mt-2 text-center animate-pulse">
            @if($isFrench)
                Modifiez les informations de l'assignation de sacs
            @else
                Edit bag assignment information
            @endif
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-6 animate-slideUp">
        <form action="{{ route('bag.assignments.update', $assignment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5 transform transition-transform duration-200 active:scale-95">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Type de sac
                    @else
                        Bag Type
                    @endif
                </label>
                <div class="bg-gray-100 px-4 py-3 rounded-xl border-l-4 border-blue-500">
                    {{ $assignment->bag->name }}
                </div>
            </div>

            <div class="mb-5 transform transition-transform duration-200 active:scale-95">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Serveur
                    @else
                        Server
                    @endif
                </label>
                <div class="bg-gray-100 px-4 py-3 rounded-xl border-l-4 border-blue-500">
                    {{ $assignment->user->name }}
                </div>
            </div>

            <div class="mb-5 transform transition-transform duration-200 active:scale-95">
                <label for="quantity_assigned" class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Quantité assignée
                    @else
                        Assigned Quantity
                    @endif
                </label>
                <input type="number" name="quantity_assigned" id="quantity_assigned"
                    value="{{ old('quantity_assigned', $assignment->quantity_assigned) }}"
                    required min="1"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all duration-200"
                    data-original="{{ $assignment->quantity_assigned }}"
                    data-stock="{{ $assignment->bag->stock_quantity }}">
                <p id="stock-warning" class="text-red-500 text-xs mt-1 hidden">
                    @if($isFrench)
                        La quantité demandée dépasse le stock disponible!
                    @else
                        Requested quantity exceeds available stock!
                    @endif
                </p>
                @error('quantity_assigned')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 transform transition-transform duration-200 active:scale-95">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Notes (facultatif)
                    @else
                        Notes (optional)
                    @endif
                </label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all duration-200">{{ old('notes', $assignment->notes) }}</textarea>
            </div>

            <div class="flex justify-between space-x-4">
                <a href="{{ route('bag.assignments.create') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-xl shadow-md text-center transition-all duration-200 active:bg-gray-400 transform active:scale-95">
                    @if($isFrench)
                        Annuler
                    @else
                        Cancel
                    @endif
                </a>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl shadow-md transition-all duration-200 active:bg-blue-800 transform active:scale-95">
                    @if($isFrench)
                        Mettre à jour
                    @else
                        Update
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<div class="hidden sm:block max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Version PC (identique à l'original) -->
    @include('buttons')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-blue-700">
            @if($isFrench)
                Modifier l'Assignation
            @else
                Edit Assignment
            @endif
        </h1>
        <p class="text-gray-600 mt-1">
            @if($isFrench)
                Modifiez les informations de l'assignation de sacs
            @else
                Edit bag assignment information
            @endif
        </p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('bag.assignments.update', $assignment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Type de sac
                    @else
                        Bag Type
                    @endif
                </label>
                <div class="bg-gray-100 px-3 py-2 rounded-md">
                    {{ $assignment->bag->name }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Serveur
                    @else
                        Server
                    @endif
                </label>
                <div class="bg-gray-100 px-3 py-2 rounded-md">
                    {{ $assignment->user->name }}
                </div>
            </div>

            <div class="mb-4">
                <label for="quantity_assigned" class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Quantité assignée
                    @else
                        Assigned Quantity
                    @endif
                </label>
                <input type="number" name="quantity_assigned" id="quantity_assigned"
                    value="{{ old('quantity_assigned', $assignment->quantity_assigned) }}"
                    required min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    data-original="{{ $assignment->quantity_assigned }}"
                    data-stock="{{ $assignment->bag->stock_quantity }}">
                <p id="stock-warning" class="text-red-500 text-xs mt-1 hidden">
                    @if($isFrench)
                        La quantité demandée dépasse le stock disponible!
                    @else
                        Requested quantity exceeds available stock!
                    @endif
                </p>
                @error('quantity_assigned')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">
                    @if($isFrench)
                        Notes (facultatif)
                    @else
                        Notes (optional)
                    @endif
                </label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $assignment->notes) }}</textarea>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('bag.assignments.create') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                    @if($isFrench)
                        Annuler
                    @else
                        Cancel
                    @endif
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded shadow transition duration-150 ease-in-out">
                    @if($isFrench)
                        Mettre à jour
                    @else
                        Update
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_assigned');
    const stockWarning = document.getElementById('stock-warning');
    const originalQuantity = parseInt(quantityInput.getAttribute('data-original'));
    const availableStock = parseInt(quantityInput.getAttribute('data-stock'));

    function checkStock() {
        const newQuantity = parseInt(quantityInput.value) || 0;
        // Calculer la différence par rapport à la valeur originale
        const difference = newQuantity - originalQuantity;

        // Si la différence est positive (augmentation), vérifier si le stock est suffisant
        if (difference > 0 && difference > availableStock) {
            stockWarning.classList.remove('hidden');
            quantityInput.classList.add('border-red-500');
            quantityInput.classList.remove('border-gray-200', 'border-blue-500');
        } else {
            stockWarning.classList.add('hidden');
            quantityInput.classList.remove('border-red-500');
            if (quantityInput === document.activeElement) {
                quantityInput.classList.add('border-blue-500');
            } else {
                quantityInput.classList.add('border-gray-200');
            }
        }
    }

    quantityInput.addEventListener('input', checkStock);
    quantityInput.addEventListener('focus', function() {
        this.classList.remove('border-gray-200');
        this.classList.add('border-blue-500');
    });
    quantityInput.addEventListener('blur', function() {
        if (!stockWarning.classList.contains('hidden')) {
            this.classList.add('border-red-500');
            this.classList.remove('border-blue-500');
        } else {
            this.classList.remove('border-blue-500');
            this.classList.add('border-gray-200');
        }
    });

    // Vérification initiale
    checkStock();
});
</script>

<style>
    /* Animations pour mobile */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.4s ease-out;
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    
    /* Styles spécifiques mobile */
    @media (max-width: 640px) {
        .mobile-container {
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        input, textarea, select {
            font-size: 16px !important; /* Empêche le zoom sur iOS */
        }
        
        .transform.active\:scale-95:active {
            transform: scale(0.95);
        }
        
        button, a {
            touch-action: manipulation; /* Améliore la réactivité tactile */
        }
    }
</style>
@endsection