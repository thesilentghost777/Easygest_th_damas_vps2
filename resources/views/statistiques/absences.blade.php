
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-teal-50 to-green-50 antialiased">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-white shadow-xl rounded-2xl p-6 border-b-4 border-blue-500">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-2xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-400">
                            {{ $isFrench ? 'Registre Détaillé des Absences' : 'Detailed Absence Register' }}
                        </h1>
                        <p class="text-gray-500 mt-2 text-base md:text-lg">
                            {{ $isFrench ? 'Analyse comprehensive des présences et absences' : 'Comprehensive analysis of attendance and absences' }}
                        </p>
                    </div>
                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-3">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-9.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" />
                            </svg>
                            {{ $isFrench ? 'Exporter' : 'Export' }}
                        </button>
                        @include('buttons')
                    </div>
                </div>
            </div>
        </div>

        <!-- Absence Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($absenceParEmploye as $employeId => $absence)
                <div class="bg-white rounded-2xl shadow-2xl border-l-4 border-blue-500 p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-3xl animate-scale-in" 
                     style="animation-delay: {{ $loop->index * 0.1 }}s">
                    
                    <!-- Employee Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl md:text-2xl font-bold text-blue-800 mb-1">{{ $absence['name'] }}</h3>
                            <span class="text-sm text-gray-500 bg-blue-50 px-3 py-1 rounded-full inline-block">
                                {{ $absence['secteur'] }}
                            </span>
                        </div>
                        <div class="bg-red-100 text-red-600 px-3 py-2 rounded-full font-semibold ml-4">
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ $absence['nombre_absences'] }}</div>
                                <div class="text-xs">{{ $isFrench ? 'absences' : 'absences' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Reason (if applicable) -->
                    @if($absence['raison_conges'])
                        <div class="bg-teal-50 border-l-4 border-teal-500 p-3 mb-4 rounded-r-lg">
                            <p class="text-teal-700 flex items-center text-sm">
                                <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                                </svg>
                                {{ $isFrench ? 'Congés' : 'Leave' }}: {{ ucfirst($absence['raison_conges']) }}
                            </p>
                        </div>
                    @endif

                    <!-- Absence Days -->
                    <div class="max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-blue-100 pr-2">
                        <h4 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                            </svg>
                            {{ $isFrench ? 'Jours d\'Absence' : 'Absence Days' }}
                        </h4>
                        <ul class="space-y-2">
                            @foreach($absence['jours_absences'] as $jour)
                                <li class="flex items-center text-gray-600 text-sm p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <svg class="h-4 w-4 mr-2 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                    </svg>
                                    <span class="flex-1">
                                        {{ \Carbon\Carbon::parse($jour)->locale($isFrench ? 'fr' : 'en')->isoFormat('dddd D MMMM YYYY') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <!-- No Absences State -->
                <div class="col-span-full bg-white rounded-2xl shadow-xl p-12 text-center animate-fade-in">
                    <svg class="h-24 w-24 mx-auto text-blue-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h2 class="text-2xl md:text-3xl font-bold text-blue-600 mb-4">
                        {{ $isFrench ? 'Aucune Absence Détectée' : 'No Absences Detected' }}
                    </h2>
                    <p class="text-gray-500 text-base md:text-lg">
                        {{ $isFrench ? 'Félicitations ! Aucune absence pour l\'instant' : 'Congratulations! No absences for now' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Mobile-First CSS Animations -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.6s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

/* Mobile Optimizations */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .grid {
        gap: 1.5rem;
    }
    
    .text-2xl, .text-3xl, .text-4xl {
        font-size: 1.5rem;
    }
    
    .max-h-64 {
        max-height: 12rem;
    }
    
    /* Touch-friendly buttons */
    button {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Custom Scrollbar */
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thumb-blue-300::-webkit-scrollbar-thumb {
    background-color: #93c5fd;
    border-radius: 3px;
}

.scrollbar-track-blue-100::-webkit-scrollbar-track {
    background-color: #dbeafe;
    border-radius: 3px;
}

/* Enhanced shadow */
.shadow-3xl {
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.hover\:shadow-3xl:hover {
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}
</style>
@endsection
