@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')

    @if(in_array(auth()->user()->role, ['chef_production', 'dg', 'ddg', 'pdg']))
    <!-- Header responsive -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 animate-fade-in">
            {{ $isFrench ? 'Factures du Complexe' : 'Complex Invoices' }}
        </h1>
        <div class="flex flex-col sm:flex-row gap-3 lg:gap-4">
            <a href="{{ route('factures-complexe.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-plus-circle mr-2"></i>{{ $isFrench ? 'Créer une facture' : 'Create Invoice' }}
            </a>
            <a href="{{ route('factures-complexe.statistiques') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 lg:py-2 rounded-xl lg:rounded-md transition-all duration-200 transform hover:scale-105 active:scale-95 font-medium">
                <i class="mdi mdi-chart-bar mr-2"></i>{{ $isFrench ? 'Statistiques' : 'Statistics' }}
            </a>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 rounded-r-lg mb-4 shadow-md animate-slide-in" role="alert">
        <span class="block sm:inline font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 rounded-r-lg mb-4 shadow-md animate-slide-in" role="alert">
        <span class="block sm:inline font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden animate-fade-in">
        <!-- Desktop table (hidden on mobile) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Référence' : 'Reference' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Date de création' : 'Creation Date' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Montant total' : 'Total Amount' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Actions' : 'Actions' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($factures as $facture)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $facture->reference }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $facture->producteur->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $facture->date_creation->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ number_format($facture->montant_total, 2, ',', ' ') }} FCFA</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $facture->statut === 'validee' ? 'bg-green-100 text-green-800' :
                                   ($facture->statut === 'annulee' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ $facture->statut === 'en_attente' ? ($isFrench ? 'En attente' : 'Pending') :
                                   ($facture->statut === 'validee' ? ($isFrench ? 'Validée' : 'Validated') : ($isFrench ? 'Annulée' : 'Cancelled')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('factures-complexe.show', $facture->id) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $isFrench ? 'Voir' : 'View' }}
                            </a>
                            @if ($facture->statut === 'en_attente')
                            
                            <form action="{{ route('factures-complexe.destroy', $facture->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette facture?' : 'Are you sure you want to delete this invoice?' }}')">
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 whitespace-nowrap text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="mdi mdi-file-document-outline text-4xl text-gray-300 mb-2"></i>
                                <p>{{ $isFrench ? 'Aucune facture trouvée' : 'No invoices found' }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile card view (visible only on mobile) -->
        
        <div class="lg:hidden p-4">
         
            @forelse ($factures as $facture)
                <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-200 shadow-sm animate-fade-in transform hover:scale-105 transition-all duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900 mb-1">{{ $facture->reference }}</div>
                            <div class="text-xs text-blue-600 font-medium">{{ $facture->producteur->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $facture->date_creation->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-blue-600 mb-2">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $facture->statut === 'validee' ? 'bg-green-100 text-green-800' :
                                   ($facture->statut === 'annulee' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ $facture->statut === 'en_attente' ? ($isFrench ? 'En attente' : 'Pending') :
                                   ($facture->statut === 'validee' ? ($isFrench ? 'Validée' : 'Validated') : ($isFrench ? 'Annulée' : 'Cancelled')) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col space-y-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('factures-complexe.show', $facture->id) }}" 
                           class="w-full py-2 px-4 bg-blue-100 text-blue-700 rounded-lg text-center text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                            <i class="mdi mdi-eye mr-2"></i>{{ $isFrench ? 'Voir les détails' : 'View details' }}
                        </a>
                        
                        @if ($facture->statut === 'en_attente')
                        <div class="flex space-x-2">
                           
                            
                            <form action="{{ route('factures-complexe.destroy', $facture->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full py-2 px-4 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors duration-200"
                                        onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette facture?' : 'Are you sure you want to delete this invoice?' }}')">
                                    <i class="mdi mdi-delete mr-2"></i>{{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mdi mdi-file-document-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune facture trouvée' : 'No invoices found' }}</p>
                </div>
            @endforelse
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
</style>
@endsection
