@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8 md:px-8 md:py-12">
    @include('buttons')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8 transform transition-all duration-300 ease-in-out md:max-w-lg md:shadow-xl" id="main-form">
        <div class="text-center mb-6 md:mb-8">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 md:w-20 md:h-20 md:mb-6">
                <svg class="w-8 h-8 text-blue-600 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-blue-600 mb-2 md:text-3xl">
                {{ $isFrench ? 'Demande d\'avance sur salaire' : 'Salary Advance Request' }}
            </h2>
        </div>
        
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
            {{ session('error') }}
        </div>
        @endif
        
        <form id="avance-form" action="{{ route('store-demandes-as') }}" method="POST">
            @csrf
            <div class="mb-6 md:mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-3 md:text-base md:text-gray-800" for="sommeAs">
                    {{ $isFrench ? 'Montant demandé' : 'Requested Amount' }}
                </label>
                <div class="relative mb-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-lg font-semibold md:text-xl">XAF</span>
                    </div>
                    <input type="number"
                        name="sommeAs"
                        id="sommeAs"
                        class="shadow appearance-none border rounded w-full py-3 pl-16 pr-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 md:py-4 md:text-xl md:pl-20"
                        placeholder="{{ $isFrench ? 'Entrez le montant...' : 'Enter amount...' }}"
                        required>
                </div>
                <p class="text-sm text-gray-500 mt-2 md:text-base">
                    {{ $isFrench ? 'Montant minimum: 1,000 XAF' : 'Minimum amount: 1,000 XAF' }}
                </p>
            </div>
            
            <button type="button"
                id="submit-avance"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 w-full transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 text-lg md:py-5 md:text-xl">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    {{ $isFrench ? 'Soumettre la demande' : 'Submit Request' }}
                </span>
            </button>
        </form>
    </div>

    <!-- Modal Code PIN -->
    <div id="pin-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 md:max-w-md md:p-10" id="pin-modal-content">
            <div class="text-center mb-8 md:mb-10">
                <div class="bg-blue-100 text-blue-600 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 md:w-24 md:h-24 md:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 md:text-3xl">
                    {{ $isFrench ? 'Confirmation requise' : 'Confirmation Required' }}
                </h3>
                <p class="text-gray-600 mt-2 text-base leading-relaxed md:text-lg md:mt-4">
                    {{ $isFrench ? 'Veuillez entrer votre code PIN pour confirmer votre demande d\'avance sur salaire' : 'Please enter your PIN code to confirm your salary advance request' }}
                </p>
            </div>
            
            <div class="mb-8 md:mb-10">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" 
                           id="code_pin" 
                           name="code_pin" 
                           class="shadow appearance-none border rounded-xl w-full py-4 pl-10 pr-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-center tracking-widest text-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-200 md:py-5 md:text-2xl"
                           placeholder="• • • • • •" 
                           maxlength="6" 
                           pattern="[0-9]{6}" 
                           inputmode="numeric"
                           autocomplete="off"
                           required>
                </div>
                <div id="pin-error" class="mt-2 text-red-600 text-base hidden md:text-lg">
                    {{ $isFrench ? 'Code PIN incorrect. Veuillez réessayer.' : 'Incorrect PIN code. Please try again.' }}
                </div>
            </div>
            
            <div class="flex flex-col space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <button type="button" 
                        id="cancel-pin" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-200 w-full text-lg order-2 md:order-1 md:py-3 md:text-base md:w-1/2">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="button" 
                        id="confirm-pin" 
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 w-full text-lg shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 order-1 md:order-2 md:py-3 md:text-base md:w-1/2">
                    {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Animation keyframes */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Touch feedback and smooth interactions */
* {
    -webkit-tap-highlight-color: transparent;
}

button {
    transition: all 0.2s ease;
}

button:active {
    transform: scale(0.98);
}

input:focus {
    transform: scale(1.02);
}

/* Improved focus states */
input:focus, button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Ensure proper mobile spacing */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    #main-form {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }
}

/* Desktop improvements */
@media (min-width: 769px) {
    #main-form {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    
    #main-form:hover {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
    
    #pin-modal-content {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.3);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainForm = document.getElementById('main-form');
    const avanceForm = document.getElementById('avance-form');
    const submitBtn = document.getElementById('submit-avance');
    const pinModal = document.getElementById('pin-modal');
    const pinModalContent = document.getElementById('pin-modal-content');
    const codePin = document.getElementById('code_pin');
    const confirmPin = document.getElementById('confirm-pin');
    const cancelPin = document.getElementById('cancel-pin');
    const pinError = document.getElementById('pin-error');
    const sommeAs = document.getElementById('sommeAs');
    
    // Add entrance animation to main form
    setTimeout(() => {
        mainForm.style.transform = 'translateY(0) scale(1)';
        mainForm.style.opacity = '1';
    }, 100);
    
    // Input validation and formatting
    sommeAs.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value && parseInt(this.value) < 1000) {
            this.style.borderColor = '#ef4444';
            this.style.boxShadow = '0 0 0 2px rgba(239, 68, 68, 0.2)';
        } else {
            this.style.borderColor = '#e5e7eb';
            this.style.boxShadow = '';
        }
    });
    
    // Mobile haptic feedback simulation
    function vibrate() {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }
    
    // Handle PIN input (numbers only)
    codePin.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
        if (this.value.length === 6) {
            vibrate();
        }
    });
    
    // Open PIN modal
    submitBtn.addEventListener('click', function() {
        const amount = sommeAs.value;
        if (!amount || parseInt(amount) < 1000) {
            vibrate();
            sommeAs.focus();
            sommeAs.classList.add('animate-shake');
            setTimeout(() => {
                sommeAs.classList.remove('animate-shake');
            }, 500);
            return;
        }
        
        vibrate();
        mainForm.style.transform = 'translateY(1rem) scale(0.95)';
        mainForm.style.opacity = '0.5';
        
        setTimeout(() => {
            pinModal.classList.remove('opacity-0', 'pointer-events-none');
            pinModalContent.classList.remove('scale-95');
            pinModalContent.classList.add('scale-100');
            codePin.focus();
        }, 300);
    });
    
    // Close modal
    cancelPin.addEventListener('click', function() {
        vibrate();
        closeModal();
    });
    
    // Confirm PIN
    confirmPin.addEventListener('click', function() {
        if (codePin.value.length !== 6) {
            vibrate();
            codePin.classList.add('animate-shake');
            setTimeout(() => {
                codePin.classList.remove('animate-shake');
            }, 500);
            return;
        }
        
        vibrate();
        const pinInputHidden = document.createElement('input');
        pinInputHidden.type = 'hidden';
        pinInputHidden.name = 'code_pin';
        pinInputHidden.value = codePin.value;
        avanceForm.appendChild(pinInputHidden);
        
        // Loading animation
        confirmPin.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto md:h-6 md:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        confirmPin.disabled = true;
        
        setTimeout(() => {
            avanceForm.submit();
        }, 1000);
    });
    
    // Allow Enter key submission
    codePin.addEventListener('keyup', function(event) {
        if (event.key === 'Enter' && this.value.length === 6) {
            confirmPin.click();
        }
    });
    
    // Close modal function
    function closeModal() {
        pinModalContent.classList.remove('scale-100');
        pinModalContent.classList.add('scale-95');
        pinModal.classList.add('opacity-0');
        
        setTimeout(() => {
            pinModal.classList.add('pointer-events-none');
            mainForm.style.transform = 'translateY(0) scale(1)';
            mainForm.style.opacity = '1';
        }, 300);
        
        codePin.value = '';
        pinError.classList.add('hidden');
    }
    
    // Close modal when clicking outside
    pinModal.addEventListener('click', function(event) {
        if (event.target === pinModal) {
            closeModal();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !pinModal.classList.contains('opacity-0')) {
            closeModal();
        }
    });
    
    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            // Add visual focus indicators
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
    });
});
</script>
@endsection