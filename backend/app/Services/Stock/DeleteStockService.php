<?php

namespace App\Services\Stock;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DeleteStockService
{
    public function run(Stock $stock): void
    {
        $stock->delete();
    }
}