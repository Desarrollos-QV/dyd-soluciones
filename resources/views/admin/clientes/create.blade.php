@extends('layouts.app')
@section('title')
    Agregando nuevo Cliente
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo Cliente</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto grid-margin stretch-card">
            <div class="card">
                <div class="card-header"><h4>Nuevo Cliente</h4></div>
                <div class="card-body">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <input type="text" name="direccion" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Teléfono</label>
                                <input type="text" name="numero_contacto" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono Emergencia</label>
                                <input type="text" name="numero_alterno" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Ruta / Zona</label>
                            <select name="pertenece_ruta" class="form-select">
                                <option value="transporte_publico">Transporte publico</option>
                                <option value="sindicato">Sindicato</option>
                                <option value="particular">particular</option>
                                <option value="empresarial">Empresarial</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <hr />
                        <div class="mb-3 mt-6">
                            <h6>Recordatorios</h6>
                            <label>Mensaje Personalizado</label>
                            <textarea name="mensaje_personalizado" class="form-control" rows="8" placeholder="Mensaje personalizado para los recordatorios"></textarea>
                        </div>
   

                        <button class="btn btn-success">Guardar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
