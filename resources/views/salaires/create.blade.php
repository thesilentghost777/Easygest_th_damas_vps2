@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
  

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-xl mx-auto">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
                <h2 class="text-2xl font-bold text-gray-900 mt-4">
                    {{ $isFrench ? "Gestion des salaires" : "Salary Management" }}
                </h2>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden">
                <!-- Mobile Card Header -->
                <div class="lg:hidden bg-blue-600 px-4 py-4">
                    <h2 class="text-lg font-medium text-white">
                        {{ $isFrench ? "Nouveau salaire" : "New Salary" }}
                    </h2>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 m-4 lg:m-6 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-green-800">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                <!-- Form Content -->
                <div class="p-4 lg:p-6">
                    @if(isset($flag) && $flag->flag==true)
                        <!-- Direct Form (Flag Enabled) -->
                        <form action="{{ route('store-salaire') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="pin" value="100009">
                            
                            <!-- Employee Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? "Employé" : "Employee" }}
                                </label>
                                <div class="relative">
                                    <select name="id_employe" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2" required>
                                        <option value="">
                                            {{ $isFrench ? "Sélectionner un employé" : "Select an employee" }}
                                        </option>
                                        @foreach($employes as $employe)
                                            <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Salary Amount -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? "Salaire mensuel" : "Monthly Salary" }}
                                </label>
                                <div class="relative">
                                    <input type="number" name="somme" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 pr-16"
                                           required min="0" step="1000"
                                           placeholder="{{ $isFrench ? 'Montant en FCFA' : 'Amount in FCFA' }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? "Enregistrer" : "Save" }}
                            </button>
                        </form>
                    @else
                        <!-- PIN Form (Flag Disabled) -->
                        <form id="salaireForm" class="space-y-6">
                            <!-- Employee Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? "Employé" : "Employee" }}
                                </label>
                                <div class="relative">
                                    <select id="id_employe" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2" required>
                                        <option value="">
                                            {{ $isFrench ? "Sélectionner un employé" : "Select an employee" }}
                                        </option>
                                        @foreach($employes as $employe)
                                            <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Salary Amount -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $isFrench ? "Salaire mensuel" : "Monthly Salary" }}
                                </label>
                                <div class="relative">
                                    <input type="number" id="somme" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 pr-16"
                                           required min="0" step="1000"
                                           placeholder="{{ $isFrench ? 'Montant en FCFA' : 'Amount in FCFA' }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="button" onclick="openPinModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? "Enregistrer" : "Save" }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pinModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 transform transition-all duration-300 scale-95">
        <!-- Modal Header -->
        <div class="text-center p-6 pb-4">
            <div class="bg-blue-100 rounded-full p-4 inline-block mb-4 animate-pulse">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">
                {{ $isFrench ? "Confirmation requise" : "Confirmation Required" }}
            </h3>
            <p class="text-gray-600 mt-2">
                {{ $isFrench ? "Veuillez entrer votre code PIN pour enregistrer ce salaire" : "Please enter your PIN code to save this salary" }}
            </p>
        </div>
        
        <!-- Modal Body -->
        <form id="pinForm" action="{{ route('store-salaire') }}" method="POST" class="px-6 pb-6">
            @csrf
            <input type="hidden" name="id_employe" id="modalEmployeId">
            <input type="hidden" name="somme" id="modalSomme">
            
            <div class="mb-6">
                <div class="relative">
                    <input type="password" name="pin" id="pinInput" autocomplete="off" maxlength="6"
                           class="block w-full h-14 text-center text-xl tracking-widest font-bold bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="• • • • • •" required>
                    <button type="button" id="togglePin" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div id="pinError" class="text-red-500 text-sm mt-2 hidden">
                    {{ $isFrench ? "Code PIN incorrect. Veuillez réessayer." : "Incorrect PIN code. Please try again." }}
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="flex gap-3">
                <button type="button" onclick="closePinModal()" 
                        class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-medium transition-all duration-200 active:scale-95">
                    {{ $isFrench ? "Annuler" : "Cancel" }}
                </button>
                <button type="submit" 
                        class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 rounded-xl text-white font-medium transition-all duration-200 active:scale-95 shadow-lg">
                    {{ $isFrench ? "Valider" : "Validate" }}
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
    
    input:focus, select:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
    
    button:active {
        transform: scale(0.95);
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    button:active, .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>

<script>
function openPinModal() {
    const idEmploye = document.getElementById('id_employe').value;
    const somme = document.getElementById('somme').value;
    
    if (!idEmploye || !somme) {
        alert('{{ $isFrench ? "Veuillez remplir tous les champs obligatoires" : "Please fill all required fields" }}');
        return;
    }
    
    document.getElementById('modalEmployeId').value = idEmploye;
    document.getElementById('modalSomme').value = somme;
    
    const modal = document.getElementById('pinModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-95');
        modal.querySelector('.transform').classList.add('scale-100');
        document.getElementById('pinInput').focus();
    }, 50);
}

function closePinModal() {
    const modal = document.getElementById('pinModal');
    modal.classList.add('opacity-0');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('pinInput').value = '';
    }, 300);
}

document.getElementById('togglePin').addEventListener('click', function() {
    const pinInput = document.getElementById('pinInput');
    pinInput.type = pinInput.type === 'password' ? 'text' : 'password';
});

document.getElementById('pinModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePinModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('pinModal').classList.contains('hidden')) {
        closePinModal();
    }
});

// Add input animations
document.querySelectorAll('input, select').forEach(element => {
    element.addEventListener('focus', function() {
        this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
    });
    
    element.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
    });
});
</script>
@endsection
