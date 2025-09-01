
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $isFrench ? 'Rapport Flux de Produits' : 'Product Flow Report' }} - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { display: flex; justify-content: space-around; margin-bottom: 30px; }
        .stat-box { border: 1px solid #ddd; padding: 10px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .anomalie { background-color: #fff3cd; padding: 10px; margin: 5px 0; border-left: 4px solid #ffc107; }
        .critique { border-left-color: #dc3545; background-color: #f8d7da; }
        .important { border-left-color: #fd7e14; background-color: #fff3cd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $isFrench ? 'Rapport Flux de Produits' : 'Product Flow Report' }}</h1>
        <h2>{{ $date }}</h2>
        <p>{{ $isFrench ? 'Généré le' : 'Generated on' }} {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>{{ $isFrench ? 'Productions' : 'Productions' }}</h3>
            <p>{{ number_format($stats['total_productions']) }}</p>
        </div>
        <div class="stat-box">
            <h3>{{ $isFrench ? 'Réceptions' : 'Receptions' }}</h3>
            <p>{{ number_format($stats['total_receptions']) }}</p>
        </div>
        <div class="stat-box">
            <h3>{{ $isFrench ? 'Assignations' : 'Assignments' }}</h3>
            <p>{{ number_format($stats['total_assignations']) }}</p>
        </div>
    </div>

    <h2>{{ $isFrench ? 'Flux détaillés' : 'Detailed flows' }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ $isFrench ? 'Produit' : 'Product' }}</th>
                <th>{{ $isFrench ? 'Producteur' : 'Producer' }}</th>
                <th>{{ $isFrench ? 'Production' : 'Production' }}</th>
                <th>{{ $isFrench ? 'Réception' : 'Reception' }}</th>
                <th>{{ $isFrench ? 'Assignation' : 'Assignment' }}</th>
                <th>{{ $isFrench ? 'Manquants (FCFA)' : 'Missing (FCFA)' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fluxData as $flux)
                <tr>
                    <td>{{ $flux['nom_produit'] }}</td>
                    <td>{{ $flux['producteur']['nom'] }}</td>
                    <td>{{ number_format($flux['production']['quantite']) }}</td>
                    <td>{{ number_format($flux['reception']['quantite']) }}</td>
                    <td>{{ number_format($flux['assignation']['quantite']) }}</td>
                    <td>{{ number_format($flux['manquants']['production_reception']['valeur'] + $flux['manquants']['reception_assignation']['valeur']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($anomalies->count() > 0)
        <h2>{{ $isFrench ? 'Anomalies détectées' : 'Detected anomalies' }}</h2>
        @foreach($anomalies as $anomalie)
            <div class="anomalie {{ $anomalie['niveau'] }}">
                <strong>{{ ucfirst($anomalie['niveau']) }}:</strong> {{ $anomalie['message'] }}
                <br>
                <em>{{ $isFrench ? 'Action:' : 'Action:' }} {{ $anomalie['action_requise'] }}</em>
            </div>
        @endforeach
    @endif
</body>
</html>
