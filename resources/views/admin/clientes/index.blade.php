@extends('layouts.app')
@section('title')
    Clientes
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Clientes</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Lista de Clientes</h4>
                    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Nuevo Cliente</a>
                </div>
                <div class="card-body">
                   <div class="table-responsive">
                    <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Ruta 22</th>
                                <th>Pago Mensual</th>
                                <th>Inicio</th>
                                <th>Vencimiento</th>
                                <th>Recordatorio</th>
                                <td>Cobro adicional</td>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->numero_contacto }}</td>
                                <td>{{ $cliente->pertenece_ruta_22 ? 'Sí' : 'No' }}</td>
                                <td>${{ number_format($cliente->pago_mensual, 2) }}</td>
                                <td>{{ $cliente->fecha_inicio }}</td>
                                <td>{{ $cliente->fecha_vencimiento }}</td>
                                <td>{{ $cliente->recordatorio }}</td>
                                <td>{{ $cliente->cobro_adicional }}</td>
                                <td class="d-flex justify-content-end align-items-center">
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline-block ml-2" onsubmit="return confirm('¿Eliminar cliente?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
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