<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateProductService
{
    public function run(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }
}