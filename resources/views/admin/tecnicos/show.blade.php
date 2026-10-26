@extends('layouts.app') 
@section('title', 'Detalles del Técnico')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tecnicos.index') }}">Registro de Técnicos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles del Técnico</li>
    </ol>
</nav>

<div class="row">
    <!-- Panel Izquierdo: Resumen y Acciones -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-4 d-flex justify-content-center">
                    @if ($tecnico->avatar)
                        <img src="{{ asset($tecnico->avatar) }}" alt="Avatar de {{ $tecnico->name }}" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                    @else
                        <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Sin Imagen" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                    @endif
                </div>
                <h5 class="card-title mb-1 fw-bold text-capitalize">{{ $tecnico->name }} {{ $tecnico->lastname }}</h5>
                <p class="text-muted mb-4">{{ $tecnico->email }}</p>

                <div class="d-flex flex-column mb-3 text-start">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Teléfono de Contacto:</span>
                    <h5 class="fw-bold">
                        @if($tecnico->telefono)
                            <a href="tel:{{ $tecnico->telefono }}"><i data-feather="phone" class="icon-sm mr-1"></i> {{ $tecnico->telefono }}</a>
                        @else
                            N/A
                        @endif
                    </h5>
                </div>

                <div class="d-flex flex-column mb-3 text-start">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Identificación Oficial:</span>
                    @if($tecnico->identificacion)
                        <a href="{{ asset($tecnico->identificacion) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                            <i data-feather="external-link" class="icon-sm mr-1"></i> Ver Identificación
                        </a>
                    @else
                        <span class="text-danger fw-bold"><i data-feather="x-circle" class="icon-sm mr-1"></i> No disponible</span>
                    @endif
                </div>

                <hr class="mt-4 mb-4">
                
                <div class="d-grid gap-2">
                    <a href="{{ route('tecnicos.edit', $tecnico->id) }}" class="btn btn-primary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar Técnico
                    </a>
                    <a href="{{ route('tecnicos.index') }}" class="btn btn-outline-secondary btn-icon-text">
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
                
                <!-- Perfil Profesional -->
                <h6 class="text-primary mb-3"><i data-feather="briefcase" class="icon-sm mr-1"></i> Perfil Profesional</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Escolaridad</th>
                                <td>{{ $tecnico->schooling }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Experiencia</th>
                                <td>{{ $tecnico->experience }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Habilidades de comunicación</th>
                                <td>{{ $tecnico->skills }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Recursos y Herramientas -->
                <h6 class="text-primary mb-3"><i data-feather="tool" class="icon-sm mr-1"></i> Recursos y Equipamiento</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">¿Cuenta con Licencia?</th>
                                <td>
                                    <span class="badge {{ strtolower(trim($tecnico->licence)) == 'si' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        {{ $tecnico->licence }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">¿Cuenta con Vehículo?</th>
                                <td>
                                    <span class="badge {{ strtolower(trim($tecnico->vehicle)) == 'si' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        {{ $tecnico->vehicle }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">¿Cuenta con Uniforme?</th>
                                <td>
                                    <span class="badge {{ strtolower(trim($tecnico->uniform)) == 'si' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        {{ $tecnico->uniform }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Herramientas</th>
                                <td>
                                    @php $tools = json_decode($tecnico->tools, true); @endphp
                                    @if(is_array($tools) && count($tools) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($tools as $tool)
                                                <span class="badge bg-primary text-white p-2" style="font-size: 0.85rem;">{{ $tool }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Sin Herramientas Registradas</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
