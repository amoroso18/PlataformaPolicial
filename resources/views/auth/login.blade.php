@extends('layouts.appAuth')

@section('content')

<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h5 class="nk-block-title">Iniciar Sesión</h5>
        <div class="nk-block-des">
            <p>Ingresa tu correo electrónico y contraseña.</p>
        </div>
    </div>
</div><!-- .nk-block-head -->

{{-- Para dectar errores del form --}}
@if ($errors->any())
<div class="fv-row mb-10">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@if(session()->has('error'))
<div class="fv-row mb-10">
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
</div>
@elseif(session()->has('success'))
<div class="fv-row mb-10">
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
</div>
@endif

{{-- Detectar errores en la sesion --}}

<form action="{{ route('credenciales') }}" method="POST" class="form-validate is-alter">
    @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <a class="link link-primary link-sm" tabindex="-1" href="{{route('ayuda')}}">'¿Necesitas ayuda?</a>
        </div>
        <div class="form-control-wrap">
            <input autocomplete="off" type="text" class="form-control form-control-lg valid @error('email') is-invalid @enderror" placeholder="ingresa tu Correo electrónico" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div><!-- .form-group -->
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="password">Contraseña</label>
            <a class="link link-primary link-sm" tabindex="-1" href="{{route('recuperar')}}">¿Olvidaste la contraseña?</a>
        </div>
        <div class="form-control-wrap">
            <a tabindex="-1" onclick="mostrarContrasena()" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
            </a>
            <input autocomplete="new-password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Ingresa tu contraseña" id="password" name="password" required autocomplete="current-password">
        </div>
        <script>
            function mostrarContrasena() {
                var tipo = document.getElementById("password");
                if (tipo.type == "password") {
                    tipo.type = "text";
                } else {
                    tipo.type = "password";
                }
            }
        </script>

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div><!-- .form-group -->
    <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block">Ingresar</button>
    </div>
</form>

{{--
<div class="text-center pt-4 pb-3">
    <h6 class="overline-title overline-title-sap"><span>OR</span></h6>
</div>
<ul class="nav justify-center gx-4">
    <li class="nav-item"><a class="nav-link" href="#">SIGCP</a></li>
    <li class="nav-item"><a class="nav-link" href="#">CORREO PNP</a></li>
</ul>
<div class="text-center mt-5">
    <span class="fw-500">¿Sin cuenta? <a href="{{route('registrarse')}}">Registrate ahora</a></span>
</div>
--}}

@endsection

@push('BarraLateralAnuncios')

<div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
        <div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
            <div class="slider-item">
                <div class="nk-feature nk-feature-center">
                    <div class="nk-feature-img">
                        <img class="round" src="{{asset('images/sivipol/BANNER2.png')}}" srcset="{{asset('images/sivipol/BANNER2.png')}} 2x" alt="">
                    </div>
                    <div class="nk-feature-content py-4 p-sm-5">
                        <h4>SIVIPOL</h4>
                        <p>SISTEMA INTEGRADO DE VIDEOVIGILANCIA POLICIAL</p>
                    </div>
                </div>
            </div><!-- .slider-item -->
            <div class="slider-item">
                <div class="nk-feature nk-feature-center">
                    <div class="nk-feature-img">
                        <img class="round" src="{{asset('images/sivipol/BANNER1.png')}}" srcset="{{asset('images/sivipol/BANNER1.png')}} 2x" alt="">
                    </div>
                    <div class="nk-feature-content py-4 p-sm-5">
                        <h4>DIRCOCOR</h4>
                        <p>Dirección Contra la Corrupción </p>
                    </div>
                </div>
            </div><!-- .slider-item -->
           
        </div><!-- .slider-init -->
        <div class="slider-dots"></div>
        <div class="slider-arrows"></div>
    </div><!-- .slider-wrap -->
</div><!-- .nk-split-content -->

@endpush