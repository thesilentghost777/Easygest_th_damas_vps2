@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Accès aux comptes des employés' : 'Employee Account Access' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Gestion des accès et contrôle des comptes' : 'Access management and account control' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:py-8 md:px-4 max-w-6xl">
        
        <!-- Impersonation Alert -->
        @if(Session::has('impersonating'))
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 md:p-6 rounded-2xl border-l-4 border-amber-500 mb-6 shadow-lg">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-amber-800 font-medium">
                        {{ $isFrench ? 'Vous êtes connecté en tant qu\'un autre utilisateur.' : 'You are logged in as another user.' }}
                    </p>
                    <a href="{{ route('account-access.return') }}" 
                       class="inline-flex items-center mt-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        {{ $isFrench ? 'Revenir à votre compte' : 'Return to your account' }}
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 md:p-6 rounded-2xl border-l-4 border-green-500 mb-6 shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 md:p-6 rounded-2xl border-l-4 border-red-500 mb-6 shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="h-6 w-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        @if($accessibleUsers->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4">
                {{ $isFrench ? 'Aucun accès disponible' : 'No access available' }}
            </h3>
            <p class="text-gray-600 text-base md:text-lg">
                {{ $isFrench ? 'Vous n\'avez accès à aucun compte d\'employé pour le moment.' : 'You currently have no access to any employee accounts.' }}
            </p>
        </div>
        @else
        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 md:p-6 border-b border-blue-200">
                <h3 class="text-xl md:text-2xl font-bold text-blue-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2m5-10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $isFrench ? 'Comptes accessibles' : 'Accessible accounts' }}
                </h3>
            </div>
            
            <!-- Mobile Cards View -->
            <div class="md:hidden p-4 space-y-4">
                @foreach($accessibleUsers as $user)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                        <div>
                            <span class="text-gray-500">{{ $isFrench ? 'Secteur' : 'Sector' }}:</span>
                            <span class="font-medium block">{{ ucfirst($user->secteur) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ $isFrench ? 'Rôle' : 'Role' }}:</span>
                            <span class="font-medium block">{{ str_replace('_', ' ', ucfirst($user->role)) }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('account-access.access', $user->id) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        {{ $isFrench ? 'Accéder au compte' : 'Access account' }}
                    </a>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Nom' : 'Name' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Secteur' : 'Sector' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Rôle' : 'Role' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($accessibleUsers as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($user->secteur) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ str_replace('_', ' ', ucfirst($user->role)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('account-access.access', $user->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ $isFrench ? 'Accéder' : 'Access' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Mobile-optimized styles */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Enhanced touch targets */
        a, button {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Smooth scrolling */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Animations */
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
    
    .bg-white {
        animation: slideInUp 0.3s ease-out;
    }
    
    /* Focus states */
    a:focus, button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }
    
    /* Hover effects for desktop */
    @media (hover: hover) {
        .hover\:shadow-lg:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    }
</style>
@endsection