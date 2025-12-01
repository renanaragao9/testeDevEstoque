<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DeletePurchaseService
{
    public function run(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $stockIds = $purchase->stockMovements()->pluck('stock_id')->toArray();

            $purchase->stockMovements()->update(['status' => 'cancelled']);

            if (!empty($stockIds)) {
                Stock::whereIn('id', $stockIds)->delete();
            }

            $purchase->delete();
        });
    }
}