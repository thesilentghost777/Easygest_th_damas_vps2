@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        @if(isset($error))
            <!-- Error State -->
            <div class="text-center animate-fade-in">
                @include('buttons')
                <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $isFrench ? 'Erreur' : 'Error' }}
                    </h2>
                    <p class="text-gray-600">{{ $error }}</p>
                </div>
            </div>
        @else
            <!-- Success State -->
            <div class="mb-6">
                @include('partials._header')
            </div>

            <div class="space-y-8">
                @if(isset($data))
                    <!-- Data Display Card -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-scale-in">
                        <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ $isFrench ? 'Résultats de la requête' : 'Query Results' }}
                            </h2>
                        </div>
                        
                        <div class="p-6">
                            <!-- Mobile-Responsive Data Display -->
                            <div class="animate-fade-in-up">
                                @include("components.data-types.$dataType", ['data' => $data])
                            </div>
                        </div>
                    </div>

                    <!-- Metadata Card -->
                    <div class="animate-fade-in-up delay-200">
                        <x-metadata :dataType="$dataType" :data="$data" />
                    </div>
                @else
                    <!-- No Data State -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center animate-fade-in">
                        <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-yellow-800 mb-2">
                            {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
                        </h3>
                        <p class="text-yellow-700">
                            {{ $isFrench ? 'Votre requête n\'a retourné aucun résultat.' : 'Your query returned no results.' }}
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Load required scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-200 {
    animation-delay: 0.2s;
}

/* Mobile-specific responsive adjustments */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Make tables scroll horizontally on mobile */
    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Responsive chart containers */
    canvas {
        max-width: 100% !important;
        height: auto !important;
    }
    
    /* Stack cards vertically on mobile */
    .grid {
        grid-template-columns: 1fr !important;
    }
}

/* Charts and interactive elements */
.chart-container {
    position: relative;
    min-height: 300px;
    max-height: 500px;
}

@media (max-width: 640px) {
    .chart-container {
        min-height: 250px;
        max-height: 400px;
    }
}

/* Loading states for dynamic content */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endsection
