import api from '@/config/api';
import type { ProductType, ProductTypePayload, ProductTypeResponse } from '@/types/productType';
import type { BaseFilters } from '@/types/global/filters';

export class ProductTypeService {
    private static readonly BASE_URL = '/product-types';

    private static preparePayload(productTypeData: ProductTypePayload | Partial<ProductTypePayload>): ProductTypePayload | Partial<ProductTypePayload> {
        return productTypeData;
    }

    private static getHeaders(): Record<string, string> {
        return {
            'Content-Type': 'application/json'
        };
    }

    static async getProductTypes(filters: Partial<BaseFilters> = {}): Promise<ProductTypeResponse> {
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.orderByColumn) params.append('order_by_column', filters.orderByColumn);
        if (filters.orderByDirection) params.append('order_by_direction', filters.orderByDirection);
        if (filters.perPage) params.append('per_page', filters.perPage.toString());
        if (filters.page) params.append('page', filters.page.toString());
        if (filters.paginate !== undefined) params.append('paginate', filters.paginate ? '1' : '0');

        const queryString = params.toString();
        const url = queryString ? `${this.BASE_URL}?${queryString}` : this.BASE_URL;

        const response = await api.get<ProductTypeResponse>(url);
        return response.data;
    }

    static async getProductType(id: number): Promise<{ data: ProductType; message: string }> {
        const response = await api.get<{ data: ProductType; message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }

    static async createProductType(productTypeData: ProductTypePayload): Promise<{ data: ProductType; message: string }> {
        const payload = this.preparePayload(productTypeData);
        const response = await api.post<{ data: ProductType; message: string }>(this.BASE_URL, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async updateProductType(id: number, productTypeData: Partial<ProductTypePayload>): Promise<{ data: ProductType; message: string }> {
        const payload = this.preparePayload(productTypeData);
        const response = await api.put<{ data: ProductType; message: string }>(`${this.BASE_URL}/${id}`, payload, {
            headers: this.getHeaders()
        });
        return response.data;
    }

    static async deleteProductType(id: number): Promise<{ message: string }> {
        const response = await api.delete<{ message: string }>(`${this.BASE_URL}/${id}`);
        return response.data;
    }
}
