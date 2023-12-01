<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PDOException;
use Illuminate\Database\QueryException;

use App\Models\DispocicionFiscal;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Models\TipoDelitos;
use App\Models\TipoPlazo;
use App\Models\EntidadPolicia;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReportesController;

class ExpedienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function expedientes()
    {
        return view('modules.expediente.index');
    }
    public function ExpedienteWS(Request $request)
    {
        try {
            if($request->type && $request->type == "_SAVE"){
                // return $request->all();
                $new = new DispocicionFiscal;
                $new->caso = $request->caso ? $request->caso:  "Sin caso";
                $new->nro = $request->nro ? $request->nro : "Sin nro";
                if($request->fecha_disposicion){
                    $new->fecha_disposicion = $request->fecha_disposicion;
                }
                if($request->fiscal_responsable_id){
                    $new->fiscal_responsable_id = $request->fiscal_responsable_id;
                }
                $new->fiscal_responsable_id = $request->fiscal_responsable_id ? $request->fiscal_responsable_id : 1;
                $new->fiscal_asistente_id = $request->fiscal_asistente_id  ? $request->fiscal_asistente_id : 1;
                $new->resumen = $request->resumen ? $request->resumen : "Sin resumen";
                $new->observaciones = $request->observaciones  ? $request->observaciones : "Sin observaciones";
                $new->plazo_id = $request->plazo_id  ? $request->plazo_id : 1;
                $new->plazo = $request->plazo ? $request->plazo_id : 0;
                $new->fecha_inicio = $request->fecha_inicio || $request->fecha_inicio != "null"  ? $request->fecha_inicio : null;
                $new->fecha_termino =  $request->fecha_termino || $request->fecha_termino != "null"  ? $request->fecha_termino : null;
                $new->estado_id =1;
                $new->save();

                $data = DispocicionFiscal::with(['getFiscal','getFiscalAdjunto','getPlazo','getEstado'])->where('id',$new->id)->first();
                return response()->json(['message' => 'Registrado correctamente', 'data' => $data]);
            }elseif($request->type && $request->type == "_EdiT"){
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
               
            }elseif($request->type && $request->type == "_EXPEDIENTES"){
                return response()->json([
                    'data' => DispocicionFiscal::with(['getFiscal','getFiscalAdjunto','getPlazo','getEstado'])->get(),
                ]);
            }elseif($request->type && $request->type == "_EXPEDIENTE"){
                return response()->json([
                    'data' => DispocicionFiscal::with(['getFiscal','getFiscalAdjunto','getPlazo','getEstado'])->where('id',$request->id)->first(),
                ]);
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
}
