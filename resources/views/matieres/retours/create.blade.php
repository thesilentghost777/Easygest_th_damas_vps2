@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 shadow-lg">
        <div class="flex items-center justify-between">
            @include('buttons')
            <div class="flex-1 text-center">
                <h1 class="text-lg font-bold">
                    {{ $isFrench ? 'Retour de Matière' : 'Material Return' }}
                </h1>
                <p class="text-blue-100 text-sm">
                    {{ $isFrench ? 'Retourner des matières assignées' : 'Return assigned materials' }}
                </p>
            </div>
            <div class="w-8"></div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isFrench ? 'Retour de Matière Première' : 'Raw Material Return' }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? 'Retourner des matières premières assignées' : 'Return assigned raw materials' }}
            </p>
        </div>
    </div>

    <!-- Error/Success Messages -->
    @if (session('success'))
    <div class="mx-4 lg:mx-auto lg:max-w-4xl mb-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm animate-pulse">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="mx-4 lg:mx-auto lg:max-w-4xl mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm animate-pulse">
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="mx-4 lg:mx-auto lg:max-w-4xl mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="mx-4 lg:mx-auto lg:max-w-4xl pb-6">
        @if($assignations->isEmpty())
            <!-- No Materials Available -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-4m-4 0H8m0 0v5a2 2 0 002 2h4a2 2 0 002-2v-5z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune matière disponible' : 'No materials available' }}
                    </h3>
                    <p class="text-gray-500">
                        {{ $isFrench ? 'Vous n\'avez pas de matières assignées avec des quantités restantes.' : 'You have no assigned materials with remaining quantities.' }}
                    </p>
                </div>
            </div>
        @else
            <!-- Return Form -->
            <form action="{{ route('matieres.retours.store') }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden">
                @csrf

                <!-- Mobile Form -->
                <div class="lg:hidden">
                    <div class="p-4 space-y-4">
                        <!-- Material Selection -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                {{ $isFrench ? 'Matière à retourner' : 'Material to return' }}
                            </label>
                            <select name="assignation_id" id="assignation_id" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                                <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                @foreach($assignations as $assignation)
                                <option value="{{ $assignation->id }}" data-quantite-restante="{{ $assignation->quantite_restante }}" data-unite="{{ $assignation->unite_assignee }}">
                                    {{ $assignation->matiere->nom }} - {{ $assignation->quantite_restante }} {{ $assignation->unite_assignee }} {{ $isFrench ? 'restantes' : 'remaining' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity to Return -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                            <label for="quantite_retournee" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $isFrench ? 'Quantité à retourner' : 'Quantity to return' }}
                            </label>
                            <div class="flex space-x-3">
                                <input type="number" name="quantite_retournee" id="quantite_retournee" min="0.001" step="0.001" class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200" required>
                                <div id="unite_display" class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600 min-w-16 text-center">
                                    -
                                </div>
                            </div>
                            <div id="quantite_max" class="mt-2 text-sm text-gray-500"></div>
                        </div>

                        <!-- Reason -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-100">
                            <label for="motif_retour" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $isFrench ? 'Motif du retour (optionnel)' : 'Return reason (optional)' }}
                            </label>
                            <textarea name="motif_retour" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 resize-none" placeholder="{{ $isFrench ? 'Expliquez pourquoi vous retournez cette matière...' : 'Explain why you are returning this material...' }}"></textarea>
                        </div>
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="p-4 bg-gray-50 space-y-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-4 rounded-xl font-bold text-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $isFrench ? 'Soumettre la demande' : 'Submit request' }}
                        </button>
                    </div>
                </div>

                <!-- Desktop Form -->
                <div class="hidden lg:block p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Material Selection -->
                        <div class="space-y-2">
                            <label for="assignation_id_desktop" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? 'Matière à retourner' : 'Material to return' }}
                            </label>
                            <select name="assignation_id" id="assignation_id_desktop" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                @foreach($assignations as $assignation)
                                <option value="{{ $assignation->id }}" data-quantite-restante="{{ $assignation->quantite_restante }}" data-unite="{{ $assignation->unite_assignee }}">
                                    {{ $assignation->matiere->nom }} - {{ $assignation->quantite_restante }} {{ $assignation->unite_assignee }} {{ $isFrench ? 'restantes' : 'remaining' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="space-y-2">
                            <label for="quantite_retournee_desktop" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? 'Quantité à retourner' : 'Quantity to return' }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="number" name="quantite_retournee" id="quantite_retournee_desktop" min="0.001" step="0.001" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <div id="unite_display_desktop" class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600 min-w-16 text-center">
                                    -
                                </div>
                            </div>
                            <div id="quantite_max_desktop" class="text-sm text-gray-500"></div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mt-6">
                        <label for="motif_retour_desktop" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Motif du retour (optionnel)' : 'Return reason (optional)' }}
                        </label>
                        <textarea name="motif_retour" id="motif_retour_desktop" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ $isFrench ? 'Expliquez pourquoi vous retournez cette matière...' : 'Explain why you are returning this material...' }}"></textarea>
                    </div>

                    <!-- Desktop Action Buttons -->
                    <div class="flex justify-end gap-4 mt-6">
                        @include('buttons')
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                            {{ $isFrench ? 'Soumettre la demande' : 'Submit request' }}
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileSelect = document.getElementById('assignation_id');
    const desktopSelect = document.getElementById('assignation_id_desktop');
    const mobileQuantity = document.getElementById('quantite_retournee');
    const desktopQuantity = document.getElementById('quantite_retournee_desktop');
    const mobileUnite = document.getElementById('unite_display');
    const desktopUnite = document.getElementById('unite_display_desktop');
    const mobileMax = document.getElementById('quantite_max');
    const desktopMax = document.getElementById('quantite_max_desktop');

    function updateDisplays(select, unite, max, quantity) {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption.value) {
            const quantiteRestante = selectedOption.getAttribute('data-quantite-restante');
            const uniteAssignee = selectedOption.getAttribute('data-unite');
            
            unite.textContent = uniteAssignee;
            max.textContent = `{{ $isFrench ? 'Maximum:' : 'Maximum:' }} ${quantiteRestante} ${uniteAssignee}`;
            quantity.max = quantiteRestante;
        } else {
            unite.textContent = '-';
            max.textContent = '';
            quantity.max = '';
        }
    }

    // Sync mobile and desktop selects
    if (mobileSelect && desktopSelect) {
        mobileSelect.addEventListener('change', function() {
            desktopSelect.value = this.value;
            updateDisplays(this, mobileUnite, mobileMax, mobileQuantity);
            updateDisplays(desktopSelect, desktopUnite, desktopMax, desktopQuantity);
        });

        desktopSelect.addEventListener('change', function() {
            mobileSelect.value = this.value;
            updateDisplays(this, desktopUnite, desktopMax, desktopQuantity);
            updateDisplays(mobileSelect, mobileUnite, mobileMax, mobileQuantity);
        });
    }

    // Sync quantity inputs
    if (mobileQuantity && desktopQuantity) {
        mobileQuantity.addEventListener('input', function() {
            desktopQuantity.value = this.value;
        });

        desktopQuantity.addEventListener('input', function() {
            mobileQuantity.value = this.value;
        });
    }

    // Sync textareas
    const mobileMotif = document.querySelector('#motif_retour');
    const desktopMotif = document.querySelector('#motif_retour_desktop');
    
    if (mobileMotif && desktopMotif) {
        mobileMotif.addEventListener('input', function() {
            desktopMotif.value = this.value;
        });

        desktopMotif.addEventListener('input', function() {
            mobileMotif.value = this.value;
        });
    }
});
</script>
@endsection
