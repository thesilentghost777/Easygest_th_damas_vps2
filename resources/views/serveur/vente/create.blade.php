@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $isFrench ? 'Enregistrer une vente' : 'Record a Sale' }}
            </h1>
            @include('buttons')
        </div>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-6 transition-all duration-500 ease-in-out transform hover:scale-[1.01]">
            <form action="{{ route('serveur.vente.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Type de transaction' : 'Transaction Type' }}
                    </label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio text-blue-600" name="type" value="Vente" checked>
                            <span class="ml-2">{{ $isFrench ? 'Vente' : 'Sale' }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio text-blue-600" name="type" value="Produit invendu">
                            <span class="ml-2">{{ $isFrench ? 'Produit invendu' : 'Unsold Product' }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio text-blue-600" name="type" value="Produit Avarie">
                            <span class="ml-2">{{ $isFrench ? 'Produit avarié' : 'Damaged Product' }}</span>
                        </label>
                    </div>
                </div>

                <!-- Nouvelle section pour la date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date de la vente' : 'Sale Date' }}
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="date" name="date_vente" id="date_vente" 
                               class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               value="{{ date('Y-m-d') }}" required>
                        <div class="flex gap-2">
                            <button type="button" id="btnAujourdhui" 
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors duration-200 text-sm font-medium">
                                {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                            </button>
                            <button type="button" id="btnHier" 
                                    class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors duration-200 text-sm font-medium">
                                {{ $isFrench ? 'Hier' : 'Yesterday' }}
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $isFrench ? 'Sélectionnez la date de la transaction' : 'Select the transaction date' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select name="produit" id="produit" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->quantite ?? 0 }}">
                                {{ $produit->nom }} - {{ $produit->prix ?? 0 }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $isFrench ? 'Stock disponible' : 'Available stock' }}: <span id="stockDisponible">0</span>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                        </label>
                        <input type="number" name="quantite" id="quantite" min="1" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Prix unitaire (FCFA)' : 'Unit Price (FCFA)' }}
                        </label>
                        <input type="number" name="prix" id="prix" min="0" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Moyen de paiement' : 'Payment Method' }}
                    </label>
                    <select name="monnaie" id="monnaie" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($typesPaiement as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="text-lg font-medium">
                            {{ $isFrench ? 'Total' : 'Total' }}: <span id="totalAmount" class="font-bold">0 FCFA</span>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 animate-bounce sm:animate-none">
                            {{ $isFrench ? 'Enregistrer' : 'Submit' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit');
    const prixInput = document.getElementById('prix');
    const quantiteInput = document.getElementById('quantite');
    const totalAmount = document.getElementById('totalAmount');
    const stockDisponible = document.getElementById('stockDisponible');
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const monnaie = document.getElementById('monnaie');
    const dateVente = document.getElementById('date_vente');
    const btnAujourdhui = document.getElementById('btnAujourdhui');
    const btnHier = document.getElementById('btnHier');

    // Gestion des boutons de date
    btnAujourdhui.addEventListener('click', function() {
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];
        dateVente.value = todayStr;
        
        // Animation du bouton
        this.classList.add('scale-95');
        setTimeout(() => this.classList.remove('scale-95'), 150);
    });

    btnHier.addEventListener('click', function() {
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = yesterday.toISOString().split('T')[0];
        dateVente.value = yesterdayStr;
        
        // Animation du bouton
        this.classList.add('scale-95');
        setTimeout(() => this.classList.remove('scale-95'), 150);
    });

    produitSelect.addEventListener('change', function() {
        const selectedOption = produitSelect.options[produitSelect.selectedIndex];
        if (selectedOption.value) {
            const prix = selectedOption.dataset.prix || 0;
            const stock = selectedOption.dataset.stock || 0;
            
            // Ne pas écraser le prix si ce n'est pas une vente
            const currentType = document.querySelector('input[name="type"]:checked').value;
            if (currentType === 'Vente') {
                prixInput.value = prix;
            }
            
            stockDisponible.textContent = stock;
            calculateTotal();
        } else {
            prixInput.value = '';
            stockDisponible.textContent = '0';
            totalAmount.textContent = '0 FCFA';
        }
    });

    function calculateTotal() {
        const prix = parseFloat(prixInput.value) || 0;
        const quantite = parseInt(quantiteInput.value) || 0;
        const total = prix * quantite;
        totalAmount.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    }

    prixInput.addEventListener('input', calculateTotal);
    quantiteInput.addEventListener('input', calculateTotal);

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Vente') {
                prixInput.removeAttribute('readonly');
                prixInput.removeAttribute('disabled');
                monnaie.removeAttribute('disabled');
                
                // Restaurer le prix du produit sélectionné
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                if (selectedOption.value) {
                    prixInput.value = selectedOption.dataset.prix || 0;
                }
            } else {
                // Pour les produits invendus/avariés, mettre le prix à 0
                prixInput.value = 0;
                prixInput.setAttribute('readonly', true);
                monnaie.value = 'Espèces';
                monnaie.setAttribute('readonly', true);
            }
            calculateTotal();
        });
    });
});
</script>
@endsection