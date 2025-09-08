@extends('layouts.app')
@section('title')
    Listado de Prospectos
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registro de Prospectos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Registro de Prospectos</h4>
                <a href="{{ route('prospects.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Prospectos
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
                                    <th>Campaña</th>
                                    <th>Prospecto</th>
                                    <th>Empresa</th>
                                    <th>Potencial</th>
                                    <th>Vendedor asignado</th>
                                    <th>Ubicación</th>
                                    <th>Contacto</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prospects as $prosp)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success text-white text-capitalize">{{ $prosp->name_company }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary text-white text-capitalize">{{ $prosp->name_prospect }}</span>
                                        </td>
                                        <td>{{ $prosp->company }}</td>
                                        <td>
                                            @php
                                                switch ($prosp->potencial) {
                                                    case 'bajo':
                                                        $badgeClass = 'badge bg-danger';
                                                        break;
                                                    case 'medio':
                                                        $badgeClass = 'badge bg-warning';
                                                        break;
                                                    case 'alto':
                                                        $badgeClass = 'badge bg-success';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge bg-secondary';
                                                }
                                            @endphp
                                            <span
                                                class="{{ $badgeClass }} text-dark text-uppercase">{{ $prosp->potencial }}</span>
                                        </td>
                                        <td>{{ $prosp->seller ? $prosp->seller->name : 'Sin Asignar' }}</td>
                                        <td>{{ $prosp->location }}</td>
                                        <td>{{ $prosp->contacts }}</td>
                                        <td>{{ $prosp->observations }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('prospects.edit', $prosp->id) }}">Editar</a>
                                                    <hr /> 
                                                    <form action="{{ route('prospects.destroy', $prosp) }}"
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
