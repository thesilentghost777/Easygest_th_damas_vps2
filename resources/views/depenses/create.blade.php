@extends('layouts.app')

@section('content')
<!-- Modern Gradient Background -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-blue-100 mb-8 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">
                            {{ $isFrench ? 'Nouvelle Dépense' : 'New Expense' }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ $isFrench ? 'Ajouter une nouvelle dépense au système' : 'Add a new expense to the system' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('depenses.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-300 hover:scale-105 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour à la liste' : 'Back to list' }}
                </a>
            </div>

            <!-- Current Balance Alert -->
            <div class="p-6">
                <div class="bg-gradient-to-r from-blue-50 to-green-50 border-l-4 border-blue-400 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-wallet text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-blue-800 font-medium">
                                {{ $isFrench ? 'Solde actuel' : 'Current Balance' }}
                            </p>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ number_format($solde->montant ?? 0, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6 animate-fade-in">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-red-800 font-medium mb-2">
                                    {{ $isFrench ? 'Erreurs de validation' : 'Validation Errors' }}
                                </h3>
                                <ul class="text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex items-center">
                                            <i class="fas fa-dot-circle text-xs mr-2"></i>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Main Form with CSRF and duplicate prevention -->
                <form action="{{ route('depenses.store') }}" method="POST" class="space-y-8" id="expenseForm" data-submitted="false">
                    @csrf
                    <input type="hidden" name="form_token" value="{{ Str::random(40) }}">
                    
                    <!-- Basic Information Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 px-6 py-4">
                            <h3 class="text-white font-semibold flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ $isFrench ? 'Informations générales' : 'General Information' }}
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Expense Name -->
                                <div class="space-y-2">
                                    <label for="nom" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Nom de la dépense' : 'Expense Name' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               name="nom" 
                                               id="nom" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                               value="{{ old('nom') }}" 
                                               placeholder="{{ $isFrench ? 'Entrez le nom de la dépense' : 'Enter expense name' }}"
                                               maxlength="255"
                                               required>
                                    </div>
                                </div>

                                <!-- Expense Type -->
                                <div class="space-y-2">
                                    <label for="type" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Type de dépense' : 'Expense Type' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-list text-gray-400"></i>
                                        </div>
                                        <select name="type" 
                                                id="type" 
                                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 appearance-none"
                                                required>
                                            <option value="">
                                                {{ $isFrench ? 'Sélectionner un type' : 'Select a type' }}
                                            </option>
                                            @php
                                                $expenseTypes = [
                                                    'achat_matiere' => $isFrench ? 'Achat de matière' : 'Material Purchase',
                                                    'livraison_matiere' => $isFrench ? 'Livraison de matière' : 'Material Delivery',
                                                    'reparation' => $isFrench ? 'Réparation' : 'Repair',
                                                    'depense_fiscale' => $isFrench ? 'Dépense fiscale' : 'Tax Expense',
                                                    'autre' => $isFrench ? 'Autre' : 'Other'
                                                ];
                                            @endphp
                                            @foreach($expenseTypes as $value => $label)
                                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="space-y-2">
                                    <label for="date" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Date' : 'Date' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                        <input type="date" 
                                               name="date" 
                                               id="date" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                               value="{{ old('date', date('Y-m-d')) }}" 
                                               max="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="space-y-2">
                                    <label for="prix" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Prix (FCFA)' : 'Price (FCFA)' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-money-bill text-gray-400"></i>
                                        </div>
                                        <input type="number" 
                                               name="prix" 
                                               id="prix" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                               value="{{ old('prix') }}" 
                                               min="1" 
                                               max="999999999"
                                               step="1" 
                                               placeholder="{{ $isFrench ? 'Entrez le prix exact' : 'Enter exact price' }}"
                                               required>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $isFrench ? 'Saisissez le prix exact de la dépense' : 'Enter the exact expense amount' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Material Fields Section (Hidden by default) -->
                    <div id="matiere-fields" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" style="display: none;">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-4">
                            <h3 class="text-white font-semibold flex items-center">
                                <i class="fas fa-boxes mr-2"></i>
                                {{ $isFrench ? 'Détails de la matière' : 'Material Details' }}
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Material Selection -->
                                <div class="space-y-2">
                                    <label for="idm" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Matière' : 'Material' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-cube text-gray-400"></i>
                                        </div>
                                        <select name="idm" 
                                                id="idm" 
                                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 appearance-none">
                                            <option value="">
                                                {{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}
                                            </option>
                                            @if(isset($matieres) && $matieres->count() > 0)
                                                @foreach($matieres as $matiere)
                                                    <option value="{{ $matiere->id }}" 
                                                            data-prix="{{ $matiere->prix_unitaire ?? 0 }}"
                                                            {{ old('idm') == $matiere->id ? 'selected' : '' }}>
                                                        {{ $matiere->nom }} - {{ number_format($matiere->prix_unitaire ?? 0, 0, ',', ' ') }} FCFA/{{ $isFrench ? 'unité' : 'unit' }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>
                                                    {{ $isFrench ? 'Aucune matière disponible' : 'No materials available' }}
                                                </option>
                                            @endif
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="space-y-2">
                                    <label for="quantite" class="block text-sm font-medium text-gray-700">
                                        {{ $isFrench ? 'Quantité' : 'Quantity' }} 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-hashtag text-gray-400"></i>
                                        </div>
                                        <input type="number" 
                                               name="quantite" 
                                               id="quantite" 
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                               value="{{ old('quantite') }}" 
                                               min="0.01" 
                                               max="999999"
                                               step="0.01"
                                               placeholder="{{ $isFrench ? 'Entrez la quantité' : 'Enter quantity' }}">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $isFrench ? 'La quantité achetée/livrée' : 'The quantity purchased/delivered' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Price Calculation Display -->
                            <div id="price-calculation" class="mt-6 p-4 bg-gray-50 rounded-lg" style="display: none;">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">{{ $isFrench ? 'Prix calculé' : 'Calculated Price' }}:</span>
                                    <span id="calculated-price" class="text-lg font-bold text-green-600">0 FCFA</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $isFrench ? 'Le prix sera automatiquement mis à jour si le champ prix est vide' : 'Price will be automatically updated if price field is empty' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('depenses.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 hover:scale-105"
                           id="cancelBtn">
                            <i class="fas fa-times mr-2"></i>
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-green-700 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn">
                            <i class="fas fa-save mr-2"></i>
                            <span id="submitText">{{ $isFrench ? 'Enregistrer la dépense' : 'Save Expense' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const matiereFields = document.getElementById('matiere-fields');
    const idmSelect = document.getElementById('idm');
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix');
    const priceCalculation = document.getElementById('price-calculation');
    const calculatedPrice = document.getElementById('calculated-price');
    const form = document.getElementById('expenseForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const cancelBtn = document.getElementById('cancelBtn');
    
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    let isSubmitting = false;
    let priceCalculationTimeout;

    // Fonction pour afficher/masquer les champs matière avec animation
    function toggleMatiereFields() {
        const selectedType = typeSelect.value;
        const needsMaterial = ['achat_matiere', 'livraison_matiere'].includes(selectedType);
        
        if (needsMaterial) {
            matiereFields.style.display = 'block';
            setTimeout(() => {
                matiereFields.classList.add('animate-fade-in');
            }, 10);
            idmSelect.required = true;
            quantiteInput.required = true;
        } else {
            matiereFields.classList.remove('animate-fade-in');
            setTimeout(() => {
                matiereFields.style.display = 'none';
            }, 300);
            idmSelect.required = false;
            quantiteInput.required = false;
            resetMaterialFields();
        }
    }

    // Fonction pour réinitialiser les champs matière
    function resetMaterialFields() {
        idmSelect.value = '';
        quantiteInput.value = '';
        priceCalculation.style.display = 'none';
        calculatedPrice.textContent = '0 FCFA';
    }

    // Fonction pour calculer et suggérer un prix (avec debounce)
    function suggestPrice() {
        clearTimeout(priceCalculationTimeout);
        priceCalculationTimeout = setTimeout(() => {
            const selectedOption = idmSelect.options[idmSelect.selectedIndex];
            const quantite = parseFloat(quantiteInput.value) || 0;
            
            if (selectedOption && selectedOption.dataset.prix && quantite > 0) {
                const prixUnitaire = parseFloat(selectedOption.dataset.prix) || 0;
                const prixSuggere = Math.round(prixUnitaire * quantite);
                
                // Afficher le prix calculé
                calculatedPrice.textContent = prixSuggere.toLocaleString('fr-FR') + ' FCFA';
                priceCalculation.style.display = 'block';
                
                // Suggérer le prix calculé si le champ prix est vide ou contient l'ancien prix calculé
                if (!prixInput.value || prixInput.dataset.wasCalculated === 'true') {
                    prixInput.value = prixSuggere;
                    prixInput.dataset.wasCalculated = 'true';
                }
            } else {
                priceCalculation.style.display = 'none';
                if (prixInput.dataset.wasCalculated === 'true') {
                    prixInput.value = '';
                    prixInput.dataset.wasCalculated = 'false';
                }
            }
        }, 300);
    }

    // Validation en temps réel avec debounce
    function validateField(field) {
        const value = field.value.trim();
        const isValid = field.checkValidity() && value !== '';
        
        if (isValid) {
            field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.add('border-green-300', 'focus:border-green-500', 'focus:ring-green-500');
        } else {
            field.classList.remove('border-green-300', 'focus:border-green-500', 'focus:ring-green-500');
            field.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        }
        
        return isValid;
    }

    // Prévention de la double soumission
    function preventDoubleSubmission() {
        if (isSubmitting) return false;
        
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        submitText.textContent = isFrench ? 'Enregistrement...' : 'Saving...';
        
        // Désactiver le bouton annuler
        cancelBtn.classList.add('pointer-events-none', 'opacity-50');
        
        return true;
    }

    // Réactiver le formulaire en cas d'erreur
    function reactivateForm() {
        isSubmitting = false;
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        submitText.textContent = isFrench ? 'Enregistrer la dépense' : 'Save Expense';
        cancelBtn.classList.remove('pointer-events-none', 'opacity-50');
    }

    // Event listeners optimisés
    typeSelect.addEventListener('change', toggleMatiereFields);
    idmSelect.addEventListener('change', suggestPrice);
    quantiteInput.addEventListener('input', suggestPrice);
    
    // Marquer le prix comme modifié manuellement
    prixInput.addEventListener('input', function() {
        if (this.dataset.wasCalculated === 'true') {
            this.dataset.wasCalculated = 'false';
        }
    });

    // Validation en temps réel pour tous les champs requis
    const requiredFields = form.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        let validationTimeout;
        
        field.addEventListener('input', function() {
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => {
                validateField(this);
            }, 500);
        });
        
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Gestion de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        // Prévenir la double soumission
        if (!preventDoubleSubmission()) {
            e.preventDefault();
            return false;
        }
        
        // Validation finale
        let isFormValid = true;
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isFormValid = false;
            }
        });
        
        // Validation spécifique pour les champs matière
        const needsMaterial = ['achat_matiere', 'livraison_matiere'].includes(typeSelect.value);
        if (needsMaterial) {
            if (!idmSelect.value || !quantiteInput.value || parseFloat(quantiteInput.value) <= 0) {
                isFormValid = false;
                if (!idmSelect.value) validateField(idmSelect);
                if (!quantiteInput.value || parseFloat(quantiteInput.value) <= 0) validateField(quantiteInput);
            }
        }
        
        // Validation du prix
        if (!prixInput.value || parseFloat(prixInput.value) <= 0) {
            isFormValid = false;
            validateField(prixInput);
        }
        
        if (!isFormValid) {
            e.preventDefault();
            reactivateForm();
            
            // Afficher une alerte
            alert(isFrench ? 'Veuillez corriger les erreurs dans le formulaire.' : 'Please fix the errors in the form.');
            return false;
        }
        
        // Marquer le formulaire comme soumis et désactiver l'alerte beforeunload
        form.dataset.submitted = 'true';
        formHasChanges = false;
        
        // Timeout de sécurité pour réactiver le formulaire si la soumission échoue
        setTimeout(() => {
            if (document.contains(form)) {
                reactivateForm();
            }
        }, 10000);
    });

    // Prévenir les soumissions multiples via Enter
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (!isSubmitting) {
                form.dispatchEvent(new Event('submit'));
            }
        }
    });

    // Prévenir la navigation accidentelle (seulement si l'utilisateur navigue vraiment)
    let formHasChanges = false;
    
    // Détecter les changements dans le formulaire
    const formInputs = form.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            formHasChanges = true;
        });
        input.addEventListener('change', () => {
            formHasChanges = true;
        });
    });
    
    // Alerte seulement si le formulaire a des changements non sauvegardés ET qu'on n'est pas en train de soumettre
    window.addEventListener('beforeunload', function(e) {
        if (formHasChanges && !isSubmitting && form.dataset.submitted !== 'true') {
            const message = isFrench ? 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter cette page ?' 
                                    : 'You have unsaved changes. Are you sure you want to leave this page?';
            e.returnValue = message;
            return message;
        }
    });

    // Initialiser l'état des champs au chargement
    toggleMatiereFields();
    
    // Validation initiale si des valeurs sont déjà présentes (old values)
    requiredFields.forEach(field => {
        if (field.value) {
            validateField(field);
        }
    });
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom scrollbar for select dropdowns */
select::-webkit-scrollbar {
    width: 8px;
}

select::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

select::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

select::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Amélioration de l'accessibilité */
input:focus, select:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* États de validation visuels */
.border-red-300 {
    border-color: #fca5a5;
}

.border-green-300 {
    border-color: #86efac;
}

/* Animation de chargement pour le bouton */
.loading::after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: 8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
@endsection
