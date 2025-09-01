@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        
        <!-- Mobile Header -->
        <div class="mb-6">
            @include('buttons')
            <div class="mt-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-4 animate-bounce">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 animate-fade-in">
                    Sherlock
                </h1>
                <p class="text-gray-600 animate-fade-in delay-100">
                    {{ $isFrench ? 'Posez votre question, je vous répondrai' : 'Ask your question, I will answer you' }}
                </p>
            </div>
        </div>

        <!-- Main Query Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-6">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $isFrench ? 'Assistant IA' : 'AI Assistant' }}
                </h3>
            </div>
            
            <div class="p-6 md:p-8">
                <form id="query-form" action="{{ route('process.query') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Query Input -->
                    <div class="group">
                        <label for="query" class="block text-sm font-medium text-gray-700 mb-3 transition-colors group-focus-within:text-blue-600">
                            {{ $isFrench ? 'Votre question (soyez très précis et bref)' : 'Your question (be very precise and brief)' }}
                        </label>
                        <div class="relative">
                            <textarea class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none bg-gray-50 focus:bg-white"
                                      id="query"
                                      name="query"
                                      rows="4"
                                      placeholder="{{ $isFrench ? 'Exemple : Combien de baguettes ont été vendues le 1er janvier 2024 ?' : 'Example: How many baguettes were sold on January 1st, 2024?' }}"
                                      required></textarea>
                            <div class="absolute bottom-3 right-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="text-lg">{{ $isFrench ? 'Soumettre la requête' : 'Submit Query' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Examples Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h4 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    {{ $isFrench ? 'Exemples de questions' : 'Example Questions' }}
                </h4>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $examples = $isFrench 
                            ? [
                                'Combien de baguettes ont été vendues le 1er janvier 2024 ?',
                                'Quel est le produit le plus rentable ce mois-ci ?',
                                'Montre-moi les productions totales d\'hier.',
                                'Analyse des ventes de la semaine dernière'
                            ]
                            : [
                                'How many baguettes were sold on January 1st, 2024?',
                                'What is the most profitable product this month?',
                                'Show me yesterday\'s total production.',
                                'Last week\'s sales analysis'
                            ];
                    @endphp
                    
                    @foreach($examples as $index => $example)
                        <div class="p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer animate-slide-in-left"
                             style="animation-delay: {{ $index * 0.1 }}s"
                             onclick="document.getElementById('query').value = '{{ $example }}'">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                </div>
                                <p class="text-sm text-gray-700 hover:text-blue-600 transition-colors">{{ $example }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 p-6 rounded-r-xl animate-fade-in-up">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800 mb-2">
                        {{ $isFrench ? 'Important' : 'Important' }}
                    </h3>
                    <p class="text-sm text-amber-700 leading-relaxed">
                        {{ $isFrench 
                            ? 'Il est possible que certaines requêtes ne fonctionnent pas correctement en raison des limitations de l\'IA ou d\'un manque de ressources (tokens). Si vous rencontrez un problème, essayez de reformuler votre question ou contactez le concepteur de l\'App.' 
                            : 'Some queries may not work correctly due to AI limitations or lack of resources (tokens). If you encounter an issue, try rephrasing your question or contact the App developer.' 
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('query-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Loading state
    submitButton.innerHTML = `
        <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ $isFrench ? 'Chargement...' : 'Loading...' }}</span>
    `;
    submitButton.disabled = true;
    
    // Reset after 30 seconds to prevent stuck state
    setTimeout(() => {
        if (submitButton.disabled) {
            submitButton.innerHTML = originalContent;
            submitButton.disabled = false;
        }
    }, 30000);
});
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

@keyframes slide-in-left {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
    40%, 43% { transform: translateY(-10px); }
    70% { transform: translateY(-5px); }
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

.animate-slide-in-left {
    animation: slide-in-left 0.6s ease-out;
}

.animate-bounce {
    animation: bounce 2s infinite;
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
