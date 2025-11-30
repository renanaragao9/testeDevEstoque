<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseFormRequest;

class StorePurchaseRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'invoice_number' => ['required', 'string', 'unique:purchases,invoice_number'],
            'purchase_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.warehouse_id' => ['required', 'exists:warehouses,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.required' => 'O número da fatura é obrigatório.',
            'invoice_number.string' => 'O número da fatura deve ser um texto.',
            'invoice_number.unique' => 'Este número de fatura já existe.',
            'purchase_date.required' => 'A data da compra é obrigatória.',
            'purchase_date.date' => 'A data da compra deve ser uma data válida.',
            'total_amount.required' => 'O valor total é obrigatório.',
            'total_amount.numeric' => 'O valor total deve ser um número.',
            'total_amount.min' => 'O valor total deve ser maior ou igual a 0.',
            'supplier_id.required' => 'O fornecedor é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado não existe.',
            'items.required' => 'Os itens da compra são obrigatórios.',
            'items.array' => 'Os itens devem ser uma lista.',
            'items.min' => 'Deve haver pelo menos um item.',
            'items.*.product_id.required' => 'O produto é obrigatório para cada item.',
            'items.*.product_id.exists' => 'O produto selecionado não existe.',
            'items.*.warehouse_id.required' => 'O armazém é obrigatório para cada item.',
            'items.*.warehouse_id.exists' => 'O armazém selecionado não existe.',
            'items.*.quantity.required' => 'A quantidade é obrigatória para cada item.',
            'items.*.quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'items.*.quantity.min' => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}