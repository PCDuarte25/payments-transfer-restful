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
            'email'     => ['required', 'email'],
            'document'  => [
                'required',
                'string',
                'regex:/^\d{11}$|^\d{3}\.\d{3}\.\d{3}-\d{2}$/'
            ],
            'password'  => ['required', 'string', 'min:6'],
            'user_type' => ['required', Rule::in(['common', 'merchant'])],
        ];
    }

    public function messages()
    {
        return [
            'document.regex' => 'O formato do documento é inválido.',
            'user_type.in' => 'O tipo de usuário deve ser "common" (comum) ou "merchant" (lojista).',
        ];
    }
}
