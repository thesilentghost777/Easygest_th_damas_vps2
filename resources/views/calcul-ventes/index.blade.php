@extends('layouts.app')

@section('title', 'Calcul des Ventes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                📊 Calcul des Ventes
            </h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">ℹ️ Information</h3>
                <p class="text-blue-700 text-sm">
                    Ce module calcule automatiquement les ventes, invendus et avaries à partir des données de réception des vendeurs.
                    <br><strong>Formule :</strong> Ventes = Total Entrées + Reste d'hier - Invendus - Avaries
                </p>
            </div>

            <form action="{{ route('calcul-ventes.calculer') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sélection du mois -->
                    <div>
                        <label for="mois" class="block text-sm font-medium text-gray-700 mb-2">
                            📅 Mois
                        </label>
                        <select name="mois" id="mois" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Sélectionner un mois --</option>
                            <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Janvier</option>
                            <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Février</option>
                            <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Mars</option>
                            <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>Avril</option>
                            <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Mai</option>
                            <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Juin</option>
                            <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Juillet</option>
                            <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Août</option>
                            <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>Septembre</option>
                            <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Octobre</option>
                            <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>Novembre</option>
                            <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Décembre</option>
                        </select>
                        @error('mois')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sélection de l'année -->
                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-2">
                            📆 Année
                        </label>
                        <select name="annee" id="annee" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Sélectionner une année --</option>
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('annee')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bouton de calcul -->
                <div class="text-center">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                        🧮 Calculer les Ventes
                    </button>
                </div>
            </form>

            <!-- Avertissement -->
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">⚠️ Attention</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Le calcul supprimera toutes les transactions existantes pour le mois sélectionné et les recalculera à partir des données de réception.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const button = e.target.querySelector('button[type="submit"]');
    button.innerHTML = '⏳ Calcul en cours...';
    button.disabled = true;
    button.classList.add('animate-pulse');
});
</script>
@endsection