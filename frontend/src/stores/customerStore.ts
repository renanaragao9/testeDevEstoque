import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Customer, CustomerPayload } from '@/types/customer';
import type { BaseFilters } from '@/types/global/filters';
import { CustomerService } from '@/services/CustomerService';
import { handleApiError } from '@/utils/errorHandler';

export const useCustomerStore = defineStore('customer', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const customers = ref<Customer[]>([]);
    const customer = ref<Customer>({
        id: 0,
        name: '',
        email: '',
        phone: ''
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });
    const customerOptions = computed(() =>
        customers.value.map((customer) => ({
            label: customer.name,
            value: customer.id
        }))
    );

    async function fetchCustomers(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await CustomerService.getCustomers(filters);
            customers.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar clientes');
        } finally {
            loading.value = false;
        }
    }

    async function createCustomer(payload: CustomerPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await CustomerService.createCustomer(payload);
            customers.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar cliente');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateCustomer(id: number, payload: CustomerPayload) {
        loading.value = true;
        error.value = null;
        try {
            const result = await CustomerService.updateCustomer(id, payload);
            const index = customers.value.findIndex((customer) => customer.id === id);
            if (index !== -1) {
                customers.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar cliente');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteCustomer(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await CustomerService.deleteCustomer(id);
            customers.value = customers.value.filter((customer) => customer.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar cliente');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearCustomer() {
        customer.value = {
            id: 0,
            name: '',
            email: '',
            phone: ''
        };
    }

    return {
        customers,
        customer,
        loading,
        error,
        pagination,
        customerOptions,
        fetchCustomers,
        createCustomer,
        updateCustomer,
        deleteCustomer,
        clearCustomer
    };
});
