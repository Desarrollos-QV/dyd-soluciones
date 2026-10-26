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
            <h4>Reporte de Ingresos y Gastos</h4>
        </div>
    </div>

    <div class="col-lg-12">
       <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('reportes.exportarExcel') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                        </div> 
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-success">Exportar a Excel</button>
                            {{-- <a href="{{ route('reportes.exportarExcel', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-success">Exportar a Excel</a> --}}
                        </div>
                    </div>
                </form>
            </div>
       </div>

        <div class="row mt-4">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 text-white"><i data-feather="trending-down" class="icon-md mr-2"></i> Total de Gastos</h6>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h3 class="mb-0 fw-bold">-${{ number_format($totalGastos, 2) }}</h3>
                                <p class="text-white-50 mt-1">Acumulado de egresos en el periodo</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 text-white"><i data-feather="pie-chart" class="icon-md mr-2"></i> Balance General</h6>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h3 class="mb-0 fw-bold">-${{ number_format($balance, 2) }}</h3>
                                <p class="text-white-50 mt-1">Saldo final (Considerando únicamente gastos actuales)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Desglose de Gastos Registrados</h5>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table table-hover border-top">
                        <thead class="bg-light">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Autorizado Por</th>
                                <th>Monto</th>
                                <th>Descripción del Gasto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gastos as $gasto)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary mb-1">{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</span> <br>
                                        <span class="text-muted tx-12"><i data-feather="clock" class="icon-sm"></i> {{ $gasto->hora }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i data-feather="user" class="icon-sm text-primary"></i>
                                            </div>
                                            <span class="fw-bold">{{ $gasto->autoriza->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger text-white fs-6">-${{ number_format($gasto->monto, 2) }}</span>
                                    </td>
                                    <td class="text-wrap" style="max-width: 300px;">{{ $gasto->descripcion }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i data-feather="inbox" class="icon-lg text-light mb-3"></i>
                                        <p>No se encontraron gastos registrados en este rango de fechas.</p>
                                    </td>
                                </tr>
                            @endforelse
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