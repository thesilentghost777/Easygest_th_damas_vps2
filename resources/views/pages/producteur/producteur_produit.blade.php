@extends('pages.producteur.pdefault')

@section('page-content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <!-- Add Product Button -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0 mb-6">
            <button id="bouton" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 lg:py-2 px-4 rounded-xl lg:rounded-md shadow transition-all duration-200 transform hover:scale-105 active:scale-95">
                <a href="{{ route('produitmp') }}" class="flex items-center justify-center space-x-2">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    <span>{{ $isFrench ? 'Ajouter produits' : 'Add Products' }}</span>
                </a>
            </button>
        </div>

       <!-- Header -->
<div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mb-6">
    <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-transparent bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text mb-2 font-sans">
        {{ $isFrench ? 'Tableau de Production' : 'Production Dashboard' }}
    </h1>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
        <p class="text-gray-600 text-sm lg:text-base font-medium tracking-wide font-mono">{{ $secteur }} - {{ $nom }}</p>
        <p class="text-gray-500 text-sm lg:text-base font-light italic font-serif">{{ $heure_actuelle->format('d/m/Y H:i') }}</p>
    </div>
</div>
    </div>

    <div class="mb-6 lg:mb-8 animate-fade-in">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-calendar-today mr-2 text-green-600"></i>
            {{ $isFrench ? 'Productions réalisées aujourd\'hui' : 'Today\'s Completed Productions' }}
        </h2>
        
        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-3 lg:gap-6">
            @forelse($p as $production)
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $production['nom'] }}</h3>
                        <span class="text-green-600 font-semibold bg-green-100 px-3 py-1 rounded-full text-sm">{{ number_format($production['prix']) }} FCFA</span>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-600">{{ $isFrench ? 'Quantité produite:' : 'Quantity produced:' }} <span class="font-semibold">{{ $production['quantite'] }}</span></p>
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Matières premières utilisées:' : 'Raw materials used:' }}</h4>
                            <ul class="space-y-1">
                                @foreach($production['matieres_premieres'] as $matiere)
                                    <li class="text-sm text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                        {{ $matiere['nom'] }}:
                                        @php
                                            $quantite = $matiere['quantite'];
                                            $unite = $matiere['unite'];

                                            // Conversion rules
                                            $conversionMapping = [
                                                'g' => ['kg' => 1000],
                                                'kg' => ['kg' => 1],
                                                'ml' => ['litre' => 1000],
                                                'cl' => ['litre' => 100],
                                                'dl' => ['litre' => 10],
                                                'l' => ['litre' => 1],
                                                'cc' => ['ml' => 5],
                                                'cs' => ['ml' => 15],
                                                'pincee' => ['g' => 1.5],
                                                'unite' => ['unite' => 1],
                                            ];

                                            $convertedQuantite = $quantite;
                                            $convertedUnite = $unite;

                                            // Perform conversion if applicable
                                            if (isset($conversionMapping[$unite])) {
                                                foreach ($conversionMapping[$unite] as $targetUnite => $conversionFactor) {
                                                    if ($convertedQuantite >= $conversionFactor) {
                                                        $convertedQuantite = $convertedQuantite / $conversionFactor;
                                                        $convertedUnite = $targetUnite;
                                                    }
                                                }
                                            }
                                        @endphp
                                        {{ number_format($convertedQuantite, 2, '.', '') }} {{ $convertedUnite }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 text-center py-12">
                    <i class="mdi mdi-factory text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production enregistrée aujourd\'hui' : 'No production recorded today' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($p as $production)
                <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500 mobile-card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $production['nom'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }} <span class="font-semibold">{{ $production['quantite'] }}</span></p>
                        </div>
                        <div class="text-right">
                            <span class="text-green-600 font-semibold bg-green-100 px-3 py-1 rounded-full text-sm">{{ number_format($production['prix']) }} FCFA</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-gray-700">{{ $isFrench ? 'Matières premières:' : 'Raw materials:' }}</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            @foreach($production['matieres_premieres'] as $matiere)
                                @php
                                    $quantite = $matiere['quantite'];
                                    $unite = $matiere['unite'];

                                    // Conversion rules
                                    $conversionMapping = [
                                        'g' => ['kg' => 1000],
                                        'kg' => ['kg' => 1],
                                        'ml' => ['litre' => 1000],
                                        'cl' => ['litre' => 100],
                                        'dl' => ['litre' => 10],
                                        'l' => ['litre' => 1],
                                        'cc' => ['ml' => 5],
                                        'cs' => ['ml' => 15],
                                        'pincee' => ['g' => 1.5],
                                        'unite' => ['unite' => 1],
                                    ];

                                    $convertedQuantite = $quantite;
                                    $convertedUnite = $unite;

                                    // Perform conversion if applicable
                                    if (isset($conversionMapping[$unite])) {
                                        foreach ($conversionMapping[$unite] as $targetUnite => $conversionFactor) {
                                            if ($convertedQuantite >= $conversionFactor) {
                                                $convertedQuantite = $convertedQuantite / $conversionFactor;
                                                $convertedUnite = $targetUnite;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="text-xs text-gray-600 mb-1">
                                    {{ $matiere['nom'] }}: {{ number_format($convertedQuantite, 2, '.', '') }} {{ $convertedUnite }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mdi mdi-factory text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production enregistrée aujourd\'hui' : 'No production recorded today' }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Productions attendues -->
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-clock-outline mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Productions attendues' : 'Expected Productions' }}
        </h2>
        
        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-3 lg:gap-6">
            @forelse($productions_attendues as $attendue)
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $attendue['nom'] }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $attendue['status'] === 'Terminé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $isFrench ? $attendue['status'] : ($attendue['status'] === 'Terminé' ? 'Completed' : 'Pending') }}
                        </span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Attendu:' : 'Expected:' }}</span>
                            <span class="font-medium">{{ $attendue['quantite_attendue'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Produit:' : 'Produced:' }}</span>
                            <span class="font-medium">{{ $attendue['quantite_produite'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ min($attendue['progression'], 100) }}%"></div>
                        </div>
                        <div class="text-center text-xs text-gray-500">{{ min($attendue['progression'], 100) }}%</div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 text-center py-12">
                    <i class="mdi mdi-clock-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production attendue' : 'No expected productions' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($productions_attendues as $attendue)
                <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-blue-500 mobile-card">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $attendue['nom'] }}-{{ $attendue['prix'] }}f</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $attendue['status'] === 'Terminé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $isFrench ? $attendue['status'] : ($attendue['status'] === 'Terminé' ? 'Completed' : 'Pending') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Attendu:' : 'Expected:' }}</span>
                            <span class="font-medium">{{ $attendue['quantite_attendue'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Produit:' : 'Produced:' }}</span>
                            <span class="font-medium">{{ $attendue['quantite_produite'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ min($attendue['progression'], 100) }}%"></div>
                        </div>
                        <div class="text-center text-xs text-gray-500">{{ min($attendue['progression'], 100) }}%</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mdi mdi-clock-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production attendue' : 'No expected productions' }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Productions recommandées -->
    <div class="mb-6 lg:mb-8 animate-fade-in">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-lightbulb-outline mr-2 text-green-600"></i>
            {{ $isFrench ? 'Productions recommandées pour' : 'Recommended productions for' }} {{ $day }}
        </h2>
        
        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-3 lg:gap-6">
            @forelse($productions_recommandees as $recommandee)
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">{{ $recommandee['nom'] }}</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Quantité recommandée:' : 'Recommended quantity:' }}</span>
                            <span class="font-medium">{{ $recommandee['quantite_recommandee'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }}</span>
                            <span class="font-medium text-green-600">{{ number_format($recommandee['prix']) }} FCFA</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 text-center py-12">
                    <i class="mdi mdi-lightbulb-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production recommandée pour aujourd\'hui' : 'No recommended productions for today' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($productions_recommandees as $recommandee)
                <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500 mobile-card">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">{{ $recommandee['nom'] }}</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <span class="font-medium">{{ $recommandee['quantite_recommandee'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $isFrench ? 'Prix:' : 'Price:' }}</span>
                            <span class="font-medium text-green-600">{{ number_format($recommandee['prix']) }} FCFA</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="mdi mdi-lightbulb-outline text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune production recommandée pour aujourd\'hui' : 'No recommended productions for today' }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Liste des produits disponibles -->
    <div class="animate-fade-in">
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="mdi mdi-package-variant-closed mr-2 text-blue-600"></i>
            {{ $isFrench ? 'Tous les produits disponibles' : 'All Available Products' }}
        </h2>
        
        <!-- Desktop Grid -->
        <div class="hidden lg:grid lg:grid-cols-4 lg:gap-4">
            @foreach($all_produits as $produit)
                <div class="bg-white rounded-lg shadow p-4 border border-blue-200 hover:shadow-lg transition-all duration-300">
                    <h3 class="font-medium text-gray-900">{{ $produit->nom }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ number_format($produit->prix) }} FCFA</p>
                </div>
            @endforeach
        </div>

        <!-- Mobile Grid -->
        <div class="lg:hidden grid grid-cols-2 gap-3">
            @foreach($all_produits as $produit)
                <div class="bg-white rounded-xl shadow p-3 border border-blue-200 mobile-card">
                    <h3 class="font-medium text-gray-900 text-sm">{{ $produit->nom }}</h3>
                    <p class="text-xs text-gray-600 mt-1">{{ number_format($produit->prix) }} FCFA</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, a, .mobile-card {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
