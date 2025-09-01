@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <br><br>
    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Stats Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right">
                        <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ $generalStats['total'] }}</p>
                        <p class="text-xs text-blue-800">{{ $isFrench ? 'Total' : 'Total' }}</p>
                    </div>

                    <div class="bg-green-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.1s">
                        <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $generalStats['actifs'] }}</p>
                        <p class="text-xs text-green-800">{{ $isFrench ? 'Actifs' : 'Active' }}</p>
                    </div>

                    <div class="bg-purple-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.2s">
                        <div class="bg-purple-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-purple-600">{{ number_format($generalStats['totalRemuneration'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-purple-800">{{ $isFrench ? 'Total FCFA' : 'Total FCFA' }}</p>
                    </div>

                    <div class="bg-yellow-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.3s">
                        <div class="bg-yellow-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-yellow-600">{{ number_format($generalStats['moyenneRemuneration'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-yellow-800">{{ $isFrench ? 'Moyenne FCFA' : 'Average FCFA' }}</p>
                    </div>
                </div>

           
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @include('buttons')
                
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $isFrench ? 'Statistiques des Stagiaires' : 'Intern Statistics' }}
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{ $isFrench ? 'Vue d\'ensemble et statistiques détaillées' : 'Overview and detailed statistics' }}
                    </p>
                </div>

                <!-- General Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isFrench ? 'Total Stagiaires' : 'Total Interns' }}
                        </h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $generalStats['total'] }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isFrench ? 'Stagiaires Actifs' : 'Active Interns' }}
                        </h3>
                        <p class="text-3xl font-bold text-green-600">{{ $generalStats['actifs'] }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isFrench ? 'Rémunération Totale' : 'Total Compensation' }}
                        </h3>
                        <p class="text-3xl font-bold text-purple-600">{{ number_format($generalStats['totalRemuneration'], 2) }} XAF</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isFrench ? 'Rémunération Moyenne' : 'Average Compensation' }}
                        </h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ number_format($generalStats['moyenneRemuneration'], 2) }} XAF</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
