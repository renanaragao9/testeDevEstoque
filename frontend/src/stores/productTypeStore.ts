import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { ProductType, ProductTypePayload } from '@/types/productType';
import type { BaseFilters } from '@/types/global/filters';
import { ProductTypeService } from '@/services/ProductTypeService';
import { handleApiError } from '@/utils/errorHandler';

export const useProductTypeStore = defineStore('productType', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const productTypes = ref<ProductType[]>([]);
    const productType = ref<ProductType>({
        id: 0,
        name: '',
        description: ''
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const productTypeOptions = computed(() =>
        productTypes.value.map((productType) => ({
            label: productType.name,
            value: productType.id
        }))
    );

    async function fetchProductTypes(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await ProductTypeService.getProductTypes(filters);
            productTypes.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar tipos de produto');
        } finally {
            loading.value = false;
        }
    }

    async function createProductType(payload: ProductTypePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await ProductTypeService.createProductType(payload);
            productTypes.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar tipo de produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateProductType(id: number, payload: ProductTypePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await ProductTypeService.updateProductType(id, payload);
            const index = productTypes.value.findIndex((productType) => productType.id === id);
            if (index !== -1) {
                productTypes.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar tipo de produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteProductType(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await ProductTypeService.deleteProductType(id);
            productTypes.value = productTypes.value.filter((productType) => productType.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar tipo de produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearProductType() {
        productType.value = {
            id: 0,
            name: '',
            description: ''
        };
    }

    return {
        productTypes,
        productType,
        loading,
        error,
        pagination,
        productTypeOptions,
        fetchProductTypes,
        createProductType,
        updateProductType,
        deleteProductType,
        clearProductType
    };
});
