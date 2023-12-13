<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use PDOException;
use Illuminate\Database\QueryException;

use App\Models\DispocicionFiscal;
use App\Models\DisposicionFiscalObjetos;
use App\Models\DisposicionFiscalTipoVideoVigilancia;
use App\Models\DisposicionFiscalReferencia;
use App\Models\DisposicionFiscalDelitos;

use App\Models\DisposicionFiscalNuevaVigilancia;
use App\Models\DisposicionFiscalNuevaVigilanciaActividad;
use App\Models\DisposicionFiscalNuevaVigilanciaEntidad;
use App\Models\DisposicionFiscalNuevaVigilanciaArchivo;
use App\Models\DisposicionFiscalDocResultado;
use App\Models\DisposicionFiscalDocResultadoAnexo;

use App\Models\TipoDocumentosReferencia;
use App\Models\TipoVideoVigilancia;

use App\Models\TipoPlazo;
use App\Models\EntidadPolicia;
use App\Models\EntidadPersona;
use App\Models\EntidadInmueble;
use App\Models\EntidadVehiculos;

use App\Models\TipoDelitos;
use App\Models\Distrito;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoInmueble;
use App\Models\TipoNacionalidad;
use App\Models\TipoContenido;

use App\Models\TipoGrado;
use App\Models\TipoUnidad;

use App\Models\DisposicionFiscalEntidadVigilancia;

use App\Models\EntidadFiscal;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReportesController;

class ExpedienteController extends Controller
{
    public $upload_files;
    public function __construct()
    {
        $this->middleware('auth');
        if (App::environment('local')) {
            $this->upload_files = public_path('files');
        } else {
            $this->upload_files = '/files';
        }
    }
    public function expedientes()
    {
        return view('modules.expediente.index');
    }
    public function ExpedienteWS(Request $request)
    {
        try {
            if ($request->type && $request->type == "_SAVE") {
                // return $request->all();
                $new = new DispocicionFiscal;
                $new->caso = $request->caso ? $request->caso :  "Sin caso";
                $new->nro = $request->nro ? $request->nro : "Sin nro";
                $new->fecha_disposicion = $request->fecha_disposicion  ? $request->fecha_disposicion : null;
                $new->fiscal_responsable_id = $request->fiscal_responsable_id ? $request->fiscal_responsable_id : 1;
                $new->oficial_acargo_id = $request->oficial_acargo_id ? $request->oficial_acargo_id : 1;
                $new->fiscal_asistente_id = $request->fiscal_asistente_id  ? $request->fiscal_asistente_id : 1;
                $new->resumen = $request->resumen ? $request->resumen : "Sin resumen";
                $new->observaciones = $request->observaciones  ? $request->observaciones : "Sin observaciones";
                $new->plazo_id = $request->plazo_id  ? $request->plazo_id : 0;
                $new->plazo = $request->plazo ? $request->plazo : 0;
                $new->fecha_inicio = $request->fecha_inicio ? $request->fecha_inicio : null;
                $new->fecha_termino =  $request->fecha_termino ? $request->fecha_termino : null;
                $new->estado_id = 1;
                if ($new->save()) {
                    if ($request->selectdataObjetoVideovigilancia_) {
                        for ($i = 0; $i < count($request->selectdataObjetoVideovigilancia_); $i++) {
                            if ($request->selectdataObjetoVideovigilancia_[$i]) {
                                $objeto = new DisposicionFiscalObjetos;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                $objeto->descripcion = $request->selectdataObjetoVideovigilancia_[$i];
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                    if ($request->selectdataTipoVideovigilancia_) {
                        foreach ($request->selectdataTipoVideovigilancia_ as $key => $value) {
                            $objeto = new DisposicionFiscalTipoVideoVigilancia;
                            $objeto->df_id =  $new->id;
                            $objeto->users_id = Auth::user()->id;
                            $objeto->vv_id = $value;
                            $objeto->estado = 1;
                            $objeto->save();
                        }
                    }
                    if ($request->selectdataReferenciaVideovigilancia_documentos_id_) {
                        for ($i = 0; $i < count($request->selectdataReferenciaVideovigilancia_documentos_id_); $i++) {
                            if ($request->selectdataReferenciaVideovigilancia_documentos_id_[$i]) {
                                if ($request->selectdataReferenciaVideovigilancia_pdf_[$i]) {
                                    $obtenernombre = time() . $i . '.' . $request->selectdataReferenciaVideovigilancia_pdf_[$i]->getClientOriginalExtension();
                                    $request->selectdataReferenciaVideovigilancia_pdf_[$i]->move($this->upload_files, $obtenernombre);
                                } else {
                                    $obtenernombre = null;
                                }

                                $objeto = new DisposicionFiscalReferencia;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                $objeto->documentos_id = $request->selectdataReferenciaVideovigilancia_documentos_id_[$i];
                                $objeto->nro = $request->selectdataReferenciaVideovigilancia_nro_[$i];
                                $objeto->fecha_documento = $request->selectdataReferenciaVideovigilancia_fecha_documento_[$i];
                                $objeto->siglas = $request->selectdataReferenciaVideovigilancia_siglas_[$i];
                                $objeto->pdf = $obtenernombre;
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                    if ($request->dataVehiculo_) {
                        for ($i = 0; $i < count($request->dataVehiculo_); $i++) {
                            if ($request->dataVehiculo_[$i]) {
                                $objeto = new DisposicionFiscalEntidadVigilancia;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                $objeto->entidads_id =  3;
                                $objeto->codigo_relacion =  $request->dataVehiculo_[$i];
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                    if ($request->dataInmueble_) {
                        for ($i = 0; $i < count($request->dataInmueble_); $i++) {
                            if ($request->dataInmueble_[$i]) {
                                $objeto = new DisposicionFiscalEntidadVigilancia;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                $objeto->entidads_id =  4;
                                $objeto->codigo_relacion =  $request->dataInmueble_[$i];
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                    if ($request->dataPersonas_) {
                        for ($i = 0; $i < count($request->dataPersonas_); $i++) {
                            if ($request->dataPersonas_[$i]) {
                                $objeto = new DisposicionFiscalEntidadVigilancia;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                if ($request->dataPersonas_nacionalidad_[$i] == 1) {
                                    $objeto->entidads_id =  1;
                                } else {
                                    $objeto->entidads_id =  2;
                                }
                                $objeto->codigo_relacion =  $request->dataPersonas_[$i];
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                    if ($request->dataDelitos_) {
                        for ($i = 0; $i < count($request->dataDelitos_); $i++) {
                            if ($request->dataDelitos_[$i]) {
                                $objeto = new DisposicionFiscalDelitos;
                                $objeto->df_id =  $new->id;
                                $objeto->users_id = Auth::user()->id;
                                $objeto->delitos_id =  $request->dataDelitos_[$i];
                                $objeto->estado = 1;
                                $objeto->save();
                            }
                        }
                    }
                }
                $data = DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado','getNuevaVigilancia',
                'getNuevaVigilancia.getNuevaVigilanciaActividad'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data, 'request' => $request->all()]);
            } elseif ($request->type && $request->type == "_EdiT") {
                $new = DispocicionFiscal::find($request->id);
                if ($new) {
                    $new->caso = $request->caso ? $request->caso : $new->caso;
                    $new->nro = $request->nro ? $request->nro : $new->nro;
                    $new->fecha_disposicion = $request->fecha_disposicion ? $request->fecha_disposicion : $new->fecha_disposicion;
                    $new->fiscal_responsable_id = $request->fiscal_responsable_id ? $request->fiscal_responsable_id : $new->fiscal_responsable_id;
                    $new->fiscal_asistente_id = $request->fiscal_asistente_id ? $request->fiscal_asistente_id : $new->fiscal_asistente_id;
                    $new->resumen = $request->resumen ? $request->resumen : $new->resumen;
                    $new->observaciones = $request->observaciones ? $request->observaciones : $new->observaciones;
                    $new->plazo_id = $request->plazo_id ? $request->plazo_id : $new->plazo_id;
                    $new->plazo = $request->plazo ? $request->plazo : $new->plazo;
                    $new->plazo_ampliacion = $request->plazo_ampliacion ? $request->plazo_ampliacion : $new->plazo_ampliacion;
                    $new->plazo_reduccion = $request->plazo_reduccion ? $request->plazo_reduccion : $new->plazo_reduccion;
                    $new->fecha_inicio = $request->fecha_inicio ? $request->fecha_inicio : $new->fecha_inicio;
                    $new->fecha_termino = $request->fecha_termino ? $request->fecha_termino : $new->fecha_termino;
                    $new->estado_id = $request->estado_id ? $request->estado_id : $new->estado_id;
                    $new->referencia_fiscal_anterior = $request->referencia_fiscal_anterior ? $request->referencia_fiscal_anterior : $new->referencia_fiscal_anterior;
                    $new->save();
                    return response()->json(['message' => 'Actualización completa', 'data' => $new]);
                } else {
                    return response()->json(['message' => '', 'error' => 'No se encontro el expediente']);
                }
            } elseif ($request->type && $request->type == "_EXPEDIENTES") {
                return response()->json([
                    'data' => DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado','getNuevaVigilancia','getResultado',
                    'getNuevaVigilancia.getNuevaVigilanciaActividad','getNuevaVigilancia.getNuevaVigilanciaActividad.getNuevaVigilanciaEntidad','getNuevaVigilancia.getNuevaVigilanciaActividad.getNuevaVigilanciaEntidad.getNuevaVigilanciaArchivo','getNuevaVigilancia.getNuevaVigilanciaActividad.getNuevaVigilanciaEntidad.getTipoEntidad'])->orderBy('id', 'desc')->get(),
                    'data_fiscal' => EntidadFiscal::get(),
                    'data_tipo_documentos' => TipoDocumentosReferencia::get(),
                    'data_tipo_videovigilancia' => TipoVideoVigilancia::get(),
                    'data_tipo_delitos' => TipoDelitos::where('id', '!=', 0)->get(),
                    'data_dist' => Distrito::where('iddistrito', '!=', 0)->get(),
                    'data_dep' => Departamento::where('iddepartamento', '!=', 0)->get(),
                    'data_prov' => Provincia::where('idprovincia', '!=', 0)->get(),
                    'data_documento_identidad' => TipoDocumentoIdentidad::where('id', '>', 1)->get(),
                    'data_inmueble' => TipoInmueble::get(),
                    'data_nacionalidad' => TipoNacionalidad::where('id', '>', 1)->get(),
                    'data_grado' => TipoGrado::where('id', '!=', 0)->get(),
                    'data_unidad' => TipoUnidad::where('id', '!=', 0)->get(),
                    'data_policia' => EntidadPolicia::with(['getUnidad', 'getGrado'])->get(),
                    'data_tipo_contenido' => TipoContenido::where('id', '!=', 0)->get(),
                ]);
            } elseif ($request->type && $request->type == "_EXPEDIENTE") {
                return response()->json([
                    'data' => DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado','getNuevaVigilancia','getResultado'])->where('id', $request->id)->first(),
                ]);
            } elseif ($request->type && $request->type == "_GETFISCAL") {
                return response()->json([
                    'data' => EntidadFiscal::get(),
                ]);
            } elseif ($request->type && $request->type == "_SAVE_FISCAL") {
                $new = new EntidadFiscal;
                $new->carnet = $request->carnet ? $request->carnet : null;
                $new->dni = $request->dni ? $request->dni :  null;
                $new->nombres = $request->nombres ? $request->nombres :  null;
                $new->paterno = $request->paterno ? $request->paterno : null;
                $new->celular = $request->celular ? $request->celular :  null;
                $new->materno = $request->materno ? $request->materno :  null;
                $new->correo = $request->correo ? $request->correo : null;
                $new->procedencia = $request->procedencia ? $request->procedencia :  null;
                $new->ficalia = $request->ficalia ? $request->ficalia :  null;
                $new->despacho = $request->despacho ? $request->despacho :  null;
                $new->ubigeo = $request->ubigeo ? $request->ubigeo :  null;
                $new->save();
                $data = EntidadFiscal::where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_SAVE_TipoVideoVigilancia") {
                $counter = TipoVideoVigilancia::count();
                $new = new TipoVideoVigilancia;
                $new->id =  $counter;
                $new->descripcion = $request->TipoVideoVigilancia ? $request->TipoVideoVigilancia : null;
                $new->save();
                $data = TipoVideoVigilancia::where('id', $counter)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_SAVE_personas") {
                $new = new EntidadPersona;
                $new->nacionalidad_id = $request->nacionalidad_id ?? 1;
                $new->documento_id = $request->documento_id ?? 1;
                $new->documento = $request->documento ?? null;
                $new->nombres = $request->nombres ?? null;
                $new->paterno = $request->paterno ?? null;
                $new->materno = $request->materno ?? null;
                $new->estado_civil = $request->estado_civil ?? null;
                $new->sexo = $request->sexo ?? null;
                $new->fecha_nacimiento = $request->fecha_nacimiento ?? null;
                $new->ubigeo_nacimiento = $request->ubigeo_nacimiento ?? null;
                $new->departamento_nacimiento = $request->departamento_nacimiento ?? null;
                $new->provincia_nacimiento = $request->provincia_nacimiento ?? null;
                $new->distrito_nacimiento = $request->distrito_nacimiento ?? null;
                $new->lugar_nacimiento = $request->lugar_nacimiento ?? null;
                $new->ubigeo_domicilio = $request->ubigeo_domicilio ?? null;
                $new->departamento_domicilio = $request->departamento_domicilio ?? null;
                $new->provincia_domicilio = $request->provincia_domicilio ?? null;
                $new->distrito_domicilio = $request->distrito_domicilio ?? null;
                $new->lugar_domicilio = $request->lugar_domicilio ?? null;
                $new->save();
                $data = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_get_personas") {
                if ($request->subtype == "_get_persona_peru") {
                    $data = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where([['documento_id', 1], ['documento', $request->documento]])->first();
                } elseif ($request->subtype == "_get_persona_extranjero") {
                    $data = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where([['documento_id', $request->documento_id], ['documento', $request->documento]])->first();
                } elseif ($request->subtype == "_get_persona_nacionalidad_nombres") {
                    $data = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])
                        ->where('nombres', 'like', '%' . $request->nombres . '%')
                        ->where('materno', 'like', '%' . $request->materno . '%')
                        ->where('paterno', 'like', '%' . $request->paterno . '%')
                        ->get();
                } elseif ($request->subtype == "_get_domicilio") {
                    $data = EntidadInmueble::with(['getTipoInmueble'])->where('inmuebles_id', $request->inmuebles_id);

                    if ($request->direccion) {
                        $data = $data->where('direccion', 'like', '%' . $request->direccion . '%');
                    }

                    if ($request->departamento) {
                        $data = $data->where('departamento', 'like', '%' . $request->departamento . '%');
                    }
                    if ($request->provincia) {
                        $data = $data->where('provincia', 'like', '%' . $request->provincia . '%');
                    }
                    if ($request->distrito) {
                        $data = $data->where('distrito', 'like', '%' . $request->distrito . '%');
                    }
                    if ($request->referencia) {
                        $data = $data->where('referencia', 'like', '%' . $request->referencia . '%');
                    }
                    $data =  $data->get();
                } else {
                    return response()->json(['message' => 'Sin coincidencias', 'data' => []]);
                }
                return response()->json(['message' => 'Procesado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_SAVE_inmueble") {
                $new = new EntidadInmueble;
                $new->inmuebles_id = $request->inmuebles_id ?? 1;
                $new->direccion = $request->direccion ?? null;
                $new->departamento = $request->departamento ?? null;
                $new->provincia = $request->provincia ?? null;
                $new->distrito = $request->distrito ?? null;
                $new->referencia = $request->referencia ?? null;
                $new->color_exterior = $request->color_exterior ?? null;
                $new->caracteristicas_especiales = $request->caracteristicas_especiales ?? null;
                $new->estado_conservacion = $request->estado_conservacion ?? null;
                $new->pisos = $request->pisos ?? null;
                $new->latitud = $request->latitud ?? null;
                $new->longitud = $request->longitud ?? null;
                $new->observaciones = $request->observaciones ?? null;
                $new->save();
                $data = EntidadInmueble::with(['getTipoInmueble'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_SAVE_VEHICULO") {
                $new = new EntidadVehiculos;
                $new->placa = $request->placa ?? null;
                $new->serie = $request->serie ?? null;
                $new->numero_motor = $request->numero_motor ?? null;
                $new->color = $request->color ?? null;
                $new->marca = $request->marca ?? null;
                $new->modelo = $request->modelo ?? null;
                $new->ano = $request->ano ?? null;
                $new->tipo_carroceria = $request->tipo_carroceria ?? null;
                $new->vin = $request->vin ?? null;
                $new->tipo_motor = $request->tipo_motor ?? null;
                $new->cilindrada_motor = $request->cilindrada_motor ?? null;
                $new->tipo_combustible = $request->tipo_combustible ?? null;
                $new->tipo_transmision = $request->tipo_transmision ?? null;
                $new->tipo_traccion = $request->tipo_traccion ?? null;
                $new->kilometraje = $request->kilometraje ?? null;
                $new->placaanterior = $request->placaanterior ?? null;
                $new->estado_vehiculo = $request->estado_vehiculo ?? "EN_CIRCULACION";
                $new->save();
                $data = EntidadVehiculos::where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_SEARCH_VEHICULO") {
                $data = EntidadVehiculos::where('placa', $request->placa)->first();
                if ($data) {
                    return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
                } else {
                    return response()->json([
                        'message' => "No se encontro el vehículo",
                        'error' => "No se encontro el vehículo"
                    ]);
                }
            } elseif ($request->type && $request->type == "_SAVE_POLICIA") {
                $new = new EntidadPolicia;
                $new->carnet = $request->carnet;
                $new->dni = $request->dni;
                $new->nombres = $request->nombres ?? null;
                $new->paterno = $request->paterno ?? null;
                $new->materno = $request->materno ?? null;
                $new->grado_id = $request->grado_id;
                $new->unidad_id = $request->unidad_id;
                $new->situacion = $request->situacion ?? null;
                $new->save();
                $data = EntidadPolicia::with(['getUnidad', 'getGrado'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            } elseif ($request->type && $request->type == "_DisposicionFiscalNuevaVigilancia") {
                $new = new DisposicionFiscalNuevaVigilancia;
                $new->df_id = $request->df_id ?? null;
                $new->users_id = Auth::user()->id;
                $new->documentos_id = $request->documentos_id ?? 0;
                $new->numeroDocumento = $request->numeroDocumento ?? 0; // Asigna el valor adecuado para numeroDocumento
                $new->siglasDocumento = $request->siglasDocumento ?? null; // Asigna el valor adecuado para siglasDocumento
                $new->fechaDocumento = $request->fechaDocumento ?? null; // Asigna el valor adecuado para fechaDocumento
                $new->asunto = $request->asunto ?? null; // Asigna el valor adecuado para asunto
                $new->respondea = $request->respondea ?? null; // Asigna el valor adecuado para respondea
                $new->evaluacion = $request->evaluacion ?? null; // Asigna el valor adecuado para evaluacion
                $new->conclusiones = $request->conclusiones ?? null; // Asigna el valor adecuado para conclusiones
                $new->estado = 1;
                if ($new->save()) {
                    if ($request->pdf) {
                        $obtenernombre = time().Auth::user()->id. '.' . $request->pdf->getClientOriginalExtension();
                        $request->pdf->move($this->upload_files, $obtenernombre);
                        $objeto = DisposicionFiscalNuevaVigilancia::find($new->id);
                        $objeto->archivo = $obtenernombre ?? null;  
                        $objeto->save();
                    }
                }
                $data = DisposicionFiscalNuevaVigilancia::with(['getNuevaVigilanciaActividad'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data, 'request' => $request->all()]);
            } elseif ($request->type && $request->type == "_DisposicionFiscalNuevaVigilanciaActividad") {
                $new = new DisposicionFiscalNuevaVigilanciaActividad;
                $new->dfnv_id = $request->dfnv_id;
                $new->users_id = Auth::user()->id;
                if($request->fechahora){
                    $dateTime = new \DateTime($request->fechahora);
                    $mysqlFormattedDate = $dateTime->format('Y-m-d H:i:s'); 
                    $new->fechahora = $mysqlFormattedDate ?? null; 
                }
                $new->estado = 1;
                $new->save();
                $data = DisposicionFiscalNuevaVigilanciaActividad::with(['getNuevaVigilanciaEntidad'])->where('id', $new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data, 'request' => $request->all()]);
            } elseif ($request->type && $request->type == "_DisposicionFiscalNuevaVigilanciaEntidad") {
                $objeto = new DisposicionFiscalNuevaVigilanciaEntidad;
                $objeto->dfnva_id =  $request->dfnva_id;
                $objeto->users_id = Auth::user()->id;
                $objeto->entidads_id =  $request->entidads_id;
                $objeto->codigo_relacion =  $request->codigo_relacion;
                $objeto->detalle =  $request->detalle ?? null;
                $objeto->estado = 1;
                $objeto->save();
                if($objeto->save()){
                    if ($request->get_nueva_vigilancia_archivo_file) {
                        for ($i = 0; $i < count($request->get_nueva_vigilancia_archivo_file); $i++) {
                            if ($request->get_nueva_vigilancia_archivo_file[$i]) {
                                if ($request->get_nueva_vigilancia_archivo_file[$i]) {
                                    $obtenernombre = time() . $i . '.' . $request->get_nueva_vigilancia_archivo_file[$i]->getClientOriginalExtension();
                                    $request->get_nueva_vigilancia_archivo_file[$i]->move($this->upload_files, $obtenernombre);
                                } else {
                                    $obtenernombre = null;
                                }
                                $objeto2 = new DisposicionFiscalNuevaVigilanciaArchivo;
                                $objeto2->dfnve_id =  $objeto->id;
                                $objeto2->users_id = Auth::user()->id;
                                $objeto2->ta_id = $request->get_nueva_vigilancia_archivo_ta_id[$i];
                                $objeto2->archivo = $obtenernombre;
                                $objeto2->estado = 1;
                                $objeto2->save();
                            }
                        }
                    }
                }
                $data = DisposicionFiscalNuevaVigilanciaEntidad::with(['getNuevaVigilanciaArchivo','getTipoEntidad'])->where('id', $objeto->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data, 'request' => $request->all(), 'files' => []]);
            } elseif ($request->type && $request->type == "_VerEntidadArchivos") {
                // $objeto = DisposicionFiscalNuevaVigilanciaEntidad::find($request->ID_ENTIDAD);
                if($request->ID_ENTIDAD == 1 || $request->ID_ENTIDAD == 2){
                    $objeto = EntidadPersona::find($request->ID_CODIGO_RELACION)->with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->first();
                }else if($request->ID_ENTIDAD == 3){
                    $objeto = EntidadVehiculos::find($request->ID_CODIGO_RELACION);
                }else if($request->ID_ENTIDAD == 4){
                    $objeto = EntidadInmueble::find($request->ID_CODIGO_RELACION)->with(['getTipoInmueble'])->first();
                }

                $data = DisposicionFiscalNuevaVigilanciaEntidad::with(['getNuevaVigilanciaArchivo','getNuevaVigilanciaArchivo.getTipoArchivo'])->where('id', $request->ID_CONTEXTO)->first();
                // $data = DisposicionFiscalNuevaVigilanciaArchivo::with(['getTipoArchivo'])->where('id', $objeto->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $objeto, 'files' =>   $data]);
            } elseif ($request->type && $request->type == "_DisposicionFiscalFINISH") {
              
                if ($request->archivo) {
                    $obtenernombre = time() . Auth::user()->id . '.' . $request->archivo->getClientOriginalExtension();
                    $request->archivo->move($this->upload_files, $obtenernombre);
                } else {
                    $obtenernombre = null;
                }

                $new = new DisposicionFiscalDocResultado;
                $new->df_id = $request->df_id;
                $new->documentos_id = $request->documentos_id;
                $new->fecha_documento = $request->fecha_documento;
                $new->asunto = $request->asunto;
                $new->resultadoFinal = $request->resultadoFinal;
                $new->destino = $request->destino;
                $new->archivo = $obtenernombre;
                $new->users_id = Auth::user()->id;
                $new->estado = 1;
                $new->save();

                $new = DispocicionFiscal::find($request->df_id);
                $new->estado_id = 2;
                $new->save();
                
                
                if ($request->dataFinish_archivo_) {
                    for ($i = 0; $i < count($request->dataFinish_archivo_); $i++) {
                            if ($request->dataFinish_archivo_[$i]) {
                                $obtenernombreYO = time() . Auth::user()->id . $i . '.' . $request->dataFinish_archivo_[$i]->getClientOriginalExtension();
                                $request->dataFinish_archivo_[$i]->move($this->upload_files, $obtenernombreYO);
                            } else {
                                $obtenernombreYO = null;
                            }

                            $objeto = new DisposicionFiscalDocResultadoAnexo;
                            $objeto->dfdr_id =  $new->id;
                            $objeto->users_id = Auth::user()->id;
                            $objeto->contenidos_id = $request->dataFinish_tipo_contenido_[$i];
                            $objeto->archivo = $obtenernombreYO;
                            $objeto->estado = 1;
                            $objeto->save();
                    }
                }
              
                return response()->json(['message' => 'Registrado correctamente', 'data' => $new]);

            } elseif ($request->type && $request->type == "_DisposicionFiscalDocResultadoAnexo") {
            } elseif ($request->type && $request->type == "_XD5") {
            } elseif ($request->type && $request->type == "_XD6") {
            } elseif ($request->type && $request->type == "_XD7") {
            }
        } catch (\Throwable $e) {
            $errorInfo = AuditoriaController::detect_errors_return_json($e);
            return response()->json([
                'message' => $request->dd ? $errorInfo : "",
                'error' => "Throwable: No pudimos registrar/actualizar/guardar , vuelve a intentarlo más tarde."
            ]);
        } catch (\Exception $e) {
            $errorInfo = AuditoriaController::detect_errors_return_json($e);
            return response()->json([
                'message' => $request->dd ? $errorInfo : "",
                'error' => "Exception: No pudimos registrar/actualizar/guardar , vuelve a intentarlo más tarde."
            ]);
        } catch (QueryException $e) {
            $errorInfo = AuditoriaController::detect_errors_return_json($e);
            return response()->json([
                'message' => $request->dd ? $errorInfo : "",
                'error' => "QueryException: No pudimos registrar/actualizar/guardar , vuelve a intentarlo más tarde."
            ]);
        } catch (PDOException $e) {
            $errorInfo = AuditoriaController::detect_errors_return_json($e);
            return response()->json([
                'message' => $request->dd ? $errorInfo : "",
                'error' => "PDOException: No pudimos registrar/actualizar/guardar , vuelve a intentarlo más tarde."
            ]);
        }
    }
    public function expediente_reporte(Request $request)
    {
        return ReportesController::expediente_reporte($request->contexto);
    }
}
