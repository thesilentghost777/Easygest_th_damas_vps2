@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="px-4 py-3">
            @include('buttons')
            <div class="mt-3 flex items-center justify-between">
                <h1 class="text-lg font-bold text-gray-800 animate-fadeInLeft">
                    {{ $isFrench ? 'Liste des ventes' : 'Sales list' }}
                </h1>
                <a href="{{ route('serveur.vente.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium shadow-lg transform transition-all duration-300 hover:scale-105 active:scale-95">
                    <i class="fas fa-plus mr-1"></i>
                    {{ $isFrench ? 'Nouvelle' : 'New sale' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Header Desktop -->
    <div class="hidden lg:block container mx-auto px-4 py-8">
        @include('buttons')
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $isFrench ? 'Liste détaillée des ventes' : 'Detailed sales list' }}
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('serveur.vente.create') }}" 
                   class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                    {{ $isFrench ? 'Nouvelle vente' : 'New sale' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres Mobile - Accordéon -->
    <div class="lg:hidden mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300">
            <button onclick="toggleFilters()" 
                    class="w-full px-4 py-4 bg-blue-50 text-left flex items-center justify-between text-blue-700 font-medium">
                <span class="flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </span>
                <i class="fas fa-chevron-down transition-transform duration-300" id="filter-chevron"></i>
            </button>
            
            <div id="mobile-filters" class="hidden px-4 pb-4 space-y-4 animate-slideDown">
                <form action="{{ route('serveur.vente.liste') }}" method="GET" class="space-y-4">
                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="transform transition-all duration-300 hover:scale-105">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                {{ $isFrench ? 'Date début' : 'Start date' }}
                            </label>
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="transform transition-all duration-300 hover:scale-105">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                {{ $isFrench ? 'Date fin' : 'End date' }}
                            </label>
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>

                    <!-- Selects -->
                    <div class="space-y-3">
                        <div class="transform transition-all duration-300 hover:scale-105">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </label>
                            <select name="produit" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                                        {{ $produit->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                                <select name="type" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    {{ $isFrench ? 'Paiement' : 'Payment' }}
                                </label>
                                <select name="monnaie" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                                    @foreach($monnaies as $monnaie)
                                        <option value="{{ $monnaie }}" {{ request('monnaie') == $monnaie ? 'selected' : '' }}>
                                            {{ $monnaie }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    {{ $isFrench ? 'Trier par' : 'Sort by' }}
                                </label>
                                <select name="sort" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="date_vente" {{ request('sort') == 'date_vente' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Date' : 'Date' }}
                                    </option>
                                    <option value="prix" {{ request('sort') == 'prix' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Prix' : 'Price' }}
                                    </option>
                                    <option value="quantite" {{ request('sort') == 'quantite' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                    </option>
                                </select>
                            </div>
                            <div class="transform transition-all duration-300 hover:scale-105">
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    {{ $isFrench ? 'Ordre' : 'Order' }}
                                </label>
                                <select name="direction" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Décroissant' : 'Descending' }}
                                    </option>
                                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Croissant' : 'Ascending' }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3 pt-2">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-medium transform transition-all duration-300 hover:scale-105 active:scale-95 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            {{ $isFrench ? 'Appliquer' : 'Apply' }}
                        </button>
                        <a href="{{ route('serveur.vente.liste') }}" 
                           class="px-4 py-3 text-blue-600 border border-blue-200 rounded-xl font-medium transform transition-all duration-300 hover:scale-105 active:scale-95">
                            <i class="fas fa-redo mr-1"></i>
                            {{ $isFrench ? 'Reset' : 'Reset' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtres Desktop -->
    <div class="hidden lg:block container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Filtres' : 'Filters' }}
            </h2>
            <form action="{{ route('serveur.vente.liste') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Date début' : 'Start date' }}
                    </label>
                    <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Date fin' : 'End date' }}
                    </label>
                    <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="produit" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select id="produit" name="produit" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                                {{ $produit->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="type" name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="monnaie" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Moyen de paiement' : 'Payment method' }}
                    </label>
                    <select id="monnaie" name="monnaie" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        @foreach($monnaies as $monnaie)
                            <option value="{{ $monnaie }}" {{ request('monnaie') == $monnaie ? 'selected' : '' }}>
                                {{ $monnaie }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Trier par' : 'Sort by' }}
                    </label>
                    <select id="sort" name="sort" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="date_vente" {{ request('sort') == 'date_vente' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </option>
                        <option value="prix" {{ request('sort') == 'prix' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Prix' : 'Price' }}
                        </option>
                        <option value="quantite" {{ request('sort') == 'quantite' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                        </option>
                    </select>
                </div>
                
                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $isFrench ? 'Ordre' : 'Order' }}
                    </label>
                    <select id="direction" name="direction" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Décroissant' : 'Descending' }}
                        </option>
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Croissant' : 'Ascending' }}
                        </option>
                    </select>
                </div>
                
                <div class="md:col-span-3 lg:col-span-3 flex items-end">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors duration-200">
                        {{ $isFrench ? 'Appliquer les filtres' : 'Apply filters' }}
                    </button>
                    <a href="{{ route('serveur.vente.liste') }}" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Mobile -->
    <div class="lg:hidden mx-4 mb-4">
        <div class="bg-white rounded-2xl shadow-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Résultats' : 'Results' }}
                    </span>
                </div>
                <span class="text-sm text-blue-600 font-bold">
                    {{ $ventes->total() }} {{ $isFrench ? 'vente(s)' : 'sale(s)' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Liste Mobile - Cards -->
    <div class="lg:hidden mx-4 space-y-3 pb-4">
        @forelse ($ventes as $index => $vente)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105" 
                 style="animation: slideInUp 0.6s ease-out {{ $index * 0.1 }}s both;">
                
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-semibold text-blue-700">
                                {{ $vente->produits->nom }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if ($vente->type == 'Vente')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $isFrench ? 'Vente' : 'Sale' }}
                                </span>
                            @elseif ($vente->type == 'Produit invendu')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $isFrench ? 'Invendu' : 'Unsold' }}
                                </span>
                            @elseif ($vente->type == 'Produit Avarie')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $isFrench ? 'Avarié' : 'Damaged' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Body Card -->
                <div class="p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <div class="text-xs text-gray-500 mb-1">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </div>
                            <div class="text-lg font-bold text-gray-800">{{ $vente->quantite }}</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-xl">
                            <div class="text-xs text-gray-500 mb-1">
                                {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}
                            </div>
                            <div class="text-sm font-bold text-gray-800">
                                {{ number_format($vente->prix, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="text-xs text-blue-600 mb-1 font-medium">
                            {{ $isFrench ? 'Total' : 'Total' }}
                        </div>
                        <div class="text-xl font-bold text-blue-700">
                            {{ number_format($vente->quantite * $vente->prix, 0, ',', ' ') }} FCFA
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="text-center">
                            <div class="text-xs text-gray-500">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </div>
                            <div class="text-sm font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $vente->created_at->format('H:i') }}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-xs text-gray-500">
                                {{ $isFrench ? 'Paiement' : 'Payment' }}
                            </div>
                            <div class="text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $vente->monnaie }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Aucune vente trouvée' : 'No sales found' }}
                </h3>
                <p class="text-gray-500 text-sm">
                    {{ $isFrench ? 'Essayez de modifier vos filtres' : 'Try adjusting your filters' }}
                </p>
            </div>
        @endforelse
    </div>

    <!-- Tableau Desktop -->
    <div class="hidden lg:block container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Résultats' : 'Results' }}
                    </h2>
                    <span class="text-gray-600">
                        {{ $ventes->total() }} {{ $isFrench ? 'vente(s) trouvée(s)' : 'sale(s) found' }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Prix' : 'Price' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Paiement' : 'Payment' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($ventes as $vente)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y') }}
                                    <div class="text-xs text-gray-500">{{ $vente->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $vente->produits->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($vente->type == 'Vente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Vente' : 'Sale' }}
                                        </span>
                                    @elseif ($vente->type == 'Produit invendu')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? 'Invendu' : 'Unsold' }}
                                        </span>
                                    @elseif ($vente->type == 'Produit Avarie')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $isFrench ? 'Avarié' : 'Damaged' }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $vente->type }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $vente->quantite }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($vente->prix, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($vente->quantite * $vente->prix, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $vente->monnaie }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">
                                    {{ $isFrench ? 'Aucune vente trouvée' : 'No sales found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Total
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $ventes->sum('quantite') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                -
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-700">
                                {{ number_format($ventes->sum(function($vente) { return $vente->quantite * $vente->prix; }), 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                -
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50">
                {{ $ventes->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Pagination Mobile -->
    <div class="lg:hidden mx-4 mt-6 pb-6">
        <div class="bg-white rounded-2xl shadow-lg p-4">
            {{ $ventes->withQueryString()->links() }}
        </div>
    </div>

    <!-- Total Mobile -->
    @if($ventes->count() > 0)
    <div class="lg:hidden mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-4">
            <div class="text-center">
                <div class="text-sm text-gray-500 mb-2">
                    {{ $isFrench ? 'Total général' : 'Grand total' }}
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <div class="text-xs text-blue-600 mb-1">
                            {{ $isFrench ? 'Quantité totale' : 'Total quantity' }}
                        </div>
                        <div class="text-lg font-bold text-blue-700">
                            {{ $ventes->sum('quantite') }}
                        </div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-xl">
                        <div class="text-xs text-green-600 mb-1">
                            {{ $isFrench ? 'Montant total' : 'Total amount' }}
                        </div>
                        <div class="text-lg font-bold text-green-700">
                            {{ number_format($ventes->sum(function($vente) { return $vente->quantite * $vente->prix; }), 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Animations */
@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 500px;
        transform: translateY(0);
    }
}

.animate-fadeInLeft {
    animation: fadeInLeft 0.6s ease-out;
}

.animate-slideDown {
    animation: slideDown 0.4s ease-out;
}

/* Responsive enhancements */
@media (max-width: 1024px) {
    .container {
        padding-left: 0;
        padding-right: 0;
    }
}

/* Custom scrollbar for mobile */
@media (max-width: 1024px) {
    ::-webkit-scrollbar {
        width: 4px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
}

/* Focus states for better accessibility */
input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Hover effects for desktop */
@media (min-width: 1024px) {
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
}

/* Pulse animation for loading states */
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

/* Mobile-specific optimizations */
@media (max-width: 640px) {
    .container {
        max-width: 100%;
    }
    
    /* Improve touch targets */
    button, a, input, select {
        min-height: 44px;
    }
    
    /* Better spacing for small screens */
    .space-y-3 > * + * {
        margin-top: 0.75rem;
    }
}
</style>

<script>
function toggleFilters() {
    const filters = document.getElementById('mobile-filters');
    const chevron = document.getElementById('filter-chevron');
    
    if (filters.classList.contains('hidden')) {
        filters.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        filters.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';

// Add loading states for form submissions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + 
                    ({{ $isFrench ? 'true' : 'false' }} ? 'Chargement...' : 'Loading...');
                submitBtn.disabled = true;
            }
        });
    });
    
    // Add stagger animation to mobile cards
    const cards = document.querySelectorAll('.lg\\:hidden .space-y-3 > div');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Add pull-to-refresh simulation for mobile
let startY = 0;
let pullThreshold = 100;

if (window.innerWidth <= 1024) {
    document.addEventListener('touchstart', function(e) {
        startY = e.touches[0].pageY;
    });
    
    document.addEventListener('touchmove', function(e) {
        const currentY = e.touches[0].pageY;
        const diff = currentY - startY;
        
        if (diff > pullThreshold && window.scrollY === 0) {
            // Add visual feedback for pull-to-refresh
            document.body.style.transform = `translateY(${Math.min(diff - pullThreshold, 50)}px)`;
            document.body.style.transition = 'transform 0.2s ease-out';
        }
    });
    
    document.addEventListener('touchend', function() {
        document.body.style.transform = '';
        document.body.style.transition = '';
    });
}
</script>
@endsection