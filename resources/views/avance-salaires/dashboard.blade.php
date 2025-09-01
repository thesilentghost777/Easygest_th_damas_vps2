@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Tableau de bord des avances" : "Advance dashboard" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
            <h1 class="text-2xl font-bold text-gray-900 mt-4">
                {{ $isFrench ? "Tableau de bord des avances sur salaire" : "Salary advance dashboard" }}
            </h1>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Total Requests Card -->
            <div class="bg-blue-600 rounded-xl lg:rounded-2xl shadow-sm lg:shadow-lg overflow-hidden transform transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="px-4 lg:px-6 py-3 lg:py-4 border-b border-blue-500 border-opacity-30">
                    <h3 class="text-white font-medium text-sm lg:text-base">
                        {{ $isFrench ? "Demandes du mois" : "Monthly requests" }} ({{ $currentMonth }})
                    </h3>
                </div>
                <div class="px-4 lg:px-6 py-4 lg:py-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="block text-2xl lg:text-3xl font-bold text-white">{{ $totalDemandes }}</span>
                            <span class="block text-blue-100 mt-1 text-sm">
                                {{ $isFrench ? "Total" : "Total" }}: {{ number_format($montantTotal, 0, ',', ' ') }} XAF
                            </span>
                        </div>
                        <div class="bg-blue-500 bg-opacity-30 p-2 lg:p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"></path>
                                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"></path>
                                <path d="M12 3v6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="bg-red-600 rounded-xl lg:rounded-2xl shadow-sm lg:shadow-lg overflow-hidden transform transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="px-4 lg:px-6 py-3 lg:py-4 border-b border-red-500 border-opacity-30">
                    <h3 class="text-white font-medium text-sm lg:text-base">
                        {{ $isFrench ? "En attente de validation" : "Pending validation" }}
                    </h3>
                </div>
                <div class="px-4 lg:px-6 py-4 lg:py-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="block text-2xl lg:text-3xl font-bold text-white">{{ $demandesEnAttente }}</span>
                            <span class="block text-red-100 mt-1 text-sm">
                                {{ $isFrench ? "Montant" : "Amount" }}: {{ number_format($montantEnAttente, 0, ',', ' ') }} XAF
                            </span>
                        </div>
                        <div class="bg-red-500 bg-opacity-30 p-2 lg:p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.5 20H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H20a2 2 0 0 1 2 2v3"></path>
                                <circle cx="18" cy="18" r="3"></circle>
                                <path d="M18 15v2l1 1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validated Requests Card -->
            <div class="bg-emerald-600 rounded-xl lg:rounded-2xl shadow-sm lg:shadow-lg overflow-hidden transform transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="px-4 lg:px-6 py-3 lg:py-4 border-b border-emerald-500 border-opacity-30">
                    <h3 class="text-white font-medium text-sm lg:text-base">
                        {{ $isFrench ? "Demandes validées" : "Validated requests" }}
                    </h3>
                </div>
                <div class="px-4 lg:px-6 py-4 lg:py-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="block text-2xl lg:text-3xl font-bold text-white">{{ $demandesValidees }}</span>
                            <span class="block text-emerald-100 mt-1 text-sm">
                                {{ $isFrench ? "Montant" : "Amount" }}: {{ number_format($montantValide, 0, ',', ' ') }} XAF
                            </span>
                        </div>
                        <div class="bg-emerald-500 bg-opacity-30 p-2 lg:p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6 text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm lg:shadow-lg overflow-hidden mb-6 lg:mb-8">
            <div class="px-4 lg:px-6 py-3 lg:py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-medium text-gray-700 text-sm lg:text-base">
                    {{ $isFrench ? "Historique des 6 derniers mois" : "Last 6 months history" }}
                </h2>
            </div>
            <div class="p-4 lg:p-6">
                <canvas id="statsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Advances Table -->
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-sm lg:shadow-lg overflow-hidden">
            <!-- Table Header -->
            <div class="px-4 lg:px-6 py-3 lg:py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-medium text-gray-700 text-sm lg:text-base">
                    {{ $isFrench ? "Liste des avances sur salaire" : "Salary advance list" }} ({{ $currentMonth }})
                </h2>
            </div>

            <!-- Mobile Cards View -->
            <div class="lg:hidden">
                @if(count($avances) > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($avances as $avance)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="font-medium text-gray-900">#{{ $avance->id }}</h3>
                                    <p class="text-sm text-gray-600">{{ $avance->employe_nom }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">{{ number_format($avance->sommeAs, 0, ',', ' ') }} XAF</p>
                                    <p class="text-xs text-gray-500">{{ $avance->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                @if($avance->retrait_valide)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $isFrench ? "Validée" : "Validated" }}
                                    </span>
                                @elseif($avance->retrait_demande)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? "En attente" : "Pending" }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $isFrench ? "Créée" : "Created" }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p class="text-sm text-gray-500">
                            {{ $isFrench ? "Aucune avance sur salaire pour ce mois" : "No salary advance for this month" }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? "Employé" : "Employee" }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? "Montant" : "Amount" }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? "Date de demande" : "Request date" }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? "Statut" : "Status" }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($avances as $avance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $avance->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $avance->employe_nom }}</div>
                                <div class="text-sm text-gray-500">{{ $avance->employe_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($avance->sommeAs, 0, ',', ' ') }} XAF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $avance->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($avance->retrait_valide)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $isFrench ? "Validée" : "Validated" }}
                                    </span>
                                @elseif($avance->retrait_demande)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $isFrench ? "En attente" : "Pending" }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $isFrench ? "Créée" : "Created" }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @if(count($avances) == 0)
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                {{ $isFrench ? "Aucune avance sur salaire pour ce mois" : "No salary advance for this month" }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
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
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-out;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statsData = @json($statistiquesMensuelles);

        const mois = statsData.map(item => {
            const [year, month] = item.mois.split('-');
            return new Date(year, month - 1).toLocaleDateString('{{ $isFrench ? "fr-FR" : "en-US" }}', { month: 'short', year: 'numeric' });
        });

        const nombreDemandes = statsData.map(item => item.nombre);
        const montants = statsData.map(item => item.montant);

        const ctx = document.getElementById('statsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mois,
                datasets: [{
                    label: '{{ $isFrench ? "Nombre de demandes" : "Number of requests" }}',
                    data: nombreDemandes,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: '{{ $isFrench ? "Montant total (XAF)" : "Total amount (XAF)" }}',
                    data: montants,
                    type: 'line',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: '{{ $isFrench ? "Nombre de demandes" : "Number of requests" }}'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: '{{ $isFrench ? "Montant (XAF)" : "Amount (XAF)" }}'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
