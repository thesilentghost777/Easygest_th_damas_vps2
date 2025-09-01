@extends('layouts.app')
@section('content')
<br>
<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $isFrench ? 'Envoi de Message' : 'Send Message' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .guidelines {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .guidelines h2 {
            color: #1877F2;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        .guidelines-list {
            list-style: none;
        }

        .guidelines-list li {
            margin: 10px 0;
            padding-left: 25px;
            position: relative;
        }

        .guidelines-list li:before {
            content: "✓";
            color: #1877F2;
            position: absolute;
            left: 0;
        }

        h1 {
            color: #1877F2;
            text-align: center;
            margin: 20px 0;
            font-size: 2em;
        }

        .message-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #e4e6eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        select:focus, textarea:focus {
            border-color: #1877F2;
            outline: none;
        }

        select {
            background: white;
            cursor: pointer;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        #sendButton {
            background: linear-gradient(to right, #1877F2, #0099FF);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        #sendButton:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(24, 119, 242, 0.3);
        }

        .confirmation-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            text-align: center;
            z-index: 1000;
            min-width: 300px;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .countdown {
            color: #1877F2;
            font-weight: bold;
            font-size: 1.2em;
            display: block;
            margin: 15px 0;
        }

        #cancelButton {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        #cancelButton:hover {
            background: #c82333;
        }

        .success-message, .error-message {
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            display: none;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
        }

        /* PIN form styles */
        .pin-form-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1001;
            width: 300px;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .pin-form-container.active {
            display: block;
            opacity: 1;
        }
        
        .pin-form h2 {
            margin-top: 0;
            color: #333;
        }
        
        .pin-input {
            text-align: center;
            font-size: 24px;
            letter-spacing: 8px;
            width: 100%;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .pin-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            width: 100%;
        }
        
        .pin-form button:hover {
            background-color: #3e8e41;
        }
        
        .pin-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .pin-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            body {
                padding: 10px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            
            .container {
                margin: 0;
                padding: 0;
            }
            
            .guidelines {
                border-radius: 20px;
                padding: 25px;
                margin-bottom: 20px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                transform: translateY(0);
                animation: slideInUp 0.6s ease-out;
            }
            
            .guidelines h2 {
                font-size: 1.3em;
                margin-bottom: 20px;
                text-align: center;
            }
            
            .guidelines-list li {
                margin: 15px 0;
                font-size: 14px;
                padding-left: 30px;
            }
            
            .guidelines-list li:before {
                font-size: 18px;
                color: #4CAF50;
            }
            
            h1 {
                font-size: 1.8em;
                margin: 15px 0;
                color: white;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .message-form {
                border-radius: 25px;
                padding: 25px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                box-shadow: 0 12px 40px rgba(0,0,0,0.15);
                margin-bottom: 20px;
                transform: translateY(0);
                animation: slideInUp 0.8s ease-out 0.2s both;
            }
            
            select, textarea {
                padding: 15px;
                border: 2px solid #e0e0e0;
                border-radius: 15px;
                font-size: 16px;
                margin: 15px 0;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                -webkit-appearance: none;
                background: white;
            }
            
            select:focus, textarea:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                transform: scale(1.02);
            }
            
            textarea {
                min-height: 120px;
                resize: none;
            }
            
            #sendButton {
                background: linear-gradient(45deg, #667eea, #764ba2);
                padding: 18px 30px;
                border-radius: 25px;
                font-size: 18px;
                font-weight: 600;
                letter-spacing: 0.5px;
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
                transform: translateY(0);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            #sendButton:hover, #sendButton:active {
                transform: translateY(-3px) scale(1.02);
                box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            }
            
            /* PIN Modal Mobile */
            .pin-form-container {
                width: 90%;
                max-width: 350px;
                border-radius: 25px;
                padding: 30px 25px;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                transform: translate(-50%, -50%) scale(0.9);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .pin-form-container.active {
                transform: translate(-50%, -50%) scale(1);
            }
            
            .pin-form h2 {
                font-size: 1.4em;
                margin-bottom: 10px;
                color: #333;
            }
            
            .pin-form p {
                color: #666;
                margin-bottom: 25px;
                line-height: 1.5;
            }
            
            .pin-input {
                font-size: 28px;
                letter-spacing: 12px;
                padding: 15px;
                border: 3px solid #e0e0e0;
                border-radius: 15px;
                background: #f8f9fa;
                transition: all 0.3s ease;
                margin: 20px 0 30px 0;
            }
            
            .pin-input:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                transform: scale(1.05);
                background: white;
            }
            
            .pin-form button {
                background: linear-gradient(45deg, #4CAF50, #45a049);
                padding: 15px 25px;
                border-radius: 20px;
                font-size: 16px;
                font-weight: 600;
                box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
                transition: all 0.3s ease;
            }
            
            .pin-form button:hover, .pin-form button:active {
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
            }
            
            .pin-overlay {
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }
            
            /* Success/Error messages mobile */
            .success-message, .error-message {
                border-radius: 15px;
                padding: 20px;
                margin: 20px 0;
                font-weight: 500;
                text-align: center;
                animation: bounceIn 0.6s ease-out;
            }
            
            /* Touch feedback */
            button:active, select:active {
                transform: scale(0.98);
            }
            
            /* Animations */
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
                    transform: scale(1.05);
                }
                70% {
                    transform: scale(0.9);
                }
                100% {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            
            /* Haptic feedback */
            .vibrate {
                animation: vibrate 0.3s ease-in-out;
            }
            
            @keyframes vibrate {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
                20%, 40%, 60%, 80% { transform: translateX(2px); }
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @include('buttons')

        <div class="guidelines">
            <h2>{{ $isFrench ? 'Conseils d\'utilisation' : 'Usage Guidelines' }}</h2>
            <ul class="guidelines-list">
                <li>{{ $isFrench ? 'Vos messages privés restent totalement anonymes' : 'Your private messages remain completely anonymous' }}</li>
                <li>{{ $isFrench ? 'Évitez tout langage inapproprié ou offensant' : 'Avoid any inappropriate or offensive language' }}</li>
                <li>{{ $isFrench ? 'Soyez précis et constructif dans vos messages' : 'Be precise and constructive in your messages' }}</li>
                <li>{{ $isFrench ? 'Les suggestions sont examinées régulièrement' : 'Suggestions are reviewed regularly' }}</li>
                <li>{{ $isFrench ? 'Les signalements sont traités en priorité' : 'Reports are handled with priority' }}</li>
            </ul>
        </div>
        
        <div class="message-form">
            <h1>{{ $isFrench ? 'Envoyez un Message' : 'Send a Message' }}</h1>
            <form id="mainForm" action="{{route('message-post')}}" method="POST">
                @csrf
                <select id="messageCategory" name="category" required>
                    <option value="" disabled selected>
                        {{ $isFrench ? 'Sélectionnez une catégorie' : 'Select a category' }}
                    </option>
                    <option value="complaint-private">
                        {{ $isFrench ? 'Plainte (Privée)' : 'Complaint (Private)' }}
                    </option>
                    <option value="suggestion">
                        {{ $isFrench ? 'Suggestion' : 'Suggestion' }}
                    </option>
                    <option value="report">
                        {{ $isFrench ? 'Signalement' : 'Report' }}
                    </option>
                </select>
                <textarea id="messageContent" name="message" 
                    placeholder="{{ $isFrench ? 'Écrivez votre message ici...' : 'Write your message here...' }}"></textarea>
                <input type="hidden" name="pin" id="pinValue">
                <div class="success-message" id="successMessage">
                    {{ $isFrench ? 'Message envoyé avec succès !' : 'Message sent successfully!' }}
                </div>
                <div class="error-message" id="errorMessage">
                    {{ $isFrench ? 'Erreur lors de l\'envoi du message.' : 'Error sending message.' }}
                </div>
                <button id="sendButton" type="button">
                    {{ $isFrench ? 'Envoyer' : 'Send' }}
                </button>
            </form>
        </div>
    </div>
    
    <!-- PIN Form -->
    <div class="pin-overlay" id="pinOverlay"></div>
    <div class="pin-form-container" id="pinFormContainer">
        <div class="pin-form">
            <h2>{{ $isFrench ? 'Code PIN requis' : 'PIN Code Required' }}</h2>
            <p>{{ $isFrench ? 'Veuillez entrer votre code PIN pour confirmer l\'envoi' : 'Please enter your PIN code to confirm sending' }}</p>
            <input type="text" class="pin-input" id="pinInput" name="pin" maxlength="6" 
                placeholder="******" pattern="[0-9]*" inputmode="numeric">
            <button type="button" id="confirmPinButton">
                {{ $isFrench ? 'Confirmer' : 'Confirm' }}
            </button>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="overlay"></div>
    <div class="confirmation-modal" id="confirmation">
        <p>{{ $isFrench ? 'Êtes-vous sûr de vouloir envoyer ce message ?' : 'Are you sure you want to send this message?' }}</p>
        <div class="countdown" id="countdown"></div>
        <button id="cancelButton">{{ $isFrench ? 'Annuler' : 'Cancel' }}</button>
    </div>

    <script>
        // Mobile haptic feedback simulation
        function vibrate(duration = 50) {
            if (navigator.vibrate) {
                navigator.vibrate(duration);
            }
            // Visual feedback for devices without vibration
            document.body.classList.add('vibrate');
            setTimeout(() => {
                document.body.classList.remove('vibrate');
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mainForm = document.getElementById('mainForm');
            const sendButton = document.getElementById('sendButton');
            const pinOverlay = document.getElementById('pinOverlay');
            const pinFormContainer = document.getElementById('pinFormContainer');
            const pinInput = document.getElementById('pinInput');
            const confirmPinButton = document.getElementById('confirmPinButton');
            const pinValue = document.getElementById('pinValue');
            const messageCategory = document.getElementById('messageCategory');
            const messageContent = document.getElementById('messageContent');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // Add entrance animations
            setTimeout(() => {
                document.querySelector('.guidelines').style.opacity = '1';
                document.querySelector('.guidelines').style.transform = 'translateY(0)';
            }, 200);
            
            setTimeout(() => {
                document.querySelector('.message-form').style.opacity = '1';
                document.querySelector('.message-form').style.transform = 'translateY(0)';
            }, 400);
            
            // Enhanced input interactions
            messageCategory.addEventListener('change', function() {
                vibrate(30);
                this.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            });
            
            messageContent.addEventListener('focus', function() {
                vibrate(25);
                this.style.transform = 'scale(1.01)';
            });
            
            messageContent.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
            
            // Show PIN modal when clicking Send
            sendButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const category = messageCategory.value;
                const message = messageContent.value;
                
                if (!category || !message.trim()) {
                    vibrate(100);
                    errorMessage.style.display = 'block';
                    errorMessage.style.animation = 'bounceIn 0.6s ease-out';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 3000);
                    return;
                }
                
                vibrate();
                pinOverlay.classList.add('active');
                pinFormContainer.classList.add('active');
                pinInput.focus();
            });
            
            // Handle PIN submission
            confirmPinButton.addEventListener('click', function() {
                const pin = pinInput.value.trim();
                
                if (pin.length !== 6 || !/^\d+$/.test(pin)) {
                    vibrate(150);
                    pinInput.style.animation = 'vibrate 0.3s ease-in-out';
                    pinInput.style.borderColor = '#ff4757';
                    setTimeout(() => {
                        pinInput.style.animation = '';
                        pinInput.style.borderColor = '#e0e0e0';
                    }, 500);
                    return;
                }
                
                vibrate();
                pinValue.value = pin;
                
                // Loading animation
                confirmPinButton.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                confirmPinButton.disabled = true;
                
                // Close PIN modal with animation
                setTimeout(() => {
                    pinFormContainer.style.opacity = '0';
                    pinOverlay.style.opacity = '0';
                    
                    setTimeout(() => {
                        pinOverlay.classList.remove('active');
                        pinFormContainer.classList.remove('active');
                        pinFormContainer.style.opacity = '';
                        pinOverlay.style.opacity = '';
                        
                        // Submit form
                        mainForm.submit();
                    }, 300);
                }, 1000);
            });
            
            // Close PIN modal when clicking overlay
            pinOverlay.addEventListener('click', function() {
                vibrate();
                pinOverlay.classList.remove('active');
                pinFormContainer.classList.remove('active');
                pinInput.value = '';
                confirmPinButton.disabled = false;
                confirmPinButton.innerHTML = '{{ $isFrench ? "Confirmer" : "Confirm" }}';
            });
            
            // Enhanced PIN input experience
            pinInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
                
                // Visual feedback for each digit
                if (this.value.length > 0) {
                    this.style.borderColor = '#667eea';
                    this.style.background = 'white';
                }
                
                if (this.value.length === 6) {
                    vibrate(50);
                    confirmPinButton.focus();
                    this.style.borderColor = '#4CAF50';
                }
            });
            
            // Allow Enter key to submit PIN
            pinInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length === 6) {
                    confirmPinButton.click();
                }
            });
            
            // Escape key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && pinFormContainer.classList.contains('active')) {
                    pinOverlay.click();
                }
            });
            
            // Touch gesture improvements
            let touchStartY = 0;
            pinFormContainer.addEventListener('touchstart', function(e) {
                touchStartY = e.touches[0].clientY;
            });
            
            pinFormContainer.addEventListener('touchend', function(e) {
                const touchEndY = e.changedTouches[0].clientY;
                const diff = touchStartY - touchEndY;
                
                // Swipe down to close
                if (diff < -50) {
                    pinOverlay.click();
                }
            });
        });
    </script>
</body>
</html>
@endsection
