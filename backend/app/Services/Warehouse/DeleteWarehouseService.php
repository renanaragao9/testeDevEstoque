<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DeleteWarehouseService
{
    public function run(Warehouse $warehouse): void
    {
        $warehouse->delete();
    }
}