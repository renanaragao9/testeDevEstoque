<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Purchase\IndexPurchaseRequest;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Http\Resources\Purchase\PurchaseResource;
use App\Models\Purchase;
use App\Services\Purchase\DeletePurchaseService;
use App\Services\Purchase\IndexPurchaseService;
use App\Services\Purchase\StorePurchaseService;
use App\Services\Purchase\UpdatePurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PurchaseController extends BaseController
{
    public function index(
        IndexPurchaseRequest $indexPurchaseRequest,
        IndexPurchaseService $indexPurchaseService,
    ): AnonymousResourceCollection {
        $data = $indexPurchaseRequest->validated();
        $purchases = $indexPurchaseService->run($data);

        return PurchaseResource::collection($purchases);
    }

    public function show(Purchase $purchase): JsonResponse
    {
        return $this->successResponse(
            new PurchaseResource($purchase),
            'Compra encontrada com sucesso.'
        );
    }

    public function store(
        StorePurchaseRequest $storePurchaseRequest,
        StorePurchaseService $storePurchaseService
    ): JsonResponse {
        $data = $storePurchaseRequest->validated();
        $purchase = $storePurchaseService->run($data);

        return $this->successResponse(
            new PurchaseResource($purchase),
            'Compra criada com sucesso.'
        );
    }

    public function update(
        UpdatePurchaseRequest $updatePurchaseRequest,
        UpdatePurchaseService $updatePurchaseService,
        Purchase $purchase
    ): JsonResponse {
        $data = $updatePurchaseRequest->validated();
        $purchase = $updatePurchaseService->run($purchase, $data);

        return $this->successResponse(
            new PurchaseResource($purchase),
            'Compra atualizada com sucesso.'
        );
    }

    public function destroy(
        Purchase $purchase,
        DeletePurchaseService $deletePurchaseService
    ): JsonResponse {
        $deletePurchaseService->run($purchase);

        return $this->successResponse(
            null,
            'Compra removida com sucesso.'
        );
    }
}