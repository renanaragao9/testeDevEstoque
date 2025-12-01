<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Product\DeleteProductService;
use App\Services\Product\IndexProductService;
use App\Services\Product\StoreProductService;
use App\Services\Product\UpdateProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends BaseController
{
    public function index(
        IndexProductRequest $indexProductRequest,
        IndexProductService $indexProductService,
    ): AnonymousResourceCollection {
        $data = $indexProductRequest->validated();
        $products = $indexProductService->run($data);

        return ProductResource::collection($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['productType', 'mark', 'specifications']);

        return $this->successResponse(
            new ProductResource($product),
            'Produto encontrado com sucesso.'
        );
    }

    public function store(
        StoreProductRequest $storeProductRequest,
        StoreProductService $storeProductService
    ): JsonResponse {
        $data = $storeProductRequest->validated();
        $product = $storeProductService->run($data);

        return $this->successResponse(
            new ProductResource($product),
            'Produto criado com sucesso.'
        );
    }

    public function update(
        UpdateProductRequest $updateProductRequest,
        UpdateProductService $updateProductService,
        Product $product
    ): JsonResponse {
        $data = $updateProductRequest->validated();
        $product = $updateProductService->run($product, $data);

        return $this->successResponse(
            new ProductResource($product),
            'Produto atualizado com sucesso.'
        );
    }

    public function destroy(
        Product $product,
        DeleteProductService $deleteProductService
    ): JsonResponse {
        $deleteProductService->run($product);

        return $this->successResponse(
            null,
            'Produto removido com sucesso.'
        );
    }
}