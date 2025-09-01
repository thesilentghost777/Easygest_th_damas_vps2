@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @include('buttons')

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-4 mobile:mb-6">
            <h1 class="text-2xl font-bold text-blue-600 mobile:text-xl mobile:text-center">
                {{ $isFrench ? 'Calendrier' : 'Calendar' }}
            </h1>
        </div>
        <div id="calendar" class="calendar-container"></div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
.fc {
    --fc-button-bg-color: #2563eb;
    --fc-button-border-color: #2563eb;
    --fc-button-hover-bg-color: #1d4ed8;
    --fc-button-hover-border-color: #1d4ed8;
    --fc-today-bg-color: #dbeafe;
    --fc-event-bg-color: #3b82f6;
    --fc-event-border-color: #2563eb;
}

.fc-event {
    border-radius: 4px;
    padding: 2px;
    transition: all 0.3s ease;
}

.fc-event:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

/* Mobile styles */
@media (max-width: 768px) {
    .mobile\:text-xl {
        font-size: 1.25rem;
    }
    
    .mobile\:text-center {
        text-align: center;
    }
    
    .mobile\:mb-6 {
        margin-bottom: 1.5rem;
    }
    
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .calendar-container {
        transform: scale(0.95);
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Calendar mobile optimizations */
    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .fc-button {
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    
    .fc-button:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
    }
    
    .fc-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e40af;
    }
    
    .fc-daygrid-day {
        min-height: 60px;
        transition: background-color 0.3s ease;
    }
    
    .fc-daygrid-day:hover {
        background-color: #f1f5f9;
    }
    
    .fc-daygrid-day-number {
        font-size: 14px;
        font-weight: 500;
        padding: 4px;
        transition: all 0.3s ease;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background-color: #2563eb;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }
    
    .fc-event {
        font-size: 11px;
        padding: 2px 4px;
        margin: 1px 0;
        border-radius: 6px;
        transform: translateX(0);
        transition: all 0.3s ease;
    }
    
    .fc-event:hover {
        transform: translateX(2px) scale(1.02);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
    }
    
    /* Mobile navigation enhancement */
    .fc-prev-button, .fc-next-button {
        border-radius: 50% !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .fc-prev-button:hover, .fc-next-button:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .fc-today-button {
        border-radius: 20px !important;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .fc-today-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(0.95);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Touch feedback */
@media (max-width: 768px) {
    .fc-button:active,
    .fc-daygrid-day:active {
        background-color: #1e40af !important;
        color: white !important;
        transform: scale(0.98);
    }
    
    /* Smooth scrolling for mobile */
    .fc-scroller {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Better touch targets */
    .fc-daygrid-day {
        min-height: 70px;
    }
    
    .fc-col-header-cell {
        padding: 12px 4px;
        font-weight: 600;
        color: #374151;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
}
</style>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth <= 768 ? 'listWeek' : 'dayGridMonth',
        locale: isFrench ? 'fr' : 'en',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: window.innerWidth <= 768 ? 'listWeek,dayGridMonth' : 'dayGridMonth,timeGridWeek'
        },
        buttonText: isFrench ? {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        } : {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List'
        },
        height: 'auto',
        aspectRatio: window.innerWidth <= 768 ? 1.0 : 1.35,
        events: [
            @if($reposConge)
                {
                    title: isFrench ? 'Jour de repos' : 'Rest Day',
                    daysOfWeek: [ {{ $jourNumber }} ],
                    color: '#22c55e',
                    textColor: '#ffffff',
                    borderColor: '#16a34a'
                },
                @if($reposConge->conges)
                {
                    title: isFrench ? 'Congés' : 'Vacation',
                    start: '{{ $reposConge->debut_c->format("Y-m-d") }}',
                    end: '{{ $reposConge->debut_c->addDays($reposConge->conges)->format("Y-m-d") }}',
                    color: '#3b82f6',
                    textColor: '#ffffff',
                    borderColor: '#1d4ed8'
                }
                @endif
            @endif
        ],
        eventDidMount: function(info) {
            // Add animation to events when they mount
            info.el.style.opacity = '0';
            info.el.style.transform = 'scale(0.8)';
            setTimeout(() => {
                info.el.style.transition = 'all 0.3s ease';
                info.el.style.opacity = '1';
                info.el.style.transform = 'scale(1)';
            }, 100);
        },
        windowResize: function() {
            // Responsive view switching
            if (window.innerWidth <= 768) {
                calendar.changeView('listWeek');
            } else {
                calendar.changeView('dayGridMonth');
            }
        }
    });
    
    calendar.render();
    
    // Add loading animation
    const calendarContainer = document.querySelector('.calendar-container');
    calendarContainer.style.opacity = '0';
    calendarContainer.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        calendarContainer.style.transition = 'all 0.6s ease-out';
        calendarContainer.style.opacity = '1';
        calendarContainer.style.transform = 'translateY(0)';
    }, 200);
});
</script>
@endsection
