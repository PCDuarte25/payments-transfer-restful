<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'document'  => [
                'required',
                'string',
                'unique:users,document',
                'regex:/^\d{11}$|^\d{3}\.\d{3}\.\d{3}-\d{2}$/'
            ],
            'password'  => ['required', 'string', 'min:6'],
            'user_type' => ['required', Rule::in(['common', 'merchant'])],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Este endereço de e-mail já existe.',
            'document.unique' => 'Este CPF/CNPJ já está cadastrado.',
            'document.regex' => 'O formato do documento é inválido.',
            'user_type.in' => 'O tipo de usuário deve ser "common" (comum) ou "merchant" (lojista).',
        ];
    }
}
