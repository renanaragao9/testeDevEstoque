<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useSupplierStore } from '@/stores/supplierStore';
import type { Supplier } from '@/types/supplier';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const supplierStore = useSupplierStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const supplierDialog = ref(false);
const deleteSupplierDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await loadSuppliers();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadSuppliers(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: supplierStore.pagination.paginate
    };

    await supplierStore.fetchSuppliers(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadSuppliers();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadSuppliers();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadSuppliers();
    }
}

async function saveSupplier(): Promise<void> {
    submitted.value = true;

    if (supplierStore.supplier.name?.trim() && supplierStore.supplier.email?.trim() && supplierStore.supplier.phone?.trim() && supplierStore.supplier.address?.trim()) {
        try {
            const payload = {
                name: supplierStore.supplier.name,
                email: supplierStore.supplier.email,
                phone: supplierStore.supplier.phone,
                address: supplierStore.supplier.address
            };

            if (supplierStore.supplier.id) {
                await supplierStore.updateSupplier(supplierStore.supplier.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Fornecedor atualizado com sucesso', life: 3000 });
            } else {
                await supplierStore.createSupplier(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Fornecedor criado com sucesso', life: 3000 });
            }

            supplierDialog.value = false;
            supplierStore.clearSupplier();
            await loadSuppliers();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: supplierStore.error || 'Erro ao salvar fornecedor', life: 3000 });
        }
    }
}

async function deleteSupplier(): Promise<void> {
    if (supplierStore.supplier.id) {
        try {
            await supplierStore.deleteSupplier(supplierStore.supplier.id);
            deleteSupplierDialog.value = false;
            supplierStore.clearSupplier();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Fornecedor deletado com sucesso', life: 3000 });
            await loadSuppliers();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: supplierStore.error || 'Erro ao deletar fornecedor', life: 3000 });
        }
    }
}

function openNew(): void {
    supplierStore.clearSupplier();
    submitted.value = false;
    supplierDialog.value = true;
}

function hideDialog(): void {
    supplierDialog.value = false;
    submitted.value = false;
}

function editSupplier(supplierData: Supplier): void {
    supplierStore.supplier = { ...supplierData };
    supplierDialog.value = true;
}

function confirmDeleteSupplier(supplierData: Supplier): void {
    supplierStore.supplier = supplierData;
    deleteSupplierDialog.value = true;
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Fornecedor" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="supplierStore.suppliers"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="supplierStore.pagination.total"
                        :loading="supplierStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} fornecedores"
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
                                <span class="text-xl text-900 font-bold">Fornecedores</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar fornecedores..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="supplierStore.loading" />
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

                        <Column field="address" header="Endereço" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.address }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editSupplier(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteSupplier(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="supplierDialog" modal maximizable header="Detalhes do Fornecedor" :style="{ width: '60vw' }">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" class="mt-2" v-model.trim="supplierStore.supplier.name" required autofocus :invalid="submitted && !supplierStore.supplier.name" placeholder="Digite o nome do fornecedor" fluid />
                    <small v-if="submitted && !supplierStore.supplier.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="email" class="font-bold mb-3">Email <span class="text-red-500">*</span></label>
                    <InputText id="email" class="mt-2" v-model.trim="supplierStore.supplier.email" type="email" required :invalid="submitted && !supplierStore.supplier.email" placeholder="Digite o email do fornecedor" fluid />
                    <small v-if="submitted && !supplierStore.supplier.email" class="text-red-500">Email é obrigatório.</small>
                </div>

                <div>
                    <label for="phone" class="font-bold mb-3">Telefone <span class="text-red-500">*</span></label>
                    <InputText id="phone" class="mt-2" v-model.trim="supplierStore.supplier.phone" required :invalid="submitted && !supplierStore.supplier.phone" placeholder="Digite o telefone do fornecedor" fluid />
                    <small v-if="submitted && !supplierStore.supplier.phone" class="text-red-500">Telefone é obrigatório.</small>
                </div>

                <div class="md:col-span-1">
                    <label for="address" class="font-bold mb-3">Endereço <span class="text-red-500">*</span></label>
                    <Textarea id="address" class="mt-2" v-model="supplierStore.supplier.address" required :invalid="submitted && !supplierStore.supplier.address" placeholder="Digite o endereço do fornecedor" fluid rows="3" />
                    <small v-if="submitted && !supplierStore.supplier.address" class="text-red-500">Endereço é obrigatório.</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveSupplier" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteSupplierDialog" modal maximizable header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="supplierStore.supplier"
                    >Tem certeza de que deseja excluir <b>{{ supplierStore.supplier.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteSupplierDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteSupplier" />
            </template>
        </Dialog>
    </div>
</template>
