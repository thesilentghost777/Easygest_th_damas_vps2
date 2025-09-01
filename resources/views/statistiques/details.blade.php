@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-10">
    @include('buttons')

    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg">
        <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4">
            <h1 class="text-3xl font-bold text-white">Statistiques de Production</h1>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('employee.performance') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 font-medium text-center">
                    Voir statistiques par producteur
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form action="{{ route('statistiques.details') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label for="date_debut" class="block text-sm font-semibold text-gray-700 mb-2">Date début</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-semibold text-gray-700 mb-2">Date fin</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="producteur" class="block text-sm font-semibold text-gray-700 mb-2">Producteur</label>
                <select id="producteur" name="producteur" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Tous les producteurs</option>
                    @foreach($producteurs as $producteur)
                        <option value="{{ $producteur->id }}" {{ request('producteur') == $producteur->id ? 'selected' : '' }}>
                            {{ $producteur->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="produit" class="block text-sm font-semibold text-gray-700 mb-2">Produit</label>
                <select id="produit" name="produit" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Tous les produits</option>
                    @foreach($produits as $produit)
                        <option value="{{ $produit->code_produit }}" {{ request('produit') == $produit->code_produit ? 'selected' : '' }}>
                            {{ $produit->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 lg:col-span-4 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des résultats -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Lot ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Producteur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Prix Vente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Coût</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase">Bénéfice Potentiel</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productions as $prod)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prod['id_lot'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prod['date_production'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prod['producteur'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prod['produit'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($prod['quantite'], 0) }}</td>
                        <td class="px-6 py-4 text-sm text-blue-600">{{ number_format($prod['chiffre_affaires'], 0) }} F</td>
                        <td class="px-6 py-4 text-sm text-red-600">{{ number_format($prod['cout_production'], 0) }} F</td>
                        <td class="px-6 py-4 text-sm {{ $prod['benefice_brut'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($prod['benefice_brut'], 0) }} F
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Aucune donnée disponible</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($productions) > 0)
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total:</td>
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900">{{ number_format($productions->sum('quantite'), 0) }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-blue-600">{{ number_format($productions->sum('chiffre_affaires'), 0) }} F</td>
                        <td class="px-6 py-3 text-sm font-semibold text-red-600">{{ number_format($productions->sum('cout_production'), 0) }} F</td>
                        <td class="px-6 py-3 text-sm font-semibold {{ $productions->sum('benefice_brut') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($productions->sum('benefice_brut'), 0) }} F
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        @if($productions instanceof \Illuminate\Pagination\LengthAwarePaginator && $productions->hasPages())
        <div class="px-6 py-3 bg-white border-t border-gray-200">
            {{ $productions->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection