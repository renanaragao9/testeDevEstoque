<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Sale\IndexSaleRequest;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Resources\Sale\SaleResource;
use App\Models\Sale;
use App\Services\Sale\DeleteSaleService;
use App\Services\Sale\IndexSaleService;
use App\Services\Sale\StoreSaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends BaseController
{
    public function index(
        IndexSaleRequest $indexSaleRequest,
        IndexSaleService $indexSaleService,
    ): AnonymousResourceCollection {
        $data = $indexSaleRequest->validated();
        $sales = $indexSaleService->run($data);

        return SaleResource::collection($sales);
    }

    public function show(Sale $sale): JsonResponse
    {
        return $this->successResponse(
            new SaleResource($sale),
            'Venda encontrada com sucesso.'
        );
    }

    public function store(
        StoreSaleRequest $storeSaleRequest,
        StoreSaleService $storeSaleService
    ): JsonResponse {
        $data = $storeSaleRequest->validated();
        $sale = $storeSaleService->run($data);

        return $this->successResponse(
            new SaleResource($sale),
            'Venda criada com sucesso.'
        );
    }



    public function destroy(
        Sale $sale,
        DeleteSaleService $deleteSaleService
    ): JsonResponse {
        $deleteSaleService->run($sale);

        return $this->successResponse(
            null,
            'Venda removida com sucesso.'
        );
    }
}