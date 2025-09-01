@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-100 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-lg">
            <h2 class="text-lg font-bold text-blue-900">
                {{ $isFrench ? 'Comment fonctionne le Calculateur ?' : 'How does the Calculator work?' }}
            </h2>
            <p class="mt-2 text-blue-700">
                {{ $isFrench ? 'Le calculateur vous permet de déterminer les quantités exactes des ingrédients nécessaires pour un nombre donné d\'unités à produire. Voici comment l\'utiliser :' : 'The calculator allows you to determine the exact quantities of ingredients needed for a given number of units to produce. Here\'s how to use it:' }}
            </p>
            <ol class="mt-4 list-decimal list-inside text-blue-700 space-y-2">
                <li>{{ $isFrench ? 'Dans le champ de saisie Quantité, entrez le nombre d\'unités que vous souhaitez produire.' : 'In the Quantity input field, enter the number of units you want to produce.' }}</li>
                <li>{{ $isFrench ? 'Cliquez sur le bouton Calculer.' : 'Click the Calculate button.' }}</li>
                <li>{{ $isFrench ? 'Les ingrédients nécessaires, ainsi que leurs quantités respectives, s\'afficheront immédiatement sous la section calculateur.' : 'The necessary ingredients, along with their respective quantities, will be displayed immediately under the calculator section.' }}</li>
            </ol>
            <p class="mt-2 text-blue-700">
                {{ $isFrench ? 'Ce calcul est basé sur les recettes optimales fournies pour chaque produit. Assurez-vous de saisir une quantité valide pour obtenir des résultats précis.' : 'This calculation is based on the optimal recipes provided for each product. Make sure to enter a valid quantity to get accurate results.' }}
            </p>
        </div>
    </div>

    <div class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                {{ $isFrench ? 'Livre de Recettes' : 'Recipe Book' }}
            </h1>
            <p class="mt-2 text-gray-600">{{ $secteur }} - {{ $nom }}</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2">
            @if(auth()->user()->secteur != 'administration')
            <a href="{{ route('recipes.instructions') }}" class="w-full sm:w-auto bg-green-500 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-lg hover:bg-green-600 text-center transition-all duration-200 transform hover:scale-105 active:scale-95">
                {{ $isFrench ? 'Recettes Détaillées' : 'Detailed Recipes' }}
            </a>
            @endif

            @if(auth()->user()->secteur == 'administration')
            <a href="{{ route('recipes.admin') }}" class="w-full sm:w-auto bg-green-500 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-lg hover:bg-green-600 text-center transition-all duration-200 transform hover:scale-105 active:scale-95">
                {{ $isFrench ? 'Recettes Avancées' : 'Advanced Recipes' }}
            </a>
            <a href="{{ route('recettes.create') }}" class="w-full sm:w-auto bg-blue-500 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-lg hover:bg-blue-600 text-center transition-all duration-200 transform hover:scale-105 active:scale-95">
                {{ $isFrench ? 'Ajouter une recette' : 'Add a recipe' }}
            </a>
            @endif
        </div>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 lg:mb-8 rounded-r-lg animate-slide-in">
        <p class="text-yellow-700">
            {{ $isFrench ? 'Vous avez pour chaque produit disponible la recette optimale proposée par l\'administration, certains employés et des sources fiables.' : 'For each available product, you have the optimal recipe proposed by the administration, some employees and reliable sources.' }}
        </p>
    </div>

    <!-- Mobile Cards -->
    <div class="lg:hidden space-y-4">
        @foreach($produits as $produit)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mobile-card animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
            <div class="p-4">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $produit->nom }}</h2>
                    <button onclick="confirmDelete({{ $produit->code_produit }})" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                        <i class="mdi mdi-delete text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Recette pour' : 'Recipe for' }} {{ $produit->matiereRecommandee->first()?->quantitep ?? 0 }} {{ $isFrench ? 'unités' : 'units' }}
                        </h3>
                        <ul class="space-y-2">
                            @foreach($produit->matiereRecommandee as $recette)
                            <li class="flex justify-between text-sm bg-white rounded-lg p-2">
                                <span class="text-gray-700 font-medium">{{ $recette->matiere->nom }}</span>
                                <span class="text-gray-600">{{ $recette->quantite }} {{ $recette->unite }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                        <h3 class="text-sm font-medium text-blue-800 mb-3">
                            {{ $isFrench ? 'Calculateur' : 'Calculator' }}
                        </h3>
                        <div class="space-y-3">
                            <input type="number"
                                   class="quantity-input w-full border border-blue-300 rounded-lg px-3 py-2 text-center"
                                   data-produit="{{ $produit->code_produit }}"
                                   placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}">
                            <button onclick="calculateIngredients(this)"
                                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors font-medium">
                                {{ $isFrench ? 'Calculer' : 'Calculate' }}
                            </button>
                        </div>
                        <div class="mt-3 ingredients-result hidden"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Grid -->
    <div class="hidden lg:grid lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($produits as $produit)
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $produit->nom }}</h2>
                    <button onclick="confirmDelete({{ $produit->code_produit }})"
                            class="text-red-500 hover:text-red-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">
                            {{ $isFrench ? 'Recette pour' : 'Recipe for' }} {{ $produit->matiereRecommandee->first()?->quantitep ?? 0 }} {{ $isFrench ? 'unités' : 'units' }}
                        </h3>
                        <ul class="mt-2 space-y-2">
                            @foreach($produit->matiereRecommandee as $recette)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-700">{{ $recette->matiere->nom }}</span>
                                <span class="text-gray-600">{{ $recette->quantite }} {{ $recette->unite }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-4 border-t">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">
                            {{ $isFrench ? 'Calculateur' : 'Calculator' }}
                        </h3>
                        <div class="flex gap-2">
                            <input type="number"
                                   class="quantity-input border rounded px-2 py-1 w-24"
                                   data-produit="{{ $produit->code_produit }}"
                                   placeholder="{{ $isFrench ? 'Quantité' : 'Quantity' }}">
                            <button onclick="calculateIngredients(this)"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition-colors">
                                {{ $isFrench ? 'Calculer' : 'Calculate' }}
                            </button>
                        </div>
                        <div class="mt-2 ingredients-result hidden"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, input, .mobile-card {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script>
function calculateIngredients(button) {
    const card = button.closest('.bg-white, .mobile-card');
    const input = card.querySelector('.quantity-input');
    const resultDiv = card.querySelector('.ingredients-result');
    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    fetch('/recettes/calculate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            produit_id: input.dataset.produit,
            quantite_cible: input.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        resultDiv.innerHTML = `
            <h4 class="text-sm font-medium text-gray-700 mt-2 mb-2">
                ${isFrench ? 'Ingrédients nécessaires:' : 'Required ingredients:'}
            </h4>
            <ul class="space-y-1">
                ${data.ingredients.map(ing => `
                    <li class="text-sm flex justify-between bg-green-50 rounded p-2">
                        <span class="text-gray-700 font-medium">${ing.nom}</span>
                        <span class="text-gray-600">${ing.quantite.toFixed(2)} ${ing.unite}</span>
                    </li>
                `).join('')}
            </ul>
        `;
        resultDiv.classList.remove('hidden');
    })
    .catch(error => {
        alert((isFrench ? 'Erreur: ' : 'Error: ') + error.message);
    });
}

function confirmDelete(produitId) {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    const message = isFrench ? 'Êtes-vous sûr de vouloir supprimer cette recette ?' : 'Are you sure you want to delete this recipe?';
    
    if (confirm(message)) {
        fetch(`/recettes/${produitId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert(isFrench ? 'Erreur lors de la suppression' : 'Error during deletion');
        });
    }
}
</script>
@endsection
