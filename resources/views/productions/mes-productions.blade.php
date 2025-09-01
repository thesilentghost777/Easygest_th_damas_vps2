@extends('layouts.app')

@section('title', 'Mes Productions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Productions</h1>
                    <p class="text-gray-600">Gérez et consultez vos productions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $productions->count() }} production{{ $productions->count() > 1 ? 's' : '' }}
                    </span>
                    @if(Auth::user()->secteur === 'administration')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                            </svg>
                            Administrateur
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($productions->isEmpty())
            <!-- État vide -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune production trouvée</h3>
                <p class="text-gray-500 mb-6">Vous n'avez pas encore de productions enregistrées.</p>
                <a href="{{ route('produitmp') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer une production
                </a>
            </div>
        @else
            <!-- Liste des productions -->
            <div class="grid gap-6">
                @foreach($productions as $production)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- En-tête de la production -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($production->id_lot, -2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Lot {{ $production->id_lot }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($production->created_at)->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Informations du produit -->
                                <div class="grid md:grid-cols-3 gap-4 mb-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600">Produit</span>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $production->produit_fixes->nom ?? 'Produit inconnu' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Code: {{ $production->produit }}
                                        </p>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600">Quantité</span>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ number_format($production->quantite_produit, 2, ',', ' ') }}
                                        </p>
                                        <p class="text-xs text-gray-500">unités</p>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V11H5z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600">Matières</span>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $production->nombre_matieres }}
                                        </p>
                                        <p class="text-xs text-gray-500">utilisée{{ $production->nombre_matieres > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-6">
                                
                                
                                <button onclick="confirmerSuppression('{{ $production->id_lot }}')" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="modal-suppression" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="fermerModal()"></div>

        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">
                Confirmer la suppression
            </h3>
            
            <p class="text-center text-gray-600 mb-6">
                Êtes-vous sûr de vouloir supprimer la production <span id="lot-id-modal" class="font-semibold"></span> ?
                Cette action est irréversible.
            </p>

            <div class="flex space-x-3">
                <button onclick="fermerModal()" 
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                    Annuler
                </button>
                
                <button onclick="supprimerProduction()" 
                        id="btn-confirmer" 
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                    <span class="btn-text">Supprimer</span>
                    <span class="btn-loading hidden">
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Suppression...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection

@push('scripts')
<script>
let idLotASupprimer = null;

function confirmerSuppression(idLot) {
    idLotASupprimer = idLot;
    document.getElementById('lot-id-modal').textContent = idLot;
    document.getElementById('modal-suppression').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function fermerModal() {
    document.getElementById('modal-suppression').classList.add('hidden');
    document.body.style.overflow = '';
    idLotASupprimer = null;
}

function supprimerProduction() {
    if (!idLotASupprimer) return;

    const btnConfirmer = document.getElementById('btn-confirmer');
    const btnText = btnConfirmer.querySelector('.btn-text');
    const btnLoading = btnConfirmer.querySelector('.btn-loading');
    
    // Désactiver le bouton et afficher le loading
    btnConfirmer.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');

    fetch('{{ route("production.supprimer") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id_lot: idLotASupprimer
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            afficherToast('Succès', data.message, 'success');
            // Supprimer l'élément du DOM
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            afficherToast('Erreur', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        afficherToast('Erreur', 'Une erreur est survenue lors de la suppression.', 'error');
    })
    .finally(() => {
        // Réactiver le bouton
        btnConfirmer.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        fermerModal();
    });
}

function afficherToast(titre, message, type) {
    const container = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';

    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full opacity-0`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${icon}
            </svg>
            <div>
                <div class="font-semibold">${titre}</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Suppression automatique
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }, 5000);
}

// Fermer la modal avec Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        fermerModal();
    }
});
</script>
@endpush
