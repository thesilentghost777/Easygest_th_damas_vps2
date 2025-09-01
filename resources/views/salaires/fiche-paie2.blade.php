@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 max-w-4xl mx-auto animate-fade-in" id="fiche-paie">
        <!-- Header -->
        <div class="text-center mb-6 lg:mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="mdi mdi-file-document text-2xl text-blue-600"></i>
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $isFrench ? 'Fiche de Paie' : 'Pay Slip' }}</h1>
            <p class="text-gray-600 text-sm lg:text-base mt-2">{{ $mois->format('F Y') }}</p>
        </div>

        <!-- Employee information -->
        <div class="mb-6 lg:mb-8 p-4 bg-blue-50 rounded-xl border border-blue-200 mobile-card">
            <h2 class="text-lg lg:text-xl font-semibold mb-4 text-gray-900 flex items-center">
                <i class="mdi mdi-account mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Informations de l\'employé' : 'Employee Information' }}
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <p class="text-gray-600 text-sm">{{ $isFrench ? 'Nom' : 'Name' }}</p>
                    <p class="font-medium text-gray-900">{{ $employe->name }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-200">
                    <p class="text-gray-600 text-sm">{{ $isFrench ? 'Secteur' : 'Sector' }}</p>
                    <p class="font-medium text-gray-900">{{ $employe->secteur }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-200 lg:col-span-2">
                    <p class="text-gray-600 text-sm">{{ $isFrench ? 'Date d\'entrée en service' : 'Service Start Date' }}</p>
                    <p class="font-medium text-gray-900">{{ $employe->annee_debut_service }}</p>
                </div>
            </div>
        </div>

        <!-- Salary details -->
        <div class="space-y-3 lg:space-y-4 mb-6 lg:mb-8">
            <!-- Base salary -->
            <div class="flex justify-between items-center py-3 lg:py-2 border-b border-gray-200 bg-white rounded-lg lg:rounded-none px-4 lg:px-0">
                <span class="font-medium text-gray-900 text-sm lg:text-base">{{ $isFrench ? 'Salaire de base' : 'Base Salary' }}</span>
                <span class="text-gray-900 font-semibold">{{ number_format($fichePaie['salaire_base'], 2) }} FCFA</span>
            </div>

            @if($fichePaie['avance_salaire'] > 0)
            <div class="flex justify-between items-center py-3 lg:py-2 border-b border-gray-200 text-red-600 bg-red-50 rounded-lg lg:rounded-none px-4 lg:px-0">
                <span class="font-medium text-sm lg:text-base">{{ $isFrench ? 'Avance sur salaire' : 'Salary Advance' }}</span>
                <span class="font-semibold">- {{ number_format($fichePaie['avance_salaire'], 2) }} FCFA</span>
            </div>
            @endif

            <!-- Deductions -->
            @foreach($fichePaie['deductions'] as $label => $montant)
                @if($montant > 0)
                <div class="flex justify-between items-center py-3 lg:py-2 border-b border-gray-200 text-red-600 bg-red-50 rounded-lg lg:rounded-none px-4 lg:px-0">
                    <span class="font-medium text-sm lg:text-base">{{ ucfirst(str_replace('_', ' ', $label)) }}</span>
                    <span class="font-semibold">- {{ number_format($montant, 2) }} FCFA</span>
                </div>
                @endif
            @endforeach

            @if($fichePaie['primes'] > 0)
            <div class="flex justify-between items-center py-3 lg:py-2 border-b border-gray-200 text-green-600 bg-green-50 rounded-lg lg:rounded-none px-4 lg:px-0">
                <span class="font-medium text-sm lg:text-base">{{ $isFrench ? 'Primes' : 'Bonuses' }}</span>
                <span class="font-semibold">+ {{ number_format($fichePaie['primes'], 2) }} FCFA</span>
            </div>
            @endif

            <!-- Net salary -->
            <div class="flex justify-between items-center py-4 lg:py-4 border-t-2 border-blue-500 text-lg lg:text-xl font-bold bg-blue-50 rounded-lg px-4 mobile-card">
                <span class="text-gray-900">{{ $isFrench ? 'Salaire net à payer' : 'Net Salary to Pay' }}</span>
                <span class="text-blue-600">{{ number_format($fichePaie['salaire_net'], 2) }} FCFA</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 lg:mt-8 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 no-print">
            @if(number_format($fichePaie['salaire_net'], 2) < 0)
            <div class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-red-200 text-red-600 rounded-xl lg:rounded-md text-center font-medium">
                <i class="mdi mdi-alert-circle-outline mr-2"></i>{{ $isFrench ? 'Salaire négatif' : 'Negative Salary' }}
            </div>
            @elseif(!$salaire->retrait_demande && !$salaire->flag && !$salaire->retrait_valide)
            <button id="demande-retrait-btn" class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-blue-600 text-white rounded-xl lg:rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-cash mr-2"></i>{{ $isFrench ? 'Demander le retrait' : 'Request Withdrawal' }}
            </button>
            @elseif ($salaire->retrait_demande && !$salaire->flag)
            <div class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-gray-200 text-gray-700 rounded-xl lg:rounded-md text-center font-medium">
                <i class="mdi mdi-clock mr-2"></i>{{ $isFrench ? 'Demande de retrait en cours' : 'Withdrawal request pending' }}
            </div>
            @else
            <div class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-gray-200 text-gray-700 rounded-xl lg:rounded-md text-center font-medium">
                {{ $isFrench ? 'Indisponible' : 'Unavailable' }}
            </div>
            @endif
            
            <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-gray-600 text-white rounded-xl lg:rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-printer mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
            </button>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
    <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" id="pin-modal-backdrop"></div>
    <div class="bg-white rounded-xl shadow-xl p-6 sm:p-8 max-w-md w-full m-4 relative z-10 transform transition-all scale-95 opacity-0" id="pin-modal-content">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="mdi mdi-lock text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ $isFrench ? 'Confirmation de sécurité' : 'Security Confirmation' }}</h3>
            <p class="text-gray-600 mt-2">{{ $isFrench ? 'Veuillez saisir votre code PIN pour confirmer le retrait de votre salaire' : 'Please enter your PIN code to confirm salary withdrawal' }}</p>
        </div>

        <form id="pin-form" action="{{ route('salaires.demande-retrait2', $salaire->id) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Code PIN' : 'PIN Code' }}</label>
                <div class="pin-input-container flex justify-center gap-2 lg:gap-3">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-10 h-10 lg:w-12 lg:h-12 text-center text-lg lg:text-xl border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                </div>
                <input type="hidden" id="pin" name="pin" required>
            </div>
            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <button type="button" id="cancel-pin" class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-gray-200 text-gray-800 rounded-xl lg:rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-200 font-medium">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-blue-600 text-white rounded-xl lg:rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium">
                    {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.5s ease-out; }

/* Modal animations */
.modal-show {
    display: flex !important;
    opacity: 1;
}
.modal-backdrop-show {
    opacity: 1;
}
.modal-content-show {
    opacity: 1;
    transform: scale(1);
}

/* Mobile card */
.mobile-card {
    transition: all 0.2s ease-out;
}

/* PIN input focus */
.pin-digit:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

/* Mobile optimizations */
@media (max-width: 1024px) {
    .mobile-card:active {
        transform: scale(0.98);
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

/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }

    #fiche-paie, #fiche-paie * {
        visibility: visible;
    }

    #fiche-paie {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        margin: 0;
        box-shadow: none;
    }

    .no-print {
        display: none !important;
    }

    @page {
        size: A4;
        margin: 2cm;
    }

    .text-red-600 {
        color: #dc2626 !important;
    }

    .text-green-600 {
        color: #059669 !important;
    }

    .text-blue-600 {
        color: #2563eb !important;
    }

    .bg-blue-50, .bg-red-50, .bg-green-50 {
        background-color: #f9fafb !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .border-b, .border-t-2 {
        border-color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const demandeRetraitBtn = document.getElementById('demande-retrait-btn');
    const pinModal = document.getElementById('pin-modal');
    const pinModalBackdrop = document.getElementById('pin-modal-backdrop');
    const pinModalContent = document.getElementById('pin-modal-content');
    const cancelPinBtn = document.getElementById('cancel-pin');
    const pinForm = document.getElementById('pin-form');
    const pinDigitInputs = document.querySelectorAll('.pin-digit');
    const pinInput = document.getElementById('pin');

    function openModal() {
        pinModal.classList.add('modal-show');
        setTimeout(() => {
            pinModalBackdrop.classList.add('modal-backdrop-show');
            pinModalContent.classList.add('modal-content-show');
        }, 10);
        pinDigitInputs[0].focus();
    }

    function closeModal() {
        pinModalContent.classList.remove('modal-content-show');
        pinModalBackdrop.classList.remove('modal-backdrop-show');
        setTimeout(() => {
            pinModal.classList.remove('modal-show');
            pinDigitInputs.forEach(input => {
                input.value = '';
            });
            pinInput.value = '';
        }, 300);
    }

    if (demandeRetraitBtn) {
        demandeRetraitBtn.addEventListener('click', openModal);
    }

    cancelPinBtn.addEventListener('click', closeModal);
    pinModalBackdrop.addEventListener('click', closeModal);

    pinDigitInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            if (value.length > 0) {
                if (!/^\d+$/.test(value)) {
                    e.target.value = '';
                } else {
                    e.target.value = value.slice(-1);
                }
            }

            if (value && index < pinDigitInputs.length - 1) {
                pinDigitInputs[index + 1].focus();
            }

            updatePinValue();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' && index > 0) {
                pinDigitInputs[index - 1].focus();
            }
            else if (e.key === 'ArrowRight' && index < pinDigitInputs.length - 1) {
                pinDigitInputs[index + 1].focus();
            }
            else if (e.key === 'Backspace' && index > 0 && input.value === '') {
                pinDigitInputs[index - 1].focus();
            }
        });
    });

    function updatePinValue() {
        let pin = '';
        pinDigitInputs.forEach(input => {
            pin += input.value;
        });
        pinInput.value = pin;
    }

    pinForm.addEventListener('submit', function(e) {
        updatePinValue();
        if (pinInput.value.length !== 6) {
            e.preventDefault();
            pinDigitInputs.forEach(input => {
                input.classList.add('border-red-500');
                setTimeout(() => {
                    input.classList.remove('border-red-500');
                }, 500);
            });
        }
    });
});
</script>
@endsection
