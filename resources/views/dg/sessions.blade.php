@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Notice -->
                <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500 mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-amber-800 font-semibold">
                                {{ $isFrench ? 'Interface complexe' : 'Complex Interface' }}
                            </h3>
                            <p class="text-amber-700 text-sm mt-1">
                                {{ $isFrench ? 'Cette interface de gestion des sessions caissières est optimisée pour les écrans d\'ordinateur. Veuillez utiliser un PC pour accéder à toutes les fonctionnalités.' : 'This cashier session management interface is optimized for computer screens. Please use a PC to access all features.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Summary -->
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500">
                        <h3 class="text-blue-800 font-semibold mb-3">
                            {{ $isFrench ? 'Sessions actives' : 'Active sessions' }}
                        </h3>
                        <div class="flex items-center">
                            <div class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ \App\Models\CashierSession::whereNull('end_time')->count() }}
                                </p>
                                <p class="text-sm text-blue-700">
                                    {{ $isFrench ? 'session(s) en cours' : 'session(s) running' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-2xl p-4 border-l-4 border-green-500">
                        <h3 class="text-green-800 font-semibold mb-3">
                            {{ $isFrench ? 'Actions disponibles sur PC' : 'Actions available on PC' }}
                        </h3>
                        <div class="space-y-2 text-sm text-green-700">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $isFrench ? 'Consulter les détails des sessions' : 'View session details' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                {{ $isFrench ? 'Calculer les manquants' : 'Calculate missing amounts' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                {{ $isFrench ? 'Filtrer et analyser les données' : 'Filter and analyze data' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                                {{ $isFrench ? 'Valider les transactions' : 'Validate transactions' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @include('buttons')
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">
                                {{ $isFrench ? 'Gestion des Sessions des Caissières' : 'Cashier Session Management' }}
                            </h2>
                            <div class="flex items-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    {{ \App\Models\CashierSession::whereNull('end_time')->count() }} 
                                    {{ $isFrench ? 'session(s) active(s)' : 'active session(s)' }}
                                </span>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="bg-gray-100 p-4 rounded-lg mb-6">
                            <form action="{{ route('dg.sessions') }}" method="GET" class="flex flex-wrap gap-4">
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Date début' : 'Start date' }}
                                    </label>
                                    <input type="date" name="date_debut" value="{{ $dateDebut }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Date fin' : 'End date' }}
                                    </label>
                                    <input type="date" name="date_fin" value="{{ $dateFin }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Caissière' : 'Cashier' }}
                                    </label>
                                    <select name="caissiere" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">{{ $isFrench ? 'Toutes les caissières' : 'All cashiers' }}</option>
                                        @foreach ($caissieres as $c)
                                            <option value="{{ $c->id }}" {{ $caissiere == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="flex-grow md:flex-grow-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $isFrench ? 'Statut' : 'Status' }}
                                    </label>
                                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">{{ $isFrench ? 'Tous les statuts' : 'All statuses' }}</option>
                                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Sessions actives' : 'Active sessions' }}
                                        </option>
                                        <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>
                                            {{ $isFrench ? 'Sessions clôturées' : 'Closed sessions' }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="flex items-end w-full md:w-auto">
                                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Statistics -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <!-- Stats by cashier (number of sessions) -->
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-3">
                                    {{ $isFrench ? 'Sessions par caissière' : 'Sessions by cashier' }}
                                </h3>
                                <div class="space-y-3">
                                    @foreach ($sessionsByUser as $stat)
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium">{{ $stat->user->name ?? ($isFrench ? 'Inconnu' : 'Unknown') }}</span>
                                            <span class="font-bold">{{ $stat->total_sessions }} {{ $isFrench ? 'session(s)' : 'session(s)' }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            @php
                                                $maxSessions = $sessionsByUser->max('total_sessions');
                                                $percentage = $maxSessions > 0 ? ($stat->total_sessions / $maxSessions * 100) : 0;
                                            @endphp
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sessions list -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Début' : 'Start' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Fin' : 'End' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Caissière' : 'Cashier' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Montants initiaux' : 'Initial amounts' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Montants finaux' : 'Final amounts' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Retraits' : 'Withdrawals' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                        <th class="py-3 px-4 text-left">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sessions as $session)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $session->start_time->format('d/m/Y H:i') }}</td>
                                            <td class="py-3 px-4">
                                                {{ $session->end_time ? $session->end_time->format('d/m/Y H:i') : ($isFrench ? 'En cours' : 'Ongoing') }}
                                            </td>
                                            <td class="py-3 px-4">{{ $session->user->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">
                                                <div class="text-sm">
                                                    <div>Cash: {{ number_format($session->initial_cash, 0, ',', ' ') }} FCFA</div>
                                                    <div>{{ $isFrench ? 'Monnaie' : 'Change' }}: {{ number_format($session->initial_change, 0, ',', ' ') }} FCFA</div>
                                                    <div>Mobile: {{ number_format($session->initial_mobile_balance, 0, ',', ' ') }} FCFA</div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($session->end_time)
                                                    <div class="text-sm">
                                                        <div>Cash: {{ number_format($session->final_cash, 0, ',', ' ') }} FCFA</div>
                                                        <div>{{ $isFrench ? 'Monnaie' : 'Change' }}: {{ number_format($session->final_change, 0, ',', ' ') }} FCFA</div>
                                                        <div>Mobile: {{ number_format($session->final_mobile_balance, 0, ',', ' ') }} FCFA</div>
                                                    </div>
                                                @else
                                                    <span class="text-gray-500">{{ $isFrench ? 'En cours' : 'Ongoing' }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="font-semibold">
                                                    {{ number_format($session->getTotalWithdrawals(), 0, ',', ' ') }} FCFA
                                                </span>
                                                @if($session->cashWithdrawals->count() > 0)
                                                    <div class="text-xs text-gray-500">
                                                        ({{ $session->cashWithdrawals->count() }} {{ $isFrench ? 'retrait(s)' : 'withdrawal(s)' }})
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($session->end_time)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                                        {{ $isFrench ? 'Clôturée' : 'Closed' }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                        {{ $isFrench ? 'Active' : 'Active' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <button onclick="showSessionDetails({{ $session->id }})" class="text-blue-600 hover:text-blue-800 mr-2">
                                                    {{ $isFrench ? 'Détails' : 'Details' }}
                                                </button>
                                                <button onclick="showMissingCalculator({{ $session->id }})" class="text-green-600 hover:text-green-800">
                                                    {{ $isFrench ? 'Calculer Manquant' : 'Calculate Missing' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if ($sessions->isEmpty())
                                        <tr>
                                            <td colspan="8" class="py-4 px-4 text-center text-gray-500">
                                                {{ $isFrench ? 'Aucune session trouvée' : 'No sessions found' }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $sessions->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session details modal -->
        <div id="session-details-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl w-full mx-auto mt-10 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $isFrench ? 'Détails de la session' : 'Session details' }}
                    </h3>
                    <button onclick="closeSessionModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="session-details-content">
                    <!-- Content loaded dynamically -->
                </div>
            </div>
        </div>

        <!-- Missing calculator modal -->
        <div id="missing-calculator-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full mx-auto mt-20">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $isFrench ? 'Calculateur de Manquant' : 'Missing Calculator' }}
                    </h3>
                    <button onclick="closeMissingModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="missing-calculator-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isFrench ? 'Montant des ventes d\'alimentation (FCFA)' : 'Food sales amount (FCFA)' }}
                        </label>
                        <input type="number" id="vente-alimentation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                    <div class="flex space-x-4">
                        <button type="button" onclick="calculateMissing()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ $isFrench ? 'Calculer le manquant' : 'Calculate missing' }}
                        </button>
                        <button type="button" onclick="closeMissingModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </button>
                    </div>
                </form>
                <div id="missing-result" class="mt-6 hidden">
                    <!-- Result will be shown here -->
                </div>
            </div>
        </div>

        <script>
        let currentSessionId = null;
        const isFrench = {{ $isFrench ? 'true' : 'false' }};

        function showSessionDetails(sessionId) {
            currentSessionId = sessionId;
            document.getElementById('session-details-modal').classList.remove('hidden');
            document.getElementById('session-details-modal').classList.add('flex');
            
            // Load session details via AJAX
            fetch(`/dg/sessions/${sessionId}/details`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('session-details-content');
                    const session = data.session;
                    const withdrawals = data.withdrawals;
                    
                    content.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3">${isFrench ? 'Informations de la session' : 'Session information'}</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>${isFrench ? 'Caissière:' : 'Cashier:'}</strong> ${session.user.name}</div>
                                    <div><strong>${isFrench ? 'Début:' : 'Start:'}</strong> ${new Date(session.start_time).toLocaleString()}</div>
                                    <div><strong>${isFrench ? 'Fin:' : 'End:'}</strong> ${session.end_time ? new Date(session.end_time).toLocaleString() : (isFrench ? 'En cours' : 'Ongoing')}</div>
                                    <div><strong>${isFrench ? 'Notes:' : 'Notes:'}</strong> ${session.notes || (isFrench ? 'Aucune' : 'None')}</div>
                                    <div><strong>${isFrench ? 'Notes de fin:' : 'End notes:'}</strong> ${session.end_notes || (isFrench ? 'Aucune' : 'None')}</div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3">${isFrench ? 'Montants' : 'Amounts'}</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>${isFrench ? 'Cash initial:' : 'Initial cash:'}</strong> ${Number(session.initial_cash).toLocaleString()} FCFA</div>
                                    <div><strong>${isFrench ? 'Monnaie initiale:' : 'Initial change:'}</strong> ${Number(session.initial_change).toLocaleString()} FCFA</div>
                                    <div><strong>${isFrench ? 'Mobile initial:' : 'Initial mobile:'}</strong> ${Number(session.initial_mobile_balance).toLocaleString()} FCFA</div>
                                    ${session.end_time ? `
                                        <div><strong>${isFrench ? 'Cash final:' : 'Final cash:'}</strong> ${Number(session.final_cash).toLocaleString()} FCFA</div>
                                        <div><strong>${isFrench ? 'Monnaie finale:' : 'Final change:'}</strong> ${Number(session.final_change).toLocaleString()} FCFA</div>
                                        <div><strong>${isFrench ? 'Mobile final:' : 'Final mobile:'}</strong> ${Number(session.final_mobile_balance).toLocaleString()} FCFA</div>
                                        <div><strong>${isFrench ? 'Montant versé:' : 'Amount remitted:'}</strong> ${Number(session.cash_remitted).toLocaleString()} FCFA</div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h4 class="font-semibold mb-3">${isFrench ? 'Retraits d\'argent' : 'Cash withdrawals'} (${withdrawals.length})</h4>
                            ${withdrawals.length > 0 ? `
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="py-2 px-3 text-left">${isFrench ? 'Montant' : 'Amount'}</th>
                                                <th class="py-2 px-3 text-left">${isFrench ? 'Raison' : 'Reason'}</th>
                                                <th class="py-2 px-3 text-left">${isFrench ? 'Retiré par' : 'Withdrawn by'}</th>
                                                <th class="py-2 px-3 text-left">${isFrench ? 'Date' : 'Date'}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${withdrawals.map(w => `
                                                <tr class="border-b">
                                                    <td class="py-2 px-3">${Number(w.amount).toLocaleString()} FCFA</td>
                                                    <td class="py-2 px-3">${w.reason}</td>
                                                    <td class="py-2 px-3">${w.withdrawn_by}</td>
                                                    <td class="py-2 px-3">${new Date(w.created_at).toLocaleString()}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td class="py-2 px-3 font-bold">${Number(data.total_withdrawals).toLocaleString()} FCFA</td>
                                                <td colspan="3" class="py-2 px-3 font-bold">${isFrench ? 'Total des retraits' : 'Total withdrawals'}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            ` : `<p class="text-gray-500">${isFrench ? 'Aucun retrait enregistré pour cette session.' : 'No withdrawals recorded for this session.'}</p>`}
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading session details:', error);
                    document.getElementById('session-details-content').innerHTML = `<p class="text-red-500">${isFrench ? 'Erreur lors du chargement des détails.' : 'Error loading details.'}</p>`;
                });
        }

        function showMissingCalculator(sessionId) {
            currentSessionId = sessionId;
            document.getElementById('missing-calculator-modal').classList.remove('hidden');
            document.getElementById('missing-calculator-modal').classList.add('flex');
            document.getElementById('missing-result').classList.add('hidden');
            document.getElementById('vente-alimentation').value = '';
        }

        function calculateMissing() {
            const venteAlimentation = document.getElementById('vente-alimentation').value;
            
            if (!venteAlimentation) {
                alert(isFrench ? 'Veuillez entrer le montant des ventes d\'alimentation' : 'Please enter the food sales amount');
                return;
            }
            
            fetch(`/dg/sessions/${currentSessionId}/calculate-missing`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    vente_alimentation: parseFloat(venteAlimentation)
                })
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('missing-result');
                const details = data.details;
                
                resultDiv.innerHTML = `
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-3">${isFrench ? 'Résultat du calcul' : 'Calculation result'}</h4>
                        <div class="space-y-2 text-sm mb-4">
                            <div>${isFrench ? 'Mobile initial:' : 'Initial mobile:'} ${Number(details.initial_mobile).toLocaleString()} FCFA</div>
                            <div>${isFrench ? 'Cash initial:' : 'Initial cash:'} ${Number(details.initial_cash).toLocaleString()} FCFA</div>
                            <div>${isFrench ? 'Montant versé:' : 'Amount remitted:'} ${Number(details.cash_remitted).toLocaleString()} FCFA</div>
                            <div>${isFrench ? 'Retraits administration:' : 'Admin withdrawals:'} ${Number(details.total_withdrawals).toLocaleString()} FCFA</div>
                            <div>${isFrench ? 'Ventes alimentation:' : 'Food sales:'} ${Number(details.vente_alimentation).toLocaleString()} FCFA</div>
                        </div>
                        <div class="border-t pt-3">
                            <div class="text-lg font-bold text-${data.manquant > 0 ? 'red' : 'green'}-600">
                                ${isFrench ? 'Manquant:' : 'Missing:'} ${Number(data.manquant).toLocaleString()} FCFA
                            </div>
                        </div>
                        ${data.manquant !== 0 ? `
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">${isFrench ? 'Explication (optionnel)' : 'Explanation (optional)'}</label>
                                <textarea id="explication" class="w-full rounded-md border-gray-300 shadow-sm" rows="3"></textarea>
                                <button onclick="validateMissing(${data.manquant})" class="mt-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                    ${isFrench ? 'Valider comme manquant temporaire' : 'Validate as temporary missing'}
                                </button>
                            </div>
                        ` : ''}
                    </div>
                `;
                
                resultDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error calculating missing:', error);
                alert(isFrench ? 'Erreur lors du calcul du manquant' : 'Error calculating missing amount');
            });
        }

        function validateMissing(montant) {
            const explication = document.getElementById('explication').value;
            
            fetch(`/dg/sessions/${currentSessionId}/validate-missing`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    montant: montant,
                    explication: explication
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(isFrench ? 'Manquant temporaire créé avec succès' : 'Temporary missing amount created successfully');
                    closeMissingModal();
                } else {
                    alert(isFrench ? 'Erreur lors de la création du manquant temporaire' : 'Error creating temporary missing amount');
                }
            })
            .catch(error => {
                console.error('Error validating missing:', error);
                alert(isFrench ? 'Erreur lors de la validation du manquant' : 'Error validating missing amount');
            });
        }

        function closeSessionModal() {
            document.getElementById('session-details-modal').classList.add('hidden');
            document.getElementById('session-details-modal').classList.remove('flex');
        }

        function closeMissingModal() {
            document.getElementById('missing-calculator-modal').classList.add('hidden');
            document.getElementById('missing-calculator-modal').classList.remove('flex');
            currentSessionId = null;
        }
        </script>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
}
</style>
@endsection
