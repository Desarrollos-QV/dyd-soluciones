<div class="row">
    <div class="col-lg-7 ">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Tipo de servicio</label>
                    <input type="text" name="tipo_servicio" class="form-control" disabled value="{{ $servicio->tipo_servicio }}">
                    </div>
                    <div class="col-md-6">
                        <label for="encargado_recibir">Encargado de recibir</label>
                        <input type="text" name="encargado_recibir" class="form-control" disabled value="{{ ucwords($servicio->cliente->nombre) }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="viaticos">Víaticos</label>
                        <input type="text" name="viaticos" class="form-control" disabled value="{{ '$'.$servicio->viaticos }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="tipo_vehiculo">Vehiculo</label>
                        <input type="text" name="tipo_vehiculo" id="tipo_vehiculo" class="form-control" disabled value="{{ $servicio->tipo_vehiculo }}">
                    </div>
                    <div class="col-md-6">
                        <label for="marca">Marca</label>
                        <input type="text" name="marca" class="form-control" id="marca" disabled value="{{ $servicio->marca }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="modelo">Modelo</label>
                        <input type="text" name="modelo" class="form-control" id="modelo" disabled value="{{ $servicio->modelo }}">
                    </div>
                    <div class="col-md-6">
                        <label for="placa">Placas</label>
                        <input type="text" name="placa" class="form-control" id="placa" disabled value="{{ $servicio->placa }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h4>Información del Cliente</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" disabled value="{{ $servicio->cliente->nombre }}">
                    </div>
                    <div class="col-md-6">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control" id="direccion" disabled value="{{ $servicio->cliente->direccion }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="numero_contacto">Número de contacto</label>
                        <input type="text" name="numero_contacto" class="form-control" id="numero_contacto" disabled value="{{ $servicio->cliente->numero_contacto }}">
                    </div>
                    <div class="col-md-6">
                        <label for="numero_alterno">Número Alterno</label>
                        <input type="text" name="numero_alterno" class="form-control" id="numero_alterno" disabled value="{{ $servicio->cliente->numero_alterno }}">
                    </div>
                </div>
            </div>

            <div class="card-body border-top">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Comprobante domicilio &nbsp;
                            @if($servicio->cliente->comprobante_domicilio != null)
                                <a href="{{ url($servicio->cliente->comprobante_domicilio) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Factura &nbsp;
                            @if($servicio->cliente->copa_factura != null)
                                <a href="{{ url($servicio->cliente->copa_factura) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Tarjeta Circulacion &nbsp;
                            @if($servicio->cliente->tarjeta_circulacion != null)
                                <a href="{{ url($servicio->cliente->tarjeta_circulacion) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Consesión &nbsp;
                            @if($servicio->cliente->copia_consesion != null)
                                <a href="{{ url($servicio->cliente->copia_consesion) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Contrato &nbsp;
                            @if($servicio->cliente->contrato != null)
                                <a href="{{ url($servicio->cliente->contrato) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label style="display: flex;align-items: center;">
                            Anexo &nbsp;
                            @if($servicio->cliente->anexo != null)
                                <a href="{{ url($servicio->cliente->anexo) }}" target="_blank">
                                    <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                    Ver Imagen
                                </a>
                            @else
                                <i class="link-icon" data-feather="x-circle" style="color:red;width:16px;"></i>
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="location">Ubicación del servicio</label>
                        <input class="controls form-control" name="location" value="{{$servicio->location}}" type="text" disabled>
                        <div class="row">
                            <div class="form-group col-md-6"><input type="hidden" name="lat" id="lat" class="form-control"  placeholder="Latitude" value="{{ $servicio->coords['lat'] }}"></div>
                            <div class="form-group col-md-6"><input type="hidden" name="lng" id="lng" class="form-control"  placeholder="Longitude" value="{{ $servicio->coords['lng'] }}"></div>
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
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="ShareLink()">Compartir Link</a>
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
    <div class="col-lg-4 grid-margin stretch-card">
        
        <div class="card mt-3">
            <div class="card-header">
                <h4>Información de la Unidad</h4>
            </div>
            <div class="card-body">
                @foreach ($servicio->cliente->unidades as $unidad)
                    <div class="row">
                        <div class="col-md-6">
                            <label for="dispositivo_instalado">Dispositivo a instalar</label>
                            <input type="text" name="dispositivo_instalado" id="dispositivo_instalado" class="form-control" disabled value="{{ $unidad->dispositivo_instalado }}">
                        </div>
                        <div class="col-md-3">
                            <label for="economico">Economico</label>
                            <input type="text" name="economico" class="form-control" id="economico" disabled value="{{ $unidad->economico }}">
                        </div>
                        <div class="col-md-3">
                            <label for="placa">Placa</label>
                            <input type="text" name="placa" class="form-control" id="placa" disabled value="{{ $unidad->placa }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tipo_unidad">Tipo de unidad</label>
                            <input type="text" name="tipo_unidad" id="tipo_unidad" class="form-control" disabled value="{{ $unidad->tipo_unidad }}">
                        </div>
                        <div class="col-md-6">
                            <label for="anio_unidad">Año de la unidad</label>
                            <input type="text" name="anio_unidad" class="form-control" id="anio_unidad" disabled value="{{ $unidad->anio_unidad }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" id="marca" class="form-control" disabled value="{{ $unidad->marca }}">
                        </div>
                        <div class="col-md-6">
                            <label for="submarca">SubMarca</label>
                            <input type="text" name="submarca" class="form-control" id="submarca" disabled value="{{ $unidad->submarca }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="numero_de_motor">Número de motor</label>
                            <input type="text" name="numero_de_motor" id="numero_de_motor" class="form-control" disabled value="{{ $unidad->numero_de_motor }}">
                        </div>
                        <div class="col-md-6">
                            <label for="vin">VIN</label>
                            <input type="text" name="vin" class="form-control" id="vin" disabled value="{{ $unidad->vin }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="imei">IMEI</label>
                            <input type="text" name="imei" id="imei" class="form-control" disabled value="{{ $unidad->imei }}">
                        </div>
                        <div class="col-md-6">
                            <label for="cuenta_con_apagado">Cuenta con apagado</label>
                            <input type="text" name="cuenta_con_apagado" class="form-control" id="cuenta_con_apagado" disabled value="{{ $unidad->cuenta_con_apagado }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Información de la SIM</h4>
            </div>
            <div class="card-body">
                @foreach ($servicio->cliente->unidades as $unidad)
                    <div class="row">
                        <div class="col-md-6">
                            <label for="compañia">Compañia</label>
                            <input type="text" name="compañia" id="compañia" class="form-control" disabled value="{{ $unidad->simcontrol->compañia }}">
                        </div>
                        <div class="col-md-3">
                            <label for="numero_sim">Número de SIM</label>
                            <input type="text" name="numero_sim" class="form-control" id="numero_sim" disabled value="{{ $unidad->simcontrol->numero_sim }}">
                        </div>
                        <div class="col-md-3">
                            <label for="numero_publico">Número público</label>
                            <input type="text" name="numero_publico" class="form-control" id="numero_publico" disabled value="{{ $unidad->simcontrol->numero_publico }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" disabled cols="30" rows="10">{!! $unidad->simcontrol->observaciones ?? "Sin Observaciones" !!}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Información del Inventario</h4>
            </div>
            <div class="card-body">
                @foreach ($servicio->cliente->unidades as $unidad)
                    <div class="row">
                        <div class="col-md-6">
                            <label for="dispositivo">Dispositivo</label>
                            <input type="text" name="dispositivo" id="dispositivo" class="form-control" disabled value="{{ $unidad->inventario->dispositivo }}">
                        </div>
                        <div class="col-md-6">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" class="form-control" id="marca" disabled value="{{ $unidad->inventario->marca }}">
                        </div>
                        
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="camaras">Camaras</label>
                            <input type="text" name="camaras" class="form-control" id="camaras" disabled value="{{ $unidad->inventario->camaras }}">
                        </div>
                        <div class="col-md-6">
                            <label for="generacion">Generacion</label>
                            <input type="text" name="generacion" class="form-control" id="generacion" disabled value="{{ $unidad->inventario->generacion }}">
                        </div>
                    </div>

                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="imei">IMEI</label>
                            <input type="text" name="imei" class="form-control" id="imei" disabled value="{{ $unidad->inventario->imei }}">
                        </div>
                        <div class="col-md-6">
                            <label for="garantia">Garantia</label>
                            <input type="text" name="garantia" class="form-control" id="garantia" disabled value="{{ $unidad->inventario->garantia }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>