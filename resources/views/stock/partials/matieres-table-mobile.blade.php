<div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
    <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
        <h3 class="text-lg font-semibold text-blue-800">{{ $isFrench ? 'Matières Premières' : 'Raw Materials' }}</h3>
    </div>
    
    <div class="p-4">
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" id="searchMatieresMobile" placeholder="{{ $isFrench ? 'Rechercher...' : 'Search...' }}"
                   class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-12">
        </div>

        <div class="space-y-3" id="matieresMobileList">
            @forelse($matieres as $matiere)
            <div class="bg-gray-50 rounded-xl p-4 border-l-4 border-blue-500 transform hover:scale-102 transition-all duration-200">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-gray-900">{{ $matiere->nom }}</h4>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($matiere->quantite, 1) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Qté/Unité:' : 'Qty/Unit:' }}</span>
                        <span>{{ number_format($matiere->quantite_par_unite, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Unité:' : 'Unit:' }}</span>
                        <span>{{ $matiere->unite_classique }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Prix:' : 'Price:' }}</span>
                        <span>{{ number_format($matiere->prix_unitaire, 0, ',', ' ') }} XAF</span>
                    </div>
                    <div>
                        <span class="text-gray-500">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                        <span class="font-semibold">{{ number_format($matiere->quantite * $matiere->prix_unitaire, 0, ',', ' ') }} XAF</span>
                    </div>
                </div>
                <div class="flex space-x-2 mt-3">
                    <button onclick="adjustQuantity('matiere', {{ $matiere->id }}, 'add')"
                            class="flex-1 bg-green-100 text-green-700 py-2 px-3 rounded-lg text-sm font-medium transform active:scale-95 transition-all duration-150">
                        <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Ajouter' : 'Add' }}
                    </button>
                    <button onclick="adjustQuantity('matiere', {{ $matiere->id }}, 'subtract')"
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-500">{{ $isFrench ? 'Aucune matière première trouvée' : 'No raw materials found' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
