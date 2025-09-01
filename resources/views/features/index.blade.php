@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- Mobile Header -->
        <div class="mb-6">
            @include('buttons')
            <div class="mt-4 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 animate-fade-in">
                    {{ $isFrench ? 'Gestion des fonctionnalités' : 'Features Management' }}
                </h1>
                <p class="text-gray-600 animate-fade-in delay-100">
                    {{ $isFrench ? 'Configurez les modules de votre application' : 'Configure your application modules' }}
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm animate-slide-in-right">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <a href="{{ route('features.enable-all') }}" 
               class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2 animate-scale-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ $isFrench ? 'Activer toutes les fonctionnalités' : 'Enable all features' }}</span>
            </a>
        </div>

        <!-- Main Features Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" x-data="{ activeTab: 'all_employees' }">
            
            <!-- Mobile Tab Navigation -->
            <div class="block md:hidden">
                <select x-model="activeTab" class="w-full p-4 bg-blue-600 text-white border-none rounded-t-2xl text-lg font-medium">
                    @foreach($featuresByCategory->keys() as $category)
                        <option value="{{ $category }}">{{ $categoryTranslations[$category] ?? ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Desktop Tab Navigation -->
            <div class="hidden md:flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
                @foreach($featuresByCategory->keys() as $index => $category)
                    <button @click="activeTab = '{{ $category }}'" 
                            :class="{ 'border-b-2 border-blue-600 text-blue-600 bg-white': activeTab === '{{ $category }}', 'text-gray-500 hover:text-gray-700': activeTab !== '{{ $category }}' }"
                            class="py-4 px-6 font-medium text-sm focus:outline-none whitespace-nowrap transition-all duration-200 animate-fade-in"
                            style="animation-delay: {{ $index * 0.1 }}s">
                        {{ $categoryTranslations[$category] ?? ucfirst($category) }}
                    </button>
                @endforeach
            </div>

            <!-- Tab Content -->
            @foreach($featuresByCategory as $category => $features)
                <div x-show="activeTab === '{{ $category }}'" class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-4 md:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $categoryTranslations[$category] ?? ucfirst($category) }}
                        </h2>
                        <a href="{{ route('features.disable-category', $category) }}" 
                           class="text-red-600 hover:text-red-800 text-sm font-medium px-4 py-2 border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-200"
                           onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir désactiver toutes les fonctionnalités de cette catégorie?' : 'Are you sure you want to disable all features in this category?' }}')">
                            {{ $isFrench ? 'Désactiver toute la catégorie' : 'Disable entire category' }}
                        </a>
                    </div>
                    
                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($features as $index => $feature)
                            <div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-r from-gray-50 to-white hover:shadow-lg transition-all duration-300 animate-scale-in" 
                                 style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="flex items-start justify-between space-x-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $feature->name }}
                                        </h3>
                                        @if($feature->description)
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $feature->description }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Toggle Switch -->
                                    <div class="flex-shrink-0">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   class="sr-only peer" 
                                                   data-feature-id="{{ $feature->id }}" 
                                                   {{ $feature->active ? 'checked' : '' }}
                                                   onchange="toggleFeatureStatus(this)">
                                            <div class="w-14 h-7 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function toggleFeatureStatus(checkbox) {
        const featureId = checkbox.getAttribute('data-feature-id');
        const active = checkbox.checked;
        
        // Add loading state
        checkbox.disabled = true;
        
        fetch('{{ route("features.toggle-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ feature_id: featureId, active: active })
        })
        .then(response => response.json())
        .then(data => {
            checkbox.disabled = false;
            
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                checkbox.checked = !checkbox.checked;
                showToast('{{ $isFrench ? "Erreur" : "Error" }}: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.disabled = false;
            checkbox.checked = !checkbox.checked;
            showToast('{{ $isFrench ? "Une erreur est survenue lors de la mise à jour du statut" : "An error occurred while updating the status" }}', 'error');
        });
    }
    
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const icon = type === 'success' 
            ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        
        toast.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-lg transition-all duration-500 transform translate-x-full opacity-0 mb-2 flex items-center space-x-3 max-w-sm`;
        toast.innerHTML = `
            <div class="flex-shrink-0">${icon}</div>
            <div class="flex-1 text-sm font-medium">${message}</div>
        `;
        
        container.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Animate out and remove
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }, 3500);
    }
</script>

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

@keyframes slide-in-right {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.5s ease-out;
}

.animate-slide-in-right {
    animation: slide-in-right 0.5s ease-out;
}

.delay-100 {
    animation-delay: 0.1s;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
