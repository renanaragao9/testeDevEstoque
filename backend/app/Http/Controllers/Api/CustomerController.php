<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Customer\IndexCustomerRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer;
use App\Services\Customer\DeleteCustomerService;
use App\Services\Customer\IndexCustomerService;
use App\Services\Customer\StoreCustomerService;
use App\Services\Customer\UpdateCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends BaseController
{
    public function index(
        IndexCustomerRequest $indexCustomerRequest,
        IndexCustomerService $indexCustomerService,
    ): AnonymousResourceCollection {
        $data = $indexCustomerRequest->validated();
        $customers = $indexCustomerService->run($data);

        return CustomerResource::collection($customers);
    }

    public function show(Customer $customer): JsonResponse
    {
        return $this->successResponse(
            new CustomerResource($customer),
            'Cliente encontrado com sucesso.'
        );
    }

    public function store(
        StoreCustomerRequest $storeCustomerRequest,
        StoreCustomerService $storeCustomerService
    ): JsonResponse {
        $data = $storeCustomerRequest->validated();
        $customer = $storeCustomerService->run($data);

        return $this->successResponse(
            new CustomerResource($customer),
            'Cliente criado com sucesso.'
        );
    }

    public function update(
        UpdateCustomerRequest $updateCustomerRequest,
        UpdateCustomerService $updateCustomerService,
        Customer $customer
    ): JsonResponse {
        $data = $updateCustomerRequest->validated();
        $customer = $updateCustomerService->run($customer, $data);

        return $this->successResponse(
            new CustomerResource($customer),
            'Cliente atualizado com sucesso.'
        );
    }

    public function destroy(
        Customer $customer,
        DeleteCustomerService $deleteCustomerService
    ): JsonResponse {
        $deleteCustomerService->run($customer);

        return $this->successResponse(
            null,
            'Cliente removido com sucesso.'
        );
    }
}