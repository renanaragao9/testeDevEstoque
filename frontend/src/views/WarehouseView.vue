<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useWarehouseStore } from '@/stores/warehouseStore';
import type { Warehouse } from '@/types/warehouse';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const warehouseStore = useWarehouseStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const warehouseDialog = ref(false);
const deleteWarehouseDialog = ref(false);
const submitted = ref(false);
const expandedRows = ref({});

const toast = useToast();

onMounted(async () => {
    try {
        await loadWarehouses();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadWarehouses(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: warehouseStore.pagination.paginate
    };

    await warehouseStore.fetchWarehouses(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadWarehouses();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadWarehouses();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadWarehouses();
    }
}

async function saveWarehouse(): Promise<void> {
    submitted.value = true;

    if (warehouseStore.warehouse.name?.trim() && warehouseStore.warehouse.location?.trim()) {
        try {
            const payload = {
                name: warehouseStore.warehouse.name,
                location: warehouseStore.warehouse.location
            };

            if (warehouseStore.warehouse.id) {
                await warehouseStore.updateWarehouse(warehouseStore.warehouse.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Armazém atualizado com sucesso', life: 3000 });
            } else {
                await warehouseStore.createWarehouse(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Armazém criado com sucesso', life: 3000 });
            }

            warehouseDialog.value = false;
            warehouseStore.clearWarehouse();
            await loadWarehouses();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: warehouseStore.error || 'Erro ao salvar armazém', life: 3000 });
        }
    }
}

async function deleteWarehouse(): Promise<void> {
    if (warehouseStore.warehouse.id) {
        try {
            await warehouseStore.deleteWarehouse(warehouseStore.warehouse.id);
            deleteWarehouseDialog.value = false;
            warehouseStore.clearWarehouse();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Armazém deletado com sucesso', life: 3000 });
            await loadWarehouses();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: warehouseStore.error || 'Erro ao deletar armazém', life: 3000 });
        }
    }
}

function openNew(): void {
    warehouseStore.clearWarehouse();
    submitted.value = false;
    warehouseDialog.value = true;
}

function hideDialog(): void {
    warehouseDialog.value = false;
    submitted.value = false;
}

function editWarehouse(warehouseData: Warehouse): void {
    warehouseStore.warehouse = { ...warehouseData };
    warehouseDialog.value = true;
}

function confirmDeleteWarehouse(warehouseData: Warehouse): void {
    warehouseStore.warehouse = warehouseData;
    deleteWarehouseDialog.value = true;
}

function formatCurrency(value: number | undefined): string {
    if (!value) return '0,00';
    return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function expandAll(): void {
    expandedRows.value = warehouseStore.warehouses
        .filter((w) => w.products && w.products.length > 0)
        .reduce(
            (acc, warehouse) => {
                acc[warehouse.id] = true;
                return acc;
            },
            {} as Record<number, boolean>
        );
}

function collapseAll(): void {
    expandedRows.value = {};
}

function onRowExpand(): void {}
function onRowCollapse(): void {}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Armazém" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        v-model:expandedRows="expandedRows"
                        :value="warehouseStore.warehouses"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="warehouseStore.pagination.total"
                        :loading="warehouseStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} armazéns"
                        :lazy="true"
                        @page="onPageChange"
                        @sort="onSort"
                        @rowExpand="onRowExpand"
                        @rowCollapse="onRowCollapse"
                        sortMode="single"
                        :sort-field="orderByColumn"
                        :sort-order="orderByDirection === 'asc' ? 1 : -1"
                        removableSort
                        tableStyle="min-width: 60rem"
                    >
                        <template #header>
                            <div class="flex flex-wrap gap-2 items-center justify-between">
                                <span class="text-xl text-900 font-bold">Armazéns</span>
                                <div class="flex gap-2">
                                    <Button variant="text" icon="pi pi-plus" label="Expandir Todos" @click="expandAll" />
                                    <Button variant="text" icon="pi pi-minus" label="Recolher Todos" @click="collapseAll" />
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar armazéns..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="warehouseStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column expander style="width: 5rem" />
                        <Column field="name" header="Nome" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="location" header="Localização" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.location }}
                            </template>
                        </Column>
                        <Column field="totalStock" header="Total Estoque" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.totalStock || 0 }}
                            </template>
                        </Column>
                        <Column field="totalStockValue" header="Valor Total" sortable>
                            <template #body="slotProps"> R$ {{ formatCurrency(slotProps.data.totalStockValue) }} </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editWarehouse(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteWarehouse(slotProps.data)" />
                            </template>
                        </Column>

                        <template #expansion="slotProps">
                            <div class="p-4">
                                <h5 class="mb-4">Produtos no armazém {{ slotProps.data.name }}</h5>
                                <DataTable v-if="slotProps.data.products && slotProps.data.products.length > 0" :value="slotProps.data.products">
                                    <Column field="name" header="Nome do Produto" sortable></Column>
                                    <Column field="total" header="Total em Estoque" sortable>
                                        <template #body="productSlot">
                                            <Tag :value="productSlot.data.total" severity="info" />
                                        </template>
                                    </Column>
                                    <Column field="average_cost" header="Custo Médio" sortable>
                                        <template #body="productSlot"> R$ {{ formatCurrency(productSlot.data.average_cost) }} </template>
                                    </Column>
                                </DataTable>
                                <div v-else class="text-center py-4">
                                    <i class="pi pi-inbox text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500">Nenhum produto encontrado neste armazém</p>
                                </div>
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="warehouseDialog" modal header="Detalhes do Armazém" :style="{ width: '50vw' }">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" v-model.trim="warehouseStore.warehouse.name" required autofocus :invalid="submitted && !warehouseStore.warehouse.name" placeholder="Digite o nome do armazém" fluid />
                    <small v-if="submitted && !warehouseStore.warehouse.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="location" class="font-bold mb-3">Localização <span class="text-red-500">*</span></label>
                    <InputText id="location" v-model.trim="warehouseStore.warehouse.location" required :invalid="submitted && !warehouseStore.warehouse.location" placeholder="Digite a localização do armazém" fluid />
                    <small v-if="submitted && !warehouseStore.warehouse.location" class="text-red-500">Localização é obrigatória.</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveWarehouse" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteWarehouseDialog" modal header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="warehouseStore.warehouse"
                    >Tem certeza de que deseja excluir <b>{{ warehouseStore.warehouse.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteWarehouseDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteWarehouse" />
            </template>
        </Dialog>
    </div>
</template>
