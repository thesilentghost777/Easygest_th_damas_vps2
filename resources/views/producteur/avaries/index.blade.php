@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- En-tête -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    {{ $isFrench ? 'Rapport des Avaries' : 'Damage Report' }}
                </h1>
                <p class="text-gray-600">
                    {{ $isFrench ? 'Vue d\'ensemble des avaries Totales et de vente' : 'Overview of production and sales damages' }}
                </p>
            </div>
           @include('buttons')
        </div>

        <!-- Résumé des totaux -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-red-800 mb-2">
                    {{ $isFrench ? 'Avaries Totales' : 'Total Damages' }}
                </h3>
                <p class="text-2xl font-bold text-red-600">
                    {{ number_format($totalAvariesProduction, 0, ',', ' ') }} FCFA
                </p>
                <p class="text-sm text-red-700 mt-1">
                    {{ count($avariesProduction) }} {{ $isFrench ? 'incidents' : 'incidents' }}
                </p>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-orange-800 mb-2">
                    {{ $isFrench ? 'Avaries de Vente' : 'Sales Damages' }}
                </h3>
                <p class="text-2xl font-bold text-orange-600">
                    {{ number_format($totalAvariesVente, 0, ',', ' ') }} FCFA
                </p>
                <p class="text-sm text-orange-700 mt-1">
                    {{ count($avariesVente) }} {{ $isFrench ? 'incidents' : 'incidents' }}
                </p>
            </div>
        </div>

        <!-- Onglets -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('production')" id="tab-production" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600">
                    {{ $isFrench ? 'Avaries Totales' : 'Total Damages' }}
                    <span class="ml-2 bg-red-100 text-red-600 py-1 px-2 rounded-full text-xs">
                        {{ count($avariesProduction) }}
                    </span>
                </button>
                <button onclick="showTab('vente')" id="tab-vente" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    {{ $isFrench ? 'Avaries de Vente' : 'Sales Damages' }}
                    <span class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs">
                        {{ count($avariesVente) }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Contenu des onglets -->
        <!-- Onglet Avaries Totales -->
        <div id="content-production" class="tab-content">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Détails des Avaries Totales' : 'Total Damages Details' }}
                </h3>
            </div>

            @if(count($avariesProduction) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-red-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider border-b">
                        {{ $isFrench ? 'ID Stock' : 'Stock ID' }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider border-b">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider border-b">
                        {{ $isFrench ? 'Quantité Avariée' : 'Damaged Quantity' }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider border-b">
                        {{ $isFrench ? 'Valeur' : 'Value' }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider border-b">
                        {{ $isFrench ? 'Date' : 'Date' }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($avariesProduction as $avarie)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900 font-mono">
                            {{ $avarie->id }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $avarie->nom_produit }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ number_format($avarie->quantite_avarie, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-red-600">
                            {{ number_format($avarie->valeur_avarie, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($avarie->date_avarie)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-8">
        <div class="text-gray-400 text-lg mb-2">📊</div>
        <p class="text-gray-500">
            {{ $isFrench ? 'Aucune avarie de production enregistrée' : 'No Total damages recorded' }}
        </p>
    </div>
@endif
        </div>

        <!-- Onglet Avaries de Vente -->
        <div id="content-vente" class="tab-content hidden">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $isFrench ? 'Détails des Avaries de Vente' : 'Sales Damages Details' }}
                </h3>
            </div>

            @if(count($avariesVente) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-orange-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    ID
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Serveur' : 'Server' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Monnaie' : 'Currency' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-orange-700 uppercase tracking-wider border-b">
                                    {{ $isFrench ? 'Date' : 'Date' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($avariesVente as $avarie)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900 font-mono">
                                        #{{ $avarie->id }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $avarie->nom_produit }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $avarie->nom_serveur }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $avarie->quantite }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-orange-600">
                                        {{ number_format($avarie->valeur_avarie, 0, ',', ' ') }} {{ $avarie->monnaie ?? 'FCFA' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $avarie->monnaie ?? 'FCFA' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($avarie->date_avarie)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-lg mb-2">🛒</div>
                    <p class="text-gray-500">
                        {{ $isFrench ? 'Aucune avarie de vente enregistrée' : 'No sales damages recorded' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Cacher tous les contenus des onglets
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });

    // Réinitialiser tous les boutons d'onglets
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-red-500', 'border-orange-500', 'text-red-600', 'text-orange-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Afficher le contenu de l'onglet sélectionné
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Styliser le bouton de l'onglet actif
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    
    if (tabName === 'production') {
        activeButton.classList.add('border-red-500', 'text-red-600');
    } else if (tabName === 'vente') {
        activeButton.classList.add('border-orange-500', 'text-orange-600');
    }
}

// Initialiser avec l'onglet de production actif
document.addEventListener('DOMContentLoaded', function() {
    showTab('production');
});
</script>
@endsection