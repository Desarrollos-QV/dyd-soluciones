@extends('layouts.app')
@section('title')
    Listado de Control de Inventario
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro de Control de Inventario</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de Control de Inventario</h4>
            <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Nuevo Control de Inventario
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
                                <th>ID</th>
                                <th>Dispositivo</th>
                                <th>Marca</th>
                                <th>Camaras</th>
                                <th>Generacion</th>
                                <th>IMEI asignado</th>
                                <th>Garantia</th>
                                <th>Accesorios</th>
                                <th>IA</th>
                                <th>Otra_empresa</th>
                                <th>Stock</th>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $dev)
                            <tr>
                                <td>#{{ $dev->id }}</td>
                                <td>{{ $dev->dispositivo }}</td>
                                <td>{{ $dev->marca }}</td>
                                <td>{{ $dev->camaras }}</td> 
                                <td>{{ $dev->generacion }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $dev->imei }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($dev->garantia)->format('Y-m-d') }}</td>
                                <td>{{ $dev->accesorios }}</td>
                                <td>
                                    @php
                                        switch ($dev->ia) {
                                            case 'si':
                                                $badgeClass = 'badge bg-success';
                                                break;
                                            case 'no':
                                                $badgeClass = 'badge bg-danger';
                                                break;
                                            default:
                                                $badgeClass = 'badge bg-warning';
                                        }
                                    @endphp
                                    <span class="{{ $badgeClass }} text-white text-uppercase"> {{ $dev->ia }}</span>
                                </td>
                                <td>
                                    @php
                                        switch ($dev->otra_empresa) {
                                            case 'si':
                                                $badgeClass = 'badge bg-success';
                                                break;
                                            case 'no':
                                                $badgeClass = 'badge bg-danger';
                                                break;
                                            default:
                                                $badgeClass = 'badge bg-warning';
                                        }
                                    @endphp
                                    <span class="{{ $badgeClass }} text-white text-uppercase"> {{ $dev->otra_empresa }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-white"> Qty-{{ $dev->stock }}</span>
                                </td>
                                 <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('devices.edit', $dev->id) }}">Editar</a>
                                            <hr /> 
                                            <form action="{{ route('devices.destroy', $dev) }}"
                                                method="POST"  style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm dropdown-item" type="submit">Eliminar</button>
                                            </form> 
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