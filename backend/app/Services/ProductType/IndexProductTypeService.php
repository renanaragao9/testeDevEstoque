<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexProductTypeService
{
    public function run(array $data): LengthAwarePaginator
    {
        $perPage = $data['per_page'] ?? 15;
        $page = $data['page'] ?? 1;
        $search = !empty($data['search']) ? '%' . $data['search'] . '%' : null;
        $sortField = $data['sort'] ?? null;
        $sortDirection = $data['direction'] ?? 'desc';
        $allowedSortFields = ['id', 'name', 'description', 'created_at', 'updated_at'];

        return ProductType::with('products')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', $search);
            })
            ->when($sortField && in_array($sortField, $allowedSortFields), function ($query) use ($sortField, $sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
