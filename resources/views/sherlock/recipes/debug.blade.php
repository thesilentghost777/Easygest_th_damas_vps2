@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Sherlock Recette - Mode Debug</h1>
                @include('buttons')
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Cette page est destinée au débogage du service Sherlock Recette. Elle permet de tester les requêtes et d'analyser les réponses brutes.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Query Form -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Tester une requête</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('sherlock.recipes.debug') }}" method="GET">
                        <div class="mb-4">
                            <label for="query" class="block text-sm font-medium text-gray-700 mb-1">
                                Requête
                            </label>
                            <textarea id="query" name="query" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Entrez votre requête pour Sherlock Recette...">{{ $query }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Tester
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($result) || isset($error))
            <!-- Debug Results -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Résultats du debug</h2>
                </div>
                <div class="p-6">
                    @if(isset($error))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Erreur: {{ $error }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($result))
                    <div class="space-y-6">
                        <!-- Success Status -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($result['success'])
                                <span class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                @else
                                <span class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium">
                                    @if($result['success'])
                                    Requête traitée avec succès
                                    @else
                                    Échec du traitement de la requête
                                    @endif
                                </h3>
                                @if(!$result['success'])
                                <p class="text-red-700 mt-1">{{ $result['error'] }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Context Data -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Données de contexte</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 overflow-auto max-h-96">
                                <pre class="text-xs text-gray-700">{{ json_encode($result['context'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>

                        <!-- AI Response -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Réponse de l'IA</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="prose max-w-none text-gray-800">
                                    {!! nl2br(e($result['response'])) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
