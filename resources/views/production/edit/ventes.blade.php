@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    @if($isFrench)
                        Gestion des Transactions de Vente
                    @else
                        Sales Transaction Management
                    @endif
                </h2>
                <p class="text-gray-600">
                    @if($isFrench)
                        Modifiez, supprimez ou ajustez les transactions de vente
                    @else
                        Edit, delete or adjust sales transactions
                    @endif
                </p>
            </div>
            @include('buttons')
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($isFrench)
                            Date début
                        @else
                            Start Date
                        @endif
                    </label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($isFrench)
                            Date fin
                        @else
                            End Date
                        @endif
                    </label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($isFrench)
                            Produit
                        @else
                            Product
                        @endif
                    </label>
                    <select name="produit" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">
                            @if($isFrench)
                                Tous les produits
                            @else
                                All products
                            @endif
                        </option>
                        @foreach($produits as $produit)
                        <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                            {{ $produit->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($isFrench)
                            Serveur
                        @else
                            Server
                        @endif
                    </label>
                    <select name="serveur" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">
                            @if($isFrench)
                                Tous les serveurs
                            @else
                                All servers
                            @endif
                        </option>
                        @foreach($serveurs as $serveur)
                        <option value="{{ $serveur->id }}" {{ request('serveur') == $serveur->id ? 'selected' : '' }}>
                            {{ $serveur->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                        @if($isFrench)
                            Filtrer
                        @else
                            Filter
                        @endif
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des transactions -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Date
                            @else
                                Date
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Produit
                            @else
                                Product
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Serveur
                            @else
                                Server
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Quantité
                            @else
                                Quantity
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Prix unitaire
                            @else
                                Unit Price
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Total
                            @else
                                Total
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Type
                            @else
                                Type
                            @endif
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            @if($isFrench)
                                Actions
                            @else
                                Actions
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ Carbon\Carbon::parse($transaction->date_vente)->format('d/m/Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $transaction->produit_nom ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $transaction->serveur_nom ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $transaction->quantite }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ number_format($transaction->prix, 0, ',', ' ') }} FCFA</td>
                            <td class="border border-gray-300 px-4 py-2">{{ number_format($transaction->quantite * $transaction->prix, 0, ',', ' ') }} FCFA</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $transaction->type }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="editTransaction({{ $transaction->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600 mr-2">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </button>
                                <button onclick="deleteTransaction({{ $transaction->id }})" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                                {{ $isFrench ? 'Aucune transaction trouvée' : 'No transactions found' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<!-- Modal d'édition -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
    <h3 class="text-lg font-semibold mb-4">{{ $isFrench ? 'Modifier la Transaction' : 'Edit Transaction' }}</h3>
    <form id="editForm">
    @csrf
    <input type="hidden" id="transactionId">
    <div class="grid grid-cols-2 gap-4 mb-4">
    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Produit' : 'Product' }}</label>
    <select id="editProduit" name="produit" class="w-full border border-gray-300 rounded px-3 py-2" required>
    @foreach($produits as $produit)
    <option value="{{ $produit->code_produit }}">{{ $produit->nom }}</option>
    @endforeach
    </select>
    </div>
    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Serveur' : 'Server' }}</label>
    <select id="editServeur" name="serveur" class="w-full border border-gray-300 rounded px-3 py-2" required>
    @foreach($serveurs as $serveur)
    <option value="{{ $serveur->id }}">{{ $serveur->name }}</option>
    @endforeach
    </select>
    </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
    <input type="number" id="editQuantite" name="quantite" min="1" class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>
    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Prix unitaire' : 'Unit Price' }}</label>
    <input type="number" id="editPrix" name="prix" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
    <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date de vente' : 'Sale Date' }}</label>
    <input type="date" id="editDateVente" name="date_vente" class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>
   <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Type' : 'Type' }}</label>
    <select id="editType" name="type" class="w-full border border-gray-300 rounded px-3 py-2" required>
        <option value="">{{ $isFrench ? 'Sélectionnez un type' : 'Select a type' }}</option>
        <option value="Vente">Vente</option>
        <option value="Produit invendu">Produit invendu</option>
        <option value="Produit Avarie">Produit Avarié</option>
    </select>
</div>
    </div>
    <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Monnaie' : 'Currency' }}</label>
    <input type="text" id="editMonnaie" name="monnaie" class="w-full border border-gray-300 rounded px-3 py-2">
    </div>
    <div class="flex justify-end space-x-3">
    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
     {{ $isFrench ? 'Annuler' : 'Cancel' }}
    </button>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
     {{ $isFrench ? 'Enregistrer' : 'Save' }}
    </button>
    </div>
    </form>
    </div>
    </div>
<script>
function editTransaction(id) {
    fetch(`/production/edit/ventes/${id}/get`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('transactionId').value = id;
            document.getElementById('editProduit').value = data.produit;
            document.getElementById('editServeur').value = data.serveur;
            document.getElementById('editQuantite').value = data.quantite;
            document.getElementById('editPrix').value = data.prix;
            document.getElementById('editDateVente').value = data.date_vente;
            document.getElementById('editType').value = data.type;
            document.getElementById('editMonnaie').value = data.monnaie || '';
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('transactionId').value;
    const formData = new FormData(this);
    
    fetch(`/production/edit/ventes/${id}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    });
});

function deleteTransaction(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette transaction ?')) {
        fetch(`/production/edit/ventes/${id}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection
