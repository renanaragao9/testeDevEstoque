<?php

namespace App\Http\Requests\ProductType;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateProductTypeRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $productTypeId = $this->route('product_type')?->id;

        return [
            'name' => [
                'string',
                'min:3',
                'max:255',
                Rule::unique('product_types', 'name')->ignore($productTypeId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um tipo de produto com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
        ];
    }
}
