<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DeleteSaleService
{
    public function run(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            $stockIds = $sale->stockMovements()->pluck('stock_id')->toArray();

            $sale->stockMovements()->update(['status' => 'cancelled']);

            if (!empty($stockIds)) {
                Stock::whereIn('id', $stockIds)->update(['is_available_use' => true]);
            }

            $sale->delete();
        });
    }
}