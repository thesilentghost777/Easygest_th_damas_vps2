@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Détail du prêt" : "Loan Details" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-blue-600 px-4 lg:px-6 py-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-2">
                <h2 class="text-lg lg:text-xl font-bold text-white">
                    {{ $isFrench ? "Détail du prêt" : "Loan Details" }} - {{ $employee->name }}
                </h2>
            </div>

            <!-- Messages -->
            <div class="p-4 lg:p-6">
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Loan Information Card -->
                    <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Information sur le prêt" : "Loan Information" }}
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                <span class="text-gray-600">{{ $isFrench ? "Prêt restant:" : "Remaining loan:" }}</span>
                                <span class="font-bold text-blue-600">{{ number_format($loanData->pret, 0, ',', ' ') }} XAF</span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                <span class="text-gray-600">{{ $isFrench ? "Remboursement mensuel actuel:" : "Current monthly repayment:" }}</span>
                                <span class="font-bold text-red-600">{{ number_format($loanData->remboursement, 0, ',', ' ') }} XAF</span>
                            </div>

                            @php
                            $totalRepaid = DB::table('loan_repayments')
                                            ->where('user_id', $employee->id)
                                            ->sum('amount');
                            @endphp

                            <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                <span class="text-gray-600">{{ $isFrench ? "Total déjà remboursé:" : "Total already repaid:" }}</span>
                                <span class="font-bold text-green-600">{{ number_format($totalRepaid, 0, ',', ' ') }} XAF</span>
                            </div>
                        </div>
                    </div>

                    <!-- Repayment Form Card -->
                    <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <div class="bg-green-100 rounded-full p-2 mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Définir le remboursement mensuel" : "Set Monthly Repayment" }}
                        </h3>

                        <form id="repaymentForm" action="{{ route('loans.set-monthly-repayment', $employee->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="remboursement" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? "Montant à rembourser ce mois" : "Amount to repay this month" }}
                                </label>
                                <div class="relative">
                                    <input type="number" name="remboursement" id="remboursement" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 pr-16" 
                                           placeholder="0" min="0" max="{{ $loanData->pret }}" value="{{ $loanData->remboursement }}" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm">XAF</span>
                                    </div>
                                </div>
                                @error('remboursement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? "Enregistrer" : "Save" }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Repayment History -->
                <div class="bg-gray-50 rounded-xl p-4 lg:p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="bg-purple-100 rounded-full p-2 mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        {{ $isFrench ? "Historique des remboursements" : "Repayment History" }}
                    </h3>

                    @if($repaymentHistory->isEmpty())
                        <div class="text-center py-8">
                            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 italic">{{ $isFrench ? "Aucun remboursement effectué." : "No repayments made." }}</p>
                        </div>
                    @else
                        <!-- Mobile View -->
                        <div class="lg:hidden space-y-3">
                            @foreach($repaymentHistory as $repayment)
                            <div class="bg-white p-4 rounded-lg border border-gray-100">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-600">{{ Carbon\Carbon::parse($repayment->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ number_format($repayment->amount, 0, ',', ' ') }} XAF</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? "Date" : "Date" }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? "Montant" : "Amount" }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($repaymentHistory as $repayment)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Carbon\Carbon::parse($repayment->created_at)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($repayment->amount, 0, ',', ' ') }} XAF</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pinModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
    <div id="pinModalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="text-center p-6 pb-4">
            <div class="bg-blue-100 rounded-full p-4 inline-block mb-4 animate-pulse">
                <svg class="w-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">
                {{ $isFrench ? "Vérification de sécurité" : "Security Verification" }}
            </h3>
            <p class="text-gray-600 mt-2 text-sm">
                {{ $isFrench ? "Veuillez entrer votre code PIN à 6 chiffres pour confirmer" : "Please enter your 6-digit PIN code to confirm" }}
            </p>
        </div>
        
        <!-- Modal Body -->
        <form id="pinForm" class="px-6 pb-6">
            <div class="mb-6">
                <div class="flex justify-center">
                    <input type="password" pattern="[0-9]*" inputmode="numeric" maxlength="6" id="pin" name="pin" 
                           class="w-full text-center py-3 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg tracking-widest font-bold" 
                           placeholder="• • • • • •" required>
                </div>
                <p id="pinError" class="mt-2 text-sm text-red-600 hidden">
                    {{ $isFrench ? "Le code PIN doit contenir 6 chiffres" : "PIN code must contain 6 digits" }}
                </p>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" id="cancelPinBtn" class="flex-1 bg-gray-100 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium active:scale-95">
                    {{ $isFrench ? "Annuler" : "Cancel" }}
                </button>
                <button type="submit" id="confirmPinBtn" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium active:scale-95 shadow-lg">
                    {{ $isFrench ? "Confirmer" : "Confirm" }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    input:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const repaymentForm = document.getElementById('repaymentForm');
    const pinModal = document.getElementById('pinModal');
    const pinModalContent = document.getElementById('pinModalContent');
    const pinForm = document.getElementById('pinForm');
    const pinInput = document.getElementById('pin');
    const pinError = document.getElementById('pinError');
    const cancelPinBtn = document.getElementById('cancelPinBtn');
    
    // Handle main form submission
    repaymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show modal with animation
        pinModal.classList.remove('hidden');
        setTimeout(() => {
            pinModal.classList.remove('opacity-0');
            pinModalContent.classList.remove('scale-95', 'opacity-0');
            pinModalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
        
        // Focus on PIN input
        setTimeout(() => {
            pinInput.focus();
        }, 300);
    });
    
    // Force PIN field to accept only numbers
    pinInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Show/hide error message
        if (this.value.length < 6 && this.value.length > 0) {
            pinError.classList.remove('hidden');
        } else {
            pinError.classList.add('hidden');
        }
    });
    
    // Cancel button
    cancelPinBtn.addEventListener('click', function() {
        closeModal();
    });
    
    // Close modal when clicking outside
    pinModal.addEventListener('click', function(e) {
        if (e.target === pinModal) {
            closeModal();
        }
    });
    
    // Handle PIN form submission
    pinForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check that PIN has 6 digits
        if (pinInput.value.length !== 6 || !/^\d+$/.test(pinInput.value)) {
            pinError.classList.remove('hidden');
            pinInput.focus();
            return;
        }
        
        // Create hidden field for PIN in main form
        const pinField = document.createElement('input');
        pinField.type = 'hidden';
        pinField.name = 'pin';
        pinField.value = pinInput.value;
        
        // Add field to form and submit
        repaymentForm.appendChild(pinField);
        repaymentForm.submit();
        
        // Vibration feedback on mobile
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
    
    // Function to close modal
    function closeModal() {
        pinModal.classList.add('opacity-0');
        pinModalContent.classList.add('scale-95', 'opacity-0');
        pinModalContent.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            pinModal.classList.add('hidden');
            pinInput.value = '';
            pinError.classList.add('hidden');
        }, 300);
    }
    
    // Listen for Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !pinModal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Add input animations
    document.querySelectorAll('input').forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
        
        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
    });
});
</script>
@endsection
