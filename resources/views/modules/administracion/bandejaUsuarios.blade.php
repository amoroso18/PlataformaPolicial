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
                <a target="_blank" href="#" class="m-1" style="font-size: 22px;"><em class="icon ni ni-setting"></em></a>

            </div>
        </div>

    </v-client-table>
</div>


<script>

    const URL_REPORTE = "{!! route('administrador_reporte_usuario') !!}";
    document.addEventListener('DOMContentLoaded', async function() {
         Vue.use(VueTables.ClientTable);
        const jsonObject = @json($usuarios);
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
                            nombres: 'APELLIDOS',
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
                    const indice = arrayDeObjetos.findIndex(objeto => objeto.id === objetoABuscar_id);
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
                EditarIndice() {

                },
            }
        });
    });
</script>
@endsection