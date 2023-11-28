<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Models\TipoGrado;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReportesController;
class PlataformaController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('modules.dashboard.index');
    }

    public function administrador_consulta_policial(Request $request)
    {
        $TipoGrado = TipoGrado::inRandomOrder()->where("id","!=",0)->first();
        $TipoUnidad = TipoUnidad::inRandomOrder()->where("id","!=",0)->first();
        return response()->json([
            'mensaje' => 'Efectivo policial encontrado',
            'data' => [
                'nombres' => AuditoriaController::generarNombreAleatorio(),
                'apellido_paterno' => AuditoriaController::generarApellidoAleatorio(),
                'apellido_materno' => AuditoriaController::generarApellidoAleatorio(),
                'nacionalidad' => AuditoriaController::generarNacionalidadAleatoria(),
                'grado' =>  $TipoGrado,
                'unidad' =>  $TipoUnidad ,
                'cip' => $request->cip ? $request->cip : rand(200000, 39999999),
                'dni' => $request->dni ? $request->dni :  rand(10000000, 99999999),
            ],
        ]);
    }
    public function administrador_usuarios_registro()
    {
        $TipoGrado = TipoGrado::get();
        $TipoPerfil = TipoPerfil::get();
        $TipoUnidad = TipoUnidad::get();
        $usuarios = User::count();
        return view('modules.administracion.registroUsuarios',compact('TipoGrado','TipoPerfil','TipoUnidad','usuarios'));
    }
    public function administrador_usuarios_registro_save(Request $request)
    {

        $verifiemail = User::where("email", $request->email)->first();
        if($verifiemail){
            return response()->json([
                'error' => 'Ya existe el usuario',
            ]);
        }
        $data = new User();
        $data->dni = $request->dni;
        $data->carnet = $request->carnet;
        $data->nombres = $request->nombres;
        $data->apellidos = $request->apellidos;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->unidad_id = $request->unidad_id;
        $data->perfil_id = $request->perfil_id;
        $data->grado_id = $request->grado_id;
        $data->password =  Hash::make($data->dni);
        $data->estado_id = 1;
        $data->save();
        return response()->json([
            'msg' => 'registrado correctamente',
            'user' => $request->email,
            'password' => $data->dni,
        ]);
    }
    public function administrador_usuarios_bandeja()
    {
        $usuarios = User::get();
        return view('modules.administracion.bandejaUsuarios',compact('usuarios'));
    }
    public function administrador_reporte_usuario(Request $request)
    {
        return ReportesController::reporte($request->contexto);
    }
    public function administrador_reporte_usuarios()
    {
        return ReportesController::reporteUsuarios();
    }
    
}
