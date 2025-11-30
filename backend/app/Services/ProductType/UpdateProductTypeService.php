<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use Illuminate\Support\Facades\DB;

class UpdateProductTypeService
{
    public function run(ProductType $productType, array $data): ProductType
    {
        $productType->update($data);

        return $productType;
    }
}
