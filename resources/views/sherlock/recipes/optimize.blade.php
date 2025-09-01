@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Optimisation' : 'Optimization' }}: {{ $recipe['name'] }}
                    </h1>
                    <div class="flex flex-wrap items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Optimisation générée' : 'Optimization generated' }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 animate-fade-in delay-100">
                    <button onclick="window.print()" 
                            class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>{{ $isFrench ? 'Imprimer' : 'Print' }}</span>
                    </button>
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Recipe Info Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 animate-scale-in">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white">{{ $recipe['name'] }}</h2>
                <p class="text-indigo-100">{{ $recipe['category'] }} • {{ $recipe['yield_quantity'] ?? '' }}</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Current Ingredients -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $isFrench ? 'Ingrédients actuels' : 'Current ingredients' }}
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($recipe['ingredients'] as $ingredient)
                                <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                    <span class="flex-shrink-0 w-2 h-2 bg-indigo-400 rounded-full mr-3"></span>
                                    <span class="text-sm">
                                        <span class="font-medium">{{ $ingredient['quantity'] }} {{ $ingredient['unit'] }}</span>
                                        <span class="text-gray-700 ml-1">{{ $ingredient['name'] }}</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Main Steps -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            {{ $isFrench ? 'Étapes principales' : 'Main steps' }}
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($recipe['steps'] as $key => $step)
                                <div class="flex items-start p-2 bg-gray-50 rounded-lg">
                                    <span class="flex-shrink-0 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full mr-3 mt-0.5">{{ $key + 1 }}</span>
                                    <span class="text-sm text-gray-700">{{ $step['instruction'] }}</span>
                                </div>
                                @if($key >= 4 && count($recipe['steps']) > 6)
                                    @if($key == 4)
                                        <div class="text-sm text-gray-500 italic pl-8">
                                            ... {{ count($recipe['steps']) - 5 }} {{ $isFrench ? 'étapes supplémentaires' : 'additional steps' }} ...
                                        </div>
                                    @break
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimization Results Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-medium text-white">
                        {{ $isFrench ? 'Optimisations proposées' : 'Proposed optimizations' }}
                    </h2>
                </div>
            </div>
            
            <div class="p-6">
                <div class="optimization-content prose prose-lg max-w-none text-gray-800">
                    {!! nl2br(e($optimization)) !!}
                </div>
            </div>
            
            <div class="bg-green-50 px-6 py-4 border-t border-green-200">
                <h3 class="text-sm font-medium text-green-900 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    {{ $isFrench ? 'Recommandation de Sherlock Recette' : 'Sherlock Recipe Recommendation' }}
                </h3>
                <p class="mt-1 text-sm text-green-700">
                    {{ $isFrench 
                        ? 'Ces recommandations sont basées sur l\'analyse des données de production historiques et les meilleures pratiques en boulangerie-pâtisserie. Vous pouvez les appliquer progressivement et observer les résultats.' 
                        : 'These recommendations are based on analysis of historical production data and best practices in bakery-pastry. You can apply them gradually and observe the results.' 
                    }}
                </p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-8 flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 animate-fade-in-up" style="animation-delay: 0.3s">
            <a href="{{ route('recipes.edit', $recipe['id']) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ $isFrench ? 'Modifier la recette avec ces suggestions' : 'Edit recipe with these suggestions' }}
            </a>
        </div>
    </div>
</div>

<!-- Mobile-First Styles -->
<style>
/* Print Styles */
@media print {
    nav, header, footer, .hidden-print, button {
        display: none !important;
    }
    
    body {
        background: white !important;
        font-size: 12pt;
        color: black !important;
    }
    
    .optimization-content {
        page-break-inside: auto;
    }
    
    h1, h2, h3 {
        page-break-after: avoid;
        color: black !important;
    }
    
    .bg-white {
        background: white !important;
        box-shadow: none !important;
    }
}

/* Screen Animations */
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
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-100 { animation-delay: 0.1s; }

/* Optimization Content Styling */
.optimization-content h1,
.optimization-content h2,
.optimization-content h3 {
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: 600;
}

.optimization-content h1 {
    font-size: 1.75rem;
    color: #059669;
    border-bottom: 2px solid #10b981;
    padding-bottom: 0.5rem;
}

.optimization-content h2 {
    font-size: 1.5rem;
    color: #047857;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.25rem;
}

.optimization-content h3 {
    font-size: 1.25rem;
    color: #065f46;
}

.optimization-content ul,
.optimization-content ol {
    padding-left: 2rem;
    margin-bottom: 1rem;
}

.optimization-content ul {
    list-style-type: disc;
}

.optimization-content ol {
    list-style-type: decimal;
}

.optimization-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.optimization-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
    text-align: justify;
}

.optimization-content strong {
    font-weight: 600;
    color: #374151;
}

.optimization-content em {
    font-style: italic;
    color: #6b7280;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .optimization-content {
        font-size: 0.95rem;
    }
    
    .optimization-content h1 {
        font-size: 1.5rem;
    }
    
    .optimization-content h2 {
        font-size: 1.25rem;
    }
    
    .optimization-content h3 {
        font-size: 1.125rem;
    }
    
    /* Mobile overflow improvements */
    .overflow-y-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Touch-friendly buttons */
    button, a {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Enhanced hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}
</style>
@endsection
