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
                        <input type="text" name="economico" id="economico" class="form-control"
                            value="{{ $unidad->economico }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="placa">Placa</label>
                        <input type="text" name="placa" id="placa" class="form-control"
                            value="{{ $unidad->placa }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="tipo_unidad">Tipo de unidad</label>
                        <input type="text" name="tipo_unidad" id="tipo_unidad" class="form-control"
                            value="{{ $unidad->tipo_unidad }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="fecha_instalacion">Fecha Instalacion</label>
                        <input type="date" name="fecha_instalacion" id="fecha_instalacion" class="form-control"
                            value="{{ old('fecha_instalacion', isset($unidad->fecha_instalacion) ? \Carbon\Carbon::parse($unidad->fecha_instalacion)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="np_sim">Dispositivo instalado</label>
                        <select name="dispositivo_instalado" id="dispositivo_instalado" class="form-select" required>
                            <option value="DVR" @if($unidad->dispositivo_instalado == 'DVR') selected @endif>DVR</option>
                            <option value="GPS" @if($unidad->dispositivo_instalado == 'GPS') selected @endif>GPS</option>
                            <option value="DASHCAM" @if($unidad->dispositivo_instalado == 'DASHCAM') selected @endif>DASHCAM</option>
                            <option value="SENSOR" @if($unidad->dispositivo_instalado == 'SENSOR') selected @endif>SENSOR</option>
                            <option value="Otro" @if($unidad->dispositivo_instalado == 'Otro') selected @endif>Otro</option>
                        </select>
                        <div class="row" id="other_dispositivo_instalado" style="display: none;">
                            <div class="col-md-12 mt-2" id="inner_other_disp">
                                 <input type="text" id="input_dispositivo_instalado" class="form-control"
                                    value="{{ $unidad->dispositivo_instalado }}" placeholder="Ingrese el nombre de la plataforma">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label for="numero_de_motor">Número de motor</label>
                        <input type="text" name="numero_de_motor" id="numero_de_motor" class="form-control"
                            value="{{ $unidad->numero_de_motor }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-3">
                        <label for="anio_unidad">Año de la unidad</label>
                        <input type="number" name="anio_unidad" id="anio_unidad" class="form-control"
                        min="1900" max="{{ date('Y') }}"
                        value="{{ $unidad->anio_unidad ?? old('anio_unidad') }}" required>
                    </div>
                    <div class="col-lg-3">
                        <label for="marca">Marca</label>
                        <input type="text" name="marca" id="marca" class="form-control"
                            value="{{ $unidad->marca }}">
                    </div>
                    <div class="col-lg-3">
                        <label for="submarca">Submarca</label>
                        <input type="text" name="submarca" id="submarca" class="form-control"
                            value="{{ $unidad->submarca }}">
                    </div>
                    <div class="col-lg-3">
                        <label for="vin">Número VIN</label>
                        <input type="text" name="vin" id="vin" class="form-control"
                            value="{{ $unidad->vin }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6">
                        <label for="imei">IMEI</label>
                        <input type="text" name="imei" id="imei" class="form-control"
                            value="{{ $unidad->imei }}">
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
                <div class="row">
                    <div class="col-lg-12">
                        <label for="cliente_id">Asignado a cliente:</label>
                        <select name="cliente_id" id="cliente_id" class="form-select mb-4">
                            @foreach($clientes as $client)
                            <option value="{{ $client->id }}"> {{$client->nombre}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <label for="numero_de_emergencia">Número de emergencia</label>
                        <input type="tel" name="numero_de_emergencia" id="numero_de_emergencia"
                            class="form-control"
                            value="{{ $unidad->numero_de_emergencia ?? old('numero_de_emergencia') }}">
                    </div>

                    <div class="col-lg-12 mt-4">
                        <label for="foto_unidad">Fotografia de la unidad</label>
                        @if(isset($unidad->id))
                            <label for="identificacion">Sube una Identificación Oficial</label>
                            <div class="row">
                                <div class="col-lg-8">
                                    <input type="file" name="foto_unidad" id="myDropify" class="form-control" value="{{$unidad->foto_unidad}}">
                                </div>
                                <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                    <img src="{{ asset($unidad->foto_unidad) }}" alt="foto_unidad" style="width:100px;height: 100px;border-radius: 25px;">
                                </div>
                            </div>
                        @else
                            <label for="foto_unidad">Sube una Identificación Oficial</label>
                            <input type="file" name="foto_unidad" id="myDropify" class="form-control" value="{{$unidad->foto_unidad}}">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <button type="submit"
                            class="btn btn-primary mr-4">{{ isset($unidad->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('unidades.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>