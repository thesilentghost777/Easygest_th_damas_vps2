
@extends('layouts.app')

@section('content')
<style>
    /* Mobile-first responsive styles */
    .group-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        animation: slideInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .group-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .group-card:hover::before {
        transform: scaleX(1);
    }

    .header-icon {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-radius: 50%;
        width: 5rem;
        height: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        animation: bounceIn 0.8s ease-out;
        transition: transform 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    .product-card {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .product-card:hover {
        border-color: #22c55e;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.15);
    }

    .calculation-card {
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .calculation-card:hover {
        border-color: #a855f7;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(168, 85, 247, 0.15);
    }

    .action-btn {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .action-btn:hover::before {
        left: 100%;
    }

    .success-alert {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        color: #166534;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
        animation: slideInDown 0.5s ease-out;
    }

    .error-alert {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        color: #991b1b;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
        animation: slideInDown 0.5s ease-out;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .group-card {
            margin: 0.5rem;
            padding: 1.5rem;
            border-radius: 20px;
        }
        
        .header-icon {
            width: 4rem;
            height: 4rem;
        }
        
        .page-title {
            text-align: center;
            font-size: 1.25rem;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }
        
        .stat-card {
            border-radius: 16px;
            padding: 1.25rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        .action-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
        }
        
        .product-card, .calculation-card {
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .card-content {
            margin-bottom: 1rem;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .icon-btn {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .icon-btn:hover {
            background: white;
            transform: scale(1.1);
        }
        
        .mobile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .mobile-grid-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    @media (min-width: 769px) {
        .container {
            max-width: 90rem;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }
        
        .desktop-table {
            display: block;
        }
        
        .mobile-cards {
            display: none;
        }
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Loading animation */
    .loading {
        position: relative;
    }

    .loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Touch feedback */
    @media (max-width: 768px) {
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        .action-btn:active, .icon-btn:active {
            transform: scale(0.95);
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <div class="container">
        @include('buttons')
        
        <div class="mb-6">
            <div class="header-icon">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h1 class="page-title font-bold text-gray-800">{{ $group->name }}</h1>
        </div>

        @if(session('success'))
            <div class="success-alert">
                <p class="text-center font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="error-alert">
                <p class="text-center font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Group Information -->
            <div class="group-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Informations du Groupe' : 'Group Information' }}
                    </h2>
                    <a href="{{ route('inventory.groups.edit', $group) }}" class="action-btn bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">
                            {{ $isFrench ? 'Description:' : 'Description:' }}
                        </p>
                        <p class="font-semibold text-blue-700">
                            {{ $group->description ?? ($isFrench ? 'Aucune description' : 'No description') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">
                            {{ $isFrench ? 'Créé le:' : 'Created on:' }}
                        </p>
                        <p class="font-semibold text-blue-700">{{ $group->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">
                            {{ $isFrench ? 'Dernière mise à jour:' : 'Last updated:' }}
                        </p>
                        <p class="font-semibold text-blue-700">{{ $group->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

        
        <!-- Products Section -->
        <div class="group-card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $isFrench ? 'Produits du Groupe' : 'Group Products' }}
                </h2>
                <a href="{{ route('inventory.products.create', $group) }}" class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $isFrench ? 'Ajouter un Produit' : 'Add Product' }}
                </a>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-8">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4 font-semibold">
                        {{ $isFrench ? 'Aucun produit n\'a encore été ajouté à ce groupe.' : 'No products have been added to this group yet.' }}
                    </p>
                    <a href="{{ route('inventory.products.create', $group) }}" class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                        {{ $isFrench ? 'Ajouter votre premier produit' : 'Add your first product' }}
                    </a>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="desktop-table overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Nom' : 'Name' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Type' : 'Type' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Prix' : 'Price' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $product->name }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $product->type }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ number_format($product->price, 0, ',', ' ') }} XAF</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('inventory.products.edit', $product) }}" class="text-amber-600 hover:text-amber-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce produit ?' : 'Are you sure you want to delete this product?' }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="card-title text-green-800">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->type }}</p>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('inventory.products.edit', $product) }}" class="icon-btn text-amber-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce produit ?' : 'Are you sure you want to delete this product?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="bg-white p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Prix' : 'Price' }}</p>
                            <p class="font-bold text-green-700 text-lg">{{ number_format($product->price, 0, ',', ' ') }} XAF</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Calculation Sessions -->
        <div class="group-card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $isFrench ? 'Sessions de Calcul de Manquants' : 'Missing Items Calculation Sessions' }}
                </h2>
                <a href="{{ route('inventory.calculations.create', $group) }}" class="action-btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    {{ $isFrench ? 'Nouvelle Session' : 'New Session' }}
                </a>
            </div>

            @if($calculations->isEmpty())
                <div class="text-center py-8">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4 font-semibold">
                        {{ $isFrench ? 'Aucune session de calcul n\'a encore été créée pour ce groupe.' : 'No calculation sessions have been created for this group yet.' }}
                    </p>
                    <a href="{{ route('inventory.calculations.create', $group) }}" class="action-btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700">
                        {{ $isFrench ? 'Créer votre première session de calcul' : 'Create your first calculation session' }}
                    </a>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="desktop-table overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Titre' : 'Title' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Date' : 'Date' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Statut' : 'Status' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Montant Total' : 'Total Amount' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($calculations as $calculation)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <a href="{{ route('inventory.calculations.show', $calculation) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $calculation->title }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $calculation->date->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $calculation->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $calculation->status === 'open' ? ($isFrench ? 'Ouvert' : 'Open') : ($isFrench ? 'Fermé' : 'Closed') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ number_format($calculation->total_amount, 0, ',', ' ') }} XAF</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('inventory.calculations.show', $calculation) }}" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            @if($calculation->status === 'open')
                                                <form action="{{ route('inventory.calculations.close', $calculation) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir fermer cette session de calcul ?' : 'Are you sure you want to close this calculation session?' }}');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards">
                    @foreach($calculations as $calculation)
                    <div class="calculation-card">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="card-title text-purple-800">
                                    <a href="{{ route('inventory.calculations.show', $calculation) }}" class="hover:text-purple-900">
                                        {{ $calculation->title }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600">{{ $calculation->date->format('d/m/Y') }}</p>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('inventory.calculations.show', $calculation) }}" class="icon-btn text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($calculation->status === 'open')
                                    <form action="{{ route('inventory.calculations.close', $calculation) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir fermer cette session de calcul ?' : 'Are you sure you want to close this calculation session?' }}');">
                                        @csrf
                                        <button type="submit" class="icon-btn text-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mobile-grid">
                            <div class="bg-white p-3 rounded-lg text-center">
                                <p class="text-xs text-gray-500">{{ $isFrench ? 'Statut' : 'Status' }}</p>
                                <p class="font-bold {{ $calculation->status === 'open' ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ $calculation->status === 'open' ? ($isFrench ? 'Ouvert' : 'Open') : ($isFrench ? 'Fermé' : 'Closed') }}
                                </p>
                            </div>
                            <div class="bg-white p-3 rounded-lg text-center">
                                <p class="text-xs text-gray-500">{{ $isFrench ? 'Montant' : 'Amount' }}</p>
                                <p class="font-bold text-purple-700">{{ number_format($calculation->total_amount, 0, ',', ' ') }} XAF</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered entrance animations for mobile
    if (window.innerWidth <= 768) {
        const cards = document.querySelectorAll('.group-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 + (index * 200));
        });

        // Animate product and calculation cards
        const productCards = document.querySelectorAll('.product-card, .calculation-card');
        productCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 800 + (index * 100));
        });
    }
    
    // Add haptic feedback for mobile
    const interactiveElements = document.querySelectorAll('a, button');
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(30);
            }
        });
    });

    // Enhanced button interactions
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        button.addEventListener('mouseup', function() {
            this.style.transform = '';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Smooth scrolling for mobile navigation
    if (window.innerWidth <= 768) {
        const hash = window.location.hash;
        if (hash) {
            const element = document.querySelector(hash);
            if (element) {
                setTimeout(() => {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 1000);
            }
        }
    }
});
</script>
@endsection
