
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 lg:px-4 py-4 lg:py-8 min-h-screen bg-gray-50">
    @include('buttons')
    
    <!-- Header responsive -->
    <div class="mb-6 animate-fade-in">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 text-white p-4 lg:p-5 rounded-t-xl shadow-lg">
            <h1 class="text-xl lg:text-2xl font-bold">
                {{ $isFrench ? 'Comparaison des Performances des Vendeurs' : 'Sellers Performance Comparison' }}
            </h1>
            <h5 class="text-blue-200 mt-1 text-sm lg:text-base">
                {{ $isFrench ? 'Statistiques pour' : 'Statistics for' }} {{ $moisActuel }}
            </h5>
        </div>

        <div class="bg-white p-4 rounded-b-xl shadow-lg">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('ventes.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 lg:py-2 bg-blue-600 border border-transparent rounded-xl lg:rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 transition-all duration-200 transform hover:scale-105 active:scale-95">
                    <i class="mdi mdi-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour aux Ventes' : 'Back to Sales' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Performance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        @foreach($statsVendeurs as $vendeur)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl animate-fade-in mobile-card">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4">
                <h5 class="font-bold text-lg truncate">{{ $vendeur->nom_serveur ?? (($isFrench ? 'Vendeur' : 'Seller') . ' #' . $vendeur->serveur_id) }}</h5>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-700 text-sm">{{ $isFrench ? 'Quantité vendue:' : 'Quantity sold:' }}</span>
                    <span class="bg-green-100 text-green-800 px-2.5 py-0.5 rounded-full text-sm font-medium">{{ $vendeur->total_ventes }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-700 text-sm">{{ $isFrench ? 'Gain rapporté:' : 'Revenue earned:' }}</span>
                    <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full text-sm font-medium">{{ number_format($vendeur->benefice, 0, ',', ' ') }} XAF</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-gray-700 text-sm">{{ $isFrench ? 'Produits invendus:' : 'Unsold products:' }}</span>
                    <span class="bg-yellow-100 text-yellow-800 px-2.5 py-0.5 rounded-full text-sm font-medium">{{ $vendeur->total_invendus }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 text-sm">{{ $isFrench ? 'Produits avariés:' : 'Damaged products:' }}</span>
                    <span class="bg-red-100 text-red-800 px-2.5 py-0.5 rounded-full text-sm font-medium">{{ $vendeur->total_avaries }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                <h5 class="font-bold flex items-center">
                    <i class="mdi mdi-chart-bar mr-2"></i>
                    {{ $isFrench ? 'Quantités Vendues' : 'Quantities Sold' }}
                </h5>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="ventesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Benefits Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                <h5 class="font-bold flex items-center">
                    <i class="mdi mdi-cash mr-2"></i>
                    {{ $isFrench ? 'Bénéfices Rapportés (XAF)' : 'Revenue Earned (XAF)' }}
                </h5>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="beneficesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Unsold Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                <h5 class="font-bold flex items-center">
                    <i class="mdi mdi-package-variant-closed mr-2"></i>
                    {{ $isFrench ? 'Produits Invendus' : 'Unsold Products' }}
                </h5>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="invendusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Damaged Chart -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                <h5 class="font-bold flex items-center">
                    <i class="mdi mdi-alert-circle mr-2"></i>
                    {{ $isFrench ? 'Produits Avariés' : 'Damaged Products' }}
                </h5>
            </div>
            <div class="p-4">
                <div class="h-64 lg:h-80">
                    <canvas id="avariesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    
    /* Mobile optimizations */
    @media (max-width: 1024px) {
        .mobile-card {
            transition: all 0.2s ease-out;
        }
        .mobile-card:active {
            transform: scale(0.98);
        }
        /* Touch targets */
        button, a {
            min-height: 44px;
            touch-action: manipulation;
        }
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = {
            labels: {!! json_encode($chartData['labels']) !!},
            dataVentes: {!! json_encode($chartData['dataVentes']) !!},
            dataBenefices: {!! json_encode($chartData['dataBenefices']) !!},
            dataInvendus: {!! json_encode($chartData['dataInvendus']) !!},
            dataAvaries: {!! json_encode($chartData['dataAvaries']) !!}
        };

        // Configuration commune
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };

        // Graphique des ventes
        new Chart(document.getElementById('ventesChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '{{ $isFrench ? "Quantités vendues" : "Quantities sold" }}',
                    data: chartData.dataVentes,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Graphique des bénéfices
        new Chart(document.getElementById('beneficesChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '{{ $isFrench ? "Bénéfices (XAF)" : "Revenue (XAF)" }}',
                    data: chartData.dataBenefices,
                    backgroundColor: 'rgba(37, 99, 235, 0.7)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Graphique des invendus
        new Chart(document.getElementById('invendusChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '{{ $isFrench ? "Produits invendus" : "Unsold products" }}',
                    data: chartData.dataInvendus,
                    backgroundColor: 'rgba(245, 158, 11, 0.7)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Graphique des avaries
        new Chart(document.getElementById('avariesChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '{{ $isFrench ? "Produits avariés" : "Damaged products" }}',
                    data: chartData.dataAvaries,
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });
    });
</script>
@endsection
