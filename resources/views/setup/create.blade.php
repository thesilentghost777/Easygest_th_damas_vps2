@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Configuration initiale" : "Initial Setup" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div class="bg-blue-600 text-white p-4 lg:p-6">
                    <h1 class="text-xl lg:text-2xl font-bold">
                        {{ $isFrench ? "Configuration initiale de l'application" : "Application Initial Setup" }}
                    </h1>
                    <p class="mt-2 text-blue-100 text-sm lg:text-base">
                        {{ $isFrench ? "Veuillez configurer les informations de base pour commencer à utiliser l'application." : "Please configure the basic information to start using the application." }}
                    </p>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 border-l-4 border-red-500 m-4 lg:m-6 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Step Navigation -->
                <div class="border-b border-gray-200 bg-gray-50">
                    <ul class="flex w-full steps-nav" id="steps-nav">
                        <li class="flex-1 active-tab" data-tab="step1">
                            <button class="w-full py-3 lg:py-4 px-2 lg:px-4 text-center border-b-2 border-blue-600 text-blue-600 focus:outline-none text-sm lg:text-base font-medium transition-colors duration-200">
                                {{ $isFrench ? "Informations générales" : "General Information" }}
                            </button>
                        </li>
                        <li class="flex-1" data-tab="step2">
                            <button class="w-full py-3 lg:py-4 px-2 lg:px-4 text-center border-b-2 border-transparent text-gray-500 focus:outline-none text-sm lg:text-base font-medium transition-colors duration-200">
                                {{ $isFrench ? "Finances" : "Finances" }}
                            </button>
                        </li>
                        <li class="flex-1" data-tab="step3">
                            <button class="w-full py-3 lg:py-4 px-2 lg:px-4 text-center border-b-2 border-transparent text-gray-500 focus:outline-none text-sm lg:text-base font-medium transition-colors duration-200">
                                {{ $isFrench ? "Finalisation" : "Finalization" }}
                            </button>
                        </li>
                    </ul>
                </div>

                <form id="setup-form" action="{{ route('setup.store') }}" method="POST" class="p-4 lg:p-6">
                    @csrf

                    <!-- Step 1: General Information -->
                    <div id="step1" class="tab-content active-content space-y-6">
                        <div class="bg-gray-50 p-4 lg:p-6 rounded-xl border border-gray-100">
                            <h2 class="text-lg lg:text-xl font-semibold mb-4 flex items-center text-gray-900">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5h6m-6 2.5h6"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Informations du complexe" : "Complex Information" }}
                            </h2>

                            <div class="space-y-4 lg:space-y-6">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Nom du complexe" : "Complex Name" }}
                                    </label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                        class="w-full px-4 py-3 lg:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="{{ $isFrench ? 'Entrez le nom de votre complexe' : 'Enter your complex name' }}"
                                        required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Entrez le nom officiel de votre complexe" : "Enter the official name of your complex" }}
                                    </p>
                                </div>

                                <div>
                                    <label for="localisation" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Localisation" : "Location" }}
                                    </label>
                                    <input type="text" name="localisation" id="localisation" value="{{ old('localisation') }}"
                                        class="w-full px-4 py-3 lg:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="{{ $isFrench ? 'Adresse du complexe' : 'Complex address' }}"
                                        required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Adresse ou emplacement de votre complexe" : "Address or location of your complex" }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" class="next-btn w-full lg:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 active:scale-95 lg:active:scale-100 font-medium shadow-lg lg:shadow-sm">
                                {{ $isFrench ? "Continuer" : "Continue" }}
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Finances -->
                    <div id="step2" class="tab-content hidden space-y-6">
                        <div class="bg-gray-50 p-4 lg:p-6 rounded-xl border border-gray-100">
                            <h2 class="text-lg lg:text-xl font-semibold mb-4 flex items-center text-gray-900">
                                <div class="bg-green-100 rounded-full p-2 mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Finances du complexe" : "Complex Finances" }}
                            </h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label for="revenu_mensuel" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Revenu mensuel (FCFA)" : "Monthly Revenue (FCFA)" }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="revenu_mensuel" id="revenu_mensuel" value="{{ old('revenu_mensuel', 0) }}"
                                            class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 text-sm">FCFA</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Revenus mensuels estimés" : "Estimated monthly revenue" }}
                                    </p>
                                </div>

                                <div>
                                    <label for="revenu_annuel" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Revenu annuel (FCFA)" : "Annual Revenue (FCFA)" }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="revenu_annuel" id="revenu_annuel" value="{{ old('revenu_annuel', 0) }}"
                                            class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 text-sm">FCFA</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Revenus annuels estimés" : "Estimated annual revenue" }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row justify-between gap-3 lg:gap-0">
                            <button type="button" class="prev-btn w-full lg:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 active:scale-95 lg:active:scale-100 font-medium">
                                {{ $isFrench ? "Retour" : "Back" }}
                            </button>
                            <button type="button" class="next-btn w-full lg:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 active:scale-95 lg:active:scale-100 font-medium shadow-lg lg:shadow-sm">
                                {{ $isFrench ? "Continuer" : "Continue" }}
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Finalization -->
                    <div id="step3" class="tab-content hidden space-y-6">
                        <div class="bg-gray-50 p-4 lg:p-6 rounded-xl border border-gray-100">
                            <h2 class="text-lg lg:text-xl font-semibold mb-4 flex items-center text-gray-900">
                                <div class="bg-purple-100 rounded-full p-2 mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? "Finalisation de la configuration" : "Configuration Finalization" }}
                            </h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label for="solde" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Solde actuel (FCFA)" : "Current Balance (FCFA)" }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="solde" id="solde" value="{{ old('solde', 0) }}"
                                            class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 text-sm">FCFA</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Solde financier actuel du complexe" : "Current financial balance of the complex" }}
                                    </p>
                                </div>

                                <div>
                                    <label for="caisse_sociale" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? "Caisse sociale (FCFA)" : "Social Fund (FCFA)" }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="caisse_sociale" id="caisse_sociale" value="{{ old('caisse_sociale', 0) }}"
                                            class="w-full px-4 py-3 lg:py-2 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                            placeholder="0">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 text-sm">FCFA</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $isFrench ? "Montant alloué à la caisse sociale" : "Amount allocated to social fund" }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row justify-between gap-3 lg:gap-0">
                            <button type="button" class="prev-btn w-full lg:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 active:scale-95 lg:active:scale-100 font-medium">
                                {{ $isFrench ? "Retour" : "Back" }}
                            </button>
                            <button type="submit" class="w-full lg:w-auto px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 active:scale-95 lg:active:scale-100 font-medium shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? "Terminer la configuration" : "Complete Setup" }}
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Progress Indicator -->
                <div class="p-4 lg:p-6 border-t border-gray-200 bg-gray-50">
                    <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5 mb-2">
                        <div class="bg-blue-600 h-2 lg:h-2.5 rounded-full progress-bar transition-all duration-300" style="width: 33%"></div>
                    </div>
                    <p class="text-sm text-center text-gray-600">
                        {{ $isFrench ? "Étape" : "Step" }} <span class="step-number font-medium">1</span> {{ $isFrench ? "sur" : "of" }} 3
                    </p>
                </div>
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
    
    button:active {
        transform: scale(0.95);
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}

/* Step animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.active-tab button {
    border-bottom-width: 2px;
    border-color: #2563eb;
    color: #2563eb;
    font-weight: 600;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll('#steps-nav li');
    const contents = document.querySelectorAll('.tab-content');
    const nextButtons = document.querySelectorAll('.next-btn');
    const prevButtons = document.querySelectorAll('.prev-btn');
    const progressBar = document.querySelector('.progress-bar');
    const stepNumber = document.querySelector('.step-number');

    let currentStep = 1;
    const totalSteps = tabs.length;

    function changeTab(step) {
        // Update tabs
        tabs.forEach(tab => {
            tab.classList.remove('active-tab');
            tab.querySelector('button').classList.remove('border-blue-600', 'text-blue-600');
            tab.querySelector('button').classList.add('border-transparent', 'text-gray-500');
        });

        tabs[step-1].classList.add('active-tab');
        tabs[step-1].querySelector('button').classList.remove('border-transparent', 'text-gray-500');
        tabs[step-1].querySelector('button').classList.add('border-blue-600', 'text-blue-600');

        // Update content
        contents.forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('active-content');
        });

        // Show with animation
        const targetContent = document.getElementById('step' + step);
        targetContent.classList.remove('hidden');
        targetContent.classList.add('active-content', 'animate-fade-in');

        // Update progress bar
        const progress = (step / totalSteps) * 100;
        progressBar.style.width = progress + '%';
        stepNumber.textContent = step;

        currentStep = step;

        // Vibration feedback on mobile
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }

    // Tab click events
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const step = parseInt(this.getAttribute('data-tab').replace('step', ''));
            changeTab(step);
        });
    });

    // Next button events
    nextButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                let canProceed = true;

                // Validation for step 1
                if (currentStep === 1) {
                    const nom = document.getElementById('nom').value;
                    const localisation = document.getElementById('localisation').value;

                    if (!nom || !localisation) {
                        alert('{{ $isFrench ? "Veuillez remplir tous les champs obligatoires." : "Please fill all required fields." }}');
                        canProceed = false;
                    }
                }

                if (canProceed) {
                    changeTab(currentStep + 1);
                }
            }
        });
    });

    // Previous button events
    prevButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep > 1) {
                changeTab(currentStep - 1);
            }
        });
    });

    // Add input animations
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