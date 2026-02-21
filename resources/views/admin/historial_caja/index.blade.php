@extends('layouts.app')
@section('title')
    Historial de Caja y Administración
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Historial de Caja y Administración</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Caja y Administración</h4>
                <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-toggle="modal"
                    data-target="#addMovementModal">
                    <i data-feather="plus"></i> Nuevo Movimiento
                </a>
            </div>
        </div>

        <!-- Agregar Ingresos, Egresos y Sumatoria en 3 Cards -->
        <!-- Cards de Totales -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Ingresos</h6>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <h3 class="mb-2 text-success">${{ number_format($totalIngresos, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Total Egresos</h6>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <h3 class="mb-2 text-danger">${{ number_format($totalEgresos, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Balance Total</h6>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <h3 class="mb-2 {{ $totalBalance >= 0 ? 'text-primary' : 'text-danger' }}">
                                ${{ number_format($totalBalance, 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-center g-2 mb-4">
                        <div class="col-md-2">
                            <label for="fecha_inicio">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control"
                                value="{{ request('fecha_inicio') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="fecha_fin">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="form-select">
                                <option value="">Todos</option>
                                <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso
                                </option>
                                <option value="egreso" {{ request('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                            </select>
                        </div>

                        <div class="col-md-2 mt-4">
                            <button class="btn btn-success">Filtrar</button>
                        </div>

                        <div class="col-md-4" style="display: flex;justify-content: end; gap: 5px;">
                            <a href="{{ route('historial-caja.exportarExcel', ['fecha_inicio' => request('fecha_inicio'), 'fecha_fin' => request('fecha_fin'), 'tipo' => request('tipo')]) }}" class="btn btn-info text-white">
                                <i data-feather="download"></i> Descargar Excel
                            </a>
                        </div>
                    </form>

                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Concepto</th>
                                <th>Método de Pago</th>
                                <th>Monto</th>
                                <th>Autorizado por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historial as $item)
                                <tr>
                                    <td>{{ $item->fecha }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->tipo == 'ingreso' ? 'success' : 'danger' }} text-white">
                                            {{ ucfirst($item->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->concepto }}</td>
                                    <td>{{ $item->metodo_pago ?? '- Sin Especificar' }}</td>
                                    <td>${{ $item->monto }}</td>
                                    <td>{{ $item->Autoriza ? ucfirst($item->Autoriza->name . ' ' . $item->Autoriza->lastname) : '-' }}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-id="{{ $item->id }}"
                                            class="btn btn-warning btn-sm btnEdit">Editar</a>
                                        <form method="POST" action="{{ route('historial-caja.destroy', $item) }}"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar registro?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $historial->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ModalBox para agregar nuevo movimiento -->
    <div class="modal fade" id="addMovementModal" tabindex="-1" aria-labelledby="addMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMovementModalLabel">Agregar Nuevo Movimiento</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="createMovementModal">
                    @csrf
                    <input type="hidden" name="movement" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="number">Monto</label>
                                        <input name="monto" class="form-control mb-4 mb-md-0" step="0.01"
                                            data-inputmask="'alias': 'currency'" required />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="tipo">Tipo de Movimiento</label>
                                        <select name="tipo" id="tipo" class="form-select" required>
                                            <option value="">Seleccion un elemento</option>
                                            <option value="ingreso">Ingreso</option>
                                            <option value="egreso">Egreso</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
 
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="date">Fecha</label>
                                        <input type="date" name="fecha" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="time">Hora</label>
                                        <input type="time" name="hora" class="form-control"  required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="autorizado_por">Quien lo autoriza</label>
                                        <select name="autorizado_por" id="autorizado_por" class="form-select">
                                            <option value="">Seleccion un elemento</option>
                                            <option value="1">SuperAdmin <sub>(SuperAdmin)</sub> </option>
                                            @foreach ($administradores as $admin)
                                                <option value="{{ $admin->id }}">
                                                    {{ $admin->name }} <sub>({{ $admin->role }})</sub>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="solicitado_por">Quien lo solicita</label>
                                        <select name="solicitado_por" id="solicitado_por" class="form-select">
                                            <option value="">Seleccion un elemento</option>
                                            @foreach ($tecnicos as $tecns)
                                                <option value="{{ $tecns->id }}">
                                                    {{ $tecns->name }} <sub>({{ $tecns->role }})</sub>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="descripcion">Descripción de lo adquirido</label>
                                        <textarea name="descripcion" id="descripcion" class="form-control" maxlength="50" rows="5"
                                            placeholder="Describe lo que se adquiere"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="motivo">Motivo</label>
                                        <textarea name="motivo" id="motivo" class="form-control" maxlength="50" rows="5"
                                            placeholder="Describe lo que se adquiere"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script>
        $(document).ready(function() {
            /**
             * Guardar nuevo movimiento via AJAX
             */
            $('#createMovementModal').on('submit', function(event) {
                event.preventDefault();
                var form = $(this);
                let id = $("#addMovementModal input[name='movement']").val();
                const url    = id ? 'historial-caja/' + id : "{{ route('gastos.store') }}";
                const method = id ? 'PUT' : 'POST';
                console.log(url, method);
                fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            monto: form.find('input[name="monto"]').val(),
                            tipo: form.find('select[name="tipo"]').val(),
                            fecha: form.find('input[name="fecha"]').val(),
                            hora: form.find('input[name="hora"]').val(),
                            autorizado_por: form.find('select[name="autorizado_por"]').val(),
                            solicitado_por: form.find('select[name="solicitado_por"]').val(),
                            descripcion: form.find('textarea[name="descripcion"]').val(),
                            motivo: form.find('textarea[name="motivo"]').val(),
                        })
                    })
                   .then(async res => {
                        if(res.ok){
                            Swal.fire(
                                'Éxito',
                                'Movimiento guardado correctamente.',
                                'success'
                            );
                            location.reload();
                        } else {
                            Swal.fire(
                                'Error',
                                'Hubo un problema al guardar el movimiento.',
                                'error'
                            );
                        }
                    })
                    .catch(err => {
                        alert('Error al guardar el movimiento. Por favor, inténtelo de nuevo.');
                    });
                
            });

            /**
             * Editar movimiento
             */
            $('.btnEdit').on('click', function() {
                let id = $(this).attr('data-id');
                let route = 'historial-caja/getElement/' + id;
                
                $.ajax({
                    url: route,
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        if(response.status !== 'success'){
                            alert('Error al obtener los datos del movimiento.');
                            return;
                        }

                        let data = response.data;
                        $("#addMovementModal input[name='movement']").val(data.id);
                        // Rellenar el formulario del modal con los datos obtenidos
                        $('#addMovementModal input[name="monto"]').val(data.monto);
                        $('#addMovementModal select[name="tipo"]').val(data.tipo);
                        $('#addMovementModal input[name="fecha"]').val(data.fecha);
                        $('#addMovementModal input[name="hora"]').val(data.hora);
                        $('#addMovementModal select[name="autorizado_por"]').val(data.autorizado_por);
                        $('#addMovementModal select[name="solicitado_por"]').val(data.solicitado_por);
                        $('#addMovementModal textarea[name="descripcion"]').val(data.descripcion);
                        $('#addMovementModal textarea[name="motivo"]').val(data.motivo);
                        // Mostrar el modal
                        $('#addMovementModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Error al cargar el formulario de edición. Por favor, inténtelo de nuevo.');
                    }
                });
                
            });
                
        });
    </script>
@endsection
