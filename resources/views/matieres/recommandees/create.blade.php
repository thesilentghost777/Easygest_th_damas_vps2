@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ $produit ? $produit->nom : ($isFrench ? 'Nouvelle recommandation' : 'New recommendation') }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $produit ? ($isFrench ? "Définir les Matières Recommandées : {$produit->nom}" : "Define Recommended Materials: {$produit->nom}") : ($isFrench ? "Nouvelles Matières Recommandées" : "New Recommended Materials") }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Définir les matières nécessaires pour la production' : 'Define the materials needed for production' }}
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Mobile Form -->
        <div class="md:hidden">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right">
                <div class="px-6 py-6">
                    <form action="{{ route('matieres.recommandees.store') }}" method="POST" id="matieres-form-mobile" class="space-y-6">
                        @csrf
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="produit_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </label>
                            
                            @if($produit)
                                <input type="hidden" name="produit" value="{{ $produit->code_produit }}">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="text-gray-900 font-medium">{{ $produit->nom }} (Code: {{ $produit->code_produit }})</div>
                                    <div class="text-sm text-blue-600 mt-1">{{ $isFrench ? 'Prix:' : 'Price:' }} {{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                </div>
                            @else
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <select id="produit_mobile" name="produit" required
                                           class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                                        <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                                        @foreach($produits as $p)
                                            <option value="{{ $p->code_produit }}" {{ old('produit') == $p->code_produit ? 'selected' : '' }}>
                                                {{ $p->nom }} (Code: {{ $p->code_produit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('produit')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="quantite_produit_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Quantité de produit (référence)' : 'Product quantity (reference)' }}
                            </label>
                            <div class="flex items-center">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10l-3-3m3 3l-3-3m3 3H9"/>
                                        </svg>
                                    </div>
                                    <input type="number" id="quantite_produit_mobile" name="quantite_produit" min="1" 
                                           value="{{ old('quantite_produit', 1) }}" required
                                           class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200">
                                </div>
                                <span class="ml-2 text-sm text-gray-600">{{ $isFrench ? 'unité(s)' : 'unit(s)' }}</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $isFrench ? 'Définissez pour quelle quantité de produits les recommandations sont données.' : 'Define for which product quantity the recommendations are given.' }}
                            </p>
                            @error('quantite_produit')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}</h3>
                                <button type="button" id="ajouter-matiere-mobile" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ $isFrench ? 'Ajouter' : 'Add' }}
                                </button>
                            </div>
                            
                            <div id="matieres-container-mobile" class="space-y-4">
                                <!-- Dynamic content will be added here -->
                            </div>
                            
                            @error('matieres')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col space-y-3 pt-6">
                            <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Enregistrer les Recommandations' : 'Save Recommendations' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Desktop Form -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    <form action="{{ route('matieres.recommandees.store') }}" method="POST" id="matieres-form" class="space-y-8">
                        @csrf
                        
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="produit" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </label>
                            
                            @if($produit)
                                <input type="hidden" name="produit" value="{{ $produit->code_produit }}">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="text-gray-900 font-medium">{{ $produit->nom }} (Code: {{ $produit->code_produit }})</div>
                                    <div class="text-sm text-blue-600 mt-1">{{ $isFrench ? 'Prix:' : 'Price:' }} {{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                </div>
                            @else
                                <select id="produit" name="produit" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium">
                                    <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                                    @foreach($produits as $p)
                                        <option value="{{ $p->code_produit }}" {{ old('produit') == $p->code_produit ? 'selected' : '' }}>
                                            {{ $p->nom }} (Code: {{ $p->code_produit }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('produit')
                                    <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="quantite_produit" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Quantité de produit (référence)' : 'Product quantity (reference)' }}
                            </label>
                            <div class="flex items-center">
                                <input type="number" id="quantite_produit" name="quantite_produit" min="1" 
                                       value="{{ old('quantite_produit', 1) }}" required
                                       class="w-40 shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg p-3 bg-white font-medium">
                                <span class="ml-2 text-sm text-gray-600">{{ $isFrench ? 'unité(s)' : 'unit(s)' }}</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $isFrench ? 'Définissez pour quelle quantité de produits les recommandations sont données.' : 'Define for which product quantity the recommendations are given.' }}
                            </p>
                            @error('quantite_produit')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}</h3>
                                <button type="button" id="ajouter-matiere" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition transform hover:scale-105">
                                    + {{ $isFrench ? 'Ajouter une matière' : 'Add material' }}
                                </button>
                            </div>
                            
                            <div id="matieres-container" class="space-y-4">
                                <!-- Dynamic content will be added here -->
                            </div>
                            
                            @error('matieres')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Enregistrer les Recommandations' : 'Save Recommendations' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const matieresContainer = document.getElementById('matieres-container');
        const matieresContainerMobile = document.getElementById('matieres-container-mobile');
        const ajouterMatiereBtn = document.getElementById('ajouter-matiere');
        const ajouterMatiereBtnMobile = document.getElementById('ajouter-matiere-mobile');
        const form = document.getElementById('matieres-form');
        const formMobile = document.getElementById('matieres-form-mobile');
        
        const matieres = @json($matieres);
        const unites = @json(\App\Enums\UniteMinimale::values());
        const unitesLabels = {
            'g': @json($isFrench ? 'gramme' : 'gram'),
            'kg': @json($isFrench ? 'kilogramme' : 'kilogram'),
            'ml': @json($isFrench ? 'millilitre' : 'milliliter'),
            'cl': @json($isFrench ? 'centilitre' : 'centiliter'),
            'dl': @json($isFrench ? 'décilitre' : 'deciliter'),
            'l': @json($isFrench ? 'litre' : 'liter'),
            'cc': @json($isFrench ? 'cuillère à café' : 'teaspoon'),
            'cs': @json($isFrench ? 'cuillère à soupe' : 'tablespoon'),
            'pincee': @json($isFrench ? 'pincée' : 'pinch'),
            'unite': @json($isFrench ? 'unité' : 'unit')
        };
        
        function createMaterialRow(isMobile = false) {
            const ligneId = Date.now();
            const containerSelector = isMobile ? matieresContainerMobile : matieresContainer;
            const ligneDiv = document.createElement('div');
            ligneDiv.className = isMobile ? 'bg-gray-50 p-4 rounded-xl border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300' : 'bg-gray-50 p-4 rounded-lg';
            ligneDiv.dataset.id = ligneId;
            
            const matiereText = @json($isFrench ? 'Matière' : 'Material');
            const quantiteText = @json($isFrench ? 'Quantité' : 'Quantity');
            const uniteText = @json($isFrench ? 'Unité' : 'Unit');
            const supprimerText = @json($isFrench ? 'Supprimer' : 'Delete');
            const selectMatiereText = @json($isFrench ? 'Sélectionner une matière' : 'Select a material');
            const selectUniteText = @json($isFrench ? 'Sélectionner une unité' : 'Select a unit');
            
            if (isMobile) {
                ligneDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-900">${matiereText} #${containerSelector.children.length + 1}</h4>
                        <button type="button" class="text-red-600 hover:text-red-800 supprimer-matiere transform hover:scale-110 transition-all duration-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">${matiereText}</label>
                            <select name="matieres[${ligneId}][id]" required
                                    class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white h-12 text-base font-medium">
                                <option value="">${selectMatiereText}</option>
                                ${matieres.map(m => `<option value="${m.id}">${m.nom} (${m.unite_minimale})</option>`).join('')}
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">${quantiteText}</label>
                                <input type="number" name="matieres[${ligneId}][quantite]" min="0.001" step="0.001" required
                                       class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white h-12 text-base font-medium">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">${uniteText}</label>
                                <select name="matieres[${ligneId}][unite]" required
                                       class="w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white h-12 text-base font-medium">
                                    <option value="">${selectUniteText}</option>
                                    ${unites.map(u => `<option value="${u}">${unitesLabels[u] || u}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                ligneDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium">${matiereText} #${containerSelector.children.length + 1}</h4>
                        <button type="button" class="text-red-600 hover:text-red-800 supprimer-matiere">
                            ${supprimerText}
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">${matiereText}</label>
                            <select name="matieres[${ligneId}][id]" required
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                                <option value="">${selectMatiereText}</option>
                                ${matieres.map(m => `<option value="${m.id}">${m.nom} (${m.unite_minimale})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">${quantiteText}</label>
                            <input type="number" name="matieres[${ligneId}][quantite]" min="0.001" step="0.001" required
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">${uniteText}</label>
                            <select name="matieres[${ligneId}][unite]" required
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                                <option value="">${selectUniteText}</option>
                                ${unites.map(u => `<option value="${u}">${unitesLabels[u] || u}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                `;
            }
            
            containerSelector.appendChild(ligneDiv);
            
            ligneDiv.querySelector('.supprimer-matiere').addEventListener('click', function() {
                ligneDiv.remove();
                
                containerSelector.querySelectorAll('h4').forEach((h4, index) => {
                    h4.textContent = `${matiereText} #${index + 1}`;
                });
            });
        }
        
        if (ajouterMatiereBtn) {
            createMaterialRow(false);
            ajouterMatiereBtn.addEventListener('click', () => createMaterialRow(false));
        }
        
        if (ajouterMatiereBtnMobile) {
            createMaterialRow(true);
            ajouterMatiereBtnMobile.addEventListener('click', () => createMaterialRow(true));
        }
        
        [form, formMobile].forEach(formElement => {
            if (formElement) {
                formElement.addEventListener('submit', function(e) {
                    const containerId = formElement.id === 'matieres-form' ? 'matieres-container' : 'matieres-container-mobile';
                    const lignesMatiere = document.querySelectorAll(`#${containerId} > div`);
                    
                    if (lignesMatiere.length === 0) {
                        e.preventDefault();
                        alert(@json($isFrench ? 'Veuillez ajouter au moins une matière recommandée.' : 'Please add at least one recommended material.'));
                    }
                });
            }
        });
    });
</script>
@endpush

<style>
@media (max-width: 768px) {
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
