<div class="row">
    <div class="col-lg-7 order-md-1 order-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Tipo de servicio</label>
                        <input type="text" name="tipo_servicio" class="form-control" disabled
                            value="{{ $servicio->tipo_servicio }}">
                    </div>
                    <div class="col-md-6">
                        <label for="encargado_recibir">Encargado de recibir</label>
                        <input type="text" name="encargado_recibir" class="form-control" disabled
                            value="{{ ucwords($servicio->cliente->nombre) }}">
                    </div>
                </div>

            </div>
        </div>



        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Información del Cliente</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" disabled
                                    value="{{ $servicio->cliente->nombre }}">
                            </div>
                            <div class="col-md-6">
                                <label for="direccion">Dirección</label>
                                <input type="text" name="direccion" class="form-control" id="direccion" disabled
                                    value="{{ $servicio->cliente->direccion }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="numero_contacto">Número de contacto</label>
                                <input type="text" name="numero_contacto" class="form-control" id="numero_contacto"
                                    disabled value="{{ $servicio->cliente->numero_contacto }}">
                            </div>
                            <div class="col-md-6">
                                <label for="numero_alterno">Número Alterno</label>
                                <input type="text" name="numero_alterno" class="form-control" id="numero_alterno"
                                    disabled value="{{ $servicio->cliente->numero_alterno }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Información de la SIM</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="compañia">Compañia</label>
                                <input type="text" name="compañia" id="compañia" class="form-control" disabled
                                    value="{{ $servicio->unidad->simcontrol->compañia }}">
                            </div>
                            <div class="col-md-12">
                                <label for="numero_sim">Número de SIM</label>
                                <input type="text" name="numero_sim" class="form-control" id="numero_sim" disabled
                                    value="{{ $servicio->unidad->simcontrol->numero_sim }}">
                            </div>
                            <div class="col-md-12">
                                <label for="numero_publico">Número público</label>
                                <input type="text" name="numero_publico" class="form-control" id="numero_publico"
                                    disabled value="{{ $servicio->unidad->simcontrol->numero_publico }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="observaciones">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control" disabled
                                    cols="30"
                                    rows="10">{!! $unidad->simcontrol->observaciones ?? 'Sin Observaciones' !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 order-md-2 order-1">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="location">Ubicación del servicio</label>
                        <input class="controls form-control" name="location" value="{{ $servicio->location }}"
                            type="text" disabled>
                        <div class="row">
                            <div class="form-group col-md-6"><input type="hidden" name="lat" id="lat"
                                    class="form-control" placeholder="Latitude" value="{{ $servicio->coords['lat'] }}">
                            </div>
                            <div class="form-group col-md-6"><input type="hidden" name="lng" id="lng"
                                    class="form-control" placeholder="Longitude" value="{{ $servicio->coords['lng'] }}">
                            </div>
                        </div>
                        <div id="map" style="width: 100%;height: 300px;border-radius:25px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                @if ($servicio->firma != null)
                    <a class="dropdown-item" target="_blank"
                        href="{{ route('servicios_agendados.generarPDF', ['id' => base64_encode($servicio->id)]) }}">Descargar
                        Reporte</a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="ShareLink()">Compartir Link</a>

                    <img src="{{ asset($servicio->firma->firma) }}" style="width: 600px; height: auto;margin: 25px;">
                @else
                    <a class="btn btn-primary " target="_blank"
                        href="{{ route('servicios_agendados.firmar', ['id' => base64_encode($servicio->id)]) }}">Solicitar
                        Firma</a>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="col-lg-5 grid-margin stretch-card">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Informacion del Inventario</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type">Tipo</label>
                            <input type="text" name="type" id="type" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->type }}">
                        </div>
                        <div class="col-md-6">
                            <label for="dispositivo">Dispositivo</label>
                            <input type="text" name="dispositivo" id="dispositivo" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->dispositivo }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" id="marca" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->marca }}">
                        </div>
                        <div class="col-md-6">
                            <label for="camaras">Camaras</label>
                            <input type="text" name="camaras" id="camaras" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->camaras }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="generacion">Generación</label>
                            <input type="text" name="generacion" id="generacion" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->generacion }}">
                        </div>
                        <div class="col-md-6">
                            <label for="imei">IMEI</label>
                            <input type="text" name="imei" id="imei" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->imei }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="garantia">Garantía</label>
                            <input type="text" name="garantia" id="garantia" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->garantia }}">
                        </div>
                        <div class="col-md-6">
                            <label for="accesorios">Accesorios</label>
                            <input type="text" name="accesorios" id="accesorios" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->accesorios }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="ia">IA</label>
                            <input type="text" name="ia" id="ia" class="form-control" disabled
                                value="{{ $servicio->unidad->inventario->ia }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 grid-margin stretch-card">
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Información de la Unidad</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="dispositivo_instalado">Dispositivo a instalar</label>
                            <input type="text" name="dispositivo_instalado" id="dispositivo_instalado"
                                class="form-control" disabled value="{{ $servicio->unidad['dispositivo_instalado'] }}">
                        </div>
                        <div class="col-md-3">
                            <label for="economico">Economico</label>
                            <input type="text" name="economico" class="form-control" id="economico" disabled
                                value="{{ $servicio->unidad['economico'] }}">
                        </div>
                        <div class="col-md-3">
                            <label for="placa">Placa</label>
                            <input type="text" name="placa" class="form-control" id="placa" disabled
                                value="{{ $servicio->unidad['placa'] }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tipo_unidad">Tipo de unidad</label>
                            <input type="text" name="tipo_unidad" id="tipo_unidad" class="form-control" disabled
                                value="{{ $servicio->unidad['tipo_unidad'] }}">
                        </div>
                        <div class="col-md-6">
                            <label for="anio_unidad">Año de la unidad</label>
                            <input type="text" name="anio_unidad" class="form-control" id="anio_unidad" disabled
                                value="{{ $servicio->unidad['anio_unidad'] }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" id="marca" class="form-control" disabled
                                value="{{ $servicio->unidad['marca'] }}">
                        </div>
                        <div class="col-md-6">
                            <label for="submarca">SubMarca</label>
                            <input type="text" name="submarca" class="form-control" id="submarca" disabled
                                value="{{ $servicio->unidad['submarca'] }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="numero_de_motor">Número de motor</label>
                            <input type="text" name="numero_de_motor" id="numero_de_motor" class="form-control" disabled
                                value="{{ $servicio->unidad['numero_de_motor'] }}">
                        </div>
                        <div class="col-md-6">
                            <label for="vin">VIN</label>
                            <input type="text" name="vin" class="form-control" id="vin" disabled
                                value="{{ $servicio->unidad['vin'] }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="imei">IMEI</label>
                            <input type="text" name="imei" id="imei" class="form-control" disabled
                                value="{{ $servicio->unidad['imei'] }}">
                        </div>
                        <div class="col-md-6">
                            <label for="cuenta_con_apagado">Cuenta con apagado</label>
                            <input type="text" name="cuenta_con_apagado" class="form-control" id="cuenta_con_apagado"
                                disabled value="{{ $servicio->unidad['cuenta_con_apagado'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>