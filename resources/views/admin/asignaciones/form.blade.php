<div class="col-lg-7">
    <div class="card">
        <div class="card-header">
            <h4>Nueva Alta de servicio</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control" required>
                        @foreach ($clientes as $cl)
                            <option value="{{ $cl->id }}"> {{ $cl->nombre }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tecnico_id">Tecnico asignado</label>
                    <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                        <option value="0">Sin asignar aún</option>
                        @foreach ($tecnicos as $tec)
                            <option value="{{ $tec->id }}"> {{ $tec->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-6">
                    <label for="tipo_servicio">Tipo de servicio</label>
                    <input type="text" id="tipo_servicio" name="tipo_servicio" class="form-control" required
                        value="{{ $assignement->tipo_servicio }}">
                </div>
                <div class="col-lg-6">
                    <label for="tel_contact">Telefono de contacto</label>
                    <input type="text" id="tel_contact" name="tel_contact" class="form-control" required
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
                    <label for="viaticos">Viaticos</label>
                    <input type="text" id="viaticos" name="viaticos" class="form-control" required
                        value="{{ $assignement->viaticos }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-4">
                    <label for="tipo_vehiculo">Tipo de Vehiculo</label>
                    <input type="text" id="tipo_vehiculo" name="tipo_vehiculo" class="form-control" required
                        value="{{ $assignement->tipo_vehiculo }}">
                </div>
                <div class="col-lg-3">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" class="form-control" required
                        value="{{ $assignement->marca }}">
                </div>
                <div class="col-lg-3">
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" class="form-control" required
                        value="{{ $assignement->modelo }}">
                </div>
                <div class="col-lg-2">
                    <label for="placa">Placa</label>
                    <input type="text" id="placa" name="placa" class="form-control" required
                        value="{{ $assignement->placa }}">
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
            </div>

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
                    <input id="pac-input" class="controls form-control" name="location" value="{{$assignement->location}}" type="text" placeholder="Ingresa una Ubicación">
                    
                    <div class="row">
                        <div class="form-group col-md-6"><input type="hidden" name="lat" id="lat" class="form-control" required placeholder="Latitude" value="{{ $assignement->lat }}"></div>
                        <div class="form-group col-md-6"><input type="hidden" name="lng" id="lng" class="form-control" required placeholder="Longitude" value="{{ $assignement->lng }}"></div>
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
