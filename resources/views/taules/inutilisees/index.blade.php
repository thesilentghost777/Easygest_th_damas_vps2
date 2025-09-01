@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <div class="animate-fade-in">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-blue-600 text-white p-4 lg:p-6">
                <h1 class="text-xl lg:text-2xl font-bold flex items-center">
                    <i class="mdi mdi-package-variant mr-3"></i>
                    {{ $isFrench ? 'Gestion des Tôles Inutilisées' : 'Unused sheet pans Management' }}
                </h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 lg:m-6 rounded-r-lg animate-slide-in">
                    <div class="flex items-center">
                        <i class="mdi mdi-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 lg:m-6 rounded-r-lg animate-slide-in">
                    <div class="flex items-center">
                        <i class="mdi mdi-alert-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="p-4 lg:p-6">
                <div class="mb-6">
                    <a href="{{ route('taules.inutilisees.create') }}" class="w-full lg:w-auto inline-flex justify-center items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 lg:py-2 px-4 rounded-xl lg:rounded transition-all duration-200 transform hover:scale-105 active:scale-95">
                        <i class="mdi mdi-plus mr-2"></i>
                        {{ $isFrench ? 'Déclarer des tôles inutilisées' : 'Declare unused sheet pans' }}
                    </a>
                </div>

                <!-- My Unused Taules -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b-2 border-blue-600 pb-2">
                        {{ $isFrench ? 'Mes tôles inutilisées' : 'My unused sheet pans' }}
                    </h3>

                    @if(count($taulesDuProducteur) > 0)
                        <!-- Mobile Cards -->
                        <div class="lg:hidden space-y-4">
                            @foreach($taulesDuProducteur as $taule)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mobile-card">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $taule->typeTaule->nom }}</h4>
                                            <p class="text-xs text-gray-500">{{ $taule->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xl font-bold text-blue-600">{{ $taule->nombre_taules }}</span>
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'tôles' : 'sheet pans' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Type de ôlee' : 'sheet pans type' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nombre' : 'Number' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date de déclaration' : 'Declaration date' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($taulesDuProducteur as $taule)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->typeTaule->nom }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->nombre_taules }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 rounded-xl p-6 text-center border border-blue-200">
                            <i class="mdi mdi-package-variant-closed text-4xl text-blue-400 mb-3"></i>
                            <p class="text-blue-700">
                                {{ $isFrench ? 'Vous n\'avez pas déclaré de tôles inutilisées.' : 'You haven\'t declared any unused sheet pans.' }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Available Taules -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b-2 border-green-600 pb-2">
                        {{ $isFrench ? 'Tôles disponibles à récupérer' : 'Available sheet pans to recover' }}
                    </h3>

                    @if(count($taulesDisponibles) > 0)
                        <!-- Mobile Cards -->
                        <div class="lg:hidden space-y-4">
                            @foreach($taulesDisponibles as $taule)
                                <div class="bg-green-50 rounded-xl p-4 border border-green-200 mobile-card">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $taule->typeTaule->nom }}</h4>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'Par' : 'By' }} {{ $taule->producteur->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $taule->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xl font-bold text-green-600">{{ $taule->nombre_taules }}</span>
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'tôles' : 'sheet pans' }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-green-200">
                                        <form action="{{ route('taules.inutilisees.recuperer', $taule) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 font-medium transition-colors" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir récupérer ces taules ?' : 'Are you sure you want to recover these taules?' }}')">
                                                {{ $isFrench ? 'Récupérer' : 'Recover' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Type de tôle' : 'sheet pans type' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nombre' : 'Number' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date de déclaration' : 'Declaration date' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($taulesDisponibles as $taule)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->producteur->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->typeTaule->nom }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->nombre_taules }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('taules.inutilisees.recuperer', $taule) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 transition-colors" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir récupérer ces taules ?' : 'Are you sure you want to recover these taules?' }}')">
                                                        {{ $isFrench ? 'Récupérer' : 'Recover' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 rounded-xl p-6 text-center border border-yellow-200">
                            <i class="mdi mdi-package-variant text-4xl text-yellow-400 mb-3"></i>
                            <p class="text-yellow-700">
                                {{ $isFrench ? 'Aucune tôle disponible à récupérer pour le moment.' : 'No sheet pans available to recover at the moment.' }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Recovered Taules -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b-2 border-purple-600 pb-2">
                        {{ $isFrench ? 'Tôles que j\'ai récupérées' : 'Sheet pans I recovered' }}
                    </h3>

                    @if(count($taulesRecuperees) > 0)
                        <!-- Mobile Cards -->
                        <div class="lg:hidden space-y-4">
                            @foreach($taulesRecuperees as $taule)
                                <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 mobile-card">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $taule->typeTaule->nom }}</h4>
                                            <p class="text-sm text-gray-600">{{ $isFrench ? 'De' : 'From' }} {{ $taule->producteur->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $taule->date_recuperation->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xl font-bold text-purple-600">{{ $taule->nombre_taules }}</span>
                                            <p class="text-xs text-gray-500">{{ $isFrench ? 'tôles' : 'sheet pans' }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-purple-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">{{ $isFrench ? 'Matière créée:' : 'Material created:' }}</span>
                                            <span class="text-sm font-medium text-purple-600">{{ $taule->matiereCreee->nom }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Producteur d\'origine' : 'Original producer' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Type de tôle' : 'Sheet pans type' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nombre' : 'Number' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Date de récupération' : 'Recovery date' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Matière créée' : 'Material created' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($taulesRecuperees as $taule)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->producteur->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->typeTaule->nom }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->nombre_taules }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->date_recuperation->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $taule->matiereCreee->nom }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200">
                            <i class="mdi mdi-package-variant-closed text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600">
                                {{ $isFrench ? 'Vous n\'avez pas encore récupéré de tôles.' : 'You haven\'t recovered any sheet pans yet.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, a, .mobile-card {
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
