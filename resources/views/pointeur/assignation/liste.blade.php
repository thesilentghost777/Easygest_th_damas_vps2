@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
   
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="lg:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm opacity-0 invisible transition-all duration-300">
        <div class="fixed right-0 top-0 h-full w-80 max-w-[80vw] bg-white shadow-xl transform translate-x-full transition-transform duration-300">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $isFrench ? 'Menu' : 'Menu' }}
                    </h3>
                    <button id="closeMobileMenu" class="p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('pointeur.workspace') }}" 
                   class="flex items-center p-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                </a>
                <a href="{{ route('pointeur.assignation.create') }}" 
                   class="flex items-center p-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-md">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ $isFrench ? 'Nouvelle assignation' : 'New Assignment' }}
                </a>
                
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden lg:block container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                @include('buttons')
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $isFrench ? 'Liste des assignations aux vendeurs' : 'Seller Assignments List' }}
                </h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pointeur.workspace') }}" 
                   class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-300 transition-all duration-200 shadow-md hover:shadow-lg">
                    {{ $isFrench ? 'Tableau de bord' : 'Dashboard' }}
                </a>
                <a href="{{ route('pointeur.assignation.create') }}" 
                   class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    {{ $isFrench ? 'Nouvelle assignation' : 'New Assignment' }}
                </a>
               
            </div>
        </div>
    </div>

    <!-- Mobile Floating Action Buttons -->
    <div class="lg:hidden fixed bottom-6 right-6 z-30 flex flex-col space-y-3">
       
        <a href="{{ route('pointeur.assignation.create') }}" 
           class="bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 hover:scale-110 active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </a>
    </div>

    <!-- Main Content -->
    <div class="px-4 pb-24 lg:pb-8 lg:container lg:mx-auto lg:px-6">
        <div class="bg-white rounded-t-3xl lg:rounded-2xl shadow-xl lg:shadow-lg overflow-hidden animate-slideInUp">
            <!-- Header Section -->
            <div class="p-6 lg:p-8 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center">
                    <div class="mb-4 lg:mb-0">
                        <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 mb-2">
                            {{ $isFrench ? 'Assignations récentes' : 'Recent Assignments' }}
                        </h2>
                        <p class="text-gray-600 text-sm lg:text-base">
                            {{ $isFrench ? 'Suivi des assignations de produits aux vendeurs' : 'Track product assignments to sellers' }}
                        </p>
                    </div>
                    
                    <!-- Mobile Stats -->
                    <div class="lg:hidden grid grid-cols-3 gap-4 mt-4">
                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $assignations->total() }}</div>
                            <div class="text-xs text-blue-600">{{ $isFrench ? 'Total' : 'Total' }}</div>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $assignations->where('status', 'en_attente')->count() }}
                            </div>
                            <div class="text-xs text-yellow-600">{{ $isFrench ? 'En attente' : 'Pending' }}</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $assignations->where('status', 'confirmé')->count() }}
                            </div>
                            <div class="text-xs text-green-600">{{ $isFrench ? 'Confirmés' : 'Confirmed' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Vendeur' : 'Seller' }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité envoyée' : 'Sent Quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Quantité confirmée' : 'Confirmed Quantity' }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($assignations as $assignation)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignation->created_at->format('d/m/Y') }}
                                    <div class="text-xs text-gray-500">{{ $assignation->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($assignation->vendeur->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $assignation->vendeur->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignation->produitRecu->produit->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $assignation->quantite_recue }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assignation->quantite_confirmee ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($assignation->status === 'en_attente')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $isFrench ? 'En attente' : 'Pending' }}
                                        </span>
                                    @elseif ($assignation->status === 'confirmé')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $isFrench ? 'Confirmé' : 'Confirmed' }}
                                        </span>
                                    @elseif ($assignation->status === 'rejeté')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $isFrench ? 'Rejeté' : 'Rejected' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-gray-500 text-sm">
                                            {{ $isFrench ? 'Aucune assignation trouvée' : 'No assignments found' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden">
                @forelse ($assignations as $assignation)
                    <div class="border-b border-gray-100 p-6 hover:bg-gray-50 transition-colors duration-200 animate-fadeIn">
                        <!-- Mobile Card Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ substr($assignation->vendeur->name, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $assignation->vendeur->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $assignation->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            @if ($assignation->status === 'en_attente')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $isFrench ? 'En attente' : 'Pending' }}
                                </span>
                            @elseif ($assignation->status === 'confirmé')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $isFrench ? 'Confirmé' : 'Confirmed' }}
                                </span>
                            @elseif ($assignation->status === 'rejeté')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $isFrench ? 'Rejeté' : 'Rejected' }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">
                                {{ $assignation->produitRecu->produit->nom }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $isFrench ? 'Quantité envoyée' : 'Sent Quantity' }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ $assignation->quantite_recue }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $isFrench ? 'Quantité confirmée' : 'Confirmed Quantity' }}
                                    </p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $assignation->quantite_confirmee ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                       
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucune assignation' : 'No Assignments' }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-6">
                                {{ $isFrench ? 'Commencez par créer votre première assignation' : 'Start by creating your first assignment' }}
                            </p>
                            <a href="{{ route('pointeur.assignation.create') }}" 
                               class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                {{ $isFrench ? 'Créer une assignation' : 'Create Assignment' }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($assignations->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $assignations->links() }}
                </div>
            @endif
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

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(300px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slideInUp {
    animation: slideInUp 0.5s ease-out;
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-slideInRight {
    animation: slideInRight 0.3s ease-out;
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

/* Mobile menu animation */
#mobileMenu.show {
    opacity: 1;
    visibility: visible;
}

#mobileMenu.show > div {
    transform: translateX(0);
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        
        function openMobileMenu() {
            mobileMenu.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenuHandler() {
            mobileMenu.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }
        
        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', closeMobileMenuHandler);
        }
        
        // Close menu when clicking overlay
        mobileMenu.addEventListener('click', function(e) {
            if (e.target === mobileMenu) {
                closeMobileMenuHandler();
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('show')) {
                closeMobileMenuHandler();
            }
        });
        
        // Animation observer for mobile cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-fadeIn');
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1
        });
        
        // Observe mobile cards
        document.querySelectorAll('.lg\\:hidden .border-b').forEach((card) => {
            observer.observe(card);
        });
        
        // Pull to refresh functionality (mobile)
        let startY = 0;
        let currentY = 0;
        let isPulling = false;
        const pullThreshold = 80;
        
        if (window.innerWidth < 1024) {
            document.addEventListener('touchstart', function(e) {
                startY = e.touches[0].pageY;
                isPulling = window.scrollY === 0;
            });
            
            document.addEventListener('touchmove', function(e) {
                if (!isPulling) return;
                
                currentY = e.touches[0].pageY;
                const pullDistance = currentY - startY;
                
                if (pullDistance > 0 && pullDistance < pullThreshold * 2) {
                    e.preventDefault();
                    
                    const header = document.querySelector('.lg\\:hidden .sticky');
                    if (header) {
                        header.style.transform = `translateY(${Math.min(pullDistance / 3, 20)}px)`;
                        header.style.opacity = Math.max(0.7, 1 - pullDistance / 200);
                    }
                }
            });
            
            document.addEventListener('touchend', function(e) {
                if (!isPulling) return;
                
                const pullDistance = currentY - startY;
                const header = document.querySelector('.lg\\:hidden .sticky');
                
                if (header) {
                    header.style.transform = '';
                    header.style.opacity = '';
                }
                
                if (pullDistance > pullThreshold) {
                    // Refresh page
                    window.location.reload();
                }
                
                isPulling = false;
            });
        }
        
        // Status badge animation
        document.querySelectorAll('[class*="bg-yellow-100"], [class*="bg-green-100"], [class*="bg-red-100"]').forEach((badge) => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Smooth scroll for mobile FAB
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Loading animation for links
        document.querySelectorAll('a:not([href^="#"])').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.href && !this.target) {
                    const spinner = document.createElement('div');
                    spinner.innerHTML = `
                        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-2xl shadow-xl">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(spinner);
                }
            });
        });
    });
</script>
@endsection