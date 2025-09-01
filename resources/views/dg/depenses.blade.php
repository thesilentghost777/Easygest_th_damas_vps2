@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile CP Balance -->
                <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500 mb-6 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-blue-800 font-semibold">
                                {{ $isFrench ? 'Solde CP actuel' : 'Current CP Balance' }}
                            </h3>
                            <p class="text-2xl font-bold text-{{ $soldeCp && $soldeCp->montant > 0 ? 'blue' : 'red' }}-600">
                                {{ $soldeCp ? number_format($soldeCp->montant, 0, ',', ' ') : '0' }} FCFA
                            </p>
                        </div>
                        <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Mobile Total -->
                <div class="bg-green-50 rounded-2xl p-4 border-l-4 border-green-500 mb-6 animate-slide-in-right">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-green-800 font-semibold">
                                {{ $isFrench ? 'Total dépenses' : 'Total expenses' }}
                            </h3>
                            <p class="text-xl font-bold text-green-600">
                                {{ number_format($totalDepenses, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Mobile Statistics Summary -->
                <div class="space-y-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $isFrench ? 'Dépenses par type' : 'Expenses by type' }}
                    </h3>
                    @foreach($statsByType as $stat)
                        <div class="bg-white border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-900">
                                    @switch($stat->type)
                                        @case('achat_matiere')
                                            {{ $isFrench ? 'Achat de matière' : 'Material purchase' }}
                                            @break
                                        @case('livraison_matiere')
                                            {{ $isFrench ? 'Livraison de matière' : 'Material delivery' }}
                                            @break
                                        @case('reparation')
                                            {{ $isFrench ? 'Réparation' : 'Repair' }}
                                            @break
                                        @case('depense_fiscale')
                                            {{ $isFrench ? 'Dépense fiscale' : 'Tax expense' }}
                                            @break
                                        @default
                                            {{ $isFrench ? 'Autre' : 'Other' }}
                                    @endswitch
                                </span>
                                <span class="font-bold text-blue-600">{{ number_format($stat->total, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = $totalDepenses > 0 ? ($stat->total / $totalDepenses * 100) : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Recent Expenses -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $isFrench ? 'Dépenses récentes' : 'Recent expenses' }}
                    </h3>
                    @forelse($depenses->take(5) as $depense)
                        <div class="bg-white border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $depense->nom }}</h4>
                                    <p class="text-sm text-gray-600">{{ $depense->auteurRelation->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $depense->date->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</p>
                                    @switch($depense->type)
                                        @case('achat_matiere')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                {{ $isFrench ? 'Achat' : 'Purchase' }}
                                            </span>
                                            @break
                                        @case('livraison_matiere')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                {{ $isFrench ? 'Livraison' : 'Delivery' }}
                                            </span>
                                            @break
                                        @case('reparation')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                {{ $isFrench ? 'Réparation' : 'Repair' }}
                                            </span>
                                            @break
                                        @case('depense_fiscale')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                {{ $isFrench ? 'Fiscale' : 'Tax' }}
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                {{ $isFrench ? 'Autre' : 'Other' }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                            @if($depense->matiere)
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Matière:' : 'Material:' }} {{ $depense->matiere->nom }}</p>
                            @endif
                            <div class="mt-2">
                                @if($depense->valider)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ $isFrench ? 'Validé' : 'Validated' }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 rounded-2xl p-8 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucune dépense' : 'No expenses' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $isFrench ? 'Aucune dépense trouvée pour cette période.' : 'No expenses found for this period.' }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @include('buttons')
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">
                                {{ $isFrench ? 'Gestion des Dépenses des CP' : 'CP Expense Management' }}
                            </h2>
                            <div class="flex items-center">
                                <span class="text-lg font-semibold mr-2">
                                    {{ $isFrench ? 'Solde CP actuel:' : 'Current CP balance:' }}
                                </span>
                                <span class="text-xl font-bold text-{{ $soldeCp && $soldeCp->montant > 0 ? 'green' : 'red' }}-600">
                                    {{ $soldeCp ? number_format($soldeCp->montant, 0, ',', ' ') : '0' }} FCFA
                                </span>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="bg-gray-100 p-4 rounded-lg mb-6">
                            <form action="{{ route('dg.depenses') }}" method="GET" class="flex flex-wrap gap-4">
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Date début' : 'Start date' }}
                                    </label>
                                    <input type="date" name="date_debut" value="{{ $dateDebut }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Date fin' : 'End date' }}
                                    </label>
                                    <input type="date" name="date_fin" value="{{ $dateFin }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Type' : 'Type' }}
                                    </label>
                                    <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">{{ $isFrench ? 'Tous les types' : 'All types' }}</option>
                                        <option value="achat_matiere" {{ $type == 'achat_matiere' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Achat de matière' : 'Material purchase' }}
                                        </option>
                                        <option value="livraison_matiere" {{ $type == 'livraison_matiere' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Livraison de matière' : 'Material delivery' }}
                                        </option>
                                        <option value="reparation" {{ $type == 'reparation' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Réparation' : 'Repair' }}
                                        </option>
                                        <option value="depense_fiscale" {{ $type == 'depense_fiscale' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Dépense fiscale' : 'Tax expense' }}
                                        </option>
                                        <option value="autre" {{ $type == 'autre' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Autre' : 'Other' }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">CP</label>
                                    <select name="cp" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">{{ $isFrench ? 'Tous les CP' : 'All CPs' }}</option>
                                        @foreach ($cps as $cpUser)
                                            <option value="{{ $cpUser->id }}" {{ $cp == $cpUser->id ? 'selected' : '' }}>
                                                {{ $cpUser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="flex items-end w-full md:w-auto">
                                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Statistics -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Stats by type -->
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-3">
                                    {{ $isFrench ? 'Dépenses par type' : 'Expenses by type' }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach ($statsByType as $stat)
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium">
                                                @switch($stat->type)
                                                    @case('achat_matiere')
                                                        {{ $isFrench ? 'Achat de matière' : 'Material purchase' }}
                                                        @break
                                                    @case('livraison_matiere')
                                                        {{ $isFrench ? 'Livraison de matière' : 'Material delivery' }}
                                                        @break
                                                    @case('reparation')
                                                        {{ $isFrench ? 'Réparation' : 'Repair' }}
                                                        @break
                                                    @case('depense_fiscale')
                                                        {{ $isFrench ? 'Dépense fiscale' : 'Tax expense' }}
                                                        @break
                                                    @default
                                                        {{ $isFrench ? 'Autre' : 'Other' }}
                                                @endswitch
                                            </span>
                                            <span class="font-bold">{{ number_format($stat->total, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            @php
                                                $percentage = $totalDepenses > 0 ? ($stat->total / $totalDepenses * 100) : 0;
                                            @endphp
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Stats by CP -->
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-3">
                                    {{ $isFrench ? 'Dépenses par CP' : 'Expenses by CP' }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach ($statsByCp as $stat)
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium">
                                                {{ $stat->auteurRelation->name ?? ($isFrench ? 'CP inconnu' : 'Unknown CP') }}
                                            </span>
                                            <span class="font-bold">{{ number_format($stat->total, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            @php
                                                $percentage = $totalDepenses > 0 ? ($stat->total / $totalDepenses * 100) : 0;
                                            @endphp
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-gray-100 p-4 rounded-lg mb-6 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">
                                {{ $isFrench ? 'Total des dépenses sur la période:' : 'Total expenses for the period:' }}
                            </h3>
                            <span class="text-xl font-bold text-blue-600">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</span>
                        </div>

                        <!-- Expense list -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                        <th class="py-3 px-4 text-left">CP</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Nom' : 'Name' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Type' : 'Type' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Matière' : 'Material' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($depenses as $depense)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $depense->date->format('d/m/Y') }}</td>
                                            <td class="py-3 px-4">{{ $depense->auteurRelation->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $depense->nom }}</td>
                                            <td class="py-3 px-4">
                                                @switch($depense->type)
                                                    @case('achat_matiere')
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                            {{ $isFrench ? 'Achat de matière' : 'Material purchase' }}
                                                        </span>
                                                        @break
                                                    @case('livraison_matiere')
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                            {{ $isFrench ? 'Livraison' : 'Delivery' }}
                                                        </span>
                                                        @break
                                                    @case('reparation')
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                            {{ $isFrench ? 'Réparation' : 'Repair' }}
                                                        </span>
                                                        @break
                                                    @case('depense_fiscale')
                                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                            {{ $isFrench ? 'Dépense fiscale' : 'Tax expense' }}
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            {{ $isFrench ? 'Autre' : 'Other' }}
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-4">{{ $depense->matiere->nom ?? 'N/A' }}</td>
                                            <td class="py-3 px-4 font-semibold">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</td>
                                            <td class="py-3 px-4">
                                                @if($depense->valider)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                        {{ $isFrench ? 'Validé' : 'Validated' }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if ($depenses->isEmpty())
                                        <tr>
                                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                                {{ $isFrench ? 'Aucune dépense trouvée' : 'No expenses found' }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $depenses->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
