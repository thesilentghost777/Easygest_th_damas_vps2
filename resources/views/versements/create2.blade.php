@extends('layouts.app')


@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                            {{ $isFrench ? 'Nouveau Versement' : 'New Payment' }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ $isFrench ? 'Créer un nouveau versement dans le système' : 'Create a new payment in the system' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('versements.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 hover:scale-105 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="xl:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-plus-circle mr-3"></i>
                            {{ $isFrench ? 'Détails du Versement' : 'Payment Details' }}
                        </h2>
                    </div>

                    <form action="{{ route('versements.store') }}" method="POST" id="versementForm" class="p-6 space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Selection -->
                            <div class="space-y-2">
                                <label for="verseur_id" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Utilisateur verseur' : 'Payer User' }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select name="verseur_id" id="verseur_id" 
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('verseur_id') border-red-300 @enderror" 
                                            required>
                                        <option value="">
                                            {{ $isFrench ? '-- Sélectionnez un utilisateur --' : '-- Select a user --' }}
                                        </option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('verseur_id') == $user->id ? 'selected' : '' }}
                                                    data-role="{{ $user->role }}" data-secteur="{{ $user->secteur }}">
                                                {{ $user->name }} ({{ $user->role }}{{ $user->secteur ? ' - ' . $user->secteur : '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('verseur_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div class="space-y-2">
                                <label for="date" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Date du versement' : 'Payment Date' }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="date" id="date" 
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('date') border-red-300 @enderror" 
                                           value="{{ old('date', date('Y-m-d')) }}" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                </div>
                                @error('date')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="libelle" class="block text-sm font-semibold text-gray-700">
                                {{ $isFrench ? 'Libellé' : 'Description' }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="libelle" id="libelle" 
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('libelle') border-red-300 @enderror" 
                                       value="{{ old('libelle') }}" 
                                       placeholder="{{ $isFrench ? 'Description du versement' : 'Payment description' }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-edit text-gray-400"></i>
                                </div>
                            </div>
                            @error('libelle')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Amount -->
                            <div class="space-y-2">
                                <label for="montant" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="montant" id="montant" 
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('montant') border-red-300 @enderror" 
                                           value="{{ old('montant') }}" 
                                           step="0.01" min="0.01" 
                                           placeholder="0.00" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 font-medium">FCFA</span>
                                    </div>
                                </div>
                                @error('montant')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PIN -->
                            <div class="space-y-2">
                                <label for="pin" class="block text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Code PIN de validation' : 'Validation PIN Code' }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="pin" id="pin" 
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('pin') border-red-300 @enderror" 
                                           placeholder="{{ $isFrench ? 'Votre code PIN' : 'Your PIN code' }}"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                </div>
                                @error('pin')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500">
                                    {{ $isFrench ? 'Votre code PIN personnel pour valider cette opération' : 'Your personal PIN code to validate this operation' }}
                                </p>
                            </div>
                        </div>

                        <!-- User Info Display -->
                        <div id="userInfo" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 animate-fade-in">
                            <h6 class="font-semibold text-blue-800 flex items-center mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ $isFrench ? 'Informations utilisateur' : 'User Information' }}
                            </h6>
                            <div id="userDetails" class="text-blue-700"></div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100">
                            <button type="submit" 
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                {{ $isFrench ? 'Enregistrer le versement' : 'Save Payment' }}
                            </button>
                            <a href="{{ route('versements.index') }}" 
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 hover:scale-105 shadow-sm">
                                <i class="fas fa-times mr-2"></i>
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="space-y-6">
                <!-- Statistics Cards -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            {{ $isFrench ? 'Récapitulatif' : 'Summary' }}
                        </h3>
                    </div>
                    
                    <div class="p-4 space-y-4">
                        <!-- Pending Amount -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($total_non_valide, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Validated Amount -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">
                                            {{ $isFrench ? 'Validés' : 'Validated' }}
                                        </p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($total_valide, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calculator text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">
                                            {{ $isFrench ? 'Total' : 'Total' }}
                                        </p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($total_non_valide + $total_valide, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                @if($versements->count() > 0)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            {{ $isFrench ? 'Versements récents' : 'Recent Payments' }}
                        </h3>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @foreach($versements->take(5) as $versement)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $versement->verseur ? $versement->verseur_name($versement->verseur) : 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">{{ $versement->libelle }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($versement->date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="ml-4 flex flex-col items-end">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($versement->montant, 0, ',', ' ') }} FCFA
                                    </p>
                                    @if($versement->status == 1)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Validé' : 'Validated' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const verseurSelect = document.getElementById('verseur_id');
    const userInfo = document.getElementById('userInfo');
    const userDetails = document.getElementById('userDetails');
    const montantInput = document.getElementById('montant');
    const form = document.getElementById('versementForm');

    // Show user information when user is selected
    verseurSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const role = selectedOption.dataset.role;
            const secteur = selectedOption.dataset.secteur;
            const userName = selectedOption.text.split(' (')[0];
            
            let details = `<strong>{{ $isFrench ? 'Nom:' : 'Name:' }}</strong> ${userName}<br>`;
            details += `<strong>{{ $isFrench ? 'Rôle:' : 'Role:' }}</strong> ${role}`;
            if (secteur) {
                details += `<br><strong>{{ $isFrench ? 'Secteur:' : 'Sector:' }}</strong> ${secteur}`;
            }
            
            userDetails.innerHTML = details;
            userInfo.classList.remove('hidden');
        } else {
            userInfo.classList.add('hidden');
        }
    });

    // Format amount display
    montantInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.title = `${value.toLocaleString()} FCFA`;
        }
    });

    // Form validation and confirmation
    form.addEventListener('submit', function(e) {
        const verseurId = verseurSelect.value;
        const montant = parseFloat(montantInput.value);
        const pin = document.getElementById('pin').value;
        
        if (!verseurId) {
            e.preventDefault();
            alert('{{ $isFrench ? 'Veuillez sélectionner un utilisateur verseur.' : 'Please select a payer user.' }}');
            return false;
        }
        
        if (!montant || montant <= 0) {
            e.preventDefault();
            alert('{{ $isFrench ? 'Veuillez entrer un montant valide.' : 'Please enter a valid amount.' }}');
            return false;
        }
        
        if (!pin) {
            e.preventDefault();
            alert('{{ $isFrench ? 'Veuillez entrer votre code PIN.' : 'Please enter your PIN code.' }}');
            return false;
        }
        
        // Confirmation before submission
        const selectedUser = verseurSelect.options[verseurSelect.selectedIndex].text.split(' (')[0];
        const confirmMessage = `{{ $isFrench ? 'Êtes-vous sûr de vouloir créer un versement de' : 'Are you sure you want to create a payment of' }} ${montant.toLocaleString()} FCFA {{ $isFrench ? 'pour' : 'for' }} ${selectedUser} ?`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection