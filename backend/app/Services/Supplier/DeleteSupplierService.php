<?php

namespace App\Services\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DeleteSupplierService
{
    public function run(Supplier $supplier): void
    {
        $supplier->delete();
    }
}