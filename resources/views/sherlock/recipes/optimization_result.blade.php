@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Optimiser la recette' : 'Optimize recipe' }}: {{ $recipe->name }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Améliorez vos recettes avec l\'intelligence artificielle' : 'Improve your recipes with artificial intelligence' }}
                    </p>
                </div>
                
                <div class="animate-fade-in delay-100">
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-6 animate-scale-in">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        {{ $isFrench 
                            ? 'Sherlock Recette va analyser cette recette et proposer des optimisations pour réduire les coûts, améliorer l\'efficacité et la qualité.' 
                            : 'Sherlock Recipe will analyze this recipe and propose optimizations to reduce costs, improve efficiency and quality.' 
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Recipe Overview Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ $isFrench ? 'Aperçu de la recette' : 'Recipe overview' }}
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recipe Details -->
                    <div>
                        <h3 class="font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isFrench ? 'Détails' : 'Details' }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ $isFrench ? 'Catégorie' : 'Category' }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $recipe->category->name ?? ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}</dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ $isFrench ? 'Temps de préparation' : 'Preparation time' }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $recipe->preparation_time ?? '--' }} min</dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ $isFrench ? 'Temps de cuisson' : 'Cooking time' }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $recipe->cooking_time ?? '--' }} min</dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    {{ $isFrench ? 'Rendement' : 'Yield' }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $recipe->yield_quantity ?? '--' }}</dd>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Ingredients -->
                    <div>
                        <h3 class="font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $isFrench ? 'Ingrédients principaux' : 'Main ingredients' }}
                        </h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach ($recipe->ingredients->take(8) as $ingredient)
                                <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                    <span class="flex-shrink-0 w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                    <span class="text-sm">
                                        <span class="font-medium">{{ $ingredient->quantity }} {{ $ingredient->unit ?? $ingredient->ingredient->unit }}</span>
                                        <span class="text-gray-700 ml-1">{{ $ingredient->ingredient->name }}</span>
                                    </span>
                                </div>
                            @endforeach
                            @if($recipe->ingredients->count() > 8)
                                <div class="text-sm text-gray-500 pl-5">
                                    + {{ $recipe->ingredients->count() - 8 }} {{ $isFrench ? 'autres ingrédients' : 'other ingredients' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimization Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ $isFrench ? 'Optimisation de la recette' : 'Recipe optimization' }}
                </h2>
            </div>
            
            <form action="{{ route('sherlock.recipes.optimize', $recipe->id) }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <!-- Goals Selection -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.4s">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            {{ $isFrench ? 'Sélectionnez les objectifs d\'optimisation' : 'Select optimization goals' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cost Reduction -->
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="goals[]" value="cost" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            {{ $isFrench ? 'Réduction des coûts' : 'Cost reduction' }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench 
                                                ? 'Optimiser les quantités d\'ingrédients pour réduire les coûts sans compromettre la qualité' 
                                                : 'Optimize ingredient quantities to reduce costs without compromising quality' 
                                            }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Efficiency -->
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="goals[]" value="efficiency" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $isFrench ? 'Efficacité de production' : 'Production efficiency' }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench 
                                                ? 'Optimiser le processus de préparation pour gagner du temps' 
                                                : 'Optimize the preparation process to save time' 
                                            }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Waste Reduction -->
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="goals[]" value="waste" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            {{ $isFrench ? 'Réduction du gaspillage' : 'Waste reduction' }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench 
                                                ? 'Optimiser l\'utilisation des ingrédients pour minimiser les pertes' 
                                                : 'Optimize ingredient usage to minimize waste' 
                                            }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Quality Improvement -->
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="goals[]" value="quality" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                            {{ $isFrench ? 'Amélioration de la qualité' : 'Quality improvement' }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench 
                                                ? 'Suggestions pour améliorer le goût, la texture et la présentation' 
                                                : 'Suggestions to improve taste, texture and presentation' 
                                            }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Climate Adaptation -->
                            <div class="relative">
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="goals[]" value="climate" checked class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $isFrench ? 'Adaptation au climat' : 'Climate adaptation' }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $isFrench 
                                                ? 'Ajustements pour le climat tropical camerounais' 
                                                : 'Adjustments for the Cameroonian tropical climate' 
                                            }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Notes -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.5s">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ $isFrench ? 'Notes supplémentaires (facultatif)' : 'Additional notes (optional)' }}
                        </h3>
                        <textarea name="additional_notes" 
                                  rows="4" 
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200" 
                                  placeholder="{{ $isFrench ? 'Ajoutez des instructions ou contraintes spécifiques pour l\'optimisation...' : 'Add specific instructions or constraints for optimization...' }}"></textarea>
                        <p class="mt-2 text-xs text-gray-500">
                            {{ $isFrench 
                                ? 'Par exemple: \"Utiliser uniquement des ingrédients locaux\" ou \"Adapter pour production industrielle\"' 
                                : 'For example: \"Use only local ingredients\" or \"Adapt for industrial production\"' 
                            }}
                        </p>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-8 flex justify-end animate-fade-in-up" style="animation-delay: 0.6s">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ $isFrench ? 'Optimiser cette recette' : 'Optimize this recipe' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile-First Animations and Styles -->
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
    
    /* Mobile checkbox improvements */
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-top: 2px;
    }
    
    /* Mobile grid adjustments */
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    /* Touch-friendly labels */
    label {
        min-height: 44px;
        touch-action: manipulation;
    }
    
    /* Smooth scrolling for overflow areas */
    .overflow-y-auto {
        -webkit-overflow-scrolling: touch;
    }
}

/* Enhanced hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Loading state */
button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Focus improvements */
input:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}
</style>

<script>
// Mobile form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    const textarea = form.querySelector('textarea');
    
    // Add loading state on form submission
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $isFrench ? 'Optimisation...' : 'Optimizing...' }}
        `;
    });
    
    // Auto-resize textarea
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    
    // Add touch feedback for checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.classList.add('bg-blue-50', 'border-blue-300');
            } else {
                label.classList.remove('bg-blue-50', 'border-blue-300');
            }
        });
        
        // Initialize checked state
        if (checkbox.checked) {
            checkbox.closest('label').classList.add('bg-blue-50', 'border-blue-300');
        }
    });
});
</script>
@endsection
