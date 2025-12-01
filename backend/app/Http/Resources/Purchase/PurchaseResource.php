<?php

namespace App\Http\Resources\Purchase;

use App\Services\Purchase\CalculatePurchaseProductsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'purchase_date' => $this->purchase_date ? $this->purchase_date->format('Y-m-d') : null,
            'total_amount' => (float) $this->total_amount,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->whenLoaded('supplier'),
            'products' => $this->whenLoaded('stockMovements', function () {
                $calculateProductsService = new CalculatePurchaseProductsService();
                return $calculateProductsService->run($this->resource);
            }, []),
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }
}