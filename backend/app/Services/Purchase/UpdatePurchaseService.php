<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseService
{
    public function run(Purchase $purchase, array $data): Purchase
    {
        $purchase->update($data);

        return $purchase;
    }
}