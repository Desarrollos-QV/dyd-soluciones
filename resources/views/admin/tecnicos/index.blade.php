@extends('layouts.app')
@section('title')
    Técnicos
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Técnicos</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Técnicos</h4>
            <a href="{{ route('tecnicos.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Nuevo Técnico
            </a>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Servicios asignados</th>
                                <th>Solicitudes Pendientes</th>
                                <th>Activo</th>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tecnicos as $tecnico)
                                <tr>
                                    <td>{{ $tecnico->name }}</td>
                                    <td>{{ $tecnico->telefono }}</td>
                                    <td>{{ $tecnico->email }}</td>
                                    <td>
                                       {{ $tecnico->serviciosAgendados->count() }} Servicios
                                    </td>
                                    <td>
                                        {{ $tecnico->solicitudes->count() }} Solicitudes
                                    </td>
                                    <td>
                                        @if($tecnico->is_active)
                                        <span class="badge badge-success">Activo</span>
                                        @else
                                        <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-end align-items-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones</button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('tecnicos.edit', $tecnico) }}">Editar</a>
                                                <a class="dropdown-item" href="#">
                                                    <form action="{{ route('tecnicos.destroy', $tecnico) }}" method="POST" class="d-inline-block">
                                                        @csrf @method('DELETE')
                                                        <button style="background: none;border:none;margin:0 !important;padding;padding: 0 !important;" onclick="return confirm('¿Eliminar técnico?')">Eliminar</button>
                                                    </form>
                                                </a> 
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
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <!-- custom js for this page -->
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <!-- end custom js for this page -->
@endsection