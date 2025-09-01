
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <svg class="inline w-8 h-8 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $isFrench ? 'Explication du Calcul des Manquants' : 'Missing Items Calculation Explanation' }}
                    </h1>
                    <a href="{{ route('flux-produit.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        ← {{ $isFrench ? 'Retour au dashboard' : 'Back to dashboard' }}
                    </a>
                </div>
            </div>

            <!-- Formule générale -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ $isFrench ? 'Formule Générale pour les Pointeurs' : 'General Formula for Pointers' }}
                </h2>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="text-lg font-mono text-center">
                        <strong>{{ $isFrench ? 'Manquant Pointeur' : 'Pointer Missing' }} = </strong>
                        <br>
                        <span class="text-blue-700">
                            ({{ $isFrench ? 'Valeur Produite - Valeur Déclarée' : 'Produced Value - Declared Value' }}) ÷ 2
                        </span>
                        <br>
                        <span class="text-lg">+</span>
                        <br>
                        <span class="text-orange-700">
                            ({{ $isFrench ? 'Valeur Déclarée - Valeur Assignée aux Vendeurs' : 'Declared Value - Value Assigned to Sellers' }})
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Première partie -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">
                            {{ $isFrench ? '1. Responsabilité Partagée (50%)' : '1. Shared Responsibility (50%)' }}
                        </h3>
                        <p class="text-sm text-gray-700 mb-3">
                            {{ $isFrench ? 'Lorsqu\'il y a une différence entre ce que le producteur a produit et ce que le pointeur a déclaré avoir reçu, la responsabilité est partagée équitablement entre les deux parties.' : 'When there is a difference between what the producer produced and what the pointer declared to have received, the responsibility is shared equally between both parties.' }}
                        </p>
                        <div class="bg-white p-3 rounded border">
                            <strong>{{ $isFrench ? 'Exemple:' : 'Example:' }}</strong>
                            <ul class="text-sm mt-2 space-y-1">
                                <li>{{ $isFrench ? 'Production: 100 unités à 500 FCFA = 50,000 FCFA' : 'Production: 100 units at 500 FCFA = 50,000 FCFA' }}</li>
                                <li>{{ $isFrench ? 'Déclaré par pointeur: 90 unités = 45,000 FCFA' : 'Declared by pointer: 90 units = 45,000 FCFA' }}</li>
                                <li class="text-red-600">{{ $isFrench ? 'Différence: 10 unités = 5,000 FCFA' : 'Difference: 10 units = 5,000 FCFA' }}</li>
                                <li class="text-blue-700"><strong>{{ $isFrench ? 'Manquant pointeur: 5,000 ÷ 2 = 2,500 FCFA' : 'Pointer missing: 5,000 ÷ 2 = 2,500 FCFA' }}</strong></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Deuxième partie -->
                    <div class="bg-orange-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-orange-800 mb-3">
                            {{ $isFrench ? '2. Responsabilité Complète (100%)' : '2. Full Responsibility (100%)' }}
                        </h3>
                        <p class="text-sm text-gray-700 mb-3">
                            {{ $isFrench ? 'Lorsque le pointeur a déclaré avoir reçu des produits mais ne les a pas tous assignés aux vendeurs, il est entièrement responsable de cette différence.' : 'When the pointer declared having received products but did not assign them all to sellers, they are fully responsible for this difference.' }}
                        </p>
                        <div class="bg-white p-3 rounded border">
                            <strong>{{ $isFrench ? 'Exemple:' : 'Example:' }}</strong>
                            <ul class="text-sm mt-2 space-y-1">
                                <li>{{ $isFrench ? 'Déclaré reçu: 90 unités = 45,000 FCFA' : 'Declared received: 90 units = 45,000 FCFA' }}</li>
                                <li>{{ $isFrench ? 'Assigné aux vendeurs: 85 unités = 42,500 FCFA' : 'Assigned to sellers: 85 units = 42,500 FCFA' }}</li>
                                <li class="text-red-600">{{ $isFrench ? 'Différence: 5 unités = 2,500 FCFA' : 'Difference: 5 units = 2,500 FCFA' }}</li>
                                <li class="text-orange-700"><strong>{{ $isFrench ? 'Manquant pointeur: 2,500 FCFA' : 'Pointer missing: 2,500 FCFA' }}</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">
                        {{ $isFrench ? 'Total du Manquant' : 'Total Missing' }}
                    </h3>
                    <div class="text-center text-xl font-mono">
                        <strong class="text-red-700">2,500 + 2,500 = 5,000 FCFA</strong>
                    </div>
                </div>
            </div>

            <!-- Notifications automatiques -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ $isFrench ? 'Notifications Automatiques' : 'Automatic Notifications' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-3">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.664 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            {{ $isFrench ? 'Alerte 1: Produits Non Assignés' : 'Alert 1: Unassigned Products' }}
                        </h3>
                        <p class="text-sm text-gray-700">
                            {{ $isFrench ? 'Le système envoie automatiquement une notification au DG et au Chef de Production lorsqu\'un pointeur a des produits qui n\'ont pas été assignés à une vendeuse depuis plus de 2 heures.' : 'The system automatically sends a notification to the DG and Production Manager when a pointer has products that have not been assigned to a seller for more than 2 hours.' }}
                        </p>
                    </div>

                    <div class="bg-red-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-red-800 mb-3">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.664 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            {{ $isFrench ? 'Alerte 2: Productions Non Déclarées' : 'Alert 2: Undeclared Productions' }}
                        </h3>
                        <p class="text-sm text-gray-700">
                            {{ $isFrench ? 'Le système détecte automatiquement lorsqu\'un producteur a des produits qu\'aucun pointeur n\'a déclarés et envoie une notification immédiate aux responsables.' : 'The system automatically detects when a producer has products that no pointer has declared and sends an immediate notification to managers.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Processus de détection -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ $isFrench ? 'Processus de Détection des Anomalies' : 'Anomaly Detection Process' }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Collecte des Données' : 'Data Collection' }}</h3>
                            <p class="text-gray-600">{{ $isFrench ? 'Le système collecte toutes les données de production, réception et assignation en temps réel.' : 'The system collects all production, reception and assignment data in real time.' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Analyse Comparative' : 'Comparative Analysis' }}</h3>
                            <p class="text-gray-600">{{ $isFrench ? 'Comparaison automatique entre les quantités produites, reçues et assignées pour détecter les écarts.' : 'Automatic comparison between produced, received and assigned quantities to detect discrepancies.' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">3</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Calcul des Manquants' : 'Missing Calculation' }}</h3>
                            <p class="text-gray-600">{{ $isFrench ? 'Application de la formule de calcul selon les règles de responsabilité définies.' : 'Application of the calculation formula according to defined responsibility rules.' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-bold">4</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $isFrench ? 'Notification et Suivi' : 'Notification and Follow-up' }}</h3>
                            <p class="text-gray-600">{{ $isFrench ? 'Envoi automatique des notifications aux responsables et suivi des actions correctives.' : 'Automatic sending of notifications to managers and follow-up of corrective actions.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
