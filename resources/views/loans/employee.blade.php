@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50 max-w-4xl">
    @include('buttons')
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 animate-fade-in">
        <div class="bg-blue-500 px-4 lg:px-6 py-4 lg:py-6">
            <h2 class="text-xl lg:text-2xl font-bold text-white flex items-center">
                <i class="mdi mdi-cash mr-2"></i>
                {{ $isFrench ? 'Gestion de vos prêts' : 'Loan Management' }}
            </h2>
        </div>

        <div class="p-4 lg:p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 mb-6 rounded-r-lg shadow-md animate-slide-in" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 mb-6 rounded-r-lg shadow-md animate-slide-in" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Current situation -->
                <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200 mobile-card">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="mdi mdi-information mr-2 text-blue-600"></i>
                        {{ $isFrench ? 'Situation actuelle' : 'Current Situation' }}
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-gray-600 text-sm lg:text-base">{{ $isFrench ? 'Prêt en cours:' : 'Current loan:' }}</span>
                            <span class="font-bold text-blue-600 text-sm lg:text-base">{{ number_format($loanData->pret, 0, ',', ' ') }} XAF</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-gray-600 text-sm lg:text-base">{{ $isFrench ? 'Remboursement du mois:' : 'Monthly repayment:' }}</span>
                            <span class="font-bold text-red-600 text-sm lg:text-base">{{ number_format($loanData->remboursement, 0, ',', ' ') }} XAF</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 p-3 bg-blue-50 rounded-lg">
                            <span class="text-gray-700 font-medium text-sm lg:text-base">{{ $isFrench ? 'Solde après remboursement:' : 'Balance after repayment:' }}</span>
                            <span class="font-bold text-gray-800 text-sm lg:text-base">{{ number_format($loanData->pret - $loanData->remboursement, 0, ',', ' ') }} XAF</span>
                        </div>
                    </div>
                </div>

                <!-- Request loan -->
                <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200 mobile-card">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="mdi mdi-hand-coin mr-2 text-green-600"></i>
                        {{ $isFrench ? 'Demander un prêt' : 'Request a Loan' }}
                    </h3>

                    @if($loanData->pret > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ $isFrench ? 'Vous avez déjà un prêt en cours. Vous ne pouvez pas faire une nouvelle demande pour le moment.' : 'You already have an active loan. You cannot make a new request at this time.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <form id="loanRequestForm" action="{{ route('loans.request') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pin" id="pinValue">
                            <div class="mb-4">
                                <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Montant souhaité' : 'Desired Amount' }}</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <input type="number" name="montant" id="montant" class="block w-full rounded-xl lg:rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 lg:py-2 text-base lg:text-sm" placeholder="0" min="1000" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 text-sm">XAF</span>
                                    </div>
                                </div>
                                @error('montant')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="button" id="submitLoanButton" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 lg:py-2 px-4 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95">
                                <i class="mdi mdi-send mr-2"></i>{{ $isFrench ? 'Soumettre la demande' : 'Submit Request' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Request history -->
            <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200 mobile-card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="mdi mdi-history mr-2 text-purple-600"></i>
                    {{ $isFrench ? 'Historique des demandes' : 'Request History' }}
                </h3>

                @php
                $loanRequests = DB::table('loan_requests')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();
                @endphp

                @if($loanRequests->isEmpty())
                    <p class="text-gray-500 italic text-center py-4">{{ $isFrench ? 'Aucune demande de prêt effectuée.' : 'No loan requests made.' }}</p>
                @else
                    <!-- Desktop table (hidden on mobile) -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($loanRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($request->amount, 0, ',', ' ') }} XAF</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' :
                                                  ($request->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $request->status == 'approved' ? ($isFrench ? 'Approuvé' : 'Approved') :
                                                  ($request->status == 'rejected' ? ($isFrench ? 'Refusé' : 'Rejected') : ($isFrench ? 'En attente' : 'Pending')) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile card view (visible only on mobile) -->
                    <div class="lg:hidden space-y-3">
                        @foreach($loanRequests as $request)
                            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm animate-fade-in transform hover:scale-105 transition-all duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($request->amount, 0, ',', ' ') }} XAF</div>
                                        <div class="text-xs text-gray-500">{{ Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' :
                                          ($request->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $request->status == 'approved' ? ($isFrench ? 'Approuvé' : 'Approved') :
                                          ($request->status == 'rejected' ? ($isFrench ? 'Refusé' : 'Rejected') : ($isFrench ? 'En attente' : 'Pending')) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- PIN overlay with enhanced mobile design -->
<div id="pinOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300 opacity-0"></div>

<!-- PIN form with enhanced mobile design -->
<div id="pinFormContainer" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl z-50 w-80 hidden transition-all duration-300 opacity-0 scale-95">
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">{{ $isFrench ? 'Confirmation requise' : 'Confirmation Required' }}</h3>
        <p class="text-gray-600 text-sm text-center mb-4">{{ $isFrench ? 'Veuillez entrer votre code PIN pour valider votre demande de prêt' : 'Please enter your PIN code to validate your loan request' }}</p>
        
        <div class="mb-6">
            <div class="flex justify-center">
                <input type="text" id="pinInput" maxlength="6" class="text-center text-2xl tracking-widest w-40 py-2 border-b-2 border-blue-500 focus:outline-none focus:border-blue-700 bg-transparent" placeholder="------" pattern="[0-9]*" inputmode="numeric">
            </div>
            <div class="text-center mt-2">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Code à 6 chiffres' : '6-digit code' }}</p>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button id="cancelPinButton" class="flex-1 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors duration-200 font-medium text-sm">
                {{ $isFrench ? 'Annuler' : 'Cancel' }}
            </button>
            <button id="confirmPinButton" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 font-medium text-sm">
                {{ $isFrench ? 'Confirmer' : 'Confirm' }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanForm = document.getElementById('loanRequestForm');
    const submitButton = document.getElementById('submitLoanButton');
    const pinOverlay = document.getElementById('pinOverlay');
    const pinFormContainer = document.getElementById('pinFormContainer');
    const pinInput = document.getElementById('pinInput');
    const confirmPinButton = document.getElementById('confirmPinButton');
    const cancelPinButton = document.getElementById('cancelPinButton');
    const pinValue = document.getElementById('pinValue');
    
    if (!submitButton) return;
    
    submitButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        const montant = document.getElementById('montant').value;
        if (!montant || montant < 1000) {
            return;
        }
        
        pinOverlay.classList.remove('hidden');
        pinFormContainer.classList.remove('hidden');
        
        setTimeout(() => {
            pinOverlay.classList.add('opacity-100');
            pinOverlay.classList.remove('opacity-0');
            
            pinFormContainer.classList.add('opacity-100', 'scale-100');
            pinFormContainer.classList.remove('opacity-0', 'scale-95');
            
            pinInput.focus();
        }, 10);
    });
    
    function closePinForm() {
        pinOverlay.classList.remove('opacity-100');
        pinOverlay.classList.add('opacity-0');
        
        pinFormContainer.classList.remove('opacity-100', 'scale-100');
        pinFormContainer.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            pinOverlay.classList.add('hidden');
            pinFormContainer.classList.add('hidden');
            pinInput.value = '';
        }, 300);
    }
    
    cancelPinButton.addEventListener('click', closePinForm);
    pinOverlay.addEventListener('click', closePinForm);
    
    confirmPinButton.addEventListener('click', function() {
        const pin = pinInput.value.trim();
        
        if (pin.length !== 6 || !/^\d+$/.test(pin)) {
            pinFormContainer.classList.add('animate-wiggle');
            setTimeout(() => {
                pinFormContainer.classList.remove('animate-wiggle');
            }, 500);
            return;
        }
        
        pinValue.value = pin;
        closePinForm();
        
        setTimeout(() => {
            loanForm.submit();
        }, 350);
    });
    
    pinInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
        
        if (this.value.length === 6) {
            confirmPinButton.focus();
        }
    });
    
    pinInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.length === 6) {
            confirmPinButton.click();
        }
    });
});
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes wiggle {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
    .mobile-card {
        transition: all 0.2s ease-out;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, input, .mobile-card {
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
