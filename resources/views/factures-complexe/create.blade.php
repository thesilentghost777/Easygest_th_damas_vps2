@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-8">
    <div class="mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
            {{ $isFrench ? 'Créer une facture pour le complexe' : 'Create invoice for complex' }}
        </h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">
            {{ $isFrench ? 'Ajouter des matières premières à la facture' : 'Add raw materials to the invoice' }}
        </p>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">{{ $isFrench ? 'Erreur!' : 'Error!' }}</strong>
        <ul class="mt-1">
            @foreach ($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('factures-complexe.store') }}" method="POST" id="factureForm" class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        @csrf

        <!-- Section Producteur -->
        <div class="mb-4 sm:mb-6">
            <label for="producteur_id" class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                {{ $isFrench ? 'Producteur' : 'Producer' }}
            </label>
            <select id="producteur_id" name="producteur_id" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">{{ $isFrench ? 'Sélectionner un producteur' : 'Select a producer' }}</option>
                @foreach ($producteurs as $producteur)
                <option value="{{ $producteur->id }}">{{ $producteur->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Section Notes -->
        <div class="mb-4 sm:mb-6">
            <label for="notes" class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                {{ $isFrench ? 'Notes' : 'Notes' }}
            </label>
            <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ $isFrench ? 'Ajouter des notes...' : 'Add notes...' }}"></textarea>
        </div>

        <!-- Section Matières premières -->
        <div class="mb-4 sm:mb-6">
            <h2 class="text-base sm:text-lg font-semibold mb-3 text-gray-900">
                {{ $isFrench ? 'Matières premières' : 'Raw materials' }}
            </h2>

            <!-- Table responsive avec scroll horizontal -->
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200" id="materialsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[140px]">
                                        {{ $isFrench ? 'Matière' : 'Material' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                        {{ $isFrench ? 'Unité' : 'Unit' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[110px]">
                                        {{ $isFrench ? 'Qté unitaire' : 'Unit qty' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[110px]">
                                        {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                        {{ $isFrench ? 'Montant' : 'Amount' }}
                                    </th>
                                    <th scope="col" class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                        {{ $isFrench ? 'Action' : 'Action' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="materialsTableBody">
                                <tr class="material-row">
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <select name="matieres[0][id]" class="matiere-select w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="">{{ $isFrench ? 'Sélectionner...' : 'Select...' }}</option>
                                            @foreach ($matieres as $matiere)
                                            <option value="{{ $matiere->id }}"
                                                data-prix="{{ $matiere->complexe->prix_complexe ?? $matiere->prix_unitaire }}"
                                                data-unite="{{ $matiere->unite_classique }}"
                                                data-qte-par-unite="{{ $matiere->quantite_par_unite }}">
                                                {{ $matiere->nom }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <input type="number" name="matieres[0][quantite]" min="0.001" step="0.001" class="quantite-input w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <input type="text" name="matieres[0][unite]" class="unite-input w-full px-2 py-1 text-xs sm:text-sm border border-gray-300 rounded-md bg-gray-50" readonly>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <span class="quantite-unitaire text-xs sm:text-sm text-gray-900 font-medium">-</span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <span class="prix-unitaire text-xs sm:text-sm text-gray-900 font-medium">-</span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <span class="montant text-xs sm:text-sm text-gray-900 font-semibold">-</span>
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <button type="button" class="text-red-600 hover:text-red-900 delete-row-btn text-xs sm:text-sm font-medium">
                                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bouton ajouter matière -->
            <div class="mt-4">
                <button type="button" id="addMaterialBtn" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm sm:text-base font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ $isFrench ? 'Ajouter une matière' : 'Add material' }}
                </button>
            </div>
        </div>

        <!-- Total -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="text-right">
                <div class="text-lg sm:text-xl font-bold text-gray-900">
                    {{ $isFrench ? 'Total:' : 'Total:' }} <span id="totalAmount" class="text-indigo-600">0 FCFA</span>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
            <a href="{{ route('factures-complexe.index') }}" class="w-full sm:w-auto text-center bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm sm:text-base font-medium transition-colors duration-200">
                {{ $isFrench ? 'Annuler' : 'Cancel' }}
            </a>
            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm sm:text-base font-medium transition-colors duration-200">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ $isFrench ? 'Enregistrer la facture' : 'Save invoice' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const materialsTableBody = document.getElementById('materialsTableBody');
    const addMaterialBtn = document.getElementById('addMaterialBtn');
    const totalAmountSpan = document.getElementById('totalAmount');
    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    // Messages multilingues
    const messages = {
        fr: {
            keepOneLine: 'Vous devez conserver au moins une ligne.',
            selectMaterial: 'Sélectionner...',
            delete: 'Supprimer'
        },
        en: {
            keepOneLine: 'You must keep at least one line.',
            selectMaterial: 'Select...',
            delete: 'Delete'
        }
    };

    const msg = isFrench ? messages.fr : messages.en;

    // Fonction pour initialiser les écouteurs d'événements sur une ligne
    function initRowEvents(row) {
        const matiereSelect = row.querySelector('.matiere-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const uniteInput = row.querySelector('.unite-input');
        const quantiteUnitaireSpan = row.querySelector('.quantite-unitaire');
        const prixUnitaireSpan = row.querySelector('.prix-unitaire');
        const montantSpan = row.querySelector('.montant');
        const deleteBtn = row.querySelector('.delete-row-btn');

        // Mettre à jour l'unité lorsqu'une matière est sélectionnée
        matiereSelect.addEventListener('change', function() {
            const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
            if (selectedOption.value) {
                uniteInput.value = selectedOption.getAttribute('data-unite');
                prixUnitaireSpan.textContent = parseFloat(selectedOption.getAttribute('data-prix')).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' FCFA';

                updateQuantiteUnitaire(row);
                updateMontant(row);
            } else {
                uniteInput.value = '';
                prixUnitaireSpan.textContent = '-';
                montantSpan.textContent = '-';
                quantiteUnitaireSpan.textContent = '-';
            }
        });

        // Mettre à jour le montant lorsque la quantité change
        quantiteInput.addEventListener('input', function() {
            updateQuantiteUnitaire(row);
            updateMontant(row);
        });

        // Supprimer la ligne
        deleteBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.material-row').length > 1) {
                row.remove();
                updateRowIndices();
                updateTotalAmount();
            } else {
                alert(msg.keepOneLine);
            }
        });
    }

    // Fonction pour mettre à jour la quantité unitaire
    function updateQuantiteUnitaire(row) {
        const matiereSelect = row.querySelector('.matiere-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const quantiteUnitaireSpan = row.querySelector('.quantite-unitaire');

        if (matiereSelect.value && quantiteInput.value > 0) {
            const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
            const quantiteParUnite = parseFloat(selectedOption.getAttribute('data-qte-par-unite'));
            const quantite = parseFloat(quantiteInput.value);

            if (quantiteParUnite > 0) {
                const quantiteUnitaire = quantite / quantiteParUnite;
                quantiteUnitaireSpan.textContent = quantiteUnitaire.toLocaleString('fr-FR', {
                    minimumFractionDigits: 1,
                    maximumFractionDigits: 1
                });
            } else {
                quantiteUnitaireSpan.textContent = '-';
            }
        } else {
            quantiteUnitaireSpan.textContent = '-';
        }
    }

    // Fonction pour mettre à jour le montant d'une ligne
    function updateMontant(row) {
        const matiereSelect = row.querySelector('.matiere-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const quantiteUnitaireSpan = row.querySelector('.quantite-unitaire');
        const montantSpan = row.querySelector('.montant');

        if (matiereSelect.value && quantiteInput.value > 0) {
            const selectedOption = matiereSelect.options[matiereSelect.selectedIndex];
            const prix = parseFloat(selectedOption.getAttribute('data-prix'));

            // Récupérer la quantité unitaire depuis le span
            let quantiteUnitaire = 0;
            if (quantiteUnitaireSpan.textContent !== '-') {
                quantiteUnitaire = parseFloat(quantiteUnitaireSpan.textContent.replace(/[^\d,.-]/g, '').replace(',', '.'));
            }

            // Calculer le montant en utilisant prix * quantité unitaire
            const montant = prix * quantiteUnitaire;

            montantSpan.textContent = montant.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' FCFA';
        } else {
            montantSpan.textContent = '-';
        }

        updateTotalAmount();
    }

    // Fonction pour mettre à jour les indices des lignes
    function updateRowIndices() {
        const rows = document.querySelectorAll('.material-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('[name^="matieres["]').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/matieres\[\d+\]/, `matieres[${index}]`);
                input.setAttribute('name', newName);
            });
        });
    }

    // Fonction pour calculer le montant total
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

    // Ajouter une nouvelle ligne
    function addNewRow() {
        const rowCount = document.querySelectorAll('.material-row').length;
        const newRow = document.querySelector('.material-row').cloneNode(true);

        // Réinitialiser les champs de la nouvelle ligne
        newRow.querySelector('.matiere-select').selectedIndex = 0;
        newRow.querySelector('.quantite-input').value = '';
        newRow.querySelector('.unite-input').value = '';
        newRow.querySelector('.prix-unitaire').textContent = '-';
        newRow.querySelector('.montant').textContent = '-';
        newRow.querySelector('.quantite-unitaire').textContent = '-';

        // Mettre à jour les noms des champs
        newRow.querySelectorAll('[name^="matieres["]').forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/matieres\[\d+\]/, `matieres[${rowCount}]`);
            input.setAttribute('name', newName);
        });

        // Mettre à jour le texte du bouton supprimer
        newRow.querySelector('.delete-row-btn').textContent = msg.delete;

        materialsTableBody.appendChild(newRow);
        initRowEvents(newRow);
    }

    // Initialiser les événements sur la première ligne
    initRowEvents(document.querySelector('.material-row'));

    // Écouteur pour le bouton d'ajout de matière
    addMaterialBtn.addEventListener('click', addNewRow);
});
</script>
@endsection