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
@endpush
@section('content')
<div class="nk-block-between">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">Expedientes de diposición fiscal</h3>
    </div><!-- .nk-block-head-content -->
    <div class="nk-block-head-content">
        <a class="btn btn-primary" data-toggle="modal" data-target="#modalADD"><em class="icon ni ni-plus"></em></a>
        <a target="_blank" href="{{route('administrador_reporte_usuarios')}}" class="btn btn-info"><em class="icon ni ni-printer-fill"></em></a>
    </div><!-- .nk-block-head-content -->
</div>
<div id="miapp" class="mt-3">
    <div class="nk-block-head-content" v-if="situacion">
        <div class="nk-block-des text-soft">
            <p v-text="situacion"></p>
        </div>
    </div><!-- .nk-block-head-content -->
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
                        <div class="form-group">
                            <label class="form-label" for="default-01">CASO</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="default-01" v-model="dataExpe.caso">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-group">
                            <label class="form-label" for="default-011">NRO</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="default-011" v-model="dataExpe.nro">
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
                uri: URL_REGISTRAR,
                situacion: "",
                loadingBody: false,
                loadingTable: false,
                dataExpe:{
                    nro : "",
                    caso: "",
                    fecha_disposicion: null,
                    fiscal_responsable_id: null,
                    fiscal_asistente_id: null,
                    resumen : "",
                    observaciones: "",
                    plazo_id: null,
                    plazo: 0,
                    fecha_hora_inicio: null,
                    fecha_hora_termino: null,
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