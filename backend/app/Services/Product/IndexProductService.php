<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexProductService
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
            ->with('productType', 'mark', 'specifications', 'stocks')
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