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
<div id="miapp">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Reporte InfoZona</h3>
            <div class="nk-block-des text-soft">
                <p>Ingresa los datos.</p>
            </div>
        </div>
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li class="nk-block-tools-opt">
                            <button class="btn btn-dark" v-if="block" v-on:click="block = !block"><em class="icon ni ni-arrow-to-up"></em></button>
                            <button class="btn btn-dark" v-if="!block" v-on:click="block = !block"><em class="icon ni ni-arrow-to-down"></em></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section v-if="!block">
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
                <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click="manualFormulario('_get_domicilio')">Buscar</button>
                <p v-if="loading">Cargando....</p>
            </div>
        </div>

    </section>
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
                <td><a class="btn btn-primary" target="_blank" :href="uri_expediente_inmueble+'?contexto='+item.id"><em class="icon ni ni-file-pdf"></em></a></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    const URL_REGISTRAR = "{{route('ExpedienteWS')}}";
    const URL_EXPEDIENTE_PERSONA = "{{route('modulo_reportes_personas_pdf')}}";
    const URL_EXPEDIENTE_INMUEBLE = "{{route('modulo_reportes_inmueble_pdf')}}";
    document.addEventListener('DOMContentLoaded', async function() {
        const jsonObject = @json($data_inmueble);
        new Vue({
            el: '#miapp',
            data: {
                loading: false,
                block: false,
                uri_registrar: URL_REGISTRAR,
                uri_expediente_persona: URL_EXPEDIENTE_PERSONA,
                uri_expediente_inmueble: URL_EXPEDIENTE_INMUEBLE,
                data_inmueble: jsonObject,
                dataInmuebleSearch: [],
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
            },
            methods: {
                manualFormulario(TIPO) {
                    console.log(TIPO)
                    this.dataInmuebleSearch = [];
                    this.loading = true;
                    const formData = new FormData();
                    formData.append('type', "_get_personas");
                    formData.append('subtype', TIPO);
                    formData.append('dd', "_get_personas");
                    if (TIPO == "_get_domicilio") {
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
                                    if (TIPO == "_get_domicilio") {
                                        this.block = true;
                                        this.dataInmuebleSearch = response.data.data;
                                    }
                                } else {
                                    this.block = false;
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
    });
</script>

@endsection