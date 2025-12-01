<?php

namespace App\Services\Dashboard;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesReportService
{
    public function run(): array
    {
        $totalQuantitySold = DB::table('stock_movements')
            ->join('sales', function ($join) {
                $join->on('stock_movements.movimentable_id', '=', 'sales.id')
                    ->where('stock_movements.movimentable_type', '=', 'App\Models\Sale');
            })
            ->where('stock_movements.type', 'out')
            ->where('stock_movements.status', 'completed')
            ->count();

        $totalSalesAmount = Sale::sum('total_amount');

        return [
            'total_quantity_sold' => (int) $totalQuantitySold,
            'total_sales_amount' => (float) $totalSalesAmount,
        ];
    }
}