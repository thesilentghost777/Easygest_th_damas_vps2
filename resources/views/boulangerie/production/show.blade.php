@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    {{ $isFrench ? 'Détails de la Production' : 'Production Details' }}
                </h1>
                <p class="text-gray-600">
                    {{ $isFrench ? 'Production du' : 'Production of' }} {{ $production->date_production->format('d/m/Y') }} 
                    {{ $isFrench ? 'par' : 'by' }} {{ $production->producteur->name }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('boulangerie.production.edit', $production->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                </a>
                <a href="{{ route('boulangerie.production.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du sac -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m2-1v6"></path>
                    </svg>
                    {{ $isFrench ? 'Informations du Sac' : 'Bag Information' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">
                            {{ $isFrench ? 'Type de sac' : 'Bag type' }}
                        </label>
                        <p class="text-lg font-semibold text-gray-800">{{ $production->sac->nom }}</p>
                        @if($production->sac->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $production->sac->description }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">
                            {{ $isFrench ? 'Date de production' : 'Production date' }}
                        </label>
                        <p class="text-lg font-semibold text-gray-800">{{ $production->date_production->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-600">{{ $production->date_production->diffForHumans() }}</p>
                    </div>
                </div>

                @if($production->observations)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-2">
                            {{ $isFrench ? 'Observations' : 'Observations' }}
                        </label>
                        <p class="text-gray-700">{{ $production->observations }}</p>
                    </div>
                @endif
            </div>

            <!-- Produits fabriqués -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ $isFrench ? 'Produits Fabriqués' : 'Manufactured Products' }} ({{ $production->productionProduits->count() }})
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Total' : 'Total' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($production->productionProduits as $produitProduction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $produitProduction->produit->nom }}</div>
                                        <div class="text-sm text-gray-500">{{ $produitProduction->produit->categorie }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($produitProduction->quantite) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($produitProduction->valeur_unitaire, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ number_format($produitProduction->valeur_totale, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                                    {{ $isFrench ? 'Total général:' : 'Grand total:' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-blue-600">
                                    {{ number_format($production->valeur_totale_fcfa, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Performance -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Performance' : 'Performance' }}
                </h3>

                @if($production->sac->configuration)
                    @php
                        $objectif = $production->sac->configuration->valeur_moyenne_fcfa;
                        $valeurActuelle = $production->valeur_totale_fcfa;
                        $pourcentage = ($valeurActuelle / $objectif) * 100;
                        $ecart = $valeurActuelle - $objectif;
                        $couleur = $pourcentage >= 100 ? 'green' : ($pourcentage >= 80 ? 'yellow' : 'red');
                    @endphp

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Objectif:' : 'Target:' }}</span>
                            <span class="font-semibold text-gray-800">{{ number_format($objectif, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Réalisé:' : 'Achieved:' }}</span>
                            <span class="font-semibold text-blue-600">{{ number_format($valeurActuelle, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Écart:' : 'Variance:' }}</span>
                            <span class="font-semibold {{ $ecart >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">{{ $isFrench ? 'Progression' : 'Progress' }}</span>
                                <span class="text-sm font-medium text-{{ $couleur }}-600">{{ number_format($pourcentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $couleur }}-500 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ min($pourcentage, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-lg {{ $production->estSousLaMoyenne() ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                            @if($production->estSousLaMoyenne())
                                <div class="flex items-center text-red-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $isFrench ? 'Sous la moyenne' : 'Below average' }}</span>
                                </div>
                            @else
                                <div class="flex items-center text-green-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $isFrench ? 'Objectif atteint' : 'Target achieved' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">
                        {{ $isFrench ? 'Aucun objectif défini pour ce sac.' : 'No target defined for this bag.' }}
                    </p>
                @endif
            </div>

            <!-- Statistiques rapides -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    {{ $isFrench ? 'Statistiques' : 'Statistics' }}
                </h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Nombre de produits:' : 'Number of products:' }}</span>
                        <span class="font-semibold text-gray-800">{{ $production->productionProduits->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Quantité totale:' : 'Total quantity:' }}</span>
                        <span class="font-semibold text-gray-800">{{ $production->productionProduits->sum('quantite') }} {{ $isFrench ? 'unités' : 'units' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Prix moyen:' : 'Average price:' }}</span>
                        <span class="font-semibold text-gray-800">
                            {{ number_format($production->productionProduits->avg('valeur_unitaire'), 0, ',', ' ') }} {{ $isFrench ? 'FCFA' : 'CFA' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Statut:' : 'Status:' }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $production->valide ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $production->valide ? ($isFrench ? 'Validé' : 'Validated') : ($isFrench ? 'En attente' : 'Pending') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Matières du sac -->
            @if($production->sac->matieres->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        {{ $isFrench ? 'Matières utilisées' : 'Materials used' }}
                    </h3>

                    <div class="space-y-2">
                        @foreach($production->sac->matieres as $matiere)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ $matiere->nom }}</span>
                                <span class="font-medium text-gray-800">{{ $matiere->pivot->quantite_utilisee }} {{ $matiere->unite_minimale }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
