@extends('layouts.app')

@section('title', $isFrench ? 'Gestion des Sacs Avariés' : 'Damaged Bags Management')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Gestion des Sacs Avariés' : 'Damaged Bags Management' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Déclarer des sacs avariés' : 'Declare damaged bags' }}
            </p>
        </div>
    </div>

    <br><br>
    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Mobile Info Banner -->
                <div class="bg-blue-50 rounded-2xl p-4 mb-6 border-l-4 border-blue-500 animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-blue-700 text-sm">
                            {{ $isFrench ? 'Cette interface vous permet de déclarer des sacs avariés. La quantité déclarée sera automatiquement déduite du stock disponible.' : 'This interface allows you to declare damaged bags. The declared quantity will be automatically deducted from available stock.' }}
                        </p>
                    </div>
                </div>

                <!-- Mobile Bag Cards -->
                <div class="space-y-4">
                    @forelse($bags as $bag)
                        <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $bag->name }}</h3>
                                    <p class="text-blue-600 font-semibold text-lg">{{ number_format($bag->price, 2) }} XAF</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($bag->stock_quantity <= $bag->alert_threshold)
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                            {{ $isFrench ? 'Stock faible' : 'Low Stock' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Stock normal' : 'Normal Stock' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-gray-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-gray-600 mb-1">
                                        {{ $isFrench ? 'Stock disponible' : 'Available stock' }}
                                    </p>
                                    <p class="font-bold {{ $bag->stock_quantity <= $bag->alert_threshold ? 'text-red-700' : 'text-green-700' }} text-lg">
                                        {{ $bag->stock_quantity }}
                                    </p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-blue-600 mb-1">
                                        {{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}
                                    </p>
                                    <p class="font-bold text-blue-700 text-lg">{{ $bag->alert_threshold }}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-center">
                                <a href="{{ route('damaged-bags.create', $bag->id) }}" class="bg-red-100 text-red-700 py-3 px-6 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $isFrench ? 'Déclarer avarie' : 'Declare damage' }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fade-in">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucun sac en stock' : 'No bags in stock' }}
                            </h3>
                            <p class="text-gray-500 mb-4">
                                {{ $isFrench ? 'Aucun sac n\'est actuellement en stock.' : 'No bags are currently in stock.' }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="container mx-auto py-6">
            @include('buttons')

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-blue-700">
                        <i class="fas fa-trash-alt mr-2"></i> {{ $isFrench ? 'Gestion des Sacs Avariés' : 'Damaged Bags Management' }}
                    </h1>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="mb-4 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <p class="text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i> {{ $isFrench ? 'Cette interface vous permet de déclarer des sacs avariés. La quantité déclarée sera automatiquement déduite du stock disponible.' : 'This interface allows you to declare damaged bags. The declared quantity will be automatically deducted from available stock.' }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $isFrench ? 'Nom du Sac' : 'Bag Name' }}</th>
                                <th class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-400 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $isFrench ? 'Prix Unitaire' : 'Unit Price' }}</th>
                                <th class="px-6 py-3 bg-gradient-to-r from-blue-400 to-green-500 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $isFrench ? 'Stock Disponible' : 'Available Stock' }}</th>
                                <th class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $isFrench ? 'Seuil d\'Alerte' : 'Alert Threshold' }}</th>
                                <th class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bags as $bag)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bag->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($bag->price, 2) }} XAF</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bag->stock_quantity <= $bag->alert_threshold ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $bag->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bag->alert_threshold }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('damaged-bags.create', $bag->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md transition">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $isFrench ? 'Déclarer avarie' : 'Declare damage' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ $isFrench ? 'Aucun sac en stock actuellement.' : 'No bags currently in stock.' }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
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
