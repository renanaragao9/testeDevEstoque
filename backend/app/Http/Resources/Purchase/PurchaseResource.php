<?php

namespace App\Http\Resources\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoiceNumber' => $this->invoice_number,
            'purchaseDate' => $this->purchase_date ? $this->purchase_date->format('Y-m-d') : null,
            'totalAmount' => (float) $this->total_amount,
            'supplierId' => $this->supplier_id,
            'supplier' => $this->whenLoaded('supplier'),
            'stockMovements' => $this->whenLoaded('stockMovements'),
            'createdAt' => $this->created_at ? $this->created_at->toISOString() : null,
            'updatedAt' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }
}