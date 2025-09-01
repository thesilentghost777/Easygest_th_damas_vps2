@extends('layouts.app')

@section('title', $currentLanguage == 'fr' ? 'Application de la Réduction' : 'Discount Application')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white p-6">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-lg p-3 mr-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $currentLanguage == 'fr' ? 'Application de la Réduction' : 'Discount Application' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $currentLanguage == 'fr' ? 'Gérez vos réductions facilement' : 'Manage your discounts easily' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Alert -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="bg-white/20 rounded-lg p-2 mr-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-lg font-semibold">
                            {{ $currentLanguage == 'fr' ? 'Résumé de la sélection' : 'Selection Summary' }}
                        </div>
                        <div class="text-blue-100">
                            <span class="font-bold text-xl">{{ count($commandes) }}</span>
                            {{ $currentLanguage == 'fr' ? 'commande(s) sélectionnée(s) pour un total de' : 'order(s) selected for a total of' }}
                            <span class="font-bold text-xl">{{ number_format($totalGeneral, 2) }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Orders List -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white p-6">
                        <h2 class="text-xl font-bold flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                            {{ $currentLanguage == 'fr' ? 'Détail des commandes' : 'Order Details' }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-2 font-semibold text-gray-700">
                                            {{ $currentLanguage == 'fr' ? 'Commande' : 'Order' }}
                                        </th>
                                        <th class="text-left py-4 px-2 font-semibold text-gray-700 hidden md:table-cell">
                                            {{ $currentLanguage == 'fr' ? 'Produit' : 'Product' }}
                                        </th>
                                        <th class="text-center py-4 px-2 font-semibold text-gray-700">
                                            {{ $currentLanguage == 'fr' ? 'Qté' : 'Qty' }}
                                        </th>
                                        <th class="text-right py-4 px-2 font-semibold text-gray-700">
                                            {{ $currentLanguage == 'fr' ? 'Prix Unit.' : 'Unit Price' }}
                                        </th>
                                        <th class="text-right py-4 px-2 font-semibold text-gray-700">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commandes as $commande)
                                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-200">
                                            <td class="py-4 px-2">
                                                <div class="font-semibold text-blue-600">{{ $commande->libelle }}</div>
                                                <small class="text-gray-500 md:hidden">{{ $commande->nom_produit }}</small>
                                            </td>
                                            <td class="py-4 px-2 hidden md:table-cell">
                                                <div class="font-medium text-gray-800">{{ $commande->nom_produit }}</div>
                                                <small class="text-gray-500">{{ $commande->categorie }}</small>
                                            </td>
                                            <td class="py-4 px-2 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ $commande->quantite }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right font-medium text-gray-700">
                                                {{ number_format($commande->prix_unitaire, 2) }} FCFA
                                            </td>
                                            <td class="py-4 px-2 text-right font-bold text-blue-600">
                                                {{ number_format($commande->sous_total, 2) }} FCFA
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gradient-to-r from-blue-50 to-green-50 border-t-2 border-blue-200">
                                        <th colspan="4" class="py-4 px-2 text-right font-bold text-gray-800">
                                            {{ $currentLanguage == 'fr' ? 'Total Général :' : 'Grand Total:' }}
                                        </th>
                                        <th class="py-4 px-2 text-right font-bold text-2xl text-blue-600">
                                            {{ number_format($totalGeneral, 2) }} FCFA
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount Panel -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-green-100 overflow-hidden sticky top-8">
                    <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white p-6">
                        <h3 class="text-lg font-bold flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM3 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $currentLanguage == 'fr' ? 'Calcul de la Réduction' : 'Discount Calculation' }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <form id="reductionForm" class="space-y-6">
                            <!-- Original Total -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $currentLanguage == 'fr' ? 'Total Original' : 'Original Total' }}
                                </label>
                                <div class="relative">
                                    <input type="text" class="w-full px-4 py-3 text-right font-bold text-xl border-2 border-gray-200 rounded-lg bg-gray-50" 
                                           value="{{ number_format($totalGeneral, 2) }}" readonly>
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">FCFA</span>
                                </div>
                            </div>

                            <!-- Discount Percentage -->
                            <div>
                                <label for="pourcentage" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $currentLanguage == 'fr' ? 'Pourcentage de Réduction' : 'Discount Percentage' }}
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="pourcentage" 
                                           class="w-full px-4 py-3 text-right text-xl border-2 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                           placeholder="0.00" 
                                           min="0" 
                                           max="100" 
                                           step="0.01">
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-blue-500 font-bold text-xl">%</span>
                                </div>
                            </div>

                            <button type="button" id="appliquerReduction" class="w-full bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $currentLanguage == 'fr' ? 'Appliquer la Réduction' : 'Apply Discount' }}
                            </button>

                            <!-- Results -->
                            <div id="resultatReduction" class="hidden space-y-4">
                                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-xl border border-green-200">
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-700 font-medium">
                                                {{ $currentLanguage == 'fr' ? 'Montant de la réduction :' : 'Discount amount:' }}
                                            </span>
                                            <span class="font-bold text-red-600 text-lg">
                                                -<span id="montantReduction">0.00</span> FCFA
                                            </span>
                                        </div>
                                        <hr class="border-gray-300">
                                        <div class="flex justify-between items-center">
                                            <span class="font-bold text-gray-800">
                                                {{ $currentLanguage == 'fr' ? 'Montant Final :' : 'Final Amount:' }}
                                            </span>
                                            <span class="font-bold text-green-600 text-2xl">
                                                <span id="montantFinal">0.00</span> FCFA
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="validerCommandes" class="w-full bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $currentLanguage == 'fr' ? 'Valider et Enregistrer' : 'Validate and Save' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-6">
                    <a href="{{ route('commandes.reduction.index') }}" class="w-full inline-flex items-center justify-center px-6 py-3 border-2 border-blue-200 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $currentLanguage == 'fr' ? 'Retour à la sélection' : 'Back to Selection' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 rounded-lg p-3 mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">
                    {{ $currentLanguage == 'fr' ? 'Confirmation de Validation' : 'Validation Confirmation' }}
                </h3>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    {{ $currentLanguage == 'fr' ? 'Êtes-vous sûr de vouloir valider ces' : 'Are you sure you want to validate these' }}
                    <strong><span id="modalNombreCommandes"></span></strong>
                    {{ $currentLanguage == 'fr' ? 'commandes avec une réduction de' : 'orders with a discount of' }}
                    <strong><span id="modalPourcentage"></span>%</strong> ?
                </p>
                <p class="text-gray-600 mb-4">
                    {{ $currentLanguage == 'fr' ? 'Le montant final sera de' : 'The final amount will be' }}
                    <strong><span id="modalMontantFinal"></span> FCFA</strong>
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-yellow-800 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $currentLanguage == 'fr' ? 'Cette action est irréversible.' : 'This action is irreversible.' }}
                    </p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" id="cancelModal" class="flex-1 px-4 py-3 text-gray-600 border-2 border-gray-200 rounded-lg hover:bg-gray-50 font-semibold transition-colors">
                    {{ $currentLanguage == 'fr' ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="button" id="confirmerValidation" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg hover:from-green-700 hover:to-blue-700 font-semibold transition-all">
                    {{ $currentLanguage == 'fr' ? 'Confirmer' : 'Confirm' }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600 font-medium">
            {{ $currentLanguage == 'fr' ? 'Validation en cours...' : 'Validation in progress...' }}
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pourcentageInput = document.getElementById('pourcentage');
    const appliquerBtn = document.getElementById('appliquerReduction');
    const validerBtn = document.getElementById('validerCommandes');
    const resultatDiv = document.getElementById('resultatReduction');
    const montantReductionSpan = document.getElementById('montantReduction');
    const montantFinalSpan = document.getElementById('montantFinal');
    
    const totalOriginal = {{ $totalGeneral }};
    const commandesIds = @json($commandesIds);
    const language = '{{ $currentLanguage }}';
    
    let montantFinalCalcule = 0;
    let pourcentageApplique = 0;

    // Modal management
    function showModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Apply discount
    appliquerBtn.addEventListener('click', function() {
        const pourcentage = parseFloat(pourcentageInput.value);
        
        if (isNaN(pourcentage) || pourcentage < 0 || pourcentage > 100) {
            const message = language === 'fr' 
                ? 'Veuillez entrer un pourcentage valide (0-100).' 
                : 'Please enter a valid percentage (0-100).';
            alert(message);
            return;
        }

        // AJAX call to calculate discount
        fetch('{{ route("commandes.reduction.apply_reduction") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                commandes_ids: commandesIds,
                pourcentage_reduction: pourcentage,
                total_original: totalOriginal
            })
        })
        .then(response => response.json())
        .then(data => {
            montantReductionSpan.textContent = data.montant_reduction;
            montantFinalSpan.textContent = data.montant_final;
            montantFinalCalcule = parseFloat(data.montant_final.replace(/,/g, ''));
            pourcentageApplique = pourcentage;
            
            resultatDiv.classList.remove('hidden');
            resultatDiv.style.animation = 'fadeInUp 0.5s ease-out';
        })
        .catch(error => {
            console.error('Error:', error);
            const message = language === 'fr' 
                ? 'Erreur lors du calcul de la réduction.' 
                : 'Error calculating discount.';
            alert(message);
        });
    });

    // Validate orders
    validerBtn.addEventListener('click', function() {
        document.getElementById('modalNombreCommandes').textContent = commandesIds.length;
        document.getElementById('modalPourcentage').textContent = pourcentageApplique;
        document.getElementById('modalMontantFinal').textContent = montantFinalSpan.textContent;
        
        showModal('confirmationModal');
    });

    // Cancel modal
    document.getElementById('cancelModal').addEventListener('click', function() {
        hideModal('confirmationModal');
    });

    // Confirm validation
    document.getElementById('confirmerValidation').addEventListener('click', function() {
        hideModal('confirmationModal');
        showModal('loadingModal');

        // AJAX call to validate orders
        fetch('{{ route("commandes.reduction.valider") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                commandes_ids: commandesIds,
                pourcentage_reduction: pourcentageApplique,
                total_original: totalOriginal,
                montant_final: montantFinalCalcule
            })
        })
        .then(response => response.json())
        .then(data => {
            hideModal('loadingModal');
            
            if (data.success) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-md';
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <div class="font-bold">${language === 'fr' ? 'Succès!' : 'Success!'}</div>
                            <div class="text-sm">${data.message}</div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(alertDiv);
                
                // Disable form
                document.getElementById('reductionForm').style.opacity = '0.6';
                document.getElementById('reductionForm').style.pointerEvents = 'none';
                
                // Redirect after delay
                setTimeout(() => {
                    window.location.href = '{{ route("commandes.reduction.index") }}';
                }, 3000);
                
            } else {
                const message = language === 'fr' ? 'Erreur: ' : 'Error: ';
                alert(message + data.message);
            }
        })
        .catch(error => {
            hideModal('loadingModal');
            console.error('Error:', error);
            const message = language === 'fr' 
                ? 'Erreur lors de la validation des commandes.' 
                : 'Error validating orders.';
            alert(message);
        });
    });

    // Allow applying discount by pressing Enter
    pourcentageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            appliquerBtn.click();
        }
    });

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'confirmationModal') {
            hideModal('confirmationModal');
        }
    });
});
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sticky {
    position: -webkit-sticky;
    position: sticky;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
}

/* Loading animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}
</style>
@endsection