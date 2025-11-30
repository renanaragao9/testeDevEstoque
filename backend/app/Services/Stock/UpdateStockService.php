<?php

namespace App\Services\Stock;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class UpdateStockService
{
    public function run(Stock $stock, array $data): Stock
    {
        $stock->update($data);

        return $stock;
    }
}