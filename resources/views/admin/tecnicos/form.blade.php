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
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ $tecnico->name }}" required>
                </div>
                <div class="col-lg-6">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $tecnico->telefono }}"
                        required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mt-4">
                <div class="col-lg-12">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ $tecnico->email }}" required>
                </div>
            </div>

            @if(!isset($tecnico))
            <div class="row mt-4">
                <div class="col-lg-6 mb-3">
                    <label>Contraseña</label>
                    <input name="password" type="password" class="form-control" autocomplete="off">
                </div>

                <div class="col-lg-6 mb-3">
                    <label>Confirmar Contraseña</label>
                    <input name="password_confirmation" type="password" class="form-control" autocomplete="off">
                </div>
            </div>
            @else
            <div class="row mt-4">
                <div class="col-lg-6 mb-3">
                    <label>Cambiar Contraseña <small>(Solo si deseas cambiarla)</small> </label>
                    <input name="password" type="password" class="form-control" autocomplete="new-password">
                </div>

                <div class="col-lg-6 mb-3">
                    <label>Estatus</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" @if($tecnico->is_active == 1) selected @endif>Activo</option>
                        <option value="0" @if($tecnico->is_active == 0) selected @endif>Inactivo</option>
                    </select>
                </div>
            </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">
            @if (isset($tecnico))
                Crear
            @else
                Actualizar
            @endif
        </button>
        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
