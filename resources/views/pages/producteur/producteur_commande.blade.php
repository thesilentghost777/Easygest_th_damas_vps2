@extends('pages.producteur.pdefault')

@section('page-content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 lg:p-6 rounded-xl shadow-lg">
            <h4 class="text-lg lg:text-xl font-bold uppercase tracking-wider">
                {{ $isFrench ? 'Informations producteur' : 'Producer Information' }}
            </h4>
            <p class="mt-2 text-sm lg:text-base">{{ $isFrench ? 'Nom:' : 'Name:' }} {{ $nom }}</p>
            <p class="mt-1 text-sm lg:text-base">{{ $isFrench ? 'Secteur:' : 'Sector:' }} {{ $secteur }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-6 space-y-3 lg:space-y-0">
            <h2 class="text-xl lg:text-2xl font-bold text-blue-800 flex items-center">
                <i class="mdi mdi-package-variant mr-2"></i>
                {{ $isFrench ? 'Liste des Commandes' : 'Orders List' }}
            </h2>
        </div>

        @if(count($commandes) > 0)
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-800 text-white">
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">{{ $isFrench ? 'Libellé' : 'Label' }}</th>
                            <th class="p-4 text-left">{{ $isFrench ? 'Date de commande' : 'Order Date' }}</th>
                            <th class="p-4 text-left">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="p-4 text-left">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                            <tr class="hover:bg-blue-50 transition duration-300 border-b border-gray-200">
                                <td class="p-4 font-bold text-blue-800">{{ $commande->id }}</td>
                                <td class="p-4">{{ $commande->libelle }}</td>
                                <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</td>
                                <td class="p-4">
                                    @php
                                        $p = \App\Models\Produit_fixes::where('code_produit', $commande->produit)->first();
                                    @endphp
                                    @if($commande->produit)
                                        {{ $p->nom ?? 'N/A' }} - {{ $p->prix }}
                                    @else
                                        {{ $isFrench ? 'Non spécifié' : 'Not specified' }}
                                    @endif
                                </td>
                                <td class="p-4">{{ $commande->quantite }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-4">
                @foreach($commandes as $commande)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm mobile-card">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-blue-800 mb-1">#{{ $commande->id }}</div>
                                <div class="text-lg font-medium text-gray-900">{{ $commande->libelle }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">{{ $isFrench ? 'Produit:' : 'Product:' }}</span>
                                <span class="text-sm font-medium">
                                    @if($commande->produit)
                                        {{ \App\Models\Produit_fixes::where('code_produit', $commande->produit)->first()->nom ?? 'N/A' }}
                                    @else
                                        {{ $isFrench ? 'Non spécifié' : 'Not specified' }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                <span class="text-sm font-semibold text-gray-800">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                                <span class="text-lg font-bold text-blue-600">{{ $commande->quantite }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="mdi mdi-package-variant-closed text-6xl text-gray-300 mb-4"></i>
                <p class="text-lg text-gray-500">
                    {{ $isFrench ? 'Aucune commande trouvée pour votre secteur.' : 'No orders found for your sector.' }}
                </p>
            </div>
        @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.mobile-card, tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                console.log('{{ $isFrench ? "Commande sélectionnée" : "Order selected" }}:', row.querySelector('td, .text-sm').textContent);
            });
        });
    });
</script>
@endsection
