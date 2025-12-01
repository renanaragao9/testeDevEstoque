<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import { useProductStore } from '@/stores/productStore';
import { useProductTypeStore } from '@/stores/productTypeStore';
import { useMarkStore } from '@/stores/markStore';
import { useSpecificationStore } from '@/stores/specificationStore';
import type { Product } from '@/types/product';
import { useToast } from 'primevue/usetoast';
import type { DataTableSortEvent } from 'primevue/datatable';

const productStore = useProductStore();
const productTypeStore = useProductTypeStore();
const markStore = useMarkStore();
const specificationStore = useSpecificationStore();

const searchTerm = ref('');
const currentPage = ref(1);
const rowsPerPage = ref(10);
const orderByColumn = ref('name');
const orderByDirection = ref<'asc' | 'desc'>('asc');
const productDialog = ref(false);
const deleteProductDialog = ref(false);
const submitted = ref(false);

const toast = useToast();

const productTypeOptions = computed(() => productTypeStore.productTypeOptions);
const markOptions = computed(() => markStore.markOptions);
const specificationOptions = computed(() => specificationStore.specificationOptions);

onMounted(async () => {
    try {
        await Promise.all([loadProducts(), loadProductTypes(), loadMarks(), loadSpecifications()]);
    } catch {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao carregar dados da página',
            life: 5000
        });
    }
});

async function loadProducts(): Promise<void> {
    const filters = {
        search: searchTerm.value || undefined,
        orderByColumn: orderByColumn.value,
        orderByDirection: orderByDirection.value,
        perPage: rowsPerPage.value,
        page: currentPage.value,
        paginate: productStore.pagination.paginate
    };

    await productStore.fetchProducts(filters);
}

async function loadProductTypes(): Promise<void> {
    await productTypeStore.fetchProductTypes({ paginate: false });
}

async function loadMarks(): Promise<void> {
    await markStore.fetchMarks({ paginate: false });
}

async function loadSpecifications(): Promise<void> {
    await specificationStore.fetchSpecifications({ paginate: false });
}

async function onSearch(): Promise<void> {
    currentPage.value = 1;
    await loadProducts();
}

async function onPageChange(event: { page: number; first: number; rows: number }): Promise<void> {
    currentPage.value = event.page + 1;
    if (event.rows !== rowsPerPage.value) {
        rowsPerPage.value = event.rows;
        currentPage.value = 1;
    }
    await loadProducts();
}

async function onSort(event: DataTableSortEvent): Promise<void> {
    if (typeof event.sortField === 'string') {
        orderByColumn.value = event.sortField || 'name';
        orderByDirection.value = event.sortOrder === 1 ? 'asc' : 'desc';
        currentPage.value = 1;
        await loadProducts();
    }
}

async function saveProduct(): Promise<void> {
    submitted.value = true;

    if (productStore.product.name?.trim() && productStore.product.price_sale > 0 && productStore.product.product_type_id > 0 && productStore.product.mark_id > 0) {
        try {
            const payload = {
                name: productStore.product.name,
                description: productStore.product.description || '',
                price_sale: productStore.product.price_sale,
                product_type_id: productStore.product.product_type_id,
                mark_id: productStore.product.mark_id,
                specifications: productStore.productSpecifications.filter((spec) => spec.value.trim() !== '')
            };

            if (productStore.product.id) {
                await productStore.updateProduct(productStore.product.id, payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produto atualizado com sucesso', life: 3000 });
            } else {
                await productStore.createProduct(payload);
                toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produto criado com sucesso', life: 3000 });
            }

            productDialog.value = false;
            productStore.clearProduct();
            await loadProducts();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: productStore.error || 'Erro ao salvar produto', life: 3000 });
        }
    }
}

async function deleteProduct(): Promise<void> {
    if (productStore.product.id) {
        try {
            await productStore.deleteProduct(productStore.product.id);
            deleteProductDialog.value = false;
            productStore.clearProduct();
            toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produto deletado com sucesso', life: 3000 });
            await loadProducts();
        } catch {
            toast.add({ severity: 'error', summary: 'Erro', detail: productStore.error || 'Erro ao deletar produto', life: 3000 });
        }
    }
}

function openNew(): void {
    productStore.clearProduct();
    submitted.value = false;
    productDialog.value = true;
}

function hideDialog(): void {
    productDialog.value = false;
    submitted.value = false;
}

async function editProduct(productData: Product): Promise<void> {
    await productStore.getProductWithSpecifications(productData.id);
    productDialog.value = true;
}

function confirmDeleteProduct(productData: Product): void {
    productStore.product = productData;
    deleteProductDialog.value = true;
}

function formatPrice(price: number): string {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(price);
}

function addSpecification(): void {
    if (selectedSpecification.value) {
        productStore.addSpecification(selectedSpecification.value.value, '');
        selectedSpecification.value = null;
    }
}

function removeSpecification(specificationId: number): void {
    productStore.removeSpecification(specificationId);
}

function getSpecificationName(specificationId: number): string {
    const spec = specificationStore.specifications.find((s) => s.id === specificationId);
    return spec?.name || '';
}

const selectedSpecification = ref<{ label: string; value: number } | null>(null);

const availableSpecifications = computed(() => {
    const usedIds = productStore.productSpecifications.map((spec) => spec.specification_id);
    return specificationOptions.value.filter((option) => !usedIds.includes(option.value));
});
</script>

<template>
    <div class="space-y-6 lg:space-y-8">
        <div class="lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 -mx-4 sm:mx-0">
                <div class="card">
                    <Toolbar class="mb-6">
                        <template #start>
                            <Button label="Adicionar Produto" icon="pi pi-plus" severity="secondary" class="mr-2" @click="openNew" />
                        </template>
                    </Toolbar>

                    <DataTable
                        :value="productStore.products"
                        dataKey="id"
                        :paginator="true"
                        :rows="rowsPerPage"
                        :totalRecords="productStore.pagination.total"
                        :loading="productStore.loading"
                        :first="(currentPage - 1) * rowsPerPage"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        :rowsPerPageOptions="[5, 10, 25, 50]"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} produtos"
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
                                <span class="text-xl text-900 font-bold">Produtos</span>
                                <div class="flex gap-2">
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="searchTerm" placeholder="Buscar produto..." @keyup.enter="onSearch" />
                                    </IconField>
                                    <Button icon="pi pi-search" severity="secondary" @click="onSearch" :loading="productStore.loading" />
                                </div>
                            </div>
                        </template>

                        <Column field="name" header="Nome" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="price_sale" header="Preço" sortable>
                            <template #body="slotProps">
                                {{ formatPrice(slotProps.data.price_sale) }}
                            </template>
                        </Column>
                        <Column field="productType.name" header="Tipo" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.productType?.name || '-' }}
                            </template>
                        </Column>
                        <Column field="mark.name" header="Marca" sortable>
                            <template #body="slotProps">
                                {{ slotProps.data.mark?.name || '-' }}
                            </template>
                        </Column>

                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editProduct(slotProps.data)" />
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteProduct(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="productDialog" modal header="Detalhes do Produto" :style="{ width: '70vw' }">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="font-bold mb-3">Nome <span class="text-red-500">*</span></label>
                    <InputText id="name" v-model.trim="productStore.product.name" required autofocus :invalid="submitted && !productStore.product.name" placeholder="Digite o nome do produto" fluid />
                    <small v-if="submitted && !productStore.product.name" class="text-red-500">Nome é obrigatório.</small>
                </div>

                <div>
                    <label for="price_sale" class="font-bold mb-3">Preço de Venda <span class="text-red-500">*</span></label>
                    <InputNumber id="price_sale" v-model="productStore.product.price_sale" mode="currency" currency="BRL" locale="pt-BR" :invalid="submitted && productStore.product.price_sale <= 0" placeholder="0,00" fluid />
                    <small v-if="submitted && productStore.product.price_sale <= 0" class="text-red-500">Preço deve ser maior que zero.</small>
                </div>

                <div>
                    <label for="product_type" class="font-bold mb-3">Tipo de Produto <span class="text-red-500">*</span></label>
                    <Dropdown
                        id="product_type"
                        v-model="productStore.product.product_type_id"
                        :options="productTypeOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Selecione um tipo"
                        :invalid="submitted && productStore.product.product_type_id <= 0"
                        fluid
                    />
                    <small v-if="submitted && productStore.product.product_type_id <= 0" class="text-red-500">Tipo de produto é obrigatório.</small>
                </div>

                <div>
                    <label for="mark" class="font-bold mb-3">Marca <span class="text-red-500">*</span></label>
                    <Dropdown id="mark" v-model="productStore.product.mark_id" :options="markOptions" optionLabel="label" optionValue="value" placeholder="Selecione uma marca" :invalid="submitted && productStore.product.mark_id <= 0" fluid />
                    <small v-if="submitted && productStore.product.mark_id <= 0" class="text-red-500">Marca é obrigatória.</small>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="font-bold mb-3">Descrição</label>
                    <Textarea id="description" v-model="productStore.product.description" placeholder="Digite a descrição do produto" fluid />
                </div>

                <!-- Especificações -->
                <div class="md:col-span-2">
                    <div class="flex justify-between items-center mb-3">
                        <label class="font-bold">Especificações</label>
                        <div class="flex gap-2">
                            <Dropdown v-model="selectedSpecification" :options="availableSpecifications" optionLabel="label" placeholder="Adicionar especificação" class="w-48" />
                            <Button icon="pi pi-plus" @click="addSpecification" :disabled="!selectedSpecification" />
                        </div>
                    </div>

                    <div v-if="productStore.productSpecifications.length > 0" class="space-y-2">
                        <div v-for="spec in productStore.productSpecifications" :key="spec.specification_id" class="flex items-center gap-2">
                            <div class="flex-1">
                                <label class="text-sm font-medium">{{ getSpecificationName(spec.specification_id) }}</label>
                                <InputText v-model="spec.value" placeholder="Digite o valor da especificação" fluid />
                            </div>
                            <Button icon="pi pi-trash" severity="danger" outlined @click="removeSpecification(spec.specification_id)" />
                        </div>
                    </div>

                    <div v-else class="text-center text-gray-500 py-4 border-2 border-dashed border-gray-300 rounded">Nenhuma especificação adicionada</div>
                </div>
            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Salvar" icon="pi pi-check" @click="saveProduct" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteProductDialog" modal header="Confirmar Exclusão" :style="{ width: '450px' }">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle text-red-500 mr-3" style="font-size: 2rem" />
                <span v-if="productStore.product"
                    >Tem certeza de que deseja excluir <b>{{ productStore.product.name }}</b
                    >?</span
                >
            </div>

            <template #footer>
                <Button label="Não" icon="pi pi-times" text @click="deleteProductDialog = false" />
                <Button label="Sim" icon="pi pi-check" text @click="deleteProduct" />
            </template>
        </Dialog>
    </div>
</template>
