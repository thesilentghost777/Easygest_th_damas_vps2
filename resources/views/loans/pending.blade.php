@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Demandes de prêts" : "Loan Requests" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-purple-600 px-4 lg:px-6 py-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-2">
                <h2 class="text-lg lg:text-xl font-bold text-white">
                    {{ $isFrench ? "Demandes de prêts en attente" : "Pending Loan Requests" }}
                </h2>
                <a href="{{ route('loans.employees-with-loans') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200 active:scale-95 lg:active:scale-100">
                    {{ $isFrench ? "Voir les prêts en cours" : "View Active Loans" }}
                </a>
            </div>

            <!-- Messages -->
            <div class="p-4 lg:p-6">
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($pendingLoans->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-12 lg:py-16">
                        <div class="p-4 bg-gray-100 rounded-full mb-4 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        </div>
                        <h3 class="text-lg lg:text-xl font-medium text-gray-900 mb-2">
                            {{ $isFrench ? "Aucune demande en attente" : "No pending requests" }}
                        </h3>
                        <p class="text-gray-600 text-center text-sm lg:text-base">
                            {{ $isFrench ? "Aucune demande de prêt en attente" : "No loan requests pending" }}
                        </p>
                    </div>
                @else
                    <!-- Mobile Cards View -->
                    <div class="lg:hidden space-y-4">
                        @foreach($pendingLoans as $loan)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 transform transition-all duration-200 active:scale-98">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-purple-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $loan->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ Carbon\Carbon::parse($loan->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-purple-600">{{ number_format($loan->amount, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <form id="approveForm-{{ $loan->id }}" action="{{ route('loans.approve', $loan->id) }}" method="POST" class="pin-protected-form flex-1">
                                    @csrf
                                    <input type="hidden" name="pin" class="pin-value">
                                    <button type="button" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium action-button transition-all duration-200 active:scale-95" data-action="approve" data-loan-id="{{ $loan->id }}">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $isFrench ? "Approuver" : "Approve" }}
                                    </button>
                                </form>

                                <form id="rejectForm-{{ $loan->id }}" action="{{ route('loans.reject', $loan->id) }}" method="POST" class="pin-protected-form flex-1">
                                    @csrf
                                    <input type="hidden" name="pin" class="pin-value">
                                    <button type="button" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium action-button transition-all duration-200 active:scale-95" data-action="reject" data-loan-id="{{ $loan->id }}">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ $isFrench ? "Rejeter" : "Reject" }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Employé" : "Employee" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Date de demande" : "Request Date" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Montant" : "Amount" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Actions" : "Actions" }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingLoans as $loan)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $loan->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ Carbon\Carbon::parse($loan->created_at)->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($loan->amount, 0, ',', ' ') }} XAF</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <form id="approveForm-{{ $loan->id }}" action="{{ route('loans.approve', $loan->id) }}" method="POST" class="pin-protected-form">
                                                    @csrf
                                                    <input type="hidden" name="pin" class="pin-value">
                                                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm action-button transition-colors" data-action="approve" data-loan-id="{{ $loan->id }}">
                                                        {{ $isFrench ? "Approuver" : "Approve" }}
                                                    </button>
                                                </form>

                                                <form id="rejectForm-{{ $loan->id }}" action="{{ route('loans.reject', $loan->id) }}" method="POST" class="pin-protected-form">
                                                    @csrf
                                                    <input type="hidden" name="pin" class="pin-value">
                                                    <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm action-button transition-colors" data-action="reject" data-loan-id="{{ $loan->id }}">
                                                        {{ $isFrench ? "Rejeter" : "Reject" }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="securityOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div id="pinModalContainer" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 mx-4">
        <!-- Modal Header -->
        <div class="bg-purple-600 rounded-t-2xl p-6 relative">
            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-purple-600 p-4 rounded-full shadow-lg animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <h3 class="text-white text-center text-xl font-bold mt-2">
                {{ $isFrench ? "Vérification de sécurité" : "Security Verification" }}
            </h3>
            <p class="text-purple-100 text-center text-sm mt-1">
                {{ $isFrench ? "Veuillez saisir votre code de sécurité à 6 chiffres pour confirmer l'action" : "Please enter your 6-digit security code to confirm the action" }}
            </p>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-6">
                <p id="actionDescription" class="text-center text-gray-700 font-medium mb-4"></p>
                
                <!-- PIN Input -->
                <div class="flex justify-center space-x-2">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    <input type="text" class="pin-digit w-12 h-14 text-center text-xl font-bold bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                </div>
                
                <!-- Error Message -->
                <div id="pinError" class="mt-3 text-center text-red-600 text-sm hidden">
                    {{ $isFrench ? "Code PIN incorrect. Veuillez réessayer." : "Incorrect PIN code. Please try again." }}
                </div>
                
                <!-- Strength Indicator -->
                <div class="flex justify-center mt-4">
                    <div class="bg-gray-200 h-1 w-full max-w-xs rounded-full overflow-hidden">
                        <div id="pinStrengthIndicator" class="h-full bg-gray-400 transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="flex space-x-3">
                <button id="cancelPinButton" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors duration-200 font-medium active:scale-95">
                    {{ $isFrench ? "Annuler" : "Cancel" }}
                </button>
                <button id="confirmPinButton" class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-all duration-200 font-medium opacity-50 cursor-not-allowed active:scale-95">
                    {{ $isFrench ? "Confirmer" : "Confirm" }}
                </button>
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
    
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .pin-digit:focus {
        transform: scale(1.05);
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active, .active\:scale-98:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}

/* Shake animation for PIN error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const securityOverlay = document.getElementById('securityOverlay');
    const pinModalContainer = document.getElementById('pinModalContainer');
    const pinDigits = document.querySelectorAll('.pin-digit');
    const confirmPinButton = document.getElementById('confirmPinButton');
    const cancelPinButton = document.getElementById('cancelPinButton');
    const actionDescription = document.getElementById('actionDescription');
    const pinStrengthIndicator = document.getElementById('pinStrengthIndicator');
    const pinError = document.getElementById('pinError');
    
    let currentFormId = null;
    let currentLoanId = null;
    let currentAction = null;
    
    // Attach events to action buttons
    document.querySelectorAll('.action-button').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const loanId = this.dataset.loanId;
            
            currentAction = action;
            currentLoanId = loanId;
            currentFormId = `${action}Form-${loanId}`;
            
            actionDescription.textContent = action === 'approve' 
                ? '{{ $isFrench ? "Vous êtes sur le point d\'approuver cette demande de prêt" : "You are about to approve this loan request" }}'
                : '{{ $isFrench ? "Vous êtes sur le point de rejeter cette demande de prêt" : "You are about to reject this loan request" }}';
            
            openPinModal();
        });
    });
    
    function openPinModal() {
        resetPinModal();
        securityOverlay.classList.remove('hidden');
        
        setTimeout(() => {
            securityOverlay.classList.add('opacity-100');
            securityOverlay.classList.remove('opacity-0');
            
            pinModalContainer.classList.add('opacity-100', 'scale-100');
            pinModalContainer.classList.remove('opacity-0', 'scale-95');
            
            pinDigits[0].focus();
        }, 10);
    }
    
    function closePinModal() {
        securityOverlay.classList.remove('opacity-100');
        securityOverlay.classList.add('opacity-0');
        
        pinModalContainer.classList.remove('opacity-100', 'scale-100');
        pinModalContainer.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            securityOverlay.classList.add('hidden');
            resetPinModal();
        }, 300);
    }
    
    function resetPinModal() {
        pinDigits.forEach(input => {
            input.value = '';
            input.classList.remove('border-red-500', 'border-green-500');
            input.classList.add('border-gray-300');
        });
        
        updatePinStrength(0);
        pinError.classList.add('hidden');
        confirmPinButton.classList.add('opacity-50', 'cursor-not-allowed');
        confirmPinButton.disabled = true;
    }
    
    // PIN digit handling
    pinDigits.forEach((digit, index) => {
        digit.addEventListener('focus', function() {
            this.select();
        });
        
        digit.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value !== '') {
                if (index < pinDigits.length - 1) {
                    pinDigits[index + 1].focus();
                }
            }
            
            checkPinCompletion();
        });
        
        digit.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                pinDigits[index - 1].focus();
            }
            
            if (e.key === 'ArrowLeft' && index > 0) {
                pinDigits[index - 1].focus();
            }
            
            if (e.key === 'ArrowRight' && index < pinDigits.length - 1) {
                pinDigits[index + 1].focus();
            }
        });
    });
    
    function checkPinCompletion() {
        let filledCount = 0;
        let pinComplete = true;
        
        pinDigits.forEach(digit => {
            if (digit.value.length === 1) {
                filledCount++;
            } else {
                pinComplete = false;
            }
        });
        
        const strength = (filledCount / pinDigits.length) * 100;
        updatePinStrength(strength);
        
        confirmPinButton.disabled = !pinComplete;
        if (pinComplete) {
            confirmPinButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            confirmPinButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    function updatePinStrength(percentage) {
        pinStrengthIndicator.style.width = `${percentage}%`;
        
        if (percentage < 33) {
            pinStrengthIndicator.className = 'h-full bg-red-500 transition-all duration-300';
        } else if (percentage < 67) {
            pinStrengthIndicator.className = 'h-full bg-yellow-500 transition-all duration-300';
        } else if (percentage < 100) {
            pinStrengthIndicator.className = 'h-full bg-blue-500 transition-all duration-300';
        } else {
            pinStrengthIndicator.className = 'h-full bg-green-500 transition-all duration-300';
        }
    }
    
    confirmPinButton.addEventListener('click', function() {
        if (this.disabled) return;
        
        let pin = '';
        pinDigits.forEach(digit => {
            pin += digit.value;
        });
        
        pinDigits.forEach(digit => {
            digit.classList.remove('border-gray-300');
            digit.classList.add('border-green-500');
        });
        
        const form = document.getElementById(currentFormId);
        if (form) {
            const pinInput = form.querySelector('.pin-value');
            pinInput.value = pin;
            
            setTimeout(() => {
                closePinModal();
                setTimeout(() => {
                    form.submit();
                }, 300);
            }, 500);
        }
        
        // Vibration feedback on mobile
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
    
    cancelPinButton.addEventListener('click', closePinModal);
    
    securityOverlay.addEventListener('click', function(e) {
        if (e.target === securityOverlay) {
            closePinModal();
        }
    });
});
</script>
@endsection
