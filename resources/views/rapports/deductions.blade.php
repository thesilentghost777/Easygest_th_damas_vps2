@extends('rapports.layout.rapport')

@section('content')
    <x-slot name="reportTitle">
        {{ $isFrench ? "Rapport des Déductions Salariales" : "Salary Deductions Report" }}
    </x-slot>

    <x-slot name="description">
        {{ $isFrench 
            ? "Ce rapport présente une analyse détaillée des déductions salariales pour le mois de {$currentMonthName}." 
            : "This report presents a detailed analysis of salary deductions for the month of {$currentMonthName}." 
        }}
    </x-slot>

    <div class="space-y-8">
        <!-- Résumé -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Résumé des déductions" : "Deductions summary" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($isFrench)
                    Au cours du mois de {{ $currentMonthName }}, un total de <strong>{{ number_format($totalDeductions, 0, ',', ' ') }} XAF</strong>
                    a été comptabilisé en déductions salariales. Ces déductions représentent une
                    {{ $evolution >= 0 ? 'augmentation' : 'diminution' }} de <strong>{{ abs($evolution) }}%</strong> par rapport au mois précédent.
                @else
                    During the month of {{ $currentMonthName }}, a total of <strong>{{ number_format($totalDeductions, 0, ',', ' ') }} XAF</strong>
                    was recorded in salary deductions. These deductions represent an
                    {{ $evolution >= 0 ? 'increase' : 'decrease' }} of <strong>{{ abs($evolution) }}%</strong> compared to the previous month.
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                {{ $isFrench ? "La répartition des déductions par catégorie est la suivante :" : "The distribution of deductions by category is as follows:" }}
            </p>

            <ul class="list-disc ml-6 text-gray-700">
                <li>
                    <strong>{{ $isFrench ? "Manquants" : "Missing items" }}</strong> : {{ number_format($totalManquants, 0, ',', ' ') }} XAF
                </li>
                <li>
                    <strong>{{ $isFrench ? "Remboursements" : "Reimbursements" }}</strong> : {{ number_format($totalRemboursements, 0, ',', ' ') }} XAF
                </li>
                <li>
                    <strong>{{ $isFrench ? "Caisse sociale" : "Social fund" }}</strong> : {{ number_format($totalCaisseSociale, 0, ',', ' ') }} XAF
                </li>
            </ul>
        </section>

        <!-- Analyse -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Analyse et recommandations" : "Analysis and recommendations" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($totalManquants > 0 && $totalDeductions > 0 && ($totalManquants / $totalDeductions) > 0.3)
                    @if($isFrench)
                        Les manquants représentent une part significative ({{ round(($totalManquants / $totalDeductions) * 100, 1) }}%)
                        des déductions totales ce mois-ci. Cette situation mérite une attention particulière et pourrait nécessiter
                        la mise en place de mesures de contrôle et de prévention plus efficaces pour réduire ces pertes à l'avenir.
                    @else
                        Missing items represent a significant portion ({{ round(($totalManquants / $totalDeductions) * 100, 1) }}%)
                        of total deductions this month. This situation deserves special attention and could require
                        implementing more effective control and prevention measures to reduce these losses in the future.
                    @endif
                @endif

                @if($totalRemboursements > 0 && $totalDeductions > 0)
                    @if($isFrench)
                        Les remboursements constituent {{ round(($totalRemboursements / $totalDeductions) * 100, 1) }}% des déductions,
                        ce qui témoigne d'un processus actif de régularisation des avances et prêts accordés précédemment aux employés.
                    @else
                        Reimbursements constitute {{ round(($totalRemboursements / $totalDeductions) * 100, 1) }}% of deductions,
                        which demonstrates an active process of regularizing advances and loans previously granted to employees.
                    @endif
                @endif

                @if($totalCaisseSociale > 0)
                    @if($isFrench)
                        Les contributions à la caisse sociale s'élèvent à {{ number_format($totalCaisseSociale, 0, ',', ' ') }} XAF
                        ce mois-ci. Ces fonds sont essentiels pour assurer la protection sociale des employés et renforcer
                        la solidarité au sein de l'entreprise.
                    @else
                        Contributions to the social fund amount to {{ number_format($totalCaisseSociale, 0, ',', ' ') }} XAF
                        this month. These funds are essential to ensure employee social protection and strengthen
                        solidarity within the company.
                    @endif
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                @if($evolution > 20)
                    @if($isFrench)
                        L'augmentation significative des déductions ce mois-ci ({{ $evolution }}%) peut indiquer
                        soit une hausse des incidents nécessitant des remboursements, soit une intensification des efforts
                        de recouvrement des créances. Une analyse plus détaillée par catégorie de déduction est recommandée
                        pour identifier les causes spécifiques de cette tendance.
                    @else
                        The significant increase in deductions this month ({{ $evolution }}%) may indicate
                        either an increase in incidents requiring reimbursements, or an intensification of debt
                        recovery efforts. A more detailed analysis by deduction category is recommended
                        to identify the specific causes of this trend.
                    @endif
                @elseif($evolution < -20)
                    @if($isFrench)
                        La diminution notable des déductions ce mois-ci ({{ abs($evolution) }}%) peut être interprétée
                        comme un signe positif, reflétant potentiellement une réduction des incidents et manquements,
                        ou l'achèvement de cycles de remboursement.
                    @else
                        The notable decrease in deductions this month ({{ abs($evolution) }}%) can be interpreted
                        as a positive sign, potentially reflecting a reduction in incidents and shortfalls,
                        or the completion of reimbursement cycles.
                    @endif
                @endif
            </p>
        </section>

        <!-- Détails des déductions -->
        <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Détail des déductions par employé" : "Details of deductions by employee" }}
            </h3>

            @if($deductions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Date" : "Date" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Employé" : "Employee" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Manquants" : "Missing items" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Remboursements" : "Reimbursements" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Prêts" : "Loans" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Caisse sociale" : "Social fund" }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($deductions as $deduction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $deduction->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($deduction->employe)->name ?? ($isFrench ? 'Employé inconnu' : 'Unknown employee') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($deduction->manquants, 0, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($deduction->remboursement, 0, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($deduction->pret, 0, ',', ' ') }} XAF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($deduction->caisse_sociale, 0, ',', ' ') }} XAF
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-gray-700 text-sm">
                    @if($isFrench)
                        Le tableau ci-dessus présente le détail des déductions salariales par employé au cours du mois de {{ $currentMonthName }},
                        avec la répartition par catégorie et le montant total pour chaque employé.
                    @else
                        The table above presents the details of salary deductions by employee during the month of {{ $currentMonthName }},
                        with the breakdown by category and the total amount for each employee.
                    @endif
                </p>
            @else
                <p class="text-gray-700">
                    @if($isFrench)
                        Aucune déduction salariale n'a été enregistrée pendant le mois de {{ $currentMonthName }}.
                    @else
                        No salary deductions were recorded during the month of {{ $currentMonthName }}.
                    @endif
                </p>
            @endif
        </section>
    </div>
@endsection
