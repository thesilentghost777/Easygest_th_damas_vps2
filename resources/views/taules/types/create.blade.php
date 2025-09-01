@extends('layouts.app')

@section('content')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $isFrench ? 'Créer un Type de Tôle' : 'Create a baking tray Type' }}
    </h2>
</x-slot>

<div class="py-4 sm:py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')

        <!-- Calculateur automatique - Responsive -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-4 sm:mb-6">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $isFrench ? 'Calculateur automatique de formules' : 'Automatic Formula Calculator' }}
                </h3>

                <!-- Grid responsive pour les champs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-4 mb-4">
                    <div class="col-span-1">
                        <label for="quantite_farine" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Quantité de farine utilisée (kg):' : 'Amount of flour used (kg):' }}
                        </label>
                        <input type="number" id="quantite_farine" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                               step="0.01" min="0"
                               placeholder="0.00">
                    </div>

                    <div class="col-span-1">
                        <label for="quantite_eau" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Quantité d\'eau utilisée (L):' : 'Amount of water used (L):' }}
                        </label>
                        <input type="number" id="quantite_eau" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                               step="0.01" min="0"
                               placeholder="0.00">
                    </div>

                    <div class="col-span-1">
                        <label for="quantite_huile" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Quantité d\'huile utilisée (L):' : 'Amount of oil used (L):' }}
                        </label>
                        <input type="number" id="quantite_huile" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                               step="0.01" min="0"
                               placeholder="0.00">
                    </div>

                    <div class="col-span-1">
                        <label for="quantite_autres" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Quantité d\'autres ingrédients (kg):' : 'Amount of other ingredients (kg):' }}
                        </label>
                        <input type="number" id="quantite_autres" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                               step="0.01" min="0"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="nombre_toles" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ $isFrench ? 'Nombre de tôles produites:' : 'Number of baking trays produced:' }}
                    </label>
                    <input type="number" id="nombre_toles" 
                           class="shadow appearance-none border rounded w-full sm:w-1/2 lg:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                           min="1"
                           placeholder="1">
                </div>

                <button type="button" id="calculer_formules" 
                        class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline text-base transition-colors duration-200">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        {{ $isFrench ? 'Calculer les formules' : 'Calculate formulas' }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Formulaire de création de type de taule - Responsive -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('taules.types.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Nom du type de tôle:' : 'Taule type name:' }}
                        </label>
                        <input type="text" name="nom" id="nom" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base" 
                               required value="{{ old('nom') }}"
                               placeholder="{{ $isFrench ? 'Entrez le nom du type' : 'Enter type name' }}">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $isFrench ? 'Description:' : 'Description:' }}
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base resize-y"
                                  placeholder="{{ $isFrench ? 'Description du type de tôle...' : 'Description of the baking tray type...' }}">{{ old('description') }}</textarea>
                    </div>

                    <!-- Section Formules - Layout flexible -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">
                            {{ $isFrench ? 'Formules de calcul' : 'Calculation Formulas' }}
                        </h4>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="formule_farine" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench 
                                        ? 'Formule pour la farine (kg):' 
                                        : 'Formula for flour (kg):' }}
                                </label>
                                <input type="text" name="formule_farine" id="formule_farine" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base font-mono" 
                                       placeholder="{{ $isFrench ? 'ex: 0.5 * n' : 'e.g: 0.5 * n' }}" 
                                       value="{{ old('formule_farine') }}">
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $isFrench 
                                        ? 'Utilisez "n" pour le nombre de tôles' 
                                        : 'Use "n" for number of baking trays' }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="formule_eau" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench ? 'Formule pour l\'eau (L):' : 'Formula for water (L):' }}
                                </label>
                                <input type="text" name="formule_eau" id="formule_eau" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base font-mono" 
                                       placeholder="{{ $isFrench ? 'ex: 0.3 * n' : 'e.g: 0.3 * n' }}" 
                                       value="{{ old('formule_eau') }}">
                            </div>

                            <div class="mb-4">
                                <label for="formule_huile" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench ? 'Formule pour l\'huile (L):' : 'Formula for oil (L):' }}
                                </label>
                                <input type="text" name="formule_huile" id="formule_huile" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base font-mono" 
                                       placeholder="{{ $isFrench ? 'ex: 0.05 * n' : 'e.g: 0.05 * n' }}" 
                                       value="{{ old('formule_huile') }}">
                            </div>

                            <div class="mb-4">
                                <label for="formule_autres" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench ? 'Formule pour autres ingrédients (kg):' : 'Formula for other ingredients (kg):' }}
                                </label>
                                <input type="text" name="formule_autres" id="formule_autres" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-base font-mono" 
                                       placeholder="{{ $isFrench ? 'ex: 0.1 * n' : 'e.g: 0.1 * n' }}" 
                                       value="{{ old('formule_autres') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action - Responsive -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <button type="submit" 
                                class="w-full sm:w-auto order-2 sm:order-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline text-base transition-colors duration-200">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ $isFrench ? 'Créer le type' : 'Create type' }}
                            </span>
                        </button>
                        <a href="{{ route('taules.types.index') }}" 
                           class="w-full sm:w-auto order-1 sm:order-2 inline-block text-center font-bold text-sm text-blue-500 hover:text-blue-800 py-3 px-6 border border-blue-500 rounded hover:bg-blue-50 transition-colors duration-200">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Message d'aide pour mobile -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 block sm:hidden">
            <div class="flex">
                <svg class="flex-shrink-0 h-5 w-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        {{ $isFrench 
                            ? 'Astuce: Tournez votre appareil en mode paysage pour une meilleure expérience sur les petits écrans.' 
                            : 'Tip: Rotate your device to landscape mode for a better experience on small screens.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calculerButton = document.getElementById('calculer_formules');
        const isFrench = {{ $isFrench ? 'true' : 'false' }};

        // Fonction pour afficher les notifications
        function showNotification(message, type = 'success') {
            // Créer une notification toast
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white border-l-4 ${type === 'success' ? 'border-green-400' : 'border-red-400'} shadow-lg rounded-lg p-4 transform transition-all duration-300 translate-x-full`;
            
            notification.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        ${type === 'success' 
                            ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                            : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                        }
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animation d'entrée
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto-suppression après 5 secondes
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        calculerButton.addEventListener('click', function() {
            // Récupérer les valeurs des champs
            const quantiteFarine = parseFloat(document.getElementById('quantite_farine').value) || 0;
            const quantiteEau = parseFloat(document.getElementById('quantite_eau').value) || 0;
            const quantiteHuile = parseFloat(document.getElementById('quantite_huile').value) || 0;
            const quantiteAutres = parseFloat(document.getElementById('quantite_autres').value) || 0;
            const nombreToles = parseInt(document.getElementById('nombre_toles').value) || 0;

            // Validation
            if (nombreToles <= 0) {
                showNotification(
                    isFrench ? 'Veuillez entrer un nombre de tôles valide.' : 'Please enter a valid number of baking trays.',
                    'error'
                );
                return;
            }

            if (quantiteFarine === 0 && quantiteEau === 0 && quantiteHuile === 0 && quantiteAutres === 0) {
                showNotification(
                    isFrench ? 'Veuillez entrer au moins une quantité d\'ingrédient.' : 'Please enter at least one ingredient quantity.',
                    'error'
                );
                return;
            }

            // Calculer les formules (quantité par tôle)
            const formuleFarine = quantiteFarine > 0 ? (quantiteFarine / nombreToles).toFixed(3) + ' * n' : '';
            const formuleEau = quantiteEau > 0 ? (quantiteEau / nombreToles).toFixed(3) + ' * n' : '';
            const formuleHuile = quantiteHuile > 0 ? (quantiteHuile / nombreToles).toFixed(3) + ' * n' : '';
            const formuleAutres = quantiteAutres > 0 ? (quantiteAutres / nombreToles).toFixed(3) + ' * n' : '';

            // Remplir les champs de formulaire
            document.getElementById('formule_farine').value = formuleFarine;
            document.getElementById('formule_eau').value = formuleEau;
            document.getElementById('formule_huile').value = formuleHuile;
            document.getElementById('formule_autres').value = formuleAutres;

            // Animation des champs modifiés
            const champsModifies = ['formule_farine', 'formule_eau', 'formule_huile', 'formule_autres'];
            champsModifies.forEach(champId => {
                const champ = document.getElementById(champId);
                if (champ.value) {
                    champ.classList.add('border-green-500', 'bg-green-50');
                    setTimeout(() => {
                        champ.classList.remove('border-green-500', 'bg-green-50');
                    }, 2000);
                }
            });

            // Afficher le message de succès
            showNotification(
                isFrench ? 'Les formules ont été calculées avec succès!' : 'Formulas have been calculated successfully!'
            );
        });

        // Amélioration de l'UX sur mobile
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Scroll vers l'élément sur mobile pour éviter que le clavier cache le champ
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            });
        });

        // Gestion de l'orientation sur mobile/tablette
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                window.scrollTo(0, window.scrollY);
            }, 500);
        });
    });
</script>

<style>
    /* Améliorations pour le tactile */
    @media (max-width: 768px) {
        input, textarea, button {
            min-height: 44px; /* Taille minimale recommandée pour le tactile */
        }
        
        /* Espacements améliorés sur mobile */
        .space-y-4 > * + * {
            margin-top: 1.5rem;
        }
    }
    
    /* Styles pour tablettes */
    @media (min-width: 768px) and (max-width: 1024px) {
        .container {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }
    
    /* Animation de focus améliorée */
    input:focus, textarea:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
    
    /* Hover states pour desktop */
    @media (hover: hover) {
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    }
</style>
@endsection