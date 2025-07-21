<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('./') }}" class="sidebar-brand" style="font-size: 20px;">
            DYD <span style="font-size: 20px;">Soluciones</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Principal</li>
            <li class="nav-item @if(Route::is('home') || Route::is('dashboard')) active @endif">
                <a href="{{ url('./') }}" class="nav-link @if(Route::is('home') || Route::is('dashboard')) active @endif">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if(Auth::user()->hasPermission('subaccounts.index'))
            <li class="nav-item @if( Route::is('subaccounts.index') || Route::is('subaccounts.create') || Route::is('subaccounts.edit')) active @endif">
                <a href="{{ url('./subaccounts') }}" class="nav-link @if( Route::is('subaccounts.index') || Route::is('subaccounts.create') || Route::is('subaccounts.edit')) active @endif">
                    <i class="link-icon" data-feather="user-plus"></i>
                    <span class="link-title">Sub Cuentas</span>
                </a>
            </li>
            @endif


            @if(Auth::user()->hasPermission('clientes.index') || Auth::user()->hasPermission('tecnicos.index') ||  Auth::user()->hasPermission('inventario.index'))
                <li class="nav-item nav-category">Páginas</li>
                @if(Auth::user()->hasPermission('clientes.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#clientes" role="button" aria-expanded="false"
                        aria-controls="clientes">
                        <i class="link-icon" data-feather="user-check"></i>
                        <span class="link-title">Clientes</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if(Route::is('clientes.index') || Route::is('clientes.edit') || Route::is('clientes.create')) show @endif" id="clientes">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('clientes.index') }}" class="nav-link @if(Route::is('clientes.index')) active @endif">Listado</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('clientes.create') }}" class="nav-link @if( Route::is('clientes.create')) active @endif">Agregar Elemento</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasPermission('tecnicos.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#tecnicos" role="button" aria-expanded="false"
                        aria-controls="tecnicos">
                        <i class="link-icon" data-feather="briefcase"></i>
                        <span class="link-title">Técnicos</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if(Route::is('tecnicos.index') || Route::is('tecnicos.edit') || Route::is('tecnicos.create')) show @endif" id="tecnicos">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('tecnicos.index') }}" class="nav-link @if(Route::is('tecnicos.index')) active @endif">Listado</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tecnicos.create') }}" class="nav-link @if( Route::is('tecnicos.create')) active @endif">Agregar Elemento</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasPermission('inventario.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#inventario" role="button" aria-expanded="false"
                        aria-controls="inventario">
                        <i class="link-icon" data-feather="list"></i>
                        <span class="link-title">Inventario</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse  @if(Route::is('inventarios.index') || Route::is('inventarios.edit') || Route::is('inventarios.create')) show @endif" id="inventario">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('inventarios.index') }}" class="nav-link @if(Route::is('inventarios.index')) active @endif">Listado</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('inventarios.create') }}" class="nav-link @if(Route::is('inventarios.create')) active @endif">Agregar Elemento</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            @endif
 
            @if(Auth::user()->hasPermission('gastos.index') || Auth::user()->hasPermission('reports.index'))
                <li class="nav-item nav-category">Contabilidad</li>
                @if(Auth::user()->hasPermission('gastos.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#reg_fastos" role="button" aria-expanded="false"
                        aria-controls="reg_fastos">
                        <i class="link-icon" data-feather="dollar-sign"></i>
                        <span class="link-title">Registro de gastos</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if(Route::is('gastos.index') || Route::is('gastos.create')) show @endif" id="reg_fastos">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('gastos.index') }}" class="nav-link @if(Route::is('gastos.index')) active @endif">Gastos</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('gastos.create') }}" class="nav-link @if(Route::is('gastos.create')) active @endif">Agregar Elemento</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if(Auth::user()->hasPermission('reports.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#reports" role="button" aria-expanded="false"
                        aria-controls="reports">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">Reportes</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if(Route::is('reportes.index')) show @endif" id="reports">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('reportes.index') }}" class="nav-link @if(Route::is('reportes.index')) active @endif">Ingresos</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="#" class="nav-link">Egresos</a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                @endif
            @endif

            @if(Auth::user()->hasPermission('servicios_agendados.index') || Auth::user()->hasPermission('reports_services.index'))
                <li class="nav-item nav-category">Servicios</li>
                @if(Auth::user()->hasPermission('servicios_agendados.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#services" role="button" aria-expanded="false"
                        aria-controls="services">
                        <i class="link-icon" data-feather="file-plus"></i>
                        <span class="link-title">Agendados</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse @if(Route::is('servicios_agendados.index') || Route::is('servicios_agendados.create')) show @endif" id="services">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('servicios_agendados.index') }}" class="nav-link @if(Route::is('servicios_agendados.index')) active @endif">Listado</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('servicios_agendados.create') }}" class="nav-link @if(Route::is('servicios_agendados.create')) active @endif">Agregar Servicio</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                {{-- @if(Auth::user()->hasPermission('reports_services.index'))
                <li class="nav-item">
                    <a href="#reports_services" class="nav-link">
                        <i class="link-icon" data-feather="file"></i>
                        <span class="link-title">Reportes</span>
                    </a>
                </li>
                @endif --}}
            @endif


            <li class="nav-item nav-category">Extras</li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" href="javascript:void(0)" class="nav-link btn btn-link">
                        <i class="link-icon" data-feather="power"></i>
                        <span class="link-title">Cerrar sesión</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
 