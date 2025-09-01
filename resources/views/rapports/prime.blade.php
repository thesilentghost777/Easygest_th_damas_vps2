@extends('rapports.layout.rapport')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Report Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        {{ $isFrench ? 'Rapport des Primes' : 'Bonus Report' }}
                    </h1>
                    <p class="text-green-100">
                        {{ $isFrench 
                            ? 'Ce rapport présente une analyse détaillée des primes accordées pour le mois de ' . $currentMonthName 
                            : 'This report presents a detailed analysis of bonuses awarded for the month of ' . $currentMonthName 
                        }}
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Bonuses -->
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200 animate-scale-in">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">{{ $isFrench ? 'Total des primes' : 'Total bonuses' }}</p>
                                    <p class="text-2xl font-bold text-green-800">{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Number of Employees -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 animate-scale-in" style="animation-delay: 0.1s">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Employés concernés' : 'Employees involved' }}</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ $nombrePrimes }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Average Amount -->
                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 animate-scale-in" style="animation-delay: 0.2s">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">{{ $isFrench ? 'Montant moyen' : 'Average amount' }}</p>
                                    <p class="text-2xl font-bold text-purple-800">{{ number_format($montantMoyen, 0, ',', ' ') }} XAF</p>
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
                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $isFrench ? 'Résumé des primes' : 'Bonus summary' }}
            </h3>

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Au cours du mois de ' . $currentMonthName . ', un total de'
                        : 'During the month of ' . $currentMonthName . ', a total of'
                    }}
                    <strong>{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</strong>
                    {{ $isFrench 
                        ? ' a été distribué en primes à ' . $nombrePrimes . ' employé(s). Ces primes représentent une'
                        : ' was distributed in bonuses to ' . $nombrePrimes . ' employee(s). These bonuses represent a'
                    }}
                    {{ $evolution >= 0 ? ($isFrench ? 'augmentation' : 'increase') : ($isFrench ? 'diminution' : 'decrease') }}
                    {{ $isFrench ? ' de ' : ' of ' }}<strong>{{ abs($evolution) }}%</strong>
                    {{ $isFrench ? ' par rapport au mois précédent.' : ' compared to the previous month.' }}
                </p>

                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Le montant moyen des primes accordées s\'élève à ' . number_format($montantMoyen, 0, ',', ' ') . ' XAF par employé concerné. La répartition des primes par type est la suivante :'
                        : 'The average amount of bonuses awarded amounts to ' . number_format($montantMoyen, 0, ',', ' ') . ' XAF per employee involved. The breakdown of bonuses by type is as follows:'
                    }}
                </p>

                <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-400 mb-4">
                    <ul class="space-y-2 text-gray-700">
                        @foreach($primesParLibelle as $prime)
                            <li class="flex items-center">
                                <span class="inline-block w-3 h-3 bg-green-400 rounded-full mr-3 flex-shrink-0"></span>
                                <span>
                                    <strong>{{ ucfirst($prime->libelle) }}</strong> :
                                    {{ $prime->nombre }} {{ $isFrench ? 'prime(s) pour un total de' : 'bonus(es) for a total of' }} {{ number_format($prime->total, 0, ',', ' ') }} XAF
                                    ({{ $totalPrimes > 0 ? round(($prime->total / $totalPrimes) * 100, 1) : 0 }}% {{ $isFrench ? 'du montant total' : 'of total amount' }})
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
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ $isFrench ? 'Analyse et recommandations' : 'Analysis and recommendations' }}
            </h3>

            <div class="prose max-w-none text-gray-700 leading-relaxed">
                <p class="mb-4">
                    @if($evolution > 20)
                        {{ $isFrench 
                            ? 'L\'augmentation significative des primes ce mois-ci (' . $evolution . '%) témoigne d\'une reconnaissance accrue des performances des employés. Cette politique de motivation par les primes contribue au maintien d\'un climat social positif et à l\'encouragement de l\'excellence.'
                            : 'The significant increase in bonuses this month (' . $evolution . '%) demonstrates increased recognition of employee performance. This bonus motivation policy contributes to maintaining a positive social climate and encouraging excellence.'
                        }}
                    @elseif($evolution < -20)
                        {{ $isFrench 
                            ? 'La diminution notable des primes ce mois-ci (' . abs($evolution) . '%) peut être liée à une évolution des critères d\'attribution ou à des résultats moins favorables. Il est important de communiquer clairement sur les raisons de cette baisse pour maintenir la motivation des équipes.'
                            : 'The notable decrease in bonuses this month (' . abs($evolution) . '%) may be related to changes in award criteria or less favorable results. It is important to communicate clearly about the reasons for this decrease to maintain team motivation.'
                        }}
                    @else
                        {{ $isFrench 
                            ? 'Le niveau des primes est resté relativement stable par rapport au mois précédent, avec une variation de ' . $evolution . '%. Cette continuité dans la politique de reconnaissance contribue à la prévisibilité des rémunérations variables pour les employés.'
                            : 'The level of bonuses remained relatively stable compared to the previous month, with a variation of ' . $evolution . '%. This continuity in the recognition policy contributes to the predictability of variable compensation for employees.'
                        }}
                    @endif
                </p>

                @if($primesParLibelle->count() > 0)
                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Les primes de type ' . ucfirst($primesParLibelle->sortByDesc('total')->first()->libelle) . ' représentent la plus grande part du budget des primes ce mois-ci (' . ($totalPrimes > 0 ? round(($primesParLibelle->sortByDesc('total')->first()->total / $totalPrimes) * 100, 1) : 0) . '%). Cette répartition reflète les priorités actuelles de l\'entreprise en matière de reconnaissance des performances.'
                        : ucfirst($primesParLibelle->sortByDesc('total')->first()->libelle) . ' type bonuses represent the largest share of the bonus budget this month (' . ($totalPrimes > 0 ? round(($primesParLibelle->sortByDesc('total')->first()->total / $totalPrimes) * 100, 1) : 0) . '%). This distribution reflects the company\'s current priorities in terms of performance recognition.'
                    }}
                </p>
                @endif

                @if($montantMoyen > 0)
                <p class="mb-4">
                    {{ $isFrench 
                        ? 'Avec un montant moyen de ' . number_format($montantMoyen, 0, ',', ' ') . ' XAF par prime, ces gratifications constituent un complément de rémunération significatif pour les employés concernés. Il est recommandé de maintenir cette politique d\'incitation pour continuer à stimuler les performances individuelles et collectives.'
                        : 'With an average amount of ' . number_format($montantMoyen, 0, ',', ' ') . ' XAF per bonus, these gratifications constitute a significant compensation supplement for the employees involved. It is recommended to maintain this incentive policy to continue stimulating individual and collective performance.'
                    }}
                </p>
                @endif
            </div>

            <!-- Key Insights -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                    <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        {{ $isFrench ? 'Points positifs' : 'Positive points' }}
                    </h4>
                    <ul class="space-y-2 text-blue-700 text-sm">
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                            {{ $isFrench 
                                ? 'Politique de reconnaissance active' 
                                : 'Active recognition policy' 
                            }}
                        </li>
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-blue-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                            {{ $isFrench 
                                ? 'Motivation des équipes maintenue' 
                                : 'Team motivation maintained' 
                            }}
                        </li>
                    </ul>
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
                                ? 'Maintenir la transparence des critères' 
                                : 'Maintain transparency of criteria' 
                            }}
                        </li>
                        <li class="flex items-start">
                            <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                            {{ $isFrench 
                                ? 'Évaluer l\'équité de la répartition' 
                                : 'Evaluate distribution fairness' 
                            }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bonus Details Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ $isFrench ? 'Détail des primes' : 'Bonus details' }}
                </h3>
            </div>

            @if($primes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Employé' : 'Employee' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Libellé' : 'Label' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($primes as $prime)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $prime->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($prime->employe)->name ?? ($isFrench ? 'Employé inconnu' : 'Unknown employee') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-sm text-gray-900">
                                    {{ ucfirst($prime->libelle) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($prime->montant, 0, ',', ' ') }} XAF
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-gray-700 text-sm">
                        {{ $isFrench 
                            ? 'Le tableau ci-dessus présente la liste complète des primes accordées au cours du mois de ' . $currentMonthName . ', avec la date d\'attribution, le bénéficiaire, le type de prime et le montant.'
                            : 'The table above presents the complete list of bonuses awarded during the month of ' . $currentMonthName . ', with the award date, beneficiary, bonus type and amount.'
                        }}
                    </p>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune prime accordée' : 'No bonuses awarded' }}
                    </h3>
                    <p class="text-gray-500">
                        {{ $isFrench 
                            ? 'Aucune prime n\'a été accordée pendant le mois de ' . $currentMonthName . '.'
                            : 'No bonuses were awarded during the month of ' . $currentMonthName . '.'
                        }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8 flex justify-center animate-fade-in-up" style="animation-delay: 0.4s">
            @include('buttons')
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

/* Smooth transitions */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
@endsection
