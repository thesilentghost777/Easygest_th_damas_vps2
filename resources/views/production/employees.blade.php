@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Employés de Production' : 'Production Employees' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Liste des boulangers et pâtissiers' : 'List of bakers and pastry chefs' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:py-8 md:px-4">
        <!-- Mobile Header Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 mb-6 md:mb-8">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2m5-10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Sélectionnez un employé pour voir ses détails' : 'Select an employee to view details' }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        {{ $isFrench ? 'Touchez une carte pour plus d\'informations' : 'Tap a card for more information' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Employee Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($employees as $employee)
            <a href="{{ route('employee.details2', $employee->id) }}" 
               class="group block focus:outline-none" 
               aria-label="{{ $isFrench ? 'Voir les détails de' : 'View details for' }} {{ $employee->name }}">
                
                <!-- Mobile Card Design -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl active:scale-95 group-focus:ring-4 group-focus:ring-blue-200">
                    
                    <!-- Card Header with Avatar -->
                    <div class="relative bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-center">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="20" cy="20" r="2"/>
                                <circle cx="40" cy="10" r="1.5"/>
                                <circle cx="60" cy="25" r="1"/>
                                <circle cx="80" cy="15" r="1.5"/>
                                <circle cx="30" cy="40" r="1"/>
                                <circle cx="70" cy="45" r="2"/>
                                <circle cx="90" cy="35" r="1"/>
                            </svg>
                        </div>
                        
                        <!-- Avatar -->
                        <div class="relative mx-auto w-16 h-16 md:w-20 md:h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-300 group-hover:rotate-6">
                            <span class="text-2xl md:text-3xl font-bold text-blue-600">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </span>
                        </div>
                        
                        <!-- Status Indicator -->
                        <div class="absolute top-4 right-4">
                            <div class="w-3 h-3 bg-green-400 rounded-full border-2 border-white shadow-sm animate-pulse"></div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-4 md:p-6">
                        <div class="text-center mb-4">
                            <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-1 truncate">
                                {{ $employee->name }}
                            </h4>
                            <p class="text-sm md:text-base text-gray-600 capitalize truncate">
                                {{ $isFrench ? $employee->role : $employee->role }}
                            </p>
                        </div>
                        
                        <!-- Action Indicator -->
                        <div class="flex items-center justify-center space-x-2 text-blue-600 group-hover:text-blue-700 transition-colors duration-200">
                            <span class="text-sm font-medium">
                                {{ $isFrench ? 'Voir détails' : 'View details' }}
                            </span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Hover Effect Overlay -->
                    <div class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($employees->isEmpty())
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2m5-10a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                {{ $isFrench ? 'Aucun employé trouvé' : 'No employees found' }}
            </h3>
            <p class="text-gray-500">
                {{ $isFrench ? 'Il n\'y a actuellement aucun employé de production enregistré.' : 'There are currently no production employees registered.' }}
            </p>
        </div>
        @endif
    </div>
</div>

<style>
    /* Mobile-First Responsive Enhancements */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Enhanced touch targets */
        a {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Smooth animations for mobile */
        * {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Mobile-optimized hover states */
        @media (hover: none) {
            .group:active .transform {
                transform: scale(0.97);
            }
        }
    }
    
    /* Custom animations */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Card hover effects */
    .group:hover .bg-gradient-to-br {
        background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
    }
    
    /* Focus states for accessibility */
    .group:focus-visible {
        outline: none;
    }
    
    .group:focus-visible .bg-white {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }
</style>
@endsection
