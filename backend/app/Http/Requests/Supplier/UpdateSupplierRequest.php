<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id;

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
                Rule::unique('suppliers', 'email')->ignore($supplierId),
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('suppliers', 'phone')->ignore($supplierId),
            ],
            'address' => ['nullable', 'string', 'max:500'],
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
            'address.string' => 'O endereço deve ser um texto.',
            'address.max' => 'O endereço não pode ter mais de 500 caracteres.',
        ];
    }
}