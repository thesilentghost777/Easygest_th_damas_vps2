@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 py-4 md:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile-First Header -->
        @include('buttons')

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-blue-800 mb-4 md:mb-0">
                {{ $isFrench ? 'Gestion des Catégories' : 'Category Management' }}
            </h1>
        </div>

        <!-- Mobile-First Info Card -->
        <div class="mobile-card bg-blue-50 border-l-4 border-blue-400 rounded-2xl md:rounded-lg p-4 md:p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="mdi mdi-information text-blue-400 text-2xl md:text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm md:text-base text-blue-700 mb-3">
                        {{ $isFrench ? 'Les catégories vous permettent de classer vos transactions pour un meilleur suivi de vos finances.' : 'Categories allow you to classify your transactions for better financial tracking.' }}
                    </p>
                    <ul class="list-disc list-inside text-sm text-blue-600 space-y-2">
                        <li><span class="font-semibold">{{ $isFrench ? 'Matière première :' : 'Raw Materials:' }}</span> {{ $isFrench ? 'Achats de farine, huile, eau...' : 'Flour, oil, water purchases...' }}</li>
                        <li><span class="font-semibold">{{ $isFrench ? 'Réparation matériel :' : 'Equipment Repair:' }}</span> {{ $isFrench ? 'Maintenance des machines...' : 'Machine maintenance...' }}</li>
                        <li><span class="font-semibold">{{ $isFrench ? 'Transport :' : 'Transport:' }}</span> {{ $isFrench ? 'Frais de transport, carburant...' : 'Transport fees, fuel...' }}</li>
                        <li><span class="font-semibold">{{ $isFrench ? 'Ventes :' : 'Sales:' }}</span> {{ $isFrench ? 'Revenus des produits vendus' : 'Revenue from sold products' }}</li>
                        <li><span class="font-semibold">{{ $isFrench ? 'Salaire :' : 'Salary:' }}</span> {{ $isFrench ? 'Paiements des employés' : 'Employee payments' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile-First Add Category Form -->
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-md p-4 md:p-6 mb-6">
            <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">
                {{ $isFrench ? 'Ajouter une Catégorie' : 'Add Category' }}
            </h2>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex flex-col md:flex-row gap-3">
                    <input type="text" 
                           name="name" 
                           placeholder="{{ $isFrench ? 'Nom de la nouvelle catégorie...' : 'New category name...' }}" 
                           required 
                           class="mobile-input flex-1 px-4 py-3 md:py-2 border border-gray-300 rounded-2xl md:rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" 
                            class="mobile-btn px-6 py-3 md:py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl md:rounded-lg shadow transition-all duration-300 flex items-center justify-center">
                        <i class="mdi mdi-plus mr-2 text-xl md:text-base"></i>
                        {{ $isFrench ? 'Ajouter' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories List -->
        <div class="mobile-card bg-white rounded-2xl md:rounded-lg shadow-md overflow-hidden">
            <!-- Mobile Cards View - Visible uniquement sur mobile -->
            <div class="block md:hidden p-4">
                <div class="space-y-4">
                    @forelse($categories as $category)
                        <div class="mobile-card bg-gray-50 rounded-xl p-4 hover:shadow-md transition-all duration-300"
                             x-data="{
                                editing: false,
                                name: '{{ $category->name }}',
                                originalName: '{{ $category->name }}',
                                cancelEdit() {
                                    this.editing = false;
                                    this.name = this.originalName;
                                },
                                submitForm() {
                                    this.$refs.hiddenInput.value = this.name;
                                    this.$refs.updateForm.submit();
                                }
                            }">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <div x-show="!editing">
                                        <h3 class="text-lg font-semibold text-gray-800" x-text="name"></h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $isFrench ? 'Catégorie de transaction' : 'Transaction category' }}
                                        </p>
                                    </div>
                                    <div x-show="editing" class="space-y-3">
                                        <input type="text"
                                               x-model="name"
                                               @keydown.enter.prevent="submitForm()"
                                               @keydown.escape.window="cancelEdit()"
                                               @click.away="cancelEdit()"
                                               class="mobile-input w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <div class="flex gap-2">
                                            <button type="button" 
                                                    @click="submitForm()" 
                                                    class="flex-1 px-3 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center">
                                                <i class="mdi mdi-check mr-2"></i>
                                                {{ $isFrench ? 'Sauvegarder' : 'Save' }}
                                            </button>
                                            <button type="button" 
                                                    @click="cancelEdit()" 
                                                    class="flex-1 px-3 py-2 bg-gray-400 text-white rounded-xl hover:bg-gray-500 transition-colors flex items-center justify-center">
                                                <i class="mdi mdi-close mr-2"></i>
                                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <form x-ref="updateForm" action="{{ route('categories.update', $category) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" x-ref="hiddenInput" :value="name">
                                    </form>
                                </div>
                                <div class="flex items-center space-x-2 ml-3" x-show="!editing">
                                    <button @click="editing = true" 
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                        <i class="mdi mdi-pencil text-lg"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette catégorie ?' : 'Are you sure you want to delete this category?' }}')">
                                            <i class="mdi mdi-delete text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mobile-card bg-gray-50 rounded-xl p-6 text-center">
                            <i class="mdi mdi-folder-outline text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">{{ $isFrench ? 'Aucune catégorie trouvée' : 'No categories found' }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ $isFrench ? 'Ajoutez votre première catégorie ci-dessus' : 'Add your first category above' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop Table View - Visible uniquement sur desktop -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Nom' : 'Name' }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors"
                            x-data="{
                                editing: false,
                                name: '{{ $category->name }}',
                                originalName: '{{ $category->name }}',
                                cancelEdit() {
                                    this.editing = false;
                                    this.name = this.originalName;
                                },
                                submitForm() {
                                    this.$refs.hiddenInput.value = this.name;
                                    this.$refs.updateForm.submit();
                                }
                            }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span x-show="!editing" x-text="name" class="text-gray-900 font-medium"></span>
                                <div x-show="editing" class="flex items-center gap-2">
                                    <input type="text"
                                           x-model="name"
                                           @keydown.enter.prevent="submitForm()"
                                           @keydown.escape.window="cancelEdit()"
                                           @click.away="cancelEdit()"
                                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <button type="button" @click="submitForm()" class="text-green-600 hover:text-green-900">
                                        <i class="mdi mdi-check text-lg"></i>
                                    </button>
                                    <button type="button" @click="cancelEdit()" class="text-red-600 hover:text-red-900">
                                        <i class="mdi mdi-close text-lg"></i>
                                    </button>
                                </div>
                                <form x-ref="updateForm" action="{{ route('categories.update', $category) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" x-ref="hiddenInput" :value="name">
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editing = true" x-show="!editing" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="mdi mdi-pencil text-lg"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette catégorie ?' : 'Are you sure you want to delete this category?' }}')">
                                        <i class="mdi mdi-delete text-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                {{ $isFrench ? 'Aucune catégorie trouvée' : 'No categories found' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add touch feedback for mobile cards
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mobile-card').forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.1s ease';
        });
        
        card.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Add form submission animation
    document.querySelector('form[action*="categories.store"]').addEventListener('submit', function() {
        const button = this.querySelector('button[type="submit"]');
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    });
});
</script>
@endpush
@endsection