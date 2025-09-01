@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Portail de Gestion' : 'Management Portal' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #005B96;
            min-height: 100vh;
            color: #333;
        }

        header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            padding: 0 2rem;
        }

        .logo-title h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .page-title {
            text-align: center;
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .module-link {
            display: flex;
            align-items: center;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .module-icon {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            transition: all 0.3s ease;
        }

        .module-producteur .module-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .module-vendeur .module-icon {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }

        .module-employe .module-icon {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .module-info {
            flex-grow: 1;
        }

        .module-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color: #2d3748;
        }

        .module-description {
            color: #718096;
            font-size: 1rem;
            margin: 0;
            line-height: 1.5;
        }

        .module-arrow {
            flex-shrink: 0;
            color: #a0aec0;
            transition: all 0.3s ease;
        }

        .module-card:hover .module-arrow {
            color: #667eea;
            transform: translateX(5px);
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            }

            .header-content {
                padding: 0 1rem;
            }

            .logo-title h1 {
                font-size: 2rem;
                animation: fadeInDown 0.8s ease-out;
            }

            .main-content {
                padding: 2rem 1rem;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
                animation: fadeInUp 0.6s ease-out 0.2s both;
            }

            .module-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                max-width: 100%;
            }

            .module-card {
                border-radius: 25px;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(15px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                transform: translateY(20px);
                animation: slideInUp 0.6s ease-out forwards;
            }

            .module-card:nth-child(1) {
                animation-delay: 0.3s;
            }

            .module-card:nth-child(2) {
                animation-delay: 0.5s;
            }

            .module-card:nth-child(3) {
                animation-delay: 0.7s;
            }

            .module-card:hover, .module-card:active {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }

            .module-link {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
            }

            .module-icon {
                width: 80px;
                height: 80px;
                margin-right: 0;
                margin-bottom: 1rem;
                border-radius: 20px;
                animation: bounceIn 0.8s ease-out;
            }

            .module-title {
                font-size: 1.3rem;
                margin-bottom: 0.75rem;
                color: #1a202c;
            }

            .module-description {
                font-size: 0.95rem;
                color: #4a5568;
                margin-bottom: 1rem;
            }

            .module-arrow {
                margin-top: 0.5rem;
                transform: rotate(90deg);
            }

            .module-card:hover .module-arrow {
                transform: rotate(90deg) translateX(5px);
                color: #667eea;
            }

            footer {
                padding: 1.5rem 1rem;
                font-size: 0.8rem;
            }

            /* Touch feedback */
            * {
                -webkit-tap-highlight-color: transparent;
            }

            .module-card:active {
                transform: translateY(-3px) scale(0.98);
                transition: transform 0.1s ease;
            }

            /* Animations */
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

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

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes bounceIn {
                0% {
                    opacity: 0;
                    transform: scale(0.3);
                }
                50% {
                    opacity: 1;
                    transform: scale(1.1);
                }
                70% {
                    transform: scale(0.9);
                }
                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-title">
                <h1 style="color:#DAA520;">
                    {{ $isFrench ? 'Portail de Gestion' : 'Management Portal' }}
                </h1>
                            </div>
        </div>
    </header>

    <main class="main-content">
        <h2 class="page-title" style="color:#DAA520;">
            {{ $isFrench ? 'Sélection de module' : 'Module Selection' }}
        </h2>
                
        <div class="module-grid">
            <div class="module-card module-producteur">
                <a href="{{ route('producteur.workspace') }}" class="module-link">
                    <div class="module-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                        </svg>
                    </div>
                    <div class="module-info">
                        <h3 class="module-title">{{ $isFrench ? 'Producteur' : 'Producer' }}</h3>
                        <p class="module-description">
                            {{ $isFrench ? 'Gestion de production et inventaires' : 'Production and inventory management' }}
                        </p>
                    </div>
                    <div class="module-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
            
            <div class="module-card module-vendeur">
                <a href="{{ route('serveur.workspace') }}" class="module-link">
                    <div class="module-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                            <path d="M3 6h18"></path>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <div class="module-info">
                        <h3 class="module-title">{{ $isFrench ? 'Vendeur' : 'Seller' }}</h3>
                        <p class="module-description">
                            {{ $isFrench ? 'Gestion des ventes et clients' : 'Sales and customer management' }}
                        </p>
                    </div>
                    <div class="module-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
            
            <div class="module-card module-employe">
                <a href="{{ route('alim.workspace') }}" class="module-link">
                    <div class="module-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="module-info">
                        <h3 class="module-title">{{ $isFrench ? 'Employé' : 'Employee' }}</h3>
                        <p class="module-description">
                            {{ $isFrench ? 'Gestion du personnel et tâches' : 'Staff and task management' }}
                        </p>
                    </div>
                    <div class="module-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 {{ $isFrench ? 'Portail de Gestion glace | Tous droits réservés' : 'Ice Management Portal | All rights reserved' }}</p>
    </footer>

    <script>
        // Add smooth scrolling and enhanced mobile interactions
        document.addEventListener('DOMContentLoaded', function() {
            const moduleCards = document.querySelectorAll('.module-card');
            
            // Add entrance animation observer for mobile
            if (window.innerWidth <= 768) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });
                
                moduleCards.forEach(card => {
                    observer.observe(card);
                });
            }
            
            // Add haptic feedback for mobile
            moduleCards.forEach(card => {
                card.addEventListener('touchstart', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                });
            });
        });
    </script>
</body>
</html>
@endsection
