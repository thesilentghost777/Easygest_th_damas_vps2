@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête mobile simplifié -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        {{ $isFrench ? 'Productions' : 'Productions' }}
                    </h1>
                    <p class="text-sm text-gray-600">
                        {{ $productions->count() }} {{ $isFrench ? 'éléments' : 'items' }}
                    </p>
                </div>
                <a href="{{ route('boulangerie.production.create') }}" 
   class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md active:bg-blue-700 transition-colors min-h-[44px] flex items-center"
   aria-label="{{ $isFrench ? 'Ajouter une nouvelle production' : 'Add new production' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
    <span class="ml-2">{{ $isFrench ? 'Nouvelle production' : 'New production' }}</span>
</a>

            </div>
        </div>
    </div>

    <!-- Message de succès mobile -->
    @if(session('success'))
        <div class="mx-4 mt-4">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-700 text-sm">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="px-4 py-4">
        @if($productions->count() > 0)
            <!-- Vue mobile en cartes -->
            <div class="space-y-4">
                @foreach($productions as $production)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- En-tête de carte -->
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg">
                                        {{ $production->sac->nom }}
                                    </h3>
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $production->producteur->name }}
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $isFrench ? $production->date_production->format('d/m/Y') : $production->date_production->format('m/d/Y') }}
                                    </p>
                                </div>
                                
                                <!-- Statut visuel -->
                                <div class="ml-4 flex flex-col items-center">
                                    @if($production->estSousLaMoyenne())
                                        <div class="w-3 h-3 bg-red-500 rounded-full mb-1" 
                                             title="{{ $isFrench ? 'Sous moyenne' : 'Below average' }}"></div>
                                    @else
                                        <div class="w-3 h-3 bg-green-500 rounded-full mb-1" 
                                             title="{{ $isFrench ? 'Objectif atteint' : 'Target achieved' }}"></div>
                                    @endif
                                    @if($production->valide)
                                        <span class="text-xs text-green-600 font-medium">✓</span>
                                    @else
                                        <span class="text-xs text-orange-600 font-medium">⏳</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Contenu principal -->
                        <div class="p-4">
                            <!-- Métriques principales -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $production->productionProduits->sum('quantite') }}
                                    </div>
                                    <div class="text-xs text-gray-600 uppercase tracking-wide">
                                        {{ $isFrench ? 'Unités' : 'Units' }}
                                    </div>
                                </div>
                                
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ number_format($production->valeur_totale_fcfa, 0, $isFrench ? ',' : '.', $isFrench ? ' ' : ',') }}
                                    </div>
                                    <div class="text-xs text-gray-600 uppercase tracking-wide">
                                        FCFA
                                    </div>
                                </div>
                            </div>

                            <!-- Performance -->
                            @if($production->sac->configuration)
                                @php
                                    $pourcentage = ($production->valeur_totale_fcfa / $production->sac->configuration->valeur_moyenne_fcfa) * 100;
                                    $couleur = $pourcentage >= 100 ? 'green' : ($pourcentage >= 80 ? 'yellow' : 'red');
                                @endphp
                                
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ $isFrench ? 'Performance' : 'Performance' }}
                                        </span>
                                        <span class="text-sm font-semibold text-{{ $couleur }}-600">
                                            {{ number_format($pourcentage, 1, $isFrench ? ',' : '.', '') }}%
                                        </span>
                                    </div>
                                    
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-{{ $couleur }}-500 h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ min($pourcentage, 100) }}%"
                                             role="progressbar"
                                             aria-valuenow="{{ $pourcentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             aria-label="{{ $isFrench ? 'Pourcentage de performance' : 'Performance percentage' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $isFrench ? 'Objectif' : 'Target' }}: {{ number_format($production->sac->configuration->valeur_moyenne_fcfa, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            @endif

                            <!-- Produits détaillés (menu déroulant) -->
                            <details class="mb-4">
                                <summary class="cursor-pointer text-sm font-medium text-gray-700 py-2 px-3 bg-gray-50 rounded-lg flex items-center justify-between hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span>{{ $production->productionProduits->count() }} {{ $isFrench ? 'produits' : 'products' }}</span>
                                        <span class="ml-2 text-xs text-gray-500">
                                            ({{ $production->productionProduits->sum('quantite') }} {{ $isFrench ? 'unités' : 'units' }})
                                        </span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                
                                <div class="mt-3 space-y-2">
                                    @forelse($production->productionProduits as $produitProduction)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900 text-sm">
                                                        {{ $produitProduction->produit->nom }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $isFrench ? 'Catégorie' : 'Category' }}: {{ $produitProduction->produit->categorie }}
                                                    </p>
                                                </div>
                                                <div class="ml-3 text-right">
                                                    <div class="font-semibold text-sm text-gray-900">
                                                        {{ $produitProduction->quantite }} {{ $isFrench ? 'unités' : 'units' }}
                                                    </div>
                                                    <div class="text-xs text-gray-600">
                                                        {{ number_format($produitProduction->valeur_unitaire, 0, $isFrench ? ',' : '.', $isFrench ? ' ' : ',') }} FCFA/{{ $isFrench ? 'unité' : 'unit' }}
                                                    </div>
                                                    <div class="text-sm font-medium text-blue-600 mt-1">
                                                        {{ number_format($produitProduction->valeur_totale, 0, $isFrench ? ',' : '.', $isFrench ? ' ' : ',') }} FCFA
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-gray-500 text-sm">
                                            {{ $isFrench ? 'Aucun produit enregistré' : 'No products recorded' }}
                                        </div>
                                    @endforelse
                                    
                                    @if($production->observations)
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <div>
                                                    <h5 class="text-sm font-medium text-yellow-800 mb-1">
                                                        {{ $isFrench ? 'Observations' : 'Observations' }}
                                                    </h5>
                                                    <p class="text-sm text-yellow-700">
                                                        {{ $production->observations }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        </div>

                        <!-- Actions -->
                        <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                            <div class="flex space-x-1">
                                <a href="{{ route('boulangerie.production.show', $production->id) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition-colors min-h-[40px]"
                                   aria-label="{{ $isFrench ? 'Voir les détails' : 'View details' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $isFrench ? 'Voir' : 'View' }}
                                </a>
                                 @php
                                     $secteur = auth()->user()->secteur;
                                     $pm = $secteur != 'administration'
                                 @endphp
                                @if ($pm)
                                    <a href="{{ route('boulangerie.production.edit', $production->id) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 active:bg-amber-200 transition-colors min-h-[40px]"
                                   aria-label="{{ $isFrench ? 'Modifier la production' : 'Edit production' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                            </div>
                            
                            <form action="{{ route('boulangerie.production.destroy', $production->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette production ?' : 'Are you sure you want to delete this production?' }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 active:bg-red-200 transition-colors min-h-[40px]"
                                        aria-label="{{ $isFrench ? 'Supprimer la production' : 'Delete production' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ $isFrench ? 'Supprimer' : 'Delete' }}</span>
                                </button>
                            </form>
                                @endif 
                                
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- État vide optimisé mobile -->
            <div class="text-center py-16 px-4">
                <div class="max-w-sm mx-auto">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ $isFrench ? 'Pas encore de productions' : 'No productions yet' }}
                    </h3>
                    
                    <p class="text-gray-600 mb-8 text-sm leading-relaxed">
                        {{ $isFrench ? 'Commencez par créer votre première production pour suivre vos performances.' : 'Start by creating your first production to track your performance.' }}
                    </p>
                    
                    <a href="{{ route('boulangerie.production.create') }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 active:bg-blue-800 transition-colors min-h-[48px]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ $isFrench ? 'Créer ma première production' : 'Create my first production' }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Tableau desktop (caché sur mobile) -->
    <div class="hidden lg:block px-4 py-4">
        @if($productions->count() > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Date' : 'Date' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Sac' : 'Bag' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produits' : 'Products' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Performance' : 'Performance' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Statut' : 'Status' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($productions as $production)
                                <tr class="hover:bg-gray-50">
                                    <!-- Le contenu du tableau original reste identique -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $isFrench ? $production->date_production->format('d/m/Y') : $production->date_production->format('m/d/Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $production->sac->nom }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $isFrench ? 'Producteur' : 'Producer' }}: {{ $production->producteur->name }}
                                        </div>
                                        @if($production->sac->configuration)
                                            <div class="text-sm text-gray-500">
                                                {{ $isFrench ? 'Objectif' : 'Target' }}: {{ number_format($production->sac->configuration->valeur_moyenne_fcfa, 0, ',', ' ') }} FCFA
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $production->productionProduits->count() }} {{ $isFrench ? 'produits' : 'products' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $production->productionProduits->sum('quantite') }} {{ $isFrench ? 'unités' : 'units' }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($production->valeur_totale_fcfa, 0, $isFrench ? ',' : '.', $isFrench ? ' ' : ',') }} FCFA
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($production->sac->configuration)
                                            @php
                                                $pourcentage = ($production->valeur_totale_fcfa / $production->sac->configuration->valeur_moyenne_fcfa) * 100;
                                                $couleur = $pourcentage >= 100 ? 'green' : ($pourcentage >= 80 ? 'yellow' : 'red');
                                            @endphp
                                            
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-{{ $couleur }}-500 h-2 rounded-full" style="width: {{ min($pourcentage, 100) }}%"></div>
                                                </div>
                                                <span class="text-sm text-{{ $couleur }}-600 font-medium">
                                                    {{ number_format($pourcentage, 1, $isFrench ? ',' : '.', '') }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($production->estSousLaMoyenne())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ $isFrench ? 'Sous moyenne' : 'Below average' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $isFrench ? 'Objectif atteint' : 'Target achieved' }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('boulangerie.production.show', $production->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            {{ $isFrench ? 'Voir' : 'View' }}
                                        </a>
                                        
                                        <a href="{{ route('boulangerie.production.edit', $production->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        
                                        <form action="{{ route('boulangerie.production.destroy', $production->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette production ?' : 'Are you sure you want to delete this production?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Améliorer la visibilité des focus pour l'accessibilité */
a:focus, button:focus, summary:focus {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
}

/* Animation douce pour les détails */
details[open] summary {
    margin-bottom: 0.5rem;
}

/* Améliorer les zones de touch sur mobile */
@media (max-width: 768px) {
    a, button {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
}

/* Scroll horizontal amélioré pour les petites tables */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
}

/* Animations pour les états de chargement */
.transition-all {
    transition: all 0.2s ease-in-out;
}
</style>
@endsection
