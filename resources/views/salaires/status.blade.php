@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Statut de votre demande d'avance" : "Your advance request status" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden border border-gray-200">
            <!-- Card Header -->
            <div class="bg-blue-600 text-white p-4 lg:p-6">
                <h2 class="text-lg lg:text-xl font-bold">
                    {{ $isFrench ? "Statut de votre demande d'avance" : "Your advance request status" }}
                </h2>
            </div>

            <!-- Card Content -->
            <div class="p-4 lg:p-6">
                @if(!$as)
                    <!-- No Request State -->
                    <div class="flex flex-col items-center justify-center py-8 lg:py-12 space-y-4">
                        <div class="p-4 bg-blue-50 rounded-full animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-lg">
                            {{ $isFrench ? "Aucune demande en cours" : "No request in progress" }}
                        </h3>
                        <p class="text-gray-600 text-center text-sm lg:text-base">
                            {{ $isFrench ? "Aucune demande d'avance en cours." : "No advance request in progress." }}
                        </p>
                        <a href="#" class="mt-4 inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ $isFrench ? "Faire une demande" : "Make a request" }}
                        </a>
                    </div>
                @else
                    <!-- Request Exists -->
                    <div class="space-y-6">
                        <!-- Amount Card -->
                        <div class="p-4 lg:p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $isFrench ? "Montant demandé" : "Requested amount" }}</p>
                                        <p class="text-lg lg:text-xl font-bold text-gray-900">{{ number_format($as->sommeAs, 0, ',', ' ') }} XAF</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="p-4 lg:p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 rounded-full p-2">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $isFrench ? "Statut" : "Status" }}</p>
                                        <span class="px-4 py-2 rounded-full text-sm font-medium {{ $as->flag ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $as->flag ? ($isFrench ? 'Approuvée' : 'Approved') : ($isFrench ? 'En attente' : 'Pending') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($as->flag && !$as->retrait_valide && $as->retrait_demande)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="p-4 lg:p-6 bg-green-50 rounded-xl border border-green-200">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">
                                            {{ $isFrench ? "Demande de retrait envoyée" : "Withdrawal request sent" }}
                                        </h3>
                                        <p class="mt-2 text-sm text-green-700">
                                            {{ $isFrench ? "Votre demande de retrait a été envoyée. Veuillez patienter la validation du côté de l'administration pour récupérer votre argent." : "Your withdrawal request has been sent. Please wait for validation from the administration to collect your money." }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                        <!-- Success State -->
                        @if($as->flag && $as->retrait_valide)
                            <div class="p-4 lg:p-6 bg-green-50 rounded-xl border border-green-200">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">
                                            {{ $isFrench ? "Retrait validé" : "Withdrawal validated" }}
                                        </h3>
                                        <p class="mt-2 text-sm text-green-700">
                                            {{ $isFrench ? "Votre demande de retrait a été validée. Vous pouvez maintenant récupérer votre avance." : "Your withdrawal request has been validated. You can now collect your advance." }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    button:active, .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>
@endsection
