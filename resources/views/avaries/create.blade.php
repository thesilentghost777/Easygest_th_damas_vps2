{{-- resources/views/avaries/create.blade.php --}}
@extends('layouts.app')

@php
    $isFrench = app()->getLocale() === 'fr';
@endphp

@section('title', $isFrench ? 'Déclarer une Avarie' : 'Declare Damage')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-background via-secondary/5 to-accent/5">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-2">
                {{ $isFrench ? 'Déclarer une Nouvelle Avarie' : 'Declare New Damage' }}
            </h1>
            <p class="text-muted-foreground text-lg">
                {{ $isFrench ? 'Renseignez les détails de l\'avarie constatée' : 'Provide details of the observed damage' }}
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-card rounded-xl shadow-xl border border-border/50 overflow-hidden animate-slideIn">
            <div class="bg-gradient-to-r from-red-500/10 to-orange-500/10 px-6 py-4 border-b border-border/50">
                <h3 class="text-lg font-semibold text-foreground flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-red-500"></i>
                    {{ $isFrench ? 'Informations sur l\'Avarie' : 'Damage Information' }}
                </h3>
            </div>
            
            <form action="{{ route('avaries.store') }}" method="POST" id="avarieForm" class="p-6 space-y-6">
                @csrf
                
                <!-- Product Selection -->
                <div class="space-y-2">
                    <label for="produit_id" class="flex items-center gap-2 text-sm font-semibold text-foreground">
                        <i class="fas fa-box text-primary"></i>
                        {{ $isFrench ? 'Produit' : 'Product' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="produit_id" id="produit_id" 
                            class="w-full px-4 py-3 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('produit_id') border-red-500 @enderror" 
                            required>
                        <option value="">-- {{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }} --</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->code_produit }}" 
                                    data-prix="{{ $produit->prix }}"
                                    {{ old('produit_id') == $produit->code_produit ? 'selected' : '' }}>
                                {{ $produit->nom }} - {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                            </option>
                        @endforeach
                    </select>
                    @error('produit_id')
                        <div class="text-red-600 text-sm flex items-center gap-1 mt-1">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Quantity -->
                    <div class="space-y-2">
                        <label for="quantite" class="flex items-center gap-2 text-sm font-semibold text-foreground">
                            <i class="fas fa-sort-numeric-up text-primary"></i>
                            {{ $isFrench ? 'Quantité' : 'Quantity' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantite" id="quantite" 
                               class="w-full px-4 py-3 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('quantite') border-red-500 @enderror"
                               value="{{ old('quantite') }}" min="1" required 
                               placeholder="{{ $isFrench ? 'Entrez la quantité' : 'Enter quantity' }}">
                        @error('quantite')
                            <div class="text-red-600 text-sm flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-triangle text-xs"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Estimated Amount -->
                    <div class="space-y-2">
                        <label for="montant_estime" class="flex items-center gap-2 text-sm font-semibold text-foreground">
                            <i class="fas fa-calculator text-primary"></i>
                            {{ $isFrench ? 'Montant Estimé' : 'Estimated Amount' }}
                        </label>
                        <div class="relative">
                            <input type="text" id="montant_estime" 
                                   class="w-full px-4 py-3 bg-secondary/10 border border-input rounded-lg text-lg font-semibold text-red-600" 
                                   readonly 
                                   placeholder="{{ $isFrench ? 'Sélectionnez un produit et une quantité' : 'Select product and quantity' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class="fas fa-coins text-yellow-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div class="space-y-2">
                    <label for="date_avarie" class="flex items-center gap-2 text-sm font-semibold text-foreground">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        {{ $isFrench ? 'Date de l\'Avarie' : 'Damage Date' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_avarie" id="date_avarie" 
                           class="w-full px-4 py-3 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('date_avarie') border-red-500 @enderror"
                           value="{{ old('date_avarie', date('Y-m-d')) }}" 
                           max="{{ date('Y-m-d') }}" required>
                    @error('date_avarie')
                        <div class="text-red-600 text-sm flex items-center gap-1 mt-1">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="flex items-center gap-2 text-sm font-semibold text-foreground">
                        <i class="fas fa-comment-alt text-primary"></i>
                        {{ $isFrench ? 'Description de l\'Avarie' : 'Damage Description' }}
                        <span class="text-muted-foreground text-xs">({{ $isFrench ? 'optionnel' : 'optional' }})</span>
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-4 py-3 bg-background border border-input rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none @error('description') border-red-500 @enderror"
                              placeholder="{{ $isFrench ? 'Décrivez les circonstances de l\'avarie, les causes possibles, etc.' : 'Describe the circumstances of the damage, possible causes, etc.' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-red-600 text-sm flex items-center gap-1 mt-1">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="text-xs text-muted-foreground flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        {{ $isFrench ? 'Une description détaillée peut aider à prévenir de futures avaries' : 'A detailed description can help prevent future damages' }}
                    </div>
                </div>

                <!-- Summary Card (Hidden initially) -->
                <div id="summaryCard" class="hidden bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 space-y-3">
                    <h4 class="font-semibold text-blue-800 flex items-center gap-2">
                        <i class="fas fa-clipboard-list"></i>
                        {{ $isFrench ? 'Récapitulatif' : 'Summary' }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600 font-medium">{{ $isFrench ? 'Produit:' : 'Product:' }}</span>
                            <div id="summaryProduit" class="font-semibold text-blue-800"></div>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">{{ $isFrench ? 'Quantité:' : 'Quantity:' }}</span>
                            <div id="summaryQuantite" class="font-semibold text-blue-800"></div>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">{{ $isFrench ? 'Montant:' : 'Amount:' }}</span>
                            <div id="summaryMontant" class="font-bold text-red-600 text-lg"></div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-border">
                    <a href="{{ route('avaries.index') }}" 
                       class="flex-1 bg-secondary hover:bg-secondary/80 text-secondary-foreground px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        {{ $isFrench ? 'Retour' : 'Back' }}
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $isFrench ? 'Déclarer l\'Avarie' : 'Declare Damage' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                <i class="fas fa-lightbulb"></i>
                {{ $isFrench ? 'Conseils pour la déclaration' : 'Declaration Tips' }}
            </h3>
            <ul class="space-y-2 text-blue-700 text-sm">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 text-xs"></i>
                    {{ $isFrench ? 'Déclarez l\'avarie dès que possible après sa découverte' : 'Report the damage as soon as possible after discovery' }}
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 text-xs"></i>
                    {{ $isFrench ? 'Soyez précis dans vos quantités et descriptions' : 'Be precise in your quantities and descriptions' }}
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-blue-500 mt-0.5 text-xs"></i>
                    {{ $isFrench ? 'Une description détaillée aide à identifier les causes' : 'A detailed description helps identify causes' }}
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.5s ease-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
    20%, 40%, 60%, 80% { transform: translateX(2px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.transform {
    transition: transform 0.2s ease-in-out;
}

input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const montantEstime = document.getElementById('montant_estime');
    const summaryCard = document.getElementById('summaryCard');
    const summaryProduit = document.getElementById('summaryProduit');
    const summaryQuantite = document.getElementById('summaryQuantite');
    const summaryMontant = document.getElementById('summaryMontant');
    const form = document.getElementById('avarieForm');
    
    const isFrench = {{ $isFrench ? 'true' : 'false' }};

    function calculerMontant() {
        const produitOption = produitSelect.options[produitSelect.selectedIndex];
        const prix = produitOption.dataset.prix || 0;
        const quantite = parseInt(quantiteInput.value) || 0;
        const montant = prix * quantite;
        
        if (montant > 0) {
            const formattedAmount = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
            montantEstime.value = formattedAmount;
            
            // Update summary
            summaryProduit.textContent = produitOption.text.split(' - ')[0];
            summaryQuantite.textContent = quantite;
            summaryMontant.textContent = formattedAmount;
            
            // Show summary card with animation
            summaryCard.classList.remove('hidden');
            summaryCard.style.animation = 'slideIn 0.3s ease-out';
        } else {
            montantEstime.value = '';
            summaryCard.classList.add('hidden');
        }
    }

    function validateForm() {
        const produit = produitSelect.value;
        const quantite = parseInt(quantiteInput.value) || 0;
        const date = document.getElementById('date_avarie').value;

        const errors = [];

        if (!produit) {
            errors.push(isFrench ? 'Veuillez sélectionner un produit' : 'Please select a product');
        }

        if (quantite <= 0) {
            errors.push(isFrench ? 'La quantité doit être supérieure à 0' : 'Quantity must be greater than 0');
        }

        if (!date) {
            errors.push(isFrench ? 'Veuillez indiquer la date de l\'avarie' : 'Please indicate the damage date');
        }

        if (errors.length > 0) {
            // Create toast notification for errors
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <div class="font-semibold">${isFrench ? 'Erreurs de validation' : 'Validation Errors'}</div>
                        <ul class="text-sm mt-1">
                            ${errors.map(error => `<li>• ${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate toast
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Remove toast after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);

            // Shake form
            form.classList.add('animate-shake');
            setTimeout(() => form.classList.remove('animate-shake'), 500);

            return false;
        }

        return true;
    }

    // Event listeners
    produitSelect.addEventListener('change', calculerMontant);
    quantiteInput.addEventListener('input', calculerMontant);

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalContent = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <i class="fas fa-spinner animate-spin"></i>
                ${isFrench ? 'Déclaration en cours...' : 'Declaring...'}
            </div>
        `;

        // Reset button after 10 seconds in case of error
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalContent;
        }, 10000);
    });

    // Auto-focus first input
    produitSelect.focus();

    // Add floating label effect
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
});
</script>
@endsection