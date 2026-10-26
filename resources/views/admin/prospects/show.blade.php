@extends('layouts.app') 
@section('title', 'Detalles del Prospecto')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('prospects.index') }}">Registro de Prospectos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles del Prospecto</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="card-title mb-0">Información del Prospecto</h6>
                    <a href="{{ route('prospects.edit', $prospect->id) }}" class="btn btn-primary btn-icon-text btn-sm">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 30%;">Campaña (Empresa)</th>
                                <td>{{ $prospect->name_company }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Contacto</th>
                                <td>{{ $prospect->name_prospect }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Giro mercantil</th>
                                <td>{{ $prospect->company }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Ubicación</th>
                                <td>{{ $prospect->location }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Detalles de Contacto</th>
                                <td>{{ $prospect->contacts }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Observaciones</th>
                                <td>
                                    @if(empty($prospect->observations))
                                        <span class="text-muted">Sin observaciones</span>
                                    @else
                                        {{ $prospect->observations }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Estado y Asignación</h6>
                
                <div class="mb-4">
                    <label class="tx-11 fw-bolder mb-0 text-uppercase">Potencial</label>
                    <div class="mt-2">
                        @php
                            switch ($prospect->potencial) {
                                case 'bajo': $badgeClass = 'badge bg-danger'; break;
                                case 'medio': $badgeClass = 'badge bg-warning'; break;
                                case 'alto': $badgeClass = 'badge bg-success'; break;
                                default: $badgeClass = 'badge bg-secondary';
                            }
                        @endphp
                        <span class="{{ $badgeClass }} text-dark text-uppercase fs-6 px-3 py-2" style="font-size: 1rem;">
                            {{ $prospect->potencial }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="tx-11 fw-bolder mb-0 text-uppercase">Estatus del Prospecto</label>
                    <div class="mt-2">
                        @php
                            $statusOptions = [
                                0 => ['class' => 'danger', 'text' => 'Sin Atender'],
                                1 => ['class' => 'warning text-dark', 'text' => 'En Proceso'],
                                2 => ['class' => 'success', 'text' => 'Concretado'],
                                3 => ['class' => 'purple text-white', 'text' => 'Competencia/Instaladores'],
                                4 => ['class' => 'secondary', 'text' => 'No Funcional'],
                            ];
                            $st = $statusOptions[$prospect->status] ?? ['class' => 'secondary', 'text' => 'Desconocido'];
                        @endphp
                        <span class="badge bg-{{ $st['class'] }} fs-6 px-3 py-2" style="font-size: 1rem;">
                            {{ $st['text'] }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="tx-11 fw-bolder mb-0 text-uppercase">Vendedor Asignado</label>
                    <div class="mt-2">
                        @if($prospect->seller)
                            <div class="d-flex align-items-center">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="text-body mb-1">{{ $prospect->seller->name }}</h6>
                                    </div>
                                    <p class="text-muted tx-13">{{ $prospect->seller->email ?? '' }}</p>
                                </div>
                            </div>
                        @else
                            <span class="text-danger fw-bold">Sin Asignar</span>
                        @endif
                    </div>
                </div>

                <hr class="mt-4 mb-4">
                
                <div class="d-grid gap-2">
                    <a href="{{ route('prospects.index') }}" class="btn btn-outline-secondary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
