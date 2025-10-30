@extends('layouts.app')
@section('title')
    Listado de Control de Simcards
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Control de Simcards</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4> Listado de Control de Simcards</h4>
            <a href="{{ route('simcontrol.create') }}" class="btn btn-primary btn-sm">
                <i data-feather="plus"></i> Agregar nueva Simcard
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
                                <th>compañia</th>
                                <th>numero SIM</th>
                                <th>numero Público</th> 
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($simcontrols as $sim)
                            <tr>
                                <td>#{{ $sim->id }}</td>
                                <td>{{ $sim->compañia }}</td>
                                <td>{{ $sim->numero_sim }}</td>
                                <td>{{ $sim->numero_publico }}</td> 
                                 <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('simcontrol.edit', $sim->id) }}">Editar</a>
                                            <hr /> 
                                            <form action="{{ route('simcontrol.destroy', $sim) }}"
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