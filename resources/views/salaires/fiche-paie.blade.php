@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Fiche de Paie" : "Payslip" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="hidden lg:block mb-6">
            @include('buttons')
            <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3 lg:py-2 bg-gray-600 text-white rounded-xl lg:rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-printer mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
            </button>
        </div>

        <!-- Payslip Card -->
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg max-w-3xl mx-auto" id="fiche-paie">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6 lg:p-8 rounded-t-lg lg:rounded-t-xl">
                
                <div class="text-center">
                    <h1 class="text-2xl lg:text-3xl font-bold mb-2">
                        {{ $isFrench ? "Fiche de Paie" : "Payslip" }}
                    </h1>
                    <p class="text-blue-100">{{ $mois->locale($isFrench ? 'fr' : 'en')->format('F Y') }}</p>
                </div>
            </div>

            <!-- Employee Information -->
            <div class="p-4 lg:p-8">
                <div class="mb-6 lg:mb-8 p-4 lg:p-6 bg-gray-50 rounded-xl">
                    <h2 class="text-lg lg:text-xl font-semibold mb-4 text-gray-900">
                        {{ $isFrench ? "Informations de l'employé" : "Employee Information" }}
                    </h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? "Nom" : "Name" }}</p>
                                <p class="font-medium text-gray-900">{{ $employe->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? "Secteur" : "Department" }}</p>
                                <p class="font-medium text-gray-900">{{ $employe->secteur ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? "Date d'entrée en service" : "Service Start Date" }}</p>
                                <p class="font-medium text-gray-900">{{ $employe->annee_debut_service ?? ($isFrench ? 'Non spécifiée' : 'Not specified') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? "Poste" : "Position" }}</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($employe->role ?? ($isFrench ? 'Non spécifié' : 'Not specified')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Details -->
                <div class="space-y-4 mb-6 lg:mb-8">
                    <!-- Base Salary -->
                    <div class="flex justify-between items-center py-3 lg:py-4 border-b border-gray-200">
                        <span class="font-medium text-gray-900">{{ $isFrench ? "Salaire de base" : "Base Salary" }}</span>
                        <span class="font-bold text-green-600">{{ number_format($fichePaie['salaire_base'], 0, ',', ' ') }} FCFA</span>
                    </div>

                    <!-- Salary Advance -->
                    @if($fichePaie['avance_salaire'] > 0)
                    <div class="flex justify-between items-center py-3 lg:py-4 border-b border-gray-200">
                        <span class="font-medium text-gray-900">{{ $isFrench ? "Avance sur salaire" : "Salary Advance" }}</span>
                        <span class="font-bold text-red-600">- {{ number_format($fichePaie['avance_salaire'], 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif

                    <!-- Deductions -->
                    @foreach($fichePaie['deductions'] as $label => $montant)
                        @if($montant > 0)
                        <div class="flex justify-between items-center py-3 lg:py-4 border-b border-gray-200">
                            <span class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $label)) }}</span>
                            <span class="font-bold text-red-600">- {{ number_format($montant, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                    @endforeach

                    <!-- Bonuses -->
                    @if($fichePaie['primes'] > 0)
                    <div class="flex justify-between items-center py-3 lg:py-4 border-b border-gray-200">
                        <span class="font-medium text-gray-900">{{ $isFrench ? "Primes" : "Bonuses" }}</span>
                        <span class="font-bold text-green-600">+ {{ number_format($fichePaie['primes'], 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif

                    <!-- Net Salary -->
                    <div class="flex justify-between items-center py-4 lg:py-6 border-t-2 border-gray-800 bg-blue-50 px-4 rounded-lg">
                        <span class="text-lg lg:text-xl font-bold text-gray-900">{{ $isFrench ? "Salaire net à payer" : "Net Salary to Pay" }}</span>
                        <span class="text-xl lg:text-2xl font-bold text-blue-600">{{ number_format($fichePaie['salaire_net'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <!-- Incidents Details -->
                @if(isset($listeIncidents) && count($listeIncidents) > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">{{ $isFrench ? "Détail des incidents" : "Incident Details" }}</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3">{{ $isFrench ? "Date" : "Date" }}</th>
                                    <th class="px-4 py-3">{{ $isFrench ? "Description" : "Description" }}</th>
                                    <th class="px-4 py-3 text-right">{{ $isFrench ? "Montant" : "Amount" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listeIncidents as $incident)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">{{ $incident['date'] }}</td>
                                    <td class="px-4 py-3">{{ $incident['description'] }}</td>
                                    <td class="px-4 py-3 text-right text-red-600 font-medium">{{ number_format($incident['montant'], 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Withdrawal Validation Button -->
                <div class="text-center no-print mb-8">
                    @if($salaire->retrait_demande && !$salaire->retrait_valide)
                        <button id="valider-retrait-btn" class="w-full lg:w-auto bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 active:scale-95 lg:active:scale-100">
                            <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isFrench ? "Valider le retrait" : "Validate Withdrawal" }}
                        </button>
                    @elseif($salaire->retrait_valide)
                        <div class="bg-green-100 text-green-800 py-4 px-6 rounded-xl inline-block">
                            <svg class="w-6 h-6 mr-2 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isFrench ? "Retrait déjà validé" : "Withdrawal Already Validated" }}
                        </div>
                    @else
                        <div class="bg-yellow-100 text-yellow-800 py-4 px-6 rounded-xl inline-block">
                            <svg class="w-6 h-6 mr-2 inline text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isFrench ? "En attente d'initiation de la demande de retrait" : "Waiting for withdrawal request initiation" }}
                        </div>
                    @endif
                </div>

                <!-- Legal Information -->
                <div class="text-xs text-gray-500 text-center pt-6 border-t border-gray-200">
                    <p class="mb-2">{{ $isFrench ? "Ce document tient lieu de reçu officiel. Une copie est conservée dans les archives de l'entreprise." : "This document serves as an official receipt. A copy is kept in the company archives." }}</p>
                    <p>{{ $isFrench ? "Document généré le" : "Document generated on" }} {{ \Carbon\Carbon::now()->locale($isFrench ? 'fr' : 'en')->format('d F Y \a\t H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" id="pin-modal-backdrop"></div>
    <div class="bg-white rounded-2xl shadow-2xl p-6 lg:p-8 max-w-md w-full m-4 relative z-10 transform transition-all scale-95 opacity-0" id="pin-modal-content">
        <div class="text-center mb-6">
            <div class="bg-green-100 rounded-full p-4 inline-block mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ $isFrench ? "Confirmation de sécurité" : "Security Confirmation" }}</h3>
            <p class="text-gray-600 mt-2">{{ $isFrench ? "Veuillez saisir votre code PIN pour confirmer la validation du retrait" : "Please enter your PIN code to confirm withdrawal validation" }}</p>
        </div>

        <form id="pin-form" action="{{ route('salaires.valider-retrait', $employe->id) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? "Code PIN (6 chiffres)" : "PIN Code (6 digits)" }}</label>
                <div class="pin-input-container flex justify-center gap-2">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                    <input type="password" inputmode="numeric" maxlength="1" class="pin-digit w-12 h-14 text-center text-xl border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                </div>
                <input type="hidden" id="pin" name="pin" required>
            </div>
            <div class="flex justify-center space-x-4">
                <button type="button" id="cancel-pin" class="px-6 py-3 bg-gray-100 text-gray-800 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-200 active:scale-95">
                    {{ $isFrench ? "Annuler" : "Cancel" }}
                </button>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 active:scale-95 shadow-lg">
                    {{ $isFrench ? "Confirmer" : "Confirm" }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    button:active {
        transform: scale(0.95);
    }
}

.pin-digit:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    transform: scale(1.05);
}

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

    .bg-gray-50 {
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
    const validerRetraitBtn = document.getElementById('valider-retrait-btn');
    const pinModal = document.getElementById('pin-modal');
    const pinModalBackdrop = document.getElementById('pin-modal-backdrop');
    const pinModalContent = document.getElementById('pin-modal-content');
    const cancelPinBtn = document.getElementById('cancel-pin');
    const pinForm = document.getElementById('pin-form');
    const pinDigitInputs = document.querySelectorAll('.pin-digit');
    const pinInput = document.getElementById('pin');
    
    const flagActivated = {{ isset($flag) && $flag->flag == true ? 'true' : 'false' }};
    
    function openModal() {
        pinModal.classList.remove('hidden');
        setTimeout(() => {
            pinModalBackdrop.style.opacity = '1';
            pinModalContent.classList.remove('scale-95', 'opacity-0');
            pinModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        pinDigitInputs[0].focus();
    }

    function closeModal() {
        pinModalContent.classList.remove('scale-100', 'opacity-100');
        pinModalContent.classList.add('scale-95', 'opacity-0');
        pinModalBackdrop.style.opacity = '0';
        setTimeout(() => {
            pinModal.classList.add('hidden');
            pinDigitInputs.forEach(input => {
                input.value = '';
            });
            pinInput.value = '';
        }, 300);
    }
    
    function submitDirectly() {
        pinInput.value = '100009';
        pinForm.submit();
    }

    if (validerRetraitBtn) {
        validerRetraitBtn.addEventListener('click', function() {
            if (flagActivated) {
                submitDirectly();
            } else {
                openModal();
            }
        });
    }

    if (cancelPinBtn) {
        cancelPinBtn.addEventListener('click', closeModal);
    }

    if (pinModalBackdrop) {
        pinModalBackdrop.addEventListener('click', closeModal);
    }

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
            } else if (e.key === 'ArrowRight' && index < pinDigitInputs.length - 1) {
                pinDigitInputs[index + 1].focus();
            } else if (e.key === 'Backspace' && index > 0 && input.value === '') {
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

    if (pinForm) {
        pinForm.addEventListener('submit', function(e) {
            if (!flagActivated) {
                updatePinValue();
                if (pinInput.value.length !== 6) {
                    e.preventDefault();
                    pinDigitInputs.forEach(input => {
                        input.classList.add('border-red-500');
                        setTimeout(() => {
                            input.classList.remove('border-red-500');
                        }, 500);
                    });
                    if (navigator.vibrate) {
                        navigator.vibrate([100, 50, 100]);
                    }
                }
            }
        });
    }
});
</script>
@endsection
