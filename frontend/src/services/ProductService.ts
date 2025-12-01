import api from '@/config/api';
import type { Product, ProductPayload, ProductResponse } from '@/types/product';
import type { BaseFilters } from '@/types/global/filters';

export class ProductService {
    private static readonly BASE_URL = '/products';

    private static preparePayload(productData: ProductPayload | Partial<ProductPayload>): ProductPayload | Partial<ProductPayload> {
        return productData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getProducts(filters: Partial<BaseFilters> = {}): Promise<ProductResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<ProductResponse>(url);
        return response.data;
    }

    static async getProduct(id: number): Promise<{ data: Product; message: string }> {
        const response = await api.get<{ data: Product; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createProduct(productData: ProductPayload): Promise<{ data: Product; message: string }> {
        const payload = this.preparePayload(productData);
        const response = await api.post<{ data: Product; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateProduct(id: number, productData: Partial<ProductPayload>): Promise<{ data: Product; message: string }> {
        const payload = this.preparePayload(productData);
        const response = await api.put<{ data: Product; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteProduct(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
