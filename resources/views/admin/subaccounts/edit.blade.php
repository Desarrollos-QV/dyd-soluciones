@extends('layouts.app')
@section('title')
    Editando Sub cuentas de Administración
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editando Subcuentas</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-8 mx-auto grid-margin stretch-card">
        <form method="POST" action="{{ route('subaccounts.update', $subaccount) }}">
            @csrf @method('PUT')
            <div class="card py-2">
                <div class="card-body"> 
                    <h6 class="card-title">
                        Editar Subcuenta
                    </h6>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label>Nombre</label>
                            <input name="name" class="form-control" value="{{ old('name', $subaccount->name) }}">
                        </div>
                
                        <div class="col-lg-6 mb-3">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" value="{{ old('email', $subaccount->email) }}">
                        </div>
    
                        <div class="col-lg-12 mb-3">
                            <label>Cambiar Contraseña <small>(Solo si deseas cambiarla)</small> </label>
                            <input name="password" type="password" class="form-control" autocomplete="new-password">
                        </div> 
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title" id="permissions">Selecciona los permisos a asignar</h6>
                </div>
                <div class="card-body">
                    <div class="card-header">
                        <h6 class="card-title" id="permissions">
                            Principal
                        </h6>
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('dashboard', $permissions)) checked="checked" @endif
                                name="dashboard" class="form-check-input">
                                Dashboard
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('subaccounts.index', $permissions)) checked="checked" @endif
                                name="subaccounts" class="form-check-input">
                                SubCuentas
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title" id="permissions">Páginas Iniciales</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('clientes.index', $permissions)) checked="checked" @endif
                                name="clientes" class="form-check-input">
                                Clientes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('tecnicos.index', $permissions)) checked="checked" @endif
                                name="tecnicos" class="form-check-input">
                                Técnicos
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('inventario.index', $permissions)) checked="checked" @endif
                                name="inventario" class="form-check-input">
                                Inventario
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title" id="permissions">Contabilidad</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('gastos.index', $permissions)) checked="checked" @endif
                                name="gastos" class="form-check-input">
                                Registro de gastos
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('reports.index', $permissions)) checked="checked" @endif
                                name="reports" class="form-check-input">
                                Reportes
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title" id="permissions">Servicios</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('servicios.index', $permissions)) checked="checked" @endif
                                name="servicios" class="form-check-input">
                                Servicios Agendados
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('reportes_services.index', $permissions)) checked="checked" @endif
                                name="reportes_services" class="form-check-input">
                                Reportes
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-end align-items-center ">
                            <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>
@endsection
