@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Réglementations" : "Regulations" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-6">
                @include('buttons')
            </div>

            <!-- Header Section -->
            <div class="text-center mb-8 lg:mb-10">
                <h1 class="text-3xl lg:text-4xl font-bold text-blue-800 mb-4 tracking-tight">
                    {{ $isFrench ? "Liste des Réglementations" : "Regulations List" }}
                </h1>
                <a href="{{ route('extras.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl
                          shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 active:scale-95 lg:active:scale-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $isFrench ? "Nouvelle Réglementation" : "New Regulation" }}
                </a>
            </div>

            <!-- Alert Message -->
            @if(session('success'))
            <div class="max-w-4xl mx-auto mb-8">
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-r-xl shadow-md animate-fade-in"
                     role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Mobile Cards View -->
            <div class="lg:hidden space-y-4 mb-6">
                @foreach($extras as $extra)
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 transition-all duration-200 active:scale-98">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-blue-800 capitalize">{{ $extra->secteur }}</h3>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">
                                        {{ $extra->heure_arriver_adequat->format('H:i') }} - {{ $extra->heure_depart_adequat->format('H:i') }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-600">{{ number_format($extra->salaire_adequat, 0, ',', ' ') }} XAF</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $extra->age_adequat }} {{ $isFrench ? "ans" : "years" }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('extras.show', $extra) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg
                                  hover:bg-blue-100 transition-colors duration-200 text-sm font-medium active:scale-95">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $isFrench ? "Voir" : "View" }}
                        </a>
                        <a href="{{ route('extras.edit', $extra) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-yellow-50 text-yellow-700 rounded-lg
                                  hover:bg-yellow-100 transition-colors duration-200 text-sm font-medium active:scale-95">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ $isFrench ? "Modifier" : "Edit" }}
                        </a>
                        <form action="{{ route('extras.destroy', $extra) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-50 text-red-700 rounded-lg
                                           hover:bg-red-100 transition-colors duration-200 text-sm font-medium active:scale-95"
                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette réglementation ?' : 'Are you sure you want to delete this regulation?' }}')">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ $isFrench ? "Suppr." : "Delete" }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table Section -->
            <div class="hidden lg:block bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-600 to-blue-700">
                                <th class="px-6 py-4 text-left text-white font-semibold">
                                    {{ $isFrench ? "Secteur" : "Sector" }}
                                </th>
                                <th class="px-6 py-4 text-left text-white font-semibold">
                                    {{ $isFrench ? "Horaires" : "Schedule" }}
                                </th>
                                <th class="px-6 py-4 text-left text-white font-semibold">
                                    {{ $isFrench ? "Salaire" : "Salary" }}
                                </th>
                                <th class="px-6 py-4 text-left text-white font-semibold">
                                    {{ $isFrench ? "Âge minimum" : "Minimum Age" }}
                                </th>
                                <th class="px-6 py-4 text-left text-white font-semibold">
                                    {{ $isFrench ? "Actions" : "Actions" }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($extras as $extra)
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-blue-800 font-medium capitalize">{{ $extra->secteur }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                                        {{ $extra->heure_arriver_adequat->format('H:i') }} - {{ $extra->heure_depart_adequat->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-green-600 font-medium">{{ number_format($extra->salaire_adequat, 0, ',', ' ') }} XAF</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $extra->age_adequat }} {{ $isFrench ? "ans" : "years" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('extras.show', $extra) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md
                                                  hover:bg-blue-200 transition-colors duration-200">
                                            {{ $isFrench ? "Voir" : "View" }}
                                        </a>
                                        <a href="{{ route('extras.edit', $extra) }}"
                                           class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md
                                                  hover:bg-yellow-200 transition-colors duration-200">
                                            {{ $isFrench ? "Modifier" : "Edit" }}
                                        </a>
                                        <form action="{{ route('extras.destroy', $extra) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md
                                                           hover:bg-red-200 transition-colors duration-200"
                                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette réglementation ?' : 'Are you sure you want to delete this regulation?' }}')">
                                                {{ $isFrench ? "Supprimer" : "Delete" }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $extras->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
    }
    
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-98:active, .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vibration feedback on button clicks
    document.querySelectorAll('button, a[href]').forEach(element => {
        element.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        });
    });
});
</script>
@endsection
