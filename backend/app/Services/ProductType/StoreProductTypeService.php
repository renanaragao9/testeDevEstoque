<?php

namespace App\Services\ProductType;

use App\Models\ProductType;

class StoreProductTypeService
{
    public function run(array $data): ProductType
    {
        return ProductType::create($data);
    }
}
