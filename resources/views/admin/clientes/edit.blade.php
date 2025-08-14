@extends('layouts.app')
@section('title')
    Clientes
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Actualizando cliente</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto grid-margin stretch-card">
            <div class="card">
                <div class="card-header"><h4>Editando el cliente #{{ $cliente->id }} - <small>({{$cliente->nombre}})</small> </h4></div>
                <div class="card-body">
                    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required value="{{ $cliente->nombre }}">
                            </div>
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="{{ $cliente->direccion }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Teléfono</label>
                                <input type="text" name="numero_contacto" class="form-control" required value="{{ $cliente->numero_contacto }}">
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono Emergencia</label>
                                <input type="text" name="numero_alterno" class="form-control" value="{{ $cliente->numero_alterno }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Ruta / Zona</label>
                            <select name="pertenece_ruta" class="form-select">
                                <option value="transporte_publico" @if($cliente->pertenece_ruta == 'transporte_publico') selected @endif>Transporte publico</option>
                                <option value="sindicato" @if($cliente->pertenece_ruta == 'sindicato') selected @endif>Sindicato</option>
                                <option value="particular" @if($cliente->pertenece_ruta == 'particular') selected @endif>particular</option>
                                <option value="empresarial" @if($cliente->pertenece_ruta == 'empresarial') selected @endif>Empresarial</option>
                                <option value="otros" @if($cliente->pertenece_ruta == 'otros') selected @endif>Otros</option>
                            </select>
                        </div>
 

                        <div class="mb-3">
                            <label>Recordatorios</label><br>
                           
                        </div>

                        <div class="mb-3">
                            <label>Mensaje Personalizado</label>
                            <textarea name="mensaje_personalizado" rows="8" placeholder="Mensaje personalizado para los recordatorios" class="form-control">{!! $cliente->mensaje_personalizado !!}</textarea>
                        </div> 
                        
                        <button class="btn btn-success">Actualizar Cliente Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
