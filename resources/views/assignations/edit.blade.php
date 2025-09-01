@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Mobile Header -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-blue-600 text-white p-4 rounded-xl shadow-lg">
            <h1 class="text-xl font-bold">{{ $isFrench ? 'Modifier une assignation' : 'Edit assignment' }}</h1>
            <p class="text-sm text-blue-200 mt-1">{{ $assignation->producteur->name }}</p>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">{{ $isFrench ? 'Modifier une assignation' : 'Edit assignment' }}</h1>
            <p class="text-gray-600 text-lg mt-2">{{ $isFrench ? 'Mise à jour de l\'assignation de matière pour' : 'Update material assignment for' }} {{ $assignation->producteur->name }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg animate-slide-in" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Mobile Assignment Info -->
    <div class="lg:hidden mb-6 animate-fade-in">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-blue-100 p-4 border-l-4 border-blue-500">
                <h2 class="text-lg font-bold text-blue-900">{{ $isFrench ? 'Informations de l\'assignation' : 'Assignment information' }}</h2>
            </div>
            <div class="p-4 space-y-3">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Producteur :' : 'Producer:' }}</p>
                    <p class="font-medium text-gray-900">{{ $assignation->producteur->name }} ({{ ucfirst($assignation->producteur->role) }})</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Matière première :' : 'Raw material:' }}</p>
                    <p class="font-medium text-gray-900">{{ $assignation->matiere->nom }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Assignment Info -->
    <div class="hidden lg:block mb-8 animate-fade-in">
        <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-6">{{ $isFrench ? 'Informations de l\'assignation' : 'Assignment information' }}</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="transform transition-all duration-300 hover:scale-105">
                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Producteur :' : 'Producer:' }}</p>
                    <p class="font-bold text-lg text-gray-900">{{ $assignation->producteur->name }} ({{ ucfirst($assignation->producteur->role) }})</p>
                </div>
                <div class="transform transition-all duration-300 hover:scale-105">
                    <p class="text-sm text-gray-600">{{ $isFrench ? 'Matière première :' : 'Raw material:' }}</p>
                    <p class="font-bold text-lg text-gray-900">{{ $assignation->matiere->nom }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-4 lg:p-8 animate-fade-in">
        <form action="{{ route('assignations.update', $assignation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Mobile Form -->
            <div class="lg:hidden space-y-6">
                <div class="mobile-field">
                    <label for="quantite_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="mdi mdi-scale text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Quantité' : 'Quantity' }}
                    </label>
                    <input type="number" id="quantite_mobile" name="quantite" value="{{ $assignation->quantite_assignee }}" step="0.001" min="0.001" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200" required>
                </div>

                <div class="mobile-field">
                    <label for="unite_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="mdi mdi-ruler text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Unité' : 'Unit' }}
                    </label>
                    <select id="unite_mobile" name="unite" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200" required>
                        @foreach(array_keys($unites) as $unite)
                            <option value="{{ $unite }}" {{ $assignation->unite_assignee == $unite ? 'selected' : '' }}>
                                {{ strtoupper($unite) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mobile-field">
                    <label for="date_limite_mobile" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="mdi mdi-calendar text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Date limite d\'utilisation (optionnel)' : 'Usage deadline (optional)' }}
                    </label>
                    <input type="date" id="date_limite_mobile" name="date_limite" value="{{ $assignation->date_limite_utilisation ? $assignation->date_limite_utilisation->format('Y-m-d') : '' }}" class="w-full py-3 px-4 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base transition-all duration-200">
                </div>

                <div class="flex flex-col space-y-3 pt-6">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                        {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                    </button>
                </div>
            </div>

            <!-- Desktop Form -->
            <div class="hidden lg:block">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div class="transform transition-all duration-300 hover:scale-105">
                        <label for="quantite" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Quantité' : 'Quantity' }}</label>
                        <input type="number" id="quantite" name="quantite" value="{{ $assignation->quantite_assignee }}" step="0.001" min="0.001" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                    </div>

                    <div class="transform transition-all duration-300 hover:scale-105">
                        <label for="unite" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Unité' : 'Unit' }}</label>
                        <select id="unite" name="unite" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            @foreach(array_keys($unites) as $unite)
                                <option value="{{ $unite }}" {{ $assignation->unite_assignee == $unite ? 'selected' : '' }}>
                                    {{ strtoupper($unite) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="transform transition-all duration-300 hover:scale-105">
                        <label for="date_limite" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date limite d\'utilisation (optionnel)' : 'Usage deadline (optional)' }}</label>
                        <input type="date" id="date_limite" name="date_limite" value="{{ $assignation->date_limite_utilisation ? $assignation->date_limite_utilisation->format('Y-m-d') : '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105">
                        {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                    </button>
                </div>
            </div>
        </form>
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
