<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecuperatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'USUARIO',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'El :attribute ya está en uso.',
            'email.required' => 'Se requiere que ingreses un :attribute.',
            'email.email' => 'Debes ingresar un :attribute válido.',
        ];
    }
}
