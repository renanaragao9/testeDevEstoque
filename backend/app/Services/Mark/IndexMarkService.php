<?php

namespace App\Services\Mark;

use App\Models\Mark;
use App\Traits\OrderByColumnAndDirection;
use App\Traits\ParseRequestParams;

class IndexMarkService
{
    use OrderByColumnAndDirection;
    use ParseRequestParams;

    private Mark $mark;

    public function __construct(Mark $mark)
    {
        $this->mark = $mark;
    }

    public function run($data)
    {
        $parseRequestParams = $this->parseRequestParams($data);
        $paginate = $parseRequestParams['paginate'];
        $search = $data['filters']['search'] ?? null;
        $orderByColumn = $data['order_by_column'] ?? 'id';
        $orderByDirection = $data['order_by_direction'] ?? 'asc';

        $query = $this->mark
            ->with('products')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });

        if ($paginate) {
            return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection)
                ->paginateWithSort(10)
                ->withQueryString();
        }

        return $this->orderByColumnAndDirection($query, $orderByColumn, $orderByDirection);
    }
}