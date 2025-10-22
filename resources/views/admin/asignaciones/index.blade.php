@extends('layouts.app')
@section('title')
    Listado de Servicios
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de Servicios</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Servicios</h4>
                <a href="{{ route('assignements.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i>Alta de nuevo servicio
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
                                    <td>Cliente</td>
                                    <td>Tecnico</td>
                                    <td>Tipo de servicio</td>
                                    <td>Telefono de contacto</td>
                                    <td>Encargado de recibir</td>
                                    <td>Ubicación</td>
                                    <td>Viaticos</td>
                                    <td>Tipo de vehiculo</td>
                                    <td>Marca / Modelo</td>
                                    <td>Dispositivo</td>
                                    <td>Placa</td>
                                    <td>Observaciones</td>
                                    <td>Opciones</td>
                                </tr>
                            </thead>
                            <tbody> 
                                
                                @foreach ($assignements as $assign)
                                    <tr> 
                                        <td>
                                            <span class="badge bg-success text-white text-capitalize">{{ $assign->cliente ? $assign->cliente->nombre : 'Sin Asignar' }}</span>
                                        </td>
                                        <td>
                                             <div class="btn-group">
                                                <button type="button" class="btn @if($assign->tecnico_id == 0) btn-danger @else btn-primary @endif dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $assign->tecnico ? ucwords($assign->tecnico->name.' '.$assign->tecnico->lastname) : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($tecnicos as $tecnico)
                                                        @if ($tecnico['id'] !== $assign->tecnico_id)
                                                            <a class="dropdown-item py-2" href="{{ route('assignements.assign', ['id' => $assign->id, 'tecnico' => $tecnico['id']]) }}">
                                                                {{ ucwords($tecnico['name'].' '.$tecnico['lastname']) }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2" href="{{ route('assignements.assign', ['id' => $assign->id, 'tecnico' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $assign->tipo_servicio }}</td>
                                        <td>{{ $assign->tel_contact }}</td>
                                        <td>{{ $assign->encargado_recibir }}</td>
                                        <td>
                                            <a href="https://google.com/maps?q={{$assign->lat}},{{$assign->lng}}" target="_blank">{{ substr($assign->location,0,10) }}...</a>
                                        </td>
                                        <td>{{ $assign->viaticos }}</td>
                                        <td>{{ $assign->tipo_vehiculo }}</td>
                                        <td>
                                            <span class="badge bg-info text-white">{{ $assign->marca }}</span> / <span class="badge bg-warning text-white">{{ $assign->modelo }}</span> 
                                        </td>
                                        <td>
                                            <span class="badge bg-primary text-white">{{ $assign->device ? $assign->device->dispositivo : 'Sin asignar' }}</span>
                                        </td>
                                        <td>{{ $assign->placa }}</td>                                        
                                        <td>{{ $assign->observaciones ?? 'Sin Observaciones' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('assignements.edit', $assign->id) }}">Editar</a>
                                                    <hr />
                                                    <form action="{{ route('assignements.destroy', $assign) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item btn btn-sm">Eliminar</button>
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
