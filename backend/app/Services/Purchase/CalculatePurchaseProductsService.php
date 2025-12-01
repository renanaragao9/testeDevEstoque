<?php

namespace App\Services\Purchase;

use App\Models\Purchase;

class CalculatePurchaseProductsService
{
    public function run(Purchase $purchase): array
    {
        if (!$purchase->relationLoaded('stockMovements')) {
            return [];
        }

        $stockMovements = $purchase->stockMovements;

        if ($stockMovements->isEmpty()) {
            return [];
        }

        $productGroups = $stockMovements
            ->filter(function ($stockMovement) {
                return $stockMovement->relationLoaded('stock')
                    && $stockMovement->stock
                    && $stockMovement->stock->relationLoaded('product')
                    && $stockMovement->stock->product;
            })
            ->groupBy(function ($stockMovement) {
                return $stockMovement->stock->product_id;
            });

        return $productGroups->map(function ($movements, $productId) {
            $firstMovement = $movements->first();
            $product = $firstMovement->stock->product;
            $totalQuantity = $movements->count();

            $totalValue = $movements->sum(function ($movement) {
                return (float) $movement->stock->product->price_sale;
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