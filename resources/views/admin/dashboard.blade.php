@extends('layouts.app')
@section('title')
    ¡Bienvenido a tu panel de control!
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">¡Bienvenido(a) a tu panel de Administración! Como "{{ ucfirst(Auth::user()->role) }}"</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control">
            </div>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                Descargar Reportes
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                @if(Auth::user()->isAdmin())
                <!-- Nuevos Clientes -->
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Nuevos Clientes</h6>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $clientesEsteMes }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class=" @if($clientesCambioPorcentaje > 0) text-success @else text-danger @endif">
                                            <span>{{ $clientesCambioPorcentaje }}%</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="apexChart1" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <!-- Nuevos Técnicos -->
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Nuevos Técnicos</h6>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{$tecnicosEsteMes}}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class=" @if($tecnicosCambioPorcentaje > 0) text-success @else text-danger @endif">
                                            <span>{{$tecnicosCambioPorcentaje}}%</span>
                                            <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="apexChart2" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 @endif

                <!-- Nuevos Servicios -->
                <div class="@if(Auth::user()->isAdmin()) col-md-4 @else col-md-6 @endif grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Nuevos Servicios</h6>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{$serviciosEsteMes}}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class=" @if($serviciosCambioPorcentaje > 0) text-success @else text-danger @endif">
                                            <span>{{$serviciosCambioPorcentaje}}%</span>
                                            <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="apexChart3" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isTecnico())
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Servicios Agendados Totales </h6>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{$serviciosTotales3Meses}}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class=" @if($serviciosCambioPorcentaje > 0) text-success @else text-danger @endif">
                                            <span>{{$serviciosCambioPorcentaje}}%</span>
                                            <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="apexChart3" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Servicios Agendados -->
    <div class="row">
        <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                        <h6 class="card-title mb-0">Servicios Agendados</h6>
                    </div>
                    <div class="row align-items-start mb-2">
                        <div class="col-md-7">
                            <p class="text-muted tx-13 mb-3 mb-md-0">
                                Grafica de servicios agendados por mes, año y semana. Puedes ver el total de servicios.
                            </p>
                        </div>
                        <div class="col-md-5 d-flex justify-content-md-end">
                            <div class="btn-group mb-3 mb-md-0" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-outline-primary">Hoy</button>
                                <button type="button" class="btn btn-outline-primary d-none d-md-block">Semana</button>
                                <button type="button" class="btn btn-primary">Mes</button>
                                <button type="button" class="btn btn-outline-primary">Año</button>
                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div id="flotChart1" class="flot-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Servicios en curso -->
    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-10">Servicios en curso</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Tipo</th>
                                    <th class="pt-0">Fecha</th>
                                    <th class="pt-0">Técnico</th>
                                    <th class="pt-0">Titular</th>
                                    <th class="pt-0">Economico de la Unidad</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviciosEnCurso as $serv)
                                <tr>
                                    <td>{{$serv->id}}</td>
                                    <td>{{$serv->tipo_servicio}}</td>
                                    <td><span class="badge badge-danger">
                                        {{$serv->created_at	}}    
                                    </span></td>
                                    <td>{{ ucwords($serv->tecnico->name)}}</td>
                                    <td>{{ ucwords($serv->cliente->nombre) }}</td>
                                    <td><span class="badge badge-success">{{ number_format($serv->costo_instalador,2) }}</span></td>
                                    
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
