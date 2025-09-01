<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Mensuel - {{ $startDate->locale('fr')->isoFormat('MMMM YYYY') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a5568;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #2d3748;
            font-size: 22px;
            margin: 0 0 5px 0;
        }
        h2 {
            color: #2d3748;
            font-size: 18px;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        h3 {
            color: #4a5568;
            font-size: 16px;
            margin: 10px 0 5px 0;
        }
        p {
            margin: 5px 0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-red {
            color: #e53e3e;
        }
        .text-green {
            color: #38a169;
        }
        .mb {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #e2e8f0;
        }
        th {
            background-color: #f7fafc;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        td {
            padding: 8px;
            font-size: 13px;
        }
        .kpi-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .kpi-box {
            width: 30%;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background-color: #f8fafc;
        }
        .kpi-title {
            font-size: 14px;
            color: #718096;
            margin-bottom: 5px;
        }
        .kpi-value {
            font-size: 18px;
            font-weight: bold;
        }
        .progress-container {
            width: 100%;
            height: 15px;
            background-color: #e2e8f0;
            border-radius: 10px;
            margin: 8px 0;
        }
        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background-color: #4299e1;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 20px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
        }
        .panel {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background-color: #ffffff;
        }
        .section-title {
            background-color: #4a5568;
            color: #ffffff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .text-sm {
            font-size: 13px;
        }
        .font-bold {
            font-weight: bold;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #fdf6b2;
            border: 1px solid #fce96a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Rapport Mensuel Global</h1>
            <p>{{ $startDate->locale('fr')->isoFormat('MMMM YYYY') }}</p>
        </div>
        
        <!-- SECTION: Synthèse financière -->
        <div class="section-title">Synthèse financière</div>
        
        <div class="kpi-container">
            <div class="kpi-box">
                <div class="kpi-title">Chiffre d'affaires</div>
                <div class="kpi-value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</div>
                <div class="text-sm {{ $evolutionChiffreAffaires >= 0 ? 'text-green' : 'text-red' }}">
                    {{ $evolutionChiffreAffaires >= 0 ? '▲' : '▼' }} {{ number_format(abs($evolutionChiffreAffaires), 1) }}% vs mois précédent
                </div>
            </div>
            
            <div class="kpi-box">
                <div class="kpi-title">Dépenses totales</div>
                <div class="kpi-value">{{ number_format($depensesTotales, 0, ',', ' ') }} FCFA</div>
                <div class="text-sm {{ $evolutionDepenses <= 0 ? 'text-green' : 'text-red' }}">
                    {{ $evolutionDepenses <= 0 ? '▼' : '▲' }} {{ number_format(abs($evolutionDepenses), 1) }}% vs mois précédent
                </div>
            </div>
            
            <div class="kpi-box">
                <div class="kpi-title">Bénéfice</div>
                <div class="kpi-value {{ $benefice >= 0 ? 'text-green' : 'text-red' }}">
                    {{ number_format($benefice, 0, ',', ' ') }} FCFA
                </div>
                <div class="text-sm {{ $evolutionBenefice >= 0 ? 'text-green' : 'text-red' }}">
                    {{ $evolutionBenefice >= 0 ? '▲' : '▼' }} {{ number_format(abs($evolutionBenefice), 1) }}% vs mois précédent
                </div>
            </div>
        </div>

        <!-- SECTION: Répartition des gains par secteur -->
        <h2>Répartition des gains par secteur</h2>
        <table>
            <thead>
                <tr>
                    <th>Secteur</th>
                    <th>Montant</th>
                    <th>Pourcentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gainsParSecteur as $secteur => $data)
                    @if($data['montant'] > 0)
                        <tr>
                            <td>{{ ucfirst($secteur) }}</td>
                            <td>{{ number_format($data['montant'], 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($data['pourcentage'], 1) }}%</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- SECTION: Répartition des dépenses par secteur -->
        <h2>Répartition des dépenses par secteur</h2>
        <table>
            <thead>
                <tr>
                    <th>Secteur</th>
                    <th>Montant</th>
                    <th>Pourcentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($depensesParSecteur as $secteur => $data)
                    @if($data['montant'] > 0)
                        <tr>
                            <td>{{ ucfirst($secteur) }}</td>
                            <td>{{ number_format($data['montant'], 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($data['pourcentage'], 1) }}%</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        
        <div class="page-break"></div>

        <!-- SECTION: Suivi des objectifs -->
        <div class="section-title">Suivi des objectifs</div>
        <p>Objectifs en cours pour la période: {{ count($objectifs) }}</p>
        
        @if(count($objectifs) > 0)
            @foreach($objectifs as $objectif)
                <div class="panel">
                    <h3>{{ $objectif['titre'] }}</h3>
                    <p class="text-sm">
                        <span>{{ ucfirst($objectif['secteur']) }}</span> | 
                        <span>Type: {{ $objectif['type'] === 'revenue' ? 'Chiffre d\'affaires' : 'Bénéfice' }}</span> | 
                        <span>Statut: {{ $objectif['atteint'] ? 'Atteint ✓' : 'En cours' }}</span>
                    </p>
                    
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ min(100, $objectif['progression']) }}%;"></div>
                    </div>
                    <p class="text-sm text-right">
                        {{ number_format($objectif['progression'], 1) }}% - 
                        {{ number_format($objectif['montant_actuel'], 0, ',', ' ') }} FCFA sur 
                        {{ number_format($objectif['montant_cible'], 0, ',', ' ') }} FCFA
                    </p>
                    
                    @if(count($objectif['sous_objectifs']) > 0)
                        <h3>Sous-objectifs</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Progression</th>
                                    <th>Montant actuel</th>
                                    <th>Montant cible</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($objectif['sous_objectifs'] as $sousObjectif)
                                    <tr>
                                        <td>{{ $sousObjectif['titre'] }}</td>
                                        <td>{{ number_format($sousObjectif['progression'], 1) }}%</td>
                                        <td>{{ number_format($sousObjectif['montant_actuel'], 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($sousObjectif['montant_cible'], 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        @else
            <p>Aucun objectif n'a été défini pour cette période.</p>
        @endif

       
       
        <!-- SECTION: Informations complémentaires (si configurées) -->
        @if(!empty($config->social_climat) || !empty($config->major_problems) || !empty($config->recommendations))
            <div class="page-break"></div>
            <div class="section-title">Informations complémentaires</div>
            
            @if(!empty($config->social_climat))
                <h2>Climat social</h2>
                @foreach($config->social_climat as $item)
                    <div class="panel">
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['description'] }}</p>
                    </div>
                @endforeach
            @endif
            
            @if(!empty($config->major_problems))
                <h2>Problèmes majeurs rencontrés</h2>
                @foreach($config->major_problems as $item)
                    <div class="panel">
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['description'] }}</p>
                    </div>
                @endforeach
            @endif
            
            @if(!empty($config->recommendations))
                <h2>Recommandations</h2>
                @foreach($config->recommendations as $item)
                    <div class="panel">
                        <h3>{{ $item['source'] }}</h3>
                        <p>{{ $item['content'] }}</p>
                    </div>
                @endforeach
            @endif
        @endif

        <!-- Pied de page -->
        <div class="footer">
            Rapport généré le {{ now()->locale('fr')->isoFormat('L [à] HH:mm') }} - Confidentiel
        </div>
    </div>
</body>
</html>
