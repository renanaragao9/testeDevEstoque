<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('customers', 'phone')->ignore($customerId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'phone.string' => 'O telefone deve ser um texto.',
            'phone.unique' => 'Este telefone já está em uso.',
        ];
    }
}