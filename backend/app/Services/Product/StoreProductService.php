<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StoreProductService
{
    public function run(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $specifications = $data['specifications'] ?? [];
            unset($data['specifications']);

            $product = Product::create($data);

            if (!empty($specifications)) {
                $this->syncSpecifications($product, $specifications);
            }

            $product->load(['productType', 'mark', 'specifications']);

            return $product;
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