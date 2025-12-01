<?php

namespace App\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'totalStock' => $this->whenLoaded('stocks', function () {
                return $this->stocks->where('is_available_use', true)->count();
            }, 0),
            'totalStockValue' => $this->whenLoaded('stocks', function () {
                return $this->stocks
                    ->where('is_available_use', true)
                    ->sum(function ($stock) {
                        return $stock->relationLoaded('product') && $stock->product ? (float) $stock->product->price_sale : 0.0;
                    });
            }, 0.0),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}