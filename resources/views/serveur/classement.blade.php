@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Filtres de date -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        @include('buttons')

        <form action="{{ route('serveur.classement') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ $dateDebut }}" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div class="flex-1">
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ $dateFin }}" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <button type="submit" class="w-full md:w-auto bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Carte du meilleur vendeur style FIFA -->
    @if($vendeurs->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Meilleur vendeur</h2>
            <div class="flex justify-center">
                <div class="w-full max-w-md">
                    @include('serveur.partials.fifa-card', ['vendeur' => $vendeurs->first(), 'isTop' => true])
                </div>
            </div>
        </div>

        <!-- Classement complet -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vendeurs->skip(1) as $index => $vendeur)
                <div>
                    @include('serveur.partials.fifa-card', ['vendeur' => $vendeur, 'isTop' => false])
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-10 text-center">
            <p class="text-gray-500 text-lg">Aucune donnée de vente disponible pour cette période</p>
        </div>
    @endif
</div>
@endsection
