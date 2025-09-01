@extends('rapports.layout.rapport')

@section('content')
<style>
    /* Mobile-first responsive styles */
    .mobile-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
        animation: slideInUp 0.6s ease-out;
    }

    .mobile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        animation: fadeInUp 0.8s ease-out;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .balance-positive {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .balance-negative {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .balance-neutral {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .summary-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #7dd3fc;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        animation: slideIn 0.8s ease-out;
    }

    .category-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
    }

    .category-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateX(5px);
    }

    .transaction-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
    }

    .transaction-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateX(5px);
    }

    .income-bg {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-left-color: #10b981;
    }

    .expense-bg {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-left-color: #ef4444;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .stat-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .mobile-card {
            padding: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .category-item,
        .transaction-item {
            padding: 0.75rem;
        }
        
        .table-responsive {
            display: none;
        }
        
        .mobile-table {
            display: block;
        }
        
        .summary-section {
            padding: 1rem;
            margin: 1rem 0;
        }
    }

    @media (min-width: 769px) {
        .stat-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        
        .mobile-table {
            display: none;
        }
        
        .table-responsive {
            display: block;
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

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
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
</style>


<!-- Header with global statistics -->
<div class="mb-8">
    <div class="stat-grid" style="display: grid;">
        <!-- Total revenues -->
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 class="stat-label" style="color: white;">{{ $isFrench ? 'Total des revenus' : 'Total Revenue' }}</h3>
            <p class="stat-value">{{ number_format($totalRevenus, 2, ',', ' ') }} XAF</p>
            <p style="font-size: 0.85rem; opacity: 0.8;">{{ $isFrench ? 'Pour le mois de' : 'For the month of' }} {{ $currentMonthName }}</p>
        </div>

        <!-- Total expenses -->
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <h3 class="stat-label"  style="color: white;">{{ $isFrench ? 'Total des dépenses' : 'Total Expenses' }}</h3>
            <p class="stat-value">{{ number_format($totalDepenses, 2, ',', ' ') }} XAF</p>
            <p style="font-size: 0.85rem; opacity: 0.8;">{{ $isFrench ? 'Pour le mois de' : 'For the month of' }} {{ $currentMonthName }}</p>
        </div>

        <!-- Balance -->
        <div class="stat-card {{ $balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
            <h3 class="stat-label"  style="color: white;">{{ $isFrench ? 'Balance' : 'Balance' }}</h3>
            <p class="stat-value">{{ number_format($balance, 2, ',', ' ') }} XAF</p>
            @if(isset($evolution))
            <p style="font-size: 0.85rem; opacity: 0.8;">
                {{ $evolution >= 0 ? '+' : '' }}{{ number_format($evolution, 2, ',', ' ') }}% {{ $isFrench ? 'vs mois précédent' : 'vs previous month' }}
            </p>
            @endif
        </div>
    </div>
</div>

<!-- Narrative summary -->
<div class="summary-section">
    <h3 style="color: #0369a1; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        🔹 {{ $isFrench ? 'Résumé des transactions' : 'Transaction Summary' }}
    </h3>
    <p style="color: #0c4a6e; line-height: 1.7;">
        {{ $isFrench ? 'Au cours du mois de' : 'During the month of' }} <strong>{{ $currentMonthName }}</strong>, {{ $isFrench ? 'l\'entreprise a enregistré des revenus totalisant' : 'the company recorded revenues totaling' }}
        <strong>{{ number_format($totalRevenus, 2, ',', ' ') }} XAF</strong> {{ $isFrench ? 'et des dépenses s\'élevant à' : 'and expenses amounting to' }}
        <strong>{{ number_format($totalDepenses, 2, ',', ' ') }} XAF</strong>.

        {{ $isFrench ? 'La balance mensuelle est de' : 'The monthly balance is' }} <strong>{{ number_format($balance, 2, ',', ' ') }} XAF</strong>
        @if(isset($evolution))
            , {{ $isFrench ? 'ce qui représente une' : 'which represents a' }} {{ $evolution >= 0 ? ($isFrench ? 'augmentation' : 'increase') : ($isFrench ? 'diminution' : 'decrease') }} {{ $isFrench ? 'de' : 'of' }}
            <strong>{{ abs($evolution) }}%</strong> {{ $isFrench ? 'par rapport au mois précédent' : 'compared to the previous month' }}.
        @endif

        @if($balance > 0)
            {{ $isFrench ? 'Ce résultat positif reflète une bonne gestion financière et une activité commerciale performante.' : 'This positive result reflects good financial management and strong business activity.' }}
        @elseif($balance == 0)
            {{ $isFrench ? 'Ce résultat équilibré montre une gestion stable des finances de l\'entreprise.' : 'This balanced result shows stable management of the company\'s finances.' }}
        @else
            {{ $isFrench ? 'Ce résultat négatif nécessite une attention particulière pour améliorer l\'équilibre financier dans les mois à venir.' : 'This negative result requires special attention to improve the financial balance in the coming months.' }}
        @endif
    </p>
</div>

<!-- Category breakdown -->
@if(isset($transactionsParCategorie) && count($transactionsParCategorie) > 0)
<div class="mb-8">
    <h3 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        📊 {{ $isFrench ? 'Répartition par catégorie' : 'Breakdown by Category' }}
    </h3>
    
    <!-- Desktop table -->
    <div class="table-responsive">
        <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr>
                        <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: right; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Type' : 'Type' }}</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: right; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactionsParCategorie as $transaction)
                    <tr style="transition: background-color 0.3s ease;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                            {{ $transaction->name }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; text-align: right; border-bottom: 1px solid #f1f5f9; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                            {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; text-align: right; border-bottom: 1px solid #f1f5f9; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                            {{ number_format($transaction->total, 2, ',', ' ') }} XAF
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile cards -->
    <div class="mobile-table">
        @foreach($transactionsParCategorie as $transaction)
        <div class="category-item {{ $transaction->type === 'income' ? 'income-bg' : 'expense-bg' }}">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-weight: 600; color: #1f2937;">{{ $transaction->name }}</span>
                <span style="font-size: 1.1rem; font-weight: 700; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                    {{ number_format($transaction->total, 2, ',', ' ') }} XAF
                </span>
            </div>
            <div style="font-size: 0.875rem; color: {{ $transaction->type === 'income' ? '#065f46' : '#991b1b' }};">
                <strong>{{ $isFrench ? 'Type' : 'Type' }}:</strong> {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Transaction details -->
<div>
    <h3 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        📋 {{ $isFrench ? 'Détail des transactions' : 'Transaction Details' }}
    </h3>
    
    <!-- Desktop table -->
    <div class="table-responsive">
        <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr>
                        <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Date</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Description</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: right; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Type' : 'Type' }}</th>
                        <th style="padding: 1rem; background: #f8fafc; text-align: right; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr style="transition: background-color 0.3s ease;">
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                            {{ $transaction->description }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                            {{ $transaction->category->name }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; text-align: right; border-bottom: 1px solid #f1f5f9; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                            {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; text-align: right; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }}; border-bottom: 1px solid #f1f5f9;">
                            {{ number_format($transaction->amount, 2, ',', ' ') }} XAF
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile cards -->
    <div class="mobile-table">
        @foreach($transactions as $transaction)
        <div class="transaction-item">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-weight: 600; color: #1f2937;">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</span>
                <span style="font-size: 1.1rem; font-weight: 700; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                    {{ number_format($transaction->amount, 2, ',', ' ') }} XAF
                </span>
            </div>
            <div style="margin-bottom: 0.5rem;">
                <div style="font-size: 0.9rem; color: #374151; margin-bottom: 0.25rem;">
                    <strong>Description:</strong> {{ $transaction->description }}
                </div>
                <div style="font-size: 0.9rem; color: #374151; margin-bottom: 0.25rem;">
                    <strong>{{ $isFrench ? 'Catégorie' : 'Category' }}:</strong> {{ $transaction->category->name }}
                </div>
                <div style="font-size: 0.9rem; color: {{ $transaction->type === 'income' ? '#10b981' : '#ef4444' }};">
                    <strong>{{ $isFrench ? 'Type' : 'Type' }}:</strong> {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
