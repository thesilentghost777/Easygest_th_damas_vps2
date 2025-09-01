@extends('rapports.layout.rapport')

@section('content')
    <x-slot name="reportTitle">
        {{ $isFrench ? "Rapport des Commandes" : "Orders Report" }}
    </x-slot>

    <x-slot name="description">
        {{ $isFrench 
            ? "Ce rapport présente une analyse détaillée des commandes pour le mois de {$currentMonthName}." 
            : "This report presents a detailed analysis of orders for the month of {$currentMonthName}." 
        }}
    </x-slot>

    <div class="space-y-8">
        <!-- Résumé -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Résumé des commandes" : "Orders summary" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($isFrench)
                    Au cours du mois de {{ $currentMonthName }}, l'entreprise a enregistré un total de
                    <strong>{{ $totalCommandes }}</strong> commandes. Sur ce total, {{ $commandesValidees }} commandes ont été validées
                    ({{ $totalCommandes > 0 ? round(($commandesValidees / $totalCommandes) * 100, 1) : 0 }}%)
                    et {{ $commandesEnAttente }} sont toujours en attente de validation.
                @else
                    During the month of {{ $currentMonthName }}, the company recorded a total of
                    <strong>{{ $totalCommandes }}</strong> orders. Out of this total, {{ $commandesValidees }} orders have been validated
                    ({{ $totalCommandes > 0 ? round(($commandesValidees / $totalCommandes) * 100, 1) : 0 }}%)
                    and {{ $commandesEnAttente }} are still awaiting validation.
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                {{ $isFrench ? "La répartition des commandes par catégorie montre que :" : "The distribution of orders by category shows that:" }}
            </p>

            <ul class="list-disc ml-6 text-gray-700">
                @foreach($commandesParCategorie as $categorie)
                    <li>
                        <strong>{{ ucfirst($categorie->categorie) }}</strong> :
                        {{ $categorie->nombre }} {{ $isFrench ? "commande(s)" : "order(s)" }}
                        ({{ $totalCommandes > 0 ? round(($categorie->nombre / $totalCommandes) * 100, 1) : 0 }}% {{ $isFrench ? "du total" : "of total" }})
                    </li>
                @endforeach
            </ul>
        </section>

        <!-- Analyse -->
        <section class="prose max-w-none">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Analyse et recommandations" : "Analysis and recommendations" }}
            </h3>

            <p class="text-gray-700 leading-relaxed">
                @if($commandesValidees > 0 && $totalCommandes > 0)
                    @if($isFrench)
                        Le taux de validation des commandes ce mois-ci s'élève à
                        {{ round(($commandesValidees / $totalCommandes) * 100, 1) }}%, ce qui témoigne d'une
                        @if(($commandesValidees / $totalCommandes) > 0.8)
                            excellente efficacité dans le traitement et la validation des commandes. Cette performance
                            contribue à la satisfaction des clients et à l'efficacité opérationnelle de l'entreprise.
                        @elseif(($commandesValidees / $totalCommandes) > 0.5)
                            bonne efficacité dans le traitement des commandes. Des améliorations sont encore possibles
                            pour augmenter ce taux et optimiser davantage le processus de validation.
                        @else
                            efficacité modérée dans le traitement des commandes. Il est recommandé d'examiner les
                            facteurs qui ralentissent le processus de validation et de mettre en place des mesures
                            pour améliorer ce taux dans les mois à venir.
                        @endif
                    @else
                        The order validation rate this month is
                        {{ round(($commandesValidees / $totalCommandes) * 100, 1) }}%, which demonstrates
                        @if(($commandesValidees / $totalCommandes) > 0.8)
                            excellent efficiency in order processing and validation. This performance
                            contributes to customer satisfaction and the company's operational efficiency.
                        @elseif(($commandesValidees / $totalCommandes) > 0.5)
                            good efficiency in order processing. Improvements are still possible
                            to increase this rate and further optimize the validation process.
                        @else
                            moderate efficiency in order processing. It is recommended to examine the
                            factors that slow down the validation process and implement measures
                            to improve this rate in the coming months.
                        @endif
                    @endif
                @endif
            </p>

            <p class="text-gray-700 leading-relaxed">
                @if($commandesParCategorie->count() > 0)
                    @php
                        $topCategory = $commandesParCategorie->sortByDesc('nombre')->first();
                        $percentage = $totalCommandes > 0 ? round(($topCategory->nombre / $totalCommandes) * 100, 1) : 0;
                    @endphp
                    @if($isFrench)
                        La catégorie <strong>{{ ucfirst($topCategory->categorie) }}</strong>
                        représente la plus grande part des commandes ce mois-ci ({{ $percentage }}%).
                        Cette information est précieuse pour orienter les stratégies d'approvisionnement et d'optimisation des stocks.
                    @else
                        The <strong>{{ ucfirst($topCategory->categorie) }}</strong> category
                        represents the largest share of orders this month ({{ $percentage }}%).
                        This information is valuable for guiding procurement strategies and stock optimization.
                    @endif
                @endif

                @if($commandesEnAttente > 0)
                    @if($isFrench)
                        Les {{ $commandesEnAttente }} commandes en attente de validation représentent un potentiel commercial
                        important qui nécessite une attention particulière. Une accélération du processus de validation
                        permettrait de concrétiser ces opportunités plus rapidement et d'améliorer la satisfaction des clients.
                    @else
                        The {{ $commandesEnAttente }} orders awaiting validation represent significant commercial potential
                        that requires special attention. Accelerating the validation process
                        would allow these opportunities to be realized more quickly and improve customer satisfaction.
                    @endif
                @endif
            </p>
        </section>

        <!-- Détails des commandes -->
        <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? "Détail des commandes" : "Order details" }}
            </h3>

            @if($commandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Date" : "Date" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Libellé" : "Label" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Produit" : "Product" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Catégorie" : "Category" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Quantité" : "Quantity" }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? "Statut" : "Status" }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($commandes as $commande)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $commande->libelle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ optional($commande->produitRelation)->nom ?? ($isFrench ? 'Produit non spécifié' : 'Product not specified') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($commande->categorie) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $commande->quantite }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($commande->valider)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? "Validée" : "Validated" }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? "En attente" : "Pending" }}
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
                        Le tableau ci-dessus présente la liste complète des commandes au cours du mois de {{ $currentMonthName }},
                        avec la date, le libellé, le produit, la catégorie, la quantité et le statut de validation de chaque commande.
                    @else
                        The table above presents the complete list of orders during the month of {{ $currentMonthName }},
                        with the date, label, product, category, quantity and validation status of each order.
                    @endif
                </p>
            @else
                <p class="text-gray-700">
                    @if($isFrench)
                        Aucune commande n'a été enregistrée pendant le mois de {{ $currentMonthName }}.
                    @else
                        No orders were recorded during the month of {{ $currentMonthName }}.
                    @endif
                </p>
            @endif
        </section>
    </div>
@endsection
