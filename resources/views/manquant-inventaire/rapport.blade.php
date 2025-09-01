@extends('layouts.app')

@section('content')
<style>
    /* Mobile-first responsive styles */
    .mobile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        animation: slideInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .mobile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ef4444, #dc2626);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .mobile-card:hover::before {
        transform: scaleX(1);
    }

    .header-icon {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
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
        transform: scale(1.1) rotate(-5deg);
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
        gap: 0.5rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
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

    .print-btn {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .print-btn:hover {
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .summary-card {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 2px solid #f59e0b;
        animation: pulse 2s ease-in-out infinite;
    }

    .summary-value {
        font-size: 2rem;
        font-weight: 800;
        color: #92400e;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .manquant-item {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #ef4444;
        transition: all 0.3s ease;
    }

    .manquant-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);
    }

    .recommendation-card {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #3b82f6;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .mobile-card {
            margin: 0.5rem;
            padding: 1.5rem;
            border-radius: 20px;
        }
        
        .header-icon {
            width: 4rem;
            height: 4rem;
        }
        
        .action-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            border-radius: 12px;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .page-title {
            text-align: center;
            font-size: 1.25rem;
            color: #ef4444;
            margin-bottom: 0.5rem;
        }
        
        .summary-card {
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
        }
        
        .summary-value {
            font-size: 1.75rem;
        }
        
        .manquant-item {
            border-radius: 16px;
            padding: 1.25rem;
        }
        
        .recommendation-card {
            border-radius: 16px;
            padding: 1.25rem;
        }
        
        .desktop-table {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .actions-grid {
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
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .desktop-table {
            display: block;
        }
        
        .mobile-cards {
            display: none;
        }
        
        .actions-grid {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .mobile-card {
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #3b82f6;
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

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-red-50">
    <div class="container">
        <div class="no-print">
            @include('buttons')
        </div>
        
        <div class="print-header">
            <div class="header-icon no-print">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.18 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h1 class="page-title font-bold text-gray-800">
                {{ $isFrench ? 'Rapport des Manquants - Inventaire' : 'Shortage Report - Inventory' }}
            </h1>
            <p class="text-center text-gray-600 mb-6">
                {{ $isFrench ? 'Généré le' : 'Generated on' }} {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>

        <!-- Actions -->
        <div class="no-print mb-6">
            <div class="actions-grid">
               
                
                <a href="{{ route('manquant-inventaire.index') }}" class="action-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ $isFrench ? 'Nouveau Calcul' : 'New Calculation' }}
                </a>
            </div>
        </div>

        <!-- Summary -->
        @if(count($manquants) > 0)
        <div class="summary-card">
            <h2 class="text-xl font-bold text-amber-800 text-center mb-4">
                {{ $isFrench ? 'Résumé des Manquants' : 'Shortage Summary' }}
            </h2>
            <div class="summary-value">
                {{ number_format($totalManquants, 0) }} XAF
            </div>
            <p class="text-center text-amber-700 font-semibold">
                {{ $isFrench ? 'Valeur totale des manquants' : 'Total shortage value' }}
            </p>
            <p class="text-center text-amber-600 mt-2">
                {{ count($manquants) }} {{ $isFrench ? 'matière(s) en rupture' : 'material(s) out of stock' }}
            </p>
        </div>
        @endif

        <!-- Manquants Details -->
        <div class="mobile-card">
            @if(count($manquants) > 0)
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    {{ $isFrench ? 'Détail des Manquants' : 'Shortage Details' }}
                </h3>
                
                <!-- Desktop Table -->
                <div class="desktop-table overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Qté Attendue' : 'Expected Qty' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Qté Réelle' : 'Actual Qty' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Manquant' : 'Shortage' }}
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($manquants as $manquant)
                            <tr class="hover:bg-red-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $manquant['matiere']->nom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ number_format($manquant['quantite_attendue'], 2) }} 
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ number_format($manquant['quantite_reelle'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-red-600">
                                    {{ number_format($manquant['manquant'], 2) }} 
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-red-600">
                                    {{ number_format($manquant['valeur_manquant'], 0) }} XAF
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards">
                    @foreach($manquants as $manquant)
                    <div class="manquant-item">
                        <h4 class="font-bold text-red-800 text-lg mb-2">{{ $manquant['matiere']->nom }}</h4>
                        
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-center">
                                <p class="text-xs text-gray-600">{{ $isFrench ? 'Attendue' : 'Expected' }}</p>
                                <p class="font-semibold">{{ number_format($manquant['quantite_attendue'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600">{{ $isFrench ? 'Réelle' : 'Actual' }}</p>
                                <p class="font-semibold">{{ number_format($manquant['quantite_reelle'], 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-red-100 p-3 rounded-lg text-center">
                            <p class="text-xs text-red-600">{{ $isFrench ? 'Manquant' : 'Shortage' }}</p>
                            <p class="font-bold text-red-800 text-lg">
                                {{ number_format($manquant['manquant'], 2) }} {{ $manquant['matiere']->unite_minimale }}
                            </p>
                            <p class="text-red-700 font-semibold">
                                {{ number_format($manquant['valeur_manquant'], 0) }} XAF
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-green-800 mb-2">
                        {{ $isFrench ? 'Aucun Manquant Détecté!' : 'No Shortages Detected!' }}
                    </h3>
                    <p class="text-green-600">
                        {{ $isFrench ? 'Toutes les matières premières sont en stock suffisant.' : 'All raw materials are in sufficient stock.' }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Recommendations -->
        @if(count($recommendations) > 0)
        <div class="mobile-card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                {{ $isFrench ? 'Recommandations' : 'Recommendations' }}
            </h3>
            
            @foreach($recommendations as $recommendation)
            <div class="recommendation-card">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-blue-800">{{ $recommendation }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered entrance animations for mobile
    if (window.innerWidth <= 768) {
        const cards = document.querySelectorAll('.manquant-item');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateX(-30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateX(0)';
            }, 100 * index);
        });
    }
    
    // Add haptic feedback for mobile
    const interactiveElements = document.querySelectorAll('button, a');
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(30);
            }
        });
    });
});
</script>
@endsection