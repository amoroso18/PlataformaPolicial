<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    // protected $redirectRoute = 'credenciales_pi3';

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'email' => 'email|required|string|min:10|max:40|unique:users',
            'password' => 'required|string|min:5|max:50',
            'celular' => 'required|string|min:9|max:9',
            'nombres' => 'required|string|min:3|max:25',
            'apellidos' => 'required|string|min:3|max:25',
        ];
    }
    public function attributes()
    {
        return [
            'email' => 'CORREO',
            'password' => 'CONTRASEÑA',
            'celular' => 'CELULAR',
            'nombres' => 'NOMBRES',
            'apellidos' => 'APELLIDOS ',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'El :attribute ya está en uso.',
            'email.required' => 'Se requiere que ingreses un usuario',
            'email.string' => 'Debes ingresar el usuario CORRECTAMENTE',
            'password.required' => 'La contraseña es requerida',
            'celular.required' => 'El celular es requerido',
            'nombres.required' => 'El nombre es requerido',
            'apellidos.required' => 'El apellido es requerido',
        ];
    }
}

