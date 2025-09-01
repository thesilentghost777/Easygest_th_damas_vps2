<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-green-50 border-b border-green-100">
        <h3 class="text-lg font-semibold text-green-800">{{ $isFrench ? 'Produits' : 'Products' }}</h3>
    </div>
    
    <div class="p-4">
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="searchProduitsMobile" placeholder="{{ $isFrench ? 'Rechercher...' : 'Search...' }}"
                   class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-0 bg-gray-50 h-12">
        </div>

        <div class="space-y-3" id="produitsMobileList">
            @forelse($produits as $produit)
            <div class="bg-gray-50 rounded-xl p-4 border-l-4 border-green-500 transform hover:scale-102 transition-all duration-200">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $produit->nom }}</h4>
                        <p class="text-xs text-gray-500">{{ $produit->code_produit }}</p>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ number_format($produit->quantite_totale, 0) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</span>
                        <span>{{ $produit->categorie }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Prix:' : 'Price:' }}</span>
                        <span>{{ number_format($produit->prix, 0, ',', ' ') }} XAF</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">{{ $isFrench ? 'Valeur totale:' : 'Total value:' }}</span>
                        <span class="font-semibold">{{ number_format($produit->quantite_totale * $produit->prix, 0, ',', ' ') }} XAF</span>
                    </div>
                </div>
                <div class="flex space-x-2 mt-3">
                    <button onclick="adjustQuantity('produit', {{ $produit->code_produit }}, 'add')"
                            class="flex-1 bg-green-100 text-green-700 py-2 px-3 rounded-lg text-sm font-medium transform active:scale-95 transition-all duration-150">
                        <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Ajouter' : 'Add' }}
                    </button>
                    <button onclick="adjustQuantity('produit', {{ $produit->code_produit }}, 'subtract')"
                            class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-lg text-sm font-medium transform active:scale-95 transition-all duration-150">
                        <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        {{ $isFrench ? 'Réduire' : 'Reduce' }}
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <p class="text-gray-500">{{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
