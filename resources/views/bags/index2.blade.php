@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Gestion des Sacs' : 'Bag Management' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Gérer votre inventaire de sacs' : 'Manage your bag inventory' }}
            </p>
        </div>
    </div>

    <!-- Mobile Action Buttons -->
    <div class="md:hidden px-4 py-4">
        <div class="grid grid-cols-1 gap-3">
            <!-- First row with 2 buttons -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('bags.create') }}" class="flex items-center justify-center bg-blue-600 text-white py-3 px-4 rounded-2xl shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ $isFrench ? 'Nouveau Sac' : 'New Bag' }}
                </a>
                <a href="{{ route('damaged-bags.index') }}" class="flex items-center justify-center bg-green-600 text-white py-3 px-4 rounded-2xl shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $isFrench ? 'Gérer Avaries' : 'Manage Damages' }}
                </a>
            </div>
            <!-- Second row with reception button -->
            <div class="grid grid-cols-1">
                <a href="{{ route('bag.receptions.create') }}" class="flex items-center justify-center bg-purple-600 text-white py-3 px-4 rounded-2xl shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m0 0l4-4m12 4l-4-4"/>
                    </svg>
                    {{ $isFrench ? 'Réception de Sacs' : 'Bag Reception' }}
                </a>
            </div>
        </div>
    </div>
    <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Mobile Bag Cards -->
                <div class="space-y-4">
                    @forelse($bags as $bag)
                        <div class="bg-white border rounded-2xl p-6 shadow-sm transform hover:scale-102 transition-all duration-300 animate-slide-in-right {{ $bag->stock_quantity <= $bag->alert_threshold ? 'border-red-200 bg-red-50' : 'border-gray-200' }}" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $bag->name }}</h3>
                                    <p class="text-blue-600 font-semibold text-lg">{{ number_format($bag->price, 0, ',', ' ') }} XAF</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($bag->stock_quantity <= $bag->alert_threshold)
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                            {{ $isFrench ? 'Stock faible' : 'Low Stock' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-gray-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-gray-600 mb-1">
                                        {{ $isFrench ? 'Stock' : 'Stock' }}
                                    </p>
                                    <p class="font-bold {{ $bag->stock_quantity <= $bag->alert_threshold ? 'text-red-700' : 'text-gray-900' }} text-lg">
                                        {{ number_format($bag->stock_quantity, 0, ',', ' ') }}
                                    </p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-blue-600 mb-1">
                                        {{ $isFrench ? 'Seuil d\'alerte' : 'Alert Threshold' }}
                                    </p>
                                    <p class="font-bold text-blue-700 text-lg">{{ number_format($bag->alert_threshold, 0, ',', ' ') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('bags.edit', $bag) }}" class="flex-1 bg-blue-100 text-blue-700 py-3 px-4 rounded-xl text-sm font-medium text-center transform hover:scale-105 active:scale-95 transition-all duration-200">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                </a>
                                <form action="{{ route('bags.destroy', $bag) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce sac ?' : 'Are you sure you want to delete this bag?' }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-100 text-red-700 py-3 px-4 rounded-xl text-sm font-medium transform hover:scale-105 active:scale-95 transition-all duration-200">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg p-8 text-center animate-fade-in">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucun sac trouvé' : 'No bags found' }}
                            </h3>
                            <p class="text-gray-500 mb-4">
                                {{ $isFrench ? 'Commencez par ajouter votre premier sac.' : 'Start by adding your first bag.' }}
            </p>
                            <a href="{{ route('bags.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl transform hover:scale-105 active:scale-95 transition-all duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $isFrench ? 'Ajouter un sac' : 'Add bag' }}
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

   
    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @include('buttons')

            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-700">{{ $isFrench ? 'Gestion des Sacs' : 'Bag Management' }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('bags.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> {{ $isFrench ? 'Nouveau Sac' : 'New Bag' }}
                    </a>
                    <a href="{{ route('damaged-bags.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> {{ $isFrench ? 'Gérer les avaries' : 'Manage damages' }}
                    </a>
                    <a href="{{ route('bags.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                        <i class="fas fa-box mr-2"></i> {{ $isFrench ? 'Gestion de Sacs' : 'Bag Management' }}
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-50 text-blue-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Nom' : 'Name' }}</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Stock' : 'Stock' }}</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Seuil d\'alerte' : 'Alert Threshold' }}</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bags as $bag)
                            <tr class="{{ $bag->stock_quantity <= $bag->alert_threshold ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $bag->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($bag->price, 0, ',', ' ') }} XAF</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="{{ $bag->stock_quantity <= $bag->alert_threshold ? 'text-red-600 font-semibold' : '' }}">
                                        {{ number_format($bag->stock_quantity, 0, ',', ' ') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($bag->alert_threshold, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bags.edit', $bag) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        <form action="{{ route('bags.destroy', $bag) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce sac ?' : 'Are you sure you want to delete this bag?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                                                <i class="fas fa-trash-alt"></i> {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $isFrench ? 'Aucun sac trouvé' : 'No bags found' }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection