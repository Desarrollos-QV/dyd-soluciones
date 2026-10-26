<div class="col-lg-7">
    <div class="card">
        <div class="card-header">
            @if($assignement->id)
                <h4>Editar Asignación #{{ $assignement->id }}</h4>
            @else
                <h4>Nueva Alta de servicio</h4>
            @endif
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control select2">
                        @foreach ($clientes as $cl)
                            <option value="{{ $cl->id }}"> {{ $cl->nombre }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="unidad_ids" class="form-label d-flex justify-content-between align-items-center">
                        <span>Unidades a Intervenir (Una o Varias)</span>
                        <button type="button" class="btn btn-link btn-xs text-success p-0 text-decoration-none" style="font-size: 13px;" data-toggle="modal" data-target="#modalAddUnidad">
                            + Agregar Unidad
                        </button>
                    </label>
                    <select name="unidad_ids[]" id="unidad_ids" class="form-control" multiple style="width: 100%;">
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                    <small class="form-text text-muted">Puedes seleccionar una o varias unidades de la flotilla del cliente.</small>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const clienteSelect = document.getElementById('cliente_id');
                    const initialClienteId = clienteSelect ? clienteSelect.value : null;
                    const assignedUnidadesIds = @json(isset($assignement) && $assignement->exists ? ($assignement->unidades()->pluck('unidades.id')->toArray() ?: ($assignement->unidad_id ? [$assignement->unidad_id] : [])) : []);

                    function initUnidadesSelect2() {
                        if (typeof $ !== 'undefined' && $.fn.select2) {
                            $('#unidad_ids').select2({
                                placeholder: 'Selecciona una o más unidades...',
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    }

                    function loadUnidades(clienteId) {
                        const $select = $('#unidad_ids');
                        $select.empty().prop('disabled', true);

                        if (!clienteId) {
                            $select.prop('disabled', false).trigger('change');
                            return;
                        }

                        fetch(`{{ url('clientes') }}/${clienteId}/unidades`)
                            .then(response => response.json())
                            .then(unidades => {
                                $select.empty();
                                if (unidades.length > 0) {
                                    unidades.forEach(unidad => {
                                        const isSelected = assignedUnidadesIds.includes(Number(unidad.id));
                                        const label = `${unidad.economico || 'Unidad'} (${unidad.placa || 'S/P'}) - ${unidad.marca || ''} ${unidad.modelo || ''}`;
                                        const option = new Option(label, unidad.id, isSelected, isSelected);
                                        $select.append(option);
                                    });
                                }
                                $select.prop('disabled', false).trigger('change');
                            })
                            .catch(error => {
                                console.error('Error fetching unidades:', error);
                                $select.prop('disabled', false).trigger('change');
                            });
                    }

                    initUnidadesSelect2();

                    // Carga inicial si hay un cliente seleccionado
                    if (initialClienteId) {
                        loadUnidades(initialClienteId);
                    }

                    if (clienteSelect) {
                        clienteSelect.addEventListener('change', function () {
                            loadUnidades(this.value);
                        });
                    }

                    // Inicializar select2 para cliente
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $('#cliente_id').select2({
                            placeholder: 'Escriba el nombre del cliente...',
                            allowClear: true,
                            width: '100%'
                        }).on('select2:select select2:unselect', function (e) {
                            loadUnidades($('#cliente_id').val());
                        });
                    }
                });
            </script>

            <div class="row mb-3">

                <div class="col-md-6">
                    <label for="tecnico_id">Tecnico asignado</label>
                    <select name="tecnico_id" id="tecnico_id" class="form-control">
                        <option value="0">Sin asignar aún</option>
                        @foreach ($tecnicos as $tec)
                            <option value="{{ $tec->id }}" @if($tec->id == $assignement->tecnico_id) selected @endif>
                                {{ $tec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-6">
                    <label for="tipo_servicio">Tipo de servicio</label>
                    <select id="tipo_servicio" name="tipo_servicio" class="form-control" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="Instalacion nueva" {{ $assignement->tipo_servicio == 'Instalacion nueva' ? 'selected' : '' }}>Instalación nueva</option>
                        <option value="Servicio de reparacion o mantenimiento" {{ $assignement->tipo_servicio == 'Servicio de reparacion o mantenimiento' ? 'selected' : '' }}>Servicio de reparación o mantenimiento</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label for="tel_contact">Telefono de contacto</label>
                    <input type="text" id="tel_contact" name="tel_contact" class="form-control"
                        value="{{ $assignement->tel_contact }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-6">
                    <label for="encargado_recibir">Encargado de recibir</label>
                    <input type="text" id="encargado_recibir" name="encargado_recibir" class="form-control" required
                        value="{{ $assignement->encargado_recibir }}">
                </div>
                <div class="col-lg-6">
                    <label for="viaticos">
                        Viaticos
                    </label>
                    <input type="text" id="viaticos" name="viaticos" class="form-control" step="0.01"
                        data-inputmask="'alias': 'currency'" required value="{{ $assignement->viaticos }}">
                    <div>
                        <label for="same_viaticos_check">¿Mismos Viaticos del servicio?</label>
                        <input type="checkbox" name="same_viaticos_check" id="same_viaticos_check" @if($assignement->same_viaticos)
                            checked @endif>
                    </div>
                </div>
            </div>

            <!--
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label for="tipo_vehiculo">Tipo de Vehiculo</label>
                    <input type="text" id="tipo_vehiculo" name="tipo_vehiculo" class="form-control"
                        value="{{ $assignement->tipo_vehiculo }}">
                </div>
                <div class="col-lg-3">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" class="form-control" value="{{ $assignement->marca }}">
                </div>
                <div class="col-lg-3">
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" class="form-control"
                        value="{{ $assignement->modelo }}">
                </div>
                <div class="col-lg-2">
                    <label for="placa">Placa</label>
                    <input type="text" id="placa" name="placa" class="form-control" value="{{ $assignement->placa }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="devices_id">Dispositivo a asignar</label>
                    <select name="devices_id" id="devices_id" class="form-control" required>
                        @foreach ($devices as $dev)
                            <option value="{{ $dev->id }}"> {{ $dev->dispositivo }} </option>
                        @endforeach
                    </select>
                </div>
            </div>-->

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="observaciones_mantenimiento">Observaciones mantenimiento</label>
                    <textarea name="observaciones_mantenimiento" id="observaciones_mantenimiento" class="form-control"
                        placeholder="observaciones mantenimiento">{!! $assignement->observaciones_mantenimiento !!}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-5">
    <div class="card">
        <div class="card-header">
            <h4>Ubicación del servicio</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="cliente_id">Ubicación</label>
                    <input id="pac-input" class="controls form-control" name="location"
                        value="{{$assignement->location}}" type="text" required placeholder="Ingresa una Ubicación">

                    <div class="row">
                        <div class="form-group col-md-6"><input type="hidden" name="lat" id="lat" class="form-control"
                                placeholder="Latitude" value="{{ $assignement->lat }}"></div>
                        <div class="form-group col-md-6"><input type="hidden" name="lng" id="lng" class="form-control"
                                placeholder="Longitude" value="{{ $assignement->lng }}"></div>
                    </div>

                    <div id="map" style="width: 100%;height: 400px;"></div>
                </div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary ml-3">
                    @if (isset($assignement))
                        Crear Servicio
                    @else
                        Actualizar
                    @endif
                </button>
                <a href="{{ route('assignements.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Unidad -->
<div class="modal fade" id="modalAddUnidad" tabindex="-1" aria-labelledby="modalAddUnidadLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUnidadLabel">Registrar Nueva Unidad</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <h6 class="text-primary mb-3">Información de la unidad</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_uni_economico" class="form-label">Económico <span class="text-danger">*</span></label>
                        <input type="text" id="new_uni_economico" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="new_uni_placa" class="form-label">Placa <span class="text-danger">*</span></label>
                        <input type="text" id="new_uni_placa" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="new_uni_tipo" class="form-label">Tipo de Unidad <span class="text-danger">*</span></label>
                        <input type="text" id="new_uni_tipo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="new_uni_fecha_inst" class="form-label">Fecha Instalación <span class="text-danger">*</span></label>
                        <input type="date" id="new_uni_fecha_inst" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="new_uni_fecha_cobro" class="form-label">Fecha de Cobro <span class="text-danger">*</span></label>
                        <input type="date" id="new_uni_fecha_cobro" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_uni_disp_inst" class="form-label">Dispositivo Instalado <span class="text-danger">*</span></label>
                        <select id="new_uni_disp_inst" class="form-select">
                            <option value="GPS">GPS</option>
                            <option value="DVR">DVR</option>
                            <option value="DASHCAM">DASHCAM</option>
                            <option value="SENSOR">SENSOR Y/O ACCESORIOS</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="new_uni_sim" class="form-label">SIM Asignada <span class="text-danger">*</span></label>
                        <select id="new_uni_sim" class="form-control">
                            <option value="">Seleccionar SIM</option>
                            @foreach($simcontrols ?? [] as $sim)
                                <option value="{{ $sim->id }}">{{ $sim->compañia }} - {{ $sim->numero_publico }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_uni_device" class="form-label">Dispositivo Asignado <span class="text-danger">*</span></label>
                        <select id="new_uni_device" class="form-control">
                            <option value="">Seleccionar Dispositivo</option>
                            @foreach($devices ?? [] as $device)
                                <option value="{{ $device->id }}">{{ $device->dispositivo }} - {{ $device->marca }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="new_uni_num_motor" class="form-label">Número de motor</label>
                        <input type="text" id="new_uni_num_motor" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="new_uni_sensor" class="form-label">Sensor y/o Accesorio</label>
                        <select id="new_uni_sensor" class="form-control">
                            <option value="">Seleccionar</option>
                            @foreach($sensores ?? [] as $sensor)
                                <option value="{{ $sensor->id }}">{{ $sensor->dispositivo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_uni_marca" class="form-label">Marca Del Vehículo</label>
                        <input type="text" id="new_uni_marca" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="new_uni_submarca" class="form-label">Submarca Del Vehículo</label>
                        <input type="text" id="new_uni_submarca" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="new_uni_anio" class="form-label">Año del Vehículo</label>
                        <input type="number" id="new_uni_anio" class="form-control" min="1900" max="{{ date('Y') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="new_uni_vin" class="form-label">Número VIN</label>
                        <input type="text" id="new_uni_vin" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="new_uni_apagado" class="form-label">¿Cuenta con apagado?</label>
                        <select id="new_uni_apagado" class="form-select">
                            <option value="si">SI</option>
                            <option value="no" selected>NO</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="new_uni_obs" class="form-label">Observaciones</label>
                        <textarea id="new_uni_obs" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <hr>
                <h6 class="text-primary mb-3">Información de la plataforma y sus costos</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="new_uni_costo_plat" class="form-label">Costo de la Plataforma</label>
                        <input type="number" step="0.01" id="new_uni_costo_plat" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="new_uni_costo_sim" class="form-label">Costo del SIM</label>
                        <input type="number" step="0.01" id="new_uni_costo_sim" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="new_uni_pago_mensual" class="form-label">Pago Mensual</label>
                        <input type="number" step="0.01" id="new_uni_pago_mensual" class="form-control" value="0">
                    </div>
                </div>

                <hr>
                <h6 class="text-primary mb-3">Información de Cuenta/Accesos y Asignación</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="new_uni_name_empresa" class="form-label">Nombre de la empresa</label>
                        <input type="text" id="new_uni_name_empresa" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre usuario 1</label>
                        <input type="text" id="new_uni_user_1" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña 1</label>
                        <input type="text" id="new_uni_pass_1" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre usuario 2</label>
                        <input type="text" id="new_uni_user_2" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña 2</label>
                        <input type="text" id="new_uni_pass_2" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="new_uni_num_emergencia" class="form-label">Número de emergencia</label>
                        <input type="tel" id="new_uni_num_emergencia" class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_save_unidad">Guardar Unidad</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSaveUnidad = document.getElementById('btn_save_unidad');
    if (btnSaveUnidad) {
        btnSaveUnidad.addEventListener('click', async function() {
            const cliente_id = document.getElementById('cliente_id').value;
            if (!cliente_id) {
                Swal.fire({ icon: 'warning', title: 'Cliente requerido', text: 'Por favor seleccione un cliente en el formulario principal primero.' });
                return;
            }

            const payload = {
                cliente_id: cliente_id,
                economico: document.getElementById('new_uni_economico').value.trim(),
                placa: document.getElementById('new_uni_placa').value.trim(),
                tipo_unidad: document.getElementById('new_uni_tipo').value.trim(),
                fecha_instalacion: document.getElementById('new_uni_fecha_inst').value,
                fecha_cobro: document.getElementById('new_uni_fecha_cobro').value,
                dispositivo_instalado: document.getElementById('new_uni_disp_inst').value,
                simcontrol_id: document.getElementById('new_uni_sim').value,
                devices_id: document.getElementById('new_uni_device').value,
                numero_de_motor: document.getElementById('new_uni_num_motor').value.trim(),
                sensor: document.getElementById('new_uni_sensor').value,
                marca: document.getElementById('new_uni_marca').value.trim(),
                submarca: document.getElementById('new_uni_submarca').value.trim(),
                anio_unidad: document.getElementById('new_uni_anio').value,
                vin: document.getElementById('new_uni_vin').value.trim(),
                cuenta_con_apagado: document.getElementById('new_uni_apagado').value,
                observaciones: document.getElementById('new_uni_obs').value.trim(),
                costo_plataforma: document.getElementById('new_uni_costo_plat').value,
                costo_sim: document.getElementById('new_uni_costo_sim').value,
                pago_mensual: document.getElementById('new_uni_pago_mensual').value,
                name_empresa: document.getElementById('new_uni_name_empresa').value.trim(),
                credenciales_user: [
                    document.getElementById('new_uni_user_1').value.trim(),
                    document.getElementById('new_uni_user_2').value.trim()
                ],
                credenciales_pass: [
                    document.getElementById('new_uni_pass_1').value.trim(),
                    document.getElementById('new_uni_pass_2').value.trim()
                ],
                numero_de_emergencia: document.getElementById('new_uni_num_emergencia').value.trim()
            };

            if (!payload.economico || !payload.placa || !payload.tipo_unidad || !payload.fecha_instalacion || !payload.fecha_cobro || !payload.simcontrol_id || !payload.devices_id || !payload.dispositivo_instalado) {
                Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor complete todos los campos obligatorios (*).' });
                return;
            }

            btnSaveUnidad.disabled = true;
            btnSaveUnidad.textContent = 'Guardando...';

            try {
                const response = await fetch('{{ route("unidades.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (data.ok) {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: 'Unidad creada correctamente', timer: 1500, showConfirmButton: false });
                    
                    // Add new option to select and select it
                    const $unidadSelect = $('#unidad_ids');
                    if ($unidadSelect.length) {
                        const label = `${data.unidad.economico || 'Unidad'} (${data.unidad.placa || 'S/P'}) - ${data.unidad.marca || ''} ${data.unidad.modelo || ''}`;
                        const newOption = new Option(label, data.unidad.id, true, true);
                        $unidadSelect.append(newOption).trigger('change');
                    }

                    // Clean inputs
                    document.querySelectorAll('#modalAddUnidad input, #modalAddUnidad textarea').forEach(el => {
                        if (el.type !== 'date' && el.type !== 'number') el.value = '';
                    });
                    
                    $('#modalAddUnidad').modal('hide');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al guardar la unidad.' });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error inesperado al guardar la unidad.' });
            } finally {
                btnSaveUnidad.disabled = false;
                btnSaveUnidad.textContent = 'Guardar Unidad';
            }
        });
    }
});
</script>