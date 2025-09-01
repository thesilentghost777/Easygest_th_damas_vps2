@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-teal-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-blue-800 mb-2">Choisissez votre mode</h1>
            <p class="text-blue-600">Sélectionnez une option pour continuer</p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('inventory.groups.index') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 flex items-center space-x-4 border border-blue-100">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-blue-800">Mode Production</h2>
                        <p class="text-blue-600 text-sm">Gérer les stocks et inventaires</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('dashboard') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 flex items-center space-x-4 border border-green-100">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-green-800">Mode Vente</h2>
                        <p class="text-green-600 text-sm">Accéder au tableau de bord des ventes</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('objectives.dashboard') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 flex items-center space-x-4 border border-purple-100">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-purple-800">Mode Objectifs</h2>
                        <p class="text-purple-600 text-sm">Définir et suivre les objectifs commerciaux</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-sm text-blue-500">
                Connecté en tant que <span class="font-medium">{{ Auth::user()->name }}</span>
            </p>
        </div>
    </div>
</div>
@endsection