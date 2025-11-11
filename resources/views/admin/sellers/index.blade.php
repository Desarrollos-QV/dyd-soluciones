@extends('layouts.app')
@section('title')
    Listado de Vendedores
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro de Vendedores</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de Vendedores</h4>
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-danger btn-sm mr-3" id="delete_selected">
                    <i data-feather="trash-2"></i> Eliminar Selección
                </button>
                <a href="{{ route('sellers.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Vendedores
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
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Telefono</th>
                                <th>Nivel de estudios</th>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $sell)
                            <tr>
                                <td>
                                    <input type="checkbox" id="select_element_{{ $sell->id }}" name="select_element_{{ $sell->id }}">
                                </td>
                                <td>
                                    <img src="{{ asset($sell->picture) }}" alt="Imagen de {{ $sell->name }}" width="50" height="50" class="rounded-circle">
                                </td>
                                <td>{{ $sell->name }}</td>
                                <td>{{ $sell->address }}</td>
                                <td>{{ $sell->phone }}</td> 
                                <td>{{ $sell->level_education }}</td>
                                <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Opciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('sellers.edit', $sell->id) }}">Editar</a>
                                            <hr /> 
                                            <form action="{{ route('sellers.destroy', $sell) }}"
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
                    let prospectId = $(this.node()).find('input[type="checkbox"]').attr('id').replace('select_element_', '');
                    selectedProspectIds.push(prospectId);
                });

                if (selectedProspectIds.length === 0) {
                    alertSwwet('Error', 'No hay Elementos seleccionados para eliminar.');
                    return;
                }

                // Confirmar la eliminación
                if (!confirm(`¿Estás seguro de que deseas eliminar ${selectedProspectIds.length} Elemento(s)?`)) {
                    return;
                }

                // Enviar la solicitud AJAX para eliminar los prospectos seleccionados
                $.ajax({
                    url: '{{ route("sellers.bulkDelete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedProspectIds
                    },
                    success: function(response) {
                        alertSwwet('Éxito', response.message);
                        // Recargar la página o actualizar la tabla
                        location.reload();
                    },
                    error: function(xhr) {
                        alertSwwet('Error', 'Ocurrió un error al eliminar los prospectos.');
                    }
                });
            });
        });
    </script>
@endsection