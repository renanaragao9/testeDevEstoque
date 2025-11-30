<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\BaseFormRequest;

class StoreSaleRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'invoice_number' => ['required', 'string', 'unique:sales,invoice_number'],
            'sale_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', 'in:pending,completed,cancelled'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.required' => 'O número da fatura é obrigatório.',
            'invoice_number.string' => 'O número da fatura deve ser um texto.',
            'invoice_number.unique' => 'Este número de fatura já existe.',
            'sale_date.required' => 'A data da venda é obrigatória.',
            'sale_date.date' => 'A data da venda deve ser uma data válida.',
            'total_amount.required' => 'O valor total é obrigatório.',
            'total_amount.numeric' => 'O valor total deve ser um número.',
            'total_amount.min' => 'O valor total deve ser maior ou igual a 0.',
            'status.string' => 'O status deve ser um texto.',
            'status.in' => 'O status deve ser pending, completed ou cancelled.',
            'customer_id.exists' => 'O cliente selecionado não existe.',
            'items.required' => 'Os itens da venda são obrigatórios.',
            'items.array' => 'Os itens devem ser uma lista.',
            'items.min' => 'Deve haver pelo menos um item.',
            'items.*.product_id.required' => 'O produto é obrigatório para cada item.',
            'items.*.product_id.exists' => 'O produto selecionado não existe.',
            'items.*.quantity.required' => 'A quantidade é obrigatória para cada item.',
            'items.*.quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'items.*.quantity.min' => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}