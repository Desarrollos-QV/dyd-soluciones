@extends('layouts.app')
@section('title', 'Detalles del Cliente - ' . ucwords($cliente->nombre))
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles del Cliente</li>
    </ol>
</nav>

<div class="row">
    <!-- Columna Izquierda: Perfil -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-4 d-flex justify-content-center">
                    @if ($cliente->avatar != null)
                        <img src="{{ asset($cliente->avatar) }}" alt="Avatar de {{ $cliente->nombre }}" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                    @else
                        @if ($cliente->sex == 'male')
                            <img src="{{ asset('assets/images/men.png') }}" alt="Avatar de {{ $cliente->nombre }}" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                        @elseif ($cliente->sex == 'female')
                            <img src="{{ asset('assets/images/women.jpg') }}" alt="Avatar de {{ $cliente->nombre }}" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                        @else
                            <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Sin Imagen" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                        @endif
                    @endif
                </div>
                <h5 class="card-title mb-1 fw-bold">{{ ucwords($cliente->nombre) }}</h5>
                <p class="text-muted mb-4">{{ $cliente->email }}</p>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar Cliente
                    </a>
                    <a href="{{ route('clientes.downloadDocs', $cliente->id) }}" class="btn btn-success btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="download"></i> Descargar Zip de Documentos
                    </a>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Información -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Información Detallada</h6>
                
                <h6 class="text-primary mb-3"><i data-feather="user" class="icon-sm mr-1"></i> Datos Generales</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Nombre Completo</th>
                                <td>{{ ucwords($cliente->nombre) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Identidad (Sexo)</th>
                                <td>
                                    @if($cliente->sex == 'male')
                                        Hombre
                                    @elseif($cliente->sex == 'female')
                                        Mujer
                                    @else
                                        {{ ucfirst($cliente->sex) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Dirección</th>
                                <td>{{ $cliente->direccion }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Teléfono Principal</th>
                                <td>{{ $cliente->numero_contacto }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Número de Emergencias</th>
                                <td>{{ $cliente->numero_alterno ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="text-primary mb-3"><i data-feather="briefcase" class="icon-sm mr-1"></i> Datos de la Empresa</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Nombre de la empresa</th>
                                <td>{{ ucwords($cliente->empresa) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Tipo de empresa</th>
                                <td>{{ $cliente->tipo_empresa }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Dirección de la empresa</th>
                                <td>{{ $cliente->direccion_empresa }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4"><i data-feather="folder" class="icon-md mr-2"></i> Documentos del Cliente</h6>
                <div class="row">
                    <!-- Comprobante Domicilio -->
                    <div class="col-md-3 col-sm-6 mb-4">
                        <p class="text-muted fw-bold mb-2 text-center text-uppercase" style="font-size: 0.75rem;">Comprobante Domicilio</p>
                        @if($cliente->comprobante_domicilio != null)
                            <a href="{{ url($cliente->comprobante_domicilio) }}" target="_blank" class="d-block border rounded p-2 shadow-sm text-decoration-none document-link h-100">
                                <div class="mb-2 rounded" style="height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                                    <img src="{{ url($cliente->comprobante_domicilio) }}" alt="Comprobante Domicilio" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                                <div class="text-center">
                                    <span class="d-block text-primary fw-bolder"><i data-feather="external-link" class="icon-sm mr-1"></i> Ver Documento</span>
                                </div>
                            </a>
                        @else
                            <div class="border rounded p-3 bg-light text-center h-100 d-flex flex-column justify-content-center">
                                <i data-feather="file-minus" class="text-muted mb-2 mx-auto"></i>
                                <span class="text-muted">No disponible</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Copia de Factura -->
                    <div class="col-md-3 col-sm-6 mb-4">
                        <p class="text-muted fw-bold mb-2 text-center text-uppercase" style="font-size: 0.75rem;">Copia de Factura</p>
                        @if($cliente->copa_factura != null)
                            <a href="{{ url($cliente->copa_factura) }}" target="_blank" class="d-block border rounded p-2 shadow-sm text-decoration-none document-link h-100">
                                <div class="mb-2 rounded" style="height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                                    <img src="{{ url($cliente->copa_factura) }}" alt="Copia de Factura" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                                <div class="text-center">
                                    <span class="d-block text-primary fw-bolder"><i data-feather="external-link" class="icon-sm mr-1"></i> Ver Documento</span>
                                </div>
                            </a>
                        @else
                            <div class="border rounded p-3 bg-light text-center h-100 d-flex flex-column justify-content-center">
                                <i data-feather="file-minus" class="text-muted mb-2 mx-auto"></i>
                                <span class="text-muted">No disponible</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tarjeta de Circulación -->
                    <div class="col-md-3 col-sm-6 mb-4">
                        <p class="text-muted fw-bold mb-2 text-center text-uppercase" style="font-size: 0.75rem;">Tarjeta de Circulación</p>
                        @if($cliente->tarjeta_circulacion != null)
                            <a href="{{ url($cliente->tarjeta_circulacion) }}" target="_blank" class="d-block border rounded p-2 shadow-sm text-decoration-none document-link h-100">
                                <div class="mb-2 rounded" style="height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                                    <img src="{{ url($cliente->tarjeta_circulacion) }}" alt="Tarjeta de Circulación" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                                <div class="text-center">
                                    <span class="d-block text-primary fw-bolder"><i data-feather="external-link" class="icon-sm mr-1"></i> Ver Documento</span>
                                </div>
                            </a>
                        @else
                            <div class="border rounded p-3 bg-light text-center h-100 d-flex flex-column justify-content-center">
                                <i data-feather="file-minus" class="text-muted mb-2 mx-auto"></i>
                                <span class="text-muted">No disponible</span>
                            </div>
                        @endif
                    </div>

                    <!-- Copia de Consesión -->
                    <div class="col-md-3 col-sm-6 mb-4">
                        <p class="text-muted fw-bold mb-2 text-center text-uppercase" style="font-size: 0.75rem;">Copia de Concesión</p>
                        @if($cliente->copia_consesion != null)
                            <a href="{{ url($cliente->copia_consesion) }}" target="_blank" class="d-block border rounded p-2 shadow-sm text-decoration-none document-link h-100">
                                <div class="mb-2 rounded" style="height: 140px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                                    <img src="{{ url($cliente->copia_consesion) }}" alt="Copia de Concesión" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                                <div class="text-center">
                                    <span class="d-block text-primary fw-bolder"><i data-feather="external-link" class="icon-sm mr-1"></i> Ver Documento</span>
                                </div>
                            </a>
                        @else
                            <div class="border rounded p-3 bg-light text-center h-100 d-flex flex-column justify-content-center">
                                <i data-feather="file-minus" class="text-muted mb-2 mx-auto"></i>
                                <span class="text-muted">No disponible</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .document-link {
        transition: all 0.2s ease-in-out;
    }
    .document-link:hover {
        transform: translateY(-5px);
        background-color: #fff;
        border-color: #007bff !important;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection