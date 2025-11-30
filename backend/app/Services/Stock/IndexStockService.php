<?php

namespace App\Services\Stock;

use App\Models\Stock;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexStockService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Stock $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->stock
            ->with('product', 'warehouse', 'stockMovements')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('warehouse', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}