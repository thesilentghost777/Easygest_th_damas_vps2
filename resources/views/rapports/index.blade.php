@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-blue-50 to-blue-100">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6">
        <div class="container mx-auto">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2 animate-fade-in-down">
                {{ $isFrench ? 'Rapports des Employés' : 'Employee Reports' }}
            </h1>
            <p class="text-blue-100 text-sm md:text-base animate-fade-in-up animation-delay-200">
                {{ $isFrench ? 'Générez des rapports professionnels pour chaque employé' : 'Generate professional reports for each employee' }}
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6 md:py-8">
        <!-- Back Button -->
        <div class="mb-6 animate-slide-in-left">
            @include('buttons')
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-xl md:rounded-lg shadow-lg md:shadow-md p-4 md:p-6 animate-fade-in-up animation-delay-300">
            <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6 text-center md:text-left">
                {{ $isFrench ? 'Sélectionnez un employé pour générer son rapport' : 'Select an employee to generate their report' }}
            </h2>

            <!-- Employees Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-6">
                @forelse($employees as $employee)
                    <a href="{{ route('rapports.generer', $employee->id) }}" class="block transform transition-all duration-300 hover:scale-105 animate-scale-in animation-delay-{{ $loop->index * 100 + 400 }}">
                        <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-100 rounded-xl md:rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-4 md:p-6 hover:border-blue-300 relative overflow-hidden group">
                            <!-- Mobile Animation Background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 md:hidden"></div>
                            
                            <!-- Content -->
                            <div class="flex items-center space-x-3 md:space-x-4 relative z-10">
                                <!-- Avatar -->
                                <div class="rounded-full bg-gradient-to-r from-blue-600 to-blue-700 h-12 w-12 md:h-10 md:w-10 flex items-center justify-center text-white font-bold shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                                
                                <!-- Employee Info -->
                                <div class="flex-1">
                                    <h4 class="text-base md:text-lg font-medium text-gray-800 group-hover:text-blue-700 transition-colors duration-300">
                                        {{ $employee->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="capitalize">
                                            {{ $employee->role ?? ($isFrench ? 'Non défini' : 'Not defined') }}
                                        </span>
                                        @if($employee->secteur)
                                            <span class="text-blue-600 font-medium"> - {{ $employee->secteur }}</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Mobile Arrow Indicator -->
                                <div class="md:hidden">
                                    <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700 transform group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Mobile Pulse Effect -->
                            <div class="absolute inset-0 rounded-xl border-2 border-blue-400 opacity-0 group-hover:opacity-100 animate-pulse md:hidden"></div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="p-6 md:p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl md:rounded-lg border border-yellow-200 text-center animate-bounce-in">
                            <div class="flex flex-col items-center space-y-3">
                                <!-- Icon -->
                                <svg class="w-12 h-12 md:w-8 md:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                
                                <!-- Message -->
                                <p class="text-yellow-700 font-medium text-sm md:text-base">
                                    {{ $isFrench ? 'Aucun employé n\'a été trouvé dans le système.' : 'No employees found in the system.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Mobile-specific styles and animations -->
<style>
@media (max-width: 768px) {
    /* Enhanced mobile animations */
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-in-left {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes bounce-in {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
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

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out forwards;
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
    }

    .animate-slide-in-left {
        animation: slide-in-left 0.5s ease-out forwards;
    }

    .animate-scale-in {
        animation: scale-in 0.5s ease-out forwards;
        opacity: 0;
    }

    .animate-bounce-in {
        animation: bounce-in 0.8s ease-out forwards;
    }

    .animation-delay-200 {
        animation-delay: 0.2s;
        opacity: 0;
    }

    .animation-delay-300 {
        animation-delay: 0.3s;
        opacity: 0;
    }

    .animation-delay-400 {
        animation-delay: 0.4s;
    }

    .animation-delay-500 {
        animation-delay: 0.5s;
    }

    .animation-delay-600 {
        animation-delay: 0.6s;
    }

    .animation-delay-700 {
        animation-delay: 0.7s;
    }

    .animation-delay-800 {
        animation-delay: 0.8s;
    }

    /* Enhanced mobile interactions */
    .group:active {
        transform: scale(0.98);
    }

    /* Mobile-specific card enhancements */
    .group:hover .bg-gradient-to-br {
        background: linear-gradient(135deg, #ffffff 0%, #dbeafe 100%);
    }

    /* Smooth mobile scrolling */
    html {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    /* Enhanced touch targets for mobile */
    .group {
        min-height: 80px;
        touch-action: manipulation;
    }

    /* Mobile-specific shadow enhancements */
    .shadow-lg {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
}

/* Desktop and tablet styles remain unchanged */
@media (min-width: 769px) {
    .animate-fade-in-down,
    .animate-fade-in-up,
    .animate-slide-in-left,
    .animate-scale-in,
    .animate-bounce-in {
        opacity: 1;
        animation: none;
    }
    
    .animation-delay-200,
    .animation-delay-300 {
        opacity: 1;
    }
}
</style>
@endsection