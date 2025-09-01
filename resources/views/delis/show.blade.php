@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Détails de l'incident" : "Incident Details" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
            </div>

            <!-- Header Card -->
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg p-6 mb-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-blue-600">{{ $deli->nom }}</h1>
                        <p class="text-gray-600 mt-2">
                            {{ $isFrench ? "Créé le" : "Created on" }} {{ $deli->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex space-x-3 w-full lg:w-auto">
                        <a href="{{ route('delis.edit', $deli) }}"
                           class="flex-1 lg:flex-none inline-flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95 lg:active:scale-100">
                            {{ $isFrench ? "Modifier" : "Edit" }}
                        </a>
                        <form action="{{ route('delis.destroy', $deli) }}" method="POST" class="flex-1 lg:flex-none">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 active:scale-95 lg:active:scale-100"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce deli ?' : 'Are you sure you want to delete this deli?' }}')">
                                {{ $isFrench ? "Supprimer" : "Delete" }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Information Card -->
                <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-blue-600 mb-4">
                        {{ $isFrench ? "Informations" : "Information" }}
                    </h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">
                                {{ $isFrench ? "Description" : "Description" }}
                            </h3>
                            <p class="text-gray-900">{{ $deli->description }}</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">
                                {{ $isFrench ? "Montant" : "Amount" }}
                            </h3>
                            <p class="text-xl lg:text-2xl font-bold text-green-700">
                                {{ number_format($deli->montant, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Employees Card -->
                <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-blue-600 mb-4">
                        {{ $isFrench ? "Employés concernés" : "Concerned Employees" }}
                    </h2>
                    <div class="space-y-4">
                        @foreach($deli->employes as $employe)
                            <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                                <div class="bg-blue-100 rounded-full p-2 mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $employe->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $isFrench ? "Date de l'incident :" : "Incident date:" }} 
                                        {{ $employe->pivot->date_incident }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        @if($deli->employes->isEmpty())
                            <div class="text-center py-8">
                                <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500">
                                    {{ $isFrench ? "Aucun employé concerné" : "No concerned employees" }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    button:active {
        transform: scale(0.95);
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    button:active, .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>
@endsection
