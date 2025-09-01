@extends('pages.pdg.pdg_default')

@section('page-content')
<div class="min-h-screen bg-gray-100">
    <br>

    <!-- Mobile Container -->
    <div class="md:hidden px-0.5 pb-20">
        <div class="bg-white rounded-t-1xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Period Selector with Custom Dates -->
                <div class="mb-6">
                    <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500">
                        <h3 class="text-blue-800 font-semibold mb-3">
                            {{ $isFrench ? 'Période actuelle' : 'Current Period' }}
                        </h3>
                        <p class="text-blue-700 text-sm mb-4">{{ $periodLabel }}</p>
                        
                        <!-- Enhanced period selector for mobile -->
                        <form action="{{ route('pdg.workspace') }}" method="GET" class="space-y-3">
                            <select name="period_type" id="mobile_period_type" onchange="toggleMobileCustomDates(this.value)" class="w-full p-3 rounded-xl border-2 border-blue-200 focus:border-blue-500 focus:ring-0 bg-white">
                                <option value="day" {{ $periodType == 'day' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                                </option>
                                <option value="week" {{ $periodType == 'week' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Cette semaine' : 'This week' }}
                                </option>
                                <option value="month" {{ $periodType == 'month' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Ce mois' : 'This month' }}
                                </option>
                                <option value="year" {{ $periodType == 'year' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Cette année' : 'This year' }}
                                </option>
                                <option value="custom" {{ $periodType == 'custom' ? 'selected' : '' }}>
                                    {{ $isFrench ? 'Période personnalisée' : 'Custom Period' }}
                                </option>
                            </select>
                            
                            <!-- Custom date fields for mobile -->
                            <div id="mobile_custom_dates" class="space-y-3 {{ $periodType != 'custom' ? 'hidden' : '' }}">
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 mb-1">
                                        {{ $isFrench ? 'Date de début' : 'Start Date' }}
                                    </label>
                                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full p-3 rounded-xl border-2 border-blue-200 focus:border-blue-500 focus:ring-0 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 mb-1">
                                        {{ $isFrench ? 'Date de fin' : 'End Date' }}
                                    </label>
                                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full p-3 rounded-xl border-2 border-blue-200 focus:border-blue-500 focus:ring-0 bg-white">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                {{ $isFrench ? 'Appliquer' : 'Apply' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Stats Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right">
                        <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalEmployees) }}</p>
                        <p class="text-xs text-blue-800">{{ $isFrench ? 'Employés' : 'Employees' }}</p>
                    </div>

                    <div class="bg-green-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.1s">
                        <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-green-600">{{ number_format($totalRevenue) }}</p>
                        <p class="text-xs text-green-800">{{ $isFrench ? 'CA FCFA' : 'Revenue FCFA' }}</p>
                    </div>

                    <div class="bg-red-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.2s">
                        <div class="bg-red-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-red-600">{{ number_format($totalExpenses) }}</p>
                        <p class="text-xs text-red-800">{{ $isFrench ? 'Dépenses FCFA' : 'Expenses FCFA' }}</p>
                    </div>

                    <div class="bg-indigo-50 rounded-2xl p-4 text-center transform hover:scale-105 transition-all duration-200 animate-slide-in-right" style="animation-delay: 0.3s">
                        <div class="bg-indigo-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-indigo-600">{{ number_format($profit) }}</p>
                        <p class="text-xs text-indigo-800">{{ $isFrench ? 'Profit FCFA' : 'Profit FCFA' }}</p>
                    </div>
                </div>

                <!-- Mobile Charts Section -->
                <div class="space-y-6 mb-6">
                    <!-- Employee Distribution Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-4 py-3 bg-purple-50 rounded-t-2xl border-b border-purple-100">
                            <h3 class="text-purple-800 font-semibold text-sm">
                                {{ $isFrench ? 'Répartition des employés' : 'Employee Distribution' }}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="relative h-48">
                                <canvas id="mobileEmployeeSectorChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Evolution Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-4 py-3 bg-blue-50 rounded-t-2xl border-b border-blue-100">
                            <h3 class="text-blue-800 font-semibold text-sm">
                                {{ $isFrench ? 'Évolution du CA' : 'Revenue Evolution' }}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="relative h-48">
                                <canvas id="mobileRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Evolution Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-4 py-3 bg-green-50 rounded-t-2xl border-b border-green-100">
                            <h3 class="text-green-800 font-semibold text-sm">
                                {{ $isFrench ? 'Évolution des ventes' : 'Sales Evolution' }}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="relative h-48">
                                <canvas id="mobileSalesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Deposits by Sector Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-4 py-3 bg-amber-50 rounded-t-2xl border-b border-amber-100">
                            <h3 class="text-amber-800 font-semibold text-sm">
                                {{ $isFrench ? 'Versements par secteur' : 'Deposits by Sector' }}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="relative h-48">
                                <canvas id="mobileDepositsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Performance Section -->
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-2xl p-4 border-l-4 border-gray-500">
                        <h3 class="text-gray-800 font-semibold mb-4">
                            {{ $isFrench ? 'Performance période' : 'Period Performance' }}
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">{{ $isFrench ? 'Production' : 'Production' }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($productionValuePeriod) }} FCFA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">{{ $isFrench ? 'Matières premières' : 'Raw Materials' }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($rawMaterialCostPeriod) }} FCFA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">{{ $isFrench ? 'Ventes' : 'Sales' }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($salesPeriod) }} FCFA</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between items-center">
                                <span class="text-gray-700 font-semibold">{{ $isFrench ? 'Marge' : 'Margin' }}</span>
                                <span class="font-bold text-green-600">{{ number_format($salesPeriod - $rawMaterialCostPeriod) }} FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500">
                        <h3 class="text-amber-800 font-semibold mb-4">
                            {{ $isFrench ? 'Versements par secteur' : 'Deposits by Sector' }}
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-amber-700">{{ $isFrench ? 'Alimentation' : 'Food' }}</span>
                                <span class="font-semibold text-amber-900">{{ number_format($cashierDeposits) }} FCFA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-amber-700">{{ $isFrench ? 'Glace' : 'Ice' }}</span>
                                <span class="font-semibold text-amber-900">{{ number_format($iceDeposits) }} FCFA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-amber-700">{{ $isFrench ? 'Ventes' : 'Sales' }}</span>
                                <span class="font-semibold text-amber-900">{{ number_format($salesDeposits) }} FCFA</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between items-center">
                                <span class="text-amber-700 font-semibold">Total</span>
                                <span class="font-bold text-amber-900">{{ number_format($cashierDeposits + $iceDeposits + $salesDeposits) }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Recent Incidents -->
                @if($recentIncidents->count() > 0)
                <div class="bg-red-50 rounded-2xl p-4 border-l-4 border-red-500 mt-6">
                    <h3 class="text-red-800 font-semibold mb-4">
                        {{ $isFrench ? 'Incidents récents' : 'Recent Incidents' }}
                        <span class="bg-red-200 text-red-800 text-xs px-2 py-1 rounded-full ml-2">{{ $incidentsCount }}</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($recentIncidents->take(5) as $incident)
                            <div class="bg-white p-3 rounded-xl">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $incident->incident }}</p>
                                        <p class="text-sm text-gray-600">{{ $incident->employee }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="font-bold text-red-600 text-sm">{{ number_format($incident->montant) }} FCFA</span>
                                </div>
                            </div>
                        @endforeach
                        @if($recentIncidents->count() > 5)
                            <p class="text-center text-sm text-gray-500">
                                {{ $isFrench ? 'Et' : 'And' }} {{ $recentIncidents->count() - 5 }} {{ $isFrench ? 'autres...' : 'others...' }}
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-6">
            <div class="container mx-auto px-4">
                @include('buttons')
                
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 lg:mb-0">
                        {{ $isFrench ? 'Tableau de bord du PDG' : 'CEO Dashboard' }}
                    </h1>
                    
                    <!-- Date Range Selector -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden p-4 w-full lg:w-auto">
                        <form action="{{ route('pdg.workspace') }}" method="GET" class="flex flex-wrap items-center gap-3">
                            <div class="flex flex-col">
                                <label for="period_type" class="text-sm font-medium text-gray-600 mb-1">
                                    {{ $isFrench ? 'Période' : 'Period' }}
                                </label>
                                <select id="period_type" name="period_type" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleCustomDates(this.value)">
                                    <option value="day" {{ $periodType == 'day' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                                    </option>
                                    <option value="week" {{ $periodType == 'week' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Cette semaine' : 'This week' }}
                                    </option>
                                    <option value="month" {{ $periodType == 'month' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Ce mois' : 'This month' }}
                                    </option>
                                    <option value="year" {{ $periodType == 'year' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Cette année' : 'This year' }}
                                    </option>
                                    <option value="custom" {{ $periodType == 'custom' ? 'selected' : '' }}>
                                        {{ $isFrench ? 'Personnalisé' : 'Custom' }}
                                    </option>
                                </select>
                            </div>
                            
                            <div id="custom_dates" class="flex gap-3 {{ $periodType != 'custom' ? 'hidden' : '' }}">
                                <div class="flex flex-col">
                                    <label for="start_date" class="text-sm font-medium text-gray-600 mb-1">
                                        {{ $isFrench ? 'Du' : 'From' }}
                                    </label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div class="flex flex-col">
                                    <label for="end_date" class="text-sm font-medium text-gray-600 mb-1">
                                        {{ $isFrench ? 'Au' : 'To' }}
                                    </label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div class="self-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    {{ $isFrench ? 'Appliquer' : 'Apply' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Period Indicator -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 p-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-lg text-gray-800">
                            {{ $isFrench ? 'Période actuelle:' : 'Current period:' }} 
                            <span class="text-blue-600">{{ $periodLabel }}</span>
                        </h2>
                        <span class="text-sm text-gray-500">
                            {{ $isFrench ? 'Les données affichées sont pour cette période' : 'Data shown is for this period' }}
                        </span>
                    </div>
                </div>
                
                <!-- Stats Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Effectif Total -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-blue-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-sm font-medium text-gray-600">
                                        {{ $isFrench ? 'Effectif Total' : 'Total Staff' }}
                                    </h2>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalEmployees) }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $isFrench ? 'Employés actifs' : 'Active employees' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chiffre d'Affaires -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-green-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-sm font-medium text-gray-600">
                                        {{ $isFrench ? 'Chiffre d\'Affaires' : 'Revenue' }}
                                    </h2>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue) }} FCFA</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $isFrench ? 'Transactions entrantes' : 'Incoming transactions' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dépenses -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-red-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-sm font-medium text-gray-600">
                                        {{ $isFrench ? 'Dépenses' : 'Expenses' }}
                                    </h2>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalExpenses) }} FCFA</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $isFrench ? 'Transactions sortantes' : 'Outgoing transactions' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-indigo-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-sm font-medium text-gray-600">
                                        {{ $isFrench ? 'Profit' : 'Profit' }}
                                    </h2>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($profit) }} FCFA</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $isFrench ? 'Marge:' : 'Margin:' }} {{ number_format($profitMargin, 2) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition des employés par secteur (Diagramme) -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Répartition des employés par secteur' : 'Employee Distribution by Sector' }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="aspect-w-1 aspect-h-1">
                            <canvas id="employeeSectorChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Évolution CA par mois (Graphique) -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Évolution du chiffre d\'affaires' : 'Revenue Evolution' }}</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="revenueChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Stats de la période sélectionnée -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Performance sur la période' : 'Period Performance' }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Production' : 'Production' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($productionValuePeriod) }} FCFA</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Matières premières' : 'Raw Materials' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($rawMaterialCostPeriod) }} FCFA</p>
                                </div>
                                <div class="bg-red-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Ventes' : 'Sales' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($salesPeriod) }} FCFA</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Marge sur la période' : 'Margin for the period' }}</h4>
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ number_format($salesPeriod - $rawMaterialCostPeriod) }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Évolution des ventes par mois (Graphique) -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Évolution des ventes' : 'Sales Evolution' }}</h3>
                        </div>
                        <div class="p-6">
                            <canvas id="salesChart" height="320"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Versements alimentation, glace, ventes -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 ">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Versements par secteur' : 'Deposits by Sector' }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Alimentation' : 'General Store' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($cashierDeposits) }} FCFA</p>
                                </div>
                                <div class="bg-amber-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Glace' : 'Ice' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($iceDeposits) }} FCFA</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Ventes' : 'Sales' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($salesDeposits) }} FCFA</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ $isFrench ? 'Total' : 'Total' }}</h4>
                                    <p class="text-xl font-semibold text-gray-900">{{ number_format($cashierDeposits + $iceDeposits + $salesDeposits) }} FCFA</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <canvas id="depositsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Incidents récents -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $isFrench ? 'Incidents récents' : 'Recent Incidents' }}</h3>
                        <div class="flex items-center">
                            <div class="bg-red-100 rounded-full p-2 mr-2">
                                <span class="text-red-700 text-xs font-medium">{{ $incidentsCount }}</span>
                            </div>
                            <span class="text-gray-500 text-sm">{{ $isFrench ? 'Total:' : 'Total:' }} {{ number_format($incidentsAmount) }} FCFA</span>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Incident' : 'Incident' }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Employé' : 'Employee' }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentIncidents as $incident)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $incident->incident }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $incident->employee }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ number_format($incident->montant) }} FCFA</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">{{ $isFrench ? 'Aucun incident récent' : 'No recent incidents' }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}

/* Loading placeholder for charts */
.chart-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 192px;
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 0.75rem;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to toggle custom date fields for mobile
    function toggleMobileCustomDates(value) {
        const customDatesDiv = document.getElementById('mobile_custom_dates');
        if (value === 'custom') {
            customDatesDiv.classList.remove('hidden');
        } else {
            customDatesDiv.classList.add('hidden');
        }
    }
    
    // Function to toggle custom date fields for desktop
    function toggleCustomDates(value) {
        const customDatesDiv = document.getElementById('custom_dates');
        if (value === 'custom') {
            customDatesDiv.classList.remove('hidden');
        } else {
            customDatesDiv.classList.add('hidden');
        }
    }
    
    // Chart data
    const employeeSectorData = {
        labels: {!! json_encode($employeesBySector->pluck('sector')) !!},
        values: {!! json_encode($employeesBySector->pluck('count')) !!}
    };
    
    const monthlyRevenueData = {
        labels: {!! json_encode($revenueByMonth->pluck('month')) !!},
        values: {!! json_encode($revenueByMonth->pluck('total')) !!}
    };
    
    const monthlySalesData = {
        labels: {!! json_encode($salesByMonth->pluck('month')) !!},
        values: {!! json_encode($salesByMonth->pluck('total')) !!}
    };
    
    const depositsData = {
        labels: ['{{ $isFrench ? "Alimentation" : "Food" }}', '{{ $isFrench ? "Glace" : "Ice" }}', '{{ $isFrench ? "Ventes" : "Sales" }}'],
        values: [{{ $cashierDeposits }}, {{ $iceDeposits }}, {{ $salesDeposits }}]
    };
    
    // Colors
    const colors = {
        blue: '#3b82f6',
        green: '#10b981',
        red: '#ef4444',
        amber: '#f59e0b',
        indigo: '#6366f1',
        purple: '#8b5cf6',
        pink: '#ec4899',
        orange: '#f97316',
        cyan: '#06b6d4',
        yellow: '#eab308'
    };
    
    // Common chart options for mobile
    const mobileChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 10,
                    padding: 10,
                    font: {
                        size: 10
                    }
                }
            }
        }
    };
    
    // Load mobile charts
    if (window.innerWidth < 768) {
        // Mobile Employee Sector Chart
        const mobileEmployeeSectorChart = new Chart(
            document.getElementById('mobileEmployeeSectorChart'),
            {
                type: 'doughnut',
                data: {
                    labels: employeeSectorData.labels,
                    datasets: [{
                        data: employeeSectorData.values,
                        backgroundColor: Object.values(colors),
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: mobileChartOptions
            }
        );
        
        // Mobile Revenue Chart
        const mobileRevenueChart = new Chart(
            document.getElementById('mobileRevenueChart'),
            {
                type: 'line',
                data: {
                    labels: monthlyRevenueData.labels,
                    datasets: [{
                        label: '{{ $isFrench ? "CA" : "Revenue" }}',
                        data: monthlyRevenueData.values,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: colors.blue,
                        tension: 0.3,
                        pointBackgroundColor: colors.blue,
                        pointRadius: 3
                    }]
                },
                options: {
                    ...mobileChartOptions,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            display: false
                        },
                        x: {
                            display: false
                        }
                    }
                }
            }
        );
        
        // Mobile Sales Chart
        const mobileSalesChart = new Chart(
            document.getElementById('mobileSalesChart'),
            {
                type: 'bar',
                data: {
                    labels: monthlySalesData.labels,
                    datasets: [{
                        label: '{{ $isFrench ? "Ventes" : "Sales" }}',
                        data: monthlySalesData.values,
                        backgroundColor: colors.green,
                        borderWidth: 0,
                        borderRadius: 2
                    }]
                },
                options: {
                    ...mobileChartOptions,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            display: false
                        },
                        x: {
                            display: false
                        }
                    }
                }
            }
        );
        
        // Mobile Deposits Chart
        const mobileDepositsChart = new Chart(
            document.getElementById('mobileDepositsChart'),
            {
                type: 'pie',
                data: {
                    labels: depositsData.labels,
                    datasets: [{
                        data: depositsData.values,
                        backgroundColor: [colors.amber, colors.blue, colors.purple],
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: mobileChartOptions
            }
        );
    } else {
        // Desktop charts
        // Répartition des employés par secteur (Diagramme)
        const employeeSectorChart = new Chart(
            document.getElementById('employeeSectorChart'),
            {
                type: 'doughnut',
                data: {
                    labels: employeeSectorData.labels,
                    datasets: [{
                        data: employeeSectorData.values,
                        backgroundColor: Object.values(colors),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            }
        );
        
        // Évolution CA par mois
        const revenueChart = new Chart(
            document.getElementById('revenueChart'),
            {
                type: 'line',
                data: {
                    labels: monthlyRevenueData.labels,
                    datasets: [{
                        label: 'Chiffre d\'affaires',
                        data: monthlyRevenueData.values,
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: colors.blue,
                        tension: 0.3,
                        pointBackgroundColor: colors.blue
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            }
        );
        
        // Évolution des ventes par mois
        const salesChart = new Chart(
            document.getElementById('salesChart'),
            {
                type: 'bar',
                data: {
                    labels: monthlySalesData.labels,
                    datasets: [{
                        label: 'Ventes',
                        data: monthlySalesData.values,
                        backgroundColor: colors.green,
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            }
        );
        
        // Graphique des versements par secteur
        const depositsChart = new Chart(
            document.getElementById('depositsChart'),
            {
                type: 'pie',
                data: {
                    labels: depositsData.labels,
                    datasets: [{
                        data: depositsData.values,
                        backgroundColor: [colors.amber, colors.blue, colors.purple],
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            }
        );
    }
</script>
@endpush
@endsection
