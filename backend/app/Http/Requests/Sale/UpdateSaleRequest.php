<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $saleId = $this->route('sale')?->id;

        return [
            'invoice_number' => [
                'sometimes',
                'string',
                Rule::unique('sales', 'invoice_number')->ignore($saleId),
            ],
            'sale_date' => ['sometimes', 'date'],
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', 'in:pending,completed,cancelled'],
            'customer_id' => ['sometimes', 'nullable', 'exists:customers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.string' => 'O número da fatura deve ser um texto.',
            'invoice_number.unique' => 'Este número de fatura já existe.',
            'sale_date.date' => 'A data da venda deve ser uma data válida.',
            'total_amount.numeric' => 'O valor total deve ser um número.',
            'total_amount.min' => 'O valor total deve ser maior ou igual a 0.',
            'status.string' => 'O status deve ser um texto.',
            'status.in' => 'O status deve ser pending, completed ou cancelled.',
            'customer_id.exists' => 'O cliente selecionado não existe.',
        ];
    }
}