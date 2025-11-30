<?php

namespace App\Services\Stock;

use App\Models\Stock;

class StoreStockService
{
    public function run(array $data): Stock
    {
        return Stock::create($data);
    }
}