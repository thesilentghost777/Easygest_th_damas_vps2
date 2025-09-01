@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            <h1 class="text-xl font-bold text-white animate-fade-in">
                <i class="fas fa-shopping-bag mr-2"></i> 
                {{ $isFrench ? 'Gestion Stock Sacs' : 'Bag Stock Management' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Gérer les stocks de vos sacs' : 'Manage your bag inventory' }}
            </p>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Mobile Bag Cards -->
                <div class="space-y-4">
                    @forelse($bags as $bag)
                        <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right {{ $bag->isLowStock() ? 'border-red-200 bg-red-50' : 'border-gray-200' }}" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $bag->name }}</h3>
                                    <p class="text-blue-600 font-semibold text-lg">{{ number_format($bag->price, 2) }} FCFA</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($bag->isLowStock())
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $isFrench ? 'Stock bas' : 'Low stock' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-gray-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-gray-600 mb-1">{{ $isFrench ? 'Stock' : 'Stock' }}</p>
                                    <p class="font-bold {{ $bag->stock_quantity > 0 ? 'text-blue-700' : 'text-red-700' }} text-lg">
                                        {{ number_format($bag->stock_quantity) }}
                                    </p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-blue-600 mb-1">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}</p>
                                    <p class="font-bold text-blue-700 text-lg">{{ number_format($bag->alert_threshold) }}</p>
                                </div>
                            </div>

                            <!-- Actions rapides -->
                            <div class="space-y-3">
                                <!-- Ajouter Stock -->
                                <form method="POST" action="{{ route('bags.add-stock', $bag) }}">
                                    @csrf
                                    <div class="flex space-x-2">
                                        <input type="number" name="quantity" class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}" min="1" max="10000" required>
                                        <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200">
                                            <i class="fas fa-plus mr-1"></i> {{ $isFrench ? 'Ajouter' : 'Add' }}
                                        </button>
                                    </div>
                                </form>

                                <!-- Retirer Stock -->
                                <form method="POST" action="{{ route('bags.remove-stock', $bag) }}">
                                    @csrf
                                    <div class="flex space-x-2">
                                        <input type="number" name="quantity" class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                               placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}" min="1" max="{{ $bag->stock_quantity }}" required {{ $bag->stock_quantity == 0 ? 'disabled' : '' }}>
                                        <button class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200 {{ $bag->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                                {{ $bag->stock_quantity == 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-minus mr-1"></i> {{ $isFrench ? 'Retirer' : 'Remove' }}
                                        </button>
                                    </div>
                                </form>

                                <!-- Modifier Seuil -->
                                <form method="POST" action="{{ route('bags.update-alert', $bag) }}">
                                    @csrf
                                    <div class="flex space-x-2">
                                        <input type="number" name="alert_threshold" class="flex-1 px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                               placeholder="{{ $isFrench ? 'Nouveau seuil' : 'New threshold' }}" min="0" max="1000" 
                                               value="{{ $bag->alert_threshold }}">
                                        <button class="bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200">
                                            <i class="fas fa-bell mr-1"></i> {{ $isFrench ? 'Seuil' : 'Threshold' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fade-in">
                            <i class="fas fa-shopping-bag text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $isFrench ? 'Aucun sac trouvé' : 'No bags found' }}</h3>
                            <p class="text-gray-500 mb-4">{{ $isFrench ? 'Votre stock de sacs est vide.' : 'Your bag inventory is empty.' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @include('buttons')
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-blue-700 mb-2">
                    <i class="fas fa-shopping-bag mr-2"></i> 
                    {{ $isFrench ? 'Gestion Stock Sacs' : 'Bag Stock Management' }}
                </h1>
                <p class="text-gray-600">{{ $isFrench ? 'Gérer les stocks de vos sacs' : 'Manage your bag inventory' }}</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Desktop Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($bags as $bag)
                    <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300 {{ $bag->isLowStock() ? 'border-2 border-red-200 bg-red-50' : '' }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $bag->name }}</h3>
                                <p class="text-blue-600 font-semibold text-lg">{{ number_format($bag->price, 2) }} FCFA</p>
                            </div>
                            @if($bag->isLowStock())
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $isFrench ? 'Stock bas' : 'Low stock' }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-xl text-center">
                                <p class="text-sm font-medium text-gray-600 mb-1">{{ $isFrench ? 'Stock' : 'Stock' }}</p>
                                <p class="font-bold {{ $bag->stock_quantity > 0 ? 'text-blue-700' : 'text-red-700' }} text-2xl">
                                    {{ number_format($bag->stock_quantity) }}
                                </p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-xl text-center">
                                <p class="text-sm font-medium text-blue-600 mb-1">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}</p>
                                <p class="font-bold text-blue-700 text-2xl">{{ number_format($bag->alert_threshold) }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3">
                            <!-- Ajouter Stock -->
                            <form method="POST" action="{{ route('bags.add-stock', $bag) }}">
                                @csrf
                                <div class="flex space-x-2">
                                    <input type="number" name="quantity" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}" min="1" max="10000" required>
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-plus mr-1"></i> {{ $isFrench ? 'Ajouter' : 'Add' }}
                                    </button>
                                </div>
                            </form>

                            <!-- Retirer Stock -->
                            <form method="POST" action="{{ route('bags.remove-stock', $bag) }}">
                                @csrf
                                <div class="flex space-x-2">
                                    <input type="number" name="quantity" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                           placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}" min="1" max="{{ $bag->stock_quantity }}" required {{ $bag->stock_quantity == 0 ? 'disabled' : '' }}>
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 {{ $bag->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                            {{ $bag->stock_quantity == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus mr-1"></i> {{ $isFrench ? 'Retirer' : 'Remove' }}
                                    </button>
                                </div>
                            </form>

                            <!-- Modifier Seuil -->
                            <form method="POST" action="{{ route('bags.update-alert', $bag) }}">
                                @csrf
                                <div class="flex space-x-2">
                                    <input type="number" name="alert_threshold" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                           placeholder="{{ $isFrench ? 'Nouveau seuil' : 'New threshold' }}" min="0" max="1000" 
                                           value="{{ $bag->alert_threshold }}">
                                    <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-bell mr-1"></i> {{ $isFrench ? 'Seuil' : 'Threshold' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-lg p-12 text-center">
                        <i class="fas fa-shopping-bag text-gray-400 text-8xl mb-6"></i>
                        <h3 class="text-2xl font-medium text-gray-900 mb-2">{{ $isFrench ? 'Aucun sac trouvé' : 'No bags found' }}</h3>
                        <p class="text-gray-500 text-lg">{{ $isFrench ? 'Votre stock de sacs est vide.' : 'Your bag inventory is empty.' }}</p>
                    </div>
                @endforelse
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
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection