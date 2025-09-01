@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gray-100">
   
    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Réserver des Matières Premières' : 'Reserve Raw Materials' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Effectuez une demande de réservation de matières' : 'Make a material reservation request' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                        <p class="text-sm font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ route('producteur.reservations.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Mobile Material Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Matière Première' : 'Raw Material' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <select name="matiere_id" required 
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                                <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Mobile Quantity and Unit -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="transform hover:scale-102 transition-all duration-200">
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-blue-600 font-semibold text-lg">#</span>
                                </div>
                                <input type="number" name="quantite_demandee" step="0.001" required
                                    class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                            </div>
                        </div>

                        <div class="transform hover:scale-102 transition-all duration-200">
                            <label class="block text-base font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Unité' : 'Unit' }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"/>
                                    </svg>
                                </div>
                                <x-unite-select name="unite_demandee" required class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md" />
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Action Button -->
                    <div class="pt-6">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-6 w-6 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ $isFrench ? 'Envoyer la demande' : 'Send request' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form action="{{ route('producteur.reservations.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Matière Première' : 'Raw Material' }}
                                </label>
                                <select name="matiere_id" required class="form-select rounded-md shadow-sm border-gray-300 w-full">
                                    <option value="">{{ $isFrench ? 'Sélectionner une matière' : 'Select a material' }}</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Quantité Demandée' : 'Requested Quantity' }}
                                </label>
                                <input type="number" name="quantite_demandee" step="0.001" required
                                       class="form-input rounded-md shadow-sm border-gray-300 w-full" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Unité' : 'Unit' }}
                                </label>
                                <x-unite-select name="unite_demandee" required />
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                {{ $isFrench ? 'Envoyer la demande de réservation' : 'Send reservation request' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>
@endsection
