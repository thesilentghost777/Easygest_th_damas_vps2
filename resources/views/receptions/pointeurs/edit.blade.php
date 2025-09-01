@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 animate-slide-down">
        <div class="flex items-center mb-4">
            <a href="{{ route('receptions.pointeurs.show', $reception->id) }}" 
               class="mr-4 p-2 text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ $isFrench ? 'Modifier la Réception' : 'Edit Reception' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    #{{ str_pad($reception->id, 6, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('receptions.pointeurs.show', $reception->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors">
                <i class="fas fa-eye mr-2"></i>
                {{ $isFrench ? 'Voir' : 'View' }}
            </a>
            <a href="{{ route('receptions.pointeurs.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ $isFrench ? 'Retour à la liste' : 'Back to list' }}
            </a>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Current Information Card -->
    <div class="mb-6 bg-blue-50 rounded-2xl p-6 border border-blue-200 animate-fade-in">
        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            {{ $isFrench ? 'Informations actuelles' : 'Current Information' }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-medium text-blue-700">
                    {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                </label>
                <p class="text-blue-900 font-semibold">{{ $reception->pointeur->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-blue-700">
                    {{ $isFrench ? 'Date' : 'Date' }}
                </label>
                <p class="text-blue-900 font-semibold">{{ \Carbon\Carbon::parse($reception->date_reception)->format('d/m/Y') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-blue-700">
                    {{ $isFrench ? 'Produit' : 'Product' }}
                </label>
                <p class="text-blue-900 font-semibold">{{ $reception->produit->nom_produit ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-blue-700">
                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                </label>
                <p class="text-blue-900 font-semibold">{{ number_format($reception->quantite_recue, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 animate-fade-in">
        <form action="{{ route('receptions.pointeurs.update', $reception->id) }}" method="POST" id="editReceptionForm">
            @csrf
            @method('PUT')
            
            <div class="p-6 sm:p-8">
                <!-- Form Fields -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Pointeur -->
                    <div>
                        <label for="pointeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-blue-500"></i>
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }} <span class="text-red-500">*</span>
                        </label>
                        <select name="pointeur_id" id="pointeur_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('pointeur_id') border-red-500 @enderror">
                            <option value="">{{ $isFrench ? 'Sélectionner un pointeur' : 'Select a pointer' }}</option>
                            @foreach($pointeurs as $pointeur)
                                <option value="{{ $pointeur->id }}" 
                                        {{ (old('pointeur_id', $reception->pointeur_id) == $pointeur->id) ? 'selected' : '' }}>
                                    {{ $pointeur->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pointeur_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date_reception" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-blue-500"></i>
                            {{ $isFrench ? 'Date de réception' : 'Reception date' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_reception" id="date_reception" required
                               value="{{ old('date_reception', $reception->date_reception) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('date_reception') border-red-500 @enderror">
                        @error('date_reception')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Produit -->
                    <div>
                        <label for="produit_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-blue-500"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }} <span class="text-red-500">*</span>
                        </label>
                        <select name="produit_id" id="produit_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('produit_id') border-red-500 @enderror">
                            <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->code_produit }}" 
                                        data-nom="{{ $produit->nom_produit }}"
                                        data-prix="{{ $produit->prix_unitaire ?? 0 }}"
                                        {{ (old('produit_id', $reception->produit_id) == $produit->code_produit) ? 'selected' : '' }}>
                                    {{ $produit->nom }} ({{ $produit->prix }})
                                </option>
                            @endforeach
                        </select>
                        @error('produit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantité -->
                    <div>
                        <label for="quantite_recue" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-weight mr-1 text-blue-500"></i>
                            {{ $isFrench ? 'Quantité reçue' : 'Received quantity' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantite_recue" id="quantite_recue" required
                               step="0.01" min="0.01"
                               value="{{ old('quantite_recue', $reception->quantite_recue) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('quantite_recue') border-red-500 @enderror">
                        @error('quantite_recue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Changes -->
                <div id="preview-changes" class="hidden mb-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-4 flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        {{ $isFrench ? 'Aperçu des modifications' : 'Preview changes' }}
                    </h3>
                    <div id="changes-content" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Content will be generated by JavaScript -->
                    </div>
                </div>

                <!-- Value Calculation -->
                <div id="value-calculation" class="hidden mb-6 bg-green-50 border border-green-200 rounded-2xl p-6 animate-fade-in">
                    <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2"></i>
                        {{ $isFrench ? 'Calcul de la valeur' : 'Value calculation' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-green-700">
                                {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}
                            </label>
                            <p id="prix-unitaire" class="text-lg font-bold text-green-900">0 FCFA</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-green-700">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </label>
                            <p id="quantite-display" class="text-lg font-bold text-green-900">0</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-green-700">
                                {{ $isFrench ? 'Valeur totale' : 'Total value' }}
                            </label>
                            <p id="valeur-totale" class="text-lg font-bold text-green-600">0 FCFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 sm:px-8 py-4 border-t border-gray-200 rounded-b-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $isFrench ? 'Dernière modification' : 'Last modified' }}: {{ $reception->updated_at->format('d/m/Y à H:i') }}
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('receptions.pointeurs.show', $reception->id) }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-center transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" id="submitBtn" disabled
                                class="px-6 py-3 bg-gray-400 text-white rounded-xl font-medium transition-all duration-200 transform disabled:scale-100 enabled:hover:scale-105 enabled:active:scale-95 enabled:bg-blue-600 enabled:hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>
                            {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editReceptionForm');
    const pointeurSelect = document.getElementById('pointeur_id');
    const dateInput = document.getElementById('date_reception');
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite_recue');
    const previewDiv = document.getElementById('preview-changes');
    const changesContent = document.getElementById('changes-content');
    const valueCalculation = document.getElementById('value-calculation');
    const submitBtn = document.getElementById('submitBtn');

    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    // Valeurs originales
    const originalValues = {
        pointeur_id: '{{ $reception->pointeur_id }}',
        pointeur_name: '{{ $reception->pointeur->name ?? "" }}',
        date_reception: '{{ $reception->date_reception }}',
        produit_id: '{{ $reception->produit_id }}',
        produit_name: '{{ $reception->produit->nom_produit ?? "" }}',
        quantite_recue: '{{ $reception->quantite_recue }}'
    };

    // Fonction pour détecter les changements
    function detectChanges() {
        const currentValues = {
            pointeur_id: pointeurSelect.value,
            pointeur_name: pointeurSelect.options[pointeurSelect.selectedIndex]?.text || '',
            date_reception: dateInput.value,
            produit_id: produitSelect.value,
            produit_name: produitSelect.options[produitSelect.selectedIndex]?.getAttribute('data-nom') || '',
            quantite_recue: quantiteInput.value
        };

        const changes = [];
        let hasChanges = false;

        // Vérifier chaque champ
        if (currentValues.pointeur_id !== originalValues.pointeur_id) {
            changes.push({
                field: isFrench ? 'Pointeur' : 'Pointer',
                old: originalValues.pointeur_name,
                new: currentValues.pointeur_name
            });
            hasChanges = true;
        }

        if (currentValues.date_reception !== originalValues.date_reception) {
            const oldDate = new Date(originalValues.date_reception).toLocaleDateString('fr-FR');
            const newDate = new Date(currentValues.date_reception).toLocaleDateString('fr-FR');
            changes.push({
                field: isFrench ? 'Date de réception' : 'Reception date',
                old: oldDate,
                new: newDate
            });
            hasChanges = true;
        }

        if (currentValues.produit_id !== originalValues.produit_id) {
            changes.push({
                field: isFrench ? 'Produit' : 'Product',
                old: originalValues.produit_name + ' (' + originalValues.produit_id + ')',
                new: currentValues.produit_name + ' (' + currentValues.produit_id + ')'
            });
            hasChanges = true;
        }

        if (parseFloat(currentValues.quantite_recue) !== parseFloat(originalValues.quantite_recue)) {
            changes.push({
                field: isFrench ? 'Quantité reçue' : 'Received quantity',
                old: parseFloat(originalValues.quantite_recue).toFixed(2),
                new: parseFloat(currentValues.quantite_recue).toFixed(2)
            });
            hasChanges = true;
        }

        // Afficher ou masquer l'aperçu
        if (hasChanges && changes.length > 0) {
            showPreview(changes);
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400');
            submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        } else {
            previewDiv.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-400');
            submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        }
    }

    // Afficher l'aperçu des changements
    function showPreview(changes) {
        let html = '';
        changes.forEach(change => {
            html += `
                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                    <div class="font-semibold text-yellow-900 mb-2">${change.field}:</div>
                    <div class="text-sm">
                        <div class="text-gray-500">${isFrench ? 'Ancien' : 'Old'}: <span class="line-through">${change.old}</span></div>
                        <div class="text-green-600 font-medium">${isFrench ? 'Nouveau' : 'New'}: ${change.new}</div>
                    </div>
                </div>
            `;
        });
        changesContent.innerHTML = html;
        previewDiv.classList.remove('hidden');
    }

    // Calculer la valeur
    function calculateValue() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        const prixUnitaire = selectedOption ? parseFloat(selectedOption.getAttribute('data-prix') || 0) : 0;
        const quantite = parseFloat(quantiteInput.value || 0);
        const valeurTotale = prixUnitaire * quantite;

        document.getElementById('prix-unitaire').textContent = prixUnitaire.toLocaleString('fr-FR') + ' FCFA';
        document.getElementById('quantite-display').textContent = quantite.toFixed(2);
        document.getElementById('valeur-totale').textContent = valeurTotale.toLocaleString('fr-FR') + ' FCFA';

        if (produitSelect.value && quantiteInput.value) {
            valueCalculation.classList.remove('hidden');
        } else {
            valueCalculation.classList.add('hidden');
        }
    }

    // Écouteurs d'événements
    pointeurSelect.addEventListener('change', detectChanges);
    dateInput.addEventListener('change', detectChanges);
    produitSelect.addEventListener('change', function() {
        detectChanges();
        calculateValue();
    });
    quantiteInput.addEventListener('input', function() {
        detectChanges();
        calculateValue();
    });

    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const quantite = parseFloat(quantiteInput.value);
        
        if (!quantite || quantite <= 0) {
            e.preventDefault();
            alert(isFrench ? 'La quantité doit être supérieure à 0.' : 'Quantity must be greater than 0.');
            quantiteInput.focus();
            return false;
        }

        // Confirmation si des changements importants
        const pointeurChanged = pointeurSelect.value !== originalValues.pointeur_id;
        const produitChanged = produitSelect.value !== originalValues.produit_id;
        
        if (pointeurChanged || produitChanged) {
            const message = isFrench ? 
                'Vous êtes sur le point de modifier le pointeur ou le produit. Êtes-vous sûr de vouloir continuer ?' :
                'You are about to modify the pointer or product. Are you sure you want to continue?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        }

        // Désactiver le bouton pour éviter les doubles soumissions
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + 
            (isFrench ? 'Mise à jour...' : 'Updating...');
    });

    // Initialiser les calculs
    calculateValue();
    detectChanges();

    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl+S pour sauvegarder
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            if (!submitBtn.disabled) {
                form.submit();
            }
        }
        
        // Escape pour annuler
        if (e.key === 'Escape') {
            const message = isFrench ? 
                'Êtes-vous sûr de vouloir annuler les modifications ?' :
                'Are you sure you want to cancel the modifications?';
            if (confirm(message)) {
                window.location.href = '{{ route("receptions.pointeurs.show", $reception->id) }}';
            }
        }
    });
});
</script>
@endpush
@endsection