@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')

    <!-- Header responsive -->
    <div class="mb-4 lg:mb-6 animate-fade-in">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">
            {{ $isFrench ? 'Modifier la facture' : 'Edit invoice' }} #{{ $facture->reference }}
        </h1>
        <p class="text-gray-600 text-sm lg:text-base mt-1">
            {{ $isFrench ? 'Mise à jour des matières premières de la facture' : 'Update invoice raw materials' }}
        </p>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 rounded-r-lg mb-4 shadow-md animate-slide-in" role="alert">
        <strong class="font-bold">{{ $isFrench ? 'Erreur!' : 'Error!' }}</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('factures-complexe.update', $facture->id) }}" method="POST" id="factureForm" class="bg-white shadow-lg rounded-xl p-4 lg:p-6 animate-fade-in mobile-form">
        @csrf
        @method('PUT')

        <!-- Mobile-optimized form fields -->
        <div class="space-y-4 lg:space-y-6">
            <div class="mobile-field">
                <label for="producteur_id" class="block text-gray-700 font-semibold mb-2 text-sm lg:text-base">
                    {{ $isFrench ? 'Producteur' : 'Producer' }}
                </label>
                <select id="producteur_id" name="producteur_id" class="w-full px-3 py-3 lg:py-2 border border-gray-300 rounded-xl lg:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base transition-all duration-200" required>
                    <option value="">{{ $isFrench ? 'Sélectionner un producteur' : 'Select a producer' }}</option>
                    @foreach ($producteurs as $producteur)
                    <option value="{{ $producteur->id }}" {{ $facture->producteur_id == $producteur->id ? 'selected' : '' }}>
                        {{ $producteur->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mobile-field">
                <label for="notes" class="block text-gray-700 font-semibold mb-2 text-sm lg:text-base">
                    {{ $isFrench ? 'Notes' : 'Notes' }}
                </label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-3 lg:py-2 border border-gray-300 rounded-xl lg:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base transition-all duration-200" placeholder="{{ $isFrench ? 'Notes supplémentaires...' : 'Additional notes...' }}">{{ $facture->notes }}</textarea>
            </div>

            <!-- Materials section with enhanced mobile design -->
            <div class="mobile-materials-section">
                <h2 class="text-lg lg:text-xl font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="mdi mdi-package-variant mr-2 text-blue-600"></i>
                    {{ $isFrench ? 'Matières premières' : 'Raw Materials' }}
                </h2>

                <!-- Desktop table (hidden on mobile) -->
                <div class="hidden lg:block overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200" id="materialsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Unité' : 'Unit' }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Montant' : 'Amount' }}
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Action' : 'Action' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="materialsTableBody">
                            @foreach ($facture->details as $index => $detail)
                            <tr class="material-row">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="matieres[{{ $index }}][id]" class="matiere-select w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                        @foreach ($matieres as $matiere)
                                        <option value="{{ $matiere->id }}"
                                            data-prix="{{ $matiere->complexe->prix_complexe ?? $matiere->prix_unitaire }}"
                                            data-unite="{{ $matiere->unite_classique }}"
                                            {{ $detail->matiere_id == $matiere->id ? 'selected' : '' }}>
                                            {{ $matiere->nom }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="number" name="matieres[{{ $index }}][quantite]" min="0.001" step="0.001" value="{{ $detail->quantite }}" class="quantite-input w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" name="matieres[{{ $index }}][unite]" value="{{ $detail->unite }}" class="unite-input w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="prix-unitaire">{{ number_format($detail->prix_unitaire, 2, ',', ' ') }} FCFA</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="montant">{{ number_format($detail->montant, 2, ',', ' ') }} FCFA</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button type="button" class="text-red-600 hover:text-red-900 delete-row-btn">{{ $isFrench ? 'Supprimer' : 'Delete' }}</button>
                                </td>
                            </tr>
                            @endforeach

                            @if($facture->details->isEmpty())
                            <tr class="material-row">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <select name="matieres[0][id]" class="matiere-select w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                        @foreach ($matieres as $matiere)
                                        <option value="{{ $matiere->id }}"
                                            data-prix="{{ $matiere->complexe->prix_complexe ?? $matiere->prix_unitaire }}"
                                            data-unite="{{ $matiere->unite_classique }}">
                                            {{ $matiere->nom }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="number" name="matieres[0][quantite]" min="0.001" step="0.001" class="quantite-input w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <input type="text" name="matieres[0][unite]" class="unite-input w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="prix-unitaire">-</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="montant">-</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button type="button" class="text-red-600 hover:text-red-900 delete-row-btn">{{ $isFrench ? 'Supprimer' : 'Delete' }}</button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Mobile cards (visible only on mobile) -->
                <div class="lg:hidden space-y-4" id="mobileMaterialsList">
                    @foreach ($facture->details as $index => $detail)
                    <div class="material-row-mobile bg-gray-50 rounded-xl p-4 border-2 border-gray-300 animate-fade-in">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Matière' : 'Material' }}</label>
                                <select name="matieres[{{ $index }}][id]" class="matiere-select w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base" required>
                                    <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                    @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        data-prix="{{ $matiere->complexe->prix_complexe ?? $matiere->prix_unitaire }}"
                                        data-unite="{{ $matiere->unite_classique }}"
                                        {{ $detail->matiere_id == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                    <input type="number" name="matieres[{{ $index }}][quantite]" min="0.001" step="0.001" value="{{ $detail->quantite }}" class="quantite-input w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                                    <input type="text" name="matieres[{{ $index }}][unite]" value="{{ $detail->unite }}" class="unite-input w-full px-3 py-3 border border-gray-300 rounded-xl bg-gray-100 text-base" readonly>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">{{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }}</span>
                                    <span class="prix-unitaire font-medium">{{ number_format($detail->prix_unitaire, 2, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Montant:' : 'Amount:' }}</span>
                                    <span class="montant font-bold text-blue-600">{{ number_format($detail->montant, 2, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            
                            <button type="button" class="delete-row-btn w-full py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors duration-200 text-sm font-medium">
                                <i class="mdi mdi-delete mr-2"></i>{{ $isFrench ? 'Supprimer cette matière' : 'Delete this material' }}
                            </button>
                        </div>
                    </div>
                    @endforeach

                    @if($facture->details->isEmpty())
                    <div class="material-row-mobile bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-300 animate-fade-in">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Matière' : 'Material' }}</label>
                                <select name="matieres[0][id]" class="matiere-select w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base" required>
                                    <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                    @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        data-prix="{{ $matiere->complexe->prix_complexe ?? $matiere->prix_unitaire }}"
                                        data-unite="{{ $matiere->unite_classique }}">
                                        {{ $matiere->nom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                    <input type="number" name="matieres[0][quantite]" min="0.001" step="0.001" class="quantite-input w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                                    <input type="text" name="matieres[0][unite]" class="unite-input w-full px-3 py-3 border border-gray-300 rounded-xl bg-gray-100 text-base" readonly>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">{{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }}</span>
                                    <span class="prix-unitaire font-medium">-</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Montant:' : 'Amount:' }}</span>
                                    <span class="montant font-bold text-blue-600">-</span>
                                </div>
                            </div>
                            
                            <button type="button" class="delete-row-btn w-full py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors duration-200 text-sm font-medium">
                                <i class="mdi mdi-delete mr-2"></i>{{ $isFrench ? 'Supprimer cette matière' : 'Delete this material' }}
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <button type="button" id="addMaterialBtn" class="w-full lg:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                    <i class="mdi mdi-plus-circle mr-2"></i>{{ $isFrench ? 'Ajouter une matière' : 'Add material' }}
                </button>
            </div>

            <!-- Total section -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 animate-fade-in">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                    <span id="totalAmount" class="text-xl lg:text-2xl font-bold text-blue-600">{{ number_format($facture->montant_total, 2, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 lg:gap-4 pt-4">
                <a href="{{ route('factures-complexe.index') }}" class="w-full sm:w-auto text-center bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                    <i class="mdi mdi-content-save mr-2"></i>{{ $isFrench ? 'Mettre à jour la facture' : 'Update invoice' }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialsTableBody = document.getElementById('materialsTableBody');
    const mobileMaterialsList = document.getElementById('mobileMaterialsList');
    const addMaterialBtn = document.getElementById('addMaterialBtn');
    const totalAmountSpan = document.getElementById('totalAmount');
    const isMobile = window.innerWidth < 1024;

    function initRowEvents(row) {
        const matiereSelect = row.querySelector('.matiere-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const uniteInput = row.querySelector('.unite-input');
        const prixUnitaireSpan = row.querySelector('.prix-unitaire');
        const montantSpan = row.querySelector('.montant');
        const deleteBtn = row.querySelector('.delete-row-btn');

        matiereSelect.addEventListener('change', function() {
            const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
            if (selectedOption.value) {
                uniteInput.value = selectedOption.getAttribute('data-unite');
                prixUnitaireSpan.textContent = parseFloat(selectedOption.getAttribute('data-prix')).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' FCFA';
                updateMontant(row);
            } else {
                uniteInput.value = '';
                prixUnitaireSpan.textContent = '-';
                montantSpan.textContent = '-';
            }
        });

        quantiteInput.addEventListener('input', function() {
            updateMontant(row);
        });

        deleteBtn.addEventListener('click', function() {
            const allRows = document.querySelectorAll(isMobile ? '.material-row-mobile' : '.material-row');
            if (allRows.length > 1) {
                row.remove();
                updateRowIndices();
                updateTotalAmount();
            } else {
                alert('{{ $isFrench ? "Vous devez conserver au moins une ligne." : "You must keep at least one line." }}');
            }
        });
    }

    function updateMontant(row) {
        const matiereSelect = row.querySelector('.matiere-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const montantSpan = row.querySelector('.montant');

        if (matiereSelect.value && quantiteInput.value > 0) {
            const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
            const prix = parseFloat(selectedOption.getAttribute('data-prix'));
            const quantite = parseFloat(quantiteInput.value);
            const montant = prix * quantite;

            montantSpan.textContent = montant.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' FCFA';
        } else {
            montantSpan.textContent = '-';
        }

        updateTotalAmount();
    }

    function updateRowIndices() {
        const rows = document.querySelectorAll(isMobile ? '.material-row-mobile' : '.material-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('[name^="matieres["]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/matieres\[\d+\]/, `matieres[${index}]`);
                input.setAttribute('name', newName);
            });
        });
    }

    function updateTotalAmount() {
        let total = 0;
        const montantSpans = document.querySelectorAll('.montant');

        montantSpans.forEach(span => {
            if (span.textContent !== '-') {
                total += parseFloat(span.textContent.replace(/[^\d,.-]/g, '').replace(',', '.'));
            }
        });

        totalAmountSpan.textContent = total.toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' FCFA';
    }

    function addNewRow() {
        const rowCount = document.querySelectorAll(isMobile ? '.material-row-mobile' : '.material-row').length;
        
        if (isMobile) {
            const newRow = document.querySelector('.material-row-mobile').cloneNode(true);
            newRow.querySelector('.matiere-select').selectedIndex = 0;
            newRow.querySelector('.quantite-input').value = '';
            newRow.querySelector('.unite-input').value = '';
            newRow.querySelector('.prix-unitaire').textContent = '-';
            newRow.querySelector('.montant').textContent = '-';
            
            newRow.querySelectorAll('[name^="matieres["]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/matieres\[\d+\]/, `matieres[${rowCount}]`);
                input.setAttribute('name', newName);
            });
            
            mobileMaterialsList.appendChild(newRow);
            initRowEvents(newRow);
        } else {
            const newRow = document.querySelector('.material-row').cloneNode(true);
            newRow.querySelector('.matiere-select').selectedIndex = 0;
            newRow.querySelector('.quantite-input').value = '';
            newRow.querySelector('.unite-input').value = '';
            newRow.querySelector('.prix-unitaire').textContent = '-';
            newRow.querySelector('.montant').textContent = '-';

            newRow.querySelectorAll('[name^="matieres["]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/matieres\[\d+\]/, `matieres[${rowCount}]`);
                input.setAttribute('name', newName);
            });

            materialsTableBody.appendChild(newRow);
            initRowEvents(newRow);
        }
    }

    document.querySelectorAll(isMobile ? '.material-row-mobile' : '.material-row').forEach(row => {
        initRowEvents(row);
    });

    addMaterialBtn.addEventListener('click', addNewRow);
    updateTotalAmount();
});
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-form {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .mobile-field {
            transition: all 0.2s ease-out;
        }
        .mobile-field:focus-within {
            transform: translateY(-2px);
        }
        /* Touch targets */
        button, input, select, textarea {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
