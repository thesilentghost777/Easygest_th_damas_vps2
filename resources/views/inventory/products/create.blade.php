@extends('layouts.app')

@section('content')
<style>
    /* Mobile-first responsive styles */
    .mobile-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        animation: slideInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .mobile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .mobile-card:hover::before {
        transform: scaleX(1);
    }

    .header-icon {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-radius: 50%;
        width: 5rem;
        height: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        animation: bounceIn 0.8s ease-out;
        transition: transform 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.1) rotate(10deg);
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        transform: scale(1.02);
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .submit-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        position: relative;
        overflow: hidden;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .group-name-highlight {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        color: #065f46;
        display: inline-block;
        margin: 0 0.25rem;
    }

    .price-input {
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .mobile-card {
            margin: 0.5rem;
            padding: 1.5rem;
            border-radius: 20px;
        }
        
        .header-icon {
            width: 4rem;
            height: 4rem;
        }
        
        .form-input {
            padding: 1rem;
            font-size: 1.1rem;
            border-radius: 12px;
        }
        
        .price-input {
            text-align: center;
            font-size: 1.3rem;
        }
        
        .form-label {
            text-align: center;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .page-title {
            text-align: center;
            font-size: 1.1rem;
            color: #10b981;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
    }

    @media (min-width: 769px) {
        .container {
            max-width: 42rem;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .submit-btn {
            margin-left: auto;
            display: block;
        }
        
        .page-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Loading animation */
    .loading {
        position: relative;
    }

    .loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50">
    <div class="container">
        @include('buttons')
        
        <div class="mb-6">
            <div class="header-icon">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h1 class="page-title font-bold text-gray-800">
                {{ $isFrench ? 'Ajouter un Produit à' : 'Add Product to' }}
                <span class="group-name-highlight">{{ $group->name }}</span>
            </h1>
        </div>

        <div class="mobile-card">
            <form action="{{ route('inventory.products.store', $group) }}" method="POST" id="createProductForm">
                @csrf

                <div class="mb-6">
                    <label for="name" class="form-label">
                        {{ $isFrench ? 'Nom du Produit' : 'Product Name' }}
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-input" 
                        value="{{ old('name') }}" 
                        required
                        placeholder="{{ $isFrench ? 'Ex: Croissant au beurre, Pain de mie...' : 'Ex: Butter croissant, Sandwich bread...' }}"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="type" class="form-label">
                        {{ $isFrench ? 'Type' : 'Type' }}
                    </label>
                    <input 
                        type="text" 
                        name="type" 
                        id="type" 
                        class="form-input" 
                        value="{{ old('type') }}" 
                        required
                        placeholder="{{ $isFrench ? 'Ex: Viennoiserie, Pain, Gâteau...' : 'Ex: Pastry, Bread, Cake...' }}"
                    >
                    @error('type')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="price" class="form-label">
                        {{ $isFrench ? 'Prix (XAF)' : 'Price (XAF)' }}
                    </label>
                    <input 
                        type="number" 
                        name="price" 
                        id="price" 
                        min="0" 
                        step="1" 
                        class="form-input price-input" 
                        value="{{ old('price') }}" 
                        required
                        placeholder="500"
                    >
                    @error('price')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span id="submitText">{{ $isFrench ? 'Ajouter le Produit' : 'Add Product' }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createProductForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const nameInput = document.getElementById('name');
    const typeInput = document.getElementById('type');
    const priceInput = document.getElementById('price');
    
    // Form validation with visual feedback
    function validateField(field, minValue = null) {
        if (minValue !== null) {
            if (parseFloat(field.value) > minValue) {
                field.style.borderColor = '#10b981';
                field.style.backgroundColor = '#f0fdf4';
            } else {
                field.style.borderColor = '#e5e7eb';
                field.style.backgroundColor = 'white';
            }
        } else {
            if (field.value.length >= 2) {
                field.style.borderColor = '#10b981';
                field.style.backgroundColor = '#f0fdf4';
            } else {
                field.style.borderColor = '#e5e7eb';
                field.style.backgroundColor = 'white';
            }
        }
    }
    
    nameInput.addEventListener('input', () => validateField(nameInput));
    typeInput.addEventListener('input', () => validateField(typeInput));
    priceInput.addEventListener('input', () => validateField(priceInput, 0));
    
    // Enhanced form submission
    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitText.textContent = '{{ $isFrench ? "Ajout..." : "Adding..." }}';
    });
    
    // Haptic feedback for mobile
    const inputs = document.querySelectorAll('input, button');
    inputs.forEach(input => {
        input.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    });
    
    // Enhanced mobile experience
    if (window.innerWidth <= 768) {
        // Smooth scroll to input on focus
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                setTimeout(() => {
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });
        });
    }
    
    // Price formatting
    priceInput.addEventListener('input', function() {
        if (this.value) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });
});
</script>
@endsection