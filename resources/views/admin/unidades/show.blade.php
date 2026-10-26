@extends('layouts.app') 
@section('title', 'Detalles de la Unidad')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('unidades.index') }}">Registro de Unidades</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles de la Unidad</li>
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
                            <i data-feather="truck" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">{{ $unidad->marca ?? 'N/A' }} {{ $unidad->submarca ?? '' }}</h5>
                    <p class="text-muted">{{ $unidad->anio_unidad ?? 'Año no especificado' }}</p>
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Placa:</span>
                    <h5 class="fw-bold"><span class="badge bg-secondary">{{ $unidad->placa }}</span></h5>
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Económico:</span>
                    <h5 class="fw-bold">{{ $unidad->economico }}</h5>
                </div>

                <div class="d-flex flex-column mb-3">
                    <span class="text-muted tx-11 fw-bolder mb-1 text-uppercase">Cliente Asignado:</span>
                    @if($unidad->cliente)
                        <div class="d-flex align-items-center">
                            @if ($unidad->cliente->avatar != null)
                                <img src="{{ asset($unidad->cliente->avatar) }}" alt="Avatar" class="rounded-circle mr-2" style="width: 30px; height: 30px;">
                            @else
                                <i data-feather="user" class="icon-sm mr-2 text-primary"></i>
                            @endif
                            <span class="fw-bold text-primary">{{ ucwords($unidad->cliente->nombre) }}</span>
                        </div>
                    @else
                        <span class="text-danger fw-bold"><i data-feather="x-circle" class="icon-sm mr-1"></i> Sin Asignar</span>
                    @endif
                </div>

                <hr class="mt-4 mb-4">
                
                <div class="d-grid gap-2">
                    <a href="{{ route('unidades.edit', $unidad->id) }}" class="btn btn-primary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar Unidad
                    </a>
                    <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary btn-icon-text">
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
                
                <!-- Datos Generales -->
                <h6 class="text-primary mb-3"><i data-feather="info" class="icon-sm mr-1"></i> Datos de la Unidad</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Tipo de unidad</th>
                                <td>{{ $unidad->tipo_unidad }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">VIN</th>
                                <td><span class="badge bg-secondary">{{ $unidad->vin ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Número de motor</th>
                                <td><span class="badge bg-primary text-white">{{ $unidad->numero_de_motor ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Número de emergencias</th>
                                <td>
                                    @if($unidad->numero_de_emergencia)
                                        <a href="tel:{{ $unidad->numero_de_emergencia }}">
                                            <i data-feather="phone" class="icon-sm mr-1"></i> {{ $unidad->numero_de_emergencia }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Observaciones</th>
                                <td>{{ $unidad->observaciones ?? 'Sin observaciones' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Instalación y Cobros -->
                <h6 class="text-primary mb-3"><i data-feather="calendar" class="icon-sm mr-1"></i> Instalación y Cobro</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Fecha de Instalación</th>
                                <td><span class="badge bg-success fs-6">{{ \Carbon\Carbon::parse($unidad->fecha_instalacion)->format('Y-m-d') }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Días faltantes para cobro</th>
                                <td>{!! App\Models\Unidades::diasFaltantesCobro($unidad->fecha_cobro) !!}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Costo Plataforma / SIM / Mensual</th>
                                <td>
                                    <span class="badge bg-light text-dark border">${{ $unidad->costo_plataforma ?? '0' }}</span> / 
                                    <span class="badge bg-light text-dark border">${{ $unidad->costo_sim ?? '0' }}</span> / 
                                    <span class="badge bg-success text-white border">${{ $unidad->pago_mensual ?? '0' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Empresa / Plataforma</th>
                                <td>{{ $unidad->name_empresa ?? 'N/A' }}</td>
                            </tr>
                            @if($unidad->credenciales)
                                @php $creds = json_decode($unidad->credenciales, true); @endphp
                                @if(is_array($creds) && (isset($creds['user']) || isset($creds['pass'])))
                                    <tr>
                                        <th class="bg-light">Credenciales</th>
                                        <td>
                                            @if(isset($creds['user']) && is_array($creds['user']))
                                                @foreach($creds['user'] as $key => $user)
                                                    <div class="mb-1">
                                                        <strong>User:</strong> {{ $user }} 
                                                        | <strong>Pass:</strong> {{ $creds['pass'][$key] ?? '' }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Dispositivos y SIM -->
                <h6 class="text-primary mb-3"><i data-feather="cpu" class="icon-sm mr-1"></i> Dispositivos y Conectividad</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Dispositivo Instalado</th>
                                <td>
                                    <span class="badge @if ($unidad->dispositivo_instalado == 'null' || !isset($unidad->dispositivo_instalado)) bg-danger @else bg-primary @endif fs-6">
                                        {{ $unidad->dispositivo_instalado != 'null' && isset($unidad->dispositivo_instalado) ? $unidad->dispositivo_instalado : 'Sin Asignar' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">IMEI del Dispositivo</th>
                                <td><span class="badge bg-primary fs-6">{{ $unidad->imei ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">SIM Asignada</th>
                                <td>
                                    @if($unidad->simcontrol)
                                        <span class="badge bg-info text-white fs-6">
                                            {{ ucwords($unidad->simcontrol->compañia) }} - {{ $unidad->simcontrol->numero_publico }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6">Sin Asignar</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Cuenta con apagado del motor</th>
                                <td>
                                    @if($unidad->cuenta_con_apagado == 'on')
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
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

@if($unidad->foto_unidad)
<div class="row mt-1">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4"><i data-feather="image" class="icon-md mr-2"></i> Fotos de la Unidad</h6>
                <div class="row">
                    @php $fotos = json_decode($unidad->foto_unidad, true); @endphp
                    @if(is_array($fotos))
                        @foreach($fotos as $foto)
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="javascript:void(0)" onclick="viewImages('Foto de la unidad', '{{ $unidad->foto_unidad }}' ,'{{ asset('uploads/fotos_unidades') }}')" class="d-block border rounded shadow-sm text-decoration-none photo-link h-100">
                                    <div class="rounded" style="height: 150px; overflow: hidden; background-color: #000;">
                                        <img src="{{ asset('uploads/fotos_unidades/'.$foto) }}" alt="Foto Unidad" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: opacity 0.3s;">
                                    </div>
                                    <div class="p-2 text-center bg-light border-top">
                                        <span class="d-block text-body fw-bolder"><i data-feather="zoom-in" class="icon-sm mr-1"></i> Ver Galería</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .photo-link {
        transition: all 0.2s ease-in-out;
    }
    .photo-link:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .photo-link:hover img {
        opacity: 1 !important;
    }
</style>
@endsection
