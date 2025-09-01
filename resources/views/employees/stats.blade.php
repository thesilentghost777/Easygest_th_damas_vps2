@extends('layouts.app')

@section('content')
<div class="py-12 mobile:py-6 mobile:px-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mobile:px-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mobile:rounded-2xl mobile:shadow-2xl">
            <div class="p-6 mobile:p-4">
                @include('buttons')
                
                <div class="text-center mb-8 mobile:mb-6">
                    <div class="mobile:bg-purple-100 mobile:rounded-full mobile:w-20 mobile:h-20 mobile:flex mobile:items-center mobile:justify-center mobile:mx-auto mobile:mb-4">
                        <svg class="mobile:w-10 mobile:h-10 mobile:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2 mobile:text-xl mobile:text-purple-600">
                        {{ $isFrench ? 'Statistiques Générales' : 'General Statistics' }}
                    </h2>
                    <p class="text-gray-600 mobile:text-gray-500">
                        {{ $isFrench ? 'Vue d\'ensemble des performances des employés' : 'Overview of employee performance' }}
                    </p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 mobile:gap-4 mobile:mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300">
                        <div class="mobile:text-center">
                            <div class="mobile:bg-white mobile:bg-opacity-20 mobile:rounded-full mobile:w-16 mobile:h-16 mobile:flex mobile:items-center mobile:justify-center mobile:mx-auto mobile:mb-3">
                                <svg class="mobile:w-8 mobile:h-8 mobile:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-white text-lg font-semibold mobile:text-base mobile:mb-2">
                                {{ $isFrench ? 'Nombre total d\'employés' : 'Total Employees' }}
                            </h3>
                            <p class="text-white text-3xl font-bold mt-2 mobile:text-2xl">{{ $stats['total_employees'] }}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg shadow-lg mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300">
                        <div class="mobile:text-center">
                            <div class="mobile:bg-white mobile:bg-opacity-20 mobile:rounded-full mobile:w-16 mobile:h-16 mobile:flex mobile:items-center mobile:justify-center mobile:mx-auto mobile:mb-3">
                                <svg class="mobile:w-8 mobile:h-8 mobile:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-white text-lg font-semibold mobile:text-base mobile:mb-2">
                                {{ $isFrench ? 'Note moyenne' : 'Average Score' }}
                            </h3>
                            <p class="text-white text-3xl font-bold mt-2 mobile:text-2xl">
                                {{ number_format($stats['average_note'], 2) }}/20
                            </p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg mobile:rounded-2xl mobile:p-6 mobile:shadow-xl mobile:transform mobile:hover:scale-105 mobile:transition-all mobile:duration-300 mobile:md:col-span-2 mobile:lg:col-span-1">
                        <div class="mobile:text-center">
                            <div class="mobile:bg-white mobile:bg-opacity-20 mobile:rounded-full mobile:w-16 mobile:h-16 mobile:flex mobile:items-center mobile:justify-center mobile:mx-auto mobile:mb-3">
                                <svg class="mobile:w-8 mobile:h-8 mobile:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-white text-lg font-semibold mobile:text-base mobile:mb-2">
                                {{ $isFrench ? 'Âge moyen' : 'Average Age' }}
                            </h3>
                            <p class="text-white text-3xl font-bold mt-2 mobile:text-2xl">
                                {{ number_format($stats['employees']->avg('age'), 0) }} {{ $isFrench ? 'ans' : 'years' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Detailed Table -->
                <div class="mt-8 overflow-x-auto mobile:mt-6 mobile:mx-2">
                    <div class="bg-white rounded-lg shadow-lg mobile:rounded-2xl mobile:shadow-xl">
                        <div class="p-4 border-b mobile:p-6 mobile:text-center">
                            <h3 class="text-lg font-semibold text-gray-800 mobile:text-xl mobile:text-blue-600">
                                {{ $isFrench ? 'Détails des employés' : 'Employee Details' }}
                            </h3>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="overflow-x-auto mobile:hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Employé' : 'Employee' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Âge' : 'Age' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Année de début' : 'Start Year' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Note' : 'Score' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($stats['employees'] as $employee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="bg-blue-100 rounded-full p-2">
                                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $employee->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $employee->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $employee->age }} {{ $isFrench ? 'ans' : 'years' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $employee->date_embauche->format('Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($employee->evaluation)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $employee->evaluation->note >= 15 ? 'bg-green-100 text-green-800' :
                                                       ($employee->evaluation->note >= 10 ? 'bg-yellow-100 text-yellow-800' :
                                                       'bg-red-100 text-red-800') }}">
                                                    {{ number_format($employee->evaluation->note, 1) }}/20
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $isFrench ? 'Non évalué' : 'Not evaluated' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('employees.show', $employee) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $isFrench ? 'Voir détails' : 'View details' }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card Layout -->
                        <div class="hidden mobile:block mobile:p-4 mobile:space-y-4">
                            @foreach($stats['employees'] as $employee)
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl shadow-md transform hover:scale-105 transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-full mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-blue-800 text-lg">{{ $employee->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $employee->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-500">{{ $isFrench ? 'Âge' : 'Age' }}</p>
                                        <p class="font-bold text-blue-700">{{ $employee->age }} {{ $isFrench ? 'ans' : 'years' }}</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-500">{{ $isFrench ? 'Année' : 'Year' }}</p>
                                        <p class="font-bold text-blue-700">{{ $employee->date_embauche->format('Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($employee->evaluation)
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                                {{ $employee->evaluation->note >= 15 ? 'bg-green-500 text-white' :
                                                   ($employee->evaluation->note >= 10 ? 'bg-yellow-500 text-white' :
                                                   'bg-red-500 text-white') }}">
                                                {{ number_format($employee->evaluation->note, 1) }}/20
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-500 text-white">
                                                {{ $isFrench ? 'Non évalué' : 'Not evaluated' }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('employees.show', $employee) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                                        {{ $isFrench ? 'Détails' : 'Details' }}
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile styles */
@media (max-width: 768px) {
    .mobile\:py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .mobile\:px-4 { padding-left: 1rem; padding-right: 1rem; }
    .mobile\:px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .mobile\:p-4 { padding: 1rem; }
    .mobile\:p-6 { padding: 1.5rem; }
    .mobile\:rounded-2xl { border-radius: 1rem; }
    .mobile\:shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .mobile\:shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .mobile\:mb-6 { margin-bottom: 1.5rem; }
    .mobile\:mb-4 { margin-bottom: 1rem; }
    .mobile\:mb-3 { margin-bottom: 0.75rem; }
    .mobile\:mb-2 { margin-bottom: 0.5rem; }
    .mobile\:mt-6 { margin-top: 1.5rem; }
    .mobile\:mx-2 { margin-left: 0.5rem; margin-right: 0.5rem; }
    .mobile\:bg-purple-100 { background-color: #f3e8ff; }
    .mobile\:bg-white { background-color: #ffffff; }
    .mobile\:bg-opacity-20 { background-opacity: 0.2; }
    .mobile\:rounded-full { border-radius: 9999px; }
    .mobile\:w-20 { width: 5rem; }
    .mobile\:h-20 { height: 5rem; }
    .mobile\:w-16 { width: 4rem; }
    .mobile\:h-16 { height: 4rem; }
    .mobile\:w-10 { width: 2.5rem; }
    .mobile\:h-10 { height: 2.5rem; }
    .mobile\:w-8 { width: 2rem; }
    .mobile\:h-8 { height: 2rem; }
    .mobile\:flex { display: flex; }
    .mobile\:items-center { align-items: center; }
    .mobile\:justify-center { justify-content: center; }
    .mobile\:mx-auto { margin-left: auto; margin-right: auto; }
    .mobile\:text-xl { font-size: 1.25rem; }
    .mobile\:text-2xl { font-size: 1.5rem; }
    .mobile\:text-base { font-size: 1rem; }
    .mobile\:text-purple-600 { color: #7c3aed; }
    .mobile\:text-blue-600 { color: #2563eb; }
    .mobile\:text-white { color: #ffffff; }
    .mobile\:text-gray-500 { color: #6b7280; }
    .mobile\:text-center { text-align: center; }
    .mobile\:gap-4 { gap: 1rem; }
    .mobile\:transform { transform: translateVar(--tw-translate-x, 0) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)); }
    .mobile\:hover\:scale-105:hover { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }
    .mobile\:transition-all { transition-property: all; }
    .mobile\:duration-300 { transition-duration: 300ms; }
    .mobile\:md\:col-span-2 { grid-column: span 2 / span 2; }
    .mobile\:lg\:col-span-1 { grid-column: span 1 / span 1; }
    .mobile\:hidden { display: none; }
    .mobile\:block { display: block; }
    .mobile\:space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
    
    /* Touch feedback */
    * {
        -webkit-tap-highlight-color: transparent;
    }
    
    .transform:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered entrance animations for mobile
    if (window.innerWidth <= 768) {
        const cards = document.querySelectorAll('.bg-gradient-to-br');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 + (index * 150));
        });
        
        const employeeCards = document.querySelectorAll('.bg-gradient-to-r.from-gray-50');
        employeeCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateX(-30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateX(0)';
            }, 800 + (index * 100));
        });
    }
    
    // Add haptic feedback for mobile
    const interactiveElements = document.querySelectorAll('a, button');
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(30);
            }
        });
    });
});
</script>
@endsection