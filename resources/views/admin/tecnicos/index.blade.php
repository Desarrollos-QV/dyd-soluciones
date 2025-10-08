@extends('layouts.app')
@section('title')
    Técnicos
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Técnicos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Técnicos</h4>
                <a href="{{ route('tecnicos.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Técnico
                </a>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Escolaridad</th>
                                    <th>Experienca</th>
                                    <th>¿Cuenta con Licencia?</th>
                                    <th>¿Cuenta con Vehículo?</th>
                                    <th>¿Cuenta con Herramienta?</th>
                                    <th>¿Cuenta con Uniforme?</th>
                                    <th>Habilidades de comunicación</th>
                                    <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($tecnicos as $tecnico)
                                    <tr>
                                        <td>
                                            <a href="{{ asset($tecnico->avatar) }}" target="_blank">
                                                <img src="{{ asset($tecnico->avatar) }}" alt="Sin Imagen" >
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ asset($tecnico->identificacion) }}" target="_blank">
                                                <img src="{{ asset($tecnico->identificacion) }}" alt="Sin Imagen" style="border-radius: 12px !important"> 
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-capitalize">{{ $tecnico->name . ' ' . $tecnico->lastname }}</span>
                                        </td>
                                        <td>{{ $tecnico->email }}</td>
                                        <td>{{ $tecnico->schooling }}</td>
                                        <td>{{ $tecnico->experience }}</td>
                                        <td>
                                            <span class="badge bg-info text-white">{{ $tecnico->licence }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary text-white">{{ $tecnico->vehicle }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary text-white">{{ $tecnico->tools ? 'Si cuenta' : 'Sin Herramientas' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-white">{{ $tecnico->uniform }}</span>
                                        </td>
                                        <td>{{ $tecnico->skills }}</td>
                                        <td class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('tecnicos.edit', $tecnico->id) }}">Editar</a>
                                                    <hr />
                                                    <form action="{{ route('tecnicos.destroy', $tecnico) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm dropdown-item"
                                                            type="submit">Eliminar</button>
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
