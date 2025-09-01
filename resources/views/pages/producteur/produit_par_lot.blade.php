@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Mobile Header -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
            <h1 class="text-xl font-bold">{{ $isFrench ? 'Productions par Lot' : 'Productions by Batch' }}</h1>
            <p class="text-sm text-blue-200 mt-1">{{ $isFrench ? 'Résumé détaillé' : 'Detailed summary' }}</p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200 text-center">
            <h1 class="text-4xl font-bold text-blue-800 mb-2">{{ $isFrench ? 'Productions par Lot' : 'Productions by Batch' }}</h1>
            <div class="h-1 w-24 bg-gradient-to-r from-blue-600 to-green-500 mx-auto rounded-full"></div>
        </div>
    </div>

    @foreach($productionsParLot as $idLot => $production)
        <!-- Mobile Card -->
        <div class="lg:hidden mb-6 animate-fade-in mobile-card" style="animation-delay: {{ $loop->index * 0.1 }}s">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Lot Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                    <h2 class="text-lg font-bold">{{ $isFrench ? 'Lot :' : 'Batch:' }} {{ $idLot }}</h2>
                </div>

                <!-- Product Info -->
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">{{ $production['produit'] }}</h3>
                    <p class="text-sm text-blue-700">
                        {{ $isFrench ? 'Quantité produite :' : 'Quantity produced:' }} 
                        <span class="font-medium">{{ number_format($production['quantite_produit'], 2) }}</span>
                    </p>
                </div>

                <!-- Materials -->
                <div class="p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ $isFrench ? 'Matières premières utilisées :' : 'Raw materials used:' }}</h4>
                    <div class="space-y-2">
                        @foreach($production['matieres'] as $matiere)
                            @php
                                [$convertedQuantity, $convertedUnit] = \App\Services\UnitConverter::convert($matiere['quantite'], $matiere['unite']);
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-900">{{ $matiere['nom'] }}</span>
                                        <div class="text-xs text-gray-600">{{ number_format($convertedQuantity, 2) }} {{ $convertedUnit }}</div>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">{{ number_format($matiere['cout'], 0, ',', ' ') }} XAF</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Stats -->
                <div class="p-4 bg-gray-50 space-y-3">
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Valeur de la production' : 'Production value' }}</span>
                        <span class="text-sm font-bold text-blue-600">{{ number_format($production['valeur_production'], 0, ',', ' ') }} XAF</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Coût des matières' : 'Material cost' }}</span>
                        <span class="text-sm font-bold text-green-600">{{ number_format($production['cout_matieres'], 0, ',', ' ') }} XAF</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                        <span class="text-sm text-gray-600">{{ $isFrench ? 'Bénéfice Estimé (brut)' : 'Estimated Profit (gross)' }}</span>
                        <span class="text-sm font-bold {{ $production['valeur_production'] - $production['cout_matieres'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($production['valeur_production'] - $production['cout_matieres'], 0, ',', ' ') }} XAF
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Card -->
        <div class="hidden lg:block mb-8 animate-fade-in transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl" style="animation-delay: {{ $loop->index * 0.1 }}s">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 max-w-4xl mx-auto">
                <!-- Lot Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                    <h2 class="text-2xl font-bold text-center">{{ $isFrench ? 'Lot :' : 'Batch:' }} {{ $idLot }}</h2>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Product Section -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6 text-center">
                        <h3 class="text-xl font-bold text-blue-900 mb-2">{{ $production['produit'] }}</h3>
                        <p class="text-lg text-gray-700">
                            {{ $isFrench ? 'Quantité produite :' : 'Quantity produced:' }} 
                            <span class="font-bold text-blue-600">{{ number_format($production['quantite_produit'], 2) }}</span>
                        </p>
                    </div>

                    <!-- Materials Section -->
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 text-center">{{ $isFrench ? 'Matières premières utilisées :' : 'Raw materials used:' }}</h4>
                        <div class="space-y-3">
                            @foreach($production['matieres'] as $matiere)
                                @php
                                    [$convertedQuantity, $convertedUnit] = \App\Services\UnitConverter::convert($matiere['quantite'], $matiere['unite']);
                                @endphp
                                <div class="flex justify-between items-center p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $matiere['nom'] }}</span>
                                        <div class="text-sm text-gray-600">{{ number_format($convertedQuantity, 2) }} {{ $convertedUnit }}</div>
                                    </div>
                                    <span class="font-bold text-green-600">{{ number_format($matiere['cout'], 0, ',', ' ') }} XAF</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Stats Table -->
                    <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-blue-800 uppercase tracking-wider">{{ $isFrench ? 'Valeur de la production' : 'Production value' }}</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-blue-800 uppercase tracking-wider">{{ $isFrench ? 'Coût des matières' : 'Material cost' }}</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-blue-800 uppercase tracking-wider">{{ $isFrench ? 'Bénéfice Estimé (brut)' : 'Estimated Profit (gross)' }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-6 text-center text-lg font-bold text-blue-600">{{ number_format($production['valeur_production'], 0, ',', ' ') }} XAF</td>
                                    <td class="px-6 py-6 text-center text-lg font-bold text-green-600">{{ number_format($production['cout_matieres'], 0, ',', ' ') }} XAF</td>
                                    <td class="px-6 py-6 text-center text-lg font-bold {{ $production['valeur_production'] - $production['cout_matieres'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($production['valeur_production'] - $production['cout_matieres'], 0, ',', ' ') }} XAF
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
            touch-action: manipulation;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, input, .mobile-card {
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
