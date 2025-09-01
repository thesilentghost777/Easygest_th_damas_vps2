@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Message d'avertissement pour mobile -->
    <div class="block md:hidden">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 text-center">
            <div class="mb-4">
                <svg class="w-16 h-16 text-orange-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-orange-800 mb-3">
                @if($isFrench)
                    Version Mobile Limitée
                @else
                    Limited Mobile Version
                @endif
            </h2>
            <p class="text-orange-700 mb-4">
                @if($isFrench)
                    Cette fonctionnalité de gestion des données de production n'est pas disponible sur mobile en raison de sa complexité.
                @else
                    This production data management feature is not available on mobile due to its complexity.
                @endif
            </p>
            <p class="text-orange-600 text-sm mb-4">
                @if($isFrench)
                    Pour accéder à cette fonctionnalité, veuillez utiliser un ordinateur de bureau ou une tablette avec un écran plus large.
                @else
                    To access this feature, please use a desktop computer or tablet with a larger screen.
                @endif
            </p>
            <div class="bg-white rounded-lg p-4 border border-orange-200">
                <p class="text-sm text-gray-600 mb-2">
                    @if($isFrench)
                        Fonctionnalités disponibles sur PC :
                    @else
                        Features available on PC:
                    @endif
                </p>
                <ul class="text-sm text-gray-700 space-y-1">
                    @if($isFrench)
                        <li>• Gestion des transactions de vente</li>
                        <li>• Modification des utilisations de matières</li>
                        <li>• Statistiques détaillées</li>
                    @else
                        <li>• Sales transaction management</li>
                        <li>• Raw material usage modification</li>
                        <li>• Detailed statistics</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Contenu principal pour desktop/tablette -->
    <div class="hidden md:block">
        <div class="bg-white rounded-lg shadow-md p-6">
            @include('buttons')
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                @if($isFrench)
                    Gestion des Données de Production
                @else
                    Production Data Management
                @endif
            </h1>
            <p class="text-gray-600 mb-8">
                @if($isFrench)
                    Choisissez le type de données que vous souhaitez modifier
                @else
                    Choose the type of data you want to modify
                @endif
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transactions de Vente / Sales Transactions -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-500 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $isFrench ? 'Transactions de Vente' : 'Sales Transactions' }}
                        </h2>
                    </div>
                    <p class="text-gray-600 mb-4">
                        {{ $isFrench 
                            ? 'Modifiez les transactions de vente, ajustez les quantités, prix et serveurs' 
                            : 'Modify sales transactions, adjust quantities, prices and servers' 
                        }}
                    </p>
                    <a href="{{ route('production.edit.ventes') }}" class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <span>{{ $isFrench ? 'Gérer les ventes' : 'Manage Sales' }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            
                <!-- Utilisation de Matières / Material Usage -->
                <div class="bg-green-50 rounded-lg border border-green-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-500 rounded-lg p-3 mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $isFrench ? 'Utilisation de Matières' : 'Material Usage' }}
                        </h2>
                    </div>
                    <p class="text-gray-600 mb-4">
                        {{ $isFrench 
                            ? 'Modifiez les données d\'utilisation des matières premières par lot de production' 
                            : 'Modify raw material usage data by production batch' 
                        }}
                    </p>
                    <a href="{{ route('production.edit.utilisations') }}" class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        <span>{{ $isFrench ? 'Gérer les utilisations' : 'Manage Usage' }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Statistiques rapides -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if($isFrench)
                        Statistiques Rapides
                    @else
                        Quick Statistics
                    @endif
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg p-4 border">
                        <div class="text-sm text-gray-600">
                            @if($isFrench)
                                Transactions cette semaine
                            @else
                                Transactions this week
                            @endif
                        </div>
                        <div class="text-2xl font-bold text-blue-600">
                            {{ DB::table('transaction_ventes')->whereBetween('date_vente', [Carbon\Carbon::now()->startOfWeek(), Carbon\Carbon::now()->endOfWeek()])->count() }}
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border">
                        <div class="text-sm text-gray-600">
                            @if($isFrench)
                                Utilisations cette semaine
                            @else
                                Usage this week
                            @endif
                        </div>
                        <div class="text-2xl font-bold text-green-600">
                            {{ DB::table('Utilisation')->whereBetween('created_at', [Carbon\Carbon::now()->startOfWeek(), Carbon\Carbon::now()->endOfWeek()])->count() }}
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border">
                        <div class="text-sm text-gray-600">
                            @if($isFrench)
                                Produits actifs
                            @else
                                Active products
                            @endif
                        </div>
                        <div class="text-2xl font-bold text-purple-600">
                            {{ DB::table('Produit_fixes')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection