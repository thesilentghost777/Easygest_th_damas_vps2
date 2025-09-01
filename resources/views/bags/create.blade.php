@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Mobile Header -->
    <div class="md:hidden bg-blue-600 shadow-lg">
        <div class="px-4 py-6">
            @include('buttons')
            <h1 class="text-xl font-bold text-white mt-4 animate-fade-in">
                {{ $isFrench ? 'Nouveau Sac' : 'New Bag' }}
            </h1>
            <p class="text-blue-100 text-sm mt-1">
                {{ $isFrench ? 'Ajouter un nouveau sac à l\'inventaire' : 'Add a new bag to inventory' }}
            </p>
        </div>
    </div>

    <br><br>
    <!-- Mobile Container -->
    <div class="md:hidden px-4 pb-20">
        <div class="bg-white rounded-t-3xl shadow-2xl -mt-6 relative z-10 animate-slide-up">
            <div class="px-6 pt-8 pb-6">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-shake">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('bags.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Mobile Name Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="name" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Nom du sac' : 'Bag name' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <input type="text" name="name" id="name" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Price Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="price" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Prix' : 'Price' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">XAF</span>
                            </div>
                            <input type="number" step="0.01" name="price" id="price" required
                                class="pl-16 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Stock Quantity Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="stock_quantity" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Quantité en stock' : 'Stock quantity' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-600 font-semibold text-lg">#</span>
                            </div>
                            <input type="number" name="stock_quantity" id="stock_quantity" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Alert Threshold Field -->
                    <div class="transform hover:scale-102 transition-all duration-200">
                        <label for="alert_threshold" class="block text-base font-semibold text-gray-700 mb-3">
                            {{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <input type="number" name="alert_threshold" id="alert_threshold" value="100" required
                                class="pl-12 w-full h-14 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-0 bg-gray-50 transition-all duration-300 hover:bg-white hover:shadow-md">
                        </div>
                    </div>

                    <!-- Mobile Action Button -->
                    <div class="pt-6">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 transform hover:scale-105 active:scale-95 transition-all duration-200">
                            <svg class="h-6 w-6 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $isFrench ? 'Enregistrer' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Version -->
    <div class="hidden md:block">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                @include('buttons')
                
                <h1 class="text-3xl font-bold text-blue-600 mb-8">{{ $isFrench ? 'Nouveau Sac' : 'New Bag' }}</h1>

                @if($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: '{{ $isFrench ? "Erreur!" : "Error!" }}',
                            html: '{!! implode("<br>", $errors->all()) !!}',
                            confirmButtonColor: '#3085d6'
                        });
                    </script>
                @endif

                <form action="{{ route('bags.store') }}" method="POST" class="bg-white rounded-lg shadow-lg p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            {{ $isFrench ? 'Nom du sac' : 'Bag name' }}
                        </label>
                        <input type="text" name="name" id="name" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                            {{ $isFrench ? 'Prix' : 'Price' }}
                        </label>
                        <input type="number" step="0.01" name="price" id="price" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_quantity">
                            {{ $isFrench ? 'Quantité en stock' : 'Stock quantity' }}
                        </label>
                        <input type="number" name="stock_quantity" id="stock_quantity" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="alert_threshold">
                            {{ $isFrench ? 'Seuil d\'alerte' : 'Alert threshold' }}
                        </label>
                        <input type="number" name="alert_threshold" id="alert_threshold" value="100" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ $isFrench ? 'Enregistrer' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
}
</style>
@endsection
