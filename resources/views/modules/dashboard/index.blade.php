@extends('layouts.app')

@push('scriptsFooter')
<script src="{{asset('assets/js/charts/gd-default.js?ver=2.9.0')}}"></script>
<style>
    .clock {
        /* position: absolute; */
        /* top: 50%; */
        left: 50%;
        /* transform: translateX(-50%) translateY(-50%); */
        color: #17D4FE;
        font-size: 20px;
        font-family: Orbitron;
        /* letter-spacing: 7px; */
    }
</style>
@endpush

@section('content')


<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Panel de control</h3>
            <div class="nk-block-des text-soft">
                <p>Bienvenido al SISTEMA INTEGRADO DE VIDEOVIGILANCIA POLICIAL</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li>
    <div id="MyClockDisplay" class="clock" onload="showTime()"></div>

                            <!-- <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">últimos</span> 30 Días</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><span>últimos 30 Días</span></a></li>
                                        <li><a href="#"><span>últimos 6 Meses</span></a></li>
                                        <li><a href="#"><span>últimos 1 Año</span></a></li>
                                    </ul>
                                </div>
                            </div> -->
                        </li>
                        <!-- <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reporte</span></a></li> -->
                    </ul>
                </div>
            </div>
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">
        <div class="col-sm-6">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">Expedientes creados</h6>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">150</span>
                            <span class="sub-title"><span class="change down text-danger"><em class="icon ni ni-arrow-long-down"></em>1.93%</span>Último mes</span>
                        </div>
                        <div class="nk-sales-ck">
                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">Expedientes pendientes</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Daily Avg. subscription"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">68</span>
                            <span class="sub-title"><span class="change up text-success"><em class="icon ni ni-arrow-long-up"></em>2.45%</span>Último mes</span>
                        </div>
                        <div class="nk-sales-ck">
                            <canvas class="sales-bar-chart" id="totalSubscription"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xl-6">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">Entidades agregadas</h6>
                            <p>Personas e inmuebles</p>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left" title="Revenue from subscription"></em>
                        </div>
                    </div>
                    <div class="align-end gy-3 gx-5 flex-wrap flex-md-nowrap flex-xl-wrap">
                        <div class="nk-sale-data-group flex-md-nowrap g-4">
                            <div class="nk-sale-data">
                                <span class="amount">585 <span class="change down text-danger"><em class="icon ni ni-arrow-long-down"></em>16.93%</span></span>
                                <span class="sub-title">Peronas</span>
                            </div>
                            <div class="nk-sale-data">
                                <span class="amount">190<span class="change up text-success"><em class="icon ni ni-arrow-long-up"></em>4.26%</span></span>
                                <span class="sub-title">Inmuebles</span>
                            </div>
                        </div>
                        <div class="nk-sales-ck sales-revenue">
                            <canvas class="sales-bar-chart" id="salesRevenue"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .col -->
        <div class="col-xl-6">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Expedientes por caducar</h6>
                            <p>En los proximos 20 días</p>
                        </div>
                    </div>
                    <div class="nk-sale-data-group align-center justify-between gy-3 gx-5">
                        <div class="nk-sale-data">
                            <span class="amount">11</span>
                        </div>
                        <div class="nk-sale-data">
                            <span class="amount sm">de 150 <small>Expedientes</small></span>
                        </div>
                    </div>
                    <div class="nk-sales-ck large pt-4">
                        <canvas class="sales-overview-chart" id="salesOverview"></canvas>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-12">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title"><span class="mr-2">Usuarios</span> <a class="link d-none d-sm-inline">Todo el historial</a></h6>
                        </div>
                    </div>
                </div>
                <div class="card-inner p-0 border-top">
                    <div class="nk-tb-list nk-tb-orders">
                        <div class="nk-tb-item nk-tb-head">
                            <!-- id, carnet, dni, nombres, apellidos, phone, phone_verified_at, email, email_verified_at, password, unidad_id, estado_id, perfil_id, grado_id, remember_token, created_at, updated_at, reseteo_contrasena, token_seguridad -->

                            <div class="nk-tb-col"><span>Nro.</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>Nombre</span></div>
                            <div class="nk-tb-col tb-col-md"><span>Correo</span></div>
                            <div class="nk-tb-col tb-col-lg"><span>Celular</span></div>
                            <div class="nk-tb-col"><span>estado_id</span></div>
                            <div class="nk-tb-col"><span class="d-none d-sm-inline">perfil</span></div>
                            <div class="nk-tb-col"><span>&nbsp;</span></div>
                        </div>
                        @foreach($usuarios as $item)
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <span class="tb-lead"><a href="#">#{{$item->id}}</a></span>
                            </div>
                            <div class="nk-tb-col tb-col-sm">
                                <div class="user-card">
                                    <div class="user-name">
                                        <span class="tb-lead"> {{$item->getGrado->descripcion}} PNP {{$item->nombres}} {{$item->apellidos}}</span>
                                    </div>

                                </div>
                            </div>
                            <div class="nk-tb-col tb-col-md">
                                <span class="tb-sub">{{$item->email}}</span>
                            </div>
                            <div class="nk-tb-col tb-col-lg">
                                <span class="tb-sub text-primary">{{$item->phone}}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub tb-amount">{{$item->getEstado->descripcion}}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="">{{$item->getPerfil->descripcion}}</span>
                            </div>
                            <div class="nk-tb-col nk-tb-col-action">
                                <div class="dropdown">
                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        <ul class="link-list-plain">
                                            <li><a href="#">View</a></li>
                                            <li><a href="#">Invoice</a></li>
                                            <li><a href="#">Print</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
                <div class="card-inner-sm border-top text-center d-sm-none">
                    <a href="#" class="btn btn-link btn-block">See History</a>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
    </div>
</div>
<script>
function showTime(){
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var session = "AM";
    
    if(h == 0){
        h = 12;
    }
    
    if(h > 12){
        h = h - 12;
        session = "PM";
    }
    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    
    setTimeout(showTime, 1000);
    
}

showTime();
</script>
@endsection