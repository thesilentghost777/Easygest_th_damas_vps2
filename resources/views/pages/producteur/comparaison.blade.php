@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-4 md:py-8" x-data="{ isMobile: window.innerWidth < 768 }" x-init="() => {
    window.addEventListener('resize', () => isMobile = window.innerWidth < 768);
}">
    @include('buttons')
    
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4" x-transition:enter="transition ease-out duration-300">
            {{ $isFrench ? 'Comparaison des Producteurs' : 'Producers Comparison' }}
        </h1>

        <form action="{{ route('producteur.comparaison') }}" method="GET" 
              class="bg-white rounded-lg shadow-sm md:shadow-md p-4 md:p-6 mb-4 md:mb-6"
              x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-4">
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                        {{ $isFrench ? 'Critère' : 'Criterion' }}
                    </label>
                    <select name="critere" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs md:text-sm">
                        <option value="benefice" {{ request('critere') == 'benefice' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                        </option>
                        <option value="quantite" {{ request('critere') == 'quantite' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Quantité produite' : 'Quantity produced' }}
                        </option>
                        <option value="efficacite" {{ request('critere') == 'efficacite' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Efficacité' : 'Efficiency' }}
                        </option>
                        <option value="diversite" {{ request('critere') == 'diversite' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Diversité des produits' : 'Product diversity' }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                        {{ $isFrench ? 'Période' : 'Period' }}
                    </label>
                    <select name="periode" id="periode" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs md:text-sm">
                        <option value="jour" {{ request('periode') == 'jour' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                        </option>
                        <option value="semaine" {{ request('periode') == 'semaine' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Cette semaine' : 'This week' }}
                        </option>
                        <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Ce mois' : 'This month' }}
                        </option>
                        <option value="personnalise" {{ request('periode') == 'personnalise' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Période personnalisée' : 'Custom period' }}
                        </option>
                    </select>
                </div>

                <div class="date-range {{ request('periode') != 'personnalise' ? 'hidden' : '' }}">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                        {{ $isFrench ? 'Date début' : 'Start date' }}
                    </label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs md:text-sm">
                </div>

                <div class="date-range {{ request('periode') != 'personnalise' ? 'hidden' : '' }}">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">
                        {{ $isFrench ? 'Date fin' : 'End date' }}
                    </label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs md:text-sm">
                </div>
            </div>

            <div class="mt-3 md:mt-4 flex justify-end space-x-3 md:space-x-4">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 md:py-2 px-3 md:px-4 rounded text-xs md:text-sm transition-all duration-200 transform hover:scale-105 active:scale-95">
                    {{ $isFrench ? 'Filtrer' : 'Filter' }}
                </button>
                <button type="button" onclick="print_()" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 md:py-2 px-3 md:px-4 rounded text-xs md:text-sm transition-all duration-200 transform hover:scale-105 active:scale-95">
                    {{ $isFrench ? 'Imprimer' : 'Print' }}
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm md:shadow-md overflow-hidden" x-transition:enter="transition ease-out duration-300">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Rang' : 'Rank' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Secteur' : 'Sector' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Bénéfice' : 'Profit' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Efficacité' : 'Efficiency' }}
                        </th>
                        <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $isFrench ? 'Diversité' : 'Diversity' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($resultats as $index => $resultat)
                    <tr class="{{ $index % 2 ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-900">
                            {{ $resultat['nom'] }}
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                            {{ $resultat['secteur'] }}
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                            {{ number_format($resultat['stats']['quantite_totale'], 0) }}
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                            {{ number_format($resultat['stats']['benefice'], 0) }} FCFA
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                            {{ number_format($resultat['stats']['efficacite'], 2) }}
                        </td>
                        <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                            {{ $resultat['stats']['nombre_produits'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function print_() {
    window.print();
}
document.getElementById('periode').addEventListener('change', function() {
    const dateRangeInputs = document.querySelectorAll('.date-range');
    if (this.value === 'personnalise') {
        dateRangeInputs.forEach(input => {
            input.classList.remove('hidden');
            input.classList.add('animate-fadeIn');
        });
    } else {
        dateRangeInputs.forEach(input => {
            input.classList.add('hidden');
            input.classList.remove('animate-fadeIn');
        });
    }
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>
@endsection