@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 animate-slide-down" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('receptions.pointeurs.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-truck-loading mr-2"></i>
                    {{ $isFrench ? 'Réceptions' : 'Receptions' }}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">
                        {{ $isFrench ? 'Détail réception' : 'Reception detail' }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header avec actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8 animate-slide-down">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-eye mr-3 text-blue-600"></i>
                {{ $isFrench ? 'Détail de la Réception' : 'Reception Detail' }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? 'Informations complètes de la réception' : 'Complete reception information' }} 
                #{{ str_pad($reception->id, 6, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <a href="{{ route('receptions.pointeurs.index') }}" 
               class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ $isFrench ? 'Retour' : 'Back' }}
            </a>
            <a href="{{ route('receptions.pointeurs.edit', $reception->id) }}" 
               class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg">
                <i class="fas fa-edit mr-2"></i>
                {{ $isFrench ? 'Modifier' : 'Edit' }}
            </a>
            <button type="button" onclick="confirmDelete({{ $reception->id }})"
                    class="inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg">
                <i class="fas fa-trash mr-2"></i>
                {{ $isFrench ? 'Supprimer' : 'Delete' }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la réception -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        {{ $isFrench ? 'Informations de la Réception' : 'Reception Information' }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pointeur -->
                        <div class="bg-blue-50 rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                <h3 class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Pointeur' : 'Pointer' }}</h3>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $reception->pointeur->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Date de réception -->
                        <div class="bg-green-50 rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar text-green-600 mr-2"></i>
                                <h3 class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Date de Réception' : 'Reception Date' }}</h3>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($reception->date_reception)->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($reception->date_reception)->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Produit -->
                        <div class="bg-indigo-50 rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-box text-indigo-600 mr-2"></i>
                                <h3 class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Produit' : 'Product' }}</h3>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $reception->produit->nom_produit ?? ($isFrench ? 'Produit supprimé' : 'Deleted product') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $isFrench ? 'Code:' : 'Code:' }} {{ $reception->produit_id }}
                            </p>
                        </div>

                        <!-- Quantité -->
                        <div class="bg-emerald-50 rounded-xl p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-weight text-emerald-600 mr-2"></i>
                                <h3 class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Quantité Reçue' : 'Received Quantity' }}</h3>
                            </div>
                            <p class="text-2xl font-bold text-emerald-600">
                                {{ number_format($reception->quantite_recue, 2, ',', ' ') }}
                            </p>
                        </div>
                    </div>

                    <!-- Détails du produit si disponible -->
                    @if($reception->produit)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-cube text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Détails du Produit' : 'Product Details' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Description' : 'Description' }}</p>
                                <p class="font-medium text-gray-900">
                                    {{ $reception->produit->description ?? ($isFrench ? 'Aucune description' : 'No description') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Prix unitaire' : 'Unit price' }}</p>
                                <p class="font-semibold text-gray-900">
                                    {{ number_format($reception->produit->prix_unitaire ?? 0, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Valeur totale' : 'Total value' }}</p>
                                <p class="font-bold text-green-600 text-lg">
                                    {{ number_format(($reception->produit->prix_unitaire ?? 0) * $reception->quantite_recue, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Réceptions liées -->
            @if($relatedReceptions->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-link mr-3"></i>
                        {{ $isFrench ? 'Réceptions liées' : 'Related receptions' }}
                        <span class="ml-2 px-2 py-1 bg-white/20 rounded-full text-sm">
                            {{ $relatedReceptions->count() }}
                        </span>
                    </h2>
                    <p class="text-green-100 text-sm mt-1">
                        {{ $isFrench ? 'Autres réceptions du même pointeur pour la même date' : 'Other receptions from the same pointer for the same date' }}
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($relatedReceptions as $related)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $related->produit->nom_produit ?? ($isFrench ? 'Produit supprimé' : 'Deleted product') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $related->produit_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($related->quantite_recue, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($related->produit)
                                        {{ number_format($related->produit->prix_unitaire * $related->quantite_recue, 0, ',', ' ') }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('receptions.pointeurs.show', $related->id) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $isFrench ? 'Voir' : 'View' }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Historique -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        {{ $isFrench ? 'Historique' : 'History' }}
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Créé le' : 'Created on' }}</span>
                        </div>
                        <p class="text-sm text-gray-900 font-semibold">
                            {{ $reception->created_at->format('d/m/Y à H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $reception->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($reception->updated_at != $reception->created_at)
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-edit text-yellow-600 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Modifié le' : 'Modified on' }}</span>
                        </div>
                        <p class="text-sm text-gray-900 font-semibold">
                            {{ $reception->updated_at->format('d/m/Y à H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $reception->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    @endif

                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-hashtag text-blue-600 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">ID</span>
                        </div>
                        <p class="text-sm font-mono text-gray-900">
                            #{{ str_pad($reception->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-tools mr-2"></i>
                        {{ $isFrench ? 'Actions Rapides' : 'Quick Actions' }}
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('receptions.pointeurs.create') }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <i class="fas fa-plus mr-2"></i>
                        {{ $isFrench ? 'Nouvelle Réception' : 'New Reception' }}
                    </a>
                    
                    <button type="button" onclick="window.print()" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-xl transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        {{ $isFrench ? 'Imprimer' : 'Print' }}
                    </button>

                    @if($reception->produit)
                    <a href="{{ route('receptions.pointeurs.index', ['produit_id' => $reception->produit_id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-medium rounded-xl transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        {{ $isFrench ? 'Autres réceptions' : 'Other receptions' }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 animate-scale-in">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $isFrench ? 'Confirmer la suppression' : 'Confirm deletion' }}
            </h3>
        </div>
        <div class="p-6">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-5xl mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette réception ?' : 'Are you sure you want to delete this reception?' }}
                </h4>
                <p class="text-gray-600 mb-6">
                    {{ $isFrench ? 'Cette action est irréversible.' : 'This action is irreversible.' }}
                </p>
                
                <div class="bg-gray-50 rounded-xl p-4 text-left">
                    <h5 class="font-semibold text-gray-900 mb-2">
                        {{ $isFrench ? 'Détails de la réception :' : 'Reception details:' }}
                    </h5>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>{{ $isFrench ? 'Pointeur:' : 'Pointer:' }}</strong> {{ $reception->pointeur->name ?? 'N/A' }}</p>
                        <p><strong>{{ $isFrench ? 'Produit:' : 'Product:' }}</strong> {{ $reception->produit->nom_produit ?? 'N/A' }}</p>
                        <p><strong>{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</strong> {{ number_format($reception->quantite_recue, 2) }}</p>
                        <p><strong>{{ $isFrench ? 'Date:' : 'Date:' }}</strong> {{ \Carbon\Carbon::parse($reception->date_reception)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-3 p-6 border-t border-gray-200">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition-colors">
                <i class="fas fa-times mr-2"></i>
                {{ $isFrench ? 'Annuler' : 'Cancel' }}
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    {{ $isFrench ? 'Supprimer définitivement' : 'Delete permanently' }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/receptions/pointeurs/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Style d'impression
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .no-print, button, .modal {
        display: none !important;
    }
    
    .bg-gradient-to-r {
        background: #f3f4f6 !important;
        color: #000 !important;
    }
    
    body.printing {
        font-size: 12px;
    }
    
    .shadow-lg {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}

.animate-scale-in {
    animation: scaleIn 0.3s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0.9);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
@endpush
@endsection