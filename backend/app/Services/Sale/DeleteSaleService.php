<?php

namespace App\Services\Sale;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DeleteSaleService
{
    public function run(Sale $sale): void
    {
        $sale->stockMovements()->delete();
        $sale->delete();
    }
}