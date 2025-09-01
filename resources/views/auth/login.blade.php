<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Container principal avec animation d'entrée -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-gradient-to-br from-blue-50 to-indigo-100 
                md:bg-gradient-to-br md:from-gray-50 md:to-gray-100">
        
        <!-- Card principal -->
        <div class="w-full max-w-md transform transition-all duration-700 ease-out animate-fadeInUp
                    bg-white rounded-3xl shadow-2xl overflow-hidden
                    md:max-w-lg md:rounded-xl md:shadow-lg">
            
            <!-- Header avec logo/titre -->
            <div class="relative px-8 pt-12 pb-8 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center
                        md:px-6 md:pt-8 md:pb-6 md:bg-blue-600">
                
                <!-- Icône boulangerie avec animation -->
                <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center
                           transform transition-transform duration-500 hover:scale-110 animate-bounce-slow
                           md:w-16 md:h-16 md:mb-3 md:animate-none">
                    <svg class="w-10 h-10 text-blue-600 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 6H3C2.45 6 2 6.45 2 7S2.45 8 3 8H21C21.55 8 22 7.55 22 7S21.55 6 21 6M20 10H4V19C4 20.1 4.9 21 6 21H18C19.1 21 20 20.1 20 19V10M12 12C13.1 12 14 12.9 14 14S13.1 16 12 16 10 15.1 10 14 10.9 12 12 12Z"/>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold mb-2 animate-slideInDown md:text-xl md:mb-1 md:animate-none">
                    {{ $isFrench ? 'Connexion' : 'Login' }}
                </h1>
                <p class="text-blue-100 text-sm animate-slideInDown md:animate-none">
                    {{ $isFrench ? 'Accédez à votre espace' : 'Access your account' }}
                </p>
                
                <!-- Décoration -->
                <div class="absolute top-0 right-0 w-32 h-32 opacity-10 transform rotate-12 translate-x-8 -translate-y-8
                           md:w-24 md:h-24">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2M21 9V7L15 1H5C3.9 1 3 1.9 3 3V21C3 22.1 3.9 23 5 23H19C20.1 23 21 22.1 21 21V9M19 9H14V4H19V9Z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Formulaire -->
            <div class="px-8 py-8 md:px-6 md:py-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-6 md:space-y-4">
                    @csrf
                    
                    <!-- Email avec animation -->
                    <div class="transform transition-all duration-300 hover:scale-105 md:hover:scale-100">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 
                                                  animate-slideInLeft md:animate-none">
                            {{ $isFrench ? 'Adresse email' : 'Email Address' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                                   autocomplete="username"
                                   class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl 
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-200 
                                          transition-all duration-300 text-gray-800 placeholder-gray-400
                                          hover:border-blue-300 hover:shadow-md
                                          md:py-3 md:rounded-lg md:focus:ring-2"
                                   placeholder="{{ $isFrench ? 'votre@email.com' : 'your@email.com' }}">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm animate-shake" />
                    </div>
                    
                    <!-- Password avec animation -->
                    <div class="transform transition-all duration-300 hover:scale-105 md:hover:scale-100">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2
                                                     animate-slideInRight md:animate-none">
                            {{ $isFrench ? 'Mot de passe' : 'Password' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required 
                                   autocomplete="current-password"
                                   class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl 
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-200 
                                          transition-all duration-300 text-gray-800 placeholder-gray-400
                                          hover:border-blue-300 hover:shadow-md
                                          md:py-3 md:rounded-lg md:focus:ring-2"
                                   placeholder="{{ $isFrench ? 'Votre mot de passe' : 'Your password' }}">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm animate-shake" />
                    </div>
                    
                    <!-- Remember Me avec style mobile -->
                    <div class="flex items-center justify-between animate-slideInUp md:animate-none">
                        <label for="remember_me" class="flex items-center group cursor-pointer">
                            <div class="relative">
                                <input id="remember_me" type="checkbox" name="remember" 
                                       class="sr-only peer">
                                <div class="w-6 h-6 bg-gray-200 rounded-lg border-2 border-gray-300 
                                           peer-checked:bg-blue-600 peer-checked:border-blue-600 
                                           transition-all duration-300 flex items-center justify-center
                                           group-hover:scale-110 md:group-hover:scale-100">
                                    <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 
                                               transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-blue-600 
                                         transition-colors duration-200">
                                {{ $isFrench ? 'Se souvenir de moi' : 'Remember me' }}
                            </span>
                        </label>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="space-y-4 pt-2">
                        <!-- Bouton de connexion principal -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold 
                                       py-4 px-6 rounded-2xl shadow-lg transform transition-all duration-300
                                       hover:from-blue-700 hover:to-blue-800 hover:scale-105 hover:shadow-xl
                                       active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-300
                                       md:py-3 md:rounded-lg md:hover:scale-100">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 animate-pulse md:animate-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                {{ $isFrench ? 'Se connecter' : 'Log in' }}
                            </span>
                        </button>
                        
                        <!-- Lien mot de passe oublié -->
                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" 
                                   class="inline-flex items-center text-sm font-medium text-blue-600 
                                          hover:text-blue-800 transition-all duration-200 
                                          hover:underline group">
                                    <svg class="w-4 h-4 mr-1 group-hover:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $isFrench ? 'Mot de passe oublié ?' : 'Forgot your password?' }}
                                </a>
                            </div>
                        @endif
                        
                        <!-- Bouton retour -->
                        <div class="text-center pt-4 border-t border-gray-100">
                            @include('buttons')
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Footer décoratif mobile -->
            <div class="px-8 pb-8 md:hidden">
                <div class="flex justify-center space-x-4 opacity-60">
                    <div class="w-3 h-3 bg-blue-300 rounded-full animate-pulse"></div>
                    <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounce-slow {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -10px, 0);
            }
            70% {
                transform: translate3d(0, -5px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.7s ease-out;
        }
        
        .animate-slideInDown {
            animation: slideInDown 0.6s ease-out;
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.5s ease-out;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.5s ease-out;
        }
        
        .animate-slideInUp {
            animation: slideInUp 0.6s ease-out;
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        /* Styles spécifiques mobile */
        @media (max-width: 768px) {
            .animate-slideInDown,
            .animate-slideInLeft,
            .animate-slideInRight,
            .animate-slideInUp {
                animation-delay: 0.2s;
            }
            
            /* Effet de tap sur mobile */
            .tap-highlight {
                -webkit-tap-highlight-color: rgba(59, 130, 246, 0.3);
            }
        }
        
        /* Optimisations desktop */
        @media (min-width: 769px) {
            .animate-fadeInUp,
            .animate-slideInDown,
            .animate-slideInLeft,
            .animate-slideInRight,
            .animate-slideInUp,
            .animate-bounce-slow {
                animation: none;
            }
        }
    </style>
</x-guest-layout>