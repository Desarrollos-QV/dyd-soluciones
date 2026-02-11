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
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDateRange">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control" id="dateFrom" placeholder="Fecha inicio" readonly>
                <span class="input-group-addon bg-transparent px-2">-</span>
                <input type="text" class="form-control" id="dateTo" placeholder="Fecha fin" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                @if(Auth::user()->isAdmin())
                    <!-- ADMIN KPIS -->
                    <!-- Prospectos -->
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Prospectos</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $prospectosCount }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nuevos Clientes -->
                    <div class="col-md-3 grid-margin stretch-card">
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
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Nuevos Técnicos -->
                    <div class="col-md-3 grid-margin stretch-card">
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nuevos Servicios -->
                    <div class="col-md-3 grid-margin stretch-card">
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
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- TECHNICIAN KPIS -->
                    <!-- Servicios Asignados -->
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Servicios Asignados (Total)</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $serviciosAsignadosCount }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Servicios En Curso -->
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Servicios En Curso</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $serviciosEnCursoCount }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Servicios Agendados Chart -->
    <div class="row">
        <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                        <h6 class="card-title mb-0">Servicios Agendados ({{ ucfirst($monthLabel) }})</h6>
                    </div>
                    <div class="flot-wrapper">
                        <div id="flotChart1" class="flot-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Servicios en curso List -->
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
                                    <th class="pt-0">Ubicación de instalación</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviciosEnCurso as $serv)
                                <tr>
                                    <td>{{$serv->id}}</td>
                                    <td>{{$serv->tipo_servicio}}</td>
                                    <td><span class="badge badge-danger">
                                        {{$serv->created_at}}    
                                    </span></td>
                                    <td>{{ ucwords($serv->tecnico->name)}}</td>
                                    <td>{{ ucwords($serv->cliente->nombre) }}</td>
                                    <td>
                                        {{$serv->location}}
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



@push('custom-scripts')
<script>
    $(function() {
        // Init Date Range Picker
        if($('#dateFrom').length && $('#dateTo').length) {
            const urlParams = new URLSearchParams(window.location.search);
            const dateFrom = urlParams.get('date_from');
            const dateTo = urlParams.get('date_to');
            
            // Set default dates (current month if no params)
            let startDate, endDate;
            
            if(dateFrom && dateTo) {
                startDate = new Date(dateFrom);
                endDate = new Date(dateTo);
            } else {
                // Default to current month
                const now = new Date();
                startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            }

            // Initialize both datepickers
            $('#dateFrom').datepicker({
                format: "dd/mm/yyyy",
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', startDate);
            
            $('#dateTo').datepicker({
                format: "dd/mm/yyyy",
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', endDate);
            
            // Update end date minimum when start date changes
            $('#dateFrom').on('changeDate', function(e) {
                $('#dateTo').datepicker('setStartDate', e.date);
                applyDateFilter();
            });
            
            // Update start date maximum when end date changes
            $('#dateTo').on('changeDate', function(e) {
                $('#dateFrom').datepicker('setEndDate', e.date);
                applyDateFilter();
            });
            
            function applyDateFilter() {
                const from = $('#dateFrom').datepicker('getDate');
                const to = $('#dateTo').datepicker('getDate');
                
                if(from && to) {
                    const fromStr = formatDate(from);
                    const toStr = formatDate(to);
                    window.location.href = '?date_from=' + fromStr + '&date_to=' + toStr;
                }
            }
            
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return year + '-' + month + '-' + day;
            }
        }

        // Flot Chart 1
        var chartData = @json($chartData);
        
        console.log('Chart Data:', chartData);
        
        // If no data, show empty
        if(chartData.length === 0) {
            console.log('No chart data available');
            // handle empty state or just leave empty
        }

        $.plot('#flotChart1', [
            {
            data: chartData,
            color: '#727cf5',
            points: { show: true, radius: 4 },
            lines: { show: true }
            }
        ], {
            series: {
            shadowSize: 0,
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: { colors: [ { opacity: 0 }, { opacity: 0.2 } ] }
            },
            points: {
                show: true,
                radius: 4,
                fillColor: '#727cf5'
            }
            },
            grid: {
            borderWidth: 0,
            labelMargin: 10,
            hoverable: true,
            clickable: true,
            mouseActiveRadius: 6,
            },
            yaxis: {
            show: true,
            color: 'rgba(0,0,0,0.1)',
            min: 0,
            tickDecimals: 0,
            minTickSize: 1
            },
            xaxis: {
            show: true,
            color: 'rgba(0,0,0,0.1)',
            min: 1,
            max: 31,
            tickDecimals: 0,
            ticks: 10
            }
        });
    });
</script>
@endpush

