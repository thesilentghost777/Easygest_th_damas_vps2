@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 shadow-lg">
        <div class="flex items-center justify-between">
            @include('buttons')
            <div class="flex-1 text-center">
                <h1 class="text-lg font-bold">
                    {{ $isFrench ? 'Validation Retours' : 'Return Validation' }}
                </h1>
                <p class="text-blue-100 text-sm">
                    {{ $isFrench ? 'Demandes en attente' : 'Pending requests' }}
                </p>
            </div>
            <div class="w-8"></div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isFrench ? 'Validation des Retours de Matières' : 'Material Return Validation' }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? 'Gérer les demandes de retour des producteurs' : 'Manage producer return requests' }}
            </p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
    <div class="mx-4 lg:mx-auto lg:max-w-6xl mb-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm animate-pulse">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="mx-4 lg:mx-auto lg:max-w-6xl mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm animate-pulse">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="mx-4 lg:mx-auto lg:max-w-6xl pb-6 space-y-6">
        
        <!-- Pending Returns Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-4 lg:p-6">
                <h2 class="text-xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $isFrench ? 'Demandes en Attente' : 'Pending Requests' }}
                    @if($retoursEnAttente->count() > 0)
                    <span class="ml-2 bg-white bg-opacity-20 rounded-full px-2 py-1 text-sm">{{ $retoursEnAttente->count() }}</span>
                    @endif
                </h2>
            </div>

            @if($retoursEnAttente->isEmpty())
                <div class="p-6 lg:p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucune demande en attente' : 'No pending requests' }}
                    </h3>
                    <p class="text-gray-500">
                        {{ $isFrench ? 'Toutes les demandes ont été traitées.' : 'All requests have been processed.' }}
                    </p>
                </div>
            @else
                <!-- Desktop Table for Pending -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Producteur' : 'Producer' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Date' : 'Date' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($retoursEnAttente as $retour)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $retour->producteur->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $retour->assignation->matiere->nom }}</div>
                                    @if($retour->motif_retour)
                                    <div class="text-sm text-gray-500">{{ $retour->motif_retour }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $retour->quantite_retournee }} {{ $retour->unite_retour }}
                                    <div class="text-xs text-gray-500">
                                        {{ $isFrench ? 'Stock +' : 'Stock +' }}{{ $retour->quantite_stock_incrementee }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $retour->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button onclick="showValidationModal({{ $retour->id }}, 'valider')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            {{ $isFrench ? 'Valider' : 'Validate' }}
                                        </button>
                                        <button onclick="showValidationModal({{ $retour->id }}, 'refuser')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            {{ $isFrench ? 'Refuser' : 'Reject' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards for Pending -->
                <div class="lg:hidden p-4 space-y-4">
                    @foreach($retoursEnAttente as $retour)
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-200 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $retour->producteur->name }}</h3>
                                <div class="text-sm text-gray-600">{{ $retour->assignation->matiere->nom }}</div>
                                <div class="text-xs text-gray-500">{{ $retour->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                                <span class="font-bold text-blue-600">{{ $retour->quantite_retournee }} {{ $retour->unite_retour }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $isFrench ? 'Ajout stock:' : 'Stock increase:' }}</span>
                                <span class="text-sm font-medium text-green-600">+{{ $retour->quantite_stock_incrementee }}</span>
                            </div>
                            
                            @if($retour->motif_retour)
                            <div class="pt-2 border-t border-yellow-200">
                                <div class="text-sm text-gray-600">
                                    <strong>{{ $isFrench ? 'Motif:' : 'Reason:' }}</strong>
                                </div>
                                <div class="text-sm text-gray-800 mt-1">{{ $retour->motif_retour }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="flex space-x-2">
                            <button onclick="showValidationModal({{ $retour->id }}, 'valider')" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transform hover:scale-105 transition-all duration-200">
                                {{ $isFrench ? 'Valider' : 'Validate' }}
                            </button>
                            <button onclick="showValidationModal({{ $retour->id }}, 'refuser')" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transform hover:scale-105 transition-all duration-200">
                                {{ $isFrench ? 'Refuser' : 'Reject' }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Validated Returns Section -->
        @if($retoursValidees->isNotEmpty())
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 lg:p-6">
                <h2 class="text-xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $isFrench ? 'Retours Récents Validés' : 'Recent Validated Returns' }}
                </h2>
            </div>

            <!-- Desktop Table for Validated -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Producteur' : 'Producer' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Validé le' : 'Validated on' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Validé par' : 'Validated by' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($retoursValidees as $retour)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $retour->producteur->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $retour->assignation->matiere->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $retour->quantite_retournee }} {{ $retour->unite_retour }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $retour->date_validation->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $retour->validateur->name }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards for Validated -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($retoursValidees as $retour)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $retour->producteur->name }}</h3>
                            <div class="text-sm text-gray-600">{{ $retour->assignation->matiere->nom }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $isFrench ? 'Validée' : 'Validated' }}
                        </span>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <span class="font-bold text-blue-600">{{ $retour->quantite_retournee }} {{ $retour->unite_retour }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Validé par:' : 'Validated by:' }}</span>
                            <span class="text-sm text-gray-800">{{ $retour->validateur->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500 text-right">{{ $retour->date_validation->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle"></h3>
            <form id="validationForm" method="POST">
                @csrf
                <input type="hidden" name="action" id="actionInput">
                
                <div class="mb-4">
                    <label for="commentaire_validation" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Commentaire (optionnel)' : 'Comment (optional)' }}
                    </label>
                    <textarea name="commentaire_validation" id="commentaire_validation" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ $isFrench ? 'Ajouter un commentaire...' : 'Add a comment...' }}"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit" id="confirmButton" class="px-4 py-2 text-white rounded">
                        {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showValidationModal(retourId, action) {
    const modal = document.getElementById('validationModal');
    const form = document.getElementById('validationForm');
    const title = document.getElementById('modalTitle');
    const actionInput = document.getElementById('actionInput');
    const confirmButton = document.getElementById('confirmButton');
    
    form.action = `/matieres-retour/${retourId}/valider`;
    actionInput.value = action;
    
    if (action === 'valider') {
        title.textContent = '{{ $isFrench ? "Valider le retour" : "Validate return" }}';
        confirmButton.textContent = '{{ $isFrench ? "Valider" : "Validate" }}';
        confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded';
    } else {
        title.textContent = '{{ $isFrench ? "Refuser le retour" : "Reject return" }}';
        confirmButton.textContent = '{{ $isFrench ? "Refuser" : "Reject" }}';
        confirmButton.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('validationModal');
    const textarea = document.getElementById('commentaire_validation');
    modal.classList.add('hidden');
    textarea.value = '';
}

// Close modal when clicking outside
document.getElementById('validationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
