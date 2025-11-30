<?php

namespace App\Http\Requests\Stock;

use App\Http\Requests\BaseFormRequest;

class UpdateStockRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'is_available_use' => ['sometimes', 'boolean'],
            'product_id' => ['sometimes', 'exists:products,id'],
            'warehouse_id' => ['sometimes', 'exists:warehouses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'is_available_use.boolean' => 'Disponível para uso deve ser verdadeiro ou falso.',
            'product_id.exists' => 'O produto selecionado não existe.',
            'warehouse_id.exists' => 'O armazém selecionado não existe.',
        ];
    }
}