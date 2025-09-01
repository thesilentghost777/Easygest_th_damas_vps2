<div class="relative overflow-hidden rounded-lg shadow-lg">
    <!-- Carte style FIFA -->
    <div class="bg-gradient-to-b {{ $isTop ? 'from-blue-800 to-blue-900' : 'from-gray-800 to-gray-900' }} h-full">
        <!-- En-tête avec note -->
        <div class="flex justify-between items-center p-4">
            <div class="{{ $isTop ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 'bg-gradient-to-br from-gray-400 to-gray-600' }} text-black font-bold text-3xl w-16 h-16 flex items-center justify-center rounded-lg shadow">
                {{ round($vendeur->score_global) }}
            </div>
            <div class="text-right">
                <div class="text-white font-bold">{{ $vendeur->name }}</div>
                @if($vendeur->score_global >= 90)
                    <div class="text-green-400 text-sm">Vendeur d'élite</div>
                @elseif($vendeur->score_global >= 75)
                    <div class="text-blue-400 text-sm">Vendeur confirmé</div>
                @else
                    <div class="text-yellow-400 text-sm">Vendeur en progression</div>
                @endif
            </div>
        </div>
        
        <!-- Photo / Icône -->
        <div class="flex justify-center mb-2">
            <div class="{{ $isTop ? 'bg-blue-700' : 'bg-gray-700' }} rounded-full p-3 border-2 {{ $isTop ? 'border-yellow-500' : 'border-gray-600' }}">
                <svg class="h-20 w-20 text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        
        <!-- Nom -->
        <div class="text-center text-white font-bold text-xl mb-4">
            {{ $vendeur->name }}
        </div>
        
        <!-- Statistiques -->
        <div class="{{ $isTop ? 'bg-gradient-to-b from-blue-700 to-blue-800' : 'bg-gradient-to-b from-gray-700 to-gray-800' }} p-4">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col items-center">
                    <div class="text-xs text-gray-300 mb-1">Chiffre d'affaires</div>
                    <div class="text-white font-semibold">{{ $vendeur->chiffre_affaires_formate }}</div>
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-xs text-gray-300 mb-1">Quantité vendue</div>
                    <div class="text-white font-semibold">{{ number_format($vendeur->quantite_totale, 0, ',', ' ') }}</div>
                </div>
            </div>
            
            <!-- Compétences -->
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="flex flex-col items-center">
                    <div class="text-xs text-gray-300 mb-1">CA</div>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $vendeur->scores['ca'] >= 80 ? 'bg-green-500' : ($vendeur->scores['ca'] >= 60 ? 'bg-yellow-500' : 'bg-orange-500') }} text-white font-semibold text-xs">
                        {{ $vendeur->scores['ca'] }}
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-xs text-gray-300 mb-1">Volume</div>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $vendeur->scores['quantite'] >= 80 ? 'bg-green-500' : ($vendeur->scores['quantite'] >= 60 ? 'bg-yellow-500' : 'bg-orange-500') }} text-white font-semibold text-xs">
                        {{ $vendeur->scores['quantite'] }}
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-xs text-gray-300 mb-1">Diversité</div>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $vendeur->scores['diversite'] >= 80 ? 'bg-green-500' : ($vendeur->scores['diversite'] >= 60 ? 'bg-yellow-500' : 'bg-orange-500') }} text-white font-semibold text-xs">
                        {{ $vendeur->scores['diversite'] }}
                    </div>
                </div>
            </div>

            <!-- Progression globale -->
            <div class="mb-4">
                <div class="flex justify-between text-xs text-gray-300 mb-1">
                    <span>Performance</span>
                    <span>{{ round($vendeur->score_global) }}/100</span>
                </div>
                <div class="w-full h-2 bg-gray-600 rounded">
                    <div class="h-full {{ $vendeur->score_global >= 90 ? 'bg-green-500' : ($vendeur->score_global >= 75 ? 'bg-blue-500' : 'bg-yellow-500') }} rounded" style="width: {{ min(100, $vendeur->score_global) }}%;"></div>
                </div>
            </div>
            
            <!-- Statistiques supplémentaires -->
            <div class="grid grid-cols-2 gap-2 text-xs text-center">
                <div class="bg-gray-600 p-2 rounded">
                    <div class="text-gray-300">Nombre de ventes</div>
                    <div class="text-white font-semibold">{{ $vendeur->nombre_ventes }}</div>
                </div>
                <div class="bg-gray-600 p-2 rounded">
                    <div class="text-gray-300">Produits différents</div>
                    <div class="text-white font-semibold">{{ $vendeur->diversite_produits }}</div>
                </div>
            </div>
        </div>
        
        <!-- Pied de carte -->
        <div class="bg-gray-900 p-2 text-center text-xs text-gray-400">
            <div>Période: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</div>
        </div>
    </div>
</div>
