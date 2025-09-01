@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-blue-700 mb-2 border-b-2 border-blue-300 pb-2">
                        {{ $isFrench ? 'Résultats pour' : 'Results for' }} {{ $tableName }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Données de la table analysée' : 'Analyzed table data' }}
                    </p>
                </div>
                
                <div class="animate-fade-in delay-100">
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Query Information Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 animate-scale-in">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $isFrench ? 'Informations sur la requête' : 'Query information' }}
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap overflow-x-auto">{{ $message }}</pre>
                </div>
            </div>
        </div>

        <!-- Results Table Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Résultats de la table' : 'Table results' }}
                </h3>
            </div>
            
            <div class="p-6">
                <div class="table-container overflow-x-auto -webkit-overflow-scrolling-touch">
                    @if(!empty($results))
                        <table class="min-w-full divide-y divide-gray-300 border rounded-lg overflow-hidden">
                            <thead class="bg-blue-100 text-blue-800">
                                <tr>
                                    @foreach((array)$results[0] as $column => $value)
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-blue-300 whitespace-nowrap">
                                            {{ $column }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results as $row)
                                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                                        @foreach((array)$row as $value)
                                            <td class="px-4 md:px-6 py-4 text-sm text-gray-900 border-b whitespace-nowrap">
                                                {{ $value }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucun résultat trouvé' : 'No results found' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $isFrench ? 'Cette table ne contient aucune donnée.' : 'This table contains no data.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile-First CSS Animations and Styles -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-100 { animation-delay: 0.1s; }

/* Mobile Table Optimizations */
.table-container {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.table-container::-webkit-scrollbar {
    height: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Mobile table adjustments */
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem !important;
        min-width: 120px;
    }
    
    /* Mobile header adjustments */
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-3xl {
        font-size: 2rem;
    }
    
    /* Touch-friendly elements */
    button, a {
        min-height: 44px;
        touch-action: manipulation;
    }
}

/* Enhanced table styling */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th:first-child,
td:first-child {
    border-left: none;
}

th:last-child,
td:last-child {
    border-right: none;
}

/* Hover effects */
tr:hover {
    background-color: #f8fafc;
}

/* Loading indicators */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endsection
