@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-100 flex items-center justify-center p-4">
    <div class="max-w-4xl mx-auto text-center">
        
        <!-- Success/Error Notifications -->
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 3000)"
                 class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 3000)"
                 class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in">
                {{ session('error') }}
            </div>
        @endif

        <!-- Warning Icon Animation -->
        <div class="mb-8 animate-bounce">
            <svg class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-6 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 8V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="15" r="1" fill="currentColor"/>
            </svg>
        </div>

        <!-- Main Error Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8 animate-scale-in">
            <h1 class="text-3xl md:text-4xl font-bold text-red-600 mb-6">
                {{ $isFrench ? 'Erreur d\'Authentification' : 'Authentication Error' }}
            </h1>

            <div class="space-y-6">
                <p class="text-xl md:text-2xl text-gray-700 mb-4">
                    {{ $isFrench ? 'Votre code secret est erroné.' : 'Your secret code is incorrect.' }}
                </p>

                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-6">
                    <p class="text-lg text-red-700 mb-3">
                        {{ $isFrench ? 'Êtes-vous sûr d\'avoir :' : 'Are you sure you have:' }}
                    </p>
                    <ul class="text-left text-red-600 space-y-3 max-w-md mx-auto">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ $isFrench ? 'Sélectionné le bon poste ?' : 'Selected the right position?' }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ $isFrench ? 'Entré le bon code secret ?' : 'Entered the correct secret code?' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="bg-blue-50 rounded-2xl shadow-lg p-6 max-w-2xl mx-auto animate-fade-in-up">
            <h2 class="text-xl md:text-2xl font-semibold text-blue-800 mb-4 flex items-center justify-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ $isFrench ? 'Action Requise' : 'Action Required' }}
            </h2>
            <p class="text-blue-700 mb-6">
                {{ $isFrench 
                    ? 'Veuillez contacter l\'administration au plus tôt pour régler ce problème.' 
                    : 'Please contact the administration as soon as possible to resolve this issue.' 
                }}
            </p>
            <button class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ $isFrench ? 'Contacter l\'Administration' : 'Contact Administration' }}
            </button>
        </div>
    </div>
</div>

<!-- Mobile-First CSS Animations -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes bounce {
    0%, 100% { 
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% { 
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-bounce {
    animation: bounce 1s infinite;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .max-w-4xl {
        max-width: 100%;
    }
    
    .text-3xl, .text-4xl {
        font-size: 1.875rem;
    }
    
    .text-xl, .text-2xl {
        font-size: 1.25rem;
    }
    
    /* Touch-friendly buttons */
    button {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Enhanced hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Focus improvements */
button:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3);
}

/* Alpine.js compatibility */
[x-cloak] { display: none !important; }
</style>

<!-- Include Alpine.js for notifications -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
