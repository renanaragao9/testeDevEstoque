<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useSpecificationStore } from '@/stores/specificationStore';
import type { Specification } from '@/types/specification';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const specificationStore = useSpecificationStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const specificationDialog = ref(false);
const deleteSpecificationDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await loadSpecifications();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadSpecifications(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: specificationStore.pagination.paginate
    };

    await specificationStore.fetchSpecifications(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadSpecifications();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadSpecifications();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadSpecifications();
    }
}

async function saveSpecification(): Promise<void> {
    submitted.value = true;

    if (specificationStore.specification.name?.trim()) {
        try {
            const payload = {
                name: specificationStore.specification.name,
                is_active: specificationStore.specification.is_active
            };

            if (specificationStore.specification.id) {
                await specificationStore.updateSpecification(specificationStore.specification.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Especificação atualizada com sucesso', life: 3000 });
            } else {
                await specificationStore.createSpecification(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Especificação criada com sucesso', life: 3000 });
            }

            specificationDialog.value = false;
            specificationStore.clearSpecification();
            await loadSpecifications();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: specificationStore.error || 'Erro ao salvar especificação', life: 3000 });
        }
    }
}

async function deleteSpecification(): Promise<void> {
    if (specificationStore.specification.id) {
        try {
            await specificationStore.deleteSpecification(specificationStore.specification.id);
            deleteSpecificationDialog.value = false;
            specificationStore.clearSpecification();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Especificação deletada com sucesso', life: 3000 });
            await loadSpecifications();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: specificationStore.error || 'Erro ao deletar especificação', life: 3000 });
        }
    }
}

function openNew(): void {
    specificationStore.clearSpecification();
    submitted.value = false;
    specificationDialog.value = true;
}

function hideDialog(): void {
    specificationDialog.value = false;
    submitted.value = false;
}

function editSpecification(specificationData: Specification): void {
    specificationStore.specification = { ...specificationData };
    specificationDialog.value = true;
}

function confirmDeleteSpecification(specificationData: Specification): void {
    specificationStore.specification = specificationData;
    deleteSpecificationDialog.value = true;
}

function getStatusLabel(isActive: boolean): string {
    return isActive ? 'Ativo' : 'Inativo';
}

function getStatusSeverity(isActive: boolean): 'success' | 'danger' {
    return isActive ? 'success' : 'danger';
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Especificação" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="specificationStore.specifications"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="specificationStore.pagination.total"
                        :loading="specificationStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} especificações"
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
                                <span class="text-xl text-900 font-bold">Especificações</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar especificação..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="specificationStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="name" header="Nome" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="is_active" header="Status" sortable>
                            <template #body="slotProps">
                                <Tag :value="getStatusLabel(slotProps.data.is_active)" :severity="getStatusSeverity(slotProps.data.is_active)" />
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editSpecification(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteSpecification(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="specificationDialog" modal header="Detalhes da Especificação" :style="{ width: '50vw' }">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" v-model.trim="specificationStore.specification.name" required autofocus :invalid="submitted && !specificationStore.specification.name" placeholder="Digite o nome da especificação" fluid />
                    <small v-if="submitted && !specificationStore.specification.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="is_active" class="font-bold mb-3">Status</label>
                    <div class="flex items-center">
                        <InputSwitch id="is_active" v-model="specificationStore.specification.is_active" />
                        <label for="is_active" class="ml-2">{{ specificationStore.specification.is_active ? 'Ativo' : 'Inativo' }}</label>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveSpecification" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteSpecificationDialog" modal header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="specificationStore.specification"
                    >Tem certeza de que deseja excluir <b>{{ specificationStore.specification.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteSpecificationDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteSpecification" />
            </template>
        </Dialog>
    </div>
</template>
