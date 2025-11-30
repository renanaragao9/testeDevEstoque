<?php

namespace App\Http\Requests\ProductType;

use App\Http\Requests\BaseFormRequest;

class StoreProductTypeRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:product_types,name'],
            'description' => ['sometimes', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um tipo de produto com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
        ];
    }
}
