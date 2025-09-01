@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        
        <!-- Mobile Header -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Explorateur de Tables' : 'Table Explorer' }}
                    </h1>
                    <p class="text-gray-600 text-sm md:text-base">
                        {{ $isFrench ? 'Explorez et analysez vos données de boulangerie' : 'Explore and analyze your bakery data' }}
                    </p>
                </div>
                
                <div class="animate-fade-in delay-100">
                    @include('buttons')
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-scale-in">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ $isFrench ? 'Sélection de table' : 'Table selection' }}
                </h2>
            </div>
            
            <form action="{{ route('query.analyze') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Table Selection -->
                <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                    <label for="table" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Sélectionnez une table' : 'Select a table' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="table" 
                                id="table" 
                                class="w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg transition-all duration-200 hover:border-blue-300 appearance-none">
                            @foreach($tables as $table)
                                <option value="{{ $table }}">
                                    @switch($table)
                                        @case('Acouper')
                                            {{ $isFrench ? 'Manquants et frais à déduire' : 'Missing items and fees to deduct' }}
                                            @break
                                        @case('Commande')
                                            {{ $isFrench ? 'Commandes' : 'Orders' }}
                                            @break
                                        @case('Complexe')
                                            {{ $isFrench ? 'Complexes' : 'Complexes' }}
                                            @break
                                        @case('Daily_assignments')
                                            {{ $isFrench ? 'Assignations quotidiennes' : 'Daily assignments' }}
                                            @break
                                        @case('Evenement')
                                            {{ $isFrench ? 'Événements' : 'Events' }}
                                            @break
                                        @case('Extra')
                                            {{ $isFrench ? 'Règles' : 'Rules' }}
                                            @break
                                        @case('Facture')
                                            {{ $isFrench ? 'Factures' : 'Invoices' }}
                                            @break
                                        @case('Horaire')
                                            {{ $isFrench ? 'Horaires' : 'Schedules' }}
                                            @break
                                        @case('Matiere')
                                            {{ $isFrench ? 'Matières' : 'Materials' }}
                                            @break
                                        @case('Matiere_recommander')
                                            {{ $isFrench ? 'Matières recommandées' : 'Recommended materials' }}
                                            @break
                                        @case('Message')
                                            {{ $isFrench ? 'Messages' : 'Messages' }}
                                            @break
                                        @case('Porter')
                                            {{ $isFrench ? 'Porteurs' : 'Carriers' }}
                                            @break
                                        @case('Prime')
                                            {{ $isFrench ? 'Primes' : 'Bonuses' }}
                                            @break
                                        @case('Production_suggerer_par_jour')
                                            {{ $isFrench ? 'Productions suggérées par jour' : 'Daily suggested productions' }}
                                            @break
                                        @case('Produit_fixes')
                                            {{ $isFrench ? 'Produits fixes' : 'Fixed products' }}
                                            @break
                                        @case('Produit_recu')
                                            {{ $isFrench ? 'Produits reçus' : 'Received products' }}
                                            @break
                                        @case('Reservations_mp')
                                            {{ $isFrench ? 'Réservations de matières premières' : 'Raw material reservations' }}
                                            @break
                                        @case('Utilisation')
                                            {{ $isFrench ? 'Utilisations' : 'Usage' }}
                                            @break
                                        @case('Versement_chef')
                                            {{ $isFrench ? 'Versements aux chefs' : 'Payments to chiefs' }}
                                            @break
                                        @case('Versement_csg')
                                            {{ $isFrench ? 'Versements CSG' : 'CSG payments' }}
                                            @break
                                        @case('announcements')
                                            {{ $isFrench ? 'Annonces' : 'Announcements' }}
                                            @break
                                        @case('assignations_matiere')
                                            {{ $isFrench ? 'Assignations de matières' : 'Material assignments' }}
                                            @break
                                        @case('avance_salaires')
                                            {{ $isFrench ? 'Avances sur salaires' : 'Salary advances' }}
                                            @break
                                        @case('bag_transactions')
                                            {{ $isFrench ? 'Transactions de sacs' : 'Bag transactions' }}
                                            @break
                                        @case('bags')
                                            {{ $isFrench ? 'Sacs' : 'Bags' }}
                                            @break
                                        @case('categories')
                                            {{ $isFrench ? 'Catégories' : 'Categories' }}
                                            @break
                                        @case('deli_user')
                                            {{ $isFrench ? 'Utilisateurs Deli' : 'Deli users' }}
                                            @break
                                        @case('delis')
                                            {{ $isFrench ? 'Delis' : 'Delis' }}
                                            @break
                                        @case('depenses')
                                            {{ $isFrench ? 'Dépenses' : 'Expenses' }}
                                            @break
                                        @case('evaluations')
                                            {{ $isFrench ? 'Évaluations Employés' : 'Employee evaluations' }}
                                            @break
                                        @case('plannings')
                                            {{ $isFrench ? 'Plannings' : 'Schedules' }}
                                            @break
                                        @case('produit_stocks')
                                            {{ $isFrench ? 'Stocks de produits' : 'Product stocks' }}
                                            @break
                                        @case('reactions')
                                            {{ $isFrench ? 'Réactions' : 'Reactions' }}
                                            @break
                                        @case('repos_conges')
                                            {{ $isFrench ? 'Repos et congés' : 'Rest and leave' }}
                                            @break
                                        @case('salaires')
                                            {{ $isFrench ? 'Salaires' : 'Salaries' }}
                                            @break
                                        @case('stagiaires')
                                            {{ $isFrench ? 'Stagiaires' : 'Interns' }}
                                            @break
                                        @case('transaction_ventes')
                                            {{ $isFrench ? 'Transactions de ventes' : 'Sales transactions' }}
                                            @break
                                        @case('transactions')
                                            {{ $isFrench ? 'Transactions Financière' : 'Financial transactions' }}
                                            @break
                                        @case('users')
                                            {{ $isFrench ? 'Utilisateurs' : 'Users' }}
                                            @break
                                        @default
                                            {{ $isFrench ? 'Table technique' : 'Technical table' }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                    <button type="submit" 
                            class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        {{ $isFrench ? 'Analyser' : 'Analyze' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8 animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ $isFrench ? 'Comment utiliser cette fonctionnalité ?' : 'How to use this feature?' }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed mb-3">
                        {{ $isFrench 
                            ? 'Cette interface vous permet d\'explorer les différentes tables disponibles dans votre base de données. Sélectionnez une table dans la liste déroulante et cliquez sur Analyser pour afficher son contenu de façon claire et structurée.' 
                            : 'This interface allows you to explore the different tables available in your database. Select a table from the dropdown list and click Analyze to display its content in a clear and structured way.' 
                        }}
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $isFrench 
                            ? 'Cette analyse est particulièrement utile pour les administrateurs souhaitant examiner les informations de fond en comble.' 
                            : 'This analysis is particularly useful for administrators who want to examine information thoroughly.' 
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile-First CSS Animations -->
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.delay-100 { animation-delay: 0.1s; }

/* Mobile Optimizations */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Touch-friendly form inputs */
    select, button {
        min-height: 44px;
        font-size: 16px; /* Prevents zoom on iOS */
        touch-action: manipulation;
    }
    
    /* Mobile spacing adjustments */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
}

/* Enhanced hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Loading state for form submission */
button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Focus states for accessibility */
select:focus, button:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>

<script>
// Mobile form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Add loading state on form submission
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $isFrench ? 'Analyse...' : 'Analyzing...' }}
        `;
    });
});
</script>
@endsection
