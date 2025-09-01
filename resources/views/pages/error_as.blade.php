@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isFrench ? 'Erreur - Avance sur Salaire' : 'Error - Salary Advance' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2d3796 0%, #242875 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            width: 100%;
        }

        .intro-error {
            text-align: center;
            margin-bottom: 30px;
        }

        .intro-text {
            font-size: 2.5rem;
            font-weight: bold;
            color: #dc3545;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }

        .crying-emoji {
            font-size: 6rem;
            animation: bounce 2s infinite ease-in-out;
            display: block;
            margin: 20px 0;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .error {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .error-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            border: 1px solid rgba(255,255,255,0.2);
            margin: 0 auto;
            max-width: 100%;
        }

        .error-title {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        .error-list {
            list-style: none;
            margin-bottom: 30px;
        }

        .error-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            background: rgba(248, 249, 250, 0.8);
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #dc3545;
            transition: all 0.3s ease;
        }

        .error-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .status-icon {
            font-size: 1.2rem;
            margin-right: 15px;
            min-width: 20px;
            text-align: center;
        }

        .date-info {
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
            display: block;
            margin-top: 5px;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
        }

        .contact-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            color: white;
            transition: all 0.3s ease;
        }

        .contact-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .contact-icon {
            color: white;
        }

        .contact-info a {
            color: white !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .intro-text {
                font-size: 2rem;
            }

            .crying-emoji {
                font-size: 4rem;
            }

            .error-box {
                padding: 25px 20px;
                margin: 0 10px;
            }

            .error-title {
                font-size: 1.2rem;
            }

            .error-item {
                padding: 12px;
                flex-direction: column;
                text-align: center;
            }

            .status-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .contact-info {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .intro-text {
                font-size: 1.8rem;
            }

            .crying-emoji {
                font-size: 3.5rem;
            }

            .error-box {
                padding: 20px 15px;
            }

            .error-title {
                font-size: 1.1rem;
            }

            .error-item {
                padding: 10px;
                font-size: 0.9rem;
            }

            .contact-info a {
                font-size: 1rem;
            }
        }

        /* Animation pour l'apparition du contenu */
        .error-box {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="intro-error">
        <div class="intro-text">{{ $isFrench ? 'ERREUR' : 'ERROR' }}</div>
        <div class="crying-emoji">😭</div>
    </div>

    <div class="error">
        <div class="error-box">
            <h4 class="error-title">
                {{ $isFrench ? 'Vérifiez les éléments suivants :' : 'Please check the following:' }}
            </h4>
            <ul class="error-list">
                <!-- Vérification de la plage de jours autorisés -->
                <li class="error-item">
                    <span class="status-icon">{{ $outOfRange ? '✗' : '✓' }}</span>
                    <div>
                        @if($outOfRange)
                            {{ $isFrench ? 'Vous n\'êtes pas dans la période autorisée pour faire une demande d\'AS' : 'You are not in the authorized period to make a salary advance request' }}
                        @else
                            {{ $isFrench ? 'Vous êtes dans la période autorisée pour faire une demande d\'AS' : 'You are in the authorized period to make a salary advance request' }}
                        @endif
                    </div>
                </li>
                
                <!-- Vérification si l'employé a déjà une demande ce mois-ci -->
                <li class="error-item">
                    <span class="status-icon">{{ $hasRequest ? '✗' : '✓' }}</span>
                    <div>
                        @if($hasRequest)
                            {{ $isFrench ? 'Vous avez déjà fait une demande d\'AS ce mois-ci' : 'You have already made a salary advance request this month' }}
                        @else
                            {{ $isFrench ? 'Vous n\'avez pas encore fait de requête d\'AS ce mois' : 'You haven\'t made any salary advance request this month' }}
                        @endif
                    </div>
                </li>
            </ul>
            
            @if(isset($error))
                <div class="error-message">
                    <span class="error-text">{{ $error }}</span>
                </div>
            @endif
            
          
        </div>
    </div>
    
    <script>
        const isFrench = {{ $isFrench ? 'true' : 'false' }};
        
        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function updateDateStatus() {
            const now = new Date();
            const dateIcon = document.getElementById('dateIcon');
            dateIcon.textContent = now.getDate() >= 9 ? '✓' : '✗';

            const currentDate = document.getElementById('currentDate');
            const todayText = isFrench ? 'Aujourd\'hui' : 'Today';
            currentDate.textContent = `(${todayText}: ${formatDate(now)})`;
        }

        // Animation supplémentaire pour l'emoji
        document.addEventListener('DOMContentLoaded', function() {
            updateDateStatus();
            
            // Ajouter un effet de rotation occasionnel à l'emoji
            const emoji = document.querySelector('.crying-emoji');
            setInterval(() => {
                if (Math.random() > 0.7) { // 30% de chance
                    emoji.style.transform = 'rotate(-10deg) scale(1.1)';
                    setTimeout(() => {
                        emoji.style.transform = 'rotate(0deg) scale(1)';
                    }, 500);
                }
            }, 3000);
        });
    </script>
</body>
</html>
@endsection