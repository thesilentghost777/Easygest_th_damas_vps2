@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-4 sm:py-8 lg:py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-teal-400 px-4 sm:px-6 py-6 sm:py-8">
            <h2 class="text-center text-2xl sm:text-3xl font-extrabold text-white">
                {{ $isFrench ?? true ? 'Créez votre code PIN' : 'Create your PIN code' }}
            </h2>
            <p class="mt-2 text-center text-sm sm:text-base text-white text-opacity-90">
                {{ $isFrench ?? true ? 'Veuillez choisir un code PIN sécurisé à 6 chiffres' : 'Please choose a secure 6-digit PIN code' }}
            </p>
        </div>
        
        <!-- Content -->
        <div class="px-4 sm:px-6 py-6 sm:py-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ $isFrench ?? true ? 'Plusieurs erreurs ont été détectées :' : 'Several errors have been detected:' }}
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('setup2.store') }}" class="space-y-5 sm:space-y-6">
                @csrf
                
                <!-- PIN Code Input -->
                <div>
                    <label for="pin_code" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ?? true ? 'Code PIN (6 chiffres)' : 'PIN Code (6 digits)' }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="password" 
                               name="pin_code" 
                               id="pin_code" 
                               required 
                               autocomplete="new-password" 
                               maxlength="6" 
                               pattern="[0-9]{6}" 
                               inputmode="numeric"
                               class="block w-full pl-10 pr-12 py-3 sm:py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-lg text-center tracking-widest transition-colors duration-200"
                               placeholder="• • • • • •">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePin" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm text-gray-500">
                        {{ $isFrench ?? true ? 'Votre code PIN doit contenir exactement 6 chiffres' : 'Your PIN code must contain exactly 6 digits' }}
                    </p>
                </div>
                
                <!-- PIN Confirmation Input -->
                <div>
                    <label for="pin_code_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ?? true ? 'Confirmez votre code PIN' : 'Confirm your PIN code' }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="password" 
                               name="pin_code_confirmation" 
                               id="pin_code_confirmation" 
                               required 
                               autocomplete="new-password" 
                               maxlength="6" 
                               pattern="[0-9]{6}" 
                               inputmode="numeric"
                               class="block w-full pl-10 pr-12 py-3 sm:py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-lg text-center tracking-widest transition-colors duration-200"
                               placeholder="• • • • • •">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePinConfirm" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Help Link -->
                <div class="flex items-center justify-center sm:justify-between pt-2 sm:pt-4">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                            {{ $isFrench ?? true ? 'Besoin d\'aide?' : 'Need help?' }}
                        </a>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="group relative w-full flex justify-center py-3 sm:py-4 px-4 border border-transparent text-base sm:text-lg font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out active:scale-95">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-200 group-hover:text-blue-100 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        {{ $isFrench ?? true ? 'Enregistrer mon code PIN' : 'Save my PIN code' }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Security Notice -->
        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-t border-gray-100">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                    {{ $isFrench ?? true ? 'Ne partagez votre code PIN avec personne. Notre équipe ne vous demandera jamais votre code PIN.' : 'Never share your PIN code with anyone. Our team will never ask for your PIN code.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Mobile-Optimized JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle PIN visibility function
    function setupToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        
        if (!toggle || !input) return;
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Enhanced icons with better mobile touch targets
            const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>`;
            
            const eyeClosed = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
            </svg>`;
            
            this.innerHTML = type === 'text' ? eyeClosed : eyeOpen;
            
            // Add haptic feedback for mobile
            if ('vibrate' in navigator) {
                navigator.vibrate(50);
            }
        });
        
        // Improve touch target for mobile
        toggle.style.minWidth = '44px';
        toggle.style.minHeight = '44px';
        toggle.style.display = 'flex';
        toggle.style.alignItems = 'center';
        toggle.style.justifyContent = 'center';
    }
    
    // Setup toggles for both inputs
    setupToggle('togglePin', 'pin_code');
    setupToggle('togglePinConfirm', 'pin_code_confirmation');
    
    // Enhanced input validation with better mobile UX
    function setupInputValidation(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        // Format input in real-time
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            value = value.substring(0, 6);
            this.value = value;
            
            // Visual feedback for completion
            if (value.length === 6) {
                this.classList.add('border-green-500', 'ring-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500', 'ring-green-500');
                this.classList.add('border-gray-300');
            }
        });
        
        // Handle paste events
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text');
            const numericData = pastedData.replace(/[^0-9]/g, '').substring(0, 6);
            this.value = numericData;
            this.dispatchEvent(new Event('input'));
        });
        
        // Mobile-specific improvements
        input.addEventListener('focus', function() {
            // Scroll input into view on mobile
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
        
        // Auto-advance to next field when PIN is complete
        if (inputId === 'pin_code') {
            input.addEventListener('input', function() {
                if (this.value.length === 6) {
                    const confirmInput = document.getElementById('pin_code_confirmation');
                    if (confirmInput) {
                        setTimeout(() => confirmInput.focus(), 100);
                    }
                }
            });
        }
    }
    
    // Setup validation for both inputs
    setupInputValidation('pin_code');
    setupInputValidation('pin_code_confirmation');
    
    // Real-time PIN matching feedback
    const pinInput = document.getElementById('pin_code');
    const confirmInput = document.getElementById('pin_code_confirmation');
    
    if (pinInput && confirmInput) {
        function checkPinMatch() {
            if (confirmInput.value.length > 0) {
                if (pinInput.value === confirmInput.value && pinInput.value.length === 6) {
                    confirmInput.classList.add('border-green-500', 'ring-green-500');
                    confirmInput.classList.remove('border-red-500', 'ring-red-500');
                } else if (confirmInput.value.length === 6) {
                    confirmInput.classList.add('border-red-500', 'ring-red-500');
                    confirmInput.classList.remove('border-green-500', 'ring-green-500');
                } else {
                    confirmInput.classList.remove('border-red-500', 'ring-red-500', 'border-green-500', 'ring-green-500');
                    confirmInput.classList.add('border-gray-300');
                }
            }
        }
        
        pinInput.addEventListener('input', checkPinMatch);
        confirmInput.addEventListener('input', checkPinMatch);
    }
    
    // Enhanced form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Prevent double submission
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }
            
            // Add loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ${document.documentElement.lang === 'fr' ? 'Enregistrement...' : 'Saving...'}
                </div>
            `;
            
            // Re-enable button after timeout (fallback)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-200 group-hover:text-blue-100 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    ${document.documentElement.lang === 'fr' ? 'Enregistrer mon code PIN' : 'Save my PIN code'}
                `;
            }, 10000);
        });
    }
    
    // Accessibility improvements
    document.querySelectorAll('input[type="password"]').forEach(input => {
        input.setAttribute('autocapitalize', 'none');
        input.setAttribute('autocorrect', 'off');
        input.setAttribute('spellcheck', 'false');
    });
});
</script>

<!-- Mobile-specific styles -->
<style>
    /* Ensure inputs are properly sized on mobile */
    @media (max-width: 640px) {
        input[type="password"] {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
        
        /* Improve button touch targets */
        button {
            min-height: 44px;
        }
        
        /* Better mobile card appearance */
        .max-w-md {
            margin: 0 16px;
        }
    }
    
    /* Loading animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Smooth transitions */
    input {
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    /* Focus states for accessibility */
    button:focus-visible {
        outline: 2px solid #3B82F6;
        outline-offset: 2px;
    }
    
    input:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection