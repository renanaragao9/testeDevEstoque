<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseFormRequest;

class StoreProductRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price_sale' => ['required', 'numeric', 'min:0'],
            'product_type_id' => ['required', 'exists:product_types,id'],
            'mark_id' => ['required', 'exists:marks,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe um produto com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'price_sale.required' => 'O preço de venda é obrigatório.',
            'price_sale.numeric' => 'O preço de venda deve ser um número.',
            'price_sale.min' => 'O preço de venda deve ser maior ou igual a 0.',
            'product_type_id.required' => 'O tipo de produto é obrigatório.',
            'product_type_id.exists' => 'O tipo de produto selecionado não existe.',
            'mark_id.required' => 'A marca é obrigatória.',
            'mark_id.exists' => 'A marca selecionada não existe.',
        ];
    }
}