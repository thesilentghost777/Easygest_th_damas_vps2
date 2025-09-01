@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 animate-fade-in">
        @include('buttons')

        <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4 lg:mb-6 flex items-center">
            <i class="mdi mdi-calculator mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Mes Déductions Salariales' : 'My Salary Deductions' }}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 mb-4 rounded-r-lg shadow-md animate-slide-in" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if($deductions)
            <div class="bg-white overflow-hidden shadow-lg rounded-xl divide-y divide-gray-200 border border-gray-100">
                <div class="px-4 lg:px-6 py-4 lg:py-5 bg-blue-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                        <i class="mdi mdi-information mr-2 text-blue-600"></i>
                        {{ $isFrench ? 'Informations de déduction sur votre salaire' : 'Salary Deduction Information' }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ $isFrench ? 'Ces montants seront déduits lors du prochain paiement.' : 'These amounts will be deducted at the next payment.' }}
                    </p>
                </div>

                <div class="px-4 lg:px-6 py-4 lg:py-6">
                    <div class="grid grid-cols-1 gap-4 lg:gap-6 lg:grid-cols-2">
                        <div class="col-span-1 lg:col-span-2">
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200 mobile-card">
                                <div class="flex items-center">
                                    <i class="mdi mdi-calendar-clock text-blue-500 text-xl mr-3"></i>
                                    <p class="text-sm text-blue-700">
                                        {{ $isFrench ? 'La dernière mise à jour de ces informations a été effectuée le' : 'These details were last updated on' }}
                                        <span class="font-semibold">{{ $deductions->date->format('d/m/Y') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Effective Missing -->
                        <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm mobile-card transform hover:scale-105 transition-all duration-200">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-base font-medium text-gray-900">{{ $isFrench ? 'Manquant Effectif' : 'Effective Missing' }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="mdi mdi-alert-circle-outline mr-1"></i>
                                    {{ $isFrench ? 'Déduit' : 'Deducted' }}
                                </span>
                            </div>
                            <p class="text-2xl lg:text-3xl font-bold text-gray-900">
                                {{ number_format($deductions->manquants, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                            </p>
                        </div>

                        <!-- Reimbursement -->
                        <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm mobile-card transform hover:scale-105 transition-all duration-200">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-base font-medium text-gray-900">{{ $isFrench ? 'Remboursement d\'emprunt' : 'Loan Reimbursement' }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="mdi mdi-cash-refund mr-1"></i>
                                    {{ $isFrench ? 'Déduit' : 'Deducted' }}
                                </span>
                            </div>
                            <p class="text-2xl lg:text-3xl font-bold text-gray-900">
                                {{ number_format($deductions->remboursement, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                            </p>
                        </div>

                        <!-- Social Fund -->
                        <div class="bg-white p-4 border border-gray-200 rounded-xl shadow-sm mobile-card transform hover:scale-105 transition-all duration-200">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-base font-medium text-gray-900">{{ $isFrench ? 'Caisse Sociale' : 'Social Fund' }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="mdi mdi-medical-bag mr-1"></i>
                                    {{ $isFrench ? 'Déduit' : 'Deducted' }}
                                </span>
                            </div>
                            <p class="text-2xl lg:text-3xl font-bold text-gray-900">
                                {{ number_format($deductions->caisse_sociale, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                            </p>
                        </div>
                    </div>

                    <!-- Total deductions -->
                    <div class="mt-6 p-4 lg:p-6 bg-blue-50 rounded-xl border border-blue-200 shadow-sm">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                                <i class="mdi mdi-calculator mr-2 text-blue-600"></i>
                                {{ $isFrench ? 'Total des déductions' : 'Total Deductions' }}
                            </h4>
                            <p class="text-xl lg:text-2xl font-bold text-blue-600">
                                {{ number_format($deductions->manquants + $deductions->remboursement + $deductions->pret + $deductions->caisse_sociale, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">FCFA</span>
                            </p>
                        </div>
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
                        <h3 class="text-lg font-medium text-blue-800 mb-2">{{ $isFrench ? 'Information' : 'Information' }}</h3>
                        <div class="text-blue-700">
                            <p>{{ $isFrench ? 'Vous n\'avez actuellement aucune déduction enregistrée.' : 'You currently have no deductions recorded.' }}</p>
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
