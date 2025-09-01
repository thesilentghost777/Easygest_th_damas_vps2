@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Gestion des annonces' : 'Announcement Management' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Tableau de bord des communications' : 'Communications dashboard' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:py-8 md:px-4 max-w-4xl">
        
        <!-- New Announcement Form -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 md:mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 md:p-6 border-b border-blue-200">
                <h3 class="text-xl md:text-2xl font-bold text-blue-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $isFrench ? 'Nouvelle annonce' : 'New announcement' }}
                </h3>
            </div>
            
            <div class="p-4 md:p-6">
                <form id="announcement-form" action="{{ route('announcements.store') }}" method="POST" class="space-y-4 md:space-y-6">
                    @csrf
                    
                    <!-- Title Field -->
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold text-sm md:text-base">
                            {{ $isFrench ? 'Titre' : 'Title' }}
                        </label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 text-base">
                    </div>
                    
                    <!-- Content Field -->
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold text-sm md:text-base">
                            {{ $isFrench ? 'Contenu' : 'Content' }}
                        </label>
                        <textarea name="content" rows="4" required
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 resize-none text-base"></textarea>
                    </div>
                    
                    <!-- Hidden PIN field for auto PIN -->
                    @if(isset($flag) && $flag->flag == true)
                    <input type="hidden" name="pin" value="100009">
                    @endif
                    
                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="button" id="submit-announcement-btn" 
                                class="w-full md:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 active:scale-95 focus:ring-4 focus:ring-blue-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            {{ $isFrench ? 'Publier l\'annonce' : 'Publish announcement' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Announcements List -->
        @foreach($announcements as $announcement)
        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden hover:shadow-xl transition-all duration-300">
            
            <!-- Announcement Header -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 md:p-6 border-b border-blue-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2">
                    <h4 class="text-lg md:text-xl font-bold text-blue-900">
                        {{ $announcement->title }}
                    </h4>
                    <span class="text-sm text-gray-600 flex-shrink-0">
                        {{ $isFrench ? 'Publié le' : 'Published on' }} {{ $announcement->created_at->format($isFrench ? 'd/m/Y H:i' : 'M d, Y H:i') }}
                    </span>
                </div>
            </div>
            
            <!-- Announcement Content -->
            <div class="p-4 md:p-6">
                <p class="text-gray-700 mb-6 text-base md:text-lg leading-relaxed">
                    {{ $announcement->content }}
                </p>
                
                <!-- Reactions Section -->
                <div class="border-t border-gray-100 pt-4 md:pt-6">
                    <h6 class="text-blue-900 font-semibold mb-4 flex items-center text-sm md:text-base">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.294-1.13L3 21l1.13-5.706A8.955 8.955 0 013 11c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                        </svg>
                        {{ $isFrench ? 'Réactions' : 'Reactions' }} ({{ $announcement->reactions->count() }})
                    </h6>
                    
                    <div class="space-y-3 md:space-y-4">
                        @foreach($announcement->reactions as $reaction)
                        <div class="border-l-4 border-blue-200 pl-4 py-2 bg-blue-50 rounded-r-lg hover:border-blue-400 transition-colors duration-200">
                            <div class="flex flex-col md:flex-row md:items-center gap-1 md:gap-2 mb-1">
                                <strong class="text-gray-900 text-sm md:text-base">{{ $reaction->user->name }}</strong>
                                <span class="text-xs md:text-sm text-gray-500">
                                    {{ $isFrench ? 'le' : 'on' }} {{ $reaction->created_at->format($isFrench ? 'd/m/Y H:i' : 'M d, Y H:i') }}
                                </span>
                            </div>
                            <p class="text-gray-700 text-sm md:text-base">{{ $reaction->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Empty State -->
        @if($announcements->isEmpty())
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                {{ $isFrench ? 'Aucune annonce' : 'No announcements' }}
            </h3>
            <p class="text-gray-500">
                {{ $isFrench ? 'Commencez par créer votre première annonce.' : 'Start by creating your first announcement.' }}
            </p>
        </div>
        @endif
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden transition-all duration-300 opacity-0 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 md:p-6 rounded-t-2xl">
            <h3 class="text-xl md:text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ $isFrench ? 'Confirmation de sécurité' : 'Security confirmation' }}
            </h3>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4 md:p-6">
            <p class="text-gray-600 mb-6 text-sm md:text-base">
                {{ $isFrench ? 'Veuillez saisir votre code PIN à 6 chiffres pour confirmer la publication.' : 'Please enter your 6-digit PIN code to confirm publication.' }}
            </p>
            
            <!-- PIN Input -->
            <div class="mb-6">
                <div class="flex justify-center mb-2">
                    <div class="pin-input-container flex gap-2 md:gap-3">
                        @for($i = 0; $i < 6; $i++)
                        <input type="text" 
                               class="pin-digit w-12 h-12 md:w-14 md:h-14 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all duration-200" 
                               maxlength="1" pattern="[0-9]" inputmode="numeric" />
                        @endfor
                    </div>
                </div>
                <input type="hidden" id="pin-complete" name="pin">
            </div>

            <!-- Modal Actions -->
            <div class="flex flex-col md:flex-row gap-3 md:justify-between">
                <button type="button" id="cancel-pin-btn" 
                        class="w-full md:w-auto px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-xl text-gray-700 font-semibold transition-all duration-200">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="button" id="submit-pin-btn" 
                        class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl text-white font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
                        disabled>
                    {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flagEnabled = {{ isset($flag) && $flag->flag == true ? 'true' : 'false' }};
    const form = document.getElementById('announcement-form');
    const submitBtn = document.getElementById('submit-announcement-btn');
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
    
    submitBtn.addEventListener('click', function() {
        if (flagEnabled) {
            form.submit();
            return;
        }
        
        if (form.checkValidity()) {
            pinModal.classList.remove('hidden');
            setTimeout(() => {
                pinModal.classList.remove('opacity-0');
                pinModal.querySelector('.scale-95').classList.add('scale-100');
                pinDigits[0].focus();
            }, 10);
        } else {
            form.reportValidity();
        }
    });
    
    cancelPinBtn.addEventListener('click', function() {
        closeModal();
    });
    
    submitPinBtn.addEventListener('click', function() {
        const pinInput = document.createElement('input');
        pinInput.type = 'hidden';
        pinInput.name = 'pin';
        pinInput.value = pinComplete.value;
        form.appendChild(pinInput);
        
        form.submit();
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
    
    pinModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    pinDigits[pinDigits.length - 1].addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !submitPinBtn.disabled) {
            submitPinBtn.click();
        }
    });
    
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !flagEnabled) {
            e.preventDefault();
            submitBtn.click();
        }
    });
});
</script>

<style>
    /* Mobile-optimized styles */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Enhanced touch targets */
        button, input, textarea {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Mobile typography */
        input, textarea {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
    
    /* Backdrop blur support */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    
    /* PIN input animations */
    .pin-digit:focus {
        transform: scale(1.05);
    }
    
    /* Modal animations */
    .scale-95 {
        transform: scale(0.95);
    }
    
    .scale-100 {
        transform: scale(1);
    }
    
    /* Focus states */
    input:focus, textarea:focus, button:focus {
        outline: none;
    }
</style>
@endsection
