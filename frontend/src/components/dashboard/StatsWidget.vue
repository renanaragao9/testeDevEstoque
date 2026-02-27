<script setup lang="ts">
import { useDashboardStore } from '@/stores/dashboardStore';

const dashboardStore = useDashboardStore();

const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};
</script>

<template>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Vendas</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">
                        {{ dashboardStore.loading ? '...' : dashboardStore.totalSales }}
                    </div>
                </div>

                <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-shopping-cart text-blue-500 !text-xl"></i>
                </div>
            </div>

            <span class="text-primary font-medium">{{ dashboardStore.totalQuantitySold }} </span>
            <span class="text-muted-color pl-1">itens vendidos</span>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Receita</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">
                        {{ dashboardStore.loading ? '...' : formatCurrency(dashboardStore.totalSalesAmount) }}
                    </div>
                </div>

                <div class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-dollar text-orange-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">Total </span>
            <span class="text-muted-color pl-1">em vendas</span>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Clientes</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">
                        {{ dashboardStore.loading ? '...' : dashboardStore.totalCustomers }}
                    </div>
                </div>

                <div class="flex items-center justify-center bg-cyan-100 dark:bg-cyan-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-users text-cyan-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">Total </span>
            <span class="text-muted-color pl-1">cadastrados</span>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xl:col-span-3">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Produtos</span>
                    <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">
                        {{ dashboardStore.loading ? '...' : dashboardStore.totalProducts }}
                    </div>
                </div>

                <div class="flex items-center justify-center bg-purple-100 dark:bg-purple-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-box text-purple-500 !text-xl"></i>
                </div>
            </div>
            <span class="text-primary font-medium">{{ dashboardStore.totalPurchases }} </span>
            <span class="text-muted-color pl-1">compras realizadas</span>
        </div>
    </div>
</template>
