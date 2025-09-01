{{-- resources/views/extras/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-4 px-4 md:py-8">
    <div class="container mx-auto max-w-4xl">
        @include('buttons')
        
        <!-- Mobile-first card with native-like design -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            <!-- Enhanced Header with glassmorphism effect -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-8 md:px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        {{ $isFrench ? "Détails de l'Extra" : "Extra Details" }}
                    </h1>
                    <p class="text-blue-100 text-lg font-medium">{{ $extra->secteur }}</p>
                </div>
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Enhanced Content with mobile-native spacing -->
            <div class="p-6 md:p-8 space-y-8">
                <!-- Enhanced Cards Grid with better mobile layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Work Hours Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-blue-600 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $isFrench ? 'Horaires de travail' : 'Work Schedule' }}
                            </h2>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-blue-200/50">
                                <span class="font-semibold text-gray-700">
                                    {{ $isFrench ? 'Début' : 'Start' }} :
                                </span>
                                <span class="text-lg font-bold text-blue-700">
                                    {{ $extra->heure_arriver_adequat->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-blue-200/50">
                                <span class="font-semibold text-gray-700">
                                    {{ $isFrench ? 'Fin' : 'End' }} :
                                </span>
                                <span class="text-lg font-bold text-blue-700">
                                    {{ $extra->heure_depart_adequat->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="font-semibold text-gray-700">
                                    {{ $isFrench ? 'Durée totale' : 'Total Duration' }} :
                                </span>
                                <span class="text-lg font-bold text-blue-700">
                                    {{ number_format($extra->duree_travail, 2) }} {{ $isFrench ? 'heures' : 'hours' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Remuneration Card -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200/50 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-green-600 rounded-xl shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $isFrench ? 'Rémunération' : 'Remuneration' }}
                            </h2>
                        </div>
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $isFrench ? 'Salaire total' : 'Total Salary' }}
                            </p>
                            <p class="text-3xl font-bold text-green-700">
                                {{ number_format($extra->salaire_adequat, 2) }} XAF
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Conditions Section -->
                <div class="space-y-6">
                    <!-- Age minimum with enhanced mobile-friendly card -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border-l-4 border-indigo-500 shadow-md">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="p-2 bg-indigo-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $isFrench ? 'Âge minimum requis' : 'Minimum Age Required' }}
                            </h3>
                        </div>
                        <p class="text-2xl font-bold text-indigo-700 ml-12">
                            {{ $extra->age_adequat }} {{ $isFrench ? 'ans' : 'years' }}
                        </p>
                    </div>

                    <!-- Enhanced Interdictions -->
                    @if($extra->interdit)
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border-l-4 border-red-500 shadow-md">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-2 bg-red-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $isFrench ? 'Interdictions' : 'Prohibitions' }}
                            </h3>
                        </div>
                        <div class="ml-12 space-y-2">
                            @foreach($extra->interditsArray as $interdit)
                            <div class="flex items-start gap-3 p-3 bg-white/50 rounded-lg">
                                <div class="w-2 h-2 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-gray-700 font-medium">{{ $interdit }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Enhanced Rules -->
                    @if($extra->regles)
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border-l-4 border-green-500 shadow-md">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-2 bg-green-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $isFrench ? 'Règles à suivre' : 'Rules to Follow' }}
                            </h3>
                        </div>
                        <div class="ml-12 space-y-2">
                            @foreach($extra->reglesArray as $regle)
                            <div class="flex items-start gap-3 p-3 bg-white/50 rounded-lg">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-gray-700 font-medium">{{ $regle }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

               
            </div>
        </div>
    </div>
</div>

<!-- Mobile-specific enhancements -->
<style>
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Native-like touch feedback */
    .hover\:scale-105:active {
        transform: scale(0.98);
    }
    
    /* Better mobile spacing */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    
    /* Mobile-optimized shadows */
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
}

/* Enhanced glassmorphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Smooth transitions for all interactive elements */
* {
    -webkit-tap-highlight-color: transparent;
}
</style>
@endsection
