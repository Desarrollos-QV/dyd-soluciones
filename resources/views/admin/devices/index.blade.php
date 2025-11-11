@extends('layouts.app')
@section('title')
    Listado de Control de Inventario
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registro de Control de Inventario</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Registro de Control de Inventario</h4>

                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-danger btn-sm mr-3" id="delete_selected">
                        <i data-feather="trash-2"></i> Eliminar Selección
                    </button>
                    <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                        <i data-feather="plus"></i> Nuevo Control de Inventario
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">


            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableResponsive" class="w-100 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Dispositivo</th>
                                    <th>Marca</th>
                                    <th>Camaras</th>
                                    <th>Generacion</th>
                                    <th>IMEI asignado</th>
                                    <th>Garantia</th>
                                    <th>Accesorios</th>
                                    <th>IA</th>
                                    <th>Otra_empresa</th>
                                    <th>Stock</th>
                                    <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($devices as $dev)
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="select_element_{{ $dev->id }}"
                                                name="select_element_{{ $dev->id }}">
                                        </td>
                                        <td>{{ $dev->dispositivo }}</td>
                                        <td>{{ $dev->marca }}</td>
                                        <td>{{ $dev->camaras }}</td>
                                        <td>{{ $dev->generacion }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $dev->imei }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($dev->garantia)->format('Y-m-d') }}</td>
                                        <td>{{ $dev->accesorios }}</td>
                                        <td>
                                            @php
                                                switch ($dev->ia) {
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
                                            <span class="{{ $badgeClass }} text-white text-uppercase">
                                                {{ $dev->ia }}</span>
                                        </td>
                                        <td>
                                            @php
                                                switch ($dev->otra_empresa) {
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
                                            <span class="{{ $badgeClass }} text-white text-uppercase">
                                                {{ $dev->otra_empresa }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-white"> Qty-{{ $dev->stock }}</span>
                                        </td>
                                        <td class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('devices.edit', $dev->id) }}">Editar</a>
                                                    <hr />
                                                    <form action="{{ route('devices.destroy', $dev) }}" method="POST"
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
                    url: '{{ route('devices.bulkDelete') }}',
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
