@extends('layouts.app')
@section('content')

<div class="card-inner">
    <div class="nk-block">
        <div class="nk-block-head">
            <h5 class="title">Usuario</h5>
            <p>Detalles de tu usuario en la plataforma</p>
        </div>
        <div class="profile-ud-list">
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">CIP</span>
                    <span class="profile-ud-value">{{Auth::user()->carnet}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">DNI</span>
                    <span class="profile-ud-value">{{Auth::user()->dni}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">NOMBRES</span>
                    <span class="profile-ud-value">{{Auth::user()->nombres}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">APELLIDOS</span>
                    <span class="profile-ud-value">{{Auth::user()->apellidos}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">CELULAR</span>
                    <span class="profile-ud-value">{{Auth::user()->phone}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">CORREO</span>
                    <span class="profile-ud-value">{{Auth::user()->email}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">PERFIL</span>
                    <span class="profile-ud-value">{{Auth::user()->getPerfil->descripcion}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">GRADO</span>
                    <span class="profile-ud-value">{{Auth::user()->getGrado->descripcion}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">UNIDAD</span>
                    <span class="profile-ud-value">{{Auth::user()->getUnidad->descripcion}}</span>
                </div>
            </div>
            <div class="profile-ud-item">
                <div class="profile-ud wider">
                    <span class="profile-ud-label">ESTADO</span>
                    <span class="profile-ud-value">{{Auth::user()->getEstado->descripcion}}</span>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection