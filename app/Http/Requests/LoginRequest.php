<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'USUARIO',
            'password' => 'CONTRASEÑA'
        ];
    }

    public function messages()
    {
        return [
          
            'email.required' => 'Se requiere que ingreses un :attribute.',
            'email.email' => 'Debes ingresar un :attribute válido.',
            'password.required' => 'La :attribute es requerida.'
        ];
    }
}
