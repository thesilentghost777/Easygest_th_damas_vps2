@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Mobile Header -->
    <div class="lg:hidden sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between p-4">
            @include('buttons')
            <h1 class="text-lg font-bold text-gray-800 truncate mx-4">
                {{ $isFrench ? 'Assigner des produits' : 'Assign Products' }}
            </h1>
            <div class="w-8"></div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                @include('buttons')
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $isFrench ? 'Assigner des produits à un vendeur' : 'Assign Products to Seller' }}
                </h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pointeur.assignation.liste') }}" 
                   class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    {{ $isFrench ? 'Liste des assignations' : 'Assignment List' }}
                </a>
            </div>
        </div>
    </div>

   

    <!-- Error Message -->
    @if (session('error'))
        <div class="mx-4 lg:mx-auto lg:max-w-6xl mb-6 animate-slideInDown">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="px-4 pb-24 lg:pb-8 lg:container lg:mx-auto lg:px-6">
        <div class="bg-white rounded-t-3xl lg:rounded-2xl shadow-xl lg:shadow-lg overflow-hidden animate-slideInUp">
            <!-- Mobile Card Header -->
            <div class="lg:hidden bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                <h2 class="text-xl font-bold mb-2">
                    {{ $isFrench ? 'Nouvelle assignation' : 'New Assignment' }}
                </h2>
                <p class="text-blue-100 text-sm">
                    {{ $isFrench ? 'Sélectionnez un vendeur et les produits' : 'Select a seller and products' }}
                </p>
            </div>

            <!-- Desktop Card Header -->
            <div class="hidden lg:block p-8 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                    {{ $isFrench ? 'Sélectionner un vendeur' : 'Select a Seller' }}
                </h2>
                <p class="text-gray-600">
                    {{ $isFrench ? 'Choisissez le vendeur et les produits à assigner' : 'Choose the seller and products to assign' }}
                </p>
            </div>

            <form id="assignationForm" action="{{ route('pointeur.assignation.store') }}" method="POST" class="p-6 lg:p-8">
                @csrf
                
                <!-- Seller Selection -->
                <div class="mb-8">
                    <label for="vendeur_id" class="block text-base lg:text-sm font-semibold text-gray-700 mb-3">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $isFrench ? 'Vendeur' : 'Seller' }}
                        </span>
                    </label>
                    <div class="relative">
                        <select name="vendeur_id" id="vendeur_id" 
                                class="w-full border-2 border-gray-300 rounded-xl lg:rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 p-4 lg:p-3 text-base lg:text-sm appearance-none bg-white" 
                                required>
                            <option value="">{{ $isFrench ? 'Sélectionner un vendeur' : 'Select a seller' }}</option>
                            @foreach($vendeurs as $vendeur)
                                <option value="{{ $vendeur->id }}">{{ $vendeur->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Products Section -->
                <div class="mb-8">
                    <label class="block text-base lg:text-sm font-semibold text-gray-700 mb-4">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $isFrench ? 'Produits à assigner' : 'Products to assign' }}
                        </span>
                    </label>
                    
                    <div class="space-y-4 max-h-96 lg:max-h-80 overflow-y-auto p-2 border-2 border-gray-200 rounded-xl lg:rounded-lg bg-gray-50">
                        @foreach($produits as $produitData)
                            <div class="bg-white border border-gray-200 rounded-xl lg:rounded-lg p-4 lg:p-6 shadow-sm hover:shadow-md transition-all duration-200 animate-fadeIn">
                                <!-- Product Header -->
                                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4">
                                    <div class="font-bold text-lg lg:text-base text-gray-800 mb-2 lg:mb-0">
                                        {{ $produitData['produit']->nom }}
                                    </div>
                                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium self-start lg:self-auto">
                                        {{ $isFrench ? 'Stock total' : 'Total Stock' }}: {{ $produitData['quantite_totale'] }}
                                    </div>
                                </div>
                                
                                <!-- Product Items -->
                                <div class="space-y-3">
                                    @foreach($produitData['items'] as $item)
                                        <div class="border-t border-gray-100 pt-3 first:border-t-0 first:pt-0">
                                            <div class="flex items-start space-x-3 lg:space-x-4">
                                                <!-- Checkbox -->
                                                <div class="flex-shrink-0 pt-1">
                                                    <input type="checkbox" 
                                                           name="produit_recu_ids[]" 
                                                           id="produit_{{ $item->id }}" 
                                                           value="{{ $item->id }}" 
                                                           class="produit-checkbox w-5 h-5 rounded-lg text-blue-600 focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                                </div>
                                                
                                                <!-- Item Info -->
                                                <div class="flex-1 min-w-0">
                                                    <label for="produit_{{ $item->id }}" class="block text-sm lg:text-xs font-medium text-gray-700 mb-1 cursor-pointer">
                                                        {{ $item->producteur->name }}
                                                    </label>
                                                    <p class="text-sm lg:text-xs text-gray-500">
                                                        {{ $item->quantite }} {{ $isFrench ? 'unités' : 'units' }} • {{ $item->date_reception }}
                                                    </p>
                                                </div>
                                                
                                                <!-- Quantity Input -->
                                                <div class="flex-shrink-0">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm lg:text-xs text-gray-600">{{ $isFrench ? 'Qté' : 'Qty' }}:</span>
                                                        <input type="number" 
                                                               name="quantites[]" 
                                                               min="1" 
                                                               max="{{ $item->quantite }}" 
                                                               value="{{ $item->quantite }}" 
                                                               class="quantite-input border border-gray-300 rounded-lg shadow-sm w-16 lg:w-20 text-sm lg:text-xs p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition-all duration-200" 
                                                               disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Remarks -->
                <div class="mb-8">
                    <label for="remarques" class="block text-base lg:text-sm font-semibold text-gray-700 mb-3">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ $isFrench ? 'Remarques' : 'Remarks' }}
                        </span>
                    </label>
                    <textarea name="remarques" 
                              id="remarques" 
                              rows="4" 
                              class="w-full border-2 border-gray-300 rounded-xl lg:rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 p-4 lg:p-3 text-base lg:text-sm resize-none"
                              placeholder="{{ $isFrench ? 'Ajouter des remarques optionnelles...' : 'Add optional remarks...' }}"></textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            id="submitButton" 
                            class="w-full lg:w-auto bg-blue-600 text-white py-4 lg:py-3 px-8 rounded-xl lg:rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed font-semibold text-base lg:text-sm transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl disabled:transform-none" 
                            disabled>
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ $isFrench ? 'Assigner les produits' : 'Assign Products' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-slideInUp {
    animation: slideInUp 0.5s ease-out;
}

.animate-slideInDown {
    animation: slideInDown 0.5s ease-out;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

/* Mobile scrollbar styling */
@media (max-width: 1024px) {
    ::-webkit-scrollbar {
        width: 4px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.produit-checkbox');
        const quantiteInputs = document.querySelectorAll('.quantite-input');
        const submitButton = document.getElementById('submitButton');
        
        // Messages multilingues
        const messages = {
            selectSeller: @json($isFrench ? 'Veuillez sélectionner un vendeur.' : 'Please select a seller.'),
            selectProduct: @json($isFrench ? 'Veuillez sélectionner au moins un produit à assigner.' : 'Please select at least one product to assign.'),
            checkQuantities: @json($isFrench ? 'Veuillez vérifier les quantités saisies.' : 'Please check the entered quantities.')
        };
        
        // Fonction pour vérifier si au moins une case est cochée
        function updateButtonState() {
            const checked = document.querySelectorAll('.produit-checkbox:checked');
            const hasSelection = checked.length > 0;
            
            submitButton.disabled = !hasSelection;
            
            // Animation du bouton
            if (hasSelection) {
                submitButton.classList.add('animate-pulse');
                setTimeout(() => {
                    submitButton.classList.remove('animate-pulse');
                }, 1000);
            }
        }
        
        // Activer/désactiver les champs de quantité avec animation
        checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', function() {
                const quantiteInput = quantiteInputs[index];
                const parentDiv = quantiteInput.closest('.bg-white');
                
                quantiteInput.disabled = !this.checked;
                
                // Animation visuelle
                if (this.checked) {
                    parentDiv.classList.add('ring-2', 'ring-blue-200', 'bg-blue-50');
                    quantiteInput.focus();
                } else {
                    parentDiv.classList.remove('ring-2', 'ring-blue-200', 'bg-blue-50');
                }
                
                updateButtonState();
            });
        });
        
        // Validation du formulaire avec feedback visuel
        document.getElementById('assignationForm').addEventListener('submit', function(e) {
            const vendeurId = document.getElementById('vendeur_id').value;
            const vendeurSelect = document.getElementById('vendeur_id');
            
            if (!vendeurId) {
                e.preventDefault();
                vendeurSelect.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                
                // Notification mobile-friendly
                if (window.innerWidth < 1024) {
                    showMobileAlert(messages.selectSeller);
                } else {
                    alert(messages.selectSeller);
                }
                
                vendeurSelect.focus();
                
                setTimeout(() => {
                    vendeurSelect.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                }, 3000);
                
                return false;
            }
            
            const checked = document.querySelectorAll('.produit-checkbox:checked');
            if (checked.length === 0) {
                e.preventDefault();
                
                if (window.innerWidth < 1024) {
                    showMobileAlert(messages.selectProduct);
                } else {
                    alert(messages.selectProduct);
                }
                
                return false;
            }
            
            // Vérifier que les quantités sont valides avec feedback visuel
            let valid = true;
            checked.forEach(checkbox => {
                const quantiteInput = checkbox.closest('.flex').querySelector('.quantite-input');
                const quantite = parseInt(quantiteInput.value);
                const max = parseInt(quantiteInput.getAttribute('max'));
                
                if (quantite < 1 || quantite > max) {
                    valid = false;
                    quantiteInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                    
                    setTimeout(() => {
                        quantiteInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                    }, 3000);
                } else {
                    quantiteInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                
                if (window.innerWidth < 1024) {
                    showMobileAlert(messages.checkQuantities);
                } else {
                    alert(messages.checkQuantities);
                }
                
                return false;
            }
            
            // Animation de soumission
            submitButton.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    ${ @json($isFrench ? 'En cours...' : 'Processing...') }
                </span>
            `;
            
            submitButton.disabled = true;
        });
        
        // Fonction pour afficher les alertes sur mobile
        function showMobileAlert(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 left-4 right-4 z-50 bg-red-500 text-white p-4 rounded-lg shadow-lg animate-slideInDown';
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                alertDiv.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    document.body.removeChild(alertDiv);
                }, 300);
            }, 3000);
        }
        
        // Animation d'entrée pour les éléments
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeIn');
                }
            });
        });
        
        document.querySelectorAll('.bg-white.border').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
@endsection