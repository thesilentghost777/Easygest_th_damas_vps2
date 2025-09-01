<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">
            {{ (isset($isFrench) && $isFrench) ? 'Dépenses Matière' : 'Material Expenses' }}
        </h3>
        <button onclick="exportData('matiere')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <span class="mr-2">📊</span>
            {{ (isset($isFrench) && $isFrench) ? 'Exporter' : 'Export' }}
        </button>
    </div>

    @if($depenses->count() > 0)
        <div class="space-y-4">
            @foreach($depenses as $depense)
                <div class="expense-card bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="text-lg font-semibold text-gray-800 mr-3">{{ $depense->nom }}</h4>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($depense->type == 'achat_matiere') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    @if($depense->type == 'achat_matiere')
                                        {{ (isset($isFrench) && $isFrench) ? 'Achat Matière' : 'Material Purchase' }}
                                    @else
                                        {{ (isset($isFrench) && $isFrench) ? 'Livraison Matière' : 'Material Delivery' }}
                                    @endif
                                </span>
                            </div>
                            <div class="text-gray-600 text-sm space-y-1">
                                <p><strong>{{ (isset($isFrench) && $isFrench) ? 'Auteur:' : 'Author:' }}</strong> {{ $depense->auteur_name }}</p>
                                <p><strong>{{ (isset($isFrench) && $isFrench) ? 'Date:' : 'Date:' }}</strong> {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</p>
                                @if($depense->matiere_nom)
                                    <p><strong>{{ (isset($isFrench) && $isFrench) ? 'Matière:' : 'Material:' }}</strong> {{ $depense->matiere_nom }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 md:text-right">
                            <div class="text-2xl font-bold text-red-600">
                                -{{ number_format($depense->prix, 2) }} FCFA
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($depense->created_at)->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    @if($depense->matiere_nom)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                <span>{{ (isset($isFrench) && $isFrench) ? 'Matière associée:' : 'Associated material:' }} <strong>{{ $depense->matiere_nom }}</strong></span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-lg font-medium text-gray-700">
                    {{ (isset($isFrench) && $isFrench) ? 'Total des dépenses matière:' : 'Total material expenses:' }}
                </span>
                <span class="text-xl font-bold text-red-600">
                    -{{ number_format($depenses->sum('prix'), 2) }} FCFA
                </span>
            </div>
        </div>
        
        <!-- Répartition par type -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">
                    {{ (isset($isFrench) && $isFrench) ? 'Achats de Matière' : 'Material Purchases' }}
                </h4>
                <div class="text-2xl font-bold text-blue-600">
                    -{{ number_format($depenses->where('type', 'achat_matiere')->sum('prix'), 2) }} FCFA
                </div>
                <div class="text-sm text-blue-700">
                    {{ $depenses->where('type', 'achat_matiere')->count() }} 
                    {{ (isset($isFrench) && $isFrench) ? 'opération(s)' : 'operation(s)' }}
                </div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <h4 class="font-medium text-purple-900 mb-2">
                    {{ (isset($isFrench) && $isFrench) ? 'Livraisons de Matière' : 'Material Deliveries' }}
                </h4>
                <div class="text-2xl font-bold text-purple-600">
                    -{{ number_format($depenses->where('type', 'livraison_matiere')->sum('prix'), 2) }} FCFA
                </div>
                <div class="text-sm text-purple-700">
                    {{ $depenses->where('type', 'livraison_matiere')->count() }} 
                    {{ (isset($isFrench) && $isFrench) ? 'opération(s)' : 'operation(s)' }}
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🏭</div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">
                {{ (isset($isFrench) && $isFrench) ? 'Aucune dépense matière' : 'No material expenses' }}
            </h3>
            <p class="text-gray-500">
                {{ (isset($isFrench) && $isFrench) ? 'Aucune dépense matière trouvée pour cette période.' : 'No material expenses found for this period.' }}
            </p>
        </div>
    @endif
</div>