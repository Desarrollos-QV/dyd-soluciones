<div class="card">
    <div class="card-header">
        <h4>Nueva Asignación</h4>
    </div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="cliente_id">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-control" required>
                    @foreach($clientes as $cl)
                    <option value="{{$cl->id}}"> {{$cl->nombre}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <form action="tecnico_id">Tecnico asignado</form>
                <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                    <option value="0">Sin asignar aún</option>
                    @foreach($tecnicos as $tec)
                    <option value="{{$tec->id}}"> {{$tec->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="unidad_id">Unidad a asignar</label>
                <select name="unidad_id" id="unidad_id" class="form-control" required>
                    @foreach($unidades as $unit)
                    <option value="{{$unit->id}}"> {{$unit->tipo_unidad}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6">
                <label for="coban_dvr">Coban o DVR</label>
                <input type="text" id="coban_dvr" name="coban_dvr" class="form-control" required
                    value="{{ $assignement->coban_dvr }}">
            </div>
        </div>

        <div class="row mb-3">
             <div class="col-lg-6">
                <label for="costo_plataforma">Costo de la plataforma</label>
                <input type="number" id="costo_plataforma" name="costo_plataforma" class="form-control" required
                    value="{{ $assignement->costo_plataforma }}">
            </div>
            <div class="col-lg-6">
                <label for="costo_sim">Costo del SIM</label>
                <input type="number" id="costo_sim" name="costo_sim" class="form-control" required
                    value="{{ $assignement->costo_sim }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="pago_mensual">Pago Mensual</label>
                <input type="number" step="0.01" id="pago_mensual" name="pago_mensual" class="form-control" required
                    value="{{ $assignement->pago_mensual }}">
            </div>
            <div class="col-md-4">
                <label for="fecha_inicio">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required
                    value="{{ $assignement->fecha_inicio }}">
            </div>
            <div class="col-md-4">
                <label for="fecha_ultimo_mantenimiento">Fecha de Vencimiento</label>
                <input type="date" id="fecha_ultimo_mantenimiento" name="fecha_ultimo_mantenimiento"
                    class="form-control" required value="{{ $assignement->fecha_ultimo_mantenimiento }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="descuento">Descuento Promocional</label>
                <input type="number" step="0.01" id="descuento" name="descuento" class="form-control"
                    value="{{ $assignement->descuento }}">
            </div>
            <div class="col-md-4">
                <label for="cobro_adicional">Cobro adicional</label>
                <input type="number" step="0.01" id="cobro_adicional" name="cobro_adicional" class="form-control"
                    value="{{ $assignement->cobro_adicional }}">
            </div>
            <div class="col-md-4">
                <label for="ganancia">Ganancias</label>
                <input type="number" step="0.01" id="ganancia" name="ganancia" class="form-control"
                    value="{{ $assignement->ganancia }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="observaciones">Ganancia del Servicio</label>
                <textarea name="observaciones" id="observaciones" class="form-control" placeholder="Observaciones Generales">{!! $assignement->observaciones !!}</textarea>
            </div>
            <div class="col-md-6">
                <label for="observaciones_mantenimiento">Observaciones mantenimiento</label>
                <textarea name="observaciones_mantenimiento" id="observaciones_mantenimiento" class="form-control"
                    placeholder="observaciones mantenimiento">{!! $assignement->observaciones_mantenimiento !!}</textarea>
            </div>
        </div>


        <div class="row">
            <button type="submit" class="btn btn-primary ml-3">
                @if (isset($assignement))
                    Crear
                @else
                    Actualizar
                @endif
            </button>
            <a href="{{ route('assignements.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
        </div>
    </div>
</div>
