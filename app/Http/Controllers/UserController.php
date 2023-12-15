<?php

namespace App\Http\Controllers;

use DB; // usar la base de datos

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest; // login request
use App\Http\Requests\RegisterRequest; // login request
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\EntidadPersona;
use App\Http\Controllers\AuditoriaController;
use App\Enums\AuditoriaUsuariosTipoEnum;

class UserController extends Controller
{
    use AuthenticatesUsers;
    public $date;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->middleware('guest')->except('logout');
    }
    public function ingresar()
    {
        return view('auth.login');
    }
    public function registrarse()
    {
        return view('register.register');
    }
    public function recuperar()
    {
        return view('auth.passwords.email');
    }
    public function ayuda()
    {
        return view('auth.help');
    }
    public function credenciales(LoginRequest $request)
    {
        if (is_numeric($request->email)) {
            $usuario = User::where('celular', $request->email)->first();
        } else {
            $usuario = User::where('email', $request->email)->first();
        }
        if (!$usuario) {
            return back()->with('error', 'No tienes habilitado un usuario!')->with('email', $request->email);
        }
        if ($usuario->estado_id != 1) {
            return back()->with('error', 'Tu usuario está BLOQUEADO!');
        }
        $pass = Hash::check($request->password, $usuario->password);
        if ($pass) {
            Auth::login($usuario);
            $request->session()->regenerate();
            $generate_token = AuditoriaController::token_sesion($usuario->id, 'WEB');
            return response(view('auth.seguridad'))->cookie('token_generate', $generate_token);
        } else {
            return back()->with('error', 'Las credenciales ingresadas no son válidas!');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    public function credenciales_reg(RegisterRequest $request)
    {

        // $usuario = User::where('email',$request->email)->first();
        // if($usuario){
        //     return back()->with('error', 'Este correo ya esta en uso, intenta con otro correo!');
        // }
        // try {
        //     $request->email = strtolower($request->email);
        //     DB::beginTransaction();
        //     $User = new User;
        //     $User->celular = $request->celular;
        //     $User->email = $request->email;
        //     $User->nombres = trim($request->nombres);
        //     $User->apellidos = trim($request->apellidos);
        //     $User->password = Hash::make($request->password);
        //     $User->estado_id     = 1;
        //     $User->nivel_id = 1;    // falta agregar logica
        //     $User->empresa_id = 1;    // falta agregar logica

        //     $User->grado_id     = 0;
        //     $User->departamento_id     = 0;
        //     $User->unidad_id     = 0;
        //     $User->carnet_id     = 0;
        //     $User->documento_id     = 0;

        //     $User->save();
        //     DB::commit();
        //     $logauth = User::where('email',$request->email)->first();
        //     if($User && $logauth){

        //         AuditoriaController::audita_creacion_usuario($logauth->id,$request->email);

        //     }

        //     Auth::login($logauth, true);
        //     $request->session()->regenerate();
        //     $generate_token = Tokens::token_sesion('WEB');
        //     return response(view('plataforma.seguridad'))->cookie('token_generate',$generate_token);
        //  } catch (\Throwable $th) {
        //     DB::rollback();
        //     dd($th);
        //     return back()->with('error', 'Los datos ingresados no son válidos!');
        //  }
    }
    public function recuperar_save(Request $request)
    {
        $usuario = User::where('email', $request->email)->first();
        if (!$usuario) {
            return back()->with('error', 'No tienes habilitado un usuario!');
        }
        if ($usuario->estado_id != 1) {
            return back()->with('error', 'Tu usuario está BLOQUEADO!');
        }
        try {
            DB::beginTransaction();
            $User = User::find($usuario->id);
            $User->reseteo_contrasena = $this->date->format('Y-m-d H:i:s');
            $User->token_seguridad = md5($this->date->format('Y-m-d H:i:s'));
            $User->save();
            if ($User) {
                AuditoriaController::audita_usuario_cambio_contrasena_email($User->id);
                DB::commit();
                if (App::environment('local')) {
                } else {
                    // $enlace = "https://app.com/reseteo-validadar-enlace?token=$User->token_seguridad&date=$User->reseteo_contrasena";
                    // self::mensaje_recuperacion($User->email,"Recuperación de contraseña",$enlace);
                }
                return back()->with('success', 'Felicidad. Mensaje enviado.');
            } else {
                return back()->with('error', 'Tu usuario tiene problemas, contactanos por whatsapp');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return back()->with('error', 'Los datos ingresados no son válidos o la plataforma esta fallando, contactanos si crees que cometimos un error!');
        }
    }
    
}
