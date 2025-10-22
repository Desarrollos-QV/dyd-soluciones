@extends('layouts.app')
@section('title')
    Historial de Caja y Administración
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Historial de Caja y Administración</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Caja y Administración</h4>
                <a href="{{ route('historial-caja.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Movimiento
                </a>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-2 mb-4">
                        <div class="col-md-2">
                            <input type="date" name="fecha_inicio" class="form-control"
                                value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                        </div>
                             
                        <div class="col-md-2">
                            <select name="tipo" class="form-select">
                                <option value="">Todos</option>
                                <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso
                                </option>
                                <option value="egreso" {{ request('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="display: flex;justify-content: end;">
                            <button class="btn btn-success w-25">Filtrar</button>
                        </div>    
                    </form>

                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Concepto</th>
                                <th>Método de Pago</th>
                                <th>Monto</th>
                                <th>Autorizado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historial as $item)
                                <tr>
                                    <td>{{ $item->fecha }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->tipo == 'ingreso' ? 'success' : 'danger' }}">
                                            {{ ucfirst($item->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->concepto }}</td>
                                    <td>{{ $item->metodo_pago ?? '- Sin Especificar' }}</td>
                                    <td>${{ number_format($item->monto, 2) }}</td>
                                    <td>{{ ucfirst($item->Autoriza->name.' '.$item->Autoriza->lastname) ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('historial-caja.edit', $item) }}"
                                            class="btn btn-warning btn-sm">Editar</a>
                                        <form method="POST" action="{{ route('historial-caja.destroy', $item) }}"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar registro?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $historial->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
