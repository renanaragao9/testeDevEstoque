<script setup lang="ts">
import { computed } from 'vue';
import { useDashboardStore } from '@/stores/dashboardStore';

const dashboardStore = useDashboardStore();

const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};

const revenueData = computed(() => {
    const totalRevenue = dashboardStore.totalSalesAmount;
    const totalSales = dashboardStore.totalSales;
    const averageTicket = totalSales > 0 ? totalRevenue / totalSales : 0;

    return {
        total: totalRevenue,
        average: averageTicket,
        sales: totalSales,
        items: dashboardStore.totalQuantitySold
    };
});
</script>

<template>
    <div class="card">
        <div class="font-semibold text-xl mb-6">Resumo de Receitas</div>

        <div v-if="dashboardStore.loading" class="flex justify-center items-center py-8">
            <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="8" />
        </div>

        <div v-else class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-primary-50 dark:bg-primary-400/10 rounded-lg">
                <div>
                    <div class="text-sm text-muted-color mb-1">Receita Total</div>
                    <div class="text-2xl font-bold text-primary">
                        {{ formatCurrency(revenueData.total) }}
                    </div>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-primary-100 dark:bg-primary-400/20 rounded-full">
                    <i class="pi pi-dollar text-primary text-xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-surface-50 dark:bg-surface-800 rounded-lg">
                    <div class="text-sm text-muted-color mb-2">Ticket Médio</div>
                    <div class="text-xl font-semibold text-surface-900 dark:text-surface-0">
                        {{ formatCurrency(revenueData.average) }}
                    </div>
                </div>

                <div class="text-center p-4 bg-surface-50 dark:bg-surface-800 rounded-lg">
                    <div class="text-sm text-muted-color mb-2">Total de Vendas</div>
                    <div class="text-xl font-semibold text-surface-900 dark:text-surface-0">
                        {{ revenueData.sales }}
                    </div>
                </div>

                <div class="text-center p-4 bg-surface-50 dark:bg-surface-800 rounded-lg">
                    <div class="text-sm text-muted-color mb-2">Itens Vendidos</div>
                    <div class="text-xl font-semibold text-surface-900 dark:text-surface-0">
                        {{ revenueData.items }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
