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
        <h3 class="nk-block-title page-title">Cambio de contraseña</h3>
        <div class="nk-block-des text-soft">
            <p>Completa el formulario para cambiar la contraseña</p>
        </div>
    </div>
</div>


<div id="miapp" class="mt-3">
    <div class="col-sm-12 mt-3">
        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-Caso" v-model="dataExpe.anterior"><label class="form-label-outlined" for="outlined-Caso">Contraseña anterior</label></div>
    </div>
    <div class="col-sm-12 mt-3">
        <div class="form-control-wrap"><input type="text" class="form-control form-control-xl form-control-outlined" id="outlined-nro" v-model="dataExpe.nueva"><label class="form-label-outlined" for="outlined-nro">Nueva contraseña</label></div>
    </div>
    <div class="col-sm-12 mt-3">
        <button class="btn btn-primary" v-if="!loadingModal" v-on:click="Grabar">Actualizar</button>
        <p v-if="loadingModal"><em class="icon ni ni-loader"></em> Cargando....</p>
    </div>
</div>


<script>
    const URL = "{!! route('perfil_cambiarpassword_save') !!}";
    const URL_SALIR = "{!! route('salir') !!}";
    document.addEventListener('DOMContentLoaded', async function() {
        const app = new Vue({
            el: '#miapp',
            data: {
                urlDATA: URL,
                dataExpe: {
                    anterior: "",
                    nueva: "",
                },
                loadingModal: false,
            },
            methods: {
                Grabar() {
                    this.loadingModal = true;
                    const formData = new FormData();
                    formData.append('passanter', this.dataExpe.anterior);
                    formData.append('newpass', this.dataExpe.nueva);
                    axios.post(this.urlDATA, formData)
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
                                    window.location.href = URL_SALIR;
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
                            });
                }
            }
        });
    });
</script>
@endsection