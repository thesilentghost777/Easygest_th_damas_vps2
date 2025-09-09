@extends('layouts.app')

@section('content')
<div class="flex min-h-screen" x-data="{ sidebarOpen: false }" x-cloak>
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
        class="lg:translate-x-0 transform lg:w-72 w-64 bg-gradient-to-br from-blue-800 to-blue-600 text-white flex flex-col fixed h-screen inset-y-0 z-40 transition-transform duration-300 ease-in-out overflow-y-auto"
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

        <!-- Menu Sections - Avec défilement -->
        <div class="flex-1 overflow-y-auto sidebar-scroll p-6">
            <!-- Production Section - Plus utilisée en premier -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-factory text-sm mr-2"></i>{{ $isFrench ? 'Production' : 'Production' }}
                </h3>
                <ul 748596748596748596class="space-y-2">
                    <li>
                        <a href="{{ route('production.chief.workspace') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-calendar-plus mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Assigner Production du jour' : 'Assign Daily Production' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assignations.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-package-variant-closed mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Assigner Matière du jour' : 'Assign Daily Materials' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reception.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                            <i class="menu-icon mdi mdi-book-open-page-variant mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Cahier de madame willy' : 'willy\'s Ledger' }}</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('assignations.resume-quantites') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-clipboard-text mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Résumé des assignations' : 'Assignment Summary' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-warehouse mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Gestion Stocks' : 'Stock Management' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('solde-cp.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-wallet mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Gérer le solde journalier' : 'Manage Daily Balance' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('depenses.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-cash-multiple mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Achat et Dépenses' : 'Purchases and Expenses' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('depenses.index2') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-truck-delivery mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Livraisons' : 'Deliveries' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('manquants_flux.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-chart-line mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Tableau de bord Flux de Produits' : 'Product Flow Dashboard' }}</span>
                        </a>
                    </li>
                    <li class="transform transition-all duration-300 hover:translate-x-2">
    <a href="{{ route('receptions.vendeurs.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
        <!-- Icône SVG -->
        <svg class="w-5 h-5 mr-3 text-blue-200 group-hover:text-white transform transition-all duration-300 group-hover:scale-110 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H3"></path>
        </svg>
        <span class="relative z-10">{{ $isFrench ? 'Réceptions Vendeurs' : 'Vendor Receipts' }}</span>
    </a>
</li>
                    <li class="transform transition-all duration-300 hover:translate-x-2">
                            <a href="{{ route('cash.distributions.index') }}" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/5 transform translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                                <i class="mdi mdi-package-variant-closed-check mr-3 text-blue-200 group-hover:text-white text-lg transform transition-all duration-300 group-hover:scale-110 relative z-10"></i>
                                <span class="relative z-10">{{ $isFrench ? 'Session de vente' : 'Sales session' }}</span>
                            </a>
                    </li>
                   <li>
    <a href="{{ route('calcul-ventes.index') }}" 
       class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors duration-200">
        <i class="menu-icon mdi mdi-calculator mr-3 text-lg" aria-hidden="true"></i>
        <span>{{ $isFrench ? 'Calculer les ventes' : 'Calculate Sales' }}</span>
    </a>
</li>
		<li>
 <a href="{{ route('calcul-production-boulangerie.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
<i class="menu-icon mdi mdi-calculator mr-3 text-lg"></i>
<span>{{ $isFrench ? 'Calcul Production Boulangerie' : 'Bakery Production Calculation' }}</span>
</a>
</li>
                   
                    <li>
                        <a href="{{ route('avaries.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-alert-circle-outline mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Avaries Pointeur' : 'Pointer Damages' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recettes.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-book-open-variant mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Recettes des produits' : 'Product Recipes' }}</span>
                        </a>
                    </li>
                    <li class="flex items-center p-2 rounded hover:bg-white/10 cursor-pointer">
                    <a href="{{ route('boulangerie.production.index') }}" class="flex items-center">
                        <i class="mdi mdi-bread-slice mr-2 text-lg"></i>
                        <span>{{ $isFrench ? 'Visualiser Production Boulangerie' : 'View Bakery Production' }}</span>
                    </a>
                </li>
                    <li>
                        <a href="{{ route('boulangerie.configuration.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cog mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Configuration sac Boulangerie' : 'Bakery Sac Configuration' }}</span>
                                </a>
                            </li>
                    <li>
                        <a href="{{ route('matieres.recommandees.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                          <i class="menu-icon mdi mdi-clipboard-list mr-3 text-lg"></i>
                          <span>{{ $isFrench ? 'Gestion des Matières recommandées' : 'Recommended Materials Management' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('production.edit.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-pencil-box mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Editer une production ou une vente' : 'Edit Production or Sale' }}</span>
                       </a>
                    </li>
                    <li>
                        <a href="{{ route('matieres.retours.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-check-circle mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Valider les retours de matières' : 'Validate Material Returns' }}</span>
                       </a>
                    </li>

                    
                </ul>
            </div>

            <!-- Section Commandes et Ventes - Seconde en importance -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-cart-outline text-sm mr-2"></i>{{ $isFrench ? 'Commandes & Sacs' : 'Orders & Bags' }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('chef.commandes.create') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-cart-plus mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Gestion Commande' : 'Order Management' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('commandes.reduction.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                        <i class="menu-icon mdi mdi-file-document-outline mr-3 text-lg"></i>
                        <span>{{ $isFrench ? 'Facturation Commandes' : 'Order Billing' }}</span>
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
                            
                    @feature('manage_bags')
                    <li>
                        <a href="{{ route('bag.assignments.create') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-shopping-outline mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Assigner sacs aux vendeuses' : 'Assign Bags to Saleswomen' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bag.recovery.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-shopping mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Récupérer sacs invendus' : 'Recover Unsold Bags' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                </ul>
            </div>

            <!-- Administration et RH - Troisième en importance -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-account-group text-sm mr-2"></i>{{ $isFrench ? 'Administration RH' : 'HR Administration' }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('employees.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-account-star mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Évaluation employés' : 'Employee Evaluation' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('choix_classement') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-podium mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Classement Employés' : 'Employee Ranking' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('repos-conges.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-calendar-check mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Planning & repos' : 'Planning & Rest' }}</span>
                        </a>
                    </li>
                                      <li>
    <a href="{{ route('versements.index') }}" 
       class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
        <i class="menu-icon mdi mdi-cash-multiple mr-3 text-lg"></i>
        <span>{{ $isFrench ? 'Nouveaux versements' : 'New Payments' }}</span>
    </a>
</li>
                   
                    @feature('temp_missing_items')
                    <li>
                        <a href="{{ route('manquant.create') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-alert-circle-outline mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Facturer un manquant' : 'Bill a Missing Item' }}</span>
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
                                <a href="{{ route('solde') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                                    <i class="menu-icon mdi mdi-cash mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Finance' : 'Finance' }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('valider-as') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-cash-plus mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Avance Salaire' : 'Salary Advance' }}</span>
                                    
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('valider-retraitcp') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-cash-remove mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Validation Retrait AS' : 'AS Withdrawal Validation' }}</span>
                                </a>
                            </li>
                            @endfeature
			 @feature('loans')
                            <li>
                                <a href="{{ route('loans.pending') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-briefcase mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Gestion des emprunts' : 'Loan Management' }}</span>
                                </a>
                            </li>
                            @endfeature
                </ul>
            </div>

            <!-- Complexe et Matières - Quatrième en importance -->
            @feature('complex_invoices')
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-domain text-sm mr-2"></i>{{ $isFrench ? 'Complexe' : 'Complex' }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('matieres.complexe.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-package-variant mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Matières premières du complexe' : 'Complex Raw Materials' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('factures-complexe.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-receipt mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Factures matières du complexe' : 'Complex Material Invoices' }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endfeature

            <!-- Statistiques - Cinquième en importance -->
            @feature('view_production_stats')
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-chart-bar text-sm mr-2"></i>{{ $isFrench ? 'Statistiques' : 'Statistics' }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('production.stats.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-factory mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Production' : 'Production' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('statistiques.commande') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-truck-delivery mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Commandes et Sacs' : 'Orders and Bags' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('statistiques.stagiere') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-school mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Stagiaires' : 'Interns' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('matieres.complexe.statistiques') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-chart-donut mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Matières du complexe' : 'Complex Materials' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('statistiques.ventes') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-cash mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Vente' : 'Sales' }}</span>
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
                        <a href="{{ route('gaspillage.index') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                          <i class="menu-icon mdi mdi-delete-empty mr-3 text-lg"></i>
                          <span>{{ $isFrench ? 'Gaspillage de matiere premiere' : 'Raw Material Waste' }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endfeature

            <!-- Analyses - Sixième en importance -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-magnify text-sm mr-2"></i>{{ $isFrench ? 'Analyses' : 'Analysis' }}
                </h3>
                <ul class="space-y-2">
                 
                    
                    @feature('view_product_stats')
                    <li>
                        <a href="{{ route('analyse.produits') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-chart-box-outline mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Performances des produits' : 'Product Performance' }}</span>
                        </a>
                    </li>
                    @endfeature
                   
                    @feature('production_details')
                     <li class="transform hover:scale-105 transition-all duration-200">
                            <a href="{{ route('production.stats.details') }}" 
                             class="flex items-center p-3 rounded-xl hover:bg-white/10 group transition-all duration-200 hover:shadow-lg">
                            <i class="mdi mdi-eye mr-3 text-lg group-hover:animate-bounce"></i>
                        <span class="font-medium">{{ $isFrench ? 'Détails des Productions' : 'Production Details' }}</span>
                </a>
            </li>
            @endfeature

                    @feature('view_producer_stats')
                    <li>
                        <a href="{{ route('employees2') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-account-group mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Performances des producteurs' : 'Producer Performance' }}</span>
                        </a>
                    </li>
                    @endfeature
                                    
                    @feature('sales_details')
                    <li>
                        <a href="{{ route('ventes.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-trending-up mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Détails des ventes' : 'Sales Details' }}</span>
                        </a>
                    </li>
                    @endfeature
                </ul>
            </div>

            <!-- Communication - Septième en importance -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-message-text-outline text-sm mr-2"></i>{{ $isFrench ? 'Communication' : 'Communication' }}
                </h3>
                <ul class="space-y-2">
                    @feature('messages_suggestions')
                    <li>
                        <a href="{{ route('announcements.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-bullhorn mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Annonces' : 'Announcements' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('access_employee_account')
                    <li>
                        <a href="{{ route('account-access.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-account-key mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Accès comptes personnel' : 'Employee Account Access' }}</span>
                        </a>
                    </li>
                    @endfeature
                </ul>
            </div>

            <!-- Divers - Dernière section -->
            <div class="space-y-4 mb-8">
                <h3 class="text-base font-semibold uppercase tracking-wider text-white/70">
                    <i class="mdi mdi-tools text-sm mr-2"></i>{{ $isFrench ? 'Divers' : 'Miscellaneous' }}
                </h3>
                <ul class="space-y-2">
                    <li>
                                <a href="{{ route('versements.validation') }}" class="menu-link flex items-center px-4 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors relative">
                                    <i class="menu-icon mdi mdi-check-circle mr-3 text-lg"></i>
                                    <span>{{ $isFrench ? 'Validation versements' : 'Payment Validation' }}</span>
                                </a>
                            </li>
                    <li>
                        <a href="{{ route('manquant-inventaire.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-package-variant mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Inventaire Matiere' : 'Material Inventory' }}</span>
                       </a>
                       </li>
                       <li>
                       <a href="{{ route('manquant-produit.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-cube-outline mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Inventaire Produit' : 'Product Inventory' }}</span>
                       </a>
                       </li>
                    @feature('reserve_raw_materials')
                    <li>
                        <a href="{{ route('chef.reservations.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-calendar-clock mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Gérer réservations' : 'Manage Reservations' }}</span>
                        </a>
                    </li>
                    @endfeature
                    
                    @feature('materials_recovery')
                    <li>
                        <a href="{{ route('taules.types.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                            <i class="menu-icon mdi mdi-grid mr-3 text-lg"></i>
                            <span>{{ $isFrench ? 'Tôles de production' : 'Production Molds' }}</span>
                        </a>
                    </li>
                    @endfeature
                    <li>
                        <a href="{{ route('producteur.avaries.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-alert-circle mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Visualiser les Avaries' : 'View Damages' }}</span>
                       </a>
                    </li>
                    <li>
                        <a href="{{ route('matieres.notifications.index') }}" class="menu-link flex items-center px-3 py-2.5 text-base rounded-lg hover:bg-white/10 transition-colors">
                       <i class="menu-icon mdi mdi-tune mr-3 text-lg"></i>
                       <span>{{ $isFrench ? 'Définir les seuils de matières' : 'Set Material Thresholds' }}</span>
                       </a>
                       </li>    
                </ul>
            </div>
        </div>

        <!-- Profile Section fixe -->
        <div class="p-6 border-t border-white/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                    <i class="mdi mdi-account text-xl"></i>
                </div>
                <div>
                    <div class="font-medium">{{ $nom ?? ($isFrench ? 'Utilisateur' : 'User') }}</div>
                    <div class="text-sm text-white/70">{{ $role ?? ($isFrench ? 'Chef de Production' : 'Production Manager') }}</div>
                </div>
            </div>
        </div>
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

    <!-- Content Area - Défilement indépendant -->
    <main class="flex-1 p-3 lg:ml-72 overflow-y-auto">
        @yield('page-content')
    </main>
</div>

<style>
    /* Hide elements on initial load to prevent flash */
    [x-cloak] {
        display: none !important;
    }
    
    /* Animation pour les icônes au survol */
    .menu-icon {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    
    .menu-link:hover .menu-icon {
        transform: scale(1.2);
        color: white;
    }
    
    /* Amélioration du défilement */
    .sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.1);
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

    /* Mobile specific styles */
    @media (max-width: 1024px) {
        /* Ensure sidebar is hidden by default on mobile */
        aside:not(.lg\\:translate-x-0) {
            transform: translateX(-100%);
        }
    }
</style>
@endsection
