@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
        <div class="mb-4 sm:mb-0 animate-slide-down">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-truck-loading mr-3 text-blue-600"></i>
                {{ $isFrench ? 'Réceptions Pointeur' : 'Pointer Receptions' }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? 'Gérez toutes les réceptions de vos pointeurs' : 'Manage all pointer receptions' }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <a href="{{ route('receptions.pointeurs.create') }}" 
               class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                {{ $isFrench ? 'Nouvelle Réception' : 'New Reception' }}
            </a>
        </div>
    </div>

    <!-- Filtres Desktop -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6 mb-6 animate-fade-in">
        <form method="GET" action="{{ route('receptions.pointeurs.index') }}" id="filter-form-desktop">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Filtres' : 'Filters' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                    </label>
                    <select name="pointeur_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">{{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }}</option>
                        @foreach($pointeurs as $pointeur)
                            <option value="{{ $pointeur->id }}" {{ request('pointeur_id') == $pointeur->id ? 'selected' : '' }}>
                                {{ $pointeur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select name="produit_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" {{ request('produit_id') == $produit->code_produit ? 'selected' : '' }}>
                                {{ $produit->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date' : 'Date' }}
                    </label>
                    <input type="date" 
                           name="date_reception" 
                           value="{{ request('date_reception') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                    <a href="{{ route('receptions.pointeurs.index') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Filtres Mobile -->
    <div class="lg:hidden bg-white rounded-2xl shadow-lg border border-gray-100 p-4 mb-6 animate-fade-in">
        <form method="GET" action="{{ route('receptions.pointeurs.index') }}" id="filter-form-mobile">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Filtres' : 'Filters' }}
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                    </label>
                    <select name="pointeur_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">{{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }}</option>
                        @foreach($pointeurs as $pointeur)
                            <option value="{{ $pointeur->id }}" {{ request('pointeur_id') == $pointeur->id ? 'selected' : '' }}>
                                {{ $pointeur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select name="produit_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" {{ request('produit_id') == $produit->code_produit ? 'selected' : '' }}>
                                {{ $produit->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date' : 'Date' }}
                    </label>
                    <input type="date" 
                           name="date_reception" 
                           value="{{ request('date_reception') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                    <a href="{{ route('receptions.pointeurs.index') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times mr-1"></i>
                        {{ $isFrench ? 'Reset' : 'Reset' }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Indicateur de filtres actifs -->
    @if(request()->hasAny(['pointeur_id', 'produit_id', 'date_reception']))
        <div class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <span class="text-sm text-blue-700 font-medium">
                            {{ $isFrench ? 'Filtres actifs:' : 'Active filters:' }}
                        </span>
                        <div class="ml-2 flex flex-wrap gap-1">
                            @if(request('pointeur_id'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $pointeurs->find(request('pointeur_id'))->name ?? 'N/A' }}
                                </span>
                            @endif
                            @if(request('produit_id'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $produits->where('code_produit', request('produit_id'))->first()->nom ?? 'N/A' }}
                                </span>
                            @endif
                            @if(request('date_reception'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ \Carbon\Carbon::parse(request('date_reception'))->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('receptions.pointeurs.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        {{ $isFrench ? 'Effacer tout' : 'Clear all' }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Messages flash -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 animate-slide-down">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 animate-slide-down">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Tableau des réceptions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-green-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Liste des Réceptions' : 'Reception List' }}
                </h3>
                <span class="text-sm text-gray-600">
                    {{ $receptions->total() }} {{ $isFrench ? 'résultat(s)' : 'result(s)' }}
                </span>
            </div>
        </div>
        
        @if(count($receptions) > 0)
            <!-- Version Mobile -->
            <div class="lg:hidden">
                @foreach($receptions as $index => $reception)
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                        {{ \Carbon\Carbon::parse($reception->date_reception)->format('d/m/Y') }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($reception->quantite_recue, 2) }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    <i class="fas fa-user mr-1 text-blue-600"></i>
                                    {{ $reception->pointeur->name ?? 'N/A' }}
                                </h4>
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fas fa-box mr-1 text-green-600"></i>
                                    {{ $reception->produit->nom ?? ($isFrench ? 'Produit supprimé' : 'Deleted product') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $reception->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 ml-4">
                                <a href="{{ route('receptions.pointeurs.show', $reception->id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ $isFrench ? 'Voir' : 'View' }}
                                </a>
                                <a href="{{ route('receptions.pointeurs.edit', $reception->id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <button type="button" onclick="confirmDelete({{ $reception->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Version Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Créé le' : 'Created' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($receptions as $index => $reception)
                            <tr class="hover:bg-gray-50 transition-colors animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ \Carbon\Carbon::parse($reception->date_reception)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2 text-blue-600"></i>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reception->pointeur->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $reception->produit->nom ?? ($isFrench ? 'Produit supprimé' : 'Deleted product') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $isFrench ? 'Code:' : 'Code:' }} {{ $reception->produit_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ number_format($reception->quantite_recue, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $reception->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('receptions.pointeurs.show', $reception->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ $isFrench ? 'Voir' : 'View' }}
                                        </a>
                                        <a href="{{ route('receptions.pointeurs.edit', $reception->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $reception->id }})"
                                                class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $receptions->appends(request()->query())->links() }}
            </div>
        @else
            <!-- État vide -->
            <div class="p-8 sm:p-12 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                    {{ $isFrench ? 'Aucune réception trouvée' : 'No receptions found' }}
                </h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['pointeur_id', 'produit_id', 'date_reception']))
                        {{ $isFrench ? 'Aucun résultat pour les filtres appliqués.' : 'No results for applied filters.' }}
                        <br>
                        <a href="{{ route('receptions.pointeurs.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $isFrench ? 'Effacer les filtres' : 'Clear filters' }}
                        </a>
                    @else
                        {{ $isFrench ? 'Commencez par créer une nouvelle réception.' : 'Start by creating a new reception.' }}
                    @endif
                </p>
                @if(!request()->hasAny(['pointeur_id', 'produit_id', 'date_reception']))
                    <a href="{{ route('receptions.pointeurs.create') }}" 
                       class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        {{ $isFrench ? 'Créer une réception' : 'Create reception' }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 animate-scale-in">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $isFrench ? 'Confirmer la suppression' : 'Confirm deletion' }}
            </h3>
        </div>
        <div class="p-6">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                <p class="text-gray-600 mb-4">
                    {{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette réception ? Cette action est irréversible.' : 'Are you sure you want to delete this reception? This action is irreversible.' }}
                </p>
            </div>
        </div>
        <div class="flex gap-3 p-6 border-t border-gray-200">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                {{ $isFrench ? 'Annuler' : 'Cancel' }}
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/receptions/pointeurs/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection