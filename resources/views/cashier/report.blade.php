@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-6 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    @include('buttons')

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-800 animate-fade-in flex items-center">
            <i class="mdi mdi-file-chart mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Rapports de Caisse' : 'Cash Reports' }}
        </h1>
    </div>

    <!-- Filter section -->
    <div class="bg-white shadow-lg rounded-2xl p-4 lg:p-6 mb-6 lg:mb-8 mobile-card animate-fade-in">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-filter-outline mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Sélectionner la période' : 'Select Period' }}
        </h2>
        <form action="{{ route('cashier.reports') }}" method="GET" class="flex flex-col space-y-4 lg:flex-row lg:items-end lg:space-y-0 lg:space-x-4">
            <div class="flex-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Date de début' : 'Start Date' }}
                </label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4">
            </div>
            <div class="flex-1">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Date de fin' : 'End Date' }}
                </label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4">
            </div>
            <div class="lg:flex-shrink-0">
                <button type="submit" 
                        class="w-full lg:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-500 text-white rounded-xl hover:from-blue-600 hover:to-blue-600 transition-all duration-200 transform hover:scale-105 shadow-lg font-medium">
                    <i class="mdi mdi-filter-outline mr-2"></i>{{ $isFrench ? 'Filtrer' : 'Filter' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden mobile-card animate-fade-in transform hover:scale-105 transition-transform duration-200">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="rounded-full p-3 bg-gradient-to-br from-blue-100 to-blue-200 mr-4">
                        <i class="mdi mdi-store text-blue-600 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Sessions de caisse' : 'Cash Sessions' }}</p>
                        <p class="text-2xl lg:text-3xl font-bold text-blue-700">{{ $statistics['total_sessions'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden mobile-card animate-fade-in transform hover:scale-105 transition-transform duration-200">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="rounded-full p-3 bg-gradient-to-br from-green-100 to-green-200 mr-4">
                        <i class="mdi mdi-cash-multiple text-green-600 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-600 font-medium truncate">{{ $isFrench ? 'Total caisse manipulé' : 'Total Cash Handled' }}</p>
                        <p class="text-lg lg:text-2xl font-bold text-green-700">{{ number_format($statistics['total_cash_handled'], 0, ',', ' ') }} <span class="text-sm">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden mobile-card animate-fade-in transform hover:scale-105 transition-transform duration-200 sm:col-span-2 lg:col-span-1">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="rounded-full p-3 bg-gradient-to-br from-yellow-100 to-yellow-200 mr-4">
                        <i class="mdi mdi-cash-register text-yellow-600 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Total versé' : 'Total Remitted' }}</p>
                        <p class="text-lg lg:text-2xl font-bold text-yellow-700">{{ number_format($statistics['total_remitted'], 0, ',', ' ') }} <span class="text-sm">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden mobile-card animate-fade-in transform hover:scale-105 transition-transform duration-200 sm:col-span-2 lg:col-span-3">
            <div class="p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="rounded-full p-3 bg-gradient-to-br from-red-100 to-red-200 mr-4">
                        <i class="mdi mdi-cash-remove text-red-600 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Total retraits' : 'Total Withdrawals' }}</p>
                        <p class="text-2xl lg:text-3xl font-bold text-red-700">{{ number_format($statistics['total_withdrawals'], 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions list -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden mobile-card animate-fade-in">
        <div class="p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="mdi mdi-history mr-2 text-gray-600"></i>
                {{ $isFrench ? 'Sessions de la période' : 'Period Sessions' }}
            </h2>

            @if(count($sessions) > 0)
                <!-- Desktop table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">{{ $isFrench ? 'Durée' : 'Duration' }}</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Caisse Initiale' : 'Initial Cash' }}</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Caisse Finale' : 'Final Cash' }}</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Montant Versé' : 'Amount Remitted' }}</th>
                                <th class="py-3 px-4 text-right text-sm font-semibold text-gray-600">{{ $isFrench ? 'Retraits' : 'Withdrawals' }}</th>
                                <th class="py-3 px-4 text-center text-sm font-semibold text-gray-600">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 border-b text-sm">
                                        {{ $session->start_time->format('d/m/Y') }}<br>
                                        <span class="text-xs text-gray-500">{{ $session->start_time->format('H:i') }} - {{ $session->end_time ? $session->end_time->format('H:i') : ($isFrench ? 'En cours' : 'Ongoing') }}</span>
                                    </td>
                                    <td class="py-3 px-4 border-b text-sm font-medium">{{ $session->duration }}</td>
                                    <td class="py-3 px-4 text-right border-b text-sm font-medium">{{ number_format($session->initial_cash, 0, ',', ' ') }} FCFA</td>
                                    <td class="py-3 px-4 text-right border-b text-sm font-medium">
                                        @if($session->end_time)
                                            {{ number_format($session->final_cash, 0, ',', ' ') }} FCFA
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right border-b text-sm font-medium">
                                        @if($session->end_time)
                                            {{ number_format($session->cash_remitted, 0, ',', ' ') }} FCFA
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right border-b text-sm font-medium">
                                        {{ number_format($session->total_withdrawals, 0, ',', ' ') }} FCFA
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
                <div class="lg:hidden space-y-4">
                    @foreach($sessions as $session)
                        <div class="bg-gradient-to-r from-gray-50 to-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $session->start_time->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $session->start_time->format('H:i') }} - {{ $session->end_time ? $session->end_time->format('H:i') : ($isFrench ? 'En cours' : 'Ongoing') }}</div>
                                    <div class="text-xs text-blue-600 font-medium mt-1">{{ $session->duration }}</div>
                                </div>
                                <a href="{{ route('cashier.session', $session->id) }}" 
                                   class="text-blue-500 hover:text-blue-700 text-2xl hover:scale-110 transition-transform">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-blue-50 p-2 rounded-lg">
                                    <div class="text-blue-600 text-xs font-medium">{{ $isFrench ? 'Initial' : 'Initial' }}</div>
                                    <div class="font-bold text-blue-800">{{ number_format($session->initial_cash, 0, ',', ' ') }}</div>
                                </div>
                                <div class="bg-green-50 p-2 rounded-lg">
                                    <div class="text-green-600 text-xs font-medium">{{ $isFrench ? 'Final' : 'Final' }}</div>
                                    <div class="font-bold text-green-800">
                                        @if($session->end_time)
                                            {{ number_format($session->final_cash, 0, ',', ' ') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-2 rounded-lg">
                                    <div class="text-yellow-600 text-xs font-medium">{{ $isFrench ? 'Versé' : 'Remitted' }}</div>
                                    <div class="font-bold text-yellow-800">
                                        @if($session->end_time)
                                            {{ number_format($session->cash_remitted, 0, ',', ' ') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-red-50 p-2 rounded-lg">
                                    <div class="text-red-600 text-xs font-medium">{{ $isFrench ? 'Retraits' : 'Withdrawals' }}</div>
                                    <div class="font-bold text-red-800">{{ number_format($session->total_withdrawals, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                    <i class="mdi mdi-cash-register text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">{{ $isFrench ? 'Aucune session de caisse trouvée pour cette période.' : 'No cash session found for this period.' }}</p>
                </div>
            @endif
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
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endsection
