<?php

namespace App\Services\Specification;

use App\Models\Specification;
use Illuminate\Support\Facades\DB;

class DeleteSpecificationService
{
    public function run(Specification $specification): void
    {
        $specification->delete();
    }
}