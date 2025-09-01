@extends('layouts.app')

@section('title', ($isFrench ? 'fr' : 'en') === 'fr' ? 'Validation des Dépenses' : 'Expense Validation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/10 to-secondary/10 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="bg-card/80 backdrop-blur-sm rounded-2xl p-8 mb-8 shadow-xl border border-border/20">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-foreground mb-4 flex items-center justify-center gap-3">
                    <i class="fas fa-clipboard-check text-primary"></i>
                    {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Validation des Dépenses' : 'Expense Validation' }}
                </h1>
                <p class="text-xl text-muted-foreground">
                    {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Gérez et validez les dépenses en attente' : 'Manage and validate pending expenses' }}
                </p>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-gradient-to-r from-primary to-primary/80 rounded-xl p-6 text-primary-foreground text-center">
                    <div class="text-3xl font-bold mb-2">{{ $depenses->total() }}</div>
                    <div class="text-primary-foreground/80 text-sm uppercase tracking-wide">
                        {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'En attente' : 'Pending' }}
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white text-center">
                    <div class="text-3xl font-bold mb-2">{{ number_format($depenses->sum('prix'), 0, ',', ' ') }} FCFA</div>
                    <div class="text-green-100 text-sm uppercase tracking-wide">
                        {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Total' : 'Total' }}
                    </div>
                </div>
                <div class="bg-gradient-to-r from-secondary to-secondary/80 rounded-xl p-6 text-secondary-foreground text-center">
                    <div class="text-3xl font-bold mb-2">{{ $depenses->where('date', '>=', today())->count() }}</div>
                    <div class="text-secondary-foreground/80 text-sm uppercase tracking-wide">
                        {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Aujourd\'hui' : 'Today' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alert-container" class="mb-6"></div>

        <!-- Content -->
        <div class="bg-card/80 backdrop-blur-sm rounded-2xl shadow-xl border border-border/20 overflow-hidden">
            @if($depenses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted text-muted-foreground">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">
                                    <i class="fas fa-tag mr-2"></i>{{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Dépense' : 'Expense' }}
                                </th>
                                <th class="px-6 py-4 text-left font-semibold">
                                    <i class="fas fa-category mr-2"></i>{{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Type' : 'Type' }}
                                </th>
                                <th class="px-6 py-4 text-left font-semibold">
                                    <i class="fas fa-money-bill-wave mr-2"></i>{{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Prix' : 'Price' }}
                                </th>
                                <th class="px-6 py-4 text-left font-semibold hidden md:table-cell">
                                    <i class="fas fa-user mr-2"></i>{{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Auteur' : 'Author' }}
                                </th>
                                <th class="px-6 py-4 text-left font-semibold hidden lg:table-cell">
                                    <i class="fas fa-calendar mr-2"></i>Date
                                </th>
                                <th class="px-6 py-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card">
                            @foreach($depenses as $depense)
                                <tr id="depense-{{ $depense->id }}" class="border-b border-border hover:bg-accent transition-all duration-200">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-foreground">{{ $depense->nom }}</div>
                                        @if($depense->matiere)
                                            <div class="text-sm text-muted-foreground">
                                                {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Matière:' : 'Material:' }} {{ $depense->matiere->nom ?? 'N/A' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $typeClasses = [
                                                'achat_matiere' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'livraison_matiere' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'reparation' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                'depense_fiscale' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                                'autre' => 'bg-muted text-muted-foreground'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeClasses[$depense->type] ?? 'bg-muted text-muted-foreground' }}">
                                            {{ str_replace('_', ' ', $depense->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-bold text-primary">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell text-foreground">
                                        {{ $depense->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell text-muted-foreground">
                                        {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                            <button onclick="confirmDepense({{ $depense->id }})" 
                                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2 text-sm">
                                                <i class="fas fa-check"></i>
                                                {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Confirmer' : 'Confirm' }}
                                            </button>
                                            <button onclick="cancelDepense({{ $depense->id }})" 
                                                    class="px-4 py-2 bg-destructive hover:bg-destructive/90 text-destructive-foreground rounded-lg font-medium transition-colors flex items-center justify-center gap-2 text-sm">
                                                <i class="fas fa-times"></i>
                                                {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Annuler' : 'Cancel' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 bg-muted/50">
                    {{ $depenses->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-check-circle text-6xl text-green-500 mb-6"></i>
                    <h3 class="text-2xl font-bold text-foreground mb-4">
                        {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Aucune dépense en attente' : 'No pending expenses' }}
                    </h3>
                    <p class="text-muted-foreground">
                        {{ ($isFrench ? 'fr' : 'en') === 'fr' ? 'Toutes les dépenses ont été traitées!' : 'All expenses have been processed!' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    const isFrench = '{{ ($isFrench ? 'fr' : 'en') }}' === 'fr';
    
    const messages = {
        fr: {
            confirmQuestion: 'Confirmer cette dépense? Une transaction sera créée.',
            cancelQuestion: 'Annuler cette dépense?',
            confirmError: 'Erreur lors de la confirmation.',
            cancelError: 'Erreur lors de l\'annulation.'
        },
        en: {
            confirmQuestion: 'Confirm this expense? A transaction will be created.',
            cancelQuestion: 'Cancel this expense?',
            confirmError: 'Error during confirmation.',
            cancelError: 'Error during cancellation.'
        }
    };

    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-destructive';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        alertContainer.innerHTML = `
            <div class="${bgColor} text-white px-6 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                <i class="fas fa-${icon}"></i>
                <span>${message}</span>
            </div>
        `;
        
        setTimeout(() => alertContainer.innerHTML = '', 5000);
    }

    function confirmDepense(id) {
        if (confirm(messages[isFrench ? 'fr' : 'en'].confirmQuestion)) {
            processExpense(id, 'confirm');
        }
    }

    function cancelDepense(id) {
        if (confirm(messages[isFrench ? 'fr' : 'en'].cancelQuestion)) {
            processExpense(id, 'cancel');
        }
    }

    function processExpense(id, action) {
        const row = document.getElementById(`depense-${id}`);
        row.style.opacity = '0.5';
        
        fetch(`/depenses/validation/${action}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => {
                    row.remove();
                    if (document.querySelectorAll('tbody tr').length === 0) location.reload();
                }, 1000);
            } else {
                showAlert(data.message, 'error');
                row.style.opacity = '1';
            }
        })
        .catch(() => {
            const errorMsg = action === 'confirm' ? messages[isFrench ? 'fr' : 'en'].confirmError : messages[isFrench ? 'fr' : 'en'].cancelError;
            showAlert(errorMsg, 'error');
            row.style.opacity = '1';
        });
    }
</script>
@endpush
@endsection
