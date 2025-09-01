@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Buttons -->
        @include('buttons')

        <!-- Mobile Header with Animation -->
        <div class="lg:hidden mb-6 animate-fade-in">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
                <h1 class="text-2xl font-bold mb-2">
                    {{ $isFrench ? 'Tableau de Bord' : 'Dashboard' }}
                </h1>
                <p class="text-blue-100 text-sm">
                    {{ $isFrench ? 'Aperçu de vos performances' : 'Overview of your performance' }}
                </p>
            </div>
        </div>

        <!-- Commandes Section -->
        <div class="mb-8">
            <!-- Desktop Title -->
            <h2 class="hidden lg:block text-2xl font-bold text-gray-900 mb-6">
                {{ $isFrench ? 'Statistiques des Commandes' : 'Order Statistics' }}
            </h2>

            <!-- Mobile Section Title with Animation -->
            <div class="lg:hidden mb-4 animate-slide-up">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-1 h-6 bg-blue-600 rounded-full mr-3"></div>
                    {{ $isFrench ? 'Commandes' : 'Orders' }}
                </h2>
            </div>

            <!-- Cards - Mobile First Design -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
                <!-- Total Orders Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Total Commandes' : 'Total Orders' }}
                            </h3>
                            <p class="text-2xl lg:text-3xl font-bold text-blue-600">{{ $orderStats['total'] }}</p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validated Orders Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Commandes Validées' : 'Validated Orders' }}
                            </h3>
                            <p class="text-2xl lg:text-3xl font-bold text-green-600">{{ $orderStats['validated'] }}</p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up sm:col-span-2 lg:col-span-1" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Commandes en Attente' : 'Pending Orders' }}
                            </h3>
                            <p class="text-2xl lg:text-3xl font-bold text-yellow-600">{{ $orderStats['pending'] }}</p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-6">
                <!-- Monthly Evolution Chart -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-4 lg:p-6 animate-fade-in-up" style="animation-delay: 0.4s">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <div class="w-3 h-3 bg-blue-600 rounded-full mr-2 lg:hidden"></div>
                        {{ $isFrench ? 'Évolution Mensuelle' : 'Monthly Evolution' }}
                    </h3>
                    <div class="relative h-64 lg:h-auto">
                        <canvas id="monthlyOrdersChart" class="max-h-64"></canvas>
                    </div>
                </div>

                <!-- Category Distribution Chart -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-4 lg:p-6 animate-fade-in-up" style="animation-delay: 0.5s">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <div class="w-3 h-3 bg-green-600 rounded-full mr-2 lg:hidden"></div>
                        {{ $isFrench ? 'Distribution par Catégorie' : 'Category Distribution' }}
                    </h3>
                    <div class="relative h-64 lg:h-auto">
                        <canvas id="categoryPieChart" class="max-h-64"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sacs Section -->
        <div>
            <!-- Desktop Title -->
            <h2 class="hidden lg:block text-2xl font-bold text-gray-900 mb-6">
                {{ $isFrench ? 'Statistiques des Sacs' : 'Bag Statistics' }}
            </h2>

            <!-- Mobile Section Title with Animation -->
            <div class="lg:hidden mb-4 animate-slide-up">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-1 h-6 bg-blue-600 rounded-full mr-3"></div>
                    {{ $isFrench ? 'Sacs' : 'Bags' }}
                </h2>
            </div>

            <!-- Cards - Mobile First Design -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
                <!-- Total Bags Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.6s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Total Sacs' : 'Total Bags' }}
                            </h3>
                            <p class="text-2xl lg:text-3xl font-bold text-blue-600">{{ $bagStats['totalBags'] }}</p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Stock Value Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Valeur Totale Stock' : 'Total Stock Value' }}
                            </h3>
                            <p class="text-xl lg:text-2xl xl:text-3xl font-bold text-green-600">
                                {{ number_format($bagStats['totalValue']->total_value, 2) }} XAF
                            </p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Card -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-6 transform transition-all duration-300 hover:scale-105 lg:hover:scale-100 hover:shadow-lg animate-fade-in-up sm:col-span-2 lg:col-span-1" style="animation-delay: 0.8s">
                    <div class="flex items-center justify-between lg:block">
                        <div>
                            <h3 class="text-sm lg:text-lg font-medium text-gray-900 mb-1 lg:mb-0">
                                {{ $isFrench ? 'Stock Faible' : 'Low Stock' }}
                            </h3>
                            <p class="text-2xl lg:text-3xl font-bold text-red-600">{{ $bagStats['lowStock'] }}</p>
                        </div>
                        <div class="lg:hidden">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-6">
                <!-- Stock Movement Chart -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-4 lg:p-6 animate-fade-in-up" style="animation-delay: 0.9s">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <div class="w-3 h-3 bg-green-600 rounded-full mr-2 lg:hidden"></div>
                        {{ $isFrench ? 'Mouvements de Stock' : 'Stock Movement' }}
                    </h3>
                    <div class="relative h-64 lg:h-auto">
                        <canvas id="stockMovementChart" class="max-h-64"></canvas>
                    </div>
                </div>

                <!-- Top Bags Chart -->
                <div class="bg-white rounded-xl lg:rounded-lg shadow-md lg:shadow p-4 lg:p-6 animate-fade-in-up" style="animation-delay: 1s">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <div class="w-3 h-3 bg-blue-600 rounded-full mr-2 lg:hidden"></div>
                        {{ $isFrench ? 'Top 5 Sacs les Plus Vendus' : 'Top 5 Best Selling Bags' }}
                    </h3>
                    <div class="relative h-64 lg:h-auto">
                        <canvas id="topBagsChart" class="max-h-64"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for Mobile Animations -->
<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out both;
}

.animate-slide-up {
    animation: slideUp 0.4s ease-out;
}

/* Mobile specific improvements */
@media (max-width: 1023px) {
    .shadow-md {
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Touch improvements for mobile */
@media (hover: none) and (pointer: coarse) {
    .hover\:scale-105:hover {
        transform: scale(1.02);
    }
    
    .hover\:shadow-lg:hover {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Common chart options for mobile
    const mobileChartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            x: {
                display: window.innerWidth > 640,
                ticks: {
                    maxTicksLimit: window.innerWidth > 640 ? 10 : 5,
                    font: {
                        size: window.innerWidth > 640 ? 12 : 10
                    }
                }
            },
            y: {
                ticks: {
                    font: {
                        size: window.innerWidth > 640 ? 12 : 10
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: window.innerWidth > 640,
                labels: {
                    font: {
                        size: window.innerWidth > 640 ? 12 : 10
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: {
                    size: 12
                },
                bodyFont: {
                    size: 11
                }
            }
        }
    };

    // Monthly Orders Chart
    new Chart(document.getElementById('monthlyOrdersChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($orderStats['monthlyOrders']->pluck('month')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Commandes par mois" : "Orders per month" }}',
                data: {!! json_encode($orderStats['monthlyOrders']->pluck('count')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: window.innerWidth > 640 ? 4 : 2,
                pointHoverRadius: window.innerWidth > 640 ? 6 : 4,
                borderWidth: 2
            }]
        },
        options: {
            ...mobileChartOptions,
            plugins: {
                ...mobileChartOptions.plugins,
                legend: {
                    display: window.innerWidth > 640
                }
            }
        }
    });

    // Category Distribution Pie Chart
    new Chart(document.getElementById('categoryPieChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($orderStats['categoryDistribution']->pluck('categorie')) !!},
            datasets: [{
                data: {!! json_encode($orderStats['categoryDistribution']->pluck('count')) !!},
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                borderWidth: 0,
                hoverOffset: window.innerWidth > 640 ? 10 : 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: window.innerWidth > 640 ? 'right' : 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: window.innerWidth > 640 ? 20 : 10,
                        font: {
                            size: window.innerWidth > 640 ? 12 : 10
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 11 }
                }
            }
        }
    });

    // Stock Movement Chart
    new Chart(document.getElementById('stockMovementChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($bagStats['transactions']->pluck('transaction_date')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Mouvement net de stock" : "Net stock movement" }}',
                data: {!! json_encode($bagStats['transactions']->pluck('net_quantity')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: window.innerWidth > 640 ? 4 : 2,
                pointHoverRadius: window.innerWidth > 640 ? 6 : 4,
                borderWidth: 2
            }]
        },
        options: {
            ...mobileChartOptions,
            plugins: {
                ...mobileChartOptions.plugins,
                legend: {
                    display: window.innerWidth > 640
                }
            }
        }
    });

    // Top Bags Chart
    new Chart(document.getElementById('topBagsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($bagStats['mostPopular']->pluck('name')) !!},
            datasets: [{
                label: '{{ $isFrench ? "Quantité vendue" : "Quantity sold" }}',
                data: {!! json_encode($bagStats['mostPopular']->pluck('total_sold')) !!},
                backgroundColor: 'rgb(59, 130, 246)',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            ...mobileChartOptions,
            indexAxis: window.innerWidth > 640 ? 'y' : 'x',
            plugins: {
                ...mobileChartOptions.plugins,
                legend: {
                    display: window.innerWidth > 640
                }
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        // Redraw charts on resize if needed
        Chart.helpers.each(Chart.instances, function(instance) {
            instance.resize();
        });
    });
});
</script>
@endpush
@endsection