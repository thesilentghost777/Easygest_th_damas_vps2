@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Générer une nouvelle recette' : 'Generate a new recipe' }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Créez des recettes professionnelles adaptées aux conditions camerounaises' : 'Create professional recipes adapted to Cameroonian conditions' }}
                    </p>
                </div>
                
                <div class="animate-fade-in delay-100">
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-4 mb-6 animate-scale-in">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-700">
                        {{ $isFrench 
                            ? 'Sherlock Recette va générer une recette professionnelle adaptée aux conditions camerounaises avec des quantités précises et des instructions détaillées.' 
                            : 'Sherlock Recipe will generate a professional recipe adapted to Cameroonian conditions with precise quantities and detailed instructions.' 
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-scale-in delay-200">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ $isFrench ? 'Nouvelle recette' : 'New recipe' }}
                </h2>
            </div>
            
            <form action="{{ route('sherlock.recipes.generate') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Product Name -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Nom du produit à créer' : 'Product name to create' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200 hover:border-blue-300" 
                                   placeholder="{{ $isFrench ? 'Ex: Croissant au chocolat' : 'Ex: Chocolate croissant' }}" 
                                   value="{{ old('name') }}" 
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $isFrench ? 'Soyez précis dans le nom du produit que vous souhaitez créer.' : 'Be specific about the product name you want to create.' }}
                        </p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Category -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.4s">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category" 
                                    name="category" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200 hover:border-blue-300">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                                <option value="Viennoiserie" {{ old('category') == 'Viennoiserie' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Viennoiserie' : 'Pastries' }}
                                </option>
                                <option value="Pain" {{ old('category') == 'Pain' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Pain' : 'Bread' }}
                                </option>
                                <option value="Pâtisserie" {{ old('category') == 'Pâtisserie' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Pâtisserie' : 'Pastry' }}
                                </option>
                                <option value="Glace" {{ old('category') == 'Glace' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Glace' : 'Ice cream' }}
                                </option>
                                <option value="Gâteau" {{ old('category') == 'Gâteau' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Gâteau' : 'Cake' }}
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Quantity -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.5s">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Quantité à produire' : 'Quantity to produce' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200 hover:border-blue-300" 
                                   placeholder="{{ $isFrench ? 'Ex: 20 pièces, 2 kg, etc.' : 'Ex: 20 pieces, 2 kg, etc.' }}" 
                                   value="{{ old('quantity') }}" 
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2M7 4h6M7 4l-2 14h8l-2-14M9 9v6m2-6v6"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $isFrench ? 'Indiquez la quantité que vous souhaitez produire.' : 'Indicate the quantity you want to produce.' }}
                        </p>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Special Requirements -->
                <div class="animate-fade-in-up" style="animation-delay: 0.6s">
                    <label for="specific_requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Exigences spécifiques (facultatif)' : 'Specific requirements (optional)' }}
                    </label>
                    <div class="relative">
                        <textarea id="specific_requirements" 
                                  name="specific_requirements" 
                                  rows="4" 
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200 hover:border-blue-300" 
                                  placeholder="{{ $isFrench ? 'Ex: Sans gluten, Préparation rapide, Utiliser des ingrédients locaux...' : 'Ex: Gluten-free, Quick preparation, Use local ingredients...' }}">{{ old('specific_requirements') }}</textarea>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $isFrench ? 'Précisez toute exigence particulière pour cette recette.' : 'Specify any particular requirements for this recipe.' }}
                    </p>
                    @error('specific_requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6 animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3">
                        <button type="button" 
                                onclick="window.history.back()" 
                                class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </button>
                        <button type="submit" 
                                class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            {{ $isFrench ? 'Générer la recette' : 'Generate recipe' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Animations CSS -->
<style>
/* Mobile-First Animations */
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
.delay-200 { animation-delay: 0.2s; }

/* Mobile Form Enhancements */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Touch-friendly form inputs */
    input, select, textarea {
        min-height: 44px;
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    /* Improved mobile spacing */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    
    /* Mobile button stack */
    .md\:flex-row {
        flex-direction: column;
    }
    
    .md\:space-x-3 > * + * {
        margin-left: 0;
        margin-top: 0.75rem;
    }
}

/* Loading state for form submission */
button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Focus states for accessibility */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
}

/* Smooth hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}
</style>

<script>
// Mobile form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Add loading state on form submission
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $isFrench ? 'Génération...' : 'Generating...' }}
        `;
    });
    
    // Auto-resize textarea
    const textarea = document.getElementById('specific_requirements');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
});
</script>
@endsection
