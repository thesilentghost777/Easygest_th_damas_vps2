
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <div class="md:hidden bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
        <div class="px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">
                        {{ $isFrench ? 'Attribution de Primes' : 'Bonus Attribution' }}
                    </h1>
                    <p class="text-indigo-100 text-sm mt-1">
                        {{ $isFrench ? 'Gérer les primes des employés' : 'Manage employee bonuses' }}
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <i class="fas fa-award text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:block bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $isFrench ? 'Attribution de Primes' : 'Bonus Attribution' }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{ $isFrench ? 'Attribuer et gérer les primes des employés' : 'Award and manage employee bonuses' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-shake">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Attribution Form -->
        <div class="bg-white rounded-2xl md:rounded-xl shadow-sm border border-gray-100 mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Attribuer une Prime' : 'Award a Bonus' }}
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('primes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Employee Selection -->
                    <div>
                        <label for="id_employe" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Employé' : 'Employee' }} *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <select name="id_employe" id="id_employe" required
                                class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 bg-white h-12 text-sm font-medium">
                                <option value="">{{ $isFrench ? '-- Sélectionner un employé --' : '-- Select an employee --' }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Prime Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="libelle" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Libellé' : 'Description' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="libelle" id="libelle" required
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 bg-white h-12 text-sm font-medium"
                                    placeholder="{{ $isFrench ? 'Ex: Prime de performance' : 'Ex: Performance bonus' }}">
                            </div>
                        </div>

                        <div>
                            <label for="montant" class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-coins text-gray-400"></i>
                                </div>
                                <input type="number" name="montant" id="montant" required min="0" step="1"
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 bg-white h-12 text-sm font-medium"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- PIN -->
                    <div>
                        <label for="pin" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Code PIN' : 'PIN Code' }} *
                        </label>
                        <div class="relative max-w-xs">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="pin" id="pin" required maxlength="6"
                                class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 bg-white h-12 text-sm font-medium text-center"
                                placeholder="••••••">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i>
                            {{ $isFrench ? 'Attribuer la Prime' : 'Award Bonus' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Primes List -->
        @if($primes->count() > 0)
            <div class="bg-white rounded-2xl md:rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $isFrench ? 'Primes Attribuées' : 'Awarded Bonuses' }}
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($primes as $prime)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $prime->user->name }}</h3>
                                            <p class="text-gray-500 text-sm">{{ $prime->libelle }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>{{ $prime->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-green-600">
                                        {{ number_format($prime->montant, 0, ',', ' ') }}
                                    </p>
                                    <p class="text-gray-500 text-sm">FCFA</p>
                                    <div class="flex items-center space-x-2 mt-3">
                                        <a href="{{ route('primes.edit', $prime->id) }}" 
                                           class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-amber-200 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        <button onclick="confirmDelete({{ $prime->id }})"
                                                class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isFrench ? 'Confirmer la suppression' : 'Confirm deletion' }}
                </h3>
            </div>
            <p class="text-gray-600 mb-6">
                {{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette prime ? Cette action est irréversible.' : 'Are you sure you want to delete this bonus? This action cannot be undone.' }}
            </p>
            
            <form id="deleteForm" method="POST" class="mb-4">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Code PIN pour confirmer' : 'PIN code to confirm' }}
                    </label>
                    <input type="password" name="pin" required maxlength="6"
                           class="w-full border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-0 h-12 text-center font-medium"
                           placeholder="••••••">
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 text-white py-3 rounded-xl font-medium hover:bg-red-700 transition-colors">
                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(primeId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/primes/${primeId}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>
@endsection
