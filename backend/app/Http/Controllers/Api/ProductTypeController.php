<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ProductType\IndexProductTypeRequest;
use App\Http\Requests\ProductType\StoreProductTypeRequest;
use App\Http\Requests\ProductType\UpdateProductTypeRequest;
use App\Http\Resources\ProductType\ProductTypeResource;
use App\Models\ProductType;
use App\Services\ProductType\DeleteProductTypeService;
use App\Services\ProductType\IndexProductTypeService;
use App\Services\ProductType\StoreProductTypeService;
use App\Services\ProductType\UpdateProductTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductTypeController extends BaseController
{
    public function index(
        IndexProductTypeRequest $indexProductTypeRequest,
        IndexProductTypeService $indexProductTypeService,
    ): AnonymousResourceCollection {
        $data = $indexProductTypeRequest->validated();
        $productTypes = $indexProductTypeService->run($data);

        return ProductTypeResource::collection($productTypes);
    }

    public function show(ProductType $productType): JsonResponse
    {
        return $this->successResponse(
            new ProductTypeResource($productType),
            'Tipo de produto encontrado com sucesso.'
        );
    }

    public function store(
        StoreProductTypeRequest $storeProductTypeRequest,
        StoreProductTypeService $storeProductTypeService
    ): JsonResponse {
        $data = $storeProductTypeRequest->validated();
        $productType = $storeProductTypeService->run($data);

        return $this->successResponse(
            new ProductTypeResource($productType),
            'Tipo de produto criado com sucesso.'
        );
    }

    public function update(
        UpdateProductTypeRequest $updateProductTypeRequest,
        UpdateProductTypeService $updateProductTypeService,
        ProductType $productType
    ): JsonResponse {
        $data = $updateProductTypeRequest->validated();
        $productType = $updateProductTypeService->run($productType, $data);

        return $this->successResponse(
            new ProductTypeResource($productType),
            'Tipo de produto atualizado com sucesso.'
        );
    }

    public function destroy(
        ProductType $productType,
        DeleteProductTypeService $deleteProductTypeService
    ): JsonResponse {
        $deleteProductTypeService->run($productType);

        return $this->successResponse(
            null,
            'Tipo de produto removido com sucesso.'
        );
    }
}
