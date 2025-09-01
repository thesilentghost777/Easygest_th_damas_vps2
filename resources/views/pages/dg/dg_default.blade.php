@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Easy Gest Dashboard</title>

    <!-- External Resources -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        /* Hide elements on initial load to prevent flash */
        [x-cloak] {
            display: none !important;
        }
        
        /* Empêcher le défilement du body et html */
        html, body {
            height: 100%;
            overflow: hidden;
        }

        /* Sidebar conteneur principal - hauteur complète, pas de défilement */
        .sidebar-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Zone défilable de la sidebar - prend tout l'espace disponible */
        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        /* Contenu principal - hauteur complète avec défilement interne */
        .main-content {
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Animation pour rendre les icônes plus fun au survol */
        .menu-icon {
            transition: transform 0.3s ease;
        }
        
        .menu-link:hover .menu-icon {
            transform: scale(1.2);
        }
        
        /* Style pour les sections */
        .menu-section {
            margin-bottom: 1.5rem;
            transition: opacity 0.3s ease;
        }
        
        /* Styles pour les badges de catégorie */
        .category-badge {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            margin-left: auto;
        }

        /* Mobile specific styles */
        @media (max-width: 1024px) {
            /* Ensure sidebar is hidden by default on mobile */
            aside:not(.lg\\:translate-x-0) {
                transform: translateX(-100%);
            }
        }
    </style>

    @php
        $nbre_manquant = App\Models\ManquantTemporaire::where('statut', 'en_attente')->count();
        $nbre_sv = App\Models\CashDistribution::where('status', 'en_cours')->count();
        $nbre_versement = App\Models\VersementChef::where('status', false)->count();
        $nbre_salaire = App\Models\Salaire::where('retrait_demande', true)->where('flag', false)->count();
        $nbre_as = App\Models\AvanceSalaire::where('retrait_demande', false)->where('flag', false)->where('sommeAs', '>', 0)->count();
        $nbre_retrait_as = App\Models\AvanceSalaire::where('retrait_demande', true)->where('retrait_valide', false)->count();
        $nbre_emprunt = DB::table('loan_requests')
            ->where('status', 'pending')
            ->join('users', 'users.id', '=', 'loan_requests.user_id')
            ->select('loan_requests.*', 'users.name')
            ->count();
        $nbre_messages = App\Models\Message::count();
        Illuminate\Support\Facades\Log::info("ZZZZZ-{$nbre_versement}");

        // Création du tableau contenant toutes les variables
        $nbres = [
            'manquant' => $nbre_manquant,
            'session_vente' => $nbre_sv,
            'versement' => $nbre_versement,
            'salaire' => $nbre_salaire,
            'avance_salaire' => $nbre_as,
            'retrait_avance_salaire' => $nbre_retrait_as,
            'emprunt' => $nbre_emprunt,
            'messages' => $nbre_messages,
        ];

    @endphp
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-full" x-data="{ sidebarOpen: false }" x-cloak>
        <!-- Mobile Menu Button - Reduced to half size -->
        <button
            class="lg:hidden p-2 text-white bg-blue-600 fixed z-50 top-2 left-2 rounded-md shadow-md"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="{{ $isFrench ? 'Ouvrir le menu' : 'Open menu' }}">
            <i class="mdi mdi-menu text-lg"></i>
        </button>

        <!-- Sidebar - With proper mobile handling to prevent flash -->
        <aside
            x-show="sidebarOpen || window.innerWidth >= 1024"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="lg:translate-x-0 transform fixed inset-y-0 z-40 w-64 lg:w-72 transition-transform duration-300 ease-in-out bg-gradient-to-br from-blue-800 to-blue-600 text-white sidebar-container lg:sticky lg:top-0 lg:h-screen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-full">

            <!-- Header fixe -->
            <div class="p-6">
                <div class="text-center border-b border-white/20 pb-6">
                    <h1 class="text-2xl font-bold">EASY GEST</h1>
                <span class="text-xs">{{ $isFrench ? 'Propulsé par TFS237' : 'Powered by TFS237' }}</span>
            </div>
            </div>

            <!-- Navigation avec défilement -->
            <nav class="sidebar-scroll">
                <div class="p-6">
                    <!-- Tableau de bord Section (le plus utilisé) -->
                    <div class="menu-section">
                        <a href="{{ route('dg.workspace') }}" class="menu-link flex items-center px-4 py-3 text-base rounded-lg hover:bg-white/10 transition-colors bg-white/5 mb-4">
                            <i class="menu-icon mdi mdi-view-dashboard mr-3 text-xl text-blue-200"></i>
                            <span class="font-medium">{{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}</span>
                        </a>
                    </div>

                    <!-- Finance Section (très utilisée) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-currency-usd mr-2 text-sm"></i>{{ $isFrench ? 'Finances' : 'Finance' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('objectives.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-target mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Objectifs' : 'Objectives' }}</span>
                                </a>
                            </li>
                           
                            <li>
                                <a href="{{ route('solde') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-bank mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Solde entreprise' : 'Company Balance' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transactions.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-swap-horizontal mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Transactions' : 'Transactions' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rapports.mensuel.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                               <i class="menu-icon mdi mdi-chart-line mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Rapports Mensuel' : 'Monthly Reports' }}</span>
                               </a>
                               </li>
                            @feature('manage_bags')
                            <li>
                                <a href="{{ route('bags.index2') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-bag-personal-outline mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Gestion des sacs' : 'Bag Management' }}</span>
                                </a>
                            </li>
                            @endfeature
                          
                        </ul>
                    </div>

                    <!-- Statistiques Section (très utilisée) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-chart-bar mr-2 text-sm"></i>{{ $isFrench ? 'Statistiques' : 'Statistics' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('analyse.produits') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-chart-box-outline mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Performance Produit' : 'Product Performance' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statistiques.finance') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cash mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Finance' : 'Finance' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statistiques.ventes') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cash mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Vente' : 'Sales' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('production.stats.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-factory mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Production' : 'Production' }}</span>
                                </a>
                            </li>
                             <li class="transform hover:scale-105 transition-all duration-200">
                                    <a href="{{ route('production.stats.details') }}" 
                                     class="flex items-center p-3 rounded-xl hover:bg-white/10 group transition-all duration-200 hover:shadow-lg">
                                    <i class="mdi mdi-eye mr-3 text-lg group-hover:animate-bounce"></i>
                                <span class="font-medium">{{ $isFrench ? 'Détails des Productions' : 'Production Details' }}</span>
                        </a>
                    </li>
                            <li>
                                <a href="{{ route('employees2') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-account-group mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Performances des producteurs' : 'Producer Performance' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('matieres.rapport-journalier') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                  <i class="menu-icon mdi mdi-chart-timeline mr-3 text-lg"></i>
                                  <span>{{ $isFrench ? 'Flash Recap Production' : 'Production Flash Recap' }}</span>
                                </a>
                              </li>
                            <li>
                                <a href="{{ route('incoherence.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-chart-box-outline mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Ratio Produit-vendu' : 'Product-Sales Ratio' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('rations.admin.statistics') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                               <i class="menu-icon mdi mdi-chart-bar mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Statistiques sur les rations des employés' : 'Employee Rations Statistics' }}</span>
                               </a>
                               </li>
                            <li>
                                <a href="{{ route('gaspillage.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                  <i class="menu-icon mdi mdi-delete-empty mr-3 text-lg"></i>
                                  <span>{{ $isFrench ? 'Gaspillage de matiere premiere' : 'Raw Material Waste' }}</span>
                                </a>
                            </li>

                            
                    
                    @feature('sales_details')
                    <li>
                        <a href="{{ route('ventes.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-trending-up mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Détails des ventes' : 'Sales Details' }}</span>
                        </a>
                    </li>
                    @endfeature
                            <li>
                                <a href="{{ route('statistiques.horaires') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-chart-bar mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Employés/RH' : 'Employees/HR' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statistiques.commande') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-truck-delivery mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Commandes et Sacs' : 'Orders and Bags' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statistiques.stagiere') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-account-group mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Stagiaires' : 'Interns' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('statistiques.autres') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-chart-pie mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Autres Statistiques' : 'Other Statistics' }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Gestion RH Section (utilisée régulièrement) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-account-group mr-2 text-sm"></i>{{ $isFrench ? 'Ressources Humaines' : 'Human Resources' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('choix_classement') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-podium mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Classement Employés' : 'Employee Ranking' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('employees.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-account-search mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Consultation & notation' : 'Consultation & Rating' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account-access.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-account-key mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Accès aux comptes' : 'Account Access' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stagiaires.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-school mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Gestion stagiaires' : 'Intern Management' }}</span>
                                </a>
                            </li>
                           
                            <li>
                                <a href="{{ route('employee.code_list') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-qrcode mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Codes d\'enregistrement' : 'Registration Codes' }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Opérations Section (utilisée régulièrement) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-cogs mr-2 text-sm"></i>{{ $isFrench ? 'Opérations' : 'Operations' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('manquants.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-alert-circle mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Gestion des Manquants' : 'Missing Items Management' }}</span>
                                    @if($nbres['manquant'] > 0)
                                        <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                            {{ $nbres['manquant'] }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @feature('primes')
                            <li>
                                <a href="{{ route('primes.create') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-gift mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Primes' : 'Bonuses' }}</span>
                                </a>
                            </li>
                            @endfeature
                            <li>
                                <a href="{{ route('depenses.validation.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-check-circle mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Validation des Dépenses' : 'Expense Validation' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cash.distributions.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-package-variant-closed-check mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Cahier des vendeuses' : 'Seller\'s Ledger' }}</span>
                                    @if($nbres['session_vente'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['session_vente'] }}
                                    </span>                           
                                    @endif
                                </a>
                            </li>
                        
                               <li>
                               <a href="{{ route('gestion_solde.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                               <i class="menu-icon mdi mdi-cash-multiple mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Cahier des depenses cp' : 'CP Expense Ledger' }}</span>
                               </a>
                               </li>
                               <li>
                               <a href="{{ route('dg.sessions') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                               <i class="menu-icon mdi mdi-account-cash mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Cahier des caissiers(es)' : 'Cashier Ledger' }}</span>
                               </a>
                               </li>
                               <li>
                               <a href="{{ route('versements.visualisation') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                   <i class="menu-icon mdi mdi-eye mr-3 text-lg"></i>
                                   <span>{{ $isFrench ? 'Cahier des Versements' : 'Payments Ledger' }}</span>
                               </a>
                           </li>
                            <li>
    <a href="{{ route('versements.index') }}" 
       class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
        <i class="menu-icon mdi mdi-cash-multiple mr-3 text-lg"></i>
        <span>{{ $isFrench ? 'Nouveaux versements' : 'New Payments' }}</span>
    </a>
</li>

                            <li>
                                <a href="{{ route('versements.validation') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-check-circle mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Validation versements' : 'Payment Validation' }}</span>
                                    @if($nbres['versement'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['versement'] }}
                                    </span>  
                                @endif
                                </a>
                            </li>
                           

                            <li>
                                <a href="{{ route('production.suggestions.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                               <i class="menu-icon mdi mdi-lightbulb-on mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Suggerer une Production' : 'Suggest Production' }}</span>
                               </a>
                               </li>
                               <li>
                                <a href="{{ route('production.edit.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                               <i class="menu-icon mdi mdi-pencil-box mr-3 text-lg"></i>
                               <span>{{ $isFrench ? 'Editer une production ou une vente' : 'Edit Production or Sale' }}</span>
                               </a>
                            </li>
                            <li>
                                <a href="{{ route('boulangerie.configuration.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cog mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Configuration sac Boulangerie' : 'Bakery Sac Configuration' }}</span>
                                </a>
                            </li>
                            <li class="flex items-center p-2 rounded hover:bg-white/10 cursor-pointer">
                    <a href="{{ route('boulangerie.production.index') }}" class="flex items-center">
                        <i class="mdi mdi-bread-slice mr-2 text-lg"></i>
                        <span>{{ $isFrench ? 'Visualiser Production Boulangerie' : 'View Bakery Production' }}</span>
                    </a>
                </li>
                            @feature('daily_rations')
                            <li>
                                <a href="{{ route('rations.admin.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-food mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Rations employés' : 'Employee Rations' }}</span>
                                </a>
                            </li>
                            @endfeature
                            <li>
                                <a href="{{ route('matieres.recommandees.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                  <i class="menu-icon mdi mdi-clipboard-list mr-3 text-lg"></i>
                                  <span>{{ $isFrench ? 'Gestion des Matières recommandées' : 'Recommended Materials Management' }}</span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>

                    <!-- Salaire,Avances et Prêts (moins utilisés) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-cash-fast mr-2 text-sm"></i>{{ $isFrench ? 'Salaires , Avances & Prêts' : 'Salaries, Advances & Loans' }}
                        </h3>
                        <ul class="space-y-1">
                            @feature('payslips_salary')
                            <li>
                                <a href="{{ route('salaires.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-cash-multiple mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Salaires' : 'Salaries' }}</span>
                                    @if($nbres['salaire'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['salaire'] }}
                                    </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('configurations.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cog mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Blocage/Deblocage des salaires et AS' : 'Salary/Advance Blocking/Unblocking' }}</span>
                                </a>
                            </li>
                            @endfeature
                            @feature('salary_advances')
                            <li>
                                <a href="{{ route('avance-salaires.dashboard') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-chart-line mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Stats Avances Salaire' : 'Salary Advance Stats' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('valider-as') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-cash-plus mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Avance Salaire' : 'Salary Advance' }}</span>
                                    @if($nbres['avance_salaire'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['avance_salaire'] }}
                                    </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('valider-retraitcp') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-cash-remove mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Validation Retrait AS' : 'AS Withdrawal Validation' }}</span>
                                    @if($nbres['retrait_avance_salaire'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['retrait_avance_salaire'] }}
                                    </span>
                                    @endif
                                </a>
                            </li>
                            @endfeature
                            @feature('loans')
                            <li>
                                <a href="{{ route('loans.pending') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-briefcase mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Gestion des emprunts' : 'Loan Management' }}</span>
                                    @if($nbres['emprunt'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['emprunt'] }}
                                    </span>
                                    @endif
                                </a>
                            </li>
                            @endfeature
                        </ul>
                    </div>

                    <!-- Communication Section (moins utilisée) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-message-text mr-2 text-sm"></i>{{ $isFrench ? 'Communication' : 'Communication' }}
                        </h3>
                        <ul class="space-y-1">
                            @feature('messages_suggestions')
                            <li>
                                <a href="{{ route('announcements.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-bullhorn mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Annonces' : 'Announcements' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('lecture_message') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-email-open mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Lire messages & suggestions' : 'Read Messages & Suggestions' }}</span>
                                    @if($nbres['messages'] > 0)
                                    <span class="absolute right-2 flex items-center justify-center bg-green-500 text-white text-xs font-medium min-w-[20px] h-5 px-1.5 rounded-full">
                                        {{ $nbres['messages'] }}
                                    </span>                                     @endif
                                </a>
                            </li>
                            @endfeature
                            @feature('sherlock_recipe')
                            <li>
                                <a href="{{ route('recettes.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-food-turkey mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Recettes des produits' : 'Product Recipes' }}</span>
                                </a>
                            </li>
                            @endfeature
                        </ul>
                    </div>

                    <!-- Règlementation Section (moins utilisée) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-file-document mr-2 text-sm"></i>{{ $isFrench ? 'Réglementation' : 'Regulations' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('extras.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-gavel mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Réglementation' : 'Regulations' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('delis.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-alert-octagon mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Infractions' : 'Violations' }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Section Rapports et Configuration (moins utilisée) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-folder-outline mr-2 text-sm"></i>{{ $isFrench ? 'Rapports & Config' : 'Reports & Config' }}
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('rapports.select') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-file-chart mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Rapports détaillés' : 'Detailed Reports' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('query.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-information mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Détails informatifs' : 'Informative Details' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('setup.edit') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cog mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Infos de la structure' : 'Company Information' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('features.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-key mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Droits d\'accès' : 'Access Rights' }}</span>
                                </a>
                            </li>
                           
                        </ul>
                    </div>

                    <!-- Sherlock Section (utilisée occasionnellement) -->
                    <div class="menu-section">
                        <h3 class="text-base font-semibold uppercase tracking-wider text-white/70 flex items-center px-2 mb-2">
                            <i class="mdi mdi-magnify mr-2 text-sm"></i>{{ $isFrench ? 'Sherlock IA' : 'Sherlock AI' }}
                        </h3>
                        <ul class="space-y-1">
                            @feature('sherlock_copilot')
                            <li>
                                <a href="{{ route('sherlock.copilot') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-robot mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Sherlock Copilot' : 'Sherlock Copilot' }}</span>
                                </a>
                            </li>
                            @endfeature
                            @feature('sherlock_advisor')
                            <li>
                                <a href="{{ route('sherlock.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-account-tie mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Conseiller Sherlock' : 'Sherlock Advisor' }}</span>
                                </a>
                            </li>
                            @endfeature
                            @feature('sherlock_recipe')
                            <li>
                                <a href="{{ route('sherlock.recipes.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-notebook mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Sherlock recettes' : 'Sherlock Recipes' }}</span>
                                </a>
                            </li>
                            @endfeature
                        </ul>
                        <br><br>

                    </div>
                </div>  
            </nav>
            
        </aside>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
             @click="sidebarOpen = false"></div>

        <!-- Main Content -->
        <main class="flex-1 main-content lg:ml-0">
            <div class="p-6">
                @yield('page-content')
            </div>
        </main>
    </div>
</body>
</html>
@endsection
