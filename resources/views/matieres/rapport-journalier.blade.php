@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Rapport Journalier' : 'Daily Report' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ date('d/m/Y', strtotime($hier)) }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Rapport d\'utilisation des matières' : 'Materials Usage Report' }} - {{ date('d/m/Y', strtotime($hier)) }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Analyse détaillée des performances quotidiennes' : 'Detailed analysis of daily performance' }}
                </p>
            </div>
        </div>

        <!-- Mobile Summary Cards -->
        <div class="md:hidden space-y-4 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300 animate-slide-in-right">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût total des matières assignées' : 'Total cost of assigned materials' }}
                </h2>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($coutTotalAssignations, 0, ',', ' ') }} FCFA</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.1s">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût total des matières utilisées' : 'Total cost of used materials' }}
                </h2>
                <p class="text-3xl font-bold text-green-600">{{ number_format($coutTotalUtilisations, 0, ',', ' ') }} FCFA</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 {{ $alerteGaspillage ? 'border-red-500' : 'border-yellow-500' }} transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: 0.2s">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût des matières restantes' : 'Cost of remaining materials' }}
                </h2>
                <p class="text-3xl font-bold {{ $alerteGaspillage ? 'text-red-600' : 'text-yellow-600' }}">{{ number_format($coutTotalRestant, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <!-- Desktop Summary Cards -->
        <div class="hidden md:block grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow rounded-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût total des matières assignées' : 'Total cost of assigned materials' }}
                </h2>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($coutTotalAssignations, 0, ',', ' ') }} FCFA</p>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût total des matières utilisées' : 'Total cost of used materials' }}
                </h2>
                <p class="text-3xl font-bold text-green-600">{{ number_format($coutTotalUtilisations, 0, ',', ' ') }} FCFA</p>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6 border-l-4 {{ $alerteGaspillage ? 'border-red-500' : 'border-yellow-500' }} transform hover:scale-105 transition-all duration-300">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ $isFrench ? 'Coût des matières restantes' : 'Cost of remaining materials' }}
                </h2>
                <p class="text-3xl font-bold {{ $alerteGaspillage ? 'text-red-600' : 'text-yellow-600' }}">{{ number_format($coutTotalRestant, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
        
        @if($alerteGaspillage)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg shadow animate-pulse">
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <p class="font-semibold">
                    {{ $isFrench ? 'ALERTE : Si ces matières restantes ne sont pas visibles au niveau du CP, alors il y a eu soit détournement de matières premières, soit inexactitude dans les enregistrements des producteurs.' : 'WARNING: If these remaining materials are not visible at the production manager level, then there has been either misappropriation of raw materials or inaccuracy in producer records.' }}
                </p>
            </div>
        </div>
        @endif

        <!-- Producer Performance Title -->
        <h2 class="text-2xl font-bold mt-12 mb-6 text-center md:text-left">
            {{ $isFrench ? 'Performances des producteurs' : 'Producer Performance' }}
        </h2>

        <!-- Mobile Producer Cards -->
        <div class="md:hidden space-y-6">
            @foreach($donneesProducteurs as $donnees)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <!-- Mobile Producer Header -->
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-black font-bold text-2xl w-12 h-12 flex items-center justify-center rounded-lg shadow">
                                {{ $donnees['note'] }}
                            </div>
                            <div class="text-right">
                                <div class="text-white font-bold text-sm">{{ $donnees['producteur']->role }}</div>
                                @if($donnees['efficacite_cout'] >= 110)
                                    <div class="text-green-400 text-xs">{{ $isFrench ? 'Excellent rendement' : 'Excellent performance' }}</div>
                                @elseif($donnees['efficacite_cout'] >= 100)
                                    <div class="text-blue-400 text-xs">{{ $isFrench ? 'Bon rendement' : 'Good performance' }}</div>
                                @else
                                    <div class="text-yellow-400 text-xs">{{ $isFrench ? 'Rendement à améliorer' : 'Performance to improve' }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="bg-gray-700 rounded-full p-2 border-2 border-gray-600 w-16 h-16 mx-auto mb-3">
                                <svg class="h-12 w-12 text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="text-white font-bold text-lg">{{ $donnees['producteur']->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Mobile Producer Stats -->
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            @foreach($donnees['competences'] as $nom => $valeur)
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">{{ $nom }}</div>
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto {{ $valeur >= 80 ? 'bg-green-500' : ($valeur >= 70 ? 'bg-yellow-500' : 'bg-orange-500') }} text-white font-semibold text-sm">
                                        {{ $valeur }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-100 p-3 rounded-xl text-center">
                                <div class="text-xs text-gray-600">{{ $isFrench ? 'Matières' : 'Materials' }}</div>
                                <div class="text-gray-900 font-semibold text-sm">{{ number_format($donnees['cout_total_matieres'], 0, ',', ' ') }} FCFA</div>
                            </div>
                            <div class="bg-gray-100 p-3 rounded-xl text-center">
                                <div class="text-xs text-gray-600">{{ $isFrench ? 'Produits' : 'Products' }}</div>
                                <div class="text-gray-900 font-semibold text-sm">{{ number_format($donnees['cout_total_produits'], 0, ',', ' ') }} FCFA</div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 p-3 rounded-xl mb-4">
                            <div class="text-xs text-blue-600 mb-1">{{ $isFrench ? 'Efficacité' : 'Efficiency' }}</div>
                            <div class="text-blue-900 font-semibold">{{ number_format($donnees['efficacite_cout'], 1, ',', ' ') }}%</div>
                            <div class="w-full h-2 bg-blue-200 rounded-full mt-2">
                                <div class="h-full {{ $donnees['efficacite_cout'] >= 110 ? 'bg-green-500' : ($donnees['efficacite_cout'] >= 100 ? 'bg-blue-500' : 'bg-yellow-500') }} rounded-full" style="width: {{ min(100, $donnees['efficacite_cout']) }}%;"></div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="text-gray-900 text-sm font-semibold mb-3">{{ $isFrench ? 'Détail des matières' : 'Material details' }}</h4>
                            <div class="space-y-2">
                                @foreach($donnees['details_matieres'] as $detail)
                                    <div class="flex justify-between text-xs">
                                        <div class="text-gray-600 flex-1">{{ $detail['matiere']->nom }}</div>
                                        <div class="text-gray-900 mx-2">{{ number_format($detail['quantite_assignee'], 2, ',', ' ') }} {{ $detail['unite_assignee'] }}</div>
                                        <div class="{{ ($detail['quantite_restante'] / $detail['quantite_assignee_minimale'] > 0.1) ? 'text-yellow-600' : 'text-green-600' }}">
                                            {{ number_format($detail['quantite_utilisee'], 2, ',', ' ') }} {{ $detail['matiere']->unite_minimale }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="bg-gray-100 p-3 rounded-xl mt-4 text-center">
                            <div class="text-xs text-gray-600">{{ $donnees['nombre_produits_crees'] }} {{ $isFrench ? 'produits créés' : 'products created' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Producer Cards (FIFA Style) -->
        <div class="hidden md:block grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($donneesProducteurs as $donnees)
                <div class="relative overflow-hidden rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="bg-gradient-to-b from-gray-800 to-gray-900 h-full">
                        <!-- Header with rating -->
                        <div class="flex justify-between items-center p-4">
                            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 text-black font-bold text-2xl w-16 h-16 flex items-center justify-center rounded-lg shadow">
                                {{ $donnees['note'] }}
                            </div>
                            <div class="text-right">
                                <div class="text-white font-bold">{{ $donnees['producteur']->role }}</div>
                                @if($donnees['efficacite_cout'] >= 110)
                                    <div class="text-green-400 text-sm">{{ $isFrench ? 'Excellent rendement' : 'Excellent performance' }}</div>
                                @elseif($donnees['efficacite_cout'] >= 100)
                                    <div class="text-blue-400 text-sm">{{ $isFrench ? 'Bon rendement' : 'Good performance' }}</div>
                                @else
                                    <div class="text-yellow-400 text-sm">{{ $isFrench ? 'Rendement à améliorer' : 'Performance to improve' }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Photo / Icon -->
                        <div class="flex justify-center mb-2">
                            <div class="bg-gray-700 rounded-full p-3 border-2 border-gray-600">
                                <svg class="h-20 w-20 text-gray-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Name -->
                        <div class="text-center text-white font-bold text-xl mb-4">
                            {{ $donnees['producteur']->name }}
                        </div>
                        
                        <!-- Statistics -->
                        <div class="bg-gradient-to-b from-gray-700 to-gray-800 p-4">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                @foreach($donnees['competences'] as $nom => $valeur)
                                    <div class="flex flex-col items-center">
                                        <div class="text-xs text-gray-300 mb-1">{{ $nom }}</div>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $valeur >= 80 ? 'bg-green-500' : ($valeur >= 70 ? 'bg-yellow-500' : 'bg-orange-500') }} text-white font-semibold text-sm">
                                                {{ $valeur }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Financial information -->
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-gray-600 p-2 rounded">
                                    <div class="text-xs text-gray-300">{{ $isFrench ? 'Matières' : 'Materials' }}</div>
                                    <div class="text-white font-semibold">{{ number_format($donnees['cout_total_matieres'], 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="bg-gray-600 p-2 rounded">
                                    <div class="text-xs text-gray-300">{{ $isFrench ? 'Produits' : 'Products' }}</div>
                                    <div class="text-white font-semibold">{{ number_format($donnees['cout_total_produits'], 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="bg-gray-600 p-2 rounded col-span-2">
                                    <div class="text-xs text-gray-300">{{ $isFrench ? 'Efficacité' : 'Efficiency' }}</div>
                                    <div class="text-white font-semibold">{{ number_format($donnees['efficacite_cout'], 1, ',', ' ') }}%</div>
                                    <div class="w-full h-1 bg-gray-700 mt-1">
                                        <div class="h-full {{ $donnees['efficacite_cout'] >= 110 ? 'bg-green-500' : ($donnees['efficacite_cout'] >= 100 ? 'bg-blue-500' : 'bg-yellow-500') }}" style="width: {{ min(100, $donnees['efficacite_cout']) }}%;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Material details -->
                            <div class="mt-4 border-t border-gray-600 pt-4">
                                <h4 class="text-white text-sm font-semibold mb-2">{{ $isFrench ? 'Détail des matières' : 'Material details' }}</h4>
                                <div class="space-y-2">
                                    @foreach($donnees['details_matieres'] as $detail)
                                        <div class="grid grid-cols-3 gap-1 text-xs">
                                            <div class="text-gray-300">{{ $detail['matiere']->nom }}</div>
                                            <div class="text-right text-white">{{ number_format($detail['quantite_assignee'], 2, ',', ' ') }} {{ $detail['unite_assignee'] }}</div>
                                            <div class="text-right {{ ($detail['quantite_restante'] / $detail['quantite_assignee_minimale'] > 0.1) ? 'text-yellow-400' : 'text-green-400' }}">
                                                {{ number_format($detail['quantite_utilisee'], 2, ',', ' ') }} {{ $detail['matiere']->unite_minimale }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card footer -->
                        <div class="bg-gray-900 p-3 text-center text-xs text-gray-400">
                            <div>{{ $donnees['nombre_produits_crees'] }} {{ $isFrench ? 'produits créés' : 'products created' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
