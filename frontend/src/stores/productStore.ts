import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { Product, ProductPayload, SpecificationSync, ProductSales } from '@/types/product';
import type { BaseFilters } from '@/types/global/filters';
import { ProductService } from '@/services/ProductService';
import { handleApiError } from '@/utils/errorHandler';

export const useProductStore = defineStore('product', () => {
    const loading = ref(false);
    const error = ref<string | null>(null);
    const productSpecifications = ref<SpecificationSync[]>([]);
    const products = ref<Product[]>([]);
    const productsSales = ref<ProductSales[]>([]);
    const product = ref<Product>({
        id: 0,
        name: '',
        description: '',
        price_sale: 0,
        product_type_id: 0,
        mark_id: 0
    });
    const pagination = ref({
        currentPage: 1,
        total: 0,
        perPage: 10,
        lastPage: 1,
        paginate: true
    });

    const productOptions = computed(() =>
        products.value.map((product) => ({
            label: product.name,
            value: product.id
        }))
    );

    const productSalesOptions = computed(() =>
        productsSales.value.map((product) => ({
            label: `${product.name} (Estoque: ${product.total_in_stock})`,
            value: product.id
        }))
    );

    async function fetchProducts(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await ProductService.getProducts(filters);
            products.value = response.data;
            pagination.value = {
                currentPage: response.meta.currentPage,
                total: response.meta.total,
                perPage: response.meta.perPage,
                lastPage: response.meta.lastPage,
                paginate: true
            };
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar produtos');
        } finally {
            loading.value = false;
        }
    }

    async function fetchProductsForSales(filters?: BaseFilters) {
        loading.value = true;
        error.value = null;
        try {
            const response = await ProductService.getProductsForSales(filters);
            productsSales.value = response.data;
            return response;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar produtos para venda');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function createProduct(payload: ProductPayload & { specifications?: SpecificationSync[] }) {
        loading.value = true;
        error.value = null;
        try {
            const result = await ProductService.createProduct(payload);
            products.value.push(result.data);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao criar produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function updateProduct(id: number, payload: ProductPayload & { specifications?: SpecificationSync[] }) {
        loading.value = true;
        error.value = null;
        try {
            const result = await ProductService.updateProduct(id, payload);
            const index = products.value.findIndex((product) => product.id === id);
            if (index !== -1) {
                products.value[index] = result.data;
            }
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao atualizar produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function deleteProduct(id: number) {
        loading.value = true;
        error.value = null;
        try {
            await ProductService.deleteProduct(id);
            products.value = products.value.filter((product) => product.id !== id);
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao deletar produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    async function getProductWithSpecifications(id: number) {
        loading.value = true;
        error.value = null;
        try {
            const result = await ProductService.getProduct(id);
            product.value = result.data;

            if (result.data.specifications) {
                productSpecifications.value = result.data.specifications.map((spec) => ({
                    specification_id: spec.id,
                    value: spec.pivot.value
                }));
            } else {
                productSpecifications.value = [];
            }

            return result.data;
        } catch (err) {
            error.value = handleApiError(err, 'Erro ao carregar produto');
            throw err;
        } finally {
            loading.value = false;
        }
    }

    function clearProduct() {
        product.value = {
            id: 0,
            name: '',
            description: '',
            price_sale: 0,
            product_type_id: 0,
            mark_id: 0
        };
        productSpecifications.value = [];
    }

    function addSpecification(specificationId: number, value: string = '') {
        const exists = productSpecifications.value.find((spec) => spec.specification_id === specificationId);
        if (!exists) {
            productSpecifications.value.push({
                specification_id: specificationId,
                value
            });
        }
    }

    function removeSpecification(specificationId: number) {
        productSpecifications.value = productSpecifications.value.filter((spec) => spec.specification_id !== specificationId);
    }

    function updateSpecificationValue(specificationId: number, value: string) {
        const spec = productSpecifications.value.find((spec) => spec.specification_id === specificationId);
        if (spec) {
            spec.value = value;
        }
    }

    return {
        products,
        productsSales,
        product,
        loading,
        error,
        pagination,
        productOptions,
        productSalesOptions,
        productSpecifications,
        fetchProducts,
        fetchProductsForSales,
        createProduct,
        updateProduct,
        deleteProduct,
        getProductWithSpecifications,
        clearProduct,
        addSpecification,
        removeSpecification,
        updateSpecificationValue
    };
});
