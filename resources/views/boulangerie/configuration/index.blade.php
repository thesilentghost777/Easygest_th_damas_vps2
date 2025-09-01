@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Configuration des Sacs</h1>
        <a href="{{ route('boulangerie.configuration.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nouveau Sac
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sacs as $sac)
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $sac->nom }}</h3>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                            Actif
                        </span>
                    </div>
                    
                    @if($sac->description)
                        <p class="text-gray-600 mb-4">{{ $sac->description }}</p>
                    @endif

                    @if($sac->configuration)
                        <div class="bg-blue-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Valeur Moyenne</span>
                                <span class="font-bold text-blue-600">
                                    {{ number_format($sac->configuration->valeur_moyenne_fcfa, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Matières utilisées ({{ $sac->matieres->count() }})</h4>
                        <div class="space-y-1">
                            @foreach($sac->matieres->take(3) as $matiere)
                                <div class="flex justify-between text-xs text-gray-600">
                                    <span>{{ $matiere->nom }}</span>
                                    <span>{{ $matiere->pivot->quantite_utilisee }} {{ $matiere->unite_minimale }}</span>
                                </div>
                            @endforeach
                            @if($sac->matieres->count() > 3)
                                <span class="text-xs text-gray-500">et {{ $sac->matieres->count() - 3 }} autres...</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('boulangerie.configuration.edit', $sac->id) }}" 
                           class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded transition duration-200 text-center">
                            Modifier
                        </a>
                        <form action="{{ route('boulangerie.configuration.destroy', $sac->id) }}" 
                              method="POST" 
                              class="flex-1"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver ce sac ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded transition duration-200">
                                Désactiver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m2-1v6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun sac configuré</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une configuration de sac.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection