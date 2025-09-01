@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
   <!-- Mobile Header -->
<div class="lg:hidden bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex-1 text-center pr-4">
            <h1 class="text-lg font-bold">
                {{ $isFrench ? 'Mes Retours' : 'My Returns' }}
            </h1>
            <p class="text-blue-100 text-sm">
                {{ $isFrench ? 'Historique des retours' : 'Return history' }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('matieres.retours.create') }}" 
               class="inline-flex items-center justify-center w-10 h-10 bg-white bg-opacity-20 rounded-full hover:bg-opacity-30 transition-all duration-200 shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

    <!-- Desktop Header -->
    <div class="hidden lg:block container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $isFrench ? 'Mes Retours de Matières' : 'My Material Returns' }}
                </h1>
                <p class="text-gray-600">
                    {{ $isFrench ? 'Historique de vos demandes de retour' : 'History of your return requests' }}
                </p>
            </div>
            <a href="{{ route('matieres.retours.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                {{ $isFrench ? 'Nouveau Retour' : 'New Return' }}
            </a>
        </div>
    </div>

    <div class="mx-4 lg:mx-auto lg:max-w-6xl pb-6">
        <br>
        @if($mesRetours->isEmpty())
            <!-- No Returns -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        {{ $isFrench ? 'Aucun retour enregistré' : 'No returns recorded' }}
                    </h3>
                    <p class="text-gray-500 mb-4">
                        {{ $isFrench ? 'Vous n\'avez pas encore effectué de demande de retour.' : 'You have not made any return requests yet.' }}
                    </p>
                    <a href="{{ route('matieres.retours.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $isFrench ? 'Faire un retour' : 'Make a return' }}
                    </a>
                </div>
            </div>
        @else
            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Matière' : 'Material' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Quantité' : 'Quantity' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Date' : 'Date' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Statut' : 'Status' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $isFrench ? 'Validé par' : 'Validated by' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($mesRetours as $retour)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $retour->assignation->matiere->nom }}</div>
                                    @if($retour->motif_retour)
                                    <div class="text-sm text-gray-500">{{ $retour->motif_retour }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $retour->quantite_retournee }} {{ $retour->unite_retour }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $retour->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($retour->statut === 'en_attente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @elseif($retour->statut === 'validee')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Validée' : 'Validated' }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $isFrench ? 'Refusée' : 'Rejected' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    @if($retour->validateur)
                                        <div>{{ $retour->validateur->name }}</div>
                                        @if($retour->date_validation)
                                        <div class="text-xs text-gray-500">{{ $retour->date_validation->format('d/m/Y H:i') }}</div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @if($retour->commentaire_validation)
                            <tr class="bg-gray-50">
                                <td colspan="5" class="px-6 py-2">
                                    <div class="text-sm text-gray-600">
                                        <strong>{{ $isFrench ? 'Commentaire:' : 'Comment:' }}</strong> {{ $retour->commentaire_validation }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-4">
                @foreach($mesRetours as $retour)
                <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200 transform transition-all duration-200 hover:scale-105">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $retour->assignation->matiere->nom }}</h3>
                            <div class="text-sm text-gray-500">{{ $retour->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="text-right">
                            @if($retour->statut === 'en_attente')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $isFrench ? 'En attente' : 'Pending' }}
                                </span>
                            @elseif($retour->statut === 'validee')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $isFrench ? 'Validée' : 'Validated' }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $isFrench ? 'Refusée' : 'Rejected' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <span class="text-lg font-bold text-blue-600">{{ $retour->quantite_retournee }} {{ $retour->unite_retour }}</span>
                        </div>
                        
                        @if($retour->motif_retour)
                        <div class="pt-2 border-t border-gray-100">
                            <div class="text-sm text-gray-600">
                                <strong>{{ $isFrench ? 'Motif:' : 'Reason:' }}</strong>
                            </div>
                            <div class="text-sm text-gray-800 mt-1">{{ $retour->motif_retour }}</div>
                        </div>
                        @endif

                        @if($retour->validateur)
                        <div class="pt-2 border-t border-gray-100">
                            <div class="text-sm text-gray-600">
                                <strong>{{ $isFrench ? 'Validé par:' : 'Validated by:' }}</strong> {{ $retour->validateur->name }}
                            </div>
                            @if($retour->date_validation)
                            <div class="text-xs text-gray-500">{{ $retour->date_validation->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                        @endif

                        @if($retour->commentaire_validation)
                        <div class="pt-2 border-t border-gray-100">
                            <div class="text-sm text-gray-600">
                                <strong>{{ $isFrench ? 'Commentaire:' : 'Comment:' }}</strong>
                            </div>
                            <div class="text-sm text-gray-800 mt-1 bg-gray-50 p-2 rounded">{{ $retour->commentaire_validation }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
