<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Statistiques Financières' : 'Financial Statistics' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/css/app.css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            animation: slideDown 0.8s ease-out;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #64748b;
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: bounceIn 0.8s ease-out;
        }

        .back-button:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.4s ease;
            animation: fadeInUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            animation: shimmer 2s infinite;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stat-card h3 {
            color: #1e293b;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-icon {
            font-size: 1.2rem;
        }

        .stat-value {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-amount {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.25rem 0;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .revenue {
            color: #059669;
            background: #ecfdf5;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .expense {
            color: #dc2626;
            background: #fef2f2;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .balance {
            color: #1d4ed8;
            background: #f0f9ff;
            padding: 0.5rem;
            border-radius: 8px;
            font-weight: 700;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            animation: slideInUp 0.8s ease-out;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }

        .chart-card h3 {
            color: #1e293b;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
        }

        .chart-container {
            height: 400px;
            position: relative;
        }

        .ratio-display {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 300px;
            text-align: center;
        }

        .ratio-value {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulse 2s infinite;
        }

        .ratio-label {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .table-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            animation: fadeIn 1s ease-out;
        }

        .table-card h3 {
            color: #1e293b;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .responsive-table {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .expense-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .expense-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .expense-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .expense-table tr:hover {
            background: #f8fafc;
        }

        .mobile-expense-item {
            display: none;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .mobile-expense-item:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateX(5px);
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .container {
                padding: 0.75rem;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .charts-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .chart-container {
                height: 300px;
            }

            .chart-card {
                padding: 1rem;
            }

            .table-card {
                padding: 1rem;
            }

            .responsive-table {
                display: none;
            }

            .mobile-expense-item {
                display: block;
            }

            .back-button {
                width: 45px;
                height: 45px;
                top: 15px;
                left: 15px;
            }

            .ratio-value {
                font-size: 2.5rem;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
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

        /* Enhanced mobile animations */
        @media (max-width: 768px) {
            .stat-card {
                animation: slideInLeft 0.6s ease-out;
            }

            .stat-card:nth-child(2) {
                animation-delay: 0.1s;
            }

            .stat-card:nth-child(3) {
                animation-delay: 0.2s;
            }

            .stat-card:nth-child(4) {
                animation-delay: 0.3s;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    @include('buttons')

    <div class="container">
        <div class="header">
            <h1>📊 {{ $isFrench ? 'Tableau de Bord Financier' : 'Financial Dashboard' }}</h1>
            <p>{{ $isFrench ? 'Vue d\'ensemble complète de vos finances' : 'Complete overview of your finances' }}</p>
        </div>

        <!-- Statistics cards -->
        <div class="stats-grid">
            <!-- Daily statistics -->
            <div class="stat-card">
                <h3>
                    <span class="stat-icon">📅</span>
                    {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                </h3>
                <div class="stat-value">
                    <div class="stat-amount revenue">
                        <span>{{ $isFrench ? 'Revenus' : 'Revenue' }}:</span>
                        <span>{{ number_format($statsJour->revenus, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount expense">
                        <span>{{ $isFrench ? 'Dépenses' : 'Expenses' }}:</span>
                        <span>{{ number_format($statsJour->depenses, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount balance">
                        <span>{{ $isFrench ? 'Solde' : 'Balance' }}:</span>
                        <span>{{ number_format($statsJour->solde, 2) }} XAF</span>
                    </div>
                </div>
            </div>

            <!-- Weekly statistics -->
            <div class="stat-card">
                <h3>
                    <span class="stat-icon">📊</span>
                    {{ $isFrench ? 'Cette semaine' : 'This Week' }}
                </h3>
                <div class="stat-value">
                    <div class="stat-amount revenue">
                        <span>{{ $isFrench ? 'Revenus' : 'Revenue' }}:</span>
                        <span>{{ number_format($statsHebdo->revenus, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount expense">
                        <span>{{ $isFrench ? 'Dépenses' : 'Expenses' }}:</span>
                        <span>{{ number_format($statsHebdo->depenses, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount balance">
                        <span>{{ $isFrench ? 'Solde' : 'Balance' }}:</span>
                        <span>{{ number_format($statsHebdo->solde, 2) }} XAF</span>
                    </div>
                </div>
            </div>

            <!-- Monthly statistics -->
            <div class="stat-card">
                <h3>
                    <span class="stat-icon">📈</span>
                    {{ $isFrench ? 'Ce mois' : 'This Month' }}
                </h3>
                <div class="stat-value">
                    <div class="stat-amount revenue">
                        <span>{{ $isFrench ? 'Revenus' : 'Revenue' }}:</span>
                        <span>{{ number_format($statsMois->revenus, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount expense">
                        <span>{{ $isFrench ? 'Dépenses' : 'Expenses' }}:</span>
                        <span>{{ number_format($statsMois->depenses, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount balance">
                        <span>{{ $isFrench ? 'Solde' : 'Balance' }}:</span>
                        <span>{{ number_format($statsMois->solde, 2) }} XAF</span>
                    </div>
                </div>
            </div>

            <!-- Annual statistics -->
            <div class="stat-card">
                <h3>
                    <span class="stat-icon">🎯</span>
                    {{ $isFrench ? 'Cette année' : 'This Year' }}
                </h3>
                <div class="stat-value">
                    <div class="stat-amount revenue">
                        <span>{{ $isFrench ? 'Revenus' : 'Revenue' }}:</span>
                        <span>{{ number_format($statsAnnee->revenus, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount expense">
                        <span>{{ $isFrench ? 'Dépenses' : 'Expenses' }}:</span>
                        <span>{{ number_format($statsAnnee->depenses, 2) }} XAF</span>
                    </div>
                    <div class="stat-amount balance">
                        <span>{{ $isFrench ? 'Solde' : 'Balance' }}:</span>
                        <span>{{ number_format($statsAnnee->solde, 2) }} XAF</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <!-- Monthly evolution -->
            <div class="chart-card">
                <h3>📈 {{ $isFrench ? 'Évolution mensuelle' : 'Monthly Evolution' }}</h3>
                <div class="chart-container">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

            <!-- Expense breakdown by category -->
            <div class="chart-card">
                <h3>🥧 {{ $isFrench ? 'Répartition des dépenses par catégorie' : 'Expense Breakdown by Category' }}</h3>
                <div class="chart-container">
                    <canvas id="depensesChart"></canvas>
                </div>
            </div>

            <!-- Daily evolution -->
            <div class="chart-card">
                <h3>📊 {{ $isFrench ? 'Évolution journalière du mois en cours' : 'Daily Evolution of Current Month' }}</h3>
                <div class="chart-container">
                    <canvas id="evolutionJournaliereChart"></canvas>
                </div>
            </div>

            <!-- Expense/revenue ratio -->
            <div class="chart-card">
                <h3>⚖️ {{ $isFrench ? 'Ratio dépenses/revenus du mois' : 'Monthly Expense/Revenue Ratio' }}</h3>
                <div class="ratio-display">
                    <div class="ratio-value {{ $ratio->ratio <= 100 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $ratio->ratio }}%
                    </div>
                    <p class="ratio-label">{{ $isFrench ? 'des revenus sont dépensés' : 'of revenue is spent' }}</p>
                </div>
            </div>
        </div>

        <!-- Top 5 expenses -->
        <div class="table-card">
            <h3>
                <span class="stat-icon">🏆</span>
                {{ $isFrench ? 'Top 5 des dépenses' : 'Top 5 Expenses' }}
            </h3>
            <div class="responsive-table">
                <table class="expense-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th>Description</th>
                            <th>{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDepenses as $depense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</td>
                            <td>{{ $depense->category->name }}</td>
                            <td>{{ $depense->description }}</td>
                            <td style="font-weight: 600; color: #dc2626;">{{ number_format($depense->amount, 2) }} XAF</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile expense items -->
            @foreach($topDepenses as $depense)
            <div class="mobile-expense-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-weight: 600; color: #1e293b;">{{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</span>
                    <span style="font-weight: 700; color: #dc2626;">{{ number_format($depense->amount, 2) }} XAF</span>
                </div>
                <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 0.25rem;">
                    <strong>{{ $isFrench ? 'Catégorie' : 'Category' }}:</strong> {{ $depense->category->name }}
                </div>
                <div style="font-size: 0.9rem; color: #64748b;">
                    <strong>Description:</strong> {{ $depense->description }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        // Monthly evolution chart
        const ctx = document.getElementById('evolutionChart').getContext('2d');
        const evolutionData = @json($evolutionMensuelle);

        const months = @if($isFrench)
            ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
        @else
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        @endif;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(d => months[d.mois - 1]),
                datasets: [
                    {
                        label: '{{ $isFrench ? "Revenus" : "Revenue" }}',
                        data: evolutionData.map(d => d.revenus),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '{{ $isFrench ? "Dépenses" : "Expenses" }}',
                        data: evolutionData.map(d => d.depenses),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '{{ $isFrench ? "Solde" : "Balance" }}',
                        data: evolutionData.map(d => d.solde),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Expense breakdown chart
        const ctxDepenses = document.getElementById('depensesChart').getContext('2d');
        const depensesData = @json($depensesParCategorie);

        new Chart(ctxDepenses, {
            type: 'pie',
            data: {
                labels: Object.keys(depensesData),
                datasets: [{
                    data: Object.values(depensesData),
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(99, 255, 132)',
                        'rgb(162, 235, 54)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Daily evolution chart
        const ctxJournalier = document.getElementById('evolutionJournaliereChart').getContext('2d');
        const evolutionJournaliereData = @json($evolutionJournaliere);

        new Chart(ctxJournalier, {
            type: 'line',
            data: {
                labels: evolutionJournaliereData.map(d => new Date(d.jour).toLocaleDateString()),
                datasets: [
                    {
                        label: '{{ $isFrench ? "Revenus" : "Revenue" }}',
                        data: evolutionJournaliereData.map(d => d.revenus),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '{{ $isFrench ? "Dépenses" : "Expenses" }}',
                        data: evolutionJournaliereData.map(d => d.depenses),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
</body>
</html>
