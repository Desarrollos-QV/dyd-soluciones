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
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-danger btn-sm mr-3" id="delete_selected">
                        <i data-feather="trash-2"></i> Eliminar Selección
                    </button>
                    <a href="{{ route('assignements.create') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus"></i>Alta de nuevo servicio
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
                                    <td>Cliente</td>
                                    <td>Tecnico</td>
                                    <td>Tipo de servicio</td>
                                    <td>Telefono de contacto</td>
                                    <td>Encargado de recibir</td>
                                    <td>Ubicación</td>
                                    <td>Viaticos</td>
                                    <td>Observaciones</td>
                                    <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($assignements as $assign)
                                    <tr @if(!App\Models\Asignaciones::checkCompleteService($assign->id)) class="table-warning" @endif>
                                        <td>
                                            <input type="checkbox" id="select_element_{{ $assign->id }}"
                                                name="select_element_{{ $assign->id }}">
                                        </td>
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
                                        <td class="text-center">
                                            <a href="https://google.com/maps?q={{$assign->lat}},{{$assign->lng}}" target="_blank">
                                               <i class="link-icon" data-feather="map-pin"></i>
                                            </a>
                                        </td>
                                        <td>${{ $assign->viaticos }}</td>                                   
                                        <td>{{ (isset($assign->getFirma) && $assign->getFirma->comentarios) ? $assign->getFirma->comentarios : 'Sin Observaciones' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('assignements.edit', $assign->id) }}">Editar</a>
                                                    @if (isset($assign->getFirma))
                                                        @if($assign->getFirma->firma != null)
                                                        <a class="dropdown-item" target="_blank" href="{{ route('servicios_agendados.generarPDF', ['id' => base64_encode($assign->id)]) }}">
                                                            Descargar Reporte
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        @endif
                                                        @if($assign->getFirma->registro_fotografico != null)
                                                        <a class="dropdown-item" href="{{ route('servicios_agendados.seePhotoRecord', ['id' => base64_encode($assign->id)]) }}">
                                                            <i class="feather icon-camera"></i> Ver Registro Fotográfico
                                                        </a>
                                                        @endif
                                                    @endif
                                                    
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
                    url: '{{ route('assignements.bulkDelete') }}',
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
@endsection
