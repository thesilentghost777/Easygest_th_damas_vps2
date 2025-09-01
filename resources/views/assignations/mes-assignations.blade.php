@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 lg:p-6 rounded-xl shadow-lg">
            <h1 class="text-lg lg:text-xl font-bold uppercase tracking-wider">
                {{ $isFrench ? 'Mes assignations de matières' : 'My Material Assignments' }}
            </h1>
            <p class="mt-2 text-sm lg:text-base text-blue-200">
                {{ $isFrench ? 'Liste des matières premières qui vous ont été assignées' : 'List of raw materials assigned to you' }}
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg animate-slide-in" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Mobile Cards -->
    <div class="lg:hidden space-y-4 mb-6">
        @forelse($assignations as $assignation)
            <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-200 mobile-card animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $assignation->matiere->nom }}</h3>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $isFrench ? 'Assigné le' : 'Assigned on' }} {{ $assignation->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                        $daysRemaining = now()->diffInDays($assignation->date_limite_utilisation, false);
                        $statusClass = $daysRemaining > 5 ? 'text-green-600' : ($daysRemaining > 0 ? 'text-yellow-600' : 'text-red-600');
                        @endphp
                        <span class="{{ $statusClass }} text-xs font-medium">
                            @if($daysRemaining < 0)
                                {{ $isFrench ? 'Expirée' : 'Expired' }}
                            @elseif($daysRemaining == 0)
                                {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                            @else
                                {{ round($daysRemaining,0) }} {{ $isFrench ? 'jours' : 'days' }}
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">{{ $isFrench ? 'Quantité assignée:' : 'Assigned quantity:' }}</span>
                        <span class="text-sm font-medium">{{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">{{ $isFrench ? 'Quantité restante:' : 'Remaining quantity:' }}</span>
                        <span class="text-lg font-bold text-blue-600">{{ round($assignation->quantite_restante,1) }} {{ $assignation->unite_assignee }}</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                            $percentage = ($assignation->quantite_restante / $assignation->quantite_assignee) * 100;
                            $colorClass = $percentage > 50 ? 'bg-green-500' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Date limite:' : 'Deadline:' }}</span>
                            <span class="text-sm {{ $statusClass }}">
                                {{ $assignation->date_limite_utilisation ? $assignation->date_limite_utilisation->format('d/m/Y') : ($isFrench ? 'Non définie' : 'Not defined') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-blue-50 rounded-xl p-6 text-center border border-blue-200">
                <i class="mdi mdi-package-variant-closed text-4xl text-blue-400 mb-3"></i>
                <p class="text-blue-700">
                    {{ $isFrench ? 'Aucune assignation de matière n\'a été trouvée pour vous.' : 'No material assignments found for you.' }}
                </p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white shadow-lg rounded-xl overflow-hidden animate-fade-in">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $isFrench ? 'Assignations de matières' : 'Material Assignments' }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Matière' : 'Material' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Quantité assignée' : 'Assigned Quantity' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Quantité restante' : 'Remaining Quantity' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Date limite' : 'Deadline' }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ $isFrench ? 'Date d\'assignation' : 'Assignment Date' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assignations as $assignation)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $assignation->matiere->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ round($assignation->quantite_restante,1) }} {{ $assignation->unite_assignee }}
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    @php
                                    $percentage = ($assignation->quantite_restante / $assignation->quantite_assignee) * 100;
                                    $colorClass = $percentage > 50 ? 'bg-green-600' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                    @endphp
                                    <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($assignation->date_limite_utilisation)
                                        @php
                                        $daysRemaining = now()->diffInDays($assignation->date_limite_utilisation, false);
                                        $statusClass = $daysRemaining > 5 ? 'text-green-600' : ($daysRemaining > 0 ? 'text-yellow-600' : 'text-red-600');
                                        @endphp
                                        <span class="{{ $statusClass }}">
                                            {{ $assignation->date_limite_utilisation->format('d/m/Y') }}
                                            @if($daysRemaining < 0)
                                                ({{ $isFrench ? 'Expirée' : 'Expired' }})
                                            @elseif($daysRemaining == 0)
                                                ({{ $isFrench ? 'Aujourd\'hui' : 'Today' }})
                                            @else
                                                ({{ round($daysRemaining,0) }} {{ $isFrench ? 'jours restants' : 'days remaining' }})
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-500">{{ $isFrench ? 'Non définie' : 'Not defined' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $assignation->created_at->format('d/m/Y') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $isFrench ? 'Aucune assignation de matière n\'a été trouvée pour vous.' : 'No material assignments found for you.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reservations Section -->
    <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="bg-gray-50 border-b border-gray-200 p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800">
                {{ $isFrench ? 'Mes demandes de réservation' : 'My Reservation Requests' }}
            </h2>
        </div>

        <!-- Mobile Cards for Reservations -->
        <div class="lg:hidden p-4 space-y-4">
            @forelse($reservations as $reservation)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mobile-card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $reservation->matiere->nom }}</h3>
                            <div class="text-xs text-gray-500">{{ $reservation->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="text-right">
                            @if($reservation->statut === 'en_attente')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $isFrench ? 'En attente' : 'Pending' }}
                                </span>
                            @elseif($reservation->statut === 'approuvee')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $isFrench ? 'Approuvée' : 'Approved' }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $isFrench ? 'Refusée' : 'Rejected' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <span class="text-sm font-medium">{{ round($reservation->quantite_demandee,1) }} {{ $reservation->unite_demandee }}</span>
                        </div>
                        @if($reservation->commentaire)
                            <div class="pt-2 border-t border-gray-200">
                                <span class="text-xs text-gray-600">{{ $isFrench ? 'Commentaire:' : 'Comment:' }}</span>
                                <p class="text-sm text-gray-900 mt-1">{{ $reservation->commentaire }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-blue-50 rounded-xl p-6 text-center border border-blue-200">
                    <i class="mdi mdi-clipboard-text-outline text-4xl text-blue-400 mb-3"></i>
                    <p class="text-blue-700">
                        {{ $isFrench ? 'Vous n\'avez pas encore fait de demande de réservation.' : 'You haven\'t made any reservation requests yet.' }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table for Reservations -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Matière' : 'Material' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Date de demande' : 'Request Date' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Commentaire' : 'Comment' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->matiere->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ round($reservation->quantite_demandee,1) }} {{ $reservation->unite_demandee }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reservation->statut === 'en_attente')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                    </span>
                                @elseif($reservation->statut === 'approuvee')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $isFrench ? 'Approuvée' : 'Approved' }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $isFrench ? 'Refusée' : 'Rejected' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $reservation->commentaire ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $isFrench ? 'Vous n\'avez pas encore fait de demande de réservation.' : 'You haven\'t made any reservation requests yet.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, .mobile-card {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
