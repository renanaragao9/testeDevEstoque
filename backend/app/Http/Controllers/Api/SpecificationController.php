<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Specification\IndexSpecificationRequest;
use App\Http\Requests\Specification\StoreSpecificationRequest;
use App\Http\Requests\Specification\UpdateSpecificationRequest;
use App\Http\Resources\Specification\SpecificationResource;
use App\Models\Specification;
use App\Services\Specification\DeleteSpecificationService;
use App\Services\Specification\IndexSpecificationService;
use App\Services\Specification\StoreSpecificationService;
use App\Services\Specification\UpdateSpecificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecificationController extends BaseController
{
    public function index(
        IndexSpecificationRequest $indexSpecificationRequest,
        IndexSpecificationService $indexSpecificationService,
    ): AnonymousResourceCollection {
        $data = $indexSpecificationRequest->validated();
        $specifications = $indexSpecificationService->run($data);

        return SpecificationResource::collection($specifications);
    }

    public function show(Specification $specification): JsonResponse
    {
        return $this->successResponse(
            new SpecificationResource($specification),
            'Especificação encontrada com sucesso.'
        );
    }

    public function store(
        StoreSpecificationRequest $storeSpecificationRequest,
        StoreSpecificationService $storeSpecificationService
    ): JsonResponse {
        $data = $storeSpecificationRequest->validated();
        $specification = $storeSpecificationService->run($data);

        return $this->successResponse(
            new SpecificationResource($specification),
            'Especificação criada com sucesso.'
        );
    }

    public function update(
        UpdateSpecificationRequest $updateSpecificationRequest,
        UpdateSpecificationService $updateSpecificationService,
        Specification $specification
    ): JsonResponse {
        $data = $updateSpecificationRequest->validated();
        $specification = $updateSpecificationService->run($specification, $data);

        return $this->successResponse(
            new SpecificationResource($specification),
            'Especificação atualizada com sucesso.'
        );
    }

    public function destroy(
        Specification $specification,
        DeleteSpecificationService $deleteSpecificationService
    ): JsonResponse {
        $deleteSpecificationService->run($specification);

        return $this->successResponse(
            null,
            'Especificação removida com sucesso.'
        );
    }
}