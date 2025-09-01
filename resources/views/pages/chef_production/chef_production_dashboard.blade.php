@extends('pages.chef_production.chef_production_default')

@section('page-content')

<div class="min-h-screen bg-gradient-to-r from-blue-50 to-green-50" x-data="dashboard()">
    <main class="py-4 md:py-8 px-4 md:px-6 lg:px-10">
        <!-- Conseil optimisé -->
        <div class="p-4 mb-4 text-white bg-blue-500 rounded-lg shadow-md transform transition-all duration-300 hover:scale-102 md:hover:scale-101">
            <h4 class="text-base md:text-lg font-semibold mb-2">
                💡 {{ $isFrench ? 'Conseil : Optimisez vos statistiques de production !' : 'Tip: Optimize your production statistics!' }}
            </h4>
            <p class="text-sm md:text-base">
                {{ $isFrench 
                    ? 'Assurez-vous d\'avoir défini clairement la production attendue journalière pour mieux suivre et analyser vos performances. 🚀'
                    : 'Make sure to clearly define the expected daily production to better track and analyze your performance. 🚀'
                }}
            </p>
        </div>

        <!-- Header responsive -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 md:mb-8 space-y-4 md:space-y-0">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight text-center md:text-left">
                {{ $isFrench ? 'Tableau de bord Chef de Production' : 'Production Manager Dashboard' }}
            </h1>
            <button @click="showAssignmentModal = true"
                    class="w-full md:w-auto inline-flex items-center justify-center px-4 md:px-5 py-3 border border-transparent rounded-xl md:rounded-md shadow-lg md:shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-300 hover:scale-105 active:scale-95">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ $isFrench ? 'Assigner production' : 'Assign production' }}
            </button>
        </div>

        <!-- Horloge responsive -->
        <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-6 md:p-8 mb-6 transform transition-all duration-300 hover:shadow-xl">
            <div class="text-3xl md:text-4xl font-bold text-center text-gray-800 font-mono" x-text="currentTime">
                --:--:--
            </div>
        </div>

        <!-- Statistiques responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Production aujourd'hui -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-blue-500 transform transition-all duration-300 hover:scale-105 md:hover:scale-102 hover:shadow-xl">
                <h3 class="text-sm font-medium text-gray-600">
                    {{ $isFrench ? 'Production aujourd\'hui' : 'Today\'s production' }}
                </h3>
                <p class="mt-2 text-2xl md:text-3xl font-semibold text-gray-900">{{ number_format($productionJour) }}</p>
                <div class="mt-1 text-sm text-gray-500">{{ $isFrench ? 'unités' : 'units' }}</div>
            </div>

            <!-- Bénéfice potentiel -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-green-500 transform transition-all duration-300 hover:scale-105 md:hover:scale-102 hover:shadow-xl">
                <h3 class="text-sm font-medium text-gray-600">
                    {{ $isFrench ? 'Bénéfice potentiel brut journalier' : 'Daily gross potential profit' }}
                </h3>
                <p class="mt-2 text-2xl md:text-3xl font-semibold text-gray-900">{{ number_format($beneficeBrut) }}</p>
                <div class="mt-1 text-sm text-gray-500">FCFA</div>
            </div>

            <!-- Rendement -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-blue-500 transform transition-all duration-300 hover:scale-105 md:hover:scale-102 hover:shadow-xl">
                <h3 class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Rendement' : 'Yield' }}</h3>
                <p class="mt-2 text-2xl md:text-3xl font-semibold text-gray-900">{{ number_format($rendementData['pourcentage'], 1) }}%</p>
                <div class="mt-1 text-sm text-gray-500">
                    {{ number_format($rendementData['reel']+$pertes) }}/{{ number_format($rendementData['attendu']) }} FCFA
                </div>
            </div>

            <!-- Coût de production -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-green-500 transform transition-all duration-300 hover:scale-105 md:hover:scale-102 hover:shadow-xl">
                <h3 class="text-sm font-medium text-gray-600">
                    {{ $isFrench ? 'Coût de production' : 'Production cost' }}
                </h3>
                <p class="mt-2 text-2xl md:text-3xl font-semibold text-gray-900">{{ number_format($pertes, 1) }}</p>
                <div class="mt-1 text-sm text-gray-500">FCFA</div>
                <div class="mt-1 text-sm text-gray-500">
                    {{ $isFrench ? 'des matières premières' : 'of raw materials' }}
                </div>
            </div>

            @php
            if (number_format($beneficeBrut) > 0) {
                    $a = $isFrench ? 'Bien' : 'Good';
                }else {
                    $a = $isFrench ? 'Mauvais' : 'Poor';
                }
            @endphp 
            <!-- Appréciation -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 border-l-4 border-blue-500 transform transition-all duration-300 hover:scale-105 md:hover:scale-102 hover:shadow-xl">
                <h3 class="text-sm font-medium text-gray-600">{{ $isFrench ? 'Appréciation' : 'Assessment' }}</h3>
                <p class="mt-2 text-2xl md:text-3xl font-semibold text-gray-900">{{ $a }}</p>
            </div>
        </div>

        <!-- Graphiques responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Production journalière -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $isFrench ? 'Production journalière' : 'Daily production' }}
                </h3>
                <div class="h-64 md:h-80 relative">
                    <canvas id="productionChart"></canvas>
                </div>
            </div>

            <!-- Évolution des bénéfices -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 transform transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $isFrench ? 'Évolution des bénéfices' : 'Profit evolution' }}
                </h3>
                <div class="h-64 md:h-80 relative">
                    <canvas id="beneficesChart"></canvas>
                </div>
            </div>

            <!-- Répartition de la production (sur toute la largeur sur mobile) -->
            <div class="bg-white rounded-2xl md:rounded-xl shadow-lg p-4 md:p-6 lg:col-span-2 transform transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $isFrench ? 'Répartition de la production' : 'Production distribution' }}
                </h3>
                <div class="h-64 md:h-80 relative">
                    <canvas id="repartitionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Productions en cours -->
        <div class="bg-white rounded-2xl md:rounded-xl shadow-lg transform transition-all duration-300 hover:shadow-xl">
            <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-green-50 rounded-t-2xl md:rounded-t-xl">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $isFrench ? 'Production en cours' : 'Current production' }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $isFrench ? 'Aujourd\'hui' : 'Today' }}</p>
            </div>

            <div class="p-4 md:p-6">
                @forelse($productionsEnCours as $production)
                    <div class="mb-6 last:mb-0 transform transition-all duration-300 hover:bg-gray-50 p-3 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-900">
                                {{ $production['produit'] }} -
                                {{ $production['status'] == 1 
                                    ? ($isFrench ? 'Terminé' : 'Completed') 
                                    : ($isFrench ? 'En cours' : 'In progress') }}
                                ({{ number_format($production['quantite_actuelle']) }}/{{ number_format($production['quantite_attendue']) }} {{ $isFrench ? 'unités' : 'units' }})
                            </span>
                        </div>
                        
                        @if($production['quantite_attendue'] > 0)
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500 ease-out" 
                                     style="width: {{ min($production['progression'], 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ number_format($production['progression'], 1) }}% {{ $isFrench ? 'complété' : 'completed' }}</span>
                                <span>{{ $isFrench ? 'Objectif:' : 'Target:' }} {{ number_format($production['quantite_attendue']) }}</span>
                            </div>
                        @else
                            <div class="text-xs text-gray-500 mt-1">
                                <span>{{ $isFrench ? 'Production libre (sans objectif défini)' : 'Free production (no target defined)' }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">
                        {{ $isFrench ? 'Aucune production en cours aujourd\'hui' : 'No production in progress today' }}
                    </p>
                @endforelse
            </div>
        </div>

        <!-- Modal d'assignation -->
        <div x-show="showAssignmentModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50 p-4"
             x-cloak>
            <div x-show="showAssignmentModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-2xl md:rounded-lg shadow-xl w-full max-w-lg"
                 @click.away="showAssignmentModal = false">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $isFrench ? 'Nouvelle production' : 'New production' }}
                    </h3>
                    <button @click="showAssignmentModal = false"
                            class="text-gray-400 hover:text-gray-600 transition duration-150 p-2 hover:bg-gray-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9.293l4.95-4.95a1 1 0 111.414 1.414L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414L8.586 10 3.636 5.05a1 1 0 011.414-1.414L10 8.586z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="assignerProduction" class="p-6">
                    <div class="space-y-5">
                        <!-- Producteur -->
                        <div>
                            <label for="producteur" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? 'Producteur' : 'Producer' }}
                            </label>
                            <select x-model="formData.producteur"
                                    id="producteur"
                                    class="mt-1 block w-full rounded-xl md:rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">{{ $isFrench ? 'Sélectionner un producteur' : 'Select a producer' }}</option>
                                @foreach($producteurs as $producteur)
                                    <option value="{{ $producteur->id }}">{{ $producteur->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Produit -->
                        <div>
                            <label for="produit" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </label>
                            <select x-model="formData.produit"
                                    id="produit"
                                    class="mt-1 block w-full rounded-xl md:rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">{{ $isFrench ? 'Sélectionner un produit' : 'Select a product' }}</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->code_produit }}">{{ $produit->nom }}-{{ $produit->prix }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantité prévue -->
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-700">
                                {{ $isFrench ? 'Quantité prévue' : 'Expected quantity' }}
                            </label>
                            <input type="number"
                                   id="quantite"
                                   x-model="formData.quantite"
                                   class="mt-1 block w-full rounded-xl md:rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes"
                                      x-model="formData.notes"
                                      rows="3"
                                      class="mt-1 block w-full rounded-xl md:rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-col md:flex-row md:justify-end space-y-3 md:space-y-0 md:space-x-3">
                        <button type="button"
                                @click="showAssignmentModal = false"
                                class="w-full md:w-auto px-5 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl md:rounded-md transition duration-200">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </button>
                        <button type="submit"
                                class="w-full md:w-auto px-5 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl md:rounded-md shadow-md transition duration-200 transform hover:scale-105 active:scale-95">
                            {{ $isFrench ? 'Assigner' : 'Assign' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>

<style>
/* Styles pour Desktop */
@media (min-width: 768px) {
    .transform:hover {
        transform: translateY(-2px);
    }
}

/* Styles pour Mobile */
@media (max-width: 767px) {
    .transform:hover {
        transform: scale(1.02);
    }
    
    .transform:active {
        transform: scale(0.98);
    }
    
    /* Animation pour les cartes sur mobile */
    .bg-white {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .bg-white:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Animation des boutons sur mobile */
    button {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Animation spéciale pour le modal sur mobile */
    .rounded-2xl {
        backdrop-filter: blur(10px);
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function dashboard() {
    return {
        showAssignmentModal: false,
        currentTime: '--:--:--',
        formData: {
            producteur: '',
            produit: '',
            quantite: '',
            notes: ''
        },
        charts: [],
        mode: localStorage.getItem('mode') || 'chef_production',

        async assignerProduction() {
            try {
                if (!this.formData.producteur || !this.formData.produit || !this.formData.quantite) {
                    alert('{{ $isFrench ? "Veuillez remplir tous les champs obligatoires" : "Please fill in all required fields" }}');
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]').content;

                const response = await fetch('/assigner-production', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(this.formData)
                });

                if (!response.ok) {
                    throw new Error('{{ $isFrench ? "Erreur lors de l\'assignation" : "Assignment error" }}');
                }

                const result = await response.json();

                this.showAssignmentModal = false;
                this.formData = {
                    producteur: '',
                    produit: '',
                    quantite: '',
                    notes: ''
                };

                window.location.reload();

            } catch (error) {
                console.error('Erreur:', error);
                alert('{{ $isFrench ? "Une erreur est survenue lors de l\'assignation de la production" : "An error occurred during production assignment" }}');
            }
        },

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            this.initCharts();
            this.applyMode(this.mode);
        },

        toggleMode(newMode) {
            this.mode = newMode;
            localStorage.setItem('mode', newMode);
            this.applyMode(newMode);
            window.location.href = `?mode=${newMode}`;
        },

        applyMode(mode) {
            const body = document.body;
            body.classList.remove('mode-employe', 'mode-chef');
            body.classList.add(`mode-${mode}`);

            const chefElements = document.querySelectorAll('.chef-only');
            const employeElements = document.querySelectorAll('.employe-only');

            chefElements.forEach(el => {
                el.style.display = mode === 'chef_production' ? 'block' : 'none';
            });

            employeElements.forEach(el => {
                el.style.display = mode === 'employe' ? 'block' : 'none';
            });
        },

        updateClock() {
            this.currentTime = new Date().toLocaleTimeString();
        },

        initCharts() {
            this.charts.forEach(chart => chart.destroy());
            this.charts = [];

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: window.innerWidth < 768 ? 'bottom' : 'top',
                    }
                }
            };

            const lineOptions = {
                ...commonOptions,
                scales: {
                    x: {
                        grid: {
                            display: true
                        },
                        ticks: {
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: window.innerWidth < 768 ? 6 : 12
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4
                    },
                    point: {
                        radius: window.innerWidth < 768 ? 2 : 4
                    }
                }
            };

            const productionData = @json($graphData['productions']);
            const productionChart = new Chart(document.getElementById('productionChart'), {
                type: 'line',
                data: {
                    labels: productionData.map(item => item.timestamp.substring(0, 5)),
                    datasets: [{
                        label: '{{ $isFrench ? "Production (unités)" : "Production (units)" }}',
                        data: productionData.map(item => item.total),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true
                    }]
                },
                options: lineOptions
            });
            this.charts.push(productionChart);

            const beneficesData = @json($graphData['benefices']);
            const beneficesChart = new Chart(document.getElementById('beneficesChart'), {
                type: 'line',
                data: {
                    labels: beneficesData.map(item => item.timestamp.substring(0, 5)),
                    datasets: [{
                        label: '{{ $isFrench ? "Bénéfices (FCFA)" : "Profits (FCFA)" }}',
                        data: beneficesData.map(item => item.benefice),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        fill: true
                    }]
                },
                options: {
                    ...lineOptions,
                    scales: {
                        ...lineOptions.scales,
                        y: {
                            ...lineOptions.scales.y,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' FCFA';
                                }
                            }
                        }
                    }
                }
            });
            this.charts.push(beneficesChart);

            const productionsData = @json($productionsEnCours);
            const repartitionChart = new Chart(document.getElementById('repartitionChart'), {
                type: 'doughnut',
                data: {
                    labels: productionsData.map(p => p.produit),
                    datasets: [{
                        data: productionsData.map(p => p.quantite_actuelle),
                        backgroundColor: [
                            '#2563eb',
                            '#059669',
                            '#dc2626',
                            '#d97706',
                            '#7c3aed'
                        ]
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 768 ? 'bottom' : 'right'
                        }
                    }
                }
            });
            this.charts.push(repartitionChart);
        }
    }
}
</script>

@endsection
