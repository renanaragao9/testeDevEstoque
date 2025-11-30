<?php

namespace App\Http\Requests\Specification;

use App\Http\Requests\BaseFormRequest;

class StoreSpecificationRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:specifications,name'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'name.unique' => 'Já existe uma especificação com este nome.',
            'is_active.boolean' => 'O status ativo deve ser verdadeiro ou falso.',
        ];
    }
}