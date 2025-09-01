@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Validation des retraits" : "Withdrawal validation" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
            <h2 class="text-2xl font-bold text-gray-800 mt-4 flex items-center">
                <span class="bg-green-500 w-4 h-4 inline-block mr-3 rounded-full"></span>
                {{ $isFrench ? "Validation des retraits" : "Withdrawal validation" }}
            </h2>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-sm lg:shadow-lg rounded-lg lg:rounded-xl border-l-4 border-blue-600">
            <div class="p-4 lg:p-6">
                @if(!$as)
                <!-- Empty State -->
                <div class="lg:hidden">
                    <div class="bg-gray-50 p-8 rounded-xl text-center">
                        <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-2">{{ $isFrench ? "Aucune demande" : "No requests" }}</h3>
                        <p class="text-gray-600 text-sm">{{ $isFrench ? "Aucune demande de retrait en attente." : "No withdrawal requests pending." }}</p>
                    </div>
                </div>
                <div class="hidden lg:block bg-gray-50 p-8 rounded-none text-center">
                    <p class="text-gray-600 font-medium">{{ $isFrench ? "Aucune demande de retrait en attente." : "No withdrawal requests pending." }}</p>
                </div>
            @else
                <!-- Mobile Card View - Single Item -->
                <div class="lg:hidden space-y-4">
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-4 shadow-sm transform transition-all duration-200 active:scale-98">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-700">{{ $as->employe->name }}</h3>
                                    <p class="text-gray-600 font-medium">
                                        {{ $isFrench ? "Montant" : "Amount" }}: <span class="text-green-600 font-bold">{{ number_format($as->sommeAs, 0, ',', ' ') }} XAF</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($flag) && $flag->flag==true)
                            <form action="{{ route('recup-retrait') }}" method="POST">
                                @csrf
                                <input type="hidden" name="as_id" value="{{ $as->id }}">
                                <input type="hidden" name="pin" value="100009">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium py-3 px-6 rounded-xl border-2 border-blue-600 transition-all duration-200 active:scale-95 flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $isFrench ? "Valider le retrait" : "Validate withdrawal" }}
                                </button>
                            </form>
                        @else
                            <button type="button" onclick="openPinModal({{ $as->id }})" class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium py-3 px-6 rounded-xl border-2 border-blue-600 transition-all duration-200 active:scale-95 flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $isFrench ? "Valider le retrait" : "Validate withdrawal" }}
                            </button>
                        @endif
                    </div>
                </div>
            
                <!-- Desktop View - Single Item -->
                <div class="hidden lg:block space-y-4 md:space-y-6">
                    <div class="border-2 border-gray-200 rounded-none p-4 md:p-6 hover:shadow-md transition-all duration-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-700">{{ $as->employe->name }}</h3>
                                <p class="text-gray-600 font-medium mt-1">{{ $isFrench ? "Montant" : "Amount" }}: <span class="text-green-600 font-bold">{{ number_format($as->sommeAs, 0, ',', ' ') }} XAF</span></p>
                            </div>
                            
                            @if(isset($flag) && $flag->flag==true)
                                <form action="{{ route('recup-retrait') }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <input type="hidden" name="as_id" value="{{ $as->id }}">
                                    <input type="hidden" name="pin" value="100009">
                                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-none border-2 border-blue-600 transition-colors duration-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $isFrench ? "Valider le retrait" : "Validate withdrawal" }}
                                    </button>
                                </form>
                            @else
                                <button type="button" onclick="openPinModal({{ $as->id }})" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-none border-2 border-blue-600 transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $isFrench ? "Valider le retrait" : "Validate withdrawal" }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pinModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 mx-4">
        <div class="text-center mb-6">
            <div class="bg-blue-100 rounded-full p-4 inline-block mb-4 animate-pulse">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $isFrench ? "Confirmation requise" : "Confirmation required" }}</h3>
            <p class="text-gray-600 mt-2">{{ $isFrench ? "Veuillez entrer votre code PIN pour valider ce retrait" : "Please enter your PIN code to validate this withdrawal" }}</p>
        </div>
        
        <form id="pinForm" action="{{ route('recup-retrait') }}" method="POST">
            @csrf
            <input type="hidden" name="as_id" id="modalAsId">
            
            <div class="mb-6">
                <div class="relative">
                    <input type="password" name="pin" id="pinInput" autocomplete="off" maxlength="6"
                        class="block w-full h-14 text-center text-xl tracking-widest font-bold bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="• • • • • •" required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <button type="button" id="togglePin" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="pinError" class="text-red-500 text-sm mt-2 hidden">{{ $isFrench ? "Code PIN incorrect. Veuillez réessayer." : "Incorrect PIN code. Please try again." }}</div>
            </div>
            
            <div class="flex items-center gap-3">
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
    
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
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
    function openPinModal(asId) {
        document.getElementById('modalAsId').value = asId;
        
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

    // Vibration feedback on mobile
    document.getElementById('pinForm').addEventListener('submit', function(e) {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
</script>
@endsection