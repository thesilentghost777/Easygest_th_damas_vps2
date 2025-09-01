@extends('layouts.app')

@section('title', (isset($isFrench) && $isFrench) ? 'Gestion du Solde CP' : 'CP Balance Management')

@push('styles')
<style>
    .tab-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .tab-button {
        transition: all 0.3s ease;
        min-width: 150px;
        cursor: pointer;
    }
    
    .tab-button.active {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }
    
    .tab-button:hover:not(.active) {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .expense-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .expense-card:hover {
        border-left-color: #667eea;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .history-item {
        position: relative;
        padding-left: 2rem;
    }
    
    .history-item::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0.5rem;
        width: 0.5rem;
        height: 0.5rem;
        background: #667eea;
        border-radius: 50%;
    }
    
    .history-item::after {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 1rem;
        width: 1px;
        height: calc(100% - 1rem);
        background: #e2e8f0;
    }
    
    .history-item:last-child::after {
        display: none;
    }
    
    @media (max-width: 768px) {
        .tab-button {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .expense-card {
            margin-bottom: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm mb-8 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Cahiers financier du CP' : 'CP Financial Ledger' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ (isset($isFrench) && $isFrench) ? 'Suivi et contrôle des dépenses du centre de profit' : 'Monitoring and control of profit center expenses' }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-lg p-4 text-white">
                        <div class="text-sm opacity-90">
                            {{ (isset($isFrench) && $isFrench) ? 'Solde Actuel' : 'Current Balance' }}
                        </div>
                        <div class="text-2xl font-bold">
                            {{ number_format($soldeActuel->montant ?? 0, 2) }} FCFA
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres de dates -->
        <div class="bg-white rounded-lg shadow-sm mb-8 p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Date de début' : 'Start Date' }}
                    </label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ (isset($isFrench) && $isFrench) ? 'Date de fin' : 'End Date' }}
                    </label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    {{ (isset($isFrench) && $isFrench) ? 'Filtrer' : 'Filter' }}
                </button>
            </form>
        </div>

      
        <!-- Onglets -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Navigation des onglets avec icônes de cahier -->
            <div class="flex flex-wrap justify-center gap-6 mb-8">
                <button class="tab-button active flex flex-col items-center p-6 rounded-lg border-2 border-blue-200 bg-blue-50 hover:bg-blue-100 transition-all duration-300" data-tab="diverses">
                    <div class="text-4xl mb-3">📔</div>
                    <span class="text-sm font-medium text-gray-700">
                        {{ (isset($isFrench) && $isFrench) ? 'Dépenses Diverses' : 'Miscellaneous Expenses' }}
                    </span>
                </button>
                <button class="tab-button flex flex-col items-center p-6 rounded-lg border-2 border-gray-200 bg-gray-50 hover:bg-gray-100 transition-all duration-300" data-tab="matiere">
                    <div class="text-4xl mb-3">📗</div>
                    <span class="text-sm font-medium text-gray-700">
                        {{ (isset($isFrench) && $isFrench) ? 'Dépenses Matière' : 'Material Expenses' }}
                    </span>
                </button>
                <button class="tab-button flex flex-col items-center p-6 rounded-lg border-2 border-gray-200 bg-gray-50 hover:bg-gray-100 transition-all duration-300" data-tab="historique">
                    <div class="text-4xl mb-3">📘</div>
                    <span class="text-sm font-medium text-gray-700">
                        {{ (isset($isFrench) && $isFrench) ? 'Historique Complet' : 'Complete History' }}
                    </span>
                </button>
            </div>

            <!-- Contenu des onglets -->
            <div class="tab-content">
                <!-- Onglet Dépenses Diverses -->
                <div id="diverses" class="tab-pane active">
                    @include('gestion_solde.partials.depenses-diverses', ['depenses' => $depensesDiverses])
                </div>

                <!-- Onglet Dépenses Matière -->
                <div id="matiere" class="tab-pane hidden">
                    @include('gestion_solde.partials.depenses-matiere', ['depenses' => $depensesMatiere])
                </div>

                <!-- Onglet Historique -->
                <div id="historique" class="tab-pane hidden">
                    @include('gestion_solde.partials.historique-total', ['historique' => $historiqueTotal])
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-button');
    const panes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Désactiver tous les onglets
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.add('hidden'));
            
            // Réinitialiser les styles des onglets
            tabs.forEach(t => {
                t.classList.remove('border-blue-200', 'bg-blue-50');
                t.classList.add('border-gray-200', 'bg-gray-50');
            });
            
            // Activer l'onglet cliqué
            this.classList.add('active');
            this.classList.remove('border-gray-200', 'bg-gray-50');
            this.classList.add('border-blue-200', 'bg-blue-50');
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
});
</script>
@endpush
@endsection