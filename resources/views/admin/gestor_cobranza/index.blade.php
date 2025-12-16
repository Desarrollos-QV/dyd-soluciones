@extends('layouts.app')
@section('title')
    Gestor de cobranza
@endsection
@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 fw-bold text-dark">
                    <i class="feather icon-handshake"></i> Gestor de Cobranza
                </h1>
                <p class="text-muted small">Gestiona pagos, deudas e ingresos de la plataforma</p>
            </div>
        </div>

        <!-- Cards Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Deuda</p>
                                <h4 class="text-danger fw-bold">$<span
                                        id="totalDeuda">{{ number_format($totalDeuda, 2) }}</span></h4>
                            </div>
                            <i class="feather icon-alert-circle text-danger" style="font-size: 2rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Pagos Procesados</p>
                                <h4 class="text-success fw-bold"><span id="totalPagos">{{ $pagosprocesados }}</span></h4>
                            </div>
                            <i class="feather icon-check-circle text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Ingresos Registrados</p>
                                <h4 class="text-info fw-bold">$<span
                                        id="totalIngresos">{{ number_format($ingresosRegistrados, 2) }}</span></h4>
                            </div>
                            <i class="feather icon-arrow-up text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Clientes Morosos</p>
                                <h4 class="text-warning fw-bold"><span id="clientesMorosos">{{ $clientesMorosos }}</span>
                                </h4>
                            </div>
                            <i class="feather icon-user-x text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar por cliente...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterStatus">
                            <option value="">-- Estado --</option>
                            <option value="pending">Pendiente</option>
                            <option value="overdue">Vencido</option>
                            <option value="paid">Pagado</option>
                        </select>
                    </div>
                    <!-- Date Range Filters -->
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFrom" placeholder="Desde">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateTo" placeholder="Hasta">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            <i class="feather icon-refresh-cw"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Cobranzas -->
        <div class="card border-0 shadow-sm">
            <div class="table-responsive" id="tablaResultados">
                <!-- Aquí se cargan los datos vía AJAX -->
                @include('admin.gestor_cobranza.table', ['data' => $data])
            </div>
        </div>
    </div>


    <!-- Modal Lateral - Información del Cliente -->
    <div class="modal fade" id="clienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-uppercase" id="clienteNombre">Información del Cliente</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabCliente">Datos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabServicios">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabHistorial">Historial</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Tab Datos -->
                        <div id="tabCliente" class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Nombre</p>
                                    <p class="fw-bold" id="clienteNombreVal">-</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Contrato</p>
                                    <p class="fw-bold" id="clienteDoc">-</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Consesión</p>
                                    <p class="fw-bold" id="clienteconsesion">-</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Factura</p>
                                    <p class="fw-bold" id="clienteFactura">-</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Dirección</p>
                                    <p class="fw-bold small" id="clienteDireccion">-</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <span class="badge bg-success" id="clienteEstado">Activo</span>
                                </div>
                            </div>
                        </div>


                        <!-- Tab Servicios -->
                        <div id="tabServicios" class="tab-pane fade">
                            <div id="serviciosContainer">
                                <p class="text-muted text-center">Cargando servicios...</p>
                            </div>
                        </div>

                        <!-- Tab Historial -->
                        <div id="tabHistorial" class="tab-pane fade">
                            <div id="historialContainer">
                                <p class="text-muted text-center">Cargando historial...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarNotificacion()">
                        <i class="fas fa-bell"></i> Enviar Notificación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Enviar Notificación -->
    <div class="modal fade" id="notificacionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold">Enviar Notificación de Pago</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Seleccionar medio de envío</label>
                        <div class="form-check pl-4">
                            <input class="form-check-input" type="radio" name="envioMedio" id="envioEmail" value="email"
                                checked>
                            <label class="form-check-label" for="envioEmail">
                                <i class="feather icon-mail"></i> Correo Electrónico
                            </label>
                        </div>
                        <div class="form-check pl-4">
                            <input class="form-check-input" type="radio" name="envioMedio" id="envioWp" value="whatsapp">
                            <label class="form-check-label" for="envioWp">
                                <i class="feather icon-message-circle"></i> WhatsApp
                            </label>
                        </div>
                        <div class="form-check pl-4">
                            <input class="form-check-input" type="radio" name="envioMedio" id="envioSms" value="sms">
                            <label class="form-check-label" for="envioSms">
                                <i class="feather icon-phone"></i> SMS
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mensaje</label>
                        <textarea class="form-control" id="mensajeNotificacion" rows="4"
                            placeholder="Escriba el mensaje...">Estimado cliente, le recordamos que su pago se encuentra vencido. Por favor, regularice su situación.</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarNotificacionConfirm()">
                        <i class="feather icon-send"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            cargarDatos();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Debounce for search input
            let timeout = null;
            document.getElementById('searchInput').addEventListener('keyup', function (e) {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    cargarDatos();
                }, 500);
            });

            document.getElementById('filterStatus').addEventListener('change', cargarDatos);
            document.getElementById('dateFrom').addEventListener('change', cargarDatos);
            document.getElementById('dateTo').addEventListener('change', cargarDatos);
        }

        function cargarDatos() {
            const route = "{{ route('collections.getCollecionAll') }}";

            $.ajax({
                url: route,
                method: "GET",
                data: {
                    search: $('#searchInput').val(),
                    status: $('#filterStatus').val(),
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                },
                beforeSend: function () {
                    $('#tablaResultados').html('<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
                },
                success: function (res) {
                    $('#tablaResultados').html(res.html);
                },
                error: function (xhr) {
                    console.error('Error cargando datos');
                    $('#tablaResultados').html('<div class="alert alert-danger">Ocurrió un error al cargar los datos.</div>');
                }
            });
        }

        function limpiarFiltros() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            cargarDatos();
        }

        function abrirModalCliente(btn) {
            const item = JSON.parse(btn.dataset.item);
            window.clienteNotifActual = item;
            const cliente = item.cliente;
            console.log(item)
            if (!cliente) return;

            document.getElementById('clienteNombre').textContent = cliente.nombre || '-';
            document.getElementById('clienteNombreVal').textContent = cliente.nombre || '-';
            document.getElementById('clienteDoc').innerHTML = cliente.contrato ? `<a href="${cliente.contrato}" target="_blank" class="text-decoration-none"><i class="feather icon-file-text"></i> Descargar</a>` : '<span class="text-muted">-</span>';
            document.getElementById('clienteconsesion').innerHTML = cliente.copia_consesion ? `<a href="${cliente.copia_consesion}" target="_blank" class="text-decoration-none"><i class="feather icon-file-text"></i> Descargar</a>` : '<span class="text-muted">-</span>';
            document.getElementById('clienteFactura').innerHTML = cliente.copa_factura ? `<a href="${cliente.copa_factura}" target="_blank" class="text-decoration-none"><i class="feather icon-file-text"></i> Descargar</a>` : '<span class="text-muted">-</span>';
            document.getElementById('clienteDireccion').textContent = cliente.direccion || '-';

            // Servicios
            const serviciosHTML = (cliente.asignaciones && cliente.asignaciones.length > 0)
                ? cliente.asignaciones.map(s => `
                        <div class="card mb-2 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <strong class="text-dark">${s.encargado_recibir || 'Sin encargado'}</strong><br>
                                        <small class="text-muted"><i class="feather icon-map-pin"></i> ${s.location || 'Sin ubicación'}</small>
                                    </div>
                                    <div class="col-md-5 text-end">
                                        <span class="badge bg-success">Activo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')
                : '<div class="text-center py-3"><p class="text-muted mb-0">No hay servicios asignados</p></div>';

            document.getElementById('serviciosContainer').innerHTML = serviciosHTML;

            // Historial Context
            document.getElementById('historialContainer').innerHTML = `
                        <div class="timeline-item mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>Cobro actual</strong>
                                        <span class="badge ${item.status === 'paid' ? 'bg-success' : 'bg-warning text-dark'}">${item.status}</span>
                                    </div>
                                    <small class="text-muted">Fecha vencimiento: ${item.due_date} - Monto: $${item.amount}</small>
                                </div>
                            </div>
                        </div>
                    `;

            new bootstrap.Modal(document.getElementById('clienteModal')).show();
        }

        function abrirModalNotificacion(btn) {
            if (btn && btn.dataset && btn.dataset.item) {
                window.clienteNotifActual = JSON.parse(btn.dataset.item);
            }
            if (!window.clienteNotifActual) return;
            new bootstrap.Modal(document.getElementById('notificacionModal')).show();
        }

        function enviarNotificacion() {
            abrirModalNotificacion();
        }

        function enviarNotificacionConfirm() {
            if (!window.clienteNotifActual) return;

            const item = window.clienteNotifActual;
            const route = `{{ url('collections') }}/${item.id}/notify`;
            const medio = document.querySelector('input[name="envioMedio"]:checked').value;
            const mensaje = document.getElementById('mensajeNotificacion').value;

            $.ajax({
                url: route,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: item.id,
                    medio: medio,
                    mensaje: mensaje
                },
                success: function (res) {
                    bootstrap.Modal.getInstance(document.getElementById('notificacionModal')).hide();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Enviado', 'Notificación enviada correctamente', 'success');
                    } else {
                        alert('Notificación enviada correctamente');
                    }
                },
                error: function (err) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'No se pudo enviar la notificación', 'error');
                    } else {
                        alert('Error al enviar notificación');
                    }
                }
            });
        }

        function checkToPaid(item) {
            const route = `{{ url('collections') }}/${item}/pay`;

            $.ajax({
                url: route,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': item
                },
                success: function (res) {
                    if (res.status == true) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Éxito', res.message, 'success').then(() => {
                                cargarDatos();
                            });
                        } else {
                            alert(res.message);
                            cargarDatos();
                        }
                    }
                },
                error: function (err) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'No se pudo procesar el pago', 'error');
                    } else {
                        alert('Error al procesar pago');
                    }
                }
            });
        }
    </script>
@endsection

@section('styles')
    <style>
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-outline-primary:hover i,
        .btn-outline-secondary:hover i,
        .btn-outline-info:hover i {
            opacity: 1;
        }

        .modal-lg {
            max-width: 900px;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-color: #0d6efd;
            background: none;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        .feather {
            display: inline-block;
            vertical-align: -0.125em;
            width: 1em;
            height: 1em;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            margin-right: 0.25rem;
        }
    </style>
@endsection