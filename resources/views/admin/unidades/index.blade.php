@extends('layouts.app')
@section('title')
    Listado de Unidades
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de Unidades</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Unidades</h4>
                <a href="{{ route('unidades.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Agregar Elemento
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
                                    <td>Tipo de unidad</td>
                                    <td>Precio</td>
                                    <td>Economico</td>
                                    <td>Placa</td>
                                    <td>Año</td>
                                    <td>VIN</td>
                                    <td>IMEI</td>
                                    <td>SIM DVR</td>
                                    <td>Marca/Submarca</td>
                                    <td>Número de motor</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unidades as $unit)
                                    <tr>
                                        <td>{{ $unit->tipo_unidad }}</td>
                                        <td>${{ number_format($unit->precio, 2) }}</td>
                                        <td>{{ $unit->economico }}</td>
                                        <td>{{ $unit->placa }}</td>
                                        <td>{{ $unit->anio_unidad }}</td>
                                        <td>{{ $unit->vin }}</td>
                                        <td>{{ $unit->imei }}</td>
                                        <td>{{ $unit->sim_dvr }}</td>
                                        <td>{{ $unit->marca_submarca }}</td>
                                        <td>{{ $unit->numero_de_motor }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('unidades.edit', $unit->id) }}">Editar</a>
                                                    <hr />
                                                    <a class="dropdown-item" href="#">
                                                        <form action="{{ route('unidades.destroy', $unit) }}" method="POST"
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
