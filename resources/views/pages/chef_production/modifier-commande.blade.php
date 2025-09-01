@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6 md:p-8">
        @include('buttons')

        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            {{ $isFrench ? 'Modifier la Commande' : 'Edit Order' }}
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('commande.update', $commande->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="libelle" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Libellé' : 'Label' }}
                    </label>
                    <input type="text" name="libelle" id="libelle"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('libelle', $commande->libelle) }}" required maxlength="50">
                </div>

                <div>
                    <label for="produit" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select name="produit" id="produit"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}"
                                {{ (old('produit', $commande->produit) == $produit->code_produit) ? 'selected' : '' }}>
                                {{ $produit->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="quantite" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                    </label>
                    <input type="number" name="quantite" id="quantite" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('quantite', $commande->quantite) }}" required>
                </div>

                <div>
                    <label for="date_commande" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Date de commande' : 'Order Date' }}
                    </label>
                    <input type="datetime-local" name="date_commande" id="date_commande"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="{{ old('date_commande', $commande->date_commande->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Catégorie' : 'Category' }}
                    </label>
                <select name="categorie" id="categorie"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
                    <option value="">{{ $isFrench ? 'Sélectionner une catégorie' : 'Select a category' }}</option>
                    <option value="boulangerie" {{ old('categorie', $commande->categorie) == 'boulangerie' ? 'selected' : '' }}>
                        {{ $isFrench ? 'boulangerie' : 'bakery' }}
                    </option>
                    <option value="patisserie" {{ old('categorie', $commande->categorie) == 'patisserie' ? 'selected' : '' }}>
                        {{ $isFrench ? 'patisserie' : 'pastry' }}
                    </option>
                    <option value="glace" {{ old('categorie', $commande->categorie) == 'glace' ? 'selected' : '' }}>
                        {{ $isFrench ? 'glace' : 'ice cream' }}
                    </option>
                </select>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-stretch gap-4 pt-6">
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 text-center border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
                        {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    form {
        animation: fadeInUp 0.6s ease-in-out;
    }

    input, select {
        font-size: 1rem;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
}
</style>
@endsection
