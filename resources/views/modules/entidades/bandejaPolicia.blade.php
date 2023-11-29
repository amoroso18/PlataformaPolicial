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
        <h3 class="nk-block-title page-title">ENTIDADES: Efectivos policiales</h3>
        <div class="nk-block-des text-soft">
            <p>Existe un total de {{$database->count()}} registros</p>
        </div>
    </div><!-- .nk-block-head-content -->
    <div class="nk-block-head-content">
        <a target="_blank" href="{{route('administrador_reporte_usuarios')}}" class="btn btn-info"><em class="icon ni ni-printer-fill"></em></a>
    </div><!-- .nk-block-head-content -->
</div>


<div id="miapp" class="mt-3">
    <v-client-table :data="data.tableData" :columns="data.columns" :options="data.options" v-if="data.tableData.length > 0">
        <div slot="opciones" slot-scope="props">
            <div class="btn-group dropup">
                <a target="_blank" :href="urlReporte+'?contexto='+props.row.id" class="m-1" style="font-size: 22px;"><em class="icon ni ni-reports"></em></a>
            </div>
        </div>
    </v-client-table>
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
                    columns: ['carnet','dni', 'nombres', 'paterno', 'materno', 'grado', 'unidad', 'situacion', 'opciones'],
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
                            paterno: 'APELLIDO PATERNO',
                            materno: 'APELLIDO MATERNO',
                            carnet: 'CIP',
                            dni: 'DNI',
                            grado: 'GRADO',
                            unidad: 'UNIDAD',
                            situacion: 'SITUACION',
                            opciones: 'OPCIONES',
                        },
                        filterable: ['carnet', 'dni', 'nombres', 'paterno', 'materno', 'grado', 'unidad', 'situacion'],
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
                
            }
        });
    });
</script>
@endsection