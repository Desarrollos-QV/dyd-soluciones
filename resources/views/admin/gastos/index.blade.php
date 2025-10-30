@extends('layouts.app')
@section('title')
    Listado de Ingresos/Gastos
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro de Gastos</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de Gastos</h4>
            <a href="{{ route('gastos.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Nuevo Gasto
            </a>
        </div>
    </div>

    <div class="col-lg-12">
       

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Autoriza</th>
                                <th>Monto</th>
                                <th>Solicita</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gastos as $gasto)
                            <tr>
                                <td>{{ $gasto->fecha }}</td>
                                <td>{{ $gasto->hora }}</td>
                                <td>{{ $gasto->autoriza ? $gasto->autoriza->name : 'Sin autorizacion' }}</td>
                                <td>${{ number_format($gasto->monto, 2) }}</td>
                                <td>{{ $gasto->solicita ? $gasto->solicita->name : "Sin Nombre" }}</td>
                                <td>{{ $gasto->motivo }}</td>
                                <td>
                                    <a href="{{ route('gastos.edit', $gasto) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('gastos.destroy', $gasto) }}" method="POST" class="d-inline-block">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este gasto?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $gastos->links() }}
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