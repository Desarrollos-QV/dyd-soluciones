<div class="col-lg-7">
    <div class="card">
        <div class="card-header">
            <h4>@yield('title')</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{$cliente->nombre}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{$cliente->direccion}}" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Teléfono</label>
                    <input type="text" name="numero_contacto" value="{{$cliente->numero_contacto}}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Número de Emergencias o cliente</label>
                    <input type="text" name="numero_alterno" value="{{$cliente->numero_alterno}}" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nombre de la empresa</label>
                    <input type="text" name="empresa" value="{{$cliente->empresa}}" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label for="tipo_empresa">Tipo de empresa</label>
                    <select name="tipo_empresa" id="tipo_empresa" class="form-select">
                        <option value="Ruta" @if($cliente->tipo_empresa == 'Ruta') selected @endif>Ruta</option>
                        <option value="Particular" @if($cliente->tipo_empresa == 'Particular') selected @endif>Particular</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="direccion_empresa">Dirección de la empresa</label>
                    <input type="text" name="direccion_empresa" id="direccion_empresa" class="form-control" value="{{$cliente->direccion_empresa}}">
                </div> 
            </div>

        </div>
    </div>
</div>

<div class="col-lg-5 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <label for="usuario">usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control"
                        value="{{ $cliente->usuario }}" required>
                </div>
                <div class="col-lg-6 ">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" class="form-control"
                        value="{{ $cliente->password ?? old('password') }}" required>
                </div>
            </div>
            <div class="row mb-3 mt-4">
                <div class="col-md-12 mt-4">
                    @if(isset($cliente->id))
                        <label id="avatar">Imagen de perfil</label>
                        <div class="row">
                            <div class="col-lg-8">
                                <input type="file" name="avatar" id="myDropify" class="form-control" value="{{$cliente->avatar}}">
                            </div>
                            <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                <img src="{{ asset($cliente->avatar) }}" alt="avatar" style="width:100px;height: 100px;border-radius: 25px;">
                            </div>
                        </div>
                    @else
                        <label id="avatar">Imagen de perfil</label>
                        <input type="file" name="avatar" id="myDropify" class="form-control" value="{{$cliente->avatar}}" required>
                    @endif
                </div>
                <div class="col-md-12 mt-4">
                    @if(isset($cliente->id))
                        <label for="identificacion">Sube una Identificación Oficial</label>
                        <div class="row">
                            <div class="col-lg-8">
                                <input type="file" name="identificacion" id="myDropify2" class="form-control" value="{{$cliente->identificacion}}">
                            </div>
                            <div class="col-lg-4" style="display: flex;justify-content: center;align-items: center;">
                                <img src="{{ asset($cliente->identificacion) }}" alt="identificacion" style="width:100px;height: 100px;border-radius: 25px;">
                            </div>
                        </div>
                    @else
                        <label for="identificacion">Sube una Identificación Oficial</label>
                        <input type="file" name="identificacion" id="myDropify2" class="form-control" value="{{$cliente->identificacion}}">
                    @endif
                </div>
            </div>

            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($cliente->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
