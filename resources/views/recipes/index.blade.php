@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $isFrench ? 'Gestion des Recettes' : 'Recipe Management' }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        {{ $isFrench ? 'Toutes vos recettes' : 'All your recipes' }}
                    </p>
                </div>
                <a href="{{ route('recipes.create') }}" class="bg-white bg-opacity-20 p-3 rounded-xl transform hover:scale-110 active:scale-95 transition-all duration-200">
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
                        {{ $isFrench ? 'Gestion des Recettes' : 'Recipe Management' }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        {{ $isFrench ? 'Gérer et organiser toutes vos recettes de production' : 'Manage and organize all your production recipes' }}
                    </p>
                </div>
                <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 transition duration-200 transform hover:scale-105">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ $isFrench ? 'Ajouter une Recette' : 'Add Recipe' }}
                </a>
            </div>
        </div>

        <!-- Mobile Recipe Cards -->
        <div class="md:hidden space-y-4">
            @forelse ($recipes as $recipe)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <a href="{{ route('recipes.show', $recipe) }}" class="text-lg font-bold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    {{ $recipe->name }}
                                </a>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                @if ($recipe->active)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $isFrench ? 'Actif' : 'Active' }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $isFrench ? 'Inactif' : 'Inactive' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-blue-50 p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-blue-600">{{ $isFrench ? 'Difficulté' : 'Difficulty' }}</p>
                                <p class="font-bold text-blue-700 text-sm">{{ $recipe->difficulty_level ?: ($isFrench ? 'Non défini' : 'Not defined') }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-xl text-center">
                                <p class="text-xs font-medium text-green-600">{{ $isFrench ? 'Temps Total' : 'Total Time' }}</p>
                                <p class="font-bold text-green-700 text-sm">
                                    @if ($recipe->total_time > 0)
                                        {{ $recipe->total_time }} min
                                    @else
                                        {{ $isFrench ? 'Non défini' : 'Not defined' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('recipes.show', $recipe) }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ $isFrench ? 'Voir' : 'View' }}
                            </a>
                            <a href="{{ route('recipes.edit', $recipe) }}" class="flex-1 bg-green-100 text-green-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>
                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette recette ?' : 'Are you sure you want to delete this recipe?' }}')">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune recette' : 'No recipes' }}
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{ $isFrench ? 'Aucune recette n\'a été créée pour le moment.' : 'No recipes have been created yet.' }}
                    </p>
                    <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl transform hover:scale-105 active:scale-95 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Créer votre première recette' : 'Create your first recipe' }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    @if ($recipes->isEmpty())
                        <div class="text-center py-12 animate-fade-in">
                            <svg class="h-20 w-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                                {{ $isFrench ? 'Aucune recette trouvée' : 'No recipes found' }}
                            </h3>
                            <p class="text-gray-500 mb-6">
                                {{ $isFrench ? 'Aucune recette n\'a été créée pour le moment.' : 'No recipes have been created yet.' }}
                            </p>
                            <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Créer votre première recette' : 'Create your first recipe' }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nom' : 'Name' }}
                                        </th>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                                        </th>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Difficulté' : 'Difficulty' }}
                                        </th>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Temps Total' : 'Total Time' }}
                                        </th>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Statut' : 'Status' }}
                                        </th>
                                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($recipes as $recipe)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4 px-4 whitespace-nowrap">
                                                <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                                    {{ $recipe->name }}
                                                </a>
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-sm">
                                                {{ $recipe->category ? $recipe->category->name : ($isFrench ? 'Non catégorisé' : 'Uncategorized') }}
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-sm">
                                                {{ $recipe->difficulty_level ?: ($isFrench ? 'Non défini' : 'Not defined') }}
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-sm">
                                                @if ($recipe->total_time > 0)
                                                    {{ $recipe->total_time }} min
                                                @else
                                                    {{ $isFrench ? 'Non défini' : 'Not defined' }}
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-sm">
                                                @if ($recipe->active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $isFrench ? 'Actif' : 'Active' }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $isFrench ? 'Inactif' : 'Inactive' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-all duration-200" title="{{ $isFrench ? 'Voir' : 'View' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('recipes.edit', $recipe) }}" class="text-indigo-600 hover:text-indigo-900 transform hover:scale-110 transition-all duration-200" title="{{ $isFrench ? 'Modifier' : 'Edit' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 transform hover:scale-110 transition-all duration-200"
                                                            onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette recette ?' : 'Are you sure you want to delete this recipe?' }}')" title="{{ $isFrench ? 'Supprimer' : 'Delete' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
