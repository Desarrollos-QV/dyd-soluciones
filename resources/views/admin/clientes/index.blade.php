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
                                <th>Imagen</th>
                                <th>Identificación</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Contácto</th>
                                <th>Contácto alterno</th>
                                <th>Empresa</th>
                                <th>Tipo de empresa</th>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>
                                    <a href="{{ asset($cliente->avatar) }}" target="_blank">
                                        @if($cliente->avatar != null)
                                        <img src="{{ asset($cliente->avatar) }}" alt="Imagen de {{ $cliente->nombre }}" >
                                        @else
                                        <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Sin Imagen" >
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ asset($cliente->identificacion) }}" target="_blank">
                                        
                                        @if($cliente->identificacion != null)
                                        <img src="{{ asset($cliente->identificacion) }}" alt="Imagen de {{ $cliente->nombre }}" style="border-radius: 12px !important"> 
                                        @else
                                        <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Sin Imagen" >
                                        @endif
                                    </a>
                                </td>
                                <td>{{ ucwords($cliente->nombre) }}</td>
                                <td>{{ $cliente->direccion }}</td>
                                <td>{{ $cliente->numero_contacto ?? 'Indefinido' }}</td>
                                <td>{{ $cliente->numero_alterno ?? 'Indefinido' }}</td>
                                <td>{{ $cliente->empresa }}</td>
                                <td>{{ $cliente->tipo_empresa }}</td> 
                                <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('clientes.edit', $cliente->id) }}">Editar</a>
                                            <hr /> 
                                            <form action="{{ route('clientes.destroy', $cliente) }}"
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