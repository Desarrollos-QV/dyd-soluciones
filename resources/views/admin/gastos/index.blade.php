@extends('layouts.app')
@section('title')
    Listado de Ingresos/Gastos
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registro de Gastos</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de Gastos</h4>
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-danger btn-sm mr-3" id="delete_selected">
                    <i data-feather="trash-2"></i> Eliminar Selección
                </button>
                <a href="{{ route('gastos.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Gasto
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
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Autoriza</th>
                                <th>Monto</th>
                                <th>Solicita</th>
                                <th>Motivo</th>
                                <th class="w-100 d-flex justify-content-end align-items-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gastos as $gasto)
                            <tr>
                                <td>
                                    <input type="checkbox" id="select_element_{{ $gasto->id }}"
                                        name="select_element_{{ $gasto->id }}">
                                </td>
                                <td>{{ $gasto->fecha }}</td>
                                <td>{{ $gasto->hora }}</td>
                                <td>{{ $gasto->autoriza ? $gasto->autoriza->name : 'Sin autorizacion' }}</td>
                                <td>${{ number_format($gasto->monto, 2) }}</td>
                                <td>{{ $gasto->solicita ? $gasto->solicita->name : "Sin Nombre" }}</td>
                                <td>{{ $gasto->motivo }}</td>
                                <td class=" d-flex justify-content-end align-items-center">
                                    <a href="{{ route('gastos.edit', $gasto) }}" class="btn btn-sm btn-warning mr-3">Editar</a>
                                    <form action="{{ route('gastos.destroy', $gasto) }}" method="POST" class="d-inline-block">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este gasto?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $gastos->links() }}
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
                    url: '{{ route('gastos.bulkDelete') }}',
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