@extends('layouts.app')
@section('title')
    Reporte de unidades por cliente
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Reporte de Unidades</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Reporte de unidades por cliente</h4>
        </div>
    </div>

    <div class="col-lg-6">
       <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('reportes.exportarExcelClients') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Selecciona el Cliente</label>
                            <select name="cliente_id" class="form-control">
                                <option value="">-- Selecciona un cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-success">Imprimir Unidades</button>
                        </div>
                    </div>
                </form>
            </div>
       </div>

        {{-- <
        <div class="card">
            <div class="card-body">
                h5>Ingresos</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Recibido de</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                 
                <h5 class="mt-4">Gastos</h5>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Autoriza</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gastos as $gasto)
                                <tr>
                                    <td>{{ $gasto->fecha }}</td>
                                    <td>{{ $gasto->hora }}</td>
                                    <td>{{ $gasto->autoriza->name }}</td>
                                    <td>${{ number_format($gasto->monto, 2) }}</td>
                                    <td>{{ $gasto->descripcion }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                 <div class="mt-4">
                    <h5>Total Gastos: ${{ number_format($totalGastos, 2) }}</h5>
                    <h5><strong>Balance: ${{ number_format($balance, 2) }}</strong></h5>
                </div> 
            </div>
        </div>
    </div>--}}
</div>
@endsection


@section('js')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <!-- custom js for this page -->
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <!-- end custom js for this page -->
@endsection