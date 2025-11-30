<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseFormRequest;

class StoreSupplierRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['nullable', 'email', 'unique:suppliers,email'],
            'phone' => ['nullable', 'string', 'unique:suppliers,phone'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
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