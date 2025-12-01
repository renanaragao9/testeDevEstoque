import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Warehouse, WarehousePayload } from '@/types/warehouse';
import type { BaseFilters } from '@/types/global/filters';
import { WarehouseService } from '@/services/WarehouseService';
import { handleApiError } from '@/utils/errorHandler';

export const useWarehouseStore = defineStore('warehouse', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const warehouses = ref<Warehouse[]>([]);
    const warehouse = ref<Warehouse>({
        id: 0,
        name: '',
        location: ''
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const warehouseOptions = computed(() =>
        warehouses.value.map((warehouse) => ({
            label: warehouse.name,
            value: warehouse.id
        }))
    );

    async function fetchWarehouses(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await WarehouseService.getWarehouses(filters);
            warehouses.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar armazéns');
        } finally {
            loading.value = false;
        }
    }

    async function createWarehouse(payload: WarehousePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await WarehouseService.createWarehouse(payload);
            warehouses.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar armazém');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateWarehouse(id: number, payload: WarehousePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await WarehouseService.updateWarehouse(id, payload);
            const index = warehouses.value.findIndex((warehouse) => warehouse.id === id);
            if (index !== -1) {
                warehouses.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar armazém');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteWarehouse(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await WarehouseService.deleteWarehouse(id);
            warehouses.value = warehouses.value.filter((warehouse) => warehouse.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar armazém');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearWarehouse() {
        warehouse.value = {
            id: 0,
            name: '',
            location: ''
        };
    }

    return {
        warehouses,
        warehouse,
        loading,
        error,
        pagination,
        warehouseOptions,
        fetchWarehouses,
        createWarehouse,
        updateWarehouse,
        deleteWarehouse,
        clearWarehouse
    };
});
