<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useProductTypeStore } from '@/stores/productTypeStore';
import type { ProductType } from '@/types/productType';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const productTypeStore = useProductTypeStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const productTypeDialog = ref(false);
const deleteProductTypeDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await loadProductTypes();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadProductTypes(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: productTypeStore.pagination.paginate
    };

    await productTypeStore.fetchProductTypes(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadProductTypes();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadProductTypes();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadProductTypes();
    }
}

async function saveProductType(): Promise<void> {
    submitted.value = true;

    if (productTypeStore.productType.name?.trim()) {
        try {
            const payload = {
                name: productTypeStore.productType.name,
                description: productTypeStore.productType.description || ''
            };

            if (productTypeStore.productType.id) {
                await productTypeStore.updateProductType(productTypeStore.productType.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Tipo de produto atualizado com sucesso', life: 3000 });
            } else {
                await productTypeStore.createProductType(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Tipo de produto criado com sucesso', life: 3000 });
            }

            productTypeDialog.value = false;
            productTypeStore.clearProductType();
            await loadProductTypes();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: productTypeStore.error || 'Erro ao salvar tipo de produto', life: 3000 });
        }
    }
}

async function deleteProductType(): Promise<void> {
    if (productTypeStore.productType.id) {
        try {
            await productTypeStore.deleteProductType(productTypeStore.productType.id);
            deleteProductTypeDialog.value = false;
            productTypeStore.clearProductType();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Tipo de produto deletado com sucesso', life: 3000 });
            await loadProductTypes();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: productTypeStore.error || 'Erro ao deletar tipo de produto', life: 3000 });
        }
    }
}

function openNew(): void {
    productTypeStore.clearProductType();
    submitted.value = false;
    productTypeDialog.value = true;
}

function hideDialog(): void {
    productTypeDialog.value = false;
    submitted.value = false;
}

function editProductType(productTypeData: ProductType): void {
    productTypeStore.productType = { ...productTypeData };
    productTypeDialog.value = true;
}

function confirmDeleteProductType(productTypeData: ProductType): void {
    productTypeStore.productType = productTypeData;
    deleteProductTypeDialog.value = true;
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Tipo de Produto" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="productTypeStore.productTypes"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="productTypeStore.pagination.total"
                        :loading="productTypeStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} tipos de produto"
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
                                <span class="text-xl text-900 font-bold">Tipos de Produto</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar tipo..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="productTypeStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="name" header="Nome" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>

                        <Column field="description" header="Descrição" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.description || '-' }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editProductType(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteProductType(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="productTypeDialog" modal maximizable header="Detalhes do Tipo de Produto" :style="{ width: '50vw' }">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" class="mt-2" v-model.trim="productTypeStore.productType.name" required autofocus :invalid="submitted && !productTypeStore.productType.name" placeholder="Digite o nome do tipo" fluid />
                    <small v-if="submitted && !productTypeStore.productType.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="description" class="font-bold mb-3">Descrição</label>
                    <Textarea id="description" class="mt-2" v-model="productTypeStore.productType.description" placeholder="Digite a descrição" fluid />
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveProductType" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteProductTypeDialog" modal maximizable header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="productTypeStore.productType"
                    >Tem certeza de que deseja excluir <b>{{ productTypeStore.productType.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteProductTypeDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteProductType" />
            </template>
        </Dialog>
    </div>
</template>
