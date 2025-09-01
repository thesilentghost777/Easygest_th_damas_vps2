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
        color: white;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        animation: pulse 2s infinite;
    }

    .status-validated {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fbbf24;
    }

    .payment-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        animation: fadeIn 0.5s ease-out;
    }

    .payment-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateX(5px);
    }

    .summary-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #7dd3fc;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        animation: slideIn 0.8s ease-out;
    }

    .analysis-section {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
        animation: fadeInUp 1s ease-out;
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
        
        .payment-item {
            padding: 0.75rem;
        }
        
        .table-responsive {
            display: none;
        }
        
        .mobile-table {
            display: block;
        }
        
        .summary-section,
        .analysis-section {
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

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
</style>


<!-- Header with statistics -->
<div class="mb-8">
    <div class="stat-grid" style="display: grid;">
        <!-- Total payments -->
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <h3 class="stat-label" style="color: white;">{{ $isFrench ? 'Total des versements' : 'Total Payments' }}</h3>
            <p class="stat-value">{{ number_format($totalVersements, 0, ',', ' ') }} XAF</p>
            <p style="font-size: 0.85rem; opacity: 0.8;">{{ $isFrench ? 'Chefs de production' : 'the CEO' }}</p>
        </div>

        <!-- Number of operations -->
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
            <h3 class="stat-label" style="color: white;">{{ $isFrench ? 'Nombre d\'opérations' : 'Number of Operations' }}</h3>
            <p class="stat-value">{{ $nombreVersements }}</p>
            <p style="font-size: 0.85rem; opacity: 0.8;">{{ $isFrench ? 'Transactions enregistrées' : 'Recorded transactions' }}</p>
        </div>

        <!-- Monthly evolution -->
        <div class="stat-card" style="background: linear-gradient(135deg, {{ isset($evolution) && $evolution >= 0 ? '#10b981, #059669' : '#ef4444, #dc2626' }});">
            <h3 class="stat-label" style="color: white;">{{ $isFrench ? 'Évolution mensuelle' : 'Monthly Evolution' }}</h3>
            <p class="stat-value">
                @if(isset($evolution))
                    {{ $evolution >= 0 ? '+' : '' }}{{ number_format($evolution, 2, ',', ' ') }}%
                @else
                    N/A
                @endif
            </p>
            <p style="font-size: 0.85rem; opacity: 0.8;">{{ $isFrench ? 'Par rapport au mois précédent' : 'Compared to previous month' }}</p>
        </div>
    </div>
</div>

<!-- Summary section -->
<div class="summary-section">
    <h3 style="color: #0369a1; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        🔹 {{ $isFrench ? 'Résumé des versements' : 'Payments Summary' }}
    </h3>
    <p style="color: #0c4a6e; line-height: 1.7;">
        {{ $isFrench ? 'Au cours du mois de' : 'During the month of' }} <strong>{{ $currentMonthName }}</strong>, {{ $isFrench ? 'un total de' : 'a total of' }}
        <strong>{{ number_format($totalVersements, 0, ',', ' ') }} XAF</strong>
        {{ $isFrench ? 'a été versé au DG, réparti sur' : 'was paid to the CEO, distributed over' }} {{ $nombreVersements }} {{ $isFrench ? 'opérations' : 'operations' }}.
        
        @if(isset($evolution))
            {{ $isFrench ? 'Ces versements représentent une' : 'These payments represent a' }} {{ $evolution >= 0 ? ($isFrench ? 'augmentation' : 'increase') : ($isFrench ? 'diminution' : 'decrease') }} {{ $isFrench ? 'de' : 'of' }}
            <strong>{{ abs($evolution) }}%</strong> {{ $isFrench ? 'par rapport au mois précédent' : 'compared to the previous month' }}.
        @endif

        @if(isset($versementsValides) && isset($versementsEnAttente))
            {{ $isFrench ? 'Sur les' : 'Of the' }} {{ $nombreVersements }} {{ $isFrench ? 'versements effectués,' : 'payments made,' }} {{ $versementsValides }} {{ $isFrench ? 'ont été validés définitivement et' : 'have been validated and' }}
            {{ $versementsEnAttente }} {{ $isFrench ? 'sont toujours en attente de validation' : 'are still awaiting validation' }}.
            {{ $isFrench ? 'Cette proportion de' : 'This proportion of' }}
            {{ $nombreVersements > 0 ? round(($versementsValides / $nombreVersements) * 100, 1) : 0 }}% {{ $isFrench ? 'de versements validés témoigne de la rigueur dans le processus de validation des paiements' : 'of validated payments demonstrates the rigor in the payment validation process' }}.
        @endif
    </p>
</div>

<!-- Analysis and recommendations -->
<div class="analysis-section">
    <h3 style="color: #065f46; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        📊 {{ $isFrench ? 'Analyse et recommandations' : 'Analysis and Recommendations' }}
    </h3>
    <div style="color: #047857; line-height: 1.7;">
        @if(isset($evolution))
            @if($evolution > 20)
                <p style="margin-bottom: 1rem;">
                    {{ $isFrench ? 'L\'augmentation significative des versements a DG ce mois-ci' : 'The significant increase in payments to the CEO this month' }} ({{ $evolution }}%)
                    {{ $isFrench ? 'peut être liée à une intensification de l\'activité de production ou à des primes exceptionnelles' : 'may be linked to intensified production activity or exceptional bonuses' }}.
                    {{ $isFrench ? 'Il est recommandé d\'analyser cette tendance en corrélation avec les performances de production pour s\'assurer de sa cohérence et de sa durabilité' : 'It is recommended to analyze this trend in correlation with production performance to ensure consistency and sustainability' }}.
                </p>
            @elseif($evolution < -20)
                <p style="margin-bottom: 1rem;">
                    {{ $isFrench ? 'La diminution notable des versements a DG ce mois-ci' : 'The notable decrease in payments to the CEO this month' }} ({{ abs($evolution) }}%)
                    {{ $isFrench ? 'pourrait indiquer une réduction de l\'activité de production ou une révision des modes de rémunération' : 'could indicate a reduction in production activity or a revision of compensation methods' }}.
                    {{ $isFrench ? 'Il convient de vérifier que cette baisse n\'impacte pas négativement la motivation des équipes et les performances de production' : 'It should be verified that this decrease does not negatively impact team motivation and production performance' }}.
                </p>
            @else
                <p style="margin-bottom: 1rem;">
                    {{ $isFrench ? 'Les versements a DG sont restés relativement stables par rapport au mois précédent, avec une variation de' : 'Payments to the CEO remained relatively stable compared to the previous month, with a variation of' }} {{ $evolution }}%.
                    {{ $isFrench ? 'Cette stabilité témoigne d\'une continuité dans l\'activité et la gestion des équipes de production' : 'This stability demonstrates continuity in activity and management of production teams' }}.
                </p>
            @endif
        @endif

        @if(isset($versementsEnAttente) && $versementsEnAttente > 0)
            <p>
                {{ $isFrench ? 'Il est important de traiter rapidement les' : 'It is important to quickly process the' }} {{ $versementsEnAttente }} {{ $isFrench ? 'versements en attente afin d\'éviter tout retard de paiement qui pourrait affecter la motivation des chefs de production' : 'pending payments to avoid any payment delays that could affect the motivation of the CEO' }}.
                {{ $isFrench ? 'Un suivi rigoureux des délais de validation est recommandé pour optimiser ce processus' : 'Rigorous monitoring of validation timelines is recommended to optimize this process' }}.
            </p>
        @endif
    </div>
</div>

<!-- Payment details -->
<div class="mt-8">
    <h3 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">
        📋 {{ $isFrench ? 'Détail des versements' : 'Payment Details' }}
    </h3>

    @if(isset($versements) && $versements->count() > 0)
        <!-- Desktop table -->
        <div class="table-responsive">
            <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr>
                            <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">Date</th>
                            <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Libellé' : 'Description' }}</th>
                            <th style="padding: 1rem; background: #f8fafc; text-align: left; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Verseur' : 'Payer' }}</th>
                            <th style="padding: 1rem; background: #f8fafc; text-align: right; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            <th style="padding: 1rem; background: #f8fafc; text-align: center; font-size: 0.875rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($versements as $versement)
                        <tr style="transition: background-color 0.3s ease;">
                            <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                                {{ $versement->date->format('d/m/Y') }}
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                                {{ $versement->libelle }}
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.875rem; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                                {{ $versement->verseur_name($versement->verseur) ?? ($isFrench ? 'Verseur inconnu' : 'Unknown payer') }}
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; text-align: right; color: #1f2937; border-bottom: 1px solid #f1f5f9;">
                                {{ number_format($versement->montant, 0, ',', ' ') }} XAF
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.875rem; text-align: center; border-bottom: 1px solid #f1f5f9;">
                                @if($versement->status)
                                    <span class="status-badge status-validated">
                                        {{ $isFrench ? 'Validé' : 'Validated' }}
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        {{ $isFrench ? 'En attente' : 'Pending' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile cards -->
        <div class="mobile-table">
            @foreach($versements as $versement)
            <div class="payment-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: #1f2937;">{{ $versement->date->format('d/m/Y') }}</span>
                    @if($versement->status)
                        <span class="status-badge status-validated">
                            {{ $isFrench ? 'Validé' : 'Validated' }}
                        </span>
                    @else
                        <span class="status-badge status-pending">
                            {{ $isFrench ? 'En attente' : 'Pending' }}
                        </span>
                    @endif
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <div style="font-size: 0.9rem; color: #374151; margin-bottom: 0.25rem;">
                        <strong>{{ $isFrench ? 'Libellé' : 'Description' }}:</strong> {{ $versement->libelle }}
                    </div>
                    <div style="font-size: 0.9rem; color: #374151; margin-bottom: 0.25rem;">
                        <strong>{{ $isFrench ? 'Verseur' : 'Payer' }}:</strong> {{ $versement->verseur_name($versement->verseur) ?? ($isFrench ? 'Verseur inconnu' : 'Unknown payer') }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: #3b82f6;">{{ number_format($versement->montant, 0, ',', ' ') }} XAF</span>
                </div>
            </div>
            @endforeach
        </div>

        <p style="margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">
            {{ $isFrench ? 'Le tableau ci-dessus présente la liste complète des versements a DG au cours du mois de' : 'The table above presents the complete list of payments to the CEO during the month of' }} {{ $currentMonthName }},
            {{ $isFrench ? 'avec la date, le libellé, le destinataire, le montant et le statut de validation de chaque opération' : 'with the date, description, recipient, amount and validation status of each operation' }}.
        </p>
    @else
        <div class="mobile-card">
            <p style="color: #6b7280; text-align: center; font-style: italic;">
                {{ $isFrench ? 'Aucun versement a DG n\'a été enregistré pendant le mois de' : 'No payments to the CEO were recorded during the month of' }} {{ $currentMonthName }}.
            </p>
        </div>
    @endif
</div>
@endsection
