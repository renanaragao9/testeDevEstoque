<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DeleteCustomerService
{
    public function run(Customer $customer): void
    {
        $customer->delete();
    }
}