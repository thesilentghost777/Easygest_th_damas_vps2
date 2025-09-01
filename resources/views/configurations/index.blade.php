@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <br>

    <!-- Mobile Container -->
    <div class="md:hidden px-2 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Header -->
                <div class="text-center mb-8">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $isFrench ? 'Gestion des Accès' : 'Access Management' }}
                    </h1>
                    <p class="text-gray-600 text-sm">
                        {{ $isFrench ? 'Contrôlez l\'accès aux demandes' : 'Control access to requests' }}
                    </p>
                </div>

                <!-- Mobile Controls -->
                <div class="space-y-4">
                    <!-- Salaire Control -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-green-500 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-green-800">
                                        {{ $isFrench ? 'Demandes de Salaire' : 'Salary Requests' }}
                                    </h3>
                                    <p class="text-green-600 text-sm">
                                        {{ $isFrench ? 'Statut:' : 'Status:' }}
                                        <span id="salaire-status-mobile" class="font-medium">
                                            {{ $config->flag2 ? ($isFrench ? 'Débloquées' : 'Unlocked') : ($isFrench ? 'Bloquées' : 'Locked') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" id="toggle-salaire-mobile" class="sr-only" {{ $config->flag2 ? 'checked' : '' }}>
                                <div class="block bg-gray-600 w-14 h-8 rounded-full toggle-bg-mobile"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition toggle-dot-mobile"></div>
                            </div>
                        </div>
                        <button onclick="toggleSalaire()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 shadow-md">
                            <span id="salaire-btn-text-mobile">
                                {{ $config->flag2 ? ($isFrench ? 'Bloquer les Demandes' : 'Block Requests') : ($isFrench ? 'Débloquer les Demandes' : 'Unlock Requests') }}
                            </span>
                        </button>
                    </div>

                    <!-- Avance Salaire Control -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-blue-500 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-blue-800">
                                        {{ $isFrench ? 'Avances Salaire' : 'Salary Advances' }}
                                    </h3>
                                    <p class="text-blue-600 text-sm">
                                        {{ $isFrench ? 'Statut:' : 'Status:' }}
                                        <span id="avance-status-mobile" class="font-medium">
                                            {{ $config->flag3 ? ($isFrench ? 'Débloquées' : 'Unlocked') : ($isFrench ? 'Bloquées' : 'Locked') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" id="toggle-avance-mobile" class="sr-only" {{ $config->flag3 ? 'checked' : '' }}>
                                <div class="block bg-gray-600 w-14 h-8 rounded-full toggle-bg-mobile"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition toggle-dot-mobile"></div>
                            </div>
                        </div>
                        <button onclick="toggleAvanceSalaire()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 shadow-md">
                            <span id="avance-btn-text-mobile">
                                {{ $config->flag3 ? ($isFrench ? 'Bloquer les Demandes' : 'Block Requests') : ($isFrench ? 'Débloquer les Demandes' : 'Unlock Requests') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-6">
            <div class="container mx-auto px-4">
                @include('buttons')
                
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        {{ $isFrench ? 'Gestion des Accès aux Demandes' : 'Request Access Management' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Contrôlez l\'accès des employés aux demandes de salaire et d\'avance salaire' : 'Control employee access to salary and salary advance requests' }}
                    </p>
                </div>

                <!-- Controls Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Salaire Control -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-white">
                                        {{ $isFrench ? 'Demandes de Salaire' : 'Salary Requests' }}
                                    </h2>
                                    <p class="text-green-100 text-sm">
                                        {{ $isFrench ? 'Contrôlez l\'accès aux demandes de retrait de salaire' : 'Control access to salary withdrawal requests' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Statut actuel' : 'Current Status' }}</p>
                                    <p id="salaire-status" class="text-lg font-semibold {{ $config->flag2 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $config->flag2 ? ($isFrench ? 'Débloquées' : 'Unlocked') : ($isFrench ? 'Bloquées' : 'Locked') }}
                                    </p>
                                </div>
                                <div class="relative">
                                    <input type="checkbox" id="toggle-salaire" class="sr-only" {{ $config->flag2 ? 'checked' : '' }}>
                                    <div class="block bg-gray-600 w-14 h-8 rounded-full toggle-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition toggle-dot"></div>
                                </div>
                            </div>
                            <button onclick="toggleSalaire()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                <span id="salaire-btn-text">
                                    {{ $config->flag2 ? ($isFrench ? 'Bloquer les Demandes' : 'Block Requests') : ($isFrench ? 'Débloquer les Demandes' : 'Unlock Requests') }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Avance Salaire Control -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-white">
                                        {{ $isFrench ? 'Avances Salaire' : 'Salary Advances' }}
                                    </h2>
                                    <p class="text-blue-100 text-sm">
                                        {{ $isFrench ? 'Contrôlez l\'accès aux demandes d\'avance salaire' : 'Control access to salary advance requests' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Statut actuel' : 'Current Status' }}</p>
                                    <p id="avance-status" class="text-lg font-semibold {{ $config->flag3 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $config->flag3 ? ($isFrench ? 'Débloquées' : 'Unlocked') : ($isFrench ? 'Bloquées' : 'Locked') }}
                                    </p>
                                </div>
                                <div class="relative">
                                    <input type="checkbox" id="toggle-avance" class="sr-only" {{ $config->flag3 ? 'checked' : '' }}>
                                    <div class="block bg-gray-600 w-14 h-8 rounded-full toggle-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition toggle-dot"></div>
                                </div>
                            </div>
                            <button onclick="toggleAvanceSalaire()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                <span id="avance-btn-text">
                                    {{ $config->flag3 ? ($isFrench ? 'Bloquer les Demandes' : 'Block Requests') : ($isFrench ? 'Débloquer les Demandes' : 'Unlock Requests') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br><br>
</div>

<style>
/* Toggle Switch Styles */
.toggle-bg {
    transition: background-color 0.2s;
}
.toggle-bg-mobile {
    transition: background-color 0.2s;
}
.toggle-dot {
    transition: transform 0.2s;
}
.toggle-dot-mobile {
    transition: transform 0.2s;
}

input:checked + .toggle-bg {
    background-color: #10b981;
}
input:checked + .toggle-bg-mobile {
    background-color: #10b981;
}

input:checked + .toggle-bg .toggle-dot {
    transform: translateX(1.5rem);
}
input:checked + .toggle-bg-mobile .toggle-dot-mobile {
    transform: translateX(1.5rem);
}

/* Loading animation */
.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>

<script>
function showToast(message, type = 'success') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} shadow-lg`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function toggleSalaire() {
    const button = document.querySelectorAll('[onclick="toggleSalaire()"]');
    button.forEach(btn => btn.classList.add('loading'));
    
    fetch('{{ route("configurations.toggle-salaire") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status text
            const statusElements = ['salaire-status', 'salaire-status-mobile'];
            const btnTextElements = ['salaire-btn-text', 'salaire-btn-text-mobile'];
            const toggleElements = ['toggle-salaire', 'toggle-salaire-mobile'];
            
            statusElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = data.status ? '{{ $isFrench ? "Débloquées" : "Unlocked" }}' : '{{ $isFrench ? "Bloquées" : "Locked" }}';
                    element.className = data.status ? 'text-lg font-semibold text-green-600' : 'text-lg font-semibold text-red-600';
                }
            });
            
            btnTextElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = data.status ? '{{ $isFrench ? "Bloquer les Demandes" : "Block Requests" }}' : '{{ $isFrench ? "Débloquer les Demandes" : "Unlock Requests" }}';
                }
            });
            
            toggleElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.checked = data.status;
                }
            });
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('{{ $isFrench ? "Erreur de connexion" : "Connection error" }}', 'error');
    })
    .finally(() => {
        button.forEach(btn => btn.classList.remove('loading'));
    });
}

function toggleAvanceSalaire() {
    const button = document.querySelectorAll('[onclick="toggleAvanceSalaire()"]');
    button.forEach(btn => btn.classList.add('loading'));
    
    fetch('{{ route("configurations.toggle-avance-salaire") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status text
            const statusElements = ['avance-status', 'avance-status-mobile'];
            const btnTextElements = ['avance-btn-text', 'avance-btn-text-mobile'];
            const toggleElements = ['toggle-avance', 'toggle-avance-mobile'];
            
            statusElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = data.status ? '{{ $isFrench ? "Débloquées" : "Unlocked" }}' : '{{ $isFrench ? "Bloquées" : "Locked" }}';
                    element.className = data.status ? 'text-lg font-semibold text-green-600' : 'text-lg font-semibold text-red-600';
                }
            });
            
            btnTextElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = data.status ? '{{ $isFrench ? "Bloquer les Demandes" : "Block Requests" }}' : '{{ $isFrench ? "Débloquer les Demandes" : "Unlock Requests" }}';
                }
            });
            
            toggleElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.checked = data.status;
                }
            });
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('{{ $isFrench ? "Erreur de connexion" : "Connection error" }}', 'error');
    })
    .finally(() => {
        button.forEach(btn => btn.classList.remove('loading'));
    });
}
</script>
@endsection
