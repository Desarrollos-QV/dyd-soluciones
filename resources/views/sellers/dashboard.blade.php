@extends('layouts.app')
@section('title')
    Dashboard Vendedores
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- KPIs -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Prospectos Asignados</h5>
                            <h2 class="mt-2 text-white">{{ $assigned_count }}</h2>
                        </div>
                        <i data-feather="users" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0 text-dark">En Proceso</h5>
                            <h2 class="mt-2 text-dark">{{ $in_process_count }}</h2>
                        </div>
                        <i data-feather="activity" class="text-dark" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Concretados</h5>
                            <h2 class="mt-2 text-white">{{ $completed_count }}</h2>
                        </div>
                        <i data-feather="check-circle" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Notes -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white font-weight-bold">Últimas Notas</div>
                <div class="card-body p-0">
                    @if($latest_notes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($latest_notes as $note)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 text-primary">{{ $note->prospect->name_company ?? $note->prospect->name_prospect }}</h6>
                                        <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 small">{{ Str::limit($note->note, 100) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">No hay notas recientes.</div>
                    @endif
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('sellers.kanban.index') }}" class="btn btn-sm btn-outline-primary">Ir al Kanban</a>
                </div>
            </div>
        </div>

        <!-- Mini Calendar -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white font-weight-bold">Calendario de Eventos</div>
                <div class="card-body p-2">
                    <div id="miniCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
@php
    $calendarEvents = $events->map(function($event) {
        return [
            'title' => $event->title . ' - ' . ($event->prospect->name_company ?? $event->prospect->name_prospect),
            'start' => $event->start,
            'end' => $event->end,
            'url' => route('sellers.kanban.index')
        ];
    });
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('miniCalendar');
        var events = @json($calendarEvents);

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'listWeek', // List view for updates
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'listWeek,dayGridMonth'
            },
            height: 400,
            events: events,
            locale: 'es',
            eventClick: function(info) {
                // Optional: Show details or navigate
            }
        });
        calendar.render();
    });
</script>
@endsection
