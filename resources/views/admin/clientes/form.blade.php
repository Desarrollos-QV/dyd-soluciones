<div class="col-lg-7">
    <div class="card">
        <div class="card-header">
            <h4>@yield('title')</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{$cliente->nombre}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{$cliente->direccion}}" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Teléfono</label>
                    <input type="text" name="numero_contacto" value="{{$cliente->numero_contacto}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Número de Emergencias o cliente</label>
                    <input type="text" name="numero_alterno" value="{{$cliente->numero_alterno}}" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nombre de la empresa</label>
                    <input type="text" name="empresa" value="{{$cliente->empresa}}" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label for="tipo_empresa">Tipo de empresa</label>
                    <select name="tipo_empresa" id="tipo_empresa" class="form-select">
                        <option value="Ruta" @if($cliente->tipo_empresa == 'Ruta') selected @endif>Ruta</option>
                        <option value="Particular" @if($cliente->tipo_empresa == 'Particular') selected @endif>Particular</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="direccion_empresa">Dirección de la empresa</label>
                    <input type="text" name="direccion_empresa" id="direccion_empresa" class="form-control" value="{{$cliente->direccion_empresa}}">
                </div> 
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Documentación del cliente</h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Comprobante domicilio &nbsp;
                        @if($cliente->comprobante_domicilio != null)
                            <a href="{{ url($cliente->comprobante_domicilio) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="comprobante_domicilio" value="{{$cliente->comprobante_domicilio}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Factura &nbsp;
                        @if($cliente->copa_factura != null)
                            <a href="{{ url($cliente->copa_factura) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="copa_factura" value="{{$cliente->copa_factura}}" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Tarjeta Circulacion &nbsp;
                        @if($cliente->tarjeta_circulacion != null)
                            <a href="{{ url($cliente->tarjeta_circulacion) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="tarjeta_circulacion" value="{{$cliente->tarjeta_circulacion}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Consesión &nbsp;
                        @if($cliente->copia_consesion != null)
                            <a href="{{ url($cliente->copia_consesion) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="copia_consesion" value="{{$cliente->copia_consesion}}" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Contrato &nbsp;
                        @if($cliente->contrato != null)
                            <a href="{{ url($cliente->contrato) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="contrato" value="{{$cliente->contrato}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label style="display: flex;align-items: center;">
                        Anexo &nbsp;
                        @if($cliente->anexo != null)
                            <a href="{{ url($cliente->anexo) }}" target="_blank">
                                <i class="link-icon" data-feather="check-circle" style="color:green;width:16px;"></i>
                                Ver Imagen
                            </a>
                        @endif
                    </label>
                    <input type="file" name="anexo" value="{{$cliente->anexo}}" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-5 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 mt-4">
                <div class="col-md-12 mt-4">
                    @if(isset($cliente->id))
                        <label id="avatar">Imagen de perfil</label>
                        <div class="row">
                            <div class="col-lg-8">
                                <input type="file" name="avatar" id="myDropify" class="form-control" value="{{$cliente->avatar}}">
                            </div>
                            <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                <img src="{{ asset($cliente->avatar) }}" alt="avatar" style="width:100px;height: 100px;border-radius: 25px;">
                            </div>
                        </div>
                    @else
                        <label id="avatar">Imagen de perfil</label>
                        <input type="file" name="avatar" id="myDropify" class="form-control" value="{{$cliente->avatar}}">
                    @endif
                </div>
                <div class="col-md-12 mt-4">
                    @if(isset($cliente->id))
                        <label for="identificacion">Sube una Identificación Oficial</label>
                        <div class="row">
                            <div class="col-lg-8">
                                <input type="file" name="identificacion" id="myDropify2" class="form-control" value="{{$cliente->identificacion}}">
                            </div>
                            <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                <img src="{{ asset($cliente->identificacion) }}" alt="identificacion" style="width:100px;height: 100px;border-radius: 25px;">
                            </div>
                        </div>
                    @else
                        <label for="identificacion">Sube una Identificación Oficial</label>
                        <input type="file" name="identificacion" id="myDropify2" class="form-control" value="{{$cliente->identificacion}}">
                    @endif
                </div>
            </div>

            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($cliente->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
