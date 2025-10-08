@extends('layouts.app')
@section('title')
    Listado de Unidades
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado de Unidades</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Unidades</h4>
                <a href="{{ route('unidades.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Agregar Elemento
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
                                    <td>Cliente asignado</td>
                                    <td>Economico/Placas</td>
                                    <td>Tipo de unidad</td>
                                    <td>Instalacion</td>
                                    <td>Disp. Instalado</td>
                                    <td>Año/Marca/Submarca</td>
                                    <td>Número de motor</td>
                                    <td>VIN / IMEI</td>
                                    <td>N.P. SIM</td>
                                    <td>¿Cuenta con apagado?</td>
                                    <td>Número de emergencias</td>
                                    <td class="d-flex justify-content-end">Opciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unidades as $unit)
                                    <tr>
                                        
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn @if($unit->cliente_id == 0) btn-danger @else btn-primary @endif dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $unit->cliente ? $unit->cliente->nombre : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($clientes as $client)
                                                        @if ($client['id'] !== $unit->cliente_id)
                                                            <a class="dropdown-item py-2" href="{{ route('unidades.assign', ['id' => $unit->id, 'client' => $client['id']]) }}">
                                                                {{ $client['nombre'] }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assign', ['id' => $unit->id, 'client' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $unit->economico }} / {{ $unit->placa }}</td>
                                        <td>{{ $unit->tipo_unidad }}</td> 
                                        <td>{{ \Carbon\Carbon::parse($unit->fecha_instalacion)->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn @if($unit->dispositivo_instalado == 'null') btn-danger @else btn-primary @endif dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ ($unit->dispositivo_instalado != 'null' || !isset($unit->dispositivo_instalado)) ? $unit->dispositivo_instalado : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($unit->dispositivo_instalado !== 'DVR')
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'DVR']) }}">
                                                        DVR
                                                    </a>
                                                    @endif
                                                    
                                                    @if ($unit->dispositivo_instalado !== 'GPS')
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'GPS']) }}">
                                                        GPS
                                                    </a>
                                                    @endif
                                                    @if ($unit->dispositivo_instalado !== 'DASHCAM')
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'DASHCAM']) }}">
                                                        DASHCAM
                                                    </a>
                                                    @endif
                                                    @if ($unit->dispositivo_instalado !== 'SENSOR')
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'SENSOR']) }}">
                                                        SENSOR
                                                    </a>
                                                    @endif
                                                     
                                                    <a class="dropdown-item py-2" href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'null']) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge bg-info text-white">{{ $unit->anio_unidad }}</span> / <span class="badge bg-warning">{{ $unit->marca }}</span> / <span class="badge bg-success">{{ $unit->submarca }}</span></td>
                                        <td>
                                            <span class="badge bg-primary text-white">{{ $unit->numero_de_motor }}</span>
                                        </td>
                                        <td><span class="badge bg-secondary text-white">{{ $unit->vin }}</span> / <span class="badge bg-primary text-white">{{ $unit->imei }}</span></td>
                                        <td>
                                            <span class="badge bg-success text-white">{{ $unit->np_sim }}</span>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            @php
                                            switch ($unit->cuenta_con_apagado) {
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
                                        <span class="{{ $badgeClass }} text-white text-uppercase"> {{ $unit->cuenta_con_apagado }}</span>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $unit->numero_de_emergencia }}" target="_blank">
                                               <i class="link-icon icon-sm" data-feather="phone"></i>
                                                {{ $unit->numero_de_emergencia }}
                                            </a>
                                        </td> 
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('unidades.edit', $unit->id) }}">Editar</a>
                                                    <a href="javascript:void(0)" class="dropdown-item" onclick="alertSwwet('Observaciones', '{{$unit->observaciones ?? 'Sin Obersaciones'}}')">Ver observaciones</a>
                                                    <a href="javascript:void(0)" class="dropdown-item" onclick="alertSweetImage('Foto de la unidad', '{{ asset($unit->foto_unidad)  }}')">Ver Foto de la unidad</a>
                                                    <hr />
                                                    <a class="dropdown-item" href="#">
                                                        <form action="{{ route('unidades.destroy', $unit) }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <span type="submit"
                                                                onclick="return confirm('¿Estás seguro?')">Eliminar</span>
                                                        </form>
                                                    </a>
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
