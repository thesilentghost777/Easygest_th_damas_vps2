<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isFrench ? 'Attestation de Stage' : 'Internship Certificate' }} - {{ $stagiaire->nom }} {{ $stagiaire->prenom }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
            color: #333;
        }
        
        @media (max-width: 768px) {
            body {
                margin: 20px;
                font-size: 14px;
            }
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #2c5282;
            padding-bottom: 20px;
        }
        
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #2c5282;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        h2 {
            color: #2c5282;
            font-size: 22px;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section {
            margin-bottom: 30px;
            padding: 15px;
            border-left: 4px solid #3b82f6;
            background-color: #f8fafc;
        }
        
        .section-title {
            color: #2c5282;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .highlight {
            background-color: #dbeafe;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 50px;
            text-align: right;
            border-top: 2px solid #e5e7eb;
            padding-top: 30px;
        }
        
        .signature-line {
            width: 250px;
            border-top: 2px solid #374151;
            margin-left: auto;
            margin-top: 60px;
            text-align: center;
            padding-top: 10px;
            font-weight: bold;
        }
        
        .company-info {
            background-color: #1e40af;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        @media print {
            body { margin: 20px; }
            .section { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="company-info">
        <h1 style="color: white; margin: 0;">TH MARKET</h1>
        <p style="margin: 5px 0 0 0; font-size: 16px;">
            {{ $isFrench ? 'Boulangerie - Pâtisserie - Excellence artisanale' : 'Bakery - Pastry - Artisanal Excellence' }}
        </p>
    </div>

    <div class="header">
        <h2>{{ $isFrench ? 'Attestation de Stage' : 'Internship Certificate' }}</h2>
    </div>

    <div class="section">
        <p>
            {{ $isFrench ? 'Nous, TH MARKET, attestons par la présente que' : 'We, TH MARKET, hereby certify that' }}
            <span class="highlight">{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</span>,
            {{ $isFrench ? 'étudiant(e) en' : 'student in' }}
            <span class="highlight">{{ $stagiaire->filiere }}</span>
            {{ $isFrench ? 'à' : 'at' }}
            <span class="highlight">{{ $stagiaire->ecole }}</span>,
            {{ $isFrench ? 'a effectué un stage au sein de notre entreprise.' : 'has completed an internship within our company.' }}
        </p>
    </div>

    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Coordonnées du Stagiaire' : 'Intern Contact Information' }}
        </div>
        <p><strong>Email :</strong> {{ $stagiaire->email }}</p>
        <p><strong>{{ $isFrench ? 'Téléphone' : 'Phone' }} :</strong> {{ $stagiaire->telephone }}</p>
    </div>

    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Période de Stage' : 'Internship Period' }}
        </div>
        <p>
            {{ $isFrench ? 'Le stage s\'est déroulé du' : 'The internship took place from' }}
            <span class="highlight">{{ $stagiaire->date_debut->format('d/m/Y') }}</span>
            {{ $isFrench ? 'au' : 'to' }}
            <span class="highlight">{{ $stagiaire->date_fin->format('d/m/Y') }}</span>,
            {{ $isFrench ? 'permettant à' : 'allowing' }} {{ $stagiaire->prenom }}
            {{ $isFrench ? 'd\'acquérir des compétences pratiques en lien avec sa formation.' : 'to acquire practical skills related to their training.' }}
        </p>
    </div>

    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Département d\'Affectation' : 'Department Assignment' }}
        </div>
        <p class="highlight" style="font-size: 16px;">
            {{ $isFrench ? ucfirst($stagiaire->departement) : ucfirst($stagiaire->departement) }}
        </p>
    </div>

    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Missions et Travaux Réalisés' : 'Missions and Work Completed' }}
        </div>
        <p>
            {{ $isFrench ? 'Durant cette période,' : 'During this period,' }} {{ $stagiaire->prenom }}
            {{ $isFrench ? 'a participé activement aux différentes missions qui lui ont été confiées, notamment le travail de:' : 'actively participated in various missions assigned, particularly work involving:' }}
        </p>
        <p style="background-color: white; padding: 15px; border-radius: 6px; margin-top: 10px; font-style: italic;">
            {{ $stagiaire->nature_travail }}
        </p>
    </div>

    @if($stagiaire->type_stage === 'professionnel')
    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Rémunération' : 'Compensation' }}
        </div>
        <p>
            {{ $isFrench ? 'Une indemnité de stage de' : 'An internship allowance of' }}
            <span class="highlight">{{ number_format($stagiaire->remuneration, 2) }} F CFA</span>
            {{ $isFrench ? 'lui a été attribuée.' : 'was awarded.' }}
        </p>
    </div>
    @endif

    <div class="section">
        <div class="section-title">
            {{ $isFrench ? 'Appréciation' : 'Evaluation' }}
        </div>
        <p style="background-color: white; padding: 15px; border-radius: 6px;">
            {{ $stagiaire->appreciation ?? ($isFrench ? 'Le stagiaire a fait preuve de sérieux et d\'engagement tout au long de son stage.' : 'The intern showed seriousness and commitment throughout their internship.') }}
        </p>
    </div>

    <div class="footer">
        <p><strong>{{ $isFrench ? 'Fait à' : 'Done in' }} _____________, {{ $isFrench ? 'le' : 'on' }} {{ now()->format('d/m/Y') }}</strong></p>
        <div class="signature-line">
            <p>{{ $isFrench ? 'Le Directeur Général' : 'General Manager' }}</p>
        </div>
    </div>
</body>
</html>
