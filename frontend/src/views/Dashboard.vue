<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useDashboardStore } from '@/stores/dashboardStore';
import BestSellingWidget from '@/components/dashboard/BestSellingWidget.vue';
import RevenueStreamWidget from '@/components/dashboard/RevenueStreamWidget.vue';
import StatsWidget from '@/components/dashboard/StatsWidget.vue';

const dashboardStore = useDashboardStore();
const toast = useToast();

const loadDashboardData = async () => {
    try {
        await dashboardStore.fetchDashboardData();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados do dashboard',
            life: 5000
        });
    }
};

const downloadStockReport = async () => {
    try {
        await dashboardStore.downloadStockExitReport();
        toast.add({
            severity: 'success',
            summary: 'Sucesso',
            detail: 'Relatório baixado com sucesso',
            life: 3000
        });
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao fazer download do relatório',
            life: 5000
        });
    }
};

onMounted(() => {
    loadDashboardData();
});

onUnmounted(() => {
    dashboardStore.resetStore();
});
</script>

<template>
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">Dashboard</h1>
            <div class="flex gap-2">
                <Button icon="pi pi-download" label="Exportar Estoque" :loading="dashboardStore.loading" @click="downloadStockReport" class="p-button-success" />
                <Button icon="pi pi-refresh" label="Atualizar" :loading="dashboardStore.loading" @click="loadDashboardData" class="p-button-outlined" />
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <StatsWidget />

            <div class="col-span-12 xl:col-span-6">
                <BestSellingWidget />
            </div>
            <div class="col-span-12 xl:col-span-6">
                <RevenueStreamWidget />
            </div>
        </div>
    </div>
</template>
