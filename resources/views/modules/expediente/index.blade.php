@extends('layouts.app')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-tables-2@2.3.5/dist/vue-tables-2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU0WytTK6kCsmp2NFdOWAMQ8yE7tacQg&libraries=places"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/googlemaps-js-api-loader/1.16.2/index.min.js"></script> -->
<script>
    const URL_LOGIN = "{!! route('login') !!}";
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response && error.response.status == 401 || error.response.status == 419) {
                window.location.href = URL_LOGIN;
            }
            if (error.response && error.response.status == 413) {
                alert("Los archivos tienen un gran tamaño, debe configurar tu servidor para que acepte archivos de gran tamaño, considere usar archivos de prueba.")
            }
            return Promise.reject(error);
        }
    );
</script>
<style lang="scss" scoped>
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        cursor: pointer;
        position: absolute;
        width: auto;
        height: 7%;
        left: 0;
        right: 0;
    }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        background: transparent;
        cursor: pointer;
        position: absolute;
        width: auto;
        height: 7%;
        left: 0;
        right: 0;
    }
    .pointer {
        cursor: pointer;
    }
    .pac-container {
        z-index: 10000 !important; /* Ajusta el valor según sea necesario */
    }
</style>
@endpush
@section('content')

<div id="miapp" class="mt-3">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title" v-show="!ocultar">Expedientes de diposición fiscal</h3>
        </div>
        <div class="nk-block-head-content">
            <ul class="nk-block-tools g-3">
                <li class="nk-block-tools-opt">
                    <button class="btn btn-dark" v-if="ocultar" v-on:click="ocultar = !ocultar"><em class="icon ni ni-arrow-to-up"></em></button>
                    <button class="btn btn-dark" v-if="!ocultar" v-on:click="ocultar = !ocultar"><em class="icon ni ni-arrow-to-down"></em></button>
                </li>
                <li class="nk-block-tools-opt">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalADD"><em class="icon ni ni-plus"></em></button>
                </li>
            </ul>
        </div>
    </div>
    <div class="nk-block" v-show="!ocultar">
        <p><strong>Resumen de expedientes</strong></p>
        <p v-if="loadingTable"><em class="icon ni ni-loader"></em> Cargando....</p>
        <div class="row g-gs" v-if="!loadingTable">
            <div class="col-md-3">
                <div class="card card-bordered ">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-0">
                            <div class="card-title">
                                <h6 class="subtitle">Total</h6>
                            </div>
                        </div>
                        <div class="card-amount"><span class="amount" v-text="data.tableData.length"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-bordered ">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-0">
                            <div class="card-title">
                                <h6 class="subtitle">Culminados</h6>
                            </div>
                        </div>
                        <div class="card-amount"><span class="amount" v-text="expe_culminados"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-bordered ">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-0">
                            <div class="card-title">
                                <h6 class="subtitle">Pendientes</h6>
                            </div>
                        </div>
                        <div class="card-amount"><span class="amount" v-text="expe_pendientes"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nk-block" v-show="!ocultar">
        <p><strong>Expedientes</strong></p>
        <p v-if="situacion" v-text="situacion"></p>
        <div class="card card-bordered card-preview">
            <p v-if="loadingTable"><em class="icon ni ni-loader"></em> Cargando....</p>
            <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
                <div slot="get_estado" slot-scope="props">
                    <div class="d-flex justify-content-center">
                        <span class="badge badge-dim badge-pill badge-secondary badge-md" v-if="props.row.estado_id == 1 || props.row.estado_id == 0" v-text="props.row.get_estado.descripcion"></span>
                        <span class="badge badge-dim badge-pill badge-success badge-md" v-if="props.row.estado_id == 2"> Culminado</span>
                    </div>
                    <span v-if="props.row.estado_id == 1 || props.row.estado_id == 0" v-text="compareFechas(props.row.fecha_termino, calculateFechaHoy(props.row)) "></span>
                </div>
                <div slot="get_fiscal" slot-scope="props" v-if="props.row.get_fiscal.id != 1">
                    <span v-text="props.row.get_fiscal.carnet + ' ' +props.row.get_fiscal.nombres + ' ' + props.row.get_fiscal.paterno + ' ' + props.row.get_fiscal.materno + ' ' + props.row.get_fiscal.ficalia"> </span>
                </div>
                <!-- <div slot="get_fiscal_adjunto" slot-scope="props" v-if="props.row.get_fiscal_adjunto.id != 1">
                    <span v-text="props.row.get_fiscal_adjunto.carnet + ' ' +props.row.get_fiscal_adjunto.nombres + ' ' + props.row.get_fiscal_adjunto.paterno + ' ' + props.row.get_fiscal_adjunto.materno + ' ' + props.row.get_fiscal_adjunto.ficalia"> </span>
                </div> -->
                <div slot="progreso" slot-scope="props">
                    <ul class="list">
                        <li>fecha_inicio: <span v-text="props.row.fecha_inicio"></span> </li>
                        <li>fecha_termino: <span v-text="props.row.fecha_termino"></span></li>
                    </ul>
                    <div class="project-progress" v-if="props.row.estado_id == 1 || props.row.estado_id == 0">
                        <div class="progress progress-pill progress-md bg-light">
                            <div class="progress-bar" :data-progress="calculateProgressBarWidth(props.row)" :style="{ width: calculateProgressBarWidth(props.row) }"></div>
                        </div>
                    </div>
                    <div class="project-progress" v-if="props.row.estado_id == 2">
                        <div class="progress progress-pill progress-md bg-light">
                            <div class="progress-bar bg-success" data-progress="100%" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div slot="opciones" slot-scope="props">
                    <div class="btn-group dropup">
                        <a target="_blank" :href="uriExpe+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a>
                        <a v-on:click="OpenEdit(props.row, 'VER_HISTORIAL')" class="m-1 pointer" style="font-size: 22px;">
                            <em class="icon ni ni-camera-fill"></em>
                        </a>
                        <a v-if="props.row.estado_id != 2 && props.row.estado_id != 3" v-on:click="OpenEdit(props.row, 'EDITAR')" data-toggle="modal" data-target="#modalEdit" class="m-1" style="font-size: 22px;"><em class="icon ni ni-setting"></em></a>
                    </div>
                </div>
            </v-client-table>
        </div>
    </div>
    <div class="nk-block-between mt-3" v-if="ocultar">
        <h3 v-if="dataEdit"><strong>Actividades de expediente </strong><span class="text-primary small">Nro. 0000</span><span class="text-primary small" v-text="dataEdit.id"></span></h3>
        <a v-if="dataEdit" class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" target="_blank" :href="uriExpe+'?contexto='+dataEdit.id"><em class="icon ni ni-printer-fill"></em></a>
    </div>
    <div class="nk-block" v-show="ocultar">
        <div id="accordion-1" class="accordion accordion-s2 mt-3" v-if="dataEdit">
            <div class="accordion-item" v-for="(item,index) in dataEdit.get_nueva_vigilancia" :key="index">
                <a href="#" class="accordion-head" data-toggle="collapse" :data-target="'#accordion-item-' + index">
                    <div class="project-title">
                        <div class="user-avatar sq bg-purple"><span v-text="index+1"></span></div>
                        <div class="project-info">
                            <h6 class="title" v-text="item.fechaDocumento"></h6>
                        </div>
                    </div>
                    <span class="accordion-icon"></span>
                </a>
                <div class="accordion-body collapse" :id="'accordion-item-' + index" :data-parent="'#accordion-item-' + index">
                    <div class="accordion-inner">
                        <div class="card card-bordered h-100">
                            <div class="card-inner">
                                <div class="project">
                                    <div class="project-details">
                                        <div class="pricing-body">
                                            <h6 class="title">Detalle de videovigilancia</h6>
                                            <ul class="pricing-features">
                                                <li><span class="w-50">Siglas Documento</span> : <span class="ml-auto" v-text="item.siglasDocumento"></span></li>
                                                <li><span class="w-50">Asunto</span> : <span class="ml-auto" v-text="item.asunto"></span></li>
                                                <li><span class="w-50">Responde a</span> : <span class="ml-auto" v-text="item.respondea"></span></li>
                                                <li><span class="w-50">Evaluación</span> : <span class="ml-auto" v-text="item.evaluacion"></span></li>
                                                <li><span class="w-50">Conclusiones</span> : <span class="ml-auto" v-text="item.conclusiones"></span></li>
                                                <li><span class="w-50">Archivo</span> : <span class="ml-auto" v-if="item.archivo">
                                                        <a target="_blank" :href="URLASSET+'files/' + item.archivo" class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary"> Ver anexo <em class="icon ni ni-external-alt"></em></a>
                                                    </span>

                                                </li>
                                                <li><span class="w-50">Fecha creación</span> : <span class="ml-auto" v-text="item.created_at"></span></li>
                                            </ul>
                                        </div>
                                        <div class="pricing-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="title mb-0">Detalle de Actividades</h6>
                                                <button data-toggle="modal" v-on:click="nuevafechadocumentovideovigilancia.IndexActividadAdd = index" data-target="#modalFechaActividadAdd" class="btn btn-primary"><em class="icon ni ni-plus"></em></button>
                                            </div>

                                            <div v-for="(item2,index2) in item.get_nueva_vigilancia_actividad" :key="index2">
                                                <section class="mb-2 mt-3">

                                                    <h6 class="title"><span class="badge badge-pill badge-outline-light text-dark" v-text="index2+1"></span> <span v-text="item2.fechahora"></span>
                                                        <button class="btn btn-dark btn-sm mt-2 ml-2" v-on:click="modalOpen('PersonasEdit_actividad',index,index2)">+ Personas</button>
                                                        <button class="btn btn-dark btn-sm mt-2 ml-2" v-on:click="modalOpen('InmuebleEdit_actividad',index,index2)">+ Inmueble</button>
                                                        <button class="btn btn-dark btn-sm mt-2 ml-2" v-on:click="modalOpen('VehiculoEdit_actividad',index,index2)">+ Vehiculo</button>
                                                    </h6>
                                                </section>
                                                <section class="mt-3 mb-3 ml-5" v-if="item2.get_nueva_vigilancia_entidad.length > 0">

                                                    <table class="table preview-reference">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th></th>
                                                                <th class="overline-title w-300px">Entidad</th>
                                                                <th class="overline-title">Detalle</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="table" v-for="(item3,index3) in item2.get_nueva_vigilancia_entidad" :key="index3">
                                                                <td scope="row" v-text="index3+1"></td>
                                                                <td v-on:click="VerEntidadActividad(item3)">
                                                                    <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" v-if="item3.entidads_id == 1 || item3.entidads_id == 2">
                                                                        <em class="icon ni ni-user-fill"></em> <span class="mr-2" v-if="item3.get_tipo_entidad" v-text="item3.get_tipo_entidad.descripcion"></span>
                                                                    </a>

                                                                    <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" v-if="item3.entidads_id == 3">
                                                                        <em class="icon ni ni-truck"></em> <span class="mr-2" v-if="item3.get_tipo_entidad" v-text="item3.get_tipo_entidad.descripcion"></span>
                                                                    </a>

                                                                    <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" v-if="item3.entidads_id == 4">
                                                                        <em class="icon ni ni-home"></em> <span class="mr-2" v-if="item3.get_tipo_entidad" v-text="item3.get_tipo_entidad.descripcion"></span>
                                                                    </a>
                                                                </td>
                                                                <td v-text="item3.detalle"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-search-box" v-on:click="modalOpen('FechaContenidoAdd')">
            <div class="form-group">
                <div class="form-control-wrap">
                    <input type="text" class="form-control form-control-lg" placeholder="Agregar contenido..." readonly>
                    <button class="form-icon form-icon-right">
                        <em class="icon ni ni-plus-sm"></em>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalADD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Formulario de nuevo expediente</h5>
                </div>
                <div class="modal-body">
                    <h5>Expediente</h5>
                    <section>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Caso</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="dataExpe.caso">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Número</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="dataExpe.nro">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Fecha de disposición</label>
                            <div class="form-control-wrap">
                                <input type="date" class="form-control" v-model="dataExpe.fecha_disposicion">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Resumen</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="dataExpe.resumen">
                            </div>
                        </div>
                    </section>
                    <h5 class="mt-5">Calendario de inicio y termino</h5>
                    <section>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Fecha de inicio</label>
                            <div class="form-control-wrap">
                                <input type="date" class="form-control" v-model="dataExpe.fecha_inicio">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Días de Plazo</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="dataExpe.plazo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Fecha de término</label>
                            <div class="form-control-wrap">
                                <input type="date" class="form-control" v-model="dataExpe.fecha_termino">
                            </div>
                        </div>

                    </section>
                    <h5 class="mt-5">Delitos</h5>
                    <section>
                        <div class="col-sm-12 mt-3" v-if="dataDelitos.length > 0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr v-for="(item,index) in dataDelitos" :key="item.id">
                                        <td v-text="item.tipo + ' -- ' +item.subtipo + ' -- ' + item.modalidad "></td>
                                        <td v-on:click="BuscarPersonasDeleteExpediente(index,'DELITO')"> <a class="pointer"><em class="icon ni ni-trash-alt"></em></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl form-control-outlined" data-ui="xl" id="outlined-select2" v-model="selectdataDelitos" @change="BuscarPersonasAddExpediente(selectdataDelitos,'DELITO')">
                                    <option v-for="item in data_tipo_delitos" :key="item.id" :value="item" v-text="item.tipo + ' -- ' +item.subtipo + ' -- ' + item.modalidad "></option>
                                </select>
                                <label class="form-label-outlined" for="outlined-select2">Selecciona una o varias</label>
                            </div>
                        </div>
                    </section>
                    <h5 class="mt-5">Archivos Digitales</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div v-for="(item, index) in dataExpe.selectdataReferenciaVideovigilancia" :key="index">
                                <div class="card card-preview mb-2">
                                    <div class="input-group-append pointer d-flex justify-content-end">
                                        <em class="icon ni ni-trash-alt" @click="removeInput('REFERENCIA',index)"></em>
                                    </div>
                                    <div class="card-inner">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <select class="form-control" data-ui="xl" id="outlined-select2" v-model="dataExpe.selectdataReferenciaVideovigilancia[index].documentos_id">
                                                            <option v-for="item in dataTipoDocumentos" :key="item.id" :value="item.id" v-text="item.descripcion"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" v-model="dataExpe.selectdataReferenciaVideovigilancia[index].nro" placeholder="Número">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-control-wrap mt-2">
                                            <input type="text" class="form-control" v-model="dataExpe.selectdataReferenciaVideovigilancia[index].siglas" placeholder="Siglas">
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="date" class="form-control" v-model="dataExpe.selectdataReferenciaVideovigilancia[index].fecha_documento">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-control-wrap" v-if="!dataExpe.selectdataReferenciaVideovigilancia[index].pdf">
                                                        <input type="file" accept="application/pdf" class="form-control" @change="handleFileChange(index,$event)">
                                                    </div>
                                                    <p v-if="dataExpe.selectdataReferenciaVideovigilancia[index].pdf" v-text="dataExpe.selectdataReferenciaVideovigilancia[index].pdfName"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="form-control-wrap mt-2">
                                            <input type="text" class="form-control " v-model="dataExpe.selectdataReferenciaVideovigilancia[index].observaciones" placeholder="Observaciones">
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-dark mt-2" @click="addInput('REFERENCIA')">Agregar más cuadros de referencia</button>
                        </div>
                    </section>
                    <h5 class="mt-5">Tipo de videovigilancia</h5>
                    <section>
                        <div class="col-sm-12 mt-3" v-if="dataExpe.selectdataTipoVideovigilancia.length > 0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr v-for="(item,index) in dataExpe.selectdataTipoVideovigilancia" :key="item.id">
                                        <td v-text="item.descripcion"></td>
                                        <td v-on:click="selectdataTipoVideovigilanciaDelete(index)"> <a class="pointer"><em class="icon ni ni-trash-alt"></em></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl form-control-outlined" data-ui="xl" id="outlined-select2" v-model="selectdataTipoVideovigilancia" @change="selectdataTipoVideovigilanciaAdd()">
                                    <option v-for="item in dataTipoVideovigilancia" :key="item.id" :value="item" v-text="item.descripcion"></option>
                                </select>
                                <label class="form-label-outlined" for="outlined-select2">Selecciona una o varias</label>
                            </div>
                        </div>
                        <div class="mt-2 mb-2 ml-2"><a class="form-note pointer" v-on:click="modalOpen('TipoVideoVigilanciaEdit')"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más Tipo de videovigilancia a la base de datos.</a></div>

                    </section>
                    <h5 class="mt-5">Objeto de videovigilancia</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div v-for="(item, index) in dataExpe.selectdataObjetoVideovigilancia" :key="index">
                                <div class="form-control-wrap mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="dataExpe.selectdataObjetoVideovigilancia[index]">
                                        <div class="input-group-append pointer" @click="removeInput('OBJECTOS',index)">
                                            <span class="input-group-text" id="basic-addon2"><em class="icon ni ni-trash-alt"></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-dark mt-2" v-on:click="modalOpen('PersonasEdit')">+ Personas</button>
                            <button class="btn btn-dark mt-2" v-on:click="modalOpen('InmuebleEdit')">+ Inmueble</button>
                            <button class="btn btn-dark mt-2" v-on:click="modalOpen('VehiculoEdit')">+ Vehiculo</button>
                        </div>
                        <table class="table mt-3" v-if="dataPersonas.length > 0">
                            <thead>
                                <tr>
                                    <th scope="col">Nacionalidad</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dataPersonas" :key="index" :value="item.id">
                                    <td v-text="item.get_tipo_nacionalidad.descripcion"></td>
                                    <td>
                                        <span v-text="item.nombres"></span>,
                                        <span v-text="item.paterno"></span>
                                        <span v-text="item.materno"></span>
                                    </td>
                                    <td v-on:click="BuscarPersonasDeleteExpediente(index,'PERSONAS')"> <a class="pointer"><em class="icon ni ni-trash-alt"></em></a></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table mt-3" v-if="dataInmueble.length > 0">
                            <thead>
                                <tr>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dataInmueble" :key="index" :value="item.id">
                                    <td v-text="item.get_tipo_inmueble.descripcion"></td>
                                    <td>
                                        <span v-text="item.direccion"></span>,
                                        <span v-text="item.departamento"></span>
                                        <span v-text="item.provincia"></span>,
                                        <span v-text="item.distrito"></span>,
                                    </td>
                                    <td v-on:click="BuscarPersonasDeleteExpediente(index,'INMUEBLE')"> <a class="pointer"><em class="icon ni ni-trash-alt"></em></a></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table mt-3" v-if="dataVehiculo.length > 0">
                            <thead>
                                <tr>
                                    <th scope="col">placa</th>
                                    <th scope="col">marca</th>
                                    <th scope="col">color</th>
                                    <th scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dataVehiculo" :key="index" :value="item.id">
                                    <td v-text="item.placa"></td>
                                    <td v-text="item.marca"></td>
                                    <td v-text="item.color"></td>
                                    <td v-on:click="BuscarPersonasDeleteExpediente(index,'VEHICULO')"> <a class="pointer"><em class="icon ni ni-trash-alt"></em></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                    <h5 class="mt-5">Fiscales</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <label class="form-label" for="outlined-select">Fiscal Responsable</label>

                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl" id="outlined-select" v-model="dataExpe.fiscal_responsable_id">
                                    <option v-for="item in dataFiscales" :key="item.id" :value="item.id" v-text="item.carnet + ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno + ' ' + item.ficalia"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <label class="form-label" for="outlined-select2">Fiscal Asistente</label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl" data-ui="xl" id="outlined-select2" v-model="dataExpe.fiscal_asistente_id">
                                    <option v-for="item in dataFiscales" :key="item.id" :value="item.id" v-text="item.carnet + ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno + ' ' + item.ficalia"></option>
                                </select>

                            </div>
                        </div>
                        <div class="mt-2 mb-2 ml-2"><a class="form-note pointer" v-on:click="modalOpen('FiscalEdit')"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más fiscales a la base de datos.</a></div>
                    </section>
                    <h5 class="mt-5">Oficial a cargo</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl" id="outlined-select" v-model="dataExpe.oficial_acargo_id">
                                    <option v-for="item in data_policia" :key="item.id" :value="item.id" v-text="item.carnet  + ' ' + item.get_grado.descripcion+ ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno"></option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2 mb-2 ml-2"><a class="form-note pointer" v-on:click="modalOpen('OficialAdd')"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más oficiales a la base de datos.</a></div>
                    </section>
                    <h5 class="mt-5">Observaciones</h5>
                    <section>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="dataExpe.observaciones">
                            </div>
                        </div>
                    </section>
                    <div class="col-sm-12 mt-3">
                        <button class="btn btn-primary" v-if="!loadingModal" v-on:click="Grabar">Registrar</button>
                        <p v-if="loadingModal"><em class="icon ni ni-loader"></em> Cargando....</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Expediente</h5>
                </div>
                <div class="modal-body">
                    <ul class="pricing-features" v-if="dataEdit.id">
                        <li><span class="w-50">Caso</span> : <span class="ml-auto" v-text="dataEdit.caso"></span></li>
                        <li><span class="w-50">Nro</span> : <span class="ml-auto" v-text="dataEdit.nro"></span></li>
                        <li><span class="w-50">Resumen</span> : <span class="ml-auto" v-text="dataEdit.resumen"></span></li>
                        <li><span class="w-50">Observaciones </span> : <span class="ml-auto" v-text="dataEdit.observaciones"></span></li>
                        <li><span class="w-50">Días plazo </span> : <span class="ml-auto" v-text="dataEdit.plazo"></span></li>
                    </ul>
                    <div class="form-group mt-2">
                        <label class="form-label" for="default-01">Asunto</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" v-model="dataFinish.asunto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-01">Fecha documento</label>
                        <div class="form-control-wrap">
                            <input type="date" class="form-control" v-model="dataFinish.fecha_documento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-01">Resultado Final</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" v-model="dataFinish.resultadoFinal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-01">Destino</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" v-model="dataFinish.destino">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-01">Tipo de archivo digital</label>
                        <div class="form-control-wrap">
                            <select class="form-control" data-ui="xl" id="outlined-select2" v-model="dataFinish.documentos_id">
                                <option v-for="item in dataTipoDocumentos" :key="item.id" :value="item.id" v-text="item.descripcion"></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="default-01">Archivo principal</label>
                        <div class="form-control-wrap">
                            <input type="file" accept="application/pdf" class="form-control" @change="handleFileChange('',$event,'_FINISH_EXPEDIENTE')">
                            <p v-if="dataFinish.archivo" v-text="dataFinish.archivo_NAME"></p>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-3">
                        <div v-for="(item, index) in dataFinish.files" :key="index">
                            <div class="card card-preview ">
                                <div class="input-group-append pointer d-flex justify-content-end">
                                    <em class="icon ni ni-trash-alt" @click="removeInput('ARCHIVOS_FINISH',index)"></em>
                                </div>
                                <div class="card-inner">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <select class="form-control" data-ui="xl" id="outlined-select2" v-model="item.ta_id">
                                                    <option v-for="item in dataTipoDocumentos" :key="item.id" :value="item.id" v-text="item.descripcion"></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="form-group">
                                            <div class="form-control-wrap" v-if="!item.pdf">
                                                <input type="file" class="form-control" @change="handleFileChange(index,$event,'_NEWFILE_ENTIDAD')">
                                            </div>
                                            <p v-if="item.pdf" v-text="item.pdfName"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-dark mt-2" @click="addInput('ARCHIVOS_FINISH')">Agregar Archivos</button>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-group">
                            <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalFINISH')">Registrar</button>
                            <p v-if="loadingPersonas">Cargando....</p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalADDFiscal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Fiscales</h5>
                </div>
                <div class="modal-body">
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Carnet</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.carnet" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">DNI</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control form-control-lg" v-model="dataFiscalAdd.dni" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Nombres</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.nombres" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Paterno</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.paterno" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Materno</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.materno" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Celular</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.celular" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Correo</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.correo" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Procedencia</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.procedencia" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Ficalia</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.ficalia" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Despacho</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.despacho" />
                        </div>
                    </section>
                    <section>
                        <div class="form-label-group mt-2">
                            <label class="form-label">Ubigeo</label>
                        </div>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg" v-model="dataFiscalAdd.ubigeo" />
                        </div>
                    </section>

                    <div class="form-group">
                        <button class="btn btn-primary" v-if="!loadingModalNuevoFiscal" v-on:click="GrabarNuevoFiscal">Registrar</button>
                        <p v-if="loadingModalNuevoFiscal"><em class="icon ni ni-loader"></em> Cargando....</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalADDTipoVideoVigilancia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Tipo de VideoVigilancia</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-tipo-VideoVigilancia" v-model="dataTipoVideovigilanciaAdd"><label class="form-label-outlined" for="outlined-tipo-VideoVigilancia">Tipo VideoVigilancia</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <button class="btn btn-primary" v-if="!loadingModalTipoVideoVigilancia" v-on:click="GrabarTipoVideoVigilancia">Registrar</button>
                        <p v-if="loadingModalTipoVideoVigilancia"><em class="icon ni ni-loader"></em> Cargando....</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPersonas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross"></em> </a>
                <div class="modal-body">
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-dni-search"><em class="icon ni ni-search"></em> PERUANO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-ext-search"><em class="icon ni ni-search"></em> EXTRANJERO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-ext-search-nacionalidad"><em class="icon ni ni-flag"></em> NOMBRES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-dni-reg"><em class="icon ni ni-plus-round"></em> PERUANOS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-ext-reg"><em class="icon ni ni-plus-c"></em> EXTRANJERO</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-dni-search">
                            <div class="form-label-group">
                                <label class="form-label">Número de DNI</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" v-model="dataPersonasAdd.documento" class="form-control form-control-lg" placeholder="Ingresa el número" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="BuscarPersonas('_get_persona_peru')">Buscar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-ext-search">
                            <div class="form-group">
                                <label class="form-label" for="default-06">Tipo de documento de identidad</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.documento_id">
                                            <option v-for="(item, index) in data_documento_identidad" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Número de documento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" v-model="dataPersonasAdd.documento" class="form-control form-control-lg" placeholder="Ingresa el número" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="BuscarPersonas('_get_persona_extranjero')">Buscar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-ext-search-nacionalidad">
                            <div class="form-label-group mt-2">
                                <label class="form-label">Nombres</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el nombres" v-model="dataPersonasAdd.nombres" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Apellido paterno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Apellido paterno" v-model="dataPersonasAdd.paterno" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Apellido materno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Apellido materno" v-model="dataPersonasAdd.materno" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="BuscarPersonas('_get_persona_nacionalidad_nombres')">Buscar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>

                            <table class="table" v-if="dataPersonasSearch.length > 0">
                                <thead>
                                    <tr>
                                        <th scope="col">Nacionalidad</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in dataPersonasSearch" :key="index" :value="item.id">
                                        <td v-text="item.get_tipo_nacionalidad.descripcion"></td>
                                        <td>
                                            <span v-text="item.nombres"></span>,
                                            <span v-text="item.paterno"></span>
                                            <span v-text="item.materno"></span>
                                        </td>
                                        <td v-if="!cambiarModoActividades"><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item, 'PERSONAS')">Agregar</button></td>
                                        <td v-if="cambiarModoActividades"><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item, 'PERSONAS_dfnva')">Agregar</button></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane" id="tab-dni-reg">
                            <div class="form-label-group mt-2">
                                <label class="form-label">documento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el documento" v-model="dataPersonasAdd.documento" />
                            </div>

                            <div class="form-label-group mt-2">
                                <label class="form-label">nombres</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el nombres" v-model="dataPersonasAdd.nombres" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">paterno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el paterno" v-model="dataPersonasAdd.paterno" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">materno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el materno" v-model="dataPersonasAdd.materno" />
                            </div>
                            <label class="form-label mt-2">Estado Civil</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataPersonasAdd.estado_civil">
                                        <option value="CASADO">CASADO</option>
                                        <option value="SOLTERO">SOLTERO</option>
                                        <option value="DIVORCIADO">DIVORCIADO</option>
                                        <option value="VIUDO">VIUDO</option>
                                    </select>
                                </div>
                            </div>
                            <label class="form-label mt-2">sexo</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataPersonasAdd.sexo">
                                        <option value="MASCULITNO">MASCULITNO</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="date" class="form-control form-control-xl form-control-outlined" id="outlined-date-nacimiento1" v-model="dataPersonasAdd.fecha_nacimiento"><label class="form-label-outlined" for="outlined-date-nacimiento1">Fecha de nacimiento</label>
                                </div>
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">ubigeo nacimiento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el ubigeo nacimiento" v-model="dataPersonasAdd.ubigeo_nacimiento" />
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">departamento nacimiento</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.departamento_nacimiento" @change="handleChangeUbigeoNaci('DEP')">
                                            <option v-for="(item, index) in data_dep" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">provincia nacimiento</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.provincia_nacimiento" @change="handleChangeUbigeoNaci('PROV')">
                                            <option v-for="(item, index) in data_filtro.nacimiento.provincia_nacimiento" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">distrito nacimiento</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.distrito_nacimiento">
                                            <option v-for="(item, index) in data_filtro.nacimiento.distrito_nacimiento" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-label-group mt-2">
                                <label class="form-label">lugar nacimiento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el lugar nacimiento" v-model="dataPersonasAdd.lugar_nacimiento" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">ubigeo domicilio</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el ubigeo domicilio" v-model="dataPersonasAdd.ubigeo_domicilio" />
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">departamento domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.departamento_domicilio" @change="handleChangeUbigeoNaci('DEP_DOM')">
                                            <option v-for="(item, index) in data_dep" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">provincia domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.provincia_domicilio" @change="handleChangeUbigeoNaci('PROV_DOM')">
                                            <option v-for="(item, index) in data_filtro.domicilio.provincia_domicilio" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">distrito domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.distrito_domicilio">
                                            <option v-for="(item, index) in data_filtro.domicilio.distrito_domicilio" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">lugar domicilio</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el lugar domicilio" v-model="dataPersonasAdd.lugar_domicilio" />
                            </div>

                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarPersonas('PERUANOS')">Registrar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="tab-ext-reg">
                            <div class="form-group mt-2">
                                <label class="form-label">Nacionalidad</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.nacionalidad_id">
                                            <option v-for="(item, index) in data_nacionalidad" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-2">
                                <label class="form-label">Tipo documento</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.documento_id">
                                            <option v-for="(item, index) in data_documento_identidad" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-label-group">
                                <label class="form-label">documento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el documento" v-model="dataPersonasAdd.documento" />
                            </div>

                            <div class="form-label-group mt-2">
                                <label class="form-label">nombres</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el nombres" v-model="dataPersonasAdd.nombres" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">paterno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el paterno" v-model="dataPersonasAdd.paterno" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">materno</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el materno" v-model="dataPersonasAdd.materno" />
                            </div>
                            <label class="form-label mt-2">Estado Civil</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataPersonasAdd.estado_civil">
                                        <option value="CASADO">CASADO</option>
                                        <option value="SOLTERO">SOLTERO</option>
                                        <option value="DIVORCIADO">DIVORCIADO</option>
                                        <option value="VIUDO">VIUDO</option>
                                    </select>
                                </div>
                            </div>
                            <label class="form-label mt-2">sexo</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataPersonasAdd.sexo">
                                        <option value="MASCULITNO">MASCULITNO</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="date" class="form-control form-control-xl form-control-outlined" id="outlined-date-nacimiento1" v-model="dataPersonasAdd.fecha_nacimiento"><label class="form-label-outlined" for="outlined-date-nacimiento1">Fecha de nacimiento</label>
                                </div>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el distrito nacimiento" v-model="dataPersonasAdd.distrito_nacimiento" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">lugar nacimiento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el lugar nacimiento" v-model="dataPersonasAdd.lugar_nacimiento" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">ubigeo domicilio</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el ubigeo domicilio" v-model="dataPersonasAdd.ubigeo_domicilio" />
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">departamento domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.departamento_domicilio" @change="handleChangeUbigeoNaci('DEP_DOM')">
                                            <option v-for="(item, index) in data_dep" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">provincia domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.provincia_domicilio" @change="handleChangeUbigeoNaci('PROV_DOM')">
                                            <option v-for="(item, index) in data_filtro.domicilio.provincia_domicilio" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label">distrito domicilio</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataPersonasAdd.distrito_domicilio">
                                            <option v-for="(item, index) in data_filtro.domicilio.distrito_domicilio" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">lugar domicilio</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el lugar domicilio" v-model="dataPersonasAdd.lugar_domicilio" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarPersonas('EXTRANJEROS')">Registrar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInmueble" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Inmueble</h5>
                </div>
                <div class="modal-body">
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-inmuenle-search"><em class="icon ni ni-search"></em> BUSCAR</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-inmuenle-reg"><em class="icon ni ni-plus-c"></em> REGISTRO</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-inmuenle-search">
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo Inmueble</label>
                            </div>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataInmuebleAdd.inmuebles_id">
                                        <option v-for="(item, index) in data_inmueble" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Dirección</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la direccion" v-model="dataInmuebleAdd.direccion" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Departamento</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Departamento" v-model="dataInmuebleAdd.departamento" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Provincia</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Provincia" v-model="dataInmuebleAdd.provincia" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Distrito</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Distrito" v-model="dataInmuebleAdd.distrito" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Referencia</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Referencia" v-model="dataInmuebleAdd.referencia" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">

                                <div class="form-group">
                                    <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="BuscarPersonas('_get_domicilio')">Buscar</button>
                                    <p v-if="loadingPersonas">Cargando....</p>
                                </div>
                            </div>

                            <table class="table" v-if="dataInmuebleSearch.length > 0">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in dataInmuebleSearch" :key="index" :value="item.id">
                                        <td v-text="item.get_tipo_inmueble.descripcion"></td>
                                        <td>
                                            <span v-text="item.direccion"></span>,
                                            <span v-text="item.departamento"></span>,
                                            <span v-text="item.provincia"></span>,
                                            <span v-text="item.distrito"></span>,
                                            <span v-text="item.referencia"></span>
                                        </td>
                                        <td v-if="!cambiarModoActividades"><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item, 'INMUEBLE')">Agregar</button></td>
                                        <td v-if="cambiarModoActividades"><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item, 'INMUEBLE_dfnva')">Agregar</button></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane" id="tab-inmuenle-reg">
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo Inmueble</label>
                            </div>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataInmuebleAdd.inmuebles_id">
                                        <option v-for="(item, index) in data_inmueble" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Departamento</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataInmuebleAdd.departamento" @change="handleChangeUbigeoNaci('DEP','INMUEBLE')">
                                            <option v-for="(item, index) in data_dep" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Provincia</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataInmuebleAdd.provincia" @change="handleChangeUbigeoNaci('PROV','INMUEBLE')">
                                            <option v-for="(item, index) in data_filtro.nacimiento.provincia_nacimiento" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Distrito</label>
                                <div class="form-control-wrap ">
                                    <div class="form-control-select">
                                        <select class="form-control" v-model="dataInmuebleAdd.distrito">
                                            <option v-for="(item, index) in data_filtro.nacimiento.distrito_nacimiento" :key="index" :value="item.opcion" v-text="item.opcion"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-label-group mt-2">
                                <label class="form-label">Ubicación</label>
                                <input id="pac-input" class="controls form-control form-control-lg" type="text" placeholder="Buscar lugar">

                            </div>
                            <div class="form-control-wrap">
                                <div id="mapDiv" style="height: 400px;"></div>
                            </div>

                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el direccion" v-model="dataInmuebleAdd.direccion" />
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el latitud" v-model="dataInmuebleAdd.latitud" />
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el longitud" v-model="dataInmuebleAdd.longitud" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Pisos</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="pisos..." v-model="dataInmuebleAdd.pisos" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Color exterior</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="color exterior..." v-model="dataInmuebleAdd.color_exterior" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Estado conservacion</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Estado conservacion..." v-model="dataInmuebleAdd.estado_conservacion" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Caracteristicas especiales</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Caracteristicas especiales..." v-model="dataInmuebleAdd.caracteristicas_especiales" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">Referencia</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Referencia..." v-model="dataInmuebleAdd.referencia" />
                            </div>
                            <div class="form-label-group mt-2">
                                <label class="form-label">observaciones</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Observaciones..." v-model="dataInmuebleAdd.observaciones" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingSarchInmueble" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarInmueble()">Registrar</button>
                                    <p v-if="loadingSarchInmueble">Cargando....</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel7" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Vehiculo</h5>
                </div>
                <div class="modal-body">
                    <ul class="nk-nav nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-vehiculo-search"><em class="icon ni ni-search"></em> BUSCAR</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-vehiculo-reg"><em class="icon ni ni-plus-c"></em> REGISTRO</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-vehiculo-search">
                            <div class="form-label-group mt-2">
                                <label class="form-label">Placa</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Placa" v-model="dataVehiculoAdd.placa" />
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingSarchInmueble" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarVehiculo('Search')">Buscar</button>
                                    <p v-if="loadingSarchInmueble">Cargando....</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-vehiculo-reg">
                            <div class="form-label-group mt-2">
                                <label class="form-label">Placa</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Placa" v-model="dataVehiculoAdd.placa" />
                            </div>

                            <!-- Serie -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Serie</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Serie" v-model="dataVehiculoAdd.serie" />
                            </div>

                            <!-- Número de Motor -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Número de Motor</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Número de Motor" v-model="dataVehiculoAdd.numero_motor" />
                            </div>

                            <!-- Color -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Color</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Color" v-model="dataVehiculoAdd.color" />
                            </div>

                            <!-- Marca -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Marca</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Marca" v-model="dataVehiculoAdd.marca" />
                            </div>

                            <!-- Modelo -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Modelo</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Modelo" v-model="dataVehiculoAdd.modelo" />
                            </div>

                            <!-- Año -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Año</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Año" v-model="dataVehiculoAdd.ano" />
                            </div>

                            <!-- Tipo de Carrocería -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo de Carrocería</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Tipo de Carrocería" v-model="dataVehiculoAdd.tipo_carroceria" />
                            </div>

                            <!-- VIN -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">VIN</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el VIN" v-model="dataVehiculoAdd.vin" />
                            </div>

                            <!-- Tipo de Motor -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo de Motor</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Tipo de Motor" v-model="dataVehiculoAdd.tipo_motor" />
                            </div>

                            <!-- Cilindrada del Motor -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Cilindrada del Motor</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Cilindrada del Motor" v-model="dataVehiculoAdd.cilindrada_motor" />
                            </div>

                            <!-- Tipo de Combustible -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo de Combustible</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Tipo de Combustible" v-model="dataVehiculoAdd.tipo_combustible" />
                            </div>

                            <!-- Tipo de Transmisión -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo de Transmisión</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Tipo de Transmisión" v-model="dataVehiculoAdd.tipo_transmision" />
                            </div>

                            <!-- Tipo de Tracción -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Tipo de Tracción</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Tipo de Tracción" v-model="dataVehiculoAdd.tipo_traccion" />
                            </div>

                            <!-- Kilometraje -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Kilometraje</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Kilometraje" v-model="dataVehiculoAdd.kilometraje" />
                            </div>

                            <!-- Placa Anterior -->
                            <div class="form-label-group mt-2">
                                <label class="form-label">Placa Anterior</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Placa Anterior" v-model="dataVehiculoAdd.placaanterior" />
                            </div>

                            <label class="form-label mt-2">Estado Vehiculo</label>
                            <div class="form-control-wrap ">
                                <div class="form-control-select">
                                    <select class="form-control" v-model="dataVehiculoAdd.estado_vehiculo">
                                        <option value="EN_CIRCULACION">EN_CIRCULACION</option>
                                        <option value="EN_REPARACION">EN_REPARACION</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-3 mb-5">
                                <div class="form-group">
                                    <button v-if="!loadingSarchInmueble" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarVehiculo()">Registrar</button>
                                    <p v-if="loadingSarchInmueble">Cargando....</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalOficialAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross"></em> </a>
                <div class="modal-body">
                    <div class="form-label-group mt-2">
                        <label class="form-label">Carnet</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Carnet" v-model="dataPoliciaAdd.carnet" />
                    </div>

                    <div class="form-label-group mt-2">
                        <label class="form-label">DNI</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el DNI" v-model="dataPoliciaAdd.dni" />
                    </div>

                    <div class="form-label-group mt-2">
                        <label class="form-label">Nombres</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa los Nombres" v-model="dataPoliciaAdd.nombres" />
                    </div>

                    <div class="form-label-group mt-2">
                        <label class="form-label">Apellido Paterno</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Apellido Paterno" v-model="dataPoliciaAdd.paterno" />
                    </div>

                    <div class="form-label-group mt-2">
                        <label class="form-label">Apellido Materno</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Apellido Materno" v-model="dataPoliciaAdd.materno" />
                    </div>
                    <div class="form-label-group mt-2">
                        <label class="form-label">Situación</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa la Situación" v-model="dataPoliciaAdd.situacion" />
                    </div>
                    <div class="form-label-group mt-2">
                        <label class="form-label">Celular</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el celular" v-model="dataPoliciaAdd.celular" />
                    </div>
                    <div class="form-label-group mt-2">
                        <label class="form-label">Correo electrónico</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el correo" v-model="dataPoliciaAdd.correo" />
                    </div>
                    <div class="form-group mt-2">
                        <label class="form-label">Grado</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control" v-model="dataPoliciaAdd.grado_id">
                                    <option v-for="(item, index) in data_grado" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label class="form-label">Unidad</label>
                        <div class="form-control-wrap ">
                            <div class="form-control-select">
                                <select class="form-control" v-model="dataPoliciaAdd.unidad_id">
                                    <option v-for="(item, index) in data_unidad" :key="index" :value="item.id" v-text="item.descripcion"></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-3 mb-5">
                        <div class="form-group">
                            <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarPolicia()">Registrar</button>
                            <p v-if="loadingPersonas">Cargando....</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFechaContenidoAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel9" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Documento de Actividad</h5>
                </div>
                <div class="modal-body">
                    <div class="card-inner">

                        <div class="form-group">
                            <label class="form-label" for="default-01">Tipo de documento</label>
                            <div class="form-control-wrap">
                                <select class="form-control" data-ui="xl" id="outlined-select2" v-model="nuevafechadocumentovideovigilancia.documentos_id">
                                    <option v-for="item in dataTipoDocumentos" :key="item.id" :value="item.id" v-text="item.descripcion"></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="default-01">Siglas</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="nuevafechadocumentovideovigilancia.siglasDocumento" placeholder="Siglas">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Nro documento</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="nuevafechadocumentovideovigilancia.numeroDocumento" placeholder="Número">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="form-label" for="default-01">Fecha documento</label>
                            <div class="form-control-wrap">
                                <input type="date" class="form-control" v-model="nuevafechadocumentovideovigilancia.fechaDocumento" placeholder="fecha Documento...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="default-01">Asunto</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control no-resize" v-model="nuevafechadocumentovideovigilancia.asunto" ></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="default-01">Evaluación</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="nuevafechadocumentovideovigilancia.evaluacion" placeholder="evaluacion...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Responde a</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" v-model="nuevafechadocumentovideovigilancia.respondea" placeholder="responde a...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="default-01">conclusiones</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control no-resize" v-model="nuevafechadocumentovideovigilancia.conclusiones"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default-01">Anexo - Documento Digital</label>
                            <div class="form-control-wrap">
                                <p v-if="nuevafechadocumentovideovigilancia.pdf" v-text="nuevafechadocumentovideovigilancia.pdfName"></p>
                                <div class="form-control-wrap">
                                    <input type="file" accept="application/pdf" class="form-control" @change="handleFileChange('',$event,'_NUEVAFVV')">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3 mb-5">
                            <div class="form-group">
                                <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalNuevaVigilancia')">Registrar</button>
                                <p v-if="loadingPersonas">Cargando....</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFechaActividadAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel10" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fecha y hora de actividades</h5>
                </div>
                <div class="modal-body">
                    <div class="card-inner">
                        <div class="form-group">
                            <label class="form-label" for="default-01">Fecha y hora de obtención</label>
                            <div class="form-control-wrap">
                                <input type="datetime-local" class="form-control" v-model="nuevafechadocumentovideovigilancia.FechaActividadAdd">
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalNuevaVigilanciaActividad')">Registrar</button>
                                <p v-if="loadingPersonas">Cargando....</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEntidadActividadAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel11" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Entidad de actividad de videovigilancia</h5>
                </div>
                <div class="modal-body" v-if="dfnva_persona.id">
                    <div class="card-inner">
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li v-if="dfnva_persona.get_tipo_nacionalidad"><span class="w-50">Nacionalidad</span> : <span class="ml-auto" v-text="dfnva_persona.get_tipo_nacionalidad.descripcion"></span></li>
                                <li v-if="dfnva_persona.get_tipo_documento_identidad"><span class="w-50">Documento</span> : <span class="ml-auto" v-text="dfnva_persona.get_tipo_documento_identidad.descripcion"></span></li>
                                <li><span class="w-50">Nombres</span> : <span class="ml-auto" v-text="dfnva_persona.nombres"></span></li>
                                <li><span class="w-50">Apellido Paterno</span> : <span class="ml-auto" v-text="dfnva_persona.paterno"></span></li>
                                <li><span class="w-50">Apellido Materno</span> : <span class="ml-auto" v-text="dfnva_persona.materno"></span></li>
                                <li><span class="w-50">Sexo </span> : <span class="ml-auto" v-text="dfnva_persona.sexo"></span></li>
                            </ul>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Detalle</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="dfnva_persona.detalle">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div v-for="(item, index) in dfnva_persona.get_nueva_vigilancia_archivo" :key="index">
                                <div class="card card-preview ">
                                    <!-- <div class="input-group-append pointer d-flex justify-content-end">
                                        <em class="icon ni ni-trash-alt" @click="removeInput('REFERENCIA',index)"></em>
                                    </div> -->
                                    <div class="card-inner">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <select class="form-control" data-ui="xl" id="outlined-select2" v-model="item.ta_id">
                                                        <option v-for="item2 in data_tipo_contenido" :key="item2.id" :value="item2.id" v-text="item2.descripcion"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group">
                                                <div class="form-control-wrap" v-if="!item.pdf">
                                                    <input type="file" class="form-control" @change="handleFileChange(index,$event,'_NEWFILE_ENTIDAD')">
                                                </div>
                                                <p v-if="item.pdf" v-text="item.pdfName"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-dark mt-2" @click="addInput('ARCHIVOS_DIGITALES_ENTIDADES')">Agregar Archivos</button>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalNuevaVigilanciaEntidad')">Registrar</button>
                                <p v-if="loadingPersonas">Cargando....</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-body" v-if="dfnva_vehiculo.id">
                    <div class="card-inner">
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li><span class="w-50">placa</span> : <span class="ml-auto" v-text="dfnva_vehiculo.placa"></span></li>
                                <li><span class="w-50">color</span> : <span class="ml-auto" v-text="dfnva_vehiculo.color"></span></li>
                                <li><span class="w-50">marca</span> : <span class="ml-auto" v-text="dfnva_vehiculo.marca"></span></li>
                                <li><span class="w-50">modelo </span> : <span class="ml-auto" v-text="dfnva_vehiculo.modelo"></span></li>
                            </ul>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Detalle</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="dfnva_vehiculo.detalle">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div v-for="(item, index) in dfnva_vehiculo.get_nueva_vigilancia_archivo" :key="index">
                                <div class="card card-preview ">
                                    <div class="card-inner">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <select class="form-control" data-ui="xl" id="outlined-select2" v-model="item.ta_id">
                                                        <option v-for="item2 in data_tipo_contenido" :key="item2.id" :value="item2.id" v-text="item2.descripcion"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group">
                                                <div class="form-control-wrap" v-if="!item.pdf">
                                                    <input type="file" class="form-control" @change="handleFileChange(index,$event,'_NEWFILE_ENTIDA_VEHICULO')">
                                                </div>
                                                <p v-if="item.pdf" v-text="item.pdfName"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-dark mt-2" @click="addInput('ARCHIVOS_DIGITALES_ENTIDADES_vehiculo')">Agregar Archivos</button>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalNuevaVigilanciaEntidad')">Registrar</button>
                                <p v-if="loadingPersonas">Cargando....</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body" v-if="dfnva_inmueble.id">
                    <div class="card-inner">
                        <div class="pricing-body">
                            <ul class="pricing-features">
                                <li v-if="dfnva_inmueble.get_tipo_inmueble"><span class="w-50">Tipo</span> : <span class="ml-auto" v-text="dfnva_inmueble.get_tipo_inmueble.descripcion"></span></li>
                                <li><span class="w-50">Dirección</span> : <span class="ml-auto" v-text="dfnva_inmueble.direccion"></span></li>
                                <li><span class="w-50">Departamento</span> : <span class="ml-auto" v-text="dfnva_inmueble.departamento"></span></li>
                                <li><span class="w-50">Provincia</span> : <span class="ml-auto" v-text="dfnva_inmueble.provincia"></span></li>
                                <li><span class="w-50">Distrito </span> : <span class="ml-auto" v-text="dfnva_inmueble.distrito"></span></li>
                            </ul>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Detalle</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="dfnva_inmueble.detalle">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div v-for="(item, index) in dfnva_inmueble.get_nueva_vigilancia_archivo" :key="index">
                                <div class="card card-preview ">
                                    <div class="card-inner">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <select class="form-control" data-ui="xl" id="outlined-select2" v-model="item.ta_id">
                                                        <option v-for="item2 in data_tipo_contenido" :key="item2.id" :value="item2.id" v-text="item2.descripcion"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group">
                                                <div class="form-control-wrap" v-if="!item.pdf">
                                                    <input type="file" class="form-control" @change="handleFileChange(index,$event,'_NEWFILE_ENTIDA_INMUEBLE')">
                                                </div>
                                                <p v-if="item.pdf" v-text="item.pdfName"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-dark mt-2" @click="addInput('ARCHIVOS_DIGITALES_ENTIDADES_inmueble')">Agregar Archivos</button>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-group">
                                <button v-if="!loadingPersonas" class="btn btn-lg btn-primary btn-block" v-on:click="GrabarActividad('_DisposicionFiscalNuevaVigilanciaEntidad')">Registrar</button>
                                <p v-if="loadingPersonas">Cargando....</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalVerEntidadActividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel11" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Entidad de actividad de videovigilancia</h5>
                </div>
                <div class="modal-body">
                    <div class="card-inner">
                        <div class="pricing-body">
                            <ul class="pricing-features" v-if="dfnva_persona.id">
                                <li>
                                    <div class="row g-gs">
                                        <div class="col-lg-6">
                                            <img :style="{ width: '100%' }" v-bind:src="'data:image/jpeg;base64,' + dfnva_persona.foto" alt="Imagen">
                                        </div>
                                        <div class="col-lg-6">
                                            <img :style="{ width: '100%' }" v-bind:src="'data:image/jpeg;base64,' + dfnva_persona.firma" alt="Imagen">
                                        </div>
                                    </div>
                                </li>
                                <li v-if="dfnva_persona.get_tipo_nacionalidad"><span class="w-50">Nacionalidad</span> : <span class="ml-auto" v-text="dfnva_persona.get_tipo_nacionalidad.descripcion"></span></li>
                                <li v-if="dfnva_persona.get_tipo_documento_identidad"><span class="w-50">Identificación</span> : <span class="ml-auto" v-text="dfnva_persona.get_tipo_documento_identidad.descripcion"></span></li>
                                <li><span class="w-50">Documento</span> : <span class="ml-auto" v-text="dfnva_persona.documento"></span></li>
                                <li><span class="w-50">Nombres</span> : <span class="ml-auto" v-text="dfnva_persona.nombres"></span></li>
                                <li><span class="w-50">Apellido Paterno</span> : <span class="ml-auto" v-text="dfnva_persona.paterno"></span></li>
                                <li><span class="w-50">Apellido Materno</span> : <span class="ml-auto" v-text="dfnva_persona.materno"></span></li>
                                <li><span class="w-50">Sexo </span> : <span class="ml-auto" v-text="dfnva_persona.sexo"></span></li>

                                <li><span class="w-50">estado_civil </span> : <span class="ml-auto" v-text="dfnva_persona.estado_civil"></span></li>
                                <li><span class="w-50">fecha_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.fecha_nacimiento"></span></li>
                                <li><span class="w-50">ubigeo_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.ubigeo_nacimiento"></span></li>
                                <li><span class="w-50">departamento_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.departamento_nacimiento"></span></li>
                                <li><span class="w-50">provincia_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.provincia_nacimiento"></span></li>
                                <li><span class="w-50">distrito_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.distrito_nacimiento"></span></li>
                                <li><span class="w-50">lugar_nacimiento </span> : <span class="ml-auto" v-text="dfnva_persona.lugar_nacimiento"></span></li>
                                <li><span class="w-50">ubigeo_domicilio </span> : <span class="ml-auto" v-text="dfnva_persona.ubigeo_domicilio"></span></li>

                                <li><span class="w-50">departamento_domicilio </span> : <span class="ml-auto" v-text="dfnva_persona.departamento_domicilio"></span></li>
                                <li><span class="w-50">provincia_domicilio </span> : <span class="ml-auto" v-text="dfnva_persona.provincia_domicilio"></span></li>
                                <li><span class="w-50">distrito_domicilio </span> : <span class="ml-auto" v-text="dfnva_persona.distrito_domicilio"></span></li>
                                <li><span class="w-50">lugar_domicilio </span> : <span class="ml-auto" v-text="dfnva_persona.lugar_domicilio"></span></li>


                            </ul>
                            <ul class="pricing-features" v-if="dfnva_vehiculo.id">
                                <li><span class="w-50">placa</span> : <span class="ml-auto" v-text="dfnva_vehiculo.placa"></span></li>
                                <li><span class="w-50">color</span> : <span class="ml-auto" v-text="dfnva_vehiculo.color"></span></li>
                                <li><span class="w-50">marca</span> : <span class="ml-auto" v-text="dfnva_vehiculo.marca"></span></li>
                                <li><span class="w-50">modelo </span> : <span class="ml-auto" v-text="dfnva_vehiculo.modelo"></span></li>
                                <li><span class="w-50">serie</span> : <span class="ml-auto" v-text="dfnva_vehiculo.serie"></span></li>
                                <li><span class="w-50">numero_motor</span> : <span class="ml-auto" v-text="dfnva_vehiculo.numero_motor"></span></li>
                                <li><span class="w-50">año</span> : <span class="ml-auto" v-text="dfnva_vehiculo.ano"></span></li>
                                <li><span class="w-50">tipo_carroceria </span> : <span class="ml-auto" v-text="dfnva_vehiculo.tipo_carroceria"></span></li>
                                <li><span class="w-50">vin</span> : <span class="ml-auto" v-text="dfnva_vehiculo.vin"></span></li>
                                <li><span class="w-50">tipo_motor</span> : <span class="ml-auto" v-text="dfnva_vehiculo.tipo_motor"></span></li>
                                <li><span class="w-50">cilindrada_motor</span> : <span class="ml-auto" v-text="dfnva_vehiculo.cilindrada_motor"></span></li>
                                <li><span class="w-50">tipo_combustible </span> : <span class="ml-auto" v-text="dfnva_vehiculo.tipo_combustible"></span></li>
                                <li><span class="w-50">tipo_transmision</span> : <span class="ml-auto" v-text="dfnva_vehiculo.tipo_transmision"></span></li>
                                <li><span class="w-50">tipo_traccion</span> : <span class="ml-auto" v-text="dfnva_vehiculo.tipo_traccion"></span></li>
                                <li><span class="w-50">kilometraje</span> : <span class="ml-auto" v-text="dfnva_vehiculo.kilometraje"></span></li>
                                <li><span class="w-50">estado_vehiculo </span> : <span class="ml-auto" v-text="dfnva_vehiculo.estado_vehiculo"></span></li>
                            </ul>
                            <ul class="pricing-features" v-if="dfnva_inmueble.id">
                                <li v-if="dfnva_inmueble.get_tipo_inmueble"><span class="w-50">Tipo</span> : <span class="ml-auto" v-text="dfnva_inmueble.get_tipo_inmueble.descripcion"></span></li>
                                <li><span class="w-50">direccion</span> : <span class="ml-auto" v-text="dfnva_inmueble.direccion"></span></li>
                                <li><span class="w-50">departamento</span> : <span class="ml-auto" v-text="dfnva_inmueble.departamento"></span></li>
                                <li><span class="w-50">provincia</span> : <span class="ml-auto" v-text="dfnva_inmueble.provincia"></span></li>
                                <li><span class="w-50">distrito </span> : <span class="ml-auto" v-text="dfnva_inmueble.distrito"></span></li>

                                <li><span class="w-50">referencia</span> : <span class="ml-auto" v-text="dfnva_inmueble.referencia"></span></li>
                                <li><span class="w-50">color_exterior</span> : <span class="ml-auto" v-text="dfnva_inmueble.color_exterior"></span></li>
                                <li><span class="w-50">caracteristicas_especiales </span> : <span class="ml-auto" v-text="dfnva_inmueble.caracteristicas_especiales"></span></li>
                                <li><span class="w-50">estado_conservacion</span> : <span class="ml-auto" v-text="dfnva_inmueble.estado_conservacion"></span></li>
                                <li><span class="w-50">latitud</span> : <span class="ml-auto" v-text="dfnva_inmueble.latitud"></span></li>
                                <li><span class="w-50">longitud</span> : <span class="ml-auto" v-text="dfnva_inmueble.longitud"></span></li>
                                <li><span class="w-50">observaciones </span> : <span class="ml-auto" v-text="dfnva_inmueble.observaciones"></span></li>


                            </ul>
                            <div class="nk-fmg-body-content">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between position-relative">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Archivos digitales</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-fmg-quick-list nk-block">
                                    <div class="toggle-expand-content expanded" data-content="quick-access">
                                        <div class="nk-files nk-files-view-grid">
                                            <div class="nk-files-list">
                                                <div class="nk-file-item nk-file" v-for="(item,index) in dfnva_files.get_nueva_vigilancia_archivo" :key="index">
                                                    <div class="nk-file-info">
                                                        <a target="_blank" :href="URLASSET+'files/'+item.archivo" class="nk-file-link">
                                                            <div class="nk-file-title">
                                                                <div class="nk-file-icon">
                                                                    <span class="nk-file-icon-type" v-if="item.ta_id ==1">
                                                                        <svg width="800px" height="800px" viewBox="0 0 24 24" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">
                                                                            <g transform="translate(0 -1028.4)">
                                                                                <path d="m23 1048.4c0 1.1-0.895 2-2 2h-8-4-6c-1.1046 0-2-0.9-2-2v-14c0-1.1 0.8954-2 2-2h6 4 4 4c1.105 0 2 0.9 2 2v4 10z" fill="#95a5a6" />
                                                                                <path d="m1 1047.4c0 1.1 0.8954 2 2 2h8 4 6c1.105 0 2-0.9 2-2v-14c0-1.1-0.895-2-2-2h-6-4-4-4c-1.1046 0-2 0.9-2 2v4 10z" fill="#bdc3c7" />
                                                                                <rect transform="translate(0 1028.4)" height="14" width="18" y="5" x="3" fill="#f39c12" />
                                                                                <path d="m16.625 8.625-10.344 10.375h14.719v-6l-4.375-4.375z" transform="translate(0 1028.4)" fill="#e67e22" />
                                                                                <path d="m8 8a2 2 0 1 1 -4 0 2 2 0 1 1 4 0z" transform="matrix(1.75 0 0 1.75 -3 1024.4)" fill="#f1c40f" />
                                                                                <path d="m8.0938 11.094-5.0938 5.094v2.812h13l-7.9062-7.906z" transform="translate(0 1028.4)" fill="#d35400" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="nk-file-icon-type" v-if="item.ta_id ==2">
                                                                        <svg width="800px" height="800px" viewBox="0 0 24 24" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">
                                                                            <g transform="translate(0 -1028.4)">
                                                                                <path d="m4 1c-1.1046 0-2 0.8954-2 2v3 11 3 1c0 1.105 0.8954 2 2 2h2 12 2c1.105 0 2-0.895 2-2v-1-3-11-3c0-1.1046-0.895-2-2-2h-2-12-2zm-0.5 2h1c0.2761 0 0.5 0.2238 0.5 0.5v1c0 0.2762-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.2238-0.5-0.5v-1c0-0.2762 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.2238 0.5 0.5v1c0 0.2762-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.2238-0.5-0.5v-1c0-0.2762 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.2238 0.5 0.5v1c0 0.2762-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.2238-0.5-0.5v-1c0-0.2762 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.2238 0.5 0.5v1c0 0.2762-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.2238-0.5-0.5v-1c0-0.2762 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.2238 0.5 0.5v1c0 0.276-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.224-0.5-0.5v-1c0-0.2762 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.2238 0.5 0.5v1c0 0.276-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.224-0.5-0.5v-1c0-0.2762 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.224 0.5 0.5v1c0 0.276-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.224 0.5 0.5v1c0 0.276-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.224 0.5 0.5v1c0 0.276-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.224 0.5 0.5v1c0 0.276-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.224 0.5 0.5v1c0 0.276-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.224 0.5 0.5v1c0 0.276-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.224-0.5-0.5v-1c0-0.276 0.224-0.5 0.5-0.5z" transform="translate(0 1028.4)" fill="#e74c3c" />
                                                                                <path d="m2 1040.4v5 3 1c0 1.1 0.8954 2 2 2h2 12 2c1.105 0 2-0.9 2-2v-1-3-5h-1.5c0.276 0 0.5 0.2 0.5 0.5v1c0 0.2-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.224-0.5 0.5-0.5h-15c0.2761 0 0.5 0.2 0.5 0.5v1c0 0.2-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.2239-0.5 0.5-0.5h-1.5zm1.5 3h1c0.2761 0 0.5 0.2 0.5 0.5v1c0 0.2-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.2 0.5 0.5v1c0 0.2-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.224-0.5 0.5-0.5zm-16 3h1c0.2761 0 0.5 0.2 0.5 0.5v1c0 0.2-0.2239 0.5-0.5 0.5h-1c-0.2761 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.2239-0.5 0.5-0.5zm16 0h1c0.276 0 0.5 0.2 0.5 0.5v1c0 0.2-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5-0.3-0.5-0.5v-1c0-0.3 0.224-0.5 0.5-0.5z" fill="#c0392b" />
                                                                                <path d="m5 22v-20.196l17.66 10.098z" transform="matrix(.28312 0 0 .29709 8.5844 1036.8)" fill="#ecf0f1" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="nk-file-icon-type" v-if="item.ta_id ==3">
                                                                        <svg width="800px" height="800px" viewBox="0 0 24 24" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">
                                                                            <g transform="translate(0 -1028.4)">
                                                                                <path d="m5 1030.4c-1.1046 0-2 0.9-2 2v8 4 6c0 1.1 0.8954 2 2 2h14c1.105 0 2-0.9 2-2v-6-4-4l-6-6h-10z" fill="#95a5a6" />
                                                                                <path d="m5 1029.4c-1.1046 0-2 0.9-2 2v8 4 6c0 1.1 0.8954 2 2 2h14c1.105 0 2-0.9 2-2v-6-4-4l-6-6h-10z" fill="#bdc3c7" />
                                                                                <g fill="#3498db">
                                                                                    <path fill="#3498db" d="m16.996 1035.1v8.8c0 0.2-0.088 0.5-0.266 0.7-0.177 0.2-0.401 0.3-0.671 0.4-0.271 0.1-0.542 0.2-0.813 0.3-0.266 0-0.516 0.1-0.75 0.1s-0.487-0.1-0.758-0.1c-0.265-0.1-0.534-0.2-0.804-0.3-0.271-0.1-0.495-0.2-0.672-0.4s-0.266-0.5-0.266-0.7c0-0.3 0.089-0.5 0.266-0.7s0.401-0.4 0.672-0.5c0.27-0.1 0.539-0.2 0.804-0.3h0.758c0.547 0 1.047 0.1 1.5 0.3v-4.2l-5.9999 1.8v5.6c0 0.2-0.0886 0.5-0.2656 0.7-0.1771 0.2-0.4011 0.3-0.6719 0.4s-0.5417 0.2-0.8125 0.3c-0.2656 0-0.5156 0.1-0.75 0.1s-0.487-0.1-0.7578-0.1c-0.2656-0.1-0.5339-0.2-0.8047-0.3s-0.4948-0.2-0.6719-0.4-0.2656-0.5-0.2656-0.7c0-0.3 0.0885-0.5 0.2656-0.7s0.4011-0.4 0.6719-0.5 0.5391-0.2 0.8047-0.3h0.7578c0.5469 0 1.0469 0.1 1.5 0.3v-7.6c0-0.1 0.0495-0.3 0.1484-0.4 0.099-0.2 0.2266-0.3 0.3828-0.3l6.4997-2h0.219c0.208 0 0.385 0 0.531 0.2 0.146 0.1 0.219 0.3 0.219 0.5" />
                                                                                </g>
                                                                                <path d="m21 1035.4-6-6v4c0 1.1 0.895 2 2 2h4z" fill="#95a5a6" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="nk-file-icon-type" v-if="item.ta_id ==4">
                                                                        <svg width="800px" height="800px" viewBox="0 0 24 24" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">
                                                                            <g transform="translate(0 -1028.4)">
                                                                                <g>
                                                                                    <path d="m5 1030.4c-1.1046 0-2 0.9-2 2v8 4 6c0 1.1 0.8954 2 2 2h14c1.105 0 2-0.9 2-2v-6-4-4l-6-6h-10z" fill="#c0392b" />
                                                                                    <path d="m5 1029.4c-1.1046 0-2 0.9-2 2v8 4 6c0 1.1 0.8954 2 2 2h14c1.105 0 2-0.9 2-2v-6-4-4l-6-6h-10z" fill="#e74c3c" />
                                                                                    <path d="m21 1035.4-6-6v4c0 1.1 0.895 2 2 2h4z" fill="#c0392b" />
                                                                                </g>
                                                                                <path fill="#ecf0f1" d="m19.112 1039.4h-0.29c-0.09 0.1-0.601 0.8-0.822 1-0.06 0.1-0.15 0.2-0.201 0.2-0.743 0.9-2.323 2.4-3.271 3.2-0.124 0.1-0.244 0.2-0.266 0.2-0.066 0.1-0.698 0.5-0.982 0.7-0.905 0.6-1.967 1.1-2.792 1.3-1.6281 0.4-2.8984 0.3-4.5476-0.5-0.4984-0.2-1.2085-0.6-1.5978-0.9-0.382-0.3-0.3417-0.3-0.3411 0.2v0.5l0.2578 0.1c0.3307 0.2 1.0445 0.6 1.5099 0.8 0.3546 0.2 0.4651 0.3 0.2667 0.3-0.1828-0.1-1.2202-0.1-1.6188 0h-0.4c-0.0092 0-0.0167 0.2-0.0167 0.4v0.4h0.1333c0.5736-0.2 1.8047-0.2 2.3778-0.1 0.6748 0.1 1.1496 0.2 1.7199 0.5 1.0881 0.5 1.9906 1.5 2.3836 2.5l0.08 0.2h0.44 0.44l-0.024-0.1c-0.141-0.6-0.537-1.3-1.027-1.8-0.322-0.4-0.8907-0.8-1.1148-1h-0.0756c-0.0364-0.1-0.4165-0.3-0.5789-0.3-0.0924-0.1-0.1677-0.1-0.1677-0.1 0-0.1 0.07-0.1 0.1555 0 0.2135 0 1.0733-0.1 1.4625-0.2 1.078-0.2 2.098-0.6 3.129-1.2 0.37-0.2 1.21-0.9 1.546-1.1 0.25-0.3 0.33-0.3 0.228-0.2-0.036 0.1-0.105 0.2-0.155 0.3-0.049 0.1-0.135 0.2-0.19 0.3-0.778 1.2-1.277 2.7-1.412 4.4-0.019 0.3-0.035 0.6-0.035 0.7v0.3h2.332 2.332l-0.076-0.1c-0.42-0.6-0.941-1.9-1.116-2.8-0.144-0.7-0.187-1.8-0.097-2.4 0.262-1.6 1.155-3 2.689-3.9 0.158-0.1 0.362-0.2 0.453-0.3l0.167-0.1v-0.3c0-0.2-0.012-0.4-0.027-0.4-0.364 0.2-1.174 0.7-1.546 0.9-0.199 0.2-0.211 0.2-0.024 0 0.268-0.3 0.73-0.8 1.221-1.4l0.229-0.2h-0.501-0.24z" />
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                                <div class="nk-file-name">
                                                                    <div class="nk-file-name-text">
                                                                        <span class="title" v-text="item.archivo"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="nk-file-actions hideable">
                                                        <a href="#" class="btn btn-sm btn-icon btn-trigger"><em class="icon ni ni-cross"></em></a>
                                                    </div>
                                                </div>

                                            </div><!-- .nk-files -->
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const URLASSET = "{{asset('')}}";
        const URL_REGISTRAR = "{{route('ExpedienteWS')}}";
        const URL_EXPEDIENTE = "{{route('expediente_reporte')}}";
        document.addEventListener('DOMContentLoaded', async function() {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU0WytTK6kCsmp2NFdOWAMQ8yE7tacQg&libraries=places`;
            script.async = true;
            script.defer = true;
            script.onload = function() {
                initVueSIVIPOL();
            };
            document.head.appendChild(script);


        }, true);

        function initVueSIVIPOL() {
            Vue.use(VueTables.ClientTable);
            const app = new Vue({
                el: '#miapp',
                data: {
                    expe_culminados: 0,
                    expe_pendientes: 0,
                    expe_caducados: 0,
                    uri: URL_REGISTRAR,
                    uriExpe: URL_EXPEDIENTE,
                    situacion: "",
                    ocultar: false,
                    loadingModalNuevoFiscal: false,
                    loadingSarchInmueble: false,
                    loadingModal: false,
                    loadingBody: false,
                    loadingTable: false,
                    loadingPersonas: false,
                    loadingModalTipoVideoVigilancia: false,
                    cambiarModoActividades: false,
                    dataEdit: {},
                    dataFinish: {
                        documentos_id: 1,
                        fecha_documento: "",
                        asunto: "",
                        resultadoFinal: "",
                        destino: "",
                        archivo: "",
                        archivo_NAME: "",
                        files: []
                    },
                    disposicion_fiscal_nueva_vigilancia_actividads: {
                        index1: null,
                        index2: null,
                    },
                    dfnva_persona: {},
                    dfnva_inmueble: {},
                    dfnva_vehiculo: {},
                    dfnva_files: [],
                    dataExpe: {
                        fecha_disposicion: "",
                        fiscal_responsable_id: 0,
                        fiscal_asistente_id: 0,
                        oficial_acargo_id: 1,
                        nro: "1",
                        caso: "CIA SAN JUAN DE MIRAFLORES",
                        resumen: "EL DIA DE LA FECHA",
                        observaciones: "Sin observaciones",
                        plazo_id: 0,
                        plazo: 0,
                        fecha_inicio: "",
                        fecha_termino: "",
                        tipo_plazo: "Días Naturales",
                        selectdataTipoVideovigilancia: [],
                        selectdataObjetoVideovigilancia: [],
                        selectdataReferenciaVideovigilancia: [],
                        tipo_documento_identidad: 2,
                        documento_identidad: "",
                    },
                    dataFiscales: [],
                    dataFiscalEdit: {},
                    dataFiscalAdd: {
                        carnet: 0,
                        dni: 0,
                        nombres: "",
                        paterno: "",
                        materno: "",
                        celular: 0,
                        correo: "",
                        procedencia: "MINISTERIO PÚBLICO",
                        ficalia: "2DA FÍSCALIA PROVINCIAL PENAL COPORATIVA DE CARABAYLLO",
                        despacho: "SEGUNDO DESPACHO",
                        ubigeo: "DISTRITO FISCAL DE LIMA NORTE"
                    },
                    dataPersonasAdd: {
                        nacionalidad_id: 0,
                        documento_id: 0,
                        documento: "",
                        nombres: "",
                        paterno: "",
                        materno: "",
                        estado_civil: "",
                        sexo: "",
                        fecha_nacimiento: "",
                        ubigeo_nacimiento: "",
                        departamento_nacimiento: "",
                        provincia_nacimiento: "",
                        distrito_nacimiento: "",
                        lugar_nacimiento: "",
                        ubigeo_domicilio: "",
                        departamento_domicilio: "",
                        provincia_domicilio: "",
                        distrito_domicilio: "",
                        lugar_domicilio: ""
                    },
                    dataDelitos: [],
                    dataVehiculo: [],
                    dataPersonas: [],
                    dataInmueble: [],
                    dataPersonasSearch: [],
                    dataFiscales: [],
                    dataTipoDocumentos: [],
                    dataTipoVideovigilancia: [],
                    dataTipoVideovigilanciaAdd: "",
                    selectdataTipoVideovigilancia: {},
                    selectdataDelitos: {},
                    data_tipo_delitos: [],
                    data_dist: [],
                    data_dep: [],
                    data_prov: [],
                    data_grado: [],
                    data_unidad: [],
                    data_filtro: {
                        domicilio: {
                            provincia_domicilio: [],
                            distrito_domicilio: []
                        },
                        nacimiento: {
                            provincia_nacimiento: [],
                            distrito_nacimiento: []
                        }
                    },
                    data_documento_identidad: [],
                    data_inmueble: [],
                    dataInmuebleAdd: {
                        inmuebles_id: 1,
                        departamento: "",
                        provincia: "",
                        distrito: "",
                        referencia: "",
                        color_exterior: "",
                        dimensiones: "",
                        caracteristicas_especiales: "",
                        estado_conservacion: "",
                        pisos: "",
                        direccion: "",
                        latitud: "",
                        longitud: "",
                        observaciones: ""
                    },
                    dataVehiculoAdd: {
                        placa: '',
                        serie: '',
                        numero_motor: '',
                        color: '',
                        marca: '',
                        modelo: '',
                        ano: '',
                        tipo_carroceria: '',
                        vin: '',
                        tipo_motor: '',
                        cilindrada_motor: '',
                        tipo_combustible: '',
                        tipo_transmision: '',
                        tipo_traccion: '',
                        kilometraje: '',
                        placaanterior: '',
                        estado_vehiculo: ''
                    },
                    dataPoliciaAdd: {
                        carnet: 0,
                        dni: 0,
                        nombres: '',
                        paterno: '',
                        materno: '',
                        grado_id: 1,
                        unidad_id: 1,
                        situacion: '',
                        celular: '',
                        correo: ''
                    },
                    data_policia: [],
                    dataInmuebleSearch: [],
                    data_nacionalidad: [],
                    data: {
                        columns: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_estado', 'progreso', 'opciones'],
                        tableData: [],
                        options: {
                            toMomentFormat: true,
                            compileTemplates: false,
                            filterByColumn: true,
                            perPage: 10,
                            pagination: {
                                chunk: 15,
                                dropdown: true
                            },
                            headings: {
                                nro: 'NRO',
                                caso: 'CASO',
                                resumen: 'RESUMEN',
                                plazo: 'DÍAS DE PLAZO',
                                get_fiscal: 'FISCAL',
                                get_estado: 'SITUACION',
                                progreso: 'PROGRESO',
                                opciones: 'OPCIONES',
                            },
                            filterable: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_estado'],
                            texts: {
                                limit: 'Mostrar:',
                                count: 'Total de {count} registros encontrados',
                                filter: 'Filtrar Busqueda:',
                                filterPlaceholder: 'Buscar ....',
                                noResults: 'No hay resultados',
                                page: 'Pagina:',
                                filterBy: 'Filtro {column}',
                                loading: 'Cargando...', // First request to server
                                defaultOption: 'Seleccion {column}' // default option for list filters
                            },
                            props: ['data', 'index', 'column'],
                        }
                    },
                    map: null,
                    marker: null,
                    nueva_vigilancias: [],
                    nuevafechadocumentovideovigilancia: {
                        documentos_id: 2,
                        numeroDocumento: 0,
                        siglasDocumento: "",
                        fechaDocumento: "",
                        asunto: "",
                        respondea: "",
                        evaluacion: "",
                        conclusiones: "",
                        pdf: "",
                        pdfName: "",
                        FechaActividadAdd: "",
                        IndexActividadAdd: null
                    }
                },
                mounted() {
                    this.Get();
                    this.initMap();
                    // setTimeout(() => {
                    //     console.log(this.data.tableData)
                    //     setTimeout(() => {
                    //         this.dataEdit = this.data.tableData[1];
                    //         // console.log(this.data.tableData)
                    //         console.log(this.dataEdit);
                    //         this.ocultar = true;

                    //     }, 1500);
                    // }, 1500);
                },
                methods: {
                    calculateFechaHoy() {
                        // Calcula la fecha_hoy utilizando moment
                        const momentFechaHoy = moment();
                        return momentFechaHoy.format('YYYY-MM-DD');
                    },
                    compareFechas(fechaFin, fechaHoy) {
                        const momentFechaFin = moment(fechaFin);
                        const momentFechaHoy = moment(fechaHoy);

                        if (momentFechaFin.isSame(momentFechaHoy, 'day')) {
                            return 'Hoy es la Fecha Fin';
                        } else if (momentFechaHoy.isAfter(momentFechaFin, 'day')) {
                            const diasPasados = momentFechaHoy.diff(momentFechaFin, 'days');
                            return `Pasaron ${diasPasados} días desde la Fecha Fin`;
                        } else {
                            const diasFaltantes = momentFechaFin.diff(momentFechaHoy, 'days');
                            return `Faltan ${diasFaltantes} días para la Fecha Fin`;
                        }
                    },
                    calculateProgressBarWidth(row) {
                        // Lógica para calcular el ancho de la barra de progreso según las condiciones
                        // Puedes devolver un porcentaje calculado dinámicamente.
                        // Ejemplo simple:
                        if (row?.get_nueva_vigilancia?.[0]?.get_nueva_vigilancia_actividad?.[0]?.get_nueva_vigilancia_entidad) {
                            return '75%';
                        } else if (row?.get_nueva_vigilancia?.[0]?.get_nueva_vigilancia_actividad) {
                            return '50%';
                        } else if (row?.get_nueva_vigilancia?.[0]) {
                            return '30%';
                        } else {
                            return '10%';
                        }
                    },
                    calcularFechaFinal() {
                        if (this.dataExpe.fecha_inicio && this.dataExpe.plazo) {
                            const fechaInicio = new Date(this.dataExpe.fecha_inicio);
                            const plazoDias = parseInt(this.dataExpe.plazo, 10);

                            const fechaFinal = new Date(fechaInicio);
                            fechaFinal.setDate(fechaInicio.getDate() + plazoDias);
                            this.dataExpe.fecha_termino = fechaFinal.toISOString().split('T')[0];
                            // console.log()
                            // Devolver la fecha formateada como string
                            // return fechaFinal.toISOString().split('T')[0];
                        }
                        return 'No hay suficientes datos para calcular la fecha final';
                    },
                    VerEntidadActividad(isDATA) {
                        console.log(isDATA);
                        this.dfnva_persona = {};
                        this.dfnva_inmueble = {};
                        this.dfnva_vehiculo = {};
                        this.loadingModalNuevoFiscal = true;
                        const formData = new FormData();
                        formData.append('type', "_VerEntidadArchivos");
                        formData.append('ID_CODIGO_RELACION', isDATA.codigo_relacion);
                        formData.append('ID_ENTIDAD', isDATA.entidads_id);
                        formData.append('ID_CONTEXTO', isDATA.id);
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    if (isDATA.entidads_id == 1 || isDATA.entidads_id == 2) {
                                        this.dfnva_persona = response.data.data;
                                    } else if (isDATA.entidads_id == 3) {
                                        this.dfnva_vehiculo = response.data.data;
                                    } else if (isDATA.entidads_id == 4) {
                                        this.dfnva_inmueble = response.data.data;

                                    }
                                    this.dfnva_files = response.data.files;
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingModalNuevoFiscal = false;
                                $('#modalVerEntidadActividad').modal('show');
                            });

                    },
                    GrabarActividad(TYPE) {
                        this.loadingPersonas = true;
                        const formData = new FormData();
                        formData.append('type', TYPE);
                        formData.append('dd', 'trucho');
                        if (TYPE == "_DisposicionFiscalNuevaVigilancia") {
                            formData.append('df_id', this.dataEdit.id);
                            for (const key in this.nuevafechadocumentovideovigilancia) {
                                formData.append(key, this.nuevafechadocumentovideovigilancia[key]);
                            }
                        }
                        if (TYPE == "_DisposicionFiscalNuevaVigilanciaActividad") {
                            const DFNV = this.dataEdit.get_nueva_vigilancia[this.nuevafechadocumentovideovigilancia.IndexActividadAdd];
                            formData.append('dfnv_id', DFNV.id);
                            formData.append('fechahora', this.nuevafechadocumentovideovigilancia.FechaActividadAdd);
                        }
                        if (TYPE == "_DisposicionFiscalNuevaVigilanciaEntidad") {
                            const index1 = this.disposicion_fiscal_nueva_vigilancia_actividads.index1;
                            const index2 = this.disposicion_fiscal_nueva_vigilancia_actividads.index2;
                            formData.append('dfnva_id', this.dataEdit.get_nueva_vigilancia[index1].get_nueva_vigilancia_actividad[index2].id);


                            if (this.dfnva_persona.id) {
                                if (this.dfnva_persona.nacionalidad_id == 1) {
                                    formData.append('entidads_id', 1);
                                } else {
                                    formData.append('entidads_id', 2);
                                }
                                formData.append('codigo_relacion', this.dfnva_persona.id);
                                formData.append('detalle', this.dfnva_persona.detalle);
                                for (let i = 0; i < this.dfnva_persona.get_nueva_vigilancia_archivo.length; i++) {
                                    if (this.dfnva_persona.get_nueva_vigilancia_archivo[i]) {
                                        formData.append(`get_nueva_vigilancia_archivo_ta_id[${i}]`, this.dfnva_persona.get_nueva_vigilancia_archivo[i].ta_id);
                                        formData.append(`get_nueva_vigilancia_archivo_file[${i}]`, this.dfnva_persona.get_nueva_vigilancia_archivo[i].pdf);
                                    }
                                }
                            }

                            if (this.dfnva_inmueble.id) {
                                formData.append('entidads_id', 4);
                                formData.append('codigo_relacion', this.dfnva_inmueble.id);
                                formData.append('detalle', this.dfnva_inmueble.detalle);
                                for (let i = 0; i < this.dfnva_inmueble.get_nueva_vigilancia_archivo.length; i++) {
                                    if (this.dfnva_inmueble.get_nueva_vigilancia_archivo[i]) {
                                        formData.append(`get_nueva_vigilancia_archivo_ta_id[${i}]`, this.dfnva_inmueble.get_nueva_vigilancia_archivo[i].ta_id);
                                        formData.append(`get_nueva_vigilancia_archivo_file[${i}]`, this.dfnva_inmueble.get_nueva_vigilancia_archivo[i].pdf);
                                    }
                                }
                            }

                            if (this.dfnva_vehiculo.id) {
                                formData.append('entidads_id', 3);
                                formData.append('codigo_relacion', this.dfnva_vehiculo.id);
                                formData.append('detalle', this.dfnva_vehiculo.detalle);
                                for (let i = 0; i < this.dfnva_vehiculo.get_nueva_vigilancia_archivo.length; i++) {
                                    if (this.dfnva_vehiculo.get_nueva_vigilancia_archivo[i]) {
                                        formData.append(`get_nueva_vigilancia_archivo_ta_id[${i}]`, this.dfnva_vehiculo.get_nueva_vigilancia_archivo[i].ta_id);
                                        formData.append(`get_nueva_vigilancia_archivo_file[${i}]`, this.dfnva_vehiculo.get_nueva_vigilancia_archivo[i].pdf);
                                    }
                                }
                            }



                        }
                        if (TYPE == "_DisposicionFiscalFINISH") {
                            formData.append('df_id', this.dataEdit.id);
                            for (let i = 0; i < this.dataFinish.files.length; i++) {
                                if (this.dataFinish.files[i]) {
                                    formData.append(`dataFinish_archivo_[${i}]`, this.dataFinish.files[i].archivo);
                                    formData.append(`dataFinish_tipo_contenido_[${i}]`, this.dataFinish.files[i].contenidos_id);
                                }
                            }
                            formData.append('documentos_id', this.dataFinish.documentos_id);
                            formData.append('fecha_documento', this.dataFinish.fecha_documento);
                            formData.append('asunto', this.dataFinish.asunto);
                            formData.append('resultadoFinal', this.dataFinish.resultadoFinal);
                            formData.append('destino', this.dataFinish.destino);
                            formData.append('archivo', this.dataFinish.archivo);
                        }
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                console.log(response.data);
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    if (TYPE == "_DisposicionFiscalNuevaVigilancia") {
                                        this.dataEdit.get_nueva_vigilancia.push(response.data.data);
                                    }
                                    if (TYPE == "_DisposicionFiscalNuevaVigilanciaActividad") {
                                        // console.log(this.dataEdit.get_nueva_vigilancia[this.nuevafechadocumentovideovigilancia.IndexActividadAdd]);
                                        this.dataEdit.get_nueva_vigilancia[this.nuevafechadocumentovideovigilancia.IndexActividadAdd].get_nueva_vigilancia_actividad.push(response.data.data);
                                    }
                                    if (TYPE == "_DisposicionFiscalNuevaVigilanciaEntidad") {
                                        const index1 = this.disposicion_fiscal_nueva_vigilancia_actividads.index1;
                                        const index2 = this.disposicion_fiscal_nueva_vigilancia_actividads.index2;

                                        this.dataEdit.get_nueva_vigilancia[index1].get_nueva_vigilancia_actividad[index2].get_nueva_vigilancia_entidad.push(response.data.data);
                                    }
                                    if (TYPE == "_DisposicionFiscalFINISH") {
                                        var ISindex = this.BuscarIndice(this.dataEdit.id);
                                        this.data.tableData[ISindex].get_resultado.push(response.data.data);
                                        this.data.tableData[ISindex].estado_id = 2;
                                        setTimeout(() => {
                                            this.expe_culminados = this.filtrarPorEstado(2);
                                            this.expe_pendientes = this.filtrarPorEstado(1);
                                        }, 1000);
                                    }
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingPersonas = false;
                                $('#modalEdit').modal('hide');
                                $('#modalFechaContenidoAdd').modal('hide');
                                $('#modalFechaActividadAdd').modal('hide');
                                $('#modalEntidadActividadAdd').modal('hide');
                            });
                    },
                    Grabar() {
                        this.loadingModal = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE");
                        formData.append('dd', "_SAVE");
                        formData.append('nro', this.dataExpe.nro);
                        formData.append('caso', this.dataExpe.caso);
                        if (this.dataExpe.fecha_disposicion) {
                            formData.append('fecha_disposicion', this.dataExpe.fecha_disposicion);
                        }
                        if (this.dataExpe.resumen) {
                            formData.append('resumen', this.dataExpe.resumen);
                        }
                        if (this.dataExpe.observaciones) {
                            formData.append('observaciones', this.dataExpe.observaciones);
                        }
                        if (this.dataExpe.plazo_id) {
                            formData.append('plazo_id', this.dataExpe.plazo_id);
                        }
                        if (this.dataExpe.plazo) {
                            formData.append('plazo', this.dataExpe.plazo);
                        }
                        if (this.dataExpe.fecha_inicio) {
                            formData.append('fecha_inicio', this.dataExpe.fecha_inicio);
                        }
                        if (this.dataExpe.fecha_termino) {
                            formData.append('fecha_termino', this.dataExpe.fecha_termino);
                        }
                        if (this.dataExpe.tipo_plazo) {
                            formData.append('tipo_plazo', this.dataExpe.tipo_plazo);
                        }

                        if (this.dataExpe.oficial_acargo_id) {
                            formData.append('oficial_acargo_id', this.dataExpe.oficial_acargo_id);
                        }
                        if (this.dataExpe.fiscal_responsable_id) {
                            formData.append('fiscal_responsable_id', this.dataExpe.fiscal_responsable_id);
                        }
                        if (this.dataExpe.fiscal_asistente_id) {
                            formData.append('fiscal_asistente_id', this.dataExpe.fiscal_asistente_id);
                        }
                        for (let i = 0; i < this.dataExpe.selectdataObjetoVideovigilancia.length; i++) {
                            if (this.dataExpe.selectdataObjetoVideovigilancia[i]) {
                                formData.append(`selectdataObjetoVideovigilancia_[${i}]`, this.dataExpe.selectdataObjetoVideovigilancia[i]);
                            }
                        }
                        // console.log(this.dataExpe);
                        if (this.dataExpe.selectdataReferenciaVideovigilancia) {
                            for (let i = 0; i < this.dataExpe.selectdataReferenciaVideovigilancia.length; i++) {
                                if (this.dataExpe.selectdataReferenciaVideovigilancia[i]) {
                                    formData.append(`selectdataReferenciaVideovigilancia_documentos_id_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].documentos_id);
                                    formData.append(`selectdataReferenciaVideovigilancia_fecha_documento_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].fecha_documento);
                                    formData.append(`selectdataReferenciaVideovigilancia_nro_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].nro);
                                    formData.append(`selectdataReferenciaVideovigilancia_pdf_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].pdf);
                                    formData.append(`selectdataReferenciaVideovigilancia_siglas_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].siglas);
                                }
                            }
                        }
                        if (this.dataExpe.selectdataTipoVideovigilancia) {
                            for (let i = 0; i < this.dataExpe.selectdataTipoVideovigilancia.length; i++) {
                                if (this.dataExpe.selectdataTipoVideovigilancia[i].id) {
                                    formData.append(`selectdataTipoVideovigilancia_[${i}]`, this.dataExpe.selectdataTipoVideovigilancia[i].id);
                                }
                            }
                        }


                        for (let i = 0; i < this.dataVehiculo.length; i++) {
                            if (this.dataVehiculo[i].id) {
                                formData.append(`dataVehiculo_[${i}]`, this.dataVehiculo[i].id);
                            }
                        }
                        for (let i = 0; i < this.dataPersonas.length; i++) {
                            if (this.dataPersonas[i].id) {
                                formData.append(`dataPersonas_[${i}]`, this.dataPersonas[i].id);
                                formData.append(`dataPersonas_nacionalidad_[${i}]`, this.dataPersonas[i].nacionalidad_id);
                            }
                        }
                        for (let i = 0; i < this.dataInmueble.length; i++) {
                            if (this.dataInmueble[i].id) {
                                formData.append(`dataInmueble_[${i}]`, this.dataInmueble[i].id);
                            }
                        }
                        for (let i = 0; i < this.dataDelitos.length; i++) {
                            if (this.dataDelitos[i].id) {
                                formData.append(`dataDelitos_[${i}]`, this.dataDelitos[i].id);
                            }
                        }
                        // console.log(formData);
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                console.log(response);
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Felicidades',
                                        text: 'Registrado correctamente',
                                        icon: 'success',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                    this.data.tableData.push(response.data.data);
                                    this.expe_culminados = this.filtrarPorEstado(2);
                                    this.expe_pendientes = this.filtrarPorEstado(1);
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.ocultar = false;
                                this.loadingModal = false;
                                $('#modalADD').modal('hide');
                            });

                    },
                    GrabarNuevoFiscal() {
                        this.loadingModalNuevoFiscal = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE_FISCAL");
                        if (this.dataFiscalAdd.carnet) {
                            formData.append('carnet', this.dataFiscalAdd.carnet);
                        }
                        if (this.dataFiscalAdd.dni) {
                            formData.append('dni', this.dataFiscalAdd.dni);
                        }
                        if (this.dataFiscalAdd.nombres) {
                            formData.append('nombres', this.dataFiscalAdd.nombres);
                        }
                        if (this.dataFiscalAdd.paterno) {
                            formData.append('paterno', this.dataFiscalAdd.paterno);
                        }
                        if (this.dataFiscalAdd.materno) {
                            formData.append('materno', this.dataFiscalAdd.materno);
                        }
                        if (this.dataFiscalAdd.celular) {
                            formData.append('celular', this.dataFiscalAdd.celular);
                        }
                        if (this.dataFiscalAdd.correo) {
                            formData.append('correo', this.dataFiscalAdd.correo);
                        }
                        if (this.dataFiscalAdd.procedencia) {
                            formData.append('procedencia', this.dataFiscalAdd.procedencia);
                        }
                        if (this.dataFiscalAdd.ficalia) {
                            formData.append('ficalia', this.dataFiscalAdd.ficalia);
                        }
                        if (this.dataFiscalAdd.despacho) {
                            formData.append('despacho', this.dataFiscalAdd.despacho);
                        }
                        if (this.dataFiscalAdd.ubigeo) {
                            formData.append('ubigeo', this.dataFiscalAdd.ubigeo);
                        }

                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    this.dataFiscales.push(response.data.data);
                                    // const DATARETURN = response.data.data;
                                    console.log(response)
                                    // const index = this.BuscarIndice(DATARETURN.id);
                                    // console.log(index)
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingModalNuevoFiscal = false;
                                $('#modalADDFiscal').modal('hide');
                                setTimeout(() => {
                                    $('#modalADD').modal('show');
                                }, 1000);
                            });

                    },
                    GrabarTipoVideoVigilancia() {
                        this.loadingModalTipoVideoVigilancia = true;
                        const formData = new FormData();
                        formData.append('type', "_DisposicionFiscalNuevaVigilancia");
                        if (this.dataTipoVideovigilanciaAdd) {
                            formData.append('TipoVideoVigilancia', this.dataTipoVideovigilanciaAdd);
                        }
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    this.dataTipoVideovigilancia.push(response.data.data);
                                    console.log(response.data.data)
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingModalTipoVideoVigilancia = false;
                                $('#modalADDTipoVideoVigilancia').modal('hide');
                                setTimeout(() => {
                                    $('#modalADD').modal('show');
                                }, 1000);

                            });
                    },
                    GrabarPersonas(TIPO) {
                        this.loadingPersonas = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE_personas");
                        if (TIPO == "PERUANOS") {
                            formData.append('ubigeo_nacimiento', this.dataPersonasAdd.ubigeo_nacimiento);
                            formData.append('departamento_nacimiento', this.dataPersonasAdd.departamento_nacimiento);
                            formData.append('provincia_nacimiento', this.dataPersonasAdd.provincia_nacimiento);
                            formData.append('distrito_nacimiento', this.dataPersonasAdd.distrito_nacimiento);
                        } else if (TIPO == "EXTRANJEROS") {
                            formData.append('documento_id', this.dataPersonasAdd.documento_id);
                            formData.append('nacionalidad_id', this.dataPersonasAdd.nacionalidad_id);
                            formData.append('lugar_nacimiento', this.dataPersonasAdd.lugar_nacimiento);
                        }
                        formData.append('documento', this.dataPersonasAdd.documento);
                        formData.append('nombres', this.dataPersonasAdd.nombres);
                        formData.append('paterno', this.dataPersonasAdd.paterno);
                        formData.append('materno', this.dataPersonasAdd.materno);
                        formData.append('estado_civil', this.dataPersonasAdd.estado_civil);
                        formData.append('sexo', this.dataPersonasAdd.sexo);
                        formData.append('fecha_nacimiento', this.dataPersonasAdd.fecha_nacimiento);

                        formData.append('ubigeo_domicilio', this.dataPersonasAdd.ubigeo_domicilio);
                        formData.append('departamento_domicilio', this.dataPersonasAdd.departamento_domicilio);
                        formData.append('provincia_domicilio', this.dataPersonasAdd.provincia_domicilio);
                        formData.append('distrito_domicilio', this.dataPersonasAdd.distrito_domicilio);
                        formData.append('lugar_domicilio', this.dataPersonasAdd.lugar_domicilio);

                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    if (this.cambiarModoActividades) {
                                        this.dfnva_persona = {
                                            ...response.data.data,
                                            detalle: "",
                                            get_nueva_vigilancia_archivo: []
                                        };
                                        $('#modalPersonas').modal('hide');
                                        setTimeout(() => {
                                            $('#modalEntidadActividadAdd').modal('show');
                                        }, 1000);
                                    } else {
                                        this.dataPersonas.push(response.data.data);
                                        console.log(this.dataPersonas)
                                        $('#modalPersonas').modal('hide');
                                        setTimeout(() => {
                                            $('#modalADD').modal('show');
                                        }, 1000);
                                    }

                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingPersonas = false;

                            });
                    },
                    GrabarInmueble() {
                        this.loadingSarchInmueble = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE_inmueble");
                        formData.append('dd', "_SAVE_inmueble");
                        formData.append('inmuebles_id', this.dataInmuebleAdd.inmuebles_id);
                        formData.append('direccion', this.dataInmuebleAdd.direccion);
                        formData.append('departamento', this.dataInmuebleAdd.departamento);
                        formData.append('provincia', this.dataInmuebleAdd.provincia);
                        formData.append('distrito', this.dataInmuebleAdd.distrito);
                        formData.append('referencia', this.dataInmuebleAdd.referencia);
                        formData.append('color_exterior', this.dataInmuebleAdd.color_exterior);
                        formData.append('caracteristicas_especiales', this.dataInmuebleAdd.caracteristicas_especiales);
                        formData.append('estado_conservacion', this.dataInmuebleAdd.estado_conservacion);
                        formData.append('pisos', this.dataInmuebleAdd.pisos);
                        formData.append('latitud', this.dataInmuebleAdd.latitud);
                        formData.append('longitud', this.dataInmuebleAdd.longitud);
                        formData.append('observaciones', this.dataInmuebleAdd.observaciones);
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    if (this.cambiarModoActividades) {
                                        this.dfnva_inmueble = {
                                            ...response.data.data,
                                            detalle: "",
                                            get_nueva_vigilancia_archivo: []
                                        };
                                        $('#modalInmueble').modal('hide');

                                        setTimeout(() => {
                                            $('#modalEntidadActividadAdd').modal('show');
                                        }, 1000);
                                        this.loadingSarchInmueble = false;
                                        return true;
                                    } else {
                                        this.dataInmueble.push(response.data.data);
                                        console.log(this.dataInmueble)
                                    }

                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingSarchInmueble = false;
                                if (!this.cambiarModoActividades) {
                                    $('#modalInmueble').modal('hide');
                                    setTimeout(() => {
                                        $('#modalADD').modal('show');
                                    }, 1000);

                                }
                            });
                    },
                    GrabarVehiculo(TIPO = null) {
                        this.dfnva_persona = {};
                        this.dfnva_inmueble = {};
                        this.dfnva_vehiculo = {};

                        this.loadingSarchInmueble = true;
                        const formData = new FormData();
                        formData.append('dd', "trae");
                        if (TIPO) {
                            formData.append('type', "_SEARCH_VEHICULO");
                            formData.append('placa', this.dataVehiculoAdd.placa);
                        } else {
                            formData.append('type', "_SAVE_VEHICULO");
                            for (const key in this.dataVehiculoAdd) {
                                formData.append(key, this.dataVehiculoAdd[key]);
                            }
                        }
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    if (this.cambiarModoActividades) {
                                        this.dfnva_vehiculo = {
                                            ...response.data.data,
                                            detalle: "",
                                            get_nueva_vigilancia_archivo: []
                                        };
                                        $('#modalVehiculo').modal('hide');
                                        setTimeout(() => {
                                            $('#modalEntidadActividadAdd').modal('show');
                                        }, 1000);
                                        this.loadingSarchInmueble = false;
                                        return true;
                                    } else {
                                        this.dataVehiculo.push(response.data.data);
                                        console.log(this.dataVehiculo)
                                    }
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingSarchInmueble = false;
                                if (!this.cambiarModoActividades) {
                                    $('#modalVehiculo').modal('hide');
                                    setTimeout(() => {
                                        $('#modalADD').modal('show');
                                    }, 1000);
                                }
                            });

                    },
                    GrabarPolicia() {
                        this.loadingPersonas = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE_POLICIA");
                        formData.append('dd', "_SAVE_POLICIA");
                        for (const key in this.dataPoliciaAdd) {
                            formData.append(`${key}`, this.dataPoliciaAdd[key]);
                        }



                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    this.data_policia.push(response.data.data);
                                    console.log(response.data)
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingPersonas = false;
                                $('#modalOficialAdd').modal('hide');
                                setTimeout(() => {
                                    $('#modalADD').modal('show');
                                }, 1000);

                            });
                    },
                    BuscarPersonas(TIPO) {
                        this.dataInmuebleSearch = [];
                        this.dataPersonasSearch = [];
                        this.loadingPersonas = true;
                        const formData = new FormData();
                        formData.append('type', "_get_personas");
                        formData.append('subtype', TIPO);
                        formData.append('dd', "_get_personas");
                        if (TIPO == "_get_persona_nacionalidad_nombres") {
                            formData.append('nombres', this.dataPersonasAdd.nombres);
                            formData.append('paterno', this.dataPersonasAdd.paterno);
                            formData.append('materno', this.dataPersonasAdd.materno);
                        } else if (TIPO == "_get_persona_extranjero") {
                            formData.append('documento_id', this.dataPersonasAdd.documento_id);
                            formData.append('documento', this.dataPersonasAdd.documento);
                        } else if (TIPO == "_get_persona_peru") {
                            formData.append('documento', this.dataPersonasAdd.documento);
                        } else if (TIPO == "_get_domicilio") {
                            formData.append('inmuebles_id', this.dataInmuebleAdd.inmuebles_id);
                            formData.append('direccion', this.dataInmuebleAdd.direccion);
                            formData.append('departamento', this.dataInmuebleAdd.departamento);
                            formData.append('provincia', this.dataInmuebleAdd.provincia);
                            formData.append('distrito', this.dataInmuebleAdd.distrito);
                            formData.append('referencia', this.dataInmuebleAdd.referencia);
                        }
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    console.log(response.data)
                                    if (response.data && response.data.data && response.data.data != null) {
                                        if (this.cambiarModoActividades) {
                                            if (TIPO == "_get_persona_nacionalidad_nombres") {
                                                this.dataPersonasSearch = response.data.data;
                                            } else if (TIPO == "_get_persona_extranjero" || TIPO == "_get_persona_peru") {
                                                this.dfnva_persona = {
                                                    ...response.data.data,
                                                    detalle: "",
                                                    get_nueva_vigilancia_archivo: []
                                                };
                                                $('#modalPersonas').modal('hide');
                                                setTimeout(() => {
                                                    $('#modalEntidadActividadAdd').modal('show');
                                                }, 1000);
                                            } else if (TIPO == "_get_domicilio") {
                                                this.dataInmuebleSearch = response.data.data;
                                            }
                                        } else {
                                            if (TIPO == "_get_persona_nacionalidad_nombres") {
                                                this.dataPersonasSearch = response.data.data;
                                            } else if (TIPO == "_get_persona_extranjero" || TIPO == "_get_persona_peru") {
                                                this.dataPersonas.push(response.data.data);
                                                $('#modalPersonas').modal('hide');
                                                setTimeout(() => {
                                                    $('#modalADD').modal('show');
                                                }, 1000);
                                            } else if (TIPO == "_get_domicilio") {
                                                this.dataInmuebleSearch = response.data.data;
                                                console.log(this.dataInmuebleSearch);
                                            }
                                        }
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: `No hay información`,
                                            icon: 'error',
                                            confirmButtonText: '¡Entendido!',
                                        });
                                    }
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingPersonas = false;
                            });
                    },
                    BuscarPersonasAddExpediente(DATA, TIPO) {
                        this.dfnva_persona = {};
                        this.dfnva_inmueble = {};
                        this.dfnva_vehiculo = {};
                        if (TIPO == "PERSONAS_dfnva") {
                            this.dfnva_persona = {
                                ...DATA,
                                detalle: "",
                                get_nueva_vigilancia_archivo: []
                            };
                            $('#modalPersonas').modal('hide');
                            setTimeout(() => {
                                $('#modalEntidadActividadAdd').modal('show');
                            }, 1000);
                            return true;
                        }
                        if (TIPO == "INMUEBLE_dfnva") {
                            this.dfnva_inmueble = {
                                ...DATA,
                                detalle: "",
                                get_nueva_vigilancia_archivo: []
                            };
                            $('#modalInmueble').modal('hide');
                            setTimeout(() => {
                                $('#modalEntidadActividadAdd').modal('show');
                            }, 1000);
                            return true;
                        }
                        if (TIPO == "VEHICULO_dfnva") {
                            this.dfnva_vehiculo = {
                                ...DATA,
                                detalle: "",
                                get_nueva_vigilancia_archivo: []
                            };
                            $('#modalVehiculo').modal('hide');
                            setTimeout(() => {
                                $('#modalEntidadActividadAdd').modal('show');
                            }, 1000);
                            return true;
                        }


                        if (TIPO == "PERSONAS") {
                            this.dataPersonas.push(DATA);
                        } else if (TIPO == "INMUEBLE") {
                            this.dataInmueble.push(DATA);
                        } else if (TIPO == "VEHICULO") {
                            this.dataVehiculo.push(DATA);
                        } else if (TIPO == "DELITO") {
                            return this.dataDelitos.push(DATA);
                        }
                        Swal.fire({
                            title: 'Felicidades',
                            text: 'Agregado al expediente',
                            icon: 'success',
                            confirmButtonText: '¡Entendido!',
                        });

                    },
                    BuscarPersonasDeleteExpediente(INDEX, TIPO) {
                        if (TIPO == "PERSONAS") {
                            this.dataPersonas.splice(INDEX, 1);
                        } else if (TIPO == "INMUEBLE") {
                            this.dataInmueble.splice(INDEX, 1);
                        } else if (TIPO == "VEHICULO") {
                            this.dataVehiculo.splice(INDEX, 1);
                        } else if (TIPO == "DELITO") {
                            this.dataDelitos.splice(INDEX, 1);
                        }
                    },
                    Get() {
                        this.loadingTable = true;
                        const formData = new FormData();
                        formData.append('dd', "_SAVE");
                        formData.append('type', "_EXPEDIENTES");
                        axios.post(URL_REGISTRAR, formData)
                            .then(response => {
                                if (response.data.error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: `${JSON.stringify(response.data.error)}`,
                                        icon: 'info',
                                        confirmButtonText: '¡Entendido!',
                                    });
                                } else {
                                    this.data.tableData = response.data.data;
                                    this.expe_culminados = this.filtrarPorEstado(2);
                                    this.expe_pendientes = this.filtrarPorEstado(1);
                                    this.dataFiscales = response.data.data_fiscal;
                                    this.dataTipoDocumentos = response.data.data_tipo_documentos;
                                    this.dataTipoVideovigilancia = response.data.data_tipo_videovigilancia;
                                    // const DATARETURN = response.data.data;
                                    this.data_tipo_delitos = response.data.data_tipo_delitos;
                                    this.data_dist = response.data.data_dist;
                                    this.data_dep = response.data.data_dep;
                                    this.data_prov = response.data.data_prov;
                                    this.data_documento_identidad = response.data.data_documento_identidad;
                                    this.data_inmueble = response.data.data_inmueble;
                                    this.data_nacionalidad = response.data.data_nacionalidad;
                                    // const index = this.BuscarIndice(DATARETURN.id);
                                    this.data_grado = response.data.data_grado;
                                    this.data_unidad = response.data.data_unidad;
                                    this.data_policia = response.data.data_policia;
                                    this.data_tipo_contenido = response.data.data_tipo_contenido;

                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error',
                                    text: `Error al realizar la solicitud: ${JSON.stringify(error)}`,
                                    icon: 'error',
                                    confirmButtonText: '¡Entendido!',
                                });
                            }).finally(() => {
                                this.loadingTable = false;
                            });
                    },
                    filtrarPorEstado(estado) {
                        // Utiliza el método filter para obtener solo los objetos con el estado deseado
                        var nuevaData = this.data.tableData.filter(item => item.estado_id === estado);
                        return nuevaData.length;
                    },
                    BuscarIndice(objetoABuscar_id) {
                        const objetoABuscar = this.data.tableData;
                        const indice = objetoABuscar.findIndex(objeto => objeto.id === objetoABuscar_id);
                        if (indice !== -1) {
                            return indice;
                        } else {
                            return false;
                        }
                    },
                    EliminarIndice(objetoABuscar_indice) {
                        // if (indice !== -1) {
                        //     var objetoABuscar = this.data.tableData;
                        //     this.data.tableData = objetoABuscar.splice(objetoABuscar_indice, 1);
                        //     return true;
                        // } else {
                        //     return false;
                        // }
                    },
                    EditarIndice(key) {
                        // this.loading = true;
                        // const formData = new FormData();
                        // formData.append('contexto', this.dataEdit.contexto);
                        // formData.append('type', key);
                        // switch (key) {
                        //     case 1:
                        //         formData.append('nombres', this.dataEdit.nombres);
                        //         formData.append('apellidos', this.dataEdit.apellidos);
                        //         break;
                        //     case 2:
                        //         formData.append('phone', this.dataEdit.phone);
                        //         break;
                        //     case 3:
                        //         formData.append('email', this.dataEdit.email);
                        //         break;
                        //     case 4:
                        //         formData.append('unidad_id', this.dataEdit.unidad_id);
                        //         break;
                        //     case 5:
                        //         formData.append('grado_id', this.dataEdit.grado_id);
                        //         break;
                        //     case 6:
                        //         formData.append('perfil_id', this.dataEdit.perfil_id);
                        //         break;
                        //     case 7:
                        //         formData.append('estado_id', this.dataEdit.estado_id);
                        //         break;
                        //     default:
                        //         break;
                        // }

                        // axios.post(URL_REGISTRAR, formData)
                        //     .then(response => {
                        //         if (response.data.error) {
                        //             Swal.fire({
                        //                 title: 'Error',
                        //                 text: `${response.data.error}`,
                        //                 icon: 'info',
                        //                 confirmButtonText: '¡Entendido!',
                        //             });
                        //         } else {
                        //             const DATARETURN = response.data.data;
                        //             console.log(DATARETURN)
                        //             const index = this.BuscarIndice(DATARETURN.id);
                        //             console.log(index)
                        //             this.data.tableData[index].id = DATARETURN.id;
                        //             this.data.tableData[index].nombres = DATARETURN.nombres;
                        //             this.data.tableData[index].apellidos = DATARETURN.apellidos;
                        //             this.data.tableData[index].phone = DATARETURN.phone;
                        //             this.data.tableData[index].email = DATARETURN.email;
                        //             this.data.tableData[index].unidad_id = DATARETURN.unidad_id;
                        //             this.data.tableData[index].grado_id = DATARETURN.grado_id;
                        //             this.data.tableData[index].estado_id = DATARETURN.estado_id;
                        //             this.data.tableData[index].perfil_id = DATARETURN.perfil_id;

                        //             // this.loading = false;
                        //             // this.iniciarRegistroMASPOL = false;
                        //             // this.iniciarRegistroMASPOLSAVE = false;
                        //             // this.iniciarRegistroMANUAL = false;
                        //             // this.iniciarRegistroMANUALSAVE = false;
                        //             // this.numero = 0;
                        //             // this.celular_selecionado = 0;
                        //             // this.dataMASPOL = {};
                        //             // Swal.fire({
                        //             //     title: `USUARIO: ${response.data.user}`,
                        //             //     text: `CONTRASEÑA: ${response.data.password}`,
                        //             //     icon: 'success',
                        //             //     confirmButtonText: '¡Entendido!',
                        //             // });
                        //         }
                        //     })
                        //     .catch(error => {
                        //         Swal.fire({
                        //             title: 'Error',
                        //             text: `Error al realizar la solicitud: ${error}`,
                        //             icon: 'error',
                        //             confirmButtonText: '¡Entendido!',
                        //         });
                        //     }).finally(() => {
                        //         this.loading = false;
                        //         $('#modalDefault').modal('hide');
                        //     });
                    },
                    OpenEdit(data, _TIPO) {
                        if (_TIPO == "VER_HISTORIAL") {
                            this.dataEdit = data;
                            this.ocultar = true;
                        } else if (_TIPO == "EDITAR") {
                            this.dataEdit = data;
                        }
                    },
                    modalOpen(TIPO, INDEX1 = null, INDEX2 = null) {
                        if (TIPO == "TipoVideoVigilanciaEdit") {
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalADDTipoVideoVigilancia').modal('show');
                            }, 1000);
                        } else if (TIPO == "FiscalEdit") {
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalADDFiscal').modal('show');
                            }, 1000);
                        } else if (TIPO == "PersonasEdit") {
                            this.cambiarModoActividades = false;
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalPersonas').modal('show');
                            }, 1000);
                        } else if (TIPO == "InmuebleEdit") {
                            this.cambiarModoActividades = false;
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalInmueble').modal('show');
                            }, 1000);
                        } else if (TIPO == "VehiculoEdit") {
                            this.cambiarModoActividades = false;
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalVehiculo').modal('show');
                            }, 1000);
                        } else if (TIPO == "OficialAdd") {
                            $('#modalADD').modal('hide');
                            setTimeout(() => {
                                $('#modalOficialAdd').modal('show');
                            }, 1000);
                        } else if (TIPO == "FechaContenidoAdd") {
                            $('#modalFechaContenidoAdd').modal('show');
                        } else if (TIPO == "PersonasEdit_actividad") {
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index1 = INDEX1;
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index2 = INDEX2;
                            this.cambiarModoActividades = true;
                            $('#modalPersonas').modal('show');
                        } else if (TIPO == "InmuebleEdit_actividad") {
                            console.log("entro qui")
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index1 = INDEX1;
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index2 = INDEX2;
                            this.cambiarModoActividades = true;
                            $('#modalInmueble').modal('show');
                        } else if (TIPO == "VehiculoEdit_actividad") {
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index1 = INDEX1;
                            this.disposicion_fiscal_nueva_vigilancia_actividads.index2 = INDEX2;
                            this.cambiarModoActividades = true;
                            $('#modalVehiculo').modal('show');
                        }
                    },
                    selectdataTipoVideovigilanciaAdd() {
                        this.dataExpe.selectdataTipoVideovigilancia.push(this.selectdataTipoVideovigilancia);
                    },
                    selectdataTipoVideovigilanciaDelete(indice) {
                        this.dataExpe.selectdataTipoVideovigilancia.splice(indice, 1);
                    },
                    addInput(TIPO) {
                        if (TIPO == "OBJECTOS") {
                            this.dataExpe.selectdataObjetoVideovigilancia.push('');
                        } else if (TIPO == "REFERENCIA") {
                            this.dataExpe.selectdataReferenciaVideovigilancia.push({
                                documentos_id: 0,
                                nro: "",
                                fecha_documento: "",
                                siglas: "",
                                pdf: "",
                                pdfName: "",
                                observaciones: ""
                            });
                        } else if (TIPO == "ARCHIVOS_DIGITALES_ENTIDADES") {
                            this.dfnva_persona.get_nueva_vigilancia_archivo.push({
                                ta_id: 1,
                                pdf: "",
                                pdfName: "",
                            });
                        } else if (TIPO == "ARCHIVOS_DIGITALES_ENTIDADES_vehiculo") {
                            if (!this.dfnva_vehiculo.get_nueva_vigilancia_archivo) {
                                this.dfnva_vehiculo.get_nueva_vigilancia_archivo = [{
                                    ta_id: 1,
                                    pdf: "",
                                    pdfName: "",
                                }];
                            } else {
                                this.dfnva_vehiculo.get_nueva_vigilancia_archivo.push({
                                    ta_id: 1,
                                    pdf: "",
                                    pdfName: "",
                                });
                            }
                        } else if (TIPO == "ARCHIVOS_DIGITALES_ENTIDADES_inmueble") {
                            console.log(this.dfnva_inmueble);
                            console.log("muebleeee")
                            console.log(this.dfnva_inmueble.get_nueva_vigilancia_archivo)
                            if (this.dfnva_inmueble.get_nueva_vigilancia_archivo) {
                                this.dfnva_inmueble.get_nueva_vigilancia_archivo.push({
                                    ta_id: 1,
                                    pdf: "",
                                    pdfName: "",
                                });
                            } else {
                                this.dfnva_inmueble.get_nueva_vigilancia_archivo = [{
                                    ta_id: 1,
                                    pdf: "",
                                    pdfName: "",
                                }];

                            }

                        } else if (TIPO == "ARCHIVOS_FINISH") {
                            this.dataFinish.files.push({
                                contenidos_id: 0,
                                archivo: ""
                            });
                        }
                    },
                    removeInput(TIPO, index) {
                        if (TIPO == "OBJECTOS") {
                            this.dataExpe.selectdataObjetoVideovigilancia.splice(index, 1); // Elimina el input en el índice especificado
                        } else if (TIPO == "REFERENCIA") {
                            this.dataExpe.selectdataReferenciaVideovigilancia.splice(index, 1);
                        } else if (TIPO == "ARCHIVOS_FINISH") {
                            this.dataFinish.files.splice(index, 1);
                        }
                    },
                    handleFileChange(index, e, ISTO = null) {
                        // Accede a la información del archivo a través del evento
                        const file = e.target.files[0];
                        if (ISTO == "_NUEVAFVV") {
                            if (file && file.type === "application/pdf") {
                                this.nuevafechadocumentovideovigilancia.pdfName = file.name || "Archivo desconocido";
                                this.nuevafechadocumentovideovigilancia.pdf = file;
                            }
                        } else if (ISTO == "_NEWFILE_ENTIDAD") {
                            this.dfnva_persona.get_nueva_vigilancia_archivo[index].pdfName = file.name || "Archivo desconocido";
                            this.dfnva_persona.get_nueva_vigilancia_archivo[index].pdf = file;
                        } else if (ISTO == "_NEWFILE_ENTIDA_INMUEBLE") {
                            this.dfnva_inmueble.get_nueva_vigilancia_archivo[index].pdfName = file.name || "Archivo desconocido";
                            this.dfnva_inmueble.get_nueva_vigilancia_archivo[index].pdf = file;
                        } else if (ISTO == "_NEWFILE_ENTIDA_VEHICULO") {
                            this.dfnva_vehiculo.get_nueva_vigilancia_archivo[index].pdfName = file.name || "Archivo desconocido";
                            this.dfnva_vehiculo.get_nueva_vigilancia_archivo[index].pdf = file;
                        } else if (ISTO == "_FINISH_EXPEDIENTE") {
                            this.dataFinish.archivo = file;
                            this.dataFinish.archivo_NAME = file.name;
                        } else {
                            // Verifica si el archivo es un PDF
                            if (file && file.type === "application/pdf") {
                                this.dataExpe.selectdataReferenciaVideovigilancia[index].pdfName = file.name || "Archivo desconocido";
                                this.dataExpe.selectdataReferenciaVideovigilancia[index].pdf = file;
                            } else {

                            }
                        }
                    },
                    handleChangeUbigeoNaci(TIPO, INMUEBLE = null) {
                        if (TIPO == "DEP") {
                            this.data_filtro.provincia_nacimiento = [];
                            this.data_filtro.distrito_nacimiento = [];

                            //filtrar departamento
                            const opciones = this.data_dep;
                            var igualdad;
                            if (INMUEBLE) {
                                igualdad = this.dataInmuebleAdd.departamento;
                            } else {
                                igualdad = this.dataPersonasAdd.departamento_nacimiento;
                            }
                            var indice = opciones.findIndex(function(opcion) {
                                return opcion.opcion == igualdad;
                            });
                            const iddepartamento = this.data_dep[indice].iddepartamento;
                            // console.log(iddepartamento)

                            // traer provincia
                            const opciones2 = this.data_prov;
                            var data2 = opciones2.filter(function(opcion) {
                                return opcion.relacion == iddepartamento;
                            });
                            this.data_filtro.nacimiento.provincia_nacimiento = data2;

                        } else if (TIPO == "PROV") {
                            this.data_filtro.distrito_nacimiento = [];
                            const opciones = this.data_prov;
                            var igualdad;
                            if (INMUEBLE) {
                                igualdad = this.dataInmuebleAdd.provincia;
                            } else {
                                igualdad = this.dataPersonasAdd.provincia_nacimiento;
                            }
                            var indice = opciones.findIndex(function(opcion) {
                                return opcion.opcion == igualdad;
                            });
                            const idprovincia = this.data_prov[indice].idprovincia;
                            // traer distrito
                            const opciones2 = this.data_dist;
                            var data2 = opciones2.filter(function(opcion) {
                                return opcion.relacion == idprovincia;
                            });
                            this.data_filtro.nacimiento.distrito_nacimiento = data2;

                        } else if (TIPO == "DEP_DOM") {

                            this.data_filtro.provincia_domicilio = [];
                            this.data_filtro.distrito_domicilio = [];

                            //filtrar departamento
                            const opciones = this.data_dep;
                            const igualdad = this.dataPersonasAdd.departamento_domicilio;
                            var indice = opciones.findIndex(function(opcion) {
                                return opcion.opcion == igualdad;
                            });
                            const iddepartamento = this.data_dep[indice].iddepartamento;

                            // traer provincia
                            const opciones2 = this.data_prov;
                            var data2 = opciones2.filter(function(opcion) {
                                return opcion.relacion == iddepartamento;
                            });
                            this.data_filtro.domicilio.provincia_domicilio = data2;

                        } else if (TIPO == "PROV_DOM") {
                            this.data_filtro.distrito_domicilio = [];
                            const opciones = this.data_prov;
                            const igualdad = this.dataPersonasAdd.provincia_domicilio;
                            var indice = opciones.findIndex(function(opcion) {
                                return opcion.opcion == igualdad;
                            });
                            const idprovincia = this.data_prov[indice].idprovincia;
                            // traer distrito
                            const opciones2 = this.data_dist;
                            var data2 = opciones2.filter(function(opcion) {
                                return opcion.relacion == idprovincia;
                            });
                            this.data_filtro.domicilio.distrito_domicilio = data2;

                        }

                    },
                    initMap() {
                        if (typeof google !== 'undefined' && google.maps) {
                            const map = new google.maps.Map(document.getElementById('mapDiv'), {
                            center: { lat: -12.100499, lng: -77.0272729 },
                            zoom: 13,
                            });

                            const initialMarker = new google.maps.Marker({
                            position: { lat: -12.100499, lng: -77.0272729 },
                            map: map,
                            title: 'Marcador Inicial',
                            draggable: true,
                            });

                            // Agrega el cuadro de búsqueda
                            const input = document.getElementById('pac-input');
                            const searchBox = new google.maps.places.SearchBox(input);
                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                            // Escucha el evento de cambios en el cuadro de búsqueda
                            map.addListener('bounds_changed', () => {
                            searchBox.setBounds(map.getBounds());
                            });

                            // Escucha el evento de selección de un lugar en el cuadro de búsqueda
                            searchBox.addListener('places_changed', () => {
                            const places = searchBox.getPlaces();

                            if (places.length === 0) {
                                return;
                            }

                            // Mueve el marcador al lugar seleccionado
                            const newLocation = places[0].geometry.location;
                            initialMarker.setPosition(newLocation);
                            map.setCenter(newLocation);

                            // Actualiza la dirección y coordenadas
                            this.dataInmuebleAdd.latitud = newLocation.lat();
                            this.dataInmuebleAdd.longitud = newLocation.lng();
                            this.dataInmuebleAdd.direccion = places[0].formatted_address;
                            });

                            // Agrega evento de arrastre al marcador
                            initialMarker.addListener('dragend', () => {
                            const markerPosition = initialMarker.getPosition();
                            this.dataInmuebleAdd.latitud = markerPosition.lat();
                            this.dataInmuebleAdd.longitud = markerPosition.lng();

                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ location: markerPosition }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                const address = results[0].formatted_address;
                                this.dataInmuebleAdd.direccion = address;
                                } else {
                                console.error('No se pudo obtener la dirección.');
                                }
                            });
                            });
                        }
                    },

                },
                watch: {
                    'dataExpe.fecha_inicio': 'calcularFechaFinal',
                    'dataExpe.plazo': 'calcularFechaFinal',
                },
            });
        }
    </script>
    @endsection