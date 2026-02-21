

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
                            <label for="type">Tipo</label>
                            <select name="type" id="type" class="form-control mb-4 mb-md-0" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="DVR" {{ $simcontrol->type == 'DVR' ? 'selected' : '' }}>DVR</option>
                                <option value="GPS" {{ $simcontrol->type == 'GPS' ? 'selected' : '' }}>GPS</option>
                                <option value="DASHCAM" {{ $simcontrol->type == 'DASHCAM' ? 'selected' : '' }}>DASHCAM</option>
                                <option value="SENSOR" {{ $simcontrol->type == 'SENSOR' ? 'selected' : '' }}>SENSOR Y/O ACCESORIOS</option>
                                <option value="Otro" {{ $simcontrol->type == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="compañia">compañia</label>
                            <input type="text" name="compañia" class="form-control mb-4 mb-md-0"
                            value="{{ $simcontrol->compañia ?? old('compañia') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="numero_sim">Número SIM</label>
                            <input type="text" name="numero_sim" class="form-control mb-4 mb-md-0"
                            min="10" max="30"
                            value="{{ $simcontrol->numero_sim ?? old('numero_sim') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="numero_publico">Número Publico</label>
                             <input type="text" name="numero_publico" class="form-control mb-4 mb-md-0"
                            value="{{ $simcontrol->numero_publico ?? old('numero_publico') }}" required/>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control mb-4" cols="30" rows="10" placeholder="observaciones">{!! $simcontrol->observaciones !!}</textarea>
                        </div>
                    </div> 
                </div>
 
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                        class="btn btn-primary">{{ isset($simcontrol->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('simcontrol.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 