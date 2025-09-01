@extends('layouts.app')

@section('content')
<div class="py-12 mobile:py-6 mobile:px-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mobile:px-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mobile:rounded-2xl mobile:shadow-2xl">
            <div class="p-6 mobile:p-4">
                <div class="flex justify-between items-center mb-6 mobile:flex-col mobile:text-center mobile:mb-8">
                    <div class="mobile:mb-4">
                        <div class="mobile:bg-blue-100 mobile:rounded-full mobile:w-20 mobile:h-20 mobile:flex mobile:items-center mobile:justify-center mobile:mx-auto mobile:mb-4">
                            <svg class="mobile:w-10 mobile:h-10 mobile:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mobile:text-xl mobile:text-blue-600">
                            {{ $isFrench ? 'Profil de ' . $user->name : $user->name . '\'s Profile' }}
                        </h2>
                    </div>
                    @include('buttons')
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mobile:gap-6">
                    <!-- Employee Information -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:border-0 mobile:bg-gradient-to-br mobile:from-blue-50 mobile:to-white">
                        <div class="flex items-center mb-4 mobile:justify-center mobile:flex-col mobile:text-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-3 mobile:p-4 mobile:mr-0 mobile:mb-3 mobile:bg-gradient-to-br mobile:from-blue-500 mobile:to-blue-600">
                                <svg class="w-8 h-8 text-blue-600 mobile:w-10 mobile:h-10 mobile:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mobile:text-xl mobile:text-blue-600">
                                {{ $isFrench ? 'Informations personnelles' : 'Personal Information' }}
                            </h3>
                        </div>
                        
                        <dl class="space-y-3 mobile:space-y-4">
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">
                                    {{ $isFrench ? 'Âge:' : 'Age:' }}
                                </dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600">
                                    {{ $user->age }} {{ $isFrench ? 'ans' : 'years old' }}
                                </dd>
                            </div>
                            
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">Email:</dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600 mobile:break-all">{{ $user->email }}</dd>
                            </div>
                            
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">
                                    {{ $isFrench ? 'Téléphone:' : 'Phone:' }}
                                </dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600">{{ $user->num_tel }}</dd>
                            </div>
                            
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">
                                    {{ $isFrench ? 'Secteur:' : 'Sector:' }}
                                </dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600">{{ $user->secteur }}</dd>
                            </div>
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">
                                    {{ $isFrench ? 'Role:' : 'Role:' }}
                                </dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600">{{ $user->role }}</dd>
                            </div>
                            <div class="flex justify-between mobile:flex-col mobile:text-center mobile:bg-white mobile:p-3 mobile:rounded-xl mobile:shadow-sm">
                                <dt class="text-gray-600 mobile:text-gray-500 mobile:text-sm mobile:mb-1">
                                    {{ $isFrench ? 'Année de début:' : 'Start year:' }}
                                </dt>
                                <dd class="text-gray-900 mobile:font-semibold mobile:text-blue-600">{{ $user->annee_debut_service }}</dd>
                            </div>
                            
                           
                        </dl>
                    </div>

                    <!-- Evaluation Form -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:border-0 mobile:bg-gradient-to-br mobile:from-green-50 mobile:to-white">
                        <div class="flex items-center mb-4 mobile:justify-center mobile:flex-col mobile:text-center">
                            <div class="bg-green-100 p-3 rounded-full mr-3 mobile:p-4 mobile:mr-0 mobile:mb-3 mobile:bg-gradient-to-br mobile:from-green-500 mobile:to-green-600">
                                <svg class="w-8 h-8 text-green-600 mobile:w-10 mobile:h-10 mobile:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mobile:text-xl mobile:text-green-600">
                                {{ $isFrench ? 'Évaluation' : 'Evaluation' }}
                            </h3>
                        </div>
                        
                        <form action="{{ route('employees.evaluate', $user) }}" method="POST" id="evaluationForm">
                            @csrf
                            <div class="space-y-4 mobile:space-y-6">
                                <div>
                                    <label for="note" class="block text-sm font-medium text-gray-700 mobile:text-base mobile:font-semibold mobile:text-center mobile:mb-3">
                                        {{ $isFrench ? 'Note sur 20' : 'Score out of 20' }}
                                    </label>
                                    <input type="number" name="note" id="note" min="0" max="20" step="0.5"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('note') border-red-500 @enderror mobile:py-3 mobile:px-4 mobile:text-lg mobile:text-center mobile:rounded-xl mobile:border-2 mobile:focus:border-green-500 mobile:focus:ring-2 mobile:focus:ring-green-200"
                                        required>
                                    @error('note')
                                        <p class="mt-1 text-sm text-red-600 mobile:text-center">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="appreciation" class="block text-sm font-medium text-gray-700 mobile:text-base mobile:font-semibold mobile:text-center mobile:mb-3">
                                        {{ $isFrench ? 'Appréciation' : 'Assessment' }}
                                    </label>
                                    <textarea name="appreciation" id="appreciation" rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('appreciation') border-red-500 @enderror mobile:py-3 mobile:px-4 mobile:text-base mobile:rounded-xl mobile:border-2 mobile:focus:border-green-500 mobile:focus:ring-2 mobile:focus:ring-green-200 mobile:resize-none"
                                        placeholder="{{ $isFrench ? 'Entrez votre appréciation...' : 'Enter your assessment...' }}"
                                        required></textarea>
                                    @error('appreciation')
                                        <p class="mt-1 text-sm text-red-600 mobile:text-center">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="w-full bg-blue-600 text-white rounded-md py-2 px-4 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mobile:py-4 mobile:text-lg mobile:font-semibold mobile:rounded-xl mobile:bg-gradient-to-r mobile:from-green-500 mobile:to-green-600 mobile:hover:from-green-600 mobile:hover:to-green-700 mobile:shadow-lg mobile:hover:shadow-xl mobile:transform mobile:hover:scale-105 mobile:active:scale-95 mobile:transition-all mobile:duration-300">
                                    {{ $isFrench ? 'Enregistrer l\'évaluation' : 'Save Evaluation' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile styles */
@media (max-width: 768px) {
    .mobile\:py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .mobile\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .mobile\:px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .mobile\:rounded-2xl { border-radius: 1rem; }
    .mobile\:shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .mobile\:p-4 { padding: 1rem; }
    .mobile\:p-6 { padding: 1.5rem; }
    .mobile\:p-3 { padding: 0.75rem; }
    .mobile\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .mobile\:py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .mobile\:py-4 { padding-top: 1rem; padding-bottom: 1rem; }
    .mobile\:mb-8 { margin-bottom: 2rem; }
    .mobile\:mb-6 { margin-bottom: 1.5rem; }
    .mobile\:mb-4 { margin-bottom: 1rem; }
    .mobile\:mb-3 { margin-bottom: 0.75rem; }
    .mobile\:mb-1 { margin-bottom: 0.25rem; }
    .mobile\:mr-0 { margin-right: 0px; }
    .mobile\:mt-1 { margin-top: 0.25rem; }
    .mobile\:bg-blue-100 { background-color: #dbeafe; }
    .mobile\:bg-white { background-color: #ffffff; }
    .mobile\:rounded-full { border-radius: 9999px; }
    .mobile\:rounded-xl { border-radius: 0.75rem; }
    .mobile\:w-20 { width: 5rem; }
    .mobile\:h-20 { height: 5rem; }
    .mobile\:w-10 { width: 2.5rem; }
    .mobile\:h-10 { height: 2.5rem; }
    .mobile\:w-full { width: 100%; }
    .mobile\:flex { display: flex; }
    .mobile\:items-center { align-items: center; }
    .mobile\:justify-center { justify-content: center; }
    .mobile\:mx-auto { margin-left: auto; margin-right: auto; }
    .mobile\:text-xl { font-size: 1.25rem; }
    .mobile\:text-lg { font-size: 1.125rem; }
    .mobile\:text-base { font-size: 1rem; }
    .mobile\:text-sm { font-size: 0.875rem; }
    .mobile\:text-blue-600 { color: #2563eb; }
    .mobile\:text-green-600 { color: #16a34a; }
    .mobile\:text-white { color: #ffffff; }
    .mobile\:text-gray-500 { color: #6b7280; }
    .mobile\:text-center { text-align: center; }
    .mobile\:font-bold { font-weight: 700; }
    .mobile\:font-semibold { font-weight: 600; }
    .mobile\:gap-6 { gap: 1.5rem; }
    .mobile\:flex-col { flex-direction: column; }
    .mobile\:shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .mobile\:shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .mobile\:border-0 { border-width: 0px; }
    .mobile\:border-2 { border-width: 2px; }
    .mobile\:bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .mobile\:bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .mobile\:from-blue-50 { --tw-gradient-from: #eff6ff; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(239, 246, 255, 0)); }
    .mobile\:from-green-50 { --tw-gradient-from: #f0fdf4; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(240, 253, 244, 0)); }
    .mobile\:from-blue-500 { --tw-gradient-from: #3b82f6; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0)); }
    .mobile\:from-green-500 { --tw-gradient-from: #22c55e; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(34, 197, 94, 0)); }
    .mobile\:from-gray-500 { --tw-gradient-from: #6b7280; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(107, 114, 128, 0)); }
    .mobile\:to-white { --tw-gradient-to: #ffffff; }
    .mobile\:to-blue-600 { --tw-gradient-to: #2563eb; }
    .mobile\:to-green-600 { --tw-gradient-to: #16a34a; }
    .mobile\:to-gray-600 { --tw-gradient-to: #4b5563; }
    .mobile\:focus\:border-green-500:focus { border-color: #22c55e; }
    .mobile\:focus\:ring-2:focus { box-shadow: 0 0 0 2px var(--tw-ring-color); }
    .mobile\:focus\:ring-green-200:focus { --tw-ring-color: #bbf7d0; }
    .mobile\:space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
    .mobile\:space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }
    .mobile\:break-all { word-break: break-all; }
    .mobile\:resize-none { resize: none; }
    .mobile\:hover\:from-green-600:hover { --tw-gradient-from: #16a34a; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(22, 163, 74, 0)); }
    .mobile\:hover\:to-green-700:hover { --tw-gradient-to: #15803d; }
    .mobile\:hover\:from-gray-600:hover { --tw-gradient-from: #4b5563; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(75, 85, 99, 0)); }
    .mobile\:hover\:to-gray-700:hover { --tw-gradient-to: #374151; }
    .mobile\:hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:transform { transform: translateVar(--tw-translate-x, 0) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)); }
    .mobile\:hover\:scale-105:hover { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }
    .mobile\:active\:scale-95:active { --tw-scale-x: 0.95; --tw-scale-y: 0.95; }
    .mobile\:transition-all { transition-property: all; }
    .mobile\:duration-300 { transition-duration: 300ms; }
    
    /* Touch feedback */
    * {
        -webkit-tap-highlight-color: transparent;
    }
    
    button:active {
        transform: scale(0.98);
    }
    
    input:focus, textarea:focus {
        transform: scale(1.02);
    }
}
</style>

<!-- Scripts for SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    
    // Success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: isFrench ? 'Succès!' : 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#22c55e',
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut'
            }
        });
    @endif

    // Validation error messages
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: isFrench ? 'Erreur!' : 'Error!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#ef4444'
        });
    @endif
    
    // Add entrance animations for mobile
    if (window.innerWidth <= 768) {
        const cards = document.querySelectorAll('.bg-white.p-6');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 300 + (index * 200));
        });
    }
    
    // Form validation enhancement
    const form = document.getElementById('evaluationForm');
    const noteInput = document.getElementById('note');
    const appreciationInput = document.getElementById('appreciation');
    
    form.addEventListener('submit', function(e) {
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
    
    // Real-time validation feedback
    noteInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value >= 0 && value <= 20) {
            this.style.borderColor = '#22c55e';
        } else {
            this.style.borderColor = '#ef4444';
        }
    });
    
    appreciationInput.addEventListener('input', function() {
        if (this.value.length >= 10) {
            this.style.borderColor = '#22c55e';
        } else {
            this.style.borderColor = '#ef4444';
        }
    });
});
</script>
@endsection
