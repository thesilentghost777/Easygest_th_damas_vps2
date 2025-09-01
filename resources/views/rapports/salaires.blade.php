@extends('rapports.layout.rapport')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Report Header -->
        <div class="mb-8 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-700 px-6 py-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                        {{ $isFrench ? 'Rapport des Salaires' : 'Salary Report' }}
                    </h1>
                    <p class="text-blue-100">
                        {{ $isFrench 
                            ? 'Ce rapport présente la liste des salaires des employés pour le mois de ' . $currentMonthName 
                            : 'This report presents the list of employee salaries for the month of ' . $currentMonthName 
                        }}
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Salaries -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 animate-scale-in">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Total des Salaires' : 'Total Salaries' }}</p>
                                    <p class="text-2xl font-bold text-blue-800">{{ number_format($totalSalaires, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Number of Employees -->
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200 animate-scale-in" style="animation-delay: 0.1s">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">{{ $isFrench ? 'Nombre d\'Employés' : 'Number of Employees' }}</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $nombreEmployes }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Average Salary -->
                        <div class="bg-teal-50 rounded-xl p-4 border border-teal-200 animate-scale-in" style="animation-delay: 0.2s">
                            <div class="flex items-center">
                                <div class="p-3 bg-teal-100 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-teal-600 font-medium">{{ $isFrench ? 'Salaire Moyen' : 'Average Salary' }}</p>
                                    <p class="text-2xl font-bold text-teal-800">{{ number_format($salaireMoyen, 0, ',', ' ') }} XAF</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.1s">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $isFrench ? 'Résumé des salaires' : 'Salary summary' }}
            </h3>

            <p class="text-gray-700 leading-relaxed mb-4">
                {{ $isFrench 
                    ? 'Au cours du mois de ' . $currentMonthName . ', un total de'
                    : 'During the month of ' . $currentMonthName . ', a total of'
                }}
                <strong>{{ number_format($totalSalaires, 0, ',', ' ') }} XAF</strong>
                {{ $isFrench 
                    ? ' a été distribué en salaires à ' . $nombreEmployes . ' employé(s). Le salaire moyen s\'élève à'
                    : ' was distributed in salaries to ' . $nombreEmployes . ' employee(s). The average salary amounts to'
                }}
                <strong>{{ number_format($salaireMoyen, 0, ',', ' ') }} XAF</strong>
                {{ $isFrench ? ' par employé.' : ' per employee.' }}
            </p>
        </div>

        <!-- Payment Status Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.2s">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ $isFrench ? 'Statut des paiements' : 'Payment status' }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Validated -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold py-1 px-3 rounded-full text-green-700 bg-green-200">
                            {{ $isFrench ? 'Validés' : 'Validated' }}
                        </span>
                        <span class="text-sm font-semibold text-green-600">{{ $pourcentageValides }}%</span>
                    </div>
                    <div class="w-full bg-green-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full transition-all duration-300" style="width: {{ $pourcentageValides }}%"></div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold py-1 px-3 rounded-full text-yellow-700 bg-yellow-200">
                            {{ $isFrench ? 'En attente' : 'Pending' }}
                        </span>
                        <span class="text-sm font-semibold text-yellow-600">{{ $pourcentageEnAttente }}%</span>
                    </div>
                    <div class="w-full bg-yellow-200 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full transition-all duration-300" style="width: {{ $pourcentageEnAttente }}%"></div>
                    </div>
                </div>

                <!-- Unprocessed -->
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold py-1 px-3 rounded-full text-red-700 bg-red-200">
                            {{ $isFrench ? 'Non traités' : 'Unprocessed' }}
                        </span>
                        <span class="text-sm font-semibold text-red-600">{{ $pourcentageNonTraites }}%</span>
                    </div>
                    <div class="w-full bg-red-200 rounded-full h-3">
                        <div class="bg-red-500 h-3 rounded-full transition-all duration-300" style="width: {{ $pourcentageNonTraites }}%"></div>
                    </div>
                </div>

                <!-- Validated Amount -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 flex flex-col justify-center items-center">
                    <p class="text-sm text-blue-600 mb-2 font-medium">{{ $isFrench ? 'Montant total validé' : 'Total validated amount' }}</p>
                    <p class="text-xl font-bold text-blue-700">{{ number_format($montantValide, 0, ',', ' ') }} XAF</p>
                </div>
            </div>
        </div>

        <!-- Salary Details Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ $isFrench ? 'Détail des salaires par employé' : 'Salary details by employee' }}
                </h3>
            </div>

            @if($salaires->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Employé' : 'Employee' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Salaire' : 'Salary' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Mois' : 'Month' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date de création' : 'Created date' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($salaires as $salaire)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ optional($salaire->employe)->name ?? ($isFrench ? 'Employé inconnu' : 'Unknown employee') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    {{ number_format($salaire->somme, 0, ',', ' ') }} XAF
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $salaire->mois_salaire }}
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    @if($salaire->retrait_valide)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Validé' : 'Validated' }}
                                        </span>
                                    @elseif($salaire->retrait_demande)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $isFrench ? 'Non traité' : 'Unprocessed' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $salaire->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-gray-700 text-sm">
                        {{ $isFrench 
                            ? 'Le tableau ci-dessus présente la liste complète des salaires pour le mois de ' . $currentMonthName . ', avec le nom de l\'employé, le montant du salaire, le mois concerné et le statut actuel de validation.'
                            : 'The table above presents the complete list of salaries for the month of ' . $currentMonthName . ', with the employee name, salary amount, concerned month and current validation status.'
                        }}
                    </p>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-blue-600 mb-2">
                        {{ $isFrench ? 'Aucun salaire enregistré' : 'No salaries recorded' }}
                    </h3>
                    <p class="text-blue-500">
                        {{ $isFrench 
                            ? 'Aucun salaire n\'a été enregistré pour le mois de ' . $currentMonthName . '.'
                            : 'No salaries were recorded for the month of ' . $currentMonthName . '.'
                        }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8 flex justify-center animate-fade-in-up" style="animation-delay: 0.4s">
            @include('buttons')
        </div>
    </div>
</div>

<!-- Mobile-First CSS -->
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
    opacity: 0;
    animation-fill-mode: forwards;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-2xl, .text-3xl {
        font-size: 1.5rem;
    }
    
    .grid-cols-3, .grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    /* Mobile table adjustments */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem !important;
        min-width: 100px;
    }
}

/* Progress bar animations */
.bg-green-500, .bg-yellow-500, .bg-red-500 {
    transition: width 0.5s ease-in-out;
}

/* Smooth transitions */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
@endsection
