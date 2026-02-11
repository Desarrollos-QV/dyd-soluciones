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
                    <div class="mb-4">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Principal</h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && in_array('dashboard', $permissions)) checked @endif
                                name="dashboard" class="form-check-input">
                                Dashboard
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('subaccounts.index', $permissions) || in_array('subaccounts', $permissions))) checked @endif
                                name="subaccounts" class="form-check-input">
                                SubCuentas
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Páginas</h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('prospects.index', $permissions) || in_array('prospects', $permissions))) checked @endif
                                name="prospects" class="form-check-input">
                                Prospectos
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('sellers.index', $permissions) || in_array('sellers', $permissions))) checked @endif
                                name="sellers" class="form-check-input">
                                Vendedores
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('clientes.index', $permissions) || in_array('clientes', $permissions))) checked @endif
                                name="clientes" class="form-check-input">
                                Clientes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('unidades.index', $permissions) || in_array('unidades', $permissions))) checked @endif
                                name="unidades" class="form-check-input">
                                Unidades
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Técnicos</h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('tecnicos.index', $permissions) || in_array('tecnicos', $permissions))) checked @endif
                                name="tecnicos" class="form-check-input">
                                Técnicos
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Servicios</h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('servicios.index', $permissions) || in_array('servicios', $permissions) || in_array('assignements.index', $permissions))) checked @endif
                                name="servicios" class="form-check-input">
                                Alta de servicios
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('reportes_services.index', $permissions) || in_array('reportes_services', $permissions))) checked @endif
                                name="reportes_services" class="form-check-input">
                                Reportes de Servicios
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Contabilidad y Cobranza</h6>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('gastos.index', $permissions) || in_array('gastos', $permissions))) checked @endif
                                name="gastos" class="form-check-input">
                                Registro de gastos
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('reports.index', $permissions) || in_array('reports', $permissions))) checked @endif
                                name="reports" class="form-check-input">
                                Reportes (Contabilidad)
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('devices.index', $permissions) || in_array('devices', $permissions))) checked @endif
                                name="devices" class="form-check-input">
                                Control de Inventario
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('simcontrol.index', $permissions) || in_array('simcontrol', $permissions))) checked @endif
                                name="simcontrol" class="form-check-input">
                                Control de SIM
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('historial-caja.index', $permissions) || in_array('historial-caja', $permissions))) checked @endif
                                name="historial-caja" class="form-check-input">
                                Caja y Administración
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" 
                                @if (isset($permissions) && (in_array('collections.index', $permissions) || in_array('collections', $permissions))) checked @endif
                                name="collections" class="form-check-input">
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
                            <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>
@endsection
