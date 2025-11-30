<?php

namespace App\Services\Sale;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class UpdateSaleService
{
    public function run(Sale $sale, array $data): Sale
    {
        $sale->update($data);

        return $sale;
    }
}