<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StoreSaleService
{
    public function run(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];
            unset($data['items']);

            foreach ($items as $item) {
                $availableStock = Stock::where('product_id', $item['product_id'])
                    ->where('is_available_use', true)
                    ->count();

                if ($availableStock < $item['quantity']) {
                    throw new \Exception('Stock insuficiente para o produto ' . $item['product_id'] . '. Disponível: ' . $availableStock . ', necessário: ' . $item['quantity']);
                }
            }

            $sale = Sale::create($data);

            foreach ($items as $item) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $stock = Stock::where('product_id', $item['product_id'])
                        ->where('is_available_use', true)
                        ->first();

                    $stock->update(['is_available_use' => false]);

                    StockMovement::create([
                        'type' => 'out',
                        'status' => 'completed',
                        'stock_id' => $stock->id,
                        'movimentable_type' => Sale::class,
                        'movimentable_id' => $sale->id,
                    ]);
                }
            }

            return $sale;
        });
    }
}