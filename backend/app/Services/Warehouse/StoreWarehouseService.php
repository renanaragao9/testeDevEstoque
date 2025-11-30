<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;

class StoreWarehouseService
{
    public function run(array $data): Warehouse
    {
        return Warehouse::create($data);
    }
}