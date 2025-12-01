<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportStockExitService
{
    public function run(): array
    {
        $stockExits = DB::table('stock_movements')
            ->join('sales', function ($join) {
                $join->on('stock_movements.movimentable_id', '=', 'sales.id')
                    ->where('stock_movements.movimentable_type', '=', 'App\Models\Sale');
            })
            ->join('stocks', 'stock_movements.stock_id', '=', 'stocks.id')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('marks', 'products.mark_id', '=', 'marks.id')
            ->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.price_sale',
                'marks.name as mark_name',
                'product_types.name as product_type_name',
                DB::raw('COUNT(*) as total_quantity'),
                DB::raw('SUM(products.price_sale) as total_value'),
                DB::raw('MIN(stock_movements.created_at) as first_sale_date'),
                DB::raw('MAX(stock_movements.created_at) as last_sale_date')
            )
            ->where('stock_movements.type', 'out')
            ->where('stock_movements.status', 'completed')
            ->groupBy(
                'products.id',
                'products.name',
                'products.price_sale',
                'marks.name',
                'product_types.name'
            )
            ->orderBy('total_quantity', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    $item->product_id,
                    $item->product_name,
                    $item->mark_name ?? 'N/A',
                    $item->product_type_name ?? 'N/A',
                    'R$ ' . number_format($item->price_sale, 2, ',', '.'),
                    (int) $item->total_quantity,
                    'R$ ' . number_format($item->total_value, 2, ',', '.'),
                    Carbon::parse($item->first_sale_date)->format('d/m/Y H:i:s'),
                    Carbon::parse($item->last_sale_date)->format('d/m/Y H:i:s'),
                ];
            })
            ->toArray();

        return $stockExits;
    }
}