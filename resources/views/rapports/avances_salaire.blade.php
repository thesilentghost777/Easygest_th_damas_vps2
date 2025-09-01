@extends('rapports.layout.rapport')

@section('content')
    <x-slot name="reportTitle">
        {{ $isFrench ? "Rapport des Avances sur Salaires" : "Salary Advances Report" }}
    </x-slot>

    <x-slot name="description">
        {{ $isFrench 
            ? "Ce rapport présente une analyse détaillée des avances sur salaires pour le mois de {$currentMonthName}." 
            : "This report presents a detailed analysis of salary advances for the month of {$currentMonthName}." 
        }}
    </x-slot>

    <div class="space-y-8">
        
        <!-- Résumé -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Résumé des avances sur salaires" : "Summary of salary advances" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($isFrench)
                    Au cours du mois de {{ $currentMonthName }}, un total de <strong>{{ number_format($totalAvances, 0, ',', ' ') }} XAF</strong>
                    a été distribué en avances sur salaires à {{ $nombreAvances }} employé(s). Ces avances représentent une
                    {{ $evolution >= 0 ? 'augmentation' : 'diminution' }} de <strong>{{ abs($evolution) }}%</strong> par rapport au mois précédent.
                @else
                    During the month of {{ $currentMonthName }}, a total of <strong>{{ number_format($totalAvances, 0, ',', ' ') }} XAF</strong>
                    was distributed as salary advances to {{ $nombreAvances }} employee(s). These advances represent an
                    {{ $evolution >= 0 ? 'increase' : 'decrease' }} of <strong>{{ abs($evolution) }}%</strong> compared to the previous month.
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                @if($isFrench)
                    Parmi les {{ $nombreAvances }} demandes d'avances, {{ $avancesValidees }} ont été validées et {{ $avancesEnAttente }}
                    sont en attente de validation. Le montant moyen des avances accordées s'élève à
                    <strong>{{ number_format($montantMoyen, 0, ',', ' ') }} XAF</strong>.
                @else
                    Among the {{ $nombreAvances }} advance requests, {{ $avancesValidees }} have been validated and {{ $avancesEnAttente }}
                    are pending validation. The average amount of advances granted is
                    <strong>{{ number_format($montantMoyen, 0, ',', ' ') }} XAF</strong>.
                @endif
            </p>
        </section>

        <!-- Analyse -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Analyse et recommandations" : "Analysis and recommendations" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($evolution > 20)
                    @if($isFrench)
                        La forte augmentation des demandes d'avances sur salaires pour ce mois de {{ $currentMonthName }} pourrait
                        indiquer des besoins financiers accrus au sein de l'équipe. Il est recommandé d'examiner les causes possibles
                        de cette tendance et d'envisager des mesures pour soutenir les employés face à leurs besoins financiers.
                    @else
                        The strong increase in salary advance requests for this month of {{ $currentMonthName }} could
                        indicate increased financial needs within the team. It is recommended to examine the possible causes
                        of this trend and consider measures to support employees with their financial needs.
                    @endif
                @elseif($evolution < -20)
                    @if($isFrench)
                        La baisse significative des demandes d'avances sur salaires pour ce mois de {{ $currentMonthName }} témoigne
                        d'une amélioration potentielle de la situation financière des employés. Cette tendance positive pourrait être
                        le résultat des mesures prises précédemment ou d'une évolution favorable de la situation économique.
                    @else
                        The significant decrease in salary advance requests for this month of {{ $currentMonthName }} shows
                        a potential improvement in employees' financial situation. This positive trend could be
                        the result of previously taken measures or a favorable evolution of the economic situation.
                    @endif
                @else
                    @if($isFrench)
                        Les demandes d'avances sur salaires pour ce mois de {{ $currentMonthName }} restent relativement stables
                        par rapport au mois précédent. Cette stabilité indique une gestion équilibrée des besoins financiers des employés.
                    @else
                        Salary advance requests for this month of {{ $currentMonthName }} remain relatively stable
                        compared to the previous month. This stability indicates balanced management of employees' financial needs.
                    @endif
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                @if($nombreAvances > 0 && $avancesEnAttente > 0)
                    @if($isFrench)
                        Il est important de traiter rapidement les {{ $avancesEnAttente }} demandes d'avances en attente afin de
                        répondre aux besoins urgents des employés concernés.
                    @else
                        It is important to quickly process the {{ $avancesEnAttente }} pending advance requests in order to
                        meet the urgent needs of the concerned employees.
                    @endif
                @endif
            </p>
        </section>

        <!-- Détails des avances -->
        <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Détail des avances sur salaires" : "Details of salary advances" }}
            </h3>

            @if($avances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Employé" : "Employee" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Montant" : "Amount" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Date" : "Date" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Statut" : "Status" }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($avances as $avance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($avance->employe)->name ?? ($isFrench ? 'Employé inconnu' : 'Unknown employee') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($avance->sommeAs, 0, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $avance->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($avance->retrait_valide)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? "Validée" : "Validated" }}
                                        </span>
                                    @elseif($avance->retrait_demande)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? "En attente" : "Pending" }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $isFrench ? "Non traitée" : "Not processed" }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-gray-700 text-sm">
                    @if($isFrench)
                        Le tableau ci-dessus présente la liste complète des avances sur salaires demandées au cours du mois de {{ $currentMonthName }},
                        avec le nom de l'employé, le montant accordé, la date de la demande et le statut actuel de validation.
                    @else
                        The table above presents the complete list of salary advances requested during the month of {{ $currentMonthName }},
                        with the employee's name, the granted amount, the request date and the current validation status.
                    @endif
                </p>
            @else
                <p class="text-gray-700">
                    @if($isFrench)
                        Aucune avance sur salaire n'a été demandée pendant le mois de {{ $currentMonthName }}.
                    @else
                        No salary advance was requested during the month of {{ $currentMonthName }}.
                    @endif
                </p>
            @endif
        </section>
    </div>
@endsection
