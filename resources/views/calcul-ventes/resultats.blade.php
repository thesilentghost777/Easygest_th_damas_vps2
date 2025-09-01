@extends('layouts.app')

@section('title', ($isFrench ?? true) ? 'Résultats des Ventes' : 'Sales Results')

@push('styles')
<style>
    .results-container {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 25%, #059669 75%, #047857 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }
    
    .results-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><circle fill="%23ffffff" fill-opacity="0.1" cx="30" cy="30" r="30"/></g></svg>') repeat;
        opacity: 0.1;
    }
    
    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
    }
    
    .card-modern {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.3);
        border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transition: all 0.4s ease;
        transform: scale(0);
    }
    
    .stats-card:hover::before {
        transform: scale(1);
    }
    
    .stats-card:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4);
    }
    
    .stats-card.success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    }
    
    .stats-card.success:hover {
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.4);
    }
    
    .stats-card.info {
        background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);
    }
    
    .stats-card.info:hover {
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.4);
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 4rem;
        color: white;
        position: relative;
    }
    
    .page-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        background: linear-gradient(135deg, #ffffff 0%, #e0f2fe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .page-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        font-weight: 400;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .section-title {
        color: #1e40af;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 2rem;
        position: relative;
        padding-left: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
        border-radius: 3px;
    }
    
    .table-modern {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border: none;
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        color: white;
    }
    
    .table-modern th {
        padding: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        border: none;
        position: relative;
    }
    
    .table-modern th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%);
    }
    
    .table-modern td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        transition: all 0.3s ease;
    }
    
    .table-modern tbody tr {
        transition: all 0.3s ease;
    }
    
    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .badge-modern {
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: inline-block;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: white;
    }
    
    .badge-info {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);
        color: white;
    }
    
    .amount {
        font-weight: 700;
        font-size: 1.15rem;
    }
    
    .pagination-modern {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination-modern .page-item {
        margin: 0 0.3rem;
    }
    
    .pagination-modern .page-link {
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        color: #1e40af;
        background: white;
        transition: all 0.3s ease;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        display: block;
    }
    
    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
    
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }
    
    .pagination-modern .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-modern {
        padding: 1rem 2rem;
        border-radius: 30px;
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #1e40af 0%, #10b981 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(30, 64, 175, 0.4);
    }
    
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.8rem;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .avatar {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .avatar:hover {
        transform: scale(1.1);
    }

    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .stats-card {
            margin-bottom: 1.5rem;
        }
        
        .table-modern {
            font-size: 0.9rem;
        }
        
        .table-modern th,
        .table-modern td {
            padding: 1rem;
        }
        
        .container-custom {
            padding: 0 0.5rem;
        }
    }

    /* Styles d'impression */
    @media print {
        .results-container { 
            background: white !important; 
            padding: 1rem 0 !important;
        }
        .page-header { 
            color: #333 !important; 
        }
        .card-modern { 
            box-shadow: none !important; 
            border: 1px solid #ddd !important; 
        }
        .stats-card { 
            background: #f8f9fa !important; 
            color: #333 !important; 
        }
        .pagination-modern { 
            display: none !important; 
        }
        .btn-modern { 
            display: none !important; 
        }
    }
</style>
@endpush

@section('content')
<div class="results-container">
    <div class="container-custom">
        <!-- En-tête -->
        <div class="page-header">
            <h1 class="page-title">
                {{ ($isFrench ?? true) ? 'Résultats des Ventes' : 'Sales Results' }}
            </h1>
            <p class="page-subtitle">
                {{ (isset($nomMois) && isset($mois) && isset($annee)) ? $nomMois[$mois] . ' ' . $annee : (($isFrench ?? true) ? 'Période non définie' : 'Period undefined') }}
            </p>
        </div>

        <!-- Statistiques globales -->
        <div class="mb-20">
            <div class="w-full">
                <h2 class="section-title">
                    {{ ($isFrench ?? true) ? 'Statistiques Globales' : 'Global Statistics' }}
                </h2>
            </div>
            @php
                $totalTransactions = 0;
                $totalQuantite = 0;
                $totalChiffre = 0;
                $colors = ['success', 'info', 'warning'];
                $colorIndex = 0;
                $statsCollection = $stats ?? collect();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($statsCollection as $stat)
                    @php
                        $totalTransactions += $stat->nombre_transactions ?? 0;
                        $totalQuantite += $stat->quantite_totale ?? 0;
                        $totalChiffre += $stat->chiffre_affaire ?? 0;
                    @endphp
                    
                    <div class="stats-card {{ $colors[$colorIndex % 3] }} animate-fade-in">
                        <div class="icon-circle">
                            @if(($stat->type ?? '') == 'Vente')
                                <i class="fas fa-shopping-cart"></i>
                            @elseif(($stat->type ?? '') == 'Achat')
                                <i class="fas fa-shopping-bag"></i>
                            @else
                                <i class="fas fa-exchange-alt"></i>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold mb-4">{{ $stat->type ?? (($isFrench ?? true) ? 'Non défini' : 'Undefined') }}</h3>
                        <div class="grid grid-cols-3 gap-4 text-center mt-6">
                            <div>
                                <div class="text-2xl font-bold mb-1">{{ number_format($stat->nombre_transactions ?? 0) }}</div>
                                <small class="text-sm opacity-80">{{ ($isFrench ?? true) ? 'Transactions' : 'Transactions' }}</small>
                            </div>
                            <div>
                                <div class="text-2xl font-bold mb-1">{{ number_format($stat->quantite_totale ?? 0) }}</div>
                                <small class="text-sm opacity-80">{{ ($isFrench ?? true) ? 'Quantité' : 'Quantity' }}</small>
                            </div>
                            <div>
                                <div class="text-2xl font-bold mb-1">{{ number_format($stat->chiffre_affaire ?? 0, 2) }} FCFA</div>
                                <small class="text-sm opacity-80">{{ ($isFrench ?? true) ? 'C.A.' : 'Revenue' }}</small>
                            </div>
                        </div>
                    </div>
                    @php $colorIndex++; @endphp
                @empty
                    <div class="col-span-full">
                        <div class="card-modern p-8 animate-fade-in">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-chart-line text-6xl mb-6" style="color: #3b82f6;"></i>
                                <p class="text-lg">{{ ($isFrench ?? true) ? 'Aucune statistique disponible' : 'No statistics available' }}</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Résumé total -->
            @if($totalTransactions > 0 || $totalQuantite > 0 || $totalChiffre > 0)
            <div class="mt-8">
                <div class="card-modern p-8 animate-fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                        <div>
                            <h3 class="text-3xl font-bold mb-2" style="color: #3b82f6;">{{ number_format($totalTransactions) }}</h3>
                            <p class="text-gray-600 mb-0">{{ ($isFrench ?? true) ? 'Total Transactions' : 'Total Transactions' }}</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold mb-2" style="color: #10b981;">{{ number_format($totalQuantite) }}</h3>
                            <p class="text-gray-600 mb-0">{{ ($isFrench ?? true) ? 'Quantité Totale' : 'Total Quantity' }}</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold mb-2" style="color: #06b6d4;">{{ number_format($totalChiffre, 2) }} FCFA</h3>
                            <p class="text-gray-600 mb-0">{{ ($isFrench ?? true) ? 'Chiffre d\'Affaires Total' : 'Total Revenue' }}</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold mb-2" style="color: #1e40af;">{{ $totalQuantite > 0 ? number_format($totalChiffre / $totalQuantite, 2) : '0' }} FCFA</h3>
                            <p class="text-gray-600 mb-0">{{ ($isFrench ?? true) ? 'Prix Moyen' : 'Average Price' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Ventes par vendeur -->
        @if(isset($ventesParVendeur) && $ventesParVendeur->count() > 0)
        <div class="mb-20">
            <div class="w-full">
                <h2 class="section-title">
                    {{ ($isFrench ?? true) ? 'Performance par Vendeur' : 'Performance by Seller' }}
                </h2>
                <div class="card-modern animate-fade-in">
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ ($isFrench ?? true) ? 'Vendeur' : 'Seller' }}</th>
                                    <th class="text-center">{{ ($isFrench ?? true) ? 'Nb Ventes' : 'Sales Count' }}</th>
                                    <th class="text-center">{{ ($isFrench ?? true) ? 'Quantité' : 'Quantity' }}</th>
                                    <th class="text-right">{{ ($isFrench ?? true) ? 'Chiffre d\'Affaires' : 'Revenue' }}</th>
                                    <th class="text-center">{{ ($isFrench ?? true) ? 'Moy./Vente' : 'Avg/Sale' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventesParVendeur->sortByDesc('chiffre_affaire') as $vente)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="avatar mr-4 w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg" style="background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);">
                                                {{ substr(optional($vente->vendeur)->name ?? 'N/A', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ optional($vente->vendeur)->name ?? (($isFrench ?? true) ? 'Vendeur Inconnu' : 'Unknown Seller') }}</div>
                                                <small class="text-gray-500">ID: {{ $vente->serveur ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-modern badge-success">{{ number_format($vente->nombre_ventes ?? 0) }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($vente->quantite_vendue ?? 0) }}</td>
                                    <td class="text-right">
                                        <span class="amount" style="color: #10b981;">{{ number_format($vente->chiffre_affaire ?? 0, 2) }} FCFA</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-semibold" style="color: #06b6d4;">{{ ($vente->nombre_ventes ?? 0) > 0 ? number_format(($vente->chiffre_affaire ?? 0) / $vente->nombre_ventes, 2) : '0' }} FCFA</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Transactions récentes -->
        <div>
            <div class="w-full">
                <h2 class="section-title">
                    {{ ($isFrench ?? true) ? 'Transactions Récentes' : 'Recent Transactions' }}
                </h2>
                <div class="card-modern animate-fade-in">
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ ($isFrench ?? true) ? 'Date' : 'Date' }}</th>
                                    <th class="text-left">{{ ($isFrench ?? true) ? 'Type' : 'Type' }}</th>
                                    <th class="text-left">{{ ($isFrench ?? true) ? 'Produit' : 'Product' }}</th>
                                    <th class="text-left">{{ ($isFrench ?? true) ? 'Vendeur' : 'Seller' }}</th>
                                    <th class="text-center">{{ ($isFrench ?? true) ? 'Qté' : 'Qty' }}</th>
                                    <th class="text-center">{{ ($isFrench ?? true) ? 'Prix Unit.' : 'Unit Price' }}</th>
                                    <th class="text-right">{{ ($isFrench ?? true) ? 'Total' : 'Total' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactionsRecentes ?? [] as $transaction)
                                <tr>
                                    <td>
                                        <div class="font-bold">{{ \Carbon\Carbon::parse($transaction->date_vente ?? now())->format('d/m/Y') }}</div>
                                        <small class="text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at ?? now())->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if(($transaction->type ?? '') == 'Vente')
                                            <span class="badge-modern badge-success">{{ ($isFrench ?? true) ? 'Vente' : 'Sale' }}</span>
                                        @elseif(($transaction->type ?? '') == 'Achat')
                                            <span class="badge-modern badge-info">{{ ($isFrench ?? true) ? 'Achat' : 'Purchase' }}</span>
                                        @else
                                            <span class="badge-modern badge-warning">{{ $transaction->type ?? (($isFrench ?? true) ? 'Non défini' : 'Undefined') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-bold">{{ optional($transaction->Produit_fixes)->nom ?? (($isFrench ?? true) ? 'Produit Inconnu' : 'Unknown Product') }}</div>
                                        @if(optional($transaction->Produit_fixes)->description ?? false)
                                            <small class="text-gray-500">{{ Str::limit($transaction->Produit_fixes->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(optional($transaction->vendeur)->name ?? false)
                                            <div class="flex items-center">
                                                <div class="avatar mr-3 w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                                    {{ substr($transaction->vendeur->name, 0, 2) }}
                                                </div>
                                                <span>{{ $transaction->vendeur->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-500">{{ ($isFrench ?? true) ? 'Non défini' : 'Undefined' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ number_format($transaction->quantite ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($transaction->prix ?? 0, 2) }} FCFA</td>
                                    <td class="text-right">
                                        <span class="amount" style="color: {{ ($transaction->type ?? '') == 'Vente' ? '#10b981' : '#3b82f6' }};">
                                            {{ number_format(($transaction->quantite ?? 0) * ($transaction->prix ?? 0), 2) }} FCFA
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <div class="text-gray-500">
                                            <i class="fas fa-inbox text-6xl mb-6" style="color: #3b82f6;"></i>
                                            <p class="text-lg">{{ ($isFrench ?? true) ? 'Aucune transaction trouvée pour cette période' : 'No transactions found for this period' }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($transactionsRecentes) && method_exists($transactionsRecentes, 'hasPages') && $transactionsRecentes->hasPages())
                    <div class="flex justify-center mt-8 pb-8">
                        <nav>
                            <ul class="pagination-modern">
                                @if ($transactionsRecentes->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">{{ ($isFrench ?? true) ? 'Précédent' : 'Previous' }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transactionsRecentes->previousPageUrl() }}">{{ ($isFrench ?? true) ? 'Précédent' : 'Previous' }}</a>
                                    </li>
                                @endif

                                @foreach ($transactionsRecentes->getUrlRange(1, $transactionsRecentes->lastPage()) as $page => $url)
                                    @if ($page == $transactionsRecentes->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($transactionsRecentes->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transactionsRecentes->nextPageUrl() }}">{{ ($isFrench ?? true) ? 'Suivant' : 'Next' }}</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">{{ ($isFrench ?? true) ? 'Suivant' : 'Next' }}</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="mt-20">
            <div class="w-full text-center">
                @if(View::exists('buttons'))
                    @include('buttons')
                @endif
                <button class="btn-modern btn-primary-modern" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i>
                    {{ ($isFrench ?? true) ? 'Imprimer' : 'Print' }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée pour les cartes
    const cards = document.querySelectorAll('.card-modern, .stats-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation des nombres
    const numbers = document.querySelectorAll('.text-2xl, .text-3xl, .amount');
    const observerOptions = {
        threshold: 0.7
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateNumber(entry.target);
            }
        });
    }, observerOptions);

    numbers.forEach(number => {
        observer.observe(number);
    });

    function animateNumber(element) {
        const text = element.textContent.replace(/[FCFA,\s]/g, '');
        const finalNumber = parseFloat(text) || 0;
        
        if (finalNumber === 0) return;
        
        const duration = 1000;
        const steps = 30;
        const increment = finalNumber / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= finalNumber) {
                current = finalNumber;
                clearInterval(timer);
            }
            
            let formattedNumber = Math.floor(current).toLocaleString('fr-FR');
            if (element.textContent.includes('FCFA')) {
                if (element.textContent.includes('.')) {
                    formattedNumber = current.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                }
                formattedNumber += ' FCFA';
            }
            
            element.textContent = formattedNumber;
        }, duration / steps);
    }
});
</script>
@endpush