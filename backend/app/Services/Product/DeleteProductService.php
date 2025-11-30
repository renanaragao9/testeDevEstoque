<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DeleteProductService
{
    public function run(Product $product): void
    {
        $product->delete();
    }
}