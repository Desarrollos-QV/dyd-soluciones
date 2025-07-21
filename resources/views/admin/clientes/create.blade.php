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
                            <label>¿Pertenece a la Ruta 22?</label>
                            <select name="pertenece_ruta_22" class="form-select">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Pago Mensual</label>
                                <input type="number" step="0.01" name="pago_mensual" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Recordatorios</label><br>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">SMS
                                    <input class="form-check-input" type="radio" name="recordatorio" id="recordatorio" value="sms">
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">Email
                                    <input class="form-check-input" type="radio" name="recordatorio" id="recordatorio" value="email">
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Mensaje Personalizado</label>
                            <textarea name="mensaje_personalizado" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Mensaje General</label>
                            <textarea name="mensaje_general" class="form-control"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Costo Plataforma</label>
                                <input type="number" step="0.01" name="costo_plataforma" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Costo SIM</label>
                                <input type="number" step="0.01" name="costo_sim" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Descuento Promocional</label>
                                <input type="number" step="0.01" name="descuento" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Ganancia del Servicio</label>
                                <input type="number" step="0.01" name="ganancia" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Cobro Frecuencia</label>
                                <select name="cobro" class="form-select">
                                    <option value="mensual">Mensual</option>
                                    <option value="quincenal">Quincenal</option>
                                    <option value="semanal">Semanal</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-success">Guardar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
