@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Centre de notifications
            </h2>
        </div>
        @if(count($unreadNotifications) > 0)
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Marquer tout comme lu
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Notifications non lues ({{ count($unreadNotifications) }})
            </h3>
            
            @if(count($unreadNotifications) > 0)
                <div class="space-y-4">
                    @foreach($unreadNotifications as $notification)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if(isset($notification->data['type']) && $notification->data['type'] === 'matiere_alert')
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        @if(isset($notification->data['type']) && $notification->data['type'] === 'matiere_alert')
                                            <p>{{ $notification->data['count'] }} matière(s) en dessous du seuil d'alerte.</p>
                                            @if(isset($notification->data['matieres']))
                                                <ul class="list-disc ml-5 mt-1">
                                                    @foreach($notification->data['matieres'] as $matiere)
                                                        <li>{{ $matiere['nom'] }}: {{ $matiere['quantite'] }} {{ $matiere['unite'] }} (Seuil: {{ $matiere['seuil'] }} {{ $matiere['unite'] }})</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <div class="mt-2">
                                                <a href="{{ $notification->data['url'] ?? '#' }}" class="text-blue-600 hover:underline">
                                                    Voir les détails →
                                                </a>
                                            </div>
                                        @else
                                            <p>{{ json_encode($notification->data) }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <form action="{{ route('notifications.mark-processed', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                Lu et traité
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="toggleRenewForm('{{ $notification->id }}')">
                                            Renotifier
                                        </button>
                                        
                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                        
                                        <form id="renew-form-{{ $notification->id }}" action="{{ route('notifications.renew', $notification->id) }}" method="POST" class="hidden mt-2 w-full">
                                            @csrf
                                            <div class="flex items-center">
                                                <label for="days-{{ $notification->id }}" class="mr-2 text-xs text-gray-700">Renotifier dans</label>
                                                <select id="days-{{ $notification->id }}" name="days" class="text-xs border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="1">1 jour</option>
                                                    <option value="2">2 jours</option>
                                                    <option value="3">3 jours</option>
                                                    <option value="7">1 semaine</option>
                                                    <option value="14">2 semaines</option>
                                                    <option value="30">1 mois</option>
                                                </select>
                                                <button type="submit" class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Confirmer
                                                </button>
                                                <button type="button" onclick="toggleRenewForm('{{ $notification->id }}')" class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    Aucune notification non lue
                </div>
            @endif
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Notifications lues (en attente de traitement)
            </h3>
            
            @if(count($readNotifications) > 0)
                <div class="space-y-4">
                    @foreach($readNotifications as $notification)
                        <div class="bg-gray-50 border-l-4 border-gray-300 p-4 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if(isset($notification->data['type']) && $notification->data['type'] === 'matiere_alert')
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-sm font-medium text-gray-800">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    <div class="mt-2 text-sm text-gray-700">
                                        @if(isset($notification->data['type']) && $notification->data['type'] === 'matiere_alert')
                                            <p>{{ $notification->data['count'] }} matière(s) en dessous du seuil d'alerte.</p>
                                            @if(isset($notification->data['matieres']))
                                                <ul class="list-disc ml-5 mt-1">
                                                    @foreach($notification->data['matieres'] as $matiere)
                                                        <li>{{ $matiere['nom'] }}: {{ $matiere['quantite'] }} {{ $matiere['unite'] }} (Seuil: {{ $matiere['seuil'] }} {{ $matiere['unite'] }})</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @else
                                            <p>{{ json_encode($notification->data) }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <form action="{{ route('notifications.mark-processed', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                Lu et traité
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="toggleRenewForm('read-{{ $notification->id }}')">
                                            Renotifier
                                        </button>
                                        
                                        <form id="renew-form-read-{{ $notification->id }}" action="{{ route('notifications.renew', $notification->id) }}" method="POST" class="hidden mt-2 w-full">
                                            @csrf
                                            <div class="flex items-center">
                                                <label for="days-read-{{ $notification->id }}" class="mr-2 text-xs text-gray-700">Renotifier dans</label>
                                                <select id="days-read-{{ $notification->id }}" name="days" class="text-xs border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="1">1 jour</option>
                                                    <option value="2">2 jours</option>
                                                    <option value="3">3 jours</option>
                                                    <option value="7">1 semaine</option>
                                                    <option value="14">2 semaines</option>
                                                    <option value="30">1 mois</option>
                                                </select>
                                                <button type="submit" class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Confirmer
                                                </button>
                                                <button type="button" onclick="toggleRenewForm('read-{{ $notification->id }}')" class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if($notification->read_at)
                                            • Lu {{ $notification->read_at->diffForHumans() }}
                                        @endif
                                        @if($notification->renew_at)
                                            • Renotification prévue {{ $notification->renew_at->diffForHumans() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    Aucune notification lue en attente de traitement
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRenewForm(id) {
        const form = document.getElementById('renew-form-' + id);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
