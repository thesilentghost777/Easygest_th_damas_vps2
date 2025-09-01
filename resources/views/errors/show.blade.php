
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <svg class="inline w-8 h-8 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.664 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Détail de l'erreur #{{ $error->id }}
                    </h1>
                    <a href="{{ route('errors.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        ← Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informations générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Type d'erreur</h3>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            {{ $error->error_type ?? 'Unknown' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Date et heure</h3>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($error->error_time)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Fichier</h3>
                        <p class="text-gray-600 font-mono text-sm">
                            {{ $error->file_path ?? 'Non spécifié' }}
                            @if($error->line_number)
                                <span class="text-red-600">:{{ $error->line_number }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Code de statut HTTP</h3>
                        @if($error->http_status_code)
                            <span class="px-3 py-1 
                                {{ $error->http_status_code >= 500 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }} 
                                rounded-full text-sm font-medium">
                                {{ $error->http_status_code }}
                            </span>
                        @else
                            <p class="text-gray-500">-</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Message d'erreur -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Message d'erreur</h2>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-red-800 font-mono text-sm whitespace-pre-wrap">{{ $error->error_message }}</p>
                </div>
            </div>

            <!-- Stack trace -->
            @if($error->stack_trace)
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Stack Trace</h2>
                    <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                        <pre class="text-xs whitespace-pre-wrap">{{ $error->stack_trace }}</pre>
                    </div>
                </div>
            @endif

            <!-- Informations sur la requête -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informations sur la requête</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Méthode HTTP</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $error->request_method ?? 'Non spécifié' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">URL</h3>
                        <p class="text-gray-600 font-mono text-sm break-all">{{ $error->request_url ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Adresse IP</h3>
                        <p class="text-gray-600">{{ $error->ip_address ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Session ID</h3>
                        <p class="text-gray-600 font-mono text-xs">{{ $error->session_id ?? 'Non spécifié' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations utilisateur -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informations utilisateur</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Utilisateur</h3>
                        @if($error->user_name)
                            <p class="text-gray-600">
                                {{ $error->user_name }}
                                @if($error->user_email)
                                    <br><span class="text-sm text-gray-500">{{ $error->user_email }}</span>
                                @endif
                            </p>
                        @else
                            <p class="text-gray-500">Utilisateur invité</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">User Agent</h3>
                        <p class="text-gray-600 text-sm break-all">
                            {{ Str::limit($error->user_agent ?? 'Non spécifié', 100) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Données de la requête -->
            @if($error->request_data && is_array($error->request_data) && count($error->request_data) > 0)
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Données de la requête</h2>
                    <div class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                        <pre class="text-sm">{{ json_encode($error->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            <!-- Statut email -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Notifications</h2>
                <div class="flex items-center">
                    <span class="mr-3">Email envoyé :</span>
                    @if($error->email_sent)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Oui</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Non</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
