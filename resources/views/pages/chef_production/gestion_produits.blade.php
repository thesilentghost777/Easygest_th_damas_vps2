
@extends('pages.chef_production.chef_production_default')

@section('page-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('buttons')
    
    <div class="mb-6">
        <div class="hidden md:block">
            <h1 class="text-3xl font-bold text-gray-800">
                {{ $isFrench ? 'Gestion des Produits' : 'Products Management' }}
            </h1>
        </div>
        
        <div class="md:hidden text-center">
            <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                </svg>
            </div>
            <h1 class="text-xl text-green-600 font-bold">
                {{ $isFrench ? 'Gestion Produits' : 'Products Management' }}
            </h1>
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
        <div class="mobile:bg-gradient-to-r mobile:from-green-50 mobile:to-blue-50 mobile:p-6 md:p-6">
            <h2 class="text-lg font-semibold mb-4 md:text-xl mobile:text-center mobile:text-green-700">
                {{ $isFrench ? 'Ajouter un Produit' : 'Add Product' }}
            </h2>
            
            <form action="{{ route('chef.produits.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mobile:gap-6">
                    <div class="mobile:bg-blue-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-blue-700">
                            {{ $isFrench ? 'Nom du produit' : 'Product Name' }}
                        </label>
                        <input type="text" name="nom" placeholder="{{ $isFrench ? 'Nom du produit' : 'Product name' }}" required
                               value="{{ old('nom') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-blue-200 mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-purple-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-purple-700">
                            {{ $isFrench ? 'Prix (XAF)' : 'Price (XAF)' }}
                        </label>
                        <input type="number" name="prix" placeholder="{{ $isFrench ? 'Prix' : 'Price' }}" required
                               value="{{ old('prix') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                      mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:border-purple-200 mobile:focus:border-purple-500 mobile:focus:ring-2 mobile:focus:ring-purple-200 mobile:bg-white mobile:shadow-sm
                                      md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                    </div>

                    <div class="mobile:bg-yellow-50 mobile:p-4 mobile:rounded-xl md:bg-transparent md:p-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-yellow-700">
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                        </label>
                        <select name="categorie" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                                       mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-yellow-200 mobile:focus:border-yellow-500 mobile:focus:ring-2 mobile:focus:ring-yellow-200 mobile:bg-white mobile:shadow-sm
                                       md:py-2 md:px-3 md:text-base md:rounded-md md:border md:border-gray-300">
                            <option value="">{{ $isFrench ? 'Sélectionner une catégorie' : 'Select a category' }}</option>
                            <option value="boulangerie" {{ old('categorie') == 'boulangerie' ? 'selected' : '' }}>{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</option>
                            <option value="patisserie" {{ old('categorie') == 'patisserie' ? 'selected' : '' }}>{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</option>
                            <option value="glace" {{ old('categorie') == 'glace' ? 'selected' : '' }}>{{ $isFrench ? 'Glace' : 'Ice Cream' }}</option>
                        </select>
                    </div>

                    <div class="mobile:col-span-1 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2 mobile:text-center mobile:font-semibold mobile:text-green-700 md:invisible">
                            {{ $isFrench ? 'Action' : 'Action' }}
                        </label>
                        <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 
                                       mobile:py-4 mobile:text-lg mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-green-500 mobile:to-green-600 mobile:hover:from-green-600 mobile:hover:to-green-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300
                                       md:py-2 md:px-4 md:text-base md:rounded-lg md:bg-green-500 md:hover:bg-green-600">
                            {{ $isFrench ? 'Ajouter' : 'Add' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des produits -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="mobile:p-4 mobile:bg-gradient-to-r mobile:from-gray-50 mobile:to-gray-100 md:p-0">
            <h3 class="text-lg font-semibold mb-4 mobile:text-center mobile:text-gray-700 md:hidden">
                {{ $isFrench ? 'Liste des Produits' : 'Products List' }}
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Nom' : 'Name' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4 mobile:hidden md:table-cell">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                        <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider mobile:px-3 mobile:py-4">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($produits as $produit)
                    <tr class="mobile:hover:bg-green-50 mobile:transition-colors mobile:duration-200" data-produit-id="{{ $produit->code_produit }}">
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:font-medium nom">{{ $produit->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:text-center mobile:font-semibold mobile:text-green-600 prix">{{ number_format($produit->prix) }} XAF</td>
                        <td class="px-6 py-4 whitespace-nowrap mobile:px-3 mobile:py-4 mobile:text-sm mobile:hidden md:table-cell categorie">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $produit->categorie == 'boulangerie' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                @if($isFrench)
                                    {{ ucfirst($produit->categorie) }}
                                @else
                                    @if($produit->categorie == 'boulangerie')
                                        Bakery
                                    @elseif($produit->categorie == 'patisserie')
                                        Pastry
                                    @else
                                        Ice Cream
                                    @endif
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium mobile:px-3 mobile:py-4">
                            <div class="flex space-x-2 mobile:flex-col mobile:space-x-0 mobile:space-y-2 md:flex-row md:space-x-2 md:space-y-0">
                                <button onclick="editProduit({{ $produit->code_produit }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mobile:bg-blue-100 mobile:text-blue-700 mobile:px-3 mobile:py-2 mobile:rounded-lg mobile:text-xs mobile:font-semibold mobile:hover:bg-blue-200 mobile:transition-colors mobile:duration-200 md:bg-blue-500 md:text-white md:hover:bg-blue-600">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </button>
                                <button onclick="confirmDelete({{ $produit->code_produit }})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded mobile:bg-red-100 mobile:text-red-700 mobile:px-3 mobile:py-2 mobile:rounded-lg mobile:text-xs mobile:font-semibold mobile:hover:bg-red-200 mobile:transition-colors mobile:duration-200 md:bg-red-500 md:text-white md:hover:bg-red-600">
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 mobile:px-4">
            {{ $produits->links() }}
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
    .mobile\:mx-auto { margin-left: auto; margin-right: auto; }
    .mobile\:text-center { text-align: center; }
    .mobile\:text-sm { font-size: 0.875rem; }
    .mobile\:text-lg { font-size: 1.125rem; }
    .mobile\:text-xl { font-size: 1.25rem; }
    .mobile\:text-xs { font-size: 0.75rem; }
    .mobile\:font-medium { font-weight: 500; }
    .mobile\:font-semibold { font-weight: 600; }
    .mobile\:font-bold { font-weight: 700; }
    .mobile\:bg-green-100 { background-color: #dcfce7; }
    .mobile\:bg-green-50 { background-color: #f0fdf4; }
    .mobile\:bg-blue-50 { background-color: #eff6ff; }
    .mobile\:bg-purple-50 { background-color: #faf5ff; }
    .mobile\:bg-yellow-50 { background-color: #fefce8; }
    .mobile\:bg-gray-50 { background-color: #f9fafb; }
    .mobile\:bg-gray-100 { background-color: #f3f4f6; }
    .mobile\:bg-blue-100 { background-color: #dbeafe; }
    .mobile\:bg-red-100 { background-color: #fee2e2; }
    .mobile\:bg-white { background-color: #ffffff; }
    .mobile\:bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .mobile\:from-green-50 { --tw-gradient-from: #f0fdf4; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(240, 253, 244, 0)); }
    .mobile\:to-blue-50 { --tw-gradient-to: #eff6ff; }
    .mobile\:from-gray-50 { --tw-gradient-from: #f9fafb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0)); }
    .mobile\:to-gray-100 { --tw-gradient-to: #f3f4f6; }
    .mobile\:from-green-500 { --tw-gradient-from: #22c55e; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(34, 197, 94, 0)); }
    .mobile\:to-green-600 { --tw-gradient-to: #16a34a; }
    .mobile\:from-green-600 { --tw-gradient-from: #16a34a; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(22, 163, 74, 0)); }
    .mobile\:to-green-700 { --tw-gradient-to: #15803d; }
    .mobile\:hover\:from-green-600:hover { --tw-gradient-from: #16a34a; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(22, 163, 74, 0)); }
    .mobile\:hover\:to-green-700:hover { --tw-gradient-to: #15803d; }
    .mobile\:text-green-600 { color: #16a34a; }
    .mobile\:text-green-700 { color: #15803d; }
    .mobile\:text-blue-600 { color: #2563eb; }
    .mobile\:text-blue-700 { color: #1d4ed8; }
    .mobile\:text-purple-700 { color: #7c2d12; }
    .mobile\:text-yellow-700 { color: #a16207; }
    .mobile\:text-gray-700 { color: #374151; }
    .mobile\:text-red-700 { color: #b91c1c; }
    .mobile\:rounded-full { border-radius: 9999px; }
    .mobile\:rounded-xl { border-radius: 0.75rem; }
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
    .mobile\:col-span-1 { grid-column: span 1 / span 1; }
    .mobile\:border-2 { border-width: 2px; }
    .mobile\:border-blue-200 { border-color: #bfdbfe; }
    .mobile\:border-purple-200 { border-color: #e9d5ff; }
    .mobile\:border-yellow-200 { border-color: #fde68a; }
    .mobile\:focus\:border-blue-500:focus { border-color: #3b82f6; }
    .mobile\:focus\:border-purple-500:focus { border-color: #7c3aed; }
    .mobile\:focus\:border-yellow-500:focus { border-color: #eab308; }
    .mobile\:focus\:ring-2:focus { box-shadow: 0 0 0 2px var(--tw-ring-color); }
    .mobile\:focus\:ring-blue-200:focus { --tw-ring-color: #bfdbfe; }
    .mobile\:focus\:ring-purple-200:focus { --tw-ring-color: #e9d5ff; }
    .mobile\:focus\:ring-yellow-200:focus { --tw-ring-color: #fde68a; }
    .mobile\:shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    .mobile\:shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:hover\:bg-green-50:hover { background-color: #f0fdf4; }
    .mobile\:hover\:bg-blue-200:hover { background-color: #bfdbfe; }
    .mobile\:hover\:bg-red-200:hover { background-color: #fecaca; }
    .mobile\:transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; }
    .mobile\:transition-all { transition-property: all; }
    .mobile\:duration-200 { transition-duration: 200ms; }
    .mobile\:duration-300 { transition-duration: 300ms; }
    .mobile\:transform { transform: translateVar(--tw-translate-x, 0) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)); }
    .mobile\:hover\:scale-105:hover { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }
    .mobile\:active\:scale-95:active { --tw-scale-x: 0.95; --tw-scale-y: 0.95; }
    .mobile\:animate-fade-in { animation: fadeIn 0.3s ease-out; }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
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

function editProduit(id) {
    const row = document.querySelector(`tr[data-produit-id="${id}"]`);
    const nom = row.querySelector('.nom').textContent;
    const prix = row.querySelector('.prix').textContent.replace(/[^\d]/g, '');
    const categorieElement = row.querySelector('.categorie');
    const categorieText = categorieElement ? categorieElement.textContent.toLowerCase() : '';
    
    let categorie = 'boulangerie';
if (categorieText.includes('patisserie') || categorieText.includes('pastry')) {
    categorie = 'patisserie';
} else if (categorieText.includes('glace') || categorieText.includes('ice cream')) {
    categorie = 'glace';
}

    const form = document.createElement('tr');
    form.innerHTML = `
        <td colspan="4" class="px-4 py-2 mobile:px-3">
            <form class="flex gap-4 mobile:flex-col mobile:gap-4 mobile:bg-blue-50 mobile:p-4 mobile:rounded-xl" onsubmit="updateProduit(event, ${id})">
                @csrf
                <input type="text" name="nom" value="${nom}" required 
                       class="border rounded px-2 py-1 mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-blue-200 mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200">
                <input type="number" name="prix" value="${prix}" required 
                       class="border rounded px-2 py-1 mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-purple-200 mobile:focus:border-purple-500 mobile:focus:ring-2 mobile:focus:ring-purple-200 mobile:text-center">
                <select name="categorie" required 
                        class="border rounded px-2 py-1 mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:border-2 mobile:border-yellow-200 mobile:focus:border-yellow-500 mobile:focus:ring-2 mobile:focus:ring-yellow-200">
                    <option value="boulangerie" ${categorie === 'boulangerie' ? 'selected' : ''}>{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</option>
                    <option value="patisserie" ${categorie === 'patisserie' ? 'selected' : ''}>{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</option>
                    <option value="glace" ${categorie === 'glace' ? 'selected' : ''}>{{ $isFrench ? 'Glace' : 'Ice Cream' }}</option>
                    </select>
                <div class="flex gap-2 mobile:flex-col mobile:gap-4">
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-green-500 mobile:to-green-600 mobile:hover:from-green-600 mobile:hover:to-green-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300">
                        {{ $isFrench ? 'Sauvegarder' : 'Save' }}
                    </button>
                    <button type="button" onclick="cancelEdit(${id})" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded mobile:py-3 mobile:px-4 mobile:text-lg mobile:rounded-xl mobile:bg-gray-400 mobile:hover:bg-gray-500">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                </div>
            </form>
        </td>
    `;

    row.replaceWith(form);
}

async function updateProduit(event, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/cp/produits/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });

        const data = await response.json();

        if (response.ok) {
            window.location.reload();
        } else {
            alert(data.message || '{{ $isFrench ? "Erreur lors de la mise à jour" : "Error during update" }}');
        }
    } catch (error) {
        alert('{{ $isFrench ? "Erreur lors de la mise à jour" : "Error during update" }}');
        console.error(error);
    }
}

function confirmDelete(id) {
    if (confirm('{{ $isFrench ? "Êtes-vous sûr de vouloir supprimer ce produit ?" : "Are you sure you want to delete this product?" }}')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/cp/produits/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert(data.message || '{{ $isFrench ? "Erreur lors de la suppression" : "Error during deletion" }}');
            }
        })
        .catch(error => {
            alert('{{ $isFrench ? "Erreur lors de la suppression" : "Error during deletion" }}');
            console.error(error);
        });
    }
}

function cancelEdit(id) {
    window.location.reload();
}
</script>
@endsection
