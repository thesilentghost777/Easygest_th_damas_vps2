@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Modifier le salaire" : "Edit Salary" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
                <h1 class="text-2xl font-bold text-gray-900 mt-4">
                    {{ $isFrench ? "Modifier le salaire" : "Edit Salary" }}
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden">
                <!-- Mobile Card Header -->
                <div class="lg:hidden bg-blue-600 px-4 py-4">
                    <h2 class="text-lg font-medium text-white">
                        {{ $isFrench ? "Modification du salaire" : "Salary Modification" }}
                    </h2>
                </div>

                <!-- Form Content -->
                <div class="p-4 lg:p-6">
                    <form id="mainForm" action="{{ route('salaires.update', $salaire->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="pin" id="bypassPin">

                        <!-- Employee Info (Read-only) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Employé" : "Employee" }}
                            </label>
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $salaire->employe->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $salaire->employe->secteur }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Amount -->
                        <div class="space-y-2">
                            <label for="somme" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Montant du salaire" : "Salary Amount" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="somme" id="somme" 
                                       value="{{ $salaire->somme }}" step="0.01"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 pr-16"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3 lg:space-y-0 lg:flex lg:justify-end lg:space-x-3">
                            <a href="{{ route('salaires.index') }}"
                               class="w-full lg:w-auto inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200 active:scale-95 lg:active:scale-100">
                                {{ $isFrench ? "Annuler" : "Cancel" }}
                            </a>
                            <button type="submit"
                                    class="w-full lg:w-auto inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                {{ $isFrench ? "Mettre à jour" : "Update" }}
                            </button>
                        </div>
                    </form>
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
            <p class="text-gray-600 mt-2 text-sm">
                {{ $isFrench ? "Veuillez entrer votre code PIN pour confirmer cette action" : "Please enter your PIN code to confirm this action" }}
            </p>
        </div>
        
        <!-- Modal Body -->
        <form id="pinForm" class="px-6 pb-6">
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
const flag = @json($flag->flag);

document.getElementById('mainForm').addEventListener('submit', function (e) {
    if (flag == true) {
        e.preventDefault();
        document.getElementById('bypassPin').value = '100009';
        this.submit();
    } else {
        e.preventDefault();
        openPinModal();
    }
});

document.getElementById('pinForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const pin = document.getElementById('pinInput').value;

    if (pin.trim().length === 6) {
        document.getElementById('bypassPin').value = pin;
        closePinModal();
        document.getElementById('mainForm').submit();
    } else {
        document.getElementById('pinError').classList.remove('hidden');
        if (navigator.vibrate) {
            navigator.vibrate([100, 50, 100]);
        }
    }
});

function openPinModal() {
    const modal = document.getElementById('pinModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-95');
        modal.querySelector('.transform').classList.add('scale-100');
        modal.querySelector('input[name="pin"]').focus();
    }, 50);
}

function closePinModal() {
    const modal = document.getElementById('pinModal');
    modal.classList.add('opacity-0');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 300);
    document.getElementById('pinError').classList.add('hidden');
    document.getElementById('pinInput').value = '';
}

document.getElementById('togglePin').addEventListener('click', () => {
    const input = document.getElementById('pinInput');
    input.type = input.type === 'password' ? 'text' : 'password';
});

// Close modal on outside click
document.getElementById('pinModal').addEventListener('click', function(e) {
    if (e.target === this) {
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
