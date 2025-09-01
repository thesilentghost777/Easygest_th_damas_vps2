@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Modifier un Sac' : 'Edit Bag' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $bag->name }}
            </p>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('bags.update', $bag) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Mobile Name Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="name" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Nom du sac' : 'Bag name' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $bag->name) }}" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Price Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="price" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Prix (FCFA)' : 'Price (FCFA)' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">FCFA</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price', $bag->price) }}" required min="0" step="0.01"
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Stock Quantity Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="stock_quantity" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Quantité en stock' : 'Stock quantity' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">#</span>
                            </div>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $bag->stock_quantity) }}" required min="0"
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Alert Threshold Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="alert_threshold" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <input type="number" name="alert_threshold" id="alert_threshold" value="{{ old('alert_threshold', $bag->alert_threshold) }}" required min="1"
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                        <p class="text-gray-500 text-sm mt-2">
                            {{ $isFrench ? 'Vous serez alerté lorsque le stock descendra en dessous de ce seuil' : 'You will be alerted when stock falls below this threshold' }}
                        </p>
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-6 w-6 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                        </button>
                        <a href="{{ route('bags.index') }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @include('buttons')
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-blue-700">{{ $isFrench ? 'Modifier un Sac' : 'Edit Bag' }}</h1>
                <p class="text-gray-600 mt-1">{{ $isFrench ? 'Modifiez les informations du sac' : 'Edit the bag information' }} {{ $bag->name }}</p>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
                <form action="{{ route('bags.update', $bag) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Nom du sac' : 'Bag name' }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $bag->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Prix unitaire (FCFA)' : 'Unit price (FCFA)' }}</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $bag->price) }}" required min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stock_quantity" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Quantité en stock' : 'Stock quantity' }}</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $bag->stock_quantity) }}" required min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('stock_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="alert_threshold" class="block text-gray-700 text-sm font-bold mb-2">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}</label>
                        <input type="number" name="alert_threshold" id="alert_threshold" value="{{ old('alert_threshold', $bag->alert_threshold) }}" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-gray-500 text-xs mt-1">{{ $isFrench ? 'Vous serez alerté lorsque le stock descendra en dessous de ce seuil' : 'You will be alerted when stock falls below this threshold' }}</p>
                        @error('alert_threshold')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('bags.index2') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded shadow transition duration-150 ease-in-out">
                            {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection
