<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
            'payer_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([$this->input('recipient_id')])
            ],
            'recipient_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([$this->input('payer_id')])
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999999.9999',
                'regex:/^\d+(\.\d{1,4})?$/'
            ]
        ];
    }

    public function messages()
    {
        return [
            'payer_id.required' => 'O ID do pagador é obrigatório.',
            'payer_id.integer' => 'O ID do pagador deve ser um número inteiro.',
            'payer_id.exists' => 'O pagador informado não existe.',
            'payer_id.not_in' => 'O pagador não pode ser a mesma pessoa que o recebedor.',

            'recipient_id.required' => 'O ID do recebedor é obrigatório.',
            'recipient_id.integer' => 'O ID do recebedor deve ser um número inteiro.',
            'recipient_id.exists' => 'O recebedor informado não existe.',
            'recipient_id.not_in' => 'O recebedor não pode ser a mesma pessoa que o pagador.',

            'amount.required' => 'O valor da transação é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número.',
            'amount.min' => 'O valor mínimo para transação é :min.',
            'amount.max' => 'O valor máximo para transação é :max.',
            'amount.regex' => 'O valor deve ter no máximo 4 casas decimais.'
        ];
    }


}
