

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
                            <label for="name_company">Nombre de la campaña</label>
                            <input type="text" name="name_company" class="form-control mb-4 mb-md-0"
                            value="{{ $prospect->name_company ?? old('name_company') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="name_prospect">Nombre del  prospecto</label>
                            <input type="text" name="name_prospect" class="form-control mb-4 mb-md-0"
                            value="{{ $prospect->name_prospect ?? old('name_prospect') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="company">Empresa</label>
                             <input type="text" name="company" class="form-control mb-4 mb-md-0"
                            value="{{ $prospect->company ?? old('company') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="potencial">Potencial</label>
                            <select name="potencial" id="potencial" class="form-select mb-4">
                                <option value="alto" @if($prospect->potencial == 'alto') selected @endif>Alto</option>
                                <option value="medio" @if($prospect->potencial == 'medio') selected @endif>Medio</option>
                                <option value="bajo" @if($prospect->potencial == 'bajo') selected @endif>Bajo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="sellers_id">Asignar a:</label>
                            <select name="sellers_id" id="sellers_id" class="form-select mb-4 ">
                                @foreach($sellers as $sells)
                                    <option value="{{$sells->id}}" @if($prospect->sellers_id == $sells->id) selected @endif> {{$sells->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="location">Ubicación</label>
                             <input type="text" name="location" class="form-control mb-4 mb-md-0"
                            value="{{ $prospect->location ?? old('location') }}" required/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="contacts">Número de contacto</label>
                             <input type="tel" name="contacts" class="form-control mb-4 mb-md-0"
                            value="{{ $prospect->contacts ?? old('contacts') }}" required/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" id="observations" class="form-control mb-4" rows="5" cols="5">{!! $prospect->observations !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($prospect->id) ? 'Actualizar' : 'Guardar' }}</button>
                        <a href="{{ route('prospects.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 