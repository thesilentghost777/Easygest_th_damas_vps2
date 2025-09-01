@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')

    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 animate-fade-in">
        <h2 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-6 text-gray-800 flex items-center">
            <i class="mdi mdi-clock-outline mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Contrôle des Horaires' : 'Time Control' }}
        </h2>

        <!-- Clock component -->
        @include('components.clock')

        <!-- Notification messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 rounded-r-xl mb-4 shadow-md animate-slide-in" role="alert">
                <div class="flex items-center">
                    <i class="mdi mdi-check-circle mr-2"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 rounded-r-xl mb-4 shadow-md animate-slide-in" role="alert">
                <div class="flex items-center">
                    <i class="mdi mdi-alert-circle mr-2"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Arrival/Departure buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4 mb-6 lg:mb-8">
            <form action="{{ route('horaire.arrivee') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 lg:py-3 px-4 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center justify-center">
                    <i class="mdi mdi-login mr-2 text-xl lg:text-base"></i>
                    {{ $isFrench ? 'Marquer l\'arrivée' : 'Mark Arrival' }}
                </button>
            </form>

            <form action="{{ route('horaire.depart') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 lg:py-3 px-4 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center justify-center">
                    <i class="mdi mdi-logout mr-2 text-xl lg:text-base"></i>
                    {{ $isFrench ? 'Marquer le départ' : 'Mark Departure' }}
                </button>
            </form>
        </div>

        <!-- Manual time entry form -->
        <div class="bg-gray-50 p-4 lg:p-6 rounded-xl mb-6 lg:mb-8 mobile-form">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                <i class="mdi mdi-pencil mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Saisie manuelle des horaires' : 'Manual Time Entry' }}
            </h3>
            <form action="{{ route('horaire.enregistrer') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="mobile-field">
                        <label for="arrive" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Heure d\'arrivée' : 'Arrival Time' }}</label>
                        <input type="time" name="arrive" id="arrive" required
                            class="w-full py-3 lg:py-2 px-3 rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-base">
                    </div>
                    <div class="mobile-field">
                        <label for="depart" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Heure de départ' : 'Departure Time' }}</label>
                        <input type="time" name="depart" id="depart" required
                            class="w-full py-3 lg:py-2 px-3 rounded-xl lg:rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-base">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="w-full lg:w-auto bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 lg:py-2 px-6 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <i class="mdi mdi-check mr-2"></i>{{ $isFrench ? 'Valider' : 'Validate' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Schedule table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <h3 class="text-lg font-semibold p-4 lg:p-6 border-b border-gray-200 text-gray-800 flex items-center">
                <i class="mdi mdi-table mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Historique des horaires' : 'Schedule History' }}
            </h3>
            
            <!-- Desktop table (hidden on mobile) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Arrivée' : 'Arrival' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Départ' : 'Departure' }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($horaires as $horaire)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $horaire->arrive->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horaire->arrive->format('H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $horaire->depart ? $horaire->depart->format('H:i') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile card view (visible only on mobile) -->
            <div class="lg:hidden p-4 space-y-4">
                @forelse($horaires as $horaire)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm animate-fade-in transform hover:scale-105 transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900">{{ $horaire->arrive->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Arrivée' : 'Arrival' }}</div>
                                <div class="font-bold text-blue-600 text-lg">{{ $horaire->arrive->format('H:i') }}</div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Départ' : 'Departure' }}</div>
                                <div class="font-bold text-green-600 text-lg">
                                    {{ $horaire->depart ? $horaire->depart->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="mdi mdi-clock-outline text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucun horaire enregistré' : 'No schedules recorded' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .mobile-form {
        transition: all 0.2s ease-out;
    }
    .mobile-field {
        transition: all 0.2s ease-out;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-form:focus-within {
            transform: translateY(-2px);
        }
        .mobile-field:focus-within {
            transform: translateY(-1px);
        }
        /* Touch targets */
        button, input {
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
