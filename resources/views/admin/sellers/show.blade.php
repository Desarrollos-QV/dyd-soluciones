@extends('layouts.app') 
@section('title', 'Detalles del Vendedor')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sellers.index') }}">Registro de Vendedores</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalles del Vendedor</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-4 d-flex justify-content-center">
                    <img src="{{ asset($seller->picture) }}" alt="Imagen de {{ $seller->name }}" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                </div>
                <h5 class="card-title mb-1 fw-bold">{{ $seller->name }}</h5>
                <p class="text-muted mb-4">{{ $seller->email }}</p>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('sellers.edit', $seller->id) }}" class="btn btn-primary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-2"></i> Editar Vendedor
                    </a>
                    <a href="{{ route('sellers.index') }}" class="btn btn-outline-secondary btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i> Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-4">Información Detallada</h6>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 30%;">Nombre Completo</th>
                                <td>{{ $seller->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Correo Electrónico</th>
                                <td>{{ $seller->email }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Teléfono</th>
                                <td>{{ $seller->phone }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Dirección</th>
                                <td>{{ $seller->address }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Nivel de Estudios</th>
                                <td>
                                    <span class="badge bg-info fs-6 px-3 py-2" style="font-size: 0.9rem;">
                                        {{ $seller->level_education }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Observaciones</th>
                                <td>
                                    @if(empty($seller->observations))
                                        <span class="text-muted">Sin observaciones</span>
                                    @else
                                        {{ $seller->observations }}
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
