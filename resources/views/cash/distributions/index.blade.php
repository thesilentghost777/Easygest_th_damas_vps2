@extends('layouts.app')

@section('content')
<br><br>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    <!-- Mobile Header -->
    <div class="block md:hidden bg-blue-600 shadow-lg p-4">
        <div class="flex justify-center">
            <div class="flex items-center bg-white rounded-full overflow-hidden shadow-sm">
                <span class="px-4 py-2 text-blue-600 font-bold text-sm">
                    {{ $isFrench ? 'Nouvelle session' : 'New session' }}
                </span>
                <a href="{{ route('cash.distributions.create') }}"
                   class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center m-1 animate-pulse hover:scale-110 active:scale-95 transition-transform duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-white"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    
    
    <br><br>

    <!-- Desktop Header -->
    <div class="hidden md:block">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @include('buttons')
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
                    {{ $isFrench ? 'Gestion de la Monnaie - Distributions' : 'Cash Management - Distributions' }}
                </h1>
                <a href="{{ route('cash.distributions.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ $isFrench ? 'Nouvelle Distribution' : 'New Distribution' }}
                </a>
            </div>
        </div>
    </div>
    <!-- Mobile Filters -->
    <div class="block md:hidden px-4 -mt-6 relative z-10">
        <div class="bg-white rounded-t-3xl shadow-2xl animate-slide-up">
            <div class="px-6 pt-6 pb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </h3>
                <form action="{{ route('cash.distributions.index') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="mobile_date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <input type="date" id="mobile_date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                               class="w-full h-12 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300">
                    </div>
                    <div>
                        <label for="mobile_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Vendeuse' : 'Seller' }}
                        </label>
                        <select id="mobile_user_id" name="user_id" class="w-full h-12 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300">
                            <option value="">{{ $isFrench ? 'Toutes les vendeuses' : 'All sellers' }}</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ request('user_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mobile_status" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select id="mobile_status" name="status" class="w-full h-12 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300">
                            <option value="">{{ $isFrench ? 'Tous les statuts' : 'All statuses' }}</option>
                            <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In progress' }}</option>
                            <option value="cloture" {{ request('status') == 'cloture' ? 'selected' : '' }}>{{ $isFrench ? 'Clôturé' : 'Closed' }}</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full h-12 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"></path>
                        </svg>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Distribution Cards -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white shadow-2xl -mt-2 relative z-10 pb-6">
            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Distributions' : 'Distributions' }}
                </h3>
                <div class="space-y-4">
                    @forelse($distributions as $distribution)
                        <div class="bg-gradient-to-r from-white to-blue-50 rounded-2xl shadow-lg border border-gray-100 p-5 transform hover:scale-105 transition-all duration-300 animate-fade-in">
                            <!-- Mobile Card Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="8.5" cy="7" r="4"/>
                                            <line x1="20" y1="8" x2="20" y2="14"/>
                                            <line x1="23" y1="11" x2="17" y2="11"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $distribution->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $distribution->date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                @if($distribution->status === 'en_cours')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? 'En cours' : 'In progress' }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $isFrench ? 'Clôturé' : 'Closed' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Mobile Card Details -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-white p-3 rounded-xl border-l-4 border-blue-500">
                                    <p class="text-xs font-medium text-gray-600">{{ $isFrench ? 'Ventes' : 'Sales' }}</p>
                                    <p class="font-bold text-gray-900">{{ number_format($distribution->sales_amount, 0, ',', ' ') }} XAF</p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border-l-4 border-blue-500">
                                    <p class="text-xs font-medium text-gray-600">{{ $isFrench ? 'Monnaie' : 'Change' }}</p>
                                    <p class="font-bold text-gray-900">{{ number_format($distribution->initial_coin_amount, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>

                            @if($distribution->status === 'cloture' && $distribution->missing_amount > 0)
                                <div class="bg-red-50 p-3 rounded-xl border-l-4 border-red-500 mb-4">
                                    <p class="text-xs font-medium text-red-600">{{ $isFrench ? 'Manquant' : 'Missing' }}</p>
                                    <p class="font-bold text-red-700">{{ number_format($distribution->missing_amount, 0, ',', ' ') }} XAF</p>
                                </div>
                            @endif

                            <!-- Mobile Card Actions -->
                            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('cash.distributions.show', $distribution) }}" 
                                   class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-200 transform hover:scale-110 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>

                                @if($distribution->status === 'en_cours')
                                    <a href="{{ route('cash.distributions.edit', $distribution) }}"
                                       class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 hover:bg-green-200 transform hover:scale-110 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    @if($flag == 0)
                                    <a href="{{ route('cash.distributions.close.form', $distribution) }}"
                                       class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 hover:bg-amber-200 transform hover:scale-110 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <p class="text-gray-600">{{ $isFrench ? 'Aucune distribution trouvée.' : 'No distributions found.' }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Mobile Pagination -->
                @if($distributions->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        {{ $distributions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:shadow-xl transition-all duration-300">
                <!-- Desktop Filters -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Filtres' : 'Filters' }}</h2>
                    <form action="{{ route('cash.distributions.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Date' : 'Date' }}</label>
                                <input type="date" id="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md hover:shadow-md transition-all duration-300">
                            </div>
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Vendeuse' : 'Seller' }}</label>
                                <select id="user_id" name="user_id" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md hover:shadow-md transition-all duration-300">
                                    <option value="">{{ $isFrench ? 'Toutes les vendeuses' : 'All sellers' }}</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ request('user_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                                <select id="status" name="status" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md hover:shadow-md transition-all duration-300">
                                    <option value="">{{ $isFrench ? 'Tous les statuts' : 'All statuses' }}</option>
                                    <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In progress' }}</option>
                                    <option value="cloture" {{ request('status') == 'cloture' ? 'selected' : '' }}>{{ $isFrench ? 'Clôturé' : 'Closed' }}</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"></path>
                                    </svg>
                                    {{ $isFrench ? 'Filtrer' : 'Filter' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Desktop Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Vendeuse' : 'Seller' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Billets' : 'Bills' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Monnaie Initiale' : 'Initial Change' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Ventes' : 'Sales' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Manquant' : 'Missing' }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($distributions as $distribution)
                                <tr class="hover:bg-gray-50 transform hover:scale-105 transition-all duration-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $distribution->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $distribution->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($distribution->bill_amount, 1, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($distribution->initial_coin_amount, 1, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($distribution->sales_amount, 1, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($distribution->status === 'en_cours')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">
                                                {{ $isFrench ? 'En cours' : 'In progress' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $isFrench ? 'Clôturé' : 'Closed' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($distribution->status === 'cloture' && $distribution->missing_amount > 0)
                                            <span class="text-red-600 font-medium">
                                                {{ number_format($distribution->missing_amount, 0, ',', ' ') }} FCFA
                                            </span>
                                        @elseif($distribution->status === 'cloture')
                                            <span class="text-green-600 font-medium">0 FCFA</span>
                                        @else
                                            <span class="text-gray-500">--</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('cash.distributions.show', $distribution) }}"
                                               class="text-blue-600 hover:text-blue-900 transform hover:scale-125 transition-all duration-200"
                                               title="{{ $isFrench ? 'Voir les détails' : 'View details' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            @if($distribution->status === 'en_cours')
                                                <a href="{{ route('cash.distributions.edit', $distribution) }}"
                                                   class="text-green-600 hover:text-green-900 transform hover:scale-125 transition-all duration-200"
                                                   title="{{ $isFrench ? 'Modifier' : 'Edit' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
                                                @if ($flag == 0)                                                <a href="{{ route('cash.distributions.close.form', $distribution) }}"
                                                   class="text-blue-600 hover:text-blue-900 transform hover:scale-125 transition-all duration-200"
                                                   title="{{ $isFrench ? 'Clôturer' : 'Close' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                    </svg>
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                        <div class="flex flex-col items-center justify-center animate-fade-in">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                                <polyline points="13 2 13 9 20 9"></polyline>
                                            </svg>
                                            <p>{{ $isFrench ? 'Aucune distribution trouvée.' : 'No distributions found.' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Desktop Pagination -->
                @if($distributions->hasPages())
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        {{ $distributions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}
</style>
@endsection
