@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
  

    <!-- Desktop/Tablet Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            @include('buttons')
        </div>

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                {{ $isFrench ? "Centre de Messages" : "Message Center" }}
            </h1>
            <p class="text-gray-600">
                {{ $isFrench ? "Gérez vos communications et notifications" : "Manage your communications and notifications" }}
            </p>
        </div>

        <!-- Categories Grid - Mobile Cards, Desktop Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 lg:mb-12">
            <!-- Private Complaints Card -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md p-4 lg:p-6 border-t-4 border-blue-500 hover:shadow-lg transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="flex lg:block items-center lg:items-start">
                    <div class="text-2xl lg:text-3xl mr-4 lg:mr-0 lg:mb-4 bg-blue-50 p-3 rounded-full lg:bg-transparent lg:p-0">🔒</div>
                    <div class="flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1 lg:mb-2">
                            {{ $isFrench ? "Plaintes privées" : "Private Complaints" }}
                        </h3>
                        <p class="text-gray-600 text-sm">
                            {{ $isFrench ? "Messages privés et confidentiels. L'identité de l'expéditeur reste anonyme." : "Private and confidential messages. Sender identity remains anonymous." }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Suggestions Card -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md p-4 lg:p-6 border-t-4 border-green-500 hover:shadow-lg transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="flex lg:block items-center lg:items-start">
                    <div class="text-2xl lg:text-3xl mr-4 lg:mr-0 lg:mb-4 bg-green-50 p-3 rounded-full lg:bg-transparent lg:p-0">💡</div>
                    <div class="flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1 lg:mb-2">
                            {{ $isFrench ? "Suggestions" : "Suggestions" }}
                        </h3>
                        <p class="text-gray-600 text-sm">
                            {{ $isFrench ? "Idées et propositions d'amélioration de nos services." : "Ideas and improvement proposals for our services." }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Reports Card -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md p-4 lg:p-6 border-t-4 border-yellow-500 hover:shadow-lg transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="flex lg:block items-center lg:items-start">
                    <div class="text-2xl lg:text-3xl mr-4 lg:mr-0 lg:mb-4 bg-yellow-50 p-3 rounded-full lg:bg-transparent lg:p-0">📝</div>
                    <div class="flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1 lg:mb-2">
                            {{ $isFrench ? "Reports" : "Reports" }}
                        </h3>
                        <p class="text-gray-600 text-sm">
                            {{ $isFrench ? "Signalements et rapports d'incidents ou de problèmes." : "Reports and incident notifications or problems." }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md p-4 lg:p-6 border-t-4 border-purple-500 hover:shadow-lg transition-all duration-200 active:scale-98 lg:active:scale-100">
                <div class="flex lg:block items-center lg:items-start">
                    <div class="text-2xl lg:text-3xl mr-4 lg:mr-0 lg:mb-4 bg-purple-50 p-3 rounded-full lg:bg-transparent lg:p-0">ℹ️</div>
                    <div class="flex-1">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-1 lg:mb-2">
                            {{ $isFrench ? "Conseils" : "Tips" }}
                        </h3>
                        <p class="text-gray-600 text-sm">
                            {{ $isFrench ? "Après lecture, supprimez les messages traités ou à ignorer." : "After reading, delete processed or ignored messages." }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="space-y-4 lg:space-y-6">
            <!-- Private Complaints -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md overflow-hidden">
                <div class="px-4 lg:px-6 py-4 bg-blue-500 text-white cursor-pointer flex justify-between items-center transition-all duration-200 active:bg-blue-600"
                     onclick="toggleMessages('complaint-private')">
                    <h2 class="text-lg lg:text-xl font-semibold">
                        {{ $isFrench ? "Plaintes privées" : "Private Complaints" }}
                    </h2>
                    <div class="flex items-center">
                        @if($messages_complaint_private->where('read', false)->count() > 0)
                            <span class="bg-white text-blue-500 px-3 py-1 rounded-full text-sm font-semibold mr-2 animate-pulse">
                                {{ $messages_complaint_private->where('read', false)->count() }}
                            </span>
                        @endif
                        <svg class="w-5 h-5 transform transition-transform duration-200" id="arrow-complaint-private" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div id="complaint-private" class="hidden messages-container">
                    @forelse($messages_complaint_private as $message)
                        <div class="border-b last:border-b-0 p-4 {{ $message->read ? 'bg-white' : 'bg-blue-50' }} transition-all duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $isFrench ? "Anonyme" : "Anonymous" }}
                                        </p>
                                    </div>
                                    <p class="text-gray-600 mb-3 leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-sm text-gray-500">{{ date('d/m/Y', strtotime($message->date_message)) }}</p>
                                </div>
                                <form action="{{ route('messages.destroy', ['message' => $message->id]) }}"
                                      method="POST" class="ml-4" onsubmit="return deleteMessage(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200 active:scale-95">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Aucun message" : "No messages" }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Suggestions -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md overflow-hidden">
                <div class="px-4 lg:px-6 py-4 bg-green-500 text-white cursor-pointer flex justify-between items-center transition-all duration-200 active:bg-green-600"
                     onclick="toggleMessages('suggestion')">
                    <h2 class="text-lg lg:text-xl font-semibold">
                        {{ $isFrench ? "Suggestions" : "Suggestions" }}
                    </h2>
                    <div class="flex items-center">
                        @if($messages_suggestion->where('read', false)->count() > 0)
                            <span class="bg-white text-green-500 px-3 py-1 rounded-full text-sm font-semibold mr-2 animate-pulse">
                                {{ $messages_suggestion->where('read', false)->count() }}
                            </span>
                        @endif
                        <svg class="w-5 h-5 transform transition-transform duration-200" id="arrow-suggestion" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div id="suggestion" class="hidden messages-container">
                    @forelse($messages_suggestion as $message)
                        <div class="border-b last:border-b-0 p-4 {{ $message->read ? 'bg-white' : 'bg-green-50' }} transition-all duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-green-100 p-2 rounded-full mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $message->name !== 'null' ? $message->name : ($isFrench ? 'Anonyme' : 'Anonymous') }}
                                        </p>
                                    </div>
                                    <p class="text-gray-600 mb-3 leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-sm text-gray-500">{{ date('d/m/Y', strtotime($message->date_message)) }}</p>
                                </div>
                                <form action="{{ route('messages.destroy', ['message' => $message->id]) }}"
                                      method="POST" class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200 active:scale-95"
                                            onclick="return confirm('{{ $isFrench ? 'Supprimer ce message ?' : 'Delete this message?' }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Aucun message" : "No messages" }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Reports -->
            <div class="bg-white rounded-xl lg:rounded-lg shadow-sm lg:shadow-md overflow-hidden">
                <div class="px-4 lg:px-6 py-4 bg-yellow-500 text-white cursor-pointer flex justify-between items-center transition-all duration-200 active:bg-yellow-600"
                     onclick="toggleMessages('report')">
                    <h2 class="text-lg lg:text-xl font-semibold">
                        {{ $isFrench ? "Reports" : "Reports" }}
                    </h2>
                    <div class="flex items-center">
                        @if($messages_report->where('read', false)->count() > 0)
                            <span class="bg-white text-yellow-500 px-3 py-1 rounded-full text-sm font-semibold mr-2 animate-pulse">
                                {{ $messages_report->where('read', false)->count() }}
                            </span>
                        @endif
                        <svg class="w-5 h-5 transform transition-transform duration-200" id="arrow-report" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div id="report" class="hidden messages-container">
                    @forelse($messages_report as $message)
                        <div class="border-b last:border-b-0 p-4 {{ $message->read ? 'bg-white' : 'bg-yellow-50' }} transition-all duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $message->name !== 'null' ? $message->name : ($isFrench ? 'Anonyme' : 'Anonymous') }}
                                        </p>
                                    </div>
                                    <p class="text-gray-600 mb-3 leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-sm text-gray-500">{{ date('d/m/Y', strtotime($message->date_message)) }}</p>
                                </div>
                                <form action="{{ route('messages.destroy', ['message' => $message->id]) }}"
                                      method="POST" class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all duration-200 active:scale-95"
                                            onclick="return confirm('{{ $isFrench ? 'Supprimer ce message ?' : 'Delete this message?' }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            {{ $isFrench ? "Aucun message" : "No messages" }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .active\:scale-98:active {
        transform: scale(0.98);
        transition: transform 0.1s ease-in-out;
    }
    
    .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-in-out;
    }
    
    .active\:bg-blue-600:active {
        background-color: #2563eb;
    }
    
    .active\:bg-green-600:active {
        background-color: #16a34a;
    }
    
    .active\:bg-yellow-600:active {
        background-color: #ca8a04;
    }
}

/* Haptic feedback simulation */
@media (hover: none) and (pointer: coarse) {
    .active\:scale-98:active, .active\:scale-95:active {
        transform: scale(0.95);
        transition: transform 0.1s ease-out;
    }
}

/* Animation for message containers */
.messages-container {
    transition: all 0.3s ease-in-out;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

@push('scripts')
<script>
function toggleMessages(type) {
    const container = document.getElementById(type);
    const arrow = document.getElementById('arrow-' + type);
    const isHidden = container.classList.contains('hidden');

    // Hide all containers and reset arrows
    document.querySelectorAll('.messages-container').forEach(el => {
        el.classList.add('hidden');
    });
    document.querySelectorAll('[id^="arrow-"]').forEach(arrow => {
        arrow.classList.remove('rotate-180');
    });

    // Toggle selected container
    if (isHidden) {
        container.classList.remove('hidden');
        arrow.classList.add('rotate-180');

        // Mark messages as read
        fetch(`/messages/mark-read/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(() => {
            // Update UI
            container.querySelectorAll('.bg-blue-50, .bg-green-50, .bg-yellow-50').forEach(msg => {
                msg.classList.remove('bg-blue-50', 'bg-green-50', 'bg-yellow-50');
                msg.classList.add('bg-white');
            });

            const countBadge = container.previousElementSibling.querySelector('span.animate-pulse');
            if (countBadge) {
                countBadge.remove();
            }
        });

        // Vibration feedback on mobile
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }
}

function deleteMessage(formElement) {
    const confirmText = '{{ $isFrench ? "Êtes-vous sûr de vouloir supprimer ce message ?" : "Are you sure you want to delete this message?" }}';
    return confirm(confirmText);
}

// Add CSS for rotation animation
const style = document.createElement('style');
style.textContent = `
    .rotate-180 {
        transform: rotate(180deg);
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
