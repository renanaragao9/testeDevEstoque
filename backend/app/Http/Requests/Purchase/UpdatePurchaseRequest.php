<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseRequest extends BaseFormRequest
{

    public function rules(): array
    {
        $purchaseId = $this->route('purchase')?->id;

        return [
            'invoice_number' => [
                'sometimes',
                'string',
                Rule::unique('purchases', 'invoice_number')->ignore($purchaseId),
            ],
            'purchase_date' => ['sometimes', 'date'],
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'supplier_id' => ['sometimes', 'exists:suppliers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.string' => 'O número da fatura deve ser um texto.',
            'invoice_number.unique' => 'Este número de fatura já existe.',
            'purchase_date.date' => 'A data da compra deve ser uma data válida.',
            'total_amount.numeric' => 'O valor total deve ser um número.',
            'total_amount.min' => 'O valor total deve ser maior ou igual a 0.',
            'supplier_id.exists' => 'O fornecedor selecionado não existe.',
        ];
    }
}