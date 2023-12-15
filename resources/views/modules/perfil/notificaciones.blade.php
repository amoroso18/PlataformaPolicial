@extends('layouts.app')
@section('content')

<div class="card-inner">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">Historial de notificaciones</h3>
        <div class="nk-block-des text-soft">
            <ul class="list-inline">
                <li>Mostrando últimos {{count($data)}}</li>
            </ul>
        </div>
        <div class="nk-block mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">FECHA</th>
                        <th scope="col">TITULO</th>
                        <th scope="col">CONTENIDO</th>
                        <th scope="col">OFICIAL</th>
                        <th scope="col">CORREO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <th scope="row">{{$item->created_at}}</th>
                        <td>{{$item->titulo}}</td>
                        <td>{{$item->contenido}}</td>
                        <td>{{$item->getOficial->getGrado->descripcion}} {{$item->getOficial->nombres}} {{$item->getOficial->paterno}} {{$item->getOficial->materno}}</td>
                        <td>{{$item->getOficial->correo}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>


@endsection