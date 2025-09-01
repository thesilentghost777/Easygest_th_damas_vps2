@extends('pages.chef_production.chef_production_default')

@section('page-content')
<!-- Version Desktop (écrans larges >= 1024px) -->
<div class="desktop-container hidden lg:block container mx-auto px-4 py-8">
    @include('buttons')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">
            @if($isFrench)
                Gestion des Livraisons
            @else
                Delivery Management
            @endif
        </h1>
        <a href="{{ route('depenses.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            @if($isFrench)
                Nouvelle Depense ou Livraison
            @else
                New Expense or Delivery
            @endif
        </a>
    </div>

    <div class="bg-blue-100 text-blue-700 p-4 rounded-lg mb-6">
        <p>
            @if($isFrench)
                Cette section vous permet de gérer toutes les dépenses liées à la production. Vous pouvez ajouter une nouvelle dépense,
                visualiser les informations détaillées (comme le nom, le prix, et le statut), et effectuer des actions comme la validation ou la modification.
            @else
                This section allows you to manage all production-related expenses. You can add a new expense,
                view detailed information (such as name, price, and status), and perform actions like validation or modification.
            @endif
        </p>
        <p class="mt-2">
            @if($isFrench)
                Les messages de succès, comme la confirmation des actions, apparaissent sous forme de notifications grâce à la gestion des sessions.
            @else
                Success messages, like action confirmations, appear as notifications through session management.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: @if($isFrench) 'Succès!' @else 'Success!' @endif,
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">@if($isFrench) Date @else Date @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Nom @else Name @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Matière @else Material @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Prix @else Price @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Auteur @else Author @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Statut @else Status @endif</th>
                        <th class="px-6 py-3 text-left">@if($isFrench) Actions @else Actions @endif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($depenses as $depense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $depense->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $depense->nom }}</td>
                            <td class="px-6 py-4">
                                {{ $depense->matiere->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4">{{ $depense->user->name }}</td>
                            <td class="px-6 py-4">
                                @if($depense->valider)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                        @if($isFrench) Validé @else Validated @endif
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">
                                        @if($isFrench) En attente @else Pending @endif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('depenses.edit', $depense) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    @if($isFrench) Modifier @else Edit @endif
                                </a>
                                @if($depense->type === 'livraison_matiere' && !$depense->valider)
                                    <form action="{{ route('depenses.valider-livraison', $depense) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-900">
                                            @if($isFrench) Valider livraison @else Validate delivery @endif
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Version iPad/Tablet (écrans moyens 640px - 1023px) -->
<div class="tablet-container hidden sm:block lg:hidden px-4 py-6">
    @include('buttons')
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-blue-600">
            @if($isFrench)
                Gestion des Livraisons
            @else
                Delivery Management
            @endif
        </h1>
        <a href="{{ route('depenses.create') }}" 
           class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2 rounded-lg transition-colors duration-200">
            @if($isFrench)
                Nouvelle Dépense/Livraison
            @else
                New Expense/Delivery
            @endif
        </a>
    </div>

    <div class="bg-blue-100 text-blue-700 p-4 rounded-lg mb-6">
        <p class="text-sm sm:text-base">
            @if($isFrench)
                Gérez ici toutes les dépenses de production. Ajoutez, visualisez et validez les livraisons facilement.
            @else
                Manage all production expenses here. Add, view and validate deliveries easily.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: @if($isFrench) 'Succès!' @else 'Success!' @endif,
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    <!-- Vue en grille pour iPad -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($depenses as $depense)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
            <!-- En-tête de la carte -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-blue-600 text-lg truncate flex-1 mr-2">{{ $depense->nom }}</h3>
                    <span class="text-sm text-gray-500 whitespace-nowrap">{{ $depense->date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">
                        @if($isFrench) Matière: @else Material: @endif
                        <span class="font-medium">{{ $depense->matiere->nom ?? 'N/A' }}</span>
                    </span>
                    @if($depense->valider)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                            @if($isFrench) ✓ Validé @else ✓ Validated @endif
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                            @if($isFrench) ⏳ En attente @else ⏳ Pending @endif
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Corps de la carte -->
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <span class="text-sm text-gray-500">
                            @if($isFrench) Auteur: @else Author: @endif
                        </span>
                        <span class="font-medium text-gray-700">{{ $depense->user->name }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">
                            @if($isFrench) Prix: @else Price: @endif
                        </span>
                        <div class="text-lg font-bold text-blue-600">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('depenses.edit', $depense) }}"
                       class="flex-1 text-center bg-blue-100 text-blue-600 hover:bg-blue-200 py-2 px-3 rounded-lg text-sm font-medium transition-colors duration-200">
                        @if($isFrench) 📝 Modifier @else 📝 Edit @endif
                    </a>
                    
                    @if($depense->type === 'livraison_matiere' && !$depense->valider)
                    <form action="{{ route('depenses.valider-livraison', $depense) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full text-center bg-green-100 text-green-600 hover:bg-green-200 py-2 px-3 rounded-lg text-sm font-medium transition-colors duration-200">
                            @if($isFrench) ✓ Valider @else ✓ Validate @endif
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Version Mobile (écrans < 640px) -->
<div class="mobile-container sm:hidden px-4 py-6">
    @include('buttons')
    
    <div class="mb-6 animate-fadeIn">
        <h1 class="text-2xl font-bold text-blue-600 text-center transform transition-transform duration-300 hover:scale-105">
            @if($isFrench)
                Gestion des Livraisons
            @else
                Delivery Management
            @endif
        </h1>
        
        <a href="{{ route('depenses.create') }}" 
           class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-medium py-3 px-4 rounded-xl shadow-md mt-4 mb-6 transition-all duration-200 active:bg-green-700 transform active:scale-95">
            @if($isFrench)
                Nouvelle Dépense/Livraison
            @else
                New Expense/Delivery
            @endif
        </a>
    </div>

    <div class="bg-blue-100 text-blue-700 p-4 rounded-xl mb-6 animate-slideUp">
        <p class="text-sm">
            @if($isFrench)
                Gérez ici toutes les dépenses de production. Ajoutez, visualisez et validez les livraisons.
            @else
                Manage all production expenses here. Add, view and validate deliveries.
            @endif
        </p>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: @if($isFrench) 'Succès!' @else 'Success!' @endif,
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
                showClass: {
                    popup: 'animate-fadeIn'
                },
                hideClass: {
                    popup: 'animate-fadeOut'
                }
            });
        </script>
    @endif

    <div class="space-y-4">
        @foreach($depenses as $depense)
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate-slideUp" style="animation-delay: {{ $loop->index * 50 }}ms">
            <div class="p-4 border-b border-gray-200 bg-blue-50">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-blue-600">{{ $depense->nom }}</h3>
                    <span class="text-sm text-gray-500">{{ $depense->date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-sm">
                        @if($isFrench)
                            Matière:
                        @else
                            Material:
                        @endif
                        <strong>{{ $depense->matiere->nom ?? 'N/A' }}</strong>
                    </span>
                    <span class="text-sm font-bold">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            
            <div class="p-4">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm">
                        @if($isFrench)
                            Auteur:
                        @else
                            Author:
                        @endif
                        <strong>{{ $depense->user->name }}</strong>
                    </span>
                    @if($depense->valider)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                            @if($isFrench) Validé @else Validated @endif
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                            @if($isFrench) En attente @else Pending @endif
                        </span>
                    @endif
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('depenses.edit', $depense) }}"
                       class="flex-1 text-center bg-blue-100 text-blue-600 hover:bg-blue-200 py-2 px-3 rounded-lg text-sm transition-all duration-200 active:bg-blue-300 transform active:scale-95">
                        @if($isFrench)
                            Modifier
                        @else
                            Edit
                        @endif
                    </a>
                    
                    @if($depense->type === 'livraison_matiere' && !$depense->valider)
                    <form action="{{ route('depenses.valider-livraison', $depense) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full text-center bg-green-100 text-green-600 hover:bg-green-200 py-2 px-3 rounded-lg text-sm transition-all duration-200 active:bg-green-300 transform active:scale-95">
                            @if($isFrench)
                                Valider
                            @else
                                Validate
                            @endif
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    /* Animations pour mobile */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-fadeOut {
        animation: fadeOut 0.5s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.4s ease-out forwards;
    }
    
    /* Styles spécifiques mobile */
    @media (max-width: 640px) {
        .mobile-container {
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .transform.active\:scale-95:active {
            transform: scale(0.95);
        }
        
        button, a {
            touch-action: manipulation;
        }
    }
    
    /* Styles spécifiques tablet */
    @media (min-width: 640px) and (max-width: 1023px) {
        .tablet-container {
            max-width: 100%;
        }
        
        /* Amélioration des hover effects sur tablet */
        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    }
    
    /* Styles pour éviter le débordement horizontal */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Animation douce pour les transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endsection