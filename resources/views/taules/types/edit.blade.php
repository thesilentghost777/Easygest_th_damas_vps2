@extends('layouts.app')

@section('content')

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $isFrench ? 'Modifier le Type de Taule' : 'Edit Taule Type' }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('buttons')

        <!-- Mobile restriction notice -->
        <div class="block md:hidden bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">
                        {{ $isFrench ? 'Fonctionnalité non disponible sur mobile' : 'Feature not available on mobile' }}
                    </p>
                    <p class="text-sm">
                        {{ $isFrench 
                            ? 'Cette fonctionnalité est uniquement disponible sur PC. Veuillez utiliser un ordinateur pour accéder à cette page.' 
                            : 'This feature is only available on PC. Please use a computer to access this page.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main content - hidden on mobile -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('taules.types.update', $type) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench ? 'Nom du type de taule:' : 'Taule type name:' }}
                            </label>
                            <input type="text" name="nom" id="nom" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="{{ old('nom', $type->nom) }}">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench ? 'Description:' : 'Description:' }}
                            </label>
                            <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $type->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="formule_farine" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench 
                                    ? 'Formule pour la farine (utilisez \'n\' pour le nombre de taules):' 
                                    : 'Formula for flour (use \'n\' for number of taules):' }}
                            </label>
                            <input type="text" name="formule_farine" id="formule_farine" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="{{ $isFrench ? 'ex: 0.5 * n' : 'e.g: 0.5 * n' }}" value="{{ old('formule_farine', $type->formule_farine) }}">
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $isFrench 
                                    ? 'Exemple: Pour 0.5kg de farine par taule, entrez "0.5 * n"' 
                                    : 'Example: For 0.5kg of flour per taule, enter "0.5 * n"' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="formule_eau" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench ? 'Formule pour l\'eau:' : 'Formula for water:' }}
                            </label>
                            <input type="text" name="formule_eau" id="formule_eau" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="{{ $isFrench ? 'ex: 0.3 * n' : 'e.g: 0.3 * n' }}" value="{{ old('formule_eau', $type->formule_eau) }}">
                        </div>

                        <div class="mb-4">
                            <label for="formule_huile" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench ? 'Formule pour l\'huile:' : 'Formula for oil:' }}
                            </label>
                            <input type="text" name="formule_huile" id="formule_huile" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="{{ $isFrench ? 'ex: 0.05 * n' : 'e.g: 0.05 * n' }}" value="{{ old('formule_huile', $type->formule_huile) }}">
                        </div>

                        <div class="mb-4">
                            <label for="formule_autres" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ $isFrench ? 'Formule pour les autres ingrédients:' : 'Formula for other ingredients:' }}
                            </label>
                            <input type="text" name="formule_autres" id="formule_autres" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="{{ $isFrench ? 'ex: 0.1 * n' : 'e.g: 0.1 * n' }}" value="{{ old('formule_autres', $type->formule_autres) }}">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                            </button>
                            <a href="{{ route('taules.types.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection