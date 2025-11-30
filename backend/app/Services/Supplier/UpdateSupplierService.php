<?php

namespace App\Services\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class UpdateSupplierService
{
    public function run(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);

        return $supplier;
    }
}