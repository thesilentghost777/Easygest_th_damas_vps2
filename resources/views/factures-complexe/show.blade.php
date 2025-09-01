@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <!-- Header responsive -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 animate-fade-in">
            {{ $isFrench ? 'Facture' : 'Invoice' }} #{{ $facture->reference }}
        </h1>
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="window.print()" class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-printer mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
            </button>
            @include('buttons')
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-4 lg:p-6 mb-4 lg:mb-6 animate-fade-in">
        <!-- Invoice information section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
            <!-- Invoice details -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mobile-card">
                <h2 class="text-lg font-semibold mb-3 text-gray-900 flex items-center">
                    <i class="mdi mdi-file-document mr-2 text-blue-600"></i>
                    {{ $isFrench ? 'Informations de la facture' : 'Invoice Information' }}
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Référence:' : 'Reference:' }}</span>
                        <span class="text-gray-900 font-semibold text-sm lg:text-base">{{ $facture->reference }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Date de création:' : 'Creation date:' }}</span>
                        <span class="text-gray-900 text-sm lg:text-base">{{ $facture->date_creation->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Statut:' : 'Status:' }}</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $facture->statut === 'validee' ? 'bg-green-100 text-green-800' :
                               ($facture->statut === 'annulee' ? 'bg-red-100 text-red-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ $facture->statut === 'en_attente' ? ($isFrench ? 'En attente' : 'Pending') :
                               ($facture->statut === 'validee' ? ($isFrench ? 'Validée' : 'Validated') : ($isFrench ? 'Annulée' : 'Cancelled')) }}
                        </span>
                    </div>
                    @if($facture->date_validation)
                    <div class="flex justify-between items-center py-2">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Date de validation:' : 'Validation date:' }}</span>
                        <span class="text-gray-900 text-sm lg:text-base">{{ $facture->date_validation->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Producer information -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mobile-card">
                <h2 class="text-lg font-semibold mb-3 text-gray-900 flex items-center">
                    <i class="mdi mdi-account mr-2 text-blue-600"></i>
                    {{ $isFrench ? 'Producteur' : 'Producer' }}
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Nom:' : 'Name:' }}</span>
                        <span class="text-gray-900 text-sm lg:text-base">{{ $facture->producteur->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">Email:</span>
                        <span class="text-gray-900 text-sm lg:text-base">{{ $facture->producteur->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="font-medium text-gray-700 text-sm lg:text-base">{{ $isFrench ? 'Rôle:' : 'Role:' }}</span>
                        <span class="text-gray-900 text-sm lg:text-base">{{ $facture->producteur->role }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($facture->notes)
        <div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200 mobile-card">
            <h2 class="text-lg font-semibold mb-3 text-gray-900 flex items-center">
                <i class="mdi mdi-note-text mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Notes' : 'Notes' }}
            </h2>
            <p class="text-gray-700 text-sm lg:text-base">{{ $facture->notes }}</p>
        </div>
        @endif

        <!-- Invoice details section -->
        <div class="mb-6">
            <h2 class="text-lg lg:text-xl font-semibold mb-4 text-gray-900 flex items-center">
                <i class="mdi mdi-format-list-bulleted mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Détails de la facture' : 'Invoice Details' }}
            </h2>

            <!-- Desktop table (hidden on mobile) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité unitaire' : 'Unit Quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Prix unitaire' : 'Unit Price' }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Montant' : 'Amount' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($facture->details as $detail)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $detail->matiere->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{ number_format($detail->quantite, 1, ',', ' ') }} {{ $detail->unite }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">
                                    {{ number_format(round($detail->quantite / $detail->matiere->quantite_par_unite), 0, ',', ' ') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{ number_format($detail->prix_unitaire, 1, ',', ' ') }} FCFA</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($detail->montant, 1, ',', ' ') }} FCFA</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right font-bold">
                                {{ $isFrench ? 'Total:' : 'Total:' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold">
                                {{ number_format($facture->montant_total, 2, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Mobile card view (visible only on mobile) -->
            <div class="lg:hidden space-y-4">
                @foreach ($facture->details as $detail)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm animate-fade-in transform hover:scale-105 transition-all duration-200">
                    <div class="mb-3">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">{{ $detail->matiere->nom }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Quantité' : 'Quantity' }}</div>
                            <div class="font-semibold text-gray-900">{{ number_format($detail->quantite, 0, ',', ' ') }} {{ $detail->unite }}</div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Qté unitaire' : 'Unit Qty' }}</div>
                            <div class="font-semibold text-gray-900">{{ number_format(round($detail->quantite / $detail->matiere->quantite_par_unite), 0, ',', ' ') }}</div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Prix unitaire' : 'Unit Price' }}</div>
                            <div class="font-semibold text-gray-900">{{ number_format($detail->prix_unitaire, 1, ',', ' ') }} FCFA</div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">{{ $isFrench ? 'Montant' : 'Amount' }}</div>
                            <div class="font-bold text-blue-600">{{ number_format($detail->montant, 1, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Mobile total -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                        <span class="text-xl font-bold text-blue-600">{{ number_format($facture->montant_total, 1, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($facture->statut === 'en_attente')
    <div class="flex flex-col sm:flex-row justify-end gap-3 lg:gap-4 animate-fade-in">
        <form action="{{ route('factures-complexe.validate', $facture->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir valider cette facture?' : 'Are you sure you want to validate this invoice?' }}')">
                <i class="mdi mdi-check-circle mr-2"></i>{{ $isFrench ? 'Valider la facture' : 'Validate Invoice' }}
            </button>
        </form>
       
        <form action="{{ route('factures-complexe.destroy', $facture->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto text-center bg-red-600 hover:bg-red-700 text-white px-6 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette facture?' : 'Are you sure you want to delete this invoice?' }}')">
                <i class="mdi mdi-delete mr-2"></i>{{ $isFrench ? 'Supprimer la facture' : 'Delete Invoice' }}
            </button>
        </form>
    </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .mobile-card {
        transition: all 0.2s ease-out;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, a {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }

        .container {
            width: 100%;
            max-width: none;
            padding: 10mm;
        }

        button, a {
            display: none !important;
        }

        .shadow-lg {
            box-shadow: none !important;
        }

        .rounded-xl {
            border-radius: 0 !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        th, td {
            border: 1px solid #ddd !important;
        }

        thead {
            display: table-header-group !important;
        }

        tfoot {
            display: table-footer-group !important;
        }
    }
</style>
@endsection
