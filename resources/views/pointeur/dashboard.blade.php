@extends('employee.default')

@section('page-content')
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($isFrench ?? true) ? 'Tableau de bord Pointeur' : 'Pointer Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0);
            background-size: 20px 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    
    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @if (session('success'))
        <div class="notification fixed top-4 right-4 z-50 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg border border-emerald-400 max-w-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="notification fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg border border-red-400 max-w-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <br><br>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        
        <!-- Quick Actions Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            
            <!-- Orders Management -->
            <a href="{{ route('chef.commandes.create') }}" class="group">
                <div class="glass-card rounded-2xl p-8 card-hover slide-up border border-blue-200" style="animation-delay: 0.1s">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ ($isFrench ?? true) ? 'Commandes' : 'Orders' }}
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            {{ ($isFrench ?? true) ? 'Gérez et suivez toutes vos commandes en cours' : 'Manage and track all your ongoing orders' }}
                        </p>
                        <div class="inline-flex items-center text-blue-600 font-medium group-hover:text-blue-700 transition-colors">
                            <span class="mr-2">{{ ($isFrench ?? true) ? 'Accéder' : 'Access' }}</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Time Tracking -->
            <a href="{{ route('receptions.pointeurs.index') }}" class="group">
                <div class="glass-card rounded-2xl p-8 card-hover slide-up border border-purple-200" style="animation-delay: 0.2s">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ ($isFrench ?? true) ? 'Pointages' : 'Time Tracking' }}
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            {{ ($isFrench ?? true) ? 'Suivez et gérez les pointages de produits' : 'Track and manage your product reception' }}
                        </p>
                        <div class="inline-flex items-center text-purple-600 font-medium group-hover:text-purple-700 transition-colors">
                            <span class="mr-2">{{ ($isFrench ?? true) ? 'Accéder' : 'Access' }}</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Damage Management -->
            <a href="{{ route('avaries.index') }}" class="group">
                <div class="glass-card rounded-2xl p-8 card-hover slide-up border border-emerald-200" style="animation-delay: 0.3s">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ ($isFrench ?? true) ? 'Avaries' : 'Damages' }}
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            {{ ($isFrench ?? true) ? 'Gérez et documentez tous les incidents et dommages' : 'Manage and document all incidents and damages' }}
                        </p>
                        <div class="inline-flex items-center text-emerald-600 font-medium group-hover:text-emerald-700 transition-colors">
                            <span class="mr-2">{{ ($isFrench ?? true) ? 'Accéder' : 'Access' }}</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

        </div>

      

        <!-- Back Button for Desktop -->
        <div class="hidden md:block text-center mb-8">
            @include('buttons')
        </div>
    </main>

    <script>
        // Auto-hide notifications
        function hideNotifications() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 5000);
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', hideNotifications);

        // Add click to dismiss functionality
        document.querySelectorAll('.notification').forEach(notification => {
            notification.addEventListener('click', () => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        });
    </script>

</body>
</html>
@endsection