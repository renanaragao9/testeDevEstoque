<?php

namespace App\Services\Supplier;

use App\Models\Supplier;

class StoreSupplierService
{
    public function run(array $data): Supplier
    {
        return Supplier::create($data);
    }
}