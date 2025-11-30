<?php

namespace App\Services\Product;

use App\Models\Product;

class StoreProductService
{
    public function run(array $data): Product
    {
        return Product::create($data);
    }
}