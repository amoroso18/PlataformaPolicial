<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Models\TipoGrado;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Models\TipoDelitos;
use App\Models\TipoPlazo;
use App\Models\EntidadPolicia;
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
        $TipoGrado = TipoGrado::inRandomOrder()->where("id", "!=", 0)->first();
        $TipoUnidad = TipoUnidad::inRandomOrder()->where("id", "!=", 0)->first();
        return response()->json([
            'mensaje' => 'Efectivo policial encontrado',
            'data' => [
                'nombres' => AuditoriaController::generarNombreAleatorio(),
                'apellido_paterno' => AuditoriaController::generarApellidoAleatorio(),
                'apellido_materno' => AuditoriaController::generarApellidoAleatorio(),
                'nacionalidad' => AuditoriaController::generarNacionalidadAleatoria(),
                'grado' =>  $TipoGrado,
                'unidad' =>  $TipoUnidad,
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
        return view('modules.administracion.registroUsuarios', compact('TipoGrado', 'TipoPerfil', 'TipoUnidad', 'usuarios'));
    }
    public function administrador_usuarios_registro_save(Request $request) //falta agregar auditoria
    {

        $verifiemail = User::where("email", $request->email)->first();
        if ($verifiemail) {
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
    public function administrador_usuarios_edit_save(Request $request) //falta agregar auditoria
    {
        $edit = User::find($request->contexto);
        switch ($request->type) {
            case "1":
                $edit->nombres = $request->nombres;
                $edit->apellidos = $request->apellidos;
                break;
            case "2":
                $edit->phone = $request->phone;
                break;
            case "3":
                $edit->email = $request->email;
                break;
            case "4":
                $edit->unidad_id = $request->unidad_id;
                break;
            case "5":
                $edit->grado_id = $request->grado_id;
                break;
            case "6":
                $edit->perfil_id = $request->perfil_id;
                break;
            case "7":
                $edit->estado_id = $request->estado_id;
                break;
            default:
                break;
        }

        $edit->save();
        return response()->json([
            'mensaje' => 'Información actualizada',
            'data' => $edit
        ]);
    }
    public function administrador_usuarios_bandeja()
    {
        $usuarios = User::get();
        $TipoGrado = TipoGrado::get();
        $TipoPerfil = TipoPerfil::get();
        $TipoUnidad = TipoUnidad::get();
        return view('modules.administracion.bandejaUsuarios', compact('TipoGrado', 'TipoPerfil', 'TipoUnidad', 'usuarios'));
    }
    public function entidades_policial()
    {
        $database = EntidadPolicia::get();
        return view('modules.entidades.bandejaPolicia', compact('database'));
    }
    public function administrador_reporte_usuario(Request $request)
    {
        return ReportesController::reporte($request->contexto);
    }
    public function administrador_reporte_usuarios()
    {
        return ReportesController::reporteUsuarios();
    }
    public function basededatos_secundarias_delitos()
    {
        $database = TipoDelitos::get();
        return view('modules.secundarias.bandejaDelitos', compact('database'));
    }
    public function basededatos_secundarias_grados()
    {
        $database = TipoGrado::get();
        return view('modules.secundarias.bandejaGrados', compact('database'));
    }
    public function basededatos_secundarias_unidades()
    {
        $database = TipoUnidad::get();
        return view('modules.secundarias.bandejaUnidades', compact('database'));
    }
    public function basededatos_secundarias_perfiles()
    {
        $database = TipoPerfil::get();
        return view('modules.secundarias.bandejaPerfiles', compact('database'));
    }
    public function basededatos_secundarias_plazos()
    {
        $database = TipoPlazo::get();
        return view('modules.secundarias.bandejaPlazos', compact('database'));
    }
    public function basededatos_secundarias_save(Request $request)
    {
        if ($request->type) {
            switch ($request->type) {
                case 'DELITOS':
                    $new = new TipoDelitos;
                    $new->id = TipoDelitos::count();
                    $new->tipo = $request->tipo;
                    $new->subtipo = $request->subtipo;
                    $new->modalidad = $request->modalidad;
                    $new->save();
                    return $new;
                    break;
                case 'UNIDADES':
                    $new = new TipoUnidad;
                    $new->id = TipoUnidad::count();
                    $new->descripcion = $request->descripcion;
                    $new->save();
                    return $new;
                    break;
                case 'PERFILES':
                    $new = new TipoPerfil;
                    $new->id = TipoPerfil::count();
                    $new->descripcion = $request->descripcion;
                    $new->save();
                    return $new;
                    break;
                case 'GRADOS':
                    $new = new TipoGrado;
                    $new->id = TipoGrado::count();
                    $new->descripcion = $request->descripcion;
                    $new->save();
                    return $new;
                    break;
                default:
                    # code...
                    break;
            }
        }
    }
}
