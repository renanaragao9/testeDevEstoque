<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexProductTypeService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private ProductType $productType;

    public function __construct(ProductType $productType)
    {
        $this->productType = $productType;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $perPage = $data['per_page'] ?? 10;
        $search = $data['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->productType
            ->with('products')
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
