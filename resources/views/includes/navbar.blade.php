<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i data-feather="search"></i>
                    </div>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="Busqueda de: Servicios, Clientes, Tecnicos, Inventario.">
            </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown nav-notifications">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>
                    <div class="indicator">
                        <div class="circle"></div>
                    </div>
                </a>
                <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                    <div class="dropdown-header d-flex align-items-end justify-content-end">
                       <p class="mb-0 font-weight-medium">Listado de Notificaciones</p>
                    </div>
                    <div class="dropdown-body inner-body-card-notify"> 
                        {{-- AJAX NOTIFICATIONS --}} 
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user())
                        <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('assets/images/background-login.jpg') }}" alt="">
                    @elseif(Auth::guard('sellers')->check())
                        <img src="{{ Auth::guard('sellers')->user()->picture ? asset(Auth::guard('sellers')->user()->picture) : asset('assets/images/background-login.jpg') }}" alt="">
                    @else
                        <img src="{{ asset('assets/images/background-login.jpg') }}" alt="">
                    @endif
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="figure mb-3">
                            @if(Auth::user())
                                <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('assets/images/background-login.jpg') }}" alt="">
                            @elseif(Auth::guard('sellers')->check())
                                <img src="{{ Auth::guard('sellers')->user()->picture ? asset(Auth::guard('sellers')->user()->picture) : asset('assets/images/background-login.jpg') }}" alt="">
                            @else
                                <img src="{{ asset('assets/images/background-login.jpg') }}" alt="">
                            @endif
                        </div>
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ Auth::user() ? Auth::user()->name : (Auth::guard('sellers')->check() ? Auth::guard('sellers')->user()->name : 'Usuario') }}</p>
                            <p class="email text-muted mb-3">{{ Auth::user() ? Auth::user()->email : (Auth::guard('sellers')->check() ? Auth::guard('sellers')->user()->email : 'Email') }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            <li class="nav-item">
                                <a href="pages/general/profile.html" class="nav-link">
                                    <i data-feather="user"></i>
                                    <span>Perfíl</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">
                                    <i data-feather="home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}" class="nav-link">
                                    <i data-feather="settings"></i>
                                    <span>Configuraciones</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link">
                                    <i data-feather="file-text"></i>
                                    <span>Reportes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <form @if(Auth::guard('sellers')->check()) action="{{ route('sellers.logout') }}" @else action="{{ route('logout') }}" @endif method="POST">
                                    @csrf
                                    <button type="submit" href="javascript:void(0)" class="nav-link btn btn-link">
                                        <i data-feather="log-out"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>