

<div class="col-lg-8 mx-auto grid-margin stretch-card">
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
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control mb-4 mb-md-0"
                            value="{{ old('name', $seller->name ?? '') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="phone">Teléfono</label>
                            <input type="tel" name="phone" class="form-control mb-4 mb-md-0"
                            value="{{ old('phone', $seller->phone ?? '') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="address">Dirección</label>
                             <input type="text" name="address" class="form-control mb-4 mb-md-0"
                            value="{{ old('address', $seller->address ?? '') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="level_education">Nivel de estudios</label>
                            <input type="text" name="level_education" class="form-control mb-4 mb-md-0"
                            value="{{ old('level_education', $seller->level_education ?? '') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" id="observations" class="form-control mb-4" rows="5" cols="5" placeholder="Observaciones Extra">{!! old('observations', $seller->observations ?? '') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
 
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control mb-4 mb-md-0"
                            value="{{ old('email', $seller->email ?? '') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="password">Contraseña {{ isset($seller->id) ? '(Opcional)' : '' }}</label>
                            <input type="password" name="password" class="form-control mb-4 mb-md-0"
                            placeholder="{{ isset($seller->id) ? 'Conservar actual' : 'Contraseña' }}"
                            {{ isset($seller->id) ? '' : 'required' }}/>
                        </div>
                    </div>
                </div>

                <div class="row">                   
                    <div class="col-lg-12 mt-4">
                        @if(isset($seller->id))
                            <label for="picture">Identificación Oficial (INE - Imagen o PDF)</label>
                            <div class="row">
                                <div class="col-lg-8">
                                    <input type="file" name="picture" id="myDropify" class="form-control" accept="image/*,.pdf,application/pdf" data-allowed-file-extensions="png jpg jpeg gif pdf" value="{{ $seller->picture }}">
                                </div>
                                <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                    @if($seller->picture)
                                        @if(Str::endsWith(strtolower($seller->picture), '.pdf'))
                                            <a href="{{ asset($seller->picture) }}" target="_blank" class="btn btn-outline-danger btn-sm text-center p-2" title="Ver documento PDF">
                                                <i data-feather="file-text" style="width: 28px; height: 28px; display: block; margin: 0 auto 4px;"></i>
                                                <small class="fw-bold">Ver INE (PDF)</small>
                                            </a>
                                        @else
                                            <a href="{{ asset($seller->picture) }}" target="_blank">
                                                <img src="{{ asset($seller->picture) }}" alt="picture" style="width:100px;height: 100px;border-radius: 25px; object-fit: cover;">
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @else
                            <label for="picture">Identificación Oficial (INE - Imagen o PDF)</label>
                            <input type="file" name="picture" id="myDropify" class="form-control" accept="image/*,.pdf,application/pdf" data-allowed-file-extensions="png jpg jpeg gif pdf" required>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                        <button type="submit"
                            class="btn btn-primary mr-4">{{ isset($seller->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('sellers.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
