<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Serveur - Server Error</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #ff4757;
            --secondary-color: #ff6b7a;
            --accent-color: #00d2ff;
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-card: #222222;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #666666;
            --border-color: #333333;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-primary);
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 71, 87, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 210, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 107, 122, 0.1) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(45deg, transparent 30%, rgba(255, 71, 87, 0.03) 50%, transparent 70%),
                linear-gradient(-45deg, transparent 30%, rgba(0, 210, 255, 0.03) 50%, transparent 70%);
            animation: backgroundShift 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }
        
        @keyframes backgroundShift {
            0%, 100% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(20px) translateY(-10px); }
            50% { transform: translateX(-10px) translateY(20px); }
            75% { transform: translateX(15px) translateY(10px); }
        }
        
        .container {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: 
                0 32px 64px var(--shadow-color),
                0 8px 32px rgba(255, 71, 87, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            padding: 3rem;
            max-width: 700px;
            width: 95%;
            text-align: center;
            position: relative;
            animation: containerAppear 0.8s ease-out;
        }
        
        @keyframes containerAppear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulse 2s ease-in-out infinite;
            filter: drop-shadow(0 4px 8px rgba(255, 71, 87, 0.3));
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }
        
        .language-section {
            margin-bottom: 2rem;
            padding: 2rem;
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .language-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            animation: borderFlow 3s ease-in-out infinite;
        }
        
        @keyframes borderFlow {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }
        
        .language-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(255, 71, 87, 0.2);
            border-color: var(--primary-color);
        }
        
        .language-section h2 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-size: clamp(1.25rem, 3vw, 1.5rem);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .language-section p {
            line-height: 1.7;
            font-size: clamp(1rem, 2.5vw, 1.1rem);
            margin-bottom: 1rem;
            color: var(--text-secondary);
            font-weight: 400;
        }
        
        .language-section p strong {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .contact-info {
            background: linear-gradient(135deg, rgba(0, 210, 255, 0.1), rgba(0, 210, 255, 0.05));
            padding: 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            color: var(--accent-color);
            border: 1px solid rgba(0, 210, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .contact-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 210, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 2.5rem 0;
            position: relative;
        }
        
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            box-shadow: 0 0 16px var(--primary-color);
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin-top: 2rem;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(255, 71, 87, 0.4);
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .footer {
            margin-top: 3rem;
            font-size: 0.9rem;
            color: var(--text-muted);
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .footer p {
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .language-section {
                padding: 1.5rem;
            }
            
            .error-icon {
                font-size: 4rem;
            }
            
            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
            
            .language-section {
                padding: 1rem;
            }
            
            .error-icon {
                font-size: 3rem;
            }
            
            .language-section h2 {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
        
        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* High contrast mode */
        @media (prefers-contrast: high) {
            :root {
                --bg-primary: #000000;
                --bg-card: #111111;
                --text-primary: #ffffff;
                --border-color: #555555;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1>Erreur Serveur - Server Error</h1>
        
        <!-- Section Française -->
        <div class="language-section">
            <h2>🇫🇷 Français</h2>
            <p>
                <strong>Une erreur technique s'est produite sur notre serveur.</strong>
            </p>
            <p>
                Nous nous excusons pour ce désagrément. L'erreur a été automatiquement signalée à notre équipe technique qui travaille déjà à la résoudre.
            </p>
            <p>
                <strong>Action requise :</strong> Veuillez contacter immédiatement le développeur pour un traitement prioritaire de cette erreur.
            </p>
            <div class="contact-info">
                📧 Email : wilfrieddark2.0@gmail.com
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Section Anglaise -->
        <div class="language-section">
            <h2>🇬🇧 English</h2>
            <p>
                <strong>A technical error occurred on our server.</strong>
            </p>
            <p>
                We apologize for this inconvenience. The error has been automatically reported to our technical team who are already working to resolve it.
            </p>
            <p>
                <strong>Required action:</strong> Please contact the developer immediately for priority processing of this error.
            </p>
            <div class="contact-info">
                📧 Email: wilfrieddark2.0@gmail.com
            </div>
        </div>
        
        <a href="/" class="btn">← Retour à l'accueil / Back to Home</a>
        
        <div class="footer">
            <p>Erreur enregistrée le {{ now()->format('d/m/Y à H:i:s') }}</p>
            <p>Error logged on {{ now()->format('m/d/Y at H:i:s') }}</p>
        </div>
    </div>
</body>
</html>