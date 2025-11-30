<?php

namespace App\Http\Requests\Stock;

use App\Http\Requests\BaseFormRequest;

class IndexStockRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            [
                'search' => ['sometimes', 'string', 'max:255'],
            ]
        );
    }

    public function messages(): array
    {
        return [
            'search.string' => 'A busca deve ser um texto.',
            'search.max' => 'A busca não pode ter mais de 255 caracteres.',
        ];
    }
}