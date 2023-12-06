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

use App\Models\TipoDocumentosReferencia;
use App\Models\TipoVideoVigilancia;

use App\Models\TipoPlazo;
use App\Models\EntidadPolicia;

use App\Models\TipoDelitos;
use App\Models\Distrito;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoInmueble;
use App\Models\TipoNacionalidad;


use App\Models\EntidadFiscal;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReportesController;

class ExpedienteController extends Controller
{
    public $upload_files;
    public function __construct()
    {
        $this->middleware('auth');
        if(App::environment('local')){
            $this->upload_files = public_path('files');
        }else{
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
                                if($request->selectdataReferenciaVideovigilancia_pdf_[$i]){
                                    $obtenernombre = time().$i.'.'.$request->selectdataReferenciaVideovigilancia_pdf_[$i]->getClientOriginalExtension();
                                    $request->selectdataReferenciaVideovigilancia_pdf_[$i]->move($this->upload_files, $obtenernombre);
                                }else{
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
                }



                $data = DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado'])->where('id', $new->id)->first();
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
                    'data' => DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado'])->orderBy('id', 'desc')->get(),
                    'data_fiscal' => EntidadFiscal::get(),
                    'data_tipo_documentos' => TipoDocumentosReferencia::get(),
                    'data_tipo_videovigilancia' => TipoVideoVigilancia::get(),
                    'data_tipo_delitos' => TipoDelitos::where('id','!=',0)->get(),
                    'data_dist' => Distrito::where('iddistrito','!=',0)->get(),
                    'data_dep' => Departamento::where('iddepartamento','!=',0)->get(),
                    'data_prov' => Provincia::where('idprovincia','!=',0)->get(),
                    'data_documento_identidad' => TipoDocumentoIdentidad::where('tipo','EXTRANJERO')->get(),
                    'data_inmueble' => TipoInmueble::get(),
                    'data_nacionalidad' => TipoNacionalidad::where('id','!=',0)->get(),
                    
                ]);
            } elseif ($request->type && $request->type == "_EXPEDIENTE") {
                return response()->json([
                    'data' => DispocicionFiscal::with(['getFiscal', 'getFiscalAdjunto', 'getPlazo', 'getEstado'])->where('id', $request->id)->first(),
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
