@extends('pages.chef_production.chef_production_default')

@section('page-content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12">
        <!-- En-tête -->
        <div class="mb-6 md:mb-10">
            @include('buttons')

            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 px-2 md:px-0">
                {{ $isFrench ? 'Gestion des Repos et Congés' : 'Rest Days and Leave Management' }}
            </h1>
            <div class="h-1 w-24 md:w-32 bg-blue-400 rounded ml-2 md:ml-0"></div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 md:mb-8 rounded-r shadow-sm mx-2 md:mx-0">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Mobile: Stack layout, Desktop: Grid layout -->
        <div class="flex flex-col xl:grid xl:grid-cols-2 xl:gap-12 space-y-6 xl:space-y-0">
            <!-- Formulaire -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mx-2 md:mx-0 order-1 xl:order-none">
                <!-- Header avec animation mobile -->
                <div class="p-4 md:p-6 bg-gradient-to-r from-blue-50 to-green-50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100/20 to-green-100/20 animate-pulse md:hidden"></div>
                    <h2 class="text-lg md:text-2xl font-semibold text-gray-800 relative z-10">
                        {{ $isFrench ? 'Définir les jours de repos et congés' : 'Define rest days and leave' }}
                    </h2>
                </div>

                <form action="{{ route('repos-conges.store') }}" method="POST" class="p-4 md:p-8 space-y-4 md:space-y-6">
                    @csrf

                    <!-- Sélection employé -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 block">
                            {{ $isFrench ? 'Employé' : 'Employee' }}
                        </label>
                        <div class="relative">
                            <select name="employe_id" required 
                                class="w-full px-4 py-3 md:py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200 appearance-none bg-white pr-10
                                       hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100">
                                <option value="">{{ $isFrench ? 'Sélectionner un employé' : 'Select an employee' }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Jour de repos -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 block">
                            {{ $isFrench ? 'Jour de repos fixe' : 'Fixed rest day' }}
                        </label>
                        <div class="relative">
                            <select name="jour" required 
                                class="w-full px-4 py-3 md:py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200 appearance-none bg-white pr-10
                                       hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100">
                                @if($isFrench)
                                    @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                        <option value="{{ $jour }}">{{ ucfirst($jour) }}</option>
                                    @endforeach
                                @else
                                    @foreach([
                                        'lundi' => 'Monday', 'mardi' => 'Tuesday', 'mercredi' => 'Wednesday', 
                                        'jeudi' => 'Thursday', 'vendredi' => 'Friday', 'samedi' => 'Saturday', 'dimanche' => 'Sunday'
                                    ] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Section Congés -->
                    <div class="pt-6 md:pt-8 border-t border-gray-100">
                        <div class="flex items-center space-x-2 mb-4 md:mb-6">
                            <div class="w-2 h-6 bg-blue-400 rounded md:hidden"></div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                                {{ $isFrench ? 'Période de congés' : 'Leave period' }}
                            </h3>
                        </div>

                        <!-- Calendrier - Masqué sur mobile -->
                        <div class="hidden md:block bg-white rounded-lg border border-gray-200 p-4 mb-6">
                            <div id="mini-calendar"></div>
                        </div>

                        <!-- Dates et durée -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 block">
                                    {{ $isFrench ? 'Date de début' : 'Start date' }}
                                </label>
                                <input type="date" name="debut_c" id="debut_c"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200
                                           hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 block">
                                    {{ $isFrench ? 'Nombre de jours' : 'Number of days' }}
                                </label>
                                <input type="number" name="conges" id="conges" min="1"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200
                                           hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100">
                            </div>
                        </div>

                        <!-- Raison -->
                        <div class="space-y-4 md:space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 block">
                                    {{ $isFrench ? 'Motif du congé' : 'Leave reason' }}
                                </label>
                                <div class="relative">
                                    <select name="raison_c" id="raison_c"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200 appearance-none bg-white pr-10
                                               hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100">
                                        <option value="">{{ $isFrench ? 'Sélectionner un motif' : 'Select a reason' }}</option>
                                        @if($isFrench)
                                            <option value="maladie">Maladie</option>
                                            <option value="evenement">Événement</option>
                                            <option value="accouchement">Accouchement</option>
                                            <option value="autre">Autre</option>
                                        @else
                                            <option value="maladie">Illness</option>
                                            <option value="evenement">Event</option>
                                            <option value="accouchement">Maternity</option>
                                            <option value="autre">Other</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div id="autre-raison-container" class="hidden space-y-2 animate-fadeIn">
                                <label class="text-sm font-medium text-gray-700 block">
                                    {{ $isFrench ? 'Préciser le motif' : 'Specify the reason' }}
                                </label>
                                <input type="text" name="autre_raison"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200
                                           hover:border-blue-300 focus:transform focus:scale-[1.02] md:focus:scale-100"
                                    placeholder="{{ $isFrench ? 'Veuillez préciser...' : 'Please specify...' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="pt-6 md:pt-8">
                        <button type="submit"
                            class="w-full md:w-auto md:ml-auto md:flex px-6 py-4 md:py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg
                                   hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                                   shadow-sm transition-all duration-200 font-medium text-center
                                   active:transform active:scale-95 hover:shadow-md">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $isFrench ? 'Enregistrer' : 'Save' }}</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des congés -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mx-2 md:mx-0 order-2 xl:order-none">
                <!-- Header avec animation mobile -->
                <div class="p-4 md:p-6 bg-gradient-to-r from-blue-50 to-green-50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-100/20 to-green-100/20 animate-pulse md:hidden"></div>
                    <h2 class="text-lg md:text-2xl font-semibold text-gray-800 relative z-10">
                        {{ $isFrench ? 'Repos et congés actuels' : 'Current rest days and leave' }}
                    </h2>
                </div>

                <div class="p-4 md:p-8">
                    @if($reposConges->count() > 0)
                        <div class="space-y-4 md:space-y-8">
                            @foreach($reposConges as $rc)
                            <div class="bg-gray-50 rounded-lg p-4 md:p-6 hover:bg-blue-50 transition-all duration-200 
                                        hover:shadow-sm transform hover:scale-[1.01] md:hover:scale-100 border-l-4 border-transparent hover:border-blue-400">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 md:mb-4 space-y-2 sm:space-y-0">
                                    <h3 class="text-base md:text-lg font-semibold text-gray-800 flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full md:hidden"></div>
                                        <span>{{ $rc->employe->name }}</span>
                                    </h3>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium self-start sm:self-auto">
                                        @if($isFrench)
                                            {{ ucfirst($rc->jour) }}
                                        @else
                                            @php
                                                $dayTranslations = [
                                                    'lundi' => 'Monday', 'mardi' => 'Tuesday', 'mercredi' => 'Wednesday',
                                                    'jeudi' => 'Thursday', 'vendredi' => 'Friday', 'samedi' => 'Saturday', 'dimanche' => 'Sunday'
                                                ];
                                            @endphp
                                            {{ $dayTranslations[$rc->jour] ?? ucfirst($rc->jour) }}
                                        @endif
                                    </span>
                                </div>

                                @if($rc->conges)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mt-3 md:mt-4">
                                    <div class="bg-white p-3 rounded-lg border border-gray-100">
                                        <p class="text-xs md:text-sm text-gray-500 font-medium">
                                            {{ $isFrench ? 'Durée' : 'Duration' }}
                                        </p>
                                        <p class="font-semibold text-gray-800 mt-1">
                                            {{ $rc->conges }} {{ $isFrench ? 'jours' : 'days' }}
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-gray-100">
                                        <p class="text-xs md:text-sm text-gray-500 font-medium">
                                            {{ $isFrench ? 'Date de début' : 'Start date' }}
                                        </p>
                                        <p class="font-semibold text-gray-800 mt-1">{{ $rc->debut_c->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="sm:col-span-2 bg-white p-3 rounded-lg border border-gray-100">
                                        <p class="text-xs md:text-sm text-gray-500 font-medium">
                                            {{ $isFrench ? 'Motif' : 'Reason' }}
                                        </p>
                                        <p class="font-semibold text-gray-800 mt-1">
                                            @if($isFrench)
                                                {{ ucfirst($rc->raison_c) }}
                                            @else
                                                @php
                                                    $reasonTranslations = [
                                                        'maladie' => 'Illness', 'evenement' => 'Event', 
                                                        'accouchement' => 'Maternity', 'autre' => 'Other'
                                                    ];
                                                @endphp
                                                {{ $reasonTranslations[$rc->raison_c] ?? ucfirst($rc->raison_c) }}
                                            @endif
                                            @if($rc->raison_c === 'autre')
                                            <span class="text-gray-600">({{ $rc->autre_raison }})</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 md:py-12">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M6 7h12l-1 10a2 2 0 01-2 2H9a2 2 0 01-2-2L6 7z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm md:text-base">
                                {{ $isFrench ? 'Aucun repos ou congé défini' : 'No rest days or leave defined' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS personnalisés -->
<style>
/* Animations pour mobile */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

/* FullCalendar styles - Desktop only */
@media (min-width: 768px) {
    .fc {
        font-family: inherit;
    }
    .fc-theme-standard th {
        background-color: #EFF6FF;
        padding: 8px 0;
    }
    .fc-daygrid-day.fc-day-selected {
        background-color: #60A5FA !important;
    }
    .fc-daygrid-day-frame {
        padding: 4px;
    }
    .fc-day-today {
        background-color: #F0FDF4 !important;
    }
    .fc-button {
        background-color: #3B82F6 !important;
        border-color: #2563EB !important;
    }
    .fc-button:hover {
        background-color: #2563EB !important;
        border-color: #1D4ED8 !important;
    }
}

/* Mobile touch improvements */
@media (max-width: 767px) {
    /* Larger touch targets */
    input, select, button {
        min-height: 48px;
    }
    
    /* Enhanced button states */
    button:active {
        transform: scale(0.95);
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Card hover effects */
    .hover\:bg-blue-50:hover {
        background-color: #eff6ff;
        transform: translateY(-1px);
    }
}

/* Focus improvements for accessibility */
input:focus, select:focus, button:focus {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
}

/* Custom scrollbar for mobile */
@media (max-width: 767px) {
    ::-webkit-scrollbar {
        width: 4px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
}
</style>

<!-- Scripts -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du calendrier - Desktop seulement
    const calendarEl = document.getElementById('mini-calendar');
    if (calendarEl && window.innerWidth >= 768) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: '{{ $isFrench ? "fr" : "en" }}',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next',
                center: 'title', 
                right: ''
            },
            selectable: true,
            select: function(info) {
                const startDate = info.start;
                const endDate = info.end;
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));

                document.getElementById('debut_c').value = startDate.toISOString().split('T')[0];
                document.getElementById('conges').value = days;
            }
        });
        calendar.render();
    }

    // Gestion du champ "autre raison"
    const raisonSelect = document.getElementById('raison_c');
    const autreRaisonContainer = document.getElementById('autre-raison-container');

    if (raisonSelect && autreRaisonContainer) {
        raisonSelect.addEventListener('change', function() {
            if (this.value === 'autre') {
                autreRaisonContainer.classList.remove('hidden');
                autreRaisonContainer.classList.add('animate-fadeIn');
            } else {
                autreRaisonContainer.classList.add('hidden');
                autreRaisonContainer.classList.remove('animate-fadeIn');
            }
        });
    }

    // Amélioration des interactions tactiles sur mobile
    if (window.innerWidth < 768) {
        // Ajouter des effets de feedback tactile
        const touchElements = document.querySelectorAll('button, select, input[type="date"], input[type="number"]');
        
        touchElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = '';
                }, 100);
            });
        });

        // Scroll smooth vers le formulaire si erreur
        const errorElements = document.querySelectorAll('.border-red-300, .text-red-600');
        if (errorElements.length > 0) {
            setTimeout(() => {
                errorElements[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    }

    // Animation d'entrée pour les cartes de congés
    const cards = document.querySelectorAll('.bg-gray-50');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.4s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endsection