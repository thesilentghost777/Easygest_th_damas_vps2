@extends('pages.chef_production.chef_production_default')

@section('page-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white">
    
    <div class="container mx-auto px-4 pb-6">
        <!-- Success/Error Messages with Enhanced Mobile Styling -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-4 rounded-r-lg mb-4 shadow-sm animate-slideInDown">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-4 rounded-r-lg mb-4 shadow-sm animate-slideInDown">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Desktop Table View (Large screens only) -->
        <div class="hidden xl:block bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full table-auto">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                            @if($isFrench) Producteur @else Producer @endif
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                            @if($isFrench) Matière @else Material @endif
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                            @if($isFrench) Quantité @else Quantity @endif
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                            @if($isFrench) Stock Disponible @else Available Stock @endif
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">
                            @if($isFrench) Actions @else Actions @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ $reservation->producteur->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ $reservation->matiere->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ formatQuantity($reservation->quantite_demandee, $reservation->unite_demandee) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ formatStock($reservation->matiere->quantite, $reservation->matiere->quantite_par_unite, $reservation->matiere->unite_classique) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <button
                                    onclick="openValidationModal('{{ $reservation->id }}')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    @if($isFrench) Valider @else Validate @endif
                                </button>
                                <button
                                    onclick="openRefusalModal('{{ $reservation->id }}')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-sm">
                                    @if($isFrench) Refuser @else Refuse @endif
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    @if($isFrench) Aucune réservation en attente @else No pending reservations @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- iPad Optimized Table View -->
        <div class="hidden md:block xl:hidden bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                @if($isFrench) Producteur @else Producer @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                @if($isFrench) Matière @else Material @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                @if($isFrench) Qté @else Qty @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                @if($isFrench) Stock @else Stock @endif
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                @if($isFrench) Actions @else Actions @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                    {{ $reservation->producteur->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $reservation->matiere->nom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-semibold text-green-600">
                                    {{ formatQuantity($reservation->quantite_demandee, $reservation->unite_demandee) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <span class="text-orange-600 font-medium">
                                        {{ formatStock($reservation->matiere->quantite, $reservation->matiere->quantite_par_unite, $reservation->matiere->unite_classique) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center space-x-2">
                                        <button
                                            onclick="openValidationModal('{{ $reservation->id }}')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-200 transform hover:scale-105 shadow-sm flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>@if($isFrench) Valider @else Validate @endif</span>
                                        </button>
                                        <button
                                            onclick="openRefusalModal('{{ $reservation->id }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-200 transform hover:scale-105 shadow-sm flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>@if($isFrench) Refuser @else Refuse @endif</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        @if($isFrench) Aucune réservation en attente @else No pending reservations @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse($reservations as $index => $reservation)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl animate-slideInUp" style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold text-lg">{{ $reservation->producteur->name }}</h3>
                                    <p class="text-blue-100 text-sm">
                                        @if($isFrench) Producteur @else Producer @endif
                                    </p>
                                </div>
                            </div>
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4 space-y-4">
                        <!-- Material Info -->
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl">
                            <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 font-medium">
                                    @if($isFrench) Matière @else Material @endif
                                </p>
                                <p class="text-gray-900 font-semibold">{{ $reservation->matiere->nom }}</p>
                            </div>
                        </div>

                        <!-- Quantity Info -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-green-50 rounded-xl">
                                <p class="text-sm text-gray-600 font-medium mb-1">
                                    @if($isFrench) Quantité demandée @else Requested Quantity @endif
                                </p>
                                <p class="text-green-700 font-bold text-lg">
                                    {{ formatQuantity($reservation->quantite_demandee, $reservation->unite_demandee) }}
                                </p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-xl">
                                <p class="text-sm text-gray-600 font-medium mb-1">
                                    @if($isFrench) Stock disponible @else Available Stock @endif
                                </p>
                                <p class="text-orange-700 font-bold text-sm">
                                    {{ formatStock($reservation->matiere->quantite, $reservation->matiere->quantite_par_unite, $reservation->matiere->unite_classique) }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-2">
                            <button
                                onclick="openValidationModal('{{ $reservation->id }}')"
                                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>@if($isFrench) Valider @else Validate @endif</span>
                            </button>
                            <button
                                onclick="openRefusalModal('{{ $reservation->id }}')"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>@if($isFrench) Refuser @else Refuse @endif</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fadeIn">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">
                        @if($isFrench) Aucune réservation @else No Reservations @endif
                    </h3>
                    <p class="text-gray-500">
                        @if($isFrench) Il n'y a aucune réservation en attente pour le moment @else No pending reservations at the moment @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Enhanced Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 animate-fadeIn">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 animate-scaleIn">
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        @if($isFrench) Confirmer la validation @else Confirm Validation @endif
                    </h3>
                    <p class="text-gray-600">
                        @if($isFrench) Êtes-vous sûr de vouloir valider cette réservation ? @else Are you sure you want to validate this reservation? @endif
                    </p>
                </div>
                
                <form id="validationForm" method="POST">
                    @csrf
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95">
                            @if($isFrench) Confirmer @else Confirm @endif
                        </button>
                        <button type="button" onclick="closeValidationModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95">
                            @if($isFrench) Annuler @else Cancel @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Refusal Modal -->
<div id="refusalModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 animate-fadeIn">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 animate-scaleIn">
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        @if($isFrench) Motif du refus @else Refusal Reason @endif
                    </h3>
                </div>
                
                <form id="refusalForm" method="POST">
                    @csrf
                    <div class="mb-6">
                        <textarea
                            name="commentaire"
                            required
                            class="w-full px-4 py-3 text-gray-700 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            rows="4"
                            placeholder="@if($isFrench) Veuillez indiquer le motif du refus @else Please indicate the reason for refusal @endif"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95">
                            @if($isFrench) Refuser @else Refuse @endif
                        </button>
                        <button type="button" onclick="closeRefusalModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-200 transform active:scale-95">
                            @if($isFrench) Annuler @else Cancel @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideInUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        transform: scale(0.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-slideInDown {
    animation: slideInDown 0.5s ease-out;
}

.animate-slideInUp {
    animation: slideInUp 0.5s ease-out forwards;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-scaleIn {
    animation: scaleIn 0.3s ease-out;
}

/* Optimisation pour iPad en mode portrait et paysage */
@media (min-width: 768px) and (max-width: 1279px) {
    .container {
        max-width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Assurer que le tableau iPad soit bien visible */
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem 1rem;
    }
    
    /* Boutons plus compacts sur iPad */
    button {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
}

/* Optimisation spécifique pour iPad en mode paysage */
@media (min-width: 1024px) and (max-width: 1279px) and (orientation: landscape) {
    th, td {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    button {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
    }
}
</style>
@endsection

@php
/**
 * Formate une quantité avec l'unité appropriée
 * 
 * @param float $quantity La quantité à formater
 * @param string $unit L'unité d'origine
 * @return string La quantité formatée avec l'unité appropriée
 */
function formatQuantity($quantity, $unit) {
    $quantity = round($quantity, 2);
    $unit = strtolower($unit);
    
    switch ($unit) {
        case 'ml':
            if ($quantity >= 1000) {
                return round($quantity / 1000, 2) . ' L';
            }
            return $quantity . ' ml';
            
        case 'l':
            return $quantity . ' L';
            
        case 'g':
            if ($quantity >= 1000) {
                return round($quantity / 1000, 2) . ' kg';
            }
            return $quantity . ' g';
            
        case 'kg':
            return $quantity . ' kg';
            
        case 'cl':
            if ($quantity >= 100) {
                return round($quantity / 100, 2) . ' L';
            }
            return $quantity . ' cl';
            
        case 'dg':
            if ($quantity >= 100) {
                return round($quantity / 100, 2) . ' g';
            }
            return $quantity . ' dg';
            
        default:
            if (is_numeric($quantity) && $quantity == 1) {
                return $quantity . ' unité';
            }
            return $quantity . ' ' . $unit;
    }
}

/**
 * Formate l'affichage du stock disponible
 * 
 * @param float $quantity La quantité totale
 * @param float $quantityPerUnit La quantité par unité
 * @param string $unit L'unité de base
 * @return string Le stock formaté
 */
function formatStock($quantity, $quantityPerUnit, $unit) {
    $quantity = round($quantity, 2);
    $quantityPerUnit = round($quantityPerUnit, 2);
    $totalQuantity = $quantity * $quantityPerUnit;
    $unit = strtolower($unit);
    
    // Formatage de la quantité totale
    $formattedTotal = formatQuantity($totalQuantity, $unit);
    
    return "$quantity unités de $quantityPerUnit $unit ($formattedTotal)";
}
@endphp

<script>
function openValidationModal(reservationId) {
    const modal = document.getElementById('validationModal');
    const form = document.getElementById('validationForm');
    form.action = "{{ route('chef.reservations.valider', ['reservation' => ':id']) }}".replace(':id', reservationId);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeValidationModal() {
    const modal = document.getElementById('validationModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openRefusalModal(reservationId) {
    const modal = document.getElementById('refusalModal');
    const form = document.getElementById('refusalForm');
    form.action = "{{ route('chef.reservations.refuser', ['reservation' => ':id']) }}".replace(':id', reservationId);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRefusalModal() {
    const modal = document.getElementById('refusalModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals on backdrop click
document.getElementById('validationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeValidationModal();
    }
});

document.getElementById('refusalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRefusalModal();
    }
});

// Enhanced mobile interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to buttons on mobile
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('touchstart', function(e) {
            const ripple = document.createElement('div');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 300);
        });
    });
});
</script>

<style>
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-slideInDown {
    animation: slideInDown 0.3s ease-out;
}

.animate-slideInUp {
    animation: slideInUp 0.4s ease-out;
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

.animate-scaleIn {
    animation: scaleIn 0.2s ease-out;
}

.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    animation: ripple-animation 0.3s ease-out;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(2);
        opacity: 0;
    }
}

/* Enhanced mobile touch feedback */
@media (max-width: 768px) {
    button:active {
        transform: scale(0.98);
    }
    
    .card-hover:active {
        transform: scale(0.99);
    }
}

/* Smooth scrolling for mobile */
@media (max-width: 768px) {
    html {
        scroll-behavior: smooth;
    }
}
</style>