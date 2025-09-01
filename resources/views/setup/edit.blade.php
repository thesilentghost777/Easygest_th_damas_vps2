@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Modifier le complexe" : "Edit Complex" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>
        
        <div class="max-w-3xl mx-auto bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="p-4 lg:p-6 bg-blue-600 text-white">
                <h1 class="text-xl lg:text-2xl font-bold">
                    {{ $isFrench ? "Modifier les informations du complexe" : "Edit Complex Information" }}
                </h1>
                <p class="mt-2 text-blue-100">
                    {{ $isFrench ? "Mettez à jour les informations de base de votre complexe." : "Update your complex's basic information." }}
                </p>
            </div>

            <!-- Messages -->
            @if (session('success'))
            <div class="bg-blue-50 text-blue-700 p-4 border-l-4 border-blue-500 mx-4 lg:mx-6 mt-4 lg:mt-6 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 border-l-4 border-red-500 mx-4 lg:mx-6 mt-4 lg:mt-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form id="complexe-form" action="{{ route('setup.update') }}" method="POST" class="p-4 lg:p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-gray-50 p-4 lg:p-6 rounded-xl border border-gray-100">
                    <h2 class="text-lg lg:text-xl font-semibold mb-4 flex items-center text-gray-900">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        {{ $isFrench ? "Informations du complexe" : "Complex Information" }}
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Nom du complexe" : "Complex Name" }}
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $complexe->nom) }}"
                                class="w-full px-4 py-3 lg:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                required>
                        </div>

                        <div>
                            <label for="localisation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Localisation" : "Location" }}
                            </label>
                            <input type="text" name="localisation" id="localisation" value="{{ old('localisation', $complexe->localisation) }}"
                                class="w-full px-4 py-3 lg:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                        <div>
                            <label for="revenu_mensuel" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Revenu mensuel (FCFA)" : "Monthly Revenue (FCFA)" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="revenu_mensuel" id="revenu_mensuel" value="{{ old('revenu_mensuel', $complexe->revenu_mensuel) }}"
                                    class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <span class="absolute right-4 top-2.5 lg:top-2 text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>

                        <div>
                            <label for="revenu_annuel" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Revenu annuel (FCFA)" : "Annual Revenue (FCFA)" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="revenu_annuel" id="revenu_annuel" value="{{ old('revenu_annuel', $complexe->revenu_annuel) }}"
                                    class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <span class="absolute right-4 top-2.5 lg:top-2 text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>

                        <div>
                            <label for="solde" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Solde actuel (FCFA)" : "Current Balance (FCFA)" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="solde" id="solde" value="{{ old('solde', $complexe->solde) }}"
                                    class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <span class="absolute right-4 top-2.5 lg:top-2 text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>

                        <div>
                            <label for="caisse_sociale" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? "Caisse sociale (FCFA)" : "Social Fund (FCFA)" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="caisse_sociale" id="caisse_sociale" value="{{ old('caisse_sociale', $complexe->caisse_sociale) }}"
                                    class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <span class="absolute right-4 top-2.5 lg:top-2 text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden PIN field if flag is enabled -->
                @if(isset($flag) && $flag->flag == true)
                <input type="hidden" name="pin" value="100009">
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col lg:flex-row justify-between gap-3 pt-6">
                    <div class="flex flex-col lg:flex-row gap-3">
                        <a href="{{ route('dashboard') }}" class="w-full lg:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center font-medium transition-all duration-200 active:scale-95 lg:active:scale-100">
                            {{ $isFrench ? "Retour au tableau de bord" : "Back to Dashboard" }}
                        </a>
                        <a href="{{ route('payday.config') }}" class="w-full lg:w-auto px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-center font-medium transition-all duration-200 active:scale-95 lg:active:scale-100">
                            {{ $isFrench ? "Configuration des jours de paiement" : "Payment Days Configuration" }}
                        </a>
                    </div>
                    <button type="button" id="submit-complexe-btn" class="w-full lg:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $isFrench ? "Mettre à jour" : "Update" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95">
        <div class="bg-blue-600 px-6 py-4 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">
                {{ $isFrench ? "Confirmation de sécurité" : "Security Confirmation" }}
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-6">
                {{ $isFrench ? "Veuillez saisir votre code PIN à 6 chiffres pour confirmer cette modification." : "Please enter your 6-digit PIN code to confirm this modification." }}
            </p>
            
            <div class="mb-6">
                <div class="flex justify-center mb-2">
                    <div class="pin-input-container flex gap-2">
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        <input type="text" class="pin-digit w-12 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200" maxlength="1" pattern="[0-9]" inputmode="numeric" />
                    </div>
                </div>
                <input type="hidden" id="pin-complete" name="pin">
            </div>

            <div class="flex justify-between gap-3">
                <button type="button" id="cancel-pin-btn" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 font-medium transition-all duration-200 active:scale-95">
                    {{ $isFrench ? "Annuler" : "Cancel" }}
                </button>
                <button type="button" id="submit-pin-btn" class="flex-1 px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95" disabled>
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
    
    input:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flagEnabled = {{ isset($flag) && $flag->flag == true ? 'true' : 'false' }};
    const form = document.getElementById('complexe-form');
    const submitBtn = document.getElementById('submit-complexe-btn');
    const pinModal = document.getElementById('pin-modal');
    const cancelPinBtn = document.getElementById('cancel-pin-btn');
    const submitPinBtn = document.getElementById('submit-pin-btn');
    const pinDigits = document.querySelectorAll('.pin-digit');
    const pinComplete = document.getElementById('pin-complete');
    
    function checkPinCompletion() {
        let isComplete = true;
        let pinValue = '';
        
        pinDigits.forEach(digit => {
            if (digit.value === '') {
                isComplete = false;
            }
            pinValue += digit.value;
        });
        
        pinComplete.value = pinValue;
        submitPinBtn.disabled = !isComplete;
    }
    
    // PIN digit handling
    pinDigits.forEach((digit, index) => {
        digit.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length === 1) {
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
            
            if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
                e.preventDefault();
            }
        });
        
        digit.addEventListener('focus', function() {
            this.select();
        });
    });
    
    // Main form submission
    submitBtn.addEventListener('click', function() {
        if (flagEnabled) {
            form.submit();
            return;
        }
        
        pinModal.classList.remove('hidden');
        setTimeout(() => {
            pinModal.classList.remove('opacity-0');
            pinModal.querySelector('.scale-95').classList.add('scale-100');
            pinDigits[0].focus();
        }, 10);
    });
    
    // Cancel PIN
    cancelPinBtn.addEventListener('click', function() {
        closeModal();
    });
    
    // Submit PIN
    submitPinBtn.addEventListener('click', function() {
        const pinInput = document.createElement('input');
        pinInput.type = 'hidden';
        pinInput.name = 'pin';
        pinInput.value = pinComplete.value;
        form.appendChild(pinInput);
        
        form.submit();
        
        // Vibration feedback
        if (navigator.vibrate) {
            navigator.vibrate(100);
        }
    });
    
    function closeModal() {
        pinModal.classList.add('opacity-0');
        pinModal.querySelector('.scale-100').classList.remove('scale-100');
        setTimeout(() => {
            pinModal.classList.add('hidden');
            pinDigits.forEach(digit => {
                digit.value = '';
            });
            pinComplete.value = '';
            submitPinBtn.disabled = true;
        }, 300);
    }
    
    // Close modal on outside click
    pinModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Enter key on last digit
    pinDigits[pinDigits.length - 1].addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !submitPinBtn.disabled) {
            submitPinBtn.click();
        }
    });

    // Add input focus animations
    document.querySelectorAll('input').forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
        
        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
    });
});
</script>
@endsection
