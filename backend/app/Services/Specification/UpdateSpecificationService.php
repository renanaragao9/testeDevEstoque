<?php

namespace App\Services\Specification;

use App\Models\Specification;
use Illuminate\Support\Facades\DB;

class UpdateSpecificationService
{
    public function run(Specification $specification, array $data): Specification
    {
        $specification->update($data);

        return $specification;
    }
}