@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        @include('buttons')
        
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4 shadow-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                {{ $isFrench ? 'Nouvelle Session de Calcul' : 'New Calculation Session' }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? 'Créez une nouvelle session pour calculer les articles manquants' : 'Create a new session to calculate missing items' }}
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden backdrop-blur-sm bg-white/90">
            <div class="p-8">
                <form action="{{ route('inventory.calculations.store', $group) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Session Title Field -->
                    <div class="form-group">
                        <label for="title" class="block text-sm font-semibold text-gray-800 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $isFrench ? 'Titre de la Session' : 'Session Title' }}
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400"
                                placeholder="{{ $isFrench ? 'Entrez le titre de votre session...' : 'Enter your session title...' }}"
                                value="{{ old('title') }}" 
                                required
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>
                        </div>
                        @error('title')
                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-600 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- Date Field -->
                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-800 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $isFrench ? 'Date de la Session' : 'Session Date' }}
                            </span>
                        </label>
                        <div class="relative">
                            <input 
                                type="date" 
                                name="date" 
                                id="date" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 bg-gray-50 focus:bg-white text-gray-800"
                                value="{{ old('date', date('Y-m-d')) }}" 
                                required
                            >
                        </div>
                        @error('date')
                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-600 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group"
                        >
                            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>{{ $isFrench ? 'Créer la Session' : 'Create Session' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Helper Text -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                {{ $isFrench ? 'Une fois créée, vous pourrez ajouter des articles à votre session' : 'Once created, you can add items to your session' }}
            </p>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Enhanced form styling */
    .form-group {
        position: relative;
    }
    
    /* Input focus effects */
    input:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.2);
    }
    
    /* Button hover effects */
    button[type="submit"]:hover {
        box-shadow: 0 20px 25px -5px rgba(34, 197, 94, 0.4), 0 10px 10px -5px rgba(34, 197, 94, 0.2);
    }
    
    /* Smooth animations */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .max-w-2xl {
            max-width: 100%;
            margin: 0 1rem;
        }
        
        .bg-white {
            margin: 0;
            border-radius: 1.5rem;
        }
        
        .p-8 {
            padding: 1.5rem;
        }
        
        input:focus {
            transform: none;
        }
        
        /* Touch-friendly sizing */
        input, button {
            min-height: 48px;
        }
        
        /* Haptic feedback simulation */
        button:active {
            background: linear-gradient(to right, #15803d, #166534);
        }
    }
    
    /* Loading state */
    button[type="submit"]:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }
    
    /* Form validation styling */
    input:valid {
        border-color: #22c55e;
    }
    
    input:invalid:not(:focus):not(:placeholder-shown) {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .bg-gradient-to-br {
            background: linear-gradient(to bottom right, #1f2937, #111827);
        }
        
        .bg-white {
            background-color: #1f2937;
            border: 1px solid #374151;
        }
        
        .text-gray-800 {
            color: #f9fafb;
        }
        
        .text-gray-600 {
            color: #d1d5db;
        }
        
        .bg-gray-50 {
            background-color: #374151;
        }
        
        .border-gray-200 {
            border-color: #4b5563;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form interactions
    initializeFormEnhancements();
    
    function initializeFormEnhancements() {
        const form = document.querySelector('form');
        const titleInput = document.getElementById('title');
        const dateInput = document.getElementById('date');
        const submitButton = document.querySelector('button[type="submit"]');
        
        // Add entrance animation
        animateFormEntrance();
        
        // Real-time validation
        addRealTimeValidation(titleInput, dateInput);
        
        // Form submission handling
        handleFormSubmission(form, submitButton);
        
        // Touch feedback for mobile
        addTouchFeedback();
    }
    
    function animateFormEntrance() {
        const card = document.querySelector('.bg-white');
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px) scale(0.95)';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0) scale(1)';
        }, 150);
    }
    
    function addRealTimeValidation(titleInput, dateInput) {
        // Title validation
        titleInput.addEventListener('input', function() {
            const isValid = this.value.trim().length >= 3;
            updateFieldValidation(this, isValid);
        });
        
        // Date validation
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            const isValid = selectedDate <= today;
            updateFieldValidation(this, isValid);
            
            if (!isValid) {
                showDateWarning();
            }
        });
    }
    
    function updateFieldValidation(field, isValid) {
        if (isValid) {
            field.classList.remove('border-red-300');
            field.classList.add('border-green-300');
        } else {
            field.classList.remove('border-green-300');
            field.classList.add('border-red-300');
        }
    }
    
    function showDateWarning() {
        // You can add a toast notification here
        console.log('Future date selected - this might be intentional');
    }
    
    function handleFormSubmission(form, submitButton) {
        form.addEventListener('submit', function(e) {
            // Add loading state
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ $isFrench ? 'Création...' : 'Creating...' }}
            `;
            
            // Re-enable after 3 seconds (fallback)
            setTimeout(() => {
                if (submitButton.disabled) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = `
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>{{ $isFrench ? 'Créer la Session' : 'Create Session' }}</span>
                    `;
                }
            }, 3000);
        });
    }
    
    function addTouchFeedback() {
        if ('vibrate' in navigator) {
            const interactiveElements = document.querySelectorAll('input, button');
            interactiveElements.forEach(element => {
                element.addEventListener('touchstart', () => {
                    navigator.vibrate(10);
                });
            });
        }
    }
});
</script>
@endpush
@endsection