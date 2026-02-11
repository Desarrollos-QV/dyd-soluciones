

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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" id="observations" class="form-control mb-4" rows="5" cols="5" placeholder="Observaciones Extra">{!! $seller->observations ?? old('observations') !!}</textarea>
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
                            <label for="email">Correo Electronico</label>
                            <input type="email" name="email" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->email ?? old('email') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control mb-4 mb-md-0"
                            value="{{ $seller->password ?? old('password') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">                   
                    <div class="col-lg-12 mt-4">
                        @if(isset($seller->id))
                            <label for="identificacion">Sube una Identificación Oficial</label>
                            <div class="row">
                                <div class="col-lg-8">
                                    <input type="file" name="picture" id="myDropify" class="form-control" value="{{$seller->picture}}">
                                </div>
                                <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                    <img src="{{ asset($seller->picture) }}" alt="picture" style="width:100px;height: 100px;border-radius: 25px;">
                                </div>
                            </div>
                        @else
                            <label for="picture">Sube una Identificación Oficial</label>
                            <input type="file" name="picture" id="myDropify" class="form-control" value="">
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
