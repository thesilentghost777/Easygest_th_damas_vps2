@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Debug - Sherlock Conseiller ({{ $month_name }})</h1>
                @include('buttons')
            </div>

            @if(!$success)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Erreur</p>
                    <p>{{ $error }}</p>
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Données brutes collectées pour l'analyse</h2>
                <p class="text-sm text-gray-600 mb-4">Cette vue est destinée au débogage et au développement. Elle affiche toutes les données collectées par Sherlock et envoyées à l'IA pour analyse.</p>
                
                <div class="space-y-6">
                    @foreach($raw_data as $moduleName => $moduleData)
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                <h3 class="font-medium" id="{{ $moduleName }}">Module : {{ str_replace('_', ' ', ucfirst($moduleName)) }}</h3>
                            </div>
                            <div class="p-4">
                                @if(isset($moduleData['error']))
                                    <div class="bg-red-50 text-red-800 p-3 rounded-md">
                                        <p class="font-semibold">Erreur</p>
                                        <p>{{ $moduleData['error'] }}</p>
                                    </div>
                                @else
                                    <pre class="text-xs overflow-auto max-h-96 bg-gray-50 p-4 rounded">{{ json_encode($moduleData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Navigation rapide</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach(array_keys($raw_data) as $moduleName)
                        <a href="#{{ $moduleName }}" class="px-3 py-1 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">
                            {{ str_replace('_', ' ', ucfirst($moduleName)) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
