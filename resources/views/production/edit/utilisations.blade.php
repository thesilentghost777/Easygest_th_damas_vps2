@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    {{ $isFrench ? 'Gestion des Utilisations de Matières' : 'Material Usage Management' }}
                </h2>
                <p class="text-gray-600">
                    {{ $isFrench 
                        ? 'Modifiez, supprimez ou ajustez les utilisations de matières premières' 
                        : 'Modify, delete or adjust raw material usage' 
                    }}
                </p>
            </div>
            @include('buttons')
        </div>

        <!-- Filtres / Filters -->
<div class="bg-gray-50 rounded-lg p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $isFrench ? 'Date début' : 'Start Date' }}
            </label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $isFrench ? 'Date fin' : 'End Date' }}
            </label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $isFrench ? 'ID Lot' : 'Batch ID' }}
            </label>
            <input type="text" name="id_lot" value="{{ request('id_lot') }}" class="w-full border border-gray-300 rounded px-3 py-2" 
                   placeholder="{{ $isFrench ? 'Rechercher par lot' : 'Search by batch' }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $isFrench ? 'Produit' : 'Product' }}
            </label>
            <select name="produit" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                @foreach($produits as $produit)
                    <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                        {{ $produit->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                {{ $isFrench ? 'Filtrer' : 'Filter' }}
            </button>
        </div>
    </form>
</div>

        <!-- Tableau des utilisations -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'ID Lot' : 'Batch ID' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Produit' : 'Product' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Matière' : 'Material' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Qté Produit' : 'Product Qty' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Qté Matière' : 'Material Qty' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Unité' : 'Unit' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </th>
                        <th class="border border-gray-300 px-4 py-2 text-left">
                            {{ $isFrench ? 'Actions' : 'Actions' }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisations as $utilisation)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->id_lot }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->produit_nom ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->matiere_nom ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->producteur_nom ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->quantite_produit }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->quantite_matiere }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $utilisation->unite_matiere }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ Carbon\Carbon::parse($utilisation->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="editUtilisation({{ $utilisation->id }})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600 mr-2">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </button>
                                <button onclick="deleteUtilisation({{ $utilisation->id }})" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                                {{ $isFrench ? 'Aucune Production trouvée' : 'No Production found' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $utilisations->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div id="editUtilisationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            {{ $isFrench ? 'Modifier l\'Utilisation' : 'Edit Usage' }}
        </h3>
        <form id="editUtilisationForm">
            @csrf
            <input type="hidden" id="utilisationId">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'ID Lot' : 'Batch ID' }}
                    </label>
                    <input type="text" id="editIdLot" name="id_lot" maxlength="20" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select id="editProduitUtil" name="produit" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}">{{ $produit->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Matière' : 'Material' }}
                    </label>
                    <select id="editMatiere" name="matierep" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Producteur' : 'Producer' }}
                    </label>
                    <select id="editProducteur" name="producteur" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        @foreach($producteurs as $producteur)
                            <option value="{{ $producteur->id }}">{{ $producteur->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Qté Produit' : 'Product Qty' }}
                    </label>
                    <input type="number" id="editQuantiteProduit" name="quantite_produit" step="0.01" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Qté Matière' : 'Material Qty' }}
                    </label>
                    <input type="number" id="editQuantiteMatiere" name="quantite_matiere" step="0.001" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Unité' : 'Unit' }}
                    </label>
                    <input type="text" id="editUniteMatiere" name="unite_matiere" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditUtilisationModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
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
function editUtilisation(id) {
    fetch(`/production/edit/utilisations/${id}/get`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('utilisationId').value = id;
            document.getElementById('editIdLot').value = data.id_lot;
            document.getElementById('editProduitUtil').value = data.produit;
            document.getElementById('editMatiere').value = data.matierep;
            document.getElementById('editProducteur').value = data.producteur;
            document.getElementById('editQuantiteProduit').value = data.quantite_produit;
            document.getElementById('editQuantiteMatiere').value = data.quantite_matiere;
            document.getElementById('editUniteMatiere').value = data.unite_matiere;
            
            document.getElementById('editUtilisationModal').classList.remove('hidden');
            document.getElementById('editUtilisationModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données');
        });
}

function closeEditUtilisationModal() {
    document.getElementById('editUtilisationModal').classList.add('hidden');
    document.getElementById('editUtilisationModal').classList.remove('flex');
}

document.getElementById('editUtilisationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('utilisationId').value;
    const formData = new FormData(this);
    
    fetch(`/production/edit/utilisations/${id}/update`, {
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

function deleteUtilisation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette utilisation ?')) {
        fetch(`/production/edit/utilisations/${id}/delete`, {
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
