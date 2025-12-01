<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useSaleStore } from '@/stores/saleStore';
import { useCustomerStore } from '@/stores/customerStore';
import { useProductStore } from '@/stores/productStore';
import type { Sale } from '@/types/sale';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const saleStore = useSaleStore();
const customerStore = useCustomerStore();
const productStore = useProductStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('invoice_number');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const saleDialog = ref(false);
const deleteSaleDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await Promise.all([loadSales(), customerStore.fetchCustomers({ paginate: false }), productStore.fetchProducts({ paginate: false })]);
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadSales(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: saleStore.pagination.paginate
    };

    await saleStore.fetchSales(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadSales();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadSales();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'invoice_number';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadSales();
    }
}

async function saveSale(): Promise<void> {
    submitted.value = true;

    if (saleStore.sale.invoiceNumber?.trim() && saleStore.sale.saleDate && saleStore.sale.totalAmount > 0 && saleStore.saleItems.length > 0) {
        const isValidItems = saleStore.saleItems.every((item) => item.productId > 0 && item.quantity > 0);

        if (!isValidItems) {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Todos os itens devem ter produto e quantidade válidos', life: 3000 });
            return;
        }

        try {
            const payload = {
                invoiceNumber: saleStore.sale.invoiceNumber,
                saleDate: saleStore.sale.saleDate,
                totalAmount: saleStore.sale.totalAmount,
                status: saleStore.sale.status,
                customerId: saleStore.sale.customerId,
                items: saleStore.saleItems.map((item) => ({
                    productId: item.productId,
                    quantity: item.quantity
                }))
            };

            if (saleStore.sale.id) {
                await saleStore.updateSale(saleStore.sale.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Venda atualizada com sucesso', life: 3000 });
            } else {
                await saleStore.createSale(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Venda criada com sucesso', life: 3000 });
            }

            saleDialog.value = false;
            saleStore.clearSale();
            await loadSales();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: saleStore.error || 'Erro ao salvar venda', life: 3000 });
        }
    } else {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Preencha todos os campos obrigatórios e adicione pelo menos um item', life: 3000 });
    }
}

async function deleteSale(): Promise<void> {
    if (saleStore.sale.id) {
        try {
            await saleStore.deleteSale(saleStore.sale.id);
            deleteSaleDialog.value = false;
            saleStore.clearSale();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Venda deletada com sucesso', life: 3000 });
            await loadSales();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: saleStore.error || 'Erro ao deletar venda', life: 3000 });
        }
    }
}

function openNew(): void {
    saleStore.clearSale();
    saleStore.addSaleItem(); // Adiciona um item inicial
    submitted.value = false;
    saleDialog.value = true;
}

function hideDialog(): void {
    saleDialog.value = false;
    submitted.value = false;
}

function editSale(saleData: Sale): void {
    saleStore.sale = { ...saleData };
    // Carrega os itens da venda se existirem
    if (saleData.stockMovements) {
        saleStore.saleItems = saleData.stockMovements.map((movement) => ({
            id: movement.id,
            productId: movement.productId,
            quantity: movement.quantity
        }));
    } else {
        saleStore.addSaleItem();
    }
    saleDialog.value = true;
}

function confirmDeleteSale(saleData: Sale): void {
    saleStore.sale = saleData;
    deleteSaleDialog.value = true;
}

function addItem(): void {
    saleStore.addSaleItem();
}

function removeItem(index: number): void {
    if (saleStore.saleItems.length > 1) {
        saleStore.removeSaleItem(index);
    }
}

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

function formatDate(dateString: string): string {
    return new Date(dateString).toLocaleDateString('pt-BR');
}

function getStatusSeverity(status: string): 'success' | 'warning' | 'danger' | 'info' {
    switch (status) {
        case 'completed':
            return 'success';
        case 'pending':
            return 'warning';
        case 'cancelled':
            return 'danger';
        default:
            return 'info';
    }
}

function getStatusLabel(status: string): string {
    switch (status) {
        case 'completed':
            return 'Concluída';
        case 'pending':
            return 'Pendente';
        case 'cancelled':
            return 'Cancelada';
        default:
            return status;
    }
}
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Venda" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="saleStore.sales"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="saleStore.pagination.total"
                        :loading="saleStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} vendas"
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
                                <span class="text-xl text-900 font-bold">Vendas</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar vendas..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="saleStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="invoiceNumber" header="Nº Fatura" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.invoiceNumber }}
                            </template>
                        </Column>
                        <Column field="saleDate" header="Data" sortable>
                            <template #body="slotProps">
                                {{ formatDate(slotProps.data.saleDate) }}
                            </template>
                        </Column>
                        <Column field="customer.name" header="Cliente" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.customer?.name || 'Sem cliente' }}
                            </template>
                        </Column>
                        <Column field="status" header="Status" sortable>
                            <template #body="slotProps">
                                <Tag :value="getStatusLabel(slotProps.data.status)" :severity="getStatusSeverity(slotProps.data.status)" />
                            </template>
                        </Column>
                        <Column field="totalAmount" header="Total" sortable>
                            <template #body="slotProps">
                                {{ formatCurrency(slotProps.data.totalAmount) }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editSale(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteSale(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="saleDialog" modal header="Detalhes da Venda" :style="{ width: '80vw' }" :maximizable="true">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="invoiceNumber" class="font-bold mb-3">Nº Fatura <span class="text-red-500">*</span></label>
                    <InputText id="invoiceNumber" v-model.trim="saleStore.sale.invoiceNumber" required autofocus :invalid="submitted && !saleStore.sale.invoiceNumber" placeholder="Digite o número da fatura" fluid />
                    <small v-if="submitted && !saleStore.sale.invoiceNumber" class="text-red-500">Número da fatura é obrigatório.</small>
                </div>

                <div>
                    <label for="saleDate" class="font-bold mb-3">Data da Venda <span class="text-red-500">*</span></label>
                    <Calendar id="saleDate" v-model="saleStore.sale.saleDate" dateFormat="dd/mm/yy" :invalid="submitted && !saleStore.sale.saleDate" placeholder="Selecione a data" fluid />
                    <small v-if="submitted && !saleStore.sale.saleDate" class="text-red-500">Data da venda é obrigatória.</small>
                </div>

                <div>
                    <label for="status" class="font-bold mb-3">Status</label>
                    <Dropdown id="status" v-model="saleStore.sale.status" :options="saleStore.statusOptions" optionLabel="label" optionValue="value" placeholder="Selecione um status" fluid />
                </div>

                <div>
                    <label for="customer" class="font-bold mb-3">Cliente</label>
                    <Dropdown id="customer" v-model="saleStore.sale.customerId" :options="customerStore.customerOptions" optionLabel="label" optionValue="value" placeholder="Selecione um cliente" showClear fluid />
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Itens da Venda</h3>
                    <Button label="Adicionar Item" icon="pi pi-plus" size="small" @click="addItem" />
                </div>

                <div v-for="(item, index) in saleStore.saleItems" :key="index" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-4 p-4 border rounded">
                    <div>
                        <label :for="`product-${index}`" class="font-bold mb-3">Produto <span class="text-red-500">*</span></label>
                        <Dropdown :id="`product-${index}`" v-model="item.productId" :options="productStore.productOptions" optionLabel="label" optionValue="value" :invalid="submitted && !item.productId" placeholder="Selecione um produto" fluid />
                    </div>

                    <div>
                        <label :for="`quantity-${index}`" class="font-bold mb-3">Quantidade <span class="text-red-500">*</span></label>
                        <InputNumber :id="`quantity-${index}`" v-model="item.quantity" :min="1" :invalid="submitted && (!item.quantity || item.quantity <= 0)" placeholder="Quantidade" fluid />
                    </div>

                    <div>
                        <Button icon="pi pi-trash" severity="danger" outlined @click="removeItem(index)" :disabled="saleStore.saleItems.length === 1" />
                    </div>
                </div>

                <small v-if="submitted && saleStore.saleItems.length === 0" class="text-red-500">Adicione pelo menos um item à venda.</small>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-start-4">
                    <label for="totalAmount" class="font-bold mb-3">Valor Total <span class="text-red-500">*</span></label>
                    <InputNumber
                        id="totalAmount"
                        v-model="saleStore.sale.totalAmount"
                        :minFractionDigits="2"
                        :maxFractionDigits="2"
                        mode="currency"
                        currency="BRL"
                        locale="pt-BR"
                        :invalid="submitted && (!saleStore.sale.totalAmount || saleStore.sale.totalAmount <= 0)"
                        placeholder="0,00"
                        fluid
                    />
                    <small v-if="submitted && (!saleStore.sale.totalAmount || saleStore.sale.totalAmount <= 0)" class="text-red-500">Valor total deve ser maior que zero.</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveSale" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteSaleDialog" modal header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="saleStore.sale"
                    >Tem certeza de que deseja excluir a venda <b>{{ saleStore.sale.invoiceNumber }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteSaleDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteSale" />
            </template>
        </Dialog>
    </div>
</template>
