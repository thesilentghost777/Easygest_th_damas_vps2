@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-100 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-lg">
            <h2 class="text-lg font-bold text-blue-900">
                {{ $isFrench ? 'Instructions de Production' : 'Production Instructions' }}
            </h2>
            <p class="mt-2 text-blue-700">
                {{ $isFrench ? 'Sélectionnez une recette pour voir les instructions détaillées' : 'Select a recipe to view detailed instructions' }}
            </p>
        </div>
    </div>

    @if ($recipes->isEmpty())
        <div class="text-center py-8 lg:py-12">
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 animate-fade-in">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">
                    {{ $isFrench ? 'Aucune recette active n\'est disponible pour le moment.' : 'No active recipes are available at the moment.' }}
                </p>
            </div>
        </div>
    @else
        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @foreach ($recipes as $recipe)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mobile-card animate-fade-in transform transition-all duration-300 hover:scale-105 active:scale-95" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <a href="{{ route('recipes.show_instructions', $recipe) }}" class="block">
                    <div class="bg-gradient-to-r from-blue-100 to-green-50 p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $recipe->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Sans catégorie' : 'No category') }}</p>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between bg-blue-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">{{ $isFrench ? 'Temps total' : 'Total time' }}</span>
                            </div>
                            <span class="text-sm font-medium text-blue-600">{{ $recipe->total_time }} min</span>
                        </div>

                        <div class="flex items-center justify-between bg-green-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-sm text-gray-700">{{ $isFrench ? 'Étapes' : 'Steps' }}</span>
                            </div>
                            <span class="text-sm font-medium text-green-600">{{ $recipe->steps->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-sm text-gray-700">{{ $isFrench ? 'Difficulté' : 'Difficulty' }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non spécifiée' : 'Not specified') }}</span>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 text-center">
                            <div class="inline-flex items-center text-sm font-medium text-blue-600 bg-blue-50 px-4 py-2 rounded-full">
                                {{ $isFrench ? 'Voir les instructions' : 'View instructions' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-6">
            @foreach ($recipes as $recipe)
            <a href="{{ route('recipes.show_instructions', $recipe) }}" class="block group transform transition-all duration-300 hover:-translate-y-2 hover:scale-105">
                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="bg-gradient-to-r from-blue-100 to-green-50 p-6 group-hover:from-blue-200 group-hover:to-green-100 transition-colors duration-300">
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors duration-300">{{ $recipe->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Sans catégorie' : 'No category') }}</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $recipe->total_time }} {{ $isFrench ? 'minutes au total' : 'minutes total' }}</span>
                            </div>
                            
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>{{ $recipe->steps->count() }} {{ $isFrench ? 'étapes' : 'steps' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-sm mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>{{ $recipe->difficulty_level ?: ($isFrench ? 'Difficulté non spécifiée' : 'Difficulty not specified') }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100 text-right">
                            <span class="inline-flex items-center text-sm font-medium text-blue-600 group-hover:text-blue-800 transition-colors duration-300">
                                {{ $isFrench ? 'Voir les instructions' : 'View instructions' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
            touch-action: manipulation;
        }
        .mobile-card:active {
            transform: scale(0.98) !important;
        }
        /* Touch targets */
        a, button {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
