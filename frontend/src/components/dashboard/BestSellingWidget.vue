<script setup lang="ts">
import { ref, computed } from 'vue';
import { useDashboardStore } from '@/stores/dashboardStore';

const menu = ref();
const dashboardStore = useDashboardStore();

const items = ref([{ label: 'Atualizar', icon: 'pi pi-fw pi-refresh' }]);

const colors = ['orange', 'cyan', 'pink', 'green', 'purple', 'teal', 'blue', 'indigo'];

const productsWithPercentage = computed(() => {
    const products = dashboardStore.bestSellingProducts;
    if (!products.length) return [];

    const maxSold = Math.max(...products.map((p) => p.total_sold));

    return products.map((product, index) => ({
        ...product,
        percentage: maxSold > 0 ? Math.round((product.total_sold / maxSold) * 100) : 0,
        color: colors[index % colors.length]
    }));
});

const handleMenuAction = (event: any) => {
    if (event.item.label === 'Atualizar') {
        dashboardStore.fetchTopSellingProducts();
    }
};

const toggleMenu = (event: Event) => {
    menu.value.toggle(event);
};
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <div class="font-semibold text-xl">Produtos Mais Vendidos</div>
            <div>
                <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded" @click="toggleMenu" />
                <Menu ref="menu" popup :model="items" class="!min-w-40" @click="handleMenuAction" />
            </div>
        </div>

        <div v-if="dashboardStore.loading" class="flex justify-center items-center py-8">
            <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="8" />
        </div>

        <div v-else-if="!productsWithPercentage.length" class="text-center py-8 text-muted-color">
            <i class="pi pi-inbox text-4xl mb-4"></i>
            <p>Nenhum produto vendido encontrado</p>
        </div>

        <ul v-else class="list-none p-0 m-0">
            <li v-for="(product, index) in productsWithPercentage" :key="product.product_id" class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <span class="text-surface-900 dark:text-surface-0 font-medium mr-2 mb-1 md:mb-0">
                        {{ product.product_name }}
                    </span>
                    <div class="mt-1 text-muted-color">{{ product.total_sold }} vendido{{ product.total_sold !== 1 ? 's' : '' }}</div>
                </div>
                <div class="mt-2 md:mt-0 flex items-center">
                    <div class="bg-surface-300 dark:bg-surface-500 rounded-border overflow-hidden w-40 lg:w-24" style="height: 8px">
                        <div :class="`bg-${product.color}-500 h-full`" :style="`width: ${product.percentage}%`"></div>
                    </div>
                    <span :class="`text-${product.color}-500 ml-4 font-medium`"> {{ product.percentage }}% </span>
                </div>
            </li>
        </ul>
    </div>
</template>
