<div class="col-lg-8">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <h5>Información de la unidad</h5>
            </h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="economico">Economico</label>
                        <input type="text" name="economico" id="economico" class="form-control" required
                            value="{{ $unidad->economico }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="placa">Placa</label>
                        <input type="text" name="placa" id="placa" class="form-control" required
                            value="{{ $unidad->placa }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-4">
                        <label for="tipo_unidad">Tipo de unidad</label>
                        <input type="text" name="tipo_unidad" id="tipo_unidad" class="form-control" required max="20"
                            value="{{ $unidad->tipo_unidad }}" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="fecha_instalacion">Fecha Instalacion</label>
                        <input type="date" name="fecha_instalacion" id="fecha_instalacion" class="form-control"
                            value="{{ old('fecha_instalacion', isset($unidad->fecha_instalacion) ? \Carbon\Carbon::parse($unidad->fecha_instalacion)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="fecha_cobro">Fecha de Cobro</label>
                        <input type="date" name="fecha_cobro" id="fecha_cobro" class="form-control"
                            value="{{ old('fecha_cobro', isset($unidad->fecha_cobro) ? \Carbon\Carbon::parse($unidad->fecha_cobro)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="dispositivo_instalado">Dispositivo instalado</label>
                        <select name="dispositivo_instalado" id="dispositivo_instalado" class="form-select" required>
                            <option value="DVR" @if($unidad->dispositivo_instalado == 'DVR') selected @endif>DVR</option>
                            <option value="GPS" @if($unidad->dispositivo_instalado == 'GPS') selected @endif>GPS</option>
                            <option value="DASHCAM" @if($unidad->dispositivo_instalado == 'DASHCAM') selected @endif>DASHCAM</option>
                            <option value="SENSOR" @if($unidad->dispositivo_instalado == 'SENSOR') selected @endif>SENSOR Y/O ACCESORIOS</option>
                        </select>
                        <div class="row" id="other_dispositivo_instalado" style="display: none;">
                            <div class="col-md-12 mt-2" id="inner_other_disp">
                                 <input type="text" id="input_dispositivo_instalado" class="form-control"
                                    value="{{ $unidad->dispositivo_instalado }}" placeholder="Ingrese el nombre de la plataforma">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label for="simcontrol_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Vincular SIM</span>
                            <button type="button" class="btn btn-link btn-xs text-success p-0 text-decoration-none" style="font-size: 13px;" data-toggle="modal" data-target="#modalAddSim">
                                + Agregar SIM
                            </button>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text py-0 px-2" style="background: transparent; border-right: none;">
                                🔍
                            </span>
                            <input type="text" id="search_sim" class="form-control form-control-sm ps-1" placeholder="Buscar SIM..." style="border-left: none; max-width: 150px; font-size: 0.8rem;">
                            <select name="simcontrol_id" id="simcontrol_id" class="form-select" required>
                                <option value="">Seleccionar SIM</option>
                                @foreach($simcontrols as $sim)
                                    <option value="{{ $sim->id }}" data-type="{{ $sim->type }}" @if($unidad->simcontrol_id == $sim->id) selected @endif>
                                        {{ $sim->compañia }} - {{ $sim->numero_publico }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="devices_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Vincular de Inventario</span>
                            <button type="button" class="btn btn-link btn-xs text-success p-0 text-decoration-none" style="font-size: 13px;" data-toggle="modal" data-target="#modalAddDevice">
                                + Agregar Inventario
                            </button>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text py-0 px-2" style="background: transparent; border-right: none;">
                                🔍
                            </span>
                            <input type="text" id="search_device" class="form-control form-control-sm ps-1" placeholder="Buscar..." style="border-left: none; max-width: 150px; font-size: 0.8rem;">
                            <select name="devices_id" id="devices_id" class="form-select" required>
                                <option value="">Seleccionar Dispositivo</option>
                                @foreach($devices as $device)
                                    <option value="{{ $device->id }}" data-type="{{ $device->type }}" @if($unidad->devices_id == $device->id) selected @endif>
                                        {{ $device->dispositivo }} - {{ $device->marca }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <label for="numero_de_motor">Número de motor</label>
                        <input type="text" name="numero_de_motor" id="numero_de_motor" class="form-control" required
                            value="{{ $unidad->numero_de_motor }}">
                    </div>
                    
                    <div class="col-lg-3">
                        <label for="sensor" class="form-label d-flex justify-content-between align-items-center">
                            <span>Sensor y/o Accesorio</span>
                            <button type="button" class="btn btn-link btn-xs text-success p-0 text-decoration-none" style="font-size: 13px;" data-toggle="modal" data-target="#modalAddSensor">
                                + Agregar
                            </button>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text py-0 px-2" style="background: transparent; border-right: none;">
                                🔍
                            </span>
                            <input type="text" id="search_sensor" class="form-control form-control-sm ps-1" placeholder="Buscar..." style="border-left: none; max-width: 90px; font-size: 0.8rem;">
                            <select name="sensor" id="sensor" class="form-select" required>
                                <option value="">Seleccionar</option>
                                @foreach($sensores as $sensor)
                                    <option value="{{ $sensor->id }}" data-type="{{ $sensor->type }}" @if($unidad->sensor == $sensor->id) selected @endif>
                                        {{ $sensor->dispositivo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="marca">Marca Del Vehículo</label>
                        <input type="text" name="marca" id="marca" class="form-control" required
                            value="{{ $unidad->marca }}">
                    </div>
                    <div class="col-lg-4">
                        <label for="submarca">Submarca Del Vehículo</label>
                        <input type="text" name="submarca" id="submarca" class="form-control" required
                            value="{{ $unidad->submarca }}">
                    </div>
                    <div class="col-lg-2">
                        <label for="anio_unidad">Año del Vehículo</label>
                        <input type="number" name="anio_unidad" id="anio_unidad" class="form-control"
                        min="1900" max="{{ date('Y') }}"
                        value="{{ $unidad->anio_unidad ?? old('anio_unidad') }}" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="vin">Número VIN</label>
                        <input type="text" name="vin" id="vin" class="form-control" required
                            value="{{ $unidad->vin }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="cuenta_con_apagado">¿Cuenta con apagado?</label>
                        <select name="cuenta_con_apagado" id="cuenta_con_apagado" class="form-select" required>
                            <option value="si">SI</option>
                            <option value="no">NO</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control mb-4" rows="5" cols="5" placeholder="Ingresa las observaciones correspondientes">{!! $unidad->observaciones !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">
                <h5>Información de la plataforma y sus costos</h5>
            </h4>
        </div>

        <div class="card-body">
            <div class="form-group">
            
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="costo_plataforma">Costo de la Plataforma</label>
                        <input name="costo_plataforma" id="costo_plataforma" class="form-control" step="0.01" data-inputmask="'alias': 'currency'"
                            value="{{ $unidad->costo_plataforma ?? 0 }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="costo_sim">Costo del SIM</label>
                        <input name="costo_sim" id="costo_sim" class="form-control" step="0.01" data-inputmask="'alias': 'currency'"
                            value="{{ $unidad->costo_sim ?? 0 }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="pago_mensual">Pago Mensual</label>
                        <input  name="pago_mensual" id="pago_mensual" class="form-control" step="0.01" data-inputmask="'alias': 'currency'"
                            value="{{ $unidad->pago_mensual ?? 0 }}">
                    </div>
                </div>

               
            </div>
        </div>
    </div>

</div>
<div class="col-lg-4">

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <h5>Información de Cuenta/Accesos</h5>
            </h4>
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <label for="name_empresa">Nombre de la empresa</label>
                        <input type="text" name="name_empresa" id="name_empresa" class="form-control"
                            value="{{ $unidad->name_empresa }}">
                    </div>
                </div>
                
                @php
                    $credenciales = json_decode($unidad->credenciales ?? '[]', true); 
                @endphp
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="credenciales_user[]">Nombre usuario</label>
                        <input type="text" name="credenciales_user[]" id="credenciales_user[]" class="form-control" value="{{ $credenciales['user'][0] ?? '' }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="credenciales_pass[]">Contraseña</label>
                        <input type="text" name="credenciales_pass[]" id="credenciales_pass[]" class="form-control" value="{{ $credenciales['pass'][0] ?? '' }}">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="credenciales_user[]">Nombre usuario</label>
                        <input type="text" name="credenciales_user[]" id="credenciales_user[]" class="form-control" value="{{ $credenciales['user'][1] ?? '' }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="credenciales_pass[]">Contraseña</label>
                        <input type="text" name="credenciales_pass[]" id="credenciales_pass[]" class="form-control" value="{{ $credenciales['user'][1] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <h5>Asignaciónes</h5>
            </h4>
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="cliente_id" class="form-label d-flex justify-content-between align-items-center">
                            <span>Asignado a cliente:</span>
                            <button type="button" class="btn btn-link btn-xs text-success p-0 text-decoration-none" style="font-size: 13px;" data-toggle="modal" data-target="#modalAddCliente">
                                + Agregar Cliente
                            </button>
                        </label>
                        <div class="input-group mb-4">
                            <span class="input-group-text py-0 px-2" style="background: transparent; border-right: none;">
                                🔍
                            </span>
                            <input type="text" id="search_cliente" class="form-control form-control-sm ps-1" placeholder="Buscar cliente..." style="border-left: none; max-width: 180px; font-size: 0.8rem;">
                            <select name="cliente_id" id="cliente_id" class="form-select" required>
                                @foreach($clientes as $client)
                                <option value="{{ $client->id }}" @if($client->id == $unidad->cliente_id) selected @endif >{{$client->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <label for="numero_de_emergencia">Número de emergencia</label>
                        <input type="tel" name="numero_de_emergencia" id="numero_de_emergencia"
                            class="form-control"
                            value="{{ $unidad->numero_de_emergencia ?? old('numero_de_emergencia') }}">
                    </div>

                    <!--<div class="col-lg-12 mt-4">
                        <label for="foto_unidad">Fotografias de la unidad</label>
                        <div id="dropzoneForm" class="dropzone"></div> 
                    </div>-->
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <button type="submit" id="submit-all"
                            class="btn btn-primary mr-4">{{ isset($unidad->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('unidades.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODALES DE CREACIÓN RÁPIDA -->
<!-- ================================================================= -->

<!-- Modal Agregar SIM -->
<div class="modal fade" id="modalAddSim" tabindex="-1" aria-labelledby="modalAddSimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddSimLabel">Agregar Nueva SIM</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_sim_type" class="form-label">Tipo</label>
                    <select id="new_sim_type" class="form-select">
                        <option value="DVR">DVR</option>
                        <option value="GPS">GPS</option>
                        <option value="DASHCAM">DASHCAM</option>
                        <option value="SENSOR">SENSOR Y/O ACCESORIOS</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="new_sim_company" class="form-label">Compañía</label>
                    <input type="text" id="new_sim_company" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new_sim_number" class="form-label">Número SIM (Mín. 10 caracteres)</label>
                    <input type="text" id="new_sim_number" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new_sim_public" class="form-label">Número Público</label>
                    <input type="text" id="new_sim_public" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new_sim_obs" class="form-label">Observaciones</label>
                    <textarea id="new_sim_obs" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_save_sim">Guardar SIM</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Dispositivo a Inventario -->
<div class="modal fade" id="modalAddDevice" tabindex="-1" aria-labelledby="modalAddDeviceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddDeviceLabel">Agregar Dispositivo a Inventario</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_type" class="form-label">Tipo</label>
                        <select id="new_dev_type" class="form-select">
                            <option value="DVR">DVR</option>
                            <option value="GPS">GPS</option>
                            <option value="DASHCAM">DASHCAM</option>
                            <option value="SENSOR">SENSOR Y/O ACCESORIOS</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_name" class="form-label">Dispositivo</label>
                        <input type="text" id="new_dev_name" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_brand" class="form-label">Marca</label>
                        <input type="text" id="new_dev_brand" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_gen" class="form-label">Generación</label>
                        <input type="text" id="new_dev_gen" class="form-control" value="G1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="new_dev_imei" class="form-label">IMEI (Para múltiples, separar por coma)</label>
                        <input type="text" id="new_dev_imei" class="form-control" placeholder="Ej: 861234567890123, 861234567890124">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_warranty" class="form-label">Garantía</label>
                        <input type="date" id="new_dev_warranty" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_ia" class="form-label">IA</label>
                        <select id="new_dev_ia" class="form-select">
                            <option value="no">NO</option>
                            <option value="si">SI</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_stock" class="form-label">Stock del dispositivo (Se calcula de los IMEIs)</label>
                        <input type="number" id="new_dev_stock" class="form-control" value="1" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_stock_alert" class="form-label">Stock Mínimo para Alerta</label>
                        <input type="number" id="new_dev_stock_alert" class="form-control" value="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_other_company" class="form-label">¿Perteneció a otra empresa?</label>
                        <select id="new_dev_other_company" class="form-select">
                            <option value="no">NO</option>
                            <option value="si">SI</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_dev_obs" class="form-label">Observaciones</label>
                        <textarea id="new_dev_obs" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_save_device">Guardar Dispositivo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Sensor / Accesorio -->
<div class="modal fade" id="modalAddSensor" tabindex="-1" aria-labelledby="modalAddSensorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddSensorLabel">Agregar Nuevo Sensor / Accesorio</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="new_sens_type" value="SENSOR">
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_name" class="form-label">Sensor / Accesorio</label>
                        <input type="text" id="new_sens_name" class="form-control" placeholder="Ej: Sensor de Temperatura, Botón de Pánico">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_brand" class="form-label">Marca</label>
                        <input type="text" id="new_sens_brand" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_gen" class="form-label">Generación</label>
                        <input type="text" id="new_sens_gen" class="form-control" value="G1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_imei" class="form-label">IMEI o Identificador (Para múltiples, separar por coma)</label>
                        <input type="text" id="new_sens_imei" class="form-control" placeholder="Ej: SENS001, SENS002">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_warranty" class="form-label">Garantía</label>
                        <input type="date" id="new_sens_warranty" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_ia" class="form-label">IA</label>
                        <select id="new_sens_ia" class="form-select">
                            <option value="no">NO</option>
                            <option value="si">SI</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_stock" class="form-label">Stock (Se calcula de los identificadores)</label>
                        <input type="number" id="new_sens_stock" class="form-control" value="1" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_stock_alert" class="form-label">Stock Mínimo para Alerta</label>
                        <input type="number" id="new_sens_stock_alert" class="form-control" value="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_other_company" class="form-label">¿Perteneció a otra empresa?</label>
                        <select id="new_sens_other_company" class="form-select">
                            <option value="no">NO</option>
                            <option value="si">SI</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_sens_obs" class="form-label">Observaciones</label>
                        <textarea id="new_sens_obs" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_save_sensor">Guardar Sensor</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Cliente -->
<div class="modal fade" id="modalAddCliente" tabindex="-1" aria-labelledby="modalAddClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddClienteLabel">Agregar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" id="new_cli_nombre" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                        <input type="email" id="new_cli_email" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_telefono" class="form-label">Número de contacto <span class="text-danger">*</span></label>
                        <input type="tel" id="new_cli_telefono" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_alterno" class="form-label">Número alterno</label>
                        <input type="tel" id="new_cli_alterno" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="new_cli_direccion" class="form-label">Dirección personal <span class="text-danger">*</span></label>
                        <input type="text" id="new_cli_direccion" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_empresa" class="form-label">Empresa <span class="text-danger">*</span></label>
                        <input type="text" id="new_cli_empresa" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="new_cli_tipo_empresa" class="form-label">Tipo de empresa <span class="text-danger">*</span></label>
                        <select id="new_cli_tipo_empresa" class="form-select">
                            <option value="Persona Fisica">Persona Física</option>
                            <option value="Persona Moral">Persona Moral</option>
                            <option value="Gobierno">Gobierno</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="new_cli_dir_empresa" class="form-label">Dirección de empresa <span class="text-danger">*</span></label>
                        <input type="text" id="new_cli_dir_empresa" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_save_cliente">Guardar Cliente</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // -----------------------------------------------------------------
    // Live Search Filtering for SIM, Device and Sensor Selects
    // -----------------------------------------------------------------
    const searchSim = document.getElementById('search_sim');
    const searchDevice = document.getElementById('search_device');
    const searchSensor = document.getElementById('search_sensor');
    
    const simSelect = document.getElementById('simcontrol_id');
    const deviceSelect = document.getElementById('devices_id');
    const sensorSelect = document.getElementById('sensor');
    const clienteSelect = document.getElementById('cliente_id');
    const searchCliente = document.getElementById('search_cliente');
    const dispInstalado = document.getElementById('dispositivo_instalado');

    // Filter function for SIM and Device (which are tied to dispositivo_instalado)
    function filterSelects() {
        const type = dispInstalado.value;
        const querySim = searchSim.value.toLowerCase().trim();
        const queryDev = searchDevice.value.toLowerCase().trim();

        // Filter SIM
        let firstSim = null;
        Array.from(simSelect.options).forEach(option => {
            const optType = option.getAttribute('data-type');
            if (!optType) return; // Skip placeholder

            const matchesType = (type === 'Otro') ? (optType === 'Otro') : (optType === type);
            const matchesQuery = option.textContent.toLowerCase().includes(querySim);

            if (matchesType && matchesQuery) {
                option.hidden = false;
                option.disabled = false;
                if (!firstSim) firstSim = option;
            } else {
                option.hidden = true;
                option.disabled = true;
            }
        });

        // Filter Device
        let firstDev = null;
        Array.from(deviceSelect.options).forEach(option => {
            const optType = option.getAttribute('data-type');
            if (!optType) return;

            const matchesType = (type === 'Otro') ? (optType === 'Otro') : (optType === type);
            const matchesQuery = option.textContent.toLowerCase().includes(queryDev);

            if (matchesType && matchesQuery) {
                option.hidden = false;
                option.disabled = false;
                if (!firstDev) firstDev = option;
            } else {
                option.hidden = true;
                option.disabled = true;
            }
        });

        // Handle selected options if they became hidden
        const selSim = simSelect.selectedOptions[0];
        if (selSim && selSim.hidden) {
            simSelect.value = (firstSim && simSelect.value !== "") ? firstSim.value : "";
        }

        const selDev = deviceSelect.selectedOptions[0];
        if (selDev && selDev.hidden) {
            deviceSelect.value = (firstDev && deviceSelect.value !== "") ? firstDev.value : "";
        }
    }

    // Filter function for Sensor (independent of dispositivo_instalado)
    function filterSensorSelect() {
        const querySens = searchSensor.value.toLowerCase().trim();
        let firstSens = null;
        Array.from(sensorSelect.options).forEach(option => {
            const optType = option.getAttribute('data-type');
            if (!optType) return;

            const matchesQuery = option.textContent.toLowerCase().includes(querySens);

            if (matchesQuery) {
                option.hidden = false;
                option.disabled = false;
                if (!firstSens) firstSens = option;
            } else {
                option.hidden = true;
                option.disabled = true;
            }
        });

        const selSens = sensorSelect.selectedOptions[0];
        if (selSens && selSens.hidden) {
            sensorSelect.value = (firstSens && sensorSelect.value !== "") ? firstSens.value : "";
        }
    }

    // Overwrite the global filterOptions so the page load and change events use our combined filter
    window.filterOptions = function(type) {
        filterSelects();
    };

    // Filter function for Cliente (independent of dispositivo_instalado)
    function filterClienteSelect() {
        const query = searchCliente.value.toLowerCase().trim();
        Array.from(clienteSelect.options).forEach(option => {
            if (!option.value) return; // skip placeholder if any
            const matches = option.textContent.toLowerCase().includes(query);
            option.hidden = !matches;
            option.disabled = !matches;
        });

        // If the selected option is now hidden, pick the first visible
        const sel = clienteSelect.selectedOptions[0];
        if (sel && sel.hidden) {
            const first = Array.from(clienteSelect.options).find(o => !o.hidden);
            clienteSelect.value = first ? first.value : '';
        }
    }

    // Bind search inputs
    if (searchSim) searchSim.addEventListener('input', filterSelects);
    if (searchDevice) searchDevice.addEventListener('input', filterSelects);
    if (searchSensor) searchSensor.addEventListener('input', filterSensorSelect);
    if (searchCliente) searchCliente.addEventListener('input', filterClienteSelect);

    // Call on load
    filterSelects();
    filterSensorSelect();

    // -----------------------------------------------------------------
    // Modal Helpers
    // -----------------------------------------------------------------
    // Set type of SIM/Device to match dispositivo_instalado when modal opens
    const modalAddSimEl = document.getElementById('modalAddSim');
    if (modalAddSimEl) {
        modalAddSimEl.addEventListener('show.bs.modal', function () {
            const type = dispInstalado.value;
            const selectType = document.getElementById('new_sim_type');
            if (selectType && type) {
                selectType.value = ['DVR', 'GPS', 'DASHCAM', 'SENSOR', 'Otro'].includes(type) ? type : 'GPS';
            }
        });
    }

    const modalAddDeviceEl = document.getElementById('modalAddDevice');
    if (modalAddDeviceEl) {
        modalAddDeviceEl.addEventListener('show.bs.modal', function () {
            const type = dispInstalado.value;
            const selectType = document.getElementById('new_dev_type');
            if (selectType && type) {
                selectType.value = ['DVR', 'GPS', 'DASHCAM', 'SENSOR', 'Otro'].includes(type) ? type : 'GPS';
            }
        });
    }

    // Auto stock calculations based on IMEI commas
    const devImeiInput = document.getElementById('new_dev_imei');
    if (devImeiInput) {
        devImeiInput.addEventListener('input', function() {
            const count = this.value.split(',').map(x => x.trim()).filter(x => x !== '').length;
            document.getElementById('new_dev_stock').value = count || 1;
        });
    }

    const sensImeiInput = document.getElementById('new_sens_imei');
    if (sensImeiInput) {
        sensImeiInput.addEventListener('input', function() {
            const count = this.value.split(',').map(x => x.trim()).filter(x => x !== '').length;
            document.getElementById('new_sens_stock').value = count || 1;
        });
    }

    // AJAX Save: SIM Control
    const btnSaveSim = document.getElementById('btn_save_sim');
    if (btnSaveSim) {
        btnSaveSim.addEventListener('click', async function() {
            const type = document.getElementById('new_sim_type').value;
            const company = document.getElementById('new_sim_company').value.trim();
            const simNum = document.getElementById('new_sim_number').value.trim();
            const pubNum = document.getElementById('new_sim_public').value.trim();
            const obs = document.getElementById('new_sim_obs').value.trim();

            if (!company || !simNum || !pubNum) {
                Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor complete todos los campos obligatorios.' });
                return;
            }
            if (simNum.length < 10) {
                Swal.fire({ icon: 'warning', title: 'Número SIM inválido', text: 'El número SIM debe tener al menos 10 caracteres.' });
                return;
            }

            btnSaveSim.disabled = true;
            btnSaveSim.textContent = 'Guardando...';

            try {
                const response = await fetch('{{ route("simcontrol.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        compañia: company,
                        numero_sim: simNum,
                        numero_publico: pubNum,
                        observaciones: obs
                    })
                });

                if (response.ok) {
                    const res = await response.json();
                    if (res.ok && res.data) {
                        // Append to select
                        const opt = document.createElement('option');
                        opt.value = res.data.id;
                        opt.setAttribute('data-type', res.data.type);
                        opt.textContent = `${res.data.compañia} - ${res.data.numero_publico}`;
                        simSelect.appendChild(opt);
                        simSelect.value = res.data.id;
                        
                        // Refresh visibility
                        filterSelects();

                        // Close Modal
                        $(modalAddSimEl).modal('hide');

                        // Clean inputs
                        document.getElementById('new_sim_company').value = '';
                        document.getElementById('new_sim_number').value = '';
                        document.getElementById('new_sim_public').value = '';
                        document.getElementById('new_sim_obs').value = '';

                        Swal.fire({ icon: 'success', title: 'Guardado', text: 'Tarjeta SIM agregada con éxito.', timer: 2000, showConfirmButton: false });
                    }
                } else if (response.status === 422) {
                    const err = await response.json();
                    let msg = '';
                    for (const k in err.errors) {
                        msg += err.errors[k].join('\n') + '\n';
                    }
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: msg });
                } else {
                    const err = await response.json();
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Error al guardar la SIM.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión con el servidor.' });
            } finally {
                btnSaveSim.disabled = false;
                btnSaveSim.textContent = 'Guardar SIM';
            }
        });
    }

    // AJAX Save: Device
    const btnSaveDevice = document.getElementById('btn_save_device');
    if (btnSaveDevice) {
        btnSaveDevice.addEventListener('click', async function() {
            const type = document.getElementById('new_dev_type').value;
            const name = document.getElementById('new_dev_name').value.trim();
            const brand = document.getElementById('new_dev_brand').value.trim();
            const gen = document.getElementById('new_dev_gen').value.trim();
            const imeisVal = document.getElementById('new_dev_imei').value.trim();
            const warranty = document.getElementById('new_dev_warranty').value;
            const ia = document.getElementById('new_dev_ia').value;
            const stockAlert = parseInt(document.getElementById('new_dev_stock_alert').value) || 1;
            const stock = parseInt(document.getElementById('new_dev_stock').value) || 1;
            const otherCompany = document.getElementById('new_dev_other_company').value;
            const obs = document.getElementById('new_dev_obs').value.trim();

            if (!name || !brand || !gen || !imeisVal) {
                Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor complete todos los campos obligatorios.' });
                return;
            }

            const imeis = imeisVal.split(',').map(x => x.trim()).filter(x => x !== '');
            if (imeis.length === 0) {
                Swal.fire({ icon: 'warning', title: 'IMEI requerido', text: 'Debe ingresar al menos un número IMEI.' });
                return;
            }

            btnSaveDevice.disabled = true;
            btnSaveDevice.textContent = 'Guardando...';

            try {
                const response = await fetch('{{ route("devices.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        dispositivo: name,
                        marca: brand,
                        generacion: gen,
                        imei: imeis,
                        garantia: warranty,
                        ia: ia,
                        stock_min_alert: stockAlert,
                        stock: stock,
                        otra_empresa: otherCompany,
                        observations: obs
                    })
                });

                if (response.ok) {
                    const res = await response.json();
                    if (res.ok && res.data) {
                        // Append to select
                        const opt = document.createElement('option');
                        opt.value = res.data.id;
                        opt.setAttribute('data-type', res.data.type);
                        opt.textContent = `${res.data.dispositivo} - ${res.data.marca}`;
                        deviceSelect.appendChild(opt);
                        deviceSelect.value = res.data.id;
                        
                        // Refresh visibility
                        filterSelects();

                        // Close Modal
                        $(modalAddDeviceEl).modal('hide');

                        // Clean inputs
                        document.getElementById('new_dev_name').value = '';
                        document.getElementById('new_dev_brand').value = '';
                        document.getElementById('new_dev_gen').value = 'G1';
                        document.getElementById('new_dev_imei').value = '';
                        document.getElementById('new_dev_stock').value = '1';
                        document.getElementById('new_dev_stock_alert').value = '1';
                        document.getElementById('new_dev_obs').value = '';

                        Swal.fire({ icon: 'success', title: 'Guardado', text: 'Dispositivo agregado con éxito.', timer: 2000, showConfirmButton: false });
                    }
                } else if (response.status === 422) {
                    const err = await response.json();
                    let msg = '';
                    for (const k in err.errors) {
                        msg += err.errors[k].join('\n') + '\n';
                    }
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: msg });
                } else {
                    const err = await response.json();
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Error al guardar el dispositivo.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión con el servidor.' });
            } finally {
                btnSaveDevice.disabled = false;
                btnSaveDevice.textContent = 'Guardar Dispositivo';
            }
        });
    }

    // AJAX Save: Sensor
    const modalAddSensorEl = document.getElementById('modalAddSensor');
    const btnSaveSensor = document.getElementById('btn_save_sensor');
    if (btnSaveSensor) {
        btnSaveSensor.addEventListener('click', async function() {
            const type = document.getElementById('new_sens_type').value;
            const name = document.getElementById('new_sens_name').value.trim();
            const brand = document.getElementById('new_sens_brand').value.trim();
            const gen = document.getElementById('new_sens_gen').value.trim();
            const imeisVal = document.getElementById('new_sens_imei').value.trim();
            const warranty = document.getElementById('new_sens_warranty').value;
            const ia = document.getElementById('new_sens_ia').value;
            const stockAlert = parseInt(document.getElementById('new_sens_stock_alert').value) || 1;
            const stock = parseInt(document.getElementById('new_sens_stock').value) || 1;
            const otherCompany = document.getElementById('new_sens_other_company').value;
            const obs = document.getElementById('new_sens_obs').value.trim();

            if (!name || !brand || !gen || !imeisVal) {
                Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor complete todos los campos obligatorios.' });
                return;
            }

            const imeis = imeisVal.split(',').map(x => x.trim()).filter(x => x !== '');
            if (imeis.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Identificador/IMEI requerido', text: 'Debe ingresar al menos un identificador o IMEI.' });
                return;
            }

            btnSaveSensor.disabled = true;
            btnSaveSensor.textContent = 'Guardando...';

            try {
                const response = await fetch('{{ route("devices.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        dispositivo: name,
                        marca: brand,
                        generacion: gen,
                        imei: imeis,
                        garantia: warranty,
                        ia: ia,
                        stock_min_alert: stockAlert,
                        stock: stock,
                        otra_empresa: otherCompany,
                        observations: obs
                    })
                });

                if (response.ok) {
                    const res = await response.json();
                    if (res.ok && res.data) {
                        // Append to select
                        const opt = document.createElement('option');
                        opt.value = res.data.id;
                        opt.setAttribute('data-type', res.data.type);
                        opt.textContent = `${res.data.dispositivo}`;
                        sensorSelect.appendChild(opt);
                        sensorSelect.value = res.data.id;
                        
                        // Refresh visibility
                        filterSensorSelect();

                        // Close Modal
                        $(modalAddSensorEl).modal('hide');

                        // Clean inputs
                        document.getElementById('new_sens_name').value = '';
                        document.getElementById('new_sens_brand').value = '';
                        document.getElementById('new_sens_gen').value = 'G1';
                        document.getElementById('new_sens_imei').value = '';
                        document.getElementById('new_sens_stock').value = '1';
                        document.getElementById('new_sens_stock_alert').value = '1';
                        document.getElementById('new_sens_obs').value = '';

                        Swal.fire({ icon: 'success', title: 'Guardado', text: 'Sensor/Accesorio agregado con éxito.', timer: 2000, showConfirmButton: false });
                    }
                } else if (response.status === 422) {
                    const err = await response.json();
                    let msg = '';
                    for (const k in err.errors) {
                        msg += err.errors[k].join('\n') + '\n';
                    }
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: msg });
                } else {
                    const err = await response.json();
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Error al guardar el sensor.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión con el servidor.' });
            } finally {
                btnSaveSensor.disabled = false;
                btnSaveSensor.textContent = 'Guardar Sensor';
            }
        });
    }

    // AJAX Save: Cliente
    const modalAddClienteEl = document.getElementById('modalAddCliente');
    const btnSaveCliente = document.getElementById('btn_save_cliente');
    if (btnSaveCliente) {
        btnSaveCliente.addEventListener('click', async function() {
            const nombre    = document.getElementById('new_cli_nombre').value.trim();
            const email     = document.getElementById('new_cli_email').value.trim();
            const telefono  = document.getElementById('new_cli_telefono').value.trim();
            const alterno   = document.getElementById('new_cli_alterno').value.trim();
            const direccion = document.getElementById('new_cli_direccion').value.trim();
            const empresa   = document.getElementById('new_cli_empresa').value.trim();
            const tipoEmp   = document.getElementById('new_cli_tipo_empresa').value;
            const dirEmp    = document.getElementById('new_cli_dir_empresa').value.trim();

            if (!nombre || !email || !telefono || !direccion || !empresa || !dirEmp) {
                Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor complete todos los campos obligatorios (*).' });
                return;
            }

            btnSaveCliente.disabled = true;
            btnSaveCliente.textContent = 'Guardando...';

            try {
                const response = await fetch('{{ route("clientes.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        nombre:            nombre,
                        email:             email,
                        numero_contacto:   telefono,
                        numero_alterno:    alterno,
                        direccion:         direccion,
                        empresa:           empresa,
                        tipo_empresa:      tipoEmp,
                        direccion_empresa: dirEmp
                    })
                });

                if (response.ok) {
                    const res = await response.json();
                    if (res.ok && res.data) {
                        // Append to select and auto-select
                        const opt = document.createElement('option');
                        opt.value = res.data.id;
                        opt.textContent = res.data.nombre;
                        clienteSelect.appendChild(opt);
                        clienteSelect.value = res.data.id;

                        // Close modal
                        $(modalAddClienteEl).modal('hide');

                        // Clear fields
                        ['new_cli_nombre','new_cli_email','new_cli_telefono','new_cli_alterno',
                         'new_cli_direccion','new_cli_empresa','new_cli_dir_empresa'].forEach(id => {
                            document.getElementById(id).value = '';
                        });
                        document.getElementById('new_cli_tipo_empresa').selectedIndex = 0;

                        Swal.fire({ icon: 'success', title: 'Guardado', text: 'Cliente agregado con éxito.', timer: 2000, showConfirmButton: false });
                    }
                } else if (response.status === 422) {
                    const err = await response.json();
                    let msg = '';
                    for (const k in err.errors) msg += err.errors[k].join('\n') + '\n';
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: msg });
                } else {
                    const err = await response.json();
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Error al guardar el cliente.' });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión con el servidor.' });
            } finally {
                btnSaveCliente.disabled = false;
                btnSaveCliente.textContent = 'Guardar Cliente';
            }
        });
    }
});
</script>