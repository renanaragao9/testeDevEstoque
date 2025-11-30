<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StorePurchaseService
{
    public function run(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];
            unset($data['items']);

            $purchase = Purchase::create($data);

            foreach ($items as $item) {
                $stock = Stock::firstOrCreate([
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                ]);

                for ($i = 0; $i < $item['quantity']; $i++) {
                    StockMovement::create([
                        'type' => 'in',
                        'status' => 'completed',
                        'stock_id' => $stock->id,
                        'movimentable_type' => Purchase::class,
                        'movimentable_id' => $purchase->id,
                    ]);
                }
            }

            return $purchase;
        });
    }
}