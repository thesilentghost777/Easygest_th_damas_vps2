@extends('layouts.app')

@section('title', ($isFrench ?? true) ? 'Validation Commandes avec Réduction' : 'Order Validation with Reduction')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-2">
                        <i class="fas fa-percent text-blue-600 mr-3"></i>
                        {{ ($isFrench ?? true) ? 'Validation Commandes' : 'Order Validation' }}
                    </h1>
                    <p class="text-slate-600">
                        {{ ($isFrench ?? true) ? 'Gérez les commandes avec réduction' : 'Manage orders with reduction' }}
                    </p>
                </div>
                <div class="flex items-center space-x-2 text-sm text-slate-500">
                    <i class="fas fa-clock"></i>
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        @if($commandes->count() > 0)
            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <form id="selectionForm" action="{{ route('commandes.reduction.selection') }}" method="POST">
                    @csrf
                    
                    <!-- Action Bar -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Selection Controls -->
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="button" id="selectAll" 
                                        class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                                    <i class="fas fa-check-double mr-2"></i>
                                    {{ ($isFrench ?? true) ? 'Tout sélectionner' : 'Select All' }}
                                </button>
                                <button type="button" id="unselectAll" 
                                        class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                                    <i class="fas fa-times mr-2"></i>
                                    {{ ($isFrench ?? true) ? 'Tout désélectionner' : 'Unselect All' }}
                                </button>
                                <div class="bg-white/20 rounded-lg px-3 py-2">
                                    <span class="text-white font-medium">
                                        <span id="selectedCount">0</span> 
                                        {{ ($isFrench ?? true) ? 'sélectionnée(s)' : 'selected' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" id="terminerSelection" disabled
                                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg transition-all duration-200 font-medium shadow-lg">
                                <i class="fas fa-arrow-right mr-2"></i>
                                {{ ($isFrench ?? true) ? 'Terminer la sélection' : 'Complete Selection' }}
                            </button>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="p-6">
                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left">
                                            <input type="checkbox" id="masterCheckbox" 
                                                   class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 focus:ring-2">
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                            {{ ($isFrench ?? true) ? 'Commande' : 'Order' }}
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                            {{ ($isFrench ?? true) ? 'Date' : 'Date' }}
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                            {{ ($isFrench ?? true) ? 'Produit' : 'Product' }}
                                        </th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                                            {{ ($isFrench ?? true) ? 'Qté' : 'Qty' }}
                                        </th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-700">
                                            {{ ($isFrench ?? true) ? 'Prix Unit.' : 'Unit Price' }}
                                        </th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-700">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($commandes as $commande)
                                        @php
                                            $sousTotal = $commande->prix_unitaire * $commande->quantite;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" 
                                                       name="commandes_ids[]" 
                                                       value="{{ $commande->id }}" 
                                                       class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 focus:ring-2 commande-checkbox">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-slate-800">{{ $commande->libelle }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-slate-600">
                                                    {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-slate-800 font-medium">{{ $commande->nom_produit }}</div>
                                                <div class="text-sm text-slate-500">{{ $commande->categorie }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ $commande->quantite }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-slate-700">
                                                {{ number_format($commande->prix_unitaire, 2) }} FCFA
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-800">
                                                {{ number_format($sousTotal, 2) }} FCFA
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="lg:hidden space-y-4">
                            @foreach($commandes as $commande)
                                @php
                                    $sousTotal = $commande->prix_unitaire * $commande->quantite;
                                @endphp
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <input type="checkbox" 
                                               name="commandes_ids[]" 
                                               value="{{ $commande->id }}" 
                                               class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 focus:ring-2 commande-checkbox mt-1">
                                        <div class="flex-1 ml-4">
                                            <h3 class="font-semibold text-slate-800">{{ $commande->libelle }}</h3>
                                            <p class="text-sm text-slate-500">
                                                {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-slate-500">{{ ($isFrench ?? true) ? 'Produit:' : 'Product:' }}</span>
                                            <div class="font-medium text-slate-800">{{ $commande->nom_produit }}</div>
                                            <div class="text-slate-500">{{ $commande->categorie }}</div>
                                        </div>
                                        <div>
                                            <span class="text-slate-500">{{ ($isFrench ?? true) ? 'Quantité:' : 'Quantity:' }}</span>
                                            <div class="font-medium text-slate-800">{{ $commande->quantite }}</div>
                                        </div>
                                        <div>
                                            <span class="text-slate-500">{{ ($isFrench ?? true) ? 'Prix unitaire:' : 'Unit price:' }}</span>
                                            <div class="font-mono text-slate-800">{{ number_format($commande->prix_unitaire, 2) }} FCFA</div>
                                        </div>
                                        <div>
                                            <span class="text-slate-500">Total:</span>
                                            <div class="font-mono font-bold text-blue-600">{{ number_format($sousTotal, 2) }} FCFA</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="text-center py-16 px-6">
                    <div class="mx-auto w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-inbox text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">
                        {{ ($isFrench ?? true) ? 'Aucune commande trouvée' : 'No orders found' }}
                    </h3>
                    <p class="text-slate-500 max-w-md mx-auto">
                        {{ ($isFrench ?? true) ? 'Toutes les commandes ont déjà été validées ou il n\'y a pas de commandes en attente.' : 'All orders have been validated or there are no pending orders.' }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const masterCheckbox = document.getElementById('masterCheckbox');
    const checkboxes = document.querySelectorAll('.commande-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const unselectAllBtn = document.getElementById('unselectAll');
    const selectedCountSpan = document.getElementById('selectedCount');
    const terminerBtn = document.getElementById('terminerSelection');

    function updateUI() {
        const checkedBoxes = document.querySelectorAll('.commande-checkbox:checked');
        const checkedCount = checkedBoxes.length;
        
        selectedCountSpan.textContent = checkedCount;
        terminerBtn.disabled = checkedCount === 0;
        
        // Update master checkbox
        if (checkedCount === 0) {
            if (masterCheckbox) {
                masterCheckbox.indeterminate = false;
                masterCheckbox.checked = false;
            }
        } else if (checkedCount === checkboxes.length) {
            if (masterCheckbox) {
                masterCheckbox.indeterminate = false;
                masterCheckbox.checked = true;
            }
        } else {
            if (masterCheckbox) {
                masterCheckbox.indeterminate = true;
            }
        }
        
        // Add/remove selection styling
        checkboxes.forEach(checkbox => {
            const card = checkbox.closest('.bg-slate-50, tr');
            if (checkbox.checked) {
                if (card) {
                    card.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                }
            } else {
                if (card) {
                    card.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                }
            }
        });
    }

    // Event listeners
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateUI();
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateUI);
    });

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateUI();
        });
    }

    if (unselectAllBtn) {
        unselectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateUI();
        });
    }

    // Form validation
    const form = document.getElementById('selectionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.commande-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                @if(($isFrench ?? true))
                    alert('Veuillez sélectionner au moins une commande.');
                @else
                    alert('Please select at least one order.');
                @endif
            }
        });
    }

    // Initialize UI
    updateUI();
});
</script>

<style>
/* Custom animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Focus styles */
input[type="checkbox"]:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}

/* Table row hover effect */
tr:hover {
    transform: translateX(2px);
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection