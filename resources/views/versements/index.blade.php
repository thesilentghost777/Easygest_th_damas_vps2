@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Header responsive -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4 lg:mb-6 space-y-3 lg:space-y-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 animate-fade-in">
            {{ $isFrench ? 'Mes Versements' : 'My Payments' }}
        </h1>
        <a href="{{ route('versements.create') }}"
           class="w-full lg:w-auto bg-blue-500 text-white px-4 py-3 rounded-xl hover:bg-blue-600 transition-all duration-200 font-medium text-center active:scale-95">
            <i class="mdi mdi-plus mr-2"></i>{{ $isFrench ? 'Nouveau Versement' : 'New Payment' }}
        </a>
    </div>

    <!-- Statistics cards responsive -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 transition-all duration-300 hover:shadow-xl active:scale-95">
            <div class="flex items-center">
                <div class="rounded-full p-3 bg-orange-100 mr-4 flex-shrink-0">
                    <i class="mdi mdi-clock-time-eight text-orange-600 text-xl lg:text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base lg:text-lg font-medium text-gray-900 mb-2 truncate">
                        {{ $isFrench ? 'Versements en attente' : 'Pending Payments' }}
                    </h3>
                    <p class="text-xl lg:text-3xl font-bold text-orange-600">
                        {{ number_format($total_non_valide, 0, ',', ' ') }} <span class="text-base lg:text-lg">FCFA</span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 transition-all duration-300 hover:shadow-xl active:scale-95">
            <div class="flex items-center">
                <div class="rounded-full p-3 bg-green-100 mr-4 flex-shrink-0">
                    <i class="mdi mdi-check-circle text-green-600 text-xl lg:text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base lg:text-lg font-medium text-gray-900 mb-2 truncate">
                        {{ $isFrench ? 'Versements validés' : 'Validated Payments' }}
                    </h3>
                    <p class="text-xl lg:text-3xl font-bold text-green-600">
                        {{ number_format($total_valide, 0, ',', ' ') }} <span class="text-base lg:text-lg">FCFA</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments list responsive -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Liste des versements' : 'Payment List' }}
            </h2>

            @if(count($versements) > 0)
                <!-- Desktop table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Date' : 'Date' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Libellé' : 'Description' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Montant' : 'Amount' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($versements as $versement)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $versement->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $versement->libelle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ number_format($versement->montant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($versement->status == 1)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Validé' : 'Validated' }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($versement->status == 0)
                                        <a href="{{ route('versements.edit', $versement) }}"
                                           class="text-blue-600 hover:text-blue-900 mr-3">{{ $isFrench ? 'Modifier' : 'Edit' }}</a>
                                        <form action="{{ route('versements.destroy', $versement) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce versement ?' : 'Are you sure you want to delete this payment?' }}')">
                                                {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">{{ $isFrench ? 'Aucune action disponible' : 'No actions available' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile card view -->
                <div class="lg:hidden space-y-4">
                    @foreach($versements as $versement)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-200 hover:shadow-md active:scale-95">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-gray-800 truncate">{{ $versement->libelle }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $versement->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="text-right ml-4 flex-shrink-0">
                                    <div class="text-lg font-bold text-blue-600">{{ number_format($versement->montant, 0, ',', ' ') }} FCFA</div>
                                    @if($versement->status == 1)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Validé' : 'Validated' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($versement->status == 0)
                            <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
                                <a href="{{ route('versements.edit', $versement) }}"
                                   class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors active:scale-95">
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <form action="{{ route('versements.destroy', $versement) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors active:scale-95"
                                            onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce versement ?' : 'Are you sure you want to delete this payment?' }}')">
                                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                    <i class="mdi mdi-cash-multiple text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">{{ $isFrench ? 'Aucun versement enregistré' : 'No payments recorded' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
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
