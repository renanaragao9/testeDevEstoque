<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { usePurchaseStore } from '@/stores/purchaseStore';
import { useSupplierStore } from '@/stores/supplierStore';
import { useWarehouseStore } from '@/stores/warehouseStore';
import { useProductStore } from '@/stores/productStore';
import type { Purchase } from '@/types/purchase';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const purchaseStore = usePurchaseStore();
const supplierStore = useSupplierStore();
const warehouseStore = useWarehouseStore();
const productStore = useProductStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('invoice_number');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const purchaseDialog = ref(false);
const deletePurchaseDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

onMounted(async () => {
    try {
        await Promise.all([loadPurchases(), supplierStore.fetchSuppliers({ paginate: false }), warehouseStore.fetchWarehouses({ paginate: false }), productStore.fetchProducts({ paginate: false })]);
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadPurchases(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: purchaseStore.pagination.paginate
    };

    await purchaseStore.fetchPurchases(filters);
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadPurchases();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadPurchases();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'invoice_number';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadPurchases();
    }
}

async function savePurchase(): Promise<void> {
    submitted.value = true;

    if (purchaseStore.purchase.invoiceNumber?.trim() && purchaseStore.purchase.purchaseDate && purchaseStore.purchase.totalAmount > 0 && purchaseStore.purchase.supplierId > 0 && purchaseStore.purchaseItems.length > 0) {
        const isValidItems = purchaseStore.purchaseItems.every((item) => item.productId > 0 && item.warehouseId > 0 && item.quantity > 0);

        if (!isValidItems) {
            toast.add({ severity: 'error', summary: 'Erro', detail: 'Todos os itens devem ter produto, armazém e quantidade válidos', life: 3000 });
            return;
        }

        try {
            const payload = {
                invoiceNumber: purchaseStore.purchase.invoiceNumber,
                purchaseDate: purchaseStore.purchase.purchaseDate,
                totalAmount: purchaseStore.purchase.totalAmount,
                supplierId: purchaseStore.purchase.supplierId,
                items: purchaseStore.purchaseItems.map((item) => ({
                    productId: item.productId,
                    warehouseId: item.warehouseId,
                    quantity: item.quantity
                }))
            };

            if (purchaseStore.purchase.id) {
                await purchaseStore.updatePurchase(purchaseStore.purchase.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Compra atualizada com sucesso', life: 3000 });
            } else {
                await purchaseStore.createPurchase(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Compra criada com sucesso', life: 3000 });
            }

            purchaseDialog.value = false;
            purchaseStore.clearPurchase();
            await loadPurchases();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: purchaseStore.error || 'Erro ao salvar compra', life: 3000 });
        }
    } else {
        toast.add({ severity: 'error', summary: 'Erro', detail: 'Preencha todos os campos obrigatórios e adicione pelo menos um item', life: 3000 });
    }
}

async function deletePurchase(): Promise<void> {
    if (purchaseStore.purchase.id) {
        try {
            await purchaseStore.deletePurchase(purchaseStore.purchase.id);
            deletePurchaseDialog.value = false;
            purchaseStore.clearPurchase();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Compra deletada com sucesso', life: 3000 });
            await loadPurchases();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: purchaseStore.error || 'Erro ao deletar compra', life: 3000 });
        }
    }
}

function openNew(): void {
    purchaseStore.clearPurchase();
    purchaseStore.addPurchaseItem();
    submitted.value = false;
    purchaseDialog.value = true;
}

function hideDialog(): void {
    purchaseDialog.value = false;
    submitted.value = false;
}

function editPurchase(purchaseData: Purchase): void {
    purchaseStore.purchase = { ...purchaseData };
    if (purchaseData.stockMovements) {
        purchaseStore.purchaseItems = purchaseData.stockMovements.map((movement) => ({
            id: movement.id,
            productId: movement.productId,
            warehouseId: movement.warehouseId,
            quantity: movement.quantity
        }));
    } else {
        purchaseStore.addPurchaseItem();
    }
    purchaseDialog.value = true;
}

function confirmDeletePurchase(purchaseData: Purchase): void {
    purchaseStore.purchase = purchaseData;
    deletePurchaseDialog.value = true;
}

function addItem(): void {
    purchaseStore.addPurchaseItem();
}

function removeItem(index: number): void {
    if (purchaseStore.purchaseItems.length > 1) {
        purchaseStore.removePurchaseItem(index);
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
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Compra" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="purchaseStore.purchases"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="purchaseStore.pagination.total"
                        :loading="purchaseStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} compras"
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
                                <span class="text-xl text-900 font-bold">Compras</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar compras..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="purchaseStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="invoiceNumber" header="Nº Fatura" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.invoiceNumber }}
                            </template>
                        </Column>
                        <Column field="purchaseDate" header="Data" sortable>
                            <template #body="slotProps">
                                {{ formatDate(slotProps.data.purchaseDate) }}
                            </template>
                        </Column>
                        <Column field="supplier.name" header="Fornecedor" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.supplier?.name || '-' }}
                            </template>
                        </Column>
                        <Column field="totalAmount" header="Total" sortable>
                            <template #body="slotProps">
                                {{ formatCurrency(slotProps.data.totalAmount) }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editPurchase(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeletePurchase(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="purchaseDialog" modal header="Detalhes da Compra" :style="{ width: '80vw' }" :maximizable="true">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="invoiceNumber" class="font-bold mb-3">Nº Fatura <span class="text-red-500">*</span></label>
                    <InputText id="invoiceNumber" v-model.trim="purchaseStore.purchase.invoiceNumber" required autofocus :invalid="submitted && !purchaseStore.purchase.invoiceNumber" placeholder="Digite o número da fatura" fluid />
                    <small v-if="submitted && !purchaseStore.purchase.invoiceNumber" class="text-red-500">Número da fatura é obrigatório.</small>
                </div>

                <div>
                    <label for="purchaseDate" class="font-bold mb-3">Data da Compra <span class="text-red-500">*</span></label>
                    <Calendar id="purchaseDate" v-model="purchaseStore.purchase.purchaseDate" dateFormat="dd/mm/yy" :invalid="submitted && !purchaseStore.purchase.purchaseDate" placeholder="Selecione a data" fluid />
                    <small v-if="submitted && !purchaseStore.purchase.purchaseDate" class="text-red-500">Data da compra é obrigatória.</small>
                </div>

                <div>
                    <label for="supplier" class="font-bold mb-3">Fornecedor <span class="text-red-500">*</span></label>
                    <Dropdown
                        id="supplier"
                        v-model="purchaseStore.purchase.supplierId"
                        :options="supplierStore.supplierOptions"
                        optionLabel="label"
                        optionValue="value"
                        :invalid="submitted && !purchaseStore.purchase.supplierId"
                        placeholder="Selecione um fornecedor"
                        fluid
                    />
                    <small v-if="submitted && !purchaseStore.purchase.supplierId" class="text-red-500">Fornecedor é obrigatório.</small>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Itens da Compra</h3>
                    <Button label="Adicionar Item" icon="pi pi-plus" size="small" @click="addItem" />
                </div>

                <div v-for="(item, index) in purchaseStore.purchaseItems" :key="index" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-4 p-4 border rounded">
                    <div>
                        <label :for="`product-${index}`" class="font-bold mb-3">Produto <span class="text-red-500">*</span></label>
                        <Dropdown :id="`product-${index}`" v-model="item.productId" :options="productStore.productOptions" optionLabel="label" optionValue="value" :invalid="submitted && !item.productId" placeholder="Selecione um produto" fluid />
                    </div>

                    <div>
                        <label :for="`warehouse-${index}`" class="font-bold mb-3">Armazém <span class="text-red-500">*</span></label>
                        <Dropdown
                            :id="`warehouse-${index}`"
                            v-model="item.warehouseId"
                            :options="warehouseStore.warehouseOptions"
                            optionLabel="label"
                            optionValue="value"
                            :invalid="submitted && !item.warehouseId"
                            placeholder="Selecione um armazém"
                            fluid
                        />
                    </div>

                    <div>
                        <label :for="`quantity-${index}`" class="font-bold mb-3">Quantidade <span class="text-red-500">*</span></label>
                        <InputNumber :id="`quantity-${index}`" v-model="item.quantity" :min="1" :invalid="submitted && (!item.quantity || item.quantity <= 0)" placeholder="Quantidade" fluid />
                    </div>

                    <div>
                        <Button icon="pi pi-trash" severity="danger" outlined @click="removeItem(index)" :disabled="purchaseStore.purchaseItems.length === 1" />
                    </div>
                </div>

                <small v-if="submitted && purchaseStore.purchaseItems.length === 0" class="text-red-500">Adicione pelo menos um item à compra.</small>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-start-3">
                    <label for="totalAmount" class="font-bold mb-3">Valor Total <span class="text-red-500">*</span></label>
                    <InputNumber
                        id="totalAmount"
                        v-model="purchaseStore.purchase.totalAmount"
                        :minFractionDigits="2"
                        :maxFractionDigits="2"
                        mode="currency"
                        currency="BRL"
                        locale="pt-BR"
                        :invalid="submitted && (!purchaseStore.purchase.totalAmount || purchaseStore.purchase.totalAmount <= 0)"
                        placeholder="0,00"
                        fluid
                    />
                    <small v-if="submitted && (!purchaseStore.purchase.totalAmount || purchaseStore.purchase.totalAmount <= 0)" class="text-red-500">Valor total deve ser maior que zero.</small>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="savePurchase" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deletePurchaseDialog" modal header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="purchaseStore.purchase"
                    >Tem certeza de que deseja excluir a compra <b>{{ purchaseStore.purchase.invoiceNumber }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deletePurchaseDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deletePurchase" />
            </template>
        </Dialog>
    </div>
</template>
