<?php

namespace App\Services\Specification;

use App\Models\Specification;

class StoreSpecificationService
{
    public function run(array $data): Specification
    {
        return Specification::create($data);
    }
}