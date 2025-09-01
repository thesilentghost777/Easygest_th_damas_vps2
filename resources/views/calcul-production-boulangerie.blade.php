@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
    <!-- Header Section -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-slate-200/50 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-center">
                <div class="text-center space-y-2">
                    <h1 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-600 to-emerald-600 bg-clip-text text-transparent">
                        {{ $isFrench ? 'Production Boulangerie' : 'Bakery Production' }}
                    </h1>
                    <p class="text-slate-600 max-w-2xl">
                        {{ $isFrench ? 'Calcul automatique de la production mensuelle' : 'Automatic monthly production calculation' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <!-- Main Card -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl border border-slate-200/50 overflow-hidden animate-in fade-in-50 slide-in-from-bottom-8 duration-700">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-emerald-500 px-8 py-8 text-center">
                        <div class="space-y-2">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-white">
                                {{ $isFrench ? 'Calculer la Production du Mois' : 'Calculate Monthly Production' }}
                            </h2>
                            <p class="text-blue-100 text-lg">
                                {{ $isFrench ? 'Rapport automatisé de boulangerie' : 'Automated bakery report' }}
                            </p>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-8 py-10">
                        <div class="text-center space-y-8">
                            <!-- Description -->
                            <div class="space-y-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-emerald-100 rounded-xl flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-600 text-lg leading-relaxed max-w-md mx-auto">
                                    {{ $isFrench 
                                        ? "Cliquez sur le bouton ci-dessous pour calculer automatiquement la production de boulangerie du mois en cours." 
                                        : "Click the button below to automatically calculate this month's bakery production." }}
                                </p>
                            </div>

                            <!-- Form -->
                            <form method="POST" action="{{ route('repartiteur.calcul-production-boulangerie') }}" class="space-y-6">
                                @csrf
                                <div class="space-y-4">
                                    <button type="submit" 
                                            class="group relative w-full max-w-sm mx-auto bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3">
                                        <span class="text-2xl">⚡</span>
                                        <span class="text-lg">{{ $isFrench ? 'Calculer Maintenant' : 'Calculate Now' }}</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Loading indicator -->
                                    <div class="hidden" id="loadingIndicator">
                                        <div class="flex items-center justify-center space-x-2 text-slate-500">
                                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                            <span class="text-sm">{{ $isFrench ? 'Calcul en cours...' : 'Calculating...' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Info Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-8">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-blue-800">{{ $isFrench ? 'Temps réel' : 'Real-time' }}</p>
                                            <p class="text-xs text-blue-600">{{ $isFrench ? 'Données actualisées' : 'Updated data' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-4 rounded-xl border border-emerald-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-emerald-800">{{ $isFrench ? 'Précis' : 'Accurate' }}</p>
                                            <p class="text-xs text-emerald-600">{{ $isFrench ? 'Calculs fiables' : 'Reliable calculations' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-4 border-t border-slate-200">
                        <div class="text-center">
                            <p class="text-sm text-slate-500 font-medium">
                                {{ $isFrench ? '🥖 Boulangerie - Calcul Mensuel Automatisé' : '🥖 Bakery - Automated Monthly Calculation' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const button = form.querySelector('button[type="submit"]');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    form.addEventListener('submit', function() {
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        loadingIndicator.classList.remove('hidden');
    });
});
</script>
@endsection
