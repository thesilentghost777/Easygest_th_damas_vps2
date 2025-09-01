
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <svg class="inline w-8 h-8 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.884-.833-2.664 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Logs d'Erreurs
                    </h1>
                    <button onclick="clearOldLogs()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Nettoyer les anciens logs
                    </button>
                </div>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                        <h3 class="text-lg font-semibold text-red-800">Total</h3>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_errors']) }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                        <h3 class="text-lg font-semibold text-orange-800">Aujourd'hui</h3>
                        <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['today_errors']) }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
                        <h3 class="text-lg font-semibold text-yellow-800">Cette semaine</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['this_week_errors']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type d'erreur</label>
                        <input type="text" name="error_type" value="{{ request('error_type') }}" 
                               placeholder="Ex: Exception, Error..." 
                               class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des erreurs -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Erreurs récentes ({{ $errors->total() }} résultats)
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date/Heure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fichier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($errors as $error)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($error->error_time)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            {{ $error->error_type ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate text-sm text-gray-900">
                                        {{ Str::limit($error->error_message, 80) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($error->file_path)
                                            <span class="font-mono text-xs">
                                                {{ basename($error->file_path) }}:{{ $error->line_number }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($error->user_name)
                                            {{ $error->user_name }}
                                        @else
                                            Invité
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($error->http_status_code)
                                            <span class="px-2 py-1 text-xs font-medium 
                                                {{ $error->http_status_code >= 500 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }} 
                                                rounded-full">
                                                {{ $error->http_status_code }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('errors.show', $error->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Aucune erreur trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($errors->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $errors->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <!-- Statistiques détaillées -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Types d'erreurs les plus fréquents -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Types d'erreurs fréquents</h3>
                    <div class="space-y-3">
                        @foreach($stats['error_types'] as $type)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $type->error_type ?? 'Unknown' }}</span>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    {{ $type->count }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Codes de statut -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Codes de statut HTTP</h3>
                    <div class="space-y-3">
                        @foreach($stats['status_codes'] as $status)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $status->http_status_code }}</span>
                                <span class="px-2 py-1 text-xs font-medium 
                                    {{ $status->http_status_code >= 500 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }} 
                                    rounded-full">
                                    {{ $status->count }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearOldLogs() {
    if (confirm('Voulez-vous supprimer les logs de plus de 30 jours ?')) {
        fetch('{{ route("errors.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ days: 30 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection
