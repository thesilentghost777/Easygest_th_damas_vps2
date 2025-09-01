@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <div class="flex justify-between items-center mt-2">
            <h1 class="text-lg font-semibold text-gray-900">
                {{ $isFrench ? "Gestion des Salaires" : "Salary Management" }}
            </h1>
            <a href="{{ route('salaires.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-all duration-200 active:scale-95 shadow-lg">
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
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $isFrench ? "Gestion des Salaires" : "Salary Management" }}
                </h1>
                <a href="{{ route('salaires.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    {{ $isFrench ? "Nouveau Salaire" : "New Salary" }}
                </a>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @foreach($salaires as $salaire)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transform transition-all duration-200 active:scale-98">
                <div class="p-4">
                    <!-- Employee Info -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $salaire->employe->name }}</h3>
                                <p class="text-sm text-gray-600">{{ number_format($salaire->somme, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <!-- Status Badge -->
                        @if($salaire->retrait_valide)
                            <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                {{ $isFrench ? "Payé" : "Paid" }}
                            </span>
                        @elseif($salaire->retrait_demande)
                            <span class="px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                {{ $isFrench ? "En attente" : "Pending" }}
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                {{ $isFrench ? "Non retiré" : "Not withdrawn" }}
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('salaires.fiche-paie', $salaire->id_employe) }}" 
                           class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors duration-200 active:scale-95">
                            {{ $isFrench ? "Fiche de paie" : "Payslip" }}
                        </a>
                        <a href="{{ route('salaires.edit', $salaire) }}" 
                           class="flex-1 text-center bg-yellow-50 text-yellow-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-yellow-100 transition-colors duration-200 active:scale-95">
                            {{ $isFrench ? "Modifier" : "Edit" }}
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <form action="{{ route('salaires.destroy', $salaire) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-50 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors duration-200 active:scale-95"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce salaire ?' : 'Are you sure you want to delete this salary?' }}')">
                                {{ $isFrench ? "Supprimer" : "Delete" }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            @if($salaires->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900 mb-2">{{ $isFrench ? "Aucun salaire" : "No salaries" }}</h3>
                <p class="text-gray-600 text-sm">{{ $isFrench ? "Aucun salaire n'a été enregistré" : "No salaries have been recorded" }}</p>
            </div>
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? "Employé" : "Employee" }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? "Salaire" : "Salary" }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? "Statut" : "Status" }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? "Actions" : "Actions" }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($salaires as $salaire)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $salaire->employe->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($salaire->somme, 2) }} FCFA</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($salaire->retrait_valide)
                                <span class="px-2 py-1 text-sm text-green-800 bg-green-100 rounded-full">
                                    {{ $isFrench ? "Payé" : "Paid" }}
                                </span>
                            @elseif($salaire->retrait_demande)
                                <span class="px-2 py-1 text-sm text-yellow-800 bg-yellow-100 rounded-full">
                                    {{ $isFrench ? "En attente" : "Pending" }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-sm text-gray-800 bg-gray-100 rounded-full">
                                    {{ $isFrench ? "Non retiré" : "Not withdrawn" }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <a href="{{ route('salaires.fiche-paie', $salaire->id_employe) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $isFrench ? "Fiche de paie" : "Payslip" }}
                            </a>
                            <a href="{{ route('salaires.edit', $salaire) }}" class="text-yellow-600 hover:text-yellow-900">
                                {{ $isFrench ? "Modifier" : "Edit" }}
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('salaires.destroy', $salaire) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr ?' : 'Are you sure?' }}')">
                                    {{ $isFrench ? "Supprimer" : "Delete" }}
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    
    /* Smooth animations for mobile */
    .transition-all {
        transition: all 0.2s ease-in-out;
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
