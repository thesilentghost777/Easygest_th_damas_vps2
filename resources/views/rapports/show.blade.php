@extends('rapports.layout.rapport')

@section('content')
<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Rapport' : 'Report' }} - {{ $employee->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8fafc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            animation: slideDown 0.6s ease-out;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease-out;
        }

        .back-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .section {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #3b82f6;
            animation: fadeInUp 0.6s ease-out;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .section h2 {
            color: #3b82f6;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-icon {
            font-size: 1.2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 700;
            color: #1e293b;
            text-align: right;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin: 1rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.9rem;
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        tr:hover {
            background: #f8fafc;
        }

        .conclusion {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #7dd3fc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            animation: fadeIn 1s ease-out;
        }

        .conclusion h2 {
            color: #0369a1;
            margin-bottom: 1rem;
        }

        .conclusion p {
            color: #0c4a6e;
            line-height: 1.7;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            color: #64748b;
            font-size: 0.9rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header {
                padding: 1.5rem 1rem;
                margin-bottom: 1rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .header p {
                font-size: 0.95rem;
            }

            .section {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 8px;
            }

            .section h2 {
                font-size: 1.1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
                padding: 0.75rem;
            }

            .info-value {
                text-align: left;
                font-size: 1rem;
            }

            .table-container {
                font-size: 0.8rem;
            }

            th, td {
                padding: 0.5rem;
            }

            .conclusion {
                padding: 1rem;
                margin-top: 1rem;
            }

            .back-button {
                width: 45px;
                height: 45px;
                top: 15px;
                left: 15px;
            }
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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

        /* Print Styles */
        @media print {
            .back-button {
                display: none;
            }
            
            .section {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #e2e8f0;
            }
            
            .header {
                background: #3b82f6 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>


    <div class="container">
        <div class="header">
            <h1>{{ $isFrench ? 'Rapport d\'Employé' : 'Employee Report' }}</h1>
            <p>{{ $employee->name }} - {{ ucfirst($employee->role ?? ($isFrench ? 'Non défini' : 'Not defined')) }}</p>
            <p>{{ $isFrench ? 'Période' : 'Period' }}: {{ $month }} | {{ $isFrench ? 'Date du rapport' : 'Report date' }}: {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="section">
            <h2>
                <span class="section-icon">👤</span>
                {{ $isFrench ? 'Informations générales' : 'General Information' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Date de naissance' : 'Date of birth' }}:</span>
                    <span class="info-value">{{ $dateNaissance }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Âge' : 'Age' }}:</span>
                    <span class="info-value">{{ $age }} {{ $isFrench ? 'ans' : 'years' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Numéro de téléphone' : 'Phone number' }}:</span>
                    <span class="info-value">{{ $employee->num_tel ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $employee->email ?? ($isFrench ? 'Non spécifié' : 'Not specified') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Année de début de service' : 'Service start year' }}:</span>
                    <span class="info-value">{{ $employee->annee_debut_service ?? ($isFrench ? 'Non spécifiée' : 'Not specified') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Années de service' : 'Years of service' }}:</span>
                    <span class="info-value">{{ $anneeService }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Jours de présence ce mois' : 'Days present this month' }}:</span>
                    <span class="info-value">{{ $joursPresence }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Total d\'heures travaillées' : 'Total hours worked' }}:</span>
                    <span class="info-value">{{ $totalHeuresTravail }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>
                <span class="section-icon">💰</span>
                {{ $isFrench ? 'Salaire et finances' : 'Salary and finances' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Salaire mensuel' : 'Monthly salary' }}:</span>
                    <span class="info-value">{{ number_format($salaire, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Avance sur salaire' : 'Salary advance' }}:</span>
                    <span class="info-value">{{ number_format($avanceSalaire, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Total des primes' : 'Total bonuses' }}:</span>
                    <span class="info-value">{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</span>
                </div>
            </div>

            @if($acouper)
            <div class="info-item" style="margin-top: 1rem;">
                <span class="info-label">{{ $isFrench ? 'Montants à déduire' : 'Amounts to deduct' }}:</span>
                <div class="info-value">
                    @if($acouper->manquants > 0)
                        {{ $isFrench ? 'Manquants' : 'Missing' }}: {{ number_format($acouper->manquants, 0, ',', ' ') }} XAF
                    @endif
                    @if($acouper->remboursement > 0)
                        | {{ $isFrench ? 'Remboursement' : 'Reimbursement' }}: {{ number_format($acouper->remboursement, 0, ',', ' ') }} XAF
                    @endif
                    @if($acouper->pret > 0)
                        | {{ $isFrench ? 'Prêt' : 'Loan' }}: {{ number_format($acouper->pret, 0, ',', ' ') }} XAF
                    @endif
                    @if($acouper->caisse_sociale > 0)
                        | {{ $isFrench ? 'Caisse sociale' : 'Social fund' }}: {{ number_format($acouper->caisse_sociale, 0, ',', ' ') }} XAF
                    @endif
                </div>
            </div>
            @endif

            @if(count($primes) > 0)
            <h3 style="margin-top: 1.5rem; color: #3b82f6;">{{ $isFrench ? 'Détail des primes' : 'Bonus details' }}:</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ $isFrench ? 'Libellé' : 'Description' }}</th>
                            <th>{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($primes as $prime)
                        <tr>
                            <td>{{ $prime->libelle }}</td>
                            <td>{{ number_format($prime->montant, 0, ',', ' ') }} XAF</td>
                            <td>{{ $prime->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        @if($evaluation)
        <div class="section">
            <h2>
                <span class="section-icon">⭐</span>
                {{ $isFrench ? 'Évaluation' : 'Evaluation' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Note' : 'Score' }}:</span>
                    <span class="info-value">{{ $evaluation->note }}/10</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Appréciation' : 'Appreciation' }}:</span>
                    <span class="info-value">{{ $evaluation->appreciation }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($reposConge)
        <div class="section">
            <h2>
                <span class="section-icon">🏖️</span>
                {{ $isFrench ? 'Congés et repos' : 'Leave and rest' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Jour de repos hebdomadaire' : 'Weekly rest day' }}:</span>
                    <span class="info-value">{{ ucfirst($reposConge->jour) }}</span>
                </div>
                @if($reposConge->conges)
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Jours de congés disponibles' : 'Available leave days' }}:</span>
                    <span class="info-value">{{ $reposConge->conges }}</span>
                </div>
                @endif
                @if($reposConge->debut_c)
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Début du dernier congé' : 'Last leave start' }}:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($reposConge->debut_c)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Raison' : 'Reason' }}:</span>
                    <span class="info-value">{{ ucfirst($reposConge->raison_c ?? ($isFrench ? 'Non spécifiée' : 'Not specified')) }}</span>
                </div>
                @if($reposConge->autre_raison)
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Détail' : 'Detail' }}:</span>
                    <span class="info-value">{{ $reposConge->autre_raison }}</span>
                </div>
                @endif
                @endif
            </div>
        </div>
        @endif

        @if(count($delits) > 0)
        <div class="section">
            <h2>
                <span class="section-icon">⚠️</span>
                {{ $isFrench ? 'Délits et incidents' : 'Offenses and incidents' }}
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>{{ $isFrench ? 'Délit' : 'Offense' }}</th>
                            <th>Description</th>
                            <th>{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($delits as $delit)
                        <tr>
                            <td>{{ $delit->deli->nom }}</td>
                            <td>{{ $delit->deli->description }}</td>
                            <td>{{ number_format($delit->deli->montant, 0, ',', ' ') }} XAF</td>
                            <td>{{ \Carbon\Carbon::parse($delit->date_incident)->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="info-item" style="margin-top: 1rem;">
                <span class="info-label">{{ $isFrench ? 'Montant total des délits' : 'Total offense amount' }}:</span>
                <span class="info-value">{{ number_format($totalDelits, 0, ',', ' ') }} XAF</span>
            </div>
        </div>
        @endif

        @if($employee->role == 'vendeur')
        <div class="section">
            <h2>
                <span class="section-icon">📈</span>
                {{ $isFrench ? 'Performance de vente' : 'Sales performance' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Chiffre d\'affaires du mois' : 'Monthly turnover' }}:</span>
                    <span class="info-value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Nombre de transactions' : 'Number of transactions' }}:</span>
                    <span class="info-value">{{ $nbTransactions }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Moyenne journalière' : 'Daily average' }}:</span>
                    <span class="info-value">{{ number_format($moyenneVentesParJour, 0, ',', ' ') }} XAF</span>
                </div>
            </div>
        </div>
        @elseif($employee->role == 'boulanger' || $employee->role == 'patissier')
        <div class="section">
            <h2>
                <span class="section-icon">🍞</span>
                {{ $isFrench ? 'Performance de production' : 'Production performance' }}
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Valeur totale de production' : 'Total production value' }}:</span>
                    <span class="info-value">{{ number_format($valeurTotaleProduction, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Coût des matières premières' : 'Raw materials cost' }}:</span>
                    <span class="info-value">{{ number_format($coutMatieresPremieres, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ $isFrench ? 'Ratio dépense/gain' : 'Cost/benefit ratio' }}:</span>
                    <span class="info-value {{ $ratioDepenseGain >= 1 ? 'status-success' : 'status-danger' }}">
                        {{ number_format($ratioDepenseGain, 2, ',', ' ') }}
                        ({{ $ratioDepenseGain >= 1 ? ($isFrench ? 'Rentable' : 'Profitable') : ($isFrench ? 'Non rentable' : 'Not profitable') }})
                    </span>
                </div>
            </div>
        </div>
        @endif

        <div class="conclusion">
            <h2>{{ $isFrench ? 'Conclusion' : 'Conclusion' }}</h2>
            <p>
                @if($employee->role == 'vendeur')
                    <strong>{{ $employee->name }}</strong> {{ $isFrench ? 'a effectué' : 'completed' }}
                    <strong>{{ $nbTransactions }}</strong> {{ $isFrench ? 'transactions ce mois-ci, générant un chiffre d\'affaires de' : 'transactions this month, generating a turnover of' }}
                    <strong>{{ number_format($chiffreAffaires, 0, ',', ' ') }} XAF</strong>.
                    @if($joursPresence > 0)
                        {{ $isFrench ? 'Sa performance quotidienne moyenne est de' : 'Their average daily performance is' }}
                        <strong>{{ number_format($moyenneVentesParJour, 0, ',', ' ') }} XAF</strong>.
                    @endif

                    @if($acouper && ($acouper->manquants > 0 || $acouper->remboursement > 0 || $acouper->pret > 0 || $acouper->caisse_sociale > 0))
                        {{ $isFrench ? 'Des déductions d\'un montant total de' : 'Deductions totaling' }}
                        <strong>{{ number_format($acouper->manquants + $acouper->remboursement + $acouper->pret + $acouper->caisse_sociale, 0, ',', ' ') }} XAF</strong>
                        {{ $isFrench ? 'seront appliquées à son salaire.' : 'will be applied to their salary.' }}
                    @endif

                    @if($totalPrimes > 0)
                        {{ $isFrench ? 'L\'employé a reçu des primes d\'un montant total de' : 'The employee received bonuses totaling' }}
                        <strong>{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</strong> {{ $isFrench ? 'ce mois-ci.' : 'this month.' }}
                    @endif
                @elseif($employee->role == 'boulanger' || $employee->role == 'patissier')
                    <strong>{{ $employee->name }}</strong> {{ $isFrench ? 'a produit des articles d\'une valeur totale de' : 'produced items with a total value of' }}
                    <strong>{{ number_format($valeurTotaleProduction, 0, ',', ' ') }} XAF</strong> {{ $isFrench ? 'ce mois-ci, utilisant des matières premières d\'un coût de' : 'this month, using raw materials costing' }}
                    <strong>{{ number_format($coutMatieresPremieres, 0, ',', ' ') }} XAF</strong>.

                    @if($ratioDepenseGain >= 1)
                        {{ $isFrench ? 'Avec un ratio dépense/gain de' : 'With a cost/benefit ratio of' }} <strong style="color: #10b981;">{{ number_format($ratioDepenseGain, 2, ',', ' ') }}</strong>,
                        {{ $isFrench ? 'sa production est rentable pour l\'entreprise.' : 'their production is profitable for the company.' }}
                    @else
                        {{ $isFrench ? 'Avec un ratio dépense/gain de' : 'With a cost/benefit ratio of' }} <strong style="color: #ef4444;">{{ number_format($ratioDepenseGain, 2, ',', ' ') }}</strong>,
                        {{ $isFrench ? 'sa production n\'est actuellement pas rentable pour l\'entreprise.' : 'their production is currently not profitable for the company.' }}
                    @endif

                    @if($acouper && ($acouper->manquants > 0 || $acouper->remboursement > 0 || $acouper->pret > 0 || $acouper->caisse_sociale > 0))
                        {{ $isFrench ? 'Des déductions d\'un montant total de' : 'Deductions totaling' }}
                        <strong>{{ number_format($acouper->manquants + $acouper->remboursement + $acouper->pret + $acouper->caisse_sociale, 0, ',', ' ') }} XAF</strong>
                        {{ $isFrench ? 'seront appliquées à son salaire.' : 'will be applied to their salary.' }}
                    @endif

                    @if($totalPrimes > 0)
                        {{ $isFrench ? 'L\'employé a reçu des primes d\'un montant total de' : 'The employee received bonuses totaling' }}
                        <strong>{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</strong> {{ $isFrench ? 'ce mois-ci.' : 'this month.' }}
                    @endif
                @else
                    <strong>{{ $employee->name }}</strong> {{ $isFrench ? 'a été présent' : 'was present for' }}
                    <strong>{{ $joursPresence }}</strong> {{ $isFrench ? 'jours ce mois-ci, cumulant un total de' : 'days this month, accumulating a total of' }} <strong>{{ $totalHeuresTravail }}</strong> {{ $isFrench ? 'heures de travail.' : 'working hours.' }}

                    @if($acouper && ($acouper->manquants > 0 || $acouper->remboursement > 0 || $acouper->pret > 0 || $acouper->caisse_sociale > 0))
                        {{ $isFrench ? 'Des déductions d\'un montant total de' : 'Deductions totaling' }}
                        <strong>{{ number_format($acouper->manquants + $acouper->remboursement + $acouper->pret + $acouper->caisse_sociale, 0, ',', ' ') }} XAF</strong>
                        {{ $isFrench ? 'seront appliquées à son salaire.' : 'will be applied to their salary.' }}
                    @endif

                    @if($totalPrimes > 0)
                        {{ $isFrench ? 'L\'employé a reçu des primes d\'un montant total de' : 'The employee received bonuses totaling' }}
                        <strong>{{ number_format($totalPrimes, 0, ',', ' ') }} XAF</strong> {{ $isFrench ? 'ce mois-ci.' : 'this month.' }}
                    @endif
                @endif
            </p>
        </div>

        <div class="footer">
            <p>{{ $isFrench ? 'Rapport généré le' : 'Report generated on' }} {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
@endsection