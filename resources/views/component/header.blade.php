<div class="nk-header nk-header-fixed is-light">
    <div class="container-lg wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-header-brand">
                <a href="{{route('perfil')}}" class="logo-link">
                    <img class="logo-light logo-img" src="{{asset('images/sivipol/BannerSivipolTemaOscuro.png')}}" srcset="{{asset('images/sivipol/BannerSivipolTemaOscuro.png')}}" alt="logo">
                    <img class="logo-dark logo-img" src="{{asset('images/sivipol/BannerSivipol.png')}}" srcset="{{asset('images/sivipol/BannerSivipol.png')}} 2x" alt="logo-dark">
                </a>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">
                                    
                                </span>
                                <a href="{{route('actualizacion')}}">Historial de usuario</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                  
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="{{route('actualizacion')}}">Ver historial de actividad de usuario</a>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    <li class="dropdown actividad-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-danger"><em class="icon ni ni-activity-alt"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">
                                    
                                </span>
                                <a href="{{route('conexion')}}">Actividad de conexión</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Inicio de sesión</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Nuevo dipositivo conectado</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Nuevo Navegador detectado</div>
                                            <div class="nk-notification-time">hace 1 hora</div>
                                        </div>
                                    </div>
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="{{route('conexion')}}">Ver historial de actividad de conexión</a>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle mr-lg-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>{{ Auth::user()->id }}</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->nombres }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('perfil')}}"><em class="icon ni ni-user-alt"></em><span>Perfil de usuario</span></a></li>
                                    <li><a href="{{route('perfil')}}"><em class="icon ni ni-account-setting-alt"></em><span>Cambio de contraseña</span></a></li>
                                    <li><a href="{{route('actualizacion')}}"><em class="icon ni ni-bell"></em><span>Historial de usuario</span></a></li>
                                    <li><a href="{{route('conexion')}}"><em class="icon ni ni-activity-alt"></em><span>Historial de conexión</span></a></li>
                                    <li><a class="dark-switch" href="#" id="toggle-theme-btn"><em class="icon ni ni-moon"></em><span>Modo oscuro</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{route('salir')}}"><em class="icon ni ni-signout"></em><span>Salir</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    <li class="d-lg-none">
                        <a href="#" class="toggle nk-quick-nav-icon mr-n1" data-target="sideNav"><em class="icon ni ni-menu"></em></a>
                    </li>
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>