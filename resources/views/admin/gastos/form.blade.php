

<div class="col-lg-4 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @yield('title')
            </h4>
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="number">Monto</label>
                            <input  name="monto" class="form-control mb-4 mb-md-0" step="0.01" data-inputmask="'alias': 'currency'"
                            value="{{ $gasto->monto ?? old('monto') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="descripcion">Descripción de lo adquirido</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" maxlength="50" rows="5" placeholder="Describe lo que se adquiere">{!! $gasto->descripcion ?? old('descripcion') !!}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="motivo">Motivo</label>
                            <textarea name="motivo" id="motivo" class="form-control" maxlength="50" rows="5" placeholder="Describe lo que se adquiere">{!! $gasto->motivo ?? old('motivo') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($gasto->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('gastos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-8">
    <div class="card">
       
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="date">Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ $gasto->fecha ?? old('fecha') }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="time">Hora</label>
                            <input type="time" name="hora" class="form-control" value="{{ $gasto->hora ?? old('hora') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="autorizado_por">Quien lo autoriza</label>
                            <select name="autorizado_por" id="autorizado_por" class="form-select" required>
                                <option value="">Seleccion un elemento</option>
                                @foreach($tecnicos as $tecns)
                                <option value="{{$tecns->id}}" @if(isset($gasto) && $tecns->id == $gasto->autorizado_por) selected @endif>{{ $tecns->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="solicitado_por">Quien lo solicita</label>
                            <select name="solicitado_por" id="solicitado_por" class="form-select" required>
                                <option value="">Seleccion un elemento</option>
                                @foreach($tecnicos as $tecns)
                                <option value="{{$tecns->id}}" @if(isset($gasto) && $tecns->id == $gasto->solicitado_por) selected @endif>{{ $tecns->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>