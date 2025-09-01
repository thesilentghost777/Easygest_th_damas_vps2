@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header Mobile -->
    <div class="bg-white shadow-sm p-4 sticky top-0 z-10">
        <h1 class="text-xl font-bold text-gray-800 text-center">
            {{ $isFrench ? 'Flux Produits' : 'Product Flow' }}
        </h1>
        <div class="text-center text-sm text-gray-600 mt-1">{{ $date }}</div>
    </div>

    <!-- Actions rapides -->
    <div class="p-4 space-y-3">
        <button onclick="calculerManquantsAuto()" class="w-full bg-red-600 text-white py-3 rounded-lg text-center">
            {{ $isFrench ? 'Calculer manquants' : 'Calculate missing' }}
        </button>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="exporterRapport()" class="bg-green-600 text-white py-2 rounded-lg text-center text-sm">
                {{ $isFrench ? 'Export' : 'Export' }}
            </button>
            <a href="{{ route('flux-produit.explication') }}" class="bg-blue-600 text-white py-2 rounded-lg text-center text-sm">
                {{ $isFrench ? 'Aide' : 'Help' }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="px-4 mb-4">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">
                <div class="text-xs text-blue-800 font-medium">{{ $isFrench ? 'Productions' : 'Productions' }}</div>
                <div class="text-lg font-bold text-blue-600">{{ number_format($stats['total_productions']) }}</div>
            </div>
            <div class="bg-green-50 p-3 rounded-lg border-l-4 border-green-500">
                <div class="text-xs text-green-800 font-medium">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</div>
                <div class="text-lg font-bold text-green-600">{{ number_format($stats['total_receptions']) }}</div>
            </div>
        </div>
    </div>

    <!-- Alertes Mobile -->
    @if($anomalies->count() > 0)
        <div class="mx-4 mb-4 bg-red-50 border-l-4 border-red-500 p-3 rounded">
            <div class="text-sm font-medium text-red-800">
                {{ $anomalies->count() }} {{ $isFrench ? 'anomalies détectées' : 'anomalies detected' }}
            </div>
            <a href="{{ route('flux-produit.anomalies') }}" class="text-xs text-red-600 underline">
                {{ $isFrench ? 'Voir détails' : 'See details' }}
            </a>
        </div>
    @endif

    <!-- Liste des flux - Mobile Cards -->
    <div class="px-4 space-y-3">
        @forelse($fluxData as $flux)
            <div class="bg-white rounded-lg shadow-sm border 
                {{ $flux['alerte_niveau'] == 'critique' ? 'border-red-300' : ($flux['alerte_niveau'] == 'important' ? 'border-orange-300' : 'border-gray-200') }}">
                
                <!-- Header Card -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $flux['nom_produit'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $flux['producteur']['nom'] }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $flux['statut'] == 'complet' ? 'bg-green-100 text-green-800' : 
                               ($flux['statut'] == 'manquant_reception' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800') }}">
                            {{ $flux['statut'] }}
                        </span>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="p-4">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-xs text-gray-500">{{ $isFrench ? 'Prod.' : 'Prod.' }}</div>
                            <div class="text-sm font-medium">{{ number_format($flux['production']['quantite']) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">{{ $isFrench ? 'Reçu' : 'Recv.' }}</div>
                            <div class="text-sm font-medium">{{ number_format($flux['reception']['quantite']) }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">{{ $isFrench ? 'Assign.' : 'Assign.' }}</div>
                            <div class="text-sm font-medium">{{ number_format($flux['assignation']['quantite']) }}</div>
                        </div>
                    </div>

                    @if($flux['manquants']['production_reception']['quantite'] > 0 || $flux['manquants']['reception_assignation']['quantite'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="text-xs text-red-600 font-medium mb-1">{{ $isFrench ? 'Manquants:' : 'Missing:' }}</div>
                            @if($flux['manquants']['production_reception']['quantite'] > 0)
                                <div class="text-xs text-red-600">
                                    Prod→Rec: {{ number_format($flux['manquants']['production_reception']['valeur']) }} FCFA
                                </div>
                            @endif
                            @if($flux['manquants']['reception_assignation']['quantite'] > 0)
                                <div class="text-xs text-orange-600">
                                    Rec→Ass: {{ number_format($flux['manquants']['reception_assignation']['valeur']) }} FCFA
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-3 flex space-x-2">
                        <button onclick="voirDetails('{{ $flux['produit_id'] }}', '{{ $flux['producteur']['id'] }}', '{{ $date }}')" 
                                class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded text-xs">
                            {{ $isFrench ? 'Détails' : 'Details' }}
                        </button>
                        @if($flux['alerte_niveau'] != 'normal')
                            <button onclick="gererAnomalies('{{ $flux['produit_id'] }}', '{{ $flux['producteur']['id'] }}')" 
                                    class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded text-xs">
                                {{ $isFrench ? 'Gérer' : 'Manage' }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6z"/>
                </svg>
                {{ $isFrench ? 'Aucun flux trouvé' : 'No flows found' }}
            </div>
        @endforelse
    </div>

    <!-- Espaceur pour le bas -->
    <div class="h-20"></div>
</div>

<!-- Modal Mobile pour les détails -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-4 shadow-lg rounded-md bg-white">
        <div class="p-4">
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
            <div id="modalContent" class="max-h-96 overflow-y-auto">
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
// Fonction pour détecter mobile
function isMobile() {
    return window.innerWidth <= 768;
}

// Adapter l'affichage selon l'écran
if (isMobile()) {
    document.body.classList.add('mobile-view');
}
</script>
@endsection
