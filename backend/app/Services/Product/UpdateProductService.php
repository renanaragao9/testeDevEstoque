<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateProductService
{
    public function run(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $specifications = $data['specifications'] ?? null;
            unset($data['specifications']);

            $product->update($data);

            if ($specifications !== null) {
                $this->syncSpecifications($product, $specifications);
            }

            return $product->fresh(['productType', 'mark', 'specifications']);
        });
    }

    private function syncSpecifications(Product $product, array $specifications): void
    {
        $syncData = [];

        foreach ($specifications as $spec) {
            if (isset($spec['specification_id']) && isset($spec['value'])) {
                $syncData[$spec['specification_id']] = ['value' => $spec['value']];
            }
        }

        $product->specifications()->sync($syncData);
    }
}