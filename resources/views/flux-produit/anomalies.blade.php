
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <svg class="inline w-8 h-8 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.664 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        {{ $isFrench ? 'Gestion des Anomalies' : 'Anomaly Management' }}
                    </h1>
                    <a href="{{ route('flux-produit.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        ← {{ $isFrench ? 'Retour au dashboard' : 'Back to dashboard' }}
                    </a>
                </div>
            </div>

            <!-- Statistiques des anomalies -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-red-800">{{ $isFrench ? 'Critiques' : 'Critical' }}</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $anomalies->where('niveau', 'critique')->count() }}</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                    <h3 class="text-sm font-medium text-orange-800">{{ $isFrench ? 'Importantes' : 'Important' }}</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $anomalies->where('niveau', 'important')->count() }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
                    <h3 class="text-sm font-medium text-yellow-800">{{ $isFrench ? 'Mineures' : 'Minor' }}</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $anomalies->where('niveau', 'mineur')->count() }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-blue-800">{{ $isFrench ? 'Résolues' : 'Resolved' }}</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $anomalies->where('statut', 'resolu')->count() }}</p>
                </div>
            </div>

            <!-- Liste des anomalies -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Anomalies détectées' : 'Detected anomalies' }}
                    </h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($anomalies as $anomalie)
                        <div class="p-6 {{ $anomalie['niveau'] == 'critique' ? 'bg-red-50' : ($anomalie['niveau'] == 'important' ? 'bg-orange-50' : 'bg-yellow-50') }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-3
                                            {{ $anomalie['niveau'] == 'critique' ? 'bg-red-100 text-red-800' : 
                                               ($anomalie['niveau'] == 'important' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($anomalie['niveau']) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $anomalie['type'] }}</span>
                                    </div>
                                    
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $anomalie['message'] }}</h3>
                                    
                                    <div class="text-sm text-gray-700 space-y-1">
                                        <p><strong>{{ $isFrench ? 'Action requise:' : 'Required action:' }}</strong> {{ $anomalie['action_requise'] }}</p>
                                        <p><strong>{{ $isFrench ? 'Responsable:' : 'Responsible:' }}</strong> {{ $anomalie['responsable'] }}</p>
                                        @if(isset($anomalie['data']))
                                            <p><strong>{{ $isFrench ? 'Détails:' : 'Details:' }}</strong> 
                                                @if($anomalie['type'] == 'produit_non_assigne')
                                                    {{ $isFrench ? 'Produit ID:' : 'Product ID:' }} {{ $anomalie['data']->id }} - 
                                                    {{ $isFrench ? 'Reçu le:' : 'Received on:' }} {{ $anomalie['data']->date_reception->format('d/m/Y H:i') }}
                                                @elseif($anomalie['type'] == 'production_non_declaree')
                                                    {{ $isFrench ? 'Lot:' : 'Batch:' }} {{ $anomalie['data']->id_lot }} - 
                                                    {{ $isFrench ? 'Quantité:' : 'Quantity:' }} {{ $anomalie['data']->quantite_produite - $anomalie['data']->quantite_recue }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="ml-4 flex space-x-2">
                                    <button onclick="marquerResolu('{{ $loop->index }}')" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        {{ $isFrench ? 'Marquer résolu' : 'Mark resolved' }}
                                    </button>
                                    <button onclick="voirDetails('{{ $loop->index }}')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        {{ $isFrench ? 'Détails' : 'Details' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isFrench ? 'Aucune anomalie détectée' : 'No anomalies detected' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function marquerResolu(index) {
    if (confirm('{{ $isFrench ? "Marquer cette anomalie comme résolue ?" : "Mark this anomaly as resolved?" }}')) {
        fetch('{{ route("flux-produit.resoudre-anomalie") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                anomalie_index: index
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ $isFrench ? "Erreur lors de la résolution" : "Error during resolution" }}');
            }
        });
    }
}

function voirDetails(index) {
    // Afficher les détails de l'anomalie
    alert('{{ $isFrench ? "Fonctionnalité à implémenter" : "Feature to implement" }}');
}
</script>
@endsection
