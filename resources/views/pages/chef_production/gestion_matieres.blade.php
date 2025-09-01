
@extends('pages.chef_production.chef_production_default')

@section('page-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('buttons')
    
    <div class="mb-6">
        <div class="hidden md:block">
            <h1 class="text-3xl font-bold text-gray-800">
                {{ $isFrench ? 'Gestion des Matières Premières' : 'Raw Materials Management' }}
            </h1>
        </div>
        
        <div class="md:hidden text-center">
            <div class="bg-amber-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-xl text-amber-600 font-bold">
                {{ $isFrench ? 'Matières Premières' : 'Raw Materials' }}
            </h1>
        </div>
    </div>

    <!-- Guide des champs (Desktop uniquement) -->
    <div class="hidden md:block bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-800">
            {{ $isFrench ? 'Guide des champs du formulaire' : 'Form Fields Guide' }}
        </h2>
        <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium">{{ $isFrench ? 'Nom de la matière :' : 'Material Name:' }}</span> {{ $isFrench ? 'Le nom identifiant la matière première (ex: Farine, Sucre, etc.)' : 'The name identifying the raw material (e.g.: Flour, Sugar, etc.)' }}</p>
            <p><span class="font-medium">{{ $isFrench ? 'Unité minimale :' : 'Minimal Unit:' }}</span> {{ $isFrench ? 'La plus petite unité de mesure utilisée pour cette matière (ex: grammes pour les solides, millilitres pour les liquides)' : 'The smallest unit of measure used for this material (e.g.: grams for solids, milliliters for liquids)' }}</p>
            <p><span class="font-medium">{{ $isFrench ? 'Unité classique :' : 'Standard Unit:' }}</span> {{ $isFrench ? 'L\'unité de mesure standard pour l\'achat en gros (ex: kg pour les solides, litre pour les liquides)' : 'The standard unit of measure for bulk purchase (e.g.: kg for solids, liter for liquids)' }}</p>
            <p><span class="font-medium">{{ $isFrench ? 'Quantité par unité :' : 'Quantity per Unit:' }}</span> {{ $isFrench ? 'Quantité de matières en unités classique contenues dans une occurrence de la matière' : 'Amount of materials in standard units contained in one occurrence of the material' }}</p>
            <p><span class="font-medium">{{ $isFrench ? 'Quantité :' : 'Quantity:' }}</span> {{ $isFrench ? 'Quantité totale d\'unités en stock' : 'Total quantity of units in stock' }}</p>
            <p><span class="font-medium">{{ $isFrench ? 'Prix unitaire :' : 'Unit Price:' }}</span> {{ $isFrench ? 'Prix d\'achat d\'une unité en FCFA' : 'Purchase price of one unit in FCFA' }}</p>
        </div>
        
        <div class="mt-6">
            <h3 class="font-semibold mb-2">{{ $isFrench ? 'Exemples concrets :' : 'Concrete Examples:' }}</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-blue-600">{{ $isFrench ? 'Exemple : Farine de blé' : 'Example: Wheat Flour' }}</h4>
                <ul class="mt-1 space-y-1 text-sm">
                    <li>• {{ $isFrench ? 'Nom : Farine de blé' : 'Name: Wheat Flour' }}</li>
                    <li>• {{ $isFrench ? 'Unité minimale : g (gramme)' : 'Minimal Unit: g (gram)' }}</li>
                    <li>• {{ $isFrench ? 'Unité classique : kg (kilogramme)' : 'Standard Unit: kg (kilogram)' }}</li>
                    <li>• {{ $isFrench ? 'Quantité par unité : 50 (car 1 sac = 50 kg)' : 'Quantity per unit: 50 (because 1 bag = 50 kg)' }}</li>
                    <li>• {{ $isFrench ? 'Nombre d\'unités : 25 (stock de 25 sacs)' : 'Number of units: 25 (stock of 25 bags)' }}</li>
                    <li>• {{ $isFrench ? 'Prix unitaire : 20000 (20000 XAF par sac)' : 'Unit price: 20000 (20000 XAF per bag)' }}</li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mobile:rounded-xl mobile:shadow-md mobile:animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mobile:rounded-xl mobile:shadow-md mobile:animate-fade-in">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire d'ajout -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="mobile:bg-gradient-to-r mobile:from-amber-50 mobile:to-orange-50 mobile:p-6 md:p-6">
            <h2 class="text-lg font-semibold mb-4 md:text-xl mobile:text-center mobile:text-amber-700">
                {{ $isFrench ? 'Ajouter une Matière Première' : 'Add Raw Material' }}
            </h2>
            
            <form action="{{ route('chef.matieres.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mobile:gap-6">
                    <div class="mobile:bg-blue-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-blue-700">
                            {{ $isFrench ? 'Nom de la matière' : 'Material Name' }}
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-blue-200 mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-purple-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-purple-700">
                            {{ $isFrench ? 'Unité minimale' : 'Minimal Unit' }}
                        </label>
                        <select name="unite_minimale" id="unite_minimale" required onchange="updateUniteClassique()"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                       mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-purple-200 mobile:focus:border-purple-500 mobile:focus:ring-2 mobile:focus:ring-purple-200 mobile:bg-white mobile:shadow-sm
                                       md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                            <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                            @foreach($unites_minimales as $unite)
                                <option value="{{ $unite }}" {{ old('unite_minimale') == $unite ? 'selected' : '' }}>
                                    @switch($unite)
                                        @case('g') {{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }} @break
                                        @case('kg') {{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }} @break
                                        @case('ml') {{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }} @break
                                        @case('cl') {{ $isFrench ? 'Centilitre (cl)' : 'Centiliter (cl)' }} @break
                                        @case('dl') {{ $isFrench ? 'Décilitre (dl)' : 'Deciliter (dl)' }} @break
                                        @case('l') {{ $isFrench ? 'Litre (l)' : 'Liter (l)' }} @break
                                        @case('cc') {{ $isFrench ? 'Cuillère à café' : 'Teaspoon' }} @break
                                        @case('cs') {{ $isFrench ? 'Cuillère à soupe' : 'Tablespoon' }} @break
                                        @case('pincee') {{ $isFrench ? 'Pincée' : 'Pinch' }} @break
                                        @case('unite') {{ $isFrench ? 'Unité' : 'Unit' }} @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mobile:bg-green-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-green-700">
                            {{ $isFrench ? 'Unité classique' : 'Standard Unit' }}
                        </label>
                        <select name="unite_classique" id="unite_classique" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                       mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-green-200 mobile:focus:border-green-500 mobile:focus:ring-2 mobile:focus:ring-green-200 mobile:bg-white mobile:shadow-sm
                                       md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                            <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                            @foreach($unites_classiques as $unite)
                                <option value="{{ $unite }}" {{ old('unite_classique') == $unite ? 'selected' : '' }}>
                                    @switch($unite)
                                        @case('kg') {{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }} @break
                                        @case('litre') {{ $isFrench ? 'Litre (L)' : 'Liter (L)' }} @break
                                        @case('unite') {{ $isFrench ? 'Unité' : 'Unit' }} @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mobile:bg-yellow-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-yellow-700">
                            {{ $isFrench ? 'Quantité par unité' : 'Quantity per Unit' }}
                            <span class="text-xs text-gray-500 mobile:block">{{ $isFrench ? '(en unité classique)' : '(in standard unit)' }}</span>
                        </label>
                        <input type="number" name="quantite_par_unite" step="0.001" value="{{ old('quantite_par_unite') }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-yellow-200 mobile:focus:border-yellow-500 mobile:focus:ring-2 mobile:focus:ring-yellow-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-indigo-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-indigo-700">
                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                        </label>
                        <input type="number" name="quantite" value="{{ old('quantite') }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-indigo-200 mobile:focus:border-indigo-500 mobile:focus:ring-2 mobile:focus:ring-indigo-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-pink-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-pink-700">
                            {{ $isFrench ? 'Prix unitaire (XAF)' : 'Unit Price (XAF)' }}
                        </label>
                        <input type="number" name="prix_unitaire" step="0.01" value="{{ old('prix_unitaire') }}" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-pink-200 mobile:focus:border-pink-500 mobile:focus:ring-2 mobile:focus:ring-pink-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 
                                   mobile:py-4 mobile:text-lg mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-blue-500 mobile:to-blue-600 mobile:hover:from-blue-600 mobile:hover:to-blue-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300
                                   md:py-2 md:px-4 md:text-base md:rounded-lg md:bg-blue-500 md:hover:bg-blue-600">
                        {{ $isFrench ? 'Ajouter la matière première' : 'Add Raw Material' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table des matières -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="mobile:p-4 mobile:bg-gradient-to-r mobile:from-gray-50 mobile:to-gray-100 md:p-0">
            <h3 class="text-lg font-semibold mb-4 mobile:text-center mobile:text-gray-700 md:hidden">
                {{ $isFrench ? 'Liste des Matières' : 'Materials List' }}
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Nom' : 'Name' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4 mobile:hidden md:table-cell">{{ $isFrench ? 'Unité min.' : 'Min. Unit' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4 mobile:hidden md:table-cell">{{ $isFrench ? 'Unité class.' : 'Std. Unit' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4 mobile:hidden md:table-cell">{{ $isFrench ? 'Qté/unité' : 'Qty/unit' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Prix unit.' : 'Unit Price' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($matieres as $matiere)
                    <tr class="mobile:hover:bg-amber-50 mobile:transition-colors mobile:duration-200">
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:font-medium">{{ $matiere->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:hidden md:table-cell">{{ $matiere->unite_minimale }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:hidden md:table-cell">{{ $matiere->unite_classique }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:hidden md:table-cell">{{ $matiere->quantite_par_unite }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:text-center mobile:font-semibold mobile:text-blue-600">{{ $matiere->quantite }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:text-center mobile:font-semibold mobile:text-green-600">{{ number_format($matiere->prix_unitaire, 0, ',', ' ') }} XAF</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium mobile:px-3 mobile:py-4">
                            <div class="flex space-x-2 mobile:flex-col mobile:space-x-0 mobile:space-y-2 md:flex-row md:space-x-2 md:space-y-0">
                                <button onclick="editMatiere({{ $matiere->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mobile:bg-blue-100 mobile:text-blue-700 mobile:px-3 mobile:py-2 mobile:rounded-lg mobile:text-xs mobile:font-semibold mobile:hover:bg-blue-200 mobile:transition-colors mobile:duration-200 md:bg-transparent md:px-0 md:py-0 md:text-base">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </button>
                                <form action="{{ route('chef.matieres.destroy', $matiere) }}" method="POST" class="inline-block mobile:w-full" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette matière ?' : 'Are you sure you want to delete this material?' }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 mobile:bg-red-100 mobile:text-red-700 mobile:px-3 mobile:py-2 mobile:rounded-lg mobile:text-xs mobile:font-semibold mobile:hover:bg-red-200 mobile:transition-colors mobile:duration-200 mobile:w-full md:bg-transparent md:px-0 md:py-0 md:text-base md:w-auto">
                                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 mobile:px-4">
            {{ $matieres->links() }}
        </div>
    </div>
</div>

<!-- Modal de modification -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 mobile:bg-opacity-70">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white mobile:top-4 mobile:mx-4 mobile:rounded-2xl mobile:animate-scale-in">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4 mobile:text-center mobile:text-xl mobile:text-amber-700">
                {{ $isFrench ? 'Modifier la matière première' : 'Edit Raw Material' }}
            </h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mobile:gap-6">
                    <div class="mobile:bg-blue-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-blue-700">
                            {{ $isFrench ? 'Nom de la matière' : 'Material Name' }}
                        </label>
                        <input type="text" name="nom" id="edit_nom" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-blue-200 mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-purple-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-purple-700">
                            {{ $isFrench ? 'Unité minimale' : 'Minimal Unit' }}
                        </label>
                        <select name="unite_minimale" id="edit_unite_minimale" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                       mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-purple-200 mobile:focus:border-purple-500 mobile:focus:ring-2 mobile:focus:ring-purple-200
                                       md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                            @foreach($unites_minimales as $unite)
                                <option value="{{ $unite }}">
                                    @switch($unite)
                                        @case('g') {{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }} @break
                                        @case('kg') {{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }} @break
                                        @case('ml') {{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }} @break
                                        @case('cl') {{ $isFrench ? 'Centilitre (cl)' : 'Centiliter (cl)' }} @break
                                        @case('dl') {{ $isFrench ? 'Décilitre (dl)' : 'Deciliter (dl)' }} @break
                                        @case('l') {{ $isFrench ? 'Litre (l)' : 'Liter (l)' }} @break
                                        @case('cc') {{ $isFrench ? 'Cuillère à café' : 'Teaspoon' }} @break
                                        @case('cs') {{ $isFrench ? 'Cuillère à soupe' : 'Tablespoon' }} @break
                                        @case('pincee') {{ $isFrench ? 'Pincée' : 'Pinch' }} @break
                                        @case('unite') {{ $isFrench ? 'Unité' : 'Unit' }} @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mobile:bg-green-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-green-700">
                            {{ $isFrench ? 'Unité classique' : 'Standard Unit' }}
                        </label>
                        <select name="unite_classique" id="edit_unite_classique" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                       mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-green-200 mobile:focus:border-green-500 mobile:focus:ring-2 mobile:focus:ring-green-200
                                       md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                            @foreach($unites_classiques as $unite)
                                <option value="{{ $unite }}">
                                    @switch($unite)
                                        @case('kg') {{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }} @break
                                        @case('litre') {{ $isFrench ? 'Litre (L)' : 'Liter (L)' }} @break
                                        @case('unite') {{ $isFrench ? 'Unité' : 'Unit' }} @break
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mobile:bg-yellow-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-yellow-700">
                            {{ $isFrench ? 'Quantité par unité' : 'Quantity per Unit' }}
                        </label>
                        <input type="number" name="quantite_par_unite" id="edit_quantite_par_unite" step="0.001" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-yellow-200 mobile:focus:border-yellow-500 mobile:focus:ring-2 mobile:focus:ring-yellow-200
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-indigo-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-indigo-700">
                            {{ $isFrench ? 'Nombre d\'unités' : 'Number of Units' }}
                        </label>
                        <input type="number" name="quantite" id="edit_quantite" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-indigo-200 mobile:focus:border-indigo-500 mobile:focus:ring-2 mobile:focus:ring-indigo-200
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-pink-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mobile:text-center mobile:font-semibold mobile:text-pink-700">
                            {{ $isFrench ? 'Prix unitaire (XAF)' : 'Unit Price (XAF)' }}
                        </label>
                        <input type="number" name="prix_unitaire" id="edit_prix_unitaire" step="0.01" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-pink-200 mobile:focus:border-pink-500 mobile:focus:ring-2 mobile:focus:ring-pink-200
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 mobile:flex-col mobile:space-x-0 mobile:space-y-4 md:flex-row md:space-x-3 md:space-y-0">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium mobile:w-full mobile:py-3 mobile:bg-gray-100 mobile:rounded-xl mobile:text-gray-700 mobile:hover:bg-gray-200 mobile:transition-colors mobile:duration-200 mobile:order-2 md:w-auto md:py-2 md:bg-transparent md:order-1">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mobile:w-full mobile:py-3 mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-blue-500 mobile:to-blue-600 mobile:hover:from-blue-600 mobile:hover:to-blue-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300 mobile:order-1 md:w-auto md:py-2 md:rounded md:bg-blue-500 md:hover:bg-blue-600 md:order-2">
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .mobile\:px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
    .mobile\:py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .mobile\:py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .mobile\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .mobile\:p-4 { padding: 1rem; }
    .mobile\:p-6 { padding: 1.5rem; }
    .mobile\:mb-4 { margin-bottom: 1rem; }
    .mobile\:mx-4 { margin-left: 1rem; margin-right: 1rem; }
    .mobile\:mx-auto { margin-left: auto; margin-right: auto; }
    .mobile\:text-center { text-align: center; }
    .mobile\:text-sm { font-size: 0.875rem; }
    .mobile\:text-lg { font-size: 1.125rem; }
    .mobile\:text-xl { font-size: 1.25rem; }
    .mobile\:text-xs { font-size: 0.75rem; }
    .mobile\:font-medium { font-weight: 500; }
    .mobile\:font-semibold { font-weight: 600; }
    .mobile\:font-bold { font-weight: 700; }
    .mobile\:bg-amber-100 { background-color: #fffbeb; }
    .mobile\:bg-amber-50 { background-color: #fffdf7; }
    .mobile\:bg-blue-50 { background-color: #eff6ff; }
    .mobile\:bg-purple-50 { background-color: #faf5ff; }
    .mobile\:bg-green-50 { background-color: #f0fdf4; }
    .mobile\:bg-yellow-50 { background-color: #fefce8; }
    .mobile\:bg-indigo-50 { background-color: #eef2ff; }
    .mobile\:bg-pink-50 { background-color: #fdf2f8; }
    .mobile\:bg-gray-50 { background-color: #f9fafb; }
    .mobile\:bg-gray-100 { background-color: #f3f4f6; }
    .mobile\:bg-blue-100 { background-color: #dbeafe; }
    .mobile\:bg-red-100 { background-color: #fee2e2; }
    .mobile\:bg-white { background-color: #ffffff; }
    .mobile\:bg-opacity-70 { --tw-bg-opacity: 0.7; }
    .mobile\:bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .mobile\:from-amber-50 { --tw-gradient-from: #fffdf7; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(255, 253, 247, 0)); }
    .mobile\:to-orange-50 { --tw-gradient-to: #fff7ed; }
    .mobile\:from-gray-50 { --tw-gradient-from: #f9fafb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0)); }
    .mobile\:to-gray-100 { --tw-gradient-to: #f3f4f6; }
    .mobile\:from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
    .mobile\:to-blue-600 { --tw-gradient-to: #2563eb; }
    .mobile\:from-blue-600 { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
    .mobile\:to-blue-700 { --tw-gradient-to: #1d4ed8; }
    .mobile\:hover\:from-blue-600:hover { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
    .mobile\:hover\:to-blue-700:hover { --tw-gradient-to: #1d4ed8; }
    .mobile\:text-amber-600 { color: #d97706; }
    .mobile\:text-amber-700 { color: #b45309; }
    .mobile\:text-blue-600 { color: #2563eb; }
    .mobile\:text-blue-700 { color: #1d4ed8; }
    .mobile\:text-purple-700 { color: #7c2d12; }
    .mobile\:text-green-600 { color: #16a34a; }
    .mobile\:text-green-700 { color: #15803d; }
    .mobile\:text-yellow-700 { color: #a16207; }
    .mobile\:text-indigo-700 { color: #4338ca; }
    .mobile\:text-pink-700 { color: #be185d; }
    .mobile\:text-gray-700 { color: #374151; }
    .mobile\:text-red-700 { color: #b91c1c; }
    .mobile\:rounded-full { border-radius: 9999px; }
    .mobile\:rounded-xl { border-radius: 0.75rem; }
    .mobile\:rounded-2xl { border-radius: 1rem; }
    .mobile\:rounded-lg { border-radius: 0.5rem; }
    .mobile\:w-20 { width: 5rem; }
    .mobile\:h-20 { height: 5rem; }
    .mobile\:w-10 { width: 2.5rem; }
    .mobile\:h-10 { height: 2.5rem; }
    .mobile\:w-full { width: 100%; }
    .mobile\:flex { display: flex; }
    .mobile\:items-center { align-items: center; }
    .mobile\:justify-center { justify-content: center; }
    .mobile\:flex-col { flex-direction: column; }
    .mobile\:space-x-0 > :not([hidden]) ~ :not([hidden]) { margin-left: 0px; }
    .mobile\:space-y-2 > :not([hidden]) ~ :not([hidden]) { margin-top: 0.5rem; }
    .mobile\:space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
    .mobile\:space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }
    .mobile\:gap-6 { gap: 1.5rem; }
    .mobile\:hidden { display: none; }
    .mobile\:table-cell { display: table-cell; }
    .mobile\:border-2 { border-width: 2px; }
    .mobile\:border-blue-200 { border-color: #bfdbfe; }
    .mobile\:border-purple-200 { border-color: #e9d5ff; }
    .mobile\:border-green-200 { border-color: #bbf7d0; }
    .mobile\:border-yellow-200 { border-color: #fde68a; }
    .mobile\:border-indigo-200 { border-color: #c7d2fe; }
    .mobile\:border-pink-200 { border-color: #fbcfe8; }
    .mobile\:focus\:border-blue-500:focus { border-color: #3b82f6; }
    .mobile\:focus\:border-purple-500:focus { border-color: #7c3aed; }
    .mobile\:focus\:border-green-500:focus { border-color: #22c55e; }
    .mobile\:focus\:border-yellow-500:focus { border-color: #eab308; }
    .mobile\:focus\:border-indigo-500:focus { border-color: #6366f1; }
    .mobile\:focus\:border-pink-500:focus { border-color: #ec4899; }
    .mobile\:focus\:ring-2:focus { box-shadow: 0 0 0 2px var(--tw-ring-color); }
    .mobile\:focus\:ring-blue-200:focus { --tw-ring-color: #bfdbfe; }
    .mobile\:focus\:ring-purple-200:focus { --tw-ring-color: #e9d5ff; }
    .mobile\:focus\:ring-green-200:focus { --tw-ring-color: #bbf7d0; }
    .mobile\:focus\:ring-yellow-200:focus { --tw-ring-color: #fde68a; }
    .mobile\:focus\:ring-indigo-200:focus { --tw-ring-color: #c7d2fe; }
    .mobile\:focus\:ring-pink-200:focus { --tw-ring-color: #fbcfe8; }
    .mobile\:shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    .mobile\:shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:hover\:bg-amber-50:hover { background-color: #fffdf7; }
    .mobile\:hover\:bg-blue-200:hover { background-color: #bfdbfe; }
    .mobile\:hover\:bg-red-200:hover { background-color: #fecaca; }
    .mobile\:hover\:bg-gray-200:hover { background-color: #e5e7eb; }
    .mobile\:transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; }
    .mobile\:transition-all { transition-property: all; }
    .mobile\:duration-200 { transition-duration: 200ms; }
    .mobile\:duration-300 { transition-duration: 300ms; }
    .mobile\:transform { transform: translateVar(--tw-translate-x, 0) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)); }
    .mobile\:hover\:scale-105:hover { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }
    .mobile\:active\:scale-95:active { --tw-scale-x: 0.95; --tw-scale-y: 0.95; }
    .mobile\:top-4 { top: 1rem; }
    .mobile\:order-1 { order: 1; }
    .mobile\:order-2 { order: 2; }
    .mobile\:animate-fade-in { animation: fadeIn 0.3s ease-out; }
    .mobile\:animate-scale-in { animation: scaleIn 0.2s ease-out; }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 768) {
        const form = document.querySelector('.bg-white.shadow-lg');
        form.style.opacity = '0';
        form.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            form.style.transition = 'all 0.6s ease-out';
            form.style.opacity = '1';
            form.style.transform = 'translateY(0)';
        }, 300);
    }
    
    const interactiveElements = document.querySelectorAll('input, select, button, a');
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(30);
            }
        });
    });
});

function editMatiere(id) {
    fetch(`/chef/matieres/${id}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('edit_nom').value = data.nom;
            document.getElementById('edit_unite_minimale').value = data.unite_minimale;
            document.getElementById('edit_unite_classique').value = data.unite_classique;
            document.getElementById('edit_quantite_par_unite').value = data.quantite_par_unite;
            document.getElementById('edit_quantite').value = Math.round(data.quantite);
            document.getElementById('edit_prix_unitaire').value = data.prix_unitaire;

            document.getElementById('editForm').action = `/chef/matieres/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('{{ $isFrench ? "Une erreur est survenue lors de la récupération des données" : "An error occurred while retrieving data" }}');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function updateUniteClassique() {
    const uniteMinimale = document.getElementById('unite_minimale').value;
    const uniteClassiqueSelect = document.getElementById('unite_classique');
    
    const unitesPermises = getUnitesClassiquesPermises(uniteMinimale);
    
    uniteClassiqueSelect.innerHTML = '<option value="">{{ $isFrench ? "Sélectionner" : "Select" }}</option>' + 
        unitesPermises.map(unite => 
            `<option value="${unite}">${getUniteClassiqueLabel(unite)}</option>`
        ).join('');
}

document.getElementById('edit_unite_minimale').addEventListener('change', function() {
    const uniteMinimale = this.value;
    const uniteClassiqueSelect = document.getElementById('edit_unite_classique');

    const unitesPermises = getUnitesClassiquesPermises(uniteMinimale);

    uniteClassiqueSelect.innerHTML = unitesPermises.map(unite =>
        `<option value="${unite}">${getUniteClassiqueLabel(unite)}</option>`
    ).join('');
});

function getUnitesClassiquesPermises(uniteMinimale) {
    const mapping = {
        'g': ['kg'],
        'kg': ['kg'],
        'ml': ['litre'],
        'cl': ['litre'],
        'dl': ['litre'],
        'l': ['litre'],
        'cc': ['kg', 'litre'],
        'cs': ['kg', 'litre'],
        'pincee': ['kg'],
        'unite': ['unite']
    };
    return mapping[uniteMinimale] || ['unite'];
}

function getUniteClassiqueLabel(unite) {
    const labels = {
        @if($isFrench)
        'kg': 'Kilogramme (kg)',
        'litre': 'Litre (L)',
        'unite': 'Unité'
        @else
        'kg': 'Kilogram (kg)',
        'litre': 'Liter (L)',
        'unite': 'Unit'
        @endif
    };
    return labels[unite] || unite;
}
</script>
@endsection
