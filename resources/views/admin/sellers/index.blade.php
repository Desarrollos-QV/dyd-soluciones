@extends('layouts.app')
@section('title')
    Listado de Vendedores
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro de Vendedores</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de Vendedores</h4>
            <a href="{{ route('sellers.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Nuevo Vendedores
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
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Telefono</th>
                                <th>Nivel de estudios</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $sell)
                            <tr>
                                <td>#{{ $sell->id }}</td>
                                <td>
                                    <img src="{{ asset($sell->picture) }}" alt="Imagen de {{ $sell->name }}" width="50" height="50" class="rounded-circle">
                                </td>
                                <td>{{ $sell->name }}</td>
                                <td>{{ $sell->address }}</td>
                                <td>{{ $sell->phone }}</td> 
                                <td>{{ $sell->level_education }}</td>
                                <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('sellers.edit', $sell->id) }}">Editar</a>
                                            <hr /> 
                                            <form action="{{ route('sellers.destroy', $sell) }}"
                                                method="POST" style="display:inline-block;">
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