@extends('layouts.app') 
@section('title', 'Tablero Kanban de Prospectos')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('sellers.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kanban Prospectos</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">En Proceso</div>
            <div class="card-body kanban-column" id="column-1" data-status="1" style="min-height: 500px; background-color: #f0f0f0;">
                @foreach($enProceso as $prospect)
                    @include('sellers.kanban.card', ['prospect' => $prospect])
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-purple text-white" style="background-color: #6f42c1;">Competencia / Instaladores</div>
            <div class="card-body kanban-column" id="column-3" data-status="3" style="min-height: 500px; background-color: #f0f0f0;">
                @foreach($competencia as $prospect)
                    @include('sellers.kanban.card', ['prospect' => $prospect])
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">Concretado</div>
            <div class="card-body kanban-column" id="column-2" data-status="2" style="min-height: 500px; background-color: #f0f0f0;">
                @foreach($concretado as $prospect)
                    @include('sellers.kanban.card', ['prospect' => $prospect])
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Prospect Details -->
<div class="modal fade" id="prospectModal" role="dialog" aria-labelledby="prospectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prospectModalLabel">Detalles del Prospecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="prospectTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General / Notas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="events-tab" data-toggle="tab" href="#events" role="tab" aria-controls="events" aria-selected="false">Eventos</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3" id="prospectTabContent">
                    <!-- General / Notes Tab -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="font-weight-bold" id="modalProspectName"></h6>
                                <p class="text-muted" id="modalCompanyName"></p>
                                <p><i data-feather="map-pin" class="icon-sm"></i> <span id="modalLocation"></span></p>
                                <p><i data-feather="phone" class="icon-sm"></i> <span id="modalContacts"></span></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="mb-3">Notas</h6>
                        <div id="notesList" style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 5px;" class="mb-3">
                            <!-- Notes will be loaded here -->
                            <p class="text-center text-muted">Cargando notas...</p>
                        </div>

                        <form id="noteForm">
                            <input type="hidden" id="note_prospect_id" name="prospect_id">
                            <div class="form-group">
                                <label for="note_content">Nueva Nota</label>
                                <textarea class="form-control" id="note_content" name="note" rows="2" placeholder="Escribe una nota..." required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm float-right" id="saveNoteBtn">Agregar Nota</button>
                        </form>
                    </div>

                    <!-- Events Tab -->
                    <div class="tab-pane fade" id="events" role="tabpanel" aria-labelledby="events-tab">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    let calendar; // Global calendar instance

    document.addEventListener('DOMContentLoaded', function () {
        const columns = document.querySelectorAll('.kanban-column');

        columns.forEach(column => {
            new Sortable(column, {
                group: 'kanban', // set both lists to same group
                animation: 150,
                onEnd: function (evt) {
                    const itemEl = evt.item;  // dragged HTMLElement
                    const newStatus = evt.to.getAttribute('data-status');
                    const prospectId = itemEl.getAttribute('data-id');

                    // AJAX request to update status
                    if (evt.from !== evt.to) {
                         updateStatus(prospectId, newStatus);
                    }
                }
            });
        });

        function updateStatus(prospectId, status) {
            fetch('{{ route("sellers.kanban.updateStatus") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    prospect_id: prospectId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
            })
            .catch((error) => {
                console.error('Error:', error);
                 Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Error al actualizar el estatus.'
                });
            });
        }

        // Save Note
        $('#saveNoteBtn').on('click', function() {
            const form = document.getElementById('noteForm');
            const data = new FormData(form);
            const jsonData = Object.fromEntries(data.entries());

            fetch('{{ route("sellers.kanban.storeNote") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => response.json())
            .then(data => {
                // Append note to list
                const notesList = document.getElementById('notesList');
                const newNoteHtml = `
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <p class="mb-1 small">${data.note.note}</p>
                            <small class="text-muted">${new Date(data.note.created_at).toLocaleString()}</small>
                        </div>
                    </div>`;
                notesList.insertAdjacentHTML('afterbegin', newNoteHtml);
                form.reset();
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({ icon: 'success', title: 'Nota agregada' });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo guardar la nota', 'error');
            });
        });

        // Initialize Calendar when tab is shown
        $('a[data-toggle="tab"][href="#events"]').on('shown.bs.tab', function (e) {
            if (calendar) {
                calendar.render();
            }
        });
    });

    function openProspectModal(id) {
        $('#prospectModal').modal('show');
        $('#note_prospect_id').val(id);
        
        // Clear previous data
        $('#modalProspectName').text('Cargando...');
        $('#modalCompanyName').text('');
        $('#modalLocation').text('');
        $('#modalContacts').text('');
        $('#notesList').html('<p class="text-center text-muted">Cargando...</p>');

        // Fetch Data
        fetch(`/sellers/kanban/prospect/${id}`)
        .then(response => response.json())
        .then(prospect => {
            // Fill General Info
            $('#modalProspectName').text(prospect.name_prospect);
            $('#modalCompanyName').text(prospect.name_company || prospect.company);
            $('#modalLocation').text(prospect.location);
            $('#modalContacts').text(prospect.contacts);

            // Fill Notes
            const notesList = document.getElementById('notesList');
            notesList.innerHTML = '';
            if (prospect.notes && prospect.notes.length > 0) {
                prospect.notes.forEach(note => {
                    const noteHtml = `
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <p class="mb-1 small">${note.note}</p>
                                <small class="text-muted">${new Date(note.created_at).toLocaleString()}</small>
                            </div>
                        </div>`;
                    notesList.insertAdjacentHTML('beforeend', noteHtml);
                });
            } else {
                notesList.innerHTML = '<p class="text-center text-muted">Sin notas registradas.</p>';
            }

            // Init/Update Calendar
            initCalendar(prospect.id, prospect.events || []);
        });
    }

    function initCalendar(prospectId, eventsData) {
        var calendarEl = document.getElementById('calendar');
        
        // Transform events to FullCalendar format
        var calendarEvents = eventsData.map(event => ({
            title: event.title,
            start: event.start,
            end: event.end,
            description: event.description
        }));

        if (calendar) {
            calendar.destroy(); // Destroy previous instance
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 500,
            events: calendarEvents,
            dateClick: function(info) {
                // Open SweetAlert to add event
                Swal.fire({
                    title: 'Agendar Evento',
                    html: `
                        <input id="swal-evt-title" class="swal2-input" placeholder="Título">
                        <input id="swal-evt-desc" class="swal2-input" placeholder="Descripción">
                        <label>Hora Inicio</label>
                        <input id="swal-evt-start" type="time" class="swal2-input" value="09:00">
                        <label>Hora Fin</label>
                        <input id="swal-evt-end" type="time" class="swal2-input" value="10:00">
                    `,
                    confirmButtonText: 'Guardar',
                    showCancelButton: true,
                    preConfirm: () => {
                        return {
                            title: document.getElementById('swal-evt-title').value,
                            description: document.getElementById('swal-evt-desc').value,
                            startTime: document.getElementById('swal-evt-start').value,
                            endTime: document.getElementById('swal-evt-end').value
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const dateStr = info.dateStr; // YYYY-MM-DD
                        const data = result.value;
                        
                        if (!data.title) {
                            Swal.fire('Error', 'El título es obligatorio', 'error');
                            return;
                        }

                        const startFull = `${dateStr}T${data.startTime}`;
                        const endFull = `${dateStr}T${data.endTime}`;

                        // Save Event AJAX
                        fetch('{{ route("sellers.kanban.storeEvent") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                prospect_id: prospectId,
                                title: data.title,
                                description: data.description,
                                start: startFull,
                                end: endFull
                            })
                        })
                        .then(res => res.json())
                        .then(resp => {
                            calendar.addEvent({
                                title: resp.event.title,
                                start: resp.event.start,
                                end: resp.event.end,
                                description: resp.event.description
                            });
                            Swal.fire('Guardado', 'Evento agendado correctamente', 'success');
                        });
                    }
                });
            },
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    text: info.event.extendedProps.description || 'Sin descripción',
                    footer: `Inicio: ${info.event.start.toLocaleString()}`
                });
            }
        });
        
        // Delay render to ensure tab is visible or handle via tab shown event
        setTimeout(() => { calendar.render(); }, 200);
    }

    function convertToClient(id) {
        Swal.fire({
            title: '¿Convertir prospecto a cliente?',
            text: "Se creará un nuevo cliente con la información de este prospecto.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, convertir'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("sellers.kanban.convertToClient") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        prospect_id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.message) {
                        Swal.fire(
                            'Convertido!',
                            data.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        data.message || 'No se pudo convertir el prospecto a cliente.',
                        'error'
                    );
                });
            }
        })
    }
</script>
@endsection
