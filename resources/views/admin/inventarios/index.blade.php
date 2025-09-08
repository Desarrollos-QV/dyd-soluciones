@extends('layouts.app')
@section('title')
    Listado de inventario
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Listado de inventario</li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Inventario</h4>
            <a href="{{ route('inventarios.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Agregar Elemento
            </a>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Teléfono Alterno</th>
                            <th>Evaluación Calidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventarios as $inventario)
                        <tr>
                            <td>{{ $inventario->nombre_completo }}</td>
                            <td>{{ $inventario->direccion }}</td>
                            <td>{{ $inventario->telefono }}</td>
                            <td>{{ $inventario->telefono_alterno }}</td>
                            <td>{{ $inventario->evaluacion_calidad }}</td>
                            <td>
                                <a href="{{ route('inventarios.edit', $inventario) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('inventarios.destroy', $inventario) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
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