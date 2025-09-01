@extends('pages.chef_production.chef_production_default')

@section('page-content')
<!-- Version Desktop (lg et plus) -->
<div class="desktop-container hidden lg:block container mx-auto px-4 py-8">
    @include('buttons')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">
            @if($isFrench)
                Gestion des Dépenses
            @else
                Expenses Management
            @endif
        </h1>
        <a href="{{ route('depenses.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            @if($isFrench)
                Nouvelle Dépense
            @else
                New Expense
            @endif
        </a>
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

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: @if($isFrench) 'Erreur!' @else 'Error!' @endif,
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">@if($isFrench) Date @else Date @endif</th>
                    <th class="px-6 py-3 text-left">@if($isFrench) Nom @else Name @endif</th>
                    <th class="px-6 py-3 text-left">@if($isFrench) Type @else Type @endif</th>
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
                            @switch($depense->type)
                                @case('achat_matiere')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">@if($isFrench) Achat @else Purchase @endif</span>
                                    @break
                                @case('livraison_matiere')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">@if($isFrench) Livraison @else Delivery @endif</span>
                                    @break
                                @case('depense_fiscale')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">@if($isFrench) Dépenses Fiscales @else Tax Expenses @endif</span>
                                    @break
                                @case('reparation')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">@if($isFrench) Réparation @else Repair @endif</span>
                                    @break
                                @default
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">@if($isFrench) Autre @else Other @endif</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            {{ $depense->matiere->nom ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">{{ $depense->user->name }}</td>
                        <td class="px-6 py-4">
                            @if($depense->valider)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">@if($isFrench) Validé @else Validated @endif</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">@if($isFrench) En attente @else Pending @endif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('depenses.edit', $depense) }}"
                                   class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>@if($isFrench) Modifier @else Edit @endif
                                </a>
                                
                                @if($depense->type === 'livraison_matiere' && !$depense->valider)
                                    <form action="{{ route('depenses.valider-livraison', $depense) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:text-green-900 px-2 py-1 rounded hover:bg-green-50 transition-colors">
                                            <i class="fas fa-check mr-1"></i>@if($isFrench) Valider @else Validate @endif
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="confirmDelete({{ $depense->id }}, '{{ $depense->nom }}', {{ $depense->prix }})"
                                        class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>@if($isFrench) Supprimer @else Delete @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Version Tablette (md à lg) - Optimisée pour iPad -->
<div class="tablet-container hidden md:block lg:hidden px-4 py-6">
    @include('buttons')
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl lg:text-3xl font-bold text-blue-600 text-center sm:text-left">
            @if($isFrench)
                Gestion des Dépenses
            @else
                Expenses Management
            @endif
        </h1>
        <a href="{{ route('depenses.create') }}" 
           class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white text-center px-6 py-3 rounded-lg font-medium shadow-md transition-all duration-200">
            @if($isFrench)
                Nouvelle Dépense
            @else
                New Expense
            @endif
        </a>
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

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: @if($isFrench) 'Erreur!' @else 'Error!' @endif,
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <!-- Grille de cartes pour tablette -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($depenses as $depense)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <!-- En-tête de la carte -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-lg text-blue-700 truncate flex-1 mr-2">{{ $depense->nom }}</h3>
                    <span class="text-sm text-gray-500 whitespace-nowrap">{{ $depense->date->format('d/m/Y') }}</span>
                </div>
                
                <div class="flex flex-wrap items-center gap-2">
                    @switch($depense->type)
                        @case('achat_matiere')
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">@if($isFrench) Achat @else Purchase @endif</span>
                            @break
                        @case('livraison_matiere')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">@if($isFrench) Livraison @else Delivery @endif</span>
                            @break
                        @case('depense_fiscale')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">@if($isFrench) Fiscal @else Tax @endif</span>
                            @break
                        @case('reparation')
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-medium">@if($isFrench) Réparation @else Repair @endif</span>
                            @break
                        @default
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">@if($isFrench) Autre @else Other @endif</span>
                    @endswitch
                    
                    @if($depense->valider)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-check mr-1"></i>@if($isFrench) Validé @else Validated @endif
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-clock mr-1"></i>@if($isFrench) En attente @else Pending @endif
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Corps de la carte -->
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">@if($isFrench) Matière @else Material @endif</p>
                        <p class="font-semibold text-gray-800">{{ $depense->matiere->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">@if($isFrench) Prix @else Price @endif</p>
                        <p class="font-bold text-lg text-green-600">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">@if($isFrench) Auteur @else Author @endif</p>
                    <p class="font-medium text-gray-700">{{ $depense->user->name }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('depenses.edit', $depense) }}"
                       class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-edit mr-1"></i>@if($isFrench) Modifier @else Edit @endif
                    </a>
                    
                    @if($depense->type === 'livraison_matiere' && !$depense->valider)
                    <form action="{{ route('depenses.valider-livraison', $depense) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full text-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-check mr-1"></i>@if($isFrench) Valider @else Validate @endif
                        </button>
                    </form>
                    @endif
                    
                    <button onclick="confirmDelete({{ $depense->id }}, '{{ $depense->nom }}', {{ $depense->prix }})"
                            class="flex-1 text-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-trash mr-1"></i>@if($isFrench) Supprimer @else Delete @endif
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Version Mobile (sm et moins) -->
<div class="mobile-container md:hidden px-4 py-6">
    @include('buttons')
    
    <div class="mb-6 animate-fadeIn">
        <h1 class="text-2xl font-bold text-blue-600 text-center transform transition-transform duration-300 hover:scale-105">
            @if($isFrench)
                Gestion des Dépenses
            @else
                Expenses Management
            @endif
        </h1>
        
        <a href="{{ route('depenses.create') }}" 
           class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-medium py-3 px-4 rounded-xl shadow-md mt-4 mb-6 transition-all duration-200 active:bg-green-700 transform active:scale-95">
            @if($isFrench)
                Nouvelle Dépense
            @else
                New Expense
            @endif
        </a>
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

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: @if($isFrench) 'Erreur!' @else 'Error!' @endif,
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
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
                
                <div class="flex items-center space-x-2 mt-2">
                    @switch($depense->type)
                        @case('achat_matiere')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">@if($isFrench) Achat @else Purchase @endif</span>
                            @break
                        @case('livraison_matiere')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">@if($isFrench) Livraison @else Delivery @endif</span>
                            @break
                        @case('depense_fiscale')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">@if($isFrench) Fiscal @else Tax @endif</span>
                            @break
                        @case('reparation')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">@if($isFrench) Réparation @else Repair @endif</span>
                            @break
                        @default
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">@if($isFrench) Autre @else Other @endif</span>
                    @endswitch
                    
                    @if($depense->valider)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">@if($isFrench) Validé @else Validated @endif</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">@if($isFrench) En attente @else Pending @endif</span>
                    @endif
                </div>
            </div>
            
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <p class="text-sm text-gray-500">@if($isFrench) Matière @else Material @endif</p>
                        <p class="font-medium">{{ $depense->matiere->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">@if($isFrench) Prix @else Price @endif</p>
                        <p class="font-medium">{{ number_format($depense->prix, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">@if($isFrench) Auteur @else Author @endif</p>
                        <p class="font-medium">{{ $depense->user->name }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('depenses.edit', $depense) }}"
                       class="text-center bg-blue-100 text-blue-600 hover:bg-blue-200 py-2 px-2 rounded-lg text-sm transition-all duration-200 active:bg-blue-300 transform active:scale-95">
                        <i class="fas fa-edit block mb-1"></i>@if($isFrench) Modifier @else Edit @endif
                    </a>
                    
                    @if($depense->type === 'livraison_matiere' && !$depense->valider)
                    <form action="{{ route('depenses.valider-livraison', $depense) }}" method="POST" class="">
                        @csrf
                        <button type="submit"
                                class="w-full text-center bg-green-100 text-green-600 hover:bg-green-200 py-2 px-2 rounded-lg text-sm transition-all duration-200 active:bg-green-300 transform active:scale-95">
                            <i class="fas fa-check block mb-1"></i>@if($isFrench) Valider @else Validate @endif
                        </button>
                    </form>
                    @else
                    <div></div>
                    @endif
                    
                    <button onclick="confirmDelete({{ $depense->id }}, '{{ $depense->nom }}', {{ $depense->prix }})"
                            class="text-center bg-red-100 text-red-600 hover:bg-red-200 py-2 px-2 rounded-lg text-sm transition-all duration-200 active:bg-red-300 transform active:scale-95">
                        <i class="fas fa-trash block mb-1"></i>@if($isFrench) Supprimer @else Delete @endif
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
    
    /* Styles pour tablette */
    @media (min-width: 768px) and (max-width: 1023px) {
        .tablet-container {
            max-width: 100%;
        }
    }
    
    /* Styles spécifiques mobile */
    @media (max-width: 767px) {
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
    
    /* Optimisations pour iPad en orientation paysage */
    @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
        .tablet-container .grid-cols-2 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
</style>

<script>
function confirmDelete(depenseId, depenseName, depensePrice) {
    const isFrench = @if($isFrench) true @else false @endif;
    
    const title = isFrench ? 'Confirmer la suppression' : 'Confirm deletion';
    const text = isFrench 
        ? `Êtes-vous sûr de vouloir supprimer la dépense "${depenseName}" d'un montant de ${new Intl.NumberFormat('fr-FR').format(depensePrice)} FCFA ?` 
        : `Are you sure you want to delete the expense "${depenseName}" amounting to ${new Intl.NumberFormat('fr-FR').format(depensePrice)} FCFA?`;
    const confirmButtonText = isFrench ? 'Oui, supprimer' : 'Yes, delete';
    const cancelButtonText = isFrench ? 'Annuler' : 'Cancel';
    const warningText = isFrench ? 'Cette action ne peut pas être annulée!' : 'This action cannot be undone!';
    const deletedTitle = isFrench ? 'Supprimé!' : 'Deleted!';
    const deletedText = isFrench ? 'La dépense a été supprimée avec succès.' : 'The expense has been successfully deleted.';
    const errorTitle = isFrench ? 'Erreur!' : 'Error!';
    const errorText = isFrench ? 'Une erreur est survenue lors de la suppression.' : 'An error occurred during deletion.';
    
    Swal.fire({
        title: title,
        html: `
            <div class="text-left">
                <p class="mb-2">${text}</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                    <p class="text-red-700 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        ${warningText}
                    </p>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Créer et soumettre le formulaire de suppression
            const form = document.getElementById('delete-form');
            form.action = `/depenses/${depenseId}`;
            form.submit();
            
            // Afficher un message de chargement
            Swal.fire({
                title: isFrench ? 'Suppression en cours...' : 'Deleting...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
}
</script>

<style>
/* Styles personnalisés pour SweetAlert */
.swal2-popup-custom {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.swal2-confirm-custom {
    font-weight: 600 !important;
    padding: 10px 24px !important;
    border-radius: 8px !important;
}

.swal2-cancel-custom {
    font-weight: 600 !important;
    padding: 10px 24px !important;
    border-radius: 8px !important;
}

/* Styles pour les icônes FontAwesome dans les boutons */
.fas {
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900;
}

/* Amélioration des boutons d'action */
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
}

/* Responsive pour les actions en mobile */
@media (max-width: 767px) {
    .grid-cols-3 > * {
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
}
</style>
@endsection
