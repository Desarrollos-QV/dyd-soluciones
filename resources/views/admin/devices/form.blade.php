

<div class="col-lg-10 mx-auto grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @yield('title')
            </h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="dispositivo">Dispositivo</label>
                            <input type="text" name="dispositivo" class="form-control mb-4 mb-md-0"
                            value="{{ $device->dispositivo ?? old('dispositivo') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" class="form-control mb-4 mb-md-0"
                            value="{{ $device->marca ?? old('marca') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="camaras">Camaras</label>
                             <input type="text" name="camaras" class="form-control mb-4 mb-md-0"
                            value="{{ $device->camaras ?? old('camaras') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="generacion">Generacion</label>
                            <input type="text" name="generacion" class="form-control mb-4 mb-md-0"
                            value="{{ $device->generacion ?? old('generacion') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="imei">IMEI Asignado</label>
                             <input type="text" name="imei" class="form-control mb-4 mb-md-0"
                            value="{{ $device->imei ?? old('imei') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="garantia">Garantia</label> 
                            <input type="date" name="garantia" class="form-control"
                                value="{{ old('garantia', isset($device->garantia) ? \Carbon\Carbon::parse($device->garantia)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="accesorios">Accesorios</label>
                             <input type="text" name="accesorios" class="form-control mb-4 mb-md-0"
                            value="{{ $device->accesorios ?? old('accesorios') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="ia">IA</label>
                            <select name="ia" id="ia" class="form-select mb-4">
                                <option value="si" @if($device->ia == 'si') selected @endif>SI</option>
                                <option value="no" @if($device->ia == 'no') selected @endif>NO</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                   <!--
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="cliente_id">Se asigna a cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-select mb-4">
                                @foreach($clientes as $client)
                                <option value="{{ $client->id }}" @if($device->cliente_id == $client->id) selected @endif>{{ $client->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="unidad_id">Se asigna a unidad</label>
                            <select name="unidad_id" id="unidad_id" class="form-select mb-4">
                                @foreach($unidades as $unit)
                                <option value="{{ $unit->id }}" @if($device->unidad_id == $unit->id) selected @endif>{{ $unit->tipo_unidad }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>-->
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="stock">Stock del dispositivo</label>
                            <input type="number" name="stock" class="form-control mb-4 mb-md-0"
                            value="{{ $device->stock ?? old('stock') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="stock_min_alert">Stock Minima para alerta</label>
                            <input type="number" name="stock_min_alert" class="form-control mb-4 mb-md-0"
                            value="{{ $device->stock_min_alert ?? old('stock_min_alert') }}" required/>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="otra_empresa">¿Pertenencio a otra empresa?</label>
                            <select name="otra_empresa" id="otra_empresa" class="form-select mb-4">
                                <option value="si" @if($device->otra_empresa == 'si') selected @endif>SI</option>
                                <option value="no" @if($device->otra_empresa == 'no') selected @endif>NO</option>
                            </select>
                        </div>
                    </div>
                </div>
 
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($device->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 