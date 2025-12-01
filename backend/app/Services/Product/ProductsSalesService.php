<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;
use Illuminate\Database\Eloquent\Builder;

class ProductsSalesService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->product
            ->select([
                'products.id',
                'products.name',
                'products.price_sale'
            ])
            ->selectRaw('COALESCE(SUM(CASE 
                WHEN stock_movements.type = "in" THEN 1 
                WHEN stock_movements.type = "out" THEN -1 
                ELSE 0 
            END), 0) as total_in_stock')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('stock_movements', function ($join) {
                $join->on('stocks.id', '=', 'stock_movements.stock_id')
                    ->where('stock_movements.status', '=', 'completed');
            })
            ->groupBy('products.id', 'products.name', 'products.price_sale')
            ->when($search, function (Builder $query) use ($search) {
                return $query->where('products.name', 'like', '%' . $search . '%');
            });

        if ($orderByColumn && $orderByDirection) {
            $query->orderBy($orderByColumn, $orderByDirection);
        }

        if ($paginate) {
            return $query->paginate($perPage)->withQueryString();
        }

        return $query->get();
    }
}