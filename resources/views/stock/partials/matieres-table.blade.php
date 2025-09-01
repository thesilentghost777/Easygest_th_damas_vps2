{{-- Partial 1: Matières Premières --}}
<div class="bg-white shadow rounded-lg 
           md:mx-0 md:my-0 md:shadow-md 
           mx-2 my-4 shadow-xl border border-white/20 backdrop-blur-sm">
    
    {{-- Header Section --}}
    <div class="px-4 py-5 sm:p-6 
               md:bg-transparent 
               bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg">
        
        {{-- Back Button --}}
        <div class="mb-4 md:hidden">
            @include('buttons')
        </div>
        
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
            <h3 class="text-lg md:text-lg font-medium text-gray-900 
                      text-xl font-bold text-center md:text-left
                      animate-pulse md:animate-none">
                {{ $isFrench ? 'Matières Premières' : 'Raw Materials' }}
            </h3>
            
            {{-- Search Section --}}
            <div class="flex gap-4 items-center w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500
                                md:text-gray-500
                                text-blue-500">
                        <svg class="h-5 w-5 md:h-5 md:w-5 h-6 w-6 animate-bounce md:animate-none" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" id="searchMatieres" 
                           placeholder="{{ $isFrench ? 'Rechercher une matière...' : 'Search material...' }}"
                           class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md
                                  md:pl-10 md:py-2 md:border-gray-300 md:rounded-md
                                  pl-12 py-4 border-2 border-blue-200 rounded-2xl bg-white/80 backdrop-blur-sm
                                  focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all duration-300
                                  text-base placeholder-gray-400">
                </div>
            </div>
        </div>
        
        {{-- Mobile Stats Cards --}}
        <div class="grid grid-cols-2 gap-3 mb-4 md:hidden">
            <div class="bg-blue-100 rounded-xl p-3 text-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-2xl font-bold text-blue-600">{{ count($matieres) }}</div>
                <div class="text-xs text-blue-500 font-medium">{{ $isFrench ? 'Articles' : 'Items' }}</div>
            </div>
            <div class="bg-green-100 rounded-xl p-3 text-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-2xl font-bold text-green-600">
                    {{ number_format($matieres->sum(function($m) { return $m->quantite * $m->prix_unitaire; }), 0, ',', ' ') }}
                </div>
                <div class="text-xs text-green-500 font-medium">{{ $isFrench ? 'Valeur Totale' : 'Total Value' }}</div>
            </div>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Nom' : 'Name' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Quantité' : 'Quantity' }}<br>{{ $isFrench ? '(unité,sac,bidon,sachet...)' : '(unit,bag,can,packet...)' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Quantité par Unité' : 'Quantity per Unit' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Unité' : 'Unit' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Prix Unitaire' : 'Unit Price' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Valeur Totale' : 'Total Value' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tableMatieres">
                @forelse($matieres as $matiere)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $matiere->nom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($matiere->quantite, 1) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($matiere->quantite_par_unite, 1) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $matiere->unite_classique }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($matiere->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ number_format($matiere->quantite * $matiere->prix_unitaire, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="adjustQuantity('matiere', {{ $matiere->id }}, 'add')"
                                class="inline-flex items-center text-green-600 hover:text-green-900 transition-colors duration-150">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $isFrench ? 'Ajouter' : 'Add' }}
                        </button>
                        <button onclick="adjustQuantity('matiere', {{ $matiere->id }}, 'subtract')"
                                class="inline-flex items-center text-yellow-600 hover:text-yellow-900 transition-colors duration-150">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            {{ $isFrench ? 'Réduire' : 'Reduce' }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ $isFrench ? 'Aucune matière première trouvée' : 'No raw materials found' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="md:hidden space-y-3 p-4" id="tableMatieres">
        @forelse($matieres as $matiere)
        <div class="bg-white/70 backdrop-blur-sm border border-white/40 rounded-2xl p-4 
                   transform hover:scale-[1.02] transition-all duration-300 hover:shadow-lg
                   animate-fadeIn">
            
            {{-- Header with name and total value --}}
            <div class="flex justify-between items-start mb-3">
                <h4 class="font-bold text-gray-900 text-lg flex-1 pr-2">{{ $matiere->nom }}</h4>
                <div class="bg-blue-100 px-3 py-1 rounded-full">
                    <span class="text-blue-800 font-bold text-sm">
                        {{ number_format($matiere->quantite * $matiere->prix_unitaire, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
            
            {{-- Details Grid --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">{{ $isFrench ? 'Quantité' : 'Quantity' }}</div>
                    <div class="font-semibold text-gray-900">{{ number_format($matiere->quantite, 1) }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">{{ $isFrench ? 'Qté/Unité' : 'Qty/Unit' }}</div>
                    <div class="font-semibold text-gray-900">{{ number_format($matiere->quantite_par_unite, 1) }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">{{ $isFrench ? 'Unité' : 'Unit' }}</div>
                    <div class="font-semibold text-gray-900">{{ $matiere->unite_classique }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-500 mb-1">{{ $isFrench ? 'Prix Unit.' : 'Unit Price' }}</div>
                    <div class="font-semibold text-gray-900">{{ number_format($matiere->prix_unitaire, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
            
          
        </div>
        @empty
        <div class="text-center py-12">
            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">{{ $isFrench ? 'Aucune matière première trouvée' : 'No raw materials found' }}</p>
        </div>
        @endforelse
    </div>
</div>
