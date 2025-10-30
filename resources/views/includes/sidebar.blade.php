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
            <li class="nav-item @if (Route::is('home') || Route::is('dashboard')) active @endif">
                <a href="{{ url('./') }}" class="nav-link @if (Route::is('home') || Route::is('dashboard')) active @endif">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard {{ Auth::user()->role }}</span>
                </a>
            </li>
            @if (Auth::user()->hasPermission('subaccounts.index'))
                <li class="nav-item @if (Route::is('subaccounts.index') || Route::is('subaccounts.create') || Route::is('subaccounts.edit')) active @endif">
                    <a href="{{ url('./subaccounts') }}"
                        class="nav-link @if (Route::is('subaccounts.index') || Route::is('subaccounts.create') || Route::is('subaccounts.edit')) active @endif">
                        <i class="link-icon" data-feather="user-plus"></i>
                        <span class="link-title">Sub Cuentas</span>
                    </a>
                </li>
            @endif


            @if (Auth::user()->hasPermission('prospects.index') || 
                Auth::user()->hasPermission('sellers.index') || 
                Auth::user()->hasPermission('clientes.index') || 
                Auth::user()->hasPermission('unidades.index'))
                <li class="nav-item nav-category">Páginas</li>

                @if (Auth::user()->hasPermission('prospects.index'))
                    <li class="nav-item @if (Route::is('prospects.index') || Route::is('prospects.edit') || Route::is('prospects.create')) active @endif">
                        <a class="nav-link" href="{{ route('prospects.index') }}">
                            <i class="link-icon" data-feather="user-plus"></i>
                            <span class="link-title">Prospectos</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasPermission('sellers.index'))
                    <li class="nav-item @if (Route::is('sellers.index') || Route::is('sellers.edit') || Route::is('sellers.create')) active @endif">
                        <a class="nav-link" href="{{ route('sellers.index') }}">
                            <i class="link-icon" data-feather="shopping-bag"></i>
                            <span class="link-title">Vendedores</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasPermission('clientes.index'))
                    <li class="nav-item @if (Route::is('clientes.index') || Route::is('clientes.edit') || Route::is('clientes.create')) active @endif">
                        <a class="nav-link" href="{{ route('clientes.index') }}">
                            <i class="link-icon" data-feather="user-check"></i>
                            <span class="link-title">Clientes</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasPermission('unidades.index'))
                    <li class="nav-item mb-1 @if (Route::is('unidades.index') || Route::is('unidades.edit') || Route::is('unidades.create')) active @endif">
                        <a class="nav-link" href="{{ route('unidades.index') }}">
                            <i class="link-icon" data-feather="truck"></i>
                            <span class="link-title">Unidades</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif
                {{-- @if (Auth::user()->hasPermission('inventario.index'))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#inventario" role="button" aria-expanded="false"
                        aria-controls="inventario">
                        <i class="link-icon" data-feather="list"></i>
                        <span class="link-title">Inventario</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse  @if (Route::is('inventarios.index') || Route::is('inventarios.edit') || Route::is('inventarios.create')) show @endif" id="inventario">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('inventarios.index') }}" class="nav-link @if (Route::is('inventarios.index')) active @endif">Listado</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('inventarios.create') }}" class="nav-link @if (Route::is('inventarios.create')) active @endif">Agregar Elemento</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif --}}
            @endif


            @if (Auth::user()->hasPermission('tecnicos.index'))
                <!-- Tecnicos -->
                <li class="nav-item nav-category">Técnicos</li>
                <li class="nav-item mt-1 @if (Route::is('tecnicos.index') || Route::is('tecnicos.edit') || Route::is('tecnicos.create')) active @endif">
                    <a class="nav-link" href="{{ route('tecnicos.index') }}">
                        <i class="link-icon" data-feather="briefcase"></i>
                        <span class="link-title">Técnicos</span>
                        <i class="link-arrow" data-feather="chevron-right"></i>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'admin' && Auth::user()->hasPermission('assignements.index'))
                <!-- Servicios -->
                <li class="nav-item nav-category">Servicios</li>
                <li class="nav-item @if (Route::is('assignements.index')) active @endif">
                    <a href="{{ route('assignements.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="file-plus"></i>
                        <span class="link-title">Alta de servicios</span>
                        <i class="link-arrow" data-feather="chevron-right"></i>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'tecnico' && Auth::user()->hasPermission('servicios_agendados.index'))
                <li class="nav-item nav-category">Servicios</li>
                <li class="nav-item">
                    <a href="{{ route('servicios_agendados.index') }}"  class="nav-link">
                        <i class="link-icon" data-feather="file-plus"></i>
                        <span class="link-title">Agendados</span>
                    </a> 
                </li>
            @endif

            @if (Auth::user()->hasPermission('gastos.index') ||
                    Auth::user()->hasPermission('reports.index') ||
                    Auth::user()->hasPermission('devices.index') ||
                    Auth::user()->hasPermission('historial-caja.index') ||
                    Auth::user()->hasPermission('collections.index'))
                <li class="nav-item nav-category">Contabilidad y Cobranza</li>
                @if (Auth::user()->hasPermission('gastos.index'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#reg_fastos" role="button"
                            aria-expanded="false" aria-controls="reg_fastos">
                            <i class="link-icon" data-feather="dollar-sign"></i>
                            <span class="link-title">Registro de gastos</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse @if (Route::is('gastos.index') || Route::is('gastos.create')) show @endif" id="reg_fastos">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{ route('gastos.index') }}"
                                        class="nav-link @if (Route::is('gastos.index')) active @endif">Gastos</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('gastos.create') }}"
                                        class="nav-link @if (Route::is('gastos.create')) active @endif">Agregar
                                        Elemento</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('reports.index'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#reports" role="button"
                            aria-expanded="false" aria-controls="reports">
                            <i class="link-icon" data-feather="file-text"></i>
                            <span class="link-title">Reportes</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse @if (Route::is('reportes.index') || Route::is('reportes.units')) show @endif" id="reports">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{ route('reportes.index') }}"
                                        class="nav-link @if (Route::is('reportes.index')) active @endif">Ingresos</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reportes.units') }}"
                                        class="nav-link @if (Route::is('reportes.units')) active @endif">Undades</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('devices.index'))
                    <li class="nav-item @if (Route::is('devices.index') || Route::is('devices.edit') || Route::is('devices.create')) active @endif">
                        <a class="nav-link" href="{{ route('devices.index') }}">
                            <i class="link-icon" data-feather="shopping-bag"></i>
                            <span class="link-title">Control de Inventario</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('simcontrol.index'))
                    <li class="nav-item @if (Route::is('simcontrol.index') || Route::is('simcontrol.edit') || Route::is('simcontrol.create')) active @endif">
                        <a class="nav-link" href="{{ route('simcontrol.index') }}">
                            <i class="link-icon" data-feather="smartphone"></i>
                            <span class="link-title">Control de SIM</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('historial-caja.index'))
                    <li class="nav-item @if (Route::is('historial-caja.index') || Route::is('historial-caja.create')) active @endif">
                        <a href="{{ route('historial-caja.index') }}"
                            class="nav-link @if (Route::is('historial-caja.index')) active @endif">
                            <i class="link-icon" data-feather="inbox"></i>
                            <span class="link-title">Caja y Administración</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('collections.index'))
                    <li class="nav-item @if (Route::is('collections.index') || Route::is('collections.create')) active @endif">
                        <a href="{{ url('./collections') }}"
                            class="nav-link @if (Route::is('collections.index')) active @endif">
                            <i class="link-icon" data-feather="bell"></i>
                            <span class="link-title">Gestión de cobranza</span>
                            <i class="link-arrow" data-feather="chevron-right"></i>
                        </a>
                    </li>
                @endif
            @endif



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
