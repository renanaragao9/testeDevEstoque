import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Supplier, SupplierPayload } from '@/types/supplier';
import type { BaseFilters } from '@/types/global/filters';
import { SupplierService } from '@/services/SupplierService';
import { handleApiError } from '@/utils/errorHandler';

export const useSupplierStore = defineStore('supplier', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const suppliers = ref<Supplier[]>([]);
    const supplier = ref<Supplier>({
        id: 0,
        name: '',
        email: '',
        phone: '',
        address: ''
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const supplierOptions = computed(() =>
        suppliers.value.map((supplier) => ({
            label: supplier.name,
            value: supplier.id
        }))
    );

    async function fetchSuppliers(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await SupplierService.getSuppliers(filters);
            suppliers.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar fornecedores');
        } finally {
            loading.value = false;
        }
    }

    async function createSupplier(payload: SupplierPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await SupplierService.createSupplier(payload);
            suppliers.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar fornecedor');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateSupplier(id: number, payload: SupplierPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await SupplierService.updateSupplier(id, payload);
            const index = suppliers.value.findIndex((supplier) => supplier.id === id);
            if (index !== -1) {
                suppliers.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar fornecedor');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteSupplier(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await SupplierService.deleteSupplier(id);
            suppliers.value = suppliers.value.filter((supplier) => supplier.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar fornecedor');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearSupplier() {
        supplier.value = {
            id: 0,
            name: '',
            email: '',
            phone: '',
            address: ''
        };
    }

    return {
        suppliers,
        supplier,
        loading,
        error,
        pagination,
        supplierOptions,
        fetchSuppliers,
        createSupplier,
        updateSupplier,
        deleteSupplier,
        clearSupplier
    };
});
