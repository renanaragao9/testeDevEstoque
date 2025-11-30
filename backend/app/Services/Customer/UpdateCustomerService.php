<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class UpdateCustomerService
{
    public function run(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }
}