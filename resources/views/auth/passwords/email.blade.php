@extends('layouts.appAuth')

@section('content')


<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h5 class="nk-block-title">Recuperar Contraseña</h5>
        <div class="nk-block-des">
            <p>Ingresa tu correo electrónico.</p>
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

<form action="{{ route('recuperar_save') }}" method="POST" class="form-validate is-alter">
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
        <button class="btn btn-lg btn-primary btn-block">Recuperar</button>
    </div>
</form>

@endsection
