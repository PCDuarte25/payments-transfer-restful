<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 *
 * Handles the validation logic for user authentication requests.
 * It ensures that the required credentials are provided in the correct format.
 *
 * @package App\Http\Requests
 */
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
     * @return array<string, array<int, mixed>>
     */
    public function rules()
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
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
