<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class DeletePurchaseService
{
    public function run(Purchase $purchase): void
    {
        $purchase->stockMovements()->delete();
        $purchase->delete();
    }
}