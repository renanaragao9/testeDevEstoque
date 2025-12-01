import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { DashboardData, SalesReport, GeneralStats, TopSellingProduct } from '@/types/dashboard';
import { DashboardService } from '@/services/DashboardService';
import { handleApiError } from '@/utils/errorHandler';

export const useDashboardStore = defineStore('dashboard', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);

    const dashboardData = ref<DashboardData | null>(null);
    const salesReport = ref<SalesReport | null>(null);
    const generalStats = ref<GeneralStats | null>(null);
    const topSellingProducts = ref<TopSellingProduct[]>([]);

    const totalQuantitySold = computed(() => salesReport.value?.total_quantity_sold ?? dashboardData.value?.sales_report?.total_quantity_sold ?? 0);
    const totalSalesAmount = computed(() => salesReport.value?.total_sales_amount ?? dashboardData.value?.sales_report?.total_sales_amount ?? 0);
    const totalPurchases = computed(() => generalStats.value?.total_purchases ?? dashboardData.value?.general_stats?.total_purchases ?? 0);
    const totalSales = computed(() => generalStats.value?.total_sales ?? dashboardData.value?.general_stats?.total_sales ?? 0);
    const totalCustomers = computed(() => generalStats.value?.total_customers ?? dashboardData.value?.general_stats?.total_customers ?? 0);
    const totalProducts = computed(() => generalStats.value?.total_products ?? dashboardData.value?.general_stats?.total_products ?? 0);
    const bestSellingProducts = computed(() => (topSellingProducts.value.length > 0 ? topSellingProducts.value : (dashboardData.value?.top_selling_products ?? [])));

    async function fetchDashboardData() {
        loading.value = true;
        error.value = null;
        try {
            const data = await DashboardService.getDashboardData();
            dashboardData.value = data;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar dados do dashboard');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function fetchSalesReport() {
        loading.value = true;
        error.value = null;
        try {
            const data = await DashboardService.getSalesReport();
            salesReport.value = data;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar relatório de vendas');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function fetchGeneralStats() {
        loading.value = true;
        error.value = null;
        try {
            const data = await DashboardService.getGeneralStats();
            generalStats.value = data;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar estatísticas gerais');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function fetchTopSellingProducts() {
        loading.value = true;
        error.value = null;
        try {
            const data = await DashboardService.getTopSellingProducts();
            topSellingProducts.value = data;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar produtos mais vendidos');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function downloadStockExitReport() {
        loading.value = true;
        error.value = null;
        try {
            await DashboardService.downloadStockExitReport();
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao fazer download do relatório');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearError() {
        error.value = null;
    }

    function resetStore() {
        dashboardData.value = null;
        salesReport.value = null;
        generalStats.value = null;
        topSellingProducts.value = [];
        error.value = null;
        loading.value = false;
    }

    return {
        loading,
        error,
        dashboardData,
        salesReport,
        generalStats,
        topSellingProducts,

        totalQuantitySold,
        totalSalesAmount,
        totalPurchases,
        totalSales,
        totalCustomers,
        totalProducts,
        bestSellingProducts,

        fetchDashboardData,
        fetchSalesReport,
        fetchGeneralStats,
        fetchTopSellingProducts,
        downloadStockExitReport,
        clearError,
        resetStore
    };
});
