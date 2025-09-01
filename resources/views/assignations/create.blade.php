@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('buttons')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mobile:text-xl mobile:font-semibold mobile:text-center mobile:animate-bounce">
            {{ $isFrench ? 'Assigner des matières premières' : 'Assign Raw Materials' }}
        </h1>
        <p class="text-gray-600 mobile:text-sm mobile:text-center mobile:animate-pulse">
            {{ $isFrench ? 'Attribuez des matières premières à un producteur' : 'Assign raw materials to a producer' }}
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 mobile:animate-shake" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mobile:p-4 mobile:shadow-lg mobile:border mobile:border-blue-100 mobile:transform mobile:transition-transform mobile:hover:scale-[1.01]">
        <form action="{{ route('assignations.store') }}" method="POST" id="assignationForm">
            @csrf

            <div class="mb-6 mobile:mb-4">
                <label for="producteur_id" class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">
                    {{ $isFrench ? 'Producteur' : 'Producer' }}
                </label>
                <select id="producteur_id" name="producteur_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2 mobile:transition-all mobile:duration-200 mobile:focus:ring-2" required>
                    <option value="">{{ $isFrench ? 'Sélectionnez un producteur' : 'Select a producer' }}</option>
                    @foreach($producteurs as $producteur)
                        <option value="{{ $producteur->id }}">{{ $producteur->name }} ({{ ucfirst($producteur->role) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6 mobile:mb-4">
                <label for="date_limite" class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">
                    {{ $isFrench ? 'Date limite d\'utilisation (optionnel)' : 'Deadline for use (optional)' }}
                </label>
                <input type="date" id="date_limite" name="date_limite" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2 mobile:transition-all mobile:duration-200 mobile:focus:ring-2">
            </div>

            <div class="border-t border-gray-200 pt-4 mb-4 mobile:pt-3">
                <h2 class="text-lg font-medium text-gray-800 mb-4 mobile:text-base mobile:font-semibold mobile:text-center mobile:animate-pulse">
                    {{ $isFrench ? 'Matières premières à assigner' : 'Raw materials to assign' }}
                </h2>
            </div>

            <div id="matieres-container">
                <div class="matiere-item bg-gray-50 p-4 rounded-md mb-4 mobile:p-3 mobile:mb-3 mobile:border mobile:border-gray-200 mobile:shadow-sm mobile:transform mobile:transition-transform mobile:hover:scale-[1.005]">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mobile:gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">
                                {{ $isFrench ? 'Matière première' : 'Raw material' }}
                            </label>
                            <select name="matieres[0][id]" class="matiere-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                                <option value="">{{ $isFrench ? 'Sélectionnez une matière' : 'Select a material' }}</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        data-unite-minimale="{{ $matiere->unite_minimale }}"
                                        data-provient-complexe="{{ $matiere->provient_du_complexe ? 'oui' : 'non' }}">
                                        {{ $matiere->nom }} ({{ $matiere->unite_classique }})
                                        @if($matiere->provient_du_complexe) - [Complexe] @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </label>
                            <input type="number" name="matieres[0][quantite]" step="0.001" min="0.001" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">
                                {{ $isFrench ? 'Unité' : 'Unit' }}
                            </label>
                            <select name="matieres[0][unite]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                                @foreach(array_keys($unites) as $unite)
                                    <option value="{{ $unite }}">{{ strtoupper($unite) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-2 complexe-indicator hidden mobile:mt-1">
                        <div class="bg-blue-50 p-2 rounded border border-blue-200 mobile:p-1.5 mobile:text-xs">
                            <p class="text-sm text-blue-800 mobile:text-xs">
                                <i class="fas fa-info-circle mr-1"></i> 
                                {{ $isFrench ? 'Cette matière provient du complexe et sera incluse dans une facture.' : 'This material comes from the complex and will be included in an invoice.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mb-6 mobile:mb-4">
                <button type="button" id="add-matiere" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded mobile:py-1.5 mobile:px-3 mobile:text-sm mobile:shadow-sm mobile:transform mobile:active:scale-95 mobile:transition-transform mobile:duration-100">
                    + {{ $isFrench ? 'Ajouter une matière' : 'Add material' }}
                </button>
            </div>

            <div class="flex justify-end mobile:flex-col mobile:gap-2">
                <a href="{{ route('assignations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2 mobile:py-1.5 mobile:px-3 mobile:text-sm mobile:mr-0 mobile:mb-2 mobile:text-center mobile:shadow-sm mobile:transform mobile:active:scale-95 mobile:transition-transform mobile:duration-100">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mobile:py-1.5 mobile:px-3 mobile:text-sm mobile:w-full mobile:shadow-sm mobile:transform mobile:active:scale-95 mobile:transition-transform mobile:duration-100 mobile:animate-pulse">
                    {{ $isFrench ? 'Enregistrer' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @media (max-width: 640px) {
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }
        .animate-bounce { animation: bounce 2s infinite; }
        .animate-pulse { animation: pulse 2s infinite; }
        .animate-shake { animation: shake 0.5s; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let matiereIndex = 0;

        // Fonction pour mettre à jour l'indicateur de complexe
        function updateComplexeIndicator(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const provientComplexe = selectedOption.getAttribute('data-provient-complexe');
            const item = selectElement.closest('.matiere-item');
            const indicator = item.querySelector('.complexe-indicator');

            if (provientComplexe === 'oui') {
                indicator.classList.remove('hidden');
                // Animation mobile seulement
                if (window.innerWidth <= 640) {
                    indicator.classList.add('animate-pulse');
                    setTimeout(() => indicator.classList.remove('animate-pulse'), 2000);
                }
            } else {
                indicator.classList.add('hidden');
            }
        }

        // Appliquer aux matières existantes
        document.querySelectorAll('.matiere-select').forEach(select => {
            select.addEventListener('change', function() {
                updateComplexeIndicator(this);
            });
        });

        document.getElementById('add-matiere').addEventListener('click', function() {
            matiereIndex++;

            const container = document.getElementById('matieres-container');
            const newItem = document.createElement('div');
            newItem.className = 'matiere-item bg-gray-50 p-4 rounded-md mb-4 mobile:p-3 mobile:mb-3 mobile:border mobile:border-gray-200 mobile:shadow-sm mobile:transform mobile:transition-transform mobile:hover:scale-[1.005]';
            
            const addText = '{{ $isFrench ? "Matière supplémentaire" : "Additional material" }}';
            const removeText = '{{ $isFrench ? "Supprimer" : "Remove" }}';
            const selectText = '{{ $isFrench ? "Sélectionnez une matière" : "Select a material" }}';
            const quantityText = '{{ $isFrench ? "Quantité" : "Quantity" }}';
            const unitText = '{{ $isFrench ? "Unité" : "Unit" }}';
            const complexeText = '{{ $isFrench ? "Cette matière provient du complexe et sera incluse dans une facture." : "This material comes from the complex and will be included in an invoice." }}';

            newItem.innerHTML = `
                <div class="flex justify-between mb-2">
                    <h3 class="text-md font-medium mobile:text-sm">${addText}</h3>
                    <button type="button" class="remove-matiere text-red-500 hover:text-red-700 mobile:text-sm mobile:transform mobile:active:scale-95 mobile:transition-transform">
                        ${removeText}
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mobile:gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">${addText}</label>
                        <select name="matieres[${matiereIndex}][id]" class="matiere-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                            <option value="">${selectText}</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}"
                                    data-unite-minimale="{{ $matiere->unite_minimale }}"
                                    data-provient-complexe="{{ $matiere->provient_du_complexe ? 'oui' : 'non' }}">
                                    {{ $matiere->nom }} ({{ $matiere->unite_classique }})
                                    @if($matiere->provient_du_complexe) - [Complexe] @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">${quantityText}</label>
                        <input type="number" name="matieres[${matiereIndex}][quantite]" step="0.001" min="0.001" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-xs">${unitText}</label>
                        <select name="matieres[${matiereIndex}][unite]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mobile:text-sm mobile:py-2" required>
                            @foreach(array_keys($unites) as $unite)
                                <option value="{{ $unite }}">{{ strtoupper($unite) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-2 complexe-indicator hidden mobile:mt-1">
                    <div class="bg-blue-50 p-2 rounded border border-blue-200 mobile:p-1.5 mobile:text-xs">
                        <p class="text-sm text-blue-800 mobile:text-xs">
                            <i class="fas fa-info-circle mr-1"></i> ${complexeText}
                        </p>
                    </div>
                </div>
            `;

            // Animation pour mobile seulement
            if (window.innerWidth <= 640) {
                newItem.style.opacity = '0';
                container.appendChild(newItem);
                setTimeout(() => {
                    newItem.style.transition = 'opacity 0.3s ease';
                    newItem.style.opacity = '1';
                }, 10);
            } else {
                container.appendChild(newItem);
            }

            // Ajouter les gestionnaires d'événements pour la nouvelle matière
            const newSelect = newItem.querySelector('.matiere-select');
            newSelect.addEventListener('change', function() {
                updateComplexeIndicator(this);
            });

            // Ajouter les gestionnaires d'événements pour les nouveaux boutons de suppression
            newItem.querySelector('.remove-matiere').addEventListener('click', function() {
                if (window.innerWidth <= 640) {
                    newItem.style.transition = 'opacity 0.3s ease';
                    newItem.style.opacity = '0';
                    setTimeout(() => {
                        container.removeChild(newItem);
                    }, 300);
                } else {
                    container.removeChild(newItem);
                }
            });
        });

        // Délégation d'événement pour les boutons de suppression
        document.getElementById('matieres-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-matiere')) {
                const matiereItem = e.target.closest('.matiere-item');
                if (window.innerWidth <= 640) {
                    matiereItem.style.transition = 'opacity 0.3s ease';
                    matiereItem.style.opacity = '0';
                    setTimeout(() => {
                        matiereItem.parentNode.removeChild(matiereItem);
                    }, 300);
                } else {
                    matiereItem.parentNode.removeChild(matiereItem);
                }
            }
        });
    });
</script>
@endsection