@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isFrench ? 'Gestion des Rations' : 'Ration Management' }}
        </h2>
    </x-slot>

    <div class="py-4 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            
            <!-- Ration par défaut -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-4 md:p-6 mb-4 md:mb-6 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center mb-4 md:mb-4">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full mr-3 md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-medium text-gray-900">
                        {{ $isFrench ? 'Ration par défaut' : 'Default Ration' }}
                    </h3>
                </div>
                
                <form method="POST" action="{{ route('rations.admin.update-default') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                    @csrf
                    <div class="flex-1">
                        <label for="montant_defaut" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="montant_defaut" 
                                   id="montant_defaut" 
                                   min="0" 
                                   step="100"
                                   value="{{ $ration ? $ration->montant_defaut : 0 }}"
                                   class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg md:rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 text-base md:text-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none md:hidden">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" 
                                class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 md:px-4 md:py-2 border border-transparent text-sm font-medium rounded-lg md:rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 mr-2 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{ $isFrench ? 'Mettre à jour pour tous' : 'Update for All' }}
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 p-3 bg-blue-50 rounded-lg md:mt-2 md:p-0 md:bg-transparent">
                    <p class="text-sm text-gray-600 md:text-gray-500 leading-relaxed">
                        <svg class="w-4 h-4 inline mr-2 text-blue-500 md:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $isFrench ? 'Ce montant sera appliqué à tous les employés qui n\'ont pas de ration personnalisée.' : 'This amount will be applied to all employees who do not have a personalized ration.' }}
                    </p>
                </div>
            </div>

            <!-- Rations personnalisées -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-4 md:p-6 mb-4 md:mb-6 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center mb-4 md:mb-4">
                    <div class="w-2 h-8 bg-green-600 rounded-full mr-3 md:hidden"></div>
                    <h3 class="text-lg md:text-lg font-medium text-gray-900">
                        {{ $isFrench ? 'Rations personnalisées par employé' : 'Personalized Rations by Employee' }}
                    </h3>
                </div>

                <form method="POST" action="{{ route('rations.admin.update-employee') }}" class="mb-6 md:mb-8 bg-gray-50 p-4 md:p-4 rounded-lg">
                    @csrf
                    <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-1 lg:grid-cols-3 md:gap-4">
                        <div class="space-y-4 md:space-y-0 md:col-span-1 lg:col-span-3 lg:grid lg:grid-cols-3 lg:gap-4">
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Employé' : 'Employee' }}
                                </label>
                                <div class="relative">
                                    <select name="employee_id" 
                                            id="employee_id" 
                                            class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg md:rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 text-base md:text-sm appearance-none bg-white">
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }}
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="montant" 
                                           id="montant" 
                                           min="0" 
                                           step="100" 
                                           value="{{ $ration ? $ration->montant_defaut : 0 }}"
                                           class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg md:rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 text-base md:text-sm">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none md:hidden">
                                        <span class="text-gray-500 text-sm">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full lg:w-auto inline-flex items-center justify-center px-6 py-3 md:px-4 md:py-2 border border-transparent text-sm font-medium rounded-lg md:rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:scale-105 active:scale-95">
                                    <svg class="w-4 h-4 mr-2 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ $isFrench ? 'Attribuer' : 'Assign' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-6 md:mt-8">
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-6 bg-purple-600 rounded-full mr-3 md:hidden"></div>
                        <h4 class="text-base md:text-md font-medium text-gray-700">
                            {{ $isFrench ? 'Liste des rations par employé' : 'List of Rations by Employee' }}
                        </h4>
                    </div>
                    
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Employé' : 'Employee' }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Montant' : 'Amount' }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $isFrench ? 'Type' : 'Type' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($employeeRations as $er)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $er->employee->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($er->montant, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($er->personnalise)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $isFrench ? 'Personnalisée' : 'Personalized' }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $isFrench ? 'Par défaut' : 'Default' }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Version mobile - Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach ($employeeRations as $er)
                            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-102">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold text-gray-900">{{ $er->employee->name }}</h5>
                                                <p class="text-xs text-gray-500">
                                                    {{ $isFrench ? 'Employé' : 'Employee' }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-3">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ number_format($er->montant, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                            
                                            @if ($er->personnalise)
                                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    {{ $isFrench ? 'Personnalisée' : 'Personalized' }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                    {{ $isFrench ? 'Par défaut' : 'Default' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($employeeRations->isEmpty())
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-gray-500 text-sm">
                                    {{ $isFrench ? 'Aucune ration personnalisée trouvée' : 'No personalized rations found' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .transform.hover\:scale-102:hover {
                transform: scale(1.02);
            }
            
            .bg-white {
                transition: all 0.3s ease;
            }
            
            input:focus, select:focus {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
            }
            
            button:active {
                transform: scale(0.98);
            }
            
            .card-animation {
                animation: slideInUp 0.3s ease-out;
            }
            
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
        
        /* Animation pour les cartes mobiles */
        @media (max-width: 768px) {
            .md\:hidden.space-y-3 > div {
                animation: slideInUp 0.3s ease-out;
                animation-fill-mode: both;
            }
            
            .md\:hidden.space-y-3 > div:nth-child(1) { animation-delay: 0.1s; }
            .md\:hidden.space-y-3 > div:nth-child(2) { animation-delay: 0.2s; }
            .md\:hidden.space-y-3 > div:nth-child(3) { animation-delay: 0.3s; }
            .md\:hidden.space-y-3 > div:nth-child(4) { animation-delay: 0.4s; }
            .md\:hidden.space-y-3 > div:nth-child(5) { animation-delay: 0.5s; }
        }
    </style>
@endsection