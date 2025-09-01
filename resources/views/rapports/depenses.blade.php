@extends('rapports.layout.rapport')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Report Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-800 px-6 py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        {{ $isFrench ? 'Rapport des Dépenses' : 'Expense Report' }}
                    </h1>
                    <p class="text-red-100">
                        {{ $isFrench 
                            ? 'Ce rapport présente une analyse détaillée des dépenses effectuées pour le mois de ' . $currentMonthName 
                            : 'This report presents a detailed analysis of expenses for the month of ' . $currentMonthName 
                        }}
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Expenses -->
                        <div class="bg-red-50 rounded-xl p-4 border border-red-200 animate-scale-in">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-red-600 font-medium">{{ $isFrench ? 'Total des dépenses' : 'Total expenses' }}</p>
                                    <p class="text-2xl font-bold text-red-800">{{ number_format($totalDepenses, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Number of Operations -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 animate-scale-in" style="animation-delay: 0.1s">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Nombre d\'opérations' : 'Number of operations' }}</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $nombreDepenses }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Evolution -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-scale-in" style="animation-delay: 0.2s">
                            <div class="flex items-center">
                                <div class="p-3 bg-gray-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Évolution' : 'Evolution' }}</p>
                                    <p class="text-2xl font-bold {{ $evolution >= 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $evolution >= 0 ? '+' : '' }}{{ $evolution }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.1s">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $isFrench ? 'Résumé des dépenses' : 'Expense summary' }}
            </h3>

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Au cours du mois de ' . $currentMonthName . ', l\'entreprise a enregistré un total de'
                        : 'During the month of ' . $currentMonthName . ', the company recorded a total of'
                    }}
                    <strong>{{ number_format($totalDepenses, 0, ',', ' ') }} XAF</strong>
                    {{ $isFrench 
                        ? ' en dépenses, réparties sur ' . $nombreDepenses . ' opérations. Ces dépenses représentent une'
                        : ' in expenses, spread over ' . $nombreDepenses . ' operations. These expenses represent a'
                    }}
                    {{ $evolution >= 0 ? ($isFrench ? 'augmentation' : 'increase') : ($isFrench ? 'diminution' : 'decrease') }}
                    {{ $isFrench ? ' de ' : ' of ' }}<strong>{{ abs($evolution) }}%</strong>
                    {{ $isFrench ? ' par rapport au mois précédent.' : ' compared to the previous month.' }}
                </p>

                <p class="mb-4">
                    {{ $isFrench 
                        ? 'La répartition des dépenses par type montre que :' 
                        : 'The breakdown of expenses by type shows that:' 
                    }}
                </p>

                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400 mb-4">
                    <ul class="space-y-2 text-gray-700">
                        @foreach($depensesParType as $type)
                            <li class="flex items-center">
                                <span class="inline-block w-3 h-3 bg-blue-400 rounded-full mr-3 flex-shrink-0"></span>
                                <span>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $type->type)) }}</strong> :
                                    {{ number_format($type->total, 0, ',', ' ') }} XAF
                                    ({{ round(($type->total / $totalDepenses) * 100, 1) }}% {{ $isFrench ? 'du total' : 'of total' }})
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Analysis Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.2s">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ $isFrench ? 'Analyse et recommandations' : 'Analysis and recommendations' }}
            </h3>

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                <p class="mb-4">
                    @if($evolution > 20)
                        {{ $isFrench 
                            ? 'L\'augmentation significative des dépenses ce mois-ci (' . $evolution . '%) mérite une attention particulière. Il est recommandé d\'examiner en détail les postes de dépenses ayant connu les plus fortes hausses afin d\'identifier les causes de cette augmentation et d\'évaluer si des mesures d\'optimisation sont nécessaires.'
                            : 'The significant increase in expenses this month (' . $evolution . '%) deserves special attention. It is recommended to examine in detail the expense categories that experienced the highest increases to identify the causes of this increase and evaluate whether optimization measures are necessary.'
                        }}
                    @elseif($evolution < -20)
                        {{ $isFrench 
                            ? 'La diminution notable des dépenses ce mois-ci (' . abs($evolution) . '%) témoigne d\'une gestion efficace des ressources financières de l\'entreprise. Cette tendance positive contribue à l\'amélioration de la rentabilité globale et devrait être maintenue dans la mesure du possible.'
                            : 'The notable decrease in expenses this month (' . abs($evolution) . '%) demonstrates effective management of the company\'s financial resources. This positive trend contributes to improving overall profitability and should be maintained where possible.'
                        }}
                    @else
                        {{ $isFrench 
                            ? 'Les dépenses sont restées relativement stables par rapport au mois précédent, avec une variation de ' . $evolution . '%. Cette stabilité témoigne d\'une gestion maîtrisée des ressources financières de l\'entreprise.'
                            : 'Expenses remained relatively stable compared to the previous month, with a variation of ' . $evolution . '%. This stability demonstrates controlled management of the company\'s financial resources.'
                        }}
                    @endif
                </p>

                @if($depensesParType->count() > 0)
                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Le principal poste de dépense ce mois-ci concerne ' . ucfirst(str_replace('_', ' ', $depensesParType->sortByDesc('total')->first()->type)) . ', représentant ' . round(($depensesParType->sortByDesc('total')->first()->total / $totalDepenses) * 100, 1) . '% du total des dépenses. Ce constat doit orienter les efforts d\'optimisation des coûts vers ce poste spécifique.'
                        : 'The main expense category this month concerns ' . ucfirst(str_replace('_', ' ', $depensesParType->sortByDesc('total')->first()->type)) . ', representing ' . round(($depensesParType->sortByDesc('total')->first()->total / $totalDepenses) * 100, 1) . '% of total expenses. This finding should guide cost optimization efforts towards this specific category.'
                    }}
                </p>
                @endif
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">
                        {{ $isFrench ? 'Métriques clés' : 'Key metrics' }}
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                {{ $isFrench ? 'Dépense moyenne' : 'Average expense' }}
                            </span>
                            <span class="font-semibold text-gray-900">
                                {{ $nombreDepenses > 0 ? number_format($totalDepenses / $nombreDepenses, 0, ',', ' ') : '0' }} XAF
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                {{ $isFrench ? 'Nombre de catégories' : 'Number of categories' }}
                            </span>
                            <span class="font-semibold text-gray-900">{{ $depensesParType->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                    <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.736-.833-3.464 0L.928 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ $isFrench ? 'Recommandations' : 'Recommendations' }}
                    </h4>
                    <ul class="space-y-2 text-yellow-700 text-sm">
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                            {{ $isFrench 
                                ? 'Surveiller les postes de dépenses les plus importants' 
                                : 'Monitor the most important expense categories' 
                            }}
                        </li>
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                            {{ $isFrench 
                                ? 'Mettre en place des budgets prévisionnels' 
                                : 'Establish forecast budgets' 
                            }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Expense Details Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ $isFrench ? 'Détail des dépenses' : 'Expense details' }}
                </h3>
            </div>

            @if($depenses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Description' : 'Description' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Type' : 'Type' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Auteur' : 'Author' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($depenses as $depense)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $depense->date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-sm text-gray-900">
                                    {{ $depense->nom }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $depense->type)) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($depense->auteurRelation)->name ?? ($isFrench ? 'Utilisateur inconnu' : 'Unknown user') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($depense->prix, 0, ',', ' ') }} XAF
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-gray-700 text-sm">
                        {{ $isFrench 
                            ? 'Le tableau ci-dessus présente la liste complète des dépenses effectuées au cours du mois de ' . $currentMonthName . ', avec la date, la description, le type, l\'auteur et le montant de chaque opération.'
                            : 'The table above presents the complete list of expenses made during the month of ' . $currentMonthName . ', with the date, description, type, author and amount of each operation.'
                        }}
                    </p>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune dépense trouvée' : 'No expenses found' }}
                    </h3>
                    <p class="text-gray-500">
                        {{ $isFrench 
                            ? 'Aucune dépense n\'a été enregistrée pendant le mois de ' . $currentMonthName . '.'
                            : 'No expenses were recorded during the month of ' . $currentMonthName . '.'
                        }}
                    </p>
                </div>
            @endif
        </div>

        
    </div>
</div>

<!-- Mobile-First CSS -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-2xl, .text-3xl {
        font-size: 1.5rem;
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    /* Mobile table adjustments */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem !important;
        min-width: 100px;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br {
        background: white !important;
    }
    
    .shadow-xl {
        box-shadow: none !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .bg-red-600, .bg-red-800 {
        background: #fee2e2 !important;
    }
}
</style>
@endsection
