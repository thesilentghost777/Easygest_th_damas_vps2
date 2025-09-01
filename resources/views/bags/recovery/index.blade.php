@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isFrench ? 'Récupération des sacs invendus' : 'Recovery of Unsold Bags' }}
        </h2>
    </x-slot>

    @include('buttons')

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($salesByServer->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-lg text-gray-600">
                                {{ $isFrench ? 'Aucun sac invendu à récupérer pour le moment.' : 'No unsold bags to recover at the moment.' }}
                            </p>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach ($salesByServer as $serverName => $sales)
                                <div class="bg-blue-50 p-4 rounded-lg shadow-md animate-fade-in">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-3">
                                        {{ $isFrench ? 'Sacs invendus par' : 'Unsold Bags by' }} {{ $serverName }}
                                    </h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white text-sm md:text-base">
                                            <thead>
                                                <tr class="bg-blue-100 text-blue-800">
                                                    <th class="py-2 px-2 sm:px-4 text-left">
                                                        {{ $isFrench ? 'Sac' : 'Bag' }}
                                                    </th>
                                                    <th class="py-2 px-2 sm:px-4 text-left">
                                                        {{ $isFrench ? 'Date de vente' : 'Sale Date' }}
                                                    </th>
                                                    <th class="py-2 px-2 sm:px-4 text-right">
                                                        {{ $isFrench ? 'Quantité invendue' : 'Unsold Qty' }}
                                                    </th>
                                                    <th class="py-2 px-2 sm:px-4 text-center">
                                                        {{ $isFrench ? 'Action' : 'Action' }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($sales as $sale)
                                                    <tr class="hover:bg-blue-50 transition-all duration-200">
                                                        <td class="py-3 px-2 sm:px-4">{{ $sale->reception->assignment->bag->name }}</td>
                                                        <td class="py-3 px-2 sm:px-4">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                                        <td class="py-3 px-2 sm:px-4 text-right">{{ $sale->quantity_unsold }}</td>
                                                        <td class="py-3 px-2 sm:px-4 text-center">
                                                            <button
                                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm transition transform hover:scale-105"
                                                                onclick="openRecoveryModal({{ $sale->id }}, {{ $sale->quantity_unsold }}, '{{ $sale->reception->assignment->bag->name }}')"
                                                            >
                                                                {{ $isFrench ? 'Récupérer' : 'Recover' }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de récupération -->
    <div id="recoveryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-[90%] max-w-md animate-slide-in-up">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                {{ $isFrench ? 'Récupérer les sacs invendus' : 'Recover Unsold Bags' }}
            </h3>

            <form id="recoveryForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $isFrench ? 'Sac :' : 'Bag:' }} <span id="bagName" class="font-medium"></span>
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $isFrench ? 'Quantité invendue :' : 'Unsold Quantity:' }} <span id="unsoldQuantity" class="font-medium"></span>
                    </p>

                    <label for="quantity_to_recover" class="block text-sm font-medium text-gray-700">
                        {{ $isFrench ? 'Quantité à récupérer' : 'Quantity to Recover' }}
                    </label>
                    <input type="number" name="quantity_to_recover" id="quantity_to_recover" min="1"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <div id="quantityError" class="text-red-500 text-sm mt-1 hidden">
                        {{ $isFrench ? 'La quantité doit être comprise entre 1 et le nombre de sacs invendus.' : 'Quantity must be between 1 and the unsold quantity.' }}
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRecoveryModal()" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md text-sm">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        {{ $isFrench ? 'Confirmer' : 'Confirm' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRecoveryModal(saleId, unsoldQuantity, bagName) {
            document.getElementById('bagName').textContent = bagName;
            document.getElementById('unsoldQuantity').textContent = unsoldQuantity;
            document.getElementById('quantity_to_recover').max = unsoldQuantity;
            document.getElementById('quantity_to_recover').value = unsoldQuantity;
            document.getElementById('recoveryForm').action = `/bags/recovery/${saleId}`;
            document.getElementById('recoveryModal').classList.remove('hidden');

            document.getElementById('quantity_to_recover').addEventListener('input', function () {
                const value = parseInt(this.value);
                const max = parseInt(this.max);
                document.getElementById('quantityError').classList.toggle('hidden', !(isNaN(value) || value < 1 || value > max));
            });
        }

        function closeRecoveryModal() {
            document.getElementById('recoveryModal').classList.add('hidden');
        }
    </script>
@endsection
