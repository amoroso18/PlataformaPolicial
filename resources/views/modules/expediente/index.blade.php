@extends('layouts.app')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-tables-2@2.3.5/dist/vue-tables-2.min.js"></script>
<script>
    const URL_LOGIN = "{!! route('login') !!}";
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response && error.response.status === 401) {
                window.location.href = URL_LOGIN;
            }
            return Promise.reject(error);
        }
    );
</script>
<style>
    /* Ajusta el estilo del input según tus preferencias */
    input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        padding-right: 24px;
        /* Ajusta según el ancho del icono */
    }

    .custom-date-input::before {
        content: '\1F4C5';
        /* Unicode del icono de calendario (puedes cambiarlo según tus preferencias) */
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        color: blue;
        /* Cambia el color del icono según tus preferencias */
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
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-Caso" v-model="dataExpe.caso"><label class="form-label-outlined" for="outlined-Caso">Caso</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-nro" v-model="dataExpe.nro"><label class="form-label-outlined" for="outlined-nro">Número</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker3" v-model="dataExpe.fecha_disposicion"><label class="form-label-outlined" for="outlined-date-picker3">Fecha de disposición</label>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-resumen" v-model="dataExpe.resumen"><label class="form-label-outlined" for="outlined-resumen">Resumen</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-Tipo" v-model="dataExpe.tipo_plazo"><label class="form-label-outlined" for="outlined-Tipo">Tipo de Plazo</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="number" class="form-control form-control-xl form-control-outlined" id="outlined-plazo" v-model="dataExpe.plazo"><label class="form-label-outlined" for="outlined-plazo">Días de Plazo</label></div>
                    </div>

                    <h5 class="mt-3">Referencias</h5>

                    <h5 class="mt-3">Tipo de videovigilancia</h5>

                    <h5 class="mt-3">Objeto de videovigilancia</h5>
                    
                    <h5 class="mt-3">Fiscales</h5>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap">
                            <select class="form-control form-control-xl form-control-outlined" id="outlined-select" v-model="dataExpe.fiscal_responsable_id" @input="handleSelectChange">
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
                    <div class="mt-2 mb-2 ml-2"><a class="form-note" data-toggle="modal" data-target="#modalADDFiscal"> <em class="icon ni ni-plus"></em> Presiona aquí para agregar más fiscales.</a></div>

                    <h5 class="mt-3">Observaciones</h5>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-observaciones" v-model="dataExpe.observaciones"><label class="form-label-outlined" for="outlined-observaciones">Observaciones</label></div>
                    </div>

                    <h5 class="mt-3">Calendario de inicio y termino</h5>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker" v-model="dataExpe.fecha_inicio"><label class="form-label-outlined" for="outlined-date-picker">Fecha de inicio</label>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-right"><em class="icon ni ni-calendar-alt"></em></div><input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker2" v-model="dataExpe.fecha_termino"><label class="form-label-outlined" for="outlined-date-picker2">Fecha de termino</label>
                        </div>
                    </div>
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
</div>

<script>
    const URL_REGISTRAR = "{{route('ExpedienteWS')}}";
    document.addEventListener('DOMContentLoaded', async function() {
        Vue.use(VueTables.ClientTable);
        const app = new Vue({
            el: '#miapp',
            data: {
                expe_culminados: 0,
                expe_pendientes: 0,
                expe_caducados: 0,
                uri: URL_REGISTRAR,
                situacion: "",
                loadingModalNuevoFiscal: false,
                loadingModal: false,
                loadingBody: false,
                loadingTable: false,
                dataEdit: {},
                dataExpe: {
                    fecha_disposicion: null,
                    fiscal_responsable_id: 0,
                    fiscal_asistente_id: 0,
                    nro: "",
                    caso: "",
                    resumen: "",
                    observaciones: "Sin observaciones",
                    plazo_id: 1,
                    plazo: 0,
                    fecha_inicio: null,
                    fecha_termino: null,
                    tipo_plazo: "Días Naturales"
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
                dataFiscales: [],
                dataTipoDocumentos: [],
                dataTipoVideovigilancia: [],
                data: {
                    columns: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_fiscal_adjunto', 'estado','progreso', 'opciones'],
                    tableData: [],
                    options: {
                        toMomentFormat: true,
                        compileTemplates: false,
                        filterByColumn: true,
                        perPage: 20,
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
                        filterable: ['nro', 'caso', 'resumen', 'plazo', 'get_fiscal', 'get_fiscal_adjunto' ],
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
            },
            mounted() {
                this.Get();
            },
            methods: {
                handleSelectChange() {
                    console.log('Selected value:', this.dataExpe.fiscal_responsable_id);
                },
                Grabar() {
                    this.loadingModal = true;
                    const formData = new FormData();
                    formData.append('type', "_SAVE");
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

                    if (this.dataExpe.fiscal_responsable_id) {
                        formData.append('fiscal_responsable_id', this.dataExpe.fiscal_responsable_id);
                    } else {
                        this.loadingModal = false;
                        return alert(this.dataExpe.fiscal_responsable_id)
                    }
                    if (this.dataExpe.fiscal_asistente_id) {
                        formData.append('fiscal_asistente_id', this.dataExpe.fiscal_asistente_id);
                    } else {
                        this.loadingModal = false;
                        return alert(this.dataExpe.fiscal_asistente_id)
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
                                this.data.tableData.push(response.data.data)
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
                                this.dataFiscales.push(response.data.data)
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
                        });

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
                                console.log(this.data.tableData)
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
                }
            }
        });
    });
</script>
@endsection