@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('buttons')
    <div class="flex justify-between items-center mb-6 mobile:flex-col mobile:items-stretch mobile:gap-4">
        <h1 class="text-2xl font-bold text-gray-800 mobile:text-xl mobile:font-semibold mobile:text-center mobile:animate-bounce">
            {{ $isFrench ? 'Gestion des assignations de matières' : 'Raw Materials Assignments Management' }}
        </h1>
        <a href="{{ route('assignations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mobile:w-full mobile:text-center mobile:py-2 mobile:shadow-md mobile:transform mobile:active:scale-95 mobile:transition-transform">
            {{ $isFrench ? 'Nouvelle assignation' : 'New Assignment' }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 mobile:animate-pulse" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mobile:shadow-lg mobile:border mobile:border-gray-200">
        <!-- Desktop Table -->
        <table class="min-w-full divide-y divide-gray-200 mobile:hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Producteur' : 'Producer' }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Matière' : 'Material' }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Quantité assignée' : 'Assigned Qty' }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Quantité restante' : 'Remaining Qty' }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Date limite' : 'Deadline' }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Actions' : 'Actions' }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($assignations as $assignation)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $assignation->producteur->name }}</div>
                            <div class="text-sm text-gray-500">{{ $assignation->producteur->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $assignation->matiere->nom }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ round($assignation->quantite_restante,1) }} {{ $assignation->unite_assignee }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $assignation->date_limite_utilisation ? $assignation->date_limite_utilisation->format('d/m/Y') : ($isFrench ? 'Non définie' : 'Not set') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('assignations.edit', $assignation->id) }}" class="bg-blue-500 hover:bg-yellow-600 text-white py-1 px-3 rounded transition-colors duration-200">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <a href="{{ route('assignations.facture', $assignation->id) }}" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded transition-colors duration-200">
                                    {{ $isFrench ? 'Voir facture' : 'View Invoice' }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $isFrench ? 'Aucune assignation trouvée.' : 'No assignments found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Mobile Cards -->
        <div class="hidden mobile:block">
            @forelse($assignations as $assignation)
                <div class="p-4 border-b border-gray-200 animate-fade-in">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $assignation->producteur->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $assignation->producteur->email }}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            {{ round($assignation->quantite_restante,1) }}/{{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}
                        </span>
                    </div>
                    
                    <div class="mb-2">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">{{ $isFrench ? 'Matière:' : 'Material:' }}</span> 
                            {{ $assignation->matiere->nom }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">{{ $isFrench ? 'Date limite:' : 'Deadline:' }}</span> 
                            {{ $assignation->date_limite_utilisation ? $assignation->date_limite_utilisation->format('d/m/Y') : ($isFrench ? 'Non définie' : 'Not set') }}
                        </p>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('assignations.edit', $assignation->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded-md text-sm transition-colors duration-200 transform active:scale-95">
                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                        </a>
                        <a href="{{ route('assignations.facture', $assignation->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded-md text-sm transition-colors duration-200 transform active:scale-95">
                            {{ $isFrench ? 'Facture' : 'Invoice' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    {{ $isFrench ? 'Aucune assignation trouvée.' : 'No assignments found.' }}
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @media (max-width: 640px) {
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-bounce { animation: bounce 2s infinite; }
        .animate-pulse { animation: pulse 2s infinite; }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
    }
</style>
@endsection