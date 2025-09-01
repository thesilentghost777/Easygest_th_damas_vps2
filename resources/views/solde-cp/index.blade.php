@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    

    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-6 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Solde du Chef de Production' : 'Production Manager Balance' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Suivi de l\'évolution du solde et des opérations' : 'Balance evolution and operations tracking' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Balance Card -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg mb-6 transform hover:scale-105 transition-all duration-300 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">{{ $isFrench ? 'Solde actuel' : 'Current balance' }}</h2>
                            <p class="text-3xl font-bold mt-2">{{ number_format($solde->montant, 0, ',', ' ') }} XAF</p>
                            @if ($solde->description)
                                <p class="text-green-100 text-sm mt-2">{{ $solde->description }}</p>
                            @endif
                        </div>
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('solde-cp.ajuster') }}" class="mt-4 inline-flex items-center bg-white bg-opacity-20 text-white px-4 py-2 rounded-xl font-medium transform active:scale-95 transition-all duration-150">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        {{ $isFrench ? 'Ajuster' : 'Adjust' }}
                    </a>
                </div>

                <!-- Mobile Statistics -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-red-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium">{{ $isFrench ? 'Dépenses totales' : 'Total expenses' }}</h3>
                                <p class="text-2xl font-bold text-red-600">
                                    {{ number_format($historique->where('type_operation', 'depense')->sum('montant'), 0, ',', ' ') }} XAF
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium">{{ $isFrench ? 'Ajustements' : 'Adjustments' }}</h3>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{ number_format($historique->where('type_operation', 'ajustement')->sum('montant'), 0, ',', ' ') }} XAF
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium">{{ $isFrench ? 'Opérations totales' : 'Total operations' }}</h3>
                                <p class="text-2xl font-bold text-blue-600">{{ $historique->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile History -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in" style="animation-delay: 0.4s;">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Historique récent' : 'Recent history' }}</h2>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @forelse ($historique as $h)
                            <div class="bg-gray-50 rounded-xl p-4 border-l-4 
                                @if($h->type_operation === 'versement') border-green-500
                                @elseif($h->type_operation === 'depense') border-red-500
                                @else border-yellow-500 @endif
                                transform hover:scale-102 transition-all duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            @if ($h->type_operation === 'versement')
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium">
                                                    {{ $isFrench ? 'Versement' : 'Payment' }}
                                                </span>
                                            @elseif ($h->type_operation === 'depense')
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">
                                                    {{ $isFrench ? 'Dépense' : 'Expense' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                                    {{ $isFrench ? 'Ajustement' : 'Adjustment' }}
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $h->description }}</p>
                                    </div>
                                    <div class="text-right ml-4">
                                        @if ($h->type_operation === 'versement')
                                            <span class="text-lg font-bold text-green-600">+{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @elseif ($h->type_operation === 'depense')
                                            <span class="text-lg font-bold text-red-600">-{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @else
                                            <span class="text-lg font-bold text-yellow-600">{{ $h->montant > 0 ? '+' : '' }}{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ number_format($h->solde_apres, 0, ',', ' ') }} XAF</p>
                                    </div>
                                </div>
                                
                                <!-- Mobile Action Buttons (only for adjustments) -->
                                @if($h->type_operation === 'ajustement')
                                <div class="flex justify-end space-x-2 mt-3 pt-3 border-t border-gray-200">
                                   
                                    <a href="{{ route('solde-cp.edit', $h->id) }}" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs rounded-lg hover:bg-green-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                                    </a>
                                    <button onclick="confirmerSuppression({{ $h->id }})" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-xs rounded-lg hover:bg-red-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                        </svg>
                                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                    </button>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500">{{ $isFrench ? 'Aucune opération' : 'No operations' }}</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Mobile Pagination -->
                        @if($historique->hasPages())
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex justify-center">
                                {{ $historique->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <!-- Desktop Balance Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 transform hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $isFrench ? 'Solde actuel' : 'Current balance' }}</h2>
                        <p class="text-4xl font-bold text-green-600 my-2">{{ number_format($solde->montant, 0, ',', ' ') }} XAF</p>
                        @if ($solde->description)
                            <p class="text-sm mt-2">{{ $solde->description }}</p>
                        @endif
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('solde-cp.ajuster') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors transform hover:scale-105">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            {{ $isFrench ? 'Ajuster le solde' : 'Adjust balance' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Desktop Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-4 transform hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm">{{ $isFrench ? 'Dépenses totales' : 'Total expenses' }}</h3>
                            <p class="text-2xl font-semibold">
                                {{ number_format($historique->where('type_operation', 'depense')->sum('montant'), 0, ',', ' ') }} XAF
                            </p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 transform hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm">{{ $isFrench ? 'Ajustements' : 'Adjustments' }}</h3>
                            <p class="text-2xl font-semibold">
                                {{ number_format($historique->where('type_operation', 'ajustement')->sum('montant'), 0, ',', ' ') }} XAF
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 transform hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gray-500 text-sm">{{ $isFrench ? 'Opérations totales' : 'Total operations' }}</h3>
                            <p class="text-2xl font-semibold">{{ $historique->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop History Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:shadow-xl transition-all duration-300">
                <div class="p-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold">{{ $isFrench ? 'Historique des opérations' : 'Operations history' }}</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Type' : 'Type' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Solde avant' : 'Balance before' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Solde après' : 'Balance after' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Utilisateur' : 'User' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Description' : 'Description' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($historique as $h)
                                <tr class="hover:bg-gray-50 transform hover:scale-101 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $h->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($h->type_operation === 'versement')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $isFrench ? 'Versement' : 'Payment' }}
                                            </span>
                                        @elseif ($h->type_operation === 'depense')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $isFrench ? 'Dépense' : 'Expense' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $isFrench ? 'Ajustement' : 'Adjustment' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($h->type_operation === 'versement')
                                            <span class="text-green-600">+{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @elseif ($h->type_operation === 'depense')
                                            <span class="text-red-600">-{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @else
                                            <span class="text-yellow-600">{{ $h->montant > 0 ? '+' : '' }}{{ number_format($h->montant, 0, ',', ' ') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($h->solde_avant, 0, ',', ' ') }} XAF
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($h->solde_apres, 0, ',', ' ') }} XAF
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $h->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $h->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($h->type_operation === 'ajustement')
                                            <div class="flex space-x-2">
                                            
                                                <a href="{{ route('solde-cp.edit', $h->id) }}" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs rounded-md hover:bg-green-200 transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                    </svg>
                                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                                </a>
                                                <button onclick="confirmerSuppression({{ $h->id }})" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-xs rounded-md hover:bg-red-200 transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                    </svg>
                                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">{{ $isFrench ? 'Non modifiable' : 'Not editable' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $isFrench ? 'Aucune opération dans l\'historique' : 'No operations in history' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Desktop Pagination -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $historique->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="modalSuppression" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">{{ $isFrench ? 'Confirmer la suppression' : 'Confirm deletion' }}</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    {{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet ajustement ? Cette action recalculera automatiquement tous les soldes suivants et ne peut pas être annulée.' : 'Are you sure you want to delete this adjustment? This action will automatically recalculate all subsequent balances and cannot be undone.' }}
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="btnConfirmerSuppression" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                </button>
                <button id="btnAnnulerSuppression" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="formSuppression" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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

.hover\:scale-102:hover {
    transform: scale(1.02);
}

.hover\:scale-101:hover {
    transform: scale(1.01);
}

/* Styles personnalisés pour la pagination mobile */
@media (max-width: 768px) {
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background-color: white;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
        cursor: not-allowed;
    }
}
</style>

<script>
let historiqueIdASupprimer = null;

function confirmerSuppression(id) {
    historiqueIdASupprimer = id;
    document.getElementById('modalSuppression').classList.remove('hidden');
}

document.getElementById('btnConfirmerSuppression').addEventListener('click', function() {
    if (historiqueIdASupprimer) {
        const form = document.getElementById('formSuppression');
        form.action = `/historique/${historiqueIdASupprimer}`;
        form.submit();
    }
});

document.getElementById('btnAnnulerSuppression').addEventListener('click', function() {
    document.getElementById('modalSuppression').classList.add('hidden');
    historiqueIdASupprimer = null;
});

// Fermer le modal si on clique en dehors
document.getElementById('modalSuppression').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        historiqueIdASupprimer = null;
    }
});
</script>
@endsection
