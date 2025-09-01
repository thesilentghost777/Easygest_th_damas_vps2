
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="container mx-auto px-4">
            <!-- Header avec titre et filtres -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div id="alertDiv" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-md max-w-2xl mx-auto">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="font-medium" id="alertText">
                    Attention Veuillez effectuer le calcul des manquants une et une seule fois a la fin du mois
                </p>
            </div>
        </div>
    </div>
    <br><br>
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 lg:mb-0">
                        <svg class="inline w-8 h-8 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ $isFrench ? 'Flux de Produits' : 'Product Flow' }}
                    </h1>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="calculerManquantsAuto()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            {{ $isFrench ? 'Calculer les manquants' : 'Calculate missing items' }}
                        </button>
                        <button onclick="exporterRapport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            {{ $isFrench ? 'Exporter le rapport' : 'Export report' }}
                        </button>
                    </div>
                </div>

                <!-- Filtres -->
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <input type="date" name="date" value="{{ $date }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </label>
                        <select name="producteur_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">{{ $isFrench ? 'Tous les producteurs' : 'All producers' }}</option>
                            @foreach($producteurs as $producteur)
                                <option value="{{ $producteur->id }}" {{ $producteur_id == $producteur->id ? 'selected' : '' }}>
                                    {{ $producteur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                        </label>
                        <select name="pointeur_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">{{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }}</option>
                            @foreach($pointeurs as $pointeur)
                                <option value="{{ $pointeur->id }}" {{ $pointeur_id == $pointeur->id ? 'selected' : '' }}>
                                    {{ $pointeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            {{ $isFrench ? 'Filtrer' : 'Filter' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-blue-800">{{ $isFrench ? 'Productions' : 'Productions' }}</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_productions']) }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                    <h3 class="text-sm font-medium text-green-800">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_receptions']) }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
                    <h3 class="text-sm font-medium text-purple-800">{{ $isFrench ? 'Assignations' : 'Assignments' }}</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_assignations']) }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
                    <h3 class="text-sm font-medium text-yellow-800">{{ $isFrench ? 'Valeur Produite' : 'Produced Value' }}</h3>
                    <p class="text-xl font-bold text-yellow-600">{{ number_format($stats['valeur_totale_produite']) }} FCFA</p>
                </div>
                <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-500">
                    <h3 class="text-sm font-medium text-indigo-800">{{ $isFrench ? 'Valeur Reçue' : 'Received Value' }}</h3>
                    <p class="text-xl font-bold text-indigo-600">{{ number_format($stats['valeur_totale_recue']) }} FCFA</p>
                </div>
                <div class="bg-pink-50 rounded-lg p-4 border-l-4 border-pink-500">
                    <h3 class="text-sm font-medium text-pink-800">{{ $isFrench ? 'Valeur Assignée' : 'Assigned Value' }}</h3>
                    <p class="text-xl font-bold text-pink-600">{{ number_format($stats['valeur_totale_assignee']) }} FCFA</p>
                </div>
            </div>

            

            <!-- Tableau des flux de produits -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Flux détaillés par produit' : 'Detailed flows by product' }}
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Producteur' : 'Producer' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Production' : 'Production' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Réception' : 'Reception' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Assignation' : 'Assignment' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Manquants' : 'Missing' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Statut' : 'Status' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($fluxData as $flux)
                                <tr class="hover:bg-gray-50 
                                    {{ $flux['alerte_niveau'] == 'critique' ? 'bg-red-50' : ($flux['alerte_niveau'] == 'important' ? 'bg-orange-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $flux['nom_produit'] }}</div>
                                                <div class="text-sm text-gray-500">{{ number_format($flux['prix_unitaire']) }} FCFA/unité</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $flux['producteur']['nom'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($flux['production']['quantite']) }} unités</div>
                                        <div class="text-sm text-gray-500">{{ number_format($flux['production']['valeur']) }} FCFA</div>
                                        <div class="text-xs text-gray-400">{{ count($flux['production']['lots']) }} lot(s)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($flux['reception']['quantite']) }} unités</div>
                                        <div class="text-sm text-gray-500">{{ number_format($flux['reception']['valeur']) }} FCFA</div>
                                        @if(count($flux['reception']['pointeurs']) > 0)
                                            <div class="text-xs text-gray-400">{{ implode(', ', array_slice($flux['reception']['pointeurs'], 0, 2)) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($flux['assignation']['quantite']) }} unités</div>
                                        <div class="text-sm text-gray-500">{{ number_format($flux['assignation']['valeur']) }} FCFA</div>
                                        <div class="text-xs text-gray-400">{{ count($flux['assignation']['details']) }} vendeur(s)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($flux['manquants']['production_reception']['quantite'] > 0 || $flux['manquants']['reception_assignation']['quantite'] > 0)
                                            <div class="space-y-1">
                                                @if($flux['manquants']['production_reception']['quantite'] > 0)
                                                    <div class="text-sm text-red-600">
                                                        Prod→Rec: {{ number_format($flux['manquants']['production_reception']['quantite']) }}
                                                        ({{ number_format($flux['manquants']['production_reception']['valeur']) }} FCFA)
                                                    </div>
                                                @endif
                                                @if($flux['manquants']['reception_assignation']['quantite'] > 0)
                                                    <div class="text-sm text-orange-600">
                                                        Rec→Ass: {{ number_format($flux['manquants']['reception_assignation']['quantite']) }}
                                                        ({{ number_format($flux['manquants']['reception_assignation']['valeur']) }} FCFA)
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-sm text-green-600">{{ $isFrench ? 'Aucun' : 'None' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $flux['statut'] == 'complet' ? 'bg-green-100 text-green-800' : 
                                               ($flux['statut'] == 'manquant_reception' ? 'bg-red-100 text-red-800' :
                                               ($flux['statut'] == 'manquant_assignation' ? 'bg-orange-100 text-orange-800' : 
                                               'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $flux['statut'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="voirDetails('{{ $flux['produit_id'] }}', '{{ $flux['producteur']['id'] }}', '{{ $date }}')" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            {{ $isFrench ? 'Détails' : 'Details' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6z"/>
                                        </svg>
                                        {{ $isFrench ? 'Aucun flux de produits trouvé pour cette date' : 'No product flows found for this date' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails du flux -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">
                    {{ $isFrench ? 'Détails du flux' : 'Flow details' }}
                </h3>
                <button onclick="fermerModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>

<script>
function calculerManquantsAuto() {
    if (confirm('{{ $isFrench ? "Voulez-vous calculer automatiquement les manquants pour tous les pointeurs ?" : "Do you want to automatically calculate missing items for all pointers?" }}')) {
        fetch('{{ route("flux-produit.calculer-manquants") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                date: '{{ $date }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ $isFrench ? "Calcul des manquants effectué avec succès" : "Missing items calculation completed successfully" }}');
                location.reload();
            } else {
                alert('{{ $isFrench ? "Erreur lors du calcul" : "Error during calculation" }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ $isFrench ? "Erreur lors du calcul" : "Error during calculation" }}');
        });
    }
}

function voirDetails(produitId, producteurId, date) {
    fetch(`{{ route("flux-produit.details") }}?produit_id=${produitId}&producteur_id=${producteurId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('modalContent');
            
            // Construire le contenu HTML des détails
            let html = '<div class="space-y-6">';
            
            // Chronologie
            html += '<div><h4 class="text-lg font-medium mb-3">{{ $isFrench ? "Chronologie" : "Timeline" }}</h4>';
            html += '<div class="space-y-2">';
            
            data.chronologie.forEach(event => {
                const eventDate = new Date(event.timestamp).toLocaleString();
                html += `<div class="flex items-start space-x-3 p-3 border rounded-lg">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-3 h-3 rounded-full ${event.type === 'production' ? 'bg-blue-500' : (event.type === 'reception' ? 'bg-green-500' : 'bg-purple-500')}"></div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${event.description}</p>
                        <p class="text-xs text-gray-500">${eventDate}</p>
                    </div>
                </div>`;
            });
            
            html += '</div></div>';
            html += '</div>';
            
            content.innerHTML = html;
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ $isFrench ? "Erreur lors du chargement des détails" : "Error loading details" }}');
        });
}

function fermerModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function exporterRapport() {
    window.open(`{{ route("flux-produit.export") }}?date={{ $date }}&producteur_id={{ $producteur_id }}&pointeur_id={{ $pointeur_id }}`, '_blank');
}

function gererAnomalies(produitId, producteurId) {
    // Rediriger vers la page de gestion des anomalies
    window.location.href = `{{ route("flux-produit.anomalies") }}?produit_id=${produitId}&producteur_id=${producteurId}`;
}
</script>
@endsection
