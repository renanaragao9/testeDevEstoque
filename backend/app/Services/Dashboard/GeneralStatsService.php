<?php

namespace App\Services\Dashboard;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class GeneralStatsService
{
    public function run(): array
    {
        $totalPurchases = Purchase::count();
        $totalSales = Sale::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        return [
            'total_purchases' => $totalPurchases,
            'total_sales' => $totalSales,
            'total_customers' => $totalCustomers,
            'total_products' => $totalProducts,
        ];
    }
}