@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 py-4 md:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile-First Header -->
        @include('buttons')

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-blue-800 mb-4 md:mb-0">
                {{ $isFrench ? 'Gestion des Transactions' : 'Transaction Management' }}
            </h1>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('categories.index') }}" 
                   class="mobile-btn inline-flex items-center justify-center px-4 py-3 md:py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl md:rounded-lg shadow transition-all duration-300">
                    <i class="mdi mdi-format-list-bulleted mr-3 text-xl md:text-base"></i>
                    {{ $isFrench ? 'Gestion Catégorie' : 'Category Management' }}
                </a>
                <button onclick="openModal('createTransactionModal')" 
                        class="mobile-btn inline-flex items-center justify-center px-4 py-3 md:py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl md:rounded-lg shadow transition-all duration-300">
                    <i class="mdi mdi-plus mr-3 text-xl md:text-base"></i>
                    {{ $isFrench ? 'Nouvelle Transaction' : 'New Transaction' }}
                </button>
            </div>
        </div>

        <!-- Mobile-First Search and Filter -->
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-md p-4 md:p-6 mb-6">
            <form action="{{ route('transactions.index') }}" method="GET" class="space-y-4">
                <div class="w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="{{ $isFrench ? 'Rechercher une transaction...' : 'Search for a transaction...' }}"
                           class="mobile-input w-full px-4 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select name="sort" class="mobile-input px-4 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="date" {{ request('sort') === 'date' ? 'selected' : '' }}>Date</option>
                        <option value="amount" {{ request('sort') === 'amount' ? 'selected' : '' }}>{{ $isFrench ? 'Montant' : 'Amount' }}</option>
                        <option value="type" {{ request('sort') === 'type' ? 'selected' : '' }}>Type</option>
                    </select>
                    <select name="direction" class="mobile-input px-4 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>{{ $isFrench ? 'Décroissant' : 'Descending' }}</option>
                        <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>{{ $isFrench ? 'Croissant' : 'Ascending' }}</option>
                    </select>
                    <button type="submit" class="mobile-btn px-4 py-3 md:py-2 bg-blue-600 text-white rounded-2xl md:rounded-lg hover:bg-blue-700 transition-colors">
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Mobile-First Transactions List -->
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-md overflow-hidden">
            <!-- Mobile Cards View -->
            <div class="block md:hidden p-4">
                <div class="space-y-4">
                    @forelse($transactions as $transaction)
                        <div class="mobile-card bg-gray-50 rounded-xl p-4 hover:shadow-md transition-all duration-300"
                             x-data="{ expanded: false }">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full
                                            {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-lg font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($transaction->amount, 2, ',', ' ') }} XAF
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">{{ $transaction->category->name }}</p>
                                    @if($transaction->description)
                                        <p class="text-sm text-gray-500">{{ Str::limit($transaction->description, 50) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 ml-3">
                                    <button onclick="openEditModal({{ $transaction->id }})" 
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                        <i class="mdi mdi-pencil text-lg"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $transaction->id }})" 
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                        <i class="mdi mdi-delete text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mobile-card bg-gray-50 rounded-xl p-6 text-center">
                            <i class="mdi mdi-file-document-outline text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">{{ $isFrench ? 'Aucune transaction trouvée' : 'No transactions found' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->type === 'income' ? ($isFrench ? 'Revenu' : 'Income') : ($isFrench ? 'Dépense' : 'Expense') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->category->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $transaction->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($transaction->amount, 2, ',', ' ') }} XAF
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openEditModal({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="mdi mdi-pencil text-lg"></i>
                                </button>
                                <button onclick="confirmDelete({{ $transaction->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="mdi mdi-delete text-lg"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                {{ $isFrench ? 'Aucune transaction trouvée' : 'No transactions found' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Mobile-First Transaction Modal -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 p-4">
    <div class="mobile-card relative top-10 md:top-20 mx-auto p-5 border w-full md:w-96 shadow-lg rounded-2xl md:rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">
                {{ $isFrench ? 'Nouvelle Transaction' : 'New Transaction' }}
            </h3>
            <form id="transactionForm" method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" required class="mobile-input w-full px-3 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="income">{{ $isFrench ? 'Revenu' : 'Income' }}</option>
                        <option value="outcome">{{ $isFrench ? 'Dépense' : 'Expense' }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Catégorie' : 'Category' }}</label>
                    <select name="category_id" required class="mobile-input w-full px-3 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Montant' : 'Amount' }}</label>
                    <input type="number" name="amount" step="0.01" required min="0"
                           class="mobile-input w-full px-3 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <div class="flex items-center space-x-2">
                        <input type="date" name="date" id="dateField" required
                               class="mobile-input flex-1 px-3 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" onclick="setTodayDate()"
                                class="mobile-btn px-3 py-3 md:py-2 bg-blue-500 text-white rounded-2xl md:rounded-md hover:bg-blue-600 focus:outline-none">
                            {{ $isFrench ? 'Auj.' : 'Today' }}
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="mobile-input w-full px-3 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex flex-col md:flex-row justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                            class="mobile-btn px-4 py-3 md:py-2 bg-gray-200 text-gray-800 rounded-2xl md:rounded-md hover:bg-gray-300">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                            class="mobile-btn px-4 py-3 md:py-2 bg-blue-600 text-white rounded-2xl md:rounded-md hover:bg-blue-700">
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile-First Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 p-4">
    <div class="mobile-card relative top-20 mx-auto p-5 border w-full md:w-96 shadow-lg rounded-2xl md:rounded-md bg-white">
        <div class="text-center">
            <i class="mdi mdi-alert-circle text-red-500 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $isFrench ? 'Confirmer la suppression' : 'Confirm Deletion' }}</h3>
            <p class="text-gray-500 mb-6">{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette transaction ?' : 'Are you sure you want to delete this transaction?' }}</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex flex-col md:flex-row justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="mobile-btn px-4 py-3 md:py-2 bg-gray-200 text-gray-800 rounded-2xl md:rounded-md hover:bg-gray-300">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="submit"
                        class="mobile-btn px-4 py-3 md:py-2 bg-red-600 text-white rounded-2xl md:rounded-md hover:bg-red-700">
                    {{ $isFrench ? 'Supprimer' : 'Delete' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(modalType, transaction = null) {
    const modal = document.getElementById('transactionModal');
    const form = document.getElementById('transactionForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');

    if (transaction) {
        modalTitle.textContent = '{{ $isFrench ? "Modifier la Transaction" : "Edit Transaction" }}';
        form.action = `/transactions/${transaction.id}`;
        methodField.innerHTML = '@method("PUT")';

        // Pre-fill form
        form.querySelector('[name="type"]').value = transaction.type;
        form.querySelector('[name="category_id"]').value = transaction.category_id;
        form.querySelector('[name="amount"]').value = transaction.amount;
        form.querySelector('[name="date"]').value = transaction.date;
        form.querySelector('[name="description"]').value = transaction.description || '';
    } else {
        modalTitle.textContent = '{{ $isFrench ? "Nouvelle Transaction" : "New Transaction" }}';
        form.action = '{{ route("transactions.store") }}';
        methodField.innerHTML = '';
        form.reset();
    }

    modal.classList.remove('hidden');
    
    // Add entrance animation
    const modalContent = modal.querySelector('.mobile-card');
    modalContent.style.transform = 'scale(0.9) translateY(20px)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modalContent.style.transform = 'scale(1) translateY(0)';
        modalContent.style.opacity = '1';
        modalContent.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('transactionModal');
    const modalContent = modal.querySelector('.mobile-card');
    
    modalContent.style.transform = 'scale(0.9) translateY(20px)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function setTodayDate() {
    let today = new Date().toISOString().split('T')[0];
    document.getElementById('dateField').value = today;
    
    // Add feedback animation
    const dateField = document.getElementById('dateField');
    dateField.style.transform = 'scale(1.02)';
    setTimeout(() => {
        dateField.style.transform = 'scale(1)';
        dateField.style.transition = 'transform 0.2s ease';
    }, 100);
}

function openEditModal(transactionId) {
    fetch(`/transactions/${transactionId}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('{{ $isFrench ? "Erreur lors de la récupération des données" : "Error retrieving data" }}');
            }
            return response.json();
        })
        .then(transaction => {
            openModal('edit', transaction);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ $isFrench ? "Une erreur est survenue lors de la récupération des données de la transaction" : "An error occurred while retrieving transaction data" }}');
        });
}

function confirmDelete(transactionId) {
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/transactions/${transactionId}`;
    deleteModal.classList.remove('hidden');
    
    // Add entrance animation
    const modalContent = deleteModal.querySelector('.mobile-card');
    modalContent.style.transform = 'scale(0.9)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
        modalContent.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = modal.querySelector('.mobile-card');
    
    modalContent.style.transform = 'scale(0.9)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const transactionModal = document.getElementById('transactionModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === transactionModal) {
        closeModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// Add touch feedback for mobile cards
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mobile-card').forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.1s ease';
        });
        
        card.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush
@endsection