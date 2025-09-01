@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Sherlock Recette' : 'Sherlock Recipe' }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Votre assistant intelligent de boulangerie-pâtisserie' : 'Your intelligent bakery-pastry assistant' }}
                    </p>
                </div>
                
                <div class="animate-fade-in delay-100">
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 md:p-8 mb-8 text-white animate-scale-in">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl md:text-2xl font-bold mb-2">
                        {{ $isFrench ? 'Intelligence artificielle pour votre boulangerie' : 'Artificial intelligence for your bakery' }}
                    </h2>
                    <p class="text-blue-100 text-sm md:text-base">
                        {{ $isFrench 
                            ? 'Sherlock Recette analyse vos données de production, optimise vos recettes et vous aide à améliorer la qualité de vos produits tout en réduisant les coûts.' 
                            : 'Sherlock Recipe analyzes your production data, optimizes your recipes and helps you improve product quality while reducing costs.' 
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Ask Question Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $isFrench ? 'Posez une question' : 'Ask a question' }}
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('sherlock.recipes.analyze') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <textarea name="query" 
                                      rows="4" 
                                      class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200" 
                                      placeholder="{{ $isFrench ? 'Ex: Comment améliorer la texture de mes croissants?' : 'Ex: How to improve the texture of my croissants?' }}">{{ old('query') }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                {{ $isFrench ? 'Analyser' : 'Analyze' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Generate Recipe Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ $isFrench ? 'Générer une recette' : 'Generate a recipe' }}
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4 text-sm md:text-base">
                        {{ $isFrench 
                            ? 'Créez une nouvelle recette adaptée à votre boulangerie avec des quantités précises et des instructions détaillées.' 
                            : 'Create a new recipe adapted to your bakery with precise quantities and detailed instructions.' 
                        }}
                    </p>
                    <div class="flex justify-end">
                        <a href="{{ route('sherlock.recipes.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ $isFrench ? 'Créer une recette' : 'Create a recipe' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 text-blue-600 mb-4 mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">
                    {{ $isFrench ? 'Recettes optimisées' : 'Optimized recipes' }}
                </h3>
                <p class="text-gray-600 text-sm text-center">
                    {{ $isFrench 
                        ? 'Obtenez des recettes précises avec des quantités adaptées au climat camerounais et vos équipements.' 
                        : 'Get precise recipes with quantities adapted to the Cameroonian climate and your equipment.' 
                    }}
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 animate-fade-in-up" style="animation-delay: 0.5s">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 text-green-600 mb-4 mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">
                    {{ $isFrench ? 'Analyse des coûts' : 'Cost analysis' }}
                </h3>
                <p class="text-gray-600 text-sm text-center">
                    {{ $isFrench 
                        ? 'Réduisez vos coûts de production en optimisant l\'utilisation des matières premières.' 
                        : 'Reduce your production costs by optimizing the use of raw materials.' 
                    }}
                </p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 animate-fade-in-up" style="animation-delay: 0.6s">
                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 text-purple-600 mb-4 mx-auto">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">
                    {{ $isFrench ? 'Expertise en pâtisserie' : 'Pastry expertise' }}
                </h3>
                <p class="text-gray-600 text-sm text-center">
                    {{ $isFrench 
                        ? 'Accédez à des conseils d\'experts adaptés aux conditions de travail camerounaises.' 
                        : 'Access expert advice adapted to Cameroonian working conditions.' 
                    }}
                </p>
            </div>
        </div>

        <!-- Example Queries -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.7s">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $isFrench ? 'Exemples de questions' : 'Example questions' }}
            </h3>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <ul class="space-y-3">
                    @foreach ($exampleQueries as $query)
                        <li>
                            <form action="{{ route('sherlock.recipes.analyze') }}" method="POST" class="inline-block w-full">
                                @csrf
                                <input type="hidden" name="query" value="{{ $query }}">
                                <button type="submit" 
                                        class="text-left w-full p-3 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200 text-sm">
                                    <span class="flex items-start">
                                        <span class="text-blue-400 mr-2 mt-0.5">→</span>
                                        <span>"{{ $query }}"</span>
                                    </span>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Existing Recipes Optimization -->
        @if($recipes->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.8s">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ $isFrench ? 'Optimiser une recette existante' : 'Optimize an existing recipe' }}
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Nom' : 'Name' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Catégorie' : 'Category' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recipes->take(5) as $recipe)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $recipe->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $recipe->category->name ?? ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('sherlock.recipes.optimize.form', $recipe->id) }}" 
                                   class="text-purple-600 hover:text-purple-900 transition-colors duration-200">
                                    {{ $isFrench ? 'Optimiser' : 'Optimize' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($recipes->count() > 5)
            <div class="bg-gray-50 px-6 py-4 text-right">
                <a href="{{ route('recipes.index') }}" 
                   class="text-sm text-purple-600 hover:text-purple-800 transition-colors duration-200">
                    {{ $isFrench ? 'Voir toutes les recettes' : 'View all recipes' }} ({{ $recipes->count() }})
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Mobile-First CSS Animations -->
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
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-100 { animation-delay: 0.1s; }

/* Mobile Optimizations */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Mobile card adjustments */
    .grid > * {
        transform: translateZ(0); /* Hardware acceleration */
    }
    
    /* Mobile table scroll */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Touch-friendly buttons */
    button, a {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Smooth hover animations */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Loading spinner */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

<script>
// Mobile enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
    
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $isFrench ? 'Traitement...' : 'Processing...' }}
                `;
            }
        });
    });
});
</script>
@endsection
