@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 animate-fade-in">
        @include('buttons')

        <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6 flex items-center">
            <i class="mdi mdi-clipboard-text-outline mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Mes Manquants' : 'My Missing Items' }}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 mb-4 rounded-r-lg shadow-md animate-slide-in" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if($manquant)
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <div class="px-4 lg:px-6 py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="mdi mdi-clipboard-text-outline mr-2 text-blue-500"></i>
                            {{ $isFrench ? 'État de votre manquant' : 'Status of Your Missing Items' }}
                        </h2>
                        <span class="px-4 py-2 inline-flex items-center text-sm leading-5 font-semibold rounded-full
                            @if($manquant->statut == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($manquant->statut == 'ajuste') bg-blue-100 text-blue-800
                            @elseif($manquant->statut == 'valide') bg-green-100 text-green-800
                            @endif mobile-status-badge">
                            <i class="mdi
                                @if($manquant->statut == 'en_attente') mdi-clock-outline
                                @elseif($manquant->statut == 'ajuste') mdi-adjust
                                @elseif($manquant->statut == 'valide') mdi-check-circle-outline
                                @endif mr-2"></i>
                            {{ $manquant->statut == 'en_attente' ? ($isFrench ? 'En attente' : 'Pending') :
                               ($manquant->statut == 'ajuste' ? ($isFrench ? 'Ajusté' : 'Adjusted') :
                               ($isFrench ? 'Validé' : 'Validated')) }}
                        </span>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <dl class="grid grid-cols-1 gap-4 lg:gap-6">
                            <div class="bg-blue-50 rounded-xl px-4 lg:px-6 py-4 lg:py-5 lg:grid lg:grid-cols-3 lg:gap-4 mobile-card transform hover:scale-105 transition-all duration-200">
                                <dt class="text-sm font-medium text-gray-600 flex items-center mb-2 lg:mb-0">
                                    <i class="mdi mdi-currency-usd mr-2 text-blue-500 text-xl"></i>
                                    {{ $isFrench ? 'Montant' : 'Amount' }}
                                </dt>
                                <dd class="text-xl lg:text-2xl font-bold text-gray-900 lg:col-span-2">
                                    {{ number_format($manquant->montant, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                                </dd>
                            </div>

                            <div class="bg-white rounded-xl border border-gray-200 px-4 lg:px-6 py-4 lg:py-5 lg:grid lg:grid-cols-3 lg:gap-4 mobile-card">
                                <dt class="text-sm font-medium text-gray-600 flex items-center mb-2 lg:mb-0">
                                    <i class="mdi mdi-text-box-outline mr-2 text-blue-500 text-xl"></i>
                                    {{ $isFrench ? 'Explication' : 'Explanation' }}
                                </dt>
                                <dd class="text-sm text-gray-700 lg:col-span-2">
                                    <div class="whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-100">{{ $manquant->explication }}</div>
                                </dd>
                            </div>

                            @if($manquant->commentaire_dg)
                                <div class="bg-blue-50 rounded-xl px-4 lg:px-6 py-4 lg:py-5 lg:grid lg:grid-cols-3 lg:gap-4 mobile-card">
                                    <dt class="text-sm font-medium text-gray-600 flex items-center mb-2 lg:mb-0">
                                        <i class="mdi mdi-comment-text-outline mr-2 text-blue-500 text-xl"></i>
                                        {{ $isFrench ? 'Commentaire du DG' : 'DG Comment' }}
                                    </dt>
                                    <dd class="text-sm text-gray-700 lg:col-span-2">
                                        <div class="bg-white p-4 rounded-lg border border-blue-200 shadow-sm">{{ $manquant->commentaire_dg }}</div>
                                    </dd>
                                </div>
                            @endif

                            <div class="bg-white rounded-xl border border-gray-200 px-4 lg:px-6 py-4 lg:py-5 lg:grid lg:grid-cols-3 lg:gap-4 mobile-card">
                                <dt class="text-sm font-medium text-gray-600 flex items-center mb-2 lg:mb-0">
                                    <i class="mdi mdi-calendar-clock mr-2 text-blue-500 text-xl"></i>
                                    {{ $isFrench ? 'Dernière mise à jour' : 'Last Update' }}
                                </dt>
                                <dd class="text-sm text-gray-700 lg:col-span-2">
                                    {{ $manquant->updated_at->format('d/m/Y à H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-blue-50 p-6 lg:p-8 rounded-xl border border-blue-200 shadow-sm animate-fade-in">
                <div class="flex flex-col lg:flex-row items-center lg:items-start">
                    <div class="flex-shrink-0 mb-4 lg:mb-0 lg:mr-4">
                        <i class="mdi mdi-information-outline text-blue-500 text-4xl lg:text-2xl"></i>
                    </div>
                    <div class="text-center lg:text-left">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">{{ $isFrench ? 'Information' : 'Information' }}</h3>
                        <div class="text-gray-700">
                            <p>{{ $isFrench ? 'Vous n\'avez actuellement aucun manquant enregistré.' : 'You currently have no missing items recorded.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
    .mobile-card {
        transition: all 0.2s ease-out;
    }
    .mobile-status-badge {
        animation: pulse 2s infinite;
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
