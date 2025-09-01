@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
   <br><br>

    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Mobile Current Stocks -->
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        {{ $isFrench ? 'Stocks Actuels' : 'Current Stocks' }}
                    </h2>
                    <div class="space-y-3">
                        @foreach($produits as $produit)
                            <div class="bg-blue-50 border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $produit->nom }}</h3>
                                        <p class="text-sm text-blue-600">
                                            {{ $isFrench ? 'Stock:' : 'Stock:' }} 
                                            <span class="font-bold">{{ $produit->getCurrentStock() }}</span> 
                                            {{ $isFrench ? 'unités' : 'units' }}
                                        </p>
                                    </div>
                                    <button 
                                        onclick="openSuggestionModal({{ $produit->code_produit }}, '{{ $produit->nom }}', {{ $produit->getCurrentStock() }})"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transform hover:scale-105 transition-all duration-200"
                                    >
                                        {{ $isFrench ? 'Suggérer' : 'Suggest' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Weekly Suggestions -->
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        {{ $isFrench ? 'Suggestions de la Semaine' : 'Weekly Suggestions' }}
                    </h2>
                    <div class="space-y-3">
                        @php
                            $jours = $isFrench ? ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] 
                                              : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $currentWeek = [];
                            for($i = 0; $i < 7; $i++) {
                                $currentWeek[] = $dateDebut->copy()->addDays($i);
                            }
                        @endphp
                        
                        @foreach($currentWeek as $index => $jour)
                            <div class="bg-green-50 border rounded-2xl p-4 shadow-sm animate-slide-in-right" style="animation-delay: {{ $index * 0.1 }}s">
                                <h4 class="font-semibold text-green-800 mb-2">
                                    {{ $jours[$index] }} {{ $jour->format('d/m') }}
                                </h4>
                                <div class="space-y-2">
                                    @php
                                        $jourKey = $jour->format('Y-m-d H:i:s');
                                        $hasSuggestions = false;
                                    @endphp
                                    @if(isset($suggestionsGrouped) && $suggestionsGrouped->count() > 0)
                                        @foreach($suggestionsGrouped as $produitId => $suggestionsByProduct)
                                            @if(isset($suggestionsByProduct[$jourKey]))
                                                @foreach($suggestionsByProduct[$jourKey] as $suggestion)
                                                    @php $hasSuggestions = true; @endphp
                                                    <div class="flex justify-between items-center text-sm bg-white p-2 rounded-lg">
                                                        <span class="font-medium">
                                                            {{ $suggestion->Produit_fixes->nom }}: 
                                                            <span class="text-green-600 font-bold">{{ $suggestion->quantity }}</span> 
                                                            {{ $isFrench ? 'unités' : 'units' }}
                                                        </span>
                                                        <button 
                                                            onclick="deleteSuggestion({{ $suggestion->id }})"
                                                            class="text-red-500 hover:text-red-700 w-6 h-6 flex items-center justify-center rounded-full hover:bg-red-100 transition-all duration-200"
                                                        >
                                                            ×
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                    
                                    @if(!$hasSuggestions)
                                        <span class="text-gray-500 text-sm">
                                            {{ $isFrench ? 'Aucune suggestion' : 'No suggestions' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="container mx-auto px-4 py-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                @if(session('success'))
                    <div id="successAlert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button onclick="closeSuccessAlert()" class="ml-4 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    @include('buttons')
                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $isFrench ? 'Suggestions de Production' : 'Production Suggestions' }}
                    </h1>
                    <div class="text-sm text-gray-600">
                        {{ $isFrench ? 'Période:' : 'Period:' }} {{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Product list with stocks -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4">
                            {{ $isFrench ? 'Stocks Actuels' : 'Current Stocks' }}
                        </h2>
                        <div class="space-y-3">
                            @foreach($produits as $produit)
                                <div class="bg-white rounded p-3 border">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-medium">{{ $produit->nom }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $isFrench ? 'Stock:' : 'Stock:' }} 
                                                <span class="font-semibold">{{ $produit->getCurrentStock() }}</span> 
                                                {{ $isFrench ? 'unités' : 'units' }}
                                            </p>
                                        </div>
                                        <button 
                                            onclick="openSuggestionModal({{ $produit->code_produit }}, '{{ $produit->nom }}', {{ $produit->getCurrentStock() }})"
                                            class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600"
                                        >
                                            {{ $isFrench ? 'Suggérer' : 'Suggest' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Weekly suggestions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4">
                            {{ $isFrench ? 'Suggestions de la Semaine' : 'Weekly Suggestions' }}
                        </h2>
                        <div class="space-y-3">
                            @php
                                $jours = $isFrench ? ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] 
                                                  : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $currentWeek = [];
                                for($i = 0; $i < 7; $i++) {
                                    $currentWeek[] = $dateDebut->copy()->addDays($i);
                                }
                            @endphp
                            
                            @foreach($currentWeek as $index => $jour)
                                <div class="bg-white rounded p-3 border">
                                    <h4 class="font-medium text-gray-800">{{ $jours[$index] }} {{ $jour->format('d/m') }}</h4>
                                    <div class="mt-2 space-y-1">
                                        @php
                                            $jourKey = $jour->format('Y-m-d H:i:s');
                                            $hasSuggestions = false;
                                        @endphp
                                        @if(isset($suggestionsGrouped) && $suggestionsGrouped->count() > 0)
                                            @foreach($suggestionsGrouped as $produitId => $suggestionsByProduct)
                                                @if(isset($suggestionsByProduct[$jourKey]))
                                                    @foreach($suggestionsByProduct[$jourKey] as $suggestion)
                                                        @php $hasSuggestions = true; @endphp
                                                        <div class="flex justify-between items-center text-sm">
                                                            <span>{{ $suggestion->Produit_fixes->nom }}: {{ $suggestion->quantity }} {{ $isFrench ? 'unités' : 'units' }}</span>
                                                            <button 
                                                                onclick="deleteSuggestion({{ $suggestion->id }})"
                                                                class="text-red-500 hover:text-red-700"
                                                            >
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                        @if(!$hasSuggestions)
                                            <span class="text-gray-500 text-sm">
                                                {{ $isFrench ? 'Aucune suggestion' : 'No suggestions' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Suggestion modal -->
<div id="suggestionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
            {{ $isFrench ? 'Suggérer une Production' : 'Suggest Production' }}
        </h3>
        <form id="suggestionForm">
            @csrf
            <input type="hidden" id="produitId" name="produit">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Produit' : 'Product' }}
                </label>
                <p id="produitNom" class="font-semibold"></p>
                <p id="stockActuel" class="text-sm text-gray-600"></p>
            </div>
            
            <div class="mb-4">
                <label for="day" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Jour' : 'Day' }}
                </label>
                <select id="day" name="day" class="w-full border border-gray-300 rounded px-3 py-2">
                    @foreach($currentWeek as $index => $jour)
                        <option value="{{ $jour->format('Y-m-d') }}">{{ $jours[$index] }} {{ $jour->format('d/m') }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isFrench ? 'Quantité suggérée' : 'Suggested quantity' }}
                </label>
                <input type="number" id="quantity" name="quantity" min="0" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeSuggestionModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    {{ $isFrench ? 'Enregistrer' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSuggestionModal(produitId, produitNom, stock) {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    document.getElementById('produitId').value = produitId;
    document.getElementById('produitNom').textContent = produitNom;
    document.getElementById('stockActuel').textContent = `${isFrench ? 'Stock actuel:' : 'Current stock:'} ${stock} ${isFrench ? 'unités' : 'units'}`;
    document.getElementById('suggestionModal').classList.remove('hidden');
    document.getElementById('suggestionModal').classList.add('flex');
}

function closeSuggestionModal() {
    document.getElementById('suggestionModal').classList.add('hidden');
    document.getElementById('suggestionModal').classList.remove('flex');
    document.getElementById('suggestionForm').reset();
}

function closeSuccessAlert() {
    document.getElementById('successAlert').style.display = 'none';
}

function deleteSuggestion(id) {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    if (confirm(isFrench ? 'Êtes-vous sûr de vouloir supprimer cette suggestion ?' : 'Are you sure you want to delete this suggestion?')) {
        fetch(`/production/suggestions/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert(isFrench ? 'Erreur lors de la suppression' : 'Error during deletion');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(isFrench ? 'Erreur lors de la suppression' : 'Error during deletion');
        });
    }
}

@if(session('success'))
    setTimeout(function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => alert.style.display = 'none', 300);
        }
    }, 5000);
@endif

document.getElementById('suggestionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("production.suggestions.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.success) {
            location.reload();
        } else if (data && !data.success) {
            const isFrench = {{ $isFrench ? 'true' : 'false' }};
            alert(isFrench ? 'Erreur lors de l\'enregistrement' : 'Error during save');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const isFrench = {{ $isFrench ? 'true' : 'false' }};
        alert(isFrench ? 'Erreur lors de l\'enregistrement' : 'Error during save');
    });
});
</script>

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
