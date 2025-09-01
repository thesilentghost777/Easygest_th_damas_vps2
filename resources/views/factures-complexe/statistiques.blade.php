@extends('layouts.app')

@section('content')
<div class="mb-8 mobile:mb-6">
    @include('buttons')
    <br>
    <div class="flex justify-between items-center mobile:flex-col mobile:items-start mobile:space-y-4">

        <h2 class="text-xl font-semibold text-gray-800 mobile:text-lg mobile:text-blue-600 mobile:font-bold mobile:text-center mobile:w-full">
            {{ $isFrench ? 'Statistiques des factures du complexe' : 'Complex Invoice Statistics' }}
        </h2>

        <form action="{{ route('factures-complexe.statistiques') }}" method="GET" class="flex gap-2 items-center mobile:w-full mobile:bg-white mobile:p-4 mobile:rounded-xl mobile:shadow-lg mobile:flex-col mobile:gap-4">
            <div class="mobile:w-full">
                <label for="mois" class="block text-sm font-medium text-gray-700 mobile:text-base mobile:font-semibold mobile:text-gray-800">
                    {{ $isFrench ? 'Mois' : 'Month' }}
                </label>
                <select id="mois" name="mois" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mobile:py-3 mobile:px-4 mobile:border-2 mobile:border-gray-200 mobile:rounded-xl mobile:text-base mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200 mobile:transition-all mobile:duration-300">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $mois == $i ? 'selected' : '' }}>
                            @if($isFrench)
                                {{ \Carbon\Carbon::createFromDate(null, $i, 1)->locale('fr_FR')->isoFormat('MMMM') }}
                            @else
                                {{ \Carbon\Carbon::createFromDate(null, $i, 1)->locale('en')->isoFormat('MMMM') }}
                            @endif
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mobile:w-full">
                <label for="annee" class="block text-sm font-medium text-gray-700 mobile:text-base mobile:font-semibold mobile:text-gray-800">
                    {{ $isFrench ? 'Année' : 'Year' }}
                </label>
                <select id="annee" name="annee" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mobile:py-3 mobile:px-4 mobile:border-2 mobile:border-gray-200 mobile:rounded-xl mobile:text-base mobile:focus:border-blue-500 mobile:focus:ring-2 mobile:focus:ring-blue-200 mobile:transition-all mobile:duration-300">
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $annee == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="self-end pb-1 mobile:w-full mobile:pb-0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium mobile:w-full mobile:py-4 mobile:text-lg mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-blue-500 mobile:to-blue-600 mobile:hover:from-blue-600 mobile:hover:to-blue-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300">
                    {{ $isFrench ? 'Filtrer' : 'Filter' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Section -->
<div class="mb-8 bg-white p-6 rounded-lg border border-gray-200 mobile:mx-2 mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:border-0 mobile:bg-gradient-to-br mobile:from-white mobile:to-blue-50">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 mobile:text-xl mobile:text-blue-600 mobile:text-center mobile:mb-6">
        {{ $isFrench ? 'Résumé pour ' . $moisName : 'Summary for ' . $moisName }}
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mobile:gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mobile:rounded-2xl mobile:p-6 mobile:border-0 mobile:bg-gradient-to-br mobile:from-blue-500 mobile:to-blue-600 mobile:text-white mobile:shadow-lg mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300">
            <h4 class="text-md font-semibold text-blue-800 mb-1 mobile:text-white mobile:text-lg">
                {{ $isFrench ? 'Total des factures' : 'Total Invoices' }}
            </h4>
            <p class="text-2xl font-bold text-blue-700 mobile:text-white mobile:text-3xl">
                {{ number_format($totalFacturesMois, 0, ',', ' ') }} FCFA
            </p>
        </div>

        <div class="bg-green-50 p-4 rounded-lg border border-green-200 mobile:rounded-2xl mobile:p-6 mobile:border-0 mobile:bg-gradient-to-br mobile:from-green-500 mobile:to-green-600 mobile:text-white mobile:shadow-lg mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300">
            <h4 class="text-md font-semibold text-green-800 mb-1 mobile:text-white mobile:text-lg">
                {{ $isFrench ? 'Nombre de factures' : 'Number of Invoices' }}
            </h4>
            <p class="text-2xl font-bold text-green-700 mobile:text-white mobile:text-3xl">
                {{ count($facturesParJour) }}
            </p>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 mobile:rounded-2xl mobile:p-6 mobile:border-0 mobile:bg-gradient-to-br mobile:from-purple-500 mobile:to-purple-600 mobile:text-white mobile:shadow-lg mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300">
            <h4 class="text-md font-semibold text-purple-800 mb-1 mobile:text-white mobile:text-lg">
                {{ $isFrench ? 'Moyenne par facture' : 'Average per Invoice' }}
            </h4>
            <p class="text-2xl font-bold text-purple-700 mobile:text-white mobile:text-3xl">
                {{ count($facturesParJour) > 0 ? number_format($totalFacturesMois / count($facturesParJour), 2, ',', ' ') : 0 }} FCFA
            </p>
        </div>
    </div>
</div>


<!-- Most Requested Materials -->
<div class="mb-8 bg-white p-6 rounded-lg border border-gray-200 mobile:mx-2 mobile:rounded-2xl mobile:p-4 mobile:shadow-xl mobile:border-0">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 mobile:text-xl mobile:text-blue-600 mobile:text-center mobile:mb-6">
        {{ $isFrench ? 'Matières les plus demandées' : 'Most Requested Materials' }}
    </h3>

    <div class="overflow-x-auto mobile:overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 mobile:hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Matière' : 'Material' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Quantité totale' : 'Total Quantity' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Montant total' : 'Total Amount' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        % {{ $isFrench ? 'du total' : 'of total' }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($matieresPlusDemandees as $matiere)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $matiere->nom }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-900">{{ number_format($matiere->quantite_totale, 3, ',', ' ') }} {{ $matiere->unite }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($matiere->montant_total, 2, ',', ' ') }} FCFA</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-900">
                            {{ $totalFacturesMois > 0 ? number_format(($matiere->montant_total / $totalFacturesMois) * 100, 2, ',', ' ') : 0 }}%
                        </div>
                    </td>
                </tr>
                @endforeach

                @if(count($matieresPlusDemandees) === 0)
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Mobile Card Layout -->
        <div class="hidden mobile:block mobile:space-y-4">
            @foreach ($matieresPlusDemandees as $matiere)
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 shadow-md transform hover:scale-105 transition-all duration-300">
                <h4 class="font-semibold text-blue-800 text-lg mb-3">{{ $matiere->nom }}</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Quantité' : 'Quantity' }}</p>
                        <p class="font-bold text-blue-700">{{ number_format($matiere->quantite_totale, 3, ',', ' ') }} {{ $matiere->unite }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Montant' : 'Amount' }}</p>
                        <p class="font-bold text-green-600">{{ number_format($matiere->montant_total, 2, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $totalFacturesMois > 0 ? number_format(($matiere->montant_total / $totalFacturesMois) * 100, 2, ',', ' ') : 0 }}% {{ $isFrench ? 'du total' : 'of total' }}
                    </span>
                </div>
            </div>
            @endforeach

            @if(count($matieresPlusDemandees) === 0)
            <div class="text-center py-8">
                <p class="text-gray-500">{{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Invoices by Producer -->
<div class="mb-8 bg-white p-6 rounded-lg border border-gray-200 mobile:mx-2 mobile:rounded-2xl mobile:p-4 mobile:shadow-xl mobile:border-0">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 mobile:text-xl mobile:text-blue-600 mobile:text-center mobile:mb-6">
        {{ $isFrench ? 'Factures par producteur' : 'Invoices by Producer' }}
    </h3>

    <div class="overflow-x-auto mobile:overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 mobile:hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Producteur' : 'Producer' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Nombre de factures' : 'Number of Invoices' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $isFrench ? 'Montant total' : 'Total Amount' }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        % {{ $isFrench ? 'du total' : 'of total' }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($facturesParProducteur as $producteur)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $producteur->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-900">{{ $producteur->nombre_factures }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($producteur->montant_total, 2, ',', ' ') }} FCFA</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-900">
                            {{ $totalFacturesMois > 0 ? number_format(($producteur->montant_total / $totalFacturesMois) * 100, 2, ',', ' ') : 0 }}%
                        </div>
                    </td>
                </tr>
                @endforeach

                @if(count($facturesParProducteur) === 0)
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Mobile Card Layout -->
        <div class="hidden mobile:block mobile:space-y-4">
            @foreach ($facturesParProducteur as $producteur)
            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200 shadow-md transform hover:scale-105 transition-all duration-300">
                <h4 class="font-semibold text-green-800 text-lg mb-3">{{ $producteur->name }}</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Factures' : 'Invoices' }}</p>
                        <p class="font-bold text-green-700">{{ $producteur->nombre_factures }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ $isFrench ? 'Montant' : 'Amount' }}</p>
                        <p class="font-bold text-blue-600">{{ number_format($producteur->montant_total, 2, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $totalFacturesMois > 0 ? number_format(($producteur->montant_total / $totalFacturesMois) * 100, 2, ',', ' ') : 0 }}% {{ $isFrench ? 'du total' : 'of total' }}
                    </span>
                </div>
            </div>
            @endforeach

            @if(count($facturesParProducteur) === 0)
            <div class="text-center py-8">
                <p class="text-gray-500">{{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Mobile styles */
@media (max-width: 768px) {
    .mobile\:mb-6 { margin-bottom: 1.5rem; }
    .mobile\:flex-col { flex-direction: column; }
    .mobile\:items-start { align-items: flex-start; }
    .mobile\:space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
    .mobile\:text-lg { font-size: 1.125rem; }
    .mobile\:text-xl { font-size: 1.25rem; }
    .mobile\:text-3xl { font-size: 1.875rem; }
    .mobile\:text-blue-600 { color: #2563eb; }
    .mobile\:text-white { color: #ffffff; }
    .mobile\:font-bold { font-weight: 700; }
    .mobile\:text-center { text-align: center; }
    .mobile\:w-full { width: 100%; }
    .mobile\:bg-white { background-color: #ffffff; }
    .mobile\:p-4 { padding: 1rem; }
    .mobile\:p-6 { padding: 1.5rem; }
    .mobile\:rounded-xl { border-radius: 0.75rem; }
    .mobile\:rounded-2xl { border-radius: 1rem; }
    .mobile\:shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:flex-col { flex-direction: column; }
    .mobile\:gap-4 { gap: 1rem; }
    .mobile\:py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .mobile\:py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .mobile\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .mobile\:border-2 { border-width: 2px; }
    .mobile\:border-0 { border-width: 0px; }
    .mobile\:border-gray-200 { border-color: #e5e7eb; }
    .mobile\:text-base { font-size: 1rem; }
    .mobile\:font-semibold { font-weight: 600; }
    .mobile\:text-gray-800 { color: #1f2937; }
    .mobile\:focus\:border-blue-500:focus { border-color: #3b82f6; }
    .mobile\:focus\:ring-2:focus { box-shadow: 0 0 0 2px var(--tw-ring-color); }
    .mobile\:focus\:ring-blue-200:focus { --tw-ring-color: #bfdbfe; }
    .mobile\:transition-all { transition-property: all; }
    .mobile\:duration-300 { transition-duration: 300ms; }
    .mobile\:pb-0 { padding-bottom: 0px; }
    .mobile\:bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .mobile\:bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .mobile\:from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
    .mobile\:to-blue-600 { --tw-gradient-to: #2563eb; }
    .mobile\:from-blue-600 { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
    .mobile\:to-blue-700 { --tw-gradient-to: #1d4ed8; }
    .mobile\:from-white { --tw-gradient-from: #ffffff; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(255, 255, 255, 0)); }
    .mobile\:to-blue-50 { --tw-gradient-to: #eff6ff; }
    .mobile\:from-green-500 { --tw-gradient-from: #22c55e; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(34, 197, 94, 0)); }
    .mobile\:to-green-600 { --tw-gradient-to: #16a34a; }
    .mobile\:from-purple-500 { --tw-gradient-from: #8b5cf6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(139, 92, 246, 0)); }
    .mobile\:to-purple-600 { --tw-gradient-to: #7c3aed; }
    .mobile\:hover\:from-blue-600:hover { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
    .mobile\:hover\:to-blue-700:hover { --tw-gradient-to: #1d4ed8; }
    .mobile\:hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:transform { transform: translateVar(--tw-translate-x, 0) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)); }
    .mobile\:hover\:scale-105:hover { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }
    .mobile\:active\:scale-95:active { --tw-scale-x: 0.95; --tw-scale-y: 0.95; }
    .mobile\:mx-2 { margin-left: 0.5rem; margin-right: 0.5rem; }
    .mobile\:h-48 { height: 12rem; }
    .mobile\:overflow-hidden { overflow: hidden; }
    .mobile\:hidden { display: none; }
    .mobile\:block { display: block; }
    .mobile\:space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
    
    /* Touch feedback */
    * {
        -webkit-tap-highlight-color: transparent;
    }
    
    button:active {
        transform: scale(0.98);
    }
    
    select:focus {
        transform: scale(1.02);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
