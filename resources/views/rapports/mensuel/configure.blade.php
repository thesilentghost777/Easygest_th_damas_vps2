@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-600 rounded-2xl shadow-lg mb-6 transform hover:scale-102 transition-all duration-300 animate-fade-in">
            <div class="px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-white">
                        {{ $isFrench ? 'Configuration du rapport mensuel' : 'Monthly Report Configuration' }}
                    </h1>
                    <p class="text-blue-100 text-sm">
                        {{ $isFrench ? 'Personnalisez vos rapports' : 'Customize your reports' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block mb-8 bg-blue-600 rounded-xl shadow-lg transform hover:scale-102 transition-all duration-300">
            <div class="px-6 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        {{ $isFrench ? 'Configuration du rapport mensuel' : 'Monthly Report Configuration' }}
                    </h2>
                    <p class="text-blue-100 mt-1">
                        {{ $isFrench ? 'Personnalisez les éléments qui apparaîtront dans le rapport mensuel' : 'Customize the elements that will appear in the monthly report' }}
                    </p>
                </div>
                @include('buttons')
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-102 transition-all duration-300">
            <form action="{{ route('rapports.mensuel.save-config') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <!-- Sectors Configuration -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $isFrench ? 'Configuration des secteurs' : 'Sectors Configuration' }}
                    </h2>
                    
                    <!-- Production Sector -->
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">
                            {{ $isFrench ? 'Secteur Production (Boulangerie-Pâtisserie)' : 'Production Sector (Bakery-Pastry)' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Catégories de dépenses pour la production' : 'Expense categories for production' }}
                                </label>
                                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    @foreach($categories as $category)
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="production_categories[]" id="prod_cat_{{ $category->id }}" value="{{ $category->id }}" 
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                {{ in_array($category->id, $config->production_categories ?? []) ? 'checked' : '' }}>
                                            <label for="prod_cat_{{ $category->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Utilisateurs pour les versements de production' : 'Users for production payments' }}
                                </label>
                                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    @foreach($usersByRole as $role => $usersInRole)
                                        <div class="mb-2">
                                            <h4 class="font-medium text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $role }}</h4>
                                            @foreach($usersInRole as $user)
                                                <div class="flex items-center ml-2 mb-1">
                                                    <input type="checkbox" name="production_users[]" id="prod_user_{{ $user->id }}" value="{{ $user->id }}" 
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                        {{ in_array($user->id, $config->production_users ?? []) ? 'checked' : '' }}>
                                                    <label for="prod_user_{{ $user->id }}" class="ml-2 block text-sm text-gray-900">
                                                        {{ $user->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- General Store Sector -->
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">
                            {{ $isFrench ? 'Secteur Alimentation' : 'General Store Sector' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Catégories de dépenses pour l\'alimentation' : 'Expense categories for General Store' }}
                                </label>
                                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    @foreach($categories as $category)
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="alimentation_categories[]" id="alim_cat_{{ $category->id }}" value="{{ $category->id }}" 
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                {{ in_array($category->id, $config->alimentation_categories ?? []) ? 'checked' : '' }}>
                                            <label for="alim_cat_{{ $category->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Utilisateurs pour les versements d\'alimentation' : 'Users for General Store payments' }}
                                </label>
                                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    @foreach($usersByRole as $role => $usersInRole)
                                        <div class="mb-2">
                                            <h4 class="font-medium text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $role }}</h4>
                                            @foreach($usersInRole as $user)
                                                <div class="flex items-center ml-2 mb-1">
                                                    <input type="checkbox" name="alimentation_users[]" id="alim_user_{{ $user->id }}" value="{{ $user->id }}" 
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                        {{ in_array($user->id, $config->alimentation_users ?? []) ? 'checked' : '' }}>
                                                    <label for="alim_user_{{ $user->id }}" class="ml-2 block text-sm text-gray-900">
                                                        {{ $user->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $isFrench ? 'Informations complémentaires' : 'Additional Information' }}
                    </h2>
                    
                    <!-- Social Climate -->
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">
                            {{ $isFrench ? 'Climat social' : 'Social Climate' }}
                        </h3>
                        <div class="space-y-4" id="social-climat-container">
                            @if(!empty($config->social_climat))
                                @foreach($config->social_climat as $index => $item)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 social-climat-item border p-3 rounded-md">
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Titre' : 'Title' }}
                                            </label>
                                            <input type="text" name="social_climat[{{ $index }}][title]" value="{{ $item['title'] }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Description' : 'Description' }}
                                            </label>
                                            <textarea name="social_climat[{{ $index }}][description]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $item['description'] }}</textarea>
                                        </div>
                                        <div class="md:col-span-4 text-right">
                                            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                                {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-social-climat" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-indigo-600 bg-white hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-gray-50 active:text-indigo-700 transition ease-in-out duration-150">
                            + {{ $isFrench ? 'Ajouter un élément' : 'Add an element' }}
                        </button>
                    </div>
                    
                    <!-- Major Problems -->
                    <div class="mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">
                            {{ $isFrench ? 'Problèmes majeurs rencontrés' : 'Major problems encountered' }}
                        </h3>
                        <div class="space-y-4" id="major-problems-container">
                            @if(!empty($config->major_problems))
                                @foreach($config->major_problems as $index => $item)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 major-problems-item border p-3 rounded-md">
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Titre du problème' : 'Problem title' }}
                                            </label>
                                            <input type="text" name="major_problems[{{ $index }}][title]" value="{{ $item['title'] }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Description du problème' : 'Problem description' }}
                                            </label>
                                            <textarea name="major_problems[{{ $index }}][description]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $item['description'] }}</textarea>
                                        </div>
                                        <div class="md:col-span-4 text-right">
                                            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                                {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-major-problem" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-indigo-600 bg-white hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-gray-50 active:text-indigo-700 transition ease-in-out duration-150">
                            + {{ $isFrench ? 'Ajouter un problème' : 'Add a problem' }}
                        </button>
                    </div>
                    
                    <!-- Recommendations -->
                    <div>
                        <h3 class="text-md font-medium text-gray-700 mb-3">
                            {{ $isFrench ? 'Recommandations des employés ou clients' : 'Employee or client recommendations' }}
                        </h3>
                        <div class="space-y-4" id="recommendations-container">
                            @if(!empty($config->recommendations))
                                @foreach($config->recommendations as $index => $item)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 recommendations-item border p-3 rounded-md">
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Source (employé/client)' : 'Source (employee/client)' }}
                                            </label>
                                            <input type="text" name="recommendations[{{ $index }}][source]" value="{{ $item['source'] }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $isFrench ? 'Recommandation' : 'Recommendation' }}
                                            </label>
                                            <textarea name="recommendations[{{ $index }}][content]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ $item['content'] }}</textarea>
                                        </div>
                                        <div class="md:col-span-4 text-right">
                                            <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                                {{ $isFrench ? 'Supprimer' : 'Remove' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-recommendation" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-indigo-600 bg-white hover:text-indigo-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-gray-50 active:text-indigo-700 transition ease-in-out duration-150">
                            + {{ $isFrench ? 'Ajouter une recommandation' : 'Add a recommendation' }}
                        </button>
                    </div>
                </div>

                <!-- Tax Configuration -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $isFrench ? 'Configuration fiscale' : 'Tax Configuration' }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Taux d\'imposition (%)' : 'Tax rate (%)' }}
                            </label>
                            <input type="number" name="tax_rate" id="tax_rate" min="0" max="100" step="0.01" 
                                value="{{ $config->tax_rate ?? 0 }}" 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $isFrench ? 'Utilisé pour calculer les taxes dans le rapport' : 'Used to calculate taxes in the report' }}
                            </p>
                        </div>
                        <div>
                            <label for="vat_rate" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $isFrench ? 'Taux de TVA (%)' : 'VAT rate (%)' }}
                            </label>
                            <input type="number" name="vat_rate" id="vat_rate" min="0" max="100" step="0.01" 
                                value="{{ $config->vat_rate ?? 18 }}" 
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $isFrench ? 'Utilisé pour calculer la TVA dans le rapport' : 'Used to calculate VAT in the report' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                        {{ $isFrench ? 'Enregistrer la configuration' : 'Save Configuration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let socialClimatIndex = {{ !empty($config->social_climat) ? count($config->social_climat) : 0 }};
    let majorProblemsIndex = {{ !empty($config->major_problems) ? count($config->major_problems) : 0 }};
    let recommendationsIndex = {{ !empty($config->recommendations) ? count($config->recommendations) : 0 }};

    // Social climate elements management
    document.getElementById('add-social-climat').addEventListener('click', function() {
        const container = document.getElementById('social-climat-container');
        const newItem = document.createElement('div');
        newItem.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 social-climat-item border p-3 rounded-md';
        newItem.innerHTML = `
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Titre' : 'Title' }}</label>
                <input type="text" name="social_climat[${socialClimatIndex}][title]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Description' : 'Description' }}</label>
                <textarea name="social_climat[${socialClimatIndex}][description]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="md:col-span-4 text-right">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                    {{ $isFrench ? 'Supprimer' : 'Remove' }}
                </button>
            </div>
        `;
        container.appendChild(newItem);
        socialClimatIndex++;
        
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            container.removeChild(newItem);
        });
    });
    
    document.querySelectorAll('.social-climat-item .remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const item = this.closest('.social-climat-item');
            item.parentNode.removeChild(item);
        });
    });

    // Major problems elements management
    document.getElementById('add-major-problem').addEventListener('click', function() {
        const container = document.getElementById('major-problems-container');
        const newItem = document.createElement('div');
        newItem.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 major-problems-item border p-3 rounded-md';
        newItem.innerHTML = `
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Titre du problème' : 'Problem title' }}</label>
                <input type="text" name="major_problems[${majorProblemsIndex}][title]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Description du problème' : 'Problem description' }}</label>
                <textarea name="major_problems[${majorProblemsIndex}][description]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="md:col-span-4 text-right">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                    {{ $isFrench ? 'Supprimer' : 'Remove' }}
                </button>
            </div>
        `;
        container.appendChild(newItem);
        majorProblemsIndex++;
        
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            container.removeChild(newItem);
        });
    });
    
    document.querySelectorAll('.major-problems-item .remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const item = this.closest('.major-problems-item');
            item.parentNode.removeChild(item);
        });
    });

    // Recommendations elements management
    document.getElementById('add-recommendation').addEventListener('click', function() {
        const container = document.getElementById('recommendations-container');
        const newItem = document.createElement('div');
        newItem.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 recommendations-item border p-3 rounded-md';
        newItem.innerHTML = `
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Source (employé/client)' : 'Source (employee/client)' }}</label>
                <input type="text" name="recommendations[${recommendationsIndex}][source]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $isFrench ? 'Recommandation' : 'Recommendation' }}</label>
                <textarea name="recommendations[${recommendationsIndex}][content]" rows="2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="md:col-span-4 text-right">
                <button type="button" class="remove-item text-red-600 hover:text-red-800">
                    {{ $isFrench ? 'Supprimer' : 'Remove' }}
                </button>
            </div>
        `;
        container.appendChild(newItem);
        recommendationsIndex++;
        
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            container.removeChild(newItem);
        });
    });
    
    document.querySelectorAll('.recommendations-item .remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const item = this.closest('.recommendations-item');
            item.parentNode.removeChild(item);
        });
    });
});
</script>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
}
</style>
@endsection
