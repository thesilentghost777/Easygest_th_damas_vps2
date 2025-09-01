<!-- resources/views/employees/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-4 sm:p-6">
                @include('buttons')
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">
                    {{ $isFrench ? 'Liste des Employés' : 'Employee List' }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($employees as $employee)
                    <a href="{{ route('employees.show', $employee) }}" class="block group transition-all duration-300 hover:scale-[1.02]">
                        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300 ease-in-out">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start sm:space-x-4">
                                <div class="bg-blue-100 p-4 rounded-full mb-4 sm:mb-0">
                                    <svg class="w-12 h-12 text-blue-600 animate-bounce sm:animate-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="text-center sm:text-left">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $employee->name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $isFrench ? $employee->age . ' ans' : $employee->age . ' years old' }}
                                    </p>
                                    @php
                                        $latestEvaluation = $employee->evaluation->first();
                                    @endphp
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $latestEvaluation && $latestEvaluation->note >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $isFrench ? 'Note' : 'Score' }}: {{ $latestEvaluation ? $latestEvaluation->note : 0 }}/20
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
