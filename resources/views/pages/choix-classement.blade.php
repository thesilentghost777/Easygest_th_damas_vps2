@extends('layouts.app')

@section('content')
<div class="min-h-full" x-data="{ selected: null, showCards: false }" x-init="setTimeout(() => showCards = true, 100)">
    <main class="py-4 md:py-10">
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('buttons')
            
            <!-- Header avec animation -->
            <div class="text-center mb-6 md:mb-8 transform transition-all duration-700" 
                 :class="showCards ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                    {{ $isFrench ? 'Sélectionnez un classement' : 'Select a ranking' }}
                </h1>
                <p class="text-sm md:text-base text-gray-600 px-4">
                    {{ $isFrench ? 'Choisissez le type de classement à consulter' : 'Choose the type of ranking to view' }}
                </p>
            </div>

            <!-- Cards Grid - Mobile First Design -->
            <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-1 lg:grid-cols-3 md:gap-6 mt-6 md:mt-8">
                
                <!-- Card Serveuse -->
                <div @click="selected = 'serveuse'"
                     :class="{'ring-4 ring-blue-500 transform scale-105 md:scale-105': selected === 'serveuse'}"
                     class="relative bg-white rounded-2xl md:rounded-lg shadow-lg overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-xl transform"
                     :style="showCards ? 'transform: translateY(0); opacity: 1;' : 'transform: translateY(20px); opacity: 0;'"
                     style="transition-delay: 100ms;">
                    
                    <!-- Mobile specific styling -->
                    <div class="md:hidden">
                        <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-blue-600 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-400 rounded-full opacity-20 transform translate-x-6 -translate-y-6"></div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 bg-blue-400 rounded-full opacity-15 transform -translate-x-4 translate-y-4"></div>
                            <div class="flex items-center space-x-4 relative z-10">
                                <div class="h-14 w-14 bg-blue-100 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white">
                                        {{ $isFrench ? 'Classement Serveuse' : 'Waitress Ranking' }}
                                    </h3>
                                    <p class="text-blue-100 text-sm mt-1">
                                        {{ $isFrench ? 'Performances et évaluations des serveuses' : 'Waitress performance and evaluations' }}
                                    </p>
                                </div>
                                <div class="text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop styling -->
                    <div class="hidden md:block px-4 py-5 sm:p-6 bg-gradient-to-br from-blue-500 to-blue-600">
                        <div class="text-center">
                            <div class="h-12 w-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-white">
                                {{ $isFrench ? 'Classement Serveuse' : 'Waitress Ranking' }}
                            </h3>
                            <p class="mt-2 text-sm text-blue-100">
                                {{ $isFrench ? 'Performances et évaluations des serveuses' : 'Waitress performance and evaluations' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Producteur -->
                <div @click="selected = 'producteur'"
                     :class="{'ring-4 ring-green-500 transform scale-105 md:scale-105': selected === 'producteur'}"
                     class="relative bg-white rounded-2xl md:rounded-lg shadow-lg overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-xl transform"
                     :style="showCards ? 'transform: translateY(0); opacity: 1;' : 'transform: translateY(20px); opacity: 0;'"
                     style="transition-delay: 200ms;">
                    
                    <!-- Mobile specific styling -->
                    <div class="md:hidden">
                        <div class="px-6 py-6 bg-gradient-to-r from-green-500 to-green-600 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-green-400 rounded-full opacity-20 transform translate-x-6 -translate-y-6"></div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 bg-green-400 rounded-full opacity-15 transform -translate-x-4 translate-y-4"></div>
                            <div class="flex items-center space-x-4 relative z-10">
                                <div class="h-14 w-14 bg-green-100 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white">
                                        {{ $isFrench ? 'Classement Producteur' : 'Producer Ranking' }}
                                    </h3>
                                    <p class="text-green-100 text-sm mt-1">
                                        {{ $isFrench ? 'Production et efficacité des producteurs' : 'Producer efficiency and output' }}
                                    </p>
                                </div>
                                <div class="text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop styling -->
                    <div class="hidden md:block px-4 py-5 sm:p-6 bg-gradient-to-br from-green-500 to-green-600">
                        <div class="text-center">
                            <div class="h-12 w-12 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-white">
                                {{ $isFrench ? 'Classement Producteur' : 'Producer Ranking' }}
                            </h3>
                            <p class="mt-2 text-sm text-green-100">
                                {{ $isFrench ? 'Production et efficacité des producteurs' : 'Producer efficiency and output' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Employé -->
                <div @click="selected = 'employe'"
                     :class="{'ring-4 ring-blue-500 transform scale-105 md:scale-105': selected === 'employe'}"
                     class="relative bg-white rounded-2xl md:rounded-lg shadow-lg overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-xl transform"
                     :style="showCards ? 'transform: translateY(0); opacity: 1;' : 'transform: translateY(20px); opacity: 0;'"
                     style="transition-delay: 300ms;">
                    
                    <!-- Mobile specific styling -->
                    <div class="md:hidden">
                        <div class="px-6 py-6 bg-gradient-to-r from-blue-400 to-blue-500 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-300 rounded-full opacity-20 transform translate-x-6 -translate-y-6"></div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 bg-blue-300 rounded-full opacity-15 transform -translate-x-4 translate-y-4"></div>
                            <div class="flex items-center space-x-4 relative z-10">
                                <div class="h-14 w-14 bg-blue-100 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-white">
                                        {{ $isFrench ? 'Classement Employé' : 'Employee Ranking' }}
                                    </h3>
                                    <p class="text-blue-100 text-sm mt-1">
                                        {{ $isFrench ? 'Performance globale des employés' : 'Overall employee performance' }}
                                    </p>
                                </div>
                                <div class="text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop styling -->
                    <div class="hidden md:block px-4 py-5 sm:p-6 bg-gradient-to-br from-blue-400 to-blue-500">
                        <div class="text-center">
                            <div class="h-12 w-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-white">
                                {{ $isFrench ? 'Classement Employé' : 'Employee Ranking' }}
                            </h3>
                            <p class="mt-2 text-sm text-blue-100">
                                {{ $isFrench ? 'Performance globale des employés' : 'Overall employee performance' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton de validation avec animations -->
            <div class="mt-8 flex justify-center px-4">
                <div x-show="selected" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 transform translate-y-4 scale-95"
                     class="w-full md:w-auto">
                    <button
                        @click="window.location.href = '/classement/' + selected"
                        class="w-full md:w-auto inline-flex items-center justify-center px-8 py-4 md:px-6 md:py-3 border border-transparent text-base font-medium rounded-2xl md:rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                        
                        <span class="mr-2">
                            {{ $isFrench ? 'Voir le classement' : 'View ranking' }}
                        </span>
                        
                        <!-- Mobile arrow with animation -->
                        <svg class="w-5 h-5 md:hidden transition-transform duration-200 transform group-hover:translate-x-1" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Indicateur de sélection mobile -->
            <div class="md:hidden mt-6 flex justify-center space-x-2" x-show="selected">
                <div class="flex items-center space-x-2 bg-blue-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-blue-700 font-medium">
                        {{ $isFrench ? 'Sélection effectuée' : 'Selection made' }}
                    </span>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection