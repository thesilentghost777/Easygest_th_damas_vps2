@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')

        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $isFrench ? 'Ingrédients' : 'Ingredients' }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        {{ $isFrench ? 'Gérer les ingrédients' : 'Manage ingredients' }}
                    </p>
                </div>
                <a href="{{ route('recipe.ingredients.create') }}" class="bg-white bg-opacity-20 p-3 rounded-xl transform hover:scale-110 active:scale-95 transition-all duration-200">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Gestion des Ingrédients' : 'Ingredient Management' }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        {{ $isFrench ? 'Gérer et organiser vos ingrédients de recettes' : 'Manage and organize your recipe ingredients' }}
                    </p>
                </div>
                <a href="{{ route('recipe.ingredients.create') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 transition duration-200 transform hover:scale-105">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ $isFrench ? 'Ajouter un Ingrédient' : 'Add Ingredient' }}
                </a>
            </div>
        </div>

        <!-- Mobile Ingredients List -->
        <div class="md:hidden space-y-4">
            @forelse ($ingredients as $ingredient)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $ingredient->name }}</h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $isFrench ? 'Unité:' : 'Unit:' }} {{ $ingredient->unit ?: ($isFrench ? 'Non définie' : 'Not defined') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                    {{ $ingredient->unit ?: '-' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('recipe.ingredients.edit', $ingredient) }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>
                            <form action="{{ route('recipe.ingredients.destroy', $ingredient) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet ingrédient ?' : 'Are you sure you want to delete this ingredient?' }}')">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fade-in">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucun ingrédient' : 'No ingredients' }}
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{ $isFrench ? 'Aucun ingrédient n\'a été créé pour le moment.' : 'No ingredients have been created yet.' }}
                    </p>
                    <a href="{{ route('recipe.ingredients.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl transform hover:scale-105 active:scale-95 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Créer votre premier ingrédient' : 'Create your first ingredient' }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    @if ($ingredients->isEmpty())
                        <div class="text-center py-12 animate-fade-in">
                            <svg class="h-20 w-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                                {{ $isFrench ? 'Aucun ingrédient trouvé' : 'No ingredients found' }}
                            </h3>
                            <p class="text-gray-500 mb-6">
                                {{ $isFrench ? 'Aucun ingrédient n\'a été créé pour le moment.' : 'No ingredients have been created yet.' }}
                            </p>
                            <a href="{{ route('recipe.ingredients.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Créer votre premier ingrédient' : 'Create your first ingredient' }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nom' : 'Name' }}
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Unité' : 'Unit' }}
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($ingredients as $ingredient)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $ingredient->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                                    {{ $ingredient->unit ?: '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('recipe.ingredients.edit', $ingredient) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-all duration-200">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('recipe.ingredients.destroy', $ingredient) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-all duration-200"
                                                            onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet ingrédient ?' : 'Are you sure you want to delete this ingredient?' }}')">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
