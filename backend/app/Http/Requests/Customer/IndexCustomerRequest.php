<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseFormRequest;

class IndexCustomerRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return array_merge(
            $this->paginate(),
            $this->orderBy(),
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