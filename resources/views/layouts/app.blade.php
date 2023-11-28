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

<body class="nk-body bg-white npc-default has-aside ">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap ">
                @include('component.header')
                <div class="nk-content ">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            @include('component.navbar')
                            <div class="nk-content-body">
                                <div class="nk-content-wrap">
                                    <p>Starter page for Demo4 layout.</p>
                                </div>
                                @include('component.footer')
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/bundle.js?ver=2.9.0')}}"></script>
    <script src="{{asset('assets/js/scripts.js?ver=2.9.0')}}"></script>
</body>

</html>