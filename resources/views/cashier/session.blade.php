@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-6 min-h-screen bg-gray-50">
    <!-- Header responsive -->
    @include('buttons')

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <div class="animate-fade-in">
            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">
                {{ $isFrench ? 'Gestion de Session' : 'Session Management' }} #{{ $session->id }}
            </h1>
            <p class="text-gray-600 text-sm lg:text-base mt-1">
                {{ $isFrench ? 'Démarrée le' : 'Started on' }} {{ $session->start_time->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-lg animate-slide-in" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg animate-slide-in" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Session status card responsive -->
    <div class="bg-white shadow-lg rounded-xl lg:rounded-lg overflow-hidden mb-4 lg:mb-6 transition-all duration-300 hover:shadow-xl">
        <div class="p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-800">
                    {{ $isFrench ? 'Statut de la Session' : 'Session Status' }}
                </h2>
                <span class="px-3 py-2 {{ $session->isActive() ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} rounded-full text-sm font-medium shadow-sm">
                    {{ $session->isActive() ? ($isFrench ? 'Active' : 'Active') : ($isFrench ? 'Clôturée' : 'Closed') }}
                </span>
            </div>

            <!-- Mobile optimized grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4 mb-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 transition-all duration-200 hover:shadow-md active:scale-95">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Caisse initiale' : 'Initial Cash' }}</p>
                        <i class="mdi mdi-cash text-blue-600"></i>
                    </div>
                    <p class="text-lg font-bold text-blue-800">{{ number_format($session->initial_cash, 0, ',', ' ') }} FCFA</p>
                    @if($session->final_cash)
                    <div class="mt-2 pt-2 border-t border-blue-200">
                        <p class="text-xs text-blue-600">{{ $isFrench ? 'Final:' : 'Final:' }} {{ number_format($session->final_cash, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 transition-all duration-200 hover:shadow-md active:scale-95">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Monnaie initiale' : 'Initial Change' }}</p>
                        <i class="mdi mdi-coins text-blue-600"></i>
                    </div>
                    <p class="text-lg font-bold text-blue-800">{{ number_format($session->initial_change, 0, ',', ' ') }} FCFA</p>
                    @if($session->final_change)
                    <div class="mt-2 pt-2 border-t border-blue-200">
                        <p class="text-xs text-blue-600">{{ $isFrench ? 'Final:' : 'Final:' }} {{ number_format($session->final_change, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 sm:col-span-2 lg:col-span-1 transition-all duration-200 hover:shadow-md active:scale-95">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-blue-600 font-medium">{{ $isFrench ? 'Mobile initial' : 'Initial Mobile' }}</p>
                        <i class="mdi mdi-cellphone text-blue-600"></i>
                    </div>
                    <p class="text-lg font-bold text-blue-800">{{ number_format($session->initial_mobile_balance, 0, ',', ' ') }} FCFA</p>
                    @if($session->final_mobile_balance)
                    <div class="mt-2 pt-2 border-t border-blue-200">
                        <p class="text-xs text-blue-600">{{ $isFrench ? 'Final:' : 'Final:' }} {{ number_format($session->final_mobile_balance, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes sections responsive -->
            @if($session->notes)
            <div class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-600 mb-1 font-medium">{{ $isFrench ? 'Notes initiales:' : 'Initial notes:' }}</p>
                <p class="text-sm text-gray-800">{{ $session->notes }}</p>
            </div>
            @endif

            @if($session->end_notes)
            <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-600 mb-1 font-medium">{{ $isFrench ? 'Notes de clôture:' : 'Closing notes:' }}</p>
                <p class="text-sm text-gray-800">{{ $session->end_notes }}</p>
            </div>
            @endif

            <!-- Session summary for closed sessions -->
            @if(!$session->isActive())
            <div class="mt-4 border-t pt-4">
                <h3 class="text-md lg:text-lg font-semibold text-gray-700 mb-3">{{ $isFrench ? 'Résumé de la session' : 'Session Summary' }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 lg:gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 transition-all duration-200 hover:shadow-md">
                        <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Durée de la session' : 'Session Duration' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-blue-800">{{ $session->duration }}</p>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 transition-all duration-200 hover:shadow-md">
                        <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Total retraits' : 'Total Withdrawals' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-blue-800">{{ number_format($session->total_withdrawals, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 sm:col-span-2 sm:col-start-2 lg:col-span-1 lg:col-start-auto transition-all duration-200 hover:shadow-md">
                        <p class="text-sm text-blue-600 mb-1 font-medium">{{ $isFrench ? 'Montant versé' : 'Amount Remitted' }}</p>
                        <p class="text-lg lg:text-xl font-bold text-blue-800">{{ number_format($session->cash_remitted, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action buttons for active sessions - mobile optimized -->
            @if($session->isActive())
            <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <button onclick="document.getElementById('withdrawalModal').classList.remove('hidden')" 
                        class="w-full sm:w-auto px-4 py-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-all duration-200 font-medium active:scale-95">
                    <i class="mdi mdi-cash-remove mr-2"></i>{{ $isFrench ? 'Enregistrer un retrait' : 'Record Withdrawal' }}
                </button>
                <button onclick="document.getElementById('endSessionModal').classList.remove('hidden')" 
                        class="w-full sm:w-auto px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 font-medium active:scale-95">
                    <i class="mdi mdi-cash-check mr-2"></i>{{ $isFrench ? 'Clôturer la session' : 'Close Session' }}
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Cash withdrawals section responsive -->
    <div class="bg-white shadow-lg rounded-xl lg:rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Retraits de caisse' : 'Cash Withdrawals' }}
            </h2>

            @if(count($withdrawals) > 0)
                <!-- Desktop table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Responsable' : 'Responsible' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Motif' : 'Reason' }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($withdrawals as $withdrawal)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $withdrawal->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $withdrawal->withdrawn_by }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $withdrawal->reason }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-medium">{{ $isFrench ? 'Total:' : 'Total:' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                    {{ number_format($withdrawals->sum('amount'), 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Mobile card view -->
                <div class="lg:hidden space-y-4">
                    @foreach($withdrawals as $withdrawal)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-200 hover:shadow-md active:scale-95">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-sm text-blue-600 font-medium">{{ $withdrawal->withdrawn_by }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-800">{{ number_format($withdrawal->amount, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-lg border">
                                <p class="text-sm text-gray-700">{{ $withdrawal->reason }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Mobile total -->
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-blue-800">{{ $isFrench ? 'Total:' : 'Total:' }}</span>
                            <span class="text-xl font-bold text-blue-800">{{ number_format($withdrawals->sum('amount'), 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                    <i class="mdi mdi-cash-remove text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">{{ $isFrench ? 'Aucun retrait enregistré pour cette session.' : 'No withdrawals recorded for this session.' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour enregistrer un retrait - mobile optimized -->
<div id="withdrawalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
        <div class="p-4 lg:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg lg:text-xl font-semibold text-gray-900">{{ $isFrench ? 'Enregistrer un retrait' : 'Record Withdrawal' }}</h3>
                <button onclick="document.getElementById('withdrawalModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 p-2">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <form action="{{ route('cashier.withdraw', $session->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Montant du retrait' : 'Withdrawal Amount' }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="amount" id="amount" required
                                class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="withdrawn_by" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Prélevé par' : 'Withdrawn by' }}</label>
                        <select name="withdrawn_by" id="withdrawn_by" required
                            class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-base py-3">
                            <option value="">{{ $isFrench ? 'Sélectionner un responsable' : 'Select a responsible' }}</option>
                            @foreach($adminEmployees as $employee)
                                <option value="{{ $employee->name }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Motif du retrait' : 'Withdrawal Reason' }}</label>
                        <textarea id="reason" name="reason" rows="3" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base"
                            placeholder="{{ $isFrench ? 'Explication du retrait...' : 'Withdrawal explanation...' }}"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button"
                        onclick="document.getElementById('withdrawalModal').classList.add('hidden')"
                        class="w-full sm:w-auto px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour clôturer la session - mobile optimized -->
<div id="endSessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
        <div class="p-4 lg:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg lg:text-xl font-semibold text-gray-900">{{ $isFrench ? 'Clôturer la session' : 'Close Session' }}</h3>
                <button onclick="document.getElementById('endSessionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 p-2">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <form action="{{ route('cashier.end-session', $session->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="final_cash" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Montant final en caisse' : 'Final Cash Amount' }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="final_cash" id="final_cash" required
                                   class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                   placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="final_change" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Monnaie restante' : 'Remaining Change' }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="final_change" id="final_change" required
                                   class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                   placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- MOMO and OM fields mobile optimized -->
                    <div>
                        <label for="momo_amount" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <div class="w-6 h-4 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 60">
                                        <path d="M10,10 L90,10 L90,50 L10,50 Z" fill="#FFCC00" stroke="white" stroke-width="2"/>
                                        <text x="25" y="25" font-family="Arial" font-size="14" font-weight="bold" fill="black">MTN</text>
                                        <text x="15" y="40" font-family="Arial" font-size="10" font-weight="bold" fill="#FF0000">Mobile</text>
                                        <text x="15" y="48" font-family="Arial" font-size="10" font-weight="bold" fill="#FF0000">Money</text>
                                        <rect x="65" y="20" width="20" height="30" rx="2" fill="white" stroke="black" stroke-width="1"/>
                                        <rect x="68" y="23" width="14" height="15" fill="#004466"/>
                                        <rect x="69" y="40" width="4" height="3" fill="black"/>
                                        <rect x="75" y="40" width="4" height="3" fill="black"/>
                                        <rect x="69" y="45" width="4" height="3" fill="black"/>
                                        <rect x="75" y="45" width="4" height="3" fill="black"/>
                                        <path d="M58,15 L70,10 L75,25 L63,30 Z" fill="#004466" stroke="white"/>
                                        <text x="65" y="22" font-family="Arial" font-size="7" font-weight="bold" fill="white">CFA</text>
                                        <path d="M60,30 L70,30 L65,40 Z" fill="#FF0000"/>
                                    </svg>
                                </div>
                                {{ $isFrench ? 'Montant MOMO final (MTN Mobile Money)' : 'Final MOMO Amount (MTN Mobile Money)' }}
                            </div>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" id="momo_amount"
                                class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                placeholder="0" oninput="calculateTotal()">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="om_amount" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <div class="w-6 h-4 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 60">
                                        <rect x="10" y="10" width="80" height="40" fill="black"/>
                                        <path d="M25,20 L35,20 L35,25 L42,25 L42,30 L35,30 L35,35 L25,20" fill="white"/>
                                        <path d="M45,20 L55,35 L55,30 L62,30 L62,25 L55,25 L55,20 L45,20" fill="#FF6600"/>
                                        <text x="25" y="45" font-family="Arial" font-size="10" font-weight="bold" fill="#FF6600">Orange Money</text>
                                    </svg>
                                </div>
                                {{ $isFrench ? 'Montant OM final (Orange Money)' : 'Final OM Amount (Orange Money)' }}
                            </div>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" id="om_amount"
                                class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                placeholder="0" oninput="calculateTotal()">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="final_mobile_balance" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Solde compte mobile final' : 'Final Mobile Balance' }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="final_mobile_balance" id="final_mobile_balance" required
                                   class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                   placeholder="0" readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="cash_remitted" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Montant versé' : 'Amount Remitted' }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="cash_remitted" id="cash_remitted" required
                                   class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 text-base py-3"
                                   placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="end_notes" class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Notes de clôture (optionnel)' : 'Closing Notes (optional)' }}</label>
                        <textarea id="end_notes" name="end_notes" rows="3"
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base"
                                  placeholder="{{ $isFrench ? 'Notes supplémentaires...' : 'Additional notes...' }}"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button"
                            onclick="document.getElementById('endSessionModal').classList.add('hidden')"
                            class="w-full sm:w-auto px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ $isFrench ? 'Clôturer la session' : 'Close Session' }}
                    </button>
                </div>

                <script>
                    function calculateTotal() {
                        const momoAmount = parseFloat(document.getElementById('momo_amount').value) || 0;
                        const omAmount = parseFloat(document.getElementById('om_amount').value) || 0;
                        const total = momoAmount + omAmount;
                        document.getElementById('final_mobile_balance').value = total;
                        
                        const currentNotes = document.getElementById('end_notes').value;
                        if (!currentNotes.includes("MOMO:") && !currentNotes.includes("OM:")) {
                            document.getElementById('end_notes').value = `MOMO: ${momoAmount} FCFA | OM: ${omAmount} FCFA` +
                                (currentNotes ? "\n\n" + currentNotes : "");
                        } else {
                            const noteLines = currentNotes.split("\n");
                            noteLines[0] = `MOMO: ${momoAmount} FCFA | OM: ${omAmount} FCFA`;
                            document.getElementById('end_notes').value = noteLines.join("\n");
                        }
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        calculateTotal();
                    });
                </script>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        /* Touch targets */
        button, .btn, a {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
