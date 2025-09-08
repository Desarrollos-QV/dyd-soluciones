

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
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->name ?? old('name') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="phone">Telefono</label>
                            <input type="tel" name="phone" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->phone ?? old('phone') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="address">Dirección</label>
                             <input type="text" name="address" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->address ?? old('address') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="level_education">Nivel de estudios</label>
                            <input type="text" name="level_education" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->level_education ?? old('level_education') }}" required/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($seller->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('sellers.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 