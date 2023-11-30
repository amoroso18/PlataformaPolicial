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
        <a target="_blank" href="{{route('administrador_reporte_usuarios')}}" class="btn btn-info"><em class="icon ni ni-printer-fill"></em></a>
    </div><!-- .nk-block-head-content -->
</div>
<div id="miapp" class="mt-3">
    <div class="nk-block">
        <p><strong>Resumen de expedientes</strong></p>
        <div class="row g-gs">
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            <div class="col-md-4">
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

    <div class="nk-block">
        <p><strong>Expedientes</strong></p>
        <p v-if="situacion" v-text="situacion"></p>
        <div class="card card-bordered card-preview">
            <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
                <div slot="estado" slot-scope="props">
                    <div class="text-success" v-if="props.row.estado_id == 1"> ACTIVO</div>
                    <div class="text-danger" v-if="props.row.estado_id != 1"> INACTIVO</div>
                </div>
                <div slot="opciones" slot-scope="props">
                    <div class="btn-group dropup">
                        <a target="_blank" :href="urlReporte+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a>
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
                    <h5 class="modal-title">Nuevo expediente</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-Caso" v-model="dataExpe.caso"><label class="form-label-outlined" for="outlined-Caso">Caso</label></div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-nro" v-model="dataExpe.nro"><label class="form-label-outlined" for="outlined-nro">Número</label></div>
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
                    <div class="col-sm-12 mt-3">
                        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-observaciones" v-model="dataExpe.observaciones"><label class="form-label-outlined" for="outlined-observaciones">Observaciones</label></div>
                    </div>
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
                    <h5 class="modal-title">Editar expediente</h5>
                </div>
                <div class="modal-body">

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
                uri: URL_REGISTRAR,
                situacion: "",
                loadingModal: false,
                loadingBody: false,
                loadingTable: false,
                dataExpe: {
                    nro: "",
                    caso: "",
                    fecha_disposicion: null,
                    fiscal_responsable_id: null,
                    fiscal_asistente_id: null,
                    resumen: "",
                    observaciones: "",
                    plazo_id: null,
                    plazo: 0,
                    fecha_inicio: null,
                    fecha_termino: null,
                    tipo_plazo: "Días Naturales"
                },
                data: {
                    columns: ['carnet', 'nombres', 'apellidos', 'phone', 'email', 'estado', 'opciones'],
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
                            nombres: 'NOMBRE',
                            apellidos: 'APELLIDOS',
                            carnet: 'CIP',
                            email: 'EMAIL',
                            phone: 'CELULAR',
                            estado: 'SITUACION',
                            opciones: 'OPCIONES',
                        },
                        filterable: ['carnet', 'nombres', 'apellidos', 'phone', 'email', ],
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
            methods: {
                Grabar(){
                    this.loadingModal = true;
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
                    if (indice !== -1) {
                        var objetoABuscar = this.data.tableData;
                        this.data.tableData = objetoABuscar.splice(objetoABuscar_indice, 1);
                        return true;
                    } else {
                        return false;
                    }
                },
                EditarIndice(key) {
                    this.loading = true;
                    const formData = new FormData();
                    formData.append('contexto', this.dataEdit.contexto);
                    formData.append('type', key);
                    switch (key) {
                        case 1:
                            formData.append('nombres', this.dataEdit.nombres);
                            formData.append('apellidos', this.dataEdit.apellidos);
                            break;
                        case 2:
                            formData.append('phone', this.dataEdit.phone);
                            break;
                        case 3:
                            formData.append('email', this.dataEdit.email);
                            break;
                        case 4:
                            formData.append('unidad_id', this.dataEdit.unidad_id);
                            break;
                        case 5:
                            formData.append('grado_id', this.dataEdit.grado_id);
                            break;
                        case 6:
                            formData.append('perfil_id', this.dataEdit.perfil_id);
                            break;
                        case 7:
                            formData.append('estado_id', this.dataEdit.estado_id);
                            break;
                        default:
                            break;
                    }

                    axios.post(URL_REGISTRAR, formData)
                        .then(response => {
                            if (response.data.error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: `${response.data.error}`,
                                    icon: 'info',
                                    confirmButtonText: '¡Entendido!',
                                });
                            } else {
                                const DATARETURN = response.data.data;
                                console.log(DATARETURN)
                                const index = this.BuscarIndice(DATARETURN.id);
                                console.log(index)
                                this.data.tableData[index].id = DATARETURN.id;
                                this.data.tableData[index].nombres = DATARETURN.nombres;
                                this.data.tableData[index].apellidos = DATARETURN.apellidos;
                                this.data.tableData[index].phone = DATARETURN.phone;
                                this.data.tableData[index].email = DATARETURN.email;
                                this.data.tableData[index].unidad_id = DATARETURN.unidad_id;
                                this.data.tableData[index].grado_id = DATARETURN.grado_id;
                                this.data.tableData[index].estado_id = DATARETURN.estado_id;
                                this.data.tableData[index].perfil_id = DATARETURN.perfil_id;

                                // this.loading = false;
                                // this.iniciarRegistroMASPOL = false;
                                // this.iniciarRegistroMASPOLSAVE = false;
                                // this.iniciarRegistroMANUAL = false;
                                // this.iniciarRegistroMANUALSAVE = false;
                                // this.numero = 0;
                                // this.celular_selecionado = 0;
                                // this.dataMASPOL = {};
                                // Swal.fire({
                                //     title: `USUARIO: ${response.data.user}`,
                                //     text: `CONTRASEÑA: ${response.data.password}`,
                                //     icon: 'success',
                                //     confirmButtonText: '¡Entendido!',
                                // });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: `Error al realizar la solicitud: ${error}`,
                                icon: 'error',
                                confirmButtonText: '¡Entendido!',
                            });
                        }).finally(() => {
                            this.loading = false;
                            $('#modalDefault').modal('hide');
                        });
                },
                OpenEdit(data) {
                    this.dataEdit.contexto = data.id;
                    this.dataEdit.nombres = data.nombres;
                    this.dataEdit.apellidos = data.apellidos;
                    this.dataEdit.phone = data.phone;
                    this.dataEdit.email = data.email;
                    this.dataEdit.unidad_id = data.unidad_id;
                    this.dataEdit.grado_id = data.grado_id;
                    this.dataEdit.estado_id = data.estado_id;
                    this.dataEdit.perfil_id = data.perfil_id;

                }
            }
        });
    });
</script>
@endsection