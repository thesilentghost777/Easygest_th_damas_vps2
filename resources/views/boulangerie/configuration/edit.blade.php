@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Modifier la Configuration - {{ $sac->nom }}</h1>
        <p class="text-gray-600">Modifiez les matières utilisées et la valeur moyenne attendue pour ce type de sac.</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('boulangerie.configuration.update', $sac->id) }}" method="POST" id="configForm">
            @csrf
            @method('PUT')
            
            <!-- Informations générales -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du sac <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom', $sac->nom) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="valeur_moyenne_fcfa" class="block text-sm font-medium text-gray-700 mb-2">
                            Valeur moyenne attendue (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="valeur_moyenne_fcfa" 
                               name="valeur_moyenne_fcfa" 
                               value="{{ old('valeur_moyenne_fcfa', $sac->configuration ? $sac->configuration->valeur_moyenne_fcfa : '') }}"
                               required
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('valeur_moyenne_fcfa') border-red-500 @enderror">
                        @error('valeur_moyenne_fcfa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $sac->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Matières utilisées -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Matières utilisées</h2>
                    <button type="button" 
                            id="addMatiere" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter matière
                    </button>
                </div>

                <div id="matieresList" class="space-y-4">
                    <!-- Les matières existantes seront chargées ici -->
                </div>
                
                @error('matieres')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="3"
                          placeholder="Notes ou observations particulières..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $sac->configuration ? $sac->configuration->notes : '') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('boulangerie.configuration.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 font-medium">
                    Mettre à jour la configuration
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const matieresList = document.getElementById('matieresList');
    const addMatiereBtn = document.getElementById('addMatiere');
    let matiereCount = 0;

    const matieres = @json($matieres);
    const sacMatieres = @json($sac->matieres);

    function createMatiereRow(existingMatiere = null) {
        const index = matiereCount++;
        const div = document.createElement('div');
        div.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200';
        
        div.innerHTML = `
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <select name="matieres[${index}][id]" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Sélectionner une matière</option>
                        ${matieres.map(matiere => `
                            <option value="${matiere.id}" ${existingMatiere && existingMatiere.id == matiere.id ? 'selected' : ''}>
                                ${matiere.nom} (${matiere.unite_minimale})
                            </option>
                        `).join('')}
                    </select>
                </div>
                
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Quantité utilisée <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="matieres[${index}][quantite]" 
                           required
                           min="0"
                           step="0.001"
                           placeholder="0.000"
                           value="${existingMatiere ? existingMatiere.pivot.quantite_utilisee : ''}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="pb-1">
                    <button type="button" 
                            onclick="this.parentElement.parentElement.parentElement.remove()"
                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        return div;
    }

    // Charger les matières existantes
    sacMatieres.forEach(matiere => {
        const row = createMatiereRow(matiere);
        matieresList.appendChild(row);
    });

    addMatiereBtn.addEventListener('click', function() {
        const row = createMatiereRow();
        matieresList.appendChild(row);
    });

    // Ajouter une ligne vide si aucune matière n'existe
    if (sacMatieres.length === 0) {
        addMatiereBtn.click();
    }
});
</script>
@endsection