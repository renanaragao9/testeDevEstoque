<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\Dashboard\GeneralStatsService;
use App\Services\Dashboard\SalesReportService;
use App\Services\Dashboard\TopSellingProductsService;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseController
{
    public function index(
        SalesReportService $salesReportService,
        GeneralStatsService $generalStatsService,
        TopSellingProductsService $topSellingProductsService
    ): JsonResponse {
        $salesReport = $salesReportService->run();
        $generalStats = $generalStatsService->run();
        $topSellingProducts = $topSellingProductsService->run();

        $dashboardData = [
            'sales_report' => $salesReport,
            'general_stats' => $generalStats,
            'top_selling_products' => $topSellingProducts,
        ];

        return $this->successResponse(
            $dashboardData,
            'Dados do dashboard retornados com sucesso.'
        );
    }
}