@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-6 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    @include('buttons')
    <!-- Header with mobile optimization -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-800 animate-fade-in">
            {{ $isFrench ? 'Gestion de la caisse' : 'Cash Management' }}
        </h1>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('cashier.reports') }}" 
               class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-600 text-white rounded-lg hover:from-blue-700 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg text-center">
                <i class="mdi mdi-file-chart mr-2"></i>{{ $isFrench ? 'Rapports' : 'Reports' }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-lg shadow-md animate-slide-in" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg shadow-md animate-slide-in" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main grid - mobile first approach -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Session card - spans full width on mobile, 2 cols on desktop -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-4 lg:p-6 mobile-card animate-fade-in">
            @if($openSession)
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <h2 class="text-lg lg:text-xl font-semibold text-green-600 flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                        {{ $isFrench ? 'Session Active' : 'Active Session' }}
                    </h2>
                    <span class="px-3 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $isFrench ? 'Démarrée il y a' : 'Started' }} {{ $openSession->start_time->diffForHumans() }}
                    </span>
                </div>
                
                <!-- Mobile-optimized grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4 mb-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                        <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Caisse initiale' : 'Initial Cash' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-blue-800">{{ number_format($openSession->initial_cash, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                        <p class="text-sm text-green-600 mb-1 font-medium">{{ $isFrench ? 'Monnaie reçue' : 'Change Received' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-green-800">{{ number_format($openSession->initial_change, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200 sm:col-span-2 lg:col-span-1">
                        <p class="text-sm text-purple-600 mb-1 font-medium">{{ $isFrench ? 'Compte mobile initial' : 'Initial Mobile Balance' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-purple-800">{{ number_format($openSession->initial_mobile_balance, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('cashier.session', $openSession->id) }}" 
                       class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200 transform hover:scale-105 shadow-lg font-medium">
                        {{ $isFrench ? 'Gérer la session' : 'Manage Session' }}
                    </a>
                </div>
            @else
                <div class="text-center py-8 lg:py-12">
                    <div class="text-6xl lg:text-7xl text-gray-300 mb-4 animate-bounce-gentle">
                        <i class="mdi mdi-cash-register"></i>
                    </div>
                    <h2 class="text-xl lg:text-2xl font-semibold text-gray-600 mb-2">
                        {{ $isFrench ? 'Aucune session active' : 'No Active Session' }}
                    </h2>
                    <p class="text-gray-500 mb-6 px-4">
                        {{ $isFrench ? 'Pour commencer à travailler, démarrez une nouvelle session de caisse.' : 'To start working, start a new cash session.' }}
                    </p>
                    <button onclick="document.getElementById('startSessionModal').classList.remove('hidden')" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-500 text-white rounded-xl hover:from-blue-500 hover:to-blue-600 transition-all duration-200 transform hover:scale-105 shadow-lg font-medium">
                        <i class="mdi mdi-plus-circle mr-2"></i>{{ $isFrench ? 'Démarrer une nouvelle session' : 'Start New Session' }}
                    </button>
                </div>
            @endif
        </div>

        <!-- Statistics card -->
        <div class="bg-white rounded-2xl shadow-lg p-4 lg:p-6 mobile-card animate-fade-in">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="mdi mdi-chart-line mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Statistiques' : 'Statistics' }}
            </h2>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-3 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Sessions récentes' : 'Recent Sessions' }}</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $sessions->count() }}</p>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 rounded-lg border border-green-100">
                    <p class="text-sm text-green-600 mb-1 font-medium">{{ $isFrench ? "Session aujourd'hui" : 'Session Today' }}</p>
                    <p class="text-2xl font-bold text-green-700">
                        {{ $sessions->where('start_time', '>=', \Carbon\Carbon::today())->count() }}
                    </p>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-3 rounded-lg border border-purple-100">
                    <p class="text-sm text-purple-600 mb-1 font-medium">{{ $isFrench ? 'Session ce mois' : 'Session This Month' }}</p>
                    <p class="text-2xl font-bold text-purple-700">
                        {{ $sessions->where('start_time', '>=', \Carbon\Carbon::now()->startOfMonth())->count() }}
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('cashier.reports') }}" 
                       class="text-blue-500 hover:text-blue-700 text-sm font-medium hover:underline">
                        <i class="mdi mdi-chart-bar mr-1"></i>{{ $isFrench ? 'Voir les rapports détaillés' : 'View Detailed Reports' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent sessions table -->
    <div class="bg-white rounded-2xl shadow-lg p-4 lg:p-6 mobile-card animate-fade-in">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-history mr-2 text-gray-600"></i>
            {{ $isFrench ? 'Sessions récentes' : 'Recent Sessions' }}
        </h2>

        @if($sessions->count() > 0)
            <!-- Mobile-friendly table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="border-b-2 border-gray-100">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Caisse Initiale' : 'Initial Cash' }}</th>
                            <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Caisse Finale' : 'Final Cash' }}</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-600">{{ $isFrench ? 'Durée' : 'Duration' }}</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-600">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-600">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 border-b text-sm">{{ $session->id }}</td>
                                <td class="py-3 px-4 border-b text-sm">{{ $session->start_time->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 text-right border-b text-sm font-medium">{{ number_format($session->initial_cash, 0, ',', ' ') }} FCFA</td>
                                <td class="py-3 px-4 text-right border-b text-sm font-medium">
                                    @if($session->end_time)
                                        {{ number_format($session->final_cash, 0, ',', ' ') }} FCFA
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center border-b text-sm">{{ $session->duration }}</td>
                                <td class="py-3 px-4 text-center border-b">
                                    @if($session->end_time)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $isFrench ? 'Clôturée' : 'Closed' }}</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">{{ $isFrench ? 'Active' : 'Active' }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center border-b">
                                    <a href="{{ route('cashier.session', $session->id) }}" 
                                       class="text-blue-500 hover:text-blue-700 text-lg hover:scale-110 transition-transform">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile card view -->
            <div class="lg:hidden space-y-3">
                @foreach($sessions as $session)
                    <div class="bg-gradient-to-r from-gray-50 to-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-bold text-gray-800">#{{ $session->id }}</span>
                                @if($session->end_time)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $isFrench ? 'Clôturée' : 'Closed' }}</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">{{ $isFrench ? 'Active' : 'Active' }}</span>
                                @endif
                            </div>
                            <a href="{{ route('cashier.session', $session->id) }}" 
                               class="text-blue-500 hover:text-blue-700 text-xl hover:scale-110 transition-transform">
                                <i class="mdi mdi-eye"></i>
                            </a>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            {{ $session->start_time->format('d/m/Y H:i') }} • {{ $session->duration }}
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">{{ $isFrench ? 'Initial:' : 'Initial:' }}</span>
                                <span class="font-medium">{{ number_format($session->initial_cash, 0, ',', ' ') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ $isFrench ? 'Final:' : 'Final:' }}</span>
                                <span class="font-medium">
                                    @if($session->end_time)
                                        {{ number_format($session->final_cash, 0, ',', ' ') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $sessions->links() }}
            </div>
        @else
            <div class="bg-gray-50 p-8 rounded-xl text-center">
                <i class="mdi mdi-cash-register text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">{{ $isFrench ? 'Aucune session de caisse trouvée.' : 'No cash session found.' }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Modal for starting session -->
<div id="startSessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="mdi mdi-cash-register mr-2 text-green-600"></i>
                    {{ $isFrench ? 'Démarrer une nouvelle session' : 'Start New Session' }}
                </h3>
                <button onclick="document.getElementById('startSessionModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500 text-2xl hover:scale-110 transition-transform">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>

            <form action="{{ route('cashier.start-session') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="initial_cash" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? "Montant d'argent reçu" : 'Amount of Money Received' }}
                        </label>
                        <div class="relative">
                            <input type="number" name="initial_cash" id="initial_cash" required
                                class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="initial_change" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Montant de monnaie reçu' : 'Amount of Change Received' }}
                        </label>
                        <div class="relative">
                            <input type="number" name="initial_change" id="initial_change" required
                                class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- MOMO Amount -->
                    <div>
                        <label for="momo_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <div class="w-6 h-4 mr-2 bg-yellow-400 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-black">M</span>
                                </div>
                                {{ $isFrench ? 'Montant MOMO (MTN Mobile Money)' : 'MOMO Amount (MTN Mobile Money)' }}
                            </div>
                        </label>
                        <div class="relative">
                            <input type="number" id="momo_amount"
                                class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="0" oninput="calculateTotal()">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- OM Amount -->
                    <div>
                        <label for="om_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <div class="w-6 h-4 mr-2 bg-orange-500 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">O</span>
                                </div>
                                {{ $isFrench ? 'Montant OM (Orange Money)' : 'OM Amount (Orange Money)' }}
                            </div>
                        </label>
                        <div class="relative">
                            <input type="number" id="om_amount"
                                class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 focus:border-blue-500 focus:ring-blue-500 text-lg"
                                placeholder="0" oninput="calculateTotal()">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="initial_mobile_balance" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Solde compte mobile initial (somme de MOMO et OM)' : 'Initial Mobile Account Balance (MOMO + OM)' }}
                        </label>
                        <div class="relative">
                            <input type="number" name="initial_mobile_balance" id="initial_mobile_balance" required
                                class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 bg-gray-50 text-lg"
                                placeholder="0" readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Notes (Distribution MOMO|OM)' : 'Notes (MOMO|OM Distribution)' }}
                        </label>
                        <textarea id="notes" name="notes" rows="2"
                            class="block w-full rounded-xl border-gray-300 py-3 px-4 focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                            placeholder="{{ $isFrench ? 'Notes supplémentaires...' : 'Additional notes...' }}" readonly></textarea>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button"
                        onclick="document.getElementById('startSessionModal').classList.add('hidden')"
                        class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        {{ $isFrench ? 'Démarrer la session' : 'Start Session' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-bounce-gentle { animation: bounce 2s infinite; }
    .mobile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<script>
    function calculateTotal() {
        const momoAmount = parseFloat(document.getElementById('momo_amount').value) || 0;
        const omAmount = parseFloat(document.getElementById('om_amount').value) || 0;
        const total = momoAmount + omAmount;
        
        document.getElementById('initial_mobile_balance').value = total;
        document.getElementById('notes').value = `MOMO: ${momoAmount} FCFA | OM: ${omAmount} FCFA`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
    });
</script>
@endsection
