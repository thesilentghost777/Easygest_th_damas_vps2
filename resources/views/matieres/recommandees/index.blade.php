@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $isFrench ? 'Matières Recommandées' : 'Recommended Materials' }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        {{ $isFrench ? 'Par produit' : 'By product' }}
                    </p>
                </div>
                <a href="{{ route('matieres.recommandees.create') }}" class="bg-white bg-opacity-20 p-3 rounded-xl transform hover:scale-110 active:scale-95 transition-all duration-200">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Matières Recommandées par Produit' : 'Recommended Materials by Product' }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        {{ $isFrench ? 'Gérer les recommandations de matières pour chaque produit' : 'Manage material recommendations for each product' }}
                    </p>
                </div>
                <a href="{{ route('matieres.recommandees.create') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 transition duration-200 transform hover:scale-105">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ $isFrench ? 'Nouvelle Recommandation' : 'New Recommendation' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-fade-in" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Mobile Product Cards -->
        <div class="md:hidden space-y-4">
            @forelse($produits as $produit)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-102 transition-all duration-300 animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $produit->nom }}</h3>
                                <p class="text-gray-600 text-sm mt-1">{{ $isFrench ? 'Code:' : 'Code:' }} {{ $produit->code_produit }}</p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $produit->matiereRecommandee->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $produit->matiereRecommandee->count() }} {{ $isFrench ? 'matière(s)' : 'material(s)' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($produit->matiereRecommandee->count() > 0)
                            <div class="bg-blue-50 p-3 rounded-xl mb-4">
                                <p class="text-xs font-medium text-blue-600 mb-1">{{ $isFrench ? 'Dernière mise à jour' : 'Last update' }}</p>
                                <p class="text-sm text-blue-800 font-semibold">{{ $produit->matiereRecommandee->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('matieres.recommandees.show', $produit->code_produit) }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ $isFrench ? 'Détails' : 'Details' }}
                            </a>
                            <a href="{{ route('matieres.recommandees.create', $produit->code_produit) }}" class="flex-1 bg-green-100 text-green-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Ajouter' : 'Add' }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fade-in">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{ $isFrench ? 'Aucun produit n\'a été trouvé.' : 'No products were found.' }}
                    </p>
                    <a href="{{ route('matieres.recommandees.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl transform hover:scale-105 active:scale-95 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $isFrench ? 'Créer une recommandation' : 'Create recommendation' }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    @if ($produits->isEmpty())
                        <div class="text-center py-12 animate-fade-in">
                            <svg class="h-20 w-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                                {{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}
                            </h3>
                            <p class="text-gray-500 mb-6">
                                {{ $isFrench ? 'Aucun produit n\'a été trouvé.' : 'No products were found.' }}
                            </p>
                            <a href="{{ route('matieres.recommandees.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Créer une recommandation' : 'Create recommendation' }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Produit' : 'Product' }}
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nombre de Matières' : 'Number of Materials' }}
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Dernière Mise à Jour' : 'Last Update' }}
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($produits as $produit)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $produit->nom }}</div>
                                                <div class="text-sm text-gray-500">{{ $isFrench ? 'Code:' : 'Code:' }} {{ $produit->code_produit }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $produit->matiereRecommandee->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $produit->matiereRecommandee->count() }} {{ $isFrench ? 'matière(s)' : 'material(s)' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($produit->matiereRecommandee->count() > 0)
                                                    {{ $produit->matiereRecommandee->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('matieres.recommandees.show', $produit->code_produit) }}" class="text-blue-600 hover:text-blue-900 transform hover:scale-110 transition-all duration-200">
                                                        {{ $isFrench ? 'Détails' : 'Details' }}
                                                    </a>
                                                    <a href="{{ route('matieres.recommandees.create', $produit->code_produit) }}" class="text-indigo-600 hover:text-indigo-900 transform hover:scale-110 transition-all duration-200">
                                                        {{ $isFrench ? 'Ajouter des Matières' : 'Add Materials' }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="p-4">
                            {{ $produits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
