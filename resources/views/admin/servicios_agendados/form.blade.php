<div class="col-lg-4 grid-margin stretch-card">
    <div class="card">
        <div class="card-header mt-1">
            @if (isset($servicio->id))
                <div class="media d-block d-sm-flex">
                    <div class="media-body">
                        <h5 class="mt-0"> @yield('title') </h5>
                        <p>
                            {{ $servicio->tipo_servicio }} | #{{$servicio->id}}
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
            <div class="row">
                <div class="col-lg-12">
                    <label for="fotos">Registro fotográfico</label>
 
                    <div class="dropzone" id="registro-fotografico-dropzone"></div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <label for="costo_instalador">Costo Instalador</label>
                    <input name="costo_instalador" step="0.01" class="form-control" data-inputmask="'alias': 'currency'"
                        value="{{ old('costo_instalador', $servicio->costo_instalador ?? '') }}">
                </div>

                <div class="col-lg-12 mt-3">
                    <label for="gasto_adicional">Gasto Adicional</label>
                    <input name="gasto_adicional" step="0.01" class="form-control" data-inputmask="'alias': 'currency'"
                        value="{{ old('gasto_adicional', $servicio->gasto_adicional ?? '') }}">
                </div>

                <div class="col-lg-12 mt-3">
                    <label for="saldo_favor">Saldo a Favor</label>
                    <input name="saldo_favor" step="0.01" class="form-control" data-inputmask="'alias': 'currency'"
                        value="{{ old('saldo_favor', $servicio->saldo_favor ?? '') }}">
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="submit"
                        class="btn btn-primary">{{ isset($servicio->id) ? 'Actualizar' : 'Guardar' }}</button>
                    <a href="{{ route('servicios_agendados.index') }}" class="btn btn-secondary mr-4 ml-3">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Tipo</label>
                    <select name="tipo_servicio" class="form-select">
                        @foreach (['Instalación', 'Reparación', 'Apoyo'] as $t)
                            <option value="{{ $t }}" @selected(old('tipo', $servicio->tipo ?? '') == $t)>{{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" class="form-control"
                        value="{{ old('fecha', $servicio->fecha ?? now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="user_id">Técnico</label>
                    <select name="user_id" class="form-select">
                        @foreach ($tecnicos as $tec)
                            <option value="{{ $tec->id }}" @selected(old('user_id', $servicio->user_id ?? '') == $tec->id)>{{ $tec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="titular">Titular</label>
                    <input type="text" name="titular" class="form-control"
                        value="{{ old('titular', $servicio->titular ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label for="contacto">Contacto</label>
                    <input type="text" name="contacto" class="form-control" data-inputmask-alias="(+999) 999-9999"
                        value="{{ old('contacto', $servicio->contacto ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label for="unidad">Unidad</label>
                    <input type="text" name="unidad" class="form-control"
                        value="{{ old('unidad', $servicio->unidad ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="falla_reportada">Falla Reportada</label>
                        <textarea name="falla_reportada" class="form-control" maxlength="50" rows="5" placeholder="Ingresa los detalles de la falla">{{ old('falla_reportada', $servicio->falla_reportada ?? '') }}</textarea>
                    </div>

                    <div class="col-lg-6">
                        <label for="reparacion_realizada">Reparación Realizada</label>
                        <textarea name="reparacion_realizada" class="form-control" maxlength="50" rows="5" placeholder="Ingresa los detalles de la reparación">{{ old('reparacion_realizada', $servicio->reparacion_realizada ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="mt-3">
                <label>Refacciones   </label>
                <div id="repuestos-list">
                    @php
                        $ref = old('refacciones', $servicio->refacciones ?? []);
                        $cant = old('refacciones_cantidad', $servicio->refacciones_cantidad ?? []);
                    @endphp
                    
                    {{-- <div class="row mb-2">
                        <div class="col">
                            <input name="refacciones[]" value="{{ $r }}" class="form-control" />
                        </div>
                        <div class="col">
                            <input name="refacciones_cantidad[]" type="number" value="{{ $cant[$i] ?? 1 }}"
                                class="form-control" />
                        </div>
                    </div> --}}
                    <table>
                        <tbody id="rows-container">
                            @foreach ($ref as $i => $r)
                            <tr>
                                <td><input type="text" name="refacciones[]" value="{{ $r }}" /></td>
                                <td><input name="refacciones_cantidad[]" type="number" value="{{ $cant[$i] ?? 1 }}"/></td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary d-flex justify-content-center align-items-center" onclick="removeRow(this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        &nbsp;<span class="mt-1">Eliminar</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">+ Añadir
                    refacción</button>
            </div>
        </div>
    </div>
</div>
