<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">
            {{ (isset($isFrench) && $isFrench) ? 'Dépenses Diverses' : 'Miscellaneous Expenses' }}
        </h3>
        <button onclick="exportData('diverses')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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
                                    @if($depense->type == 'reparation') bg-orange-100 text-orange-800
                                    @elseif($depense->type == 'depense_fiscale') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @switch($depense->type)
                                        @case('reparation')
                                            {{ (isset($isFrench) && $isFrench) ? 'Réparation' : 'Repair' }}
                                            @break
                                        @case('depense_fiscale')
                                            {{ (isset($isFrench) && $isFrench) ? 'Dépense Fiscale' : 'Tax Expense' }}
                                            @break
                                        @default
                                            {{ (isset($isFrench) && $isFrench) ? 'Autre' : 'Other' }}
                                    @endswitch
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
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-lg font-medium text-gray-700">
                    {{ (isset($isFrench) && $isFrench) ? 'Total des dépenses diverses:' : 'Total miscellaneous expenses:' }}
                </span>
                <span class="text-xl font-bold text-red-600">
                    -{{ number_format($depenses->sum('prix'), 2) }} FCFA
                </span>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">
                {{ (isset($isFrench) && $isFrench) ? 'Aucune dépense diverse' : 'No miscellaneous expenses' }}
            </h3>
            <p class="text-gray-500">
                {{ (isset($isFrench) && $isFrench) ? 'Aucune dépense diverse trouvée pour cette période.' : 'No miscellaneous expenses found for this period.' }}
            </p>
        </div>
    @endif
</div>