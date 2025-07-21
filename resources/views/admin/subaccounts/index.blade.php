@extends('layouts.app')
@section('title')
    Sub cuentas de Administración
@endsection
@section('content')

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subcuentas</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-end align-items-center">
                    <a href="{{ route('subaccounts.create') }}" class="btn btn-primary">Nueva Subcuenta</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Activo</th>
                                    <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subaccounts as $sub)
                                    <tr>
                                        <td>#{{ $sub->id }}</td>
                                        <td>{{ $sub->name }}</td>
                                        <td>{{ $sub->email }}</td>
                                        <td>{{ $sub->is_active ? 'Sí' : 'No' }}</td>
                                        <td class="d-flex justify-content-end align-items-center">
                                            <a href="{{ route('subaccounts.edit', $sub) }}" class="btn btn-sm btn-warning">Editar</a>
                                            <form action="{{ route('subaccounts.destroy', $sub) }}" method="POST" class="d-inline-block ml-2"
                                                onsubmit="return confirm('¿Eliminar esta subcuenta?')">
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