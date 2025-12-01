import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Specification, SpecificationPayload } from '@/types/specification';
import type { BaseFilters } from '@/types/global/filters';
import { SpecificationService } from '@/services/SpecificationService';
import { handleApiError } from '@/utils/errorHandler';

export const useSpecificationStore = defineStore('specification', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const specifications = ref<Specification[]>([]);
    const specification = ref<Specification>({
        id: 0,
        name: '',
        is_active: true
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const specificationOptions = computed(() =>
        specifications.value.map((specification) => ({
            label: specification.name,
            value: specification.id
        }))
    );

    async function fetchSpecifications(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await SpecificationService.getSpecifications(filters);
            specifications.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar especificações');
        } finally {
            loading.value = false;
        }
    }

    async function createSpecification(payload: SpecificationPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await SpecificationService.createSpecification(payload);
            specifications.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar especificação');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateSpecification(id: number, payload: SpecificationPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await SpecificationService.updateSpecification(id, payload);
            const index = specifications.value.findIndex((specification) => specification.id === id);
            if (index !== -1) {
                specifications.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar especificação');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteSpecification(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await SpecificationService.deleteSpecification(id);
            specifications.value = specifications.value.filter((specification) => specification.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar especificação');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearSpecification() {
        specification.value = {
            id: 0,
            name: '',
            is_active: true
        };
    }

    return {
        specifications,
        specification,
        loading,
        error,
        pagination,
        specificationOptions,
        fetchSpecifications,
        createSpecification,
        updateSpecification,
        deleteSpecification,
        clearSpecification
    };
});
