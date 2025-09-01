@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="text-center mb-6 lg:mb-10 animate-fade-in">
        @include('buttons')
        
        <div class="bg-blue-100 border-l-4 border-blue-600 p-4 rounded-r-xl my-6 mobile-card">
            <div class="flex items-center gap-3">
                <i class="mdi mdi-information text-blue-600 text-xl"></i>
                <p class="text-blue-900 font-medium italic text-sm lg:text-base">
                    {{ $isFrench ? '"Parce que nul n\'est censé ignorer la loi"' : '"Because no one is supposed to ignore the law"' }}
                </p>
            </div>
        </div>
        
        <h1 class="text-2xl lg:text-4xl font-bold text-blue-800 mb-4 tracking-tight">
            {{ $isFrench ? 'Liste des Réglementations' : 'List of Regulations' }}
        </h1>
    </div>

    <!-- Desktop Table Section (hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-xl shadow-xl overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-600 to-blue-700">
                        <th class="px-6 py-4 text-left text-white font-semibold">{{ $isFrench ? 'Secteur' : 'Sector' }}</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">{{ $isFrench ? 'Horaires' : 'Schedule' }}</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">{{ $isFrench ? 'Salaire' : 'Salary' }}</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">{{ $isFrench ? 'Âge minimum' : 'Minimum Age' }}</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($extras as $extra)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-blue-800 font-medium">{{ $extra->secteur }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                                {{ $extra->heure_arriver_adequat->format('H:i') }} - {{ $extra->heure_depart_adequat->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-green-600 font-medium">{{ number_format($extra->salaire_adequat, 2) }} XAF</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $extra->age_adequat }} {{ $isFrench ? 'ans' : 'years' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('extras.show', $extra) }}"
                                   class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md
                                          hover:bg-blue-200 transition-colors duration-200">
                                    {{ $isFrench ? 'Voir' : 'View' }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards Section (visible only on mobile) -->
    <div class="lg:hidden space-y-4">
        @foreach($extras as $extra)
            <div class="bg-white rounded-xl p-4 shadow-lg border border-gray-200 animate-fade-in transform hover:scale-105 transition-all duration-200 mobile-card">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">{{ $extra->secteur }}</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="mdi mdi-clock-outline text-blue-600 mr-2"></i>
                                <span class="text-sm text-gray-700">
                                    {{ $extra->heure_arriver_adequat->format('H:i') }} - {{ $extra->heure_depart_adequat->format('H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="mdi mdi-cash text-green-600 mr-2"></i>
                                <span class="text-sm font-semibold text-green-600">{{ number_format($extra->salaire_adequat, 2) }} XAF</span>
                            </div>
                            <div class="flex items-center">
                                <i class="mdi mdi-account text-purple-600 mr-2"></i>
                                <span class="text-sm text-gray-700">{{ $extra->age_adequat }} {{ $isFrench ? 'ans minimum' : 'years minimum' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('extras.show', $extra) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-colors duration-200 font-medium">
                        <i class="mdi mdi-eye mr-2"></i>{{ $isFrench ? 'Voir les détails' : 'View details' }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center animate-fade-in">
        {{ $extras->links('pagination::tailwind') }}
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .mobile-card {
        transition: all 0.2s ease-out;
    }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card:active {
            transform: scale(0.98) !important;
        }
        /* Touch targets */
        button, a {
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
