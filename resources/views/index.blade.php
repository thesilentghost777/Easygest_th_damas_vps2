<!DOCTYPE html>
<html lang="fr">
<head>
  @PwaHead

  <base href="https://www.thmarket.sn">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Easy Gest</title>
  <meta name="description" content="TH MARKET - Solution de gestion complète pour votre boulangerie pâtisserie">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    @keyframes glow {
      0% { text-shadow: 0 0 5px rgba(255, 0, 0, 0.5); }
      50% { text-shadow: 0 0 15px rgba(255, 0, 0, 0.8); }
      100% { text-shadow: 0 0 5px rgba(255, 0, 0, 0.5); }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInFromRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes goldShimmer {
      0% { 
        background-position: -200% center;
        text-shadow: 
          0 0 5px rgba(255, 215, 0, 0.8),
          0 0 10px rgba(255, 215, 0, 0.6),
          0 0 15px rgba(255, 215, 0, 0.4);
      }
      50% {
        background-position: 200% center;
        text-shadow: 
          0 0 10px rgba(255, 215, 0, 1),
          0 0 20px rgba(255, 215, 0, 0.8),
          0 0 30px rgba(255, 215, 0, 0.6),
          0 0 40px rgba(255, 215, 0, 0.4);
      }
      100% { 
        background-position: -200% center;
        text-shadow: 
          0 0 5px rgba(255, 215, 0, 0.8),
          0 0 10px rgba(255, 215, 0, 0.6),
          0 0 15px rgba(255, 215, 0, 0.4);
      }
    }

    .animate-pulse {
      animation: pulse 2s infinite;
    }

    .animate-glow {
      animation: glow 2s ease-in-out infinite;
    }

    .animate-fadeInUp {
      animation: fadeInUp 1s ease forwards;
    }

    .animate-fadeInUp-delay {
      opacity: 0;
      animation: fadeInUp 1s ease 0.3s forwards;
    }

    .animate-slideInFromRight {
      animation: slideInFromRight 0.3s ease-out forwards;
    }

    .animate-gradientShift {
      animation: gradientShift 3s ease-in-out infinite;
    }

    .gold-text {
      background: linear-gradient(45deg, #ffd700, #ffed4e, #ffd700, #ffa500, #ffd700);
      background-size: 300% 300%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: goldShimmer 3s ease-in-out infinite;
      filter: drop-shadow(0 2px 4px rgba(255, 215, 0, 0.3));
    }

    .hero-bg-desktop {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 25%, #667eea 50%, #764ba2 75%, #667eea 100%);
      background-size: 400% 400%;
      animation: gradientShift 8s ease-in-out infinite;
    }

    .hero-bg-mobile {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      background-size: 400% 400%;
    }

    .hero-bg-mobile-alt {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      background-size: 400% 400%;
    }

    .hero-bg-mobile-bakery {
      background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 25%, #ff8a80 50%, #f48fb1 75%, #ce93d8 100%);
      background-size: 400% 400%;
    }

    .mobile-menu {
      transform: translateX(100%);
      transition: transform 0.3s ease-in-out;
    }

    .mobile-menu.active {
      transform: translateX(0);
    }

    .hamburger-line {
      transition: all 0.3s ease;
      transform-origin: center;
    }

    .hamburger.active .hamburger-line:nth-child(1) {
      transform: rotate(45deg) translateY(8px);
    }

    .hamburger.active .hamburger-line:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active .hamburger-line:nth-child(3) {
      transform: rotate(-45deg) translateY(-8px);
    }

    @media (max-width: 768px) {
      .animate-pulse,
      .animate-glow {
        animation: none;
      }
      
      .hero-bg-mobile {
        animation: gradientShift 4s ease-in-out infinite;
      }

      .mobile-card-shadow {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      }

      .mobile-btn {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .mobile-btn:active {
        transform: scale(0.95);
        transition: transform 0.1s ease;
      }
    }

    @media (min-width: 769px) {
      .animate-pulse {
        animation: pulse 2s infinite;
      }
      
      .animate-glow {
        animation: glow 2s ease-in-out infinite;
      }
    }

    .lang-switch {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .lang-switch:hover {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
      transform: translateY(-1px);
    }
    
    .lang-option {
      transition: all 0.3s ease;
    }
    
    .lang-option.active {
      color: #fbbf24;
      font-weight: 600;
    }
    
    .lang-option:not(.active) {
      opacity: 0.7;
    }
  </style>
</head>
<body class="font-[Poppins] text-gray-800">
  <!-- Header -->
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
  <header class="fixed top-0 w-full z-50 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 shadow-lg">
    <div class="px-4 py-3 flex justify-between items-center h-20 md:h-24">
      <div class="flex items-center gap-3 md:gap-4">
        <svg class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 fill-red-500 filter drop-shadow animate-pulse" viewBox="0 0 24 24">
          <path d="M12,3L4,9V21H20V9L12,3M12,5.5L18,10V19H6V10L12,5.5M12,7A3,3 0 0,0 9,10C9,11.31 9.83,12.42 11,12.83V17H13V12.83C14.17,12.42 15,11.31 15,10A3,3 0 0,0 12,7Z"/>
        </svg>
        <h1 class="text-xl md:text-2xl lg:text-4xl font-bold text-red-600 tracking-wide animate-glow">Complexe TH</h1>
      </div>

      <!-- Language Switch Button -->
      <div class="lang-switch px-4 py-2 rounded-full cursor-pointer transition-all duration-300" onclick="toggleLanguage()">
        <span class="lang-option active text-sm font-medium text-white" id="lang-fr">FR</span>
        <span class="text-white/50 mx-2">|</span>
        <span class="lang-option text-sm font-medium text-white" id="lang-en">EN</span>
      </div>
      
      <!-- Desktop Navigation -->
      <nav class="hidden md:block">
        <ul class="flex items-center gap-6 lg:gap-8">
          <li><a href="{{ route('login') }}" 
                 data-fr="Connexion" 
                 data-en="Login"
                 class="text-base lg:text-lg font-medium text-white px-6 py-3 rounded-full bg-white bg-opacity-10 backdrop-filter backdrop-blur-sm border border-white border-opacity-20 transition duration-300 hover:bg-red-500 hover:text-white hover:shadow-lg hover:-translate-y-1">Connexion</a></li>
          <li><a href="{{ route('register') }}" 
                 data-fr="Enregistrement" 
                 data-en="Register"
                 class="text-base lg:text-lg font-medium text-white px-6 py-3 rounded-full bg-red-500 bg-opacity-90 transition duration-300 hover:bg-red-600 hover:shadow-lg hover:-translate-y-1">Enregistrement</a></li>
        </ul>
      </nav>

      <!-- Mobile Hamburger -->
      <button class="hamburger md:hidden cursor-pointer z-50 p-2" id="mobileMenuToggle">
        <div class="hamburger-line w-6 h-0.5 bg-white mb-1.5"></div>
        <div class="hamburger-line w-6 h-0.5 bg-white mb-1.5"></div>
        <div class="hamburger-line w-6 h-0.5 bg-white"></div>
      </button>
    </div>
  </header>

  <!-- Mobile Menu Overlay -->
  <div class="mobile-menu fixed inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 z-40 md:hidden" id="mobileMenu">
    <div class="flex flex-col justify-center items-center h-full space-y-8">
      <div class="text-center mb-8">
        <svg class="w-16 h-16 fill-red-500 mx-auto mb-4" viewBox="0 0 24 24">
          <path d="M12,3L4,9V21H20V9L12,3M12,5.5L18,10V19H6V10L12,5.5M12,7A3,3 0 0,0 9,10C9,11.31 9.83,12.42 11,12.83V17H13V12.83C14.17,12.42 15,11.31 15,10A3,3 0 0,0 12,7Z"/>
        </svg>
        <h2 class="text-3xl font-bold text-red-500">Complexe TH</h2>
      </div>
      
      <div class="space-y-6 w-full px-8">
        <a href="{{ route('login') }}" 
           data-fr="Connexion" 
           data-en="Login"
           class="mobile-btn mobile-card-shadow block text-xl font-medium text-white text-center px-8 py-4 rounded-2xl transition duration-300 transform hover:scale-105">
          <svg class="w-6 h-6 inline mr-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
          </svg>
          Connexion
        </a>
        
        <a href="{{ route('register') }}" 
           data-fr="Enregistrement" 
           data-en="Register"
           class="mobile-btn mobile-card-shadow block text-xl font-medium text-white text-center px-8 py-4 rounded-2xl bg-red-500 bg-opacity-80 transition duration-300 transform hover:scale-105">
          <svg class="w-6 h-6 inline mr-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/>
          </svg>
          Enregistrement
        </a>
      </div>

      <div class="absolute bottom-8 text-center">
        <p class="text-gray-400 text-sm" 
           data-fr="Appuyez n'importe où pour fermer" 
           data-en="Tap anywhere to close">Appuyez n'importe où pour fermer</p>
      </div>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="hero-bg-desktop hero-bg-mobile min-h-[85vh] md:min-h-[75vh] flex flex-col justify-center items-center text-center p-4 pt-24 md:pt-28 relative overflow-hidden">
    <!-- Desktop sophisticated overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/20 hidden md:block"></div>
    
    <!-- Mobile gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/10 to-black/30 md:hidden"></div>
    
    <div class="rounded-2xl md:rounded-3xl bg-black/10 md:bg-white/10 backdrop-blur-lg p-6 md:p-12 z-10 max-w-5xl mx-4 mobile-card-shadow border border-white/20">
      <h2 class="text-3xl md:text-5xl lg:text-7xl mb-8 animate-fadeInUp font-bold tracking-wider">
        <span class="block md:inline text-white md:text-white">Easy</span> 
        <span class="block md:inline text-yellow-300 md:gold-text">Gest</span>
      </h2>
      <p class="text-lg md:text-2xl lg:text-3xl text-white font-medium animate-fadeInUp-delay leading-relaxed max-w-4xl mx-auto px-2 md:text-white/90"
         data-fr="Simplifiez la gestion de votre structure avec notre solution complète. Tout au même endroit."
         data-en="Simplify the management of your business with our complete solution. Everything in one place.">
        Simplifiez la gestion de votre structure avec notre solution complète. Tout au même endroit.
      </p>
      
      <!-- Desktop CTA -->
      <div class="mt-10 hidden md:block">
        <div class="flex gap-6 justify-center">
          <a href="{{ route('login') }}" 
             data-fr="Se connecter" 
             data-en="Login"
             class="px-8 py-4 bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold text-lg rounded-2xl transition duration-300 transform hover:scale-105 hover:bg-white/30 hover:shadow-2xl">
            Se connecter
          </a>
          <a href="{{ route('register') }}" 
             data-fr="Commencer" 
             data-en="Get Started"
             class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-600 text-gray-900 font-bold text-lg rounded-2xl transition duration-300 transform hover:scale-105 hover:shadow-2xl hover:from-yellow-300 hover:to-yellow-500">
            Commencer
          </a>
        </div>
      </div>
      
      <!-- Mobile CTA -->
      <div class="mt-8 md:hidden">
        <button class="mobile-btn mobile-card-shadow px-8 py-4 rounded-2xl text-white font-semibold text-lg transition duration-300 transform hover:scale-105" 
                data-fr="Commencer maintenant" 
                data-en="Start Now"
                onclick="document.getElementById('mobileMenu').classList.add('active')">
          Commencer maintenant
          <svg class="w-5 h-5 inline ml-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/>
          </svg>
        </button>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 p-6 md:p-12 lg:p-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="feature-card p-6 md:p-10 rounded-2xl md:rounded-3xl bg-white shadow-lg md:shadow-xl mobile-card-shadow transition duration-500 text-center relative overflow-hidden hover:-translate-y-3 hover:shadow-2xl group">
      <div class="feature-top-border absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-yellow-400 transform scale-x-0 origin-left transition-transform duration-500"></div>
      <div class="bg-gradient-to-br from-red-50 to-red-100 w-24 h-24 md:w-20 md:h-20 rounded-3xl flex items-center justify-center mx-auto mb-8 md:mb-6 group-hover:scale-110 transition-transform duration-300">
        <svg class="w-12 h-12 md:w-10 md:h-10 fill-red-500 transition duration-300 group-hover:fill-red-600" viewBox="0 0 24 24">
          <path d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z"/>
        </svg>
      </div>
      <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-800 group-hover:text-red-600 transition-colors duration-300"
          data-fr="Gestion des Employés"
          data-en="Employee Management">Gestion des Employés</h3>
      <p class="text-base md:text-lg text-gray-600 leading-relaxed"
         data-fr="Gérez efficacement vos équipes et leurs plannings"
         data-en="Efficiently manage your teams and their schedules">Gérez efficacement vos équipes et leurs plannings</p>
    </div>

    <div class="feature-card p-6 md:p-10 rounded-2xl md:rounded-3xl bg-white shadow-lg md:shadow-xl mobile-card-shadow transition duration-500 text-center relative overflow-hidden hover:-translate-y-3 hover:shadow-2xl group">
      <div class="feature-top-border absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-cyan-400 transform scale-x-0 origin-left transition-transform duration-500"></div>
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 w-24 h-24 md:w-20 md:h-20 rounded-3xl flex items-center justify-center mx-auto mb-8 md:mb-6 group-hover:scale-110 transition-transform duration-300">
        <svg class="w-12 h-12 md:w-10 md:h-10 fill-blue-500 transition duration-300 group-hover:fill-blue-600" viewBox="0 0 24 24">
          <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V19H5V5H19Z"/>
        </svg>
      </div>
      <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-800 group-hover:text-blue-600 transition-colors duration-300"
          data-fr="Gestion des Stocks"
          data-en="Inventory Management">Gestion des Stocks</h3>
      <p class="text-base md:text-lg text-gray-600 leading-relaxed"
         data-fr="Contrôlez vos inventaires et anticipez vos besoins en matières premières"
         data-en="Control your inventory and anticipate your raw material needs">Contrôlez vos inventaires et anticipez vos besoins en matières premières</p>
    </div>

    <div class="feature-card p-6 md:p-10 rounded-2xl md:rounded-3xl bg-white shadow-lg md:shadow-xl mobile-card-shadow transition duration-500 text-center relative overflow-hidden hover:-translate-y-3 hover:shadow-2xl group md:col-span-2 lg:col-span-1">
      <div class="feature-top-border absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 to-emerald-400 transform scale-x-0 origin-left transition-transform duration-500"></div>
      <div class="bg-gradient-to-br from-green-50 to-green-100 w-24 h-24 md:w-20 md:h-20 rounded-3xl flex items-center justify-center mx-auto mb-8 md:mb-6 group-hover:scale-110 transition-transform duration-300">
        <svg class="w-12 h-12 md:w-10 md:h-10 fill-green-500 transition duration-300 group-hover:fill-green-600" viewBox="0 0 24 24">
          <path d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16V8H21V16"/>
        </svg>
      </div>
      <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-800 group-hover:text-green-600 transition-colors duration-300"
          data-fr="Mini Comptabilité"
          data-en="Mini Accounting">Mini Comptabilité</h3>
      <p class="text-base md:text-lg text-gray-600 leading-relaxed"
         data-fr="Suivez vos revenus et dépenses simplement et efficacement"
         data-en="Track your income and expenses simply and efficiently">Suivez vos revenus et dépenses simplement et efficacement</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white text-center p-6 md:p-8 relative">
    <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.05)_25%,rgba(255,255,255,0.05)_50%,transparent_50%)] bg-[length:10px_10px]"></div>
    <p class="relative z-10 text-sm md:text-base"
       data-fr="© 2025 Easy Gest - Powered by TFS237"
       data-en="© 2025 Easy Gest - Powered by TFS237">© 2025 Easy Gest - Tous droits réservés</p>
  </footer>

  <script>
    let currentLanguage = 'fr';

    function toggleLanguage() {
        currentLanguage = currentLanguage === 'fr' ? 'en' : 'fr';
        
        // Update language switch button
        const frOption = document.getElementById('lang-fr');
        const enOption = document.getElementById('lang-en');
        
        if (currentLanguage === 'fr') {
            frOption.classList.add('active');
            enOption.classList.remove('active');
            document.documentElement.lang = 'fr';
        } else {
            frOption.classList.remove('active');
            enOption.classList.add('active');
            document.documentElement.lang = 'en';
        }
        
        // Update all translatable elements
        const translatableElements = document.querySelectorAll('[data-fr][data-en]');
        translatableElements.forEach(element => {
            const translation = element.getAttribute(`data-${currentLanguage}`);
            if (translation) {
                // Check if element has SVG icon
                const svgIcon = element.querySelector('svg');
                if (svgIcon) {
                    // Preserve icon and update text
                    const iconHTML = svgIcon.outerHTML;
                    const textSpan = element.querySelector('span') || element.childNodes[element.childNodes.length - 1];
                    
                    if (element.textContent.includes('maintenant') || element.textContent.includes('Now')) {
                        // For buttons with text followed by icon
                        element.innerHTML = translation + iconHTML;
                    } else {
                        // For buttons with icon followed by text
                        element.innerHTML = iconHTML + translation;
                    }
                } else {
                    // No icon, just update text
                    element.textContent = translation;
                }
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    mobileMenuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking outside or on links
    mobileMenu.addEventListener('click', function(e) {
      if (e.target === this || e.target.tagName === 'A') {
        this.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
      }
    });

    // Prevent body scroll when mobile menu is open
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.target.classList.contains('active')) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = '';
        }
      });
    });
    
    observer.observe(mobileMenu, { attributes: true, attributeFilter: ['class'] });

    // Feature cards animation
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const cardObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          entry.target.style.transitionDelay = `${index * 0.1}s`;

          // Animate the top border
          const topBorder = entry.target.querySelector('.feature-top-border');
          if (topBorder) {
            setTimeout(() => {
              topBorder.classList.add('scale-x-100');
            }, index * 100);
          }
        }
      });
    }, observerOptions);

    document.querySelectorAll('.feature-card').forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(30px)';
      card.style.transition = 'all 0.6s ease-out';
      cardObserver.observe(card);
    });

    // Handle orientation change
    window.addEventListener('orientationchange', function() {
      setTimeout(function() {
        window.scrollTo(0, 1);
      }, 500);
    });

    // Service Worker registration
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
  </script>
  
  @RegisterServiceWorkerScript
</body>
</html>
