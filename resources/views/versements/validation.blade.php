@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">
            {{ $isFrench ? 'Validation des Versements' : 'Payment Validation' }}
        </h1>
        <p class="mt-2 text-gray-600">
            {{ $isFrench ? 'Total en attente :' : 'Total pending:' }} {{ number_format($total_en_attente, 0, ',', ' ') }} FCFA
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-xl animate-slide-in" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-xl animate-slide-in" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Versements en attente' : 'Pending Payments' }}
            </h2>

            @if(count($versements) > 0)
                <!-- Desktop table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Auteur' : 'Author' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Libellé' : 'Description' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($versements as $versement)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $versement->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $versement->verseur_name($versement->verseur) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $versement->libelle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ number_format($versement->montant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                    <button type="button"
                                        class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition-colors focus:ring-2 focus:ring-green-300"
                                        onclick="showConfirmModal('validate', '{{ $versement->code_vc }}', '{{ $versement->libelle }}', '{{ number_format($versement->montant, 0, ',', ' ') }} FCFA')">
                                        {{ $isFrench ? 'Valider' : 'Validate' }}
                                    </button>
                                    
                                    <button
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition-colors focus:ring-2 focus:ring-blue-300"
                                        onclick="toggleEditForm('{{ $versement->code_vc }}')">
                                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                                    </button>
                                    
                                    <button type="button"
                                        class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-colors focus:ring-2 focus:ring-red-300"
                                        onclick="showConfirmModal('reject', '{{ $versement->code_vc }}', '{{ $versement->libelle }}', '{{ number_format($versement->montant, 0, ',', ' ') }} FCFA')">
                                        {{ $isFrench ? 'Rejeter' : 'Reject' }}
                                    </button>
                                </td>
                            </tr>
                            <!-- Edit form row - hidden by default -->
                            <tr id="edit-form-{{ $versement->code_vc }}" class="hidden bg-gray-50">
                                <td colspan="5" class="px-6 py-4">
                                    <form action="{{ route('versements.update', $versement) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="libelle" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Libellé' : 'Description' }}</label>
                                                <input type="text" name="libelle" id="libelle" value="{{ $versement->libelle }}" 
                                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div>
                                                <label for="montant" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}</label>
                                                <input type="number" name="montant" id="montant" value="{{ $versement->montant }}" 
                                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="toggleEditForm('{{ $versement->code_vc }}')" 
                                                class="bg-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                                            </button>
                                            <button type="submit" 
                                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                                                {{ $isFrench ? 'Enregistrer' : 'Save' }}
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile card view -->
                <div class="lg:hidden space-y-4">
                    @foreach($versements as $versement)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-200 hover:shadow-md">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-gray-800 truncate">{{ $versement->libelle }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $versement->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-blue-600 font-medium">{{ $versement->verseur_name($versement->verseur) }}</div>
                                </div>
                                <div class="text-right ml-4 flex-shrink-0">
                                    <div class="text-lg font-bold text-blue-600">{{ number_format($versement->montant, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col space-y-2 pt-3 border-t border-gray-200">
                                <div class="flex space-x-2">
                                    <button type="button"
                                        class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-colors active:scale-95 focus:ring-2 focus:ring-green-300"
                                        onclick="showConfirmModal('validate', '{{ $versement->code_vc }}', '{{ $versement->libelle }}', '{{ number_format($versement->montant, 0, ',', ' ') }} FCFA')">
                                        {{ $isFrench ? 'Valider' : 'Validate' }}
                                    </button>
                                    
                                    
                                </div>
                                
                                <button type="button"
                                    class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition-colors active:scale-95 focus:ring-2 focus:ring-red-300"
                                    onclick="showConfirmModal('reject', '{{ $versement->code_vc }}', '{{ $versement->libelle }}', '{{ number_format($versement->montant, 0, ',', ' ') }} FCFA')">
                                    {{ $isFrench ? 'Rejeter' : 'Reject' }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- Mobile edit form - hidden by default -->
                        <div id="edit-form-{{ $versement->code_vc }}" class="hidden bg-white p-4 rounded-xl border border-blue-200 shadow-md">
                            <form action="{{ route('versements.update', $versement) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label for="libelle_mobile" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Libellé' : 'Description' }}</label>
                                        <input type="text" name="libelle" id="libelle_mobile" value="{{ $versement->libelle }}" 
                                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-3 text-base">
                                    </div>
                                    <div>
                                        <label for="montant_mobile" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}</label>
                                        <input type="number" name="montant" id="montant_mobile" value="{{ $versement->montant }}" 
                                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-3 text-base">
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button type="submit" 
                                        class="w-full bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition-colors active:scale-95">
                                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                                    </button>
                                    <button type="button" onclick="toggleEditForm('{{ $versement->code_vc }}')" 
                                        class="w-full bg-gray-300 text-gray-700 py-3 rounded-xl hover:bg-gray-400 transition-colors active:scale-95">
                                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                    <i class="mdi mdi-cash-multiple text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">{{ $isFrench ? 'Aucun versement en attente de validation' : 'No payments pending validation' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation stylisée -->
<div id="confirmModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="hideConfirmModal()"></div>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-2xl transform transition-all scale-95 opacity-0 max-w-md w-full mx-4 z-10" id="modalContent">
        <div class="p-6">
            <!-- Header avec icône -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full" id="modalIcon">
                <!-- L'icône sera ajoutée dynamiquement -->
            </div>
            
            <!-- Titre -->
            <h3 class="text-xl font-bold text-center text-gray-900 mb-2" id="modalTitle">
                <!-- Le titre sera ajouté dynamiquement -->
            </h3>
            
            <!-- Message -->
            <div class="text-center text-gray-600 mb-6" id="modalMessage">
                <!-- Le message sera ajouté dynamiquement -->
            </div>
            
            <!-- Boutons d'action -->
            <div class="flex space-x-3">
                <button
                    class="flex-1 px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors focus:ring-2 focus:ring-gray-300"
                    onclick="hideConfirmModal()">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button
                    id="confirmButton"
                    class="flex-1 px-4 py-2 text-white rounded-lg transition-colors focus:ring-2"
                    onclick="executeAction()">
                    <!-- Le texte sera ajouté dynamiquement -->
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Forms cachés pour les actions -->
@if(count($versements) > 0)
    @foreach($versements as $versement)
        <form id="validateForm-{{ $versement->code_vc }}" action="{{ route('versements.valider', $versement) }}" method="POST" style="display: none;">
            @csrf
            @method('POST')
        </form>
        
        <form id="rejectForm-{{ $versement->code_vc }}" action="{{ route('versements.destroy', $versement) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

<script>
    // Variables globales
    let currentAction = null;
    let currentVersementId = null;
    
    // Traductions
    const translations = {
        fr: {
            validate: {
                title: 'Confirmer la validation',
                message: 'Êtes-vous sûr de vouloir valider ce versement ?',
                button: 'Valider'
            },
            reject: {
                title: 'Confirmer le rejet',
                message: 'Êtes-vous sûr de vouloir rejeter ce versement ?',
                button: 'Rejeter'
            }
        },
        en: {
            validate: {
                title: 'Confirm Validation',
                message: 'Are you sure you want to validate this payment?',
                button: 'Validate'
            },
            reject: {
                title: 'Confirm Rejection',
                message: 'Are you sure you want to reject this payment?',
                button: 'Reject'
            }
        }
    };
    
    const language = @json($isFrench ? 'fr' : 'en');
    
    // Fonction pour afficher le modal
    function showConfirmModal(action, versementId, libelle, montant) {
        console.log('showConfirmModal called:', action, versementId); // Debug
        
        currentAction = action;
        currentVersementId = versementId;
        
        const modal = document.getElementById('confirmModal');
        const modalContent = document.getElementById('modalContent');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const confirmButton = document.getElementById('confirmButton');
        
        if (!modal || !modalContent) {
            console.error('Modal elements not found');
            return;
        }
        
        // Configuration selon l'action
        if (action === 'validate') {
            modalIcon.innerHTML = '<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            modalIcon.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-green-100';
            confirmButton.className = 'flex-1 px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors focus:ring-2 focus:ring-green-300';
        } else {
            modalIcon.innerHTML = '<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
            modalIcon.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100';
            confirmButton.className = 'flex-1 px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors focus:ring-2 focus:ring-red-300';
        }
        
        // Textes selon la langue
        const actionTexts = translations[language][action];
        modalTitle.textContent = actionTexts.title;
        modalMessage.innerHTML = `
            <p class="mb-2">${actionTexts.message}</p>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="font-semibold text-gray-800">${libelle}</p>
                <p class="text-sm text-gray-600">${montant}</p>
            </div>
        `;
        confirmButton.textContent = actionTexts.button;
        
        // Afficher le modal avec animation
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });
        
        // Focus sur le bouton de confirmation
        setTimeout(() => confirmButton.focus(), 100);
    }
    
    // Fonction pour cacher le modal
    function hideConfirmModal() {
        const modal = document.getElementById('confirmModal');
        const modalContent = document.getElementById('modalContent');
        
        if (!modal || !modalContent) return;
        
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            currentAction = null;
            currentVersementId = null;
        }, 200);
    }
    
    // Fonction pour exécuter l'action
    function executeAction() {
        console.log('executeAction called:', currentAction, currentVersementId); // Debug
        
        if (currentAction && currentVersementId) {
            const formId = currentAction === 'validate' ? 
                `validateForm-${currentVersementId}` : 
                `rejectForm-${currentVersementId}`;
            
            console.log('Looking for form:', formId); // Debug
            const form = document.getElementById(formId);
            
            if (form) {
                console.log('Form found, submitting...'); // Debug
                form.submit();
            } else {
                console.error('Form not found:', formId);
                // Fallback: utiliser l'ancienne méthode
                if (currentAction === 'validate') {
                    if (confirm('Êtes-vous sûr de vouloir valider ce versement ?')) {
                        // Créer et soumettre le formulaire dynamiquement
                        const tempForm = document.createElement('form');
                        tempForm.method = 'POST';
                        tempForm.action = `/versements/${currentVersementId}/valider`;
                        
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken.getAttribute('content');
                            tempForm.appendChild(csrfInput);
                        }
                        
                        document.body.appendChild(tempForm);
                        tempForm.submit();
                    }
                } else {
                    if (confirm('Êtes-vous sûr de vouloir rejeter ce versement ?')) {
                        const tempForm = document.createElement('form');
                        tempForm.method = 'POST';
                        tempForm.action = `/versements/${currentVersementId}`;
                        
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken.getAttribute('content');
                            tempForm.appendChild(csrfInput);
                        }
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        tempForm.appendChild(methodInput);
                        
                        document.body.appendChild(tempForm);
                        tempForm.submit();
                    }
                }
            }
        }
        hideConfirmModal();
    }
    
    // Fonction pour toggle edit form
    function toggleEditForm(id) {
        const form = document.getElementById(`edit-form-${id}`);
        if (form) {
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                const firstInput = form.querySelector('input[type="text"]');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            } else {
                form.classList.add('hidden');
            }
        }
    }
    
    // Gestion des événements quand le DOM est chargé
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded'); // Debug
        
        // Gestion des touches clavier pour le modal
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('confirmModal');
            if (modal && !modal.classList.contains('hidden')) {
                if (event.key === 'Escape') {
                    hideConfirmModal();
                } else if (event.key === 'Enter') {
                    event.preventDefault();
                    executeAction();
                }
            }
        });
        
        // Fermer le modal en cliquant sur l'overlay
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    hideConfirmModal();
                }
            });
        }
    });
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes modalSlideIn {
        from { transform: scale(0.8) translateY(-20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }
    
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Transitions pour le modal */
    #modalContent {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Amélioration de l'accessibilité */
    button:focus, input:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        /* Touch targets */
        button, .btn, a {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Modal responsive */
        #confirmModal .max-w-md {
            max-width: calc(100vw - 2rem);
        }
    }
    
    /* Animation pour les boutons */
    .active\:scale-95:active {
        transform: scale(0.95);
    }
    
    /* Amélioration visuelle des focus rings */
    .focus\:ring-2:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }
</style>
@endsection