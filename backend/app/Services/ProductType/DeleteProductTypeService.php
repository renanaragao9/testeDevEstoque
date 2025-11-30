<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use Illuminate\Support\Facades\DB;

class DeleteProductTypeService
{
    public function run(ProductType $productType): void
    {
        $productType->delete();
    }
}
