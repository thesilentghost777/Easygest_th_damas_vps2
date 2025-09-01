@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Bouton retour --}}
    @include('buttons')

    <h1 class="text-2xl md:text-3xl font-semibold text-blue-800 mb-6 text-center">
        {{ $isFrench ? 'Liste des Matières Premières' : 'Raw Materials List' }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($matieres as $matiere)
            <div 
                class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-4 flex flex-col justify-between mobile-card"
            >
                <div>
                    <h2 class="text-lg font-bold text-blue-700">
                        {{ $matiere->nom }}
                    </h2>

                </div>

                {{-- Action par exemple: analyser ou modifier --}}
                <form action="{{ route('gaspillage.details-matiere', ['idMatiere' => $matiere->id]) }}" method="GET" class="mt-4">
                    <button 
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-xl font-semibold text-sm md:text-base
                               transition-transform duration-200 transform hover:scale-105 active:scale-95"
                    >
                        {{ $isFrench ? 'Analyser' : 'Analyze' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<style>
@media (max-width: 768px) {
    .mobile-card {
        border: 1px solid #e0e0e0;
        animation: fadeSlideIn 0.4s ease-in-out both;
        margin-bottom: 12px;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h1 {
        font-size: 1.5rem;
        line-height: 2rem;
    }

    button {
        font-size: 0.9rem;
        border-radius: 1rem;
    }
}
</style>
@endsection
