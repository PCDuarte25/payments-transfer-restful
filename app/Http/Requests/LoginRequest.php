<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'O campo de email é obrigatório.',
            'email.email'       => 'O campo de email deve ser um endereço de email válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min'      => 'A senha deve ter no mínimo :min caracteres.',
        ];
    }
}
