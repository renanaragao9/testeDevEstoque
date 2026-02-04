<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useMarkStore } from '@/stores/markStore';
import type { Mark } from '@/types/mark';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const markStore = useMarkStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const markDialog = ref(false);
const deleteMarkDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await loadMarks();
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadMarks(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: markStore.pagination.paginate
    };

    await markStore.fetchMarks(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadMarks();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadMarks();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadMarks();
    }
}

async function saveMark(): Promise<void> {
    submitted.value = true;

    if (markStore.mark.name?.trim()) {
        try {
            const payload = {
                name: markStore.mark.name,
                description: markStore.mark.description || '',
                country: markStore.mark.country || ''
            };

            if (markStore.mark.id) {
                await markStore.updateMark(markStore.mark.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Marca atualizada com sucesso', life: 3000 });
            } else {
                await markStore.createMark(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Marca criada com sucesso', life: 3000 });
            }

            markDialog.value = false;
            markStore.clearMark();
            await loadMarks();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: markStore.error || 'Erro ao salvar marca', life: 3000 });
        }
    }
}

async function deleteMark(): Promise<void> {
    if (markStore.mark.id) {
        try {
            await markStore.deleteMark(markStore.mark.id);
            deleteMarkDialog.value = false;
            markStore.clearMark();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Marca deletada com sucesso', life: 3000 });
            await loadMarks();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: markStore.error || 'Erro ao deletar marca', life: 3000 });
        }
    }
}

function openNew(): void {
    markStore.clearMark();
    submitted.value = false;
    markDialog.value = true;
}

function hideDialog(): void {
    markDialog.value = false;
    submitted.value = false;
}

function editMark(markData: Mark): void {
    markStore.mark = { ...markData };
    markDialog.value = true;
}

function confirmDeleteMark(markData: Mark): void {
    markStore.mark = markData;
    deleteMarkDialog.value = true;
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Marca" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="markStore.marks"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="markStore.pagination.total"
                        :loading="markStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} marcas"
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
                                <span class="text-xl text-900 font-bold">Marcas</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar marca..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="markStore.loading" />
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

                        <Column field="country" header="País" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.country || '-' }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editMark(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteMark(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="markDialog" modal maximizable header="Detalhes da Marca" :style="{ width: '50vw' }">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" class="mt-2" v-model.trim="markStore.mark.name" required autofocus :invalid="submitted && !markStore.mark.name" placeholder="Digite o nome da marca" fluid />
                    <small v-if="submitted && !markStore.mark.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="description" class="font-bold mb-3">Descrição</label>
                    <Textarea id="description" class="mt-2" v-model="markStore.mark.description" placeholder="Digite a descrição" fluid />
                </div>

                <div>
                    <label for="country" class="font-bold mb-3">País</label>
                    <InputText id="country" class="mt-2" v-model="markStore.mark.country" placeholder="Digite o país de origem" fluid />
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveMark" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteMarkDialog" modal maximizable header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="markStore.mark"
                    >Tem certeza de que deseja excluir <b>{{ markStore.mark.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteMarkDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteMark" />
            </template>
        </Dialog>
    </div>
</template>
