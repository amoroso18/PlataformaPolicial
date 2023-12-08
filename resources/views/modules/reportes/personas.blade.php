@extends('layouts.app')


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
@endpush

@section('content')
<div class="nk-block-between">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">Reporte InfoSombra</h3>
        <div class="nk-block-des text-soft">
            <p>Selecciona un tipo de búsqueda e ingresa los datos</p>
        </div>
    </div>

</div>


<div id="miapp">

    <ul class="nav nav-tabs nav-tabs-s2">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabItem9">NOMBRES APELLIDOS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabItem10">DNI</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tabItem9">
            <div class="form-label-group">
                <label class="form-label">Búscar por nombres y apellidos</label>
            </div>
            <div class="form-control-wrap">
                <input type="text" v-model="dataPersonasAdd.nombres" class="form-control form-control-lg" placeholder="Ingresa el nombre" />
            </div>
            <div class="form-control-wrap">
                <input type="text" v-model="dataPersonasAdd.paterno" class="form-control form-control-lg" placeholder="Ingresa el apellido paterno" />
            </div>
            <div class="form-control-wrap">
                <input type="text" v-model="dataPersonasAdd.materno" class="form-control form-control-lg" placeholder="Ingresa el apellido materno" />
            </div>
            <div class="col-sm-12 mt-3 mb-5" v-if="dataPersonasAdd.nombres.length > 0">
                <div class="form-group">
                    <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click="manualFormulario('_get_persona_nacionalidad_nombres')">Buscar</button>
                    <p v-if="loading">Cargando....</p>
                </div>
            </div>

            <table class="table mt-3" v-if="dataPersonasSearch.length > 0">
                <thead>
                    <tr>
                        <th scope="col">Nacionalidad</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in dataPersonasSearch" :key="index" :value="item.id">
                        <td v-text="item.get_tipo_nacionalidad.descripcion"></td>
                        <td>
                            <span v-text="item.documento"></span>
                        </td>
                        <td>
                            <span v-text="item.nombres"></span>,
                            <span v-text="item.paterno"></span>
                            <span v-text="item.materno"></span>
                        </td>
                        <td><a class="btn btn-primary" target="_blank" :href="uri_expediente_persona+'?contexto='+item.id"><em class="icon ni ni-file-pdf"></em></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="tabItem10">
            <div class="form-label-group">
                <label class="form-label">Número de documento de identidad</label>
            </div>
            <div class="form-control-wrap">
                <input type="text" v-model.number="dataPersonasAdd.documento" class="form-control form-control-lg" placeholder="Ingresa el DNI" />
            </div>
            <div class="col-sm-12 mt-3 mb-5">
                <div class="form-group">
                    <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click="manualFormulario('_get_persona_peru')">Buscar</button>
                    <p v-if="loading">Cargando....</p>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    const URL_REGISTRAR = "{{route('ExpedienteWS')}}";
    const URL_EXPEDIENTE_PERSONA = "{{route('modulo_reportes_personas_pdf')}}";
    const URL_EXPEDIENTE_INMUEBLE = "{{route('modulo_reportes_inmueble_pdf')}}";

    function initVueSIVIPOL() {
        document.addEventListener('DOMContentLoaded', async function() {
            new Vue({
                el: '#miapp',
                data: {
                    dataPersonasAdd: {
                        documento: 0,
                        cip: 0,
                        nombres: "",
                        paterno: "",
                        materno: "",
                        documento_id: 0
                    },
                    loading: false,
                    dataPersonasSearch: [],
                    uri_registrar: URL_REGISTRAR,
                    uri_expediente_persona: URL_EXPEDIENTE_PERSONA,
                    uri_expediente_inmueble: URL_EXPEDIENTE_INMUEBLE
                },
                methods: {
                    manualFormulario(TIPO) {
                        console.log(TIPO)
                        this.dataPersonasSearch = [];
                        this.loading = true;
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
                                        } else if (TIPO == "_get_persona_peru") {
                                            const redirectUrl = `${this.uri_expediente_persona}?contexto=${response.data.data.id}`;
                                            window.open(redirectUrl, '_blank');
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
                                this.loading = false;
                            });
                    },
                    BuscarPersonasAddExpediente() {

                    }
                }
            });
        })
    };
    initVueSIVIPOL();
</script>

@endsection