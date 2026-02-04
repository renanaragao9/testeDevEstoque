<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useCustomerStore } from '@/stores/customerStore';
import type { Customer } from '@/types/customer';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const customerStore = useCustomerStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const customerDialog = ref(false);
const deleteCustomerDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await loadCustomers();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadCustomers(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: customerStore.pagination.paginate
    };

    await customerStore.fetchCustomers(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadCustomers();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadCustomers();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadCustomers();
    }
}

async function saveCustomer(): Promise<void> {
    submitted.value = true;

    if (customerStore.customer.name?.trim() && customerStore.customer.email?.trim() && customerStore.customer.phone?.trim()) {
        try {
            const payload = {
                name: customerStore.customer.name,
                email: customerStore.customer.email,
                phone: customerStore.customer.phone
            };

            if (customerStore.customer.id) {
                await customerStore.updateCustomer(customerStore.customer.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Cliente atualizado com sucesso', life: 3000 });
            } else {
                await customerStore.createCustomer(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Cliente criado com sucesso', life: 3000 });
            }

            customerDialog.value = false;
            customerStore.clearCustomer();
            await loadCustomers();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: customerStore.error || 'Erro ao salvar cliente', life: 3000 });
        }
    }
}

async function deleteCustomer(): Promise<void> {
    if (customerStore.customer.id) {
        try {
            await customerStore.deleteCustomer(customerStore.customer.id);
            deleteCustomerDialog.value = false;
            customerStore.clearCustomer();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Cliente deletado com sucesso', life: 3000 });
            await loadCustomers();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: customerStore.error || 'Erro ao deletar cliente', life: 3000 });
        }
    }
}

function openNew(): void {
    customerStore.clearCustomer();
    submitted.value = false;
    customerDialog.value = true;
}

function hideDialog(): void {
    customerDialog.value = false;
    submitted.value = false;
}

function editCustomer(customerData: Customer): void {
    customerStore.customer = { ...customerData };
    customerDialog.value = true;
}

function confirmDeleteCustomer(customerData: Customer): void {
    customerStore.customer = customerData;
    deleteCustomerDialog.value = true;
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Cliente" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="customerStore.customers"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="customerStore.pagination.total"
                        :loading="customerStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} clientes"
                        :lazy="true"
                        @page="onPageChange"
                        @sort="onSort"
                        sortMode="single"
                        :sort-field="orderByColumn"
                        :sort-order="orderByDirection === 'asc' ? 1 : -1"
                        removableSort
                    >
                        <template #header>
                            <div class="flex flex-wrap gap-2 items-center justify-between">
                                <span class="text-xl text-900 font-bold">Clientes</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar clientes..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="customerStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="name" header="Nome" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>

                        <Column field="email" header="Email" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.email }}
                            </template>
                        </Column>

                        <Column field="phone" header="Telefone" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.phone }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editCustomer(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteCustomer(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="customerDialog" modal maximizable header="Detalhes do Cliente" :style="{ width: '50vw' }">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" class="mt-2" v-model.trim="customerStore.customer.name" required autofocus :invalid="submitted && !customerStore.customer.name" placeholder="Digite o nome do cliente" fluid />
                    <small v-if="submitted && !customerStore.customer.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="email" class="font-bold mb-3">Email <span class="text-red-500">*</span></label>
                    <InputText id="email" class="mt-2" v-model.trim="customerStore.customer.email" type="email" required :invalid="submitted && !customerStore.customer.email" placeholder="Digite o email do cliente" fluid />
                    <small v-if="submitted && !customerStore.customer.email" class="text-red-500">Email é obrigatório.</small>
                </div>

                <div>
                    <label for="phone" class="font-bold mb-3">Telefone <span class="text-red-500">*</span></label>
                    <InputText id="phone" class="mt-2" v-model.trim="customerStore.customer.phone" required :invalid="submitted && !customerStore.customer.phone" placeholder="Digite o telefone do cliente" fluid />
                    <small v-if="submitted && !customerStore.customer.phone" class="text-red-500">Telefone é obrigatório.</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveCustomer" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteCustomerDialog" modal maximizable header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="customerStore.customer"
                    >Tem certeza de que deseja excluir <b>{{ customerStore.customer.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteCustomerDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteCustomer" />
            </template>
        </Dialog>
    </div>
</template>
