@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Nouvel incident" : "New Incident" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
                <h1 class="text-3xl font-bold text-blue-600 mt-4">
                    {{ $isFrench ? "Incidents" : "Incidents" }}
                </h1>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden">
                <!-- Mobile Card Header -->
                <div class="lg:hidden bg-blue-600 px-4 py-4">
                    <h2 class="text-lg font-medium text-white">
                        {{ $isFrench ? "Détails de l'incident" : "Incident Details" }}
                    </h2>
                </div>

                <!-- Form Content -->
                <div class="p-4 lg:p-6">
                    <form action="{{ route('delis.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Type Field -->
                        <div class="space-y-2">
                            <label for="nom" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Type" : "Type" }}
                            </label>
                            <input type="text" name="nom" id="nom"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 transition-all duration-200"
                                   placeholder="{{ $isFrench ? 'Type d\'incident' : 'Incident type' }}"
                                   required>
                        </div>

                        <!-- Description Field -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Description" : "Description" }}
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 transition-all duration-200"
                                      placeholder="{{ $isFrench ? 'Décrivez l\'incident en détail...' : 'Describe the incident in detail...' }}"></textarea>
                        </div>

                        <!-- Amount Field -->
                        <div class="space-y-2">
                            <label for="montant" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Sanction (Montant) (F CFA)" : "Penalty (Amount) (F CFA)" }}
                            </label>
                            <div class="relative">
                                <input type="number" name="montant" id="montant"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 pr-16 transition-all duration-200"
                                       placeholder="{{ $isFrench ? 'Montant de la sanction' : 'Penalty amount' }}"
                                       min="0">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date Field -->
                        <div class="space-y-2">
                            <label for="date_incident" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Date de l'incident" : "Incident Date" }}
                            </label>
                            <div class="flex gap-2">
                                <input type="date" name="date_incident" id="date_incident"
                                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base lg:text-sm py-3 lg:py-2 transition-all duration-200">
                                <button type="button" onclick="setTodayDate()"
                                        class="inline-flex items-center px-4 py-3 border border-blue-500 text-sm font-medium rounded-lg text-blue-500 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 active:scale-95 lg:active:scale-100">
                                    {{ $isFrench ? "Aujourd'hui" : "Today" }}
                                </button>
                            </div>
                        </div>

                        <!-- Employees Selection -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? "Employés concernés" : "Concerned Employees" }}
                            </label>
                            <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <div class="space-y-3">
                                    @foreach($employes as $employe)
                                    <label class="flex items-center p-2 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="checkbox" name="employes[]" value="{{ $employe->id }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mr-3">
                                        <span class="text-sm text-gray-700 font-medium">{{ $employe->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3 lg:space-y-0 lg:flex lg:justify-end lg:space-x-3">
                            <a href="{{ route('delis.index') }}"
                               class="w-full lg:w-auto inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200 active:scale-95 lg:active:scale-100">
                                {{ $isFrench ? "Annuler" : "Cancel" }}
                            </a>
                            <button type="submit"
                                    class="w-full lg:w-auto inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 active:scale-95 lg:active:scale-100 shadow-lg lg:shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? "Enregistrer" : "Save" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    input:focus, textarea:focus, select:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
    
    button:active {
        transform: scale(0.95);
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
function setTodayDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;
    document.getElementById('date_incident').value = formattedDate;
    
    // Vibration feedback on mobile
    if (navigator.vibrate) {
        navigator.vibrate(50);
    }
}

// Add input animations
document.querySelectorAll('input, textarea, select').forEach(element => {
    element.addEventListener('focus', function() {
        this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
    });
    
    element.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
    });
});
</script>
@endsection
