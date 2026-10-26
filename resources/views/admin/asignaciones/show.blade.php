@extends('layouts.app') 
@section('title', 'Detalles del Servicio')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignements.index') }}">Registro de Servicios</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles del Servicio</li>
    </ol>
</nav>

<div class="row">
    <!-- Panel Izquierdo: Resumen y Acciones -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i data-feather="tool" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-1">Servicio #{{ $assignement->id }}</h5>
                    <p class="text-muted text-uppercase">{{ $assignement->tipo_servicio }}</p>
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Estado del Servicio:</span>
                    <h5 class="fw-bold">
                        @php
                            $statusMap = [
                                0 => ['class' => 'secondary', 'text' => 'Pendiente'],
                                1 => ['class' => 'info', 'text' => 'Asignado / En Progreso'],
                                2 => ['class' => 'primary', 'text' => 'En Camino'],
                                3 => ['class' => 'warning', 'text' => 'Trabajando'],
                                4 => ['class' => 'success', 'text' => 'Finalizado'],
                                5 => ['class' => 'dark', 'text' => 'Cancelado/Reagendado']
                            ];
                            $st = $statusMap[$assignement->status] ?? ['class' => 'secondary', 'text' => 'Desconocido'];
                        @endphp
                        <span class="badge bg-{{ $st['class'] }}">{{ $st['text'] }}</span>
                    </h5>
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Cliente Asignado:</span>
                    @if($assignement->cliente)
                        <div class="d-flex align-items-center mt-1">
                            @if ($assignement->cliente->avatar != null)
                                <img src="{{ asset($assignement->cliente->avatar) }}" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                                <i data-feather="user" class="icon-sm mr-2 text-primary"></i>
                            @endif
                            <span class="fw-bold text-primary">{{ ucwords($assignement->cliente->nombre) }}</span>
                        </div>
                    @else
                        <span class="text-danger fw-bold"><i data-feather="x-circle" class="icon-sm mr-1"></i> Sin Cliente Asignado</span>
                    @endif
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Técnico Asignado:</span>
                    @if($assignement->tecnico)
                        <div class="d-flex align-items-center mt-1">
                            @if ($assignement->tecnico->avatar != null)
                                <img src="{{ asset($assignement->tecnico->avatar) }}" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                                <i data-feather="user" class="icon-sm mr-2 text-info"></i>
                            @endif
                            <span class="fw-bold text-info">{{ ucwords($assignement->tecnico->name . ' ' . $assignement->tecnico->lastname) }}</span>
                        </div>
                    @else
                        <span class="text-danger fw-bold"><i data-feather="x-circle" class="icon-sm mr-1"></i> Sin Asignar</span>
                    @endif
                </div>

                <hr class="mt-4 mb-4">
                
                <div class="d-grid gap-2">
                    <a href="{{ route('assignements.edit', $assignement->id) }}" class="btn btn-primary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar Servicio
                    </a>
                    <a href="{{ route('assignements.index') }}" class="btn btn-outline-secondary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel Derecho: Información Detallada -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Información Detallada</h6>
                
                <!-- Datos de Servicio -->
                <h6 class="text-primary mb-3"><i data-feather="info" class="icon-sm mr-1"></i> Datos del Servicio</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Tipo de Servicio</th>
                                <td>{{ $assignement->tipo_servicio }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Teléfono de Contacto</th>
                                <td>
                                    @if($assignement->tel_contact)
                                        <a href="tel:{{ $assignement->tel_contact }}"><i data-feather="phone" class="icon-sm mr-1"></i> {{ $assignement->tel_contact }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Encargado de Recibir</th>
                                <td>{{ $assignement->encargado_recibir ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Ubicación</th>
                                <td>
                                    {{ $assignement->location ?? 'N/A' }}
                                    @if($assignement->lat && $assignement->lng)
                                        <a href="https://google.com/maps?q={{$assignement->lat}},{{$assignement->lng}}" target="_blank" class="ms-2 btn btn-xs btn-outline-primary">
                                            <i data-feather="map-pin" class="icon-sm mr-1"></i> Ver Mapa
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Viáticos</th>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold fs-6">${{ $assignement->viaticos ?? '0.00' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Observaciones de Asignación</th>
                                <td>{{ $assignement->observaciones ?? 'Sin observaciones' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Comentarios (Del Técnico)</th>
                                <td>
                                    @if(isset($assignement->getFirma) && $assignement->getFirma->comentarios)
                                        <span class="fst-italic text-secondary">"{{ $assignement->getFirma->comentarios }}"</span>
                                    @else
                                        <span class="text-muted">Sin comentarios</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Unidades / Flota Asignada -->
                @php
                    $unidadesAsignadas = ($assignement->unidades && $assignement->unidades->count() > 0)
                        ? $assignement->unidades
                        : ($assignement->unidad ? collect([$assignement->unidad]) : collect([]));
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-primary mb-0">
                        <i data-feather="truck" class="icon-sm mr-1"></i> 
                        Unidades / Vehículos Asignados
                        @if($unidadesAsignadas->count() > 1)
                            <span class="badge bg-info ms-1">{{ $unidadesAsignadas->count() }} Vehículos (Flota)</span>
                        @endif
                    </h6>
                </div>

                @if($unidadesAsignadas->count() > 0)
                    <div class="table-responsive mb-4">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Económico</th>
                                    <th>Placa</th>
                                    <th>Vehículo / Modelo</th>
                                    <th>Dispositivo GPS</th>
                                    <th>SIM / Línea</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unidadesAsignadas as $idx => $u)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $idx + 1 }}</span></td>
                                        <td><strong>{{ $u->economico ?? 'S/N' }}</strong></td>
                                        <td><span class="badge bg-light text-dark border">{{ $u->placa ?? 'N/A' }}</span></td>
                                        <td>{{ trim(($u->marca ?? '') . ' ' . ($u->submarca ?? '') . ' ' . ($u->anio_unidad ?? '')) ?: 'N/A' }}</td>
                                        <td>
                                            @if($u->inventario)
                                                <i data-feather="cpu" class="icon-xs text-primary me-1"></i> {{ $u->inventario->name }} <span class="text-muted tx-12">({{ $u->inventario->imei ?? $u->inventario->serial }})</span>
                                            @else
                                                <span class="text-muted">Sin GPS asignado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->simcontrol)
                                                <i data-feather="smartphone" class="icon-xs text-success me-1"></i> {{ $u->simcontrol->sim_card }}
                                            @else
                                                <span class="text-muted">Sin SIM</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary mb-4">
                        <i data-feather="alert-circle" class="icon-sm mr-1"></i> No hay unidades asignadas a este servicio.
                    </div>
                @endif

                <!-- Archivos y Reportes -->
                <h6 class="text-primary mb-3"><i data-feather="file" class="icon-sm mr-1"></i> Archivos y Reportes</h6>
                <div class="row">
                    @if($assignement->file)
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-light shadow-sm">
                            <div class="d-flex align-items-center">
                                <i data-feather="file-text" class="text-primary mr-2" style="width: 24px; height: 24px;"></i>
                                <span class="fw-bold">Orden de Servicio</span>
                            </div>
                            <a href="{{ asset('uploads/ordenes_servicio/'.$assignement->file) }}" target="_blank" class="btn btn-sm btn-primary">
                                Ver Archivo
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(isset($assignement->getFirma))
                        @if($assignement->getFirma->firma != null)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-light shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i data-feather="download" class="text-success mr-2" style="width: 24px; height: 24px;"></i>
                                    <span class="fw-bold">Reporte en PDF</span>
                                </div>
                                <a href="{{ route('servicios_agendados.generarPDF', ['id' => base64_encode($assignement->id)]) }}" target="_blank" class="btn btn-sm btn-success">
                                    Descargar
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($assignement->getFirma->registro_fotografico != null)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-light shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i data-feather="camera" class="text-info mr-2" style="width: 24px; height: 24px;"></i>
                                    <span class="fw-bold text-dark">Registro Fotográfico</span>
                                </div>
                                <a href="{{ route('servicios_agendados.seePhotoRecord', ['id' => base64_encode($assignement->id)]) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                    Ver Galería
                                </a>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if(!$assignement->file && !(isset($assignement->getFirma) && ($assignement->getFirma->firma != null || $assignement->getFirma->registro_fotografico != null)))
                        <div class="col-12">
                            <div class="alert alert-secondary mb-0" role="alert">
                                <i data-feather="info" class="icon-sm mr-2"></i> No hay archivos o reportes generados para este servicio.
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
