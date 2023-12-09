@extends('layouts.app')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-tables-2@2.3.5/dist/vue-tables-2.min.js"></script>
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
</style>
@endpush
@section('content')
<div class="nk-block-between">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">Expedientes de diposición fiscal</h3>
    </div><!-- .nk-block-head-content -->
    <div class="nk-block-head-content">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalADD"><em class="icon ni ni-plus"></em></button>
    </div><!-- .nk-block-head-content -->
</div>
<div id="miapp" class="mt-3">
    <div class="nk-block">
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
            <div class="col-md-3">
                <div class="card card-bordered ">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-0">
                            <div class="card-title">
                                <h6 class="subtitle">Caducados</h6>
                            </div>
                        </div>
                        <div class="card-amount"><span class="amount" v-text="expe_caducados"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nk-block">
        <p><strong>Expedientes</strong></p>
        <p v-if="situacion" v-text="situacion"></p>
        <div class="card card-bordered card-preview">
            <p v-if="loadingTable"><em class="icon ni ni-loader"></em> Cargando....</p>
            <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
                <div slot="estado" slot-scope="props">
                    <div class="text-success" v-if="props.row.estado_id == 1"> ACTIVO</div>
                    <div class="text-danger" v-if="props.row.estado_id != 1"> INACTIVO</div>
                </div>
                <div slot="get_fiscal" slot-scope="props" v-if="props.row.get_fiscal.id != 1">
                    <span v-text="props.row.get_fiscal.carnet + ' ' +props.row.get_fiscal.nombres + ' ' + props.row.get_fiscal.paterno + ' ' + props.row.get_fiscal.materno + ' ' + props.row.get_fiscal.ficalia"> </span>
                </div>
                <div slot="get_fiscal_adjunto" slot-scope="props" v-if="props.row.get_fiscal_adjunto.id != 1">
                    <span v-text="props.row.get_fiscal_adjunto.carnet + ' ' +props.row.get_fiscal_adjunto.nombres + ' ' + props.row.get_fiscal_adjunto.paterno + ' ' + props.row.get_fiscal_adjunto.materno + ' ' + props.row.get_fiscal_adjunto.ficalia"> </span>
                </div>
                <div slot="opciones" slot-scope="props">
                    <div class="btn-group dropup">
                        <a target="_blank" :href="uriExpe+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a>
                        <a v-on:click="OpenEdit(props.row)" data-toggle="modal" data-target="#modalEdit" class="m-1" style="font-size: 22px;"><em class="icon ni ni-setting"></em></a>
                    </div>
                </div>
            </v-client-table>
        </div>
    </div>
    <div class="modal fade" id="modalADD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-Caso" v-model="dataExpe.caso"><label class="form-label-outlined" for="outlined-Caso">Caso</label></div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-nro" v-model="dataExpe.nro"><label class="form-label-outlined" for="outlined-nro">Número</label></div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <input type="date" class="form-control form-control-xl form-control-outlined" id="outlined-date-picker3" v-model="dataExpe.fecha_disposicion">
                                <label class="form-label-outlined" for="outlined-date-picker3">Fecha de disposición </label>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-resumen" v-model="dataExpe.resumen"><label class="form-label-outlined" for="outlined-resumen">Resumen</label></div>
                        </div>
                      
                       
                    </section>
                    <h5 class="mt-5">Calendario de inicio y termino</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="date" class="form-control form-control-xl form-control-outlined" id="outlined-date-picker" v-model="dataExpe.fecha_inicio"><label class="form-label-outlined" for="outlined-date-picker">Fecha de inicio</label>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap"><input type="number" class="form-control form-control-xl form-control-outlined" id="outlined-plazo" v-model="dataExpe.plazo"><label class="form-label-outlined" for="outlined-plazo">Días de Plazo</label></div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div>
                                <input type="date" class="form-control form-control-xl form-control-outlined" id="outlined-date-picker2" v-model="dataExpe.fecha_termino"><label class="form-label-outlined" for="outlined-date-picker2">Fecha de termino</label>
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
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl form-control-outlined" id="outlined-select" v-model="dataExpe.fiscal_responsable_id">
                                    <option v-for="item in dataFiscales" :key="item.id" :value="item.id" v-text="item.carnet + ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno + ' ' + item.ficalia"></option>
                                </select>
                                <label class="form-label-outlined" for="outlined-select">Fiscal Responsable</label>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl form-control-outlined" data-ui="xl" id="outlined-select2" v-model="dataExpe.fiscal_asistente_id">
                                    <option v-for="item in dataFiscales" :key="item.id" :value="item.id" v-text="item.carnet + ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno + ' ' + item.ficalia"></option>
                                </select>
                                <label class="form-label-outlined" for="outlined-select2">Fiscal Asistente</label>
                            </div>
                        </div>
                        <div class="mt-2 mb-2 ml-2"><a class="form-note pointer" v-on:click="modalOpen('FiscalEdit')"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más fiscales a la base de datos.</a></div>
                    </section>
                    <h5 class="mt-5">Oficial a cargo</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap">
                                <select class="form-control form-control-xl form-control-outlined" id="outlined-select" v-model="dataExpe.oficial_acargo_id">
                                    <option v-for="item in data_policia" :key="item.id" :value="item.id" v-text="item.carnet  + ' ' + item.get_grado.descripcion+ ' ' +item.nombres + ' ' + item.paterno + ' ' + item.materno"></option>
                                </select>
                                <label class="form-label-outlined" for="outlined-select">Oficial a cargo</label>
                            </div>
                        </div>
                        <div class="mt-2 mb-2 ml-2"><a class="form-note pointer" v-on:click="modalOpen('OficialAdd')"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más oficiales a la base de datos.</a></div>
                    </section>
                    <h5 class="mt-5">Observaciones</h5>
                    <section>
                        <div class="col-sm-12 mt-3">
                            <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-observaciones" v-model="dataExpe.observaciones"><label class="form-label-outlined" for="outlined-observaciones">Observaciones</label></div>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Expediente</h5>
                </div>
                <div class="modal-body">
                    <div id="accordion" class="accordion">
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1">
                                <h6 class="title">Editar expediente</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-1" data-parent="#accordion">
                                <div class="accordion-inner">

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-2">
                                <h6 class="title">Editar Situación</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-2" data-parent="#accordion">
                                <div class="accordion-inner">

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
    <div class="modal fade" id="modalADDFiscal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Fiscales</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="number" class="form-control form-control-xl form-control-outlined" id="outlined-carnet" v-model="dataFiscalAdd.carnet"><label class="form-label-outlined" for="outlined-carnet">Carnet</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="number" class="form-control form-control-xl form-control-outlined" id="outlined-dni" v-model="dataFiscalAdd.dni"><label class="form-label-outlined" for="outlined-dni">DNI</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-nombres" v-model="dataFiscalAdd.nombres"><label class="form-label-outlined" for="outlined-nombres">Nombres</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-paterno" v-model="dataFiscalAdd.paterno"><label class="form-label-outlined" for="outlined-paterno">Paterno</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-materno" v-model="dataFiscalAdd.materno"><label class="form-label-outlined" for="outlined-materno">Materno</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="number" class="form-control form-control-xl form-control-outlined" id="outlined-celular" v-model="dataFiscalAdd.celular"><label class="form-label-outlined" for="outlined-celular">Celular</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-correo" v-model="dataFiscalAdd.correo"><label class="form-label-outlined" for="outlined-correo">Correo</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-procedencia" v-model="dataFiscalAdd.procedencia"><label class="form-label-outlined" for="outlined-procedencia">Procedencia</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-ficalia" v-model="dataFiscalAdd.ficalia"><label class="form-label-outlined" for="outlined-ficalia">Ficalia</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-despacho" v-model="dataFiscalAdd.despacho"><label class="form-label-outlined" for="outlined-despacho">Despacho</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-ubigeo" v-model="dataFiscalAdd.ubigeo"><label class="form-label-outlined" for="outlined-ubigeo">Ubigeo</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
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
        <div class="modal-dialog" role="document">
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
        <div class="modal-dialog modal-lg" role="document">
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
                                        <td><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item, 'PERSONAS')">Agregar</button></td>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInmueble" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                                        <td><button class="btn btn-primary" v-on:click="BuscarPersonasAddExpediente(item,'INMUEBLE')">Agregar</button></td>
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
                            </div>
                            <div class="form-control-wrap">
                                <div id="map" style="height: 400px;"></div>
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
        <div class="modal-dialog" role="document">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross"></em> </a>
                <div class="modal-body">
                    <!-- Carnet -->
                    <div class="form-label-group mt-2">
                        <label class="form-label">Carnet</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Carnet" v-model="dataPoliciaAdd.carnet" />
                    </div>

                    <!-- DNI -->
                    <div class="form-label-group mt-2">
                        <label class="form-label">DNI</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el DNI" v-model="dataPoliciaAdd.dni" />
                    </div>

                    <!-- Nombres -->
                    <div class="form-label-group mt-2">
                        <label class="form-label">Nombres</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa los Nombres" v-model="dataPoliciaAdd.nombres" />
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="form-label-group mt-2">
                        <label class="form-label">Apellido Paterno</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" placeholder="Ingresa el Apellido Paterno" v-model="dataPoliciaAdd.paterno" />
                    </div>

                    <!-- Apellido Materno -->
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_REGISTRAR = "{{route('ExpedienteWS')}}";
    const URL_EXPEDIENTE = "{{route('expediente_reporte')}}";

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU0WytTK6kCsmp2NFdOWAMQ8yE7tacQg&libraries=places`;
    script.async = true;
    script.defer = true;
    script.onload = function() {
        initVueSIVIPOL();
    };
    document.head.appendChild(script);

    function initVueSIVIPOL() {
        document.addEventListener('DOMContentLoaded', async function() {
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
                    loadingModalNuevoFiscal: false,
                    loadingSarchInmueble: false,
                    loadingModal: false,
                    loadingBody: false,
                    loadingTable: false,
                    loadingPersonas: false,
                    loadingModalTipoVideoVigilancia: false,
                    dataEdit: {},
                    dataExpe: {
                        fecha_disposicion: "",
                        fiscal_responsable_id: 0,
                        fiscal_asistente_id: 0,
                        oficial_acargo_id: 1,
                        nro: "",
                        caso: "",
                        resumen: "",
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
                        situacion: ''
                    },
                    data_policia: [],
                    dataInmuebleSearch: [],
                    data_nacionalidad: [],
                    data: {
                        columns: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_fiscal_adjunto', 'estado', 'progreso', 'opciones'],
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
                                get_fiscal_adjunto: 'FISCAL ADJUNTO',
                                estado: 'SITUACION',
                                progreso: 'PROGRESO',
                                opciones: 'OPCIONES',
                            },
                            filterable: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_fiscal_adjunto'],
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
                },
                mounted() {
                    this.Get();
                    this.initMap();
                },
                methods: {
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
                    Grabar() {
                        this.loadingModal = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE");
                        formData.append('dd', "_SAVE");
                        if (this.dataExpe.nro) {
                            formData.append('nro', this.dataExpe.nro);
                        }
                        if (this.dataExpe.caso) {
                            formData.append('caso', this.dataExpe.caso);
                        }
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
                        for (let i = 0; i < this.dataExpe.selectdataReferenciaVideovigilancia.length; i++) {
                            if (this.dataExpe.selectdataReferenciaVideovigilancia[i]) {
                                formData.append(`selectdataReferenciaVideovigilancia_documentos_id_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].documentos_id);
                                formData.append(`selectdataReferenciaVideovigilancia_fecha_documento_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].fecha_documento);
                                formData.append(`selectdataReferenciaVideovigilancia_nro_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].nro);
                                formData.append(`selectdataReferenciaVideovigilancia_pdf_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].pdf);
                                formData.append(`selectdataReferenciaVideovigilancia_siglas_[${i}]`, this.dataExpe.selectdataReferenciaVideovigilancia[i].siglas);
                            }
                        }
                        for (let i = 0; i < this.dataExpe.selectdataTipoVideovigilancia.length; i++) {
                            if (this.dataExpe.selectdataTipoVideovigilancia[i].id) {
                                formData.append(`selectdataTipoVideovigilancia_[${i}]`, this.dataExpe.selectdataTipoVideovigilancia[i].id);
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
                                $('#modalADD').modal('show');
                            });

                    },
                    GrabarTipoVideoVigilancia() {
                        this.loadingModalTipoVideoVigilancia = true;
                        const formData = new FormData();
                        formData.append('type', "_SAVE_TipoVideoVigilancia");
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
                                $('#modalADD').modal('show');
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
                                    this.dataPersonas.push(response.data.data);
                                    console.log(this.dataPersonas)
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
                                $('#modalPersonas').modal('hide');
                                $('#modalADD').modal('show');
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
                                    this.dataInmueble.push(response.data.data);
                                    console.log(this.dataInmueble)
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
                                $('#modalInmueble').modal('hide');
                                $('#modalADD').modal('show');
                            });
                    },
                    GrabarVehiculo(TIPO = null) {
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
                                    this.dataVehiculo.push(response.data.data);
                                    console.log(this.dataVehiculo)
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
                                $('#modalVehiculo').modal('hide');
                                $('#modalADD').modal('show');
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
                                $('#modalADD').modal('show');
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
                                        if (TIPO == "_get_persona_nacionalidad_nombres") {
                                            this.dataPersonasSearch = response.data.data;
                                        } else if (TIPO == "_get_persona_extranjero" || TIPO == "_get_persona_peru") {
                                            this.dataPersonas.push(response.data.data);
                                            $('#modalPersonas').modal('hide');
                                            $('#modalADD').modal('show');
                                        } else if (TIPO == "_get_domicilio") {
                                            this.dataInmuebleSearch = response.data.data;
                                            console.log(this.dataInmuebleSearch);
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
                        // const objetoABuscar = this.data.tableData;
                        // const indice = objetoABuscar.findIndex(objeto => objeto.id === objetoABuscar_id);
                        // if (indice !== -1) {
                        //     return indice;
                        // } else {
                        //     return false;
                        // }
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
                    OpenEdit(data) {
                        console.log(data);
                        this.dataEdit = data;
                    },
                    modalOpen(TIPO) {
                        if (TIPO == "TipoVideoVigilanciaEdit") {
                            $('#modalADD').modal('hide');
                            $('#modalADDTipoVideoVigilancia').modal('show');
                        } else if (TIPO == "FiscalEdit") {
                            $('#modalADD').modal('hide');
                            $('#modalADDFiscal').modal('show');
                        } else if (TIPO == "PersonasEdit") {
                            $('#modalADD').modal('hide');
                            $('#modalPersonas').modal('show');
                        } else if (TIPO == "InmuebleEdit") {
                            $('#modalADD').modal('hide');
                            $('#modalInmueble').modal('show');
                        } else if (TIPO == "VehiculoEdit") {
                            $('#modalADD').modal('hide');
                            $('#modalVehiculo').modal('show');
                        } else if (TIPO == "OficialAdd") {
                            $('#modalADD').modal('hide');
                            $('#modalOficialAdd').modal('show');
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
                        }
                    },
                    removeInput(TIPO, index) {
                        if (TIPO == "OBJECTOS") {
                            this.dataExpe.selectdataObjetoVideovigilancia.splice(index, 1); // Elimina el input en el índice especificado
                        } else if (TIPO == "REFERENCIA") {
                            this.dataExpe.selectdataReferenciaVideovigilancia.splice(index, 1);
                        }
                    },
                    handleFileChange(index, e) {
                        // Accede a la información del archivo a través del evento
                        const file = e.target.files[0];

                        // Verifica si el archivo es un PDF
                        if (file && file.type === "application/pdf") {
                            // Asigna el nombre del archivo a una propiedad pdfName en la estructura de datos
                            this.dataExpe.selectdataReferenciaVideovigilancia[index].pdfName = file.name;
                            // Asigna el objeto de archivo a una propiedad pdf en la estructura de datos
                            this.dataExpe.selectdataReferenciaVideovigilancia[index].pdf = file;
                            // Puedes realizar otras acciones o actualizaciones necesarias aquí
                            // Ejemplo: Muestra el nombre del archivo en la consola
                            console.log(`PDF seleccionado en el índice ${index}: ${file.name}`);
                        } else {
                            // Maneja el caso en que el archivo no es un PDF
                            console.error("Por favor, selecciona un archivo PDF.");
                            // Puedes mostrar un mensaje al usuario o realizar otras acciones
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
                        // Inicializa el mapa de Google
                        const map = new google.maps.Map(document.getElementById('map'), {
                            center: {
                                lat: -12.100499,
                                lng: -77.0272729
                            },
                            zoom: 13,
                        });

                        // Agrega un marcador al centro del mapa
                        const initialMarker = new google.maps.Marker({
                            position: {
                                lat: -12.100499,
                                lng: -77.0272729
                            },
                            map: map,
                            title: 'Marcador Inicial',
                            draggable: true, // Permite mover el marcador
                        });

                        // Agrega evento de arrastre al marcador
                        initialMarker.addListener('dragend', () => {
                            const markerPosition = initialMarker.getPosition();
                            this.dataInmuebleAdd.latitud = markerPosition.lat();
                            this.dataInmuebleAdd.longitud = markerPosition.lng();

                            // Utiliza la API de Geocodificación para obtener la dirección
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({
                                location: markerPosition
                            }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                    const address = results[0].formatted_address;
                                    this.dataInmuebleAdd.direccion = address;
                                    // console.log('Nueva Dirección:', address);
                                } else {
                                    // console.error('No se pudo obtener la dirección.');
                                }
                            });
                        });
                    },
                },
                watch: {
                    'dataExpe.fecha_inicio': 'calcularFechaFinal',
                    'dataExpe.plazo': 'calcularFechaFinal',
                },
            });
        }, false);
    }
</script>
@endsection