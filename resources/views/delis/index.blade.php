@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3  top-0 z-40">
        @include('buttons')
        <div class="flex justify-between items-center mt-2">
            <h1 class="text-lg font-semibold text-gray-900">
                {{ $isFrench ? "Liste des Incidents" : "Incidents List" }}
            </h1>
            <a href="{{ route('delis.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-all duration-200 active:scale-95 shadow-lg">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ $isFrench ? "Nouveau" : "New" }}
            </a>
        </div>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
            <div class="flex justify-between items-center mt-4">
                <h1 class="text-3xl font-bold text-blue-600">
                    {{ $isFrench ? "Liste des Incidents" : "Incidents List" }}
                </h1>
                <a href="{{ route('delis.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ $isFrench ? "Nouvel incident" : "New Incident" }}
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg lg:mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @foreach($delis as $deli)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transform transition-all duration-200 active:scale-98">
                <div class="p-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $deli->nom }}</h3>
                                <p class="text-sm text-gray-600">{{ number_format($deli->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 line-clamp-2">{{ $deli->description }}</p>
                    </div>

                    <!-- Employees -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">{{ $isFrench ? "Employés concernés:" : "Concerned employees:" }}</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($deli->employes as $employe)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $employe->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('delis.edit', $deli) }}" 
                           class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors duration-200 active:scale-95">
                            {{ $isFrench ? "Modifier" : "Edit" }}
                        </a>
                        <form action="{{ route('delis.destroy', $deli) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-50 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors duration-200 active:scale-95"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce deli ?' : 'Are you sure you want to delete this deli?' }}')">
                                {{ $isFrench ? "Supprimer" : "Delete" }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            @if($delis->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900 mb-2">{{ $isFrench ? "Aucun incident" : "No incidents" }}</h3>
                <p class="text-gray-600 text-sm">{{ $isFrench ? "Aucun incident n'a été enregistré" : "No incidents have been recorded" }}</p>
            </div>
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">{{ $isFrench ? "Nom" : "Name" }}</th>
                            <th class="py-3 px-4 text-left">{{ $isFrench ? "Description" : "Description" }}</th>
                            <th class="py-3 px-4 text-left">{{ $isFrench ? "Sanction(Montant)" : "Penalty(Amount)" }}</th>
                            <th class="py-3 px-4 text-left">{{ $isFrench ? "Employés concernés" : "Concerned Employees" }}</th>
                            <th class="py-3 px-4 text-left">{{ $isFrench ? "Actions" : "Actions" }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($delis as $deli)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">{{ $deli->nom }}</td>
                            <td class="py-3 px-4">{{ $deli->description }}</td>
                            <td class="py-3 px-4">{{ number_format($deli->montant, 0, ',', ' ') }} FCFA</td>
                            <td class="py-3 px-4">
                                @foreach($deli->employes as $employe)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm mr-1 mb-1">
                                        {{ $employe->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('delis.edit', $deli) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm transition-colors">
                                        {{ $isFrench ? "Modifier" : "Edit" }}
                                    </a>
                                    <form action="{{ route('delis.destroy', $deli) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm transition-colors"
                                                onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce deli ?' : 'Are you sure you want to delete this deli?' }}')">
                                            {{ $isFrench ? "Supprimer" : "Delete" }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
    
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    button:active, .active\:scale-95:active, .active\:scale-98:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>
@endsection
