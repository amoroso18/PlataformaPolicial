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
        <h3 class="nk-block-title page-title">Registro de usuario(s)</h3>
        <div class="nk-block-des text-soft">
            <p>Existe un total de {{$usuarios}} usuario(s) creado(s).</p>
        </div>
    </div><!-- .nk-block-head-content -->
    <div class="nk-block-head-content">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDefault">Buscar efectivo
            policial</button>
    </div><!-- .nk-block-head-content -->
</div>


<div id="miapp">
    <!-- Modal -->
    <div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">Buscar Efectivo policial</h5>

                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs nav-tabs-s2">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabItem9">MASPOL CIP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem10">MASPOL DNI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabItem11">MANUAL</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabItem9">
                            <p class="lead">Al autocompletar los datos, no podrás editar el GRADO y UNIDAD hasta registrarlo como nuevo.</p>

                            <div class="form-label-group">
                                <label class="form-label">Número de carnet</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" v-model.number="numero" class="form-control form-control-lg" placeholder="Ingresa el número de carnet" />
                            </div>
                            <div class="form-group mt-3">
                                <button v-if="!loading && numero > 0" class="btn btn-lg btn-primary btn-block" v-on:click={maspolFormulario(1)}>Buscar</button>
                                <p v-if="loading">Cargando....</p>
                            </div>

                        </div>
                        <div class="tab-pane" id="tabItem10">
                            <p class="lead">Al autocompletar los datos, no podrás editar el GRADO y UNIDAD hasta registrarlo como nuevo.</p>

                            <div class="form-label-group">
                                <label class="form-label">Número de documento de identidad</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" v-model.number="numero" class="form-control form-control-lg" placeholder="Ingresa el DNI" />
                            </div>
                            <div class="form-group mt-3">
                                <button v-if="!loading && numero > 0" class="btn btn-lg btn-primary btn-block" v-on:click={maspolFormulario()}>Buscar</button>
                                <p v-if="loading">Cargando....</p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabItem11">
                            <p class="lead">Al seleccionar el modo de registro manual, podrás editar el usuario antes y después del registro.</p>
                            <p>
                                Ingresa el número de carnet para inicial el registro manual
                            </p>
                            <div class="form-label-group">
                                <label class="form-label">Número de carnet</label>
                            </div>
                            <div class="form-control-wrap">
                                <input type="text" v-model.number="numero" class="form-control form-control-lg" placeholder="Ingresa el CIP" />
                            </div>
                            <div class="form-group mt-3">
                                <button v-if="numero > 0" class="btn btn-lg btn-primary btn-block" v-on:click={manualFormulario()}>Iniciar Registro Manual</button>
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

    <div class="nk-block-head-content mt-3">
        <div class="nk-block-des text-soft"  v-if="iniciarRegistroMASPOL">
            <div class="nk-block">
                <div class="nk-block-head">
                    <h5 class="title">Información Personal</h5>
                    <p>Información extraída del servicio web de MASPOL</p>
                </div><!-- .nk-block-head -->
                <div class="profile-ud-list">
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">Nombres</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.nombres"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">Apellido Paterno</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.apellido_paterno"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">Apellido Materno</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.apellido_materno"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">Grado</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.grado.descripcion"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">Unidad</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.unidad.descripcion"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">CIP</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.cip"></span>
                        </div>
                    </div>
                    <div class="profile-ud-item">
                        <div class="profile-ud wider">
                            <span class="profile-ud-label">DNI</span>
                            <span class="profile-ud-value" v-text="dataMASPOL.dni"></span>
                        </div>
                    </div>
                </div><!-- .profile-ud-list -->
            </div>
            <div class="nk-block-head">
                <h5 class="title">Información de usuario</h5>
                <p>Completa el formulario</p>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="form-label" for="default-01">Celular</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-Celular" v-model="celular_selecionado">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Correo electrónico</label>
                    <div class="form-control-wrap">
                        <input type="email" class="form-control" id="default-Correo" v-model="email_selecionado">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-Perfil">Perfil</label>
                    <div class="form-control-wrap">
                        <select v-model="perfil_selecionado" class="form-control">
                            <option v-for="option in dataPerfil" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3 mb-5">
                <div class="form-group">
                    <button v-if="perfil_selecionado && celular_selecionado  && email_selecionado && !iniciarRegistroMASPOLSAVE" class="btn btn-lg btn-primary btn-block" v-on:click={registrarFormulario()}>Registrar Usuario</button>
                    <p v-if="iniciarRegistroMASPOLSAVE">Cargando....</p>
                </div>
            </div>

        </div>
        <div class="nk-block-des text-soft"  v-if="iniciarRegistroMANUAL">
            <div class="nk-block-head">
                <h5 class="title">Información de usuario</h5>
                <p>Completa el formulario</p>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">CIP</label>

                    <div class="form-control-wrap">
                    <span class="profile-ud-value" v-text="numero"></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Nombres</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-01" v-model="dataMANUAL.nombres">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Apellido Paterno</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-01" v-model="dataMANUAL.apellido_paterno">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Apellido Materno</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-01" v-model="dataMANUAL.apellido_materno">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">DNI</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-01" v-model="dataMANUAL.dni">
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="form-label" for="default-01">Celular</label>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control" id="default-Celular" v-model="celular_selecionado">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Correo electrónico</label>
                    <div class="form-control-wrap">
                        <input type="email" class="form-control" id="default-Correo" v-model="email_selecionado">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-01">Grado</label>
                    <div class="form-control-wrap">
                    <select v-model="dataMANUAL.grado_id" class="form-control">
                            <option v-for="option in dataGrado" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-02">Unidad</label>
                    <div class="form-control-wrap">
                    <select v-model="dataMANUAL.unidad_id" class="form-control">
                            <option v-for="option in dataUnidad" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="default-Perfil">Perfil</label>
                    <div class="form-control-wrap">
                        <select v-model="perfil_selecionado" class="form-control">
                            <option v-for="option in dataPerfil" :value="option.id" :key="option.id" v-text="option.descripcion"></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-3 mb-5">
                <div class="form-group">
                    <button v-if="perfil_selecionado && celular_selecionado  && email_selecionado && !iniciarRegistroMANUALSAVE" class="btn btn-lg btn-primary btn-block" v-on:click={registrarManualFormulario()}>Registrar Usuario</button>
                    <p v-if="iniciarRegistroMANUALSAVE">Cargando....</p>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    const URL_CONSULTA = "{!! route('administrador_consulta_policial') !!}";
    const URL_REGISTRAR = "{!! route('administrador_usuarios_registro_save') !!}";
    const TipoGrado =  @json($TipoGrado);
    const TipoPerfil =   @json($TipoPerfil);
    const TipoUnidad =   @json($TipoUnidad);

    new Vue({
        el: '#miapp',
        data: {
            numero: 0,
            loading: false,
            iniciarRegistroMASPOL: false,
            iniciarRegistroMASPOLSAVE: false,
            iniciarRegistroMANUAL: false,
            iniciarRegistroMANUALSAVE: false,
            dataMASPOL: {},
            dataMANUAL:{
                nombres: "",
                dni: "",
                apellido_paterno: "",
                apellido_materno: "",
                phone: 0,
                email: "",
                unidad_id: 0,
                grado_id: 0,
             },
            perfil_selecionado: 0,
            celular_selecionado: 0,
            email_selecionado: "",
            dataGrado: TipoGrado,
            dataPerfil: TipoPerfil,
            dataUnidad: TipoUnidad,
        },
        methods: {
            maspolFormulario(tipo = null) {

                // Mostrar indicador de carga
                this.loading = true;
                this.perfil_selecionado = 0;
                this.celular_selecionado = 0;
                this.email_selecionado = "";
                this.iniciarRegistroMASPOLSAVE = false;
                var adjunto= "";
                if(tipo == 1){
                    adjunto = `?cip=${this.numero}`;
                }else{
                    adjunto = `?dni=${this.numero}`;
                }

                // Realizar una solicitud POST con Axios
                axios.get(`${URL_CONSULTA}${adjunto}`)
                    .then(response => {
                        console.log(response);
                        // Ocultar indicador de carga
                        this.loading = false;
                        // Cerrar el modal
                        $('#modalDefault').modal('hide');
                        if(response.data.error){
                            return Swal.fire({
                                title: 'Error',
                                text: `${response.data.error}`,
                                icon: 'info',
                                confirmButtonText: '¡Entendido!',

                            });
                        }
                        this.iniciarRegistroMASPOL = true;
                        this.dataMASPOL = response.data.data;
                        // Manejar la respuesta exitosa
                        // this.resultado = 'Edad obtenida: ' + response.data.edad;
                    })
                    .catch(error => {
                        // Ocultar indicador de carga
                        this.loading = false;

                        // Manejar el error
                        // this.resultado = 'Error al realizar la solicitud';
                    });
            },
            manualFormulario() {
                this.loading = false;
                this.perfil_selecionado = 0;
                this.celular_selecionado = 0;
                this.email_selecionado = "";
                this.iniciarRegistroMASPOL = false;
                this.iniciarRegistroMASPOLSAVE = false;
                $('#modalDefault').modal('hide');
                this.iniciarRegistroMANUAL = true;
            },
            registrarFormulario() {
                if (!this.validarCorreoElectronico(this.email_selecionado)) {
                    return Swal.fire({
                        title: 'Error',
                        text: 'Debes ingresar un correo electrónico válido',
                        icon: 'info',
                        confirmButtonText: '¡Entendido!',
                    });
                }
                const formData = new FormData();
                formData.append('dni', this.dataMASPOL.dni);
                formData.append('carnet', this.dataMASPOL.cip);
                formData.append('nombres', this.dataMASPOL.nombres);
                formData.append('apellidos', this.dataMASPOL.apellido_paterno + " " + this.dataMASPOL.apellido_materno);
                formData.append('phone', this.celular_selecionado);
                formData.append('email', this.email_selecionado);
                formData.append('unidad_id', this.dataMASPOL.unidad.id);
                formData.append('perfil_id', this.perfil_selecionado);
                formData.append('grado_id', this.dataMASPOL.grado.id);

                this.iniciarRegistroMASPOLSAVE = true;

                axios.post(URL_REGISTRAR, formData)
                    .then(response => {
                        if (response.data.error) {
                            this.iniciarRegistroMASPOLSAVE = false;
                            return Swal.fire({
                                title: 'Error',
                                text: `${response.data.error}`,
                                icon: 'info',
                                confirmButtonText: '¡Entendido!',

                            });
                        }

                        this.loading = false;
                        this.iniciarRegistroMASPOL = false;
                        this.iniciarRegistroMASPOLSAVE = false;
                        this.numero = 0;
                        this.celular_selecionado = 0;
                        this.dataMASPOL = {};
                        Swal.fire({
                            title: `USUARIO: ${response.data.user}`,
                            text: `CONTRASEÑA: ${response.data.password}`,
                            icon: 'success',
                            confirmButtonText: '¡Entendido!',
                        });
                    })
                    .catch(error => {
                        console.error('Error al realizar la solicitud', error);
                    }).finally(() => {
                        this.iniciarRegistroMASPOLSAVE = false;
                    });

            },
            registrarManualFormulario(){
                if (!this.validarCorreoElectronico(this.email_selecionado)) {
                    return Swal.fire({
                        title: 'Error',
                        text: 'Debes ingresar un correo electrónico válido',
                        icon: 'info',
                        confirmButtonText: '¡Entendido!',
                    });
                }
                this.iniciarRegistroMANUALSAVE = true;

                const formData = new FormData();
                formData.append('dni', this.dataMANUAL.dni);
                formData.append('carnet', this.numero);
                formData.append('nombres', this.dataMANUAL.nombres);
                formData.append('apellidos', this.dataMANUAL.apellido_paterno + " " + this.dataMANUAL.apellido_materno);
                formData.append('phone', this.celular_selecionado);
                formData.append('email', this.email_selecionado);
                formData.append('unidad_id', this.dataMANUAL.unidad_id);
                formData.append('perfil_id', this.perfil_selecionado);
                formData.append('grado_id', this.dataMANUAL.grado_id);

                axios.post(URL_REGISTRAR, formData)
                    .then(response => {
                        if (response.data.error) {
                            this.iniciarRegistroMANUALSAVE = false;
                            return Swal.fire({
                                title: 'Error',
                                text: `${response.data.error}`,
                                icon: 'info',
                                confirmButtonText: '¡Entendido!',

                            });
                        }

                        this.loading = false;
                        this.iniciarRegistroMASPOL = false;
                        this.iniciarRegistroMASPOLSAVE = false;
                        this.iniciarRegistroMANUAL = false;
                        this.iniciarRegistroMANUALSAVE = false;
                        this.numero = 0;
                        this.celular_selecionado = 0;
                        this.dataMASPOL = {};
                        Swal.fire({
                            title: `USUARIO: ${response.data.user}`,
                            text: `CONTRASEÑA: ${response.data.password}`,
                            icon: 'success',
                            confirmButtonText: '¡Entendido!',
                        });
                    })
                    .catch(error => {
                        console.error('Error al realizar la solicitud', error);
                    }).finally(() => {
                        this.iniciarRegistroMANUALSAVE = false;
                    });
            },
            validarCorreoElectronico(correo) {
                // Utilizar una expresión regular simple para verificar si el correo electrónico tiene un formato válido
                const expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return expresionRegular.test(correo);
            },
        }
    });
</script>

@endsection