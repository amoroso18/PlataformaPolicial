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
        <h3 class="nk-block-title page-title">Bandeja de usuario(s)</h3>
        <div class="nk-block-des text-soft">
            <p>Existe un total de {{$usuarios->count()}} usuario(s) creado(s).</p>
        </div>
    </div><!-- .nk-block-head-content -->
    <div class="nk-block-head-content">
        <a href="{{route('administrador_usuarios_registro')}}" class="btn btn-primary"><em class="icon ni ni-plus"></em></a>
        <a target="_blank" href="{{route('administrador_reporte_usuarios')}}" class="btn btn-info"><em class="icon ni ni-printer-fill"></em></a>
    </div><!-- .nk-block-head-content -->
</div>


<div id="miapp" class="mt-3">
    <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
        <div slot="estado" slot-scope="props">
            <div class="text-success" v-if="props.row.estado_id == 1"> ACTIVO</div>
            <div class="text-danger" v-if="props.row.estado_id != 1"> INACTIVO</div>
        </div>
        <div slot="opciones" slot-scope="props">
            <div class="btn-group dropup">
                <a target="_blank" :href="urlReporte+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a>
                <a v-on:click="OpenEdit(props.row)" data-toggle="modal" data-target="#modalDefault" class="m-1" style="font-size: 22px;"><em class="icon ni ni-setting"></em></a>

            </div>
        </div>

    </v-client-table>
    <div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Editar usuario</h5>
                </div>
                <div class="modal-body">
                    <div id="accordion" class="accordion">
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1">
                                <h6 class="title">Nombres y Apellidos</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-1" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                            <label class="form-label" for="default-01">Nombres</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="default-01" v-model="dataEdit.nombres">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                            <label class="form-label" for="default-011">Apellidos</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="default-011" v-model="dataEdit.apellidos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(1)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-2">
                                <h6 class="title">Celular</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-2" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                                <input type="text" class="form-control" id="default-02" v-model="dataEdit.phone">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(2)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-3">
                                <h6 class="title">Correo electrónico</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-3" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                                <input type="text" class="form-control" id="default-03" v-model="dataEdit.email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(3)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-5">
                                <h6 class="title">Unidad</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-5" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <select v-model="dataEdit.unidad_id" class="form-control">
                                        <option v-for="option in dataUnidad" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                                    </select>

                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(4)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-4">
                                <h6 class="title">Grado</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-4" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <select v-model="dataEdit.grado_id" class="form-control">
                                        <option v-for="option in dataGrado" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                                    </select>

                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(5)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-6">
                                <h6 class="title">Perfil</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-6" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <select v-model="dataEdit.perfil_id" class="form-control">
                                        <option v-for="option in dataPerfil" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                                    </select>
                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(6)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-7">
                                <h6 class="title">Situación</h6>
                                <span class="accordion-icon"></span>
                            </a>
                            <div class="accordion-body collapse" id="accordion-item-7" data-parent="#accordion">
                                <div class="accordion-inner">
                                    <select v-model="dataEdit.estado_id" class="form-control">
                                        <option :value="1" :key="1">ACTIVO</option>
                                        <option :value="2" :key="2">SUSPENDIDO</option>
                                    </select>
                                    <div class="col-sm-12 mt-3 mb-5">
                                        <div class="form-group">
                                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={EditarIndice(7)}>Editar Usuario</button>
                                            <p v-if="loading">Cargando....</p>
                                        </div>
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
    const URL_REPORTE = "{!! route('administrador_reporte_usuario') !!}";
    const URL_REGISTRAR = "{!! route('administrador_usuarios_edit_save') !!}";
    document.addEventListener('DOMContentLoaded', async function() {
        Vue.use(VueTables.ClientTable);
        const jsonObject = @json($usuarios);
        const TipoGrado = @json($TipoGrado);
        const TipoPerfil = @json($TipoPerfil);
        const TipoUnidad = @json($TipoUnidad);

        const app = new Vue({
            el: '#miapp',
            data: {
                dataUsuarios: jsonObject,
                urlReporte: URL_REPORTE,
                loading: false,
                data: {
                    columns: ['carnet', 'nombres', 'apellidos', 'phone', 'email', 'estado', 'opciones'],
                    tableData: jsonObject,
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
                dataEdit: {
                    contexto: null,
                    nombres: "",
                    apellidos: "",
                    phone: 0,
                    email: "",
                    unidad_id: 0,
                    grado_id: 0,
                    estado_id: 0,
                    perfil_id: 0,
                },
                dataGrado: TipoGrado,
                dataPerfil: TipoPerfil,
                dataUnidad: TipoUnidad,
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