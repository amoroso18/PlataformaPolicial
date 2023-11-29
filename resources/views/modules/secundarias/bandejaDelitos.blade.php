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
    .VueTables .row .col-md-12 {
        display: flex;
        justify-content: space-between;
    }
</style>
@endpush
@section('content')
<div class="nk-block-between">
    <div class="nk-block-head-content">
        <h3 class="nk-block-title page-title">BD: Delitos</h3>
        <div class="nk-block-des text-soft">
            <p>Existe un total de {{$database->count()}} registros</p>
        </div>
    </div>
    <div class="nk-block-head-content">
        <div class="nk-block-head-content">
            <a class="btn btn-primary" data-toggle="modal" data-target="#modalDefault"><em class="icon ni ni-plus"></em></a>
            <button onclick="printDiv()" class="btn btn-info"><em class="icon ni ni-printer-fill"></em></button>
        </div>
    </div>
</div>


<div id="miapp" class="mt-3">
    <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
        <div slot="estado" slot-scope="props">
            <div class="text-success" v-if="props.row.estado == 1"> ACTIVO</div>
            <div class="text-danger" v-if="props.row.estado != 1"> INACTIVO</div>
        </div>
        <div slot="opciones" slot-scope="props">
            <div class="btn-group dropup">
                <!-- <a target="_blank" :href="urlReporte+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a> -->
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
                    <h5 class="modal-title">Nuevo contenido</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mt-3">
                        <div class="form-group">
                            <label class="form-label" for="default-01">Tipo</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="default-01" v-model="dataEdit.tipo">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-group">
                            <label class="form-label" for="default-011">Subtipo</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="default-011" v-model="dataEdit.subtipo">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="form-group">
                            <label class="form-label" for="default-0111">Modalidad</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="default-0111" v-model="dataEdit.modalidad">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3 mb-5">
                        <div class="form-group">
                            <button v-if="!loading" class="btn btn-lg btn-primary btn-block" v-on:click={ADD()}>Agregar</button>
                            <p v-if="loading">Cargando....</p>
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
    document.addEventListener('DOMContentLoaded', async function() {
        Vue.use(VueTables.ClientTable);
        const jsonObject = @json($database);
        const app = new Vue({
            el: '#miapp',
            data: {
                urlReporte: URL_REPORTE,
                data: {
                    columns: ['tipo', 'subtipo', 'modalidad', 'estado', 'opciones'],
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
                            tipo: 'TIPO',
                            subtipo: 'SUBTIPO',
                            modalidad: 'MODALIDAD',
                            estado: 'SITUACION',
                            opciones: 'OPCIONES',
                        },
                        filterable: ['tipo', 'subtipo', 'modalidad'],
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
                loading: true,
                dataEdit:{
                    tipo: "",
                    subtipo: "",
                    modalidad: ""
                }
            },
            methods: {
                ADD(){
                    
                }
            }
        });
    });

    function printDiv() {
        // Obtén el contenido del div que deseas imprimir
        const printContent = document.getElementById('miapp').innerHTML;

        // Crea una nueva ventana para la impresión
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Imprimir</title></head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Espera a que se cargue el contenido antes de imprimir
        printWindow.onload = function() {
            printWindow.print();
            printWindow.onafterprint = function() {
                printWindow.close();
            };
        };
    }
</script>
@endsection