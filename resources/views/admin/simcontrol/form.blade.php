

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
                            <label for="compañia">compañia</label>
                            <input type="text" name="compañia" class="form-control mb-4 mb-md-0"
                            value="{{ $simcontrol->compañia ?? old('compañia') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="numero_sim">Número SIM</label>
                            <input type="text" name="numero_sim" class="form-control mb-4 mb-md-0"
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
 