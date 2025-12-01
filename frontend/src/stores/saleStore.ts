import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Sale, SalePayload, SaleItem } from '@/types/sale';
import type { BaseFilters } from '@/types/global/filters';
import { SaleService } from '@/services/SaleService';
import { handleApiError } from '@/utils/errorHandler';

export const useSaleStore = defineStore('sale', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const sales = ref<Sale[]>([]);
    const sale = ref<Sale>({
        id: 0,
        invoiceNumber: '',
        saleDate: new Date().toISOString().split('T')[0],
        totalAmount: 0,
        customerId: undefined
    });
    const saleItems = ref<SaleItem[]>([]);
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });

    const saleOptions = computed(() =>
        sales.value.map((sale) => ({
            label: sale.invoiceNumber,
            value: sale.id
        }))
    );

    async function fetchSales(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await SaleService.getSales(filters);
            sales.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar vendas');
        } finally {
            loading.value = false;
        }
    }

    async function createSale(payload: SalePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await SaleService.createSale(payload);
            sales.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar venda');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteSale(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await SaleService.deleteSale(id);
            sales.value = sales.value.filter((sale) => sale.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar venda');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearSale() {
        sale.value = {
            id: 0,
            invoiceNumber: '',
            saleDate: new Date().toISOString().split('T')[0],
            totalAmount: 0,
            customerId: undefined
        };
        saleItems.value = [];
    }

    function addSaleItem() {
        saleItems.value.push({
            productId: 0,
            quantity: 1
        });
    }

    function removeSaleItem(index: number) {
        saleItems.value.splice(index, 1);
    }

    function clearSaleItems() {
        saleItems.value = [];
    }

    return {
        sales,
        sale,
        saleItems,
        loading,
        error,
        pagination,
        saleOptions,
        fetchSales,
        createSale,
        deleteSale,
        clearSale,
        addSaleItem,
        removeSaleItem,
        clearSaleItems
    };
});
