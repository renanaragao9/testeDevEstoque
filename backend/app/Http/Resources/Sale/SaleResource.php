<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'sale_date' => $this->sale_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'customer' => $this->whenLoaded('customer'),
            'stock_movements' => $this->whenLoaded('stockMovements'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}