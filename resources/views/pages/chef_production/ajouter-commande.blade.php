@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Mobile Back Button -->
    @include('buttons')

    <!-- Bouton "Nouvelle commande" pour afficher/masquer le formulaire -->
    <div class="text-center">
        <button id="toggleFormButton"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 mobile:animate-bounce mobile:transform mobile:hover:scale-105 mobile:transition mobile:duration-300">
            {{ $isFrench ? 'Nouvelle Commande' : 'New Order' }}
        </button>
    </div>

    <!-- Formulaire d'ajout de commande caché initialement -->
    <div id="orderForm" class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 mt-6 hidden mobile:animate-slideDown mobile:duration-300">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $isFrench ? 'Nouvelle Commande' : 'New Order' }}</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mobile:animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mobile:animate-shake">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('chef.commandes.store2') }}" method="POST" id="commandeForm">
            @csrf
            
            <!-- Section 1: Informations générales de la commande -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-4">{{ $isFrench ? 'Informations générales' : 'General Information' }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mobile:animate-fadeIn mobile:delay-100">
                        <label for="libelle" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Libellé de la commande' : 'Order Label' }}</label>
                        <input type="text" name="libelle" id="libelle"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mobile:p-3"
                               value="{{ old('libelle') }}" required maxlength="50"
                               placeholder="{{ $isFrench ? 'Ex: Commande du matin, Événement spécial...' : 'Ex: Morning order, Special event...' }}">
                    </div>

                    <div class="flex items-end space-x-2 mobile:animate-fadeIn mobile:delay-200">
                        <div class="flex-grow">
                            <label for="date_commande" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Date de commande' : 'Order Date' }}</label>
                            <input type="datetime-local" name="date_commande" id="date_commande"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mobile:p-3"
                                   value="{{ old('date_commande') }}" required>
                        </div>
                        <button type="button" id="setTodayBtn"
                                class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 h-10 mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                            {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section 2: Ajout de produits -->
            <div class="bg-green-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-green-800 mb-4">{{ $isFrench ? 'Produits à commander' : 'Products to Order' }}</h3>
                
                <!-- Formulaire d'ajout de produit -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-white rounded-lg border-2 border-dashed border-green-300">
                    <div>
                        <label for="produit_select" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Produit' : 'Product' }}</label>
                        <select id="produit_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 mobile:p-3">
                            <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->code_produit }}" data-nom="{{ $produit->nom }}" data-prix="{{ $produit->prix }}" data-categorie="{{ $produit->categorie }}">
                                    {{ $produit->nom }} - {{ $produit->prix }}FCFA
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="quantite_input" class="block text-sm font-medium text-gray-700">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                        <input type="number" id="quantite_input" min="1" value="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 mobile:p-3">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="button" id="addProductBtn"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 mobile:transform mobile:hover:scale-105 mobile:transition mobile:duration-200">
                            {{ $isFrench ? 'Ajouter Produit' : 'Add Product' }}
                        </button>
                    </div>
                </div>

                <!-- Tableau des produits ajoutés -->
                <div class="bg-white rounded-lg border">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h4 class="text-md font-medium text-gray-900">{{ $isFrench ? 'Produits dans cette commande' : 'Products in this order' }}</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="productsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="productsTableBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                        {{ $isFrench ? 'Aucun produit ajouté' : 'No products added' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section 3: Validation -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="resetBtn"
                        class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                </button>
                <button type="submit" id="submitBtn" disabled
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                    {{ $isFrench ? 'Valider la Commande' : 'Validate Order' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Section Liste des commandes -->
    <div class="max-w-6xl mx-auto mt-8 bg-white rounded-lg shadow-md p-6 mobile:animate-slideUp mobile:duration-300">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $isFrench ? 'Liste des Commandes' : 'Orders List' }}</h2>

        <div class="overflow-x-auto">
            <!-- Desktop Table -->
            <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Libellé' : 'Label' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($commandes->sortByDesc('created_at') as $commande)
                    <tr class="hover:bg-gray-50 mobile:transition mobile:duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->libelle }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->produit_fixe->nom ?? ($isFrench ? 'Produit non trouvé' : 'Product not found') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->quantite }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->date_commande }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $isFrench ? $commande->categorie : 
                               ($commande->categorie == 'patisserie' ? 'Pastry' : 
                               ($commande->categorie == 'glace' ? 'Ice Cream' : 'Bakery')) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $commande->valider ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $commande->valider ? ($isFrench ? 'Effectuée' : 'Completed') : ($isFrench ? 'Non effectuée' : 'Pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('commande.edit', $commande->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 mr-3 mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                               {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>

                            <button onclick="deleteCommande({{ $commande->id }})"
                                    class="text-red-600 hover:text-red-900 mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                                {{ $isFrench ? 'Supprimer' : 'Delete' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @foreach($commandes->sortByDesc('created_at') as $commande)
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 mobile:animate-fadeIn mobile:duration-300 mobile:transform mobile:hover:scale-[1.01] mobile:transition mobile:duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-blue-600">{{ $commande->libelle }}</h3>
                            <p class="text-gray-600">{{ $commande->produit_fixe->nom ?? ($isFrench ? 'Produit non trouvé' : 'Product not found') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $commande->valider ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $commande->valider ? ($isFrench ? 'Effectuée' : 'Completed') : ($isFrench ? 'Non effectuée' : 'Pending') }}
                        </span>
                    </div>
                    
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Quantité' : 'Quantity' }}</p>
                            <p>{{ $commande->quantite }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Date' : 'Date' }}</p>
                            <p>{{ $commande->date_commande }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{ $isFrench ? 'Catégorie' : 'Category' }}</p>
                            <p>{{ $isFrench ? $commande->categorie : 
                                ($commande->categorie == 'patisserie' ? 'Pastry' : 
                                ($commande->categorie == 'glace' ? 'Ice Cream' : 'Bakery')) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex justify-end space-x-2">
                        <a href="{{ route('commande.edit', $commande->id) }}"
                           class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                           {{ $isFrench ? 'Modifier' : 'Edit' }}
                        </a>
                        <button onclick="deleteCommande({{ $commande->id }})"
                                class="px-3 py-1 bg-red-500 text-white rounded-md text-sm mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    /* Mobile Animations */
    @media (max-width: 768px) {
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .mobile\:animate-slideDown {
            animation: slideDown 0.3s ease-out forwards;
        }
        
        .mobile\:animate-slideUp {
            animation: slideUp 0.3s ease-out forwards;
        }
        
        .mobile\:animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .mobile\:animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .mobile\:animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .mobile\:animate-bounce {
            animation: bounce 2s infinite;
        }
        
        /* Add padding to form elements on mobile */
        .mobile\:p-3 {
            padding: 0.75rem;
        }
    }
</style>

<script>
    let productsInOrder = [];

    document.addEventListener('DOMContentLoaded', function() {
        const toggleFormButton = document.getElementById('toggleFormButton');
        const orderForm = document.getElementById('orderForm');
        const setTodayBtn = document.getElementById('setTodayBtn');
        const addProductBtn = document.getElementById('addProductBtn');
        const resetBtn = document.getElementById('resetBtn');
        const commandeForm = document.getElementById('commandeForm');

        // Toggle form visibility
        toggleFormButton.addEventListener('click', function() {
            orderForm.classList.toggle('hidden');
            
            if (!orderForm.classList.contains('hidden')) {
                orderForm.classList.add('mobile:animate-slideDown');
                setTimeout(() => {
                    orderForm.classList.remove('mobile:animate-slideDown');
                }, 300);
            }
        });

        // Set today's date
        setTodayBtn.addEventListener('click', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById('date_commande').value = formattedDateTime;
        });

        // Add product to order
        addProductBtn.addEventListener('click', function() {
            const produitSelect = document.getElementById('produit_select');
            const quantiteInput = document.getElementById('quantite_input');
            
            if (!produitSelect.value || !quantiteInput.value || quantiteInput.value < 1) {
                alert('{{ $isFrench ? "Veuillez sélectionner un produit et une quantité valide" : "Please select a product and valid quantity" }}');
                return;
            }

            const selectedOption = produitSelect.options[produitSelect.selectedIndex];
            const productData = {
                id: produitSelect.value,
                nom: selectedOption.dataset.nom,
                prix: selectedOption.dataset.prix,
                categorie: selectedOption.dataset.categorie,
                quantite: parseInt(quantiteInput.value)
            };

            // Check if product already exists in order
            const existingProductIndex = productsInOrder.findIndex(p => p.id === productData.id);
            if (existingProductIndex !== -1) {
                productsInOrder[existingProductIndex].quantite += productData.quantite;
            } else {
                productsInOrder.push(productData);
            }

            updateProductsTable();
            
            // Reset product selection
            produitSelect.selectedIndex = 0;
            quantiteInput.value = 1;
        });

        // Reset form
        resetBtn.addEventListener('click', function() {
            if (confirm('{{ $isFrench ? "Êtes-vous sûr de vouloir réinitialiser le formulaire ?" : "Are you sure you want to reset the form?" }}')) {
                productsInOrder = [];
                updateProductsTable();
                document.getElementById('libelle').value = '';
                document.getElementById('date_commande').value = '';
                document.getElementById('produit_select').selectedIndex = 0;
                document.getElementById('quantite_input').value = 1;
            }
        });

        // Form submission
        commandeForm.addEventListener('submit', function(e) {
            if (productsInOrder.length === 0) {
                e.preventDefault();
                alert('{{ $isFrench ? "Veuillez ajouter au moins un produit à la commande" : "Please add at least one product to the order" }}');
                return;
            }

            // Add hidden inputs for products
            productsInOrder.forEach((product, index) => {
                const hiddenInputs = [
                    { name: `produits[${index}][id]`, value: product.id },
                    { name: `produits[${index}][quantite]`, value: product.quantite },
                    { name: `produits[${index}][categorie]`, value: product.categorie }
                ];

                hiddenInputs.forEach(input => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    commandeForm.appendChild(hiddenInput);
                });
            });
        });
    });

    function updateProductsTable() {
        const tableBody = document.getElementById('productsTableBody');
        const emptyRow = document.getElementById('emptyRow');
        const submitBtn = document.getElementById('submitBtn');

        // Clear existing rows (except empty row)
        const existingRows = tableBody.querySelectorAll('tr:not(#emptyRow)');
        existingRows.forEach(row => row.remove());

        if (productsInOrder.length === 0) {
            emptyRow.style.display = 'table-row';
            submitBtn.disabled = true;
        } else {
            emptyRow.style.display = 'none';
            submitBtn.disabled = false;

            productsInOrder.forEach((product, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">${product.nom}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">${product.prix}FCFA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">${product.quantite}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${product.categorie}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button type="button" onclick="removeProduct(${index})" 
                                class="text-red-600 hover:text-red-900 mobile:transform mobile:hover:scale-110 mobile:transition mobile:duration-200">
                            {{ $isFrench ? 'Supprimer' : 'Remove' }}
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
    }

    function removeProduct(index) {
        productsInOrder.splice(index, 1);
        updateProductsTable();
    }

    function deleteCommande(id) {
        if (confirm('{{ $isFrench ? "Êtes-vous sûr de vouloir supprimer cette commande ?" : "Are you sure you want to delete this order?" }}')) {
            fetch(`/commandes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert('{{ $isFrench ? "Erreur lors de la suppression" : "Error while deleting" }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ $isFrench ? "Erreur lors de la suppression" : "Error while deleting" }}');
            });
        }
    }

    // Add hover effects for mobile
    if (window.innerWidth <= 768) {
        const buttons = document.querySelectorAll('button, a');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('mobile:transform', 'mobile:scale-105');
            });
            
            button.addEventListener('touchend', function() {
                this.classList.remove('mobile:transform', 'mobile:scale-105');
            });
        });
    }
</script>
@endsection