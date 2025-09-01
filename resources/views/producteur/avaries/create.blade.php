@extends('pages.producteur.pdefault')

@section('page-content')
<br><br>
<div class="min-h-screen bg-gray-50">
    

    <!-- Desktop Header -->
    <div class="hidden md:block py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $isFrench ? 'Enregistrer une Avarie de Production' : 'Record Production Damage' }}</h1>
                    <p class="mt-2 text-gray-600">{{ $isFrench ? 'Indiquez les informations sur le produit avarié et les matières utilisées' : 'Provide information about the damaged product and materials used' }}</p>
                </div>
            </div>
        </div>
    </div>

   <!-- Mobile Container - Largeur optimisée -->
   <div class="md:hidden px-[0.01cm] pb-10">
    <div class="bg-white  shadow-2xl -mt-6 relative z-10 animate-slide-up w-full mx-auto">
        <div class="px-4 pt-8 pb-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                    <h3 class="text-sm font-medium mb-2">{{ $isFrench ? 'Erreurs de validation' : 'Validation errors' }}</h3>
                    <ul class="text-sm space-y-1">
                        @if(session('error'))
                            <li>{{ session('error') }}</li>
                        @endif
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('producteur.avaries.store') }}" method="POST" id="avarieForm" class="space-y-5">
                @csrf

                <!-- Mobile Product Section - Optimisée -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="transform hover:scale-102 transition-all duration-200 sm:col-span-2 lg:col-span-1">
                        <label for="produit" class="block text-base font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Produit Avarié' : 'Damaged Product' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <select name="produit" id="produit" required
                                class="pl-10 pr-3 w-full h-12 text-base border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                                <option value="">{{ $isFrench ? 'Sélectionnez un produit' : 'Select a product' }}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}">
                                        {{ $produit->nom }} - {{ $produit->prix }} FCFA
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="transform hover:scale-102 transition-all duration-200 sm:col-span-2 lg:col-span-1">
                        <label for="quantite_produit" class="block text-base font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Quantité avariée' : 'Damaged quantity' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-red-600 font-semibold text-base">#</span>
                            </div>
                            <input type="number" step="1" min="1" name="quantite_produit" id="quantite_produit" required value="1"
                                class="pl-10 pr-3 w-full h-12 text-base border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>
                </div>

                <!-- Mobile Checkbox - Plus spacieux -->
                <div class="bg-yellow-50 p-4 rounded-xl border-l-4 border-yellow-500">
                    <div class="flex items-start space-x-3">
                        <input id="avarie_reutilisee" name="avarie_reutilisee" type="checkbox" 
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-0.5 flex-shrink-0">
                        <label for="avarie_reutilisee" class="block text-sm font-medium text-gray-900 leading-relaxed">
                            {{ $isFrench ? 'Cette avarie sera réutilisée pour une nouvelle production (ne pas déduire les matières)' : 'This damage will be reused for new production (do not deduct materials)' }}
                        </label>
                    </div>
                </div>

                <!-- Mobile Materials Section - Layout amélioré -->
                <div class="bg-blue-50 rounded-xl p-4 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">
                        {{ $isFrench ? 'Matières Premières Utilisées' : 'Raw Materials Used' }}
                    </h3>
                    <p class="text-sm text-blue-600 mb-4 leading-relaxed">
                        {{ $isFrench ? 'Ajoutez les matières premières qui ont été utilisées pour cette production avariée' : 'Add the raw materials that were used for this damaged production' }}
                    </p>

                    <div id="matieres-container" class="space-y-4">
                        <div class="matiere-item bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="space-y-4">
                                <!-- Matière première - Pleine largeur -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Matière Première' : 'Raw Material' }}</label>
                                    <select name="matieres[0][matiere_id]" required
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0 bg-white text-sm">
                                        <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                                        @foreach($matieres as $matiere)
                                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Quantité et Unité - Layout responsive -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                        <input type="number" step="0.001" name="matieres[0][quantite]" required
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0 text-sm">
                                    </div>
                                    <div class="sm:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                                        <select name="matieres[0][unite]" required
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0 text-sm">
                                            <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                                            <option value="g">{{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }}</option>
                                            <option value="kg">{{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }}</option>
                                            <option value="ml">{{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }}</option>
                                            <option value="cl">{{ $isFrench ? 'Centilitre (cl)' : 'Centiliter (cl)' }}</option>
                                            <option value="dl">{{ $isFrench ? 'Décilitre (dl)' : 'Deciliter (dl)' }}</option>
                                            <option value="l">{{ $isFrench ? 'Litre (l)' : 'Liter (l)' }}</option>
                                            <option value="cc">{{ $isFrench ? 'Cuillère à café (cc)' : 'Teaspoon (cc)' }}</option>
                                            <option value="cs">{{ $isFrench ? 'Cuillère à soupe (cs)' : 'Tablespoon (cs)' }}</option>
                                            <option value="pincee">{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                            <option value="unite">{{ $isFrench ? 'Unité' : 'Unit' }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="ajouterMatiere()" class="mt-4 w-full bg-green-100 text-green-700 py-3 px-4 rounded-lg font-medium transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Ajouter une matière première' : 'Add raw material' }}
                    </button>
                </div>

                <!-- Mobile Submit Button - Optimisé -->
                <div class="pt-4">
                    <button type="submit" class="w-full h-12 bg-red-600 text-white text-base font-bold rounded-xl shadow-lg hover:bg-red-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        {{ $isFrench ? 'Enregistrer l\'avarie' : 'Record damage' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
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
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('producteur.avaries.store') }}" method="POST" id="avarieForm" class="divide-y divide-gray-200">
                        @csrf

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="produit">
                                        {{ $isFrench ? 'Produit Avarié' : 'Damaged Product' }}
                                    </label>
                                    <select name="produit" id="produit"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                                        required>
                                        <option value="">{{ $isFrench ? 'Sélectionnez un produit' : 'Select a product' }}</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->code_produit }}">
                                                {{ $produit->nom }} - {{ $produit->prix }} FCFA
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="quantite_produit">
                                        {{ $isFrench ? 'Quantité avariée' : 'Damaged quantity' }}
                                    </label>
                                    <input type="number" step="1" min="1" name="quantite_produit" id="quantite_produit"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required value="1">
                                </div>
                            </div>

                            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4">
    <div class="flex items-center">
        <input 
            id="avarie_reutilisee" 
            name="avarie_reutilisee" 
            type="checkbox" 
            {{ old('avarie_reutilisee') ? 'checked' : '' }}
            class="h-6 w-6 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        >
        <label for="avarie_reutilisee" class="ml-3 block text-base font-medium text-gray-900">
            Cette avarie sera réutilisée pour une nouvelle production (ne pas déduire les matières)
        </label>
    </div>
</div>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $isFrench ? 'Matières Premières Utilisées' : 'Raw Materials Used' }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $isFrench ? 'Ajoutez les matières premières qui ont été utilisées pour cette production avariée' : 'Add the raw materials that were used for this damaged production' }}</p>
                            </div>

                            <div id="matieres-container" class="space-y-4">
                                <div class="matiere-item p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Matière Première' : 'Raw Material' }}</label>
                                            <select name="matieres[0][matiere_id]"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                                                required>
                                                <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                                                @foreach($matieres as $matiere)
                                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                                            <input type="number" step="0.001" name="matieres[0][quantite]"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                                            <select name="matieres[0][unite]"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                                                required>
                                                <option value="">{{ $isFrench ? 'Sélectionner' : 'Select' }}</option>
                                                <option value="g">{{ $isFrench ? 'Gramme (g)' : 'Gram (g)' }}</option>
                                                <option value="kg">{{ $isFrench ? 'Kilogramme (kg)' : 'Kilogram (kg)' }}</option>
                                                <option value="ml">{{ $isFrench ? 'Millilitre (ml)' : 'Milliliter (ml)' }}</option>
                                                <option value="cl">{{ $isFrench ? 'Centilitre (cl)' : 'Centiliter (cl)' }}</option>
                                                <option value="dl">{{ $isFrench ? 'Décilitre (dl)' : 'Deciliter (dl)' }}</option>
                                                <option value="l">{{ $isFrench ? 'Litre (l)' : 'Liter (l)' }}</option>
                                                <option value="cc">{{ $isFrench ? 'Cuillère à café (cc)' : 'Teaspoon (cc)' }}</option>
                                                <option value="cs">{{ $isFrench ? 'Cuillère à soupe (cs)' : 'Tablespoon (cs)' }}</option>
                                                <option value="pincee">{{ $isFrench ? 'Pincée' : 'Pinch' }}</option>
                                                <option value="unite">{{ $isFrench ? 'Unité' : 'Unit' }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" onclick="ajouterMatiere()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $isFrench ? 'Ajouter une matière première' : 'Add raw material' }}
                                </button>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50">
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ $isFrench ? 'Enregistrer l\'avarie' : 'Record damage' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let matiereCount = 1;

function ajouterMatiere() {
    const container = document.getElementById('matieres-container');
    const template = document.querySelector('.matiere-item').cloneNode(true);

    template.querySelectorAll('input, select').forEach(input => {
        const name = input.name.replace('[0]', `[${matiereCount}]`);
        input.name = name;
        input.value = '';
    });

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'mt-2 inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500';
    removeButton.innerHTML = `
        <svg class="-ml-1 mr-1 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        {{ $isFrench ? 'Supprimer' : 'Remove' }}
    `;
    removeButton.onclick = function() {
        template.remove();
    };
    template.appendChild(removeButton);

    container.appendChild(template);
    matiereCount++;
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>
@endsection
