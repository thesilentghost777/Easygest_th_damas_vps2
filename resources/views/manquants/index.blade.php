@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Gestion des Manquants' : 'Missing Items Management' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Suivi et validation' : 'Tracking and validation' }}
            </p>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Warning -->
                <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500 mb-6 animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h3 class="text-amber-800 font-semibold">
                                {{ $isFrench ? 'Rappel important' : 'Important Reminder' }}
                            </h3>
                            <p class="text-amber-700 text-sm mt-1">
                                {{ $isFrench ? 'Les manquants ne doivent être ajustés qu\'à la fin du mois.' : 'Missing items should only be adjusted at month-end.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Quick Actions -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <a href="{{ route('manquants.calculer') }}" class="bg-blue-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right">
                        <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-blue-800">
                            {{ $isFrench ? 'Calculer Tous' : 'Calculate All' }}
                        </p>
                    </a>
                    
                    <a href="{{ route('manquant.create') }}" class="bg-green-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.1s">
                        <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-green-800">
                            {{ $isFrench ? 'Nouveau Manquant' : 'New Missing Item' }}
                        </p>
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Mobile Missing Items List -->
                <div class="space-y-4">
                    @forelse($manquants as $manquant)
                        <div class="bg-white border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $manquant->employe->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ ucfirst($manquant->employe->role) }}</p>
                                    </div>
                                </div>
                                
                                @if($manquant->statut == 'en_attente')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                    </span>
                                @elseif($manquant->statut == 'ajuste')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $isFrench ? 'Ajusté' : 'Adjusted' }}
                                    </span>
                                @elseif($manquant->statut == 'valide')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ $isFrench ? 'Validé' : 'Validated' }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-lg font-bold text-gray-900 mb-3">
                                {{ number_format($manquant->montant, 0, ',', ' ') }} FCFA
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <button type="button" onclick="showDetails({{ $manquant->id }})" class="text-blue-600 text-sm font-medium">
                                    {{ $isFrench ? 'Voir détails' : 'View details' }}
                                </button>
                                
                                @if($manquant->statut != 'valide')
                                    <div class="flex space-x-2">
                                        <a href="{{ route('manquants.ajuster', $manquant->id) }}" class="text-yellow-600 text-sm">
                                            {{ $isFrench ? 'Ajuster' : 'Adjust' }}
                                        </a>
                                        <a href="{{ route('manquants.valider', $manquant->id) }}" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir valider ce manquant?' : 'Are you sure you want to validate this missing item?' }}')" class="text-green-600 text-sm">
                                            {{ $isFrench ? 'Valider' : 'Validate' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 rounded-2xl p-8 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucun manquant' : 'No missing items' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $isFrench ? 'Aucun manquant trouvé pour le moment.' : 'No missing items found at the moment.' }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <div class="flex items-center">
                @include('buttons')
                <div class="py-1">
                    <i class="mdi mdi-calendar-clock text-xl mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">
                        {{ $isFrench ? 'Rappel important' : 'Important Reminder' }}
                    </p>
                    <p>
                        {{ $isFrench ? 'Veuillez noter que les manquants ne doivent être ajustés ou validés qu\'à la fin du mois. Toute validation prématurée pourrait affecter les calculs mensuels.' : 'Please note that missing items should only be adjusted or validated at month-end. Premature validation could affect monthly calculations.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $isFrench ? 'Gestion des Manquants' : 'Missing Items Management' }}
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('manquants.calculer') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="mdi mdi-calculator-variant mr-2"></i>
                        {{ $isFrench ? 'Calculer Tous les Manquants' : 'Calculate All Missing Items' }}
                    </a>
                    <a href="{{ route('manquant.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="mdi mdi-plus-circle mr-2"></i>
                        {{ $isFrench ? 'Facturer un Manquant' : 'Bill a Missing Item' }}
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Employé' : 'Employee' }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Fonction' : 'Function' }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Montant' : 'Amount' }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($manquants as $manquant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="mdi mdi-account text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $manquant->employe->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $manquant->employe->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($manquant->employe->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <span class="font-semibold text-gray-900">{{ number_format($manquant->montant, 0, ',', ' ') }} FCFA</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($manquant->statut == 'en_attente')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                    </span>
                                @elseif($manquant->statut == 'ajuste')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $isFrench ? 'Ajusté' : 'Adjusted' }}
                                    </span>
                                @elseif($manquant->statut == 'valide')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $isFrench ? 'Validé' : 'Validated' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <button type="button" 
                                        onclick="showDetails({{ $manquant->id }})" 
                                        class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-sm p-1 transition-colors duration-200"
                                        title="Voir les détails"
                                        aria-label="Voir les détails de l'élément {{ $manquant->id }}">
                                        <i class="mdi mdi-eye text-lg"></i>
                                    </button>

                                    @if($manquant->statut != 'valide')
                                        <a href="{{ route('manquants.ajuster', $manquant->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="mdi mdi-pencil text-lg"></i>
                                        </a>

                                        <a href="{{ route('manquants.valider', $manquant->id) }}" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir valider ce manquant?' : 'Are you sure you want to validate this missing item?' }}')" class="text-green-600 hover:text-green-900">
                                            <i class="mdi mdi-check-circle text-lg"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ $isFrench ? 'Aucun manquant trouvé' : 'No missing items found' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for details -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] overflow-y-auto m-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Détails du Manquant' : 'Missing Item Details' }}
                </h3>
                <button onclick="hideDetails()" class="text-gray-400 hover:text-gray-500">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="hideDetails()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ $isFrench ? 'Fermer' : 'Close' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Définir les fonctions globalement
    window.showDetails = function(id) {
        fetch(`/manquants/${id}/details`)
            .then(response => response.json())
            .then(data => {
                const content = document.getElementById('modalContent');
                const isFrench = @json($isFrench);

                let html = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">${isFrench ? 'Employé' : 'Employee'}</p>
                            <p class="text-base">${data.employe.name}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">${isFrench ? 'Fonction' : 'Function'}</p>
                            <p class="text-base">${data.employe.role}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">${isFrench ? 'Montant' : 'Amount'}</p>
                            <p class="text-lg font-semibold">${new Intl.NumberFormat('fr-FR').format(data.montant)} FCFA</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">${isFrench ? 'Statut' : 'Status'}</p>
                            <p class="text-base">${data.statut}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-500">${isFrench ? 'Explication' : 'Explanation'}</p>
                        <pre class="mt-1 p-3 bg-gray-50 rounded text-sm whitespace-pre-wrap">${data.explication}</pre>
                    </div>
                `;

                if (data.commentaire_dg) {
                    html += `
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-500">${isFrench ? 'Commentaire du DG' : 'GM Comment'}</p>
                            <p class="mt-1 p-3 bg-blue-50 rounded text-sm">${data.commentaire_dg}</p>
                        </div>
                    `;
                }

                content.innerHTML = html;
                document.getElementById('detailsModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert(isFrench ? 'Erreur lors du chargement des détails' : 'Error loading details');
            });
    };

    window.hideDetails = function() {
        document.getElementById('detailsModal').classList.add('hidden');
    };
});
</script>
    
<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection