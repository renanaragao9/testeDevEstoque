<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexSaleService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Sale $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->sale
            ->with(['customer', 'stockMovements.stock.product'])
            ->when($search, function ($query) use ($search) {
                return $query->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($q) use ($search) {
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