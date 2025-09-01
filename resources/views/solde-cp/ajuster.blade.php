@extends('layouts.app')

@section('content')
<br><br>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
   

    <!-- Desktop Header -->
    <div class="hidden md:block py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('buttons')
            <div class="mb-6 bg-blue-600 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
                <div class="px-6 py-5">
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Ajustement du Solde' : 'Balance Adjustment' }}
                    </h2>
                    <p class="text-blue-100 mt-2">
                        {{ $isFrench ? 'Modification manuelle du solde du chef de production' : 'Manual modification of production manager balance' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Container -->
    <div class="block md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Current Balance -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg mb-6 transform hover:scale-105 transition-all duration-300 animate-fade-in">
                    <div class="text-center">
                        <h2 class="text-lg font-semibold">{{ $isFrench ? 'Solde actuel' : 'Current balance' }}</h2>
                        <p class="text-4xl font-bold mt-2">{{ number_format($solde->montant, 0, ',', ' ') }} XAF</p>
                    </div>
                </div>

                <!-- Mobile Form -->
                <form action="{{ route('solde-cp.store-ajustement') }}" method="POST" id="formAjustementMobile" class="space-y-6">
                    @csrf

                    <!-- Mobile Operation Type -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Type d\'opération' : 'Operation type' }}
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200">
                                <input type="radio" name="operation" value="ajouter" class="h-5 w-5 text-blue-600 mobile-operation" checked>
                                <div class="ml-3">
                                    <span class="text-gray-700 font-medium">{{ $isFrench ? 'Ajouter au solde' : 'Add to balance' }}</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200">
                                <input type="radio" name="operation" value="soustraire" class="h-5 w-5 text-blue-600 mobile-operation">
                                <div class="ml-3">
                                    <span class="text-gray-700 font-medium">{{ $isFrench ? 'Soustraire du solde' : 'Subtract from balance' }}</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200">
                                <input type="radio" name="operation" value="fixer" class="h-5 w-5 text-blue-600 mobile-operation">
                                <div class="ml-3">
                                    <span class="text-gray-700 font-medium">{{ $isFrench ? 'Fixer à un montant précis' : 'Set to specific amount' }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Mobile Amount -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="montantMobile" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Montant (XAF)' : 'Amount (XAF)' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" id="montantMobile" name="montant" required min="0"
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md font-medium">
                        </div>
                        <p class="text-sm text-gray-500 mt-2" id="operationDescriptionMobile">
                            {{ $isFrench ? 'Le montant sera ajouté au solde actuel.' : 'The amount will be added to current balance.' }}
                        </p>
                    </div>

                    <!-- Mobile Description -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="descriptionMobile" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Motif de l\'ajustement' : 'Adjustment reason' }}
                        </label>
                        <div class="relative">
                            <div class="absolute top-4 left-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <textarea id="descriptionMobile" name="description" rows="4" required
                                class="pl-12 w-full border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Mobile Warning -->
                    <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl p-4 animate-pulse">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                    <path d="M12 9v4"/>
                                    <path d="M12 17h.01"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700">
                                    {{ $isFrench ? 'Attention : Les ajustements de solde sont journalisés et ne peuvent pas être annulés.' : 'Warning: Balance adjustments are logged and cannot be undone.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Action Buttons -->
                    <div class="pt-6 space-y-4">
                        <button type="button" onclick="confirmAjustement('mobile')"
                            class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                           
                            {{ $isFrench ? 'Valider l\'ajustement' : 'Validate adjustment' }}
                        </button>
                        <a href="{{ route('solde-cp.index') }}" class="w-full h-14 bg-gray-100 text-gray-700 text-lg font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-200 transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Container -->
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <!-- Desktop Current Balance -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 transform hover:shadow-xl transition-all duration-300">
                <h2 class="text-lg font-semibold mb-2">{{ $isFrench ? 'Solde actuel' : 'Current balance' }}</h2>
                <p class="text-3xl font-bold text-green-600">{{ number_format($solde->montant, 0, ',', ' ') }} XAF</p>
            </div>

            <!-- Desktop Form -->
            <div class="bg-white rounded-lg shadow-md p-6 transform hover:shadow-xl transition-all duration-300">
                <form action="{{ route('solde-cp.store-ajustement') }}" method="POST" id="formAjustementDesktop">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Type d\'opération' : 'Operation type' }}</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="operation" value="ajouter" class="h-5 w-5 text-blue-600 desktop-operation" checked>
                                <span class="ml-2 text-gray-700">{{ $isFrench ? 'Ajouter au solde' : 'Add to balance' }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="operation" value="soustraire" class="h-5 w-5 text-blue-600 desktop-operation">
                                <span class="ml-2 text-gray-700">{{ $isFrench ? 'Soustraire du solde' : 'Subtract from balance' }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="operation" value="fixer" class="h-5 w-5 text-blue-600 desktop-operation">
                                <span class="ml-2 text-gray-700">{{ $isFrench ? 'Fixer à un montant précis' : 'Set to specific amount' }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="montantDesktop" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Montant (XAF)' : 'Amount (XAF)' }}</label>
                        <input type="number" id="montantDesktop" name="montant" required min="0"
                            class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 hover:shadow-md transition-all duration-300">
                        <p class="text-sm text-gray-500 mt-1" id="operationDescriptionDesktop">
                            {{ $isFrench ? 'Le montant sera ajouté au solde actuel.' : 'The amount will be added to current balance.' }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="descriptionDesktop" class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Motif de l\'ajustement' : 'Adjustment reason' }}</label>
                        <textarea id="descriptionDesktop" name="description" rows="3" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 hover:shadow-md transition-all duration-300"></textarea>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                    <path d="M12 9v4"/>
                                    <path d="M12 17h.01"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    {{ $isFrench ? 'Attention : Les ajustements de solde sont journalisés et ne peuvent pas être annulés. Assurez-vous de bien vérifier les informations avant de valider.' : 'Warning: Balance adjustments are logged and cannot be undone. Make sure to verify information before validating.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('solde-cp.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transform hover:scale-105 transition-all duration-200">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="button" onclick="confirmAjustement('desktop')"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transform hover:scale-105 transition-all duration-200">
                            {{ $isFrench ? 'Valider l\'ajustement' : 'Validate adjustment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const soldeActuel = {{ $solde->montant }};
        const isFrench = {{ $isFrench ? 'true' : 'false' }};

        // Fonction pour formater les nombres
        function formatNumber(number) {
            return new Intl.NumberFormat('fr-FR').format(number);
        }

        // Fonction pour mettre à jour la description (version générique)
        function updateDescription(context) {
            const operationRadio = document.querySelector(`.${context}-operation:checked`);
            const montantInput = document.getElementById(`montant${context.charAt(0).toUpperCase() + context.slice(1)}`);
            const operationDescription = document.getElementById(`operationDescription${context.charAt(0).toUpperCase() + context.slice(1)}`);
            
            if (!operationRadio || !montantInput || !operationDescription) return;
            
            const selectedOperation = operationRadio.value;
            const montant = montantInput.value || 0;

            switch (selectedOperation) {
                case 'ajouter':
                    operationDescription.textContent = isFrench 
                        ? `Le montant sera ajouté au solde actuel. Nouveau solde estimé: ${formatNumber(parseInt(soldeActuel) + parseInt(montant))} XAF`
                        : `Amount will be added to current balance. Estimated new balance: ${formatNumber(parseInt(soldeActuel) + parseInt(montant))} XAF`;
                    break;
                case 'soustraire':
                    operationDescription.textContent = isFrench
                        ? `Le montant sera soustrait du solde actuel. Nouveau solde estimé: ${formatNumber(parseInt(soldeActuel) - parseInt(montant))} XAF`
                        : `Amount will be subtracted from current balance. Estimated new balance: ${formatNumber(parseInt(soldeActuel) - parseInt(montant))} XAF`;
                    break;
                case 'fixer':
                    operationDescription.textContent = isFrench
                        ? `Le solde sera fixé à ce montant exactement.`
                        : `Balance will be set to this exact amount.`;
                    break;
            }
        }

        // Initialisation pour mobile
        const mobileOperationRadios = document.querySelectorAll('.mobile-operation');
        const montantMobile = document.getElementById('montantMobile');
        
        mobileOperationRadios.forEach(radio => {
            radio.addEventListener('change', () => updateDescription('mobile'));
        });
        
        if (montantMobile) {
            montantMobile.addEventListener('input', () => updateDescription('mobile'));
        }

        // Initialisation pour desktop
        const desktopOperationRadios = document.querySelectorAll('.desktop-operation');
        const montantDesktop = document.getElementById('montantDesktop');
        
        desktopOperationRadios.forEach(radio => {
            radio.addEventListener('change', () => updateDescription('desktop'));
        });
        
        if (montantDesktop) {
            montantDesktop.addEventListener('input', () => updateDescription('desktop'));
        }

        // Mise à jour initiale
        updateDescription('mobile');
        updateDescription('desktop');
    });

    function confirmAjustement(context) {
        const formId = context === 'mobile' ? 'formAjustementMobile' : 'formAjustementDesktop';
        const operationSelector = context === 'mobile' ? '.mobile-operation:checked' : '.desktop-operation:checked';
        const montantId = context === 'mobile' ? 'montantMobile' : 'montantDesktop';
        const descriptionId = context === 'mobile' ? 'descriptionMobile' : 'descriptionDesktop';
        
        const operationRadio = document.querySelector(operationSelector);
        const montantInput = document.getElementById(montantId);
        const descriptionInput = document.getElementById(descriptionId);
        
        if (!operationRadio || !montantInput || !descriptionInput) {
            console.error('Elements not found for context:', context);
            return;
        }
        
        const operation = operationRadio.value;
        const montant = montantInput.value;
        const description = descriptionInput.value;
        const soldeActuel = {{ $solde->montant }};
        const isFrench = {{ $isFrench ? 'true' : 'false' }};

        // Validation des champs
        if (!montant || !description.trim()) {
            Swal.fire({
                title: isFrench ? 'Erreur' : 'Error',
                text: isFrench ? 'Veuillez remplir tous les champs obligatoires' : 'Please fill all required fields',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validation du montant
        if (parseFloat(montant) <= 0) {
            Swal.fire({
                title: isFrench ? 'Erreur' : 'Error',
                text: isFrench ? 'Le montant doit être supérieur à zéro' : 'Amount must be greater than zero',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        let message = '';
        switch (operation) {
            case 'ajouter':
                message = isFrench 
                    ? `Vous êtes sur le point d'ajouter ${montant} XAF au solde actuel.`
                    : `You are about to add ${montant} XAF to current balance.`;
                break;
            case 'soustraire':
                if (parseInt(montant) > soldeActuel) {
                    Swal.fire({
                        title: isFrench ? 'Erreur' : 'Error',
                        text: isFrench ? 'Le montant à soustraire est supérieur au solde actuel.' : 'Amount to subtract is greater than current balance.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                message = isFrench
                    ? `Vous êtes sur le point de soustraire ${montant} XAF du solde actuel.`
                    : `You are about to subtract ${montant} XAF from current balance.`;
                break;
            case 'fixer':
                message = isFrench
                    ? `Vous êtes sur le point de fixer le solde à ${montant} XAF.`
                    : `You are about to set balance to ${montant} XAF.`;
                break;
        }

        Swal.fire({
            title: isFrench ? 'Confirmation' : 'Confirmation',
            text: message + (isFrench ? ' Cette action sera enregistrée dans l\'historique. Voulez-vous continuer?' : ' This action will be recorded in history. Do you want to continue?'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: isFrench ? 'Oui, valider' : 'Yes, validate',
            cancelButtonText: isFrench ? 'Annuler' : 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.5s ease-out;
}

.hover\:scale-102:hover {
    transform: scale(1.02);
}
</style>
@endsection