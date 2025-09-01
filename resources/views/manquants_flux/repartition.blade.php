<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répartition des Manquants</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 15px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
        .main-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .info-card {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="main-container">
    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-shadow gradient-header text-white">
                    <div class="card-body text-center py-4">
                        <h1 class="card-title mb-2">
                            <i class="fas fa-calculator me-3"></i>
                            Répartition des Manquants
                        </h1>
                        <p class="card-text">Répartir les manquants mensuels aux employés concernés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages d'alerte -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Informations importantes -->
        <div class="info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Information importante
                    </h5>
                    <p class="mb-0">
                        Cette action va répartir tous les manquants du mois sélectionné aux employés concernés 
                        (pointeurs et vendeurs) proportionnellement à leurs quantités traitées. 
                        Les montants seront ajoutés dans leurs comptes de manquants temporaires.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-users text-warning" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>

        <!-- Formulaire principal -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-calendar-alt text-primary" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">Sélectionner le mois</h4>
                        </div>

                        <form method="POST" action="{{ route('manquant-flux.repartir') }}" onsubmit="return confirmerRepartition()">
                            @csrf
                            <div class="mb-4">
                                <label for="mois" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-2"></i>
                                    Mois de calcul
                                </label>
                                <input type="month" 
                                       class="form-control @error('mois') is-invalid @enderror" 
                                       id="mois" 
                                       name="mois" 
                                       value="{{ old('mois', date('Y-m')) }}"
                                       max="{{ date('Y-m') }}"
                                       required>
                                @error('mois')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Sélectionnez le mois pour lequel vous souhaitez répartir les manquants
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gradient text-white">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Répartir les Manquants
                                </button>
                                <a href="{{ route('manquant-flux.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avertissements -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-shadow border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Avertissements
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-chevron-right me-2 text-warning"></i>Cette action est irréversible</li>
                            <li><i class="fas fa-chevron-right me-2 text-warning"></i>Assurez-vous que les calculs de manquants du mois sont corrects</li>
                            <li><i class="fas fa-chevron-right me-2 text-warning"></i>Les employés concernés verront leurs manquants ajoutés en statut "en_attente"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmerRepartition() {
            const mois = document.getElementById('mois').value;
            const moisFormate = new Date(mois + '-01').toLocaleDateString('fr-FR', { 
                month: 'long', 
                year: 'numeric' 
            });
            
            return confirm(`Êtes-vous sûr de vouloir répartir tous les manquants du mois de ${moisFormate} ?\n\nCette action est irréversible et va affecter les comptes des employés concernés.`);
        }

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>