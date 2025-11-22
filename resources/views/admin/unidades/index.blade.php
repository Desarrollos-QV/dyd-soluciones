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
                
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-danger btn-sm mr-3" id="delete_selected">
                        <i data-feather="trash-2"></i> Eliminar Selección
                    </button>
                    <a href="{{ route('unidades.create') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus"></i> Agregar Elemento
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableResponsive" class="w-100 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <td>Cliente asignado</td>
                                    <td>Dispositivo Asignado</td>
                                    <td>SIM Asignada</td>
                                    <td>Disp. Instalado</td>
                                    <td>Instalacion</td>
                                    <td>Corte</td>
                                    <td>Año/Marca/Submarca</td>
                                    <td>Número de motor</td>
                                    <td>VIN / IMEI</td>
                                    <td>Número de emergencias</td>
                                    <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unidades as $unit)
                                    <tr @if(!App\Models\Unidades::checkCompleteness($unit->id)) class="table-warning " @endif>
                                        <td>
                                            <input type="checkbox" id="select_element_{{ $unit->id }}" name="select_element_{{ $unit->id }}">
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn @if ($unit->cliente_id == 0) btn-danger @else btn-primary @endif dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $unit->cliente ? ucwords($unit->cliente->nombre) : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($clientes as $client)
                                                        @if ($client['id'] !== $unit->cliente_id)
                                                            <a class="dropdown-item py-2"
                                                                href="{{ route('unidades.assign', ['id' => $unit->id, 'client' => $client['id']]) }}">
                                                                {{ ucwords($client['nombre']) }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2"
                                                        href="{{ route('unidades.assign', ['id' => $unit->id, 'client' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn @if ($unit->devices_id == 0 || $unit->devices_id == null) btn-danger @else btn-primary @endif dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $unit->device ? ucwords($unit->device->dispositivo) : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($devices as $device)
                                                        @if ($device['id'] !== $unit->devices_id)
                                                            <a class="dropdown-item py-2"
                                                                href="{{ route('unidades.assignDevice', ['id' => $unit->id, 'device' => $device['id']]) }}">
                                                                {{ ucwords($device['dispositivo']) }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2"
                                                        href="{{ route('unidades.assignDevice', ['id' => $unit->id, 'device' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn @if ($unit->simcontrol_id == 0 || $unit->simcontrol_id == null) btn-danger @else btn-primary @endif dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $unit->simcontrol ? ucwords($unit->simcontrol->compañia).' - '.$unit->simcontrol->numero_publico : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($simcontrols as $sim)
                                                        @if ($sim['id'] !== $unit->simcontrol_id)
                                                            <a class="dropdown-item py-2"
                                                                href="{{ route('unidades.assignSIM', ['id' => $unit->id, 'sim' => $sim['id']]) }}">
                                                                {{ ucwords($sim['compañia']).' - '.$sim['numero_publico'] }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2"
                                                        href="{{ route('unidades.assignSIM', ['id' => $unit->id, 'sim' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn @if ($unit->dispositivo_instalado == 'null') btn-danger @else btn-primary @endif dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $unit->dispositivo_instalado != 'null' || !isset($unit->dispositivo_instalado) ? $unit->dispositivo_instalado : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if ($unit->dispositivo_instalado !== 'DVR')
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'DVR']) }}">
                                                            DVR
                                                        </a>
                                                    @endif

                                                    @if ($unit->dispositivo_instalado !== 'GPS')
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'GPS']) }}">
                                                            GPS
                                                        </a>
                                                    @endif
                                                    @if ($unit->dispositivo_instalado !== 'DASHCAM')
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'DASHCAM']) }}">
                                                            DASHCAM
                                                        </a>
                                                    @endif
                                                    @if ($unit->dispositivo_instalado !== 'SENSOR')
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'SENSOR']) }}">
                                                            SENSOR
                                                        </a>
                                                    @endif

                                                    <a class="dropdown-item py-2"
                                                        href="{{ route('unidades.assignDisp', ['id' => $unit->id, 'disp' => 'null']) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success text-white">{{ \Carbon\Carbon::parse($unit->fecha_instalacion)->format('Y-m-d') }}</span>
                                        </td>
                                        <td>
                                            {!! App\Models\Unidades::diasFaltantesCobro($unit->fecha_cobro) !!}
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-white">{{ $unit->anio_unidad }}</span> / <span
                                                class="badge bg-warning">{{ $unit->marca }}</span> / <span
                                                class="badge bg-success">{{ $unit->submarca }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary text-white">{{ $unit->numero_de_motor }}</span>
                                        </td>
                                        <td><span class="badge bg-secondary text-white">{{ $unit->vin }}</span> / <span
                                                class="badge bg-primary text-white">{{ $unit->imei }}</span>
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
                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                        onclick="alertSwwet('Observaciones', '{{ $unit->observaciones ?? 'Sin Obersaciones' }}')">Ver
                                                        observaciones</a>
                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                        onclick="viewImages('Foto de la unidad', '{{ $unit->foto_unidad }}' ,'{{ asset('uploads/fotos_unidades') }}')">Ver
                                                        Fotos de la unidad</a>
                                                    <hr />
                                                    <a href="{{ route('unidades.destroy', $unit) }}" class="dropdown-item"
                                                        onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas eliminar esta unidad? Esta acción no se puede deshacer.')) { this.querySelector('form').submit(); }">
                                                        <form action="{{ route('unidades.destroy', $unit) }}"
                                                            class="dropdown-item" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <span type="submit">Eliminar</span>
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

    <div class="modal fade bd-carousel-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Listado de Fotos de la unidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                        <div class="carousel-inner" id="inner-carousel">

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            const table = $('#dataTableResponsive').DataTable({
                responsive: true,
            });

            // Limpiamos todos los Checkbox al cargar la tabla
            $("input[type='checkbox']").prop('checked', false);

            // Detectamos el clic en cualquier fila de la tabla
            table.on('click', 'input[type="checkbox"]', function(e) {
                let classList = e.currentTarget.parentElement.parentElement.classList;
                classList.toggle('selected');
            });

            // Manejar el clic en el botón de eliminar prospectos seleccionados
            $('#delete_selected').on('click', function() {
                let selectedProspectIds = [];
                table.rows('.selected').every(function(rowIdx, tableLoop, rowLoop) {
                    let prospectId = $(this.node()).find('input[type="checkbox"]').attr('id')
                        .replace('select_element_', '');
                    selectedProspectIds.push(prospectId);
                });

                if (selectedProspectIds.length === 0) {
                    alertSwwet('Error', 'No hay Elementos seleccionados para eliminar.');
                    return;
                }

                // Confirmar la eliminación
                if (!confirm(
                        `¿Estás seguro de que deseas eliminar ${selectedProspectIds.length} Elemento(s)?`
                    )) {
                    return;
                }

                // Enviar la solicitud AJAX para eliminar los prospectos seleccionados
                $.ajax({
                    url: '{{ route('unidades.bulkDelete') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedProspectIds
                    },
                    success: function(response) {
                        if (response.ok) {
                            alertSwwet('Éxito', response.message);
                            // Recargar la página o eliminar las filas de la tabla
                            location.reload();
                        } else {
                            alertSwwet('Error', response.message);
                        }
                    },
                    error: function(xhr) {
                        alertSwwet('Error', 'Ocurrió un error al eliminar los prospectos.');
                    }
                });
            });
        });
    </script>
    <!-- end custom js for this page -->
    <script src="{{ asset('assets/js/carousel.js') }}"></script>

    <script>
        function viewImages(title, images, path) {
            const Images = JSON.parse(images);
            const modal = $('.bd-carousel-modal-lg');
            const innerCarousel = document.querySelector('#inner-carousel');

            let imageHtml = `
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        `;
            Images.forEach((img, index) => {
                if (index === 0) {
                    imageHtml += `
                                    <div class="carousel-item active">
                                        <img src="${path}/${img}" class="d-block w-100" alt="...">
                                    </div>`;
                } else {
                    imageHtml += `
                                    <div class="carousel-item">
                                        <img src="${path}/${img}" class="d-block w-100" alt="...">
                                    </div>`;
                }
            });
            `</div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>`;

            innerCarousel.innerHTML = imageHtml;
            modal.modal('show');
        }
    </script>
@endsection
