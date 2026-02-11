@extends('layouts.app')
@section('title')
    Mis Prospectos
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sellers.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mis Prospectos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Listado de Prospectos Asignados</h4>
                        <a href="{{ route('sellers.prospects.create') }}" class="btn btn-primary btn-sm">
                            <i data-feather="plus"></i> Nuevo Prospecto
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>Campaña</th>
                                    <th>Contacto</th>
                                    <th>Giro mercantil</th>
                                    <th>Potencial</th>
                                    <th>Estatus</th>
                                    <th>Ubicación</th>
                                    <th>Contacto</th>
                                    <th>Procesos</th>
                                    <th class="d-flex justify-content-end">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prospects as $prosp)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success text-white text-capitalize">{{ $prosp->name_company }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-white text-capitalize">{{ $prosp->name_prospect }}</span>
                                        </td>
                                        <td>{{ $prosp->company }}</td>
                                        <td>
                                            @php
                                                switch ($prosp->potencial) {
                                                    case 'bajo': $badgeClass = 'badge bg-danger'; break;
                                                    case 'medio': $badgeClass = 'badge bg-warning'; break;
                                                    case 'alto': $badgeClass = 'badge bg-success'; break;
                                                    default: $badgeClass = 'badge bg-secondary';
                                                }
                                            @endphp
                                            <span class="{{ $badgeClass }} text-dark text-uppercase">{{ $prosp->potencial }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusText = 'Desconocido';
                                                $statusClass = 'secondary';
                                                switch ($prosp->status) {
                                                    case 0: $statusClass = 'danger'; $statusText = 'Sin Atender'; break;
                                                    case 1: $statusClass = 'warning text-dark'; $statusText = 'En Proceso'; break;
                                                    case 2: $statusClass = 'success'; $statusText = 'Concretado'; break;
                                                    case 3: $statusClass = 'purple'; $statusText = 'Competencia/Instaladores'; break;
                                                    case 4: $statusClass = 'secondary'; $statusText = 'No Funcional'; break;
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>{{ $prosp->location }}</td>
                                        <td>{{ $prosp->contacts }}</td>
                                        <td>
                                            @if ($prosp->status == 0)
                                                    <span class="badge bg-success text-white" style="cursor: pointer;" onclick="startProcess({{ $prosp->id }})">
                                                        <i data-feather="play" style="font-size: 10px;"></i> Iniciar Proceso
                                                    </span>
                                            @elseif($prosp->status == 5)
                                                <span class="badge bg-secondary text-white" style="cursor: pointer;" disabled>
                                                    <i data-feather="check" style="font-size: 10px;"></i> Proceso Finalizado
                                                </span>
                                            @else
                                                <span class="badge bg-success text-white" style="cursor: pointer;" onclick="startProcess({{ $prosp->id }})">
                                                    <i data-feather="play" style="font-size: 10px;"></i> Ver Proceso
                                                </span>
                                            @endif
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('sellers.prospects.edit', $prosp->id) }}">Editar</a>
                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                        onclick="alertSwwet('Observaciones', '{{ $prosp->observations ?? 'Sin Obersaciones' }}')">Ver
                                                        observaciones</a>
                                                    <hr />
                                                </div>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#dataTableExample').DataTable();
        });

        function startProcess(id) {
            Swal.fire({
                title: '¿Iniciar proceso con este prospecto?',
                text: "El prospecto se moverá al tablero Kanban 'En Proceso'.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, iniciar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("sellers.kanban.updateStatus") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            prospect_id: id,
                            status: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        Swal.fire(
                            'Iniciado!',
                            'El prospecto ha sido movido a En Proceso.',
                            'success'
                        ).then(() => {
                            window.location.href = "{{ route('sellers.kanban.index') }}";
                        });
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al iniciar el proceso.',
                            'error'
                        );
                    });
                }
            })
        }
    </script>
@endsection
