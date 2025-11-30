<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Warehouse\IndexWarehouseRequest;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Http\Resources\Warehouse\WarehouseResource;
use App\Models\Warehouse;
use App\Services\Warehouse\DeleteWarehouseService;
use App\Services\Warehouse\IndexWarehouseService;
use App\Services\Warehouse\StoreWarehouseService;
use App\Services\Warehouse\UpdateWarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WarehouseController extends BaseController
{
    public function index(
        IndexWarehouseRequest $indexWarehouseRequest,
        IndexWarehouseService $indexWarehouseService,
    ): AnonymousResourceCollection {
        $data = $indexWarehouseRequest->validated();
        $warehouses = $indexWarehouseService->run($data);

        return WarehouseResource::collection($warehouses);
    }

    public function show(Warehouse $warehouse): JsonResponse
    {
        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Armazém encontrado com sucesso.'
        );
    }

    public function store(
        StoreWarehouseRequest $storeWarehouseRequest,
        StoreWarehouseService $storeWarehouseService
    ): JsonResponse {
        $data = $storeWarehouseRequest->validated();
        $warehouse = $storeWarehouseService->run($data);

        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Armazém criado com sucesso.'
        );
    }

    public function update(
        UpdateWarehouseRequest $updateWarehouseRequest,
        UpdateWarehouseService $updateWarehouseService,
        Warehouse $warehouse
    ): JsonResponse {
        $data = $updateWarehouseRequest->validated();
        $warehouse = $updateWarehouseService->run($warehouse, $data);

        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Armazém atualizado com sucesso.'
        );
    }

    public function destroy(
        Warehouse $warehouse,
        DeleteWarehouseService $deleteWarehouseService
    ): JsonResponse {
        $deleteWarehouseService->run($warehouse);

        return $this->successResponse(
            null,
            'Armazém removido com sucesso.'
        );
    }
}