@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <h1 class="text-2xl lg:text-3xl font-bold text-center mb-6 lg:mb-8 text-gray-900 animate-fade-in">
        {{ $isFrench ? 'Mes Primes' : 'My Bonuses' }}
    </h1>

    @if($hasPrimes)
    <div class="celebration-container mb-6 lg:mb-8 animate-fade-in">
        <div class="text-center">
            <div class="inline-block animate-bounce">
                🎉
            </div>
            <div class="inline-block animate-bounce delay-100">
                👏
            </div>
            <div class="inline-block animate-bounce delay-200">
                🌟
            </div>
        </div>
        <p class="text-center text-lg lg:text-xl font-semibold text-green-600 mt-4">
            {{ $isFrench ? 'Félicitations ! Vous avez reçu des primes !' : 'Congratulations! You have received bonuses!' }}
        </p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 animate-fade-in">
        <!-- Total section with enhanced mobile design -->
        <div class="mb-6 bg-green-50 rounded-xl p-4 lg:p-6 border border-green-200 mobile-card transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg lg:text-xl font-semibold mb-2 text-gray-900 flex items-center">
                        <i class="mdi mdi-gift mr-2 text-green-600"></i>
                        {{ $isFrench ? 'Total des primes reçues' : 'Total Bonuses Received' }}
                    </h2>
                    <p class="text-2xl lg:text-3xl font-bold text-green-600">{{ number_format($totalPrimes, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="hidden lg:block">
                    <i class="mdi mdi-cash-multiple text-4xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Desktop table (hidden on mobile) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Montant' : 'Amount' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($primes as $prime)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $prime->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $prime->libelle }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold text-sm">
                            {{ number_format($prime->montant, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="mdi mdi-gift-outline text-4xl text-gray-300 mb-2"></i>
                                <p>{{ $isFrench ? 'Vous n\'avez pas encore reçu de prime' : 'You haven\'t received any bonuses yet' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile card view (visible only on mobile) -->
        <div class="lg:hidden space-y-4">
            @forelse($primes as $prime)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm animate-fade-in transform hover:scale-105 transition-all duration-200 mobile-card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900 mb-1">{{ $prime->libelle }}</div>
                            <div class="text-xs text-gray-500">{{ $prime->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">{{ number_format($prime->montant, 0, ',', ' ') }} FCFA</div>
                            <div class="text-xs text-gray-500">{{ $isFrench ? 'Prime' : 'Bonus' }}</div>
                        </div>
                    </div>
                    
                    <div class="bg-green-100 p-2 rounded-lg">
                        <div class="flex items-center">
                            <i class="mdi mdi-gift text-green-600 mr-2"></i>
                            <span class="text-xs text-green-700 font-medium">{{ $isFrench ? 'Prime accordée' : 'Bonus awarded' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mdi mdi-gift-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Vous n\'avez pas encore reçu de prime' : 'You haven\'t received any bonuses yet' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .mobile-card {
        transition: all 0.2s ease-out;
    }
    
    .celebration-container {
        animation: fadeIn 1s ease-out;
    }

    .delay-100 {
        animation-delay: 0.1s;
    }

    .delay-200 {
        animation-delay: 0.2s;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card:active {
            transform: scale(0.98) !important;
        }
        /* Touch targets */
        button, .mobile-card {
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