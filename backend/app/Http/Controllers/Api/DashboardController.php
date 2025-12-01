<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Exports\ExportStockExitExport;
use App\Services\Dashboard\ExportStockExitService;
use App\Services\Dashboard\GeneralStatsService;
use App\Services\Dashboard\SalesReportService;
use App\Services\Dashboard\TopSellingProductsService;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

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

    public function salesReport(SalesReportService $salesReportService): JsonResponse
    {
        $salesReport = $salesReportService->run();

        return $this->successResponse(
            $salesReport,
            'Relatório de vendas retornado com sucesso.'
        );
    }

    public function generalStats(GeneralStatsService $generalStatsService): JsonResponse
    {
        $generalStats = $generalStatsService->run();

        return $this->successResponse(
            $generalStats,
            'Estatísticas gerais retornadas com sucesso.'
        );
    }

    public function topSellingProducts(TopSellingProductsService $topSellingProductsService): JsonResponse
    {
        $topSellingProducts = $topSellingProductsService->run();

        return $this->successResponse(
            $topSellingProducts,
            'Produtos mais vendidos retornados com sucesso.'
        );
    }

    public function exportStockExit(ExportStockExitService $exportStockExitService)
    {
        $stockExitData = $exportStockExitService->run();

        if (empty($stockExitData)) {
            return $this->errorResponse(['message' => 'Não há dados para exportar'], 404);
        }

        $fileName = 'relatorio_saidas_estoque_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ExportStockExitExport($stockExitData), $fileName);
    }
}