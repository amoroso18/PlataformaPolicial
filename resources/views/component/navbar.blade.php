<div class="nk-aside" data-content="sideNav" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
    <div class="nk-sidebar-menu" data-simplebar>
        <ul class="nk-menu">
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Dashboard</h6>
            </li>
            <li class="nk-menu-item">
                <a href="{{route('dashboard')}}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                    <span class="nk-menu-text">Panel</span>
                </a>
            </li>
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Administración</h6>
            </li>
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-tile-thumb"></em></span>
                    <span class="nk-menu-text">Usuarios</span>
                </a>
                <ul class="nk-menu-sub">
                    <li class="nk-menu-item">
                        <a href="{{route('administrador_usuarios_registro')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Registro</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('administrador_usuarios_bandeja')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Bandeja</span></a>
                    </li>
                </ul>
            </li>
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-db"></em></span>
                    <span class="nk-menu-text">Entidades</span>
                </a>
                <ul class="nk-menu-sub">
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Policia</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Agente</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Fiscal</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Peruanos</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Extranjeros</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Vehiculos</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Denuncias</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Armas</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Celulares</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('entidades_policial')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Inmuebles</span></a>
                    </li>
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-db"></em></span>
                    <span class="nk-menu-text">Secundarias</span>
                </a>
                <ul class="nk-menu-sub">
                    <li class="nk-menu-item">
                        <a href="{{route('basededatos_secundarias_delitos')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Delitos</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('basededatos_secundarias_grados')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Grados</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('basededatos_secundarias_perfiles')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Perfiles</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('basededatos_secundarias_plazos')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Plazos</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{route('basededatos_secundarias_unidades')}}" class="nk-menu-link"><span
                                class="nk-menu-text">Unidades</span></a>
                    </li>
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Disposición Fiscal</h6>
            </li><!-- .nk-menu-heading -->
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
                    <span class="nk-menu-text">Expedientes</span>
                </a>
                <ul class="nk-menu-sub">
                    <li class="nk-menu-item">
                        <a href="{{route('expedientes')}}" class="nk-menu-link"><span class="nk-menu-text">Nuevo
                                expediente</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="html/project-list.html" class="nk-menu-link"><span class="nk-menu-text">Buscar
                                expediente</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="html/project-list.html" class="nk-menu-link"><span class="nk-menu-text">Bandeja de
                                expedientes</span></a>
                    </li>
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                    <span class="nk-menu-text">Situación</span>
                </a>
                <ul class="nk-menu-sub">
                    <li class="nk-menu-item">
                        <a href="html/user-list-regular.html" class="nk-menu-link"><span
                                class="nk-menu-text">Expedientes pendientes</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="html/user-list-compact.html" class="nk-menu-link"><span
                                class="nk-menu-text">Expedientes caducados</span></a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="html/user-details-regular.html" class="nk-menu-link"><span
                                class="nk-menu-text">Expedientes culminados</span></a>
                    </li>
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item">
                <a href="html/email-templates.html" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-text-rich"></em></span>
                    <span class="nk-menu-text">Reportes</span>
                </a>
            </li>

        </ul><!-- .nk-menu -->
    </div><!-- .nk-sidebar-menu -->
    <div class="nk-aside-close">
        <a href="#" class="toggle" data-target="sideNav"><em class="icon ni ni-cross"></em></a>
    </div><!-- .nk-aside-close -->
</div><!-- .nk-aside -->