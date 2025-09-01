<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @PwaHead

    <meta charset="utf-8">
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#005B96">

    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Raleway:wght@800&family=Urbanist:wght@800&family=Lexend:wght@800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

        /* Splash Screen Styles - Hostinger Style */
#splash-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #000080 0%, #c17575 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 1;
    transition: opacity 0.8s ease-out;
}

#splash-screen.hide {
    opacity: 0;
    pointer-events: none;
}

.splash-logo-container {
    position: relative;
    width: 25vw; /* 1/4 de la largeur de l'écran */
    height: 25vw;
    max-width: 300px;
    max-height: 300px;
    min-width: 120px;
    min-height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.splash-logo {
    font-family: 'Poppins', sans-serif;

    width: 60%;
    height: 60%;
    background: linear-gradient(135deg, #000080 0%, #e87979 100%);
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: calc(5vw + 20px);
    font-weight: bold;
    color: rgb(247, 247, 247);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    z-index: 2;
    position: relative;
}

/* Animation de cercle tournant comme Hostinger */
.hostinger-ring {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 4px solid transparent;
    border-radius: 50%;
    animation: hostinger-spin 2s linear infinite;
}

.hostinger-ring:before {
    content: '';
    position: absolute;
    top: -4px;
    left: -4px;
    width: 100%;
    height: 100%;
    border: 4px solid transparent;
    border-top: 4px solid rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    animation: hostinger-spin 2s linear infinite;
}

.hostinger-ring:after {
    content: '';
    position: absolute;
    top: -8px;
    left: -8px;
    width: calc(100% + 16px);
    height: calc(100% + 16px);
    border: 2px solid transparent;
    border-top: 2px solid rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    animation: hostinger-spin 3s linear infinite reverse;
}

@keyframes hostinger-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Effet de pulsation sur le logo */
.splash-logo {
    animation: logo-pulse 3s ease-in-out infinite;
}

@keyframes logo-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.splash-text {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-family: 'Urbanist', sans-serif;
    font-size: calc(1vw + 16px);
    font-weight: 600;
    opacity: 0.9;
    letter-spacing: 1px;
}

/* Ajustements mobiles */
@media (max-width: 768px) {
    .splash-logo-container {
        width: 40vw;
        height: 40vw;
        max-width: 200px;
        max-height: 200px;
    }
    
    .splash-logo {
        font-size: calc(8vw + 10px);
        border-radius: 15px;
    }
    
    .splash-text {
        font-size: 18px;
        bottom: 25%;
    }
}

/* Effet de particules flottantes (optionnel) */
.splash-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
    50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
}
    /* Styles pour assurer que les icônes MDI sont visibles */
    .mdi {
        display: inline-block;
        font: normal normal normal 24px/1 "Material Design Icons";
        font-size: inherit;
        text-rendering: auto;
        line-height: inherit;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
     .alert-danger {
        border-color: #dc3545;
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .alert-danger .fas {
        color: #dc3545;
    }
    
    .alert-dismissible .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        padding: 0.75rem 1.25rem;
    }
    
    /* Mobile Bottom Navigation */
    .mobile-nav-hidden {
        transform: translateY(100%);
    }
    
    .mobile-nav-visible {
        transform: translateY(0);
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .animate-slide-up {
        animation: slideInUp 0.4s ease-out;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    /* Smooth transitions for mobile */
    .mobile-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .mobile-content {
        padding-bottom: 100px; /* Space for bottom nav */
    }
    
    /* Floating button */
    .floating-toggle {
        position: fixed;
        bottom: 120px;
        right: 20px;
        z-index: 1000;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transform: scale(0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .floating-toggle.show {
        transform: scale(1);
    }
    
    .floating-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
    }
    .mobile-transition {
            transition: all 0.3s ease-in-out;
        }
        
        .mobile-nav-visible {
            transform: translateY(0);
            opacity: 1;
        }
        
        .mobile-nav-hidden {
            transform: translateY(100%);
            opacity: 0;
        }
    
    @media (min-width: 768px) {
        .mobile-content {
            padding-bottom: 0;
        }
        
        .floating-toggle {
            display: none;
        }
    }

    </style>
</head>
<body class="font-sans antialiased" x-data="{ 
    mobileNavVisible: true, 
    showFloatingButton: false,
    toggleMobileNav() {
        this.mobileNavVisible = !this.mobileNavVisible;
        this.showFloatingButton = !this.mobileNavVisible;
    }
}">
@if(session('error') && session('error_type') === 'unexpected_error')
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="unexpected-error-alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>{{ session('error') }}</strong>
                <br>
                <small class="text-muted">
                    {{ Auth::user()->language === 'en' ? 'Error ID: ' : 'ID d\'erreur: ' }}{{ session()->getId() }}
                </small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        // Auto-hide après 10 secondes
        setTimeout(function() {
            const alert = document.getElementById('unexpected-error-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 10000);
    </script>
@endif
    <div class="min-h-screen bg-gray-100">

        <!-- Splash Screen - Hostinger Style -->
<div id="splash-screen">
    <div class="splash-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 0.5s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 2.5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 3.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 4s;"></div>
    </div>
    
    <div class="splash-logo-container">
        <div class="hostinger-ring"></div>
        <div class="splash-logo">
            EG
        </div>
    </div>
    
    <div class="splash-text">
{{ $isFrench ? 'chargement...' : 'loading...' }}    </div>
</div>
        <!-- Desktop Navigation -->
        <nav x-data="{ open: false }" class="hidden md:block bg-gray-50 border-b border-gray-200 shadow-sm">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
                <div class="flex justify-between h-16 items-center">
                   <img src="{{ asset('assets/logos/TH_LOGO.png') }}" alt="TH Logo" class="h-8 w-auto mr-3">
               
                    <!-- Centered navigation links -->
                    <div class="flex-1 flex justify-center">
                        <div class="flex space-x-6">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600
                                {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                {{ $isFrench ? 'Accueil' : 'Home' }}
                            </x-nav-link>
                            <x-nav-link :href="route('workspace.redirect')" :active="request()->routeIs('workspace.redirect')"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600
                                {{ request()->routeIs('workspace.redirect') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                {{ $isFrench ? 'Espace de travail' : 'Workspace' }}
                            </x-nav-link>
                            <x-nav-link :href="route('about')" :active="request()->routeIs('about')"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600
                                {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                {{ $isFrench ? 'Mentions légales' : 'Legal Disclaimer' }}
                            </x-nav-link>
                            <x-nav-link :href="route('account-access.return')" :active="request()->routeIs('account-access.return')"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600
                                {{ request()->routeIs('account-access.return') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                {{ $isFrench ? 'Retour au compte original' : 'Return to original account' }}
                            </x-nav-link>
                            <x-nav-link :href="route('workspace.switcher')" :active="request()->routeIs('workspace.switcher')"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600
                                {{ request()->routeIs('workspace.switcher') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                {{ $isFrench ? 'Changement de mode' : 'Mode switch' }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Right side with settings -->
                    <div class="w-1/4 flex justify-end">
                        <div class="flex items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-100 hover:text-gray-800 transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-2">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ $isFrench ? 'Profil' : 'Profile' }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('language.index')">
                                        {{ $isFrench ? 'Changer de langue' : 'Change Language' }}
                                        <span class="ml-2 text-xs text-gray-500">
                                            {{ $isFrench ? '🇫🇷 FR' : '🇺🇸 EN' }}
                                        </span>
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ $isFrench ? 'Déconnexion' : 'Log Out' }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Header -->
        <div class="md:hidden bg-gray-300/20 px-4 py-3 flex items-center justify-between shadow-lg">            <div class="flex items-center">
                <img src="{{ asset('assets/logos/TH_LOGO.png') }}" alt="TH Logo" class="h-8 w-auto mr-3">
                <div>
                    <h1 class="text-red-500 text-lg font-bold">TH MARKET</h1>
                    <p class="text-blue-600 text-xs">{{ Auth::user()->name }}</p>
                </div>
            </div>
            <x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button class="flex items-center p-2 rounded-full bg-blue-200 text-blue-900">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </x-slot>
    <x-slot name="content">
        <!-- Option de retour à la page précédente -->
        <x-dropdown-link href="javascript:history.back()">
            <div class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ $isFrench ? 'Retour' : 'Back' }}
            </div>
        </x-dropdown-link>
        
        <!-- Option d'actualisation -->
        <x-dropdown-link href="javascript:location.reload()">
            <div class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ $isFrench ? 'Actualiser' : 'Refresh' }}
            </div>
        </x-dropdown-link>
        
        <!-- Séparateur -->
        <div class="border-t border-gray-100"></div>
        
        <x-dropdown-link :href="route('profile.edit')">
            {{ $isFrench ? 'Profil' : 'Profile' }}
        </x-dropdown-link>
        <x-dropdown-link :href="route('language.index')">
            {{ $isFrench ? 'Changer de langue' : 'Change Language' }}
            <span class="ml-2 text-xs text-gray-500">
                {{ $isFrench ? '🇫🇷 FR' : '🇺🇸 EN' }}
            </span>
        </x-dropdown-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ $isFrench ? 'Déconnexion' : 'Log Out' }}
            </x-dropdown-link>
        </form>
    </x-slot>
</x-dropdown>
        </div>

        <!-- Header -->
        @if (isset($header))
            <header class="hidden md:block bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="mobile-content">
            @if(session('success'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>

        <!-- Menu mobile modifié -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40" x-data="{ 
        mobileNavVisible: true,
        isFrench: true 
    }" x-init="
        // Masquer automatiquement le menu après 2 secondes
        setTimeout(() => {
            mobileNavVisible = false;
        }, 2000);
        
        // Optionnel: Réafficher le menu au survol ou clic sur la zone
        $el.addEventListener('mouseenter', () => {
            mobileNavVisible = true;
        });
        
        // Masquer à nouveau après 2 secondes si réaffiché
        $watch('mobileNavVisible', (value) => {
            if (value) {
                setTimeout(() => {
                    mobileNavVisible = false;
                }, 2000);
            }
        });
    ">
        <div class="mobile-transition" :class="mobileNavVisible ? 'mobile-nav-visible' : 'mobile-nav-hidden'">
            <div class="bg-gray-100 border-t border-gray-200 shadow-2xl rounded-t-2xl">
                <!-- Padding réduit pour un menu plus compact -->
                <div class="px-1 py-1">
                    <!-- Navigation compacte -->
                    <div class="flex justify-around items-center">
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-2 rounded-lg bg-blue-50 text-blue-600 mobile-transition hover:bg-gray-50">
                            <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                            </svg>
                            <span class="text-xs font-medium" x-text="isFrench ? 'Accueil' : 'Home'"></span>
                        </a>
                        
                        <a href="{{ route('workspace.redirect') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-gray-600 mobile-transition hover:bg-gray-50">
                            <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-medium" x-text="isFrench ? 'Travail' : 'Work'"></span>
                        </a>
                        <a href="{{ route('account-access.return') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-gray-600 mobile-transition hover:bg-gray-50">
                             <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="text-xs font-medium" x-text="isFrench ? 'compte original' : 'original account'"></span>
                        </a>
                        <a href="{{ route('workspace.switcher') }}" class="flex flex-col items-center py-1 px-2 rounded-lg text-gray-600 mobile-transition hover:bg-gray-50">
                            <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="text-xs font-medium" x-text="isFrench ? 'Mode' : 'Mode'"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Floating Toggle Button -->
        <button @click="toggleMobileNav()" 
                class="md:hidden floating-toggle" 
                :class="showFloatingButton ? 'show' : ''">
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>
    @stack('scripts')
    @RegisterServiceWorkerScript
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service Worker enregistré avec succès:', registration);
                    })
                    .catch(error => {
                        console.error('Erreur d\'enregistrement du Service Worker:', error);
                    });
            });
        }

document.addEventListener('DOMContentLoaded', () => {
    // Affiche instantanément
    document.getElementById('splash-screen').classList.remove('hide');
    
    // Force la fermeture après 3 secondes maximum
    setTimeout(() => {
        document.getElementById('splash-screen').classList.add('hide');
    }, 3000);
});

window.addEventListener('load', () => {
    // Masque après chargement complet (si moins de 3s)
    setTimeout(() => document.getElementById('splash-screen').classList.add('hide'), 100);
});

// Ré-expose le splash sur navigation interne
document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', e => {
        const url = link.getAttribute('href');
        if (!url.startsWith('#') && !link.hasAttribute('target')) {
            document.getElementById('splash-screen').classList.remove('hide');
            
            // Force la fermeture après 3 secondes maximum pour la navigation
            setTimeout(() => {
                document.getElementById('splash-screen').classList.add('hide');
            }, 3000);
        }
    });
});
    </script>
</body>
</html>
