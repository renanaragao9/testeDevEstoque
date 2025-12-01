import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Purchase, PurchasePayload, PurchaseItem } from '@/types/purchase';
import type { BaseFilters } from '@/types/global/filters';
import { PurchaseService } from '@/services/PurchaseService';
import { handleApiError } from '@/utils/errorHandler';

export const usePurchaseStore = defineStore('purchase', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const purchases = ref<Purchase[]>([]);
    const purchase = ref<Purchase>({
        id: 0,
        invoiceNumber: '',
        purchaseDate: new Date().toISOString().split('T')[0],
        totalAmount: 0,
        supplierId: 0
    });
    const purchaseItems = ref<PurchaseItem[]>([]);
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });

    const purchaseOptions = computed(() =>
        purchases.value.map((purchase) => ({
            label: purchase.invoiceNumber,
            value: purchase.id
        }))
    );

    async function fetchPurchases(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await PurchaseService.getPurchases(filters);
            purchases.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar compras');
        } finally {
            loading.value = false;
        }
    }

    async function createPurchase(payload: PurchasePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await PurchaseService.createPurchase(payload);
            purchases.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar compra');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updatePurchase(id: number, payload: PurchasePayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await PurchaseService.updatePurchase(id, payload);
            const index = purchases.value.findIndex((purchase) => purchase.id === id);
            if (index !== -1) {
                purchases.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar compra');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deletePurchase(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await PurchaseService.deletePurchase(id);
            purchases.value = purchases.value.filter((purchase) => purchase.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar compra');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearPurchase() {
        purchase.value = {
            id: 0,
            invoiceNumber: '',
            purchaseDate: new Date().toISOString().split('T')[0],
            totalAmount: 0,
            supplierId: 0
        };
        purchaseItems.value = [];
    }

    function addPurchaseItem() {
        purchaseItems.value.push({
            productId: 0,
            warehouseId: 0,
            quantity: 1
        });
    }

    function removePurchaseItem(index: number) {
        purchaseItems.value.splice(index, 1);
    }

    function clearPurchaseItems() {
        purchaseItems.value = [];
    }

    return {
        purchases,
        purchase,
        purchaseItems,
        loading,
        error,
        pagination,
        purchaseOptions,
        fetchPurchases,
        createPurchase,
        updatePurchase,
        deletePurchase,
        clearPurchase,
        addPurchaseItem,
        removePurchaseItem,
        clearPurchaseItems
    };
});
