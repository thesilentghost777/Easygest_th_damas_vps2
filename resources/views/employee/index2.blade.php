@extends('employee.default2')

@section('page-content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-6 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header with mobile optimization -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-800 animate-fade-in flex items-center">
            <i class="mdi mdi-account-circle mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Profil Employé' : 'Employee Profile' }} - {{ $user->name }}
        </h1>
    </div>

    <!-- Profile header card with enhanced mobile design -->
    <div class="bg-white rounded-2xl p-4 lg:p-8 mb-4 lg:mb-6 shadow-xl mobile-card animate-fade-in transform hover:scale-105 transition-all duration-300">
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <div class="relative">
                <div class="h-20 w-20 lg:h-24 lg:w-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-500 flex items-center justify-center text-white text-2xl lg:text-3xl font-bold shadow-lg animate-bounce-gentle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
            </div>
            <div class="text-center sm:text-left flex-1">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                    <p class="text-blue-600 font-medium">{{ ucfirst($user->role) }}</p>
                    <span class="hidden sm:block text-gray-300">•</span>
                    <p class="text-purple-600 font-medium">{{ ucfirst($user->secteur) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Information cards grid with enhanced mobile design -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
        <!-- Personal information card -->
        <div class="bg-white rounded-2xl p-4 lg:p-6 shadow-xl mobile-card animate-fade-in transform hover:scale-105 transition-all duration-300">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mr-3">
                    <i class="mdi mdi-account text-green-600 text-lg"></i>
                </div>
                {{ $isFrench ? 'Informations Personnelles' : 'Personal Information' }}
            </h2>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Email' : 'Email' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ $user->email ?? ($isFrench ? 'Non renseigné' : 'Not specified') }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-green-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Téléphone' : 'Phone' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ $user->num_tel ?? ($isFrench ? 'Non renseigné' : 'Not specified') }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-purple-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Date de naissance' : 'Date of Birth' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ $user->date_naissance ? date('d/m/Y', strtotime($user->date_naissance)) : ($isFrench ? 'Non renseignée' : 'Not specified') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional information card -->
        <div class="bg-white rounded-2xl p-4 lg:p-6 shadow-xl mobile-card animate-fade-in transform hover:scale-105 transition-all duration-300">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mr-3">
                    <i class="mdi mdi-briefcase text-blue-600 text-lg"></i>
                </div>
                {{ $isFrench ? 'Informations Professionnelles' : 'Professional Information' }}
            </h2>
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Secteur' : 'Sector' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ ucfirst($user->secteur) ?? ($isFrench ? 'Non renseigné' : 'Not specified') }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-green-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Rôle' : 'Role' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ ucfirst($user->role) ?? ($isFrench ? 'Non renseigné' : 'Not specified') }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-purple-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Année de début' : 'Start Year' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ $user->annee_debut_service ?? ($isFrench ? 'Non renseignée' : 'Not specified') }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-yellow-50 p-3 rounded-xl border border-gray-100 transform hover:scale-105 transition-transform">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Ancienneté' : 'Seniority' }}</span>
                        <span class="text-gray-900 font-semibold text-sm">{{ $user->annee_debut_service ? (date('Y') - $user->annee_debut_service) . ($isFrench ? ' ans' : ' years') : ($isFrench ? 'Non calculable' : 'Not calculable') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial information card with enhanced design -->
    <div class="bg-white rounded-2xl p-4 lg:p-6 shadow-xl mobile-card animate-fade-in transform hover:scale-105 transition-all duration-300">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <div class="w-8 h-8 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full flex items-center justify-center mr-3">
                <i class="mdi mdi-cash-multiple text-yellow-600 text-lg"></i>
            </div>
            {{ $isFrench ? 'Informations Financières' : 'Financial Information' }}
        </h2>
        <div class="bg-gradient-to-r from-blue-50 to-indigo-100 rounded-2xl p-4 lg:p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Avance sur salaire récupérée ce mois' : 'Salary advance recovered this month' }}</p>
                    <p class="text-2xl lg:text-3xl font-bold text-blue-800">{{ number_format($user->avance_salaire, 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center animate-pulse">
                    <i class="mdi mdi-trending-up text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-bounce-gentle { animation: bounce 2s infinite; }
    .mobile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Mobile touch optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
    }
</style>
@endsection
