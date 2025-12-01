<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price_sale' => $this->price_sale,
            'product_type_id' => $this->product_type_id,
            'mark_id' => $this->mark_id,
            'product_type' => $this->whenLoaded('productType'),
            'mark' => $this->whenLoaded('mark'),
            'specifications' => $this->whenLoaded('specifications'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}