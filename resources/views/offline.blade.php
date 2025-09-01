<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGPTH - Mode hors ligne</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 35%, #1d4ed8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
            position: relative;
        }
        
        /* Animations de fond */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(147, 197, 253, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(29, 78, 216, 0.4) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        /* Particules flottantes */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0px) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        .main-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 1rem;
        }
        
        .offline-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            text-align: center;
            animation: containerFadeIn 1s ease-out;
            position: relative;
            overflow: hidden;
        }
        
        .offline-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        @keyframes containerFadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .offline-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 
                0 10px 25px rgba(59, 130, 246, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: iconPulse 3s ease-in-out infinite;
            position: relative;
        }
        
        .offline-icon::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 2px solid rgba(59, 130, 246, 0.3);
            animation: ripple 2s infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        .status-badge {
            display: inline-block;
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(239, 68, 68, 0.3);
            animation: statusBlink 2s ease-in-out infinite;
        }
        
        @keyframes statusBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes titleGlow {
            0% { filter: brightness(1); }
            100% { filter: brightness(1.1); }
        }
        
        .description {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            color: #e2e8f0;
        }
        
        .retry-button {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 
                0 4px 15px rgba(59, 130, 246, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .retry-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .retry-button:hover::before {
            left: 100%;
        }
        
        .retry-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(59, 130, 246, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }
        
        .retry-button:active {
            transform: translateY(0);
        }
        
        .retry-button.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .retry-button .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }
        
        .retry-button.loading .spinner {
            display: block;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .features {
            margin-top: 3rem;
            text-align: left;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .features h3 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #e2e8f0;
            font-weight: 600;
        }
        
        .features-grid {
            display: grid;
            gap: 1rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
            animation: featureSlideIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.6s; }
        .feature-item:nth-child(4) { animation-delay: 0.8s; }
        
        @keyframes featureSlideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .feature-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .feature-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .feature-text {
            font-size: 0.95rem;
            color: #e2e8f0;
            line-height: 1.4;
        }
        
        .connection-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 0.5rem;
            }
            
            .offline-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .offline-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
            
            h1 {
                font-size: 1.6rem;
            }
            
            .description {
                font-size: 1rem;
            }
            
            .retry-button {
                padding: 12px 24px;
                font-size: 0.95rem;
            }
            
            .features {
                padding: 1.5rem;
                margin-top: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .offline-container {
                padding: 1.5rem 1rem;
            }
            
            .offline-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            h1 {
                font-size: 1.4rem;
            }
            
            .description {
                font-size: 0.95rem;
            }
            
            .retry-button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
            
            .connection-status {
                top: 10px;
                right: 10px;
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
            
            .features {
                padding: 1rem;
            }
            
            .feature-item {
                padding: 0.5rem;
                gap: 0.75rem;
            }
            
            .feature-icon {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
            }
            
            .feature-text {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 320px) {
            .offline-container {
                padding: 1rem 0.75rem;
            }
            
            h1 {
                font-size: 1.2rem;
            }
            
            .description {
                font-size: 0.9rem;
            }
        }
        
        /* Mode sombre amélioré */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 35%, #334155 100%);
            }
        }
        
        /* Réduction de mouvement pour l'accessibilité */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Particules animées -->
    <div class="particles" id="particles"></div>
    
    <!-- Statut de connexion -->
    <div class="connection-status">
        <div class="status-dot"></div>
        <span>Hors ligne</span>
    </div>
    
    <div class="main-container">
        <div class="offline-container">
            <div class="status-badge">
                📡 Mode hors ligne activé
            </div>
            
            <div class="offline-icon">
                🌐
            </div>
            
            <h1>Connexion interrompue</h1>
            
            <p class="description">
                Pas de panique ! SGPTH continue de fonctionner en mode hors ligne 
                avec vos données mises en cache.
            </p>
            
            <button class="retry-button" onclick="checkConnection()" id="retryBtn">
                <div class="spinner"></div>
                <span>Réessayer la connexion</span>
            </button>
            
            <div class="features">
                <h3>✨ Disponible hors ligne</h3>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <div class="feature-text">Consultation des données récentes</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🧭</div>
                        <div class="feature-text">Navigation complète dans l'interface</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">💾</div>
                        <div class="feature-text">Accès aux pages déjà visitées</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔄</div>
                        <div class="feature-text">Synchronisation automatique</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Génération des particules
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth < 768 ? 15 : 25;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Taille aléatoire
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Position horizontale aléatoire
                particle.style.left = `${Math.random() * 100}%`;
                
                // Délai d'animation aléatoire
                particle.style.animationDelay = `${Math.random() * 15}s`;
                particle.style.animationDuration = `${15 + Math.random() * 10}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        // Fonction de vérification de connexion améliorée
        async function checkConnection() {
            const button = document.getElementById('retryBtn');
            const buttonText = button.querySelector('span');
            const statusDot = document.querySelector('.status-dot');
            const statusText = document.querySelector('.connection-status span');
            
            // Animation de chargement
            button.classList.add('loading');
            buttonText.textContent = 'Vérification...';
            
            try {
                // Test de connexion réel
                const response = await fetch('{{ route("workspace.redirect") }}', {
                    method: 'HEAD',
                    cache: 'no-cache',
                    timeout: 5000
                });
                
                if (response.ok) {
                    // Connexion rétablie
                    button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    buttonText.textContent = 'Connexion rétablie !';
                    statusDot.style.background = '#10b981';
                    statusText.textContent = 'En ligne';
                    
                    setTimeout(() => {
                        window.location.href = '{{ route("workspace.redirect") }}';
                    }, 1000);
                } else {
                    throw new Error('Connexion échouée');
                }
            } catch (error) {
                // Toujours hors ligne
                button.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
                buttonText.textContent = 'Toujours hors ligne';
                
                setTimeout(() => {
                    button.classList.remove('loading');
                    button.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
                    buttonText.textContent = 'Réessayer la connexion';
                }, 2000);
            }
        }
        
        // Vérification automatique de la connexion
        window.addEventListener('online', function() {
            const statusDot = document.querySelector('.status-dot');
            const statusText = document.querySelector('.connection-status span');
            
            statusDot.style.background = '#10b981';
            statusText.textContent = 'Reconnexion...';
            
            setTimeout(() => {
                window.location.href = '{{ route("workspace.redirect") }}';
            }, 1000);
        });
        
        // Vérification périodique
        let connectionCheckInterval = setInterval(async () => {
            if (navigator.onLine) {
                try {
                    const response = await fetch('{{ route("workspace.redirect") }}', {
                        method: 'HEAD',
                        cache: 'no-cache'
                    });
                    
                    if (response.ok) {
                        clearInterval(connectionCheckInterval);
                        window.location.href = '{{ route("workspace.redirect") }}';
                    }
                } catch (error) {
                    // Connexion toujours indisponible
                }
            }
        }, 10000); // Vérification toutes les 10 secondes
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Animation d'entrée pour les éléments
            const elements = document.querySelectorAll('.offline-container > *');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
        
        // Nettoyage sur changement de page
        window.addEventListener('beforeunload', function() {
            clearInterval(connectionCheckInterval);
        });
        
        // Support du raccourci clavier
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                checkConnection();
            }
        });
    </script>
</body>
</html>