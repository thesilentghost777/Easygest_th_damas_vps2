<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">
            {{ (isset($isFrench) && $isFrench) ? 'Historique Total' : 'Complete History' }}
        </h3>
        <button onclick="exportData('historique')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <span class="mr-2">📊</span>
            {{ (isset($isFrench) && $isFrench) ? 'Exporter' : 'Export' }}
        </button>
    </div>

    @if($historique->count() > 0)
        <div class="bg-white rounded-lg shadow-sm">
            <div class="max-h-96 overflow-y-auto">
                @foreach($historique as $operation)
                    <div class="history-item border-b border-gray-100 p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="mr-3">
                                        @switch($operation->type_operation)
                                            @case('versement')
                                                <span class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-medium">+</span>
                                                @break
                                            @case('depense')
                                                <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-medium">-</span>
                                                @break
                                            @default
                                                <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium">~</span>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">
                                            @switch($operation->type_operation)
                                                @case('versement')
                                                    {{ (isset($isFrench) && $isFrench) ? 'Versement' : 'Deposit' }}
                                                    @break
                                                @case('depense')
                                                    {{ (isset($isFrench) && $isFrench) ? 'Dépense' : 'Expense' }}
                                                    @break
                                                @default
                                                    {{ (isset($isFrench) && $isFrench) ? 'Ajustement' : 'Adjustment' }}
                                            @endswitch
                                        </h4>
                                        <div class="text-gray-600 text-sm">
                                            {{ (isset($isFrench) && $isFrench) ? 'Par' : 'By' }} {{ $operation->user_name }} • 
                                            {{ \Carbon\Carbon::parse($operation->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($operation->description)
                                    <p class="text-gray-600 text-sm mb-2">{{ $operation->description }}</p>
                                @endif
                                
                                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                    <span>
                                        <strong>{{ (isset($isFrench) && $isFrench) ? 'Solde avant:' : 'Balance before:' }}</strong> 
                                        {{ number_format($operation->solde_avant, 2) }} FCFA
                                    </span>
                                    <span>
                                        <strong>{{ (isset($isFrench) && $isFrench) ? 'Solde après:' : 'Balance after:' }}</strong> 
                                        {{ number_format($operation->solde_apres, 2) }} FCFA
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0 md:text-right">
                                <div class="text-2xl font-bold 
                                    @if($operation->type_operation == 'versement') text-green-600
                                    @elseif($operation->type_operation == 'depense') text-red-600
                                    @else text-blue-600
                                    @endif">
                                    @if($operation->type_operation == 'versement')
                                        +{{ number_format($operation->montant, 2) }} FCFA
                                    @elseif($operation->type_operation == 'depense')
                                        -{{ number_format($operation->montant, 2) }} FCFA
                                    @else
                                        {{ number_format($operation->montant, 2) }} FCFA
                                    @endif
                                </div>
                                
                                @if($operation->operation_id)
                                    <div class="text-xs text-gray-400 mt-1">
                                        ID: {{ $operation->operation_id }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Résumé de l'historique -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
           
            
            <div class="bg-red-50 rounded-lg p-4">
                <h4 class="font-medium text-red-900 mb-2">
                    {{ (isset($isFrench) && $isFrench) ? 'Dépenses' : 'Expenses' }}
                </h4>
                <div class="text-2xl font-bold text-red-600">
                    -{{ number_format($historique->where('type_operation', 'depense')->sum('montant'), 2) }} FCFA
                </div>
                <div class="text-sm text-red-700">
                    {{ $historique->where('type_operation', 'depense')->count() }} 
                    {{ (isset($isFrench) && $isFrench) ? 'opération(s)' : 'operation(s)' }}
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">
                    {{ (isset($isFrench) && $isFrench) ? 'Ajustements' : 'Adjustments' }}
                </h4>
                <div class="text-2xl font-bold text-blue-600">
                    {{ number_format($historique->where('type_operation', 'ajustement')->sum('montant'), 2) }} FCFA
                </div>
                <div class="text-sm text-blue-700">
                    {{ $historique->where('type_operation', 'ajustement')->count() }} 
                    {{ (isset($isFrench) && $isFrench) ? 'opération(s)' : 'operation(s)' }}
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">
                {{ (isset($isFrench) && $isFrench) ? 'Aucun historique' : 'No history' }}
            </h3>
            <p class="text-gray-500">
                {{ (isset($isFrench) && $isFrench) ? 'Aucune opération trouvée pour cette période.' : 'No operations found for this period.' }}
            </p>
        </div>
    @endif
</div>

<script>
function exportData(type) {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("gestion_solde.export") }}';
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = type;
    
    const dateDebutInput = document.createElement('input');
    dateDebutInput.type = 'hidden';
    dateDebutInput.name = 'date_debut';
    dateDebutInput.value = '{{ $dateDebut }}';
    
    const dateFinInput = document.createElement('input');
    dateFinInput.type = 'hidden';
    dateFinInput.name = 'date_fin';
    dateFinInput.value = '{{ $dateFin }}';
    
    form.appendChild(typeInput);
    form.appendChild(dateDebutInput);
    form.appendChild(dateFinInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>