<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'description' => ['sometimes', 'string', 'max:1000'],
            'price_sale' => ['sometimes', 'numeric', 'min:0'],
            'product_type_id' => ['sometimes', 'exists:product_types,id'],
            'mark_id' => ['sometimes', 'exists:marks,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um produto com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'price_sale.numeric' => 'O preço de venda deve ser um número.',
            'price_sale.min' => 'O preço de venda deve ser maior ou igual a 0.',
            'product_type_id.exists' => 'O tipo de produto selecionado não existe.',
            'mark_id.exists' => 'A marca selecionada não existe.',
        ];
    }
}