@extends('pages/serveur/serveur_default')

@section('page-content')
@include('buttons')

<div class="flex justify-center items-center min-h-[80vh] px-4 py-12">
    <div class="w-full max-w-xl">
        <h1 class="text-3xl sm:text-2xl text-center font-extrabold text-blue-600 mb-8 animate-fade-in font-[\'Poppins\',sans-serif]">
            {{ $isFrench ? 'Quelle opération voulez-vous effectuer ?' : 'What action would you like to perform?' }}
        </h1>

        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-4 transition-all duration-500 ease-in-out font-[\'Poppins\',sans-serif]">
            <div class="space-y-6 sm:space-y-4">
                <a href="{{ route('bag.receptions.create') }}"
                   class="block bg-green-500 hover:bg-green-700 text-white text-center font-bold py-4 px-6 rounded-xl shadow-md focus:outline-none focus:shadow-outline transform transition-transform duration-300 hover:scale-105 sm:py-3 sm:px-4">
                    📦 {{ $isFrench ? 'Sacs/Contenants reçus' : 'Bags/Containers Received' }}
                </a>

                <a href="{{ route('bag.sales.create') }}"
                   class="block bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-4 px-6 rounded-xl shadow-md focus:outline-none focus:shadow-outline transform transition-transform duration-300 hover:scale-105 sm:py-3 sm:px-4">
                    💰 {{ $isFrench ? 'Sacs/Contenants vendus' : 'Bags/Containers Sold' }}
                </a>
            </div>
        </div>

        
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

@media (max-width: 640px) {
    h1 {
        animation: pop-in 0.6s ease-in-out;
    }

    @keyframes pop-in {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .space-y-6 > * {
        animation: slide-in 0.4s ease-out;
    }

    @keyframes slide-in {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
}
</style>
@endsection
