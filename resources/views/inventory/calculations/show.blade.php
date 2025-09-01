@extends('layouts.app')

@section('content')
<style>
    /* Mobile-first responsive styles */
    .calculation-card {
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

    .calculation-card::before {
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

    .calculation-card:hover::before {
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

    .item-card {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .item-card:hover {
        border-color: #22c55e;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.15);
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

    .info-alert {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        color: #1e40af;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        animation: slideInDown 0.5s ease-out;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .calculation-card {
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
        
        .item-card {
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
        
        .mobile-form {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .mobile-input {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 1rem;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
        }
        
        .mobile-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: scale(1.02);
        }
        
        .mobile-select {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
        }
        
        .mobile-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .modal-mobile {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
        }
        
        .modal-content {
            border-radius: 24px;
            padding: 2rem;
            margin: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .desktop-table {
            display: block;
        }
        
        .mobile-cards {
            display: none;
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

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .calculation-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
        
        .page-title {
            color: #000 !important;
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <div class="container">
        @include('buttons')
        
        <div class="mb-6">
            <div class="header-icon">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h1 class="page-title font-bold text-gray-800">{{ $calculation->title }}</h1>
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

        @if(session('info'))
            <div class="info-alert">
                <p class="text-center font-semibold">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Session Information -->
            <div class="calculation-card md:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Informations de la Session' : 'Session Information' }}
                    </h2>
                    @if($calculation->status === 'open')
                        <form action="{{ route('inventory.calculations.close', $calculation) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir fermer cette session de calcul ?' : 'Are you sure you want to close this calculation session?' }}');">
                            @csrf
                            <button type="submit" class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ $isFrench ? 'Fermer la Session' : 'Close Session' }}
                            </button>
                        </form>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                            {{ $isFrench ? 'Session fermée' : 'Session closed' }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Groupe:' : 'Group:' }}</p>
                        <p class="font-semibold text-blue-700">{{ $group->name }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Date:' : 'Date:' }}</p>
                        <p class="font-semibold text-blue-700">{{ $calculation->date->format('d/m/Y') }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Statut:' : 'Status:' }}</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $calculation->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $calculation->status === 'open' ? ($isFrench ? 'Ouvert' : 'Open') : ($isFrench ? 'Fermé' : 'Closed') }}
                        </span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Créé le:' : 'Created on:' }}</p>
                        <p class="font-semibold text-blue-700">{{ $calculation->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="calculation-card">
                <h2 class="text-xl font-semibold text-blue-900 mb-4 text-center">
               {{ $isFrench ? 'Résumé' : 'Summary' }}
               </h2>
               <div class="stat-card bg-white border-2 border-blue-200 text-blue-900 mb-6 shadow-lg">
               <div class="stat-value">{{ $missingItems->count() }}</div>
               <div class="stat-label">
               {{ $isFrench ? 'Articles' : 'Items' }}
               </div>
               </div>
               <div class="stat-card bg-white border-2 border-blue-200 text-blue-900 mb-6 shadow-lg">
               <div class="stat-value text-blue-800">{{ number_format($calculation->total_amount, 0, ',', ' ') }}</div>
               <div class="stat-label text-blue-600">
               {{ $isFrench ? 'Montant Total (XAF)' : 'Total Amount (XAF)' }}
               </div>
               </div>
               <button onclick="printReport()" class="action-btn w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg transition-all duration-200">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
               </svg>
               {{ $isFrench ? 'Imprimer le Rapport' : 'Print Report' }}
               </button>
               </div>
        </div>

        <div id="report-content">
            <!-- Add missing items form -->
            @if($calculation->status === 'open')
                <div class="calculation-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                        {{ $isFrench ? 'Ajouter un Article Manquant' : 'Add Missing Item' }}
                    </h2>

                    @if($products->isEmpty())
                        <div class="text-center p-6 bg-yellow-50 rounded-xl">
                            <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <p class="text-yellow-700 font-semibold">
                                {{ $isFrench ? 'Tous les produits ont déjà été ajoutés à cette session.' : 'All products have already been added to this session.' }}
                            </p>
                        </div>
                    @else
                        <form action="{{ route('inventory.calculations.add-item', $calculation) }}" method="POST" class="mobile-form">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? 'Produit' : 'Product' }}
                                    </label>
                                    <select name="product_id" id="product_id" class="mobile-select" required>
                                        <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format($product->price, 0, ',', ' ') }} XAF)</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="expected_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? 'Quantité Attendue' : 'Expected Quantity' }}
                                    </label>
                                    <input type="number" name="expected_quantity" id="expected_quantity" min="0" class="mobile-input" required>
                                </div>

                                <div>
                                    <label for="actual_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $isFrench ? 'Quantité Réelle' : 'Actual Quantity' }}
                                    </label>
                                    <input type="number" name="actual_quantity" id="actual_quantity" min="0" class="mobile-input" required>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <button type="submit" class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ $isFrench ? 'Ajouter l\'Article' : 'Add Item' }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Missing items list -->
            <div class="calculation-card">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $isFrench ? 'Articles Manquants' : 'Missing Items' }}
                    </h2>
                </div>

                @if($missingItems->isEmpty())
                    <div class="text-center py-8">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-4 font-semibold">
                            {{ $isFrench ? 'Aucun article manquant n\'a encore été enregistré.' : 'No missing items have been recorded yet.' }}
                        </p>
                        @if($calculation->status === 'open')
                            <p class="text-gray-500 text-sm">
                                {{ $isFrench ? 'Utilisez le formulaire ci-dessus pour ajouter des articles.' : 'Use the form above to add items.' }}
                            </p>
                        @endif
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="desktop-table overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Type' : 'Type' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Prix Unitaire' : 'Unit Price' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Qté Attendue' : 'Expected Qty' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Qté Réelle' : 'Actual Qty' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Qté Manquante' : 'Missing Qty' }}</th>
                                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                    @if($calculation->status === 'open')
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($missingItems as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $item->product->name }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $item->product->type }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ number_format($item->product->price, 0, ',', ' ') }} XAF</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $item->expected_quantity }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $item->actual_quantity }}</td>
                                        <td class="py-3 px-4 text-sm font-medium {{ $item->missing_quantity > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $item->missing_quantity }}
                                        </td>
                                        <td class="py-3 px-4 text-sm font-medium {{ $item->amount > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ number_format($item->amount, 0, ',', ' ') }} XAF
                                        </td>
                                        @if($calculation->status === 'open')
                                            <td class="py-3 px-4 text-sm text-gray-700">
                                                <div class="flex space-x-2 no-print">
                                                    <button type="button" onclick="openEditModal({{ $item->id }}, {{ $item->expected_quantity }}, {{ $item->actual_quantity }})" class="text-amber-600 hover:text-amber-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('inventory.calculations.delete-item', $item) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet article ?' : 'Are you sure you want to delete this item?' }}');">
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
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="{{ $calculation->status === 'open' ? '6' : '5' }}" class="py-3 px-4 text-right text-sm text-gray-800">{{ $isFrench ? 'Total:' : 'Total:' }}</td>
                                    <td class="py-3 px-4 text-sm font-bold {{ $calculation->total_amount > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ number_format($calculation->total_amount, 0, ',', ' ') }} XAF
                                    </td>
                                    @if($calculation->status === 'open')
                                        <td></td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="mobile-cards">
                        @foreach($missingItems as $item)
                        <div class="item-card">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="card-title text-green-800">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->product->type }} - {{ number_format($item->product->price, 0, ',', ' ') }} XAF</p>
                                </div>
                                @if($calculation->status === 'open')
                                    <div class="card-actions">
                                        <button type="button" onclick="openEditModal({{ $item->id }}, {{ $item->expected_quantity }}, {{ $item->actual_quantity }})" class="icon-btn text-amber-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('inventory.calculations.delete-item', $item) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cet article ?' : 'Are you sure you want to delete this item?' }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mobile-grid mb-3">
                                <div class="bg-white p-3 rounded-lg text-center">
                                    <p class="text-xs text-gray-500">{{ $isFrench ? 'Attendue' : 'Expected' }}</p>
                                    <p class="font-bold text-blue-700">{{ $item->expected_quantity }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg text-center">
                                    <p class="text-xs text-gray-500">{{ $isFrench ? 'Réelle' : 'Actual' }}</p>
                                    <p class="font-bold text-green-700">{{ $item->actual_quantity }}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Manquant:' : 'Missing:' }}</p>
                                    <p class="font-bold {{ $item->missing_quantity > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $item->missing_quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Montant:' : 'Amount:' }}</p>
                                    <p class="font-bold {{ $item->amount > 0 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ number_format($item->amount, 0, ',', ' ') }} XAF
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 rounded-xl text-white text-center">
                            <p class="text-sm mb-1">{{ $isFrench ? 'Total des manquants:' : 'Total missing:' }}</p>
                            <p class="text-2xl font-bold">{{ number_format($calculation->total_amount, 0, ',', ' ') }} XAF</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50 no-print modal-mobile">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ $isFrench ? 'Modifier l\'Article' : 'Edit Item' }}
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="edit_expected_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Quantité Attendue' : 'Expected Quantity' }}
                </label>
                <input type="number" name="expected_quantity" id="edit_expected_quantity" min="0" class="mobile-input" required>
            </div>

            <div class="mb-6">
                <label for="edit_actual_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Quantité Réelle' : 'Actual Quantity' }}
                </label>
                <input type="number" name="actual_quantity" id="edit_actual_quantity" min="0" class="mobile-input" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="action-btn bg-gradient-to-r from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="submit" class="action-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700">
                    {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered entrance animations for mobile
    if (window.innerWidth <= 768) {
        const cards = document.querySelectorAll('.calculation-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 + (index * 200));
        });

        // Animate item cards
        const itemCards = document.querySelectorAll('.item-card');
        itemCards.forEach((card, index) => {
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
    const interactiveElements = document.querySelectorAll('a, button, input, select');
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
});

const isFrench = {{ $isFrench ? 'true' : 'false' }};

function openEditModal(itemId, expectedQuantity, actualQuantity) {
    if (navigator.vibrate) {
        navigator.vibrate(50);
    }
    document.getElementById('editForm').action = '/inventory/calculations/items/' + itemId;
    document.getElementById('edit_expected_quantity').value = expectedQuantity;
    document.getElementById('edit_actual_quantity').value = actualQuantity;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function printReport() {
    const printContents = document.getElementById('report-content').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <style>
            @media print {
                body {
                    font-family: 'Helvetica', 'Arial', sans-serif;
                    color: #333;
                }
                @page {
                    size: A4;
                    margin: 1cm;
                }
                button, .no-print {
                    display: none !important;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
            }
        </style>
        <div class="print-container">
            <h1 style="text-align: center; margin-bottom: 20px;">{{ $calculation->title }}</h1>
            <p style="text-align: center; margin-bottom: 20px;">${isFrench ? 'Date' : 'Date'}: {{ $calculation->date->format('d/m/Y') }}</p>
            <p style="text-align: center; margin-bottom: 20px;">${isFrench ? 'Groupe' : 'Group'}: {{ $group->name }}</p>
            ${printContents}
        </div>
    `;

    window.print();
    document.body.innerHTML = originalContents;
}
</script>
@endsection
