<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="{{ config('app.name_large', 'Laravel') }}">
    <meta name="author" content="FullStack Developer AECC">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{asset('images/sivipol/LogoSivipol.png')}}">
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{asset('assets/css/dashlite.css?ver=2.9.0')}}">
    <link id="skin-default" rel="stylesheet" href="{{asset('assets/css/theme.css?ver=2.9.0')}}">
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div id="app" class="nk-app-root">
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-md">
                        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                                <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                            </div>
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5">
                                    <a href="{{route('login')}}" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" src="{{asset('images/sivipol/BannerSivipol.png')}}" srcset="{{asset('images/sivipol/BannerSivipol.png')}} 2x" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" src="{{asset('images/sivipol/BannerSivipol.png')}}" srcset="{{asset('images/sivipol/BannerSivipol.png')}}" alt="logo-dark">
                                    </a>
                                </div>


                                <main>
                                    @yield('content')
                                </main>


                            </div><!-- .nk-block -->
                            <div class="nk-block nk-auth-footer">
                                <!-- <div class="nk-block-between">
                                    <ul class="nav nav-sm">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Nuevo usuario</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Soporte</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Sugerencias</a>
                                        </li>
                                    </ul>
                                </div> -->
                                <div class="mt-3">
                                    <p> SIVIPOL &copy; 2023</p>
                                </div>
                            </div><!-- .nk-block -->
                        </div><!-- .nk-split-content -->


                        @stack('BarraLateralAnuncios')

                    </div><!-- .nk-split -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
    </div>
    <script src="{{asset('assets/js/bundle.js?ver=2.9.0')}}"></script>
    <script src="{{asset('assets/js/scripts.js?ver=2.9.0')}}"></script>
</body>

</html>