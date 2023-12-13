@extends('layouts.app')
@section('content')

<div class="card-inner">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">Historial de conectividad de usuario</h3>
        <div class="nk-block-des text-soft">
            <ul class="list-inline">
                <li>Usuario ID: <span class="text-base">{{Auth::user()->id}}</span></li>
                <li>Usuario última actualización: <span class="text-base">{{Auth::user()->updated_at}}</span></li>
            </ul>
        </div>
        <div class="nk-block mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">TIPO DE CONEXIÓN</th>
                        <th scope="col">DISPOSITIVO</th>
                        <th scope="col">VERSIÓN</th>
                        <th scope="col">NAVEGADOR</th>
                        <th scope="col">FECHA DE CONEXIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(Auth::user()->getHistorialConexion as $item)
                    <tr>
                        <th scope="row">{{$item->autenticacion}}</th>
                        <td>{{$item->dipositivo}}</td>
                        <td>{{$item->lugar}}</td>
                        <td>{{$item->navegador}}</td>
                        <td>{{$item->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>


@endsection