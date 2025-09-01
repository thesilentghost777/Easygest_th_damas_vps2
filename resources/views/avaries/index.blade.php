{{-- resources/views/avaries/index.blade.php --}}
@extends('layouts.app')

@php
    $isAdminOrManager = in_array(Auth::user()->secteur, ['administration']);
@endphp

@section('title', $isAdminOrManager ? ($isFrench ? 'Toutes les Avaries' : 'All Damages') : ($isFrench ? 'Mes Avaries' : 'My Damages'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-background via-secondary/5 to-accent/5">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="space-y-2">
                <h1 class="text-3xl md:text-4xl font-bold text-foreground">
                    @if($isAdminOrManager)
                        {{ $isFrench ? 'Gestion des Avaries' : 'Damage Management' }}
                    @else
                        {{ $isFrench ? 'Mes Avaries Déclarées' : 'My Declared Damages' }}
                    @endif
                </h1>
                <p class="text-muted-foreground text-lg">
                    {{ $isFrench ? 'Suivi et gestion des avaries déclarées' : 'Track and manage declared damages' }}
                </p>
            </div>
            <a href="{{ route('avaries.create') }}" 
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus"></i>
                <span>{{ $isFrench ? 'Nouvelle Avarie' : 'New Damage' }}</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="bg-card rounded-xl shadow-lg border border-border/50 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-primary/10 to-accent/10 px-6 py-4 border-b border-border/50">
                <h3 class="text-lg font-semibold text-foreground flex items-center gap-2">
                    <i class="fas fa-filter text-primary"></i>
                    {{ $isFrench ? 'Filtres et Tri' : 'Filters and Sorting' }}
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('avaries.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    
                    @if($isAdminOrManager && $pointeurs->count() > 0)
                    <div class="space-y-2">
                        <label for="pointeur_id" class="block text-sm font-medium text-foreground">
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                        </label>
                        <select name="pointeur_id" id="pointeur_id" class="w-full px-3 py-2 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">-- {{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }} --</option>
                            @foreach($pointeurs as $pointeur)
                                <option value="{{ $pointeur->id }}" {{ request('pointeur_id') == $pointeur->id ? 'selected' : '' }}>
                                    {{ $pointeur->name }} 
                                    @if($pointeur->secteur)
                                        ({{ $pointeur->secteur }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="space-y-2">
                        <label for="date_debut" class="block text-sm font-medium text-foreground">
                            {{ $isFrench ? 'Date début' : 'Start Date' }}
                        </label>
                        <input type="date" name="date_debut" id="date_debut" 
                               class="w-full px-3 py-2 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                               value="{{ request('date_debut') }}">
                    </div>

                    <div class="space-y-2">
                        <label for="date_fin" class="block text-sm font-medium text-foreground">
                            {{ $isFrench ? 'Date fin' : 'End Date' }}
                        </label>
                        <input type="date" name="date_fin" id="date_fin" 
                               class="w-full px-3 py-2 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                               value="{{ request('date_fin') }}">
                    </div>

                    <div class="space-y-2">
                        <label for="sort_by" class="block text-sm font-medium text-foreground">
                            {{ $isFrench ? 'Trier par' : 'Sort by' }}
                        </label>
                        <select name="sort_by" id="sort_by" class="w-full px-3 py-2 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="date_avarie" {{ request('sort_by', 'date_avarie') == 'date_avarie' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </option>
                            @if($isAdminOrManager)
                            <option value="pointeur" {{ request('sort_by') == 'pointeur' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                            </option>
                            @endif
                            <option value="montant_total" {{ request('sort_by') == 'montant_total' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Montant' : 'Amount' }}
                            </option>
                            <option value="quantite" {{ request('sort_by') == 'quantite' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="sort_order" class="block text-sm font-medium text-foreground">
                            {{ $isFrench ? 'Ordre' : 'Order' }}
                        </label>
                        <select name="sort_order" id="sort_order" class="w-full px-3 py-2 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Décroissant' : 'Descending' }}
                            </option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Croissant' : 'Ascending' }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">{{ $isFrench ? 'Filtrer' : 'Filter' }}</span>
                        </button>
                        <a href="{{ route('avaries.index') }}" class="bg-secondary hover:bg-secondary/80 text-secondary-foreground px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters Summary -->
        @if(request()->hasAny(['pointeur_id', 'date_debut', 'date_fin', 'sort_by', 'sort_order']))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 mb-6 shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                <span class="font-medium">{{ $isFrench ? 'Filtres/Tri actifs :' : 'Active Filters/Sort:' }}</span>
                
                @if(request('pointeur_id') && $isAdminOrManager)
                    <span class="inline-flex items-center px-2 py-1 bg-primary text-primary-foreground text-xs rounded-full">
                        {{ $isFrench ? 'Pointeur:' : 'Pointer:' }} {{ $pointeurs->find(request('pointeur_id'))->name ?? ($isFrench ? 'Inconnu' : 'Unknown') }}
                    </span>
                @endif
                @if(request('date_debut'))
                    <span class="inline-flex items-center px-2 py-1 bg-green-500 text-white text-xs rounded-full">
                        {{ $isFrench ? 'Depuis:' : 'From:' }} {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                    </span>
                @endif
                @if(request('date_fin'))
                    <span class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">
                        {{ $isFrench ? "Jusqu'au:" : 'Until:' }} {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                    </span>
                @endif
                @if(request('sort_by') && request('sort_by') != 'date_avarie')
                    <span class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                        {{ $isFrench ? 'Tri:' : 'Sort:' }} {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }} 
                        ({{ request('sort_order', 'desc') == 'desc' ? ($isFrench ? 'Décroissant' : 'Desc') : ($isFrench ? 'Croissant' : 'Asc') }})
                    </span>
                @endif
            </div>
        </div>
        @endif

        @if($avaries->count() > 0)
            <!-- Results Card -->
            <div class="bg-card rounded-xl shadow-lg border border-border/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-primary to-primary/80 text-primary-foreground">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'pointeur', 'sort_order' => (request('sort_by') == 'pointeur' && request('sort_order', 'desc') == 'desc') ? 'asc' : 'desc']) }}" 
                                       class="text-primary-foreground hover:text-primary-foreground/80 flex items-center gap-2 transition-colors">
                                        {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                                        @if(request('sort_by') == 'pointeur')
                                            <i class="fas fa-sort-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }} text-yellow-300"></i>
                                        @else
                                            <i class="fas fa-sort opacity-50"></i>
                                        @endif
                                    </a>
                                </th>
                                
                                <th class="px-6 py-4 text-left font-semibold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date_avarie', 'sort_order' => (request('sort_by') == 'date_avarie' && request('sort_order', 'desc') == 'desc') ? 'asc' : 'desc']) }}" 
                                       class="text-primary-foreground hover:text-primary-foreground/80 flex items-center gap-2 transition-colors">
                                        {{ $isFrench ? 'Date' : 'Date' }}
                                        @if(request('sort_by', 'date_avarie') == 'date_avarie')
                                            <i class="fas fa-sort-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }} text-yellow-300"></i>
                                        @else
                                            <i class="fas fa-sort opacity-50"></i>
                                        @endif
                                    </a>
                                </th>
                                
                                <th class="px-6 py-4 text-left font-semibold">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                
                                <th class="px-6 py-4 text-center font-semibold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantite', 'sort_order' => (request('sort_by') == 'quantite' && request('sort_order', 'desc') == 'desc') ? 'asc' : 'desc']) }}" 
                                       class="text-primary-foreground hover:text-primary-foreground/80 flex items-center justify-center gap-2 transition-colors">
                                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                        @if(request('sort_by') == 'quantite')
                                            <i class="fas fa-sort-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }} text-yellow-300"></i>
                                        @else
                                            <i class="fas fa-sort opacity-50"></i>
                                        @endif
                                    </a>
                                </th>
                                
                                <th class="px-6 py-4 text-right font-semibold">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'montant_total', 'sort_order' => (request('sort_by') == 'montant_total' && request('sort_order', 'desc') == 'desc') ? 'asc' : 'desc']) }}" 
                                       class="text-primary-foreground hover:text-primary-foreground/80 flex items-center justify-end gap-2 transition-colors">
                                        {{ $isFrench ? 'Montant' : 'Amount' }}
                                        @if(request('sort_by') == 'montant_total')
                                            <i class="fas fa-sort-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }} text-yellow-300"></i>
                                        @else
                                            <i class="fas fa-sort opacity-50"></i>
                                        @endif
                                    </a>
                                </th>
                                
                                <th class="px-6 py-4 text-left font-semibold">{{ $isFrench ? 'Description' : 'Description' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($avaries as $avarie)
                            <tr class="hover:bg-secondary/5 transition-colors duration-200 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($avarie->user_id == Auth::id())
                                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    {{ $isFrench ? 'MOI' : 'ME' }}
                                                </span>
                                            @else
                                                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-secondary-foreground text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground {{ $avarie->user_id == Auth::id() ? 'text-green-700' : '' }}">
                                                {{ $avarie->user->name }}
                                            </div>
                                            @if($avarie->user->secteur)
                                                <div class="text-sm text-muted-foreground flex items-center gap-1">
                                                    <i class="fas fa-building text-xs"></i>
                                                    {{ $avarie->user->secteur }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground">{{ $avarie->date_avarie->format('d/m/Y') }}</div>
                                    <div class="text-sm text-muted-foreground flex items-center gap-1">
                                        <i class="fas fa-clock text-xs"></i>
                                        {{ $avarie->date_avarie->diffForHumans() }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground">{{ $avarie->produit->nom }}</div>
                                    <div class="text-sm text-muted-foreground flex items-center gap-1">
                                        <i class="fas fa-tag text-xs"></i>
                                        {{ $avarie->produit->categorie }}
                                    </div>
                                    <div class="text-sm text-blue-600">
                                        {{ $isFrench ? 'Prix unitaire:' : 'Unit price:' }} {{ number_format($avarie->produit->prix, 0, ',', ' ') }} FCFA
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                                        {{ number_format($avarie->quantite) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-red-600 text-lg">
                                        {{ number_format($avarie->montant_total, 0, ',', ' ') }} FCFA
                                    </div>
                                    <div class="text-sm text-muted-foreground">
                                        ({{ number_format($avarie->quantite) }} × {{ number_format($avarie->produit->prix, 0, ',', ' ') }})
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($avarie->description)
                                        <div class="max-w-xs">
                                            <div class="text-sm text-foreground truncate" title="{{ $avarie->description }}">
                                                <i class="fas fa-comment-alt text-blue-500 text-xs mr-1"></i>
                                                {{ Str::limit($avarie->description, 30) }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-sm text-muted-foreground italic flex items-center gap-1">
                                            <i class="fas fa-comment-slash text-xs"></i>
                                            {{ $isFrench ? 'Aucune description' : 'No description' }}
                                        </div>
                                    @endif
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer with Statistics and Pagination -->
                <div class="bg-gradient-to-r from-secondary/5 to-accent/5 px-6 py-4 border-t border-border/50">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1">
                            <div class="bg-primary/10 rounded-lg p-4 text-center border border-primary/20">
                                <div class="flex items-center justify-center gap-2 text-primary mb-1">
                                    <i class="fas fa-list"></i>
                                    <span class="font-semibold">{{ $isFrench ? 'Résultats' : 'Results' }}</span>
                                </div>
                                <div class="font-bold text-foreground">{{ $avaries->count() }}</div>
                                <div class="text-xs text-muted-foreground">{{ $isFrench ? 'sur' : 'of' }} {{ $avaries->total() }} {{ $isFrench ? 'total' : 'total' }}</div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 text-center border border-red-200">
                                <div class="flex items-center justify-center gap-2 text-red-600 mb-1">
                                    <i class="fas fa-calculator"></i>
                                    <span class="font-semibold">{{ $isFrench ? 'Montant Total' : 'Total Amount' }}</span>
                                </div>
                                <div class="font-bold text-red-600 text-lg">
                                    {{ number_format($avaries->sum('montant_total'), 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                            @if($avaries->count() > 1)
                            <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-200">
                                <div class="flex items-center justify-center gap-2 text-blue-600 mb-1">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="font-semibold">{{ $isFrench ? 'Moyenne' : 'Average' }}</span>
                                </div>
                                <div class="font-bold text-blue-600">
                                    {{ number_format($avaries->avg('montant_total'), 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            {{ $avaries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-card rounded-xl shadow-lg border border-border/50 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-foreground mb-2">
                        {{ $isFrench ? 'Aucune avarie trouvée' : 'No damages found' }}
                    </h3>
                    @if(request()->hasAny(['pointeur_id', 'date_debut', 'date_fin']))
                        <p class="text-muted-foreground mb-6">
                            {{ $isFrench ? 'Aucune avarie ne correspond aux critères de filtre sélectionnés.' : 'No damages match the selected filter criteria.' }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('avaries.index') }}" class="bg-secondary hover:bg-secondary/80 text-secondary-foreground px-6 py-2 rounded-lg font-medium transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                {{ $isFrench ? 'Effacer les filtres' : 'Clear filters' }}
                            </a>
                            <a href="{{ route('avaries.create') }}" class="bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-2 rounded-lg font-medium transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                {{ $isFrench ? 'Déclarer une avarie' : 'Declare damage' }}
                            </a>
                        </div>
                    @else
                        <p class="text-muted-foreground mb-6">
                            @if($isAdminOrManager)
                                {{ $isFrench ? "Aucune avarie n'a encore été déclarée par les pointeurs." : 'No damages have been declared by pointers yet.' }}
                            @else
                                {{ $isFrench ? "Vous n'avez encore déclaré aucune avarie." : 'You have not declared any damages yet.' }}
                            @endif
                        </p>
                        <a href="{{ route('avaries.create') }}" class="bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-2 rounded-lg font-medium transition-all duration-200 inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            {{ $isFrench ? 'Déclarer une avarie' : 'Declare damage' }}
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes to table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 50}ms`;
        row.classList.add('animate-slideIn');
    });

    // Enhanced tooltip functionality
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title && title.length > 30) {
                // Create custom tooltip for long titles
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-lg max-w-xs';
                tooltip.textContent = title;
                tooltip.style.position = 'fixed';
                tooltip.style.pointerEvents = 'none';
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
                
                this.addEventListener('mouseleave', () => {
                    tooltip.remove();
                }, { once: true });
            }
        });
    });
});
</script>
@endsection