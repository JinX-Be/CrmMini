@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Calendar</h1>
    <a href="{{ route('appointments.create') }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">
        + New appointment
    </a>
</div>

<div class="bg-white rounded-xl shadow p-4">
    <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            height: 'auto',
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json(route('appointments.events')),
            eventClick: function (info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            },
            dateClick: function (info) {
                window.location.href = @json(route('appointments.create')) + '?date=' + info.dateStr;
            }
        });
        calendar.render();
    });
</script>
@endsection
