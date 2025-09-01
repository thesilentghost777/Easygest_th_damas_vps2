@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Desktop Header -->
    <div class="hidden md:flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">
            {{ $isFrench ? 'Matières Recommandées : ' . $produit->nom : 'Recommended Materials: ' . $produit->nom }}
        </h1>
        <div class="flex space-x-2">
           @include('buttons')
            <a href="{{ route('matieres.recommandees.create', $produit->code_produit) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                {{ $isFrench ? 'Modifier les recommandations' : 'Edit recommendations' }}
            </a>
        </div>
    </div>

    <!-- Mobile Header -->
    <div class="md:hidden mb-6">
        <div class="flex items-center justify-between mb-4">
            @include('buttons')
            <h1 class="text-xl font-bold text-blue-600 flex-1 text-center px-4">
                {{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}
            </h1>
        </div>
        
        <!-- Mobile Limited Functionality Notice -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">
                        {{ $isFrench ? 'Version Mobile Limitée' : 'Limited Mobile Version' }}
                    </h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <p>{{ $isFrench ? 'Cette version mobile affiche uniquement les détails. Pour modifier les recommandations, utilisez la version ordinateur.' : 'This mobile version only displays details. To edit recommendations, please use the desktop version.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Informations sur le produit -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Informations Produit' : 'Product Information' }}
                </h2>
                <div class="grid grid-cols-1 gap-3 md:gap-4">
                    <div class="flex flex-col sm:flex-row">
                        <span class="text-gray-600 font-medium min-w-0 sm:min-w-24">
                            {{ $isFrench ? 'Code:' : 'Code:' }}
                        </span>
                        <span class="sm:ml-2 text-gray-900 font-mono text-sm md:text-base">{{ $produit->code_produit }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="text-gray-600 font-medium min-w-0 sm:min-w-24">
                            {{ $isFrench ? 'Nom:' : 'Name:' }}
                        </span>
                        <span class="sm:ml-2 text-gray-900 text-sm md:text-base">{{ $produit->nom }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="text-gray-600 font-medium min-w-0 sm:min-w-24">
                            {{ $isFrench ? 'Catégorie:' : 'Category:' }}
                        </span>
                        <span class="sm:ml-2 text-gray-900 text-sm md:text-base">{{ $produit->categorie ?? ($isFrench ? 'Non définie' : 'Not defined') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="text-gray-600 font-medium min-w-0 sm:min-w-24">
                            {{ $isFrench ? 'Prix:' : 'Price:' }}
                        </span>
                        <span class="sm:ml-2 text-gray-900 font-semibold text-blue-600 text-sm md:text-base">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
            
        
        </div>
    </div>

    <!-- Liste des matières recommandées -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800">
                {{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}
            </h2>
        </div>
        
        <div id="matieres-recommandees-standard">
            @if($produit->matiereRecommandee->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Pour ' . ($produit->matiereRecommandee->first()->quantitep ?? 1) . ' produit(s)' : 'For ' . ($produit->matiereRecommandee->first()->quantitep ?? 1) . ' product(s)' }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Unité' : 'Unit' }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($produit->matiereRecommandee as $recommandation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $recommandation->matiere->nom }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ round($recommandation->quantite, 0) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $recommandation->unite }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('matieres.recommandees.edit', $recommandation->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        <form action="{{ route('matieres.recommandees.destroy', $recommandation->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette recommandation ?' : 'Are you sure you want to delete this recommendation?' }}')">
                                                {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden space-y-3">
                    @foreach($produit->matiereRecommandee as $recommandation)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $recommandation->matiere->nom }}</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $recommandation->unite }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <span class="text-gray-600">
                                        {{ $isFrench ? 'Quantité:' : 'Quantity:' }}
                                    </span>
                                    <span class="font-medium text-gray-900 ml-1">
                                        {{ round($recommandation->quantite, 0) }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $isFrench ? 'pour ' . ($recommandation->quantitep ?? 1) . ' produit(s)' : 'for ' . ($recommandation->quantitep ?? 1) . ' product(s)' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm md:text-base">
                        {{ $isFrench ? 'Aucune matière n\'est encore recommandée pour ce produit.' : 'No materials are yet recommended for this product.' }}
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Calculated Materials (Desktop Only) -->
        <div id="matieres-recommandees-calculees" class="hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité nécessaire' : 'Required quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Unité' : 'Unit' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Stock disponible' : 'Available stock' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody id="matieres-calculees-body" class="bg-white divide-y divide-gray-200">
                        <!-- Les données seront ajoutées dynamiquement ici -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulaire pour ajouter une nouvelle matière recommandée (Desktop Only) -->
    @if($produit->matiereRecommandee->count() > 0)
        <div class="hidden md:block bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Ajouter une Matière Supplémentaire' : 'Add Additional Material' }}
            </h2>
            
            @if($matieres->count() > 0)
                <form action="{{ route('matieres.recommandees.add-matiere', $produit->code_produit) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label for="matierep" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </label>
                            <select id="matierep" name="matierep" required
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                                <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }} ({{ $matiere->unite_minimale }})</option>
                                @endforeach
                            </select>
                            @error('matierep')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="quantitep" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Quantité de produit (référence)' : 'Product quantity (reference)' }}
                            </label>
                            <input type="number" id="quantitep" name="quantitep" min="1" 
                                   value="{{ old('quantitep', $produit->matiereRecommandee->first()->quantitep ?? 1) }}" required
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                            @error('quantitep')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Quantité de matière' : 'Material quantity' }}
                            </label>
                            <input type="number" id="quantite" name="quantite" min="0.001" step="0.001" required
                                   value="{{ old('quantite') }}"
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                            @error('quantite')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="unite" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Unité' : 'Unit' }}
                            </label>
                            <select id="unite" name="unite" required 
                                   class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500">
                                <option value="g" {{ old('unite') == 'g' ? 'selected' : '' }}>{{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }}</option>
                                <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>{{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }}</option>
                                <option value="ml" {{ old('unite') == 'ml' ? 'selected' : '' }}>{{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }}</option>
                                <option value="cl" {{ old('unite') == 'cl' ? 'selected' : '' }}>{{ $isFrench ? 'Centilitre (cl)' : 'Centiliter (cl)' }}</option>
                                <option value="dl" {{ old('unite') == 'dl' ? 'selected' : '' }}>{{ $isFrench ? 'Décilitre (dl)' : 'Deciliter (dl)' }}</option>
                                <option value="l" {{ old('unite') == 'l' ? 'selected' : '' }}>{{ $isFrench ? 'Litre (l)' : 'Liter (l)' }}</option>
                                <option value="cc" {{ old('unite') == 'cc' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à café (cc)' : 'Teaspoon (cc)' }}</option>
                                <option value="cs" {{ old('unite') == 'cs' ? 'selected' : '' }}>{{ $isFrench ? 'Cuillère à soupe (cs)' : 'Tablespoon (cs)' }}</option>
                                <option value="pincee" {{ old('unite') == 'pincee' ? 'selected' : '' }}>{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                <option value="unite" {{ old('unite') == 'unite' ? 'selected' : '' }}>{{ $isFrench ? 'Unité' : 'Unit' }}</option>
                            </select>
                            @error('unite')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        {{ $isFrench ? 'Ajouter cette Matière' : 'Add this Material' }}
                    </button>
                </form>
            @else
                <div class="text-center py-4 text-gray-500">
                    {{ $isFrench ? 'Toutes les matières disponibles ont déjà été recommandées pour ce produit.' : 'All available materials have already been recommended for this product.' }}
                </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize calculator on desktop
        if (window.innerWidth >= 768) {
            const calculerBtn = document.getElementById('calculer');
            const quantiteProduitInput = document.getElementById('quantite_produit');
            const matieresStandard = document.getElementById('matieres-recommandees-standard');
            const matieresCalculees = document.getElementById('matieres-recommandees-calculees');
            const matieresCalculeesBody = document.getElementById('matieres-calculees-body');
            
            if (calculerBtn) {
                calculerBtn.addEventListener('click', function() {
                    const quantiteProduit = parseInt(quantiteProduitInput.value) || 1;
                    
                    fetch(`{{ route('matieres.recommandees.conversion') }}?produit_id={{ $produit->code_produit }}&quantite_produit=${quantiteProduit}`)
                        .then(response => response.json())
                        .then(data => {
                            matieresCalculeesBody.innerHTML = '';
                            
                            data.matieres.forEach(matiere => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50';
                                
                                // Vérifier si le stock est suffisant
                                const stockSuffisant = matiere.stock_disponible >= matiere.quantite;
                                const isFrench = {{ $isFrench ? 'true' : 'false' }};
                                
                                row.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">${matiere.matiere_nom}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${matiere.quantite.toLocaleString('fr-FR', {minimumFractionDigits: 3, maximumFractionDigits: 3})}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${matiere.unite}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${matiere.stock_disponible.toLocaleString('fr-FR', {minimumFractionDigits: 3, maximumFractionDigits: 3})}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${stockSuffisant ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${stockSuffisant ? (isFrench ? 'Suffisant' : 'Sufficient') : (isFrench ? 'Insuffisant' : 'Insufficient')}
                                        </span>
                                    </td>
                                `;
                                
                                matieresCalculeesBody.appendChild(row);
                            });
                            
                            // Afficher les données calculées et masquer les standards
                            matieresStandard.classList.add('hidden');
                            matieresCalculees.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Erreur lors du calcul:', error);
                            const isFrench = {{ $isFrench ? 'true' : 'false' }};
                            alert(isFrench ? 'Une erreur est survenue lors du calcul des matières recommandées.' : 'An error occurred while calculating recommended materials.');
                        });
                });
            }
        }
    });
</script>
@endpush
@endsection