<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class UpdateWarehouseService
{
    public function run(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);

        return $warehouse;
    }
}