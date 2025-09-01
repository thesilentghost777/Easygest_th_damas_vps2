@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-6">
            @include('buttons')
            <div class="mt-4 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 animate-fade-in">
                    {{ $isFrench ? 'Configuration des jours de paie' : 'Payday Configuration' }}
                </h1>
                <p class="text-gray-600 animate-fade-in delay-100">
                    {{ $isFrench ? 'Gérez les paramètres de paiement de vos employés' : 'Manage your employees payment settings' }}
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm animate-slide-in-right">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 animate-scale-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    {{ $isFrench ? 'Configuration des paiements' : 'Payment Configuration' }}
                </h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('payday.config.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Mobile-First Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Salary Day -->
                        <div class="group">
                            <label for="salary_day" class="block text-sm font-medium text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                {{ $isFrench ? 'Jour du mois pour les salaires' : 'Monthly salary day' }}
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="salary_day" 
                                       id="salary_day" 
                                       min="1" 
                                       max="31" 
                                       value="{{ $config->salary_day }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-medium bg-gray-50 focus:bg-white">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                {{ $isFrench ? 'Jour du mois auquel les salaires sont disponibles.' : 'Day of the month when salaries are available.' }}
                            </p>
                            @error('salary_day')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Advance Day -->
                        <div class="group">
                            <label for="advance_day" class="block text-sm font-medium text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                {{ $isFrench ? 'Jour du mois pour les avances sur salaire' : 'Monthly salary advance day' }}
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="advance_day" 
                                       id="advance_day" 
                                       min="1" 
                                       max="31" 
                                       value="{{ $config->advance_day }}" 
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-medium bg-gray-50 focus:bg-white">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                {{ $isFrench ? 'Jour du mois auquel les avances sur salaire sont disponibles.' : 'Day of the month when salary advances are available.' }}
                            </p>
                            @error('advance_day')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" 
                                class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $isFrench ? 'Enregistrer' : 'Save' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Payment Calendar Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $isFrench ? 'Calendrier des paiements' : 'Payment Calendar' }}
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Mobile-Responsive Table -->
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <!-- Mobile View -->
                        <div class="block md:hidden space-y-4">
                            @php
                                $currentDate = now();
                                $months = [];
                                
                                for ($i = 0; $i < 6; $i++) {
                                    $month = $currentDate->copy()->addMonths($i);
                                    
                                    $advanceDate = $month->copy()->day($config->advance_day);
                                    $salaryDate = $month->copy()->day($config->salary_day);
                                    
                                    if ($advanceDate->month != $month->month) {
                                        $advanceDate = $month->copy()->endOfMonth();
                                    }
                                    
                                    if ($salaryDate->month != $month->month) {
                                        $salaryDate = $month->copy()->endOfMonth();
                                    }
                                    
                                    $months[] = [
                                        'name' => $month->format('F Y'),
                                        'advance' => $advanceDate->format('d/m/Y'),
                                        'salary' => $salaryDate->format('d/m/Y')
                                    ];
                                }
                            @endphp
                            
                            @foreach ($months as $index => $month)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-slide-in-left" style="animation-delay: {{ $index * 0.1 }}s">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $month['name'] }}
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Avances' : 'Advances' }}</p>
                                            <p class="font-medium text-orange-600 bg-orange-50 py-2 px-3 rounded-lg">{{ $month['advance'] }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Salaires' : 'Salaries' }}</p>
                                            <p class="font-medium text-green-600 bg-green-50 py-2 px-3 rounded-lg">{{ $month['salary'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden md:block">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Mois' : 'Month' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date avances' : 'Advance date' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date salaires' : 'Salary date' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($months as $index => $month)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150 animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $month['name'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                    {{ $month['advance'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ $month['salary'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

@keyframes slide-in-right {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slide-in-left {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.animate-slide-in-right {
    animation: slide-in-right 0.5s ease-out;
}

.animate-slide-in-left {
    animation: slide-in-left 0.6s ease-out;
}

.delay-100 {
    animation-delay: 0.1s;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
