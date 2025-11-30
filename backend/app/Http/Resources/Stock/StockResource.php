<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_available_use' => $this->is_available_use,
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
            'product' => $this->whenLoaded('product'),
            'warehouse' => $this->whenLoaded('warehouse'),
            'stock_movements' => $this->whenLoaded('stockMovements'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}