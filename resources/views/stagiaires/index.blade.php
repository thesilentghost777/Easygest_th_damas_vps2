@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
   <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                <!-- Mobile Notice -->
                <div class="bg-amber-50 rounded-2xl p-4 border-l-4 border-amber-500 mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-amber-800 font-semibold">
                                {{ $isFrench ? 'Fonctionnalité PC uniquement' : 'Desktop-only Feature' }}
                            </h3>
                            <p class="text-amber-700 text-sm mt-1">
                                {{ $isFrench ? 'Cette fonctionnalité avancée nécessite un écran plus large. Veuillez utiliser un ordinateur pour accéder à la gestion complète des stagiaires.' : 'This advanced feature requires a larger screen. Please use a computer to access the complete intern management system.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Summary -->
                <div class="space-y-4">
                    @if($stagiaires->count() > 0)
                        <div class="bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500 animate-fade-in">
                            <h3 class="text-blue-800 font-semibold mb-2">
                                {{ $isFrench ? 'Résumé des stagiaires' : 'Interns Summary' }}
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-blue-600 mb-1">
                                        {{ $isFrench ? 'Total' : 'Total' }}
                                    </p>
                                    <p class="font-bold text-blue-700 text-lg">{{ $stagiaires->count() }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-xl text-center">
                                    <p class="text-xs font-medium text-green-600 mb-1">
                                        {{ $isFrench ? 'Actifs' : 'Active' }}
                                    </p>
                                    <p class="font-bold text-green-700 text-lg">
                                        {{ $stagiaires->where('date_fin', '>=', now())->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Intern Cards (Limited) -->
                        <div class="space-y-3">
                            @foreach($stagiaires->take(3) as $stagiaire)
                                <div class="bg-white border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</h4>
                                            <p class="text-sm text-gray-600">{{ $stagiaire->ecole }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $stagiaire->date_fin >= now() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $stagiaire->date_fin >= now() ? ($isFrench ? 'Actif' : 'Active') : ($isFrench ? 'Terminé' : 'Finished') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $stagiaire->date_debut->format('d/m/Y') }} - {{ $stagiaire->date_fin->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endforeach

                            @if($stagiaires->count() > 3)
                                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                                    <p class="text-gray-600 text-sm">
                                        {{ $isFrench ? 'Et ' . ($stagiaires->count() - 3) . ' autres stagiaires...' : 'And ' . ($stagiaires->count() - 3) . ' more interns...' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-2xl p-8 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $isFrench ? 'Aucun stagiaire' : 'No interns' }}
                            </h3>
                            <p class="text-gray-500">
                                {{ $isFrench ? 'Aucun stagiaire n\'est actuellement enregistré.' : 'No interns are currently registered.' }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Mobile Quick Actions -->
                <div class="mt-6 bg-blue-50 rounded-2xl p-4 border-l-4 border-blue-500">
                    <h3 class="text-blue-800 font-semibold mb-3">
                        {{ $isFrench ? 'Actions rapides disponibles sur PC' : 'Quick actions available on PC' }}
                    </h3>
                    <div class="space-y-2 text-sm text-blue-700">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $isFrench ? 'Ajouter de nouveaux stagiaires' : 'Add new interns' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ $isFrench ? 'Modifier les informations' : 'Edit information' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isFrench ? 'Gérer la rémunération' : 'Manage compensation' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ $isFrench ? 'Générer des rapports' : 'Generate reports' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @include('buttons')
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">
                                {{ $isFrench ? 'Liste des Stagiaires' : 'Intern List' }}
                            </h2>
                            <a href="{{ route('stagiaires.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ $isFrench ? 'Ajouter un stagiaire' : 'Add an intern' }}
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Nom & Prénom' : 'Name & Surname' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'École' : 'School' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Type de Stage' : 'Internship Type' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Période' : 'Period' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Rémunération' : 'Compensation' }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $isFrench ? 'Actions' : 'Actions' }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($stagiaires as $stagiaire)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $stagiaire->nom }} {{ $stagiaire->prenom }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $stagiaire->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $stagiaire->ecole }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($stagiaire->type_stage) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $stagiaire->date_debut->format('d/m/Y') }} - {{ $stagiaire->date_fin->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($stagiaire->remuneration, 2) }} XAF
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                                                </a>
                                                <button onclick="openRemunerationModal({{ $stagiaire->id }})" class="text-green-600 hover:text-green-900">
                                                    {{ $isFrench ? 'Rémunération' : 'Compensation' }}
                                                </button>
                                                <button onclick="openAppreciationModal({{ $stagiaire->id }})" class="text-blue-600 hover:text-blue-900">
                                                    {{ $isFrench ? 'Appréciation' : 'Assessment' }}
                                                </button>
                                                <a href="{{ route('stagiaires.report', $stagiaire) }}" class="text-purple-600 hover:text-purple-900">
                                                    {{ $isFrench ? 'Rapport' : 'Report' }}
                                                </a>
                                                <form action="{{ route('stagiaires.destroy', $stagiaire) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce stagiaire ?' : 'Are you sure you want to delete this intern?' }}')">
                                                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Rémunération -->
        <div id="remunerationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $isFrench ? 'Définir la rémunération' : 'Set compensation' }}
                    </h3>
                    <form id="remunerationForm" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="remuneration" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="closeRemunerationModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-700 rounded">
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                                {{ $isFrench ? 'Valider' : 'Validate' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Appréciation -->
        <div id="appreciationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $isFrench ? 'Ajouter une appréciation' : 'Add an assessment' }}
                    </h3>
                    <form id="appreciationForm" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <textarea name="appreciation" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="button" onclick="closeAppreciationModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-700 rounded">
                                {{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                                {{ $isFrench ? 'Valider' : 'Validate' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openRemunerationModal(stagiaireId) {
                document.getElementById('remunerationModal').classList.remove('hidden');
                document.getElementById('remunerationForm').action = `/stagiaires/${stagiaireId}/remuneration`;
            }

            function closeRemunerationModal() {
                document.getElementById('remunerationModal').classList.add('hidden');
            }

            function openAppreciationModal(stagiaireId) {
                document.getElementById('appreciationModal').classList.remove('hidden');
                document.getElementById('appreciationForm').action = `/stagiaires/${stagiaireId}/appreciation`;
            }

            function closeAppreciationModal() {
                document.getElementById('appreciationModal').classList.add('hidden');
            }
        </script>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
}
</style>
@endsection
