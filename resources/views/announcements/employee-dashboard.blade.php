@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile-First -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 md:p-6 shadow-xl">
        <div class="container mx-auto">
            <div class="space-y-3">
                @include('buttons')
                
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                    {{ $isFrench ? 'Annonces de la direction' : 'Management Announcements' }}
                </h1>
                <p class="text-blue-100 text-sm md:text-base">
                    {{ $isFrench ? 'Communications officielles et informations importantes' : 'Official communications and important information' }}
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:py-8 md:px-4 max-w-4xl">
        @foreach($announcements as $announcement)
        <div class="bg-white rounded-2xl shadow-lg mb-6 md:mb-8 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            
            <!-- Announcement Header -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 md:p-6 border-b border-blue-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3">
                    <div class="flex-1">
                        <h3 class="text-xl md:text-2xl font-bold text-blue-900 mb-2">
                            {{ $announcement->title }}
                        </h3>
                        
                        <!-- Mobile Author Info -->
                        <div class="flex items-center space-x-3 text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($announcement->user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium">{{ $announcement->user->name }}</span>
                            </div>
                            <span class="text-gray-400">•</span>
                            <time class="text-gray-600">
                                {{ $announcement->created_at->format($isFrench ? 'd/m/Y H:i' : 'M d, Y H:i') }}
                            </time>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcement Content -->
            <div class="p-4 md:p-6">
                <div class="prose prose-sm md:prose max-w-none mb-6 md:mb-8">
                    <p class="text-gray-700 leading-relaxed text-base md:text-lg">
                        {{ $announcement->content }}
                    </p>
                </div>

                <!-- Reactions Section -->
                <div class="bg-gray-50 rounded-xl p-4 md:p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.294-1.13L3 21l1.13-5.706A8.955 8.955 0 013 11c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                            </svg>
                            {{ $isFrench ? 'Réactions' : 'Reactions' }} ({{ $announcement->reactions->count() }})
                        </h4>
                    </div>

                    <!-- Reactions List -->
                    <div class="space-y-3 md:space-y-4 max-h-96 overflow-y-auto">
                        @foreach($announcement->reactions as $reaction)
                        <div class="bg-white rounded-xl p-3 md:p-4 shadow-sm border border-gray-200 transform transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start space-x-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($reaction->user->name, 0, 1)) }}
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-1 md:gap-2 mb-2">
                                        <span class="font-semibold text-gray-900 truncate">
                                            {{ $reaction->user->name }}
                                        </span>
                                        <time class="text-xs md:text-sm text-gray-500 flex-shrink-0">
                                            {{ $reaction->created_at->format($isFrench ? 'd/m/Y H:i' : 'M d, Y H:i') }}
                                        </time>
                                    </div>
                                    <p class="text-gray-700 text-sm md:text-base">
                                        {{ $reaction->comment }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reaction Form -->
                <form action="{{ route('announcements.react', $announcement) }}" method="POST" 
                      class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 md:p-6 border border-blue-200">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm md:text-base">
                            <svg class="w-5 h-5 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            {{ $isFrench ? 'Votre réaction' : 'Your reaction' }}
                        </label>
                        <textarea name="comment" rows="3" required
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 resize-none text-base"
                                  placeholder="{{ $isFrench ? 'Partagez votre avis...' : 'Share your thoughts...' }}"></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 active:scale-95 focus:ring-4 focus:ring-blue-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ $isFrench ? 'Envoyer ma réaction' : 'Send reaction' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

        <!-- Empty State -->
        @if($announcements->isEmpty())
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                {{ $isFrench ? 'Aucune annonce' : 'No announcements' }}
            </h3>
            <p class="text-gray-500">
                {{ $isFrench ? 'Il n\'y a actuellement aucune annonce de la direction.' : 'There are currently no management announcements.' }}
            </p>
        </div>
        @endif
    </div>
</div>

<style>
    /* Mobile-optimized styles */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Enhanced touch targets */
        button, textarea {
            min-height: 44px;
            touch-action: manipulation;
        }
        
        /* Improved scrolling */
        .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Mobile typography */
        .prose {
            font-size: 16px;
            line-height: 1.6;
        }
    }
    
    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bg-white {
        animation: slideInUp 0.3s ease-out;
    }
    
    /* Focus states */
    textarea:focus, button:focus {
        outline: none;
    }
    
    /* Hover effects for desktop */
    @media (hover: hover) {
        .hover\:shadow-xl:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .hover\:-translate-y-1:hover {
            transform: translateY(-0.25rem);
        }
    }
</style>
@endsection
