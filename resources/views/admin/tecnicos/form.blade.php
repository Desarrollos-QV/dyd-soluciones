<div class="col-lg-7">
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
                        <label for="lastname">Apellidos</label>
                        <input type="text" name="lastname" class="form-control" value="{{ $tecnico->lastname }}"
                            required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="schooling">Escolaridad</label>
                        <input type="text" name="schooling" class="form-control" value="{{ $tecnico->schooling }}"
                            placeholder="Ej.- Secondaria, Tecnica, Licenciatura, Ingenieria, etc." required>
                    </div>
                    <div class="col-lg-6">
                        <label for="experience">Experiecia</label>
                        <textarea name="experience" id="experience" class="form-control"
                            placeholder="Ingresa tu Experiecia (No es obligatorio)">{!! $tecnico->experience !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="licence">¿Cuenta con Licencia?</label>
                        <select name="licence" id="licence" class="form-select">
                            <option value="SI" @if ($tecnico->licence == 'SI') selected @endif>Si</option>
                            <option value="NO" @if ($tecnico->licence == 'NO') selected @endif>NO</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="vehicle">¿Cuenta con Vehículo?</label>
                        <select name="vehicle" id="vehicle" class="form-select">
                            <option value="SI" @if ($tecnico->vehicle == 'SI') selected @endif>Si</option>
                            <option value="NO" @if ($tecnico->vehicle == 'NO') selected @endif>NO</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="uniform">¿Cuenta con Uniforme?</label>
                        <select name="uniform" id="uniform" class="form-select">
                            <option value="SI" @if ($tecnico->uniform == 'SI') selected @endif>Si</option>
                            <option value="NO" @if ($tecnico->uniform == 'NO') selected @endif>NO</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="skills">Habilidades de comunicación</label>
                        <textarea name="skills" id="skills" class="form-control" placeholder="Ingresa tus Habilidades para comunicarte"
                            required>{!! $tecnico->skills !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-5">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ $tecnico->email }}"
                            required>
                    </div>
                </div>

                @if (!isset($tecnico->id))
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
                                <option value="1" @if ($tecnico->is_active == 1) selected @endif>Activo</option>
                                <option value="0" @if ($tecnico->is_active == 0) selected @endif>Inactivo
                                </option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="tools" class="d-flex align-items-center justify-content-between">
                            ¿Cuenta con herramientas?

                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTools()">+ Añadir
                                herramienta</button>
                        </label>
                        <div id="repuestos-list">
                            @php
                                $ref = old('tools', $tecnico->tools ?? []);
                            @endphp
                            <table class="table-secondary w-100 border">
                                <tbody id="rows-container">
                                    <tr>
                                        <td>
                                            <input type="text" name="tools[]"
                                                placeholder="Agrega nueva herramienta" style="padding: 5px 0 5px 5px;" />
                                        </td>
                                    </tr>
                                    @foreach ($ref as $i => $r)
                                        <tr>
                                            <td>
                                                <input type="text" name="tools[]" value="{{ $r }}"
                                                    placeholder="Agrega nueva herramienta"
                                                    style="padding: 5px 0 5px 5px;" />
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-outline-primary d-flex justify-content-center align-items-center"
                                                    onclick="removeRow(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-trash-2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10"
                                                            y2="17"></line>
                                                        <line x1="14" y1="11" x2="14"
                                                            y2="17"></line>
                                                    </svg>
                                                    &nbsp;<span class="mt-1">Eliminar</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary">
                            @if (isset($tecnico))
                                Crear Tecnico
                            @else
                                Actualizar
                            @endif
                        </button>
                        <a href="{{ route('tecnicos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
