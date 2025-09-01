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
        border-left: 4px solid #3b82f6;
    }

    .mobile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
    }

    .header-section {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        text-align: center;
        animation: fadeInDown 0.8s ease-out;
    }

    .header-icon {
        background: white;
        border-radius: 50%;
        width: 5rem;
        height: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
        animation: bounceIn 1s ease-out;
    }

    .create-btn {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .group-item {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
        animation: slideInRight 0.6s ease-out;
    }

    .group-item:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-left-color: #1d4ed8;
    }

    .group-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .group-description {
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .group-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-box {
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3b82f6;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 0.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .view-btn { color: #3b82f6; }
    .edit-btn { color: #f59e0b; }
    .delete-btn { color: #ef4444; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-icon {
        background: #f3f4f6;
        border-radius: 50%;
        width: 4rem;
        height: 4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .success-alert {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        animation: slideInDown 0.6s ease-out;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .header-section {
            padding: 1.5rem;
            margin: 0.5rem 0.5rem 1.5rem;
        }
        
        .header-icon {
            width: 4rem;
            height: 4rem;
        }
        
        .mobile-card {
            margin: 0.5rem;
            border-radius: 20px;
        }
        
        .create-btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            justify-content: center;
        }
        
        .group-item {
            margin: 0.5rem;
            border-radius: 16px;
        }
        
        .group-stats {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        
        .action-buttons {
            justify-content: center;
            gap: 1rem;
        }
        
        .action-btn {
            padding: 0.75rem;
            border-radius: 12px;
        }
        
        .page-title {
            font-size: 1.25rem;
            color: #3b82f6;
        }
        
        .page-subtitle {
            font-size: 0.875rem;
            opacity: 0.8;
        }
    }

    @media (min-width: 769px) {
        .container {
            max-width: 72rem;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        
        .desktop-table {
            display: block;
        }
        
        .mobile-list {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .desktop-table {
            display: none;
        }
        
        .mobile-list {
            display: block;
        }
    }

    /* Animations */
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <div class="container">
        @include('buttons')
        
        <div class="header-section">
            <div class="header-icon">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="header-content">
                <div>
                    <h1 class="page-title font-bold text-gray-800">
                        {{ $isFrench ? 'Groupes de Produits' : 'Product Groups' }}
                    </h1>
                    <p class="page-subtitle text-gray-600">
                        {{ $isFrench ? 'Organisez vos produits de boulangerie-pâtisserie' : 'Organize your bakery products' }}
                    </p>
                </div>
                <a href="{{ route('inventory.groups.create') }}" class="create-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $isFrench ? 'Nouveau Groupe' : 'New Group' }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="success-alert">
                <p class="text-green-700 text-center font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="mobile-card">
            @if($groups->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">
                        {{ $isFrench ? 'Aucun groupe créé' : 'No groups created' }}
                    </h3>
                    <p class="mb-4">
                        {{ $isFrench ? 'Commencez par créer votre premier groupe de produits' : 'Start by creating your first product group' }}
                    </p>
                    <a href="{{ route('inventory.groups.create') }}" class="create-btn">
                        {{ $isFrench ? 'Créer votre premier groupe' : 'Create your first group' }}
                    </a>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="desktop-table overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Nom' : 'Name' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Description' : 'Description' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Produits' : 'Products' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Créé le' : 'Created on' }}
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    {{ $isFrench ? 'Actions' : 'Actions' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($groups as $group)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <a href="{{ route('inventory.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $group->name }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $group->description ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $group->products->count() }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $group->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('inventory.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('inventory.groups.edit', $group) }}" class="text-amber-600 hover:text-amber-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('inventory.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce groupe ?' : 'Are you sure you want to delete this group?' }}');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List -->
                <div class="mobile-list">
                    @foreach($groups as $group)
                    <div class="group-item">
                        <h4 class="group-name">
                            <a href="{{ route('inventory.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $group->name }}
                            </a>
                        </h4>
                        <p class="group-description">
                            {{ $group->description ?? ($isFrench ? 'Aucune description' : 'No description') }}
                        </p>
                        
                        <div class="group-stats">
                            <div class="stat-box">
                                <div class="stat-value">{{ $group->products->count() }}</div>
                                <div class="stat-label">{{ $isFrench ? 'Produits' : 'Products' }}</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value">{{ $group->created_at->format('d/m') }}</div>
                                <div class="stat-label">{{ $isFrench ? 'Créé le' : 'Created' }}</div>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="{{ route('inventory.groups.show', $group) }}" class="action-btn view-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('inventory.groups.edit', $group) }}" class="action-btn edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('inventory.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce groupe ?' : 'Are you sure you want to delete this group?' }}');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Staggered animations for mobile
    if (window.innerWidth <= 768) {
        const groupItems = document.querySelectorAll('.group-item');
        groupItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-30px)';
            
            setTimeout(() => {
                item.style.transition = 'all 0.6s ease-out';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 200 + (index * 100));
        });
    }
    
    // Haptic feedback for mobile
    const interactiveElements = document.querySelectorAll('a, button');
    interactiveElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    });
    
    // Enhanced hover effects for action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });
});
</script>
@endsection
