<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexPurchaseService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Purchase $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->purchase
            ->with('supplier', 'stockMovements', 'stockMovements.stock.product', 'stockMovements.stock.warehouse')
            ->when($search, function ($query) use ($search) {
                return $query->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($q) use ($search) {
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