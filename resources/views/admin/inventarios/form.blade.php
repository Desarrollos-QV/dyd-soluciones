<div class="card">
    <div class="card-header">
        @if (isset($inventario->id))
            <div class="media d-block d-sm-flex">
                <img src="{{ asset($inventario->ine_comprobante) }}" class="wd-100p mb-3 mb-sm-0 align-self-start mr-3 wd-sm-100" alt="INE Comprobante">
                <div class="media-body">
                    <h5 class="mt-0"> @yield('title') </h5>
                    <p>
                        {{$inventario->nombre_completo}}
                    </p>
                </div>
            </div>
        @else
        <h4 class="card-title">
            @yield('title')
        </h4>
        @endif
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-6">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control"
                        value="{{ $inventario->nombre_completo }}" required>
                </div>
                <div class="col-lg-6">
                    <label for="direccion">Dirección</label>
                    <input name="direccion" id="direccion" class="form-control" required
                        value="{{ $inventario->direccion }}" />
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"
                        value="{{ $inventario->telefono }}" required>
                </div>

                <div class="col-lg-6">
                    <label for="telefono_alterno">Teléfono Alterno</label>
                    <input type="text" name="telefono_alterno" id="telefono_alterno" class="form-control"
                        value="{{ $inventario->telefono_alterno }}">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <label for="evaluacion_calidad">Evaluación Calidad</label>
                    <select name="evaluacion_calidad" id="evaluacion_calidad" class="form-control" required>
                        <option value="si" {{ $inventario->evaluacion_calidad == 'si' ? 'selected' : '' }}>Sí
                        </option>
                        <option value="no" {{ $inventario->evaluacion_calidad == 'no' ? 'selected' : '' }}>No
                        </option>
                    </select>
                </div>

                <div class="col-lg-6">
                    <label for="ine_comprobante">INE Comprobante</label>
                    <input type="file" id="myDropify" name="ine_comprobante" class="border" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit"
                        class="btn btn-primary">{{ isset($inventario->id) ? 'Actualizar' : 'Guardar' }}</button>
                    <a href="{{ route('inventarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>