<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur détectée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 0 0 5px 5px;
        }
        .error-details {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 10px 0;
        }
        .stack-trace {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .info-table th,
        .info-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .info-table th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .urgent {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Erreur détectée dans {{ $appName }}</h1>
        <p>Une erreur inattendue s'est produite</p>
    </div>

    <div class="content">
        <p><strong class="urgent">Temps d'erreur:</strong> {{ $errorLog->error_time->format('d/m/Y H:i:s') }}</p>
        
        <div class="error-details">
            <h3>Détails de l'erreur</h3>
            <table class="info-table">
                <tr>
                    <th>Type d'erreur</th>
                    <td>{{ $errorLog->error_type }}</td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td>{{ $errorLog->error_message }}</td>
                </tr>
                <tr>
                    <th>Fichier</th>
                    <td>{{ $errorLog->file_path }}</td>
                </tr>
                <tr>
                    <th>Ligne</th>
                    <td>{{ $errorLog->line_number }}</td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td>{{ $errorLog->request_url }}</td>
                </tr>
                <tr>
                    <th>Méthode</th>
                    <td>{{ $errorLog->request_method }}</td>
                </tr>
                <tr>
                    <th>Adresse IP</th>
                    <td>{{ $errorLog->ip_address }}</td>
                </tr>
                @if($errorLog->user)
                <tr>
                    <th>Utilisateur</th>
                    <td>{{ $errorLog->user->name }} (ID: {{ $errorLog->user->id }})</td>
                </tr>
                @endif
            </table>
        </div>

        @if($errorLog->request_data && count($errorLog->request_data) > 0)
        <div class="error-details">
            <h3>Données de la requête</h3>
            <div class="stack-trace">
                {{ json_encode($errorLog->request_data, JSON_PRETTY_PRINT) }}
            </div>
        </div>
        @endif

        @if($errorLog->stack_trace)
        <div class="error-details">
            <h3>Stack Trace</h3>
            <div class="stack-trace">
                {{ $errorLog->stack_trace }}
            </div>
        </div>
        @endif

        <div class="error-details">
            <h3>Actions recommandées</h3>
            <ul>
                <li>Vérifiez les logs de l'application pour plus de détails</li>
                <li>Reproduisez l'erreur si possible</li>
                <li>Contactez l'équipe de développement si nécessaire</li>
                <li>Surveillez les erreurs similaires</li>
            </ul>
        </div>

        <p><small>Cette notification a été générée automatiquement par le système de monitoring de {{ $appName }}.</small></p>
    </div>
</body>
</html>