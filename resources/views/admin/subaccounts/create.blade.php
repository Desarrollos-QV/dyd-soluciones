@extends('layouts.app')
@section('title')
    Agreagndo Sub cuentas de Administración
@endsection

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregando Subcuentas</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mx-auto grid-margin stretch-card">
            <form method="POST" action="{{ route('subaccounts.store') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Crear Subcuenta</h6>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label>Nombre</label>
                                <input name="name" class="form-control" value="{{ old('name') }}">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label>Email</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label>Contraseña</label>
                                <input name="password" type="password" class="form-control" autocomplete="off">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label>Confirmar Contraseña</label>
                                <input name="password_confirmation" type="password" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title" id="permissions">Selecciona los permisos a asignar</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Principal</h6>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="dashboard" class="form-check-input">
                                    Dashboard
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="subaccounts" class="form-check-input">
                                    SubCuentas
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Páginas</h6>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="prospects" class="form-check-input">
                                    Prospectos
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="sellers" class="form-check-input">
                                    Vendedores
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="clientes" class="form-check-input">
                                    Clientes
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="unidades" class="form-check-input">
                                    Unidades
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Técnicos</h6>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="tecnicos" class="form-check-input">
                                    Técnicos
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Servicios</h6>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="servicios" class="form-check-input">
                                    Alta de servicios
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="reportes_services" class="form-check-input">
                                    Reportes de Servicios
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted border-bottom pb-2 mb-3">Contabilidad y Cobranza</h6>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="gastos" class="form-check-input">
                                    Registro de gastos
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="reports" class="form-check-input">
                                    Reportes (Contabilidad)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="devices" class="form-check-input">
                                    Control de Inventario
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="simcontrol" class="form-check-input">
                                    Control de SIM
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="historial-caja" class="form-check-input">
                                    Caja y Administración
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="collections" class="form-check-input">
                                    Gestión de cobranza
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-end align-items-center ">
                                <button class="btn btn-primary">Crear SubCuenta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection