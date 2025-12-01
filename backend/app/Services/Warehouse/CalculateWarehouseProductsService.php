<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;

class CalculateWarehouseProductsService
{
    public function run(Warehouse $warehouse): array
    {
        if (!$warehouse->relationLoaded('stocks')) {
            return [];
        }

        $availableStocks = $warehouse->stocks->where('is_available_use', true);

        if ($availableStocks->isEmpty()) {
            return [];
        }

        $productGroups = $availableStocks
            ->filter(function ($stock) {
                return $stock->relationLoaded('product') && $stock->product;
            })
            ->groupBy('product_id');

        return $productGroups->map(function ($stocks, $productId) {
            $product = $stocks->first()->product;
            $totalQuantity = $stocks->count();
            $totalValue = $stocks->sum(function ($stock) {
                return (float) $stock->product->price_sale;
            });
            $averageCost = $totalQuantity > 0 ? round($totalValue / $totalQuantity, 2) : 0.0;

            return [
                'name' => $product->name,
                'total' => $totalQuantity,
                'average_cost' => $averageCost
            ];
        })->values()->toArray();
    }
}