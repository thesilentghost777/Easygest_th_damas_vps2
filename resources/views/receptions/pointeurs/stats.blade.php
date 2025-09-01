{{-- resources/views/receptions/pointeurs/stats.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistiques Réceptions Pointeur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques des Réceptions Pointeur
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('receptions.pointeurs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour aux réceptions
                        </a>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>
                            Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques générales --}}
    <div class="row mt-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['total_receptions']) }}</h3>
                            <p class="card-text">Total Réceptions</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck-loading fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['receptions_aujourd_hui']) }}</h3>
                            <p class="card-text">Aujourd'hui</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['receptions_ce_mois']) }}</h3>
                            <p class="card-text">Ce Mois</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['pointeurs_actifs']) }}</h3>
                            <p class="card-text">Pointeurs Actifs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['produits_recus']) }}</h3>
                            <p class="card-text">Produits Différents</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ number_format($stats['quantite_totale'], 2) }}</h3>
                            <p class="card-text">Quantité Totale</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-weight fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Top 5 Pointeurs --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Top 5 Pointeurs Les Plus Actifs
                    </h5>
                </div>
                <div class="card-body">
                    @if($topPointeurs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Pointeur</th>
                                        <th>Nb Réceptions</th>
                                        <th>Progression</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPointeurs as $index => $pointeur)
                                        <tr>
                                            <td>
                                                @if($index == 0)
                                                    <i class="fas fa-medal text-warning fa-lg"></i>
                                                @elseif($index == 1)
                                                    <i class="fas fa-medal text-secondary fa-lg"></i>
                                                @elseif($index == 2)
                                                    <i class="fas fa-medal text-dark fa-lg"></i>
                                                @else
                                                    <span class="badge bg-primary">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $pointeur->pointeur->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-success fs-6">
                                                    {{ number_format($pointeur->nb_receptions) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-gradient-success" 
                                                         style="width: {{ ($pointeur->nb_receptions / $topPointeurs->first()->nb_receptions) * 100 }}%">
                                                        {{ number_format(($pointeur->nb_receptions / $topPointeurs->first()->nb_receptions) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>Aucun pointeur actif trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top 5 Produits --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>
                        Top 5 Produits Les Plus Reçus
                    </h5>
                </div>
                <div class="card-body">
                    @if($topProduits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Produit</th>
                                        <th>Quantité Totale</th>
                                        <th>Progression</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProduits as $index => $produit)
                                        <tr>
                                            <td>
                                                @if($index == 0)
                                                    <i class="fas fa-star text-warning fa-lg"></i>
                                                @elseif($index == 1)
                                                    <i class="fas fa-star text-secondary fa-lg"></i>
                                                @elseif($index == 2)
                                                    <i class="fas fa-star text-dark fa-lg"></i>
                                                @else
                                                    <span class="badge bg-success">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $produit->produit->nom_produit ?? 'Produit supprimé' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $produit->produit_id }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info fs-6">
                                                    {{ number_format($produit->quantite_totale, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-gradient-info" 
                                                         style="width: {{ ($produit->quantite_totale / $topProduits->first()->quantite_totale) * 100 }}%">
                                                        {{ number_format(($produit->quantite_totale / $topProduits->first()->quantite_totale) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>Aucun produit reçu trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques (optionnel avec Chart.js) --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par Pointeur
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="pointeursChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gradient-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Évolution Mensuelle
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Résumé détaillé --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Résumé Détaillé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-primary">Activité Générale</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>
                                    {{ number_format($stats['total_receptions']) }} réceptions enregistrées
                                </li>
                                <li><i class="fas fa-users text-primary me-2"></i>
                                    {{ number_format($stats['pointeurs_actifs']) }} pointeurs différents
                                </li>
                                <li><i class="fas fa-boxes text-info me-2"></i>
                                    {{ number_format($stats['produits_recus']) }} types de produits
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-success">Performance Récente</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-calendar-day text-success me-2"></i>
                                    {{ number_format($stats['receptions_aujourd_hui']) }} réceptions aujourd'hui
                                </li>
                                <li><i class="fas fa-calendar-alt text-info me-2"></i>
                                    {{ number_format($stats['receptions_ce_mois']) }} réceptions ce mois
                                </li>
                                <li><i class="fas fa-chart-line text-warning me-2"></i>
                                    Moyenne: {{ $stats['total_receptions'] > 0 ? number_format($stats['quantite_totale'] / $stats['total_receptions'], 2) : 0 }} par réception
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-info">Données Quantitatives</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-weight text-primary me-2"></i>
                                    {{ number_format($stats['quantite_totale'], 2) }} unités au total
                                </li>
                                <li><i class="fas fa-calculator text-success me-2"></i>
                                    Généré le {{ now()->format('d/m/Y à H:i') }}
                                </li>
                                <li><i class="fas fa-sync text-warning me-2"></i>
                                    Données en temps réel
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    const pointeursLabels = {!! json_encode($topPointeurs->pluck('pointeur.name')->toArray()) !!};
    const pointeursData = {!! json_encode($topPointeurs->pluck('nb_receptions')->toArray()) !!};

    // Graphique en secteurs des pointeurs
    const ctxPointeurs = document.getElementById('pointeursChart').getContext('2d');
    new Chart(ctxPointeurs, {
        type: 'doughnut',
        data: {
            labels: pointeursLabels,
            datasets: [{
                data: pointeursData,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' réceptions';
                        }
                    }
                }
            }
        }
    });

    // Graphique d'évolution (exemple avec données simulées)
    const ctxEvolution = document.getElementById('evolutionChart').getContext('2d');
    new Chart(ctxEvolution, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Réceptions',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .card-tools {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
    
    .row {
        break-inside: avoid;
    }
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #d39e00);
}

.bg-gradient-dark {
    background: linear-gradient(45deg, #343a40, #1d2124);
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.card-title {
    font-weight: 600;
}

.fa-medal {
    filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
}

.fa-star {
    filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.3));
}

.list-unstyled li {
    margin-bottom: 0.5rem;
}

canvas {
    max-height: 300px !important;
}
</style>
@endpush