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
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .mobile-card:hover::before {
        transform: scaleX(1);
    }

    .header-icon {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
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
        transform: scale(1.1) rotate(5deg);
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
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

    .matiere-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .matiere-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    .quantity-display {
        background: #dbeafe;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        color: #1e40af;
        text-align: center;
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
            text-align: center;
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
            font-size: 1.25rem;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }
        
        .matiere-card {
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .matiere-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .quantity-display {
            font-size: 1.1rem;
            padding: 0.75rem;
            border-radius: 12px;
        }
        
        .matiere-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e40af;
            text-align: center;
            margin-bottom: 1rem;
        }
    }

    @media (min-width: 769px) {
        .container {
            max-width: 90rem;
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
        
        .matiere-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1rem;
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

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <div class="container">
        @include('buttons')
        
        <div class="mb-6">
            <div class="header-icon">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h1 class="page-title font-bold text-gray-800">
                {{ $isFrench ? 'Calcul des Manquants - Inventaire' : 'Shortage Calculation - Inventory' }}
            </h1>
            <p class="text-center text-gray-600 mb-6">
                {{ $isFrench ? 'Saisissez les quantités réelles (en unités : sac, alvéole, bidon...) pour calculer les manquants' : 'Enter actual quantities (in units: bag, cell, container...) to calculate shortages' }}
            </p>
        </div>

        <div class="mobile-card">
            <form action="{{ route('manquant-inventaire.calculer') }}" method="POST" id="inventaireForm">
                @csrf

                <div class="matiere-grid">
                    @foreach($matieres as $matiere)
                        <div class="matiere-card">
                            <div class="matiere-name">{{ $matiere->nom }}</div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="form-label">
                                        {{ $isFrench ? 'Quantité Attendue' : 'Expected Quantity' }}
                                    </label>
                                    <div class="quantity-display">
                                        {{ number_format($matiere->quantite, 2) }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="quantite_{{ $matiere->id }}" class="form-label">
                                        {{ $isFrench ? 'Quantité Réelle' : 'Actual Quantity' }}
                                    </label>
                                    <input 
                                        type="number" 
                                        name="quantites_reelles[{{ $matiere->id }}]" 
                                        id="quantite_{{ $matiere->id }}"
                                        class="form-input" 
                                        step="0.01" 
                                        min="0"
                                        placeholder="0.00"
                                        required
                                    >
                                </div>
                            </div>
                            
                            <div class="text-xs text-gray-500 text-center">
                                {{ $isFrench ? 'Prix unitaire' : 'Unit price' }}: {{ number_format($matiere->prix_par_unite_minimale, 2) }} XAF/{{ $matiere->unite_minimale }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span id="submitText">{{ $isFrench ? 'Calculer les Manquants' : 'Calculate Shortages' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('inventaireForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const inputs = document.querySelectorAll('input[type="number"]');
    
    // Form validation with visual feedback
    function validateInput(input) {
        const value = parseFloat(input.value);
        if (value >= 0 && input.value !== '') {
            input.style.borderColor = '#10b981';
            input.style.backgroundColor = '#f0fdf4';
        } else {
            input.style.borderColor = '#e5e7eb';
            input.style.backgroundColor = 'white';
        }
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', () => validateInput(input));
        input.addEventListener('blur', () => validateInput(input));
    });
    
    // Enhanced form submission
    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitText.textContent = '{{ $isFrench ? "Calcul en cours..." : "Calculating..." }}';
    });
    
    // Haptic feedback for mobile
    const allInputs = document.querySelectorAll('input, button');
    allInputs.forEach(input => {
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
        
        // Add staggered animation for cards
        const cards = document.querySelectorAll('.matiere-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    }
});
</script>
@endsection
