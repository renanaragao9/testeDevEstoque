<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexWarehouseService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Warehouse $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->warehouse
            ->with(['stocks.product'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort($perPage)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}