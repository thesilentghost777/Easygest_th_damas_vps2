@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="animate-fade-in">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 lg:p-6">
                <h1 class="text-xl lg:text-2xl font-bold flex items-center">
                    <i class="mdi mdi-package-variant mr-3"></i>
                    {{ $isFrench ? 'Mes Matières Premières Assignées' : 'My Assigned Raw Materials' }}
                </h1>
            </div>

            @if($assignations->isEmpty())
                <div class="p-6 lg:p-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-alert-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm lg:text-base text-yellow-700">
                                    {{ $isFrench ? 'Aucune matière première ne vous a été assignée pour le moment.' : 'No raw materials have been assigned to you at the moment.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité Assignée' : 'Assigned Quantity' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité Restante' : 'Remaining Quantity' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Date Limite' : 'Deadline' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Statut' : 'Status' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assignations as $assignation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $assignation->matiere->nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ round($assignation->quantite_restante,1) }} {{ $assignation->unite_assignee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $assignation->date_limite_utilisation->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assignation->utilisee)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $isFrench ? 'Utilisée' : 'Used' }}
                                            </span>
                                        @else
                                            @if($assignation->date_limite_utilisation->isPast())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $isFrench ? 'Expirée' : 'Expired' }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $isFrench ? 'Disponible' : 'Available' }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="lg:hidden p-4 space-y-4">
                    @foreach($assignations as $assignation)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm mobile-card">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $assignation->matiere->nom }}</h3>
                                    <div class="text-xs text-gray-500">{{ $assignation->date_limite_utilisation->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    @if($assignation->utilisee)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Utilisée' : 'Used' }}
                                        </span>
                                    @else
                                        @if($assignation->date_limite_utilisation->isPast())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $isFrench ? 'Expirée' : 'Expired' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $isFrench ? 'Disponible' : 'Available' }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-600">{{ $isFrench ? 'Quantité assignée:' : 'Assigned quantity:' }}</span>
                                    <span class="text-sm font-medium">{{ round($assignation->quantite_assignee,1) }} {{ $assignation->unite_assignee }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Quantité restante:' : 'Remaining quantity:' }}</span>
                                    <span class="text-lg font-bold text-blue-600">{{ round($assignation->quantite_restante,1) }} {{ $assignation->unite_assignee }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        .mobile-card {
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
