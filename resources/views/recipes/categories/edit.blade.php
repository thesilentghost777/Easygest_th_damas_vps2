@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('buttons')
        
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4">
                <h1 class="text-xl font-bold text-white">
                    {{ $isFrench ? 'Modifier la Catégorie' : 'Edit Category' }}
                </h1>
                <p class="text-blue-100 text-sm mt-1">
                    {{ $category->name }}
                </p>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5">
                <h2 class="text-2xl font-bold text-white">
                    {{ $isFrench ? 'Modifier la Catégorie' : 'Edit Category' }}
                </h2>
                <p class="text-blue-100 mt-1">
                    {{ $isFrench ? 'Mettre à jour les informations de' : 'Update information for' }} "{{ $category->name }}"
                </p>
            </div>
        </div>

        <!-- Mobile Form -->
        <div class="md:hidden">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-slide-in-right">
                <div class="px-6 py-6">
                    <form action="{{ route('recipe.categories.update', $category) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="name_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Nom de la catégorie' : 'Category Name' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name_mobile" value="{{ old('name', $category->name) }}" required
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 h-14 text-lg font-medium transform hover:scale-102 transition-all duration-200"
                                    placeholder="{{ $isFrench ? 'Ex: Pains, Viennoiseries...' : 'Ex: Breads, Pastries...' }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="transform hover:scale-105 transition-all duration-300">
                            <label for="description_mobile" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Description' : 'Description' }}
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <textarea name="description" id="description_mobile" rows="4"
                                    class="pl-10 w-full border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-gray-50 text-lg transform hover:scale-102 transition-all duration-200"
                                    placeholder="{{ $isFrench ? 'Description de la catégorie...' : 'Category description...' }}">{{ old('description', $category->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col space-y-3 pt-6">
                            <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Desktop Form -->
        <div class="hidden md:block">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200 transform hover:scale-102 transition-all duration-300">
                <div class="p-8">
                    <form action="{{ route('recipe.categories.update', $category) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="name" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Nom de la catégorie' : 'Category Name' }} *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                    class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white font-medium"
                                    placeholder="{{ $isFrench ? 'Ex: Pains, Viennoiseries, Gâteaux...' : 'Ex: Breads, Pastries, Cakes...' }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                            <label for="description" class="block text-lg font-semibold text-gray-700 mb-3">
                                {{ $isFrench ? 'Description' : 'Description' }}
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <textarea name="description" id="description" rows="4"
                                    class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-base border-gray-300 rounded-lg p-3 bg-white"
                                    placeholder="{{ $isFrench ? 'Description détaillée de la catégorie...' : 'Detailed category description...' }}">{{ old('description', $category->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm font-medium text-white bg-red-500 p-2 rounded-lg">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-10">
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 rounded-xl font-bold text-base text-white uppercase tracking-wider hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-lg transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isFrench ? 'Mettre à jour' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
