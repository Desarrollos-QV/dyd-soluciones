@extends('layouts.app')
@section('title')
    Listado de Asignaciones
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de Asignaciones</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Asignaciones</h4>
                <a href="{{ route('assignements.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i>Generar Asiganción
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
                                    <td>Cliente</td>
                                    <td>Dispositivo</td>
                                    <td>Economico</td>
                                    <td>Placa</td>
                                    <td>Coban o DVR</td>
                                    <td>Fecha de inicio</td>
                                    <td>Tecnico</td> 
                                    <td>Observaciones</td>
                                    <td>Número de motor</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignements as $assign)
                                    <tr>
                                        <td>{{ $assign->cliente->nombre }}</td>
                                        <td>{{ $assign->unidad->vin }}</td>
                                        <td>{{ $assign->unidad->economico }}</td>
                                        <td>{{ $assign->unidad->placa }}</td>
                                        <td>{{ $assign->coban_dvr }}</td>
                                        <td>{{ $assign->fecha_inicio }}</td>
                                        <td>{{ $assign->tecnico ? $assign->tecnico->name : 'Sin Asignar' }}</td>
                                        <td>{{ $assign->observaciones ?? 'Sin Observaciones' }}</td>
                                        <td>{{ $assign->unidad->numero_de_motor }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('assignements.edit', $assign->id) }}">Editar</a>
                                                    <hr />
                                                    <a class="dropdown-item" href="#">
                                                        <form action="{{ route('assignements.destroy', $assign) }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <span type="submit"
                                                                onclick="return confirm('¿Estás seguro?')">Eliminar</span>
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
