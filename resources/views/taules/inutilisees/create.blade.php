@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="max-w-4xl mx-auto animate-fade-in">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-blue-600 text-white p-4 lg:p-6">
                <h1 class="text-xl lg:text-2xl font-bold flex items-center">
                    <i class="mdi mdi-package-variant mr-3"></i>
                    {{ $isFrench ? 'Déclarer des Tôles Inutilisées' : 'Declare Unused Sheet Pans' }}
                </h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 lg:m-6 rounded-r-lg animate-slide-in">
                    <div class="flex items-center">
                        <i class="mdi mdi-alert-circle mr-2"></i>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 lg:m-6 rounded-r-lg animate-slide-in">
                    <div class="flex items-center">
                        <i class="mdi mdi-alert-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @if(session('errorDetails'))
                        <details class="mt-2 text-sm">
                            <summary class="cursor-pointer">{{ $isFrench ? 'Voir les détails techniques' : 'View technical details' }}</summary>
                            <pre class="mt-2 p-2 bg-red-50 rounded overflow-auto">{{ session('errorDetails') }}</pre>
                        </details>
                    @endif
                </div>
            @endif

            <div class="p-4 lg:p-6">
                <!-- Mobile Form -->
                <div class="lg:hidden">
                    <form action="{{ route('taules.inutilisees.calculer') }}" method="POST" id="calcul-form" class="space-y-4">
                        @csrf

                        <div class="mobile-field">
                            <label for="type_taule_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="mdi mdi-package-variant-closed mr-2 text-blue-600"></i>
                                {{ $isFrench ? 'Type de tôle:' : 'Taule type:' }}
                            </label>
                            <select name="type_taule_id" id="type_taule_id" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200" required>
                                <option value="">{{ $isFrench ? 'Sélectionnez un type de tôle' : 'Select a sheet pan type' }}</option>
                                @foreach($typesTaules as $type)
                                    <option value="{{ $type->id }}"
                                        data-farine="{{ $type->formule_farine }}"
                                        data-eau="{{ $type->formule_eau }}"
                                        data-huile="{{ $type->formule_huile }}"
                                        data-autres="{{ $type->formule_autres }}"
                                        {{ old('type_taule_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mobile-field">
                            <label for="nombre_taules" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="mdi mdi-counter mr-2 text-blue-600"></i>
                                {{ $isFrench ? 'Nombre de tôles inutilisées:' : 'Number of unused sheet pans:' }}
                            </label>
                            <input type="number" name="nombre_taules" id="nombre_taules" min="1" 
                                   class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200" 
                                   required value="{{ old('nombre_taules', 1) }}"
                                   placeholder="{{ $isFrench ? 'Entrez le nombre' : 'Enter number' }}">
                        </div>

                        <div class="pt-4">
                            <button id="calcul_button" type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                                <i class="mdi mdi-calculator mr-2"></i>
                                {{ $isFrench ? 'Calculer les matières' : 'Calculate materials' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Desktop Form -->
                <div class="hidden lg:block">
                    <form action="{{ route('taules.inutilisees.calculer') }}" method="POST" id="calcul-form-desktop" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_taule_id_desktop" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench ? 'Type de tôle:' : 'Taule type:' }}
                                </label>
                                <select name="type_taule_id" id="type_taule_id_desktop" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">{{ $isFrench ? 'Sélectionnez un type de tôle' : 'Select a sheet pan type' }}</option>
                                    @foreach($typesTaules as $type)
                                        <option value="{{ $type->id }}"
                                            data-farine="{{ $type->formule_farine }}"
                                            data-eau="{{ $type->formule_eau }}"
                                            data-huile="{{ $type->formule_huile }}"
                                            data-autres="{{ $type->formule_autres }}"
                                            {{ old('type_taule_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="nombre_taules_desktop" class="block text-gray-700 text-sm font-bold mb-2">
                                    {{ $isFrench ? 'Nombre de tôles inutilisées:' : 'Number of unused sheet pans:' }}
                                </label>
                                <input type="number" name="nombre_taules" id="nombre_taules_desktop" min="1" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                       required value="{{ old('nombre_taules', 1) }}">
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <button id="calcul_button_desktop" type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-all duration-200">
                                {{ $isFrench ? 'Calculer les matières' : 'Calculate materials' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results Section -->
                @if(session('matieres'))
                    <div id="resultats_calcul" class="mt-8 animate-slide-in">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $isFrench ? 'Résultats du calcul' : 'Calculation results' }}
                        </h3>

                        <!-- Mobile Results -->
                        <div class="lg:hidden space-y-3">
                            @foreach(session('matieres') as $matiere)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $matiere['nom'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ number_format($matiere['quantite'], 2) }} {{ $matiere['unite'] }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-blue-600">{{ number_format($matiere['prix'], 0) }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-lg font-semibold text-blue-900">{{ $isFrench ? 'Total' : 'Total' }}</h4>
                                    <span class="text-xl font-bold text-blue-900">{{ number_format(session('prixTotal'), 0) }} FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Results -->
                        <div class="hidden lg:block bg-gray-100 p-4 rounded">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left font-medium text-gray-700 py-2">{{ $isFrench ? 'Matière' : 'Material' }}</th>
                                        <th class="text-left font-medium text-gray-700 py-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                                        <th class="text-left font-medium text-gray-700 py-2">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('matieres') as $matiere)
                                        <tr>
                                            <td class="py-2">{{ $matiere['nom'] }}</td>
                                            <td class="py-2">{{ number_format($matiere['quantite'], 2) }} {{ $matiere['unite'] }}</td>
                                            <td class="py-2">{{ number_format($matiere['prix'], 0) }} FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t">
                                        <td class="py-2 font-bold">{{ $isFrench ? 'Total' : 'Total' }}</td>
                                        <td></td>
                                        <td class="py-2 font-bold">{{ number_format(session('prixTotal'), 0) }} FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <form action="{{ route('taules.inutilisees.store') }}" method="POST" class="mt-6" id="taules-form">
                            @csrf
                            <input type="hidden" name="type_taule_id" value="{{ session('typeTaule')->id }}">
                            <input type="hidden" name="nombre_taules" value="{{ session('nombreTaules') }}">

                            <div class="flex flex-col lg:flex-row justify-end gap-2">
                                <a href="{{ route('taules.inutilisees.index') }}" class="w-full lg:w-auto text-center lg:inline-block py-3 lg:py-2 px-4 text-blue-500 hover:text-blue-800 font-medium">
                                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                                </a>
                                <button type="submit" class="w-full lg:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 lg:py-2 px-4 rounded-xl lg:rounded focus:outline-none focus:shadow-outline transition-all duration-200 transform hover:scale-105 active:scale-95">
                                    {{ $isFrench ? 'Enregistrer ces tôles inutilisées' : 'Save these unused sheet pans' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-6 pt-4 border-t">
                        <p class="text-gray-600 italic text-center">
                            {{ $isFrench ? 'Veuillez sélectionner un type de tôle et indiquer le nombre de tôles pour calculer les matières premières nécessaires.' : 'Please select a sheet pan type and indicate the number of sheet pans to calculate the required raw materials.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-field {
            transition: all 0.2s ease-out;
        }
        .mobile-field:focus-within {
            transform: translateY(-2px);
        }
        /* Touch targets */
        button, input, select {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
