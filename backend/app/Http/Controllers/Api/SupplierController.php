<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Supplier\IndexSupplierRequest;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierResource;
use App\Models\Supplier;
use App\Services\Supplier\DeleteSupplierService;
use App\Services\Supplier\IndexSupplierService;
use App\Services\Supplier\StoreSupplierService;
use App\Services\Supplier\UpdateSupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplierController extends BaseController
{
    public function index(
        IndexSupplierRequest $indexSupplierRequest,
        IndexSupplierService $indexSupplierService,
    ): AnonymousResourceCollection {
        $data = $indexSupplierRequest->validated();
        $suppliers = $indexSupplierService->run($data);

        return SupplierResource::collection($suppliers);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return $this->successResponse(
            new SupplierResource($supplier),
            'Fornecedor encontrado com sucesso.'
        );
    }

    public function store(
        StoreSupplierRequest $storeSupplierRequest,
        StoreSupplierService $storeSupplierService
    ): JsonResponse {
        $data = $storeSupplierRequest->validated();
        $supplier = $storeSupplierService->run($data);

        return $this->successResponse(
            new SupplierResource($supplier),
            'Fornecedor criado com sucesso.'
        );
    }

    public function update(
        UpdateSupplierRequest $updateSupplierRequest,
        UpdateSupplierService $updateSupplierService,
        Supplier $supplier
    ): JsonResponse {
        $data = $updateSupplierRequest->validated();
        $supplier = $updateSupplierService->run($supplier, $data);

        return $this->successResponse(
            new SupplierResource($supplier),
            'Fornecedor atualizado com sucesso.'
        );
    }

    public function destroy(
        Supplier $supplier,
        DeleteSupplierService $deleteSupplierService
    ): JsonResponse {
        $deleteSupplierService->run($supplier);

        return $this->successResponse(
            null,
            'Fornecedor removido com sucesso.'
        );
    }
}