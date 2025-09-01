<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Résumé des Matières Premières Assignées' : 'Assigned Raw Materials Summary' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-soft-blue { background-color: #f0f9ff; }
        .bg-day-header { background: linear-gradient(135deg, #e5f3ff, #dbeafe); }
        .bg-header-blue { background: linear-gradient(135deg, #1e3a8a, #1d4ed8); }
        .bg-soft-green { background-color: #f0fdf4; }
        .border-square { border-radius: 8px; }
        .shadow-card { box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
        .border-light-blue { border-color: #bfdbfe; }
    </style>
</head>
<body class="bg-soft-blue min-h-screen font-sans">
    <div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8">
        @php
        function convertirUnite($quantite, $unite) {
            $conversionsMasse = [
                'mg' => ['seuil' => 1000, 'unite_superieure' => 'g'],
                'g' => ['seuil' => 1000, 'unite_superieure' => 'kg'],
                'kg' => ['seuil' => 1000, 'unite_superieure' => 't']
            ];

            $conversionsVolume = [
                'ml' => ['seuil' => 100, 'unite_superieure' => 'dl'],
                'dl' => ['seuil' => 10, 'unite_superieure' => 'l'],
                'l' => ['seuil' => 1000, 'unite_superieure' => 'm³']
            ];

            $groupeConversions = in_array($unite, array_keys($conversionsMasse)) ? $conversionsMasse :
                                 (in_array($unite, array_keys($conversionsVolume)) ? $conversionsVolume : null);

            if ($groupeConversions === null) {
                return [
                    'quantite' => $quantite,
                    'unite' => $unite
                ];
            }

            foreach ($groupeConversions as $uniteActuelle => $config) {
                if ($unite === $uniteActuelle && $quantite >= $config['seuil']) {
                    return [
                        'quantite' => $quantite / $config['seuil'],
                        'unite' => $config['unite_superieure']
                    ];
                }
            }

            return [
                'quantite' => $quantite,
                'unite' => $unite
            ];
        }
        @endphp

        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 animate-fade-in">
            <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
                <h1 class="text-xl font-bold">{{ $isFrench ? 'Résumé des Matières Premières' : 'Raw Materials Summary' }}</h1>
                <p class="text-sm text-blue-200 mt-1">{{ $isFrench ? 'Assignations détaillées' : 'Detailed assignments' }}</p>
            </div>
            <div class="mt-4">
                @include('buttons')
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4 animate-fade-in">
            <h1 class="text-4xl font-bold text-blue-600">{{ $isFrench ? 'Résumé des Matières Premières Assignées' : 'Assigned Raw Materials Summary' }}</h1>
            <div class="flex flex-wrap gap-3">
                @include('buttons')
            </div>
        </div>

        @if(empty($resumeParDate))
            <div class="bg-white border-square shadow-card p-6 lg:p-8 text-center animate-fade-in">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune matière assignée trouvée.' : 'No assigned materials found.' }}</p>
            </div>
        @else
            @php
                $totalGeneral = 0;
            @endphp

            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-6">
                @foreach($resumeParDate as $date => $resumeMatieres)
                    <div class="bg-white border-square shadow-card overflow-hidden animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="bg-day-header px-4 py-3 border-b border-light-blue">
                            <h2 class="text-lg font-bold text-green-700">
                                {{ $isFrench ? 'Assignations du' : 'Assignments from' }} {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </h2>
                        </div>

                        <div class="p-4 space-y-4">
                            @php
                                $totalJour = 0;
                            @endphp

                            @foreach($resumeMatieres as $resume)
                                @php
                                    $quantiteTotaleConvertie = convertirUnite(
                                        $resume['quantite_totale'],
                                        $resume['unite']
                                    );
                                @endphp

                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mobile-card">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $resume['matiere']->nom }}</h3>
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'Référence:' : 'Reference:' }} {{ $resume['matiere']->reference }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-blue-600">{{ number_format($quantiteTotaleConvertie['quantite'], 1, ',', ' ') }}</div>
                                            <div class="text-sm text-gray-600">{{ $quantiteTotaleConvertie['unite'] }}</div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($resume['details'] as $detail)
                                            @php
                                                $quantiteDetailConvertie = convertirUnite(
                                                    $detail['quantite'],
                                                    $detail['unite']
                                                );
                                            @endphp
                                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <span class="font-medium text-blue-800">{{ $detail['producteur']->name }}</span>
                                                        <div class="text-xs text-gray-600 mt-1">
                                                            {{ number_format($quantiteDetailConvertie['quantite'], 1, ',', ' ') }} {{ $quantiteDetailConvertie['unite'] }}
                                                            <span class="text-gray-500">({{ number_format($detail['quantite_convertie'], 1, ',', ' ') }} {{ $resume['unite'] }})</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-gray-200 text-center">
                                        <span class="text-lg font-bold text-blue-800">{{ number_format($resume['prix_total'], 2, ',', ' ') }} XAF</span>
                                    </div>
                                </div>
                                @php
                                    $totalJour += $resume['prix_total'];
                                    $totalGeneral += $resume['prix_total'];
                                @endphp
                            @endforeach

                            <div class="bg-blue-100 rounded-lg p-4 border border-blue-300">
                                <div class="text-center">
                                    <span class="text-sm text-blue-700">{{ $isFrench ? 'Total du jour:' : 'Daily total:' }}</span>
                                    <div class="text-xl font-bold text-blue-800">
                                        {{ number_format($totalJour, 2, ',', ' ') }} XAF
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Tables -->
            <div class="hidden lg:block space-y-10">
                @php
                $totalGeneral = 0;
            @endphp
                @foreach($resumeParDate as $date => $resumeMatieres)
                    <div class="bg-white border-square shadow-card overflow-hidden animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="bg-day-header px-6 py-4 border-b border-light-blue">
                            <h2 class="text-xl font-semibold text-green-700">
                                {{ $isFrench ? 'Assignations du' : 'Assignments from' }} {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-header-blue text-white">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Matière' : 'Material' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Producteurs & Détails' : 'Producers & Details' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Unité' : 'Unit' }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ $isFrench ? 'Prix Total' : 'Total Price' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $totalJour = 0;
                                    @endphp

                                    @foreach($resumeMatieres as $resume)
                                        @php
                                            $quantiteTotaleConvertie = convertirUnite(
                                                $resume['quantite_totale'],
                                                $resume['unite']
                                            );
                                        @endphp

                                        <tr class="hover:bg-soft-green transition-colors duration-300">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $resume['matiere']->nom }}</div>
                                                <div class="text-xs text-gray-500">{{ $isFrench ? 'Référence:' : 'Reference:' }} {{ $resume['matiere']->reference }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-3">
                                                    @foreach($resume['details'] as $detail)
                                                        @php
                                                            $quantiteDetailConvertie = convertirUnite(
                                                                $detail['quantite'],
                                                                $detail['unite']
                                                            );
                                                        @endphp
                                                        <div class="text-sm border-l-4 border-blue-300 pl-3 py-1 hover:bg-blue-50 transition-colors duration-200">
                                                            <span class="font-medium text-blue-800">{{ $detail['producteur']->name }}</span>
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                {{ number_format($quantiteDetailConvertie['quantite'], 1, ',', ' ') }} {{ $quantiteDetailConvertie['unite'] }}
                                                                <span class="text-gray-500">({{ number_format($detail['quantite_convertie'], 3, ',', ' ') }} {{ $resume['unite'] }})</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                {{ number_format($quantiteTotaleConvertie['quantite'], 1, ',', ' ') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $quantiteTotaleConvertie['unite'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-blue-800">{{ number_format($resume['prix_total'], 2, ',', ' ') }} XAF</div>
                                            </td>
                                        </tr>
                                        @php
                                            $totalJour += $resume['prix_total'];
                                            $totalGeneral += $resume['prix_total'];
                                        @endphp
                                    @endforeach

                                    <tr class="bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            {{ $isFrench ? 'Total du jour:' : 'Daily total:' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-lg font-bold text-blue-800">
                                                {{ number_format($totalJour, 2, ',', ' ') }} XAF
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total General -->
            <div class="mt-8 bg-header-blue text-white border-square shadow-card p-4 lg:p-6 animate-fade-in">
                <div class="text-center lg:text-right">
                    <span class="text-lg font-semibold">{{ $isFrench ? 'Total général:' : 'Grand total:' }} </span>
                    <span class="text-2xl lg:text-3xl font-bold ml-2">{{ number_format($totalGeneral, 2, ',', ' ') }} XAF</span>
                </div>
            </div>
        @endif
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
            button, .mobile-card {
                min-height: 44px;
                touch-action: manipulation;
            }
            /* Smooth scrolling */
            * {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</body>
</html>
