@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ $isFrench ? 'Seuils de Notification des Matières' : 'Material Notification Thresholds' }}
        </h1>
        <p class="text-gray-600">
            {{ $isFrench 
                ? 'Configurez les seuils de notification pour recevoir des alertes quand le stock est bas' 
                : 'Configure notification thresholds for materials to receive alerts when stock is low' }}
        </p>
    </div>

    <!-- Messages de succès -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Boutons d'action -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <div class="flex flex-wrap gap-2">
            <button type="button" onclick="selectAll()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ $isFrench ? 'Tout Sélectionner' : 'Select All' }}
            </button>
            <button type="button" onclick="deselectAll()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ $isFrench ? 'Tout Désélectionner' : 'Deselect All' }}
            </button>
            <button type="button" onclick="toggleNotifications(true)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ $isFrench ? 'Activer Sélectionnés' : 'Enable Selected' }}
            </button>
            <button type="button" onclick="toggleNotifications(false)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ $isFrench ? 'Désactiver Sélectionnés' : 'Disable Selected' }}
            </button>
        </div>
    </div>

    <!-- Formulaire de mise à jour en lot -->
    <form method="POST" action="{{ route('matieres.notifications.update-batch') }}" id="batchForm">
        @csrf
        
        <!-- Vue Desktop avec responsive -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllCheckboxes()">
                            </th>
                            <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Matière' : 'Material' }}
                            </th>
                            <th class="hidden sm:table-cell px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Stock Actuel' : 'Current Stock' }}
                            </th>
                            <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Seuil' : 'Threshold' }}
                            </th>
                            <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Notifications' : 'Notifications' }}
                            </th>
                            <th class="hidden sm:table-cell px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($matieres as $matiere)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       name="selected_matieres[]" 
                                       value="{{ $matiere->id }}" 
                                       class="matiere-checkbox">
                                <input type="hidden" 
                                       name="matieres[{{ $matiere->id }}][id]" 
                                       value="{{ $matiere->id }}">
                            </td>
                            <td class="px-3 lg:px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $matiere->nom }}</div>
                                <!-- Afficher le stock sur mobile -->
                                <div class="sm:hidden text-xs text-gray-500 mt-1">
                                    {{ $isFrench ? 'Stock:' : 'Stock:' }} {{ number_format($matiere->quantite, 2) }}
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-3 lg:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($matiere->quantite, 2) }}</div>
                            </td>
                            <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                                <input type="number" 
                                       name="matieres[{{ $matiere->id }}][quantite_seuil]" 
                                       value="{{ $matiere->quantite_seuil ?? 0 }}"
                                       min="0" 
                                       step="0.01"
                                       class="w-16 sm:w-24 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="matieres[{{ $matiere->id }}][notification_active]" 
                                           value="1"
                                           {{ ($matiere->notification_active ?? true) ? 'checked' : '' }}
                                           class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                    <span class="ml-2 text-xs sm:text-sm text-gray-700">
                                        {{ $isFrench ? 'Actif' : 'Active' }}
                                    </span>
                                </label>
                            </td>
                            <td class="hidden sm:table-cell px-3 lg:px-6 py-4 whitespace-nowrap">
                                @if(isset($matiere->quantite_seuil) && $matiere->quantite < $matiere->quantite_seuil && ($matiere->notification_active ?? true))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $isFrench ? 'Bas' : 'Below' }}
                                    </span>
                                @elseif(isset($matiere->quantite_seuil) && $matiere->quantite >= $matiere->quantite_seuil)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $isFrench ? 'OK' : 'OK' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $isFrench ? 'Non Défini' : 'Not Set' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bouton de sauvegarde -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                {{ $isFrench ? 'Enregistrer les Modifications' : 'Save Changes' }}
            </button>
        </div>
    </form>
</div>

<script>
function selectAll() {
    document.querySelectorAll('.matiere-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
}

function deselectAll() {
    document.querySelectorAll('.matiere-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
}

function toggleAllCheckboxes() {
    const masterCheckbox = document.getElementById('selectAllCheckbox');
    document.querySelectorAll('.matiere-checkbox').forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
    });
}

function toggleNotifications(active) {
    document.querySelectorAll('.matiere-checkbox:checked').forEach(checkbox => {
        const index = checkbox.value;
        const row = checkbox.closest('tr') || checkbox.closest('.bg-white');
        const notificationCheckbox = row.querySelector('input[name*="[notification_active]"]');
        if (notificationCheckbox) {
            notificationCheckbox.checked = active;
        }
    });
}
</script>
@endsection