@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="text-center mb-8">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ $currentLanguage === 'fr' ? 'Choisir la langue' : 'Choose Language' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $currentLanguage === 'fr' ? 'Sélectionnez votre langue préférée' : 'Select your preferred language' }}
                    </p>
                </div>

                <form action="{{ route('language.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-3">
                        <!-- Option Français -->
                        <label class="language-option flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-50 {{ $currentLanguage === 'fr' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}" data-language="fr">
                            <input type="radio" name="language" value="fr" 
                                   class="language-radio" 
                                   {{ $currentLanguage === 'fr' ? 'checked' : '' }}>
                            <div class="flex items-center space-x-3 w-full">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        🇫🇷
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">Français</h3>
                                    <p class="text-sm text-gray-600">Langue française</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="radio-indicator w-5 h-5 rounded-full border-2 {{ $currentLanguage === 'fr' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }} flex items-center justify-center">
                                        <div class="radio-dot w-2 h-2 rounded-full bg-white {{ $currentLanguage === 'fr' ? 'block' : 'hidden' }}"></div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option English -->
                        <label class="language-option flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-50 {{ $currentLanguage === 'en' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}" data-language="en">
                            <input type="radio" name="language" value="en" 
                                   class="language-radio" 
                                   {{ $currentLanguage === 'en' ? 'checked' : '' }}>
                            <div class="flex items-center space-x-3 w-full">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        🇺🇸
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">English</h3>
                                    <p class="text-sm text-gray-600">English language</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="radio-indicator w-5 h-5 rounded-full border-2 {{ $currentLanguage === 'en' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }} flex items-center justify-center">
                                        <div class="radio-dot w-2 h-2 rounded-full bg-white {{ $currentLanguage === 'en' ? 'block' : 'hidden' }}"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        {{ $currentLanguage === 'fr' ? 'Valider' : 'Confirm' }}
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('workspace.redirect') }}" 
                       class="text-sm text-gray-500 hover:text-gray-700">
                        {{ $currentLanguage === 'fr' ? 'Retour au tableau de bord' : 'Back to dashboard' }}
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    {{ $currentLanguage === 'fr' ? 'Connecté en tant que' : 'Connected as' }} 
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer tous les éléments nécessaires
            const languageOptions = document.querySelectorAll('.language-option');
            const languageRadios = document.querySelectorAll('.language-radio');
            
            // Fonction pour mettre à jour l'apparence des options
            function updateLanguageSelection(selectedValue) {
                languageOptions.forEach(option => {
                    const radio = option.querySelector('.language-radio');
                    const indicator = option.querySelector('.radio-indicator');
                    const dot = option.querySelector('.radio-dot');
                    
                    if (radio.value === selectedValue) {
                        // Option sélectionnée
                        option.classList.remove('border-gray-200');
                        option.classList.add('border-blue-500', 'bg-blue-50');
                        indicator.classList.remove('border-gray-300');
                        indicator.classList.add('border-blue-500', 'bg-blue-500');
                        dot.classList.remove('hidden');
                        dot.classList.add('block');
                        radio.checked = true;
                    } else {
                        // Option non sélectionnée
                        option.classList.remove('border-blue-500', 'bg-blue-50');
                        option.classList.add('border-gray-200');
                        indicator.classList.remove('border-blue-500', 'bg-blue-500');
                        indicator.classList.add('border-gray-300');
                        dot.classList.remove('block');
                        dot.classList.add('hidden');
                        radio.checked = false;
                    }
                });
            }
            
            // Gérer les clics sur les labels
            languageOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    // Empêcher le double déclenchement
                    if (e.target.type === 'radio') return;
                    
                    const radio = this.querySelector('.language-radio');
                    const selectedValue = radio.value;
                    
                    updateLanguageSelection(selectedValue);
                });
            });
            
            // Gérer les changements directs sur les radio buttons
            languageRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        updateLanguageSelection(this.value);
                    }
                });
            });
        });
    </script>

    <style>
        /* Cacher les radio buttons par défaut mais les garder accessibles */
        .language-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        /* Assurer que les labels sont cliquables */
        .language-option {
            user-select: none;
        }
        
        /* Animation pour les transitions */
        .radio-indicator {
            transition: all 0.2s ease-in-out;
        }
        
        .radio-dot {
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection