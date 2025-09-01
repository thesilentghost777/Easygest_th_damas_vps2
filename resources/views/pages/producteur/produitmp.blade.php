@extends('pages.producteur.pdefault')

@section('page-content')
<br><br>
<div class="min-h-screen bg-gray-50">
   
    <!-- Desktop Header -->
    <div class="pb-10">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up w-full mx-auto">
            <div class="px-4 pt-8 pb-6">
                <button id="bouton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow mb-6">
                    <a href="{{ route('producteur.avaries.create') }}" class="flex items-center space-x-2">
                        <i class="mdi mdi-plus-circle-outline"></i>
                        <span>{{ $isFrench ? 'Ajouter Avarie' : 'Add Damage' }}</span>
                    </a>
                </button>
                <div class="mb-8">
                    <h2 class="text-3xl font-light text-gray-800 border-b-2 border-blue-600 pb-3 tracking-wide">
                        Enregistrer une Production
                    </h2>
                    <p class="mt-2 text-gray-600 font-light text-lg leading-relaxed font-serif italic">
                        {{ $isFrench ? 'Remplissez les informations ci-dessous pour enregistrer une nouvelle production' : 'Fill in the information below to record a new production' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'erreur/succès communs -->
    @if(session('success'))
        <div class="mx-2 md:mx-auto md:max-w-4xl mb-6 p-4 rounded-lg bg-green-50 border border-green-200 animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mx-2 md:mx-auto md:max-w-4xl mb-6 p-4 rounded-lg bg-red-50 border border-red-200 animate-shake">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ $isFrench ? 'Erreurs de validation' : 'Validation errors' }}</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @if(session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulaire unique pour mobile et desktop -->
    <div class="container mx-auto px-2 md:px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white md:rounded-xl shadow-lg md:shadow-sm border-0 md:border border-gray-200 overflow-hidden -mt-6 md:mt-0 relative z-10 animate-slide-up md:animate-none">
                <form action="{{ route('utilisations.store') }}" method="POST" id="productionForm" class="divide-y divide-gray-200">
                    @csrf

                    <!-- Section Informations Générales -->
                    <div class="p-3 md:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                            <!-- Date de Production -->
                            <div class="transform hover:scale-[1.01] md:hover:scale-100 transition-all duration-200">
                                <label class="block text-base md:text-sm font-semibold md:font-medium text-gray-700 mb-2 md:mb-1" for="date_production">
                                    {{ $isFrench ? 'Date de production' : 'Production date' }}
                                    <span class="text-xs text-gray-500 ml-1">({{ $isFrench ? 'optionnel' : 'optional' }})</span>
                                </label>
                                <div class="relative md:static">
                                    <div class="absolute md:hidden inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="date" name="date_production" id="date_production"
                                        class="pl-10 md:pl-3 w-full h-12 md:h-auto text-base md:text-sm border-2 md:border border-gray-200 md:border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500 bg-gray-50 md:bg-white transition-all duration-300 hover:bg-white hover:shadow-sm md:hover:bg-white md:hover:shadow-none py-2"
                                        value="{{ old('date_production') }}"
                                        max="{{ date('Y-m-d') }}">
                                    <small class="text-xs text-gray-500 mt-1 block">
                                        {{ $isFrench ? 'Laisser vide pour utiliser la date actuelle' : 'Leave empty to use current date' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Produit -->
                            <div class="transform hover:scale-[1.01] md:hover:scale-100 transition-all duration-200">
                                <label class="block text-base md:text-sm font-semibold md:font-medium text-gray-700 mb-2 md:mb-1" for="produit">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </label>
                                <div class="relative md:static">
                                    <div class="absolute md:hidden inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <select name="produit" id="produit" required
                                        class="pl-10 md:pl-3 w-full h-12 md:h-auto text-base md:text-sm border-2 md:border border-gray-200 md:border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500 bg-gray-50 md:bg-white transition-all duration-300 hover:bg-white hover:shadow-sm md:hover:bg-white md:hover:shadow-none py-2 pr-10">
                                        <option value="">{{ $isFrench ? 'Sélectionnez un produit' : 'Select a product' }}</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->code_produit }}" {{ old('produit') == $produit->code_produit ? 'selected' : '' }}>
                                                {{ $produit->nom }} - {{ $produit->prix }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Quantité -->
                            <div class="transform hover:scale-[1.01] md:hover:scale-100 transition-all duration-200">
                                <label class="block text-base md:text-sm font-semibold md:font-medium text-gray-700 mb-2 md:mb-1" for="quantite_produit">
                                    {{ $isFrench ? 'Quantité produite' : 'Quantity produced' }}
                                </label>
                                <div class="relative md:static">
                                    <div class="absolute md:hidden inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-blue-600 font-semibold text-base">#</span>
                                    </div>
                                    <input type="number" step="1" min="1" name="quantite_produit" id="quantite_produit" required 
                                        value="{{ old('quantite_produit', '1') }}"
                                        class="pl-10 md:pl-3 w-full h-12 md:h-auto text-base md:text-sm border-2 md:border border-gray-200 md:border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500 bg-gray-50 md:bg-white transition-all duration-300 hover:bg-white hover:shadow-sm md:hover:bg-white md:hover:shadow-none py-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Matières Premières -->
                    <div class="p-3 md:p-6">
                        <div class="bg-blue-50 rounded-lg p-3 md:p-4 border-l-4 border-blue-500 mb-4">
                            <h3 class="text-base md:text-lg font-semibold md:font-medium text-blue-800 md:text-gray-900 mb-2 md:mb-1">
                                {{ $isFrench ? 'Matières Premières' : 'Raw Materials' }}
                            </h3>
                            <p class="text-sm text-blue-600 md:text-gray-500">
                                {{ $isFrench ? 'Ajoutez les matières premières nécessaires pour cette production' : 'Add the raw materials needed for this production' }}
                            </p>
                        </div>

                        <div id="matieres-container" class="space-y-3 md:space-y-4">
                            <div class="matiere-item bg-white md:bg-gray-50 p-3 md:p-4 rounded-lg shadow-sm md:shadow-none border border-gray-200">
                                <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2 md:mb-1">{{ $isFrench ? 'Matière Première' : 'Raw Material' }}</label>
                                        <select name="matieres[0][matiere_id]" required onchange="updateUniteOptions(this)"
                                            class="matiere-select w-full p-2.5 md:p-2 text-base md:text-sm border border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500 bg-white">
                                            <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                                            @foreach($matieres as $matiere)
                                                <option value="{{ $matiere->id }}" 
                                                    data-unite-minimale="{{ $matiere->unite_minimale }}" 
                                                    data-unite-classique="{{ $matiere->unite_classique }}">
                                                    {{ $matiere->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2 md:contents">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2 md:mb-1">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                            <input type="number" step="0.001" name="matieres[0][quantite]" required
                                                class="w-full p-2.5 md:p-2 text-base md:text-sm border border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2 md:mb-1">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                                            <select name="matieres[0][unite]" required
                                                class="unite-select w-full p-2.5 md:p-2 text-base md:text-sm border border-gray-300 rounded-lg md:rounded-md focus:border-blue-500 md:focus:border-indigo-500 focus:ring-0 md:focus:ring-indigo-500">
                                                <option value="">{{ $isFrench ? 'Sélectionner une matière d\'abord' : 'Select a material first' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 md:mt-4">
                            <button type="button" onclick="ajouterMatiere()"
                                class="w-full md:w-auto bg-green-100 md:bg-white text-green-700 md:text-gray-700 py-2.5 md:py-2 px-3 md:px-4 rounded-lg md:rounded-md font-medium md:font-medium transform hover:scale-[1.02] md:hover:scale-100 active:scale-[0.98] md:active:scale-100 transition-all duration-200 border-0 md:border md:border-gray-300 shadow-sm md:shadow-sm hover:bg-green-50 md:hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 md:focus:ring-indigo-500 inline-flex items-center justify-center">
                                <svg class="h-4 w-4 md:h-5 md:w-5 mr-2 md:-ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Ajouter une matière première' : 'Add raw material' }}
                            </button>
                        </div>
                    </div>

                    <!-- Section Submit -->
                    <div class="p-3 md:px-6 md:py-4 bg-gray-50">
                        <div class="flex justify-center md:justify-end">
                            <button type="submit"
                                class="w-full md:w-auto h-12 md:h-auto bg-blue-600 md:bg-indigo-600 text-white text-base md:text-sm font-bold md:font-medium rounded-lg md:rounded-md shadow-md md:shadow-sm hover:bg-blue-700 md:hover:bg-indigo-700 transform hover:scale-[1.02] md:hover:scale-100 active:scale-[0.98] md:active:scale-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 md:focus:ring-indigo-500 inline-flex items-center justify-center px-4 py-2">
                                <svg class="h-5 w-5 mr-2 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Enregistrer la production' : 'Record production' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let matiereCount = 1;

// Mapping des unités compatibles selon l'enum UniteMinimale
const uniteMapping = {
    'g': ['g', 'kg'],
    'kg': ['g', 'kg'],
    'ml': ['ml', 'cl', 'dl', 'l'],
    'cl': ['ml', 'cl', 'dl', 'l'],
    'dl': ['ml', 'cl', 'dl', 'l'],
    'l': ['ml', 'cl', 'dl', 'l'],
    'cc': ['cc', 'cs', 'g', 'kg', 'ml', 'cl', 'dl', 'l'],
    'cs': ['cc', 'cs', 'g', 'kg', 'ml', 'cl', 'dl', 'l'],
    'pincee': ['pincee', 'g', 'kg'],
    'unite': ['unite']
};

// Labels pour les unités
const uniteLabels = {
    'g': '{{ $isFrench ? "Gramme (g)" : "Gram (g)" }}',
    'kg': '{{ $isFrench ? "Kilogramme (kg)" : "Kilogram (kg)" }}',
    'ml': '{{ $isFrench ? "Millilitre (ml)" : "Milliliter (ml)" }}',
    'cl': '{{ $isFrench ? "Centilitre (cl)" : "Centiliter (cl)" }}',
    'dl': '{{ $isFrench ? "Décilitre (dl)" : "Deciliter (dl)" }}',
    'l': '{{ $isFrench ? "Litre (l)" : "Liter (l)" }}',
    'cc': '{{ $isFrench ? "Cuillère à café (cc)" : "Teaspoon (cc)" }}',
    'cs': '{{ $isFrench ? "Cuillère à soupe (cs)" : "Tablespoon (cs)" }}',
    'pincee': '{{ $isFrench ? "Pincée" : "Pinch" }}',
    'unite': '{{ $isFrench ? "Unité" : "Unit" }}'
};

// Initialiser la date par défaut à aujourd'hui
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date_production');
    if (dateInput && !dateInput.value) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
    }
});

function updateUniteOptions(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const uniteMinimale = selectedOption.getAttribute('data-unite-minimale');
    const uniteClassique = selectedOption.getAttribute('data-unite-classique');
    
    // Trouver le select d'unité correspondant dans le même container
    const container = selectElement.closest('.matiere-item');
    const uniteSelect = container.querySelector('.unite-select');
    
    // Vider les options actuelles
    uniteSelect.innerHTML = '<option value="">{{ $isFrench ? "Sélectionner" : "Select" }}</option>';
    
    if (uniteMinimale && uniteClassique) {
        // Déterminer les unités autorisées basées sur l'unité minimale
        let unitesAutorisees = [];
        
        if (uniteMapping[uniteMinimale]) {
            unitesAutorisees = uniteMapping[uniteMinimale];
        }
        
        // Ajouter toujours l'unité minimale et classique
        if (!unitesAutorisees.includes(uniteMinimale)) {
            unitesAutorisees.push(uniteMinimale);
        }
        if (!unitesAutorisees.includes(uniteClassique)) {
            unitesAutorisees.push(uniteClassique);
        }
        
        // Créer les options pour les unités autorisées
        unitesAutorisees.forEach(unite => {
            if (uniteLabels[unite]) {
                const option = document.createElement('option');
                option.value = unite;
                option.textContent = uniteLabels[unite];
                uniteSelect.appendChild(option);
            }
        });
    }
}

function ajouterMatiere() {
    const container = document.getElementById('matieres-container');
    const template = document.querySelector('.matiere-item').cloneNode(true);

    // Mettre à jour les noms des champs
    template.querySelectorAll('input, select').forEach(input => {
        const name = input.name.replace(/\[0\]/, `[${matiereCount}]`);
        input.name = name;
        input.value = '';
    });

    // Réinitialiser le select d'unité pour la nouvelle ligne
    const uniteSelect = template.querySelector('.unite-select');
    uniteSelect.innerHTML = '<option value="">{{ $isFrench ? "Sélectionner une matière d\'abord" : "Select a material first" }}</option>';

    // Ajouter l'événement onchange au nouveau select de matière
    const matiereSelect = template.querySelector('.matiere-select');
    matiereSelect.onchange = function() {
        updateUniteOptions(this);
    };

    // Créer le bouton de suppression
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'mt-2 w-full md:w-auto inline-flex items-center justify-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-[1.02] md:hover:scale-100 active:scale-[0.98] md:active:scale-100 transition-all duration-200';
    removeButton.innerHTML = `
        <svg class="h-4 w-4 mr-1 md:-ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        {{ $isFrench ? 'Supprimer' : 'Remove' }}
    `;
    removeButton.onclick = function() {
        template.remove();
    };
    
    // Ajouter le bouton de suppression au template
    template.appendChild(removeButton);

    container.appendChild(template);
    matiereCount++;
}

// Validation avant soumission du formulaire
document.getElementById('productionForm').addEventListener('submit', function(e) {
    let isValid = true;
    const matiereItems = document.querySelectorAll('.matiere-item');
    
    // Validation de la date
    const dateInput = document.getElementById('date_production');
    if (dateInput.value) {
        const selectedDate = new Date(dateInput.value);
        const today = new Date();
        today.setHours(23, 59, 59, 999); // Fin de la journée
        
        if (selectedDate > today) {
            isValid = false;
            dateInput.classList.add('border-red-500');
            
            // Afficher un message d'erreur pour la date
            let errorMsg = dateInput.parentNode.querySelector('.date-error');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'text-red-500 text-xs mt-1 date-error';
                errorMsg.textContent = '{{ $isFrench ? "La date de production ne peut pas être dans le futur" : "Production date cannot be in the future" }}';
                dateInput.parentNode.appendChild(errorMsg);
            }
        } else {
            dateInput.classList.remove('border-red-500');
            const errorMsg = dateInput.parentNode.querySelector('.date-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    }
    
    // Validation des unités de matières
    matiereItems.forEach(item => {
        const matiereSelect = item.querySelector('.matiere-select');
        const uniteSelect = item.querySelector('.unite-select');
        
        if (matiereSelect && uniteSelect) {
            const selectedMatiereOption = matiereSelect.options[matiereSelect.selectedIndex];
            const selectedUnite = uniteSelect.value;
            
            if (selectedMatiereOption.value && selectedUnite) {
                const uniteMinimale = selectedMatiereOption.getAttribute('data-unite-minimale');
                
                // Vérifier si l'unité sélectionnée est autorisée
                let unitesAutorisees = [];
                if (uniteMapping[uniteMinimale]) {
                    unitesAutorisees = uniteMapping[uniteMinimale];
                }
                
                if (!unitesAutorisees.includes(selectedUnite)) {
                    isValid = false;
                    
                    // Marquer le champ en erreur
                    uniteSelect.classList.add('border-red-500');
                    
                    // Afficher un message d'erreur
                    let errorMsg = item.querySelector('.unite-error');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.className = 'text-red-500 text-xs mt-1 unite-error';
                        errorMsg.textContent = '{{ $isFrench ? "Unité non compatible avec cette matière" : "Unit not compatible with this material" }}';
                        uniteSelect.parentNode.appendChild(errorMsg);
                    }
                } else {
                    // Enlever la marque d'erreur si elle existait
                    uniteSelect.classList.remove('border-red-500');
                    const errorMsg = item.querySelector('.unite-error');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            }
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('{{ $isFrench ? "Veuillez corriger les erreurs avant de soumettre." : "Please correct the errors before submitting." }}');
    }
});

// Supprimer les messages d'erreur lors du changement d'unité ou de date
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('unite-select')) {
        e.target.classList.remove('border-red-500');
        const errorMsg = e.target.parentNode.querySelector('.unite-error');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
    
    if (e.target.id === 'date_production') {
        e.target.classList.remove('border-red-500');
        const errorMsg = e.target.parentNode.querySelector('.date-error');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
});

// Validation en temps réel de la date
document.getElementById('date_production').addEventListener('change', function(e) {
    const selectedDate = new Date(e.target.value);
    const today = new Date();
    today.setHours(23, 59, 59, 999);
    
    if (e.target.value && selectedDate > today) {
        e.target.classList.add('border-red-500');
        let errorMsg = e.target.parentNode.querySelector('.date-error');
        if (!errorMsg) {
            errorMsg = document.createElement('p');
            errorMsg.className = 'text-red-500 text-xs mt-1 date-error';
            errorMsg.textContent = '{{ $isFrench ? "La date de production ne peut pas être dans le futur" : "Production date cannot be in the future" }}';
            e.target.parentNode.appendChild(errorMsg);
        }
    } else {
        e.target.classList.remove('border-red-500');
        const errorMsg = e.target.parentNode.querySelector('.date-error');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
});
</script>
@endsection
