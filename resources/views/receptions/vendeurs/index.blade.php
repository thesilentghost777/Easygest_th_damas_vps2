@extends('layouts.app')

@section('title', $isFrench ? 'Réceptions Vendeurs' : 'Vendor Receptions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 mb-8">
            @include('buttons')
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl px-6 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold">
                                {{ $isFrench ? 'Réceptions Vendeurs' : 'Vendor Receptions' }}
                            </h1>
                            <p class="text-blue-100 mt-1">
                                {{ $isFrench ? 'Gestion des réceptions et stocks vendeurs' : 'Vendor reception and stock management' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('receptions.vendeurs.create') }}" 
                           class="bg-white text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Nouvelle Réception' : 'New Reception' }}</span>
                        </a>
                       
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="mx-6 mt-6">
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center space-x-3 shadow-sm">
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="p-6">
                <form id="filter-form" method="GET" action="{{ route('receptions.vendeurs.index') }}" class="bg-blue-50 rounded-xl p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Vendeur' : 'Vendor' }}
                            </label>
                            <select name="vendeur_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="filter-vendeur">
                                <option value="">{{ $isFrench ? 'Tous les vendeurs' : 'All vendors' }}</option>
                                @foreach($vendeurs as $vendeur)
                                    <option value="{{ $vendeur->id }}" {{ request('vendeur_id') == $vendeur->id ? 'selected' : '' }}>
                                        {{ $vendeur->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </label>
                            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="filter-date">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                                </svg>
                                <span>{{ $isFrench ? 'Filtrer' : 'Filter' }}</span>
                            </button>
                            @if(request()->hasAny(['vendeur_id', 'date']))
                                <a href="{{ route('receptions.vendeurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>{{ $isFrench ? 'Réinitialiser' : 'Reset' }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['vendeur_id', 'date']))
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Filtres actifs:' : 'Active filters:' }}</span>
                            @if(request('vendeur_id'))
                                @php
                                    $selectedVendeur = $vendeurs->find(request('vendeur_id'));
                                @endphp
                                @if($selectedVendeur)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $isFrench ? 'Vendeur:' : 'Vendor:' }} {{ $selectedVendeur->name }}
                                    </span>
                                @endif
                            @endif
                            @if(request('date'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $isFrench ? 'Date:' : 'Date:' }} {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    @endif
                </form>

                <!-- Data Table -->
                @if($receptions->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-100">
                                    <th class="text-left py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Date' : 'Date' }}
                                    </th>
                                    <th class="text-left py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Vendeur' : 'Vendor' }}
                                    </th>
                                    <th class="text-left py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Entrée Matin' : 'Morning Entry' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Entrée Journée' : 'Day Entry' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Invendu' : 'Unsold' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Avarie' : 'Spoiled' }}
                                    </th>
                                    <th class="text-center py-4 px-3 text-sm font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $isFrench ? 'Actions' : 'Actions' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($receptions as $reception)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="py-4 px-3">
                                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-3 py-2 rounded-lg font-medium text-sm inline-block">
                                                {{ $reception->date_reception->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="bg-green-100 rounded-full p-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $reception->vendeur->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="font-medium text-gray-900">{{ $reception->produit->nom }}</div>
                                            <div class="text-sm text-gray-500">prix: {{ $reception->produit->prix }}</div>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-green-100 text-green-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($reception->quantite_entree_matin, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($reception->quantite_entree_journee, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($reception->quantite_invendue, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <span class="bg-red-100 text-red-800 px-3 py-2 rounded-full font-semibold">
                                                {{ number_format($reception->quantite_avarie ?? 0, 0) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-3 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('receptions.vendeurs.show', $reception->id) }}" 
                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">{{ $isFrench ? 'Voir' : 'View' }}</span>
                                                </a>
                                                <a href="{{ route('receptions.vendeurs.edit', $reception->id) }}" 
                                                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">{{ $isFrench ? 'Modifier' : 'Edit' }}</span>
                                                </a>
                                                <form action="{{ route('receptions.vendeurs.destroy', $reception->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette réception ?' : 'Are you sure you want to delete this reception?' }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-medium transition-colors duration-200 inline-flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span class="hidden sm:inline">{{ $isFrench ? 'Supprimer' : 'Delete' }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @foreach($receptions as $reception)
                            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="font-semibold text-gray-900 mb-1">{{ $reception->vendeur->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $reception->produit->nom }}</div>
                                    </div>
                                    <div class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 px-3 py-1 rounded-lg text-sm font-medium">
                                        {{ $reception->date_reception->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Matin' : 'Morning' }}</div>
                                        <div class="bg-green-100 text-green-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($reception->quantite_entree_matin, 0) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Journée' : 'Day' }}</div>
                                        <div class="bg-blue-100 text-blue-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($reception->quantite_entree_journee, 0) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Invendu' : 'Unsold' }}</div>
                                        <div class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($reception->quantite_invendue, 0) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-1">{{ $isFrench ? 'Avarie' : 'Spoiled' }}</div>
                                        <div class="bg-red-100 text-red-800 px-3 py-2 rounded-lg font-semibold">
                                            {{ number_format($reception->quantite_avarie ?? 0, 0) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                    <a href="{{ route('receptions.vendeurs.show', $reception->id) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>{{ $isFrench ? 'Voir' : 'View' }}</span>
                                    </a>
                                    <a href="{{ route('receptions.vendeurs.edit', $reception->id) }}" 
                                       class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>{{ $isFrench ? 'Modifier' : 'Edit' }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                            {{ $receptions->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="bg-gradient-to-br from-blue-100 to-green-100 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            {{ $isFrench ? 'Aucune réception trouvée' : 'No receptions found' }}
                        </h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            @if(request()->hasAny(['vendeur_id', 'date']))
                                {{ $isFrench ? 'Aucune réception ne correspond aux critères de filtrage.' : 'No receptions match the filtering criteria.' }}
                            @else
                                {{ $isFrench ? 'Commencez par enregistrer une nouvelle réception vendeur.' : 'Start by creating a new vendor reception.' }}
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @if(request()->hasAny(['vendeur_id', 'date']))
                                <a href="{{ route('receptions.vendeurs.index') }}" 
                                   class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                    {{ $isFrench ? 'Voir toutes les réceptions' : 'View all receptions' }}
                                </a>
                            @endif
                            <a href="{{ route('receptions.vendeurs.create') }}" 
                               class="bg-gradient-to-r from-blue-600 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                {{ $isFrench ? 'Créer une réception' : 'Create a reception' }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
