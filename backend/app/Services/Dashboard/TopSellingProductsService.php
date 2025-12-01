<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TopSellingProductsService
{
    public function run(): array
    {
        $topSellingProducts = DB::table('stock_movements')
            ->join('sales', function ($join) {
                $join->on('stock_movements.movimentable_id', '=', 'sales.id')
                    ->where('stock_movements.movimentable_type', '=', 'App\Models\Sale');
            })
            ->join('stocks', 'stock_movements.stock_id', '=', 'stocks.id')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COUNT(*) as total_sold')
            )
            ->where('stock_movements.type', 'out')
            ->where('stock_movements.status', 'completed')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'total_sold' => (int) $item->total_sold,
                ];
            })
            ->toArray();

        return $topSellingProducts;
    }
}