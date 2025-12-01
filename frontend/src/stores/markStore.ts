import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Mark, MarkPayload } from '@/types/mark';
import type { BaseFilters } from '@/types/global/filters';
import { MarkService } from '@/services/MarkService';
import { handleApiError } from '@/utils/errorHandler';

export const useMarkStore = defineStore('mark', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const marks = ref<Mark[]>([]);
    const mark = ref<Mark>({
        id: 0,
        name: '',
        description: '',
        country: ''
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const markOptions = computed(() =>
        marks.value.map((mark) => ({
            label: mark.name,
            value: mark.id
        }))
    );

    async function fetchMarks(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await MarkService.getMarks(filters);
            marks.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar marcas');
        } finally {
            loading.value = false;
        }
    }

    async function createMark(payload: MarkPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await MarkService.createMark(payload);
            marks.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar marca');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateMark(id: number, payload: MarkPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await MarkService.updateMark(id, payload);
            const index = marks.value.findIndex((mark) => mark.id === id);
            if (index !== -1) {
                marks.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar marca');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteMark(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await MarkService.deleteMark(id);
            marks.value = marks.value.filter((mark) => mark.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar marca');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearMark() {
        mark.value = {
            id: 0,
            name: '',
            description: '',
            country: ''
        };
    }

    return {
        marks,
        mark,
        loading,
        error,
        pagination,
        markOptions,
        fetchMarks,
        createMark,
        updateMark,
        deleteMark,
        clearMark
    };
});
