@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('buttons')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">
            @if($isFrench)
                Matières du Complexe
            @else
                Complex Ingredients
            @endif
        </h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 animate-bounce" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Mobile View (sm and smaller) -->
    <div class="block md:hidden">
        <div class="space-y-4">
            @foreach($matieres as $matiere)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-800 truncate">{{ $matiere->nom }}</h3>
                                <p class="text-sm text-gray-600">
                                    @if($isFrench)
                                        Unité: {{ $matiere->unite_minimale }}
                                    @else
                                        Unit: {{ $matiere->unite_minimale }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right ml-4 flex-shrink-0">
                                <p class="text-sm font-semibold text-blue-600">{{ number_format($matiere->prix_unitaire, 2) }} FCFA</p>
                                <div class="flex items-center justify-end mt-1">
                                    @if($matiere->provientDuComplexe())
                                        <span class="flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                        <span class="ml-1.5 text-xs text-green-600">
                                            @if($isFrench) Oui @else Yes @endif
                                        </span>
                                    @else
                                        <span class="flex h-3 w-3 relative">
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-300"></span>
                                        </span>
                                        <span class="ml-1.5 text-xs text-gray-600">
                                            @if($isFrench) Non @else No @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($matiere->provientDuComplexe())
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm">
                                <span class="font-medium text-gray-700">
                                    @if($isFrench)
                                        Prix Complexe:
                                    @else
                                        Complex Price:
                                    @endif
                                </span>
                                @if($matiere->complexe && $matiere->complexe->prix_complexe)
                                    <span class="text-blue-600 font-semibold">{{ number_format($matiere->complexe->prix_complexe, 2) }} FCFA</span>
                                @else
                                    <span class="text-gray-400">
                                        @if($isFrench)
                                            Non défini
                                        @else
                                            Not defined
                                        @endif
                                    </span>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div class="mt-4 flex space-x-2">
                            <form method="POST" action="{{ route('matieres.complexe.toggle', $matiere->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full {{ $matiere->provientDuComplexe() ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-2.5 px-4 rounded-lg shadow-md transform active:scale-95 transition-all duration-200 font-medium">
                                    {{ $matiere->provientDuComplexe() ? ($isFrench ? 'Retirer' : 'Remove') : ($isFrench ? 'Ajouter' : 'Add') }}
                                </button>
                            </form>

                            @if($matiere->provientDuComplexe())
                                <button onclick="togglePrixModal('{{ $matiere->id }}', '{{ $matiere->nom }}', '{{ $matiere->prix_complexe ?? $matiere->prix_par_unite_minimale }}')" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-4 rounded-lg shadow-md transform active:scale-95 transition-all duration-200 font-medium">
                                    @if($isFrench)
                                        Modifier Prix
                                    @else
                                        Edit Price
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tablet View (iPad - md to lg) -->
    <div class="hidden md:block lg:hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($matieres as $matiere)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-102 transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $matiere->nom }}</h3>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">
                                            @if($isFrench) Unité: @else Unit: @endif
                                        </span>
                                        {{ $matiere->unite_minimale }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-600">
                                            @if($isFrench) Prix Standard: @else Standard Price: @endif
                                        </span>
                                        <span class="text-blue-600 font-semibold">{{ number_format($matiere->prix_unitaire, 2) }} FCFA</span>
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 text-right">
                                <div class="flex items-center justify-end">
                                    @if($matiere->provientDuComplexe())
                                        <span class="flex h-4 w-4 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                                        </span>
                                        <span class="ml-2 text-sm font-medium text-green-600">
                                            @if($isFrench) Du Complexe @else From Complex @endif
                                        </span>
                                    @else
                                        <span class="flex h-4 w-4 relative">
                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-gray-300"></span>
                                        </span>
                                        <span class="ml-2 text-sm text-gray-600">
                                            @if($isFrench) Externe @else External @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($matiere->provientDuComplexe())
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-sm">
                                <span class="font-medium text-gray-700">
                                    @if($isFrench)
                                        Prix Complexe:
                                    @else
                                        Complex Price:
                                    @endif
                                </span>
                                @if($matiere->complexe && $matiere->complexe->prix_complexe)
                                    <span class="text-blue-600 font-bold">{{ number_format($matiere->complexe->prix_complexe, 2) }} FCFA</span>
                                @else
                                    <span class="text-gray-400 italic">
                                        @if($isFrench)
                                            Non défini
                                        @else
                                            Not defined
                                        @endif
                                    </span>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('matieres.complexe.toggle', $matiere->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full {{ $matiere->provientDuComplexe() ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-3 px-4 rounded-lg shadow-md transform hover:scale-105 active:scale-95 transition-all duration-200 font-medium">
                                    {{ $matiere->provientDuComplexe() ? ($isFrench ? 'Retirer du Complexe' : 'Remove from Complex') : ($isFrench ? 'Ajouter au Complexe' : 'Add to Complex') }}
                                </button>
                            </form>

                            @if($matiere->provientDuComplexe())
                                <button onclick="togglePrixModal('{{ $matiere->id }}', '{{ $matiere->nom }}', '{{ $matiere->prix_complexe ?? $matiere->prix_unitaire }}')" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg shadow-md transform hover:scale-105 active:scale-95 transition-all duration-200 font-medium">
                                    @if($isFrench)
                                        Modifier Prix
                                    @else
                                        Edit Price
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Desktop View (lg and larger) -->
    <div class="hidden lg:block bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Nom @else Name @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Unité Minimale @else Minimum Unit @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Prix Standard @else Standard Price @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Du Complexe @else From Complex @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Prix Complexe @else Complex Price @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            @if($isFrench) Actions @else Actions @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($matieres as $matiere)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $matiere->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $matiere->unite_minimale }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-blue-600">{{ number_format($matiere->prix_unitaire, 2) }} FCFA</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($matiere->provientDuComplexe())
                                        <span class="flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                        <span class="ml-1.5 text-sm font-medium text-green-600">
                                            @if($isFrench) Oui @else Yes @endif
                                        </span>
                                    @else
                                        <span class="flex h-3 w-3 relative">
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-300"></span>
                                        </span>
                                        <span class="ml-1.5 text-sm text-gray-600">
                                            @if($isFrench) Non @else No @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($matiere->complexe && $matiere->complexe->prix_complexe)
                                    <div class="text-sm font-semibold text-blue-600">{{ number_format($matiere->complexe->prix_complexe, 2) }} FCFA</div>
                                @else
                                    <div class="text-sm text-gray-400 italic">
                                        @if($isFrench)
                                            Non défini
                                        @else
                                            Not defined
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('matieres.complexe.toggle', $matiere->id) }}">
                                        @csrf
                                        <button type="submit" class="{{ $matiere->provientDuComplexe() ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-2 px-4 rounded-lg shadow-md transform hover:scale-105 active:scale-95 transition-all duration-200 font-medium">
                                            {{ $matiere->provientDuComplexe() ? ($isFrench ? 'Retirer' : 'Remove') : ($isFrench ? 'Ajouter' : 'Add') }}
                                        </button>
                                    </form>

                                    @if($matiere->provientDuComplexe())
                                        <button onclick="togglePrixModal('{{ $matiere->id }}', '{{ $matiere->nom }}', '{{ $matiere->prix_complexe ?? $matiere->prix_unitaire }}')" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg shadow-md transform hover:scale-105 active:scale-95 transition-all duration-200 font-medium">
                                            @if($isFrench)
                                                Modifier Prix
                                            @else
                                                Edit Price
                                            @endif
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour modifier le prix - Amélioré pour tablet -->
<div id="prixModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4 z-50">
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 animate-fade-in-up">
        <div class="p-6 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">
                    @if($isFrench)
                        Modifier le prix
                    @else
                        Edit Price
                    @endif
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="prixForm" method="POST" action="">
                @csrf
                <div class="mb-6">
                    <label for="prix_complexe" class="block text-sm font-bold text-gray-700 mb-2">
                        @if($isFrench)
                            Prix pour le complexe (FCFA)
                        @else
                            Price for the complex (FCFA)
                        @endif
                    </label>
                    <input type="number" id="prix_complexe" name="prix_complexe" step="0.01" min="0" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200 text-lg" 
                           required>
                </div>
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" onclick="closeModal()" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transform active:scale-95 transition-all duration-200">
                        @if($isFrench)
                            Annuler
                        @else
                            Cancel
                        @endif
                    </button>
                    <button type="submit" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transform active:scale-95 transition-all duration-200 shadow-lg">
                        @if($isFrench)
                            Enregistrer
                        @else
                            Save
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePrixModal(id, nom, prix) {
        const modalTitle = document.getElementById('modalTitle');
        const prixInput = document.getElementById('prix_complexe');
        const form = document.getElementById('prixForm');
        const modal = document.getElementById('prixModal');
        
        modalTitle.innerText = `{{ $isFrench ? 'Modifier le prix de' : 'Edit price for' }} "${nom}"`;
        prixInput.value = prix;
        form.action = `/matieres/complexe/${id}/prix`;
        modal.classList.remove('hidden');
        
        // Focus sur l'input pour une meilleure UX
        setTimeout(() => {
            prixInput.focus();
            prixInput.select();
        }, 100);
    }

    function closeModal() {
        const modal = document.getElementById('prixModal');
        modal.classList.add('hidden');
    }

    // Fermer la modal si on clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('prixModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Validation du formulaire
    document.getElementById('prixForm').addEventListener('submit', function(e) {
        const prix = document.getElementById('prix_complexe').value;
        if (!prix || parseFloat(prix) < 0) {
            e.preventDefault();
            alert('{{ $isFrench ? "Veuillez entrer un prix valide" : "Please enter a valid price" }}');
        }
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }

    /* Améliorations pour les transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Responsive breakpoints personnalisés */
    @media (min-width: 768px) and (max-width: 1023px) {
        /* Styles spécifiques pour les tablets */
        .container {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        /* Optimisation des cartes pour tablet */
        .grid-cols-2 > div {
            min-height: 280px;
        }
    }

    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    /* Amélioration des hover effects sur desktop */
    @media (hover: hover) {
        .hover\:scale-102:hover {
            transform: scale(1.02);
        }
        
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
    }

    /* Optimisation pour les écrans tactiles */
    @media (hover: none) {
        .hover\:scale-102:hover,
        .hover\:scale-105:hover {
            transform: none;
        }
    }
</style>
@endsection