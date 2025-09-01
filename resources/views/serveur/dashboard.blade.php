@extends('pages.serveur.serveur_default')

@section('page-content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8 min-h-screen">
        <!-- Mobile Header -->
        <div class="md:hidden flex flex-col space-y-4 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 animate-fade-in">
                    {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                </h1>
                @include('buttons')
            </div>
            
            <!-- Mobile Action Buttons with animations -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('serveur.vente.create') }}" 
                   class="bg-green-500 text-white py-3 px-4 rounded-lg shadow-md hover:bg-green-600 transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $isFrench ? 'Nouvelle vente' : 'New sale' }}
                </a>
                <a href="{{ route('serveur.vente.liste') }}" 
                   class="bg-blue-500 text-white py-3 px-4 rounded-lg shadow-md hover:bg-blue-600 transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                    {{ $isFrench ? 'Ventes' : 'Sales' }}
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 animate-fade-in">{{ $isFrench ? 'Tableau de bord Vendeur' : 'Seller Dashboard' }}</h1>
            <div class="space-x-2">
                <a href="{{ route('serveur.vente.create') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-300 shadow-md">
                    {{ $isFrench ? 'Nouvelle vente' : 'New sale' }}
                </a>
                <a href="{{ route('serveur.vente.liste') }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transform hover:scale-105 transition-all duration-300 shadow-md">
                    {{ $isFrench ? 'Liste des ventes' : 'Sales list' }}
                </a>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Mobile Stats Cards with animations -->
            <div class="md:hidden space-y-4">
                <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-green-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">{{ $isFrench ? 'Ventes du jour' : 'Today\'s sales' }}</h2>
                    <div class="flex justify-between items-end">
                        <p class="text-3xl font-bold text-green-500">{{ $ventesJour->count() }}</p>
                        <p class="text-gray-600">{{ $isFrench ? 'Total' : 'Total' }}: {{ number_format($totalVentes, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-yellow-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up" style="animation-delay: 0.1s">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">{{ $isFrench ? 'Invendus du jour' : 'Today\'s unsold' }}</h2>
                    <div class="flex justify-between items-end">
                        <p class="text-3xl font-bold text-yellow-500">{{ $invendusJour->count() }}</p>
                        <p class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}: {{ $invendusJour->sum('quantite') }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-red-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up" style="animation-delay: 0.2s">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">{{ $isFrench ? 'Avariés du jour' : 'Today\'s damaged' }}</h2>
                    <div class="flex justify-between items-end">
                        <p class="text-3xl font-bold text-red-500">{{ $avariesJour->count() }}</p>
                        <p class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}: {{ $avariesJour->sum('quantite') }}</p>
                    </div>
                </div>
            </div>

            <!-- Desktop Stats Cards -->
            <div class="hidden md:block bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700">{{ $isFrench ? 'Ventes du jour' : 'Today\'s sales' }}</h2>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-green-500">{{ $ventesJour->count() }}</p>
                    <p class="text-gray-600">{{ $isFrench ? 'Total' : 'Total' }}: {{ number_format($totalVentes, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>

            <div class="hidden md:block bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700">{{ $isFrench ? 'Invendus du jour' : 'Today\'s unsold' }}</h2>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-yellow-500">{{ $invendusJour->count() }}</p>
                    <p class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}: {{ $invendusJour->sum('quantite') }}</p>
                </div>
            </div>

            <div class="hidden md:block bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500 transform hover:scale-[1.02] transition-all duration-300 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700">{{ $isFrench ? 'Avariés du jour' : 'Today\'s damaged' }}</h2>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-red-500">{{ $avariesJour->count() }}</p>
                    <p class="text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}: {{ $avariesJour->sum('quantite') }}</p>
                </div>
            </div>
        </div>

        <!-- Section additionnelle pour occuper l'espace -->
        <div class="hidden md:block">
            <!-- Graphiques ou informations supplémentaires -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $isFrench ? 'Aperçu rapide' : 'Quick Overview' }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $ventesJour->count() + $invendusJour->count() + $avariesJour->count() }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Total articles' : 'Total items' }}</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $ventesJour->count() > 0 ? round(($ventesJour->count() / ($ventesJour->count() + $invendusJour->count() + $avariesJour->count())) * 100, 1) : 0 }}%</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Taux de vente' : 'Sales rate' }}</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($totalVentes / ($ventesJour->count() ?: 1), 0, ',', ' ') }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Moyenne/vente' : 'Avg/sale' }} (FCFA)</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg">
                        <div class="text-2xl font-bold text-orange-600">{{ date('d/m/Y') }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Aujourd\'hui' : 'Today' }}</div>
                    </div>
                </div>
            </div>

            <!-- Section des actions rapides -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $isFrench ? 'Actions rapides' : 'Quick Actions' }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-300">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700">{{ $isFrench ? 'Nouvelle vente' : 'New Sale' }}</h4>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Enregistrer une vente' : 'Record a sale' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-300">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700">{{ $isFrench ? 'Voir rapports' : 'View Reports' }}</h4>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Consulter les statistiques' : 'Check statistics' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-300">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700">{{ $isFrench ? 'Historique' : 'History' }}</h4>
                                <p class="text-sm text-gray-500">{{ $isFrench ? 'Voir l\'historique' : 'View history' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out forwards;
    }
    
    /* Assurer que le body et html ont une couleur de fond */
    html, body {
        background-color: #f9fafb;
        min-height: 100vh;
    }
    
    /* Mobile specific styles */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .stats-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection