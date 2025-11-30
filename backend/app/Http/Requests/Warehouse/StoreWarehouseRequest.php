<?php

namespace App\Http\Requests\Warehouse;

use App\Http\Requests\BaseFormRequest;

class StoreWarehouseRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:warehouses,name'],
            'location' => ['sometimes', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um armazém com este nome.',
            'location.string' => 'A localização deve ser um texto.',
            'location.max' => 'A localização não pode ter mais de 500 caracteres.',
        ];
    }
}