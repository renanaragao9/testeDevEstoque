<?php

namespace App\Http\Requests\Warehouse;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $warehouseId = $this->route('warehouse')?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('warehouses', 'name')->ignore($warehouseId),
            ],
            'location' => ['sometimes', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um armazém com este nome.',
            'location.string' => 'A localização deve ser um texto.',
            'location.max' => 'A localização não pode ter mais de 500 caracteres.',
        ];
    }
}