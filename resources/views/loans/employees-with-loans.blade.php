@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40">
        @include('buttons')
        <h1 class="text-lg font-semibold text-gray-900 mt-2">
            {{ $isFrench ? "Employés avec prêts en cours" : "Employees with active loans" }}
        </h1>
    </div>

    <!-- Desktop/Tablet Layout -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm lg:shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-indigo-600 px-4 lg:px-6 py-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-2">
                <h2 class="text-lg lg:text-xl font-bold text-white">
                    {{ $isFrench ? "Employés avec prêts en cours" : "Employees with active loans" }}
                </h2>
            </div>

            <!-- Messages -->
            <div class="p-4 lg:p-6">
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($employeesWithLoans->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-12 lg:py-16">
                        <div class="p-4 bg-gray-100 rounded-full mb-4 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg lg:text-xl font-medium text-gray-900 mb-2">
                            {{ $isFrench ? "Aucun prêt en cours" : "No active loans" }}
                        </h3>
                        <p class="text-gray-600 text-center text-sm lg:text-base">
                            {{ $isFrench ? "Aucun employé n'a de prêt en cours" : "No employee has an active loan" }}
                        </p>
                    </div>
                @else
                    <!-- Mobile Cards View -->
                    <div class="lg:hidden space-y-4">
                        @foreach($employeesWithLoans as $loan)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 transform transition-all duration-200 active:scale-98">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-indigo-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $loan->employe->name }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ $isFrench ? "Prêt restant" : "Remaining loan" }}:</span>
                                    <span class="font-medium text-gray-900">{{ number_format($loan->pret, 0, ',', ' ') }} XAF</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ $isFrench ? "Remboursement mensuel" : "Monthly repayment" }}:</span>
                                    <span class="font-medium text-gray-900">{{ number_format($loan->remboursement, 0, ',', ' ') }} XAF</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('loans.employee-detail', $loan->id_employe) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium text-center block transition-all duration-200 active:scale-95">
                                {{ $isFrench ? "Voir les détails" : "View details" }}
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Employé" : "Employee" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Prêt restant" : "Remaining loan" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Remboursement mensuel" : "Monthly repayment" }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? "Actions" : "Actions" }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($employeesWithLoans as $loan)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $loan->employe->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($loan->pret, 0, ',', ' ') }} XAF</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($loan->remboursement, 0, ',', ' ') }} XAF</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('loans.employee-detail', $loan->id_employe) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md transition-colors">
                                                {{ $isFrench ? "Détails" : "Details" }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-95:active, .active\:scale-98:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}
</style>
@endsection
