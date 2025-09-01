@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Nouveau Stagiaire' : 'New Intern' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Ajouter un stagiaire' : 'Add an intern' }}
            </p>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Notice -->
                <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500 mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-amber-800 font-semibold">
                                {{ $isFrench ? 'Formulaire complexe' : 'Complex Form' }}
                            </h3>
                            <p class="text-amber-700 text-sm mt-1">
                                {{ $isFrench ? 'Ce formulaire détaillé est optimisé pour les écrans d\'ordinateur. Veuillez utiliser un PC pour une meilleure expérience.' : 'This detailed form is optimized for computer screens. Please use a PC for a better experience.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Quick Form Preview -->
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500">
                        <h3 class="text-blue-800 font-semibold mb-3">
                            {{ $isFrench ? 'Informations requises' : 'Required Information' }}
                        </h3>
                        <div class="space-y-2 text-sm text-blue-700">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $isFrench ? 'Informations personnelles (nom, prénom, email)' : 'Personal information (name, surname, email)' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $isFrench ? 'Informations académiques (école, filière, niveau)' : 'Academic information (school, field, level)' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8a1 1 0 011-1h3z"/>
                                </svg>
                                {{ $isFrench ? 'Détails du stage (type, dates, secteur)' : 'Internship details (type, dates, sector)' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $isFrench ? 'Nature du travail et missions' : 'Work nature and assignments' }}
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-2xl p-4 border-l-4 border-green-500">
                        <h3 class="text-green-800 font-semibold mb-3">
                            {{ $isFrench ? 'Actions disponibles sur PC' : 'Actions available on PC' }}
                        </h3>
                        <div class="space-y-2 text-sm text-green-700">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Créer un nouveau dossier stagiaire' : 'Create new intern file' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                                {{ $isFrench ? 'Validation et enregistrement automatique' : 'Validation and automatic saving' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8a1 1 0 011-1h3z"/>
                                </svg>
                                {{ $isFrench ? 'Gestion des documents et contrats' : 'Document and contract management' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @include('buttons')
                    
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">
                            {{ $isFrench ? 'Ajouter un stagiaire' : 'Add an intern' }}
                        </h2>

                        <form action="{{ route('stagiaires.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Nom' : 'Last Name' }}
                                    </label>
                                    <input type="text" name="nom" id="nom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Prénom' : 'First Name' }}
                                    </label>
                                    <input type="text" name="prenom" id="prenom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Téléphone' : 'Phone' }}
                                    </label>
                                    <input type="tel" name="telephone" id="telephone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="ecole" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'École' : 'School' }}
                                    </label>
                                    <input type="text" name="ecole" id="ecole" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="niveau_etude" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Niveau d\'étude' : 'Study Level' }}
                                    </label>
                                    <input type="text" name="niveau_etude" id="niveau_etude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="filiere" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Filière' : 'Field of Study' }}
                                    </label>
                                    <input type="text" name="filiere" id="filiere" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="type_stage" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Type de stage' : 'Internship Type' }}
                                    </label>
                                    <select name="type_stage" id="type_stage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="academique">{{ $isFrench ? 'Académique' : 'Academic' }}</option>
                                        <option value="professionnel">{{ $isFrench ? 'Professionnel' : 'Professional' }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="date_debut" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Date de début' : 'Start Date' }}
                                    </label>
                                    <input type="date" name="date_debut" id="date_debut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>

                                <div>
                                    <label for="date_fin" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Date de fin' : 'End Date' }}
                                    </label>
                                    <input type="date" name="date_fin" id="date_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                            </div>

                            <div>
                                <label for="departement" class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? 'Secteur' : 'Sector' }}
                                </label>
                                <select name="departement" id="departement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="production">{{ $isFrench ? 'Production' : 'Production' }}</option>
                                    <option value="alimentation">{{ $isFrench ? 'Alimentation' : 'General store' }}</option>
                                    <option value="administration">{{ $isFrench ? 'Administration' : 'Administration' }}</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="nature_travail" class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? 'Nature du travail' : 'Work Nature' }}
                                </label>
                                <textarea name="nature_travail" id="nature_travail" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                            </div>

                            <div class="flex justify-end mt-6">
                                <a href="{{ route('stagiaires.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ $isFrench ? 'Enregistrer' : 'Save' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
}
</style>
@endsection
