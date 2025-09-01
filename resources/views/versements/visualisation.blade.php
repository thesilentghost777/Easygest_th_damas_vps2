@extends('layouts.app')

@section('title', (isset($isFrench) && $isFrench) ? 'Visualisation des Versements' : 'Payments Visualization')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    }
    
    .versement-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .versement-card:hover {
        border-left-color: #667eea;
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .status-badge {
        transition: all 0.2s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .filter-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .top-verseur-item {
        transition: all 0.3s ease;
    }
    
    .top-verseur-item:hover {
        background-color: #f8fafc;
        transform: translateX(10px);
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .versement-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm mb-8 p-6">
           @include('buttons')

            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Cahier des Versements' : 'Payments Ledger' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ (isset($isFrench) && $isFrench) ? 'Suivi et analyse des versements des employés' : 'Employee payment tracking and analysis' }}
                    </p>
                </div>

               
            </div>
        </div>

      <!-- Statistiques globales -->
<div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Versements -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-100 mb-1">
                    {{ (isset($isFrench) && $isFrench) ? 'Total Versements' : 'Total Payments' }}
                </p>
                <p class="text-3xl font-bold text-white">{{ $statistiques['total_versements'] }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3 ml-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Montant Total -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-green-100 mb-1">
                    {{ (isset($isFrench) && $isFrench) ? 'Montant Total' : 'Total Amount' }}
                </p>
                <p class="text-3xl font-bold text-white">{{ number_format($statistiques['montant_total'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3 ml-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3-3-3h1.5a3 3 0 110-6H9zm-1 0V6a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Montant Moyen -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-purple-100 mb-1">
                    {{ (isset($isFrench) && $isFrench) ? 'Montant Moyen' : 'Average Amount' }}
                </p>
                <p class="text-3xl font-bold text-white">{{ number_format($statistiques['montant_moyen'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3 ml-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- En Attente -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-orange-100 mb-1">
                    {{ (isset($isFrench) && $isFrench) ? 'En Attente' : 'Pending' }}
                </p>
                <p class="text-3xl font-bold text-white">{{ $statistiques['versements_en_attente'] }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3 ml-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

        <!-- Filtres et tri -->
        <div class="bg-white rounded-lg shadow-sm mb-8 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ (isset($isFrench) && $isFrench) ? 'Filtres et Tri' : 'Filters and Sorting' }}
            </h3>
            
            <form method="GET" class="filter-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Date début' : 'Start Date' }}
                    </label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Date fin' : 'End Date' }}
                    </label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Employé' : 'Employee' }}
                    </label>
                    <select name="verseur" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ (isset($isFrench) && $isFrench) ? 'Tous les employés' : 'All employees' }}</option>
                        @foreach($employes as $employe)
                            <option value="{{ $employe->id }}" {{ $verseur == $employe->id ? 'selected' : '' }}>
                                {{ $employe->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Statut' : 'Status' }}
                    </label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ (isset($isFrench) && $isFrench) ? 'Tous les statuts' : 'All statuses' }}</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'En attente' : 'Pending' }}
                        </option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'Validé' : 'Validated' }}
                        </option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Trier par' : 'Sort by' }}
                    </label>
                    <select name="order_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="date" {{ $orderBy == 'date' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'Date' : 'Date' }}
                        </option>
                        <option value="montant" {{ $orderBy == 'montant' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'Montant' : 'Amount' }}
                        </option>
                        <option value="verseur" {{ $orderBy == 'verseur' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'Employé' : 'Employee' }}
                        </option>
                        <option value="status" {{ $orderBy == 'status' ? 'selected' : '' }}>
                            {{ (isset($isFrench) && $isFrench) ? 'Statut' : 'Status' }}
                        </option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        {{ (isset($isFrench) && $isFrench) ? 'Appliquer' : 'Apply' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des versements -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">
                            {{ (isset($isFrench) && $isFrench) ? 'Liste des Versements' : 'Payments List' }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">
                                {{ $versements->count() }} {{ (isset($isFrench) && $isFrench) ? 'résultat(s)' : 'result(s)' }}
                            </span>
                            <a href="?order_direction={{ $orderDirection == 'asc' ? 'desc' : 'asc' }}&order_by={{ $orderBy }}&date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}&verseur={{ $verseur }}&status={{ $status }}" 
                               class="text-blue-600 hover:text-blue-800">
                                @if($orderDirection == 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            </a>
                        </div>
                    </div>

                    @if($versements->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($versements as $versement)
                                <div class="versement-card bg-gray-50 rounded-lg p-4">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="text-lg font-semibold text-gray-800 mr-3">
                                                    {{ $versement->libelle }}
                                                </h4>
                                                <span class="status-badge px-3 py-1 text-xs font-medium rounded-full
                                                    @if($versement->status == 1) bg-green-100 text-green-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    @if($versement->status == 1)
                                                        {{ (isset($isFrench) && $isFrench) ? 'Validé' : 'Validated' }}
                                                    @else
                                                        {{ (isset($isFrench) && $isFrench) ? 'En attente' : 'Pending' }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="text-gray-600 text-sm space-y-1">
                                                <p><strong>{{ (isset($isFrench) && $isFrench) ? 'Verseur:' : 'Payer:' }}</strong> {{ $versement->verseur_name }}</p>
                                                <p><strong>{{ (isset($isFrench) && $isFrench) ? 'Date:' : 'Date:' }}</strong> {{ \Carbon\Carbon::parse($versement->date)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 md:text-right">
                                            <div class="text-2xl font-bold text-green-600">
                                                +{{ number_format($versement->montant, 0, ',', ' ') }} FCFA
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($versement->created_at)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">💰</div>
                            <h3 class="text-xl font-medium text-gray-600 mb-2">
                                {{ (isset($isFrench) && $isFrench) ? 'Aucun versement trouvé' : 'No payments found' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ (isset($isFrench) && $isFrench) ? 'Aucun versement ne correspond aux critères sélectionnés.' : 'No payments match the selected criteria.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques détaillées -->
            <div class="space-y-6">
                <!-- Répartition par statut -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ (isset($isFrench) && $isFrench) ? 'Répartition par Statut' : 'Status Distribution' }}
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="font-medium text-green-800">
                                    {{ (isset($isFrench) && $isFrench) ? 'Validés' : 'Validated' }}
                                </p>
                                <p class="text-sm text-green-600">{{ $statistiques['versements_valides'] }} versements</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-800">
                                    {{ number_format($statistiques['montant_valide'], 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="font-medium text-yellow-800">
                                    {{ (isset($isFrench) && $isFrench) ? 'En attente' : 'Pending' }}
                                </p>
                                <p class="text-sm text-yellow-600">{{ $statistiques['versements_en_attente'] }} versements</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-yellow-800">
                                    {{ number_format($statistiques['montant_en_attente'], 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 des verseurs -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ (isset($isFrench) && $isFrench) ? 'Top 5 des Verseurs' : 'Top 5 Payers' }}
                    </h4>
                    <div class="space-y-3">
                        @foreach($statistiques['top_verseurs'] as $index => $verseur)
                            <div class="top-verseur-item flex items-center justify-between p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $verseur->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $verseur->nombre_versements }} versements</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600">
                                        {{ number_format($verseur->montant_total, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
